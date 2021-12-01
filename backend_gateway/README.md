# Gateway Service

## 部屬至伺服器

先於根目錄撰寫合適的 .env 檔後再執行以下動作。

### 建構與執行 Docker
1. `docker build -t cosme_gateway_service .`
    > 也可以使用 --no-cache 重新複製檔案
3. `docker run --name=cosme_gateway_service -p 8000:80 --rm -itd cosme_gateway_service`
    > --rm 指令會使 container 關閉後直接刪除
4. `docker run --name=cosme_gateway_service -p 8000:80  --restart always -itd cosme_gateway_service`
    > --restart always 在重啟後自動掛載容器
5.  `docker exec -it cosme_gateway_service /bin/bash`

## 關閉 Docker
1. `docker kill cosme_gateway_service`

## 刪除容器
1. `docker container rm cosme_gateway_service`