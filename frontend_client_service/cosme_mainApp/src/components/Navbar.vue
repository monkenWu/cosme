<template>
  <nav class="theme_navbar" :class="{'navbarBG': activeVal}">
    <h1>
      <router-link to="/" title="回首頁">
        <img src="@/assets/logo.png" alt="logo" width="90">
      </router-link>
    </h1>
    <ul ref="box-content" class="box-content" :class="{'navbarOpen': dom.navbarOpen}">
      <li v-for="(item, index) in list" :key="index"
        @click.stop="dom.navbarOpen = false">
        <router-link :to="`/${item.routeName}`" :title="item.routeName">
          <span>{{item.routeText}}</span>
          <font-awesome-icon :icon="['fas', 'angle-right']" class="d-block d-lg-none" />
        </router-link>
      </li>
      <li v-if="userInfo.email">
        <a href="#" @click.prevent="$emit('user-logout')">
          <span>登出</span>
          <font-awesome-icon :icon="['fas', 'angle-right']" class="d-block d-lg-none" />
        </a>
      </li>
      <a href="#" class="bars-close" @click.prevent="dom.navbarOpen = false">
        <font-awesome-icon :icon="['fas', 'times']" />
      </a>
    </ul>
    <a href="#" class="bars" @click.prevent="dom.navbarOpen = true">
      <font-awesome-icon :icon="['fas', 'bars']" />
    </a>
  </nav>
</template>

<script>
export default {
  props: ['list', 'activeVal'],
  data() {
    return {
      dom: {
        navbarOpen: false,
        navbarScroll: false,
      },
    };
  },
  computed: {
    userInfo() {
      return this.$store.state.user.userInfo;
    },
  },
  methods: {
    toggleMenu() {
      this.dom.navbarOpen = false;
    },
  },
};
</script>

<style lang="scss" scoped>
.theme_navbar{
  display: flex;
  justify-content: space-between;
  z-index: 100;
  .bars{
    svg{
      display: block;
    }
  }
  .bars-close{
    // display: none;
    transition: all 0.2s ease-in-out 0.3s;
    transform: rotateY(90deg);
    opacity: 0;
    position: absolute;
    top: 80px;
    right: 30px;
    font-size: 48px;
  }
  @media (min-width: 992px) {
    width: 200px;
    height: 100%;
    flex-direction: column;
    align-items: center;
    .bars{
      svg{
        display: none;
      }
    }
    .router-link-active {
      color: #a3a4d4;
      &::before{
        opacity: 1 !important;
      }
    }
    ul{
      width: 100%;
      margin-bottom: 100px;
      position: relative;
      a{
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        font-size: 16px;
        font-weight: bold;
        color: #555;
        position: relative;
        span{
          position: relative;
          z-index: 10;
          display: inline-block;
          width: 70px;
          text-align: left;
        }
        &::before{
          content: url(~@/assets/img/hover.svg);
          position: absolute;
          width: calc(100% - 20px);
          right: -1px;
          opacity: 0;
          transition: all 0.2s ease-in-out;
        }
        &:hover::before{
          opacity: .4;
        }
      }
    }
  }
  @media (max-width: 991px) {
    position: fixed;
    top: 0;
    width: 100%;
    height: 60px;
    align-items: center;
    padding: 0px 15px 0 30px;
    transition: all 0.2s ease-in-out;
    &::after{
      transition: all 0.2s ease-in-out;
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      content: '';
      opacity: 0;
      background-image: linear-gradient(90deg, #ffc39f, #ffafbe);
      height: 5px;
    }
    &.navbarBG{
      background-color: #fff;
      box-shadow: 0 0 3px rgba($color: #000, $alpha: .3);
      &::after{
        opacity: 1;
      }
    }
    .bars{
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      height: 50px;
      width: 50px;
    }
    .box-content{
      position: fixed;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      z-index: -1;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      background-color: rgba($color: #fff, $alpha: .9);
      // box-shadow: 1px 5px 8px rgba($color: #000000, $alpha: .1);
      transition: all 0.3s ease-in-out;
      opacity: 0;
      overflow: hidden;
      &.navbarOpen{
        left: 0%;
        opacity: 1;
        z-index: 10000;
        .bars-close{
          display: block;
          transform: rotateY(0deg);
          opacity: 1;
        }
      }
    }
    li{
      border-top: 1px solid #ccc;
      width: 80%;
      // display: none;
      &:nth-child(1) {
        border: none;
      }
      a{
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 15px;
        &::after{
          // content: '🢒';
          font-size: 30px;
        }
      }
    }
  }
}
</style>
