<template>
  <main class="user-content mt-3">
    <router-view />
    <LightBox
      ref="lightbox"
      :media="imgData"
      :show-light-box="false"
      v-if="userInfo"
    />
    <div
      class="animate__animated animate__faster animate__fadeIn"
      v-if="userInfo"
    >
      <div class="photo">
        <div class="figure">
          <figure
            v-if="userInfo.img"
            :style="{ 'background-image': `url(${userInfo.img})` }"
            @click="openLightBox()"
          />
        </div>
        <label class="text-primary mb-2" for="photoFile">更換頭貼</label>
        <input
          type="file"
          @change="changeFiles"
          id="photoFile"
          class="d-none"
        />
      </div>
      <div class="route-title mt-4">
        <h3>個人資料</h3>
        <router-link to="/user/edit">
          <font-awesome-icon :icon="['fas', 'edit']" class="text-warning" />
          編輯內容
        </router-link>
      </div>
      <table class="userInfo">
        <tr>
          <th>會員名稱:</th>
          <td>{{ userInfo.name }}</td>
        </tr>
        <tr>
          <th>電子信箱:</th>
          <td>{{ userInfo.email }}</td>
        </tr>
        <tr>
          <th>會員密碼:</th>
          <td>
            <router-link to="/user/edit-pwd" class="text-dark">
              <font-awesome-icon :icon="['fas', 'edit']" class="text-warning" />
              修改密碼
            </router-link>
          </td>
        </tr>
        <tr>
          <th>生理性別:</th>
          <td v-if="userInfo.sex === 0">女性</td>
          <td v-if="userInfo.sex === 1">男性</td>
        </tr>
        <tr>
          <th>出生日期:</th>
          <td>{{ userInfo.birth }}</td>
        </tr>
        <tr>
          <th>會員權限</th>
          <td v-if="userInfo.business">商業使用者</td>
          <td v-else>一般使用者</td>
        </tr>
      </table>
    </div>
  </main>
</template>

<script>
import LightBox from 'vue-image-lightbox';

export default {
  data() {
    return {};
  },
  components: {
    LightBox,
  },
  computed: {
    userInfo() {
      return this.$store.state.user.userInfo;
    },
    imgData() {
      const vm = this;
      const arr = [
        {
          thumb: vm.userInfo.img,
          src: vm.userInfo.img,
          caption: vm.userInfo.name,
        },
      ];
      return arr;
    },
  },
  methods: {
    openLightBox() {
      const vm = this;
      vm.$refs.lightbox.showImage(0);
    },
    changeFiles(e) {
      const file = e.target.files[0];
      this.$store.state.photoModule.uploadUserOriginalPhoto = URL.createObjectURL(
        file,
      );
      this.$router.push('/user/crop');
    },
  },
};
</script>
