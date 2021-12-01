import Vue from 'vue';
import Router from 'vue-router';
import makeup from './router_modules/makeup';
import home from './router_modules/home';
import photo from './router_modules/photo';
import history from './router_modules/history';
import authorize from './router_modules/authorize';
import forgetPassword from './router_modules/forgetPassword';
import user from './router_modules/user';
import auth from './router_modules/auth';
import analysis from './router_modules/analysis';

Vue.use(Router);
export default new Router({
  routes: [
    {
      path: '/',
      component: () => import('./views/Layout_main.vue'),
      children: [
        makeup,
        home,
        photo,
        history,
        user,
        authorize,
        forgetPassword,
        analysis,
      ],
    },
    {
      path: '/auth',
      component: () => import('./views/Layout_default.vue'),
      children: [
        auth,
      ],
    },
    // {
    //   path: '*',
    //   redirect: '404',
    // },
  ],
});
