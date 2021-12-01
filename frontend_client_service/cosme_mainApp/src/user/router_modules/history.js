const history = {
  path: '/history',
  component: () => import('../views/Container.vue'),
  children: [
    {
      path: '',
      name: 'History',
      component: () => import('../views/history/History.vue'),
      children: [
        {
          path: 'detail/:id',
          name: 'HistoryModal',
          component: () => import('../views/history/HistoryModal.vue'),
        },
      ],
      meta: {
        breadcrumb: [
          { name: '歷程記錄' },
        ],
      },
    },
    {
      path: 'class',
      name: 'Class',
      component: () => import('../views/history/HistoryClass.vue'),
      meta: {
        breadcrumb: [
          { name: '歷程記錄', link: 'history' },
          { name: '分類清單' },
        ],
      },
    },
    {
      path: 'list/:id',
      name: 'List',
      component: () => import('../views/history/HistoryList.vue'),
      meta: {
        breadcrumb: [
          { name: '歷程記錄', link: 'history' },
          { name: '分類清單', link: 'history/class' },
          { name: '清單資料' },
        ],
      },
    },
  ],
};

export default history;
