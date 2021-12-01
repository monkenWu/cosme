<?php namespace App\Libraries;

class Verify
{
    /**
     * 規則名稱與正規表示法
     *
     * @var array
     */
    protected $rule = [
        "ruleName" => "regex"
    ];

    /**
     * 規則名稱與自訂錯誤碼
     *
     * @var array
     */
    protected $errStatusCode = [
        'ruleName' => 'String',
    ];

    /**
     * 規則名稱與自訂錯誤說明
     *
     * @var array
     */
    protected $errMessage = [
        'ruleName' => 'String'
    ];

    /**
     * 驗證字串是否符合規則
     *
     * @param string $data 待驗證陣列
     * @param string $type 使用的規則
     * @return boolean|array 判斷結果
     */
    public function doVerify($data, string $type = null)
    {
        if (is_array($data)){
            foreach ($data as $key => $value){
                if(!$this->doVerify($value, $key)){
                    return [
                        "status" => false,
                        "statusCode" => $this->getStatusCode($key),
                        "message" => $this->getMsg($key)
                    ];
                }
            }
            return ["status" => true];
        }

        if (preg_match($this->rule[$type], $data)) {
            return true;
        }else {
            return false;
        }
    }

    /**
     * 取得自訂狀態碼
     *
     * @param string $type 使用的規則
     * @return string 自訂狀態碼
     */
    public function getStatusCode(string $type):string{
        return $this->errStatusCode[$type];
    }

    /**
     * 取得錯誤訊息
     */
    public function getMsg(string $type){
        return $this->errMessage[$type];
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
