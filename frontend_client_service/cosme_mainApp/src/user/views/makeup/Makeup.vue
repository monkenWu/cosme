<template>
  <section class="makeup mt-4">
    <router-view />
    <div class="d-flex box-content">
      <TabButton
        title="新增妝容照"
        subtitle="Add Photo"
        color="#ffc39f"
        subtitleColor="#fea875"
        :fwiIcon="['far', 'plus-square']"
        @btn-click="$router.push({ name: 'MakeupAdd' })"
      />
      <TabButton
        title="妝容照管理"
        subtitle="Manage Photo"
        color="#a0ace6"
        subtitleColor="#a3a4d4"
        :fwiIcon="['fas', 'user-cog']"
        @btn-click="$router.push({ name: 'MakeupManage' })"
      />
    </div>
    <MakeupSearch v-if="entryStatus==='mainApp'" @search="doSearch" />
    <!-- 載入動畫 -->
    <div class="d-flex justify-content-center" v-if="isLoadingPhotos">
      <img src="@/assets/img/loading.svg" height="70" />
    </div>
    <ul class="makeup-content row mt-4">
      <li
        class="col-xl-3 col-lg-4 col-md-4 col-sm-6 mb-3"
        v-for="(item, index) in list"
        :key="index"
      >
        <div class="makeup-item">
          <!-- try-icon START -->
          <article
            class="try-icon"
            data-toggle="modal"
            data-target="#TryMakeup"
            @click="openTryMakeup(true, item.photoID)"
            v-if="userInfo.name"
          >
            <font-awesome-icon :icon="['fas', 'magic']" />
          </article>
          <article class="try-icon" @click="openTryMakeup(false)" v-else>
            <font-awesome-icon :icon="['fas', 'magic']" />
          </article>
          <!-- try-icon END -->
          <router-link
            tag="figure"
            :to="`/makeup/detail/${item.key}`"
            class="figure"
            :style="{ 'background-image': photoDefined(item.imgURL_s) }"
          >
            <img src="~@/assets/img/transparent.png" class="transparent" />
            <div class="info">
              <article class="d-flex align-items-center">
                <div class="pic">
                  <figure
                    :style="{
                      'background-image': photoDefined(item.author.imgURL),
                    }"
                  >
                    <img
                      src="~@/assets/img/transparent.png"
                      class="transparent"
                    />
                  </figure>
                </div>
                <p>{{ item.author.name }}</p>
              </article>
            </div>
            <div class="hover">
              <font-awesome-icon :icon="['fas', 'search']" />
            </div>
          </router-link>
          <article class="content">
            <router-link tag="h3" :to="`/makeup/detail/${item.key}`">{{
              item.name
            }}</router-link>
            <ul class="text-primary">
              <li
                v-for="(iItem, iIndex) in item.tags"
                :key="iIndex"
                v-show="iIndex <= 2"
              >
                #{{ iItem.text }}
              </li>
            </ul>
          </article>
        </div>
      </li>
    </ul>
    <section v-if="list.length === 0 && !isLoadingPhotos" class="ml-5">
      無相關搜尋結果
    </section>
    <TryMakeup />
  </section>
</template>

<script>
/* eslint-disable no-restricted-globals */
/* eslint-disable import/no-unresolved */
import TabButton from '@/components/TabButton.vue';
import { getParameterByName } from '@/helpers/helper';
import TryMakeup from './MakeupComponent/TryMakeup.vue';
import MakeupSearch from './MakeupComponent/MakeupSearch.vue';

// 判斷是從 sdk 還是 mainApp 來的
const getEntryStatus = () => {
  const entryData = getParameterByName('entry', location.href);
  if (entryData === 'sdk') {
    return 'sdk';
  }
  return 'mainApp';
};

const getSdkShopName = () => getParameterByName('shop', location.href);

export default {
  name: 'not-keep-alive',
  components: { TabButton, TryMakeup, MakeupSearch },
  data() {
    return {
      entryStatus: getEntryStatus(),
      tryPostKey: '',
      isLoadingPhotos: false,
    };
  },
  computed: {
    list() {
      return this.$store.state.makeupModule.makeupPostList;
    },
    userInfo() {
      return this.$store.state.user.userInfo;
    },
  },
  methods: {
    openTryMakeup(isLogin, tryPostKey) {
      this.$bus.$emit('open:TryMakeup', isLogin);
      this.$store.state.makeupModule.tryPostKey = tryPostKey;
    },
    doSearch(searchTxt) {
      const vm = this;
      vm.isLoadingPhotos = true;
      vm.$store
        .dispatch('GET_POST_LIST', { searchTxt })
        .catch(() => {
          vm.$swal('失敗', '完妝照相簿獲取失敗', 'error');
        })
        .finally(() => {
          vm.isLoadingPhotos = false;
        });
    },
    photoDefined(img) {
      if (img) {
        return `url(${img})`;
      }
      return '';
    },
  },
  beforeRouteEnter(to, from, next) {
    next((vm) => {
      // 從電商sdk看的話，只能看到他們家的貼文
      const searchTxt = vm.entryStatus === 'mainApp' ? '' : getSdkShopName();
      // if (vm.$store.state.makeupModule.makeupPostList.length === 0) {
      vm.isLoadingPhotos = true;
      vm.$store
        .dispatch('GET_POST_LIST', { searchTxt })
        .catch(() => {
          vm.$swal('失敗', '完妝照相簿獲取失敗', 'error');
        })
        .finally(() => {
          vm.isLoadingPhotos = false;
        });
      // }
    });
  },
};
</script>
