<template>
  <header class="header px-lg-4 px-md-0 mx-3 mx-sm-4">
    <Breadcrumb v-if="breadcrumbList" :list="breadcrumbList" />
    <h3 v-else>歡迎來到妝典</h3>
    <router-link to="/user" tag="div" class="content" v-if="userInfo.email">
      <div class="user-photo">
        <figure :style="{backgroundImage: `url(${userInfo.img})`}"></figure>
      </div>
      <h4>{{ userInfo.name }}</h4>
    </router-link>
  </header>
</template>

<script>
import Breadcrumb from './Breadcrumb.vue';

export default {
  props: {
    userInfo: Object,
  },
  computed: {
    breadcrumbList() {
      return this.$route.meta.breadcrumb;
    },
  },
  components: {
    Breadcrumb,
  },
};
</script>

<style lang="scss" scoped>
.header {
  padding-top: 10px;
  padding-bottom: 10px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin: 0 30px;
  h3{
    color: #999;
  }
  @media (max-width: 991px){
    flex-direction: row-reverse;
  }
  @media (min-width: 991px) {
    background-color: #f3f3f3;
    height: 70px;
    border-radius: 0 0 10px 10px;
  }
}
.content{
  display: flex;
  align-items: center;
  cursor: pointer;
  .user-photo{
    border-radius: 50%;
    position: relative;
    background-color: #fff;
    background-image: url(~@/assets/img/bg/user-img-null.jpg);
    background-size: cover;
    background-position: center;
    box-shadow: 1px 1px 4px rgba(#000000, .3);
    margin: 10px;
    &::after {
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      position: absolute;
      content: '';
      border: 1px solid #74ebd5;
      width: 135%;
      height: 135%;
      border-radius: 50%;
      box-shadow: 1px 1px 4px rgba(darken(#74ebd5, 20), .5);
      transition: all 0.2s ease-in-out;
    }
    figure{
      position: absolute;
      width: 100%;
      height: 100%;
      background-size: cover;
      background-position: center;
      border-radius: 50%;
      z-index: 10;
    }
  }
  h4{
    transition: all 0.2s ease-in-out;
    margin-left: 8px;
    color: #555;
    font-weight: bold;
  }
  &:hover {
    .user-photo{
      &::after{
        width: 100%;
        height: 100%;
      }
    }
    h4{
      color: #f295a8;
    }
  }
  @media (min-width: 768px) {
    .user-photo{
      width: 40px;
      height: 40px;
    }
  }
  @media (max-width: 767px){
    .user-photo{
      width: 30px;
      height: 30px;
    }
  }
}
</style>
