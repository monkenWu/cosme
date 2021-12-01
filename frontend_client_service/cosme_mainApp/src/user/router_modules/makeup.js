const makeup = {
  path: '/makeup',
  component: () => import('../views/Container.vue'),
  children: [{
    path: '',
    name: 'makeup',
    component: () => import('../views/makeup/Makeup.vue'),
    meta: {
      breadcrumb: [{
        name: '妝容分享',
      }],
      public: true,
    },
    children: [{
      path: 'detail/:id',
      name: 'MakeupPostDetail',
      component: () => import('../views/makeup/MakeupPostDetail.vue'),
      meta: {
        public: true,
      },
    }],
  }, {
    path: 'add',
    name: 'MakeupAdd',
    component: () => import('../views/makeup/MakeupAdd.vue'),
    meta: {
      breadcrumb: [{
        name: '妝容分享',
        link: 'makeup',
      },
      {
        name: '新增妝容',
      },
      ],
    },
    children: [{
      path: 'crop',
      name: 'MakeupCrop',
      component: () => import('../views/makeup/MakeupCrop.vue'),
    },
    {
      path: 'camera',
      name: 'MakeupCamera',
      component: () => import('../views/makeup/MakeupCamera.vue'),
    },
    ],
  }, {
    path: 'manage',
    name: 'MakeupManage',
    component: () => import('../views/makeup/MakeupManage.vue'),
    meta: {
      breadcrumb: [{
        name: '妝容分享',
        link: 'makeup',
      },
      {
        name: '管理妝容',
      },
      ],
    },
    children: [{
      path: 'detail/:id',
      name: 'MakeupManageDetail',
      component: () => import('../views/makeup/MakeupManageDetail.vue'),
    }],
  }, {
    path: 'edit/:id',
    name: 'MakeupEdit',
    component: () => import('../views/makeup/MakeupEdit.vue'),
    meta: {
      breadcrumb: [{
        name: '妝容分享',
        link: 'makeup',
      },
      {
        name: '管理妝容',
        link: 'makeup/manage',
      },
      {
        name: '編輯妝容',
      },
      ],
    },
  }],
};

export default makeup;
