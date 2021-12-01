<template>
  <main>
    <LightBox ref="lightbox" :media="lightBox" :show-light-box="false" />
    <div class="animate__animated animate__faster animate__fadeIn mt-4">
      <div class="route-title">
        <router-link to="/photo">
          <font-awesome-icon :icon="['fas', 'arrow-left']" style="color: #ffc39f" /> 回管理頁面
        </router-link>
        <h3>完成上傳</h3>
      </div>
      <div class="done">
        <p>驗證完成。</p>
        <p>共有 <span class="text-info">{{ result.done.length }}</span> 張通過、
          <span class="text-danger">{{ result.error.length }}</span> 張不通過</p>
      </div>
      <div class="photo">
        <ul class="photo-content row mt-3">
          <li class="col-xl-3 col-lg-4 col-md-3 col-sm-6 mb-4"
          v-for="(item, index) in result.done" :key="index" >
            <figure class="picture"
            :style="{backgroundImage: `url(${lightBox[index].thumb})`}" >
              <div class="hover-content">
                <article>
                  <button class="btn-none h3 text-white p-3" @click="openLightBox(index)">
                    <font-awesome-icon :icon="['fas', 'search']" />
                  </button>
                </article>
              </div>
              <img src="@/assets/img/transparent.png" width="100%" />
            </figure>
            <article class="text">
              <div class="d-flex justify-content-between mt-2 align-items-center">
                <font-awesome-icon :icon="['fas', 'check-circle']" class="text-info h5 mb-0" />
                <button  class="btn-none text-primary" @click="openLightBox(index)">
                  <font-awesome-icon :icon="['fas', 'search']" /> 查看相片
                </button>
              </div>
            </article>
          </li>
          <li class="col-xl-3 col-lg-4 col-md-3 col-sm-6 mb-4"
          v-for="item in result.error" :key="item.key" >
            <figure class="picture"
            :style="{backgroundImage: `url(${item.key})`}" >
              <img src="@/assets/img/transparent.png" width="100%" />
            </figure>
            <article class="text">
              <div class="d-flex justify-content-between mt-2 align-items-center">
                <font-awesome-icon :icon="['far', 'times-circle']" class="text-danger h5 mb-0" />
                <button class="btn-none text-muted" @click="reportError(item)">
                  <font-awesome-icon :icon="['fas', 'exclamation-triangle']" /> 回報錯誤
                </button>
              </div>
            </article>
          </li>
        </ul>
      </div>
    </div>
  </main>
</template>

<script>
import LightBox from 'vue-image-lightbox';

export default {
  data() {
    return {
      result: null,
    };
  },
  components: { LightBox },
  computed: {
    lightBox() {
      const vm = this;
      const list = [];
      if (vm.result) {
        vm.result.done.forEach((item) => {
          list.push({
            thumb: item.imgURL_s,
            src: item.imgURL_L,
          });
        });
        return list;
      }
      return false;
    },
  },
  methods: {
    openLightBox(index) {
      const vm = this;
      vm.$refs.lightbox.showImage(index);
    },
    reportError(val) {
      console.log(val);
    },
  },
  created() {
    this.result = {
      done: [
        {
          key: 'aaa111',
          isDefault: false,
          updateDate: '2020/08/07 09:30:00',
          img: 'bbb111',
          imgURL_s: 'https://i.imgur.com/Wp5IAZW.jpg',
          imgURL_L: 'https://i.imgur.com/hLG3hXR.jpg',
        },
        {
          key: 'aaa112',
          isDefault: false,
          updateDate: '2020/08/07 09:30:00',
          img: 'bbb112',
          imgURL_s: 'https://i.imgur.com/qGm4U26.jpg',
          imgURL_L: 'https://i.imgur.com/Ij3uNMp.jpg',
        },
      ],
      error: [
        {
          key: 'https://i.imgur.com/7SplQ7d.jpg', // base64
          mag: '驗證失敗',
        },
      ],
    };
  },
};
</script>

<style lang="scss" scoped>
.done {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  color: #999;
  font-size: 18px;
  margin-top: 30px;
}
</style>
