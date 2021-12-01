<template>
  <section class="makeup mt-4 add">
    <router-view />
    <LightBox ref="lightbox" :media="[fileData]" :show-light-box="false" />
    <div class="route-title">
      <a href="#" @click.prevent="$router.go(-1)">
        <font-awesome-icon
          :icon="['fas', 'arrow-left']"
          style="color: #ffc39f"
        />回上一頁
      </a>
      <h3>妝容表單</h3>
    </div>
    <Step v-if="level >= 2" :content="pageData" @page-change="pageChange" />
    <main class="page-content">
      <section
        class="step step-1"
        :class="{ pageActive: pageData.currentPage === 1 }"
      >
        <section>
          <a href="#" class="d-block text-center" @click.prevent="openLightBox">
            <img
              :src="formData.imgURL_s"
              width="350"
              alt="妝容照片"
              class="img-fluid rounded"
            />
          </a>
        </section>
        <MakeupForm
          v-if="level < 2"
          v-bind="formData"
          @submit-form="submitForm"
        />
        <MakeupForm v-else v-bind="formData" @submit-form="nextStep" />
      </section>
      <section
        class="step step-2"
        :class="{ pageActive: pageData.currentPage === 2 }"
      >
        <MakeProductForm
          :productDataList="formProductData"
          @submit-form="uploadProductAndForm"
        />
      </section>
    </main>
  </section>
</template>

<script>
/* eslint-disable import/no-unresolved */
import LightBox from 'vue-image-lightbox';
import Step from '@/components/Step.vue';
import MakeupForm from './MakeupComponent/MakeupForm.vue';
import MakeProductForm from './MakeupComponent/MakeProductForm.vue';

export default {
  name: 'not-keep-alive',
  components: {
    LightBox,
    MakeupForm,
    MakeProductForm,
    Step,
  },
  data() {
    return {
      upLoadingImg: false,
      pageData: {
        currentPage: 1,
        activePage: 1,
        steps: [
          {
            step: 1,
            text: '妝容資料',
          },
          {
            step: 2,
            text: '上妝產品',
          },
          {
            step: 3,
            text: '完成上傳',
          },
        ],
      },
      page1formData: {},
    };
  },
  computed: {
    fileData() {
      return {
        thumb: this.formData.imgURL_s,
        src: this.formData.imgURL_L,
      };
    },
    dom() {
      return this.$store.state.uiModule.dom;
    },
    formData() {
      return this.$store.getters.getUserMakeupPhotoById(this.$route.params.id);
    },
    formProductData() {
      return this.formData.products.map((item) => ({
        formData: {
          proName: item.name,
          proURL: item.url,
          proContent: item.intro,
        },
        photoData: {
          originPhoto: item.imgpath,
          thumb: item.imgpath,
          src: item.imgpath,
        },
      }));
    },
    // 使用者權限等級
    level() {
      if (this.$store.state.user.userInfo.business) {
        return 2;
      }
      return 1;
    },
  },
  methods: {
    openLightBox() {
      const vm = this;
      vm.$refs.lightbox.showImage(0);
    },
    submitForm(data) {
      this.$store.state.uiModule.isLoading = true;
      this.$store
        .dispatch('MODIFY_MAKEUP', {
          key: this.$route.params.id,
          newContent: data,
        })
        .then(() => {
          this.$swal('成功', '修改完成', 'success');
          this.$store.state.uiModule.isLoading = false;
          this.$router.go(-1);
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
      this.$store.dispatch('MODIFY_MAKEUP', { key: this.formData.key, newContent: makeupData }).then(() => {
        this.$swal('成功', '修改完成', 'success');
        this.$store.state.uiModule.isLoading = false;
        this.$router.push('/makeup/manage');
      });
    },
  },
  mounted() {
    if (this.formData.imgURL_L === '') {
      this.$store.dispatch('GET_MAKEUP_L_WITH_KEY', {
        key: this.formData.key,
        imgKey: this.formData.photoID,
      });
    }
  },
};
</script>
