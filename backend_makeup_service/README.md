# Makeup_service 

## 使用模型

[PSGAN GIT](https://github.com/wtjiang98/PSGAN)

## 依賴

### pip 直接安裝依賴

```
pip3 install numpy
pip3 install Pillow
pip3 install torch torchvision
pip3 install torchgpipe
pip3 install matplotlib
pip3 install fvcore
pip3 install fire
pip3 install requests
pip3 install opencv-python
pip3 install flask
pip3 install python-dotenv
```

### 在 ubuntu18.04 中布署 dlib 依賴庫
```
apt-get install build-essential cmake pkg-config
apt-get install libx11-dev libatlas-base-dev
apt-get install libgtk-3-dev libboost-python-dev 
pip3 install dlib
```

### 在 Windows 中部屬 dlib 依賴庫

#### 使用已編譯檔案
> 支援 python3.8 64位元 
1. 下載 [dlib-19.19.0-cp38-cp38-win_amd64.whl](https://drive.google.com/file/d/1nQd_PDUMhZFcosE7vMQujBt8J1PyZjlc/view?usp=sharing)
2. cd 至檔案存放資料夾
3. `pip3 install dlib-19.19.0-cp38-cp38-win_amd64.whl`

#### 自行編譯

1. 安裝 Cmake 
2. 安裝 minGW（依 python 版本安裝相應位元）
3. `pip3 install wheel`
4. 下載最新版 [dlib](https://github.com/davisking/dlib) 至 `setup.py` 找到以下內容
    ```python
    if platform.system() == "Windows":
        cmake_args += ['-DCMAKE_LIBRARY_OUTPUT_DIRECTORY_{}={}'.format(cfg.upper(), extdir)]
        if sys.maxsize > 2**32:
            cmake_args += ['-A', 'x64']
        # Do a parallel build
        build_args += ['--', '/m'] 
    ```
    將這個 if 區塊的指令修改如下:
    ```python
    if platform.system() == "Windows":
        cmake_args += ['-DCMAKE_LIBRARY_OUTPUT_DIRECTORY_{}={}'.format(cfg.upper(), extdir)]
        # if sys.maxsize > 2**32:
        #     cmake_args += ['-A', 'x64']
        # Do a parallel build
        build_args += ['--', '-j4']
    ```
    * minGW 提供的 C C++ 編譯器沒有提供 `-A` 與 `x64` 的指令，直接註解即可
    * `-j4` 為四線程編譯，可依執行環境的不同修改數字。
4. 命令提示字元 cd 至 dlib 根目錄使用 minGW 進行編譯
    ```
    python setup.py -G "MinGW Makefiles" bdist_wheel
    ```
5. 編譯成功後至 `dist` 資料夾可以找到類似 `dlib-19.19.99-cp37-cp37m-win_amd64.whl` 的檔案
6. cd 至 dist 資料夾並執行指令
    ```
    pip3 install dlib-19.19.99-cp37-cp37m-win_amd64.whl
    ```
7. 成功部屬 dlib

## 執行測試伺服器
1. CD 至專案目錄
2. flask run

## 修改資料寫入位置與運算方式
1. 打開根目錄 `.env` 檔案
```
#--------------------------------------------------------------------
# ENVIRONMENT
#--------------------------------------------------------------------

# cuda or cpu
USING_DEVICE = cpu

# file read path
SOURCE_PATH = C:\psganEnv\image\source\
REFERENCE_PATH = C:\psganEnv\image\reference\
# file save path
SYNTHESIZE_PSTH = C:\psganEnv\image\synthesize\
```
2. USING_DEVICE 可以填入 cpu 或 cuda
3. PATH 位置為執行系統下的絕對位置，必須以斜線結尾

## api 請求方式

### [POST] `/api/v1/makeup`
傳入使用者圖像與參考圖像，運算完成後回傳產生的檔案名稱
* parmetgers : `json`
    ```json
    {
        "userIMG" : "string",
        "referenceIMG" : "string"
    }
    ```
* response : `json`
    ```json
    {
        "fileName" : "string"
    }
    ```