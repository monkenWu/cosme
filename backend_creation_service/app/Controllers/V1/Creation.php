<?php namespace App\Controllers\V1;

use CodeIgniter\RESTful\ResourceController;
use App\Models\CreationVerify;
use App\Models\CreationTags;
use App\Models\CreationProducts;

class Creation extends ResourceController
{
    
    protected $modelName = 'App\Models\Creation';
    protected $format    = 'json';

    /**
     * List
     *
     * @return void
     *
     * @OA\Get(
     *     path="/api/v1/creation",
     *     tags = {"creation"},
     *     description="取得使用者所有文章",
     *     @OA\Parameter(
     *         name="userID",
     *         in="query",
     *         description="使用者主鍵",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
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
     *                       @OA\Property(property="key", type="string",description="妝容主鍵"),
     *                       @OA\Property(property="imgKey", type="string",description="參考用上妝照主鍵"),
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
    public function index(){
        try {
            $user_key = $this->request->getGet("userID");
            $offset = $this->request->getGet("offset");
            $size = $this->request->getGet("size");
            if($user_key == null || $offset == null || $size == null)
                return $this->respond(["statusCode"=>"Creation001","msg" => "傳入欄位具有缺失"], 400);
            $result = $this->model
                ->orderBy('created_at','DESC')
                ->where('user_key',$user_key)
                ->findAll($size,$offset);
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }
        
        $creationTagsModel = new CreationTags();
        $creationProtuctsModel = new CreationProducts();
        $outputData = [];
        foreach ($result as $row) {
            try {
                $creationTagsResult = $creationTagsModel
                    ->join('tag','tag_key = tag.key')
                    ->where('creation_key',$row["key"])
                    ->findAll();
                $tags =[];
                foreach ($creationTagsResult as $value) {
                    $tags[] = $value["name"];
                }
            } catch (\Throwable $th) {
                log_message('critical', $th->__toString());
                return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
            }
            try {
                $creationProtuctsResult = $creationProtuctsModel
                    ->where('creation_key',$row["key"])
                    ->findAll();
                $products = [];
                foreach ($creationProtuctsResult as $value) {
                    $products[] = [
                        "name" => $value["name"],
                        "imgpath" => $value["imgpath"],
                        "url" => $value["url"],
                        "intro" => $value["intro"],
                    ];
                }
            } catch (\Throwable $th) {
                log_message('critical', $th->__toString());
                return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
            }    
            $outputData[] = [
                "key" => sha1($row["key"]),
                "imgKey" => sha1($row["photo_reference_key"]),
                "title" => $row["title"],
                "content" => $row["content"],
                "createdAt" => $row["created_at"],
                "updatedAt" => $row["updated_at"],
                "tags" => $tags,
                "products" => $products
            ];
        }

        $count = 0;
        try {
            $countResult = $this->model
                ->select("count(`key`) as count")
                ->where('user_key',$user_key)
                ->findAll();
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }
        try {
            $count = $countResult[0]['count'];
        } catch (\Throwable $th) {
            $count = 0;
        }

        return $this->respond([
            "statusCode"=>"SUCCESS",
            "msg" => "請求成功",
            "data" => $outputData,
            "amount" => (int)$count
        ], 200);
    }

    /**
     * all POST
     *
     * @return void
     *
     * @OA\Post(
     *     path="/api/v1/creation/all",
     *     tags = {"creation"},
     *     description="不分會員取得所有列表",
     *      @OA\Parameter(
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
     *                       @OA\Property(property="photoReferenceKey", type="string",description="參考用上妝照主鍵"),
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
    public function allCreation(){

        $data = $this->request->getJSON(true);
        $escData = esc($data);
        try {
            $tags = $escData["tags"];
        } catch (\Throwable $th) {
            $tags = false;
        }

        try {
            $offset = $this->request->getGet("offset");
            $size = $this->request->getGet("size");
            $like = $this->request->getGet("like");
            if($offset == null || $size == null)
                return $this->respond(["statusCode"=>"Creation001","msg" => "傳入欄位具有缺失"], 400);
            $select = "
                creation.key,
                creation.title,
                creation.content,
                creation.created_at,
                creation.updated_at,
                creation.photo_reference_key,user.name as author_name,
                user.key as author_key,user.img as author_img,
                creation.key as creation_prekey
            ";

            $this->model
                ->select($select)
                ->join('user','creation.user_key = user.key')
                ->orderBy('creation.created_at','DESC');

            //判斷是否需要字串搜索
            if($like){
                $this->model
                    ->like("creation.title",$like)
                    ->orLike("creation.content",$like)
                    ->orLike("user.name",$like);
            }

            //判斷是否需要標籤搜索
            if($tags && count($tags) > 0){
                $tagsSQL = "
                    SELECT GROUP_CONCAT(`tag`.`name`)
                    FROM `creation_tags`
                    JOIN `tag` ON `tag_key` = `tag`.`key`
                    WHERE `creation_key` =  `creation`.`key`
                ";
                foreach ($tags as $value) {
                    $this->model->like("({$tagsSQL})",$value);
                }
            }

            $countBuilder = clone $this->model->__get("builder");
            $count = $countBuilder->where("deleted_at IS NULL")->countAllResults();
            $result = $this->model->findAll($size,$offset);

        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }

        $creationTagsModel = new CreationTags();
        $creationProtuctsModel = new CreationProducts();
        $outputData = [];
        foreach ($result as $row) {
            try {
                $creationTagsResult = $creationTagsModel
                    ->join('tag','tag_key = tag.key')
                    ->where('creation_key',$row["key"])
                    ->findAll();
                $tags =[];
                foreach ($creationTagsResult as $value) {
                    $tags[] = $value["name"];
                }
            } catch (\Throwable $th) {
                log_message('critical', $th->__toString());
                return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
            }
            try {
                $creationProtuctsResult = $creationProtuctsModel
                    ->where('creation_key',$row["key"])
                    ->findAll();
                $products = [];
                foreach ($creationProtuctsResult as $value) {
                    $products[] = [
                        "name" => $value["name"],
                        "imgpath" => $value["imgpath"],
                        "url" => $value["url"],
                        "intro" => $value["intro"],
                    ];
                }
            } catch (\Throwable $th) {
                log_message('critical', $th->__toString());
                return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
            }    
            $outputData[] = [
                'key' => sha1($row["key"]),
                "imgKey" => sha1($row["photo_reference_key"]),
                "title" => $row["title"],
                "content" => $row["content"],
                "createdAt" => $row["created_at"],
                "updatedAt" => $row["updated_at"],
                "tags" => $tags,
                "author" => [
                    "name" => $row["author_name"],
                    "key" => sha1($row["author_key"]),
                    "img" => $row["author_img"] ?? ""
                ],
                "products" => $products
            ];
        }

        return $this->respond([
            "statusCode"=>"SUCCESS",
            "msg" => "請求成功",
            "data" => $outputData,
            "amount" => $count
        ], 200);
    }

    /**
     * 取得單筆貼文
     *
     * @return void
     *
     * @OA\Get(
     *     path="/api/v1/creation/{creationID}",
     *     tags = {"creation"},
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
     *                  @OA\Property(property="photoReferenceKey", type="string",description="參考用上妝照主鍵"),
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
     *                  ),
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
    public function show($creationID = null){
        //尋找與驗證 key 是否合法
        try {
            $result = $this->model
                ->where("sha1(`key`)",$creationID)
                ->findAll();
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }
        if(count($result) == 0) return $this->respond(["statusCode"=>"Creation007",'msg' => '找不到 creationID'], 404);
        $creationID =  $result[0]["key"];

        try {
            $select = "creation.key,creation.title, creation.content, creation.created_at, creation.updated_at, creation.photo_reference_key,user.name as author_name, user.key as author_key,user.img as author_img";
            $result = $this->model
                ->select($select)
                ->join('user','creation.user_key = user.key')
                ->find($creationID);
            if ($result == null) {
                return $this->respond(["statusCode"=>"Creation006","msg" => "creationID 不存在於資料庫"], 404);
            }
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }

        $creationTagsModel = new CreationTags();
        $tags =[];
        try {
            $creationTagsResult = $creationTagsModel
                ->join('tag','tag_key = tag.key')
                ->where('creation_key',$creationID)
                ->findAll();
            foreach ($creationTagsResult as $value) {
                $tags[] = $value["name"];
            }
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }

        $creationProtuctsModel = new CreationProducts();
        $products = [];
        try {
            $creationProtuctsResult = $creationProtuctsModel
                ->where('creation_key',$creationID)
                ->findAll();
            foreach ($creationProtuctsResult as $row) {
                $products[] = [
                    "name" => $row["name"],
                    "imgpath" => $row["imgpath"],
                    "url" => $row["url"],
                    "intro" => $row["intro"],
                ];
            }
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }


        $outputData = [
            'key' => sha1($result["key"]),
            "imgKey" => sha1($result["photo_reference_key"]),
            "title" => $result["title"],
            "content" => $result["content"],
            "createdAt" => $result["created_at"],
            "updatedAt" => $result["updated_at"],
            "tags" => $tags,
            "author" => [
                "name" => $result["author_name"],
                "key" => sha1($result["author_key"]),
                "img" => $result["author_img"] ?? ""
            ],
            "products" => $products
        ];

        return $this->respond([
            "statusCode"=>"SUCCESS",
            "msg" => "請求成功",
            "data" => $outputData
        ]);
    }

    /**
     * Create
     *
     * @return void
     *
     * @OA\Post(
     *     path="/api/v1/creation",
     *     tags = {"creation"},
     *     description="建立新的貼文",
     *     @OA\RequestBody(
     *          description= "以 application/json 格式傳遞內容",
     *          required= true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="userKey", type="integer",description="使用者主鍵"),
     *              @OA\Property(property="photoReferenceKey", type="integer",description="參考用上妝照主鍵"),
     *              @OA\Property(property="title", type="string",description="文章標題"),
     *              @OA\Property(property="content", type="string",description="文章內容"),
     *              @OA\Property(
     *                  property="tags", type="array",description="標籤",
     *                  @OA\Items(
     *                      type="integer",description="標籤主鍵"
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
     *              @OA\Property(property="creationKey", type="string",description="已生成主鍵"),
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
            $createData = [
                "user_key" => $escData["userKey"],
                "photo_reference_key" => $escData["photoReferenceKey"],
                "title" => $escData["title"],
                "content" => $escData["content"]
            ];
            $tags = $escData["tags"];
        } catch (\Throwable $th) {
            return $this->respond(["statusCode"=>"Creation001","msg" => "傳入欄位具有缺失"], 400);
        }

        //驗證欄位內容
        $veriModel = new CreationVerify();
        $verify = $veriModel->doVerify($createData);
        if (!$verify["status"]) {
            return $this->respond(["statusCode"=>$verify['statusCode'],"msg" => $verify['message']], 400);
        }

        //寫入資料庫
        try {
            $creationKey = $this->model->insert($createData);
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }

        //處理 tag 寫入
        $tagCreateData = [];
        foreach ($tags as $value) {
            $tagCreateData[] = [
                "creation_key" => $creationKey,
                "tag_key" => $value
            ];
        }
        $creationTagsModel = new CreationTags();
        try {
            $creationTagsModel->insertBatch($tagCreateData);
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }

        return $this->respond([
            "statusCode"=>"Created",
            "msg" => "建立成功",
            "creationKey" => sha1($creationKey)
        ], 201);

    }

    /**
     * 更新
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
     *                      type="integer",description="標籤主鍵"
     *                  )
     *              )
     *         )
     *     ), 
     *     @OA\Parameter(
     *         name="userKey",
     *         in="query",
     *         description="使用者主鍵",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
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
    public function update($creationID = null){
        try {
            $userKey = $this->request->getGet("userKey");
            if($userKey == null)
                return $this->respond(["statusCode"=>"Creation001","msg" => "傳入欄位具有缺失"], 400);
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }

        // 尋找與驗證 key 是否合法
        try {
            $result = $this->model
                ->where("sha1(`key`)",$creationID)
                ->where('user_key',$userKey)
                ->findAll();
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }
        if(count($result) == 0) return $this->respond(["statusCode"=>"Creation008",'msg' => '找不到 creationID 與 user_key'], 404);
        $creationID =  $result[0]["key"];

        // 驗證與剃除不可修改的格式
        $verifyModel = new CreationVerify();
        $inputData = esc($this->request->getJSON(true));
        $field = $verifyModel->getFieldKeys();
        $updateData = [];
        foreach ($field as $value) {
            if(isset($inputData[$value])){
                $updateData[$value] = $inputData[$value];
            }
        }
        if(isset($updateData["user_key"])) unset($updateData["user_key"]) ;
        if(isset($updateData["photo_reference_key"])) unset($updateData["photo_reference_key"]) ;
        if(count($updateData) == 0){
            return $this->respond(["statusCode"=>"Creation001","msg" => "傳入欄位具有缺失"], 400);
        }
        // 判定格式
        $verifyData = $verifyModel->doVerify($updateData);
        if (!$verifyData["status"]) {
            return $this->respond(["statusCode"=>$verifyData['statusCode'],"msg" => $verifyData['message']], 400);
        }

        // 更新資料庫
        try {
            $this->model->update($creationID,$updateData);
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }

        // Tag 處理
        if(isset($inputData["tags"])){
            // 執行刪除 Tag
            $creationTagsModel = new CreationTags();
            try {
                $creationTagsModel->delete($creationID);
            } catch (\Throwable $th) {
                log_message('critical', $th->__toString());
                return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
            }
            $tagCreateData = [];
            foreach ($inputData["tags"] as $value) {
                $tagCreateData[] = [
                    "creation_key" => $creationID,
                    "tag_key" => $value
                ];
            }
            // 處理新 Tag
            try {
                $creationTagsModel->insertBatch($tagCreateData);
            } catch (\Throwable $th) {
                log_message('critical', $th->__toString());
                return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
            }
        }

        return $this->respond([
            "statusCode"=>"SUCCESS",
            "msg" => "請求成功",
        ], 200);
    }

    /**
     * Delete
     *
     * @return void
     *
     * @OA\Delete(
     *     path="/api/v1/creation/{creationID}",
     *     tags = {"creation"},
     *     description="透過 creationID 刪除文章",
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
        try {
            $userKey = $this->request->getGet("userKey");
            if($userKey == null)
                return $this->respond(["statusCode"=>"Creation001","msg" => "傳入欄位具有缺失"], 400);
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }

        // 尋找與驗證 key 是否合法
        try {
            $result = $this->model
                ->where("sha1(`key`)",$creationID)
                ->where('user_key',$userKey)
                ->findAll();
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"DBError","msg" => "資料庫處理失敗"], 400);
        }
        if(count($result) == 0) return $this->respond(["statusCode"=>"Creation008",'msg' => '找不到 creationID 與 user_key'], 404);
        $creationID =  $result[0]["key"];
        $imgKey = $result[0]["photo_reference_key"];

        //執行刪除
        try {
            $this->model->delete($creationID);
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(['msg' => '資料庫處理錯誤'], 400);
        }

        return $this->respond([
            "statusCode"=>"SUCCESS",
            "msg" => "請求成功",
            "imgKey" => sha1($imgKey)
        ],200);
    }

}
