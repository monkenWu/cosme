<?php

namespace App\Controllers\V1;

use CodeIgniter\RESTful\ResourceController;

class Makeup extends ResourceController
{

    protected $format    = 'json';

    /**
     * 圖片微服務實體
     *
     * @var App\Libraries\Microservice\ServiceEntity
     */
    private $photoService;
    /**
     * 上妝微服務實體
     *
     * @var App\Libraries\Microservice\ServiceEntity
     */
    private $makeupService;
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
        $this->makeupService = $gateway->getServiceEntity("makeup_service");
        $this->userData = \config\Services::auth()->getUserData();
    }

    /**
     * 試妝合成照清單
     *
     * @return void
     *
     * @OA\Get(
     *     path="/api/v1/makeup",
     *     tags = {"makeup"},
     *     description="傳入使用者令牌後回傳試妝合成照陣列清單",
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
     *                  property="data", type="array",description="試妝照清單，主鍵皆已 sha1 加密",
     *                  @OA\Items(
     *                       type="object",description="單筆圖片內容",
     *                       @OA\Property(property="imgKey", type="string", description="圖片主鍵(以SHA1加密)"),
     *                       @OA\Property(property="withoutKey", type="string", description="素顏照主鍵"),
     *                       @OA\Property(property="ReferenceKey", type="string", description="參考照主鍵"),
     *                       @OA\Property(property="creationKey", type="string", description="文章主鍵"),
     *                       @OA\Property(property="createdDate", type="string", description="建立時間"),
     *                       @OA\Property(property="score", type="integer", description="分數，0 為尚未評分")
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
            "/api/v1/synthesize",
            [
                'query' => [ 'userID' => $userID]
            ]
        );
        $listResultData = json_decode($listResult->getBody(),true);
        return $this->respond($listResultData, $listResult->getStatusCode());
    }

    /**
     * 取得單張合成照
     *
     * @return void
     *
     * @OA\Get(
     *     path="/api/v1/makeup/{imgKey}",
     *     tags = {"makeup"},
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
        $showResult = $this->photoService->action(
            "GET",
            "/api/v1/synthesize/{$imgKey}"
        );
        $showResultData = json_decode($showResult->getBody(),true);
        return $this->respond($showResultData,$showResult->getStatusCode());
    }

    /**
     * 建立妝容合成照
     *
     * @return void
     *
     * @OA\Post(
     *     path="/api/v1/makeup",
     *     tags = {"makeup"},
     *     description="傳入資料建立合成照",
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
     *             @OA\Property(property="referenceKey", type="string", description="完妝照KEY"),
     *             @OA\Property(property="withoutKey", type="string", description="素顏照KEY")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="【回傳已生成的照片】Request is successful.",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="伺服器文字訊息"),
     *              @OA\Property(property="imgKey", type="string", description="圖片主鍵(以SHA1加密)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="【伺服器運算新的照片】Request is successful.",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="伺服器文字訊息"),
     *              @OA\Property(property="imgKey", type="string", description="圖片主鍵(以SHA1加密)")
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

        //判斷是否有運算過了
        $checkResult = $this->photoService->action(
            "POST",
            "/api/v1/synthesize/check",
            [
                'headers' => [
                    'Content-Type' => "application/json"
                ],
                'body' => json_encode($bodyData)
            ]
        );
        if($checkResult->getStatusCode() == 200){
            $checkResultData = json_decode($checkResult->getBody(),true);
            return $this->respond($checkResultData,$checkResult->getStatusCode());
        }

        $referenceKey = $bodyData["referenceKey"] ?? "none";
        $referenceResult = $this->photoService->action(
            "GET",
            "/api/v1/reference/{$referenceKey}",
            [
                'query' => [ 'isFull' => "true",'onlyFileName' => "true"],
            ]
        );
        $referenceFileName = json_decode($referenceResult->getBody(),true)["data"];

        $withoutKey = $bodyData["withoutKey"] ?? "none";
        $withoutResult = $this->photoService->action(
            "GET",
            "/api/v1/without/{$withoutKey}",
            [
                'query' => [ 'isFull' => "true",'onlyFileName' => "true"],
            ]
        );
        $withoutFileName = json_decode($withoutResult->getBody(),true)["data"];

        $makeupResult = $this->makeupService->action(
            "post",
            "/api/v1/makeup",
            [
                'headers' => [
                    'Content-Type' => "application/json"
                ],
                "body" => json_encode([
                    "userIMG" => $withoutFileName,
                    "referenceIMG"=>$referenceFileName
                ])
            ]
        );
        $bodyData["fileName"] = json_decode($makeupResult->getBody(),true)["fileName"];

        $createResult = $this->photoService->action(
            "POST",
            "/api/v1/synthesize",
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
     * (公開) 妝容分享測試上妝
     *
     * @return void
     *
     * @OA\Post(
     *     path="/api/v1/makeup/trial",
     *     tags = {"makeup","public"},
     *     description="傳入資料建立單次合成照",
     *     @OA\RequestBody(
     *         description= "以 application/json 格式傳遞圖片資料。withoutIMG 以 base64 傳遞",
     *         required= true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="referenceKey", type="string", description="完妝照KEY"),
     *             @OA\Property(property="withoutIMG", type="string", description="素顏照 Base64")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Request is successful.運算成功以 base64 回傳成功圖像。",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="伺服器文字訊息"),
     *              @OA\Property(
     *                   property="data",type="object",description="新建好的圖片內容",
     *                   @OA\Property(property="imgData", type="string", description="運算成功 base64")
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
    public function trial()
    {
        $data = $this->request->getJSON(true);

        //上傳素顏照至公開帳號
        $createData = [
            "userKey" => 1,
            "img" => $data["withoutIMG"] ?? "none",
            "isDefault" => 0
        ];
        $createResult = $this->photoService->action(
            "POST",
            "/api/v1/without",
            [
                'headers' => [
                    'Content-Type' => "application/json"
                ],
                'body' => json_encode($createData)
            ]
        );
        $imgKey = json_decode($createResult->getBody(),true)["imgKey"];

        //取得參考照檔案名稱
        $referenceKey = $data["referenceKey"] ?? "none";
        $referenceResult = $this->photoService->action(
            "GET",
            "/api/v1/reference/{$referenceKey}",
            [
                'query' => [ 'isFull' => "true",'onlyFileName' => "true"],
            ]
        );
        $referenceFileName = json_decode($referenceResult->getBody(),true)["data"];

        //取得素顏照檔案名稱
        $withoutKey = $imgKey ?? "none";
        $withoutResult = $this->photoService->action(
            "GET",
            "/api/v1/without/{$withoutKey}",
            [
                'query' => [ 'isFull' => "true",'onlyFileName' => "true"],
            ]
        );
        $withoutFileName = json_decode($withoutResult->getBody(),true)["data"];

        //AI 產生合成照
        $makeupResult = $this->makeupService->action(
            "post",
            "/api/v1/makeup",
            [
                'headers' => [
                    'Content-Type' => "application/json"
                ],
                "body" => json_encode([
                    "userIMG" => $withoutFileName,
                    "referenceIMG"=>$referenceFileName
                ])
            ]
        );
        //合成照紀錄至資料庫
        $makeupData = [
            "userKey" => 1 ,
            "referenceKey" => $referenceKey ,
            "withoutKey" => $withoutKey ,
            "fileName" => json_decode($makeupResult->getBody(),true)["fileName"] ,
        ];
        $createResult = $this->photoService->action(
            "POST",
            "/api/v1/synthesize",
            [
                'headers' => [
                    'Content-Type' => "application/json"
                ],
                'body' => json_encode($makeupData)
            ]
        );
        $createResultData = json_decode($createResult->getBody(),true);
        $makeupImgKey = $createResultData["imgKey"];
        unset($createResultData["imgKey"]);
        
        //取得合成結果
        $showResult = $this->photoService->action(
            "GET",
            "/api/v1/synthesize/{$makeupImgKey}"
        );
        $createResultData["imgData"] = json_decode($showResult->getBody(),true)["data"];

        return $this->respond($createResultData,201);
    }

    /**
     * 妝容評分
     *
     * @return void
     *
     * @OA\Put(
     *     path="/api/v1/makeup/{imgKey}",
     *     tags = {"makeup"},
     *     description="更新上妝合成照評分",
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
     *             @OA\Property(property="score", type="integer", description="評分")
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
            "/api/v1/synthesize/{$imgKey}",
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
     * 刪除上妝合成照
     *
     * @return void
     *
     * @OA\Delete(
     *     path="/api/v1/makeup/{imgKey}",
     *     tags = {"makeup"},
     *     description="刪除傳入上妝合成照 key",
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
            "/api/v1/synthesize/{$imgKey}",
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
