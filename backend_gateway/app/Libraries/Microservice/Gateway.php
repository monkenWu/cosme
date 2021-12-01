<?php namespace App\Libraries\Microservice;

use App\Libraries\Microservice\GatewayException;
use App\Libraries\Microservice\ServiceEntity;

class Gateway
{

    private $servicesEntity = [];
    private $serviceList = [];

    public function __construct()
    {
        $config = \CodeIgniter\Config\Config::get("Gateway");
        $this->serviceList = $config->services;
    }

    /**
     * 取得單一服務實體
     *
     * @param String $serviceName
     * @param boolean $getShared
     * @return ServiceEntity
     */
    public function getServiceEntity(String $serviceName,bool $getShared = true):ServiceEntity{
        if(!isset($this->serviceList[$serviceName])){ 
            throw GatewayException::forServiceNotFound($serviceName);
        }
        $path = $this->serviceList[$serviceName];
        if ($getShared){
            if(isset($this->servicesEntity[$serviceName])){
                return $this->servicesEntity[$serviceName];
            }
            $this->servicesEntity[$serviceName] = new ServiceEntity($path,$serviceName);
            return $this->servicesEntity[$serviceName];
        }
		return new ServiceEntity($path,$serviceName);
    }

    /**
     * 重置所有共享服務實體
     *
     * @return void
     */
    public function reset(){
        $this->servicesEntity = [];
    }

}

?>