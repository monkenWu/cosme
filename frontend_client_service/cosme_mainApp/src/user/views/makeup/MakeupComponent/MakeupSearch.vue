<template>
  <section class="mb-4">
    <section class="search-item row justify-content-center mb-2">
      <section class="col-12 col-md-12">
        <Search
        style="margin-right: 0 !important"
        class="w-100"
        :active="true"
         @search-btn-click="$emit('search', searchTxt)">
          <input
            slot="searchInput"
            ref="searchInput"
            type="text"
            name="search"
            id="search"
            class="w-100 px-3"
            placeholder="搜尋妝容..."
            v-model="searchTxt"
            @keydown.down="doChooseTag(1)"
            @keydown.up="doChooseTag(-1)"
            @keyup.enter="doSearch"
            @focus="isFocusOnSearchInput = true"
            @blur="isFocusOnSearchInput = false"
          />
        </Search>
        <transition name="slide-fade">
          <ul v-if="isFocusOnSearchInput" class="tag-list col-6">
            <transition-group name="slide-fade">
              <li
                v-for="(item, index) in tagList"
                :key="index"
                @click="searchByTag(item)"
                class="row"
                :class="{ choosed: chooseTagIndex == index }"
              >
                <article class="col-2">
                  <font-awesome-icon
                    :icon="['fas', 'search']"
                    class="icon mt-2"
                  />
                </article>
                <div class="tag-list-item py-2 col-8">{{ item }}</div>
              </li>
            </transition-group>
          </ul>
        </transition>
      </section>
    </section>
  </section>
</template>

<script>
/* eslint-disable import/no-unresolved */
import makeupService from '@/user/store/modules/service/makeupService.ts';
import Search from '@/components/Search.vue';

export default {
  components: {
    Search,
  },
  data() {
    return {
      searchTxt: '',
      chooseTagIndex: -1,
      tagList: [],
      isFocusOnSearchInput: false,
    };
  },
  methods: {
    doChooseTag(offset) {
      // taglist是空的
      if (this.tagList.length === 0) {
        this.chooseTagIndex = -1;
        return false;
      }
      // 向下選
      if (offset === 1) {
        // 選到最尾
        if (this.tagList.length - 1 === this.chooseTagIndex) {
          this.chooseTagIndex = 0;
          return true;
        }
        // 還沒選到最尾
        this.chooseTagIndex += 1;
      }
      // 向上選
      if (offset === -1) {
        // 選到第一個
        if (this.chooseTagIndex === 0) {
          this.chooseTagIndex = this.tagList.length - 1;
          return true;
        }
        // 還沒選到第一個
        this.chooseTagIndex -= 1;
      }
      return true;
    },
    searchByTag(tag) {
      this.searchTxt = tag;
      this.$emit('search', tag);
    },
    doSearch() {
      // 沒有選tag
      this.$refs.searchInput.blur();
      if (this.chooseTagIndex === -1) {
        this.$emit('search', this.searchTxt);
        return true;
      }
      // 有選 tag
      this.searchByTag(this.tagList[this.chooseTagIndex]);
      this.chooseTagIndex = -1;
      return true;
    },
  },
  watch: {
    searchTxt(value) {
      // 需要 input focus 才能使用
      if (
        value[0] !== '#'
        || value.length <= 1
        || this.isFocusOnSearchInput === false
      ) {
        this.tagList = [];
        return false;
      }
      makeupService.getLikeTags({ like: value.substring(1) }).then((res) => {
        this.tagList = res.map((item) => `#${item}`);
      });
      return true;
    },
  },
};
</script>

<style lang="scss" scoped>
.search-label::before{
  content: none;
}
.tag-list {
  position: absolute;
  z-index: 150;
  border-radius: 10px;
  box-shadow: gray 1px 1px 2px;
  li {
    background: white;
    color: rgba(51, 51, 51, 0.5);
    cursor: pointer;
    transition: 0.2s ease-in-out;
    &:hover {
      color: white;
      background: #8be7e3;
    }
    &.choosed {
      color: white;
      background-color: #8be7e3;
    }
  }
}

.slide-fade-enter-active {
  transition: all 0.3s ease;
}
.slide-fade-leave-active {
  transition: all 0.3s ease;
}
.slide-fade-enter, .slide-fade-leave-to
/* .slide-fade-leave-active for below version 2.1.8 */ {
  transform: translateY(-10px);
  opacity: 0;
}
</style>
