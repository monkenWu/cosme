/* eslint-disable import/no-unresolved */
import Vue from 'vue';
// import Loading from 'vue-loading-overlay';

import './importModules/tmp1';
import './importModules/tmp2';
import './importModules/ui';

import axios from 'axios';
import authRefresh from './axiosRefreshToken';

import App from './App.vue';
import router from './router';
import './bus';

import store from './store/index.ts';

Vue.config.productionTip = false;

// 驗證
router.beforeEach((to, from, next) => {
  // console.log('Too', to.meta.public);
  if (!to.meta.public && !store.state.user.isLogin) {
    next({
      path: '/auth',
    });
  } else next();
});

// axios.defaults.baseURL = 'http://localhost:8080';
// axios.defaults.baseURL = 'https://api.cosme.dev/api/v1';
axios.defaults.baseURL = process.env.VUE_APP_API_BASE_URL;

// 重新取得accessToken並自動重新request
authRefresh(axios, store);

function vueInit() {
  new Vue({
    store,
    router,
    render: (h) => h(App),
  }).$mount('#app');
}

// 自動登入
store.dispatch('REMEMBER_LOGIN').finally(() => {
  vueInit();
});
