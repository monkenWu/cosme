<?php namespace App\Libraries\Microservice;

use App\Libraries\Microservice\GatewayException;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;

class ServiceEntity
{
    private $httpClient;
    private $serviceName;

    public function __construct($serviceURL,$serviceName)
    {
        $this->httpClient = new Client([
            'base_uri' => $serviceURL
        ]);
        $this->serviceName = $serviceName;
    }

    public function action(string $method,string $path,array $requestData = []){
        try {
            $response = $this->httpClient->request(
                $method,
                $path,
                $requestData
            );
        } catch(\GuzzleHttp\Exception\ClientException $th){
            $response = $th->getResponse();
        } catch (\Throwable $th) {
            throw GatewayException::forServiceActionError($this->serviceName,$th->getMessage());
            ;
        }
        return $response;
    }
    
}

?>