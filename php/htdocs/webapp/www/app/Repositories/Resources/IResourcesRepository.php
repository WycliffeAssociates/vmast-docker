<?php


namespace App\Repositories\Resources;


interface IResourcesRepository
{
    public function getScripture($lang, $resource, $bookSlug, $bookNum, $chapter = null);

    public function getMdResource($lang, $resource, $bookSlug, $chapter = null, $toHtml = false);

    public function parseMdResource($lang, $resource, $bookSlug, $toHtml = false, $folderPath = null);

    public function getTw($lang, $category, $eventID = null, $chapter = null, $toHtml = false);

    public function getBc($lang, $bookSlug, $chapter = null, $toHtml = false);

    public function getBcArticle($lang, $article, $toHtml = false);

    public function getBcSource($lang, $bookSlug, $bookNum, $chapter = null);

    public function getBcArticlesSource($lang, $word = null);

    public function getQaGuide($lang);

    public function getOtherResource($lang, $resource, $bookSlug);

    public function parseTw($lang, $bookSlug, $toHtml = true, $folderPath = null);

    public function parseTwByBook($lang, $bookSlug, $chapter, $toHtml = false);

    public function getObs($lang, $chapter = null);

    public function getMedia($lang, $resource, $bookSlug, $chapter);

    public function refreshResource($lang, $resource);

    public function clearResourceCache($lang, $resource, $book = null);

    public function forgetCatalogs();

    public function getSources();

    public function forgetLanguages();

    public function getLanguages($url = null);

    public function getApiUrl($type);

}