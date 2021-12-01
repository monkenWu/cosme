<?php namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

class Home extends BaseController
{
	use ResponseTrait;

	public function index()
	{
		return $this->respond(["msg"=>"on"],200);
	}

	//--------------------------------------------------------------------
}
