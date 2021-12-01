<?php

namespace App\Controllers\V1;

use CodeIgniter\RESTful\ResourceController;

class Without extends ResourceController
{

    protected $format    = 'json';

    /**
     * 圖片微服務實體
     *
     * @var App\Libraries\Microservice\ServiceEntity
     */
    private $photoService;
    /**
     * 使用者資料物件
     *
     * @var \App\Libraries\TokenVerify\UserData
     */
    private $userData;

    public function __construct()
    {
        $gateway = \config\Services::gateway();
        $this->photoService = $gateway->getServiceEntity("photo_service");
        $this->userData = \config\Services::auth()->getUserData();
    }

    /**
     * 素顏照清單
     *
     * @return void
     *
     * @OA\Get(
     *     path="/api/v1/without",
     *     tags = {"without"},
     *     description="傳入使用者令牌後回傳素顏陣列清單",
     *     @OA\Parameter(
     *         name="Access-Token",
     *         in="header",
     *         description="使用者通行令牌",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="請求成功",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="伺服器文字訊息"),
     *              @OA\Property(
     *                  property="data", type="array",description="素顏照清單",
     *                  @OA\Items(
     *                       type="object",description="單筆圖片內容",
     *                       @OA\Property(property="key", type="string", description="圖片主鍵(以SHA1加密)"),
     *                       @OA\Property(property="isDefault", type="boolean", description="是否為預設圖片"),
     *                       @OA\Property(property="updateDate", type="string", description="上傳時間")
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
        $userID = $this->userData->key;
        $listResult = $this->photoService->action(
            "get",
            "/api/v1/without",
            [
                'query' => [ 'userID' => $userID]
            ]
        );
        $listResultData = json_decode($listResult->getBody(),true);
        return $this->respond($listResultData, $listResult->getStatusCode());
    }

    /**
     * 取得單張素顏照
     *
     * @return void
     *
     * @OA\Get(
     *     path="/api/v1/without/{imgKey}",
     *     tags = {"without"},
     *     description="依 imgKey 取得使用者圖像",
     *     @OA\Parameter(
     *         name="imgKey",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="Access-Token",
     *         in="header",
     *         description="使用者通行令牌",
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
            "/api/v1/without/{$imgKey}",
            [
                'query' => [ 'isFull' => $isFull]
            ]
        );
        $showResultData = json_decode($showResult->getBody(),true);
        return $this->respond($showResultData,$showResult->getStatusCode());
    }

    /**
     * 上傳素顏照
     *
     * @return void
     *
     * @OA\Post(
     *     path="/api/v1/without",
     *     tags = {"without"},
     *     description="上傳素顏照，以 base64 的方式上傳",
     *     @OA\Parameter(
     *         name="Access-Token",
     *         in="header",
     *         description="使用者通行令牌",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description= "以 application/json 格式傳遞圖片資料。img 為 base64 字串。",
     *         required= true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="img", type="string", description="base64 IMG圖像"),
     *             @OA\Property(property="isDefault", type="integer", description="是否直接設定為預設圖像")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Request is successful.",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="伺服器文字訊息"),
     *              @OA\Property(
     *                   property="data",type="object",description="新建好的圖片內容",
     *                   @OA\Property(property="imgKey", type="string", description="圖片主鍵(以SHA1加密)"),
     *                   @OA\Property(property="tumbnial", type="string", description="縮圖檔案名稱"),
     *                   @OA\Property(property="full", type="string", description="完整圖片檔案名稱"),
     *                   @OA\Property(property="isDefault", type="integer", description="是否為預設圖片")
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="輸入資料格式有誤",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *             @OA\Property(property="msg", type="string",description="伺服器文字訊息"),     *             )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="資料庫處理失敗",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *             @OA\Property(property="msg", type="string",description="伺服器文字訊息"),     *         )
     *     ),
     * )
     **/
    public function create()
    {        
        $bodyData = $this->request->getJSON(true);
        $bodyData["userKey"] = $this->userData->key;
        $createResult = $this->photoService->action(
            "POST",
            "/api/v1/without",
            [
                'headers' => [
                    'Content-Type' => "application/json"
                ],
                'body' => json_encode($bodyData)
            ]
        );
        $createResultData = json_decode($createResult->getBody(),true);
        return $this->respond($createResultData,$createResult->getStatusCode());
    }

    /**
     * 更新素顏照
     *
     * @return void
     *
     * @OA\Put(
     *     path="/api/v1/without/{imgKey}",
     *     tags = {"without"},
     *     description="更新素顏照設定。",
     *     @OA\Parameter(
     *         name="imgKey",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="Access-Token",
     *         in="header",
     *         description="使用者通行令牌",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description= "以 application/json 格式傳遞圖片資料。",
     *         required= true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="isDefault", type="integer", description="是否為預設圖像")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="請求成功",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *             @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="伺服器回傳錯誤",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *            @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="imgKey 不存在於資料庫",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *             @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     )
     * )
     **/
    public function update($imgKey = null)
    {
        $bodyData = $this->request->getJSON(true);
        $bodyData["userKey"] = $this->userData->key;
        $updateResult = $this->photoService->action(
            "PUT",
            "/api/v1/without/{$imgKey}",
            [
                'headers' => [
                    'Content-Type' => "application/json"
                ],
                'body' => json_encode($bodyData)
            ]
        );
        $updateResultData = json_decode($updateResult->getBody(),true);
        return $this->respond($updateResultData,$updateResult->getStatusCode());
    }

    /**
     * 刪除素顏照
     *
     * @return void
     *
     * @OA\Delete(
     *     path="/api/v1/without/{imgKey}",
     *     tags = {"without"},
     *     description="刪除傳入素顏照 key",
     *     @OA\Parameter(
     *         name="imgKey",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="Access-Token",
     *         in="header",
     *         description="使用者通行令牌",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="請求成功",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *             @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="伺服器回傳錯誤",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *            @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="imgKey 不存在於資料庫",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *             @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     )
     * )
     **/
    public function delete($imgKey = null)
    {
        $bodyData = ["userKey" => $this->userData->key];
        $deleteResult = $this->photoService->action(
            "DELETE",
            "/api/v1/without/{$imgKey}",
            [
                'headers' => [
                    'Content-Type' => "application/json"
                ],
                'body' => json_encode($bodyData)
            ]
        );
        $deleteResultData = json_decode($deleteResult->getBody(),true);
        return $this->respond($deleteResultData,$deleteResult->getStatusCode());
    }

}
