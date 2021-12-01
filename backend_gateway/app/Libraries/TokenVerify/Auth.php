<?php namespace App\Libraries\TokenVerify;

class Auth
{

    /**
     * user service entity
     *
     * @var \App\Libraries\Microservice\ServiceEntity
     */
    protected $userService;

    /**
     * request result data by jsondecode()
     *
     * @var array
     */
    protected $resultData;

    /**
     * userdata
     *
     * @var \App\Libraries\TokenVerify\UserData
     */
    protected $userData;

    /**
     * http status code
     *
     * @var int
     */
    protected $statusCode;

    public function __construct()
    {
        $gateway = \config\Services::gateway();
        $this->userService = $gateway->getServiceEntity("user_service");
    }

    /**
     * 驗證 accessToken 是否合法
     *
     * @param String $accessToken
     * @return Boolean 是否合法
     */
    public function doVarify(String $accessToken):bool{
        $authResult = $this->userService->action(
            "GET",
            "/api/v1/auth",
            [
                'headers' => [
                    'Access-Token' => $accessToken
                ]
            ]
        );
        $this->statusCode = $authResult->getStatusCode();
        $this->resultData = json_decode($authResult->getBody(),true);

        if($this->statusCode >= 400 && $this->statusCode < 500){
            return false;
        }

        $this->userData = new UserData($this->resultData["data"]);
        return true;
    }

    public function getUserData(){
        return $this->userData;
    }

    public function getStatusCode():int{
        return $this->statusCode;
    }

    public function getResultData():array{
        return $this->resultData;
    }

}