<?php namespace App\Controllers\V1;

use CodeIgniter\RESTful\ResourceController;

class Tag extends ResourceController
{
    
    protected $modelName = 'App\Models\Tag';
    protected $format    = 'json';

    /**
     * Show
     *
     * @return void
     *
     * @OA\Get(
     *     path="/api/v1/tag",
     *     tags = {"Tag"},
     *     description="Show Demo",
     *     @OA\Parameter(
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
    public function index(){
        try {
            $like = $this->request->getGet("like");
            $size = $this->request->getGet("size");
            if($like == null || $size == null)
                return $this->respond(["statusCode"=>"Creation001","msg" => "傳入欄位具有缺失"], 400);
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }

        try {
            $result = $this->model
                ->like('name',$like)
                ->findAll($size,0);
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }

        $returnTags = [];
        foreach ($result as $value) {
            $returnTags[] = $value["name"];
        }

        return $this->respond([
            "statusCode"=>"SUCCESS",
            "msg" => "請求成功",
            "tags" => $returnTags
        ], 200);
    }

    /**
     * Create
     *
     * @return void
     *
     * @OA\Post(
     *     path="/api/v1/tag",
     *     tags = {"Tag"},
     *     description="建立標籤，若標籤於資料庫中以建立，則不重複建立，僅回傳主鍵。",
     *     @OA\RequestBody(
     *         description= "以 application/json 格式傳遞資料。",
     *         required= true,
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Property(
     *                  property="tags", type="array",description="標籤",
     *                  @OA\Items(
     *                      type="string",description="標籤名稱"
     *                  )
     *              )
     *         )
     *     ), 
     *     @OA\Response(
     *         response=201,
     *         description="Request is successful.",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="statusCode", type="string",description="Cosme 狀態碼"),
     *              @OA\Property(property="msg", type="string",description="message"),
     *              @OA\Property(
     *                  property="tags", type="array",description="標籤",
     *                  @OA\Items(
     *                      type="integer",description="標籤主鍵"
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
    public function create(){
        //取得規定資訊
        $data = $this->request->getJSON(true);
        $escData = esc($data);
        try {
            $tags = $escData["tags"];
        } catch (\Throwable $th) {
            return $this->respond(["statusCode"=>"Tag001","msg" => "傳入欄位具有缺失"], 400);
        }

        // 判斷是否存在於資料庫中，若是則使用目前已有的主鍵，若否則新建新的 tag
        $returnKeys = [];
        foreach ($tags as $value) {
            try {
                $result = $this->model->where("name",$value)->findAll();
            } catch (\Throwable $th) {
                log_message('critical', $th->__toString());
                return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
            }
            if(count($result) == 0){
                try {
                    $tagKey = $this->model->insert([
                        "name" => $value
                    ]);
                } catch (\Throwable $th) {
                    if($th->getCode() == 1062){
                        $returnKeys[] = (int)$this->model->where("name",$value)->findAll()[0]["key"];
                        continue;
                    }else{
                        log_message('critical', $th->__toString());
                        return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);    
                    }
                }
                $returnKeys[] = (int)$tagKey;
            }else{
                $returnKeys[] = (int)$result[0]["key"];
            }
        }
        
        return $this->respond([
            "statusCode"=>"Created",
            "msg" => "建立成功",
            "tags" => $returnKeys
        ], 201);
    }

}
