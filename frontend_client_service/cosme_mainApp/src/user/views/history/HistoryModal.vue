<template>
  <div>
    <Modal :ModalImg="photoData.imgURL_s">
      <div class="history-detail" v-if="photoData">
        <main>
          <div class="title">
            <h3>試妝細節</h3>
            <button class="btn-none">
              <font-awesome-icon :icon="['fas', 'download']" />
              下載圖像
            </button>
          </div>
          <div class="detail mb-2">
            <ul class="text-list">
              <li>
                <p>日期:</p>
                <h4>{{ photoData.createdDate }}</h4>
              </li>
              <!-- <li>
                <p>品牌: </p>
                <a :href="photoData.comURL">{{ photoData.com }}</a>
              </li>
              <li>
                <p>產品: </p>
                <a :href="photoData.productURL">{{ photoData.productName }}</a>
              </li>
              <li v-if="photoData.colorID">
                <p>色號: </p>
                <h4>{{ photoData.colorID }}</h4>
              </li> -->
            </ul>
          </div>
          <div class="detail mb-2 reference" v-if="makeupDataOfthisTry.imgURL_s">
            <img width="80" :src="makeupDataOfthisTry.imgURL_s" class="rounded">
            <div class="article">
              <p class="name">{{ makeupDataOfthisTry.name }}</p>
              <p class="content">{{ makeupDataOfthisTry.content }}</p>
            </div>
          </div>
          <!-- <Share /> -->
        </main>
        <footer class="stars mb-3 mt-3 pt-3 border-top">
          <div class="title mb-0 mb-md-2">
            <a href="#" v-if="photoData.score !== '0'" @click.prevent="photoData.score = '0'">重新評分</a>
            <p v-else >為這個妝容評分</p>
            <p>{{ photoData.score }}/5.0</p>
          </div>
          <!-- 已被評分的星星 -->
          <star-rating
            v-if="photoData.score !== '0'"
            :rating="Number(photoData.score)"
            :read-only="true"
            :show-rating="false"
          />
          <!-- 未被評分的星星 -->
          <star-rating
            v-else
            :rating="Number(photoData.score)"
            @rating-selected="starsClick($event)"
            :show-rating="false"
          />
        </footer>
      </div>
    </Modal>
  </div>
</template>

<script>
/* eslint-disable import/no-unresolved */
import Modal from '@/components/Modal/Modal.vue';
// import Share from '@/components/Share.vue';
import StarRating from 'vue-star-rating';
import makeupService from '@/user/store/modules/service/makeupService.ts';
import tryService from '@/user/store/modules/service/tryService.ts';

export default {
  components: {
    Modal,
    StarRating,
    // Share,
  },
  data() {
    return {
      makeupDataOfthisTry: {},
    };
  },
  computed: {
    photoData() {
      return this.$store.getters.getHistoryPhotoById(this.$route.params.id);
    },
    readOnly() {
      if (this.photoData.score !== '0') {
        return true;
      }
      return false;
    },
  },
  methods: {
    starsClick(score) {
      this.$store.commit('setLoading', true);
      this.photoData.score = score;
      tryService
        .tryRating({ tryKey: this.photoData.imgKey, score })
        .then(() => {
          this.$swal('成功', '評分成功', 'success');
        })
        .finally(() => {
          this.$store.commit('setLoading', false);
        });
    },
  },
  mounted() {
    makeupService
      .getMakeupPostsWithKey({ key: this.photoData.creationKey })
      .then((res) => {
        console.log(res);
        this.makeupDataOfthisTry = res;
        return makeupService.getMakeupWithKey({
          imgKey: res.photoID,
          size: 's',
        });
      })
      .then((res) => {
        this.makeupDataOfthisTry.imgURL_s = res;
      });
    // makeupService.getMakeupWithKey({
    //   imgKey: this.photoData.ReferenceKey,
    //   size: 's',
    // })
    //   .then((res) => {
    //     console.log(res);
    //     this.makeupDataOfthisTry.imgURL_s = res;
    //   });
  },
};
</script>

<style lang="scss">
.vue-star-rating {
  justify-content: space-around;
  width: 100%;
}
</style>
