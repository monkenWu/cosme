const check_init_ajax = (obj) => {

  const {
    token = "",
    data = {},
    successCallback = () => {},
    failedCallback = () => {}
  } = obj;

  console.log(obj);

  let checkRequest = new XMLHttpRequest();

  // 掛載成功事件
  checkRequest.addEventListener("load", () => {
    console.log("check success");
    successCallback();
  });

  // 掛載失敗事件
  checkRequest.addEventListener("error", () => {
    console.log("check failed")
    failedCallback();
  });

  // 設定連線
  checkRequest.open("POST", "http://localhost/cosme/cosme_sdk/sdk/assets/bg.jpg");
  checkRequest.setRequestHeader("headerToken", token);

  // 發送連線
  checkRequest.send();
}

export default check_init_ajax