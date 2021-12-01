<template>
  <section class="edit user-form mt-4">
    <div class="route-title mb-4 mb-lg-1 animate__animated animate__faster animate__fadeIn">
      <a href="#" @click.prevent="$router.go(-1)">
        <font-awesome-icon :icon="['fas', 'arrow-left']" style="color: #ffc39f" />
        回上一頁
      </a>
      <h3>修改密碼</h3>
    </div>
    <ValidationObserver ref="form" tag="div"
    class="animate__animated animate__faster animate__fadeIn p-0 p-lg-3">
      <form @submit.prevent="submitEdit">
        <!-- item -->
        <ValidationProvider class="item-con" for="passwordOld"
        v-slot="{ errors }" name="舊密碼"
        rules="required|max:50|min:8" tag="label">
          <p class="text-primary">舊密碼：</p>
          <div class="input-box" :class="{active: dom.inputActive === 'passwordOld'}">
            <font-awesome-icon :icon="['fas', 'lock']" />
            <input
              :type="view.pwdOld"
              id="passwordOld"
              name="passwordOld"
              placeholder="請輸入舊密碼"
              class="rounded pr-5"
              @focus="dom.inputActive = 'passwordOld'"
              @blur="dom.inputActive = null"
              v-model="formData.passwordOld"
            />
            <b @click="view.pwdOld = 'text'" v-if="view.pwdOld === 'password'" class="view">
              <font-awesome-icon class="text-primary" :icon="['fas', 'eye']" />
            </b>
            <b @click="view.pwdOld = 'password'" class="view" v-else>
              <font-awesome-icon class="text-danger" :icon="['fas', 'eye-slash']" />
            </b>
          </div>
          <span class="text-danger small">{{ errors[0] }}</span>
        </ValidationProvider>
        <!-- item -->
        <ValidationProvider class="item-con" for="password"
        v-slot="{ errors }" name="新密碼"
        rules="required|max:50|min:8" vid="confirm" tag="label">
          <p class="text-primary">新密碼：</p>
          <div class="input-box" :class="{active: dom.inputActive === 'password'}">
            <font-awesome-icon :icon="['fas', 'lock']" />
            <input
              :type="view.pwd"
              id="password"
              name="password"
              placeholder="請填入新密碼"
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
        v-slot="{ errors }" name="新密碼確認" rules="required|confirmed:confirm" tag="label">
          <p class="text-primary">新密碼確認：</p>
          <div class="input-box" :class="{active: dom.inputActive === 'confirm'}">
            <font-awesome-icon :icon="['fas', 'check-double']" />
            <input
              :type="view.conf"
              id="confirm"
              name="confirm"
              placeholder="再次輸入新密碼"
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
  required, min, max, confirmed,
} from 'vee-validate/dist/rules';

extend('required', required);
extend('min', min);
extend('max', max);
extend('confirmed', confirmed);

export default {
  data() {
    return {
      view: {
        pwdOld: 'password',
        pwd: 'password',
        conf: 'password',
      },
      formData: {
        passwordOld: '',
        password: '',
        confirm: '',
      },
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
    submitEdit() {
      const vm = this;
      vm.$refs.form.validate().then((success) => {
        if (!success) {
          return;
        }
        // eslint-disable-next-line no-restricted-globals
        const confirming = confirm('確認修改密碼?');
        if (confirming) {
          // 送出
          vm.$store.commit('setLoading', true);
          // this.$store
          //   .dispatch('SIGNUP', this.formData)
          //   .then(() => {
          //     this.$router.push('/').catch(() => {});
          //   })
          //   .catch(() => {
          //     alert('註冊失敗');
          //   });
        }
      });
    },
  },
};
</script>
