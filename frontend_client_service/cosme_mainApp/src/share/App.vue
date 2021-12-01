<template>
  <div id="app">
    <loading :active.sync="isLoading">
      <img src="@/assets/img/loading.svg" width="150" />
    </loading>
    <main class="layout">
      <section class="share-content">
        <h4 class="tittle">線上試妝</h4>
        <div class="photo-content">
          <div class="photo-item makeup-item">
            <h1>{{ creation.data.name }}</h1>
            <img :src="creation.m_img" alt="" />
          </div>
          <div class="photo-item">
            <h1>你的照片</h1>
            <img v-if="userPhoto.croped != ''" :src="userPhoto.croped" alt="" class="user-photo" @click="views = 'crop';"/>
            <div v-if="userPhoto.croped == ''" class="default-user-img">
              <font-awesome-icon icon="image" />
            </div>
          </div>
          <div class="photo-buttons">
            <TabButton
              title="拍攝照片"
              subtitle="Take a Photo"
              color="#74ebd5"
              subtitleColor="#54debd"
              :fwiIcon="['fas', 'camera']"
              @btn-click="
                views = 'camera';
                isLoading = true;
              "
            />
            <TabButton
              title="從相簿中選擇"
              subtitle="Album"
              color="#ffafbe"
              subtitleColor="#f295a8"
              :fwiIcon="['far', 'images']"
            >
              <ImgFileInput @uploadFinishHook="photoReadyToCrop" />
            </TabButton>
          </div>
        </div>
        <button v-if="tryImgData != ''" class="submit" @click="views = 'showTry'">
          <span>合成結果</span>
        </button>
        <button v-if="userPhoto.croped != ''" class="submit" @click="tryMakeup">
          <span>開始試妝</span>
        </button>
        <a href="https://cosme.dev/mainApp/#/makeup" class="cosme-link mt-4" target="_blank"><img src="@/assets/img/logo.png" class="mr-2" width="30px" alt=""><span>試更多妝就在 Cosme 妝典</span></a>
      </section>
      <Modal
        ref="modal"
        v-show="isCameraReady && views == 'camera'"
        @close-modal="
          views = '';
          isCameraReady = false;
        "
      >
        <Camera
          ref="camera"
          v-if="views == 'camera'"
          @capture-finish="photoReadyToCrop"
          @camera-ready="cameraReadyDo"
          @cancel="
            views = '';
            isCameraReady = false;
          "
        >
        </Camera>
      </Modal>
      <Modal ref="modal" v-show="views == 'crop'" @close-modal="views = ''">
        <Crop
          :cropingPhotoURL="userPhoto.original"
          @clip-finish="clipFinish"
          @cancel="views = ''"
        />
      </Modal>
      <Modal ref="modal" v-show="views == 'showTry'" @close-modal="views = ''">
        <img :src="tryImgData" width="100%" alt="" />
      </Modal>
    </main>
  </div>
</template>

<script>
/* eslint-disable import/no-unresolved */
import axios from 'axios';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import TabButton from '@/components/TabButton.vue';
import ImgFileInput from '@/components/ImgFileInput.vue';
import Modal from '@/components/Modal/PopModal.vue';
import Camera from '@/components/Camera.vue';
import Crop from '@/components/Crop.vue';
import faceDetect from '@/helpers/faceDetect';
import makeupService from '@/user/store/modules/service/makeupService';
import tryService from '@/user/store/modules/service/tryService.ts';

export default {
  name: 'App',
  components: {
    TabButton,
    ImgFileInput,
    Modal,
    Camera,
    Loading,
    Crop,
  },
  data() {
    return {
      makeupImgKey: '',
      withoutKey: '',
      tryImgData: '',
      creation: {
        data: {},
        m_img: '',
        L_img: '',
      },
      isLoading: false,
      isCameraReady: false,
      views: '',
      userPhoto: {
        original: '',
        croped: '',
      },
    };
  },
  methods: {
    cameraReadyDo() {
      this.isLoading = false;
      this.isCameraReady = true;
    },
    photoReadyToCrop(img) {
      this.views = '';
      this.userPhoto.original = img;
      this.views = 'crop';
    },
    clipFinish(imgData) {
      this.userPhoto.croped = imgData;
      this.isLoading = true;
      faceDetect(imgData, 0.6)
        .then(() => {
          this.$swal('成功', '偵測到臉部，可以試妝', 'success');
        })
        .finally(() => {
          this.isLoading = false;
        });
      this.views = '';
    },
    tryMakeup() {
      this.isLoading = true;
      tryService
        .tryShareMakeup({
          makeupKey: this.makeupImgKey,
          withoutImgData: this.userPhoto.croped,
        })
        .then((res) => {
          this.tryImgData = res;
          this.views = 'showTry';
          this.isLoading = false;
        });
    },
  },
  mounted() {
    axios.defaults.baseURL = process.env.VUE_APP_API_BASE_URL;
    // eslint-disable-next-line
    const key = location.href.split("?key=")[1];
    makeupService
      .getMakeupPostsWithKey({ key })
      .then((res) => {
        this.creation.data = res;
        this.makeupImgKey = res.photoID;
        return makeupService.getMakeupWithKey({
          imgKey: res.photoID,
          size: 's',
        });
      })
      .then((res) => {
        this.creation.m_img = res;
      });
  },
};
</script>
<style lang="scss">
@import "./assets/scss/share";

.__view {
  display: flex;
  flex-direction: column;
}
#app {
  min-height: 100vh;
}
</style>
