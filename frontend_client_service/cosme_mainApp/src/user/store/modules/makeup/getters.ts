/* eslint-disable import/no-unresolved */
/* eslint-disable import/extensions */
import { GetterTree } from 'vuex';
import { State } from './types';
import { RootState } from '../../types';

const getters:GetterTree<State, RootState> = {
  // 以id搜尋圖片資料
  getUserMakeupPhotoById: (state) => (id:number) => state.userMakeupList.find(
    (item) => item.key === id.toString(),
  ),
  getUserMakeupImgKey: (state) => (key:number) => {
    const makeupItem = state.userMakeupList.find(
      (item) => item.key === key.toString(),
    );
    if (makeupItem) {
      return makeupItem.photoID;
    }
    return '';
  },
  getMakeupPostImgKey: (state) => (key: number) => {
    const postItem = state.makeupPostList.find(
      (item) => item.key === key.toString(),
    );
    if (postItem) {
      return postItem.photoID;
    }
    return '';
  },
};

export default getters;
