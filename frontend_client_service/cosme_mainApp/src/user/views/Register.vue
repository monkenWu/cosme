<template>
  <section class="user-form signup mt-5 mt-lg-0">
    <div class="route-title animate__animated animate__faster animate__fadeIn">
      <a href="#" @click.prevent="$router.go(-1)">
        <font-awesome-icon :icon="['fas', 'arrow-left']" style="color: #ffc39f" />
        回上一頁
      </a>
    </div>
    <ValidationObserver ref="form" tag="div"
    class="signup-content animate__animated animate__faster animate__fadeIn">
      <h2 class="h3 text-center text-secondary my-3">註冊帳號</h2>
      <form @submit.prevent="submitRegister">
        <!-- item -->
        <ValidationProvider class="item-con" for="email"
        v-slot="{ errors }" name="電子信箱" rules="required|email" tag="label">
          <p class="text-primary">Email：</p>
          <div class="input-box" :class="{active: dom.inputActive === 'email'}">
            <font-awesome-icon :icon="['fas', 'envelope']" />
            <input
              type="email"
              id="email"
              name="email"
              placeholder="請填入電子信箱"
              class="rounded"
              @focus="dom.inputActive = 'email'"
              @blur="dom.inputActive = null"
              v-model="formData.email"
            />
          </div>
          <span class="text-danger small">{{ errors[0] }}</span>
        </ValidationProvider>
        <!-- item -->
        <ValidationProvider class="item-con" for="name"
        v-slot="{ errors }" name="會員名稱" rules="required|max:30" tag="label">
          <p class="text-primary">會員名稱：</p>
          <div class="input-box" :class="{active: dom.inputActive === 'name'}">
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
        <ValidationProvider class="item-con" for="password"
        v-slot="{ errors }" name="會員密碼"
        rules="required|max:50|min:8" vid="confirm" tag="label">
          <p class="text-primary">會員密碼：</p>
          <div class="input-box" :class="{active: dom.inputActive === 'password'}">
            <font-awesome-icon :icon="['fas', 'lock']" />
            <input
              :type="view.pwd"
              id="password"
              name="password"
              placeholder="請填入會員密碼"
              class="rounded pr-5"
              @focus="dom.inputActive = 'password'"
              @blur="dom.inputActive = null"
              v-model="formData.password"
            />
            <b @click="view.pwd = 'text'" v-if="view.pwd === 'password'" class="view">
              <font-awesome-icon class="text-primary" :icon="['fas', 'eye']" />
            </b>
            <b @click="view.pwd = 'password'" class="view" v-else>
              <font-awesome-icon class="text-danger" :icon="['fas', 'eye-slash']" />
            </b>
          </div>
          <span class="text-danger small">{{ errors[0] }}</span>
        </ValidationProvider>
        <!-- item -->
        <ValidationProvider class="item-con" for="confirm"
        v-slot="{ errors }" name="密碼確認" rules="required|confirmed:confirm" tag="label">
          <p class="text-primary">密碼確認：</p>
          <div class="input-box" :class="{active: dom.inputActive === 'confirm'}">
            <font-awesome-icon :icon="['fas', 'check-double']" />
            <input
              :type="view.conf"
              id="confirm"
              name="confirm"
              placeholder="再次輸入密碼"
              class="rounded"
              @focus="dom.inputActive = 'confirm'"
              @blur="dom.inputActive = null"
              v-model="formData.confirm"
            />
            <b @click="view.conf = 'text'" v-if="view.conf === 'password'" class="view">
              <font-awesome-icon class="text-primary" :icon="['fas', 'eye']" />
            </b>
            <b @click="view.conf = 'password'" v-else class="view">
              <font-awesome-icon class="text-danger" :icon="['fas', 'eye-slash']" />
            </b>
          </div>
          <span class="text-danger small">{{ errors[0] }}</span>
        </ValidationProvider>
        <!-- item -->
        <ValidationProvider class="item-con" for="birth"
        v-slot="{ errors }" name="出生日期" rules="required" tag="label">
          <p class="text-primary">出生日期：</p>
          <div class="input-box" :class="{active: dom.inputActive === 'birth'}">
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
        <ValidationProvider class="item-con"
        v-slot="{ errors }" name="性別" rules="required" tag="div">
          <p class="text-primary">會員性別：</p>
          <div class="radio-style">
            <article class="ml-2">
              <label for="sex-1" class="mr-3 p-2">
                <input type="radio" id="sex-1" name="sex" v-model="formData.sex" value="1">
                男
              </label>
              <label for="sex-0" class="mr-3 p-2">
                <input type="radio" id="sex-0" name="sex" v-model="formData.sex" value="0">
                女
              </label>
            </article>
            <span class="text-danger small">{{ errors[0] }}</span>
          </div>
        </ValidationProvider>
        <div class="submit mt-3">
          <div class="input-box mb-3">
            <button>
              <span>立即註冊</span>
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
  name: 'SignUp',
  data() {
    return {
      view: {
        pwd: 'password',
        conf: 'password',
      },
      formData: {
        name: '',
        email: '',
        password: '',
        confirm: '',
        remember: false,
        sex: null,
        birth: '',
      },
      // formData: {
      //   email: 'test123@gmail.com',
      //   name: 'testPeople',
      //   password: '12345678',
      //   confirm: '12345678',
      //   remember: false,
      //   sex: 1,
      //   birth: '2020-05-05',
      // },
    };
  },
  components: {
    ValidationProvider, ValidationObserver,
  },
  computed: {
    isLogin() {
      return this.$store.state.user.isLogin;
    },
    dom() {
      return this.$store.state.uiModule.dom;
    },
  },
  methods: {
    submitRegister() {
      const vm = this;
      vm.$refs.form.validate().then((success) => {
        if (!success) {
          return;
        }
        // eslint-disable-next-line no-restricted-globals
        const confirming = confirm('確認送出表單? 送出後電子信箱無法更改');
        if (confirming) {
          // 送出
          vm.$store.commit('setLoading', true);
          vm.$store
            .dispatch('SIGNUP', this.formData)
            .then(() => {
              vm.$store.commit('setLoading', false);
              vm.$router.push('/').catch(() => {});
            })
            .catch(() => {
              vm.$store.commit('setLoading', false);
              this.$swal('登入失敗', '註冊失敗', 'error');
            });
        }
      });
    },
  },
};
</script>
