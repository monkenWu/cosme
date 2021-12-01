/* eslint-disable camelcase */
/* eslint-disable import/no-unresolved */
/* eslint-disable import/extensions */
import Vuex, { StoreOptions } from 'vuex';
import Vue from 'vue';
import { RootState } from './types';
import user from './modules/user/index';
import uiModule from './modules/ui';
import photoModule from './modules/photo/index';
import makeupModule from './modules/makeup/index';
import historyModule from './modules/history/index';

// 掛載 mockApi
import mock from './modules/service/mockApi/main';

// 是否使用 mockApi
if (process.env.VUE_APP_USE_MOCK_API === 'true') {
  mock();
}

Vue.use(Vuex);

const store: StoreOptions<RootState> = {
  state: {
    version: '1.0.0',
  },
  modules: {
    user,
    uiModule,
    photoModule,
    makeupModule,
    historyModule,
  },
};

export default new Vuex.Store<RootState>(store);
