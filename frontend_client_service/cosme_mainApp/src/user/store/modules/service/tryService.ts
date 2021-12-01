/* eslint-disable */
import axios from "axios";

// Service 介面
export interface Service {
  getTryHistory(payload?: any): Promise<any>;
  tryMakeup(payload: { makeupKey: string; withoutKey: string }): Promise<any>;
  getTryByImgKey(payload: { imgKey: string }): Promise<any>;
  deleteTry(payload: { imgKey: string }): Promise<any>;
  tryRating(payload: { tryKey: string; score: number }): Promise<any>;
  // 分享試妝
  tryShareMakeup(payload: {
    makeupKey: string;
    withoutImgData: string;
  }): Promise<any>;
}

const tryService: Service = {
  getTryHistory() {
    return new Promise((resolve, reject) => {
      axios({
        method: "get",
        url: "/makeup",
      }).then((res) => {
        resolve(res.data.data);
      });
    });
  },
  tryMakeup({ makeupKey, withoutKey }) {
    return new Promise((resolve, reject) => {
      axios({
        method: "post",
        url: "/makeup",
        data: { referenceKey: makeupKey, withoutKey },
      }).then((res) => {
        resolve(res.data.imgKey);
      });
    });
  },
  getTryByImgKey({ imgKey }) {
    return new Promise((resolve, reject) => {
      axios({
        method: "get",
        url: `/makeup/${imgKey}`,
      }).then((res) => {
        resolve(res.data.data);
      });
    });
  },
  deleteTry({ imgKey }) {
    return new Promise((resolve, reject) => {
      axios({
        method: "delete",
        url: `/makeup/${imgKey}`,
      }).then(() => {
        resolve();
      });
    });
  },
  tryShareMakeup({ makeupKey, withoutImgData }) {
    return new Promise((resolve, reject) => {
      axios({
        method: "post",
        url: "/makeup/trial",
        data: { referenceKey: makeupKey, withoutIMG: withoutImgData },
      }).then((res) => {
        resolve(res.data.imgData);
      });
    });
  },
  tryRating({ tryKey, score }) {
    return new Promise((resolve, reject) => {
      axios({
        method: "put",
        url: `/makeup/${tryKey}`,
        data: { score },
      }).then(() => {
        resolve();
      });
    });
  },
};

export default tryService;
