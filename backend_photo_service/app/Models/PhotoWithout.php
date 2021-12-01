<?php namespace App\Models;

use CodeIgniter\Model;

class PhotoWithout extends Model
{
    protected $table      = 'photo_without';
    protected $primaryKey = 'key';

    protected $returnType = 'array';

    protected $allowedFields = ['user_key', 'tumbnial', 'full', 'is_default'];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
}
