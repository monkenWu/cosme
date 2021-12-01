<template>
  <section class="login">
    <h3>Login</h3>
    <font-awesome-icon :icon="['fas', 'user-circle']" />
    <ValidationObserver ref="form" tag="div" style="width:100%">
      <form action @submit.prevent="onSubmit" class="form-content">
        <ValidationProvider v-slot="{ errors }" name="電子信箱" rules="required|email" tag="div">
          <div class="input-box" :class="{active: dom.inputActive === 'email'}">
            <input
              type="email"
              id="email"
              name="email"
              placeholder="請輸入電子信箱"
              @focus="dom.inputActive = 'email'"
              @blur="dom.inputActive = null"
              v-model="formData.email"
            />
            <font-awesome-icon :icon="['fas', 'envelope']" />
          </div>
          <span class="text-danger small ml-2 mb-2 d-inline-block">{{ errors[0] }}</span>
        </ValidationProvider>
        <ValidationProvider v-slot="{ errors }" name="密碼" rules="required|min:8" tag="div">
          <div class="input-box" :class="{active: dom.inputActive === 'password'}">
            <input
              type="password"
              id="password"
              name="password"
              placeholder="請輸入密碼"
              @focus="dom.inputActive = 'password'"
              @blur="dom.inputActive = null"
              v-model="formData.password"
            />
            <font-awesome-icon :icon="['fas', 'lock']" />
          </div>
          <span class="text-danger small ml-2 mb-2 d-inline-block">{{ errors[0] }}</span>
        </ValidationProvider>
        <div class="input-box mb-3">
          <button class="submit">
            <span>立即登入</span>
          </button>
        </div>
        <div class="footer-input">
          <div class="form-check">
            <input
              class="form-check-input mt-0"
              type="checkbox"
              id="remember"
              v-model="formData.remember"
            />
            <label class="form-check-label" for="remember">記住帳號</label>
          </div>
          <router-link to="forgetPassword">忘記密碼</router-link>
        </div>
      </form>
    </ValidationObserver>
    <div class="hr-content">
      <h4>or</h4>
    </div>
    <div class="social-content">
      <ul>
        <li class="fb">
          <button>
            <font-awesome-icon :icon="['fab', 'facebook-f']" />
          </button>
        </li>
        <li class="google">
          <button>
            <font-awesome-icon :icon="['fab', 'google']" />
          </button>
        </li>
        <li class="twitter">
          <button>
            <font-awesome-icon :icon="['fab', 'twitter']" />
          </button>
        </li>
      </ul>
      <router-link to="/auth/register" tag="button">立即註冊</router-link>
    </div>
  </section>
</template>

<script>
import { ValidationObserver, ValidationProvider, extend } from 'vee-validate';
import { required, email, min } from 'vee-validate/dist/rules';

extend('email', email);
extend('required', required);
extend('min', min);

export default {
  name: 'Login',
  data() {
    return {
      ops: {
        scrollPanel: {
          scrollingY: true,
          scrollingX: false,
          speed: 100,
        },
        bar: {
          background: '#e6dee9',
        },
      },
      formData: {
        email: '',
        password: '',
        remember: false,
      },
    };
  },
  components: {
    ValidationProvider,
    ValidationObserver,
  },
  methods: {
    login() {
      this.$store.commit('setLoading', true);
      this.$store
        .dispatch('LOGIN', this.formData)
        .then(() => {
          this.$store.commit('setLoading', false);
          this.$router.push('/').catch(() => {});
        })
        .catch(() => {
          this.$store.commit('setLoading', false);
          this.$swal('登入失敗', '帳號密碼有誤', 'error');
        });
    },
    onSubmit() {
      const vm = this;
      this.$refs.form.validate().then((success) => {
        if (!success) {
          return;
        }
        vm.login();
      });
    },
  },
  computed: {
    isLogin() {
      return this.$store.state.user.isLogin;
    },
    dom() {
      return this.$store.state.uiModule.dom;
    },
  },
  mounted() {
    // 未登入路由自動跳轉
    if (this.isLogin === false) {
      // this.$router.push('/').catch(() => {});
    }
  },
};
</script>
