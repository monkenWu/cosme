# Gateway Service

## 部屬至伺服器

先於根目錄撰寫合適的 .env 檔後再執行以下動作。

### 建構與執行 Docker
1. `docker build -t cosme_photo_service .`
    > 也可以使用 --no-cache 重新複製檔案
3. `docker run --name=cosme_photo_service -p 8102:80 -v /mnt:/var/www/html/writable/uploads --rm -itd cosme_photo_service`
    > --rm 指令會使 container 關閉後直接刪除
4. `docker run --name=cosme_photo_service -p 8102:80 -v /mnt:/var/www/html/writable/uploads  --restart always -itd cosme_photo_service`
    > --restart always 在重啟後自動掛載容器
5.  `docker exec -it cosme_photo_service /bin/bash`

## 關閉 Docker
1. `docker kill cosme_photo_service`

## 刪除容器
1. `docker container rm cosme_photo_service`