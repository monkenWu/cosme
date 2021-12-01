<template>
  <!-- Modal -->
  <div
    class="modal fade try-makeup"
    id="TryMakeup"
    tabindex="-1"
    role="dialog"
    aria-labelledby="tryModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tryModalLabel">試妝照選擇</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <section
          class="bg-light p-3"
          v-if="uiRender.activeItem && !uiRender.loading"
        >
          <figure
            class="selected-img rounded"
            :style="{
              'background-image': `url(${uiRender.activeItem.imgURL_s})`,
            }"
          >
            <img
              src="~@/assets/img/transparent.png"
              width="100%"
              class="transparent"
            />
          </figure>
        </section>
        <div class="modal-body" v-if="!uiRender.loading">
          <ul class="row mx-0" v-if="listData.data.length > 0">
            <li
              class="col-4 col-sm-3 px-1"
              @click="changeActive(item, index)"
              v-for="(item, index) in listData.data"
              :key="index"
            >
              <figure
                class="select-item rounded"
                :class="{ active: uiRender.activeIndex === index }"
                :style="{ 'background-image': `url(${item.imgURL_s})` }"
              >
                <img
                  src="~@/assets/img/transparent.png"
                  width="100%"
                  class="transparent"
                />
              </figure>
            </li>
          </ul>
          <section v-else class="none-photo-area">
            <font-awesome-icon :icon="['fas', 'images']" />
            <h3>目前尚無素顏照可使用</h3>
            <router-link
              to="/photo/add"
              tag="button"
              class="mt-4"
              data-dismiss="modal"
              >前往新增未上妝照</router-link
            >
          </section>
        </div>
        <div class="modal-body text-center py-3 text-primary" v-else>
          <font-awesome-icon :icon="['fas', 'spinner']" spin class="h1" />
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-sm btn-outline-secondary"
            data-dismiss="modal"
          >
            關閉
          </button>
          <button
            type="button"
            class="btn btn-sm btn-warning"
            v-if="listData.data.length"
            @click="tryMakeupClick"
          >
            立即試妝
          </button>
        </div>
      </div>
    </div>
    <PopModal v-if="showTryResult == true" @close-modal="showTryResult = false">
      <img width="100%" :src="tryResult" alt="" />
    </PopModal>
  </div>
</template>

<script>
/* eslint-disable import/no-unresolved */
import tryService from '@/user/store/modules/service/tryService.ts';
import PopModal from '@/components/Modal/PopModal.vue';

export default {
  components: { PopModal },
  data() {
    return {
      showTryResult: false,
      tryResult: '',
      uiRender: {
        activeItem: null,
        activeIndex: null,
        loading: false,
      },
    };
  },
  computed: {
    listData() {
      return this.$store.state.photoModule.noMakeupPhotoList;
    },
    tryPostKey() {
      return this.$store.state.makeupModule.tryPostKey;
    },
  },
  methods: {
    openModal(isLogin) {
      const vm = this;
      if (isLogin) {
        // loading 不知道為什麼出現問題
        // vm.$store.commit('setLoading', true);
        vm.uiRender.loading = true;
        vm.$store
          .dispatch('GET_PHOTO_LIST')
          .then(() => {
            vm.listData.data.forEach((item, index) => {
              if (item.isDefault) {
                vm.uiRender.activeItem = item;
                vm.uiRender.activeIndex = index;
              }
            });
            // vm.$store.commit('setLoading', false);
            vm.uiRender.loading = false;
          })
          .catch(() => {
            vm.$store.commit('setLoading', false);
            vm.uiRender.loading = false;
            alert('相簿獲取失敗');
          })
          .finally(() => {});
      } else {
        // eslint-disable-next-line no-restricted-globals
        const confirming = confirm('請登入後再繼續');
        if (confirming === true) {
          vm.$router.push('/login');
        }
      }
    },
    changeActive(item, index) {
      this.uiRender.activeItem = item;
      this.uiRender.activeIndex = index;
    },
    tryMakeupClick() {
      // const vm = this;
      // 試妝 api 之類的，item 會傳入要試妝的照片內容
      const withoutItem = this.uiRender.activeItem;
      this.$store.commit('setLoading', true);
      tryService
        .tryMakeup({
          makeupKey: this.tryPostKey,
          withoutKey: withoutItem.imgKey,
        })
        .then((res) => tryService.getTryByImgKey({ imgKey: res }))
        .then((res) => {
          this.tryResult = res;
          this.showTryResult = true;
        })
        .finally(() => {
          this.$store.commit('setLoading', false);
        });
    },
  },
  created() {
    this.$bus.$on('open:TryMakeup', (isLogin) => {
      this.openModal(isLogin);
    });
  },
  beforeDestroy() {
    // 元件銷毀前要註銷監聽事件
    this.$bus.$off('open:TryMakeup');
  },
};
</script>

<style lang="scss" scoped>
.modal-title {
  font-weight: bold;
  color: #999;
}
</style>
