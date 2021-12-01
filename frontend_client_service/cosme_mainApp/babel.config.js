// module.exports = {
//   presets: [
//     '@vue/cli-plugin-babel/preset',
//   ],
// };

module.exports = {
  presets: [
    ['@vue/app', {
      polyfills: [
        'es.promise',
        'es.symbol',
      ],
    }],
  ],
};
