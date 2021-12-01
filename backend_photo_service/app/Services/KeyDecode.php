<?php namespace App\Services;

class KeyDecode
{
    private $_response;
    private $_code;
    private $_result;

    /**
     * 取得被 SHA1 加密之主鍵
     *
     * @param \CodeIgniter\Model $model 傳入模型
     * @param string $key 傳入欲轉換的 Key 字串
     * @param string $keyName 傳入此字串的名稱
     * @return int|bool
     */
    public function getKey(\CodeIgniter\Model $model,string $key,string $keyName = ""){
        try {
            $result = $model
                ->where("sha1(`key`)",$key)
                ->findAll();
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            $this->_response = ["statusCode"=>"DBError","msg" => "資料庫處理失敗"];
            $this->_code = 400;
            return false;
        }
        if(count($result) == 0){
            $this->_response = ["statusCode"=>"DBErrorKey","msg" => "{$keyName}主鍵驗證失敗"];
            $this->_code = 400;
            return false;
        }
        $this->_result = $result[0];
        return $result[0]["key"];
    }

    /**
     * 回傳響應陣列
     *
     * @return string
     */
    public function getResponse():array{
        return $this->_response;
    }

    /**
     * 回傳 HTTP 狀態碼
     *
     * @return string
     */
    public function getCode():int{
        return $this->_code;
    }

    /**
     * 回傳 Result 陣列內容
     *
     * @return array
     */
    public function getResult():array{
        return $this->_result;
    }

}
