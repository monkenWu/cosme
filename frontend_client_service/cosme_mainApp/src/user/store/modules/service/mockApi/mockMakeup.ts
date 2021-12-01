// // const apiVersion = process.env.VUE_APP_API_VERSION;
// /*eslint-disable*/
// import { Service } from "../makeupService"
import { Method } from 'axios';

type MockServiceType = {
  name: string;
  method: Method;
  url: string | RegExp;
  success: any;
  fail?: any;
  responseFail?: boolean;
}[];

const mockMakeup: MockServiceType = [
  // {
  //   name: 'getMakeupList',
  //   method: 'get',
  //   url: '/creation',
  //   success: [
  //     {
  //       imgKey: 'aaaa123',
  //       name: '夏季上班族氣色妝',
  //       content: '夏季上班族氣色妝的內容...',
  //       updateDate: '2020/09/19',
  //       tags: [
  //         {
  //           text: '妝容',
  //         },
  //         {
  //           text: '正式妝容',
  //         },
  //       ],
  //     },
  //     {
  //       imgKey: 'aaaa456',
  //       name: '情侶約會妝',
  //       content: '情侶約會妝的內容...',
  //       updateDate: '2020/09/19',
  //       tags: [
  //         {
  //           text: '妝容',
  //         },
  //         {
  //           text: '可愛妝容',
  //         },
  //         {
  //           text: '大眼妝容',
  //         },
  //       ],
  //     },
  //   ],
  // },
  // {
  //   name: 'getMakeupWithKey',
  //   method: 'get',
  //   url: /\/creation\/\w*/,
  //   success: 'https://i.imgur.com/kSjsxuy.jpg',
  // },
  // {
  //   name: 'uploadMakeup',
  //   method: 'post',
  //   url: '/creation',
  //   success: {
  //     imgKey: 'aaaa456',
  //     name: '情侶約會妝',
  //     content: '情侶約會妝的內容...',
  //     updateDate: '2020/09/19',
  //     tags: [
  //       {
  //         text: '妝容',
  //       },
  //     ],
  //   },
  // },
  // {
  //   name: 'modifyMakeup',
  //   method: 'put',
  //   url: /\/creation\/\w*/,
  //   success: '',
  // },
  // {
  //   name: 'deleteMakeup',
  //   method: 'delete',
  //   url: /\/creation\/\w*/,
  //   success: '',
  // },
  {
    name: 'getPostList',
    method: 'get',
    url: '/post',
    success: [
      {
        key: '123456798',
        photoID: 'aaaa123',
        name: '妝容名稱',
        time: '2020/09/19',
        imgID: 'aaaa123',
        content:
          '夏季上班族氣色妝超好看妝容介紹..妝容介紹妝容介紹妝容介紹妝容介紹妝容介紹\n妝容介紹妝容介紹妝容介紹 \n妝容介紹 \n妝容介紹 \n妝容介紹 \n妝容介紹 \n妝容介紹 \n妝容介紹 \n妝容介紹 \n妝容介紹 \n妝容介紹 \n妝容介紹 \n妝容介紹 \n妝容介紹 \n妝容介紹 \n妝容介紹 \n妝容介紹 \n妝容介紹 \n妝容介紹 \n妝容介紹 \n妝容介紹 \n妝容介紹 \n妝容介紹 \n妝容介紹 \n妝容介紹 \n妝容介紹 \n妝容介紹 \n妝容介紹 \n\n妝容介紹 \n妝容介紹 ',
        tags: [
          {
            text: '妝容',
          },
          {
            text: '可愛妝容',
          },
          {
            text: '大眼妝容',
          },
          {
            text: '韓系妝容',
          },
          {
            text: '132456789',
          },
        ],
        // imgURL_s: 'https://i.imgur.com/kSjsxuy.jpg',
        // imgURL_L: 'https://i.imgur.com/kSjsxuy.jpg',
        author: {
          name: '霈霈',
          // imgURL: 'https://cdn.style-map.com/post/photo/medium/544894.jpg',
        },
      },
    ],
  },
];

export default mockMakeup;
