<?php namespace App\Controllers\V1;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PhotoReference;
use App\Models\PhotoWithout;
use App\Models\PhotoFileUpload;

class Synthesize extends ResourceController
{
    
    protected $modelName = 'App\Models\PhotoSynthesize';
    protected $format    = 'json';
    protected $savePath  = '';

    public function __construct(){
        $config = \CodeIgniter\Config\Config::get("UploadFile");
        $this->savePath = $config->synthesizePath;
    }


    /**
     * 試妝照清單
     *
     * @return void
     *
     * @OA\Get(
     *     path="/api/v1/synthesize",
     *     tags = {"synthesize"},
     *     description="取得試妝照清單",
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
     *         description="Request is successful.",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="msg", type="string",description="message"),
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
    public function index(){
        try {
            $user_key = $this->request->getGet("userID");
            if($user_key == null) return $this->respond(["statusCode"=>"Photo001","msg" => "傳入欄位具有缺失"], 400);
            $result = $this->model
                ->select("
                    creation.key as creation_key,
                    photo_synthesize.key as key,
                    without_key,
                    reference_key,
                    photo_synthesize.created_at,
                    photo_synthesize.score
                ")
                ->where("photo_synthesize.user_key",$user_key)
                ->join("creation","photo_reference_key = reference_key")
                ->findAll();
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }

        $outputData = [];
        foreach ($result as $row) {
            $outputData[] = [
                "imgKey" => sha1($row["key"]),
                "withoutKey" => sha1($row["without_key"]),
                "ReferenceKey" => sha1($row["reference_key"]),
                "creationKey" => sha1($row["creation_key"]),
                "createdDate" => $row["created_at"],
                "score" => $row["score"]
            ];
        }
        return $this->respond([
            "statusCode"=>"SUCCESS",
            "msg" => "請求成功",
            "data" => $outputData,
        ], 200);
    }

    /**
     * 顯示單張上妝照
     *
     * @return void
     *
     * @OA\Get(
     *     path="/api/v1/synthesize/{imgKey}",
     *     tags = {"synthesize"},
     *     description="依 imgKey 取得使用者上妝圖像",
     *     @OA\Parameter(
     *         name="imgKey",
     *         in="path",
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
    public function show($imgKey = null){
        
        //尋找與驗證 key 是否合法
        $keyDecode = \Config\Services::KeyDecode();
        if(!$imgKey = $keyDecode->getKey($this->model,$imgKey,"imgKey")){
            return $this->respond($keyDecode->getResponse(), $keyDecode->getCode());
        }
        $result = $keyDecode->getResult();
        //取圖片 base64
        try {
            $fileName =  $result["name"];
            $photoReader = new PhotoFileUpload($this->savePath);
            $base64 = $photoReader->getFileByBase64($fileName);
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
            "data" => $base64
        ], 200);
    }

    /**
     * 查詢是否運算過此筆資料
     *
     * @return void
     *
     * @OA\Post(
     *     path="/api/v1/synthesize/check",
     *     tags = {"synthesize"},
     *     description="Create synthesize",
     *     @OA\RequestBody(
     *         description= "以 application/json 格式傳遞圖片資料。",
     *         required= true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="userKey", type="integer", description="使用者Key"),
     *             @OA\Property(property="referenceKey", type="string", description="完妝照KEY"),
     *             @OA\Property(property="withoutKey", type="string", description="素顏照KEY"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Request is successful.",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="伺服器文字訊息"),
     *              @OA\Property(property="imgKey", type="string",description="圖片主鍵")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="並無紀錄",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *             @OA\Property(property="msg", type="string",description="伺服器文字訊息")
     *         )
     *     )
     * )
     **/
    public function check()
    {
        //取得規定資訊
        $data = $this->request->getJSON(true);
        $escData = esc($data);
        try {
            $userKey = $escData["userKey"];
            $referenceKey = $escData["referenceKey"];
            $withoutKey = $escData["withoutKey"];
        } catch (\Throwable $th) {
            return $this->respond(["statusCode"=>"Photo001","msg" => "傳入欄位具有缺失"], 400);
        }

        //尋找與驗證 key 是否合法
        $keyDecode = \Config\Services::KeyDecode();
        if(!$referenceKey = $keyDecode->getKey(new PhotoReference(),$referenceKey,"referenceKey")){
            return $this->respond($keyDecode->getResponse(), $keyDecode->getCode());
        }
        if(!$withoutKey = $keyDecode->getKey(new PhotoWithout(),$withoutKey,"withoutKey")){
            return $this->respond($keyDecode->getResponse(), $keyDecode->getCode());
        }
        
        //查詢資料庫
        try {
            $result = $this->model
                ->where("user_key",$userKey)
                ->where("reference_key",$referenceKey)
                ->where("without_key",$withoutKey)
                ->findAll();
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }

        //處理回傳資料
        if(isset($result[0]["key"])){
            $imgKey = sha1($result[0]["key"]);
        }else{
            return $this->respond(["statusCode"=>"Photo007","msg" => "不存在於資料庫"], 404);
        }

        return $this->respond([
            "statusCode"=>"SUCCESS",
            "msg" => "請求成功",
            "imgKey" => $imgKey
        ], 200);
    }

    /**
     * 新建妝容合成照
     *
     * @return void
     *
     * @OA\Post(
     *     path="/api/v1/synthesize",
     *     tags = {"synthesize"},
     *     description="Create synthesize",
     *     @OA\RequestBody(
     *         description= "以 application/json 格式傳遞圖片資料。",
     *         required= true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="userKey", type="integer", description="使用者Key"),
     *             @OA\Property(property="referenceKey", type="string", description="完妝照KEY"),
     *             @OA\Property(property="withoutKey", type="string", description="素顏照KEY"),
     *             @OA\Property(property="fileName", type="string", description="合成照檔案名稱"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Request is successful.",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="伺服器文字訊息"),
     *              @OA\Property(property="imgKey", type="string",description="主鍵")
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
    public function create(){
        //取得規定資訊
        $data = $this->request->getJSON(true);
        $escData = esc($data);
        try {
            $userKey = $escData["userKey"];
            $referenceKey = $escData["referenceKey"];
            $withoutKey = $escData["withoutKey"];
            $fileName = $escData["fileName"];
        } catch (\Throwable $th) {
            return $this->respond(["statusCode"=>"Photo001","msg" => "傳入欄位具有缺失"], 400);
        }

        //尋找與驗證 key 是否合法
        $keyDecode = \Config\Services::KeyDecode();
        if(!$referenceKey = $keyDecode->getKey(new PhotoReference(),$referenceKey,"referenceKey")){
            return $this->respond($keyDecode->getResponse(), $keyDecode->getCode());
        }
        if(!$withoutKey = $keyDecode->getKey(new PhotoWithout(),$withoutKey,"withoutKey")){
            return $this->respond($keyDecode->getResponse(), $keyDecode->getCode());
        }

        //寫入資料庫
        $createData = [
            'user_key' => $userKey,
            'reference_key' => $referenceKey,
            "without_key" => $withoutKey,
            "name" => $fileName,
            'score' => 0
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
        ],201);
    }

    /**
     * 更新評分
     *
     * @return void
     *
     * @OA\Put(
     *     path="/api/v1/synthesize/{imgKey}",
     *     tags = {"synthesize"},
     *     description="更新妝容照評分",
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
     *             @OA\Property(property="score", type="integer", description="評分")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Request is successful.",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="伺服器文字訊息")
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
    public function update($imgKey = null){
        $data = $this->request->getJSON(true);
        $escData = esc($data);
        try {
            $score = (int)$escData["score"];
        } catch (\Throwable $th) {
            return $this->respond(["statusCode"=>"Photo001","msg" => "傳入欄位具有缺失"], 400);
        }

        if($score < 1 || $score > 5) return $this->respond(["statusCode"=>"Photo009","msg" => "評分值錯誤"], 400);

        //尋找與驗證 key 是否合法
        $keyDecode = \Config\Services::KeyDecode();
        if(!$imgKey = $keyDecode->getKey($this->model,$imgKey,"imgKey")){
            return $this->respond($keyDecode->getResponse(), $keyDecode->getCode());
        }

        //執行更新
        $set = [
            "score" => $score,
        ];
        try {
            $this->model->where('key', $imgKey)
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
     * 刪除妝容照
     *
     * @return void
     *
     * @OA\Delete(
     *     path="/api/v1/synthesize/{imgKey}",
     *     tags = {"synthesize"},
     *     description="Delete synthesize",
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
     *             @OA\Property(property="userKey", type="integer", description="使用者主鍵")
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
    public function delete($imgKey = null){
        //驗證資料傳遞
        $data = $this->request->getJSON(true);
        $escData = esc($data);
        try {
            $userKey = $escData["userKey"];
        } catch (\Throwable $th) {
            return $this->respond(["statusCode"=>"Photo001","msg" => "傳入欄位具有缺失"], 400);
        }

        //尋找與驗證 key 是否合法
        $keyDecode = \Config\Services::KeyDecode();
        if(!$imgKey = $keyDecode->getKey($this->model,$imgKey,"imgKey")){
            return $this->respond($keyDecode->getResponse(), $keyDecode->getCode());
        }
        $result = $keyDecode->getResult();
        $thisImgKey =  $result["key"];
        $thisUserKey = $result["user_key"];
        if($thisUserKey != $userKey) return $this->respond(["statusCode"=>"Photo008","msg" => "使用者驗證失敗"], 400);

        //執行刪除
        try {
            $this->model->delete($thisImgKey);
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(['msg' => '資料庫處理錯誤'], 401);
        }

        return $this->respond([
            "statusCode"=>"SUCCESS",
            "msg" => "請求成功"
        ],200);
    }

}
