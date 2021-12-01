const user = {
  path: '/user',
  component: () => import('../views/Container.vue'),
  children: [
    {
      path: '',
      name: 'User',
      component: () => import('../views/user/User.vue'),
      meta: {
        breadcrumb: [
          { name: '個人資料' },
        ],
      },
      children: [
        {
          path: 'crop',
          name: 'UserPhotoCrop',
          component: () => import('../views/user/UserPhotoCrop.vue'),
        },
      ],
    }, {
      path: 'edit',
      name: 'UserEdit',
      component: () => import('../views/user/UserEdit.vue'),
      meta: {
        breadcrumb: [
          { name: '個人資料', link: 'user' },
          { name: '資料編輯' },
        ],
      },
    }, {
      path: 'edit-pwd',
      name: 'UserEditPwd',
      component: () => import('../views/user/UserEditPwd.vue'),
      meta: {
        breadcrumb: [
          { name: '個人資料', link: 'user' },
          { name: '修改密碼' },
        ],
      },
    },
  ],
};

export default user;
