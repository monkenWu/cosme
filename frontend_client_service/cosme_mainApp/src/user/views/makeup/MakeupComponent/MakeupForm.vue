<template>
  <ValidationObserver
    ref="form"
    tag="div"
    v-slot="{ valid }"
    class="animate__animated animate__faster animate__fadeIn"
  >
    <div class="route-title mt-4">
      <h3 class="text-secondary">妝容說明填寫</h3>
    </div>
    <div>
      <form class="px-0 px-sm-2 content" @submit.prevent="submitForm">
        <!-- item -->
        <ValidationProvider
          for="name"
          v-slot="{ errors }"
          name="妝容名稱"
          tag="label"
          rules="required|min:2|max:30"
        >
          <p class="mb-1 text-primary">* 妝容名稱：</p>
          <input
            type="text"
            id="name"
            name="name"
            placeholder="在此填入妝容名稱"
            class="form-control"
            v-model="formData.name"
          />
          <span class="text-danger small">{{ errors[0] }}</span>
        </ValidationProvider>
        <!-- item -->
        <ValidationProvider
          for="content"
          v-slot="{ errors }"
          name="妝容介紹"
          tag="label"
          rules="required|max:500"
        >
          <p class="mb-1 text-primary">* 妝容介紹：</p>
          <textarea
            type="text"
            id="content"
            name="content"
            ref="content"
            placeholder="在此填入妝容介紹"
            class="form-control"
            v-model="formData.content"
          />
          <span class="text-danger small">{{ errors[0] }}</span>
        </ValidationProvider>
        <!-- item -->
        <label class="custom-tags">
          <p class="mb-1 text-primary">
            <font-awesome-icon :icon="['fas', 'tags']" />標籤設定
          </p>
          <vue-tags-input
            class="custom-tags"
            v-model="tagData.tag"
            placeholder="最多 10 個標籤"
            :tags="formData.tags"
            :validation="tagData.validation"
            :autocomplete-items="filteredItems"
            :add-on-key="[32, 13]"
            :max-tags="10"
            @before-adding-tag="checkTag"
            @tags-changed="newTags => formData.tags = newTags"
          />
          <ValidationProvider for="tags" tag="label" name="標籤" v-slot="{ errors }" rules="required">
            <input type="checkbox" id="tags" v-model="formData.tags" class="d-none" />
            <span
              class="text-danger small"
              v-if="tagData.tagError||errors[0]"
            >{{ tagData.tagError }} {{ errors[0] }}</span>
          </ValidationProvider>
        </label>
        <!-- item -->
        <div class="input-box mb-3 mt-5 justify-content-end">
          <button class="submit done" v-if="valid && canSubmitInterceptor">
            <span>完成填寫</span>
          </button>
          <button class="submit" v-else @click.prevent disabled>
            <span>尚未完成</span>
          </button>
        </div>
      </form>
    </div>
  </ValidationObserver>
</template>

<script>
import VueTagsInput from '@johmun/vue-tags-input';
import { ValidationObserver, ValidationProvider, extend } from 'vee-validate';
import { required, min, max } from 'vee-validate/dist/rules';

extend('required', required);
extend('min', min);
extend('max', max);

export default {
  components: {
    VueTagsInput,
    ValidationProvider,
    ValidationObserver,
  },
  props: {
    name: {
      type: String,
      default: '',
    },
    content: {
      type: String,
      default: '',
    },
    time: {
      type: String,
      default: '',
    },
    tags: {
      type: Array,
      default: () => [],
    },
    // 可以加入額外判斷再送出時
    canSubmitInterceptor: {
      type: Boolean,
      default: true,
    },
    cantMessage: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      formData: {
        name: this.name,
        content: this.content,
        time: this.time,
        tags: this.tags,
      },
      tagData: {
        tag: '',
        tagError: '',
        autocompleteItems: [],
        validation: [
          {
            // 不能使用的符號設定
            classes: 'no-braces',
            rule: ({ text }) => text.indexOf('@') !== -1 || text.indexOf('}') !== -1,
            disableAdd: true,
          },
        ],
      },
    };
  },
  watch: {
    'formData.content': {
      handler() {
        const textarea = this.$refs.content;
        let adjustedHeight = textarea.clientHeight;
        adjustedHeight = Math.max(textarea.scrollHeight, adjustedHeight);
        if (adjustedHeight > textarea.clientHeight) {
          textarea.style.height = `${adjustedHeight}px`;
        }
      },
    },
  },
  computed: {
    filteredItems() {
      return this.tagData.autocompleteItems.filter(
        (i) => i.text.toLowerCase().indexOf(this.tagData.tag.toLowerCase()) !== -1,
      );
    },
    dom() {
      return this.$store.state.uiModule.dom;
    },
  },
  methods: {
    checkTag(obj) {
      const vm = this;
      vm.tagData.tagError = '';
      if (vm.tags.length >= 10) {
        vm.tagData.tagError = '最多只能使用 10 個標籤';
      } else if (obj.tag.text.indexOf(' ') !== -1) {
        vm.tagData.tagError = '請勿使用空白符號';
      } else {
        obj.addTag();
      }
    },
    submitForm() {
      const vm = this;
      // 先判斷外部額外因素是否通過
      if (this.canSubmitInterceptor) {
        vm.$refs.form.validate().then((success) => {
          if (!success) {
            return;
          }
          this.$emit('submit-form', this.formData);
          // this.formData = {
          //   name: '',
          //   content: '',
          //   time: '',
          //   tags: [],
          // };
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
    // 取得auto tag資料
    this.tagData.autocompleteItems = [
      {
        text: '日系妝容',
      },
    ];
  },
};
</script>
