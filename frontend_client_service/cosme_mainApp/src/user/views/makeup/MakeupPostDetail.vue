<template>
  <div v-if="photoDetail">
    <MakeupModal :photoData="photoDetail">
      <section slot="author" class="author">
        <figure class="pic" :style="{'background-image': `url(${photoDetail.author.imgURL})`}">
          <img src="~@/assets/img/transparent.png" width="100%" />
        </figure>
        <p>{{ photoDetail.author.name }}</p>
      </section>
    </MakeupModal>
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
      const list = this.$store.state.makeupModule.makeupPostList;
      return list.find(
        (item) => item.key === this.key,
      );
    },
  },
  methods: {
    test() {
      const imgKey = this.$store.getters.getMakeupPostImgKey(this.key);
      this.$store.dispatch('GET_POST_L_WITH_KEY', { key: this.key, imgKey }).then((res) => {
        this.imgL = res;
      });
    },
  },
  mounted() {
    if (this.photoDetail) {
      if (!this.photoDetail.imgURL_L) {
        this.test();
      }
    }
  },
  updated() {
    if (this.photoDetail) {
      if (!this.photoDetail.imgURL_L) {
        this.test();
      }
    }
  },
};
</script>
