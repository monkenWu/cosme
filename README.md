# cosme
以 CodeIgniter4、Vue.js、Flask 開發之上妝系統

[系統 Demo](https://cosme.sdpmlab.org/mainApp/)

[分享功能 Demo](https://cosme.sdpmlab.org/share/?key=26655aaf465115b43fcb29a613805faac0b6c98c)

## 簡介

利用 [PSGAN GIT](https://github.com/wtjiang98/PSGAN) 製做的線上試妝平台，會員可以分享妝容照片至社群平台，以也能以自身的素顏照透過他人上傳的妝容照進行試妝。

## 系統需求

1. PHP 7.4↑
2. Mysql 5.6
3. Python 3.6
4. Nvidia Gpu(cuda suport)
5. docker-ce V19（使用V20將會造成錯誤）

## 啟動

1. 以下服務採 CodeIgniter4 開發，至專案根目錄中完成 `.env` 組態設定檔案。
    * `backend_gateway`
    * `backend_creation_service`
    * `backend_photo_service`
    * `backend_user_service`
2. 以下服務採 Python Flask 開發，主要組態設定檔案位於 `backend_makeup_service\.env`，可依需求調整
    * `backend_makeup_service`
3. 以下服務採 Vue.js 與 Node.js開發，主要組態設定檔案位於 `frontend_client_service\cosme_mainApp\.env`，可依需求調整
    * `frontend_client_service`
4. 啟動專案
    ```
    docker-compose up
    ```
5. 將專案根目錄中的 ``cosme.sql`` 匯入至資料庫
6. 依序對 CodeIgniter4 之專案下達指令取回 Composer Libraries
    ```
    docker-compose exec gateway composer install
    docker-compose exec gateway creation_service install
    docker-compose exec gateway photo_service install
    docker-compose exec gateway user_service install
    ```
7. 專案預設連接埠為
    * 前端網頁 `localhost:7010`
    * 後端 Gateway `localhost:7001`

### 使用 docker 與 Nvidia-cuda 加速 PSGAN 運算

請確定你的裝置已安裝顯示卡驅動程式。
以下文件以 Ubuntu 做為環境撰寫。

#### 搭建 Nvida 容器環境
1. `distribution=$(. /etc/os-release;echo $ID$VERSION_ID)`
2. `curl -s -L https://nvidia.github.io/nvidia-docker/gpgkey | sudo apt-key add -`
3. `curl -s -L https://nvidia.github.io/nvidia-docker/$distribution/nvidia-docker.list | sudo tee /etc/apt/sources.list.d/nvidia-docker.list
`
4. `sudo apt-get update`
    > 若在更新的過程出現 NO_PUBKEY  XXXXXXXX 的錯誤，執行
    `sudo apt-key adv --keyserver keyserver.ubuntu.com --recv-keys 3B4FE6ACC0B21F32`
    後再重新 `sudo apt-get update`
6. `sudo apt-get install -y nvidia-docker2`
7. `sudo systemctl restart docker`
8. `sudo docker run --rm --gpus all nvidia/cuda:11.0-base nvidia-smi`
    > 透過運作基本的 CUDA 容器來測試工作設定：
    ```
    +-----------------------------------------------------------------------------+
    | NVIDIA-SMI 450.51.06    Driver Version: 450.51.06    CUDA Version: 11.0     |
    |-------------------------------+----------------------+----------------------+
    | GPU  Name        Persistence-M| Bus-Id        Disp.A | Volatile Uncorr. ECC |
    | Fan  Temp  Perf  Pwr:Usage/Cap|         Memory-Usage | GPU-Util  Compute M. |
    |                               |                      |               MIG M. |
    |===============================+======================+======================|
    |   0  GeForce RTX 206...  On   | 00000000:65:00.0 Off |                  N/A |
    | 32%   33C    P8     9W / 184W |    313MiB /  7979MiB |      0%      Default |
    |                               |                      |                  N/A |
    +-------------------------------+----------------------+----------------------+

    +-----------------------------------------------------------------------------+
    | Processes:                                                                  |
    |  GPU   GI   CI        PID   Type   Process name                  GPU Memory |
    |        ID   ID                                                   Usage      |
    |=============================================================================|
    +-----------------------------------------------------------------------------+
    ```

#### 調整 docker-compose

將根目錄下的 `docker-compose.yml` 做出以下調整

```=45
  makeup_service:
    build:
      context: ./backend_makeup_service/.
      dockerfile: Dockerfile
    runtime: nvidia
    volumes:
      - ./backend_makeup_service:/app/service
      - ./backend_makeup_service/.env:/app/service/.env
      - ./backend_makeup_service/nginx.conf:/etc/nginx/nginx.conf
      - ./photo/Without:/mnt/Without
      - ./photo/Reference:/mnt/Reference
      - ./photo/Synthesize:/mnt/Synthesize
    ports:
      - 7005:80
```

#### 調整 env 

至 `backend_makeup_service\.env` 做出以下調整

```=5
# cuda or cpu
USING_DEVICE = cuda
```

#### 重新建構映像檔

```
docker-compose up --build --no-cache makeup_service
```