/* eslint-disable camelcase */
/* eslint-disable import/no-unresolved */
/* eslint-disable import/extensions */
import { ActionTree, ActionContext } from 'vuex';
import {
  State,
  tagItem,
  MakeupInfo,
  UserMakeupPhotoItem,
  MakeupPostItem,
} from './types';
import { RootState, ExtendActionContext } from '../../types';
import makeupService from '../service/makeupService';
import { getDataByInfoList } from '../../helper';

// 讓commit出現提示
type AugmentedActionContext = ExtendActionContext &
  Omit<ActionContext<State, RootState>, 'commit'>;

// ACTION 介面
interface Actions<A = AugmentedActionContext> {
  GET_MAKEUP_LIST(context: A, payload?: any): Promise<any>;
  GET_MAKEUP_WITH_KEY(
    context: A,
    payload: { key: string; size: string }
  ): Promise<any>;
  UPLOAD_MAKEUP(context: A, payload: MakeupInfo): Promise<any>;
  MODIFY_MAKEUP(
    context: A,
    payload: {
      key: string;
      newContent: { name: string; content: string; tags: tagItem[] };
    }
  ): Promise<any>;
  GET_MAKEUP_L_WITH_KEY(
    context: A,
    payload: { key: string; imgKey: string }
  ): Promise<any>;
  GET_POST_L_WITH_KEY(
    context: A,
    payload: { key: string; imgKey: string }
  ): Promise<any>;
  DELETE_MAKEUP(context: A, imgKey: string): Promise<any>;
  GET_POST_LIST(context: A, payload?: any): Promise<any>;
}

const actions: ActionTree<State, RootState> & Actions = {
  GET_MAKEUP_LIST(context) {
    return new Promise((resolve, reject) => {
      makeupService
        .getMakeupList()
        .then((MakeupInfoList) => {
          // 如果有相簿資訊就開始取得預覽圖
          if (MakeupInfoList.length > 0) {
            getDataByInfoList(
              MakeupInfoList,
              (
                infoItem,
                processResolve: (dataItem: UserMakeupPhotoItem) => void,
              ) => {
                makeupService
                  .getMakeupWithKey({
                    imgKey: infoItem.photoID,
                    size: 's',
                  })
                  .then((resImgDataS) => {
                    const photoItem = infoItem;
                    photoItem.imgURL_s = resImgDataS;
                    processResolve(photoItem);
                  });
              },
            ).then((userMakeupList) => {
              context.commit('setUserMakeupList', userMakeupList);
              resolve();
            });
          } else {
            // 相簿資訊是空的就將資訊設為空
            context.commit('setNoMakeupPhotoList', []);
            resolve();
          }
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  GET_MAKEUP_WITH_KEY(context, payload) {
    return new Promise((resolve, reject) => {
      resolve();
    });
  },
  // 上傳素顏照片
  UPLOAD_MAKEUP(context, payload) {
    return new Promise((resolve, reject) => {
      const img = context.state.uploadMakeupPhoto.src;
      if (img === null) {
        reject(new Error('無上傳完妝照 data'));
        return;
      }
      makeupService
        .uploadMakeup({ img, ...payload })
        .then(() => {
          context.commit('clearUploadMakeup');
          context.dispatch('GET_MAKEUP_LIST');
          resolve();
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  // 以key取得圖片大圖L data
  GET_MAKEUP_L_WITH_KEY(context, { key, imgKey }) {
    // const imgKey = context.getters.getUserMakeupImgKey(key);
    return new Promise((resolve, reject) => {
      makeupService
        .getMakeupWithKey({
          imgKey,
          size: 'L',
        })
        .then((res: string) => {
          context.commit('setUserMakeupData', {
            key,
            dataKey: 'imgURL_L',
            newData: res,
          });
          resolve();
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  MODIFY_MAKEUP(context, { key, newContent }) {
    return new Promise((resolve, reject) => {
      makeupService.modifyMakeup({ key, newContent }).then(() => {
        context.dispatch('GET_MAKEUP_LIST');
        resolve();
      });
    });
  },
  DELETE_MAKEUP(context, key) {
    return new Promise((resolve, reject) => {
      makeupService.deleteMakeup({ key }).then(() => {
        context.dispatch('GET_MAKEUP_LIST');
        resolve();
      });
    });
  },
  GET_POST_LIST(context, payload) {
    return new Promise((resolve, reject) => {
      makeupService.getPostList(payload).then((PostInfoList) => {
        // context.commit('setMakeupPostList', res);
        if (PostInfoList.length > 0) {
          getDataByInfoList(
            PostInfoList,
            (infoItem, processResolve: (dataItem: MakeupPostItem) => void) => {
              makeupService
                .getMakeupWithKey({
                  imgKey: infoItem.photoID,
                  size: 's',
                })
                .then((resImgDataS) => {
                  const photoItem = infoItem;
                  photoItem.imgURL_s = resImgDataS;
                  processResolve(photoItem);
                })
                .catch(() => {
                  reject();
                });
            },
          ).then((userMakeupList) => {
            context.commit('setMakeupPostList', userMakeupList);
            resolve();
          });
        } else {
          // 相簿資訊是空的就將資訊設為空
          context.commit('setMakeupPostList', []);
          resolve();
        }
      });
    });
  },
  GET_POST_L_WITH_KEY(context, { key, imgKey }) {
    return new Promise((resolve, reject) => {
      makeupService
        .getMakeupWithKey({
          imgKey,
          size: 'L',
        })
        .then((res: string) => {
          context.commit('setMakeupPostData', {
            key,
            dataKey: 'imgURL_L',
            newData: res,
          });
          resolve(res);
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
};

export default actions;
