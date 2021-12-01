/*eslint-disable*/
export interface UploadMakeupPhoto {
  originPhoto: null | string;
  thumb: null | string;
  src: null | string;
}

export interface tagItem {
  text: string; //'可愛妝容'
}

// 完妝照的基本資訊
export interface MakeupInfo {
  name: string;
  content: string;
  tags: tagItem[];
  products?: any[];
}

// API 回傳的格式
// export interface MakeupApiInfoItem {
//   imgKey: string; //'aaaa123',
//   name: string; //'夏季上班族氣色妝',
//   content: string;
//   updateDate: string; //'2020/09/19',
//   tags: tagItem[];
// }

export interface UserMakeupPhotoItem {
  key: string; //'這篇文章的key',
  photoID: string; //'拿照片的id',
  name: string; //'夏季上班族氣色妝',
  time: string; //'2020/09/19',
  imgID: string; //'拿照片的id',,
  content: string;
  tags: tagItem[];
  imgURL_s: string; //'base64',
  imgURL_L: string; //'base64',
  products?: any[];
}


export interface MakeupPostItem extends UserMakeupPhotoItem {
  author: {
    name: string; //"霈霈";
    key: string; //"";
    imgURL: string; //"https://cdn.style-map.com/post/photo/medium/544894.jpg";
  };
}

export interface State {
  uploadMakeupPhoto: UploadMakeupPhoto;
  uploadMakeupOriginalPhoto: string;
  canNewMakeupUpload: boolean;
  userMakeupList: UserMakeupPhotoItem[];
  makeupPostList: MakeupPostItem[];
  tryPostKey: string;
}
