import axios from 'axios';

const userService = {
  // 註冊
  signup(payload:any) {
    return new Promise((resolve, reject) => {
      axios({
        method: 'post',
        url: '/user',
        data: {
          name: payload.name.toString(),
          email: payload.email.toString(),
          password: payload.password.toString(),
          sex: Number(payload.sex),
          birth: payload.birth.toString(),
        },
      })
        .then((res) => {
          resolve(res.data);
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  // 登入
  login(payload:any) {
    return new Promise((resolve, reject) => {
      axios({
        method: 'post',
        url: '/user/login',
        data: {
          account: payload.email,
          password: payload.password,
        },
      })
        .then((res) => {
          resolve(res.data.data);
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  // 取得使用者資料
  getUserInfo() {
    return new Promise((resolve, reject) => {
      axios({
        method: 'get',
        url: '/user',
      })
        .then((res) => {
          console.log(res);
          const userRes = res.data.data;
          userRes.sex = parseInt(userRes.sex);
          resolve(userRes);
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  // 修改使用者資料
  modifyUserInfo(payload: any) {
    // 轉換型別
    payload.business = payload.business === 'true';
    return new Promise((resolve, reject) => {
      axios({
        method: 'put',
        url: '/user',
        data: payload,
      })
        .then((res) => {
          resolve(res.data.data);
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  // 更新 accessTocken
  getNewAccessToken(expiredAccessToken:any, refreshToken:any) {
    return new Promise((resolve, reject) => {
      axios({
        method: 'put',
        url: `/user/refresh/${expiredAccessToken}`,
        headers: {
          'Refresh-Token': refreshToken,
        },
      })
        .then((res) => {
          resolve(res.data);
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  // 登出
  logout(accessToken:any, refreshToken:any) {
    return new Promise((resolve, reject) => {
      axios({
        method: 'delete',
        url: `/user/${accessToken}`,
        headers: {
          'Refresh-Token': refreshToken,
        },
      })
        .then((res) => {
          console.log(res);
          resolve(res);
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
};
export default userService;
