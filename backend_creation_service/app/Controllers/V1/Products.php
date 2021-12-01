<?php namespace App\Controllers\V1;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Creation;
use Symfony\Component\Yaml\Dumper;

class Products extends ResourceController
{
    
    protected $modelName = 'App\Models\CreationProducts';
    protected $format    = 'json';

    /**
     * 更新產品資訊
     *
     * @return void
     *
     * @OA\Put(
     *     path="/api/v1/products/{creationID}",
     *     tags = {"products"},
     *     description="更新產品資訊",
     *     @OA\Parameter(
     *         name="creationID",
     *         in="path",
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
     *              @OA\Property(
     *                  property="data", type="array",description="產品描述物件",
     *                  @OA\Items(
     *                       type="object",description="單筆圖片內容",
     *                       @OA\Property(property="name", type="string",description="產品名稱"),
     *                       @OA\Property(property="imgpath", type="string",description="產品圖片網址"),
     *                       @OA\Property(property="url", type="string",description="產品網址"),
     *                       @OA\Property(property="intro", type="string",description="產品介紹")
     *                  )
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
    public function update($creationID = null){
        
        //取得規定資訊
        $data = $this->request->getJSON(true);
        $escData = esc($data);
        try {
            $productsData = $escData["data"];
        } catch (\Throwable $th) {
            return $this->respond(["statusCode"=>"Creation001","msg" => "傳入欄位具有缺失"], 400);
        }
        
        //尋找與驗證 key 是否合法
        $keyDecode = \Config\Services::KeyDecode();
        if(!$creationKey = $keyDecode->getKey(new Creation(),$creationID,"creationID")){
            return $this->respond($keyDecode->getResponse(), $keyDecode->getCode());
        }

        //建構寫入資料
        $datas = [];
        $date = date('Y-m-d H:i:s', time());
        try {
            foreach ($productsData as $product) {
                $datas[] = [
                    "creation_key" => $creationKey,
                    "name"=>$product["name"],
                    "imgpath" => $product["imgpath"],
                    "url" => $product["url"],
                    "intro" => $product["intro"],
                    "updated_at" => $date,
                    "created_at" => $date
                ];
            }    
        } catch (\Throwable $th) {
            return $this->respond(["statusCode"=>"Creation001","msg" => "傳入欄位具有缺失"], 400);
        }

        //寫入資料庫
        try {
            $this->model->where('creation_key', $creationKey)->delete();
            $this->model->insertBatch($datas);
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(['msg' => '資料庫處理錯誤'], 400);
        }

        return $this->respond([
            "statusCode"=>"SUCCESS",
            "msg" => "請求成功",
        ],200);
    }

    /**
     * 刪除產品
     *
     * @return void
     *
     * @OA\Delete(
     *     path="/api/v1/products/{creationID}",
     *     tags = {"products"},
     *     description="透過 creationID 刪除產品",
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
    public function delete($creationID = null){

        //尋找與驗證 key 是否合法
        $keyDecode = \Config\Services::KeyDecode();
        if(!$creationKey = $keyDecode->getKey(new Creation(),$creationID,"creationID")){
            return $this->respond($keyDecode->getResponse(), $keyDecode->getCode());
        }
        
        //移除資料
        try {
            $this->model->where('creation_key', $creationKey)->delete();
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(['msg' => '資料庫處理錯誤'], 400);
        }

        return $this->respond([
            "statusCode"=>"SUCCESS",
            "msg" => "請求成功",
        ],200);
    }

}
