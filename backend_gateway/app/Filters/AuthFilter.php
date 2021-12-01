<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\API\ResponseTrait;

class AuthFilter implements FilterInterface
{
    use ResponseTrait;

    /**
     * Response Object
     *
     * @var \CodeIgniter\HTTP\Response
     */
    protected $response;

    public function before(RequestInterface $request, $arguments = null)
    {
        $this->response = \config\Services::response(config(App::class),false);
        $this->request = $request;
        $auth = \config\Services::auth();
        $accessToken = $request->getHeaderLine("Access-Token");
        if(!$auth->doVarify($accessToken)){
            $this->response->setHeader("Access-Control-Allow-Origin","*");
            $this->response->setHeader("Access-Control-Allow-Credentials","true");
            $this->response->setHeader("Access-Control-Allow-Headers","Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers, Access-Token, Refresh-Token");
            if(strcasecmp($request->getMethod(),"options") == 0){
                $this->response->setHeader("Access-Control-Allow-Methods","DELETE, PUT, POST, GET, OPTIONS");
            }
            return $this->respond($auth->getResultData(),$auth->getStatusCode());
        }
        return $request;
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