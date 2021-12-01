<template>
  <section class="makeup mt-4 add">
    <router-view />
    <LightBox ref="lightbox" :media="[fileData]" :show-light-box="false" />
    <LightBox ref="proLightbox" :media="[productLightBox]" :show-light-box="false" />
    <div class="animate__animated animate__faster animate__fadeIn">
      <div class="route-title">
        <a href="#" @click.prevent="$router.go(-1)">
          <font-awesome-icon :icon="['fas', 'arrow-left']" style="color: #ffc39f" /> 取消上傳
        </a>
        <h3>新增妝容照</h3>
      </div>
      <Step
        v-if="level >= 2"
        :content="pageData"
        @page-change="pageChange" />
      <main class="page-content">
        <section
          class="step step-1"
          :class="{'pageActive': pageData.currentPage === 1}">
          <div class="d-flex box-content" v-if="!fileData.src">
            <TabButton
              title="拍攝照片"
              subtitle="Take a Photo"
              color="#74ebd5"
              subtitleColor="#54debd"
              :fwiIcon="['fas', 'camera']"
              @btn-click="$router.push({name:'MakeupCamera'})"
            />
            <TabButton
              title="從相簿中選擇"
              subtitle="Album"
              color="#ffafbe"
              subtitleColor="#f295a8"
              :fwiIcon="['far', 'images']"
            >
              <ImgFileInput @uploadFinishHook="fileInputFinish" />
            </TabButton>
          </div>
          <div class="preview-picture" v-else>
            <figure>
              <img :src="fileData.thumb" alt="妝容裁切" class="img-fluid rounded" />
              <figcaption>
                <a href="#" @click.prevent="openLightBox">檢視相片</a>
                <ul>
                  <li>
                    <a href="#" @click.prevent="deletePhoto()">刪除照片</a>
                  </li>
                  <li>
                    <router-link to="add/crop">重新裁切</router-link>
                  </li>
                </ul>
              </figcaption>
            </figure>
          </div>
          <MakeupForm
            v-if="level < 2"
            :canSubmitInterceptor="canNewMakeupUpload"
            cantMessage="照片未通過無法上傳"
            @submit-form="uploadPhotoAndForm"
          />
          <MakeupForm
            v-else
            :canSubmitInterceptor="canNewMakeupUpload"
            cantMessage="照片未通過無法上傳"
            @submit-form="nextStep"
          />
        </section>
        <section
          class="step step-2"
          :class="{'pageActive': pageData.currentPage === 2}">
          <MakeProductForm
            @openLightProBox="openLightProBox"
            @submit-form="uploadProductAndForm"
          />
        </section>
      </main>
    </div>
  </section>
</template>

<script>
import { mapState } from 'vuex';
import LightBox from 'vue-image-lightbox';
/* eslint-disable import/no-unresolved */
import TabButton from '@/components/TabButton.vue';
import ImgFileInput from '@/components/ImgFileInput.vue';
import Step from '@/components/Step.vue';
import MakeupForm from './MakeupComponent/MakeupForm.vue';
import MakeProductForm from './MakeupComponent/MakeProductForm.vue';

export default {
  components: {
    LightBox,
    MakeupForm,
    MakeProductForm,
    TabButton,
    ImgFileInput,
    Step,
  },
  data() {
    return {
      upLoadingImg: false,
      progress: 0,
      canUpload: false,
      pageData: {
        currentPage: 1,
        activePage: 1,
        steps: [
          {
            step: 1,
            text: '妝容資料',
          }, {
            step: 2,
            text: '上妝產品',
          }, {
            step: 3,
            text: '完成上傳',
          },
        ],
      },
      productLightBox: {},
      page1formData: {},
    };
  },
  watch: {
    progress(val) {
      if (val >= 100) {
        this.$router.push('/photo/done');
      }
    },
  },
  computed: {
    ...mapState({
      fileData: (state) => state.makeupModule.uploadMakeupPhoto,
      dom: (state) => state.uiModule.dom,
      canNewMakeupUpload: (state) => state.makeupModule.canNewMakeupUpload,
    }),
    // 使用者權限等級
    level() {
      if (this.$store.state.user.userInfo.business) {
        return 2;
      }
      return 1;
    },
  },
  methods: {
    fileInputFinish(img) {
      this.$store.commit('addUserMakeupPhoto', img);
      this.$router.push('/makeup/add/crop');
    },
    openLightBox() {
      const vm = this;
      vm.$refs.lightbox.showImage(0);
    },
    openLightProBox(val) {
      this.productLightBox = val;
      this.$refs.proLightbox.showImage(0);
    },
    deletePhoto() {
      this.$swal({
        title: '提醒',
        text: '確定要刪除照片?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '刪除',
      }).then((res) => {
        if (res.isConfirmed) {
          this.$store.commit('deleteUserMakeupPhoto');
          this.$store.commit('changeCanNewMakeupUploadState', false);
        }
      });
    },
    uploadPhotoAndForm(formData) {
      this.$store.state.uiModule.isLoading = true;
      this.$store.dispatch('UPLOAD_MAKEUP', formData).then(() => {
        this.$swal('成功', '上傳完成', 'success');
        this.$store.state.uiModule.isLoading = false;
        this.$router.push('/makeup/manage');
      });
    },
    uploadProductAndForm(productFormData) {
      this.$store.state.uiModule.isLoading = true;
      const makeupData = {
        name: this.page1formData.name,
        content: this.page1formData.content,
        tags: this.page1formData.tags,
        products: productFormData.map((item) => ({
          name: item.formData.proName,
          imgpath: item.photoData.src,
          url: item.formData.proURL,
          intro: item.formData.proContent,
        })),
      };
      this.$store.dispatch('UPLOAD_MAKEUP', makeupData).then(() => {
        this.$swal('成功', '上傳完成', 'success');
        this.$store.state.uiModule.isLoading = false;
        this.$router.push('/makeup/manage');
      });
    },
    pageChange(item) {
      if (item.step <= this.pageData.activePage) {
        this.pageData.currentPage = item.step;
      }
    },
    nextStep(page1formData) {
      this.page1formData = page1formData;
      this.pageData.currentPage += 1;
      this.pageData.activePage += 1;
    },
  },
};
</script>
