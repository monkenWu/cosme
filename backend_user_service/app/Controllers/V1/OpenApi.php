<?php namespace App\Controllers\V1;

use CodeIgniter\RESTful\ResourceController;

/**
 * 公開 API 檔案
 *
 * @SWG\Swagger(
 *      @OA\Info(
 *          title="Cosme User Service V1 API DOC",
 *          version="1.2.0"
 *      )
 * )
 */
class OpenApi extends ResourceController
{
    
    protected $format    = 'json';

    public function index(){
        $sep = DIRECTORY_SEPARATOR;
        if (strtoupper(substr(PHP_OS, 0, 3)) === "WIN")  $tag = "\\";
        $swagger = \OpenApi\scan(APPPATH."Controllers{$sep}V1");
        return $this->respond($swagger,200);
    }

}
