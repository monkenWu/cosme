<?php namespace App\Controllers\V1;

use CodeIgniter\RESTful\ResourceController;

/**
 *  API 說明書
 *
 * @SWG\Swagger(
 *      @OA\Info(
 *          title="Cosme Gateway API V1 DOC",
 *          version="1.4.0"
 *      )
 * )
 */
class OpenApi extends ResourceController
{
    
    protected $format    = 'json';

    public function index(){
        $session = \Config\Services::session();
        if(!$session->has("docVerify")){
			return 	$this->respond(["No permission
            "],401);
        }
        $sep = DIRECTORY_SEPARATOR;
        $swagger = \OpenApi\scan(APPPATH."Controllers{$sep}V1");
        return $this->respond($swagger,200);
    }

}
