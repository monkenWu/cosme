<template>
  <div class="makeup-item">
    <figure class="figure" :style="{ 'background-image': `url(${imgURL_s})` }">
      <img src="~@/assets/img/transparent.png" class="transparent" />
      <slot name="hoverBtn">
        <div class="hover flex-column">
          <router-link
            :to="`/makeup/edit/${userMakeupKey}`"
            tag="button"
            class="btn btn-light mb-2"
          >
            <font-awesome-icon :icon="['fas', 'edit']" />修改內容
          </router-link>
          <button @click="$emit('delete-makeup')" class="btn btn-light">
            <font-awesome-icon :icon="['far', 'trash-alt']" />刪除照片
          </button>
        </div>
      </slot>
    </figure>
    <article class="content">
      <router-link tag="h3" :to="`/makeup/manage/detail/${userMakeupKey}`">{{
        name
      }}</router-link>
      <ul class="text-primary">
        <li v-for="(iItem, iIndex) in tags" :key="iIndex" v-show="iIndex <= 2">
          #{{ iItem.text }}
        </li>
      </ul>
    </article>
  </div>
</template>

<script>
export default {
  props: {
    imgURL_s: {
      type: String,
      default: '',
    },
    userMakeupKey: {
      type: String,
      default: '',
    },
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
  },
};
</script>
