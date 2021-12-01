<?php namespace App\Libraries\TokenVerify;

class UserData
{
    /**
     * 主鍵
     *
     * @var int
     */
    public $key;
    
    /**
     * 使用者名稱
     *
     * @var string
     */
    public $name;

    /**
     * 使用者信箱
     *
     * @var string
     */
    public $email;

    /**
     * 使用者生理性別，1 或 0
     *
     * @var int
     */
    public $sex;

    /**
     * 使用者生日
     *
     * @var string
     */
    public $birth;

    /**
     * 使用者圖像檔案
     *
     * @var string
     */
    public $img;

    /**
     * 是否為商用使用者
     *
     * @var boolean
     */
    public $business;

    /**
     * 使用者所有資訊
     *
     * @var array
     */
    protected $allData;

    /**
     * 傳入使用者資料陣列初始化使用者類別
     *
     * @param array $user
     */
    public function __construct(array $user)
    {
        $this->key = (int)$user["key"];
        $this->name = $user["name"];
        $this->email = $user["email"];
        $this->sex = (int)$user["sex"];
        $this->img = $user["img"];
        $this->business = $user["business"];
        $this->allData = $user;
    }

    /**
     * 取得使用者資料陣列
     *
     * @return array
     */
    public function getAllData():array{
        return $this->allData;
    }
}