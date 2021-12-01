/* eslint-disable import/extensions */
/* eslint-disable import/no-unresolved */
import { Module } from 'vuex';
import getters from './getters';
import actions from './actions';
import mutations from './mutations';
import { State } from './types';
import { RootState } from '../../types';

const state:State = {
  uploadMakeupPhoto: {
    originPhoto: null,
    thumb: null,
    src: null,
  },
  // uploadUserOriginalPhoto: '',
  uploadMakeupOriginalPhoto: '',
  canNewMakeupUpload: false,
  userMakeupList: [],
  makeupPostList: [],
  tryPostKey: '',
};

const makeupModule: Module<State, RootState> = {
  state,
  getters,
  actions,
  mutations,
};

export default makeupModule;
