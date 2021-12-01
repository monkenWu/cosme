/* eslint-disable camelcase */
/* eslint-disable import/no-unresolved */
/* eslint-disable import/extensions */
import { ActionTree, ActionContext } from 'vuex';
import { RootState, ExtendActionContext } from '../../types';
import { State, RegisterInfo, ModifyUserInfo } from './types';
import userService from '../service/userService';
import localService from '../service/localService';

type AugmentedActionContext = ExtendActionContext &
  Omit<ActionContext<State, RootState>, 'commit'>;

// ACTION 介面

interface Actions<A = AugmentedActionContext> {
  SIGNUP(context: A, payload: RegisterInfo): Promise<any>;
  LOGIN(
    context: A,
    payload: { email: string; password: string; remember: boolean }
  ): Promise<any>;
  GET_USER_INFO(context: A): Promise<any>;
  MODIFY_USER_INFO(context: A, payload: ModifyUserInfo): Promise<any>;
  GET_NEW_ACCESSTOKEN(context: A): Promise<any>;
  LOGOUT(context: A): void;
  REMEMBER_LOGIN(context: A): Promise<any>;
}

const actions: ActionTree<State, RootState> & Actions = {
  // 註冊
  SIGNUP(context, payload) {
    return new Promise((resolve, reject) => {
      userService
        .signup(payload)
        .then((res) => {
          resolve(res);
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  // 從登入頁面登入
  LOGIN(context, payload) {
    if (payload.remember === true) {
      console.log('尚未實作remember邏輯，預設都會記住使用者');
    }
    return new Promise((resolve, reject) => {
      userService
        .login(payload)
        .then((res: any) => {
          context.commit('setAccessToken', res.accessToken);
          context.commit('setRefreshToken', res.refreshToken);
          context.dispatch('GET_USER_INFO');
          context.commit('setLogin');
          resolve(res);
        })
        .catch((err) => {
          console.log(err);
          reject(err);
        });
    });
  },
  // 取得使用者資料
  GET_USER_INFO(context) {
    return new Promise((resolve, reject) => {
      userService
        .getUserInfo()
        .then((res: any) => {
          context.commit('setUserInfo', res);
          resolve(res);
        })
        .catch((err) => {
          console.log('未登入或登入狀態無效');
          reject(err);
        });
    });
  },
  // 修改使用者資料
  MODIFY_USER_INFO(context, payload) {
    return new Promise((resolve, reject) => {
      userService
        .modifyUserInfo(payload)
        .then((res) => {
          // 如果資料成功更新就改變 state
          context.dispatch('GET_NEW_ACCESSTOKEN').then(() => {
            context.dispatch('GET_USER_INFO');
          });

          resolve(res);
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  // 更新 accessTocken
  GET_NEW_ACCESSTOKEN(context) {
    const expiredAccessToken = context.state.accessToken;
    const { refreshToken } = context.state;
    return new Promise((resolve, reject) => {
      userService
        .getNewAccessToken(expiredAccessToken, refreshToken)
        .then((res: any) => {
          context.commit('setAccessToken', res.accessToken);
          resolve(res);
        })
        .catch((err) => {
          context.dispatch('LOGOUT');
          reject(err);
        });
    });
  },
  // 登出
  LOGOUT(context) {
    const { accessToken, refreshToken } = context.state;
    userService.logout(accessToken, refreshToken);
    // .then((/* res */) => {
    //   context.commit('logoutUser');
    //   context.commit('cleanPhotoData');
    //   context.commit('setAccessToken', '');
    // })
    // .catch((/* err */) => {
    // });
    context.commit('logoutUser');
    context.commit('cleanPhotoData');
    context.commit('setAccessToken', '');
  },
  // 記住使用者，嘗試自動登入
  REMEMBER_LOGIN(context) {
    return new Promise((resolve, reject) => {
      const accessToken = localService.getItem('accessToken');
      const refreshToken = localService.getItem('refreshToken');
      Promise.all([accessToken, refreshToken]).then((tokens: any) => {
        context.commit('setAccessToken', tokens[0]);
        context.commit('setRefreshToken', tokens[1]);
        // 確定token的有效性才改變登入狀態
        context
          .dispatch('GET_USER_INFO')
          .then(() => {
            context.commit('setLogin');
            resolve();
          })
          .catch(() => {
            reject();
          });
      });
    });
  },
};

export default actions;
