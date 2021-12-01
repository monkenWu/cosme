### Set ENV 
```
sudo apt-get update
sudo apt-get install -y nodejs
```

### Build process
```
cd cosme_mainApp
npm install
npm run build:user
npm run build:share
cd ../cosme_sdk
npm install
npm run build
cd ../cosme_frontend_server
npm install
```
### Start server
```
cd cosme_frontend_server
npm start
```