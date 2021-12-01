<template>
  <div class="share-content">
    <p><font-awesome-icon :icon="['fas', 'share-alt']" /> 分享至</p>
    <ul>
      <li class="facebook">
        <font-awesome-icon :icon="['fab', 'facebook']" />
      </li>
      <li class="line">
        <font-awesome-icon :icon="['fab', 'line']" />
      </li>
      <li class="twitter">
        <font-awesome-icon :icon="['fab', 'twitter']" />
      </li>
      <li>
        <font-awesome-icon @click="openShareLink" :icon="['fas', 'link']" />
      </li>
    </ul>
    <PopModal v-if="showShareModal" @close-modal="showShareModal = false">
      <div class="share-link-window row justify-content-center my-1">
        <input
          ref="linkInput"
          class="mx-2 my-1 col-10 col-md-8"
          type="text"
          v-model="linkUrl"
          readonly="readonly"
        />
        <button
          @click="copyLink"
          class="btn mx-2 my-1 btn-outline-success col-md-3 col-10"
        >
          複製連結
        </button>
        <VueQrcode
          class="qrcode my-2"
          :options="{ width: 200 }"
          :value="linkUrl"
        />
      </div>
    </PopModal>
  </div>
</template>

<script>
/* eslint-disable import/no-unresolved */
import PopModal from '@/components/Modal/PopModal.vue';
import VueQrcode from '@chenfengyuan/vue-qrcode';

const {
  NODE_ENV,
  VUE_APP_FRONTEND_SERVICE_URL,
  VUE_APP_DEVELOPMENT_FRONTEND_SERVICE_URL,
} = process.env;

const serviceUrl = NODE_ENV === 'production'
  ? VUE_APP_FRONTEND_SERVICE_URL
  : VUE_APP_DEVELOPMENT_FRONTEND_SERVICE_URL;

const shareTypeUrlList = {
  makeupPost: `${serviceUrl}/share/?key=`,
};

export default {
  props: {
    makeupPostId: String,
    shareType: String,
  },
  components: { PopModal, VueQrcode },
  data() {
    return {
      linkUrl: `${shareTypeUrlList[this.shareType]}${this.makeupPostId}`,
      showShareModal: false,
    };
  },
  methods: {
    openShareLink() {
      this.showShareModal = true;
    },
    copyLink() {
      // chrome 實現複製到剪貼簿
      const copyText = this.$refs.linkInput;
      copyText.select();
      copyText.setSelectionRange(0, 99999); /* For mobile devices */
      document.execCommand('copy');
      this.$swal({
        title: '成功',
        text: '已複製連結',
        icon: 'success',
      });
    },
  },
};
</script>
