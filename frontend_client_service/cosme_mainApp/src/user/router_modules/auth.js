const auth = {
  path: '',
  component: () => import('../views/Container.vue'),
  children: [
    {
      path: '',
      name: 'Login',
      component: () => import('../views/Login.vue'),
      meta: { public: true },
    }, {
      path: 'register',
      name: 'Register',
      component: () => import('../views/Register.vue'),
      meta: { public: true },
    },
  ],
};

export default auth;
