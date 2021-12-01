#!/bin/bash
cd ./cosme_mainApp
npm install
npm run build:all
cd ../cosme_sdk
npm run build
cd ../cosme_frontend_server
npm install
