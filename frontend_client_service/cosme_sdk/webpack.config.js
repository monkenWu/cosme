var path = require('path')

module.exports = {
  entry: ['./src/main'], 
  output: {
    path: path.join(__dirname, '../cosme_frontend_server/public/sdk'),
    filename: 'cosme_sdk.js'
  }
}