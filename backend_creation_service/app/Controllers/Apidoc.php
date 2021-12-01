<?php namespace App\Controllers;

class Apidoc extends BaseController
{
    public function index(){
        if (!env("OpenApiDoc",false)){
            throw new \CodeIgniter\Exceptions\PageNotFoundException("apidoc");
        }
        $config = new \Config\App();
        $stableVersion = $config->stableVersion;
        return view("apidocView",[
            "title" => "Service API DOC",
            "version" => $stableVersion
        ]);
    }
}
