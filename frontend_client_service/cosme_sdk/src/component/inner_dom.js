const cosmeSdkInnerDom = (src, string1 = "test") => {
    /*html*/
    return `

    <img id="cosme_SDK_btn" src="/sdk/assets/logo.png" alt="" 
    onclick="document.querySelector('#cosme_SDK_iframe').classList.toggle('show')">
    <iframe id="cosme_SDK_iframe" src="${src}" frameborder="0"></iframe>
  
    ${style()}
  `
};


const style = () => {
    /*html*/
    return `
    <style>
    #cosme_SDK {
        font-size: 100px;
        position: fixed;
        z-index: 2147483646 !important;
        right: 10px;
        bottom: 10px;
    }

    #cosme_SDK_btn{
        position: fixed !important;
        width:50px !important;
        right: 20px !important;
        bottom: 60px !important;
        z-index: 2147483647 !important;
        background:white !important;
        padding:5px 5px !important;
        border-radius: 50% !important;
        cursor: pointer !important;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19) !important;
        transition: 0.5s ease-in !important;
    }

    #cosme_SDK_btn:hover{
        box-shadow: 0 6px 10px 0 rgba(0, 0, 0, 0.4), 0 6px 20px 0 rgba(0, 0, 0, 0.25);
    }



    #cosme_SDK>#cosme_SDK_iframe {
        opacity: 0;
        width: 0px;
        height: 0px;
        position: absolute;
        background-color: #fff;
        z-index: 99 !important;
        bottom: 65px; 
        right: 35px;
        border-radius: 20px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        transition:.4s;
    }

    #cosme_SDK>#cosme_SDK_iframe.show{
        opacity: 1;
        width: 360px;
        height: 550px;
    }

    @media screen and (max-width: 600px) {
        #cosme_SDK>#cosme_SDK_iframe.show {
            width: 100vw;
            height:100vh;
            right: 0px;
            bottom: 0px;
        }

        #cosme_SDK {
            right: 0px;
            bottom: 0px;
        }
    }
    </style>
    `
}

export default cosmeSdkInnerDom;