// const apiVersion = process.env.VUE_APP_API_VERSION;
/*eslint-disable*/

const withoutPhotoList = [{
    imgKey: "img01",
    isDefault: true,
    updateDate: "2019-09-09",
    imgURL_s: 'https://i.imgur.com/9XgTIFB.jpg',
    imgURL_L: 'https://i.imgur.com/FL04AeP.jpg',
  },
  {
    imgKey: "img02",
    isDefault: true,
    updateDate: "2019-09-09",
    imgURL_s: 'https://i.imgur.com/sK5udqo.jpg',
    imgURL_L: 'https://i.imgur.com/opstPPK.jpg',
  },
];

class mockPhoto {

  mock

  constructor(mockInstance:any) {
    this.mock = mockInstance;

    // 註冊 mockAPI 可傳入 fales，將會回傳錯誤狀態
    // this.mockGetPhotoList();
    // this.mockUploadNoMakeupPhoto();
    this.mockGetMakeupEffectPreviewList();
    // this.MockgetPhotoWithKey();
  }

  // 註冊API
  mockGetPhotoList(statusSuccess = true) {
    this.mock.onGet('/without').reply((config:any) => {
      console.log('MOCKserver:GetPhotoList', config);
      if (statusSuccess === true) {
        // const {
        //   offset,
        //   length
        // } = config.params;
        return [
          200, {
            // // 回傳偏移後的所需數量
            // data: noMakeupPhotoList.slice(offset, length + offset),
            // allDataLength: noMakeupPhotoList.length,
            data: withoutPhotoList.map((item) => {
              return {
                imgKey: item.imgKey,
                isDefault: item.isDefault,
                updateDate: item.updateDate,
              }
            }),
          }
        ];
      }
      return [
        401, {
          msg: 'failed',
        },
      ];
    });
  }


  MockgetPhotoWithKey(statusSuccess = true) {
    // /without/後面一定要有東西
    this.mock.onGet(/\/without\/\w*/).reply((config:any) => {
      console.log('MOCKserver:MockgetPhotoWithKey', config);
      if (statusSuccess === true) {
        return [
          200, {
            data: (() => {
              const imgData:any = withoutPhotoList.find((item) => {
                // 從url篩選key值
                const urlArr = config.url.split("/");
                return item.imgKey == urlArr[urlArr.length - 1]
              })
              if (config.params.isFull == true) {
                return imgData.imgURL_L
              }
              return imgData.imgURL_s
            })()
          },
        ];
      }
      return [
        401, {
          msg: 'failed',
        },
      ];
    });
  }

  mockUploadNoMakeupPhoto(statusSuccess = true) {
    this.mock.onPost('/without').reply(async (config:any) => {
      console.log('MOCKserver:uploadNoMakeupPhoto', config);

      const sleep = (value:any) =>
        new Promise((resolve) => setTimeout(resolve, value));

      // 模擬上傳進度
      const total = 1024; // mocked file size
      for (const progress of [0, 0.2, 0.4, 0.6, 0.8, 1]) {
        await sleep(500);
        if (config.onUploadProgress) {
          config.onUploadProgress({
            loaded: total * progress,
            total
          });
        }
      }
      if (statusSuccess === true) {
        return [
          200, {
            data: {
              imgKey: "img03",
              tumbnial: "https://i.imgur.com/nYrMAsh.jpg",
              full: "https://i.imgur.com/JRmkwaJ.jpg",
              isDefault: 0
            }
          },
        ];
      }
      return [
        401, {
          msg: 'failed',
        },
      ];
    });
  }

  mockGetMakeupEffectPreviewList(statusSuccess = true) {
    this.mock.onGet('/makeup').reply((config:any) => {
      console.log('MOCKserver:getMakeupEffectPreviewList', config);
      if (statusSuccess === true) {
        return [
          200, [{
              key: '5e1ger53g1a35r',
              productID: '54561351',
              modelIMG: 'we4654wf',
              com: 'maybelline',
              comURL: 'https://www.maybelline.com.tw/',
              productName: 'GT562',
              productURL: '產品網址',
            },
            {
              key: '478464r53g1a35r',
              productID: '33225554',
              modelIMG: 'thdf556',
              com: 'innisfree',
              comURL: 'http://www.innisfree.com/tw/zh/main/index.do',
              productName: 'RD006',
              productURL: '產品網址',
            },
            /** 取 length 筆* */
          ],
        ];
      }
      return [
        401, {
          msg: 'failed',
        },
      ];
    });
  }
}

export default mockPhoto;