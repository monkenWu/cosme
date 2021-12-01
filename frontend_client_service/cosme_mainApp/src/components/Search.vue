<template>
  <label
    for="search"
    class="search-label mr-sm-5"
    :class="{ active: active === true }"
  >
    <slot name="searchInput">
      <input
        type="text"
        name="search"
        id="search"
        :placeholder="placeholderTxt"
        @focus="active = true"
        @blur="active = false"
      />
    </slot>
    <button
      class="btn-none"
      @click.stop="$emit('search-btn-click')"
      @focus="active = false"
    >
      <font-awesome-icon :icon="['fas', 'search']" />
    </button>
  </label>
</template>

<script>
export default {
  props: {
    placeholderTxt: String,
  },
  data() {
    return {
      active: false,
    };
  },
};
</script>
