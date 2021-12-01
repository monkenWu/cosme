/* eslint-disable camelcase */
/* eslint-disable import/no-unresolved */
/* eslint-disable import/extensions */
import { MutationTree } from 'vuex';
import { State, UploadMakeupPhoto, UserMakeupPhotoItem } from './types';

export interface Mutations<S = State> {
  addUserMakeupPhoto(state: S, imgData: string): void;
  updateMakeupPhoto(state: S, newUploadMakeupPhoto: UploadMakeupPhoto): void;
  deleteUserMakeupPhoto(state: S): void;
  cropUpdataMakeupPhoto(state: S, cropImg: string): void;
  changeCanNewMakeupUploadState(state: S, booleanObj: boolean): void;
  clearUploadMakeup(state: S): void;
  setUserMakeupList(state: S, newUserMakeupList: State['userMakeupList']): void;
  setUserMakeupData(
    state: S,
    payload: { key: string; dataKey: keyof UserMakeupPhotoItem; newData: any }
  ): void;
  setMakeupPostList(state: S, newMakeupPostList: State['makeupPostList']): void;
  setMakeupPostData(
    state: S,
    payload: { key: string; dataKey: keyof UserMakeupPhotoItem; newData: any }
  ): void;
}

const mutations: MutationTree<State> & Mutations = {
  // 新增新妝容照
  addUserMakeupPhoto(state, imgData) {
    state.uploadMakeupPhoto = {
      originPhoto: imgData,
      thumb: imgData,
      src: imgData,
    };
  },
  // 新上傳的妝容照片狀態更新
  updateMakeupPhoto(state, newUploadMakeupPhoto) {
    state.uploadMakeupPhoto = newUploadMakeupPhoto;
  },
  // 刪除新妝容照
  deleteUserMakeupPhoto(state) {
    state.uploadMakeupPhoto = {
      originPhoto: null,
      thumb: null,
      src: null,
    };
  },
  cropUpdataMakeupPhoto(state, cropImg) {
    state.uploadMakeupPhoto.thumb = cropImg;
    state.uploadMakeupPhoto.src = cropImg;
  },
  // 照片可上傳狀態更新
  changeCanNewMakeupUploadState(state, booleanObj) {
    state.canNewMakeupUpload = booleanObj;
  },
  // 清除上傳完妝照資料
  clearUploadMakeup(state) {
    state.uploadMakeupPhoto = {
      originPhoto: null,
      thumb: null,
      src: null,
    };
  },
  setUserMakeupList(state, newUserMakeupList) {
    state.userMakeupList = newUserMakeupList;
  },
  setUserMakeupData(state, payload) {
    const { key, dataKey, newData } = payload;
    const keyItem = state.userMakeupList.find(
      (item) => item.key === key,
    );
    if (keyItem !== undefined) {
      keyItem[dataKey] = newData;
    }
  },
  setMakeupPostList(state, newMakeupPostList) {
    state.makeupPostList = newMakeupPostList;
  },
  setMakeupPostData(state, payload) {
    const { key, dataKey, newData } = payload;
    const keyItem = state.makeupPostList.find(
      (item) => item.key === key,
    );
    if (keyItem !== undefined) {
      keyItem[dataKey] = newData;
    }
  },
};

export default mutations;
