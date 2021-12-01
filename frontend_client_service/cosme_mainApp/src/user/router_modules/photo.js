const photo = {
  path: '/photo',
  component: () => import('../views/Container.vue'),
  children: [
    {
      path: '',
      name: 'Photo',
      component: () => import('../views/photo/Photo.vue'),
      meta: {
        breadcrumb: [
          { name: '照片管理' },
        ],
      },
      children: [
        {
          path: 'detail/:id',
          name: 'PhotoModal',
          component: () => import('../views/photo/PhotoModal.vue'),
        },
      ],
    }, {
      path: 'add',
      name: 'PhotoAdd',
      component: () => import('../views/photo/PhotoAdd.vue'),
      meta: {
        breadcrumb: [
          { name: '照片管理', link: 'photo' },
          { name: '新增未上妝照片' },
        ],
      },
      children: [
        {
          path: 'crop/:id',
          name: 'PhotoCrop',
          component: () => import('../views/photo/PhotoCrop.vue'),
        },
        {
          path: 'camera',
          name: 'PhotoCamera',
          component: () => import('../views/photo/PhotoCamera.vue'),
        },
      ],
    }, {
      path: 'done',
      name: 'PhotoDone',
      component: () => import('../views/photo/PhotoDone.vue'),
      meta: {
        breadcrumb: [
          { name: '照片管理', link: 'photo' },
          { name: '上傳完成' },
        ],
      },
    },
  ],
};

export default photo;
