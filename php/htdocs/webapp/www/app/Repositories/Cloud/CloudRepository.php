<?php

namespace App\Repositories\Cloud;

use Helpers\Git;
use Helpers\Session;
use Helpers\Tools;
use File;

class CloudRepository implements ICloudRepository {
    private $wacsServer = "https://content.bibletranslationtools.org/api/v1";
    private $wacsHostname = "content.bibletranslationtools.org";
    private $door43Server = "https://git.door43.org/api/v1";
    private $door43Hostname = "git.door43.org";

    private $server;
    private $serverUrl;
    private $authUrl;
    private $gitServerUrl;
    private $username;
    private $accessToken;

    public function initialize($server, $getUser = true) {
        $this->server = $server;
        $this->serverUrl = $server == "wacs" ? $this->wacsServer : $this->door43Server;
        $this->gitServerUrl = $server == "wacs" ? $this->wacsHostname : $this->door43Hostname;
        $this->authUrl = $server == "wacs" ? "https://$this->wacsHostname" : "https://$this->door43Hostname";
        $this->accessToken = $this->isAuthenticated() ? Session::get($server)["access_token"] : "";
        $this->username = $getUser && $this->isAuthenticated() ? $this->getUsername($server) : "";
    }

    public function isAuthenticated() {
        return isset($this->server) && Session::exists($this->server);
    }

    public function prepareAuthRequestUrl() {
        if (!isset($this->server)) {
            return "Cloud is not initialized";
        }

        $state = uniqid();
        Session::set("oauth_state", $state);

        $clientID = $_ENV["WACS_CLIENT_ID"];
        if ($this->server == "dcs") {
            $clientID = $_ENV["DCS_CLIENT_ID"];
        }

        return "$this->authUrl/login/oauth/authorize?client_id=$clientID"
            ."&redirect_uri={$_ENV["APP_URL"]}members/oauth/$this->server"
            ."&response_type=code&state=$state";
    }

    public function getStateHash() {
        return Session::get("oauth_state");
    }

    public function requestAccessToken($code) {
        if (!isset($this->server)) {
            $response = new \stdClass();
            $response->error = true;
            $response->error_description = "Cloud is not initialized";
            return $response;
        }

        $clientID = $_ENV["WACS_CLIENT_ID"];
        $clientSecret = $_ENV["WACS_CLIENT_SECRET"];
        if ($this->server == "dcs") {
            $clientID = $_ENV["DCS_CLIENT_ID"];
            $clientSecret = $_ENV["DCS_CLIENT_SECRET"];
        }

        $post = [
            "client_id" => $clientID,
            "client_secret" => $clientSecret,
            "code" => $code,
            "grant_type" => "authorization_code",
            "redirect_uri" => "{$_ENV["APP_URL"]}members/oauth/$this->server",
        ];
        $response = $this->authRequest("/login/oauth/access_token", $post);
        $json = json_decode($response);

        if (!isset($json->error)) {
            Session::set($this->server, [
                "access_token" => $json->access_token,
                "token_type" => $json->token_type,
                "expires_in" => $json->expires_in,
                "refresh_token" => $json->refresh_token,
            ]);
        }

        return $json;
    }

    public function uploadRepo($repoName, $projectFiles) {
        $result = new \stdClass();
        $result->success = false;

        if($repoName != null && !empty($projectFiles)) {
            if (!isset($this->server)) {
                $result->message = "Cloud is not initialized";
                return $result;
            }

            $repo = $this->getRepo($repoName);

            if(empty($repo) || !isset($repo->clone_url)) {
                $repo = $this->createEmptyRepo($repoName);
            }

            $uniqid = uniqid();
            $repoPath = "/tmp/{$repoName}_{$uniqid}";

            $gitRepo = Git::clone_remote($repoPath, $repo->clone_url);
            $gitRepo->remove_remote();
            $gitRepo->add_remote("https://{$this->username}:{$this->accessToken}@{$this->gitServerUrl}/{$repo->full_name}.git");
            $gitRepo->set_username($this->username);
            $gitRepo->set_email($this->username); // Not mandatory

            foreach ($projectFiles as $projectFile) {
                File::putWithDirs($repoPath . "/" . $projectFile->relPath(), $projectFile->content());
            }

            $gitRepo->add();
            $gitRepo->commit("Updated");
            $gitRepo->push(branch: $repo->default_branch);

            $result->success = true;
            $result->repo = $repo;

            File::deleteDirectory($repoPath);
        } else {
            $result->message = __("not_implemented");
        }

        return $result;
    }

    private function request($path, $post = []) {
        $url = $this->serverUrl . $path . "?access_token=" . $this->accessToken;
        return Tools::http_request($url, $post);
    }

    private function authRequest($path, $post) {
        $url = $this->authUrl . $path;
        return Tools::http_request($url, $post);
    }

    private function getUsername($server) {
        $data = $this->request("/user");
        $user = json_decode($data);
        return !isset($user->error) ? $user->login : "unknown";
    }

    private function getRepo($repoName) {
        $data = $this->request("/repos/{$this->username}/{$repoName}");
        return json_decode($data);
    }

    private function createEmptyRepo($repoName) {
        $data = $this->request("/user/repos", ["name" => $repoName]);
        return json_decode($data);
    }
}