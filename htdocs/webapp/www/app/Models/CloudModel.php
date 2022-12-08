<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 6/5/18
 * Time: 2:15 PM
 */

namespace App\Models;

use Database\Model;
use DB;
use File;
use Helpers\Git;
use Helpers\ProjectFile;


class CloudModel extends Model
{
    private $wacsServer = "https://wacs.bibletranslationtools.org/api/v1";
    private $wacsGitServer = "content.bibletranslationtools.org";
    private $door43Server = "https://git.door43.org/api/v1";
    private $door43GitServer = "git.door43.org";
    private $vmastToken = "vmast";
    private $serverUrl = "";
    private $gitServer = "";
    private $username = "";
    private $password = "";
    private $token = "";
    private $otp = ""; // Two factor authentication code

    public  function __construct($server, $username, $password, $otp, $token = "")
    {
        parent::__construct();

        $this->serverUrl = $server == "wacs" ? $this->wacsServer : $this->door43Server;
        $this->gitServer = $server == "wacs" ? $this->wacsGitServer : $this->door43GitServer;
        $this->username = $username;
        $this->password = $password;
        $this->token = $token;
        $this->otp = $otp;
    }

    public function getAccessTokens() {
        $ch = $this->initCurl("/users/$this->username/tokens");
        $data = curl_exec($ch);

        if(curl_errno($ch))
        {
            return "error: " . curl_error($ch);
        }

        curl_close($ch);

        return $data;
    }

    public function deleteAccessToken($id) {
        $ch = $this->initCurl("/users/$this->username/tokens/$id");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

        $data = curl_exec($ch);

        if(curl_errno($ch))
        {
            return "error: " . curl_error($ch);
        }

        curl_close($ch);

        return $data;
    }

    public function createAccessToken() {
        $ch = $this->initCurl("/users/$this->username/tokens", ["name" => $this->vmastToken]);
        $data = curl_exec($ch);

        if(curl_errno($ch))
        {
            return "error: " . curl_error($ch);
        }

        curl_close($ch);

        return $data;
    }

    public function getVmastAccessToken($data)
    {
        if(!is_array($data))
        {
            $data = json_decode($data, true);
        }

        if(is_array($data))
        {
            foreach ($data as $key => $token)
            {
                if(is_array($token))
                {
                    if($token["name"] == $this->vmastToken)
                    {
                        return $token;
                    }
                }
                else
                {
                    if($key == "name" && $token == $this->vmastToken)
                    {
                        return $data;
                    }
                }

            }
        }

        return [];
    }

    private function initCurl($path, $postData = [], $auth = true)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->serverUrl . $path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        if($auth)
        {
            curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-Gitea-OTP: {$this->otp}"]);
        }

        if(!empty($postData))
        {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }

        return $ch;
    }

    /**
     * Uload repository
     * @param string $repoName
     * @param ProjectFile[] $projectFiles
     * @return array
     */
    public function uploadRepo($repoName, $projectFiles)
    {
        $result = ["success" => false, "message" => ""];

        if($repoName != null && !empty($projectFiles))
        {
            $repo = json_decode($this->getRepo($repoName), true);

            if(empty($repo) || !isset($repo["clone_url"]))
            {
                $repo = json_decode($this->createEmptyRepo($repoName), true);
            }

            $uniqid = uniqid();
            $repoPath = "/tmp/{$repoName}_{$uniqid}";

            $gitRepo = Git::clone_remote($repoPath, $repo["clone_url"]);
            $gitRepo->remove_remote();
            $gitRepo->add_remote("https://{$this->username}:{$this->token}@{$this->gitServer}/{$repo["full_name"]}.git");
            $gitRepo->set_username($this->username);
            $gitRepo->set_email($this->username); // Not mandatory

            foreach ($projectFiles as $projectFile)
            {
                File::putWithDirs($repoPath . "/" . $projectFile->relPath(), $projectFile->content());
            }

            $gitRepo->add();
            $gitRepo->commit("Updated");
            $gitRepo->push();

            $result["success"] = true;
            $result["message"] = $repo;

            /*try {
                $gitRepo = Git::clone_remote($repoPath, $repo["clone_url"]);
                $gitRepo->remove_remote();
                $gitRepo->add_remote("https://{$this->username}:{$this->token}@{$this->gitServer}/{$repo["full_name"]}.git");
                $gitRepo->set_username($this->username);
                $gitRepo->set_email($this->username); // Not mandatory

                foreach ($projectFiles as $projectFile)
                {
                    File::putWithDirs($repoPath . "/" . $projectFile->relPath(), $projectFile->content());
                }

                $gitRepo->add();
                $gitRepo->commit("Updated");
                $gitRepo->push();

                $result["success"] = true;
                $result["message"] = $repo;
            } catch (\Exception $e) {
                $result["message"] = $e->getMessage();
            }*/

            File::deleteDirectory($repoPath);
        }
        else
        {
            $result["message"] = __("not_implemented");
        }

        return $result;
    }

    private function getRepo($repoName)
    {
        $ch = $this->initCurl("/repos/{$this->username}/{$repoName}");
        $data = curl_exec($ch);

        if(curl_errno($ch))
        {
            return "error: " . curl_error($ch);
        }

        curl_close($ch);

        return $data;
    }

    private function createEmptyRepo($repoName)
    {
        $ch = $this->initCurl("/user/repos?token={$this->token}", ["name" => $repoName]);
        $data = curl_exec($ch);

        if(curl_errno($ch))
        {
            return "error: " . curl_error($ch);
        }

        curl_close($ch);

        return $data;
    }
}