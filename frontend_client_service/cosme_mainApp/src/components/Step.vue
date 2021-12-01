<template>
  <div class="step-content mt-3 mb-4" v-if="content.currentPage">
    <ul>
      <li
      v-for="item in content.steps"
      @click="pageChange(item)"
      :key="item.step"
      :class="{
        'pageActive': content.currentPage === item.step,
        'pageDone': item.step <= content.activePage
      }">
        <h3>Step.{{ item.step }}</h3>
        <p>{{ item.text }}</p>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  props: {
    content: {
      type: Object,
      default: () => ({
        currentPage: 0,
        activePage: 0,
        steps: [{ step: 0, text: '步驟內容' }],
      }),
    },
  },
  methods: {
    pageChange(item) {
      this.$emit('page-change', item);
    },
  },
};
</script>
