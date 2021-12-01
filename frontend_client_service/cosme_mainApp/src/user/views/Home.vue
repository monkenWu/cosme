<template>
  <main class="home-content" v-if="userInfo">
     <div class="animate__animated animate__faster animate__fadeIn content">
       <div class="picture">
         <figure v-if="userInfo.img"
          :style="{'background-image': `url(${userInfo.img})`}" />
       </div>
       <h3>{{ userInfo.name }}</h3>
       <ul class="links">
          <router-link tag="li" to="/photo">
            <img src="@/assets/img/home_album.jpg" alt="照片管理" >
            照片管理
          </router-link>
          <router-link tag="li" to="/history">
            <img src="@/assets/img/home_history.jpg" alt="歷史紀錄" >
            歷史紀錄
          </router-link>
          <router-link tag="li" to="/authorize">
            <img src="@/assets/img/home_auth.jpg" alt="授權管理" >
            授權管理
          </router-link>
       </ul>
     </div>
  </main>
</template>

<script>
export default {
  name: 'Home',
  computed: {
    userInfo() {
      return this.$store.state.user.userInfo;
    },
  },
};
</script>
