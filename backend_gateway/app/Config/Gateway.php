<?php namespace Config;

use CodeIgniter\Config\BaseConfig;

class Gateway extends BaseConfig
{

    // ["name"=>"ip:port"]
    public $services = [
        "user_service" => "http://localhost:8081",
        "photo_service" => "http://localhost:8082",
        "creation_service" => "http://localhost:8083",
        "makeup_service" => "http://localhost:8084",
    ];

}
