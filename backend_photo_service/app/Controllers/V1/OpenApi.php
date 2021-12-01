<?php namespace App\Controllers\V1;

use CodeIgniter\RESTful\ResourceController;

/**
 * 公開 API 檔案
 *
 * @SWG\Swagger(
 *      @OA\Info(
 *          title="Photo Service API DOC",
 *          version="1.3.0"
 *      )
 * )
 */
class OpenApi extends ResourceController
{
    
    protected $format    = 'json';

    public function index(){
        $sep = DIRECTORY_SEPARATOR;
        $swagger = \OpenApi\scan(APPPATH."Controllers{$sep}V1");
        return $this->respond($swagger,200);
    }

}
