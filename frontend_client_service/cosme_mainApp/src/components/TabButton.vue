<template>
  <label class="btn-box" @click="$emit('btn-click')">
    <hr :style="{background: color}" />
    <div class="icon">
      <font-awesome-icon :icon="fwiIcon" :style="{color: color}" />
    </div>
    <div class="text">
      <h3>{{title}}</h3>
      <h4 :style="{color: subtitleColor}">{{subtitle}}</h4>
    </div>
    <slot></slot>
  </label>
</template>

<script>

export default {
  name: 'TabButton',
  props: {
    title: String,
    subtitle: String,
    fwiIcon: Array,
    color: String,
    subtitleColor: {
      type: String,
      default() {
        return this.color;
      },
    },
  },
};
</script>

<style lang="scss">
.btn-box{
  min-width: 215px;
  position: relative;
  background-color: #f6f6f6;
  border-radius: 10px;
  display: flex;
  height: 80px;
  overflow: hidden;
  cursor: pointer;
  transition: all 0.2s ease-in-out;
  margin-right: 35px;
  hr{
    margin: 0;
    position: absolute;
    width: 7px;
    left: 0;
    height: 100%;
    top: 0;
  }
  .icon{
    width: 75px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
    margin-left: 7px;
    svg{
      transition: all 0.2s ease-in-out;
    }
  }
  .text{
    flex-grow: 1;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    flex-direction: column;
    h3{
      margin-bottom: 8px;
      font-weight: bold;
      color: #777;
      white-space: nowrap;
    }
    h4 {
      font-weight: bold;
      white-space: nowrap;
      font-size: 14px;
    }
  }
  &:hover{
    // transform: scale(.9, .9);
    filter:drop-shadow(1px 1px 3px rgba(#000, .2));
    background-color: #f9f9f9;
  }
  @media (max-width: 767px){
    margin-right: 20px;
  }
  input[type=file] {
    z-index: -1;
    position: absolute;
    right: 0;
    width: 0px;
  }
}
</style>
