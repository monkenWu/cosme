<template>
  <Modal>
    <Crop :cropingPhotoURL="cropingPhotoURL" @clip-finish="clipFinish" @cancel="$router.go(-1)" />
  </Modal>
</template>

<script>
/* eslint-disable import/no-unresolved */
import Modal from '@/components/Modal/CropModal.vue';
import Crop from '@/components/Crop.vue';

export default {
  components: { Modal, Crop },
  computed: {
    photoList() {
      return this.$store.state.photoModule.uploadPhotoList;
    },
    photoObjIndex() {
      return this.$route.params.id;
    },
    cropingPhotoURL() {
      return this.photoList[this.photoObjIndex].originPhoto;
    },
  },
  methods: {
    clipFinish(img) {
      this.photoList[this.photoObjIndex].thumb = img;
      this.photoList[this.photoObjIndex].src = img;
      this.$router.push({ path: '/photo/add' });
    },
  },
};
</script>

<style lang="scss" scoped>
.crop-content {
  width: 100%;
  height: 100%;
  position: fixed;
  top: 0;
  left: 0;
  background-color: rgba($color: #000000, $alpha: 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 100;
  .content {
    min-height: 400px;
    background: #fff;
    width: 500px;
    max-width: 100%;
  }
}
</style>
