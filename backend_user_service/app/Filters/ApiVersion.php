<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class ApiVersion implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {
        // if($request->getHeaderLine("Back-Version") == ""){
        //     throw new \CodeIgniter\Exceptions\PageNotFoundException("api back-version not found");
        // }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }

}