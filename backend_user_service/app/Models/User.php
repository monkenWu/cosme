<?php namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table      = 'user';
    protected $primaryKey = 'key';

    protected $returnType = 'array';

    protected $allowedFields = ['name', 'email', 'password', 'sex', 'birth', 'img','business'];

    protected $useTimestamps = true;
    protected $useSoftDeletes = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
}
