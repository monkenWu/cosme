<?php namespace App\Models;

use CodeIgniter\Model;

class Creation extends Model
{
    protected $table      = 'creation';
    protected $primaryKey = 'key';

    protected $returnType = 'array';

    protected $allowedFields = ['user_key','photo_reference_key', 'title', 'content'];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
}
