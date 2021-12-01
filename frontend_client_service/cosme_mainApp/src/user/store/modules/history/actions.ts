/* eslint-disable camelcase */
/* eslint-disable import/no-unresolved */
/* eslint-disable import/extensions */
import { ActionTree, ActionContext } from 'vuex';
import { State } from './types';
import { RootState, ExtendActionContext } from '../../types';
import tryService from '../service/tryService';
import { getDataByInfoList } from '../../helper';

type AugmentedActionContext = ExtendActionContext &
  Omit<ActionContext<State, RootState>, 'commit'>;

// ACTION 介面

interface Actions<A = AugmentedActionContext> {
  GET_TRY_HISTORY(context: A, payload?: any): Promise<any>;
}

const actions: ActionTree<State, RootState> & Actions = {
  GET_TRY_HISTORY(context) {
    return new Promise((resolve, reject) => {
      tryService
        .getTryHistory()
        .then((HistoryInfoList) => {
          // 如果有相簿資訊就開始取得預覽圖
          getDataByInfoList(
            HistoryInfoList,
            (infoItem: any, processResolve: (dataItem: any) => void) => {
              tryService
                .getTryByImgKey({
                  imgKey: infoItem.imgKey,
                })
                .then((resImgDataS) => {
                  const historyItem = infoItem;
                  historyItem.imgURL_s = resImgDataS;
                  processResolve(historyItem);
                });
            },
          ).then((historyList) => {
            context.commit('setTryHistoryList', historyList);
            resolve();
          });
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
};

export default actions;
