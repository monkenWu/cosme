/* eslint-disable */
import MockAdapter from "axios-mock-adapter";
import axios from "axios";

import mockPhoto from "./mockPhoto";
import mockUser from "./mockUser";
// import mockMakeup from "./mockMakeup";

// const mockList = [...mockMakeup];

function mock() {
  // const mockInstance: any = new MockAdapter(axios, { delayResponse: 1000 });

  // new mockPhoto(mockInstance);
  // new mockUser(mockInstance);

  // mockList.forEach((item) => {
  //   const { name, method, url, success, fail, responseFail } = item;

  //   // get -> onGet
  //   const mockMethod = `on${method.slice(0, 1).toUpperCase() +
  //     method.slice(1).toLowerCase()}`;

  //   mockInstance[mockMethod](url).reply((requestObj: any) => {
  //     console.log(`mock:${name}:${method}:${url}`);
  //     if (responseFail !== true) {
  //       return [
  //         200,
  //         {
  //           statusCode: "200",
  //           msg: "success",
  //           data: success,
  //         },
  //       ];
  //     }
  //     if (fail !== undefined) {
  //       return fail;
  //     }
  //     return [
  //       401,
  //       {
  //         msg: "failed",
  //       },
  //     ];
  //   });
  // });

  // // 沒有配對到的會發送真實ajax
  // mockInstance.onAny().passThrough();
}

export default mock;
