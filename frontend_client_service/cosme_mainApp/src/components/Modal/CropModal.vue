<template>
  <div class="modal-component animate__animated animate__faster animate__fadeIn">
    <div class="content">
      <a @click.prevent="$router.go(-1)" href="#" class="close-btn">
        <font-awesome-icon :icon="['fas', 'times']" />
      </a>
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Crop',
};
</script>

<style lang="scss" scoped>
.modal-component{
  overflow-y: auto;
  overflow-x: hidden;
  .content{
    padding: 10px;
    border-radius: 5px;
    @media (min-width: 768px){
      width: 600px;
      max-width: 90%;
      flex-direction: column;
      margin: 50px 0;
      // .js-clipper-basic{
      //   max-width: 90%;
      //   margin: 30px 5%;
      // }
    }
    @media (max-width: 767px){
      align-self: center;
    }
  }
}
</style>
