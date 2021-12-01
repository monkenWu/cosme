/* eslint-disable import/no-unresolved */
/* eslint-disable import/extensions */
import { GetterTree } from 'vuex';
import { State } from './types';
import { RootState } from '../../types';

const getters:GetterTree<State, RootState> = {
  // 以id搜尋圖片資料
  getNoMakeupPhotoById: (state) => (id:number) => state.noMakeupPhotoList.data.find(
    (noMakeupPhoto) => noMakeupPhoto.imgKey === id.toString(),
  ),
};

export default getters;
