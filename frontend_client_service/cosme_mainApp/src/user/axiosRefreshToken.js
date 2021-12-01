/*eslint-disable*/



const axiosRefreshToken = (axios, store) => {
  let isRefreshing = false;
  let tasks = [];

  axios.interceptors.response.use(
    response => {
      return response;
    },
    err => {
      const {
        config,
        response: {
          status,
          data
        }
      } = err;
      if (status === 403 && data.statusCode === "Auth004") {

        // 尚未開始更新即啟動更新
        if (!isRefreshing) {
          isRefreshing = true;
          store
            .dispatch("GET_NEW_ACCESSTOKEN")
            // 更新完成或失敗，皆會處理在tasks中等待的promise
            .then((res) => {
              isRefreshing = false;
              tasks.forEach(item => {
                item.originalRequest.headers["Access-Token"] = res.accessToken;
                item.resolve(axios(item.originalRequest));
              });
              tasks = [];
            })
            .catch(err => {
              isRefreshing = false;
              tasks.forEach(item => {
                item.reject(err);
              });
            });
        }

        // 回傳promise並把其放置在等待區等候token更新
        return new Promise((resolve, reject) => {
          tasks.push({
            originalRequest: config,
            resolve,
            reject
          });
        });
        
      } else {
        return Promise.reject(err);;
      }
    }
  );
}

export default axiosRefreshToken;