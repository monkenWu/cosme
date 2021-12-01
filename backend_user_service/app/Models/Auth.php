<?php namespace App\Models;

use CodeIgniter\Model;

class Auth extends Model
{
    protected $table      = 'user_auth';
    protected $primaryKey = 'key';

    protected $returnType = 'array';

    protected $allowedFields = ['user_key', 'access_token', 'refresh_token', 'user_agent', 'user_ip'];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
}
