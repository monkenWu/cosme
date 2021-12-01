/* eslint-disable camelcase */
/* eslint-disable import/no-unresolved */
/* eslint-disable import/extensions */
import { MutationTree } from 'vuex';
import axios from 'axios';
import { State, UserInfo } from './types';
import localService from '../service/localService';

export interface Mutations<S = State> {
  setLogin(state: S): void;
  setAccessToken(state: S, accessToken: string): void;
  setRefreshToken(state: S, refreshToken: string): void;
  setUserInfo(state: S, newUserInfo: UserInfo): void;
  logoutUser(state: S): void;
}

const mutations: MutationTree<State> & Mutations = {
  setLogin(state) {
    state.isLogin = true;
  },
  setAccessToken(state, accessToken) {
    state.accessToken = accessToken;
    axios.defaults.headers.common['Access-Token'] = accessToken;
    localService.setItem('accessToken', accessToken);
  },
  setRefreshToken(state, refreshToken) {
    state.refreshToken = refreshToken;
    localService.setItem('refreshToken', refreshToken);
  },
  setUserInfo(state, newUserInfo) {
    state.userInfo = newUserInfo;
    localService.getItem('usersInfo')
      .then((res:any) => {
        if (res === null) {
          res = {};
        }
        // 以使用者mail為key
        if (newUserInfo.email !== undefined) {
          res[newUserInfo.email] = newUserInfo;
          localService.setItem('usersInfo', res);
        }
      });
  },
  logoutUser(state) {
    localService.removeItem('accessToken');
    localService.removeItem('refreshToken');
    state.userInfo = {};
    state.userToken = '';
    state.isLogin = false;
  },
};

export default mutations;
