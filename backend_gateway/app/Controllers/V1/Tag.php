<?php

namespace App\Controllers\V1;

use CodeIgniter\RESTful\ResourceController;

class Tag extends ResourceController
{

    protected $format    = 'json';

    /**
     * 文章微服務實體
     *
     * @var App\Libraries\Microservice\ServiceEntity
     */
    private $creationService;

    public function __construct()
    {
        $gateway = \config\Services::gateway();
        $this->creationService = $gateway->getServiceEntity("creation_service");
    }

    /**
     * 標籤清單
     *
     * @return void
     *
     * @OA\Get(
     *     path="/api/v1/tag",
     *     tags = {"creation","public"},
     *     description="傳入",
     *      @OA\Parameter(
     *         name="like",
     *         in="query",
     *         description="搜索內容",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="size",
     *         in="query",
     *         description="回傳筆數",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Request is successful.",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="伺服器文字訊息"),
     *              @OA\Property(
     *                  property="tags", type="array",description="標籤",
     *                  @OA\Items(
     *                      type="string",description="標籤名稱"
     *                  )
     *              )
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
     * )
     **/
    public function index()
    {
        $like = $this->request->getGet("like");
        $size = $this->request->getGet("size");
        $listResult = $this->creationService->action(
            "get",
            "/api/v1/tag",
            [
                'query' => [
                    'like' => $like,
                    'size' => $size
                ]
            ]
        );
        $listResultData = json_decode($listResult->getBody(),true);
        return $this->respond($listResultData, $listResult->getStatusCode());
    }

}
