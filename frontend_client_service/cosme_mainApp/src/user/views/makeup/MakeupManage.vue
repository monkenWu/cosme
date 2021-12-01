<template>
  <main class="makeup mt-4">
    <router-view />
    <div class="route-title animate__animated animate__faster animate__fadeIn">
      <a href="#" @click.prevent="$router.push('/makeup')">
        <font-awesome-icon :icon="['fas', 'arrow-left']" style="color: #ffc39f" />
        回上一頁
      </a>
      <h3>管理妝容</h3>
    </div>
     <!-- 載入動畫 -->
    <div class="d-flex justify-content-center" v-if="isLoadingPhotos">
      <img src="@/assets/img/loading.svg" height="70" />
    </div>
    <ul class="makeup-content row mt-4 animate__animated animate__faster animate__fadeIn">
      <li class="col-xl-3 col-lg-4 col-md-4 col-sm-6 mb-3" v-for="(item, index) in list" :key="index">
        <MakeupManageItem v-bind="item"  :userMakeupKey="item.key" @delete-makeup="deleteMakeup(item.key)"/>
      </li>
    </ul>
    <TryMakeup />
  </main>
</template>

<script>
/* eslint-disable import/no-unresolved */
import MakeupManageItem from './MakeupComponent/MakeupManageItem.vue';
import TryMakeup from './MakeupComponent/TryMakeup.vue';

export default {
  components: { MakeupManageItem, TryMakeup },
  data() {
    return {
      isLoadingPhotos: false,
    };
  },
  computed: {
    list() {
      return this.$store.state.makeupModule.userMakeupList;
    },
  },
  methods: {
    deleteMakeup(imgKey) {
      /* eslint-disable-next-line */
      const yes = confirm("你確定要刪除嗎？");
      if (yes) {
        console.log(`刪除${imgKey}`);
        this.$store
          .dispatch('DELETE_MAKEUP', imgKey)
          .then(() => {
            this.isLoadingPhotos = false;
          });
      }
    },
  },
  // 切路由時皆會刷新
  beforeRouteEnter(to, from, next) {
    next((vm) => {
      // if (vm.$store.state.makeupModule.userMakeupList.length === 0) {
      vm.isLoadingPhotos = true;
      vm.$store
        .dispatch('GET_MAKEUP_LIST')
        .then(() => {
          vm.isLoadingPhotos = false;
        })
        .catch(() => {
          vm.isLoadingPhotos = false;
          this.$swal('登入失敗', '完妝照相簿獲取失敗', 'error');
        });
      // }
    });
  },
};
</script>
