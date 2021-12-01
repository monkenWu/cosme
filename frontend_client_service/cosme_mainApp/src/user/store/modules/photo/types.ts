/*eslint-disable*/
export interface NoMakeupPhotoItem {
  imgKey: string;
  isDefault: boolean;
  updateDate: string;
  // imgURL_s、imgURL_L 為了響應必須先添加內容，可先塞 ""
  imgURL_s: string;
  imgURL_L: string;
}

export interface MakeupEffectPreviewItem {
}

export interface UploadPhotoLtem {

  // 原圖(讓使用者重新裁切) 不上傳到資料庫
  originPhoto: string,
  // thumb 小圖(裁切後預覽圖)
  thumb: string,
  // src 大圖(裁切後原圖)
  src: string,
  // 正在讀取
  // onload:boolean,
}

// API 回傳的格式
export interface PhotoInfoItem {
  key: string;
  isDefault: boolean;
  updateDate: string;
}

export interface State {
  // 素顏照相簿列表
  noMakeupPhotoList: {
    data: NoMakeupPhotoItem[];
    allDataLength(): number;
  };
  // modal中預覽列表
  makeupEffectPreviewList: MakeupEffectPreviewItem[];
  // 上傳素顏照等待區
  uploadPhotoList: UploadPhotoLtem[];
  uploadPhotoProgress: number;
}
