/* eslint-disable */
import axios from "axios";
import { PhotoInfoItem } from "../photo/types";

// interface UploadReturnPhotoItem {
//   imgKey: string;
//   tumbnial: string;
//   full: string;
//   isDefault: 0; //0or1
// }

// Service 介面
interface Service {
  getPhotoList(payload?: any): Promise<PhotoInfoItem[]>;
  getPhotoWithKey(payload: { imgKey: string; size: string }): Promise<string>;
  modifyNoMakeupPhoto(payload: {
    imgKey: string;
    isDefault: boolean;
  }): Promise<void>;
  uploadNoMakeupPhoto(payload: string): Promise<void>;
  deleteNoMakeupPhoto(payload: { imgKey: string }): Promise<void>;
  getMakeupEffectPreviewList(payload: any): Promise<void>;
}

const photoService: Service = {
  // 取得素顏管理列表
  getPhotoList(payload) {
    return new Promise((resolve, reject) => {
      axios({
        method: "get",
        url: "/without",
        // params: payload,
      })
        .then((res) => {
          resolve(res.data.data);
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  getPhotoWithKey(payload) {
    const { imgKey, size } = payload;
    return new Promise((resolve, reject) => {
      axios({
        method: "get",
        url: `/without/${imgKey}`,
        params: {
          isFull: (() => {
            if (size == "L") {
              return true;
            }
            if (size == "s") {
              return false;
            }
            return false;
          })(),
        },
      })
        .then((res: any) => {
          const resImgDataS: string = res.data.data;
          resolve(resImgDataS);
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  modifyNoMakeupPhoto(payload) {
    const { imgKey, isDefault } = payload;
    return new Promise((resolve, reject) => {
      axios({
        method: "put",
        url: `/without/${imgKey}`,
        data: {
          // boolean to int
          isDefault: isDefault ? 1 : 0,
        },
      })
        .then(() => {
          resolve();
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  uploadNoMakeupPhoto(payload) {
    return new Promise((resolve, reject) => {
      axios({
        method: "post",
        url: `/without`,
        data: {
          img: payload,
          isDefault: 0,
        },
      })
        .then((res) => {
          resolve();
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  deleteNoMakeupPhoto(payload) {
    const { imgKey } = payload;
    return new Promise((resolve, reject) => {
      axios({
        method: "delete",
        url: `/without/${imgKey}`,
      })
        .then((res) => {
          resolve(res.data);
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  getMakeupEffectPreviewList(payload) {
    return new Promise((resolve, reject) => {
      axios({
        method: "get",
        url: `/makeup`,
        params: payload,
      })
        .then((res) => {
          resolve(res.data);
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
};
export default photoService;
