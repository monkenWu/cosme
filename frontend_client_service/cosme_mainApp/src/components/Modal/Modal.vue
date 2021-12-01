<template>
  <div class="modal-component animate__animated animate__faster animate__fadeIn">
    <!-- <div class="content" v-if="ModalImg"> -->
    <div class="content">
      <a @click.prevent="$router.go(-1)" href="#" class="close-btn">
        <font-awesome-icon :icon="['fas', 'times']" />
      </a>
      <figure :style="{'background-image': `url(${ModalImg})`}">
        <img src="~@/assets/img/transparent.png" class="img-fluid" />
      </figure>
      <article>
        <slot></slot>
      </article>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Modal',
  props: {
    ModalImg: String,
  },
};
</script>
