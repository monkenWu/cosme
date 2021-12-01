# user_service 手冊
本服務整合了使用者服務與認證服務等 APIs。

## 錯誤碼

### V1

#### All_Service
| statusCode | HTTP Code | Description |
| ----------- | --------- | ----------- |
| DBError | 400 | 資料庫處理失敗，詳細錯誤將寫入至該 Service 後端 Log 檔 |
| SUCCESS | 200 | 請求、驗證、更新、刪除成功 |
| Created | 201 | 建立成功 |

#### User API組

| statusCode | HTTP Code | Description |
| ----------- | --------- | ----------- |
| User001 | 400 | 傳入欄位具有缺失，請確認傳入欄位是否與 API DOC 規定相符 |
| User002 | 400 | 帳號或密碼不可為空 |
| User003 | 401 | 驗證失敗，帳號或密碼錯誤 |
| User004 | 404 | userID 不存在於資料庫 |
| User005 | 400 | 此帳號已被註冊 |
| User006 | 400 | 使用者名稱不可存在特殊字符，最少兩個字以上，十個字以下 |
| User007 | 400 | 信箱格式錯誤，必須為 mail@domain.com 相似格式 |
| User008 | 400 | 性別格式錯誤，必須為 0 or 1 |
| User009 | 400 | 生日格式錯誤，必須為 yyyy-mm-dd |
| User010 | 400 | 密碼格式錯誤，可使用英文、數字，以及常見特殊字元，最少五個字，最多二十字以下 |

#### Auth_Service

| statusCode | HTTP Code | Description |
| ----------- | --------- | ----------- |
| Auth001 | 400 | 傳入欄位具有缺失，請確認傳入欄位是否與 API DOC 規定相符 |
| Auth002 | 400 | 數位簽章驗證失敗，資料可能被竄改 |
| Auth003 | 400 | 簽名在某個時間點後才能夠使用 |
| Auth004 | 403 | Token 已經過期 |
| Auth005 | 400 | Token 不符合 JWT 格式，或驗證過程發生未知的錯誤 |
| Auth006 | 400 | 驗證程式庫運作過程發生未知錯誤，詳細錯誤將寫入至該 Service 後端 Log 檔 |
| Auth007 | 404 | Token 合法，找不到資料庫中相同令牌與使用者的資訊，可能已登出或遭竄改|


## 環境

1. PHP7.3~7.*
2. MariaDB
3. Composer

## 部屬

將專案 clone 至本地端，再依下列步驟部屬程式。

1. 取得框架檔案

    gitlab 上的程式不含框架本體，所以必須在 clone 至本地端後，使用 Composer 套件管理器將依賴下載回來。
    至 cmd 或 bash 中，cd 至專案根目錄執行以下指令。
    ```
    composer update
    ```

2. env檔設定

    1. 複製跟目錄下的 env 並且重新命名為 .env 。
    2. 進入 .env 移除以下設定的 # 。
    ```
    CI_ENVIRONMENT = development
    ```
    ```
    OpenApiDoc = true
    ```
    ```
    app.baseURL = 'http://localhost:8081/'
    ```
    3. 前往 JWT 區段，寫入亂數 KEY 與 ISS 、 AUD
    4. 前往 DATABASE 區段，調整資料庫連線，並移除 # 。

3. 建立資料庫
    1. 建立 cosme 資料庫，字元集 `utf8` 排序規則 `utf8_unicode_ci`
    2. 匯入根目錄下的 Cosme.sql
4. 啟動開發伺服器 

    至 cmd 或 bash ，cd 到專案根目錄執行以下指令。
    ```
    php spark serve -port 8081
    ```
    當畫面出現以下訊息，代表開發伺服器已經部屬完畢，可以開始開發。
    ```
    CodeIgniter development server started on http://localhost:8081
    ```
    執行 Ctrl+C 可以關閉開發伺服器。

## 文件

### 路由規則 

[Codeigniter4 REST-ful](https://codeigniter.tw/userguide/incoming/restful.html)

1. 以 REST-ful API 為設計模式，並加上版本號碼
    ```
    /api/{version}/{resourceName}
    ```
2. 每個版本號碼都必須實作 openapi 讓 Swagger 得以掃描
3. API 統一於 App/Config/Router.php 進行設定

### 內容解析
1. 除非是檔案的上傳，否則統一以 `Request body` 以下並且格式為 `application/json` 傳遞資料。
2. 必須正確運用 Http 動詞進行路由設計

### 建構響應
[Codeigniter4 API響應](https://codeigniter.tw/userguide/outgoing/api_responses.html)
1. 必須依據語意回傳正確的 [status code](https://developer.mozilla.org/zh-TW/docs/Web/HTTP/Status)
2. 不論正確或失敗，響應必須擁有 msg 描述本次的狀況。
3. 回傳統一以 json 形式開發。 

### 功能開發

1. 適當地將 Controller 重複的邏輯拆分為 [Service](https://codeigniter.tw/userguide/concepts/services.html)
2. 可以額外撰寫 Model 分離 Controller 邏輯
3. 統一使用 CI4 的 [Model](https://codeigniter.tw/userguide/models/model.html) 進行查詢
