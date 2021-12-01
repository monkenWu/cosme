<?php namespace App\Controllers\V1;

use CodeIgniter\RESTful\ResourceController;

class Demo extends ResourceController
{
    
    //protected $modelName = 'App\Models';
    protected $format    = 'json';

    /**
     * List
     *
     * @return void
     *
     * @OA\Get(
     *     path="/api/v1/demo",
     *     tags = {"demo"},
     *     description="List Demo",
     *     @OA\Response(
     *         response=200,
     *         description="Request is successful.",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="msg", type="string",description="message")
     *         )
     *     ),
     * )
     **/
    public function index(){
        return $this->respond([
            "msg" => "index method successful."
        ],200);
    }

    /**
     * Show
     *
     * @return void
     *
     * @OA\Get(
     *     path="/api/v1/demo/{id}",
     *     tags = {"demo"},
     *     description="Show Demo",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Request is successful.",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="id", type = "string", description="id"),
     *              @OA\Property(property="msg", type="string",description="message")
     *         )
     *     ),
     * )
     **/
    public function show($id = null){
        return $this->respond([
            "id" => $id,
            "msg" => "show method successful."
        ],200);
    }

    /**
     * Create
     *
     * @return void
     *
     * @OA\Post(
     *     path="/api/v1/demo",
     *     tags = {"demo"},
     *     description="Create Demo",
     *     @OA\Response(
     *         response=201,
     *         description="Request is successful.",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="msg", type="string",description="message")
     *         )
     *     ),
     * )
     **/
    public function create(){
        return $this->respond([
            "msg" => "create method successful."
        ],201);
    }

    /**
     * Update
     *
     * @return void
     *
     * @OA\Put(
     *     path="/api/v1/demo/{id}",
     *     tags = {"demo"},
     *     description="Update Demo",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Request is successful.",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="id", type = "string", description="id"),
     *              @OA\Property(property="msg", type="string",description="message")
     *         )
     *     ),
     * )
     **/
    public function update($id = null){
        return $this->respond([
            "id" => $id,
            "msg" => "update method successful."
        ],200);
    }

    /**
     * Delete
     *
     * @return void
     *
     * @OA\Delete(
     *     path="/api/v1/demo/{id}",
     *     tags = {"demo"},
     *     description="Delete Demo",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Request is successful.",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="id", type = "string", description="id"),
     *              @OA\Property(property="msg", type="string",description="message")
     *         )
     *     ),
     * )
     **/
    public function delete($id = null){
        return $this->respond([
            "id" => $id,
            "msg" => "delede method successful."
        ],200);
    }

}
