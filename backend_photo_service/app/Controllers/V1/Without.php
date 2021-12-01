<?php

namespace App\Controllers\V1;

use App\Models\PhotoVerify;
use CodeIgniter\RESTful\ResourceController;
use App\Models\PhotoFileUpload;

class Without extends ResourceController
{
    
    protected $modelName = 'App\Models\PhotoWithout';
    protected $format    = 'json';
    protected $savePath  = '';

    public function __construct()
    {
        $config = \CodeIgniter\Config\Config::get("UploadFile");
        $this->savePath = $config->withoutPath;
    }

    /**
     * 素顏照清單
     *
     * @return void
     *
     * @OA\Get(
     *     path="/api/v1/without",
     *     tags = {"without"},
     *     description="以 query 傳入使用者主鍵後，回傳陣列清單",
     *     @OA\Parameter(
     *         name="userID",
     *         in="query",
     *         description="使用者主鍵",
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
     *                       @OA\Property(property="imgKey", type="string", description="圖片主鍵(以SHA1加密)"),
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
        try {
            $userID = $this->request->getGet("userID");
            if($userID == null) return $this->respond(["statusCode"=>"Photo001","msg" => "傳入欄位具有缺失"], 400);
            $result = $this->model
                ->where("user_key",$userID)
                ->findAll();
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }
        
        $returnData = [];
        foreach ($result as $row) {
            $returnData[] = [
                "key" => sha1($row["key"]),
                "isDefault" => (boolean)$row["is_default"],
                "updateDate" => $row["updated_at"]
            ];
        }
        return $this->respond([
            "statusCode"=>"SUCCESS",
            "msg" => "請求成功",
            "data" => $returnData,
        ], 200);
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
     *         name="isFull",
     *         in="query",
     *         description="是否為大圖",
     *         required=true,
     *         @OA\Schema(
     *             type="boolean"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="onlyFileName",
     *         in="query",
     *         description="是否僅回傳檔案名稱",
     *         required=false,
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
        //驗證傳入值
        $isFull = $this->request->getGet("isFull");
        $onlyFileName = $this->request->getGet("onlyFileName");
        if($isFull == null) return $this->respond(["statusCode"=>"Photo001","msg" => "傳入欄位具有缺失"], 400);
        
        //以主鍵搜索是否有符合的資料
        try {
            $result = $this->model
                ->where("sha1(`key`)",$imgKey)
                ->findAll();
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }
        if(count($result) == 0) return $this->respond(["statusCode"=>"Photo007",'msg' => '找不到 imgKey'], 404);

        //取圖片 base64
        try {
            $fileName = $isFull=="true" ? $result[0]["full"] : $result[0]["tumbnial"];
            if(!$onlyFileName){
                $photoReader = new PhotoFileUpload($this->savePath);
                $base64 = $photoReader->getFileByBase64($fileName);    
            }
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond([
                "statusCode"=>"Photo005",
                "msg" => "圖片寫入庫運作過程發生未知錯誤"
            ], 400);
        }

        return $this->respond([
            "statusCode"=>"SUCCESS",
            "msg" => "請求成功",
            "data" => $onlyFileName ? $fileName : $base64 
        ], 200);
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
     *     @OA\RequestBody(
     *         description= "以 application/json 格式傳遞圖片資料。img 為 base64 字串。",
     *         required= true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="userKey", type="string", description="使用者主鍵"),
     *             @OA\Property(property="img", type="string", description="base64 IMG圖像"),
     *             @OA\Property(property="isDefault", type="integer", description="是否直接設定為預設圖像")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Request is successful.",
     *         @OA\JsonContent(
     *              type="object",
     *             @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *             @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="輸入資料格式有誤",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *             @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="資料庫處理失敗",
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
        //取得規定資訊
        $data = $this->request->getJSON(true);
        $escData = esc($data);
        try {
            $userKey = $escData["userKey"];
            $img = $escData["img"];
            $isDefault = $escData["isDefault"];
        } catch (\Throwable $th) {
            return $this->respond(["statusCode"=>"Photo001","msg" => "傳入欄位具有缺失"], 400);
        }

        //驗證欄位內容
        $veriModel = new PhotoVerify();
        $verify = $veriModel->doVerify($escData);
        if (!$verify["status"]) {
            return $this->respond(["statusCode"=>$verify['statusCode'],"msg" => $verify['message']], 400);
        }

        //處理上傳圖片
        try {
            $fileUpload = new PhotoFileUpload($this->savePath);
            if(!$fileUpload->doUpload($img)){
                return $this->respond([
                    "statusCode"=>"Photo006",
                    "msg" => $fileUpload->getError()
                ], 400);
            }
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond([
                "statusCode"=>"Photo005",
                "msg" => "圖片寫入庫運作過程發生未知錯誤"
            ], 400);
        }
        
        //寫入資料庫
        $createData = [
            'user_key' => $userKey,
            'is_default' => $isDefault,
            "tumbnial" => $fileUpload->getTumbnialFileName(),
            "full" => $fileUpload->getFullFileName()
        ];
        try {
            $imgKey = $this->model->insert($createData);
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }

        return $this->respond([
            "statusCode"=>"Created",
            "msg" => "建立成功",
            "imgKey" => sha1($imgKey)
        ], 201);
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
     *     @OA\RequestBody(
     *         description= "以 application/json 格式傳遞圖片資料。",
     *         required= true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="isDefault", type="integer", description="是否為預設圖像"),
     *             @OA\Property(property="userKey", type="string", description="使用者主鍵")
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
        //取得規定資訊
        $data = $this->request->getJSON(true);
        $escData = esc($data);
        try {
            $isDefault = $escData["isDefault"];
            $userKey = $escData["userKey"];
        } catch (\Throwable $th) {
            return $this->respond(["statusCode"=>"Photo001","msg" => "傳入欄位具有缺失"], 400);
        }
        //驗證欄位內容
        $veriModel = new PhotoVerify();
        $verify = $veriModel->doVerify($escData);
        if (!$verify["status"]) {
            return $this->respond(["statusCode"=>$verify['statusCode'],"msg" => $verify['message']], 400);
        }
        
        //尋找與驗證 key 是否合法
        try {
            $result = $this->model
                ->where("sha1(`key`)",$imgKey)
                ->findAll();
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }
        if(count($result) == 0) return $this->respond(["statusCode"=>"Photo007",'msg' => '找不到 imgKey'], 404);
        $thisImgKey =  $result[0]["key"];
        $thisUserKey = $result[0]["user_key"];
        if($thisUserKey != $userKey) return $this->respond(["statusCode"=>"Photo008","msg" => "使用者驗證失敗"], 400);

        //判斷是否要修正預設
        if($isDefault == 1){
            try {
                //移除舊的isDefault
                $this->model->where('user_key',$thisUserKey)
                ->where('is_default', 1)
                ->set(['is_default'=> 0])
                ->update();
            } catch (\Throwable $th) {
                log_message('critical', $th->__toString());
                return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
            }
        }

        //執行更新
        $set = [
            "is_default" => $isDefault
        ];
        try {
            $this->model->where('key', $thisImgKey)
                ->set($set)
                ->update();
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }

        return $this->respond([
            "statusCode"=>"SUCCESS",
            "msg" => "請求成功",
        ], 200);
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
     *     @OA\RequestBody(
     *         description= "以 application/json 格式傳遞資料。",
     *         required= true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="userKey", type="string", description="使用者主鍵")
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
        //驗證資料傳遞
        $data = $this->request->getJSON(true);
        $escData = esc($data);
        try {
            $userKey = $escData["userKey"];
        } catch (\Throwable $th) {
            return $this->respond(["statusCode"=>"Photo001","msg" => "傳入欄位具有缺失"], 400);
        }

        //尋找與驗證 key 是否合法
        try {
            $result = $this->model
                ->where("sha1(`key`)",$imgKey)
                ->findAll();
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }
        if(count($result) == 0) return $this->respond(["statusCode"=>"Photo007",'msg' => '找不到 imgKey'], 404);
        $thisImgKey =  $result[0]["key"];
        $thisUserKey = $result[0]["user_key"];
        if($thisUserKey != $userKey) return $this->respond(["statusCode"=>"Photo008","msg" => "使用者驗證失敗"], 400);

        //執行刪除
        try {
            $this->model->delete($thisImgKey);
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(['msg' => '資料庫處理錯誤'], 401);
        }

        //更換預設
        if($result[0]["is_default"] == 1){
            $userKey = $result[0]["user_key"];
            $imgList = $this->model->where('user_key', $userKey)->findAll();
            if(count($imgList) != 0){
                $firstImgKey = $imgList[0]["key"];
                try {
                    $this->model
                        ->where('key', $firstImgKey)
                        ->set(["is_default" => 1])
                        ->update();
                } catch (\Throwable $th) {
                    log_message('critical', $th->__toString());
                    return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
                }    
            }    
        }

        return $this->respond([
            "statusCode"=>"SUCCESS",
            "msg" => "請求成功"
        ],200);
    }
}
