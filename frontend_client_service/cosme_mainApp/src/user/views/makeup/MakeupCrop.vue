<template>
  <Modal>
   <Crop :cropingPhotoURL="fileData.originPhoto" @clip-finish="clipFinish" @cancel="$router.go(-1)"/>
  </Modal>
</template>

<script>
/* eslint-disable import/no-unresolved */
import Modal from '@/components/Modal/CropModal.vue';
import Crop from '@/components/Crop.vue';
import faceDetect from '@/helpers/faceDetect';

export default {
  components: { Modal, Crop },
  computed: {
    fileData() {
      return this.$store.state.makeupModule.uploadMakeupPhoto;
    },
  },
  methods: {
    clipFinish(cropImg) {
      this.$emit('crop-finish', cropImg);
      this.$store.commit('cropUpdataMakeupPhoto', cropImg);
      this.$router.go(-1);
      this.doFaceDetect(cropImg);
    },
    doFaceDetect(cropImg) {
      const vm = this;
      // 開始人臉辨識
      this.$store.state.uiModule.isLoading = true;
      faceDetect(cropImg, 0.6)
        .then(() => {
          vm.$store.commit('changeCanNewMakeupUploadState', true);
          this.canUpload = true;
          this.$swal('成功', '偵測到臉部，可以上傳', 'success');
        })
        .catch(() => {
          vm.$store.commit('changeCanNewMakeupUploadState', false);
        })
        .finally(() => {
          this.$store.state.uiModule.isLoading = false;
        });
    },
  },
};
</script>
