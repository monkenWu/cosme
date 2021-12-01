<template>
  <section class="edit user-form mt-4">
    <div
      class="route-title mb-4 mb-lg-1 animate__animated animate__faster animate__fadeIn"
    >
      <a href="#" @click.prevent="$router.go(-1)">
        <font-awesome-icon
          :icon="['fas', 'arrow-left']"
          style="color: #ffc39f"
        />
        回上一頁
      </a>
      <h3>編輯帳號</h3>
    </div>
    <ValidationObserver
      ref="form"
      tag="div"
      class="animate__animated animate__faster animate__fadeIn p-0 p-lg-3"
    >
      <form @submit.prevent="submitEdit">
        <!-- item -->
        <ValidationProvider
          class="item-con"
          for="name"
          v-slot="{ errors }"
          name="會員名稱"
          rules="required|max:30"
          tag="label"
        >
          <p class="text-primary">會員名稱：</p>
          <div
            class="input-box"
            :class="{ active: dom.inputActive === 'name' }"
          >
            <font-awesome-icon :icon="['fas', 'user']" />
            <input
              type="text"
              id="name"
              name="name"
              placeholder="請填入會員名稱"
              class="rounded"
              @focus="dom.inputActive = 'name'"
              @blur="dom.inputActive = null"
              v-model="formData.name"
            />
          </div>
          <span class="text-danger small">{{ errors[0] }}</span>
        </ValidationProvider>
        <!-- item -->
        <ValidationProvider
          class="item-con"
          for="birth"
          v-slot="{ errors }"
          name="出生日期"
          rules="required"
          tag="label"
        >
          <p class="text-primary">出生日期：</p>
          <div
            class="input-box"
            :class="{ active: dom.inputActive === 'birth' }"
          >
            <input
              type="date"
              id="birth"
              name="birth"
              class="rounded pl-2 pl-sm-4 text-secondary"
              @focus="dom.inputActive = 'birth'"
              @blur="dom.inputActive = null"
              v-model="formData.birth"
            />
          </div>
          <span class="text-danger small">{{ errors[0] }}</span>
        </ValidationProvider>
        <!-- item -->
        <ValidationProvider
          class="item-con"
          v-slot="{ errors }"
          name="性別"
          rules="required"
          tag="div"
        >
          <p class="text-primary">會員性別：</p>
          <div class="radio-style">
            <article class="ml-2">
              <label for="sex-1" class="mr-3 p-2">
                <input
                  type="radio"
                  id="sex-1"
                  name="sex"
                  v-model="formData.sex"
                  value="1"
                />
                男
              </label>
              <label for="sex-0" class="mr-3 p-2">
                <input
                  type="radio"
                  id="sex-0"
                  name="sex"
                  v-model="formData.sex"
                  value="0"
                />
                女
              </label>
            </article>
            <span class="text-danger small">{{ errors[0] }}</span>
          </div>
        </ValidationProvider>
        <ValidationProvider
          class="item-con"
          v-slot="{ errors }"
          name="會員權限"
          rules="required"
          tag="div"
        >
          <p class="text-primary">會員權限：</p>
          <div class="radio-style">
            <article class="ml-2">
              <label for="business-false" class="mr-3 p-2">
                <input
                  type="radio"
                  id="business-false"
                  name="business"
                  v-model="formData.business"
                  value="false"
                />
                一般使用者
              </label>
              <label for="business-true" class="mr-3 p-2">
                <input
                  type="radio"
                  id="business-true"
                  name="business"
                  v-model="formData.business"
                  value="true"
                />
                商業使用者
              </label>
            </article>
            <span class="text-danger small">{{ errors[0] }}</span>
          </div>
        </ValidationProvider>
        <div class="submit mt-3">
          <div class="input-box mb-3">
            <button>
              <span>送出修改</span>
            </button>
          </div>
        </div>
      </form>
    </ValidationObserver>
  </section>
</template>

<script>
import { ValidationObserver, ValidationProvider, extend } from 'vee-validate';
import {
  required, email, min, max, confirmed,
} from 'vee-validate/dist/rules';

extend('email', email);
extend('required', required);
extend('min', min);
extend('max', max);
extend('confirmed', confirmed);

export default {
  data() {
    return {
      formData: {},
    };
  },
  components: {
    ValidationProvider,
    ValidationObserver,
  },
  computed: {
    isLogin() {
      return this.$store.state.user.isLogin;
    },
    dom() {
      return this.$store.state.uiModule.dom;
    },
    userInfo() {
      return this.$store.state.user.userInfo;
    },
  },
  methods: {
    submitEdit() {
      const vm = this;
      this.$refs.form.validate().then((success) => {
        if (!success) {
          return;
        }
        // eslint-disable-next-line no-restricted-globals
        const confirming = confirm('確認送出表單?');
        if (confirming) {
          // 送出
          vm.$store.commit('setLoading', true);
          console.log(this.formData);
          this.$store
            .dispatch('MODIFY_USER_INFO', this.formData)
            .then(() => {
              this.$swal('成功', '編輯成功', 'success');
            })
            .catch(() => {
              alert('編輯失敗');
            })
            .finally(() => {
              this.$store.commit('setLoading', false);
            });
        }
      });
    },
  },
  mounted() {
    // 利用解構處理 call by refrence 的問題，用 tmpEmail 避免與上方的 import email 衝突
    const {
      birth,
      business,
      email: tmpEmail,
      img,
      key,
      name,
      sex,
      time,
    } = this.userInfo;
    this.formData = {
      birth,
      business,
      email: tmpEmail,
      img,
      key,
      name,
      sex,
      time,
    };
  },
};
</script>
