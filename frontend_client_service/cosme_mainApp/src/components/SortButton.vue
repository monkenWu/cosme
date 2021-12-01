<template>
  <button class="btn-none sort-btn">
    <font-awesome-icon
      :icon="['fas', 'sort-amount-down']"
      @click="active = !active"/>
    <span v-if="!active" @click="active = true"> 排序依據</span>
    <select
      v-model="value"
      @change="sortMethod()"
      @blur="sortMethod()"
      v-if="active"
      autofocus
    >
      <option value="1">最舊→最新</option>
      <option value="0">最新→最舊</option>
    </select>
  </button>
</template>

<script>
export default {
  props: ['sortActive'],
  data() {
    return {
      active: false,
      value: 1,
    };
  },
  methods: {
    sortMethod() {
      const vm = this;
      vm.active = false;
    },
  },
};
</script>
