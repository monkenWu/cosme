<?php namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

class Apidoc extends BaseController
{
    use ResponseTrait;
    public function _remap(String $service="")
    {
        if (!env("OpenApiDoc",false)){
            throw new \CodeIgniter\Exceptions\PageNotFoundException("apidoc");
        }
        $config = new \Config\App();
        $stableVersion = $config->stableVersion;

        //判斷是否為驗證需求
        if($service == "verifydoc"){
            if($this->doVerify()){
                return $this->respond("OK",200);
            }else{
                return $this->respond("Error",401);
            }
        }
        //判斷是否驗證通過
        if(!$this->verify())return view("docVerifyView");
        //回傳說明書內容
        return view("apidocView",[
            "title" => "Cosme User Service API 說明書",
            "version" => $stableVersion
        ]);
    }

    private function verify(){
        $session = \Config\Services::session();
        if($session->has("docVerify")){
			return true;	
        }
        return false;
    }

    private function doVerify(){
        $password = $this->request->getJSON(true)["password"];
        if($password == env("OpenApiDocPassword")){
            $session = \Config\Services::session();
            $session->set("docVerify","OK");
            return true;
        }
        return false;
    }
}
