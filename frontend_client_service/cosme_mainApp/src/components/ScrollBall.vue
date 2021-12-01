<template>
  <button class="top-btn" @click="scrollTo('header')"
    :class="{active: activeVal}">
    <font-awesome-icon :icon="['fas', 'arrow-up']" />
  </button>
</template>

<script>
export default {
  props: ['activeVal'],
  methods: {
    scrollTo(val) {
      this.$bus.$emit('scroll:top', val);
    },
  },
};
</script>

<style lang="scss" scoped>
.top-btn{
  position: fixed;
  background-color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
  border-radius: 50%;
  opacity: 0;
  color: #a0ace6;
  border: 1px solid #74ebd5;
  z-index: -1;
  transition: all 0.2s cubic-bezier(0.67, -0.23, 0.78, 2.42);
  transform: translateY(20px);
  svg{
    transition: all 0.2s ease-in-out;
  }
  &:focus {
    outline: none;
    color: #ffafbe;
  }
  &::before{
    content: '';
    width: 130%;
    height: 130%;
    position: absolute;
    border-radius: 50%;
    box-shadow: 1px 1px 5px rgba(#000, .3);
  }
  &:hover {
    svg{
      transform: scale(1.3, 1.3);
    }
  }
  &.active{
    opacity: 1;
    transform: translateY(0px);
    z-index: 0;
  }
  @media (max-width: 991px) {
    bottom: 30px;
    right: 20px;
    height: 30px;
    width: 30px;
  }
  @media (min-width: 991px) {
    bottom: calc(10vh + 50px);
    right: calc(10vw + 20px);
    height: 40px;
    width: 40px;
  }
}
</style>
