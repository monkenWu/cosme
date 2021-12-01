// /* eslint-disable import/no-unresolved */
// /* eslint-disable import/extensions */
// import mutations from '../mutations';
// import { State } from '../types';

// const {
//   setNoMakeupPhotoList,
//   setPhotoDataL,
// } = mutations;

// const baseState:State = {
//   // 素顏照相簿
//   noMakeupPhotoList: {
//     data: [],
//     allDataLength() {
//       return this.data.length;
//     },
//   },
//   makeupEffectPreviewList: [],
//   uploadPhotoList: [],
//   uploadPhotoProgress: 0,
// };

// const photoListTestData = [{
//   imgKey: 'img03',
//   tumbnial: 'https://i.imgur.com/nYrMAsh.jpg',
//   isDefault: 0,
// }, {
//   imgKey: 'img04',
//   tumbnial: 'https://i.imgur.com/nYrMAsh.jpg',
//   isDefault: 0,
// }];

// test('setting new NoMakeupPhotoList', () => {
//   const state = baseState;
//   setNoMakeupPhotoList(state, photoListTestData);
//   expect(state.noMakeupPhotoList.data).toBe(photoListTestData);
//   expect(state.noMakeupPhotoList.allDataLength).toBe(photoListTestData.length);
// });

// test('set Photo big img data', () => {
//   const state = baseState;
//   state.noMakeupPhotoList.data = photoListTestData;
//   const testObj = {
//     imgKey: 'img03',
//     imgData_L: 'testData',
//   };
//   setPhotoDataL(state, testObj);
//   const keyItem = state.noMakeupPhotoList.data.find((item) => item.imgKey === testObj.imgKey);
//   expect(keyItem.imgURL_L).toBe(testObj.imgData_L);
// });
