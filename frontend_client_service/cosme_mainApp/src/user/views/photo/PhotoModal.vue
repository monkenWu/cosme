<template>
  <div>
    <Modal :ModalImg="photoData.imgURL_L">
      <div class="detail-content">
        <div class="detail">
          <h3 v-if="photoData.isDefault">
            <font-awesome-icon :icon="['fas', 'check-circle']" />
            <span>
              已設為預覽圖像
              <!-- <small>(點擊取消)</small> -->
            </span>
          </h3>
          <h3 @click="setPhotoDefault" v-else>
            <font-awesome-icon :icon="['far', 'check-circle']" class="text-secondary" />
            <span>
              非預覽圖像
              <small>(點擊設為預覽)</small>
            </span>
          </h3>
          <h4>上傳日期: {{photoData.updateDate}}</h4>
        </div>
        <div class="preview">
          <h3>
            <span>預覽效果</span>
            <button class="btn-none text-secondary">
              <font-awesome-icon :icon="['fas', 'sync-alt']" />
            </button>
          </h3>
          <ul class="animate__animated animate__faster animate__fadeIn" v-if="previewProductList">
            <li v-for="item in previewProductList" :key="item.key" class="item">
              <figure
                class="figure"
                style="background-image:url(https://cf.shopee.tw/file/fca76df5463ff4ccb34954a70ce352ea)"
              ></figure>
                 <!-- <figure
                class="figure"
                :style="{backgroundImage:`url( ${require('@/assets/logo.png')} )`}"
              ></figure> -->
              <div class="text">
                <a href="#">品牌名稱</a>
                <p>{{item.com}}</p>
                <p>{{item.productName}}</p>
              </div>
              <div class="bd">
                <p>預覽</p>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </Modal>
  </div>
</template>

<script>
// eslint-disable-next-line import/no-unresolved
import Modal from '@/components/Modal/Modal.vue';

export default {
  components: {
    Modal,
  },
  data() {
    return {
    };
  },
  computed: {
    previewProductList() {
      return this.$store.state.photoModule.makeupEffectPreviewList;
    },
    photoData() {
      return this.$store.getters.getNoMakeupPhotoById(
        this.$route.params.id,
      );
    },
  },
  methods: {
    setPhotoDefault() {
      const { imgKey/* , isDefault */ } = this.photoData;
      this.$store.dispatch('MODIFY_NO_MAKEUP_PHOTO', {
        imgKey,
        // isDefault: !isDefault,
        isDefault: true,
      });
    },
  },
  mounted() {
    // 取得大圖資訊
    if (this.photoData === undefined) {
      console.log('無此圖片');
      // this.$router.push('/photo');
    } else if (this.photoData.imgURL_L === '') {
      this.$store.dispatch('GET_PHOTO_L_WITH_KEY', this.$route.params.id);
    }
  },
  updated() {
    if (this.photoData) {
      if (this.photoData.imgURL_L === '') {
        this.$store.dispatch('GET_PHOTO_L_WITH_KEY', this.$route.params.id);
      }
    }
  },
};
</script>
