<template>
  <div>
    <div class="d-flex justify-content-center bg-light">
      <clipper-basic
        :style="basicStyle"
        :src="cropingPhotoURL"
        ref="clipper"
        :ratio="1"
        @load="imgLoad()" />
    </div>
    <footer class="crop-footer">
      <slot name="other-btn"></slot>
      <button @click="$emit('cancel')" class="btn btn-outline-secondary">取消</button>
      <button @click="doClip()" class="btn btn-success">裁切</button>
    </footer>
  </div>
</template>

<script>

/**
 * clipFinishHook : 傳遞裁切完成的圖片至外部
 */
export default {
  props: {
    cropingPhotoURL: String,
  },
  data() {
    return {
      cropData: {
        maxWidth: 700,
        maxHeight: 500,
        based: 850,
      },
    };
  },
  computed: {
    basicStyle() {
      return { maxWidth: `${this.cropData.based}px` };
    },
    // cropData() {
    //   return this.$store.state.uiModule.cropData;
    // },
  },
  methods: {
    doClip() {
      const canvas = this.$refs.clipper.clip();// call component's clip method
      const img = canvas.toDataURL('image/jpeg');// canvas->image
      this.$emit('clip-finish', img);
    },
    imgLoad() {
      const { imgRatio } = this.$refs.clipper;
      // this.$store.commit('CropImgLoad', imgRatio);
      this.CropImgLoad(imgRatio);
    },
    CropImgLoad(imgRatio) {
    /* eslint no-param-reassign: ["error", { "props": false }] */
      if (imgRatio < 1) this.cropData.based = this.cropData.maxHeight * imgRatio;
      else this.cropData.based = this.cropData.maxWidth;
    },
  },
};
</script>

<style lang="scss" scoped>
.crop-content{
  width: 100%;
  height: 100%;
  position: fixed;
  top: 0;
  left: 0;
  background-color: rgba($color: #000000, $alpha: .5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 100;
  .content{
    min-height: 400px;
    background: #fff;
    width: 500px;
    max-width: 100%;
  }
}
</style>
