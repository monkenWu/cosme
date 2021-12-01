<?php namespace App\Controllers\V1;

use CodeIgniter\RESTful\ResourceController;
use App\Models\User;

class Auth extends ResourceController
{
    
    protected $modelName = 'App\Models\Auth';
    protected $format    = 'json';

    /**
     * 驗證 Token 是否有效，並回傳 Token 中的使用者資訊
     *
     * @param string $level
     * @return void
     * 
     * @OA\Get(
     *     path="/api/v1/auth",
     *     tags = {"auth"},
     *     description="以 Header 中的 Access-Token 欄位進行驗證，成功回傳使用者資料",
     *      @OA\Parameter(
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
     *         response=403,
     *         description="Token 已經過期",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     ),
     * )
     **/
    public function index(){
        try {
            $accessToken = $this->request->getHeaderLine("Access-Token");
            if($accessToken ==="") throw new \Throwable ;
        } catch (\Throwable $th) {
            return $this->respond(["statusCode"=>"Auth001","msg"=>"傳入欄位具有缺失"],400);
        }

        //驗證Token
        try {
            $auth = \Config\Services::auth();
            $result = $auth->verification($accessToken);
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"Auth006","msg"=>"驗證程式庫運作過程發生未知錯誤"],400);
        }

        //若驗證失敗則回傳失敗內容
        if($result["code"] != 200){
            return $this->respond([
                "statusCode"=>$result["statusCode"],
                "msg" => $result["msg"]
            ],$result["code"]);   
        }

        return $this->respond([
            "statusCode"=>$result["statusCode"],
            "data" => $result["data"],
            "msg" => $result["msg"]
        ],$result["code"]);
    }

    /**
     * 建立新的 token
     *
     * @param string $level
     * @return void
     * 
     * @OA\Post(
     *     path="/api/v1/auth",
     *     tags = {"auth"},
     *     description="創建 token 回傳，並儲存置資料庫",
     *      @OA\RequestBody(
     *          description= "以 application/json 格式傳遞使用者主鍵",
     *          required= true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="key", type="integer",description="使用者主鍵"),
     *              @OA\Property(property="name", type="string",description="使用者名稱"),
     *              @OA\Property(property="email", type="string",description="信箱"),
     *              @OA\Property(property="sex", type="integer",description="性別"),
     *              @OA\Property(property="birth", type="string",description="生日"),
     *              @OA\Property(property="img", type="string",description="使用者圖片路徑"),
     *              @OA\Property(property="business", type="boolean", description="是否商用"),
     *              @OA\Property(property="userAgent", type="string",description="運作環境"),
     *              @OA\Property(property="userIP", type="string",description="使用者IP位置")
     *          )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="建立成功",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="伺服器文字訊息"),
     *              @OA\Property(property="data", type="object",description="Token內容",
     *                  @OA\Property(property="accessToken", type="string",description="驗證用Token"),
     *                  @OA\Property(property="refreshToken", type="string",description="更新用Token")
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
    public function create(){
        $data = $this->request->getJSON(true);        
        try {
            $siginData = [
                "key" => $data["key"],
                "name" => $data["name"],
                "email" => $data["email"],
                "sex" => $data["sex"],
                "birth" => $data["birth"],
                "img" => $data["img"] ?? "",
                "business" => $data["business"] == 1 ? true : false,
                "name" => $data["name"]
            ];
            $userAgent = $data["userAgent"];
            $userIP = $data["userIP"];
        } catch (\Throwable $th) {
            return $this->respond(["statusCode"=>"Auth001","msg"=>"傳入欄位具有缺失"],400);
        }
        
        //簽發 Token 與寫入資料庫
        try {
            $auth = \Config\Services::auth();
            $tokens = $auth->siginToken($siginData);    
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"Auth006","msg"=>"驗證程式庫運作過程發生未知錯誤"],400);
        }

        //儲存資料庫
        try {
            $this->model->insert([
                "user_key" => $data["key"],
                "access_token" => $tokens["accessToken"],
                "refresh_token" => $tokens["refreshToken"],
                "user_agent" => $userAgent,
                "user_ip" => $userIP
            ]);            
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }

        return $this->respond([
            "statusCode"=>"Created",
            "msg" => "建立成功",
            "data" => $tokens
        ],201);
    }

    /**
     * 傳入過期的 Access_token 並回傳新的 access_token 字串
     *
     * @param string $id accessToken
     * @return void
     * 
     * @OA\Put(
     *     path="/api/v1/auth/{accessToken}",
     *     tags = {"auth"},
     *     description="以　url 的 Access_token　與 Header 欄位 Refresh_token 進行驗證",
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
     *         description="使用者重新簽發專用 Token",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *          description= "以 application/json 格式傳遞",
     *          required= true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="userAgent", type="string",description="運作環境"),
     *              @OA\Property(property="userIP", type="string",description="使用者IP位置")
     *          )
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
     *         response=403,
     *         description="Token 已經過期",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="找不到使用者登入資訊",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     ),
     * )
     **/
    public function update($accessToken = ""){
        $data = $this->request->getJSON(true);
        try {
            $refreshToken = $this->request->getHeaderLine("Refresh-Token");
            $userAgent = $data["userAgent"];
            $userIP = $data["userIP"];
            if($refreshToken ==="" || $accessToken ==="") throw new \Throwable ;
        } catch (\Throwable $th) {
            return $this->respond(["statusCode"=>"Auth001","msg"=>"傳入欄位具有缺失"],400);
        }

        //驗證Token
        try {
            $auth = \Config\Services::auth();
            $result = $auth->verification($refreshToken);
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"Auth006","msg"=>"驗證程式庫運作過程發生未知錯誤"],400);
        }
        //若驗證失敗則回傳失敗內容
        if($result["code"] != 200){
            return $this->respond([
                "statusCode"=>$result["statusCode"],
                "msg" => $result["msg"]
            ],$result["code"]);   
        }

        //以傳入值進行資料庫查詢
        try {
            $authResult = $this->model
            ->where('refresh_token', $refreshToken)
            ->where('access_token', $accessToken)
            ->where('user_agent', $userAgent)
            ->findAll();
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }

        //取值
        try {
            $authResult =  $authResult[0];
            if($authResult === NULL) throw new \Throwable ;
        } catch (\Throwable $th) {
            return $this->respond(["statusCode"=>"Auth007","msg"=>"找不到使用者登入資訊"],404);
        }

        //取得最新使用者資料
        $userModel = new User();
        try {
            $userData = $userModel->where('key',$authResult["user_key"])->findAll()[0];
            $siginData = [
                "key" => $userData["key"],
                "name" => $userData["name"],
                "email" => $userData["email"],
                "sex" => $userData["sex"],
                "birth" => $userData["birth"],
                "img" => $userData["img"] ?? "",
                "name" => $userData["name"],
                "business" => $userData["business"] == 1 ? true : false
            ];
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }

        //簽發新 Token 與寫入資料庫
        try {
            $auth = \Config\Services::auth();
            $accessToken = $auth->createAccessToken($siginData);    
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"Auth006","msg"=>"驗證程式庫運作過程發生未知錯誤"],400);
        }
        $updateData = [
            "access_token" => $accessToken,
            "user_ip" => $userIP
        ];

        //更新資料庫
        try {
            $this->model->update($authResult["key"],$updateData);
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }

        return $this->respond([
            "statusCode"=>"SUCCESS",
            "msg" => "更新成功",
            "accessToken" => $accessToken
        ],200);
    }

    /**
     * 登出
     * 傳入 url 的 Access_token 與 Header 欄位 Refresh_token 執行登出
     * 
     * @param string $userID
     * @return void
     * 
     * @OA\Delete(
     *     path="/api/v1/auth/{accessToken}",
     *     tags = {"auth"},
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
     *         description="找不到使用者登入資訊",
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
        try {
            $refreshToken = $this->request->getHeaderLine("Refresh-Token");
            if($refreshToken === "" || $accessToken === "") throw new \Throwable ;
        } catch (\Throwable $th) {
            return $this->respond(["statusCode"=>"Auth001","msg"=>"傳入欄位具有缺失"],400);
        }

        //查詢使用者
        try {
            $authResult = $this->model
            ->where('refresh_token',$refreshToken)
            ->where('access_token',$accessToken)
            ->findAll();
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }

        //嘗試取得使用者資料
        try {
            $authResult = $authResult[0];
            if($authResult === NULL) throw new \Throwable ;
        } catch (\Throwable $th) {
            return $this->respond(["statusCode"=>"Auth007","msg"=>"找不到使用者登入資訊"],404);
        }

        //刪除資料
        try {
            $this->model->delete($authResult["key"]);
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }

        return $this->respond([
            "statusCode"=>"SUCCESS",
            "msg" => "請求成功"
        ],200);
    }

}
