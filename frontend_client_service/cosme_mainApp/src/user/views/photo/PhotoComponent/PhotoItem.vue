<template>
  <div>
    <figure class="picture">
      <h4 v-if="isDefault">
        <font-awesome-icon :icon="['fas', 'check-circle']" />
      </h4>
      <div class="hover-content">
        <article>
          <a
            href="#"
            tag="button"
            class="btn btn-outline-secondary btn-sm mb-2"
            @click.prevent="$emit('to-detail')"
          >
            <font-awesome-icon :icon="['fas', 'search']" />查看相片
          </a>

          <!-- </router-link> -->
          <button @click="$emit('delete')" class="btn btn-outline-secondary btn-sm">
            <font-awesome-icon :icon="['fas', 'trash-alt']" />刪除相片
          </button>
        </article>
      </div>
      <img :src="img" class="img-fluid" />
    </figure>
    <article class="text">
      <div class="box default" v-if="isDefault">
        <div class="icon">
          <font-awesome-icon :icon="['far', 'circle']" />
          <font-awesome-icon :icon="['fas', 'check']" />
        </div>已設為預覽圖
      </div>
      <div class="box" v-else>
        <div class="icon">
          <font-awesome-icon :icon="['far', 'circle']" />
        </div>未設為預覽圖
      </div>
    </article>
  </div>
</template>

<script>
export default {
  props: {
    isDefault: {
      type: Boolean,
      default: false,
    },
    img: String,
  },
};
</script>
