<template>
  <div>
    <slot></slot>
    <input
      style="visibility:hidden"
      type="file"
      id="files"
      name="files"
      accept="image/x-png, image/jpeg"
      @change="onFileChange"
    />
  </div>
</template>

<script>
export default {
  methods: {
    onFileChange(e) {
      const { files } = e.target;
      if (files) {
        files.forEach((el) => {
          const reader = new FileReader();
          reader.onload = (event) => {
            // vm.selectedFile = event.target.result;
            const img = event.target.result;
            this.$emit('uploadFinishHook', img);
            // vm.$store.commit('addNewUploadPhoto', event.target.result);
            // // 載入圖片完成開始裁切
            // vm.$router.push({ path: 'add/crop/0' });
          };
          reader.readAsDataURL(el);
        });
      }
    },
  },
};
</script>
