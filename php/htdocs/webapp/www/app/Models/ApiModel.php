<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 6/5/18
 * Time: 2:15 PM
 */

namespace App\Models;

use Cache;
use Database\Model;
use DB;
use File;
use Helpers\Manifest\ManifestParser;
use Helpers\Markdownify\Converter;
use Helpers\Tools;
use Helpers\ZipStream\Exception;
use ZipArchive;


class ApiModel extends Model
{
    public  function __construct()
    {
        parent::__construct();
    }

    private function downloadPredefinedChunks($book, $lang = "en", $project = "ulb")
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.unfoldingword.org/ts/txt/2/$book/$lang/$project/chunks.json");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        $response = curl_exec($ch);

        if(curl_errno($ch))
        {
            return "error: " . curl_error($ch);
        }

        curl_close($ch);

        return $response;
    }

    public function getPredefinedChunks($book, $lang = "en", $project = "ulb") {
        try {
            $json = $this->downloadPredefinedChunks($book, $lang, $project);
            $chunks = $json ? (array)json_decode($json, true) : [];

            if($chunks == null) {
                $json = $this->downloadPredefinedChunks($book);
                $chunks = $json ? (array)json_decode($json, true) : [];
            }

            $book = [];

            foreach ($chunks as $chunk) {
                $id = $chunk["id"];
                $chapter = (int)preg_replace("/-[0-9]+$/", "", $id);

                if(!array_key_exists($chapter, $book)) {
                    $book[$chapter] = [];
                }

                $range = range($chunk["firstvs"], $chunk["lastvs"]);
                $book[$chapter][] = array_fill_keys(array_values($range), '');
            }

            return $book;
        } catch (\Exception $e) {
            return [];
        }
    }


    /**
     * Compiles all the chunks into a single usfm file
     * @param $folderpath
     * @return null
     */
    public function compileUSFMProject($folderpath) {
        $usfm = null;

        if(File::exists($folderpath)) {
            $filepath = $folderpath . "/tmpfile";

            $files = File::files($folderpath);
            foreach ($files as $file) {
                if(preg_match("/\.usfm$/", $file)) {
                    // If repository contains only one usfm with entire book
                    $usfm = File::get($file);
                    File::deleteDirectory($folderpath);
                    return $usfm;
                }
            }

            // Iterate through all the chapters and chunks
            $dirs = File::directories($folderpath, "<0");
            sort($dirs);
            foreach($dirs as $dir) {
                if(preg_match("/\d{2,3}$/", $dir, $chapters)) {
                    $chapter = (integer)$chapters[0];

                    $files = File::allFiles($dir);
                    sort($files);
                    foreach($files as $file) {
                        if(preg_match("/\d{2,3}.txt$/", $file, $chunks)) {
                            $chunk = (integer)$chunks[0];
                            $text = File::get($file);
                            if($chunk == 1) {
                                // Fix usfm with missed chapter number tags
                                if(!preg_match("/^\\\\c/", $text)) {
                                    $text = "\c ".$chapter." \n\n".$text;
                                }
                            }

                            File::append($filepath, "\n\s5\n" . $text);
                        }
                    }
                } elseif (preg_match("/front$/", $dir)) {
                    $files = File::allFiles($dir);

                    foreach ($files as $file) {
                        if (preg_match("/title.txt$/", $file)) {
                            $text = File::get($file);

                            $headerUsfm = "\\h ".$text."\n";
                            $headerUsfm .= "\\toc1 ".$text."\n";
                            $headerUsfm .= "\\toc2 ".$text."\n";
                            $headerUsfm .= "\\mt ".$text."\n";

                            File::prepend($filepath, $headerUsfm."\n");
                        }
                    }
                }
            }

            if(File::exists($filepath)) {
                $usfm = File::get($filepath);
                File::deleteDirectory($folderpath);
            }
        }

        return $usfm;
    }



    /**
     * Clones repository into temporary directory
     * @param $url
     * @return string Path to directory
     */
    public function processRepoUrl($url)
    {
        $folderpath = "/tmp/".uniqid();

        shell_exec("/usr/bin/git clone ". $url ." ".$folderpath." 2>&1");

        return $folderpath;
    }


    /**
     * Exctracts .zip (.tstudio file as well) file into temporary directory
     * @param $file
     * @return string Path to directory
     */
    public function processZipFile($file)
    {
        $folderpath = "/tmp/".uniqid();

        $zip = new ZipArchive();
        $zip->open($file["tmp_name"]);
        $zip->extractTo($folderpath);
        $zip->close();
        $dirs = File::directories($folderpath);

        foreach ($dirs as $dir) {
            if(File::isDirectory($dir))
            {
                $folderpath = $dir;
                break;
            }
        }

        return $folderpath;
    }


    /**
     * Exctracts .zip file into temporary directory
     * @param $file
     * @return string Path to directory
     */
    public function processSourceZipFile($file)
    {
        $folderpath = "/tmp/".uniqid();

        $zip = new ZipArchive();
        $zip->open($file);
        $zip->extractTo($folderpath);
        $zip->close();

        return $folderpath;
    }


    public function processResource($path, $lang, $slug) {
        try {
            $target = "../app/Templates/Default/Assets/source/" . $lang . "_" . $slug;

            if(!File::isDirectory($target)) {
                File::makeDirectory($target, 0755, true);
            }

            $this->mergeMediaManifests($path, $target);

            $resourceDir = $this->getManifestParentDir($path);
            File::copyDirectory($resourceDir, $target);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    private function getManifestParentDir($dir) {
        $files = File::allFiles($dir);
        foreach ($files as $file) {
            if ($file->getFilename() == "manifest.yaml") {
                return $file->getPathInfo();
            }
        }
        return $dir;
    }

    private function mergeMediaManifests($source, $target) {
        $targetParser = new ManifestParser($target);
        $targetMedia = $targetParser->parseMedia();

        if ($targetMedia) {
            $sourceParser = new ManifestParser($source);
            $sourceMedia = $sourceParser->parseMedia();
            if ($sourceMedia) {
                $projects = $sourceMedia->getProjects();
                foreach ($targetMedia->getProjects() as $project) {
                    $existent = array_filter($projects, function ($item) use ($project) {
                        return $item->getIdentifier() == $project->getIdentifier();
                    });
                    if (sizeof($existent) == 0) {
                        $projects[] = $project;
                    }
                }
                $sourceMedia->setProjects($projects);
                $sourceParser->writeMedia();
            }
        }
    }

    public function getNotesChunks($notes)
    {
        $chunks = array_keys($notes["notes"]);
        $totalVerses = isset($notes["totalVerses"]) ? $notes["totalVerses"] : 0;
        $arr = [];
        $tmp = [];

        foreach ($chunks as $key => $chunk) {
            if(isset($chunks[$key + 1]))
            {
                for($i = $chunk; $i < $chunks[$key + 1]; $i++)
                {
                    $tmp[] = $i;
                }

                $arr[] = $tmp;
                $tmp = [];
            }
            else
            {
                if($chunk <= $totalVerses)
                {
                    for($i = $chunk; $i <= $totalVerses; $i++)
                    {
                        $tmp[] = $i;
                    }

                    $arr[] = $tmp;
                    $tmp = [];
                }
            }
        }

        return $arr;
    }

    public function getNotesVerses($notes)
    {
        $tnVerses = [];
        $fv = 1;
        $i = 0;
        foreach (array_keys($notes["notes"]) as $key) {
            $i++;
            if($key == 0)
            {
                $tnVerses[] = $key;
                continue;
            }

            if(($key - $fv) >= 1)
            {
                $tnVerses[$fv] = $fv != ($key - 1) ? $fv . "-" . ($key - 1) : $fv;
                $fv = $key;

                if($i == sizeof($notes["notes"]))
                    $tnVerses[$fv] = $fv != $notes["totalVerses"] ? $fv . "-" . $notes["totalVerses"] : $fv;
                continue;
            }
        }

        return $tnVerses;
    }

    public function getQuestionsChunks($questions)
    {
        $chunks = array_keys($questions["questions"]);

        $chunks = array_map(function ($elm) {
            return [$elm];
        }, $chunks);

        return $chunks;
    }

    public function getObsChunks($chapter)
    {
        $chunks = $chapter->chunks->map(function($item, $key) {
            return [$key];
        });

        return $chunks->toArray();
    }

    public function getResourceChunks($chapter)
    {
        $chunks = $chapter->chunks->map(function($item, $key) {
            return [$key];
        });

        return $chunks->toArray();
    }


    public function testChunks($chunks, $totalVerses)
    {
        if(!is_array($chunks) || empty($chunks)) return false;

        $lastVerse = 0;

        foreach ($chunks as $chunk) {
            if(!is_array($chunk) || empty($chunk)) return false;

            // Test if first verse is 1
            if($lastVerse == 0 && $chunk[0] != 1) return false;

            // Test if all verses are in right order
            foreach ($chunk as $verse) {
                if((integer)$verse > ($lastVerse+1)) return false;
                $lastVerse++;
            }
        }

        // Test if all verses added to chunks
        if($lastVerse != $totalVerses) return false;

        return true;
    }

    public function testChunkNotes($chunks, $notes)
    {
        if(!is_array($chunks))
            return false;

        if(sizeof($chunks) != sizeof($notes))
            return false;

        $converter = new Converter;
        $converter->setKeepHTML(false);
        foreach ($chunks as $key => $chunk) {
            if(trim($chunk) == "")
                return false;

            $md = $converter->parseString($chunk);
            if(trim($md) == "")
                return false;

            $chunks[$key] = $md;
        }

        return $chunks;
    }

    public function testChunkQuestions($chunks, $questions)
    {
        if(!is_array($chunks))
            return false;

        if(sizeof($questions) != sizeof($chunks))
            return false;

        $converter = new Converter;
        $converter->setKeepHTML(false);
        foreach ($chunks as $key => $chunk) {
            if(trim($chunk) == "")
                return false;

            $md = $converter->parseString($chunk);
            if(trim($md) == "")
                return false;

            $chunks[$key] = $md;
        }

        return $chunks;
    }

    public function testChunkWords($chunks, $words)
    {
        if(!is_array($chunks))
            return false;

        if(sizeof($words) != sizeof($chunks))
            return false;

        $converter = new Converter;
        $converter->setKeepHTML(false);
        foreach ($chunks as $key => $chunk) {
            if(trim($chunk) == "")
                return false;

            $md = $converter->parseString($chunk);
            if(trim($md) == "")
                return false;

            $chunks[$key] = $md;
        }

        return $chunks;
    }

    public function testChunkRadio($postChunks, $radioChunks)
    {
        if(!is_array($postChunks))
            return false;

        if(sizeof($radioChunks) != sizeof($postChunks))
            return false;

        foreach ($postChunks as $key => $chunk) {
            if(Tools::has_empty($chunk))
                return false;

            $postChunks[$key] = $chunk;
        }

        return $postChunks;
    }

    public function testChunkMd($chunks, $md)
    {
        if(!is_array($chunks))
            return false;

        if($md->count() != sizeof($chunks))
            return false;

        foreach ($chunks as $key => $chunk) {
            if(trim($chunk["text"]) == "")
                return false;

            $chunks[$key]["text"] = $chunk["text"];
            $chunks[$key]["meta"] = $chunk["meta"];
            $chunks[$key]["type"] = $chunk["type"];
        }

        return $chunks;
    }

    public function clearAllCache() {
        Cache::flush();
    }
}