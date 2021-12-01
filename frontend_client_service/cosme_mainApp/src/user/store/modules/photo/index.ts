/* eslint-disable import/extensions */
/* eslint-disable import/no-unresolved */
import { Module } from 'vuex';
import getters from './getters';
import actions from './actions';
import mutations from './mutations';
import { State } from './types';
import { RootState } from '../../types';

const state:State = {
  // 素顏照相簿
  noMakeupPhotoList: {
    data: [],
    allDataLength() {
      return this.data.length;
    },
  },

  // modal中預覽列表
  makeupEffectPreviewList: [],

  // 上傳素顏照等待區
  uploadPhotoList: [],

  // 上傳進度
  uploadPhotoProgress: 0,
};

const photoModule: Module<State, RootState> = {
  state,
  getters,
  actions,
  mutations,
};

export default photoModule;
