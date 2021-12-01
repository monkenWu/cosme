const analysis = {
  path: '/analysis',
  component: () => import('../views/Container.vue'),
  children: [
    {
      path: '',
      name: 'Analysis',
      component: () => import('../views/analysis/Analysis.vue'),
    },
  ],
};

export default analysis;
