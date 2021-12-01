<?php namespace App\Models;

use CodeIgniter\Model;

class PhotoSynthesize extends Model
{
    protected $table      = 'photo_synthesize';
    protected $primaryKey = 'key';

    protected $returnType = 'array';

    protected $allowedFields = ['user_key', 'reference_key', 'without_key', 'creation_key', 'name', 'score'];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
}
