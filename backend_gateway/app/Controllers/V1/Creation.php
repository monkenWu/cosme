<?php

namespace App\Controllers\V1;

use CodeIgniter\RESTful\ResourceController;

class Creation extends ResourceController
{

    protected $format    = 'json';

    /**
     * 文章微服務實體
     *
     * @var App\Libraries\Microservice\ServiceEntity
     */
    private $creationService;
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
        $this->creationService = $gateway->getServiceEntity("creation_service");
        $this->photoService = $gateway->getServiceEntity("photo_service");
        $this->userData = \config\Services::auth()->getUserData();
    }

    /**
     * 使用者文章清單
     *
     * @return void
     *
     * @OA\Get(
     *     path="/api/v1/creation",
     *     tags = {"creation"},
     *     description="傳入使用者令牌後回傳文章清單",
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
     *         name="offset",
     *         in="query",
     *         description="偏移量",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
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
     *                  property="data", type="array",description="個人文章清單",
     *                  @OA\Items(
     *                       type="object",description="單筆圖片內容",
     *                       @OA\Property(property="key", type="string",description="文章主鍵"),
     *                       @OA\Property(property="imgKey", type="string",description="上妝照主鍵"),
     *                       @OA\Property(property="title", type="string",description="文章標題"),
     *                       @OA\Property(property="content", type="string",description="文章內容"),
     *                       @OA\Property(property="created_at", type="string",description="新建時間"),
     *                       @OA\Property(property="updated_at", type="string",description="更新時間"),
     *                       @OA\Property(
     *                          property="tags", type="array",description="標籤",
     *                          @OA\Items(
     *                              type="string",description="標籤名稱"
     *                          )
     *                       ),
     *                       @OA\Property(
     *                          property="products", type="array",description="使用產品",
     *                          @OA\Items(
     *                              @OA\Property(property="name", type="string",description="產品名稱"),
     *                              @OA\Property(property="imgpath", type="string",description="產品圖片網址"),
     *                              @OA\Property(property="url", type="string",description="產品連結"),
     *                               @OA\Property(property="intro", type="string",description="產品介紹")
     *                          )
     *                       )
     *                  )
     *              ),
     *             @OA\Property(property="amount", type="integer",description="資料總筆數"),
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
        $offset = $this->request->getGet("offset");
        $size = $this->request->getGet("size");
        $listResult = $this->creationService->action(
            "get",
            "/api/v1/creation",
            [
                'query' => [
                    'userID' => $userID,
                    'offset' => $offset,
                    'size' => $size
                ]
            ]
        );
        $listResultData = json_decode($listResult->getBody(),true);
        return $this->respond($listResultData, $listResult->getStatusCode());
    }

    /**
     * (公開)取得單篇文章
     *
     * @return void
     *
     * @OA\Get(
     *     path="/api/v1/creation/{creationID}",
     *     tags = {"creation","public"},
     *     description="傳入 ID 取得單筆貼文",
     *     @OA\Parameter(
     *         name="creationID",
     *         in="path",
     *         description="貼文ID",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="功能正常",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *             @OA\Property(property="msg", type="string",description="伺服器文字訊息"),
     *             @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  description="單筆圖片內容",
     *                  @OA\Property(property="key", type="string",description="妝容主鍵"),
     *                  @OA\Property(property="imgKey", type="string",description="參考用上妝照主鍵"),
     *                  @OA\Property(property="title", type="string",description="標題"),
     *                  @OA\Property(property="content", type="string",description="文章內容"),
     *                  @OA\Property(property="created_at", type="string",description="新建時間"),
     *                  @OA\Property(property="updated_at", type="string",description="更新時間"),
     *                  @OA\Property(
     *                     property="tags", type="array",description="標籤",
     *                     @OA\Items(
     *                         type="string",description="標籤名稱"
     *                     )
     *                  ),
     *                  @OA\Property(
     *                     property="author", type="array",description="作者資料",
     *                     @OA\Items(
     *                         @OA\Property(property="name", type="string",description="作者名稱"),
     *                         @OA\Property(property="key", type="string",description="作者主鍵"),
     *                         @OA\Property(property="img", type="string",description="作者大頭貼檔名")
     *                     )
     *                  ),
     *                  @OA\Property(
     *                     property="products", type="array",description="使用產品",
     *                     @OA\Items(
     *                         @OA\Property(property="name", type="string",description="產品名稱"),
     *                         @OA\Property(property="imgpath", type="string",description="產品圖片網址"),
     *                         @OA\Property(property="url", type="string",description="產品連結"),
     *                          @OA\Property(property="intro", type="string",description="產品介紹")
     *                     )
     *                  )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="伺服器回傳錯誤",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="creationID 不存在於資料庫",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *             @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     )
     * )
     **/
    public function show($creationID = null)
    {
        $showResult = $this->creationService->action(
            "GET",
            "/api/v1/creation/{$creationID}"
        );
        $showResultData = json_decode($showResult->getBody(),true);
        return $this->respond($showResultData,$showResult->getStatusCode());
    }

    /**
     * (公開)取得所有文章
     *
     * @return void
     *
     * @OA\Post(
     *     path="/api/v1/creation/post",
     *     tags = {"creation","public"},
     *     description="不分會員取得所有列表",
     *     @OA\Parameter(
     *         name="offset",
     *         in="query",
     *         description="偏移量",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
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
     *     @OA\Parameter(
     *         name="like",
     *         in="query",
     *         description="字串搜索",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *          description= "以 application/json 格式傳遞內容，傳入欲搜索的標籤，並非必須傳入值",
     *          required= false,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="tags", type="array",description="標籤",
     *                  @OA\Items(
     *                      type="string",description="標籤名稱"
     *                  )
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Request is successful.",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="伺服器文字訊息"),
     *              @OA\Property(
     *                  property="data", type="array",description="個人文章清單",
     *                  @OA\Items(
     *                       type="object",description="單筆圖片內容",
     *                       @OA\Property(property="key", type="string",description="妝容主鍵"),
     *                       @OA\Property(property="imgKey", type="string",description="參考用上妝照主鍵"),
     *                       @OA\Property(property="title", type="string",description="標題"),
     *                       @OA\Property(property="content", type="string",description="文章內容"),
     *                       @OA\Property(property="created_at", type="string",description="新建時間"),
     *                       @OA\Property(property="updated_at", type="string",description="更新時間"),
     *                       @OA\Property(
     *                          property="tags", type="array",description="標籤",
     *                          @OA\Items(
     *                              type="string",description="標籤名稱"
     *                          )
     *                       ),
     *                       @OA\Property(
     *                          property="author", type="array",description="作者資料",
     *                          @OA\Items(
     *                              @OA\Property(property="name", type="string",description="作者名稱"),
     *                              @OA\Property(property="key", type="string",description="作者主鍵"),
     *                               @OA\Property(property="img", type="string",description="作者大頭貼檔名")
     *                          )
     *                       ),
     *                       @OA\Property(
     *                          property="products", type="array",description="使用產品",
     *                          @OA\Items(
     *                              @OA\Property(property="name", type="string",description="產品名稱"),
     *                              @OA\Property(property="imgpath", type="string",description="產品圖片網址"),
     *                              @OA\Property(property="url", type="string",description="產品連結"),
     *                               @OA\Property(property="intro", type="string",description="產品介紹")
     *                          )
     *                       )
     *                  )
     *              ),
     *             @OA\Property(property="amount", type="integer",description="資料總筆數"),
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
    public function post()
    {
        $offset = $this->request->getGet("offset");
        $size = $this->request->getGet("size");
        $query = [
            'offset' => $offset,
            'size' => $size
        ];
        if($like = $this->request->getGet("like")) $query["like"] = $like;
        $data = [];
        $data["query"] = $query;
        try {
            $requestBodyData = $this->request->getJSON(true);
            $data["body"] = json_encode([ "tags" => $requestBodyData["tags"] ]);
        } catch (\Throwable $th) {}
        $data['headers'] = [
            'Content-Type' => "application/json"
        ];
        $listResult = $this->creationService->action(
            "post",
            "/api/v1/creation/all",
            $data
        );
        $listResultData = json_decode($listResult->getBody(),true);
        return $this->respond($listResultData, $listResult->getStatusCode());
    }


    /**
     * 上傳貼文
     *
     * @return void
     *
     * @OA\Post(
     *     path="/api/v1/creation",
     *     tags = {"creation"},
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
     *          description= "以 application/json 格式傳遞內容",
     *          required= true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="img", type="string",description="base64格式照片"),
     *              @OA\Property(property="title", type="string",description="文章標題"),
     *              @OA\Property(property="content", type="string",description="文章內容"),
     *              @OA\Property(
     *                  property="tags", type="array",description="標籤",
     *                  @OA\Items(
     *                      type="string",description="標籤名稱"
     *                  )
     *              ),
     *              @OA\Property(
     *                 property="products", type="array",description="使用產品",
     *                 @OA\Items(
     *                     @OA\Property(property="name", type="string",description="產品名稱"),
     *                     @OA\Property(property="imgpath", type="string",description="產品圖片網址"),
     *                     @OA\Property(property="url", type="string",description="產品連結"),
     *                      @OA\Property(property="intro", type="string",description="產品介紹")
     *                 )
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Request is successful.",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="message"),
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
    public function create()
    {        
        $bodyData = $this->request->getJSON(true);
        $userKey = $this->userData->key;

        $createPhotoResult = $this->photoService->action(
            "POST",
            "/api/v1/reference",
            [
                'headers' => [
                    'Content-Type' => "application/json"
                ],
                'body' => json_encode([
                    "userKey" => $userKey,
                    "img" => $bodyData["img"] ?? ""
                ])
            ]
        );
        $createPhotoResultData = json_decode($createPhotoResult->getBody(),true);
        $imgKey = $createPhotoResultData["imgKey"];

        $createTagResult = $this->creationService->action(
            "POST",
            "/api/v1/tag",
            [
                'headers' => [
                    'Content-Type' => "application/json"
                ],
                'body' => json_encode([
                    "tags" => $bodyData["tags"] ?? ""
                ])
            ]
        );
        $createTagResultData = json_decode($createTagResult->getBody(),true);
        $tags = $createTagResultData["tags"];

        $createResult = $this->creationService->action(
            "POST",
            "/api/v1/creation",
            [
                'headers' => [
                    'Content-Type' => "application/json"
                ],
                'body' => json_encode([
                    "userKey" => $userKey,
                    "photoReferenceKey" => $imgKey,
                    "title" => $bodyData["title"] ?? "",
                    "content" => $bodyData["content"] ?? "",
                    "tags" => $tags
                ])
            ]
        );
        $createResultData = json_decode($createResult->getBody(),true);
        $creationKey = $createResultData["creationKey"];

        if(isset($bodyData["products"])){
            $addProducts = $this->creationService->action(
                "PUT",
                "/api/v1/products/{$creationKey}",
                [
                    'headers' => [
                        'Content-Type' => "application/json"
                    ],
                    "body" => json_encode([
                        "data" => $bodyData["products"]
                    ])
                ]
            );
            $addProductsData = json_decode($addProducts->getBody(),true);
            return $this->respond($addProductsData,$addProducts->getStatusCode());
        }

        return $this->respond($createResultData,$createResult->getStatusCode());
    }

    /**
     * 更新文章
     *
     * @return void
     *
     * @OA\Put(
     *     path="/api/v1/creation/{creationID}",
     *     tags = {"creation"},
     *     description="Update Demo",
     *     @OA\RequestBody(
     *         description= "以 application/json 格式傳遞資料。",
     *         required= true,
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Property(property="title", type="string",description="文章標題"),
     *              @OA\Property(property="content", type="string",description="文章內容"),
     *              @OA\Property(
     *                  property="tags", type="array",description="標籤",
     *                  @OA\Items(
     *                      type="string",description="標籤名稱"
     *                  )
     *              ),
     *              @OA\Property(
     *                 property="products", type="array",description="使用產品",
     *                 @OA\Items(
     *                     @OA\Property(property="name", type="string",description="產品名稱"),
     *                     @OA\Property(property="imgpath", type="string",description="產品圖片網址"),
     *                     @OA\Property(property="url", type="string",description="產品連結"),
     *                      @OA\Property(property="intro", type="string",description="產品介紹")
     *                 )
     *              )
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
     *         name="creationID",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Request is successful.",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="msg", type="string",description="message")
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
     *         description="找不到 creationID",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *             @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     )
     * )
     **/
    public function update($creationID  = null)
    {
        $bodyData = $this->request->getJSON(true);
        $userKey = $this->userData->key;

        if(isset($bodyData["tags"])){
            $createTagResult = $this->creationService->action(
                "POST",
                "/api/v1/tag",
                [
                    'headers' => [
                        'Content-Type' => "application/json"
                    ],
                    'body' => json_encode([
                        "tags" => $bodyData["tags"] ?? ""
                    ])
                ]
            );
            $createTagResultData = json_decode($createTagResult->getBody(),true);
            $bodyData["tags"] = $createTagResultData["tags"];    
        }

        $updateResult = $this->creationService->action(
            "PUT",
            "/api/v1/creation/{$creationID}",
            [
                'headers' => [
                    'Content-Type' => "application/json"
                ],
                'query' => ['userKey' => $userKey],
                'body' => json_encode($bodyData)
            ]
        );
        $updateResultData = json_decode($updateResult->getBody(),true);

        if(isset($bodyData["products"])){
            $addProducts = $this->creationService->action(
                "PUT",
                "/api/v1/products/{$creationID}",
                [
                    'headers' => [
                        'Content-Type' => "application/json"
                    ],
                    "body" => json_encode([
                        "data" => $bodyData["products"]
                    ])
                ]
            );
            $addProductsData = json_decode($addProducts->getBody(),true);
            return $this->respond($addProductsData,$addProducts->getStatusCode());
        }

        return $this->respond($updateResultData,$updateResult->getStatusCode());
    }

    /**
     * 刪除文章
     *
     * @return void
     *
     * @OA\Delete(
     *     path="/api/v1/creation/{creationID}",
     *     tags = {"creation"},
     *     description="透過 creationID 刪除文章",
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
     *         name="creationID",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Request is successful.",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="message"),
     *              @OA\Property(property="imgKey", type="string",description="已刪除的文章圖片ID"),
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
     *         description="找不到 creationID",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *             @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     )
     * )
     **/
    public function delete($creationID = null)
    {
        $userKey = $this->userData->key;
        $deleteCreationResult = $this->creationService->action(
            "DELETE",
            "/api/v1/creation/{$creationID}",
            [
                'query' => ['userKey' => $userKey],
            ]
        );
        $deleteCreationResultData = json_decode($deleteCreationResult->getBody(),true);
        if($deleteCreationResult->getStatusCode() != 200){
            return $this->respond($deleteCreationResultData,$deleteCreationResult->getStatusCode());
        }

        $imgKey = $deleteCreationResultData["imgKey"];
        $deleteResult = $this->photoService->action(
            "DELETE",
            "/api/v1/reference/{$imgKey}"
        );
        $deleteResultData = json_decode($deleteResult->getBody(),true);

        return $this->respond($deleteResultData,$deleteResult->getStatusCode());
    }

}
