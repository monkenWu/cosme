<template>
  <section class="photo">
    <router-view />
    <LightBox ref="lightbox" :media="fileData" :show-light-box="false" />
    <div class="animate__animated animate__faster animate__fadeIn" v-if="!upLoadingImg">
      <div class="route-title">
        <a href="#" @click.prevent="$router.go(-1)">
          <font-awesome-icon :icon="['fas', 'arrow-left']" style="color: #ffc39f" />
          回上一頁
        </a>
        <h3>新增未上妝照</h3>
      </div>
      <div class="d-flex box-content">
        <TabButton
          title="拍攝照片"
          subtitle="Take a Photo"
          color="#74ebd5"
          subtitleColor="#54debd"
          :fwiIcon="['fas', 'camera']"
          @btn-click="$router.push({name:'PhotoCamera'})"
        />
        <TabButton
          title="從相簿中選擇"
          subtitle="Album"
          color="#ffafbe"
          subtitleColor="#f295a8"
          :fwiIcon="['far', 'images']"
        >
          <ImgFileInput @uploadFinishHook="uploadFinish" />
        </TabButton>
      </div>
      <div class="divider-content" v-if="fileData">
        <h4>已選擇圖像({{ fileData.length }}/1)</h4>
        <hr />
        <button @click="uploadPhoto" :class="{disabled: fileData.length < 1}">確定上傳</button>
      </div>
      <ul class="photo-content row mt-3" v-if="fileData.length">
        <li
          class="col-xl-3 col-lg-4 col-md-3 col-sm-6 mb-4"
          v-for="(item, index) in fileData"
          :key="index"
        >
          <PhotoAddItem
            :index="index"
            :img="item.thumb"
            @open-detail="openLightBox(index)"
            @re-crop="$router.push({ path: `add/crop/${index}` })"
            @delete="deleteUploadPhoto(index)"
          />
        </li>
      </ul>
    </div>
    <div
      class="animate__animated animate__faster animate__fadeIn loading-progress"
      v-if="upLoadingImg"
    >
      <h3>開始上傳...</h3>
      <div class="bar-con">
        <div class="bar" :style="{width: `${progress}%`}" />
      </div>
      <button class="mt-3" @click="cancelUpload">
        <font-awesome-icon :icon="['fas', 'times']" class="text-danger" />取消上傳
      </button>
    </div>
  </section>
</template>

<script>
import LightBox from 'vue-image-lightbox';
/* eslint-disable import/no-unresolved */
import faceDetect from '@/helpers/faceDetect';
import TabButton from '@/components/TabButton.vue';
import ImgFileInput from '@/components/ImgFileInput.vue';
import PhotoAddItem from './PhotoComponent/PhotoAddItem.vue';

export default {
  data() {
    return {
      upLoadingImg: false,
    };
  },
  components: {
    LightBox,
    ImgFileInput,
    TabButton,
    PhotoAddItem,
  },
  computed: {
    fileData() {
      return this.$store.state.photoModule.uploadPhotoList;
    },
    progress() {
      return this.$store.state.photoModule.uploadPhotoProgress;
    },
  },
  methods: {
    openLightBox(index) {
      const vm = this;
      vm.$refs.lightbox.showImage(index);
    },
    uploadFinish(img) {
      this.$store.commit('addNewUploadPhoto', img);
      // 載入圖片完成開始裁切
      this.$router.push({ path: 'add/crop/0' });
    },
    deleteUploadPhoto(index) {
      this.fileData.splice(index, 1);
    },
    uploadPhoto() {
      const vm = this;
      if (vm.fileData.length < 1) {
        alert('尚未選擇圖片');
        return;
      }
      this.$store.commit('setLoading', true);
      faceDetect(vm.fileData[0].src, 0.6)
        .then(() => {
          alert('偵測到臉部，可以上傳');
          vm.upLoadingImg = true;
          this.$store
            .dispatch('UPLOAD_NO_MAKEUP_PHOTO')
            .then(() => {
              alert('上傳完成');
            })
            .catch(() => {
              alert('上傳失敗');
            })
            .finally(() => {
              vm.upLoadingImg = false;
              this.$router.push('/photo');
            });
        })
        .finally(() => {
          this.$store.commit('setLoading', false);
        });
    },
    cancelUpload() {
      const vm = this;
      vm.upLoadingImg = false;
      vm.progress = 0;
    },
  },
};
</script>
