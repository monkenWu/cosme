<?php namespace App\Models;

use CodeIgniter\Model;

class CreationTags extends Model
{
    protected $table      = 'creation_tags';
    protected $primaryKey = 'creation_key';

    protected $returnType = 'array';

    protected $allowedFields = ['creation_key', 'tag_key'];

    protected $useTimestamps = false;
    protected $useSoftDeletes = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
}
