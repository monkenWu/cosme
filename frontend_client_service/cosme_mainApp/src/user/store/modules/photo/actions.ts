/* eslint-disable camelcase */
/* eslint-disable import/no-unresolved */
/* eslint-disable import/extensions */
import { ActionTree, ActionContext } from 'vuex';
import { State, NoMakeupPhotoItem, PhotoInfoItem } from './types';
import { RootState, ExtendActionContext } from '../../types';
import photoService from '../service/photoService';
import { getDataByInfoList } from '../../helper';

type AugmentedActionContext = ExtendActionContext &
  Omit<ActionContext<State, RootState>, 'commit'>;

// ACTION 介面

interface Actions<A = AugmentedActionContext> {
  GET_PHOTO_LIST(context: A, payload?: any): Promise<any>;
  GET_PHOTO_L_WITH_KEY(context: A, imgKey: string): Promise<any>;
  UPLOAD_NO_MAKEUP_PHOTO(context: A): Promise<any>;
  MODIFY_NO_MAKEUP_PHOTO(
    context: A,
    payload: { imgKey: string; isDefault: boolean }
  ): Promise<any>;
  DELETE_NO_MAKEUP_PHOTO(context: A, payload: { imgKey: string }): Promise<any>;
  GET_MAKEUP_EFFECT_PREVIEW_LIST(context: A, payload?: any): Promise<any>;
}

const actions: ActionTree<State, RootState> & Actions = {
  GET_PHOTO_LIST(context, payload) {
    return new Promise((resolve, reject) => {
      photoService
        .getPhotoList(payload)
        .then((photoInfoList) => {
          // 如果有相簿資訊就開始取得預覽圖
          if (photoInfoList.length > 0) {
            getDataByInfoList(
              photoInfoList,
              (infoItem, processResolve:(dataItem:NoMakeupPhotoItem)=>void) => {
                photoService
                  .getPhotoWithKey({
                    imgKey: infoItem.key,
                    size: 's',
                  })
                  .then((resImgDataS) => {
                    const photoItem: NoMakeupPhotoItem = {
                      imgKey: infoItem.key,
                      isDefault: infoItem.isDefault,
                      updateDate: infoItem.updateDate,
                      imgURL_s: resImgDataS,
                      imgURL_L: '',
                    };
                    processResolve(photoItem);
                  });
              },
            ).then((photoList) => {
              context.commit('setNoMakeupPhotoList', photoList);
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
  // 以key取得圖片大圖L data
  GET_PHOTO_L_WITH_KEY(context, imgKey) {
    return new Promise((resolve, reject) => {
      photoService
        .getPhotoWithKey({
          imgKey,
          size: 'L',
        })
        .then((res: string) => {
          // .then((res: string) => {
          context.commit('setPhotoDataL', {
            imgKey,
            imgData_L: res,
          });
          resolve();
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  // 上傳素顏照片
  UPLOAD_NO_MAKEUP_PHOTO(context) {
    return new Promise((resolve, reject) => {
      // 以array形式，預留以後多張上傳功能
      const payload = context.state.uploadPhotoList[0].src;
      photoService
        .uploadNoMakeupPhoto(payload)
        .then(() => {
          context.dispatch('GET_PHOTO_LIST');
          context.commit('clearUploadPhotoList', null);
          resolve();
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  // 修改圖片資料
  MODIFY_NO_MAKEUP_PHOTO(context, payload) {
    return new Promise((resolve, reject) => {
      photoService
        .modifyNoMakeupPhoto(payload)
        .then(() => {
          context.commit('modifyPhotoInfo', payload);
          resolve();
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  // 刪除素顏照
  DELETE_NO_MAKEUP_PHOTO(context, payload) {
    return new Promise((resolve, reject) => {
      photoService
        .deleteNoMakeupPhoto(payload)
        .then((res) => {
          // context.commit('setMakeupEffectPreviewList', res.data);
          context.commit('deleteNoMakeupPhoto', payload);
          context.dispatch('GET_PHOTO_LIST');
          resolve(res);
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  // 取得預覽用產品資料
  GET_MAKEUP_EFFECT_PREVIEW_LIST(context, payload) {
    return new Promise((resolve, reject) => {
      photoService
        .getMakeupEffectPreviewList(payload)
        .then((res) => {
          context.commit('setMakeupEffectPreviewList', res);
          resolve(res);
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
};

export default actions;
