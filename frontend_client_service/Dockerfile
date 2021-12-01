FROM node:8

RUN apt update
RUN apt install python -y
COPY . /app
WORKDIR /app/cosme_mainApp
RUN npm install
RUN npm run build:user
RUN npm run build:share
WORKDIR /app/cosme_sdk
RUN npm install
RUN npm run build
WORKDIR /app/cosme_frontend_server
RUN npm install

EXPOSE 3000
CMD ["/bin/bash","-c","cd /app/cosme_frontend_server && npm start"]