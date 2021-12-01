<template>
  <Modal ref="modal" v-show="isCameraReady">
    <Camera ref="camera"
     @capture-finish="captureFinish" @camera-ready="cameraReadyDo" @cancel="$router.go(-1)">
    </Camera>
  </Modal>
</template>

<script>
/* eslint-disable import/no-unresolved */
import Modal from '@/components/Modal/CropModal.vue';
import Camera from '@/components/Camera.vue';

export default {
  components: {
    Modal,
    Camera,
  },
  data() {
    return {
      isCameraReady: false,
    };
  },
  methods: {
    captureFinish(img) {
      this.$store.commit('addNewUploadPhoto', img);
      // 載入圖片完成開始裁切
      this.$router.push({ path: '/photo/add/crop/0' });
    },
    cameraReadyDo() {
      this.$store.commit('setLoading', false);
      this.isCameraReady = true;
    },
  },
  mounted() {
    this.$store.commit('setLoading', true);
  },
};
</script>
