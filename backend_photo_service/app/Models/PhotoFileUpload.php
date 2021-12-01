<?php

namespace App\Models;

class PhotoFileUpload
{
    
    protected $filePath = "";
    protected $tumbnialFileName = "";
    protected $fullFileName = "";
    protected $error = "";

    public function __construct($filePath)
    {
        $this->filePath = $filePath.DIRECTORY_SEPARATOR;
    }

    /**
     * 傳入 base64 並寫入至相應檔案位置
     *
     * @param string $base64Img
     * @return bool 上傳成功或失敗
     */
    public function doUpload(string $base64Img):bool{
        $imgMatch = [];
        preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64Img, $imgMatch);
        if(in_array($imgMatch[2],array('jpeg','jpg','png'))){
            
            if($fullFileName = $this->writeFile($imgMatch,$base64Img)){
                $this->fullFileName = $fullFileName;
            }else{
                return false;
            }

            if($tumbnialFileName = $this->createTumbnialFile($this->fullFileName,$imgMatch[2])){
                $this->tumbnialFileName = $tumbnialFileName;
                return true;
            }else{
                return false;
            }

        }else{
            $this->error = "圖片格式錯誤，僅支援 jpeg、jpg、png 圖像上傳。你所傳入的格式為：".$imgMatch[2];
            return false;
        }
    }

    /**
     * 取得檔案內容並且轉換成 base64 回傳
     *
     * @param string $fileName
     * @return string base64 code
     */
    public function getFileByBase64(string $fileName):string{
        $path = $this->filePath.$fileName;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }

    /**
     * 轉換 base64 圖檔並寫入磁碟
     *
     * @param array $imgMatch
     * @param string $base64data
     * @return mixed
     */
    private function writeFile(array $imgMatch,string $base64data)
    {
        $fileName = md5(uniqid(rand())).'.'.$imgMatch[2];
        $fileWritePath = $this->filePath.$fileName;
        $imgContent = base64_decode(str_replace($imgMatch[1], '', $base64data));
        try {
            $result = file_put_contents($fileWritePath, $imgContent);
        } catch (\Throwable $th) {
            $this->error = "原圖上傳過程發生問題：".$th->getMessage();
            return false;
        }
        if($result){
            return $fileName;
        }else{
            return false;
        }
    }

    /**
     * 建立縮圖並寫入磁碟
     *
     * @param string $fullFileName
     * @param string $imgType
     * @return mixed
     */
    private function createTumbnialFile(string $fullFileName,string $imgType){
        try {
            if($imgType == "png"){
                $fullImg = imagecreatefrompng($this->filePath.$fullFileName);
            }else{
                $fullImg = imagecreatefromjpeg($this->filePath.$fullFileName);
            }
            // 原圖尺寸
            $fullW = imagesx($fullImg);
            $fullH = imagesy($fullImg);
            //縮圖尺寸
            $thumbW = 300;
            $thumbH = 300;
            if($fullW !== $fullH){
                if($fullW > $fullH){
                    $thumbH = intval($fullH / $fullW * 300);
                }else{
                    $thumbW = intval($fullW / $fullH * 300);
                }            
            }
            //處理縮圖
            $thumb = imagecreatetruecolor($thumbW, $thumbH);
            imagecopyresized($thumb, $fullImg, 0, 0, 0, 0, $thumbW, $thumbH, $fullW, $fullH);
            //寫入縮圖至磁碟
            $fileName = md5(uniqid(rand())).'.jpeg';
            $thumbFilePath = $this->filePath.$fileName;
            imagejpeg($thumb, $thumbFilePath);
        } catch (\Throwable $th) {
            $this->error = "縮圖上傳過程發生問題：".$th->getMessage();
            return false;
        }
        return $fileName;
    }

    /**
     * 取得上傳成功的縮圖檔案名稱
     *
     * @return string
     */
    public function getTumbnialFileName():string{
        return $this->tumbnialFileName;
    }

    /**
     * 取得上傳成功的原圖檔案名稱
     *
     * @return string
     */
    public function getFullFileName():string{
        return $this->fullFileName;
    }

    /**
     * 取得上傳失敗描述字串
     *
     * @return string
     */
    public function getError():string{
        return $this->error;
    }

}
