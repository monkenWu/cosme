/* eslint-disable import/extensions */
/* eslint-disable import/no-unresolved */
import { Module } from 'vuex';
import getters from './getters';
import actions from './actions';
import mutations from './mutations';
import { State } from './types';
import { RootState } from '../../types';

const state:State = {
  userInfo: {},
  userToken: '',
  accessToken: '',
  refreshToken: '',
  isLogin: false,
  // hasLoginHistory: false,
};

const userModule: Module<State, RootState> = {
  state,
  getters,
  actions,
  mutations,
};

export default userModule;
