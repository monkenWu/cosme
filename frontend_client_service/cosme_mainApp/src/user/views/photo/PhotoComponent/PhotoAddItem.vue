<template>
  <div>
    <figure v-if="!onload" class="picture" :style="{backgroundImage: `url(${img})`}">
      <div class="hover-content">
        <article>
          <button class="btn-none h3 text-white p-3" @click="$emit('open-detail')">
            <font-awesome-icon :icon="['fas', 'search']" />
          </button>
        </article>
      </div>
      <img src="@/assets/img/transparent.png" width="100%" />
    </figure>
    <article v-if="!onload" class="text">
      <div class="d-flex justify-content-between small mt-2">
        <a href="#" @click.prevent="$emit('delete',index)" class="text-secondary">
          <font-awesome-icon :icon="['fas', 'trash-alt']" />刪除相片
        </a>
        <a href="#" @click.prevent="$emit('re-crop',index)" class="text-secondary">
          <font-awesome-icon :icon="['fas', 'crop-alt']" />重新裁切
        </a>
        <slot name="other-btn"></slot>
      </div>
    </article>
    <img v-if="onload" src="@/assets/img/loading.svg" height="70" />
  </div>
</template>

<script>
export default {
  props: {
    onload: {
      type: Boolean,
      default: false,
    },
    index: Number,
    img: String,
  },
};
</script>
