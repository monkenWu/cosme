<?php namespace App\Services;

use \Firebase\JWT\JWT;

class Auth
{

    /**
     * 簽發 TOKEN
     *
     * @param array $data
     * @return array Tokens 索引陣列
     */
	public function siginToken(array $data):array{
        $time = time(); //當前時間
        $tokens = [
            "accessToken" => $this->createAccessToken($data,$time),
            "refreshToken" => $this->createRefreshToken($data,$time)
        ];
        return $tokens;
    }

     /**
     * 簽發 Access Token
     *
     * @param array $data 欲儲存資訊
     * @param int $time 時間戳
     * @return string Token 字串
     */
	public function createAccessToken(array $data,$time = null):string{
        if($time === null) $time = time(); //當前時間
        $data["time"] = $time;
        $accessToken = [
            'iss' => env("JWT_ISS"), //簽發者 可選
            'aud' => env("JWT_AUD"), //接收該JWT的一方，可選
            'iat' => $time, //簽發時間
            'nbf' => $time + (int)env("JWT_ACCESS_NBF") ,//(Not Before)：某個時間點後才能訪問，比如設置time+30，表示當前時間30秒後才能使用
            'exp' => $time + (int)env("JWT_ACCESS_EXP"), //過期時間
            'data' => $data
        ];
        $token = $this->base64urlEncode(
            JWT::encode(
                $accessToken,
                env("JWT_KEY"),
                env("JWT_ENCODE_ALG")
            )
        );
        return $token;
    }

    /**
     * 簽發 Refresh Token
     *
     * @param array $data 欲儲存資訊
     * @param int $time 時間戳
     * @return string Token 字串
     */
	public function createRefreshToken(array $data,$time = null):string{
        if($time === null) $time = time(); //當前時間
        $data["time"] = $time;
        $refreshToken = [
            'iss' => env("JWT_ISS"), //簽發者 可選
            'aud' => env("JWT_AUD"), //接收該JWT的一方，可選
            'iat' => $time, //簽發時間
            'nbf' => $time + (int)env("JWT_REFRESH_NBF") ,//(Not Before)：某個時間點後才能訪問，比如設置time+30，表示當前時間30秒後才能使用
            'exp' => $time + (int)env("JWT_REFRESH_EXP"), //過期時間
            'data' => $data
        ];
        $token = $this->base64urlEncode(
            JWT::encode(
                $refreshToken,
                env("JWT_KEY"),
                env("JWT_ENCODE_ALG")
            )
        );
        return $token;
    }

    /**
     * 驗證傳入的 token 是否合法
     *
     * @param string $token
     * @return array 解析成功內容
     */
    public function verification(string $token):array{
        try {
            JWT::$leeway = 60;
            $decoded = JWT::decode(
                $this->base64urlDecode($token),
                env("JWT_KEY"),
                [env("JWT_ENCODE_ALG")]
            );
            return [
                "statusCode"=>"SUCCESS",
                "msg" => "解析成功",
                "data" => (array)$decoded->data,
                "code" => 200
            ];    
        } catch(\Firebase\JWT\SignatureInvalidException $e) {
            return [
                "statusCode"=>"Auth002",
                "msg" => "數位簽章驗證失敗，資料可能被竄改",
                "code" => 400
            ];
        }catch(\Firebase\JWT\BeforeValidException $e) {
            return [
                "statusCode"=>"Auth003",
                "msg" => "簽名在某個時間點後才能夠使用",
                "code" => 400
            ];
        }catch(\Firebase\JWT\ExpiredException $e) {
            return [
                "statusCode"=>"Auth004",
                "msg" => "Token 已經過期",
                "code" => 403
            ];
        }catch(\Exception $e) {
            return [
                "statusCode"=>"Auth005",
                "msg" => "Token 不符合 JWT 格式，或驗證過程發生未知的錯誤",
                "code" => 400
            ];
        }
    }

    /**
     * 傳入字串後輸出相容於 URL 的 base64 編碼
     *
     * @param string $token
     * @return string
     */
    private function base64urlEncode(string $token) : string {
        return rtrim(strtr(base64_encode($token), '+/', '-_'), '=');
    }

    /**
     * 解碼相容於 URL 的 base64 編碼
     *
     * @param string $token
     * @return string
     */
    private function base64urlDecode(string $token) : string {
        return base64_decode(str_pad(strtr($token, '-_', '+/'), strlen($token) % 4, '=', STR_PAD_RIGHT));
    }
    
}
