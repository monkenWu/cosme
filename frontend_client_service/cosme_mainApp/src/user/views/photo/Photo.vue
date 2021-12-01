<template>
  <section class="photo">
    <router-view />
    <div class="d-flex box-content">
      <TabButton
        title="新增未上妝照"
        subtitle="Add Photo"
        color="#ffc39f"
        subtitleColor="#fea875"
        :fwiIcon="['far', 'plus-square']"
        @btn-click="$router.push('/photo/add')"
      />
      <TabButton
        title="相片使用紀錄"
        subtitle="History"
        color="#a0ace6"
        subtitleColor="#a3a4d4"
        :fwiIcon="['fas', 'history']"
        @btn-click="$router.push('/history')"
      />
    </div>
    <div class="d-flex mb-3">
      <h3>未上妝照片管理</h3>
      <button type="button" class="btn-none ml-1">
        <font-awesome-icon :icon="['far', 'question-circle']" />
      </button>
    </div>
    <!-- 載入動畫 -->
    <div class="d-flex justify-content-center" v-if="isLoadingPhotos">
      <img src="@/assets/img/loading.svg" height="70" />
    </div>
    <ul class="photo-content row" v-else>
      <li
        class="col-6 col-lg-3 col-md-3 col-sm-6 mb-4"
        v-for="item in listData.data"
        :key="item.imgKey"
      >
        <PhotoItem
          :isDefault="item.isDefault"
          :img="item.imgURL_s"
          @to-detail="$router.push(`/photo/detail/${item.imgKey}`)"
          @delete="deletePhoto(item.imgKey)"
        />
      </li>
      <li class="col-6 col-lg-3 col-md-3 col-sm-6 mb-4">
        <router-link class="add-con" to="/photo/add" tag="div">
          <img src="@/assets/img/transparent.png" class="img-fluid" />
          <font-awesome-icon :icon="['fas', 'plus']" />
        </router-link>
      </li>
    </ul>
  </section>
</template>

<script>
/* eslint-disable import/no-unresolved */
import TabButton from '@/components/TabButton.vue';
import PhotoItem from './PhotoComponent/PhotoItem.vue';

export default {
  name: 'Photo',
  components: { TabButton, PhotoItem },
  data() {
    return {
      isLoadingPhotos: false,
    };
  },
  computed: {
    listData() {
      return this.$store.state.photoModule.noMakeupPhotoList;
    },
  },
  methods: {
    deletePhoto(imgKey) {
      /* eslint-disable-next-line */
      const yes = confirm("你確定要刪除嗎？");
      if (yes) {
        console.log(`刪除${imgKey}`);
        this.$store.dispatch('DELETE_NO_MAKEUP_PHOTO', { imgKey });
      }
    },
  },
  created() {
    this.$store.dispatch('GET_MAKEUP_EFFECT_PREVIEW_LIST', {
      random: 0,
      length: 3,
    });
  },
  // 切路由時皆會刷新
  beforeRouteEnter(to, from, next) {
    next((vm) => {
      // if (vm.$store.state.photoModule.noMakeupPhotoList.data.length === 0) {
      vm.isLoadingPhotos = true;
      vm.$store
        .dispatch('GET_PHOTO_LIST')
        .then(() => {
          vm.isLoadingPhotos = false;
        })
        .catch(() => {
          vm.isLoadingPhotos = false;
          alert('相簿獲取失敗');
        });
      // }
    });
  },
};
</script>

<style lang="scss" scoped>
.box-content {
  margin-bottom: 40px;
  @media (max-width: 767px) {
    overflow-x: auto;
    overflow-y: hidden;
    margin-right: -30px;
    margin-left: -30px;
    padding-right: 30px;
    padding-left: 30px;
  }
}
</style>
