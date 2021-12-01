/*eslint-disable*/
export interface UserInfo {
  key?: string; //"4";
  name?: string; //"陳建宇_修改";
  email?: string; //"sam585456525@gmail.com";
  sex?: number; //0;
  birth?: string; //"1997-10-17";
  img?: string; //"base64";
  time?: number; //1600045946;
}

export interface RegisterInfo {
  name: string;
  email: string;
  password: string;
  confirm: string;
  remember: boolean;
  sex: number; //0or1,
  birth: string;
}

export interface ModifyUserInfo {
  name: string;
  email: string;
  password: string;
  confirm: string;
  remember: boolean;
  sex: number; //0or1,
  birth: string;
}

export interface State {
  userInfo: UserInfo;
  userToken: string;
  accessToken: string;
  refreshToken: string;
  isLogin: boolean;
}
