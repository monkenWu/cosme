<template>
  <main class="history">
    <router-view />
    <div class="d-flex box-content mb-4">
      <TabButton
        title="分類清單"
        subtitle="Classification"
        color="#ffc39f"
        subtitleColor="#fea875"
        :fwiIcon="['fas', 'clipboard-list']"
        @btn-click="$router.push({ path: '/history/class' })"
      />
    </div>
    <div class="route-title">
      <h3>
        上妝照片管理
        <button type="button" class="btn-none ml-1">
          <font-awesome-icon :icon="['far', 'question-circle']" />
        </button>
      </h3>
      <SortButton />
    </div>
    <div class="d-flex justify-content-center" v-if="isLoadingPhotos">
      <img src="@/assets/img/loading.svg" height="70" />
    </div>
    <ul class="row" v-if="list">
      <!-- 載入動畫 -->
      <li
        class="col-xl-3 col-lg-4 col-md-4 col-sm-6 mb-4"
        v-for="(item, index) in list"
        :key="index"
      >
        <div class="item">
          <router-link
            tag="div"
            :to="`/history/detail/${item.imgKey}`"
            class="picture"
          >
            <figure>
              <img :src="item.imgURL_s" alt="上妝照片" />
            </figure>
            <h2 v-if="item.com" class="brand-name">{{ item.com }}</h2>
          </router-link>
          <div class="text-con">
            <router-link tag="h3" :to="`/history/detail/${item.productID}`">
              {{ item.productName }}
            </router-link>
            <div class="btn-con">
              <a
                class="btn-none download"
                download
                :href="item.imgURL_L"
                target="_blank"
              >
                <font-awesome-icon :icon="['fas', 'download']" />
                下載圖像
              </a>
              <button
                class="else-btn-list btn-none"
                @blur="dom.elseActive = null"
                @click="dom.elseActive = index"
              >
                <font-awesome-icon :icon="['fas', 'ellipsis-h']" />
                <ol v-if="dom.elseActive === index">
                  <li @click.stop="addToList(index)">新增至清單</li>
                  <li @click.stop="sharePicture(index)">分享相片</li>
                  <li @click.stop="deletePicture(item.imgKey)">刪除相片</li>
                </ol>
              </button>
            </div>
          </div>
        </div>
      </li>
    </ul>
  </main>
</template>

<script>
/* eslint-disable import/no-unresolved */
import TabButton from '@/components/TabButton.vue';
import SortButton from '@/components/SortButton.vue';
// eslint-disable-next-line no-unused-vars
import tryService from '@/user/store/modules/service/tryService.ts';

export default {
  data() {
    return {
      isLoadingPhotos: false,
      // list: null,
      dom: {
        sortActive: false,
        elseActive: null,
      },
      sort: 0,
    };
  },
  components: {
    SortButton,
    TabButton,
  },
  methods: {
    addToList() {
      alert('新增至清單');
    },
    sharePicture() {
      alert('分享相片');
    },
    deletePicture(imgKey) {
      this.$swal({
        title: '提醒',
        text: '確定要刪除此試妝紀錄?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '刪除',
      })
        .then((res) => {
          if (res.isConfirmed) {
            this.isLoadingPhotos = true;
            return tryService.deleteTry({ imgKey });
          }
          return false;
        })
        .then(() => this.$store.dispatch('GET_TRY_HISTORY'))
        .finally(() => {
          this.isLoadingPhotos = false;
        });
    },
  },
  computed: {
    list() {
      return this.$store.state.historyModule.tryHistoryList;
    },
  },
  beforeRouteEnter(to, from, next) {
    next((vm) => {
      vm.isLoadingPhotos = true;
      vm.$store
        .dispatch('GET_TRY_HISTORY')
        .catch(() => {
          vm.$swal('失敗', '完妝照相簿獲取失敗', 'error');
        })
        .finally(() => {
          vm.isLoadingPhotos = false;
        });
    });
  },
};
</script>

<style lang="scss" scoped>
.else-btn-list ol {
  margin-right: 10px;
}
</style>
