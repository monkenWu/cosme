<template>
  <div id="app">
    <loading :active.sync="isLoading">
      <img src="@/assets/img/loading.svg" width="150"/>
    </loading>
    <router-view />
  </div>
</template>

<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
  name: 'CosMe',
  components: {
    Loading,
  },
  computed: {
    isLogin() {
      return this.$store.state.user.isLogin;
    },
    isLoading() {
      return this.$store.state.uiModule.isLoading;
    },
  },
};
</script>

<style lang="scss">
@import "~@/assets/scss/all";

.__view{
  display: flex;
  flex-direction: column;
}
#app{
  height: 100vh;
  overflow-y: auto;
}
</style>
