<?php


namespace App\Repositories\Resources;


use App\Data\Resource\ResourceChapter;
use App\Data\Resource\ResourceMapper;
use App\Domain\ParseResource;
use App\Repositories\Event\IEventRepository;
use App\Repositories\Preferences\IPreferenceRepository;
use DOMDocument;
use Exception;
use File;
use Helpers\Manifest\ManifestParser;
use Helpers\Manifest\MediaData;
use Helpers\Manifest\MediaFormat;
use Helpers\Arrays;
use Helpers\Parsedown;
use Helpers\UsfmParser;
use SplFileObject;
use Support\Collection;
use Cache;
use Support\Str;
use ZipArchive;

class ResourcesRepository implements IResourcesRepository {
    private $rootPath = "../app/Templates/Default/Assets/source/";

    private $wacsApiUrl = "https://content.bibletranslationtools.org/api/v1/";
    private $dcsApiUrl = "https://git.door43.org/api/v1/";

    private $wacsCatalogUrl = "https://api.bibletranslationtools.org/v3/catalog.json";
    private $dcsCatalogUrl = "https://api.door43.org/v3/catalog.json";

    private $wacsCatalogPath;
    private $dcsCatalogPath;
    private $languagesPath;

    private $qaGuideUrl = "https://v-raft.com/api/rubric/";
    private $bcUrl = "https://content.bibletranslationtools.org/WycliffeAssociates/en_bc/archive/master.zip";

    private $wordsDatabase = null;
    private $wordsDictionary = null;

    private $eventRepo;
    private $prefRepo;

    public function __construct(
        IEventRepository $eventRepo,
        IPreferenceRepository $prefRepo
    ) {
        $this->wacsCatalogPath = $this->rootPath . "catalog.json";
        $this->dcsCatalogPath = $this->rootPath . "catalog_dcs.json";
        $this->languagesPath = $this->rootPath . "langnames.json";

        $this->eventRepo = $eventRepo;
        $this->prefRepo = $prefRepo;
    }

    /**
     * Get Obs collection
     * @param string $lang
     * @param null $chapter Filter by chapter
     * @return ResourceChapter|Collection|null
     */
    public function getObs($lang, $chapter = null) {
        $obs_cache_key = $lang . "_obs_obs";

        if (Cache::has($obs_cache_key)) {
            $obsSource = Cache::get($obs_cache_key);
            $data = json_decode($obsSource, true);
            $obs = ResourceMapper::toResource($data);
        } else {
            $obs = $this->parseObs($lang);
            if (!$obs->isEmpty()) {
                $data = ResourceMapper::fromResource($obs);
                Cache::add($obs_cache_key, json_encode($data), 365 * 24 * 7);
            }
        }

        if ($chapter !== null) {
            $obs = $obs->filter(function($item) use ($chapter) {
                return $item->chapter == $chapter;
            })->first();
        }

        return $obs;
    }

    /**
     * Get Scripture
     * @param string $lang
     * @param string $resource ulb or udb
     */
    public function getScripture($lang, $resource, $bookSlug, $bookNum, $chapter = null)
    {
        $scripture_cache_key = $lang . "_" . $resource . "_" . $bookSlug;

        if (Cache::has($scripture_cache_key)) {
            $source = Cache::get($scripture_cache_key);
            $book = json_decode($source, true);
        } else {
            $book = $this->parseScripture($lang, $resource, $bookSlug, $bookNum);
            if ($book && !empty($book["chapters"])) {
                Cache::add($scripture_cache_key, json_encode($book), 365 * 24 * 7);
            }
        }

        if ($chapter !== null) {
            return $book[$chapter] ?? [];
        }

        return $book;
    }

    public function getMdResource($lang, $resource, $bookSlug, $chapter = null, $toHtml = false)
    {
        $resource_cache_key = $lang . "_" . $resource . "_" . $bookSlug . ($toHtml ? "_html" : "");

        if (Cache::has($resource_cache_key)) {
            $source = Cache::get($resource_cache_key);
            $book = json_decode($source, true);
        } else {
            $book = $this->parseMdResource($lang, $resource, $bookSlug, $toHtml);
            if (!empty($book)) {
                Cache::add($resource_cache_key, json_encode($book), 365 * 24 * 7);
            }
        }

        if ($chapter !== null) {
            return $book[$chapter] ?? [];
        }

        return $book;
    }

    public function getBc($lang, $bookSlug, $chapter = null, $toHtml = false)
    {
        $resource_cache_key = $lang . "_bc_" . $bookSlug . ($toHtml ? "_html" : "");

        if (Cache::has($resource_cache_key)) {
            $source = Cache::get($resource_cache_key);
            $book = json_decode($source, true);
        } else {
            $book = $this->parseBc($lang, $bookSlug, $toHtml);
            if (!empty($book)) {
                Cache::add($resource_cache_key, json_encode($book), 365 * 24 * 7);
            }
        }

        if ($chapter != null) {
            return $book[$chapter] ?? [];
        }

        return $book;
    }

    public function getBcArticle($lang, $article, $toHtml = false) 
    {
        return $this->parseBcArticle($lang, $article, $toHtml);
    }

    /**
     * Get Bible Commentaries collection for translation
     * @param string $lang
     * @param null $bookSlug Filter by book slug
     * @param null $chapter Filter by chapter
     * @return ResourceChapter|Collection|null
     */
    public function getBcSource($lang, $bookSlug, $bookNum, $chapter = null) {
        $bc_cache_key = $lang . "_bc_" . $bookSlug . "_tr";

        if (Cache::has($bc_cache_key)) {
            $bcSource = Cache::get($bc_cache_key);
            $data = json_decode($bcSource, true);
            $bc = ResourceMapper::toResource($data);
        } else {
            $bc = $this->parseBcSource($lang, $bookSlug, $bookNum);
            if (!$bc->isEmpty()) {
                $data = ResourceMapper::fromResource($bc);
                Cache::add($bc_cache_key, json_encode($data), 365 * 24 * 7);
            }
        }

        if ($chapter !== null) {
            $bc = $bc->filter(function($item) use ($chapter) {
                return $item->chapter == $chapter;
            })->first();
        }

        return $bc;
    }

    /**
     * Get Bible Commentaries Articles collection for translation
     * @param string $lang
     * @param null $word Filter by word
     * @return ResourceChapter|Collection|null
     */
    public function getBcArticlesSource($lang, $word = null)
    {
        $resource_cache_key = $lang . "_bca_tr";
        if (Cache::has($resource_cache_key)) {
            $source = Cache::get($resource_cache_key);
            $data = json_decode($source, true);
            $bca = ResourceMapper::toResource($data);
        } else {
            $bca = $this->parseBcArticlesSource($lang);
            if (!empty($book)) {
                $data = ResourceMapper::fromResource($bca);
                Cache::add($resource_cache_key, json_encode($data), 365 * 24 * 7);
            }
        }

        if ($word !== null) {
            $bca = $bca->filter(function($item) use ($word) {
                return $item->chapter == $word;
            })->first();
        }

        return $bca;
    }

    public function getTw($lang, $category, $eventID = null, $chapter = null, $toHtml = false) {
        $resource_cache_key = $lang . "_tw_" . $category . ($toHtml ? "_html" : "");

        if (Cache::has($resource_cache_key)) {
            $source = Cache::get($resource_cache_key);
            $book = json_decode($source, true);
        } else {
            $book = $this->parseTw($lang, $category, $toHtml);
            if (!empty($book)) {
                Cache::add($resource_cache_key, json_encode($book), 365 * 24 * 7);
            }
        }

        if ($chapter !== null && $eventID !== null) {
            $event = $this->eventRepo->get($eventID);
            $group = $event->wordGroups->filter(function($item) use ($chapter) {
                return $item->groupID == $chapter;
            })->first();

            if ($group) {
                $group_words = (array)json_decode($group->words, true);
                $words = array_values(array_filter($book, function ($e) use ($group_words) {
                    return in_array($e["word"], $group_words);
                }));

                return [
                    "words" => $words,
                    "group" => $group_words
                ];
            }
        }

        return $book;
    }

    public function getQaGuide($lang) {
        $qaguide_cache_key = $lang . "_rubric_rubric";

        if (Cache::has($qaguide_cache_key)) {
            $source = Cache::get($qaguide_cache_key);
            $qaGuide = json_decode($source);
        } else {
            $qaGuide = $this->parseQaGuide($lang);
            if ($qaGuide) {
                Cache::add($qaguide_cache_key, json_encode($qaGuide), 365 * 24 * 7);
            }
        }

        return $qaGuide;
    }

    public function getOtherResource($lang, $resource, $bookSlug) {
        $resource_cache_key = $lang . "_" . $resource . "_" . $bookSlug;

        if (Cache::has($resource_cache_key)) {
            $source = Cache::get($resource_cache_key);
            $book = json_decode($source, true);
        } else {
            $book = $this->parseJsonResource($lang, $resource, $bookSlug);
            if ($book) {
                Cache::add($resource_cache_key, json_encode($book), 365 * 24 * 7);
            }
        }

        return $book;
    }

    public function getMedia($lang, $resource, $bookSlug, $chapter) {
        $resourcePath = $this->rootPath . $lang . "_" . $resource;
        $parser = new ManifestParser($resourcePath);
        $parsedMedia = $parser->parseMedia();

        if (!$parsedMedia) return null;

        $mediaData = null;
        foreach ($parsedMedia->getProjects() as $project) {
            if ($project->getIdentifier() == $bookSlug) {
                $mediaData = new MediaData();
                foreach ($project->getMedia() as $media) {
                    if ($media->getIdentifier() == MediaFormat::MP3 ||
                        $media->getIdentifier() == MediaFormat::CUE) {
                        $chapterUrl = str_replace("{chapter}", $chapter, $media->getChapterUrl());
                        $path = new \SplFileInfo($resourcePath);
                        $chapterPath = $resourcePath . "/" . $chapterUrl;
                        $sourcePath = $path->getPathInfo()->getFilename();
                        $projectPath = $path->getFilename();
                        $chapterFile = $sourcePath . "/" . $projectPath . "/" . $chapterUrl;

                        if ($media->getIdentifier() == MediaFormat::CUE) {
                            if (File::exists($chapterPath)) {
                                $cueData = File::get($chapterPath);
                                $mediaData->setCueUrl($chapterFile);
                                $mediaData->setCueData($cueData);
                            }
                        } else {
                            $mediaData->setAudioUrl($chapterFile);
                        }
                    }
                }
                if ($mediaData->getAudioUrl() == null) {
                    $mediaData = null;
                }
            }
        }
        return $mediaData;
    }

    /**
     * Update resource
     * @param string $lang
     * @param string $resource
     * @return bool
     */
    public function refreshResource($lang, $resource) {
        $this->forgetCatalog($this->wacsCatalogPath);
        $this->forgetCatalog($this->dcsCatalogPath);

        $this->forgetResource($lang, $resource);

        switch ($resource) {
            case "rad":
            case "odb":
                return false;
            default:
                if ($this->downloadResource($lang, $resource)) return true;
                break;
        }

        return false;
    }

    public function clearResourceCache($lang, $resource, $book = null) {
        $cacheKey = $lang . "_" . $resource . ($book ? "_" . $book : "");
        Cache::forget($cacheKey);
    }

    public function forgetCatalogs() {
        $this->forgetCatalog($this->wacsCatalogPath);
        $this->forgetCatalog($this->dcsCatalogPath);
    }

    public function getSources() {
        $wacsCatalog = $this->getCatalog($this->wacsCatalogPath, $this->wacsCatalogUrl);
        $wacsSources = $this->getCatalogSources($wacsCatalog);

        $dcsCatalog = $this->getCatalog($this->dcsCatalogPath, $this->dcsCatalogUrl);
        $dcsSources = $this->getCatalogSources($dcsCatalog);

        $ids = array_map(function ($item) {
            return "${item["langID"]}_${item["slug"]}";
        }, $wacsSources);

        $newSources = array_filter($dcsSources, function($item) use($ids) {
            $id = "${item["langID"]}_${item["slug"]}";
            return !in_array($id, $ids);
        });

        return Arrays::append($wacsSources, $newSources);
    }

    public function forgetLanguages() {
        File::delete($this->languagesPath);
    }

    public function getLanguages($url = null) {
        $response = ["success" => false];

        if (!File::exists($this->languagesPath)) {
            $result = $this->downloadLanguages($url);
            if ($result !== true) {
                $response["error"] = $result;
                return $response;
            }
        }

        $langFile = File::get($this->languagesPath);
        $languages = json_decode($langFile);
        $response["success"] = true;
        $response["languages"] = $languages;

        return $response;
    }

    private function getCatalogSources($catalog) {
        $sources = [];

        foreach($catalog->languages as $language) {
            foreach($language->resources as $resource) {
                if(in_array($resource->identifier, [
                    "ta",
                    "obs-tn",
                    "obs-tq",
                    "obs-sq",
                    "obs-sn",
                    "twl",
                    "tqtsv",
                    "t4t"
                ])) continue;

                $tmp = [];
                $tmp["langID"] = $language->identifier;
                $tmp["slug"] = $resource->identifier;
                $tmp["name"] = $resource->title;
                $sources[] = $tmp;
            }
        }

        return $sources;
    }

    /**
     * Remove downloaded resource and cache
     * @param string $lang
     * @param string $resource
     */
    private function forgetResource($lang, $resource) {
        $cacheKey = $lang . "_" . $resource;
        Cache::forget($cacheKey);

        $folderPath = $this->rootPath . $lang . "_" . $resource;
        File::deleteDirectory($folderPath);
    }

    /**
     * Get parsed catalog
     * @param string $path
     * @param string $url
     * @return mixed
     */
    private function getCatalog($path, $url) {
        $filepath = $path;
        if(!File::exists($filepath)) {
            $catalog = $this->downloadCatalog($url);

            if($catalog)
                File::put($filepath, $catalog);
            else
                $catalog = "[]";
        } else {
            $catalog = File::get($filepath);
        }

        return json_decode($catalog);
    }

    /**
     * Update catalog
     * @param string $path
     * @param string $url
     */
    private function refreshCatalog($path, $url) {
        $this->forgetCatalog($path);
        $this->getCatalog($path, $url);
    }

    /**
     * Remove downloaded catalog
     * @param string $path
     */
    private function forgetCatalog($path) {
        File::delete($path);
    }

    /**
     * @throws Exception
     */
    private function downloadLanguages($url = null) {
        $url = $url ?? $this->prefRepo->langNamesUrl()->prefValue;
        $result = true;

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);

            if(curl_errno($ch)) {
                throw new Exception(curl_error($ch));
            }

            curl_close($ch);

            // Try to decode response to check if it is valid json string
            json_decode($response);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Invalid json file");
            }

            File::put($this->languagesPath, $response);
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        return $result;
    }

    /**
     * Download catalog
     * @param string $url
     * @return bool|string
     */
    private function downloadCatalog($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $cat = curl_exec($ch);

        if(curl_errno($ch)) {
            return false;
        }

        curl_close($ch);
        return $cat;
    }

    /**
     * Download resource and extract it.
     * If url is not provided, will try to find in WACS/DCS catalogs
     * @param string $lang
     * @param string $resource
     * @param string $url
     * @return null|string
     */
    private function downloadResource($lang, $resource, $url = null) {
        $folderPath = $this->rootPath . $lang . "_" . $resource;

        if(!File::exists($folderPath)) {
            $result = $this->fetchResource($lang, $resource, $url);

            if ($result) {
                $extension = $result["pathinfo"]["extension"] ?? "json";
                $content = $result["content"] ?? null;

                if ($extension == "zip") {
                    $filePath = $folderPath . ".zip";
                    File::put($filePath, $content);

                    if(File::exists($filePath)) {
                        $zip = new ZipArchive();
                        $zip->open($filePath);
                        $zip->extractTo($this->rootPath);
                        $zip->close();

                        File::delete($filePath);
                    }
                } else {
                    $filePath = $folderPath . "/" . $resource . "." . $extension;
                    if (File::makeDirectory($folderPath, 0755, true)) {
                        File::put($filePath, $content);
                    } else {
                        $folderPath = null;
                    }
                }
            } else {
                $folderPath = null;
            }
        }

        return $folderPath;
    }

    /**
     * Parse .usfm file of scripture and return array
     * @param string $lang
     * @param string $folderPath
     * @return array
     **/
    private function parseScripture($lang, $resource, $bookSlug, $bookNum) {
        $book = [];

        $folderPath = $this->downloadResource($lang, $resource);
        if(!$folderPath) return $book;

        if ($bookSlug && $bookNum) {
            $filePath = $folderPath . "/" . sprintf("%02d", $bookNum) . "-" . strtoupper($bookSlug) . ".usfm";

            if (!File::exists($filePath)) return [];

            $source = File::get($filePath);
            $usfm = UsfmParser::parse($source);

            if ($usfm && isset($usfm["chapters"])) {
                $book["id"] = $usfm["id"] ?? "";
                $book["ide"] = $usfm["ide"] ?? "";
                $book["h"] = $usfm["h"] ?? $bookSlug;
                $book["toc1"] = $usfm["toc1"] ?? $bookSlug;
                $book["toc2"] = $usfm["toc2"] ?? $bookSlug;
                $book["toc3"] = $usfm["toc3"] ?? $bookSlug;
                $book["mt"] = $usfm["mt"] ?? $bookSlug;
                $book["chapters"] = $usfm["chapters"];

                foreach ($usfm["chapters"] as $chap => $chunks) {
                    if (!isset($book[$chap])) {
                        $book[$chap] = ["text" => []];
                    }

                    foreach ($chunks as $chunk) {
                        foreach ($chunk as $v => $text) {
                            $book[$chap]["text"][$v] = $text;
                        }
                    }

                    $arrKeys = array_keys($book[$chap]["text"]);
                    $lastVerse = explode("-", end($arrKeys));
                    $lastVerse = $lastVerse[sizeof($lastVerse)-1];
                    $book[$chap]["totalVerses"] = !empty($book[$chap]["text"]) ? $lastVerse : 0;
                }
            }
        }

        return $book;
    }

    /**
     * Parse .md files of obs and return array
     * @param string $lang
     * @return Collection
     **/
    private function parseObs($lang) {
        $collection = new Collection();
        $folderPath = $this->downloadResource($lang, "obs");
        if(!$folderPath) return $collection;

        $contentPath = $folderPath . "/content";

        $files = File::allFiles($contentPath);
        foreach($files as $file) {
            preg_match("/(\d{2,3}).md$/i", $file, $matches);
            if(!isset($matches[1])) continue;
            $chapter = (int)$matches[1];

            $md = File::get($file);
            $collection->push(ParseResource::parse($md, $chapter, true));
        }

        $collection->sortBy(function($item) {
            return $item->chapter;
        });

        return $collection;
    }

    public function parseMdResource($lang, $resource, $bookSlug, $toHtml = false, $folderPath = null) {
        $book = [];

        if (!$folderPath) {
            $folderPath = $this->downloadResource($lang, $resource);
        }

        if (!$folderPath) return $book;

        // Get book folder
        $dirs = File::directories($folderPath);

        $bookFolderPath = null;
        foreach($dirs as $dir) {
            preg_match("/[1-3a-z]{3}$/i", $dir, $matches);
            if(isset($matches[0]) && strtolower($matches[0]) == $bookSlug) {
                $bookFolderPath = $dir;
                break;
            }
        }

        if($bookFolderPath != null)
            $folderPath = $bookFolderPath;

        if(!$folderPath) return $book;

        $files = File::allFiles($folderPath);
        foreach($files as $file) {
            preg_match("/(\d{2,3}|front)\/(\d{2,3}|intro|index|title).(md|txt)$/i", $file, $matches);

            if(!isset($matches[1]) || !isset($matches[2])) continue;

            if($matches[2] == "index")
                continue;

            if($matches[1] == "front")
                $matches[1] = 0;

            if($matches[2] == "intro" || $matches[2] == "title")
                $matches[2] = 0;

            $chapter = (int)$matches[1];
            $chunk = (int)$matches[2];
            $ext = strtolower($matches[3]);

            if(!isset($book[$chapter]))
                $book[$chapter] = [];
            if(isset($book[$chapter]) && !isset($book[$chapter][$chunk]))
                $book[$chapter][$chunk] = [];

            $content = File::get($file);

            if($ext == "txt") {
                $content = $this->jsonToMarkdown($content);
            }

            if($toHtml) {
                $parsedown = new Parsedown();
                $content = $parsedown->text($content);
                $content = preg_replace("//", "", $content);
            }

            $book[$chapter][$chunk][] = $content;
        }

        ksort($book);
        return array_map(function ($elm) {
            ksort($elm);
            return $elm;
        }, $book);
    }

    private function parseBc($lang, $bookslug, $toHtml = false) {
        $book = [];
        $folderPath = $this->downloadResource($lang, "bc", $this->bcUrl);

        if (!$folderPath) return $book;

        $dirs = File::directories($folderPath);

        $bookFolderPath = null;
        foreach ($dirs as $dir) {
            preg_match("/\d+-([1-3a-z]{3})$/i", $dir, $matches);
            if (isset($matches[1]) && $matches[1] == $bookslug) {
                $bookFolderPath = $dir;
                break;
            }
        }

        if ($bookFolderPath != null) {
            $folderPath = $bookFolderPath;
        }

        if (!$bookFolderPath) return $book;

        $files = File::allFiles($folderPath);
        foreach ($files as $file) {
            preg_match("/(\d{2,3}|intro).(md|txt)$/i", $file, $matches);

            if (!isset($matches[1])) continue;

            if ($matches[1] == "intro") $matches[1] = 0;

            $chapter = (int)$matches[1];
            $ext = strtolower($matches[2]);

            if (!isset($book[$chapter]))
                $book[$chapter] = [];

            $content = File::get($file);

            if ($ext == "txt") {
                $content = $this->jsonToMarkdown($content);
            }

            if ($toHtml) {
                $parsedown = new Parsedown();
                $content = $parsedown->text($content);
                $content = preg_replace("//", "", $content);
                
                // Find BC articles html tags and assign class
                $content = preg_replace("/<a /", "<a class=\"bc-article\" ", $content);
            }

            $book[$chapter] = $content;
        }

        ksort($book);

        return $book;
    }

    private function parseBcArticle($lang, $article, $toHtml = false) {
        $folderPath = $this->downloadResource($lang, "bc", $this->bcUrl);

        if (!$folderPath) return [];

        $articlePath = $folderPath . "/articles/" . $article;

        $content = File::get($articlePath);

        if ($toHtml) {
            $parsedown = new Parsedown();
            $content = $parsedown->text($content);
            $content = preg_replace("//", "", $content);

            // Find BC articles html tags and assign class
            $content = preg_replace("/<a /", "<a class=\"bc-article\" ", $content);
        }

        return $content;
    }

    /**
     * Parse .md files of bc and return array
     * @param string $lang
     * @return Collection
     **/
    private function parseBcSource($lang, $bookSlug, $bookNum) {
        $collection = new Collection();
        $folderPath = $this->downloadResource($lang, "bc", $this->bcUrl);
        if(!$folderPath) return $collection;

        $contentPath = $folderPath . "/" . sprintf("%02d", $bookNum) . "-" . $bookSlug;

        if (!File::exists($contentPath)) return $collection;

        $files = File::allFiles($contentPath);
        foreach($files as $file) {
            preg_match("/(\d{2,3}|intro).md$/i", $file, $matches);
            if(!isset($matches[1])) continue;

            if($matches[1] == "intro")
                $matches[1] = 0;

            $chapter = (int)$matches[1];

            $md = File::get($file);
            $collection->push(ParseResource::parse($md, $chapter));
        }

        $collection->sortBy(function($item) {
            return $item->chapter;
        });

        return $collection;
    }

    public function parseBcArticlesSource($lang, $folderPath = null) {
        $collection = new Collection();

        if(!$folderPath)
            $folderPath = $this->downloadResource($lang, "bc", $this->bcUrl);

        if (!$folderPath) return $collection;

        $folderPath = $folderPath . "/articles";

        $files = File::allFiles($folderPath);

        foreach ($files as $file) {
            preg_match("/\/([\da-z-_]+).md$/i", $file, $matches);

            if(!isset($matches[1])) continue;

            $word_name = $matches[1];
            $md = File::get($file);

            $collection->push(ParseResource::parse($md, $word_name));
        }

        $collection->sortBy(function($item) {
            return $item->chapter;
        });

        return $collection;
    }

    public function parseTw($lang, $bookSlug, $toHtml = true, $folderPath = null) {
        $book = [];

        if(!$folderPath)
            $folderPath = $this->downloadResource($lang, "tw");

        if (!$folderPath) return $book;

        $files = File::allFiles($folderPath);

        $words = [];

        foreach ($files as $file) {
            $filename = $file->getBasename('.' . $file->getExtension());
            if($this->getTwBookByWord($filename) == $bookSlug) {
                preg_match("/\/([\da-z-_]+).(md|txt)$/i", $file, $matches);

                if(!isset($matches[1]) || !isset($matches[2])) continue;

                $word_name = $matches[1];
                $ext = strtolower($matches[2]);

                $word = [];

                $content = File::get($file);

                if($ext == "txt") {
                    $content = $this->jsonToMarkdown($content);
                }

                if($toHtml) {
                    $parsedown = new Parsedown();
                    $content = $parsedown->text($content);
                    $content = preg_replace("//", "", $content);
                }

                $word["text"] = $content;
                $word["word"] = $word_name;
                $words[] = $word;
            }
        }

        usort($words, function($a, $b) {
            return strcmp($a["word"], $b["word"]);
        });

        return $words;
    }

    public function parseTwByBook($lang, $bookSlug, $chapter, $toHtml = false) {
        $folderPath = $this->downloadResource($lang, "tw");

        if (!$folderPath) return [];

        $wordDatabase = $this->getWordsDatabase();
        $filtered = [];

        foreach ($wordDatabase as $word) {
            if($bookSlug == $word[0] && $chapter == $word[1]) {
                if(!isset($filtered[$word[5]]))
                    $filtered[$word[5]] = [];

                if(!isset($filtered[$word[5]]["verses"]))
                    $filtered[$word[5]]["verses"] = [];

                if(!isset($filtered[$word[5]]["term"])) {
                    $filtered[$word[5]]["term"] = $word[3];
                    $filtered[$word[5]]["name"] = $word[3];
                }

                $filtered[$word[5]]["verses"][] = (int)$word[2];
            }
        }

        $files = File::allFiles($folderPath);

        foreach ($filtered as $key => &$word) {
            $word["range"] = $this->getTwRange($word["verses"]);

            foreach ($files as $file) {
                if(preg_match("/".$key.".md$/i", $file)) {
                    $content = File::get($file);

                    if ($toHtml) {
                        $parsedown = new Parsedown();
                        $dom = new DOMDocument();

                        $content = $parsedown->text($this->removeUtf8Bom($content));
                        $content = preg_replace("//", "", $content);
                        $dom->loadHTML('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . $content);

                        $headers = $dom->getElementsByTagName("h1");
                        if(!empty($headers))
                        {
                            $word["name"] = $headers[0]->nodeValue;
                        }
                    }
                    $word["text"] = $content;
                }
            }
        }

        return $filtered;
    }

    public function getApiUrl($type) {
        switch ($type) {
            case "wacs":
                return $this->wacsApiUrl;
            default:
                return $this->dcsApiUrl;
        }
    }

    private function parseQaGuide($lang) {
        $qaGuide = [];
        $url = $this->qaGuideUrl . $lang;

        $folderPath = $this->downloadResource($lang, "rubric", $url);
        if (!$folderPath) return $qaGuide;

        $filePath = $folderPath . "/rubric.json";

        $source = File::get($filePath);

        return json_decode($source);

    }

    /**
     * Parse odb|rad book sources from local file
     * @param $lang
     * @param $resource
     * @param $bookSlug
     * @return array
     */
    private function parseJsonResource($lang, $resource, $bookSlug) {
        $book = [];

        $filePath = $this->rootPath . $lang . "_".$resource . "/" . strtoupper($bookSlug) . ".json";

        if(File::exists($filePath)) {
            $sourceData = File::get($filePath);
            $source = (array)json_decode($sourceData, true);
            $chapters = [];

            if(!empty($source) && isset($source["root"])) {
                foreach ($source["root"] as $i => $chapter) {
                    $chapters[$i+1] = [];
                    $k = 1;
                    foreach ($chapter as $section) {
                        if(!is_array($section)) {
                            $chapters[$i+1][$k] = $section;
                            $k++;
                        } else {
                            foreach ($section as $p) {
                                $chapters[$i+1][$k] = $p;
                                $k++;
                            }
                        }
                    }
                }
                $book["chapters"] = $chapters;
            }
        }

        return $book;
    }

    /**
     * Fetch resource from remote url
     * If url is not provided, will try to find in WACS/DCS catalogs
     * @param string $lang
     * @param string $resource
     * @param string $url
     * @return array|null
     */
    private function fetchResource($lang, $resource, $url = null) {
        if (!$url) {
            // Find resource on WACS first, if not there find in DCS
            $catalog = $this->getCatalog($this->wacsCatalogPath, $this->wacsCatalogUrl);
            $url = $this->getResourceUrl($catalog, $lang, $resource);

            if ($url == "") {
                $catalog = $this->getCatalog($this->dcsCatalogPath, $this->dcsCatalogUrl);
                $url = $this->getResourceUrl($catalog, $lang, $resource);
            }
        }

        if($url == "") return null;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        $resource = curl_exec($ch);

        if(curl_errno($ch)) return null;

        curl_close($ch);

        return [
            "content" => $resource,
            "pathinfo" => pathinfo($url)
        ];
    }

    private function getResourceUrl($catalog, $lang, $res) {
        $url = "";

        foreach($catalog->languages as $language) {
            if($language->identifier == $lang) {
                foreach($language->resources as $resource) {
                    if($resource->identifier == $res) {
                        if (isset($resource->formats)) {
                            foreach ($resource->formats as $format) {
                                if (Str::endsWith($format->url, ".zip")) {
                                    $url = $format->url;
                                    break 3;
                                }
                            }
                        }

                        if (isset($resource->projects)) {
                            foreach ($resource->projects as $project) {
                                foreach($project->formats as $format) {
                                    if (Str::endsWith($format->url, ".zip")) {
                                        $url = $format->url;
                                        break 4;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $url;
    }

    /**
     * Get tW database
     * @return SplFileObject
     */
    private function getWordsDatabase() {
        // Parses csv file and returns an array of words
        // Each word array has 6 elements
        // 0 - book code (gen)
        // 1 - chapter
        // 2 - verse
        // 3 - term (ex. Heavens)
        // 4 - category (ex. kt, other, names)
        // 5 - reference name (ex. heaven)

        if ($this->wordsDatabase == null) {
            $words = new SplFileObject($this->rootPath . "words_db.csv");
            $words->setFlags(SplFileObject::READ_CSV);
            $this->wordsDatabase = $words;
        }

        return $this->wordsDatabase;
    }

    /**
     * Get tW dictionary
     * @return SplFileObject
     */
    private function getWordsDictionary() {
        // Parses csv file and returns an array of words
        // Each word array has 2 elements
        // 0 - reference name (ex. heaven)
        // 1 - category (ex. kt, other, names)

        if ($this->wordsDictionary == null) {
            $words = new SplFileObject($this->rootPath . "words_dict.csv");
            $words->setFlags(SplFileObject::READ_CSV);
            $this->wordsDictionary = $words;
        }

        return $this->wordsDictionary;
    }

    private function getTwBookByWord($word) {
        $words = $this->getWordsDictionary();
        foreach ($words as $w) {
            if($w[0] == $word) {
                return $w[1];
            }
        }

        return "unknown";
    }

    private function jsonToMarkdown($json) {
        $data = (array)json_decode($json);
        $md = "";
        foreach ($data as $item) {
            $md .= "# ".$item->title."  \n\n";
            $md .= $item->body."  \n\n";
        }
        return $md;
    }

    private function getTwRange($verses) {
        if(count($verses) == 1)
            return [$verses[0]];

        $range = [];
        for ($i = 0; $i < count($verses); $i++) {
            $rStart = $verses[$i];
            $rEnd = $rStart;

            if(!isset($verses[$i])) {
                $range[] = $rStart;
                continue;
            }

            while (isset($verses[$i + 1]) && ($verses[$i + 1] - $verses[$i]) == 1) {
                $rEnd = $verses[$i + 1];
                $i++;
            }
            $range[] = $rStart == $rEnd ? $rStart : $rStart . '-' . $rEnd;
        }
        return $range;
    }

    private function removeUtf8Bom($text) {
        $bom = pack('H*','EFBBBF');
        return preg_replace("/^$bom/", '', $text);
    }
}