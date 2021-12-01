<?php

namespace App\Models;

use App\Libraries\Verify;

class CreationVerify extends Verify
{

    protected $rule = [
        'user_key' => '/\d+/',
        'photo_reference_key' => '/\d+/',
        'title' => '/.{4,200}/',
        'content' => '/.{4,20000}/'
    ];

    protected $errStatusCode = [
        'user_key' => 'Creation002',
        'photo_reference_key' => 'Creation003',
        'title' => 'Creation004',
        'content' => 'Creation005'
    ];

    protected $errMessage = [
        'user_key' => '使用者主鍵格式錯誤',
        'photo_reference_key' => '上妝照主鍵格式錯誤',
        'title' => '標題格式錯誤，長度需為兩字以上五十字以下',
        'content' => '內容格式錯誤，長度須為兩字以上五千字以下',
    ];

}
