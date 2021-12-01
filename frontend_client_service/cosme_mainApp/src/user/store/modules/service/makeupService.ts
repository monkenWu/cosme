/* eslint-disable */
import axios from "axios";
import {
  MakeupInfo,
  UserMakeupPhotoItem,
  MakeupPostItem,
} from "../makeup/types";

// Service 介面
export interface Service {
  getMakeupList(payload?: any): Promise<UserMakeupPhotoItem[]>;
  getMakeupPostsWithKey(payload: { key: string }): Promise<MakeupPostItem>; //單張完妝照資訊
  getMakeupWithKey(payload: { imgKey: string; size: string }): Promise<string>; //單張照片資源
  modifyMakeup(payload: { key: string; newContent: MakeupInfo }): Promise<void>;
  uploadMakeup(payload: { img: string } & MakeupInfo): Promise<void>;
  deleteMakeup(payload: { key: string }): Promise<void>;
  getPostList(payload?: any): Promise<MakeupPostItem[]>;
  getLikeTags(payload: { like: string }): Promise<any[]>;
}

const makeupService: Service = {
  getMakeupList() {
    return new Promise((resolve, reject) => {
      axios({
        method: "get",
        url: "/creation",
        params: {
          offset: 0,
          size: 500,
        },
      })
        .then((res) => {
          const resList: MakeupPostItem[] = res.data.data.map((item: any) => {
            return {
              key: item.key, //'123456798',
              photoID: item.imgKey, //'aaaa123',
              name: item.title, //'夏季上班族氣色妝',
              time: item.createdAt, //'2020/09/19',
              imgID: item.imgKey, //'aaaa123',
              content: item.content,
              tags: item.tags.map((tagItem: string) => {
                return { text: tagItem };
              }),
              imgURL_s: "", //'base64',
              imgURL_L: "", //'base64',
              products: item.products,
            };
          });
          resolve(resList);
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  getMakeupPostsWithKey({ key }) {
    return new Promise((resolve, reject) => {
      axios({
        method: "get",
        url: `/creation/${key}`,
      })
        .then((res) => {
          const resData = res.data.data;
          const makeupPostData: MakeupPostItem = {
            key: resData.key, //'123456798',
            photoID: resData.imgKey, //'aaaa123',
            name: resData.title, //'夏季上班族氣色妝',
            time: resData.createdAt, //'2020/09/19',
            imgID: resData.imgKey, //'aaaa123',
            content: resData.content,
            tags: resData.tags.map((tagItem: string) => {
              return { text: tagItem };
            }),
            imgURL_s: "", //'base64',
            imgURL_L: "", //'base64',
            products: resData.products,
            author: {
              name: resData.author.name,
              key: resData.author.key,
              imgURL: resData.author.img,
            },
          };
          resolve(makeupPostData);
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  getMakeupWithKey({ imgKey, size }) {
    return new Promise((resolve, reject) => {
      axios({
        method: "get",
        url: `/reference/${imgKey}`,
        params: (() => {
          if (size == "L") {
            return { isFull: true };
          }
          if (size == "s") {
            return { isFull: false };
          }
          return { isFull: false };
        })(),
      })
        .then((res) => {
          resolve(res.data.data);
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  uploadMakeup({ img, name, content, tags, products }) {
    return new Promise((resolve, reject) => {
      axios({
        method: "post",
        url: "/creation",
        data: {
          img,
          title: name,
          content,
          tags: tags.map((item) => item.text),
          products: (() => {
            if (products) {
              return products;
            }
            return [];
          })(),
        },
      })
        .then((res) => {
          resolve();
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  modifyMakeup({ key, newContent: { name, content, tags, products } }) {
    return new Promise((resolve, reject) => {
      axios({
        method: "put",
        url: `/creation/${key}`,
        data: {
          title: name,
          content,
          tags: tags.map((item) => item.text),
          products: (() => {
            if (products) {
              return products;
            }
            return [];
          })(),
        },
      })
        .then((res) => {
          resolve();
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  deleteMakeup({ key }) {
    return new Promise((resolve, reject) => {
      axios({
        method: "delete",
        url: `/creation/${key}`,
      })
        .then((res) => {
          resolve();
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  getPostList({ searchTxt }) {
    return new Promise((resolve, reject) => {
      const like = () => {
        if (searchTxt !== "" && searchTxt[0] !== "#") {
          return `&like=${searchTxt}`;
        }
        return "";
      };

      const tags = () => {
        if (searchTxt !== "" && searchTxt[0] === "#") {
          return {
            tags: [searchTxt.substring(1)],
          };
        }
        return {};
      };

      axios({
        method: "post",
        url: `/creation/post?offset=0&size=16${like()}`,
        data: tags(),
      })
        .then((res) => {
          const resList: MakeupPostItem[] = res.data.data.map((item: any) => {
            return {
              key: item.key, //'123456798',
              photoID: item.imgKey, //'aaaa123',
              name: item.title, //'夏季上班族氣色妝',
              time: item.createdAt, //'2020/09/19',
              imgID: item.imgKey, //'aaaa123',
              content: item.content,
              tags: item.tags.map((tagItem: string) => {
                return { text: tagItem };
              }),
              imgURL_s: "", //'base64',
              imgURL_L: "", //'base64',
              products: item.products,
              author: {
                name: item.author.name,
                key: item.author.key,
                imgURL: item.author.img,
              },
            };
          });
          resolve(resList);
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  getLikeTags({ like }) {
    return new Promise((resolve, reject) => {
      axios({
        method: "get",
        url: `/tag`,
        params: {
          like,
          size: 10,
        },
      })
        .then((res) => {
          resolve(res.data.tags);
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
};

export default makeupService;
