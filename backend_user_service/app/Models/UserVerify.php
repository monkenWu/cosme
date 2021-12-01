<?php namespace App\Models;

class UserVerify
{
    private $rule = [
        'name' => '/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]{2,11}$/u',
        'email' => '/^([a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4})*$/',
        'sex' => '/^(0|1)$/',
        'birth' => '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/',
        'password' => '/^.*(?=.{5,20}).*$/',
        'business' => '/^(0|1)$/'
    ];

    private $errStatusCode = [
        'name' => 'User006',
        'email' => 'User007',
        'sex' => 'User008',
        'birth' => 'User009',
        'password' => 'User010',
        'business' => 'User011'
    ];

    private $errMessage = [
        'name' => '使用者名稱不可存在特殊字符，最少兩個字以上，十個字以下',
        'email' => '信箱格式錯誤',
        'sex' => '性別格式錯誤',
        'birth' => '生日格式錯誤',
        'password' => '密碼格式錯誤，可使用英文、數字，以及常見特殊字元，最少五個字，最多二十字以下',
        'business' => '商業確認欄位錯誤，請傳入 true 或 false'
    ];

    /**
     * 驗證字串是否符合規則
     *
     * @param string|array $data 待驗證的字串或陣列
     * @param string $type 使用的規則
     * @return boolean|array 判斷結果
     */
    public function doVerify($data,string $type = null){

        if (is_array($data)){
			foreach ($data as $key => $value){
                if(!$this->doVerify($value,$key)){
                    return [
                        "status" => false,
                        "statusCode" => $this->getStatusCode($key),
                        "message" => $this->getMsg($key)
                    ];
                }
            }
            return ["status" => true];
        }

        if (preg_match($this->rule[$type],$data)) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * 取得錯誤訊息
     *
     * @param string $type 使用的規則
     * @return string 錯誤訊息
     */
    public function getMsg(string $type):string{
        return $this->errMessage[$type];
    }

    /**
     * 取得 Cosme 狀態碼
     *
     * @param string $type 使用的規則
     * @return string Cosme 狀態碼
     */
    public function getStatusCode(string $type):string{
        return $this->errStatusCode[$type];
    }

    /**
     * 取得所有可用的欄位屬性名稱。
     *
     * @return array
     */
    public function getFieldKeys():array{
        return array_keys($this->rule);
    }

}
