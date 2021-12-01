// console.log(`build cosme module : ${process.env.COSME_MODULE}`);
module.exports = {
  publicPath: process.env.NODE_ENV === 'production'
    ? `/${process.env.COSME_MODULE}/`
    : '/',
  // configureWebpack: {
  //   module: {
  //     rules: [
  //       {
  //         test: /\.worker\.js$/,
  //         use: { loader: 'worker-loader' },
  //       },
  //     ],
  //   },
  // },
};
