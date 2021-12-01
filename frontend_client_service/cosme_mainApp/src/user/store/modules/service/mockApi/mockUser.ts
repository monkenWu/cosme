/* eslint-disable */
// const apiVersion = process.env.VUE_APP_API_VERSION;
const apiUri = "/user";

class mockUser {

  mock 

  constructor(mockInstance: any) {
    this.mock = mockInstance;

    // 註冊 mockAPI 可傳入 fales，將會回傳錯誤狀態
    // this.mockSignup();
    // this.mockGetUserInfo(false);
    // this.mockModifyUserInfo();
    // this.mockLogin(false);

    // 還沒好
    // this.mockGetNewAccessToken();
  }

  // 註冊API
  mockSignup(statusSuccess = true) {
    this.mock.onPost(apiUri).reply((config:any) => {
      console.log(`MOCKserver:Signup`, config.data);
      if (statusSuccess == true) {
        return [
          200, {
            msg: "Created"
          },
        ];
      } else {
        return [
          401, {
            msg: "failed"
          }
        ]
      }

    });
  }

  // 登入
  mockLogin(statusSuccess = true) {
    this.mock.onPost(`/user/login`).reply((config:any) => {
      console.log(`MOCKserver:Login`, config);
      if (statusSuccess == true) {
        return [
          200, {
            msg: "success",
            data: {
              "accessToken": `!@#$%^&*QWERT:${100+Math.floor(Math.random() * Math.floor(99))}`,
              "refreshToken": `!@#$%^&*QWERT:${100+Math.floor(Math.random() * Math.floor(99))}`
            }
          },
        ];
      } else {
        return [
          403, {
            statusCode:"Auth004",
            msg: "login failed"
          }
        ]
      }

    });
  }

  // 取得使用者資料
  mockGetUserInfo(statusSuccess = true) {
    this.mock.onGet(apiUri).reply((config:any) => {
      console.log(`MOCKserver:GetUserInfo`, config);
      if (statusSuccess == true) {
        return [
          200, {
            data: {
              name: "mockUser",
              img: "url",
              email: "mockUser@mail.mail",
              sex: 0,
              birth: "1999/01/01"
            }

          },
        ];
      } else {
        return [
          403, {
            msg: "error msg string",
            statusCode:"Auth004",
          }
        ]
      }
    });
  }

  // 修改使用者資料
  mockModifyUserInfo(statusSuccess = true) {
    this.mock.onPut(apiUri).reply((config:any) => {
      console.log(`MOCKserver:modifyUserInfo`, config.data);
      if (statusSuccess == true) {
        return [
          200, {
            msg: "Updated"
          },
        ];
      } else {
        return [
          401, {
            msg: "error msg string"
          }
        ]
      }
    })
  }


  // // 註冊API
  // mockGetNewAccessToken(statusSuccess = true) {
  //   this.mock.onPost(apiUri).reply((config) => {
  //     console.log(`server:GetNewAccessToken`, config.data);
  //     if (statusSuccess == true) {
  //       return [
  //         200, {
  //           msg: "Created"
  //         },
  //       ];
  //     } else {
  //       return [
  //         401, {
  //           msg: "failed"
  //         }
  //       ]
  //     }

  //   });
  // }


}


export default mockUser;