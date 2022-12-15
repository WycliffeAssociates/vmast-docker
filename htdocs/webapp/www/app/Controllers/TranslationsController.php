<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Data\Resource\ResourceChunkType;
use App\Domain\EventContributors;
use App\Domain\ProjectContributors;
use App\Models\ApiModel;
use App\Models\TranslationsModel;
use App\Models\EventsModel;
use App\Repositories\Cloud\ICloudRepository;
use App\Repositories\Event\IEventRepository;
use App\Repositories\Member\IMemberRepository;
use App\Repositories\Project\IProjectRepository;
use Helpers\Constants\EventMembers;
use Helpers\Constants\EventStates;
use Helpers\Constants\OdbSections;
use Helpers\Constants\RadioSections;
use Helpers\Manifest\Normal\Project;
use Helpers\ProjectFile;
use Helpers\Spyc;
use Helpers\Tools;
use Shared\Legacy\Error;
use View;
use Config\Config;
use Helpers\Session;
use Helpers\Url;
use Helpers\Parsedown;
use File;

class TranslationsController extends Controller
{
    private $_model;
    private $_eventModel;
    private $_apiModel;

    private $eventsRepo;
    private $projectsRepo;
    private $memberRepo;
    private $cloudRepo;

    public function __construct(
        IProjectRepository $projectsRepo,
        IEventRepository $eventsRepo,
        IMemberRepository $memberRepo,
        ICloudRepository $cloudRepo
    ) {
        parent::__construct();

        $this->projectsRepo = $projectsRepo;
        $this->eventsRepo = $eventsRepo;
        $this->memberRepo = $memberRepo;
        $this->cloudRepo = $cloudRepo;

        if(Config::get("app.isMaintenance")
            && !in_array($_SERVER['REMOTE_ADDR'], Config::get("app.ips"))) {
            Url::redirect("maintenance");
        }

        $member = $this->memberRepo->get(Session::get("memberID"));

        if (!$member) Url::redirect('members/login');
        if(!$member->verified) Url::redirect("members/error/verification");
        if(!$member->profile->exists()) Url::redirect("members/profile");

        $this->_model = new TranslationsModel();
        $this->_eventModel = new EventsModel($this->eventsRepo);
        $this->_apiModel = new ApiModel();
    }

    public function languages() {
        $data['menu'] = 3;

        $data['title'] = __('choose_language');
        $data["languages"] = $this->_model->getTranslationLanguages();

        return View::make('Translations/Languages')
            ->shares("title", __("translations"))
            ->shares("data", $data);
    }

    public function resources($lang) {
        $data['menu'] = 3;

        $data['title'] = __('choose_book');
        $data['bookProjects'] = $this->_model->getTranslationProjects($lang);
        $data['language'] = $this->_model->getLanguageInfo($lang);

        return View::make('Translations/Resources')
            ->shares("title", __("translations"))
            ->shares("data", $data);
    }

    public function books($lang, $bookProject, $sourceBible) {
        $data['menu'] = 3;

        $data['title'] = __('choose_book');
        $data['books'] = $this->_model->getTranslationBooks($lang, $bookProject, $sourceBible);
        $data['language'] = $this->_model->getLanguageInfo($lang);
        $data['project'] = ["bookProject" => $bookProject, "sourceBible" => $sourceBible];
        $data["mode"] = "bible";

        if(sizeof($data['books']) > 0)
        {
            $data["mode"] = $data['books'][0]->bookProject;
        }

        return View::make('Translations/Books')
            ->shares("title", __("translations"))
            ->shares("data", $data);
    }

    public function book($lang, $bookProject, $sourceBible, $bookCode) {
        $data['menu'] = 3;

        $book = $this->_model->getTranslation($lang, $bookProject, $sourceBible, $bookCode);
        $data['language'] = $this->_model->getLanguageInfo($lang);
        $data['project'] = ["bookProject" => $bookProject, "sourceBible" => $sourceBible];
        $data['bookInfo'] = $this->_model->getBookInfo($bookCode);
        $data['book'] = "";

        $parsedown = new Parsedown();

        if(!empty($book)) {
            $data["data"] = $book[0];
            $data['title'] = $data['data']->bookName;
            $data["mode"] = "bible";

            $sourceBible = $data['data']->sourceBible;
            $bookProject = $data['data']->bookProject;
            $eventID = $data['data']->eventID;

            $odbBook = [];
            $radioBook = [];

            $mappedBook = [];
            foreach ($book as $chunk) {
                $mappedBook[$chunk->chapter][] = $chunk;
            }

            if ($bookProject == "bca") {
                $this->sortBcaBook($mappedBook);
            } elseif ($bookProject == "tw") {
                $this->sortTwBook($mappedBook);
            }

            foreach ($mappedBook as $chap) {
                $chapterNumber = $chap[0]->chapter;
                $chapters = $this->_eventModel->getChapters($eventID, null, $chapterNumber);
                $chapter = $chapters[0];

                if($sourceBible == "odb") {
                    $odbBook[$chapterNumber] = [];
                } elseif ($sourceBible == "rad") {
                    $radioBook[$chapterNumber] = [];
                } else {
                    if(in_array($bookProject, ["tn","tq","tw","obs","bc","bca"])) {
                        $level = " - ".($chapter["l3checked"] ? "L3" : ($chapter["checked"] ? "L2" : "L1"));
                    } else {
                        $level = " - ".($chapter["l3checked"] ? "L3" : ($chapter["l2checked"] ? "L2" : "L1"));
                    }

                    $data['book'] .= !in_array($bookProject, ["tw","bca"]) ? ($chapterNumber > 0
                        ? '<h2 class="chapter_title">'.__("chapter", [$chapterNumber]).$level.'</h2>'
                        : '<h2 class="chapter_title">'.__("front").$level.'</h2>') : "";
                }

                foreach ($chap as $chunk) {
                    $verses = json_decode($chunk->translatedVerses);
                    if($verses == null) continue;

                    // Start of chunk
                    $data['book'] .= '<div>';

                    if(in_array($bookProject, ["tn","tq","tw","obs","bc","bca"])) {
                        $chunks = (array)json_decode($chapter["chunks"], true);
                        $currChunk = $chunks[$chunk->chunk] ?? [$chunk->chunk];

                        $versesLabel = "";
                        if($bookProject != "tw") {
                            if($currChunk[0] != $currChunk[sizeof($currChunk)-1])
                                $versesLabel = __("chunk_verses", $currChunk[0] . "-" . $currChunk[sizeof($currChunk)-1]);
                            else
                                if($currChunk[0] == 0)
                                    $versesLabel = __("intro");
                                else
                                    $versesLabel = __("chunk_verses", $currChunk[0]);
                        }

                        $data["mode"] = $bookProject;

                        if (in_array($bookProject, ["obs","bc","bca"])) {
                            if(!empty($verses->{EventMembers::L3_CHECKER}->verses)) {
                                $resourceChunk = $verses->{EventMembers::L3_CHECKER}->verses;
                            } elseif (!empty($verses->{EventMembers::CHECKER}->verses)) {
                                $resourceChunk = $verses->{EventMembers::CHECKER}->verses;
                            } else {
                                $resourceChunk = $verses->{EventMembers::TRANSLATOR}->verses;
                            }

                            switch ($resourceChunk->type) {
                                case ResourceChunkType::IMAGE:
                                    if ($resourceChunk->text) {
                                        $data['book'] .= '<img src="' . $resourceChunk->meta . '" />';
                                        $data['book'] .= '<div class="resource_chunk">' . $resourceChunk->text . '</div>';
                                    } else {
                                        $data['book'] .= $parsedown->parse($resourceChunk->meta);
                                    }
                                    break;
                                case ResourceChunkType::LINK:
                                    $data['book'] .= '<div class="resource_chunk">' . $resourceChunk->text . '</div>';
                                    break;
                                default:
                                    if (!empty($resourceChunk->meta)) {
                                        $content = str_replace("{}", $resourceChunk->text, $resourceChunk->meta);
                                        $data['book'] .= '<div class="resource_chunk">' . $parsedown->parse($content) . '</div>';
                                    } else {
                                        $data['book'] .= '<div class="resource_chunk">' . $resourceChunk->text . '</div>';
                                    }
                                    break;
                            }
                        } else {
                            if(!empty($verses->{EventMembers::L3_CHECKER}->verses))
                            {
                                $text = $parsedown->text($verses->{EventMembers::L3_CHECKER}->verses);
                            }
                            elseif (!empty($verses->{EventMembers::CHECKER}->verses))
                            {
                                $text = $parsedown->text($verses->{EventMembers::CHECKER}->verses);
                            }
                            else
                            {
                                $text = $parsedown->text($verses->{EventMembers::TRANSLATOR}->verses);
                            }

                            $data['book'] .= '<br/><strong class="note_chunk_verses">'.$versesLabel.'</strong> '.$text." ";
                        }
                    } else {
                        if(!empty($verses->{EventMembers::L3_CHECKER}->verses)) {
                            foreach ($verses->{EventMembers::L3_CHECKER}->verses as $verse => $text) {
                                // Footnotes
                                $replacement = " <span data-toggle=\"tooltip\" data-placement=\"auto auto\" title=\"$2\" class=\"booknote mdi mdi-bookmark\"></span> ";
                                $text = preg_replace("/\\\\f[+\s]+(.*)\\\\ft[+\s]+(.*)\\\\f\\*/Uui", $replacement, $text);
                                $text = preg_replace("/\\\\[a-z0-9-]+\\s?\\\\?\\*?/", "", $text);
                                $data['book'] .= '<strong><sup>'.$verse.'</sup></strong> '.$text." ";
                            }
                        } elseif (!empty($verses->{EventMembers::L2_CHECKER}->verses)) {
                            foreach ($verses->{EventMembers::L2_CHECKER}->verses as $verse => $text) {
                                // Footnotes
                                $replacement = " <span data-toggle=\"tooltip\" data-placement=\"auto auto\" title=\"$2\" class=\"booknote mdi mdi-bookmark\"></span> ";
                                $text = preg_replace("/\\\\f[+\s]+(.*)\\\\ft[+\s]+(.*)\\\\f\\*/Uui", $replacement, $text);
                                $text = preg_replace("/\\\\[a-z0-9-]+\\s?\\\\?\\*?/", "", $text);
                                $data['book'] .= '<strong><sup>'.$verse.'</sup></strong> '.$text." ";
                            }
                        } else {
                            if($chunk->bookProject == "rad") {
                                $translation = isset($verses->{EventMembers::CHECKER}->verses)
                                && !empty($verses->{EventMembers::CHECKER}->verses)
                                    ? $verses->{EventMembers::CHECKER}->verses
                                    : $verses->{EventMembers::TRANSLATOR}->verses;

                                if(!is_object($translation)) {
                                    $ind = $chunk->chunk == 0 ? RadioSections::ENTRY : RadioSections::TITLE;
                                    $radioBook[$chapterNumber][RadioSections::enum($ind)] = $translation;
                                } else {
                                    $tmp = [];
                                    $tmp["name"] = $translation->name;
                                    $tmp["text"] = $translation->text;
                                    $radioBook[$chapterNumber][RadioSections::enum(RadioSections::SPEAKERS)][] = $tmp;
                                }
                            } else {
                                foreach ($verses->{EventMembers::TRANSLATOR}->verses as $verse => $text) {
                                    if($sourceBible == "odb") {
                                        if($verse >= OdbSections::CONTENT) {
                                            $odbBook[$chapterNumber][OdbSections::enum($verse)][] = $text;
                                        } else {
                                            $odbBook[$chapterNumber][OdbSections::enum($verse)] = $text;
                                        }
                                    } else {
                                        // Footnotes
                                        $replacement = " <span data-toggle=\"tooltip\" data-placement=\"auto auto\" title=\"$2\" class=\"booknote mdi mdi-bookmark\"></span> ";
                                        $text = preg_replace("/\\\\f[+\s]+(.*)\\\\ft[+\s]+(.*)\\\\f\\*/Uui", $replacement, $text);
                                        $text = preg_replace("/\\\\[a-z0-9-]+\\s?\\\\?\\*?/", "", $text);
                                        $data['book'] .= '<strong><sup>'.$verse.'</sup></strong> '.$text." ";
                                    }
                                }
                            }
                        }
                    }

                    // End of chunk
                    $data['book'] .= '</div>';
                }
            }

            // Render ODB book
            if(!empty($odbBook)) {
                foreach ($odbBook as $chapter => $topic) {
                    $data["book"] .= '<h2 class="chapter_title">'.__("devotion_number", ["devotion" => $chapter]).'</h2>';

                    if(trim($topic[OdbSections::enum(OdbSections::TITLE)]) != "")
                        $data["book"] .= '<p class="odb_section">'.$topic[OdbSections::enum(OdbSections::TITLE)].'</p>';

                    if(trim($topic[OdbSections::enum(OdbSections::PASSAGE)]) != "")
                        $data["book"] .= '<p class="odb_section">'.$topic[OdbSections::enum(OdbSections::PASSAGE)].'</p>';

                    if(trim($topic[OdbSections::enum(OdbSections::PASSAGE)]) != "")
                        $data["book"] .= '<p class="odb_section">'.$topic[OdbSections::enum(OdbSections::VERSE)].'</p>';

                    foreach ($topic[OdbSections::enum(OdbSections::CONTENT)] as $key => $p) {
                        if(trim($p) != "")
                            $data["book"] .= '<p '.($key == 0 ? 'class="odb_section"' : '').'>'.$p.'</p>';
                    }

                    if(trim($topic[OdbSections::enum(OdbSections::AUTHOR)]) != "")
                        $data["book"] .= '<p class="odb_section">'.$topic[OdbSections::enum(OdbSections::AUTHOR)].'</p>';

                    if(trim($topic[OdbSections::enum(OdbSections::BIBLE_IN_A_YEAR)]) != "")
                        $data["book"] .= '<p class="odb_section">'.$topic[OdbSections::enum(OdbSections::BIBLE_IN_A_YEAR)].'</p>';

                    if(trim($topic[OdbSections::enum(OdbSections::THOUGHT)]) != "")
                        $data["book"] .= '<p class="odb_section">'.$topic[OdbSections::enum(OdbSections::THOUGHT)].'</p>';
                }
            } elseif (!empty($radioBook)) {
                // Render Radio book
                foreach ($radioBook as $topic) {
                    if(trim($topic[RadioSections::enum(RadioSections::ENTRY)]) != "")
                        $data["book"] .= '<h2 class="chapter_title">'.$topic[RadioSections::enum(RadioSections::ENTRY)].'</h2>';

                    if(trim($topic[RadioSections::enum(RadioSections::TITLE)]) != "")
                        $data["book"] .= '<p class="radio_section">'.$topic[RadioSections::enum(RadioSections::TITLE)].'</p>';

                    foreach ($topic[RadioSections::enum(RadioSections::SPEAKERS)] as $p) {
                        $data["book"] .= '<div class="radio_section">';
                        foreach ($p as $key => $item) {
                            if(trim($item) != "")
                                if($key == "name")
                                    $data["book"] .= '<p><strong>'.$item.'</strong></p>';
                                else
                                    $data["book"] .= '<p>'.$item.'</p>';
                        }
                        $data["book"] .= "<div>";
                    }
                }
            }
        }

        return View::make('Translations/Book')
            ->shares("title", __("translations"))
            ->shares("data", $data);
    }

    public function downloadUsfm($lang, $bookProject, $sourceBible, $bookCode)
    {
        if($lang != null && $bookProject != null && $sourceBible != null && $bookCode != null)
        {
            $books = $this->_model->getTranslation($lang, $bookProject, $sourceBible, $bookCode);

            if(!empty($books) && isset($books[0]))
            {
                $projectFiles = $this->getUsfmProjectFiles($books);
                $filename = $books[0]->targetLang . "_" . $bookProject . ($bookCode ? "_".$bookCode : "") . ".zip";
                $this->_model->generateZip($filename, $projectFiles, true);
            }
            else
            {
                echo "There is no such book translation.";
            }
        }
    }

    public function downloadTs($lang, $bookProject, $sourceBible, $bookCode)
    {
        if($lang != null && $bookProject != null && $sourceBible != null && $bookCode != null)
        {
            if($bookCode == "dl")
            {
                echo "Not Implemented!";
                exit;
            }

            $book = $this->_model->getTranslation($lang, $bookProject, $sourceBible, $bookCode);

            if(!empty($book) && isset($book[0]))
            {
                $root = $book[0]->targetLang."_".$book[0]->bookCode."_text_".$book[0]->bookProject;
                $projectFiles = $this->getTsProjectFiles($book);
                $this->_model->generateZip($root . ".tstudio", $projectFiles, true);
            }
            else
            {
                echo "There is no such book translation.";
            }
        }
    }

    public function downloadJson($lang, $bookProject, $sourceBible, $bookCode)
    {
        if($lang != null && $bookProject != null && $sourceBible != null && $bookCode != null)
        {
            $books = $this->_model->getTranslation($lang, $bookProject, $sourceBible, $bookCode);

            if(!empty($books) && isset($books[0]))
            {
                $projectFiles = $this->getJsonProjectFiles($books);
                $filename = $books[0]->targetLang . "_" . $bookProject . ($bookCode ? "_".$bookCode : "") . ".zip";
                $this->_model->generateZip($filename, $projectFiles, true);
            }
            else
            {
                echo "There is no such book translation.";
            }
        }
    }

    public function downloadMd($lang, $bookProject, $sourceBible, $bookCode)
    {
        if($lang != null && $bookProject != null && $sourceBible != null && $bookCode != null)
        {
            $books = $this->_model->getTranslation($lang, $bookProject, $sourceBible, $bookCode);

            if(!empty($books) && isset($books[0]))
            {
                $projectFiles = $this->getMdProjectFiles($books);
                $filename = $books[0]->targetLang."_" . $bookProject . "_".$books[0]->bookCode . ".zip";
                $this->_model->generateZip($filename, $projectFiles, true);
            }
        }

        echo "An error occurred! Contact administrator.";
    }

    public function downloadMdTw($lang, $sourceBible, $bookCode)
    {
        if($lang != null && $sourceBible != null && $bookCode != null)
        {
            $books = $this->_model->getTranslation($lang, "tw", $sourceBible, $bookCode);

            if(!empty($books) && isset($books[0]))
            {
                $projectFiles = $this->getMdTwProjectFiles($books);
                $filename = $lang . "_tw_" . $books[0]->bookName. ".zip";
                $this->_model->generateZip($filename, $projectFiles, true);
            }
        }

        echo "An error occurred! Contact administrator.";
    }

    public function downloadResource($lang, $resource, $sourceBible, $bookCode)
    {
        if($lang != null)
        {
            $books = $this->_model->getTranslation($lang, $resource, $sourceBible, $bookCode);

            if(!empty($books) && isset($books[0]))
            {
                $projectFiles = $this->getResourceProjectFiles($books);
                switch ($resource) {
                    case "bc":
                        $resourceName = "${bookCode}_bc";
                        break;
                    case "bca":
                        $resourceName = "bc";
                        break;
                    default:
                        $resourceName = $resource;
                }

                $filename = "${lang}_$resourceName.zip";
                $this->_model->generateZip($filename, $projectFiles, true);
            }
        }

        echo "An error occurred! Contact administrator.";
    }

    public function export($lang, $bookProject, $sourceBible, $bookCode, $server)
    {
        $response = ["success" => false];

        // Check if user is logged in to the server
        $this->cloudRepo->initialize($server);

        if($this->cloudRepo->isAuthenticated()) {
            $repoName = null;
            $projectFiles = [];

            if(Tools::isHelp($bookProject)) {
                switch ($bookProject) {
                    case "obs":
                        $repoName = "{$lang}_obs";
                        break;
                    case "bca":
                        $repoName = "{$lang}_bc";
                        break;
                    default:
                        $repoName = "{$lang}_{$bookCode}_{$bookProject}";
                }

                if($bookProject == "tw") {
                    $books = $this->_model->getTranslation($lang, "tw", $sourceBible, $bookCode);
                    if(!empty($books) && isset($books[0])) {
                        $projectFiles = $this->getMdTwProjectFiles($books, true);
                    }
                } elseif (in_array($bookProject, ["obs","bc","bca"])) {
                    $books = $this->_model->getTranslation($lang, $bookProject, $sourceBible, $bookCode);
                    if(!empty($books) && isset($books[0])) {
                        $projectFiles = $this->getResourceProjectFiles($books, true);
                    }
                } else {
                    $books = $this->_model->getTranslation($lang, $bookProject, $sourceBible, $bookCode);
                    if(!empty($books) && isset($books[0])) {
                        $projectFiles = $this->getMdProjectFiles($books, true);
                    }
                }
            } elseif (in_array($bookProject, ["ulb", "udb", "sun"])) {
                if($sourceBible != "odb") {
                    $repoName = "{$lang}_{$bookCode}_text_{$bookProject}";
                    $books = $this->_model->getTranslation($lang, $bookProject, $sourceBible, $bookCode);
                    if(!empty($books) && isset($books[0])) {
                        $projectFiles = $this->getUsfmProjectFiles($books);
                    }
                }
            }

            $result = $this->cloudRepo->uploadRepo($repoName, $projectFiles);

            if($result->success) {
                $response["success"] = true;
                $response["url"] = $result->repo->html_url;
            } else {
                $response["error"] = $result->message;
            }
        } else {
            $response["authenticated"] = false;
            $response["server"] = $server;
            $response["url"] = $this->cloudRepo->prepareAuthRequestUrl();
        }

        echo json_encode($response);
    }


    private function getUsfmProjectFiles($books)
    {
        $projectFiles = [];

        switch ($books[0]->state)
        {
            case EventStates::STARTED:
            case EventStates::TRANSLATING:
                $chk_lvl = 0;
                break;
            case EventStates::TRANSLATED:
            case EventStates::L2_RECRUIT:
            case EventStates::L2_CHECK:
                $chk_lvl = 1;
                break;
            case EventStates::L2_CHECKED:
            case EventStates::L3_RECRUIT:
            case EventStates::L3_CHECK:
                $chk_lvl = 2;
                break;
            case EventStates::COMPLETE:
                $chk_lvl = 3;
                break;
            default:
                $chk_lvl = 0;
        }

        $manifest = $this->_model->generateManifest($books[0]);
        $manifest->setCheckingLevel((string)$chk_lvl);

        $event = $this->eventsRepo->get($books[0]->eventID);
        $eventContributors = new EventContributors(
            $event,
            $manifest->getCheckingLevel(),
            $books[0]->bookProject,
            false
        );
        foreach ($eventContributors->get() as $cat => $list)
        {
            if($cat == "admins") continue;
            foreach ($list as $contributor)
            {
                $manifest->addContributor($contributor["fname"] . " " . $contributor["lname"]);
            }
        }

        $usfm_books = [];
        $lastChapter = 0;
        $lastCode = null;
        $chapterStarted = false;

        foreach ($books as $chunk) {
            $code = sprintf('%02d', $chunk->sort)."-".strtoupper($chunk->bookCode);

            if($code != $lastCode)
            {
                $lastChapter = 0;
                $chapterStarted = false;
            }

            if(!isset($usfm_books[$code]))
            {
                $usfm_books[$code] = "\\id ".strtoupper($chunk->bookCode)." ".__($chunk->bookProject)."\n";
                $usfm_books[$code] .= "\\ide UTF-8 \n";
                $usfm_books[$code] .= "\\h ".mb_strtoupper(__($chunk->bookCode))."\n";
                $usfm_books[$code] .= "\\toc1 ".__($chunk->bookCode)."\n";
                $usfm_books[$code] .= "\\toc2 ".__($chunk->bookCode)."\n";
                $usfm_books[$code] .= "\\toc3 ".ucfirst($chunk->bookCode)."\n";
                $usfm_books[$code] .= "\\mt1 ".mb_strtoupper(__($chunk->bookCode))."\n\n\n\n";
            }

            $verses = json_decode($chunk->translatedVerses);

            if($chunk->chapter != $lastChapter)
            {
                $usfm_books[$code] .= "\\s5 \n";
                $usfm_books[$code] .= "\\c ".$chunk->chapter." \n";
                $usfm_books[$code] .= "\\p \n";

                $lastChapter = $chunk->chapter;
                $chapterStarted = true;
            }

            // Start of chunk
            if(!$chapterStarted)
            {
                $usfm_books[$code] .= "\\s5\n";
                $usfm_books[$code] .= "\\p\n";
            }

            $chapterStarted = false;

            if(!empty($verses->{EventMembers::L3_CHECKER}->verses))
            {
                foreach ($verses->{EventMembers::L3_CHECKER}->verses as $verse => $text) {
                    $usfm_books[$code] .= "\\v ".$verse." ".html_entity_decode($text, ENT_QUOTES)."\n";
                }
            }
            elseif (!empty($verses->{EventMembers::L2_CHECKER}->verses))
            {
                foreach ($verses->{EventMembers::L2_CHECKER}->verses as $verse => $text) {
                    $usfm_books[$code] .= "\\v ".$verse." ".html_entity_decode($text, ENT_QUOTES)."\n";
                }
            }
            else
            {
                foreach ($verses->{EventMembers::TRANSLATOR}->verses as $verse => $text) {
                    $usfm_books[$code] .= "\\v ".$verse." ".html_entity_decode($text, ENT_QUOTES)."\n";
                }
            }

            // End of chunk
            $usfm_books[$code] .= "\n\n";

            $lastCode = $code;

            if(!$manifest->getProject($chunk->bookCode))
            {
                $manifest->addProject(new Project(
                    $chunk->bookName,
                    'other',
                    $chunk->bookCode,
                    (int)$chunk->sort,
                    "./".(sprintf("%02d", $chunk->sort))."-".(strtoupper($chunk->bookCode)).".usfm",
                    ["bible-".($chunk->sort < 41 ? "ot" : "nt")]
                ));
            }
        }

        $yaml = Spyc::YAMLDump($manifest->output(), 4, 0);

        foreach ($usfm_books as $filename => $content)
        {
            $filePath = $filename.".usfm";
            $projectFiles[] = ProjectFile::withContent($filePath, $content);
        }
        $projectFiles[] = ProjectFile::withContent("manifest.yaml", $yaml);

        return $projectFiles;
    }


    private function getTsProjectFiles($book)
    {
        $projectFiles = [];

        switch ($book[0]->state)
        {
            case EventStates::STARTED:
            case EventStates::TRANSLATING:
                $chk_lvl = 0;
                break;
            case EventStates::TRANSLATED:
            case EventStates::L2_RECRUIT:
            case EventStates::L2_CHECK:
                $chk_lvl = 1;
                break;
            case EventStates::L2_CHECKED:
            case EventStates::L3_RECRUIT:
            case EventStates::L3_CHECK:
                $chk_lvl = 2;
                break;
            case EventStates::COMPLETE:
                $chk_lvl = 3;
                break;
            default:
                $chk_lvl = 0;
        }

        $manifest = $this->_model->generateTstudioManifest($book[0]);

        // Set translators/checkers
        $event = $this->eventsRepo->get($book[0]->eventID);
        $eventContributors = new EventContributors(
            $event,
            $chk_lvl,
            $book[0]->bookProject,
            false
        );

        foreach ($eventContributors->get() as $cat => $list)
        {
            if($cat == "admins") continue;
            foreach ($list as $contributor)
            {
                $manifest->addTranslator($contributor["fname"] . " " . $contributor["lname"]);
            }
        }

        $manifest->setFinishedChunks([]);

        $packageManifest = $this->_model->generatePackageManifest($book[0]);
        $root = $packageManifest->getRoot();

        $bookChunks = $this->_apiModel->getPredefinedChunks($book[0]->bookCode, $book[0]->sourceLangID, $book[0]->sourceBible);

        foreach ($book as $chunk) {
            $verses = json_decode($chunk->translatedVerses, true);

            if(!empty($verses[EventMembers::L3_CHECKER]["verses"]))
            {
                $chunkVerses = $verses[EventMembers::L3_CHECKER]["verses"];
            }
            elseif (!empty($verses[EventMembers::L2_CHECKER]["verses"]))
            {
                $chunkVerses = $verses[EventMembers::L2_CHECKER]["verses"];
            }
            else
            {
                $chunkVerses = $verses[EventMembers::TRANSLATOR]["verses"];
            }

            foreach ($chunkVerses as $vNum => $vText)
            {
                if(array_key_exists($chunk->chapter, $bookChunks))
                {
                    foreach ($bookChunks[$chunk->chapter] as $index => $chk)
                    {
                        if(array_key_exists($vNum, $chk))
                        {
                            $bookChunks[$chunk->chapter][$index][$vNum] = "\\v $vNum ".$vText;
                        }
                    }
                }
            }
        }

        foreach ($bookChunks as $cNum => $chap)
        {
            foreach ($chap as $chk)
            {
                $format = "%02d";
                $chapPath = sprintf($format, $cNum);
                reset($chk);
                $chunkPath = sprintf($format, key($chk));
                $filePath = $root. "/" . $chapPath . "/" . $chunkPath . ".txt";

                $t = join(" ", $chk);

                $projectFiles[] = ProjectFile::withContent($filePath, $t);

                $manifest->addFinishedChunk($chapPath."-".$chunkPath);
            }
        }

        // Add git initial files
        $tmpDir = "/tmp";
        if(Tools::unzip("../app/Templates/Default/Assets/.git.zip", $tmpDir))
        {
            foreach (Tools::iterateDir($tmpDir . "/.git/") as $file)
            {
                $projectFiles[] = ProjectFile::withFile($root . "/.git/" . $file["rel"], $file["abs"]);
            }
            File::delete($tmpDir . "/.git");
        }

        // Add license file
        $license = File::get("../app/Templates/Default/Assets/LICENSE.md");
        $projectFiles[] = ProjectFile::withContent($root . "/LICENSE.md", $license);
        // Add package manifest
        $packageManifestContent = json_encode($packageManifest->output(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $projectFiles[] = ProjectFile::withContent("manifest.json", $packageManifestContent);
        // Add project manifest
        $manifestContent = json_encode($manifest->output(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $projectFiles[] = ProjectFile::withContent($root . "/manifest.json", $manifestContent);

        return $projectFiles;
    }


    private function getJsonProjectFiles($books)
    {
        $projectFiles = [];

        switch ($books[0]->state)
        {
            case EventStates::STARTED:
            case EventStates::TRANSLATING:
            case EventStates::TRANSLATED:
            case EventStates::L2_RECRUIT:
            case EventStates::L2_CHECK:
                $chk_lvl = 1;
                break;
            case EventStates::L2_CHECKED:
            case EventStates::L3_RECRUIT:
            case EventStates::L3_CHECK:
                $chk_lvl = 2;
                break;
            case EventStates::COMPLETE:
                $chk_lvl = 3;
                break;
            default:
                $chk_lvl = 0;
        }

        $manifest = $this->_model->generateManifest($books[0]);

        $json_books = [];
        $lastChapter = 0;
        $lastCode = null;

        foreach ($books as $chunk) {
            $code = strtoupper($chunk->bookCode);

            if($code != $lastCode)
            {
                $lastChapter = 0;
            }

            if(!isset($json_books[$code]))
            {
                $json_books[$code] = ["root" => []];

                $event = $this->eventsRepo->get($chunk->eventID);
                $eventContributors = new EventContributors(
                    $event,
                    $chk_lvl,
                    $chunk->bookProject,
                    false
                );
                foreach ($eventContributors->get() as $cat => $list)
                {
                    if($cat == "admins") continue;
                    foreach ($list as $contributor)
                    {
                        $manifest->addContributor($contributor["fname"] . " " . $contributor["lname"]);
                    }
                }
            }

            $verses = json_decode($chunk->translatedVerses);

            if($chunk->chapter != $lastChapter)
            {
                $lastChapter = $chunk->chapter;
                $json_books[$code]["root"][$lastChapter-1] = [];
            }

            if(!empty($verses->{EventMembers::L3_CHECKER}->verses))
            {
                foreach ($verses->{EventMembers::L3_CHECKER}->verses as $verse => $text) {
                    $json_books[$code]["root"][$lastChapter-1][OdbSections::enum($verse)] = html_entity_decode($text, ENT_QUOTES);
                }
            }
            elseif (!empty($verses->{EventMembers::L2_CHECKER}->verses))
            {
                foreach ($verses->{EventMembers::L2_CHECKER}->verses as $verse => $text) {
                    $json_books[$code]["root"][$lastChapter-1][OdbSections::enum($verse)] = html_entity_decode($text, ENT_QUOTES);
                }
            }
            else
            {
                if($chunk->bookProject == "rad")
                {
                    $translation = isset($verses->{EventMembers::CHECKER}->verses)
                        && !empty($verses->{EventMembers::CHECKER}->verses)
                        ? $verses->{EventMembers::CHECKER}->verses
                        : $verses->{EventMembers::TRANSLATOR}->verses;

                    if(!is_object($translation))
                    {
                        $ind = $chunk->chunk == 0 ? RadioSections::ENTRY : RadioSections::TITLE;
                        $json_books[$code]["root"][$lastChapter-1][RadioSections::enum($ind)] = html_entity_decode($translation, ENT_QUOTES);
                    }
                    else
                    {
                        $tmp = [];
                        $tmp["name"] = $translation->name;
                        $tmp["text"] = $translation->text;
                        $json_books[$code]["root"][$lastChapter-1][RadioSections::enum(RadioSections::SPEAKERS)][] = $tmp;
                    }
                }
                else
                {
                    foreach ($verses->{EventMembers::TRANSLATOR}->verses as $verse => $text) {
                        if($verse >= OdbSections::CONTENT)
                        {
                            $json_books[$code]["root"][$lastChapter-1][OdbSections::enum($verse)][] = html_entity_decode($text, ENT_QUOTES);
                        }
                        else
                        {
                            $json_books[$code]["root"][$lastChapter-1][OdbSections::enum($verse)] = html_entity_decode($text, ENT_QUOTES);
                        }
                    }
                }
            }

            $lastCode = $code;

            if(!$manifest->getProject($chunk->bookCode))
            {
                $manifest->addProject(new Project(
                    $chunk->bookName,
                    'other',
                    $chunk->bookCode,
                    (int)$chunk->sort,
                    "./".(strtoupper($chunk->bookCode)).".json",
                    ["rad"]
                ));
            }
        }

        foreach ($json_books as $filename => $content)
        {
            $filePath = $filename.".json";
            $content = json_encode($content, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
            $projectFiles[] = ProjectFile::withContent($filePath, $content);
        }

        $yaml = Spyc::YAMLDump($manifest->output(), 4, 0);
        $projectFiles[] = ProjectFile::withContent("manifest.yaml", $yaml);

        return $projectFiles;
    }


    private function getMdProjectFiles($books, $upload = false)
    {
        $projectFiles = [];
        $lastChapter = -1;
        $chapter = [];

        switch ($books[0]->state)
        {
            case EventStates::STARTED:
                $chk_lvl = 0;
                break;
            case EventStates::TRANSLATING:
                $chk_lvl = 1;
                break;
            case EventStates::TRANSLATED:
            case EventStates::L3_CHECK:
                $chk_lvl = 2;
                break;
            case EventStates::COMPLETE:
                $chk_lvl = 3;
                break;
            default:
                $chk_lvl = 0;
        }

        $manifest = $this->_model->generateManifest($books[0]);
        $manifest->setCheckingLevel((string)$chk_lvl);

        $root = !$upload ? $books[0]->targetLang."_".$books[0]->bookProject . "/" : "";

        foreach ($books as $chunk) {
            $verses = json_decode($chunk->translatedVerses);

            if($chunk->chapter != $lastChapter)
            {
                $lastChapter = $chunk->chapter;

                $chapters = $this->_eventModel->getChapters(
                    $chunk->eventID,
                    null,
                    $chunk->chapter
                );
                $chapter = $chapters[0];
            }

            $chunks = (array)json_decode($chapter["chunks"], true);
            $currChunk = $chunks[$chunk->chunk] ?? 1;

            $bookPath = $chunk->bookCode;
            $format = $chunk->bookCode == "psa" ? "%03d" : "%02d";
            $chapPath = $chunk->chapter > 0 ? sprintf($format, $chunk->chapter) : "front";
            $chunkPath = $currChunk[0] > 0 ? sprintf($format, $currChunk[0]) : "intro";
            $filePath = $root . $bookPath . "/" . $chapPath . "/" . $chunkPath . ".md";

            if(!empty($verses->{EventMembers::L3_CHECKER}->verses))
            {
                $text = $verses->{EventMembers::L3_CHECKER}->verses;
            }
            elseif (!empty($verses->{EventMembers::CHECKER}->verses))
            {
                $text = $verses->{EventMembers::CHECKER}->verses;
            }
            else
            {
                $text = $verses->{EventMembers::TRANSLATOR}->verses;
            }

            $projectFiles[] = ProjectFile::withContent($filePath, $text);

            if(!$manifest->getProject($chunk->bookCode))
            {
                $event = $this->eventsRepo->get($chunk->eventID);
                $eventContributors = new EventContributors(
                    $event,
                    $manifest->getCheckingLevel(),
                    $chunk->bookProject,
                    false
                );
                foreach ($eventContributors->get() as $cat => $list)
                {
                    if($cat == "admins") continue;
                    foreach ($list as $contributor)
                    {
                        $manifest->addContributor($contributor["fname"] . " " . $contributor["lname"]);
                    }
                }

                $manifest->addProject(new Project(
                    $chunk->bookName . " " . __($books[0]->bookProject),
                    "",
                    $chunk->bookCode,
                    (int)$chunk->sort,
                    "./".$chunk->bookCode,
                    []
                ));
            }
        }

        $yaml = Spyc::YAMLDump($manifest->output(), 4, 0);
        $projectFiles[] = ProjectFile::withContent($root . "manifest.yaml", $yaml);

        return $projectFiles;
    }


    private function getMdTwProjectFiles($books, $upload = false)
    {
        $projectFiles = [];

        switch ($books[0]->state)
        {
            case EventStates::STARTED:
                $chk_lvl = 0;
                break;
            case EventStates::TRANSLATING:
                $chk_lvl = 1;
                break;
            case EventStates::TRANSLATED:
            case EventStates::L3_CHECK:
                $chk_lvl = 2;
                break;
            case EventStates::COMPLETE:
                $chk_lvl = 3;
                break;
            default:
                $chk_lvl = 0;
        }

        $manifest = $this->_model->generateManifest($books[0]);
        $manifest->setCheckingLevel((string)$chk_lvl);

        // Set contributor list from entire project contributors
        $project = $this->projectsRepo->get($books[0]->projectID);
        $projectContributors = new ProjectContributors(
            $project,
            false,
            false
        );
        foreach ($projectContributors->get() as $contributor)
        {
            $manifest->addContributor($contributor["fname"] . " " . $contributor["lname"]);
        }

        $manifest->addProject(new Project(
            __($books[0]->bookProject),
            "",
            "bible",
            0,
            "./bible",
            []
        ));

        $root = !$upload ? $books[0]->targetLang."_tw/" : "";

        foreach ($books as $chunk) {
            $verses = json_decode($chunk->translatedVerses);
            $words = (array) json_decode($chunk->words, true);

            $currWord = $words[$chunk->chunk] ?? null;

            if(!$currWord) continue;

            $bookPath = $chunk->bookName;
            $chunkPath = $currWord;
            $filePath = $root. "bible/" . $bookPath ."/". $chunkPath.".md";

            if(!empty($verses->{EventMembers::L3_CHECKER}->verses))
            {
                $text = $verses->{EventMembers::L3_CHECKER}->verses;
            }
            else
            {
                $text = $verses->{EventMembers::TRANSLATOR}->verses;
            }

            $projectFiles[] = ProjectFile::withContent($filePath, $text);
        }

        $yaml = Spyc::YAMLDump($manifest->output(), 4, 0);
        $projectFiles[] = ProjectFile::withContent($root."manifest.yaml", $yaml);

        return $projectFiles;
    }

    private function getResourceProjectFiles($books, $upload = false)
    {
        $bookProject = $books[0]->bookProject;

        switch ($books[0]->state) {
            case EventStates::STARTED:
                $chk_lvl = 0;
                break;
            case EventStates::TRANSLATING:
                $chk_lvl = 1;
                break;
            case EventStates::TRANSLATED:
            case EventStates::L3_CHECK:
                $chk_lvl = 2;
                break;
            case EventStates::COMPLETE:
                $chk_lvl = 3;
                break;
            default:
                $chk_lvl = 0;
        }

        $manifest = $this->_model->generateManifest($books[0]);
        $manifest->setCheckingLevel((string)$chk_lvl);

        // Set contributor list from entire project contributors
        $project = $this->projectsRepo->get($books[0]->projectID);
        $projectContributors = new ProjectContributors(
            $project,
            false,
            false
        );
        foreach ($projectContributors->get() as $contributor) {
            $manifest->addContributor($contributor["fname"] . " " . $contributor["lname"]);
        }

        $rootProject = $bookProject == "bca" ? "bc" : $bookProject;
        $root = !$upload ? $books[0]->targetLang."_".$rootProject."/" : "";

        $projectFiles = [];
        $lastChapter = $bookProject == "bc" ? -1 : 0;
        $chapters = [];
        $listStarted = false;
        $words = [];

        foreach ($books as $chunk) {
            $verses = json_decode($chunk->translatedVerses);

            if($chunk->chapter != $lastChapter)
            {
                $lastChapter = $chunk->chapter;
                $chapters[$lastChapter] = "";
            }

            if(!empty($verses->{EventMembers::L3_CHECKER}->verses))
            {
                $resource = $verses->{EventMembers::L3_CHECKER}->verses;
            }
            elseif (!empty($verses->{EventMembers::CHECKER}->verses))
            {
                $resource = $verses->{EventMembers::CHECKER}->verses;
            }
            else
            {
                $resource = $verses->{EventMembers::TRANSLATOR}->verses;
            }

            $beforeLineBreak = "";
            $afterLineBreak = "\n\n";

            if ($listStarted) {
                $beforeLineBreak = "\n\n";
                $afterLineBreak = "\n";
                $listStarted = false;
            }

            $resource->text = html_entity_decode($resource->text);

            switch ($resource->type) {
                case ResourceChunkType::IMAGE:
                    if (!empty($resource->text)) {
                        $chapters[$lastChapter] .= "![OBS Image](" . $resource->meta . ")\n\n";
                        $chapters[$lastChapter] .= $resource->text . "\n\n";
                    } else {
                        $chapters[$lastChapter] .= $resource->meta . "\n\n";
                    }
                    break;
                case ResourceChunkType::LINK:
                    $chapters[$lastChapter] .= $beforeLineBreak . $this->formatBcLink($resource) . $afterLineBreak;
                    break;
                default:
                    if (!empty($resource->meta)) {
                        $isListItem = preg_match("/^(\d+\.|-|\*)\s\{\}$/", $resource->meta);
                        if ($isListItem) {
                            $listStarted = true;
                            $beforeLineBreak = "\n";
                            $afterLineBreak = "";
                        }

                        $content = str_replace("{}", $resource->text, $resource->meta);
                        $chapters[$lastChapter] .= $beforeLineBreak . $content . $afterLineBreak;
                    } else {
                        $chapters[$lastChapter] .= $resource->text . "\n\n";
                    }
                    break;
            }

            if ($bookProject != "bca") {
                if(!$manifest->getProject($chunk->bookCode))
                {
                    if ($bookProject == "obs") {
                        $manifest->addProject(new Project(
                            __($bookProject),
                            "",
                            $chunk->bookCode,
                            0,
                            "./content",
                            []
                        ));
                    } else {
                        $manifest->addProject(new Project(
                            $chunk->bookName,
                            "",
                            $chunk->bookCode,
                            (int)$chunk->sort,
                            sprintf("%02d", $chunk->sort)."-".$chunk->bookCode,
                            []
                        ));
                    }
                }
            }
            $words[$lastChapter] = $chunk->word;
        }

        foreach ($chapters as $chapter => $content) {
            $format = "%02d";
            switch ($bookProject) {
                case "obs":
                    $contentPath = "content";
                    $chapPath = $chapter > 0 ? sprintf($format, $chapter) : "intro";
                    break;

                case "bca":
                    $contentPath = "articles";
                    $chapPath = $words[$chapter];
                    break;

                default:
                    $contentPath = sprintf("%02d", $books[0]->sort)."-".$books[0]->bookCode;
                    $chapPath = $chapter > 0 ? sprintf($format, $chapter) : "intro";
            }


            $filePath = $root . $contentPath . "/" . $chapPath . ".md";
            $projectFiles[] = ProjectFile::withContent($filePath, $content);
        }

        $yaml = Spyc::YAMLDump($manifest->output(), 4, 0);
        $projectFiles[] = ProjectFile::withContent($root . "manifest.yaml", $yaml);

        return $projectFiles;
    }

    private function formatBcLink($link) {
        $parts = mb_split(":", $link->text);
        $metas = mb_split(":", $link->meta);
        $start = "";
        $content = $parts[0];
        $metaContent = $metas[0];

        if (sizeof($parts) > 1) {
            $start = "$parts[0]:";
            $content = $parts[1];
        }

        if (sizeof($metas) > 1) {
            $metaContent = $metas[1];
        }

        $links = mb_split(";", $content);
        $metaLinks = mb_split(";", $metaContent);

        foreach ($links as $key => $ln) {
            $ln = trim($ln);
            $metaLinks[$key] = trim($metaLinks[$key]);
            $metaLinks[$key] = preg_replace("/^\[.*?\]/", "[$ln]", $metaLinks[$key]);
        }

        $joined = join("; ", $metaLinks);

        return "$start $joined";
    }

    private function sortBcaBook(&$book) {
        usort($book, function($a, $b) {
            $aVerses = json_decode($a[0]->translatedVerses);
            $bVerses = json_decode($b[0]->translatedVerses);

            if (!empty($aVerses->{EventMembers::CHECKER}->verses)) {
                $aVerse = $aVerses->{EventMembers::CHECKER}->verses->text;
            } else {
                $aVerse = $aVerses->{EventMembers::TRANSLATOR}->verses->text;
            }

            if (!empty($bVerses->{EventMembers::CHECKER}->verses)) {
                $bVerse = $bVerses->{EventMembers::CHECKER}->verses->text;
            } else {
                $bVerse = $bVerses->{EventMembers::TRANSLATOR}->verses->text;
            }

            return strcasecmp($aVerse, $bVerse);
        });
    }

    private function sortTwBook(&$book) {
        $combined = [[]];
        foreach ($book as $group) {
            $combined[0] = array_merge($combined[0], $group);
        }
        $book = $combined;

        usort($book[0], function($a, $b) {
            $aVerses = json_decode($a->translatedVerses);
            $bVerses = json_decode($b->translatedVerses);

            if (!empty($aVerses->{EventMembers::CHECKER}->verses)) {
                $aVerse = $aVerses->{EventMembers::CHECKER}->verses;
            } else {
                $aVerse = $aVerses->{EventMembers::TRANSLATOR}->verses;
            }

            if (!empty($bVerses->{EventMembers::CHECKER}->verses)) {
                $bVerse = $bVerses->{EventMembers::CHECKER}->verses;
            } else {
                $bVerse = $bVerses->{EventMembers::TRANSLATOR}->verses;
            }

            $aVerse = Tools::trim($aVerse);
            $bVerse = Tools::trim($bVerse);

            return strcasecmp($aVerse, $bVerse);
        });
    }
}
