<?php
namespace App\Models;

use Database\Model;
use Helpers\Manifest\Normal\Language;
use Helpers\Manifest\Normal\Manifest;
use Helpers\Manifest\Normal\Source;
use Helpers\Manifest\Package\CommitHash;
use Helpers\Manifest\Tstudio\Manifest as TstudioManifest;
use Helpers\Manifest\Tstudio\Generator;
use Helpers\Manifest\Tstudio\Project;
use Helpers\Manifest\Tstudio\Resource;
use Helpers\Manifest\Tstudio\SourceTranslation;
use Helpers\Manifest\Tstudio\TargetLanguage;
use Helpers\Manifest\Tstudio\Type;
use Helpers\Manifest\Package\Manifest as PackageManifest;
use Helpers\Manifest\Package\Generator as PackageGenerator;
use Helpers\Manifest\Package\TargetTranslation as PackageTargetTranslation;
use Helpers\ProjectFile;
use Helpers\ZipStream\Exception\OverflowException;
use Helpers\ZipStream\ZipStream;
use Helpers\ZipStream\Option\Archive as ZipOptions;

class TranslationsModel extends Model
{
    public  function __construct()
    {
        parent::__construct();
    }


    public function getTranslationLanguages()
    {
        return $this->db->table("projects")
            ->select("projects.targetLang", "languages.langName", "languages.angName")
            ->leftJoin("languages", "projects.targetLang","=", "languages.langID")
            ->groupBy("projects.targetLang")
            ->get();
    }

    public function getTranslationProjects($lang)
    {
        return $this->db->table("projects")
            ->select("projects.targetLang", "languages.langName", "languages.angName",
                "projects.bookProject", "projects.sourceBible")
            ->leftJoin("languages", "projects.targetLang","=", "languages.langID")
            ->where("projects.targetLang", $lang)
            ->groupBy(["projects.bookProject","projects.sourceBible"])->get();
    }

    public function getTranslationBooks($lang, $bookProject, $sourceBible)
    {
        return $this->db->table("projects")
            ->select("projects.targetLang", "languages.langName", "languages.angName",
                "projects.bookProject", "projects.sourceBible",
                "book_info.name AS bookName", "events.bookCode", "book_info.sort")
            ->leftJoin("languages", "projects.targetLang","=", "languages.langID")
            ->leftJoin("events", "projects.projectID", "=", "events.projectID")
            ->leftJoin("book_info", "events.bookCode","=", "book_info.code")
            ->where("projects.targetLang", $lang)
            ->where("projects.bookProject", $bookProject)
            ->where("projects.sourceBible", $sourceBible)
            ->orderBy("book_info.sort")
            ->groupBy("events.bookCode")->get();
    }

    /**
     * Get translation work
     * @param $lang Language ID
     * @param $bookProject string Book project type (ulb, udb, tn, sun, l2)
     * @param $sourceBible string Source bible (ulb, ayt, odb)
     * @param $bookCode string Book slug
     * @return array
     */
    public function getTranslation($lang, $bookProject, $sourceBible, $bookCode = null)
    {
        $builder = $this->db->table("translations")
            ->select("translations.targetLang", "languages.langName", "languages.angName",
                "translations.bookProject", "projects.sourceBible", "translations.bookCode", "book_info.name AS bookName", "book_info.sort",
                "translations.chapter", "translations.chunk", "translations.translatedVerses", "events.state",
                "translations.eventID", "languages.direction", "projects.sourceLangID", "projects.sourceBible",
                "projects.projectID", "projects.resLangID", "chapters.chunks", "words.word")
            ->leftJoin("languages", "translations.targetLang","=", "languages.langID")
            ->leftJoin("book_info", "translations.bookCode","=", "book_info.code")
            ->leftJoin("projects", "translations.projectID","=", "projects.projectID")
            ->leftJoin("events", "translations.eventID","=", "events.eventID")
            ->leftJoin("chapters", function ($join) {
                $join->on("chapters.chapter", "=", "translations.chapter")
                    ->on("chapters.eventID", "=", "events.eventID");
            })
            ->leftJoin("words", function ($join) {
                $join->on("words.wordID", "=", "translations.chapter")
                    ->on("words.eventID", "=", "events.eventID");
            })
            ->where("translations.targetLang", $lang)
            ->where("translations.bookProject", $bookProject)
            ->where("projects.sourceBible", $sourceBible)
            ->where("translations.translateDone", true)
            ->orderBy("book_info.sort")
            ->orderBy("translations.chapter")
            ->orderBy("translations.chunk");

        if($bookCode)
            $builder->where("translations.bookCode", $bookCode);

        if($bookProject == "tw")
        {
            $builder->addSelect("word_groups.words")
                ->leftJoin("word_groups", "translations.chapter", "=", "word_groups.groupID");
        }

        return $builder->get();
    }

    /**
     * Get translation work information
     * @param $eventID
     * @param null $chapter
     * @return array|static[]
     */
    public function getTranslationByEventID($eventID, $chapter = null)
    {
        $builder = $this->db->table("translators");

        $builder->select("translations.chapter", "translations.chunk", "translations.translateDone",
                "translations.translatedVerses",
                "translations.firstvs", "translations.dateUpdate", "translators.memberID", "translators.step",
                "translators.verbCheck", "translators.peerCheck", "translators.kwCheck", "translators.crCheck",
                "translators.otherCheck", "translators.currentChapter")
            ->leftJoin("translations", "translators.trID", "=", "translations.trID")
            ->where("translators.eventID", $eventID)
            ->orderBy("translations.chapter")
            ->orderBy("translations.chunk");

        if($chapter !== null)
            $builder->where("translations.chapter", $chapter);
        return $builder->get();
    }


    /** Get translation work of translator in event
     * (all - if chapter null, chunk - if chunk not null, chapter - if chapter not null)
     * @param int $trID
     * @param int $chapter
     * @param int $chunk
     * @return array
     */
    public function getEventTranslation($trID, $chapter = null, $chunk = null)
    {
        $builder = $this->db->table("translations")
            ->where("trID", $trID)
            ->orderBy("firstvs");

        if($chapter !== null) {
            $builder->where("chapter", $chapter);
            if($chunk !== null) {
                $builder->where("chunk", $chunk);
            }
        }

        return $builder->get();
    }


    /**
     * Get translation work of translator/checker in event by eventID
     * (all - if chapter null, chunk - if chunk not null, chapter - if chapter not null)
     * @param $eventID
     * @param null $chapter
     * @param null $chunk
     * @return array|\Database\Query\Builder[]
     */
    public function getEventTranslationByEventID($eventID, $chapter = null, $chunk = null)
    {
        $builder = $this->db->table("translations")
            ->where("eventID", $eventID)
            ->orderBy("chunk");

        if($chapter !== null) {
            $builder->where("chapter", $chapter);
            if($chunk !== null) {
                $builder->where("chunk", $chunk);
            }
        }

        return $builder->get();
    }

    public function getSources() {
        return $this->db->table("sources")
            ->leftJoin("languages", "languages.langID", "=", "sources.langID")
            ->orderBy("sources.langID")
            ->orderBy("sources.slug")
            ->get();
    }

    public function getKnownSourceTypes() {
        return $this->db->table("sources")
            ->groupBy("slug")
            ->orderBy("slug")
            ->get();
    }

    public function getLanguageInfo($lang)
    {
        return $this->db->table("languages")
            ->where("langID", $lang)->get();
    }

    public function getBookInfo($bookCode)
    {
        return $this->db->table("book_info")
            ->where("code", $bookCode)->get();
    }

    public function getBooksList()
    {
        return $this->db->table("book_info")
            ->orderBy("sort")->get();
    }

    /**
     * Create translation record
     * @param array $data
     * @return int
     */
    public function createTranslation($data)
    {
        return $this->db->table("translations")
            ->insertGetId($data);
    }

    /** Update translation
     * @param array $data
     * @param array $where
     * @return int
     */
    public function updateTranslation($data, $where)
    {
        return $this->db->table("translations")
            ->where($where)
            ->update($data);
    }

    /**
     * Delete translation/s
     * @param $where
     * @return int
     */
    public function deleteTranslation($where)
    {
        return $this->db->table("translations")
            ->where($where)
            ->delete();
    }

    public function getComment($eventID, $chapter, $chunk, $memberID, $level, $saved)
    {
        return $this->db->table("comments")
            ->where("eventID", $eventID)
            ->where("chapter", $chapter)
            ->where("chunk", $chunk)
            ->where("memberID", $memberID)
            ->where("level", $level)
            ->where("saved", $saved)
            ->get();
    }

    public function getCommentsByEvent($eventID, $chapter = null, $chunk = null)
    {
        $builder = $this->db->table("comments")
            ->select("comments.*", "members.userName", "members.firstName", "members.lastName")
            ->leftJoin("members", "comments.memberID", "=", "members.memberID")
            ->where("comments.eventID", $eventID)
            ->orderBy("comments.cID", "desc")
            ->orderBy("comments.chapter")
            ->orderBy("comments.chunk");

        if($chapter !== null)
        {
            $builder->where("comments.chapter", $chapter);
            if($chunk !== null)
                $builder->where("comments.chunk", $chunk);
        }

        return $builder->get();
    }

    public function deleteCommentsByEvent($eventID, $chapter = null)
    {
        $builder = $this->db->table("comments");

        $builder->where("eventID", $eventID);
        if($chapter !== null)
            $builder->where("chapter", $chapter);

        return $builder->delete();

    }

    public function getKeywords($where)
    {
        return $this->db->table("keywords")
            ->select()
            ->where($where)
            ->orderBy("chapter")
            ->orderBy("chunk")
            ->orderBy("verse")->get();
    }

    public function deleteKeyword($kID)
    {
        return $this->db->table("keywords")
            ->where("kID", $kID)
            ->delete();
    }

    public function createKeyword($data)
    {
        return $this->db->table("keywords")
            ->insertGetId($data);
    }

    public function createComment($data)
    {
        return $this->db->table("comments")
            ->insertGetId($data);
    }

    public function updateComment($data, $where)
    {
        return $this->db->table("comments")
            ->where($where)
            ->update($data);
    }

    public function deleteComment($where)
    {
        return $this->db->table("comments")
            ->where($where)
            ->delete();
    }

    public function generateManifest($data) {
        if(in_array($data->bookProject, ["tn","tq","tw","obs","bc","bca"]))
        {
            $format = "text/markdown";
        }
        elseif (in_array($data->sourceBible, ["odb","rad"]))
        {
            $format = "text/json";
        }
        else
        {
            $format = "text/usfm";
        }

        if(in_array($data->bookProject, ["tn","tq","tw","bc","bca"]))
        {
            $type = $data->bookProject == "tw" ? "dict" : "help";
        }
        elseif ($data->bookProject == "obs") {
            $type = "book";
        }
        else
        {
            $type = "bundle";
        }

        if(in_array($data->bookProject, ["tn","tq","tw","obs","bc","bca"]))
        {
            $subject = __($data->bookProject);
        }
        elseif ($data->sourceBible == "odb")
        {
            $subject = "Our Daily Bread";
        }
        elseif ($data->bookProject == "rad")
        {
            $subject = "Radio";
        }
        else
        {
            $subject = "Bible";
        }

        if(in_array($data->bookProject, ["tn","tq","tw","bc","bca"]))
        {
            $relation = [
                $data->targetLang."/ulb",
                $data->targetLang."/udb"
            ];

            if($data->bookProject == "tw")
            {
                $relation[] = $data->targetLang."/obs";
                $relation[] = $data->targetLang."/tn";
                $relation[] = $data->targetLang."/tq";
            }
        }
        elseif ($data->bookProject == "rad" || $data->sourceBible == "odb")
        {
            $relation = [];
        }
        else
        {
            $relation = [
                $data->targetLang."/tw",
                $data->targetLang."/tq",
                $data->targetLang."/tn"
            ];
        }

        $source = in_array($data->bookProject, ["tn","tq","tw","obs","bc","bca"]) ? [
            new Source(
                $data->bookProject != "bca" ? $data->bookProject : "bc",
                $data->resLangID,
                "1"
            )
        ] : [
            new Source(
                $data->sourceBible,
                $data->sourceLangID,
                "1"
            )
        ];

        $manifest = new Manifest();

        $manifest->setCreator("Wycliffe Associates");
        $manifest->setPublisher("unfoldingWord");
        $manifest->setFormat($format);
        $manifest->setIdentifier($data->bookProject != "bca" ? $data->bookProject : "bc");
        $manifest->setIssued(date("Y-m-d", time()));
        $manifest->setModified(date("Y-m-d", time()));
        $manifest->setLanguage(new Language(
            $data->direction,
            $data->targetLang,
            $data->langName));
        $manifest->setRelation($relation);
        $manifest->setSource($source);
        $manifest->setSubject($subject);
        $manifest->setTitle(__($data->bookProject));
        $manifest->setType($type);
        $manifest->setCheckingEntity(["Wycliffe Associates"]);

        return $manifest;
    }

    public function generateTstudioManifest($data)
    {
        $manifest = new TstudioManifest();

        $manifest->setPackageVersion("6");
        $manifest->setFormat("usfm");
        $manifest->setGenerator(new Generator("ts-desktop", "1"));
        $manifest->setTargetLanguage(new TargetLanguage($data->targetLang, $data->langName, $data->direction));
        $manifest->setProject(new Project($data->bookCode, $data->bookName));
        $manifest->setType(new Type("text", "Text"));
        $manifest->setResource(new Resource($data->bookProject, __($data->bookProject)));
        $manifest->setSourceTranslations([new SourceTranslation($data->sourceLangID, $data->sourceBible, "3", "", "")]);

        return $manifest;
    }

    public function generatePackageManifest($data)
    {
        $root = $data->targetLang."_".$data->bookCode."_text_".$data->bookProject;

        $manifest = new PackageManifest();

        $manifest->setGenerator(new PackageGenerator("ts-desktop", "1"));
        $manifest->setPackageVersion(2);
        $manifest->setTimestamp(time() * 1000);
        $manifest->setRoot($root);
        $manifest->setTargetTranslations([new PackageTargetTranslation(
            $root,
            $root,
            new CommitHash("", "", ""),
            $data->direction)
        ]);

        return $manifest;
    }

    /**
     * Make zipstream file
     * @param string File name
     * @param ProjectFile[] $files
     * @param bool Should output header
     * @throws OverflowException
     */
    public function generateZip($filename, $files, $out = false)
    {
        $zipOptions = new ZipOptions();
        $zipOptions->setSendHttpHeaders($out);
        $zip = new ZipStream($filename, $zipOptions);

        foreach ($files as $file)
        {
            if($file->isFromDisk())
            {
                $zip->addFileFromPath($file->relPath(), $file->absPath());
            }
            else
            {
                $zip->addFile($file->relPath(), $file->content());
            }
        }

        $zip->finish();
    }
}