<?php

namespace App\Models;

use App\Libraries\Verify;

class PhotoVerify extends Verify
{

    protected $rule = [
        'userKey' => '/\d+/',
        'img' => '/^(data:\s*image\/(\w+);base64,)/',
        'isDefault' => '/0|1/'
    ];

    protected $errStatusCode = [
        'userKey' => 'Photo002',
        'img' => 'Photo003',
        'isDefault' => 'Photo004'
    ];

    protected $errMessage = [
        'userKey' => '使用者主鍵格式錯誤，使用者主鍵只能是數字',
        'img' => '圖片文件格式錯誤，圖片格式限定為base64',
        'isDefault' => '是否為預設圖片格式錯誤，只能傳入 0 或 1',
    ];

}
