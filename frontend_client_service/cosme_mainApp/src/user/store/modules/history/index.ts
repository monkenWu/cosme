/* eslint-disable import/extensions */
/* eslint-disable import/no-unresolved */
import { Module } from 'vuex';
import getters from './getters';
import actions from './actions';
import mutations from './mutations';
import { State } from './types';
import { RootState } from '../../types';

const state:State = {
  // 試妝紀錄
  tryHistoryList: [
    // {
    //   key: 'aaa111',
    //   productID: 'a1111',
    //   modelIMG: 'img111',
    //   com: 'KATE',
    //   comURL: 'https://www.kate-global.net/tw/',
    //   productName: '同調六色眼彩盤',
    //   productURL: 'https://www.kate-global.net/tw/pickup/tone_dimensional_palette/',
    //   // 需要另外補上的
    //   stars: 0,
    //   date: '2020/07/12',
    //   // 另外讀取的
    //   imgURL_s: 'https://i.imgur.com/nYrMAsh.jpg',
    //   imgURL_L: 'https://i.imgur.com/JRmkwaJ.jpg',
    // }, {
    //   key: 'aaa222',
    //   productID: 'a2222',
    //   modelIMG: 'img222',
    //   com: 'Maybelline',
    //   comURL: 'https://www.maybelline.com.tw/',
    //   productName: '超持久霧感液態唇膏 (RED)',
    //   productURL: 'https://www.maybelline.com.tw/lip/lip-polish/ssmi-rogue-red',
    //   // 需要另外補上的
    //   stars: 0,
    //   colorID: 'RD006',
    //   date: '2020/07/12',
    //   // 另外讀取的
    //   imgURL_s: 'https://i.imgur.com/3EIOVrk.jpg',
    //   imgURL_L: 'https://i.imgur.com/kSjsxuy.jpg',
    // }, {
    //   key: 'aaa333',
    //   productID: 'a3331',
    //   modelIMG: 'img333',
    //   com: 'KATE',
    //   comURL: 'https://www.kate-global.net/tw/',
    //   productName: '同調六色眼彩盤',
    //   productURL: 'https://www.kate-global.net/tw/pickup/tone_dimensional_palette/',
    //   // 需要另外補上的
    //   stars: 0,
    //   date: '2020/07/12',
    //   // 另外讀取的
    //   imgURL_s: 'https://i.imgur.com/pHcrKCS.jpg',
    //   imgURL_L: 'https://i.imgur.com/smT4EeP.jpg',
    // }, {
    //   key: 'aaa444',
    //   productID: 'a4442',
    //   modelIMG: 'img444',
    //   com: 'Maybelline',
    //   comURL: 'https://www.maybelline.com.tw/',
    //   productName: '超持久霧感液態唇膏 (RED)',
    //   productURL: 'https://www.maybelline.com.tw/lip/lip-polish/ssmi-rogue-red',
    //   // 需要另外補上的
    //   stars: 0,
    //   colorID: 'RD006',
    //   date: '2020/07/12',
    //   // 另外讀取的
    //   imgURL_s: 'https://i.imgur.com/5CG0ibp.jpg',
    //   imgURL_L: 'https://i.imgur.com/e66v4G4.jpg',
    // }, {
    //   key: 'aaa555',
    //   productID: 'a5551',
    //   modelIMG: 'img555',
    //   com: 'KATE',
    //   comURL: 'https://www.kate-global.net/tw/',
    //   productName: '同調六色眼彩盤',
    //   productURL: 'https://www.kate-global.net/tw/pickup/tone_dimensional_palette/',
    //   // 需要另外補上的
    //   stars: 0,
    //   date: '2020/07/12',
    //   // 另外讀取的
    //   imgURL_s: 'https://i.imgur.com/GbrRQks.jpg',
    //   imgURL_L: 'https://i.imgur.com/bLMNA0m.jpg',
    // }, {
    //   key: 'aaa666',
    //   productID: 'a6662',
    //   modelIMG: 'img666',
    //   com: 'Maybelline',
    //   comURL: 'https://www.maybelline.com.tw/',
    //   productName: '超持久霧感液態唇膏 (RED)',
    //   productURL: 'https://www.maybelline.com.tw/lip/lip-polish/ssmi-rogue-red',
    //   // 需要另外補上的
    //   stars: 0,
    //   colorID: 'RD006',
    //   date: '2020/07/12',
    //   // 另外讀取的
    //   imgURL_s: 'https://i.imgur.com/N7Dnp4Q.jpg',
    //   imgURL_L: 'https://i.imgur.com/N7Dnp4Q.jpg',
    // },
  ],
};

const photoModule: Module<State, RootState> = {
  state,
  getters,
  actions,
  mutations,
};

export default photoModule;
