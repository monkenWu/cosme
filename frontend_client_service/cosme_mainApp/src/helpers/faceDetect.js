import * as faceapi from 'face-api.js';

/**
 *
 * 以後改成用 reject 傳 err，不要在裡面 alert
 */

// 在public提供模型下載資源

// 統一分數，先調低看看
export default function (imgBase64/* , passScore */) {
  return new Promise((resolve, reject) => {
    faceapi.nets.tinyFaceDetector.loadFromUri('/faceapi')
      .then(() => {
        const el = document.createElement('IMG');
        el.src = imgBase64;
        faceapi.detectAllFaces(el, new faceapi.TinyFaceDetectorOptions()).then(
          (res) => {
            if (res.length === 0) {
              alert('無法偵測到臉部，請重新裁切或選擇其他圖片');
              reject();
            } else {
              if (res.length > 1) {
                alert('無法使用多個臉孔，請使用個人照');
                reject();
              }
              // if (res[0].score > passScore) {
              if (res[0].score > 0.1) {
                resolve(res);
                // alert('偵測到臉部，開始上傳');
              } else {
                alert('臉部不清楚，請重新裁切或選擇其他圖片');
                reject();
              }
            }
          },
        );
      });
  });
}
