<template>
  <Modal>
    <div class="d-flex justify-content-center bg-light">
      <clipper-basic
        :style="basicStyle"
        :src="OriginalPhoto"
        ref="clipper"
        :ratio="1"
        @load="imgLoad" />
    </div>
    <footer class="crop-footer">
      <button @click="closeCropModal()" class="btn btn-outline-secondary">取消</button>
      <button @click="doClip()" class="btn btn-outline-success">裁切</button>
    </footer>
  </Modal>
</template>

<script>
// eslint-disable-next-line import/no-unresolved
import Modal from '@/components/Modal/CropModal.vue';

export default {
  data() {
    return {
    };
  },
  components: { Modal },
  computed: {
    OriginalPhoto() {
      return this.$store.state.photoModule.uploadUserOriginalPhoto;
    },
    basicStyle() {
      return this.$store.getters.CropBasicStyle;
    },
    cropData() {
      return this.$store.state.uiModule.cropData;
    },
  },
  methods: {
    closeCropModal() {
      this.$router.go(-1);
    },
    doClip() {
      const canvas = this.$refs.clipper.clip();// call component's clip method
      const imgData = canvas.toDataURL('image/jpeg');// canvas->image
      this.uploadPhoto(imgData);
      this.$router.go(-1);
    },
    uploadPhoto(imgBase64) {
      const vm = this;
      vm.upLoadingImg = true;
      vm.$store.commit('setLoading', true);
      vm.$store
        .dispatch('MODIFY_USER_INFO', { img: imgBase64 })
        .then(() => {
          alert('上傳完成');
        })
        .catch((err) => {
          console.log(err, { img: imgBase64 });
          alert('上傳失敗');
        })
        .finally(() => {
          vm.upLoadingImg = false;
          vm.$router.go(-1);
        });
    },
    imgLoad() {
      const { imgRatio } = this.$refs.clipper;
      this.$store.commit('CropImgLoad', imgRatio);
    },
  },
};
</script>
