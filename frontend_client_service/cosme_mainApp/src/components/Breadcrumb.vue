<template>
  <nav aria-label="breadcrumb" v-if="breadcrumbList">
    <ol class="breadcrumb m-0 bg-transparent justify-content-end px-0 small">
      <li class="breadcrumb-item py-1">
        <router-link to="/">首頁</router-link>
      </li>
      <li
        class="breadcrumb-item py-1"
        v-for="(item, index) in list"
        :key="index">
        <router-link :to="`/${item.link}`" v-if="item.link">
          {{ item.name }}
        </router-link>
        <span v-else class="text-secondary">{{ item.name }}</span>
      </li>
    </ol>
  </nav>
</template>

<script>
export default {
  name: 'Container',
  props: ['list'],
  data() {
    return {
      breadcrumbList: [],
    };
  },
  watch: {
    $route() { this.updateList(); },
  },
  mounted() {
    this.updateList();
  },
  methods: {
    updateList() {
      this.breadcrumbList = this.$route.meta.breadcrumb;
    },
  },
};
</script>
