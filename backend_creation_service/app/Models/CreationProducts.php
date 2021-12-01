<?php namespace App\Models;

use CodeIgniter\Model;

class CreationProducts extends Model
{
    protected $table      = 'creation_products';
    protected $primaryKey = 'key';

    protected $returnType = 'array';

    protected $allowedFields = ['creation_key','name', 'imgpath', 'url', 'intro'];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

}
