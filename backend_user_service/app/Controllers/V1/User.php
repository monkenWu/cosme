<?php

namespace App\Controllers\V1;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserVerify;

class User extends ResourceController
{
    protected $modelName = 'App\Models\User';
    protected $format    = 'json';

    /**
     * 使用者帳號密碼登入驗證
     *
     * @param string $level
     * @return void
     *
     * @OA\Post(
     *     path="/api/v1/user/login",
     *     tags = {"user"},
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
     *         response=200,
     *         description="驗證成功",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="伺服器文字訊息")
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
     *         response=401,
     *         description="登入失敗，帳號或密碼錯誤",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     ),
     * )
     **/
    public function login()
    {
        $dataArr = $this->request->getJSON(true);
        try {
            $account = $dataArr["account"];
            $password = $dataArr["password"];
        } catch (\Throwable $th) {
            return $this->respond(["statusCode"=>"User001","msg" => "傳入欄位具有缺失"], 400);
        }
        if ($account == "" || $password == "") {
            return $this->respond(["statusCode"=>"User002","msg" => "帳號或密碼不可為空"], 400);
        }

        try {
            $result = $this->model->where('email', $account)
            ->where('password', sha1($password))
            ->findAll();
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }

        if (count($result) == 0) {
            return $this->respond([
                "statusCode"=>"User003",
                "msg" => "帳號或密碼錯誤"
            ],401);
        }

        try {
            $userData = $this->model->find($result[0]['key']);
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }
        $data = [
            "key" => $userData["key"],
            "name" => $userData["name"],
            "email" => $userData["email"],
            "sex" => $userData["sex"],
            "birth" => $userData["birth"],
            "img" => $userData["img"] ?? "",
            "business" => $userData["business"] == 1 ? true : false
        ];

        return $this->respond([
            "statusCode"=>"SUCCESS",
            "msg" => "請求成功",
            "data" => $data
        ],200);
    }


    /**
     * 以使用者 ID 搜索後回傳使用者資料
     *
     * @param string $id
     * @return void
     *
     * @OA\Get(
     *     path="/api/v1/user/{userID}",
     *     tags = {"user"},
     *     description="以使用者 ID 搜索後回傳使用者資料",
     *     @OA\Parameter(
     *         name="userID",
     *         in="path",
     *         description="使用者ID",
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
     *                  description="使用者資料",
     *                  @OA\Property(property="name", type="string", description="name"),
     *                  @OA\Property(property="email", type="string", description="email"),
     *                  @OA\Property(property="password", type="string", description="password"),
     *                  @OA\Property(property="sex", type="integer", description="sex"),
     *                  @OA\Property(property="birth", type="string", description="birth"),
     *                  @OA\Property(property="business", type="boolean", description="是否商用"),
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
     *         description="userID 不存在於資料庫",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *             @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     )
     * )
     **/
    public function show($userID = null)
    {
        try {
            $result = $this->model->find($userID);
            if ($result == null) {
                return $this->respond(["statusCode"=>"User004","msg" => "userID 不存在於資料庫"], 404);
            }
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }
        $data = [
            "key" => $result["key"],
            "name" => $result["name"],
            "email" => $result["email"],
            "sex" => $result["sex"],
            "birth" => $result["birth"],
            "img" => $result["img"] ?? "",
            "business" => $result["business"] == 1 ? true : false,
        ];
        return $this->respond([
            "statusCode"=>"SUCCESS",
            "msg" => "請求成功",
            "data" => $data
        ]);
    }


    /**
     * 建立使用者
     *
     * @return void
     *
     * @OA\Post(
     *     path="/api/v1/user",
     *     tags = {"user"},
     *     description="create功能",
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
     *         description="伺服器回傳錯誤",
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
        $data = $this->request->getJSON(true);
        $escData = esc($data);
        $check = $this->model->where('email',$data['email'])->findAll();
        //判斷帳號重複
        if(count($check) > 0) {
            return $this->respond(["statusCode"=>"User005","msg" => "此帳號已被註冊"],400);
        }

        $verifyModel = new UserVerify();

        $data = [
            'name' => $escData['name'],
            'email' => $escData['email'],
            'password' => $data['password'],
            'sex' => $data['sex'],
            'birth' => $escData['birth'],
        ];

        //判斷格式
        $verify = $verifyModel->doVerify($data);
        if (!$verify["status"]) {
            return $this->respond(["statusCode"=>$verify['statusCode'],"msg" => $verify['message']], 400);
        }

        //密碼加密
        $data["password"] = sha1($data["password"]);

        try {
            $this->model->insert($data);
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }

        return $this->respond([
            "statusCode"=>"Created",
            "msg" => "建立成功"
        ], 201);
    }


    /**
     * 編輯使用者資訊
     * 以 application/json 格式傳值
     *
     * @return void
     *
     * @OA\Put(
     *     path="/api/v1/user/{userID}",
     *     tags = {"user"},
     *     description="以使用者 ID 確認身分後更新資料",
     *     @OA\Parameter(
     *         name="userID",
     *         in="path",
     *         description="使用者ID",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description= "以 application/json 格式傳遞登入資訊，所有欄位皆為可選",
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
     *         description="userID 不存在於資料庫",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *             @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     )
     * )
     **/
    public function update($id = null)
    {
        try {
            $dataArr = $this->model->find($id);
            if ($dataArr == null) {
                return $this->respond(["statusCode"=>"User004","msg" => "userID 不存在於資料庫"],404);
            }
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }

        //使用允許的欄位過濾輸入欄位，前端可以自由傳入需要更新的欄位
        $inputData = $this->request->getJSON(true);
        $verifyModel = new UserVerify;
        $field = $verifyModel->getFieldKeys();
        $updateData = [];
        foreach ($field as $value) {
            if(isset($inputData[$value])){
                $updateData[$value] = $inputData[$value];
            }
        }
        if(count($updateData) == 0){
            return $this->respond(["statusCode"=>"User001","msg" => "傳入欄位具有缺失"], 400);
        }
        if(isset($updateData["business"])) $updateData["business"] = (int)$updateData["business"];
        //判定格式
        $verifyData = $verifyModel->doVerify($updateData);
        if (!$verifyData["status"]) {
            return $this->respond(["statusCode"=>$verifyData['statusCode'],"msg" => $verifyData['message']], 400);
        }
        if(isset($updateData["password"])) $updateData["password"] = sha1($updateData["password"]);

        //更新資料庫
        try {
            $this->model->update($id,$updateData);
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }
        return $this->respond([
            "statusCode"=>"SUCCESS",
            "msg" => "請求成功",
        ], 200);
    }
}
