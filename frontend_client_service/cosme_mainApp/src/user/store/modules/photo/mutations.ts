/* eslint-disable camelcase */
/* eslint-disable import/no-unresolved */
/* eslint-disable import/extensions */
import { MutationTree } from 'vuex';
import { State, NoMakeupPhotoItem } from './types';

export interface Mutations<S = State> {
  setNoMakeupPhotoList(state: S, newPhotoList: NoMakeupPhotoItem[]): void;
  setPhotoDataL(state: S, payload: { imgKey: string; imgData_L: string }): void;
  clearUploadPhotoList(state: S, payload?: any): void;
  deleteNoMakeupPhoto(state: S, payload: { imgKey: string }): void;
  modifyPhotoInfo(
    state: S,
    payload: { imgKey: string; isDefault: boolean }
  ): void;
  setMakeupEffectPreviewList(state: S, newPreviewList: any): void;
  cleanPhotoData(state: S, payload?: any): void;
  addNewUploadPhoto(state: S, newUploadPhoto: string): void;
}

const mutations: MutationTree<State> & Mutations = {
  setNoMakeupPhotoList(state, newPhotoList) {
    state.noMakeupPhotoList.data = newPhotoList;
  },
  // 設定大圖
  setPhotoDataL(state, payload) {
    const { imgKey, imgData_L } = payload;
    const keyItem = state.noMakeupPhotoList.data.find(
      (item) => item.imgKey === imgKey,
    );
    if (keyItem !== undefined) {
      keyItem.imgURL_L = imgData_L;
    }
  },
  clearUploadPhotoList(state) {
    state.uploadPhotoList = [];
  },
  deleteNoMakeupPhoto(state, payload) {
    const { imgKey } = payload;
    const list = state.noMakeupPhotoList.data;
    const index = list.findIndex((item) => item.imgKey === imgKey);
    list.splice(index, 1);
  },
  modifyPhotoInfo(state, payload) {
    const { imgKey, isDefault } = payload;
    const list = state.noMakeupPhotoList.data;
    const index = list.findIndex((item) => item.imgKey === imgKey);
    // 其餘都設為非預設
    list.forEach((item) => {
      item.isDefault = false;
    });
    list[index].isDefault = isDefault;
  },
  setMakeupEffectPreviewList(state, newPreviewList) {
    state.makeupEffectPreviewList = newPreviewList;
  },
  // 登出清空資料
  cleanPhotoData(state) {
    state.noMakeupPhotoList.data = [];
  },
  // 新增待驗證上傳素顏照
  addNewUploadPhoto(state, newUploadPhoto) {
    state.uploadPhotoList = [];
    state.uploadPhotoList.push({
      originPhoto: newUploadPhoto,
      thumb: newUploadPhoto,
      src: newUploadPhoto,
    });
  },

};

export default mutations;
