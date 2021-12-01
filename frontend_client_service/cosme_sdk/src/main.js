import cosmeSdkInnerDom from './component/inner_dom.js'
import check_init_ajax from './js/check_init_ajax.js'

window.cosme_sdk_file = (() => {

  const cosmeSdkDom = document.querySelector("#cosme_SDK");

  // 只能搜尋div上param指定的內容
  const iframeSrc = `https://cosme.dev/mainApp/#/makeup?entry=sdk&shop=${cosmeSdkDom.getAttribute("param")}`;

  // 初始化 SDK
  function _initDom() {
    // 加入 innerDiv
    cosmeSdkDom.innerHTML = cosmeSdkInnerDom(iframeSrc);
  }


  // 初始化 main
  function init() {
    _initDom();

    // 檢查token後決定是否開啟
    // check_init_ajax({
    //   token:cosmeSdkDom.getAttribute("token"),
    //   data:cosmeSdkDom.getAttribute("param"),
    //   successCallback:_initDom,
    //   failedCallback(){return}
    // });
  }



  return {
    init: init,
  }
})()

// 初始化SDK
cosme_sdk_file.init();