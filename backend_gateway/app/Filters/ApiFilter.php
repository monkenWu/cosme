<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class ApiFilter implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {
       
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $response->setHeader("Access-Control-Allow-Origin","*");
        $response->setHeader("Access-Control-Allow-Credentials","true");
        $response->setHeader("Access-Control-Allow-Headers","Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers, Access-Token, Refresh-Token");
        if(strcasecmp($request->getMethod(),"options") == 0){
            $response->setHeader("Access-Control-Allow-Methods","DELETE, PUT, POST, GET, OPTIONS");
        }
    }

}