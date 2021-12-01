<template>
  <main class="history mt-4">
    <div class="route-title animate__animated animate__faster animate__fadeIn" v-if="classData">
      <a href="#" @click.prevent="$router.go(-1)">
        <font-awesome-icon :icon="['fas', 'arrow-left']" style="color: #ffc39f" />
        回上一頁
      </a>
      <h3>{{ classData.listName }}</h3>
    </div>
    <ul class="row animate__animated animate__faster animate__fadeIn" v-if="list">
      <li class="col-xl-3 col-lg-4 col-md-4 col-sm-6 mb-4"
       v-for="(item, index) in list" :key="index">
        <div class="item">
          <router-link tag="div" :to="`/history/detail/${item.productID}`" class="picture">
            <figure>
              <img :src="item.imgURL_s" alt="上妝照片">
            </figure>
            <h2 class="brand-name">{{ item.com }}</h2>
          </router-link>
          <div class="text-con">
            <router-link tag="h3" to="/">{{ item.productName }}</router-link>
            <div class="btn-con">
              <button class="btn-none download">
                <font-awesome-icon :icon="['fas', 'download']" />
                下載圖像
              </button>
              <button class="else-btn-list btn-none"
              @blur="dom.elseActive = null"
              @click="dom.elseActive = index">
                <font-awesome-icon :icon="['fas', 'ellipsis-h']" />
                <ol v-if="dom.elseActive === index">
                  <li @click.stop="">從清單中移除</li>
                  <li @click.stop="">分享相片</li>
                  <li @click.stop="">刪除相片</li>
                </ol>
              </button>
            </div>
          </div>
        </div>
      </li>
    </ul>
  </main>
</template>

<script>
export default {
  data() {
    return {
      list: null,
      classData: null,
      dom: {
        elseActive: null,
      },
    };
  },
  created() {
    this.list = [
      {
        key: 'aaa111',
        productID: 'b111',
        modelIMG: 'img111',
        com: 'KATE',
        comURL: 'https://www.kate-global.net/tw/',
        productName: '同調六色眼彩盤',
        productURL: 'https://www.kate-global.net/tw/pickup/tone_dimensional_palette/',
        // 需要另外補上的
        stars: 0,
        date: '2020/07/12',
        // 另外讀取的
        imgURL_s: 'https://i.imgur.com/nYrMAsh.jpg',
        imgURL_L: 'https://i.imgur.com/JRmkwaJ.jpg',
      }, {
        key: 'aaa222',
        productID: 'b222',
        modelIMG: 'img222',
        com: 'Maybelline',
        comURL: 'https://www.maybelline.com.tw/',
        productName: '超持久霧感液態唇膏 (RED)',
        productURL: 'https://www.maybelline.com.tw/lip/lip-polish/ssmi-rogue-red',
        // 需要另外補上的
        stars: 0,
        colorID: 'RD006',
        date: '2020/07/12',
        // 另外讀取的
        imgURL_s: 'https://i.imgur.com/3EIOVrk.jpg',
        imgURL_L: 'https://i.imgur.com/kSjsxuy.jpg',
      }, {
        key: 'aaa333',
        productID: 'b333',
        modelIMG: 'img333',
        com: 'Maybelline',
        comURL: 'https://www.maybelline.com.tw/',
        productName: '超持久霧感液態唇膏 (RED)',
        productURL: 'https://www.maybelline.com.tw/lip/lip-polish/ssmi-rogue-red',
        // 需要另外補上的
        stars: 0,
        colorID: 'RD006',
        date: '2020/07/12',
        // 另外讀取的
        imgURL_s: 'https://i.imgur.com/pHcrKCS.jpg',
        imgURL_L: 'https://i.imgur.com/smT4EeP.jpg',
      }, {
        key: 'aaa444',
        productID: 'b444',
        modelIMG: 'img444',
        com: 'Maybelline',
        comURL: 'https://www.maybelline.com.tw/',
        productName: '超持久霧感液態唇膏 (RED)',
        productURL: 'https://www.maybelline.com.tw/lip/lip-polish/ssmi-rogue-red',
        // 需要另外補上的
        stars: 0,
        colorID: 'RD006',
        date: '2020/07/12',
        // 另外讀取的
        imgURL_s: 'https://i.imgur.com/5CG0ibp.jpg',
        imgURL_L: 'https://i.imgur.com/e66v4G4.jpg',
      },
    ];
    this.classData = {
      listID: this.$route.params.id,
      listName: '清單一號',
      listLength: 4,
      creatTime: '2020/07/28',
    };
  },
};
</script>
