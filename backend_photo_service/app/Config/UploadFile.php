<?php namespace Config;

use CodeIgniter\Config\BaseConfig;

class UploadFile extends BaseConfig
{

    public $referencePath = WRITEPATH.DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR."Reference";

    public $synthesizePath = WRITEPATH.DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR."Synthesize";

    public $withoutPath = WRITEPATH.DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR."Without";

}
