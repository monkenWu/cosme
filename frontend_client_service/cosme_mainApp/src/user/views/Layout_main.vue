<template>
  <main class="layout">
    <section class="layout-content menu-content">
      <div class="con">
        <Navbar
          :list="navList"
          :activeVal="dom.scrollActive"
          @user-logout="logout"
        />
      </div>
    </section>
    <section class="layout-content main-content">
      <div class="con">
        <vue-scroll :ops="ops" ref="vs" @handle-scroll="handleScroll">
          <Header id="header" :userInfo="userInfo" />
          <!-- 可使用include/exclude方法決定哪些組件要保留 -->
          <router-view />
          <ScrollBall :activeVal="dom.scrollActive" />
        </vue-scroll>
      </div>
    </section>
  </main>
</template>

<script>
/* eslint-disable import/no-unresolved */
import Header from '@/components/Header.vue';
import Navbar from '@/components/Navbar.vue';
import ScrollBall from '@/components/ScrollBall.vue';
// import 'vue-loading-overlay/dist/vue-loading.css';

export default {
  name: 'CosMe',
  components: {
    Navbar,
    Header,
    ScrollBall,
  },
  watch: {
    'dom.windowWidth': {
      immediate: true,
      handler: function windowWidthlVal(val) {
        const vm = this;
        if (val > 992) {
          // 如果不是手機板
          vm.ops.scrollPanel.scrollingY = true;
        } else {
          vm.ops.scrollPanel.scrollingY = false;
        }
      },
    },
  },
  data() {
    return {
      ops: {
        scrollPanel: {
          scrollingY: true,
          scrollingX: false,
          speed: 100,
        },
        bar: {
          background: '#e6dee9',
        },
      },
    };
  },
  computed: {
    navList() {
      console.log(this.$store.state.user.userInfo);
      const analysisNavObj = (() => {
        if (this.$store.state.user.userInfo.business) {
          return [{
            routeName: 'analysis',
            routeText: '商業分析',
          }];
        }
        return [];
      })();

      return [
        {
          routeName: 'makeup',
          routeText: '妝容分享',
        },
        {
          routeName: 'photo',
          routeText: '照片管理',
        },
        {
          routeName: 'history',
          routeText: '歷程記錄',
        },
        ...analysisNavObj,
        {
          routeName: 'user',
          routeText: '個人資料',
        },
        {
          routeName: 'authorize',
          routeText: '授權管理',
        },
      ];
    },
    isLogin() {
      return this.$store.state.user.isLogin;
    },
    userInfo() {
      return this.$store.state.user.userInfo;
    },
    dom() {
      return this.$store.state.uiModule.dom;
    },
  },
  methods: {
    logout() {
      this.$swal({
        title: '提醒',
        text: '確定要登出?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '登出',
      }).then((res) => {
        if (res.isConfirmed) {
          this.$store.dispatch('LOGOUT').then(() => {
            this.$router.push('/auth').catch(() => {});
          });
        }
      });
    },
    scrollTo(val) {
      const vm = this;
      let scrollY = vm.dom.windowtop;
      let oldTimestamp = null;

      function step(newTimestamp) {
        if (oldTimestamp !== null) {
          const vals = vm.dom.windowtop * (newTimestamp - oldTimestamp);
          scrollY -= vals / 300;
          if (scrollY <= 0) return;
          document.scrollingElement.scrollTop = scrollY;
        }
        oldTimestamp = newTimestamp;
        window.requestAnimationFrame(step);
      }

      if (vm.dom.windowWidth >= 992) {
        vm.$refs.vs.scrollIntoView(`#${val}`, 500);
      } else {
        if (vm.dom.windowtop === 0) return;
        window.requestAnimationFrame(step);
      }
    },
    handleScroll(vertical) {
      const vm = this;
      vm.$store.commit('setWindowtop', vertical.scrollTop);
      if (vm.dom.windowtop >= 60) {
        vm.$store.commit('setScrollActive', true);
      } else {
        vm.$store.commit('setScrollActive', false);
      }
    },
  },
  mounted() {
    const vm = this;
    const app = document.getElementById('app');
    // 取得瀏覽器寬度
    vm.$store.commit('setWindowWidth', window.innerWidth);
    // 觸發調整瀏覽器寬度事件
    window.onresize = () => {
      vm.$store.commit('setWindowWidth', window.innerWidth);
    };
    // 觸發滾動事件
    app.onscroll = () => {
      vm.handleScroll(app);
    };
  },
  created() {
    const vm = this;
    vm.$bus.$on('scroll:top', (val) => vm.scrollTo(val));
  },
};
</script>
