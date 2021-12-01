<?php namespace App\Models;

use CodeIgniter\Model;

class Tag extends Model
{
    protected $table      = 'tag';
    protected $primaryKey = 'key';

    protected $returnType = 'array';

    protected $allowedFields = ['name'];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
}
