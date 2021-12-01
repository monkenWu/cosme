<template>
  <div class="modal-component animate__animated animate__faster animate__fadeIn">
    <div class="content">
      <a @click.prevent="$emit('close-modal')" href="#" class="close-btn">
        <font-awesome-icon :icon="['fas', 'times']" />
      </a>
      <slot></slot>
    </div>
    <span class="bg"  @click.prevent="$emit('close-modal')"/>
  </div>
</template>

<script>
export default {
  name: 'Crop',
};
</script>

<style lang="scss" scoped>
.modal-component{
  z-index: 200;
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
  .bg{
    display: block;
    position: absolute;
    width: 100%;
    height: 100%;
  }
}
</style>
