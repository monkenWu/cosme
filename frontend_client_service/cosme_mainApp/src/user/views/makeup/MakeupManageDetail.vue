<template>
  <div>
    <MakeupModal :photoData="photoDetail" />
  </div>
</template>

<script>
/* eslint-disable import/no-unresolved */
import MakeupModal from './MakeupComponent/MakeupModal.vue';

export default {
  components: {
    MakeupModal,
  },
  data() {
    return {
      key: this.$route.params.id.toString(),
    };
  },
  computed: {
    photoDetail() {
      return this.$store.getters.getUserMakeupPhotoById(
        this.key,
      );
    },
  },
  mounted() {
    if (this.photoDetail.imgURL_L === '') {
      const imgKey = this.$store.getters.getUserMakeupImgKey(this.key);
      this.$store.dispatch('GET_MAKEUP_L_WITH_KEY', { key: this.key, imgKey });
    }
  },
};
</script>
