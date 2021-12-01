const state = {
  isLoading: false,
  dom: {
    windowWidth: 0,
    windowtop: 0,
    scrollActive: false,
    inputActive: null,
    // openCropModal: false,
    // cropPhotoIndex: null,
  },
  // cropData: {
  //   maxWidth: 700,
  //   maxHeight: 500,
  //   based: 850,
  // },
};

const getters = {
  // CropBasicStyle: (cropState:any) => ({ maxWidth: `${cropState.cropData.based}px` }),
};

// const actions = {};

const mutations = {
  CropImgLoad(cropState:any, imgRatio:any) {
    /* eslint no-param-reassign: ["error", { "props": false }] */
    if (imgRatio < 1) cropState.cropData.based = cropState.cropData.maxHeight * imgRatio;
    else cropState.cropData.based = cropState.cropData.maxWidth;
  },
  setLoading(State:any, currentState:any) {
    state.isLoading = currentState;
  },
  setWindowWidth(State:any, currentState:any) {
    state.dom.windowWidth = currentState;
  },
  setWindowtop(State:any, currentState:any) {
    state.dom.windowtop = currentState;
  },
  setScrollActive(State:any, currentState:any) {
    state.dom.scrollActive = currentState;
  },
};

const uiModule = {
  state,
  getters,
  // actions,
  mutations,
};

export default uiModule;
