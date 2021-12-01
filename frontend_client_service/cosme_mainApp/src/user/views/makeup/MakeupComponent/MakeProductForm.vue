<template>
  <section class="make-product">
    <div class="route-title mt-4">
      <h3 class="text-secondary">新增上妝產品內容</h3>
      <a
        href="#"
        @click="addProductArea"
        class="text-primary"
        v-if="list.length < 5"
      >
        <font-awesome-icon :icon="['fas', 'plus-circle']" /> 新增產品 ({{
          list.length
        }}/5)
      </a>
      <p class="text-secondary" v-else>
        <font-awesome-icon :icon="['fas', 'plus-circle']" /> 已達上限 ({{
          list.length
        }}/5)
      </p>
    </div>
    <ValidationObserver ref="form" tag="div" v-slot="{ valid }">
      <form @submit.prevent="submitForm">
        <ul>
          <li
            class="item animate__animated animate__faster animate__fadeIn"
            v-for="(item, index) in list"
            :key="index"
          >
            <a
              href="#"
              @click.prevent="deleteItem(index)"
              class="close-btn"
              v-if="list.length > 1"
            >
              <font-awesome-icon :icon="['fas', 'times']" />
            </a>
            <div class="row">
              <div class="col-xl-2 col-lg-3 col-md-3 pr-md-0 my-md-0 my-3">
                <div class="preview-picture" v-if="item.photoData.thumb">
                  <figure>
                    <img
                      :src="item.photoData.thumb"
                      alt="妝容商品"
                      class="img-fluid rounded"
                    />
                    <figcaption>
                      <a href="#" @click.prevent="openLightBox(index)">
                        <font-awesome-icon :icon="['fas', 'search-plus']" />
                      </a>
                      <ul>
                        <li>
                          <a
                            href="#"
                            @click.prevent="deletePhoto(index)"
                            class="text-danger"
                          >
                            <font-awesome-icon :icon="['fas', 'trash-alt']" />
                          </a>
                        </li>
                        <li>
                          <!-- <router-link to="add/crop" class="text-info">
                            <font-awesome-icon :icon="['fas', 'crop']" />
                          </router-link> -->
                          <a
                            href="#"
                            @click.prevent="
                              cropProductImg.open = true;
                              cropProductImg.productIndex = index;
                            "
                          >
                            <font-awesome-icon :icon="['fas', 'crop']"
                          /></a>
                        </li>
                      </ul>
                    </figcaption>
                  </figure>
                </div>
                <label
                  @click="currentProduct = index"
                  class="upload-img pt-3"
                  v-else
                >
                  <font-awesome-icon :icon="['fas', 'plus-circle']" />
                  上傳圖片
                  <ImgFileInput @uploadFinishHook="fileInputFinish" />
                </label>
              </div>
              <div class="col pl-0 d-flex flex-column justify-content-center">
                <div class="row">
                  <p class="col-12">上妝商品說明</p>
                  <ValidationProvider
                    class="col-md-4 pr-md-0"
                    v-slot="{ errors }"
                    name="商品名稱"
                    tag="label"
                    rules="required|max:30"
                  >
                    <input
                      type="text"
                      class="form-control"
                      placeholder="商品名稱"
                      v-model="item.formData.proName"
                    />
                    <span class="text-danger small">{{ errors[0] }}</span>
                  </ValidationProvider>
                  <ValidationProvider
                    class="col-md-8 link"
                    v-slot="{ errors }"
                    name="商品連結"
                    tag="label"
                    rules="required|url"
                  >
                    <font-awesome-icon :icon="['fas', 'link']" />
                    <input
                      type="text"
                      class="form-control"
                      placeholder="商品連結"
                      v-model="item.formData.proURL"
                    />
                    <span class="text-danger small">{{ errors[0] }}</span>
                  </ValidationProvider>
                </div>
                <div class="row">
                  <ValidationProvider
                    class="col-12"
                    v-slot="{ errors }"
                    name="商品及品牌說明"
                    tag="label"
                    rules="required"
                  >
                    <textarea
                      class="form-control textarea"
                      placeholder="商品及品牌說明"
                      v-model="item.formData.proContent" />
                    <span class="text-danger small">{{ errors[0] }}</span>
                  </ValidationProvider>
                </div>
              </div>
            </div>
          </li>
        </ul>
        <div class="input-box mb-3 mt-5 justify-content-end">
          <button class="submit done" v-if="valid">
            <span>完成填寫</span>
          </button>
          <button class="submit" v-else @click.prevent disabled>
            <span>尚未完成</span>
          </button>
        </div>
      </form>
    </ValidationObserver>
    <Modal
      v-if="cropProductImg.open"
      @close-modal="cropProductImg.open = false"
    >
      <Crop
        :cropingPhotoURL="
          list[cropProductImg.productIndex].photoData.originPhoto
        "
        @clip-finish="clipFinish"
        @cancel="cropProductImg.open = false"
      />
    </Modal>
  </section>
</template>

<script>
/* eslint-disable import/no-unresolved */
import ImgFileInput from '@/components/ImgFileInput.vue';
import { ValidationObserver, ValidationProvider, extend } from 'vee-validate';
import { required, max } from 'vee-validate/dist/rules';
import Modal from '@/components/Modal/PopModal.vue';
import Crop from '@/components/Crop.vue';

extend('required', required);
extend('max', max);
extend('url', (value) => {
  const regexp = /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
  if (regexp.test(value)) {
    return true;
  }
  return false;
});
extend('url', {
  validate: (value) => {
    const regexp = /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
    if (regexp.test(value)) {
      return true;
    }
    return false;
  },
  message: '{_field_} 網址格式有誤',
});

export default {
  props: {
    productDataList: {
      type: Array,
    },
  },
  data() {
    return {
      cropProductImg: {
        open: false,
        productIndex: null,
      },
      canSubmitInterceptor: true,
      currentProduct: 0,
      list: [
        {
          formData: {
            proName: '',
            proURL: '',
            proContent: '',
          },
          photoData: {
            originPhoto: '',
            thumb: '',
            src: '',
          },
        },
      ],
    };
  },
  components: {
    ImgFileInput,
    ValidationProvider,
    ValidationObserver,
    Modal,
    Crop,
  },
  methods: {
    fileInputFinish(img) {
      this.list[this.currentProduct].photoData = {
        originPhoto: img,
        thumb: img,
        src: img,
      };
      // 裁切圖片不知道為什麼一直失效
    },
    deletePhoto(index) {
      this.list[index].photoData = {
        originPhoto: '',
        thumb: '',
        src: '',
      };
    },
    deleteItem(index) {
      this.list.splice(index, 1);
    },
    openLightBox(index) {
      this.$emit('openLightProBox', this.list[index].photoData);
    },
    addProductArea() {
      const obj = {
        formData: {
          proName: '',
          proURL: '',
          proContent: '',
        },
        photoData: {
          originPhoto: '',
          thumb: '',
          src: '',
        },
      };
      this.list.push(obj);
    },
    clipFinish(imgData) {
      this.list[this.cropProductImg.productIndex].photoData.thumb = imgData;
      this.list[this.cropProductImg.productIndex].photoData.src = imgData;
      this.cropProductImg.open = false;
    },
    submitForm() {
      const vm = this;
      // 先判斷外部額外因素是否通過
      if (this.canSubmitInterceptor) {
        vm.$refs.form.validate().then((success) => {
          if (!success) {
            return;
          }
          this.$emit('submit-form', this.list);
        });
      } else {
        console.log('無法提交：canSubmitInterceptor==false');
        if (this.cantMessage !== '') {
          alert(this.cantMessage);
        }
      }
    },
  },
  mounted() {
    console.log(this.productDataList);
    if (this.productDataList) {
      this.list = this.productDataList;
    }
  },
};
</script>
