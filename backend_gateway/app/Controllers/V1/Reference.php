<?php

namespace App\Controllers\V1;

use CodeIgniter\RESTful\ResourceController;

class Reference extends ResourceController
{

    protected $format    = 'json';

    /**
     * 圖片微服務實體
     *
     * @var App\Libraries\Microservice\ServiceEntity
     */
    private $photoService;

    public function __construct()
    {
        $gateway = \config\Services::gateway();
        $this->photoService = $gateway->getServiceEntity("photo_service");
    }

    /**
     * (公開)取得單張裝容照
     *
     * @return void
     *
     * @OA\Get(
     *     path="/api/v1/reference/{imgKey}",
     *     tags = {"reference","public"},
     *     description="依 imgKey 取得裝容圖像",
     *     @OA\Parameter(
     *         name="imgKey",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="isFull",
     *         in="query",
     *         description="是否為大圖",
     *         required=true,
     *         @OA\Schema(
     *             type="boolean"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="請求成功",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="伺服器文字訊息"),
     *              @OA\Property(property="data", type="string",description="base64 code")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="伺服器回傳錯誤",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *             @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="photoID 不存在於資料庫",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *             @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     )
     * )
     **/
    public function show($imgKey = null)
    {
        $isFull = $this->request->getGet("isFull");
        $showResult = $this->photoService->action(
            "GET",
            "/api/v1/reference/{$imgKey}",
            [
                'query' => [ 'isFull' => $isFull]
            ]
        );
        $showResultData = json_decode($showResult->getBody(),true);
        return $this->respond($showResultData,$showResult->getStatusCode());
    }


}
