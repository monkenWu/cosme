<?php

namespace App\Controllers\V1;

use CodeIgniter\RESTful\ResourceController;

class User extends ResourceController
{

    protected $format    = 'json';
    private $userService;
    private $tokenAuthModel;

    public function __construct()
    {
        $gateway = \config\Services::gateway();
        $this->userService = $gateway->getServiceEntity("user_service");
        $this->tokenAuthModel = \config\Services::auth();
    }

    /**
     * 執行登入
     *
     * @param string $level
     * @return void
     *
     * @OA\Post(
     *     path="/api/v1/user/login",
     *     tags = {"user","public"},
     *     description="帳號密碼驗證",
     *      @OA\RequestBody(
     *          description= "以 application/json 格式傳遞登入資訊",
     *          required= true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="account", type="string",description="帳號"),
     *              @OA\Property(property="password", type="string",description="密碼")
     *          )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="登入成功",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="伺服器文字訊息"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="accessToken", type="string",description="請求Token"),
     *                  @OA\Property(property="refreshToken", type="string",description="重新要求Token")
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="伺服器回傳錯誤，User001,002、Auth001,006",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="登入失敗，帳號或密碼錯誤，User003",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     )
     * )
     **/
    public function login()
    {
        $requestData = $this->request->getJSON(true);

        //驗證帳號密碼是否正確
        $loginResult = $this->userService->action(
            "POST",
            "/api/v1/user/login",
            [
                'headers' => [
                    'Content-Type' => "application/json"
                ],
                'body' => json_encode($requestData)
            ]
        );
        $loginResultData = json_decode($loginResult->getBody(),true);
        if($loginResult->getStatusCode()>=400){
            return $this->respond($loginResultData, $loginResult->getStatusCode());
        }

        //解析使用者資料
        $userData = $loginResultData["data"];
        $userData["userIP"] = $_SERVER['REMOTE_ADDR'];
        $userData["userAgent"] = $this->request->getUserAgent()->getAgentString();

        //新建token
        $authResult = $this->userService->action(
            "POST",
            "/api/v1/auth",
            [
                'headers' => [
                    'Content-Type' => "application/json"
                ],
                'body' => json_encode($userData)
            ]
        );
        $authResultData = json_decode($authResult->getBody(),true);

        return $this->respond($authResultData,$authResult->getStatusCode());
    }

    /**
     * 傳入 Access-Token 回傳使用者資料
     *
     * @param string $id
     * @return void
     *
     * @OA\Get(
     *     path="/api/v1/user",
     *     tags = {"user"},
     *     description="傳入 Access-Token 回傳使用者資料",
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
     *             @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="name", type="string", description="name"),
     *                  @OA\Property(property="email", type="string", description="email"),
     *                  @OA\Property(property="password", type="string", description="password"),
     *                  @OA\Property(property="sex", type="integer", description="sex"),
     *                  @OA\Property(property="birth", type="string", description="birth"),
     *                  @OA\Property(property="business", type="boolean", description="是否商用")
     *             ),
     *             @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="伺服器回傳錯誤,Auth001~003,005~006",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *             @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Refresh_token 已經過期,Auth004",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="找不到使用者登入資訊，Auth007",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     ),
     * )
     **/
    public function index()
    {   
        $result = $this->tokenAuthModel->doVarify(
            $this->request->getHeaderLine("Access-Token")
        );
        return $this->respond($this->tokenAuthModel->getResultData(),$this->tokenAuthModel->getStatusCode());
    }

    /**
     * 傳入過期的 Access_token 並回傳新的 access_token 字串
     *
     * @param string $id accessToken
     * @return void
     * 
     * @OA\Put(
     *     path="/api/v1/user/refresh/{accessToken}",
     *     tags = {"user"},
     *     description="以url 的 Access_token　與 Header 欄位 Refresh_token 進行驗證",
     *     @OA\Parameter(
     *         name="accessToken",
     *         in="path",
     *         description="已過期的 accessToken",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="Refresh-Token",
     *         in="header",
     *         description="使用者重新簽發專用令牌",
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
     *         description="伺服器回傳錯誤,Auth001~003,005~006",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *             @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Refresh_token 已經過期,Auth004",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="找不到使用者登入資訊，Auth007",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     ),
     * )
     **/
    public function refresh($accessToken = ""){
        $refreshToken = $this->request->getHeaderLine("Refresh-Token");
        $body = [
            "userIP" => $_SERVER['REMOTE_ADDR'],
            "userAgent" => $this->request->getUserAgent()->getAgentString()
        ];
        $authResult = $this->userService->action(
            "PUT",
            "/api/v1/auth/{$accessToken}",
            [
                'headers' => [
                    'Refresh-Token' => $refreshToken,
                    'Content-Type' => "application/json"
                ],
                'body' => json_encode($body)
            ]
        );
        $authResultData = json_decode($authResult->getBody(),true);

        return $this->respond($authResultData,$authResult->getStatusCode());
    }

    /**
     * 建立使用者
     *
     * @return void
     *
     * @OA\Post(
     *     path="/api/v1/user",
     *     tags = {"user","public"},
     *     description="建立使用者",
     *     @OA\RequestBody(
     *         description= "以 application/json 格式傳遞使用者資料",
     *         required= true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", description="name"),
     *             @OA\Property(property="email", type="string", description="email"),
     *             @OA\Property(property="password", type="string", description="password"),
     *             @OA\Property(property="sex", type="integer", description="sex"),
     *             @OA\Property(property="birth", type="string", description="birth"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="建立成功",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *             @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="伺服器回傳錯誤，User005~010",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     )
     * )
     **/
    public function create()
    {
        $userData = $this->request->getJSON(true);
        $userResult = $this->userService->action(
            "POST",
            "/api/v1/user",
            [
                'headers' => [
                    'Content-Type' => "application/json"
                ],
                'body' => json_encode($userData)
            ]
        );
        $userResultData = json_decode($userResult->getBody(),true);

        return $this->respond($userResultData,$userResult->getStatusCode());
    }


    /**
     * 編輯使用者資訊
     *
     * @return void
     *
     * @OA\Put(
     *     path="/api/v1/user",
     *     tags = {"user"},
     *     description="以 Header 中的 Access-Token 欄位進行驗證後更新資料",
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
     *         description= "以 application/json 格式傳遞登入資訊。依照實作需求，只需要傳遞需要修改的 key/value 即可，不需全數傳遞",
     *         required= true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", description="name"),
     *             @OA\Property(property="email", type="string", description="email"),
     *             @OA\Property(property="password", type="string", description="password"),
     *             @OA\Property(property="sex", type="integer", description="sex"),
     *             @OA\Property(property="img", type="string", description="img"),
     *             @OA\Property(property="birth", type="string", description="birth"),
     *             @OA\Property(property="business", type="boolean", description="是否商用")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="更新使用者資料成功",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="msg", type="string",description="訊息"),
     *             @OA\Property(property="status", type="boolean",description="狀態")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="更新使用者資料失敗",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type = "boolean", description="狀態"),
     *             @OA\Property(property="msg", type = "string", description="錯誤訊息"),
     *             )
     *     ),
     * )
     **/
    public function updateUser()
    {
        //驗證
        $accessToken = $this->request->getHeaderLine("Access-Token");
        if(!$this->tokenAuthModel->doVarify($accessToken)){
            return $this->respond($this->tokenAuthModel->getResultData(),$this->tokenAuthModel->getStatusCode());
        }
        $userData = $this->tokenAuthModel->getUserData();
        $userKey = $userData->key;

        //更新
        $updateData = $this->request->getJSON(true);
        if(isset($updateData["email"])) unset($updateData["email"]);
        if(isset($updateData["key"])) unset($updateData["key"]);   
        $userResult = $this->userService->action(
            "PUT",
            "/api/v1/user/{$userKey}",
            [
                'headers' => [
                    'Content-Type' => "application/json"
                ],
                'body' => json_encode($updateData)
            ]
        );
        $userResultData = json_decode($userResult->getBody(),true);
        
        return $this->respond($userResultData,$userResult->getStatusCode());
    }

    /**
     * 登出
     * 傳入 url 的 Access_token 與 Header 欄位 Refresh_token 執行登出
     * 
     * @param string $userID
     * @return void
     * 
     * @OA\Delete(
     *     path="/api/v1/user/{accessToken}",
     *     tags = {"user"},
     *     description="傳入 url:access_token 與 Header 欄位 Refresh_token 執行登出",
     *     @OA\Parameter(
     *         name="accessToken",
     *         in="path",
     *         description="Access token",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="Refresh-Token",
     *         in="header",
     *         description="Refresh token",
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
     *             @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="找不到使用者登入資訊，Auth007",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     )
     * )
     **/
    public function delete($accessToken = "")
    {
        $refreshToken = $this->request->getHeaderLine("Refresh-Token");
        $authResult = $this->userService->action(
            "DELETE",
            "/api/v1/auth/{$accessToken}",
            [
                'headers' => [
                    'Refresh-Token' => $refreshToken
                ]
            ]
        );
        $authResultData = json_decode($authResult->getBody(),true);
        return $this->respond($authResultData,$authResult->getStatusCode());
    }

}
