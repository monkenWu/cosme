<template>
  <main class="mt-4">
    <div class="route-title animate__animated animate__faster animate__fadeIn">
      <a href="#" @click.prevent="$router.go(-1)">
        <font-awesome-icon :icon="['fas', 'arrow-left']" style="color: #ffc39f" />
        回上一頁
      </a>
      <h3>分類清單</h3>
    </div>
    <section class="class-content animate__animated animate__faster animate__fadeIn">
      <div class="title-menu px-md-4 mb-2">
        <Search />
        <SortButton />
      </div>
      <ul class="class-list mb-5" v-if="list">
        <li v-for="(item, index) in list" :key="index" class="list-item">
          <router-link class="picture" :to="`/history/list/${item.listID}`" tag="div">
            <figure class="four-picture" v-for="(imgs, in_index) in item.fourIMG" :key="in_index">
              <img :src="imgs" :alt="`清單${index}圖片`" v-if="imgs">
              <span class="no_pic" v-else>
                <img src="~@/assets/img/transparent.png" class="img-fluid" />
              </span>
            </figure>
          </router-link>
          <article class="text-article">
            <router-link class="title" :to="`/history/list/${item.listID}`">{{ item.listName }}</router-link>
            <p>{{ item.listLength }} 張照片</p>
          </article>
          <button class="else-btn-list btn-none"
          @blur="dom.elseActive = null"
          @click="dom.elseActive = index">
            <font-awesome-icon :icon="['fas', 'ellipsis-v']" />
            <ol v-if="dom.elseActive === index">
              <li @click.stop="">新增清單</li>
              <li @click.stop="">編輯清單</li>
              <li @click.stop="">分享清單</li>
              <li @click.stop="">刪除清單</li>
            </ol>
          </button>
        </li>
      </ul>
    </section>
  </main>
</template>

<script>
// eslint-disable-next-line import/no-unresolved
import SortButton from '@/components/SortButton.vue';
// eslint-disable-next-line import/no-unresolved
import Search from '@/components/Search.vue';

export default {
  data() {
    return {
      list: null,
      dom: {
        searchActive: false,
        elseActive: null,
      },
    };
  },
  components: {
    SortButton, Search,
  },
  created() {
    this.list = [
      {
        fourIMG: [
          'https://i.imgur.com/nYrMAsh.jpg',
          'https://i.imgur.com/3EIOVrk.jpg',
          'https://i.imgur.com/pHcrKCS.jpg',
          'https://i.imgur.com/5CG0ibp.jpg',
        ],
        listID: '123456',
        listName: '清單一號',
        listLength: 4,
        creatTime: '2020/07/28',
      }, {
        fourIMG: [
          'https://i.imgur.com/N7Dnp4Q.jpg',
          'https://i.imgur.com/GbrRQks.jpg',
          '',
          '', // 如果清單不到四張圖片，剩下的回傳空字串
        ],
        listID: '22345678',
        listName: '長江七號',
        listLength: 2,
        creatTime: '2020/07/27',
      },
    ];
  },
};
</script>
