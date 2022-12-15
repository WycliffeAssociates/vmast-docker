<?php

namespace App\Repositories\Cloud;

interface ICloudRepository
{
    public function initialize($server);
    public function isAuthenticated();
    public function uploadRepo($repoName, $projectFiles);
    public function prepareAuthRequestUrl();
    public function getStateHash();
    public function requestAccessToken($code);
}