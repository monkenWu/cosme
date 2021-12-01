const authorize = {
  path: '/authorize',
  component: () => import('../views/Container.vue'),
  children: [
    {
      path: '',
      name: 'Authorize',
      component: () => import('../views/authorize/Authorize.vue'),
      meta: {
        breadcrumb: [
          { name: '授權管理' },
        ],
      },
    },
    {
      path: 'cancle',
      name: 'AuthorizeCancle',
      component: () => import('../views/authorize/AuthorizeCancled.vue'),
      meta: {
        breadcrumb: [
          { name: '授權管理', link: 'authorize' },
          { name: '撤銷名單' },
        ],
      },
    },
  ],
};

export default authorize;
