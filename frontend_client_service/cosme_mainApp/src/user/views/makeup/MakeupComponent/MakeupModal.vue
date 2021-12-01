<template>
  <Modal :ModalImg="photoData.imgURL_L">
    <div class="makeup-detail">
      <vue-scroll :ops="ops" ref="sc">
        <header class="d-flex justify-content-between align-items-center mb-2">
          <h2 class="text-danger h4 m-0">{{ photoData.name }}</h2>
          <!-- try-icon START -->
          <p
            class="try-icon"
            data-toggle="modal"
            data-target="#TryMakeup"
            @click="openTryMakeup(true, photoData.photoID)"
            v-if="userInfo.name"
          >
            <font-awesome-icon :icon="['fas', 'magic']" />
          </p>
          <p class="try-icon" @click="openTryMakeup(false)" v-else>
            <font-awesome-icon :icon="['fas', 'magic']" />
          </p>
          <!-- try-icon END -->
        </header>
        <section class="tags mb-3">
          <font-awesome-icon :icon="['fas', 'tags']" />
          <ul>
            <li v-for="(item, index) in photoData.tags" :key="index">
              #{{ item.text }}
            </li>
          </ul>
        </section>
        <main>
          <div class="detail mb-2">
            <p
              class="bio"
              style="word-break: break-all"
              v-html="contentFormat"
            />
          </div>
        </main>
        <section v-if="photoData.products.length > 0" class="preview mb-2">
          <h3>
            <span>妝容化妝品</span>
          </h3>
          <ul class="animate__animated animate__faster animate__fadeIn">
            <li v-for="item in photoData.products" :key="item.key" class="item">
              <figure
                class="figure"
                :style="{ 'background-image': `url('${item.imgpath}')` }"
              ></figure>
              <div class="text">
                <a :href="item.url" target="_blank">{{ item.name }}</a>
                <p>{{ item.intro }}</p>
              </div>
              <a :href="item.url" target="_blank" class="bd">
                <p>Learn<br />More</p>
              </a>
            </li>
          </ul>
        </section>
        <Share shareType="makeupPost" :makeupPostId="photoData.key" />
        <footer class="mt-4">
          <slot name="author"></slot>
          <p class="text-muted text-right">
            <small class="d-block small">發布日期:</small>
            {{ photoData.time }}
          </p>
        </footer>
      </vue-scroll>
    </div>
  </Modal>
</template>

<script>
/* eslint-disable import/no-unresolved */
import Modal from '@/components/Modal/Modal.vue';
import Share from '@/components/Share.vue';

export default {
  components: {
    Modal,
    Share,
  },
  props: {
    photoData: Object,
  },
  data() {
    return {
      ops: {
        scrollPanel: {
          scrollingY: true,
          scrollingX: false,
          speed: 50,
        },
        bar: {
          background: '#e6dee9',
        },
      },
    };
  },
  computed: {
    contentFormat() {
      let formatedData = this.photoData.content.replace(/\r\n/g, '<br/>');
      formatedData = this.photoData.content.replace(/\n/g, '<br/>');
      return formatedData;
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
  },
};
</script>
