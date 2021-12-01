<template>
  <main class="mt-4 authorize">
    <div class="route-title animate__animated animate__faster animate__fadeIn">
      <a href="#" @click.prevent="$router.go(-1)">
        <font-awesome-icon :icon="['fas', 'arrow-left']" style="color: #ffc39f" />
        回上一頁
      </a>
      <h3>撤銷授權名單</h3>
    </div>
    <section class="animate__animated animate__faster animate__fadeIn">
      <div class="title-menu px-md-4 mb-2">
        <Search />
        <SortButton />
      </div>
      <table class="table authorize-table">
        <thead class="thead-primary">
          <tr>
            <th scope="col">#</th>
            <th scope="col">品牌商標</th>
            <th scope="col">品牌名稱</th>
            <th scope="col">撤銷日期</th>
            <th scope="col">電商資訊</th>
            <th scope="col">重新授權</th>
          </tr>
        </thead>
        <tbody v-if="list">
          <tr v-for="(item, index) in list" :key="index">
            <th scope="row" class="number text-secondary">
              {{ index + 1 }}
            </th>
            <td class="logo">
              <a :href="item.comURL" target="_blank" class="d-block">
                <img :src="item.comLogoURL" :alt="item.com" class="img-fluid">
              </a>
            </td>
            <td class="com">
              <a :href="item.comURL" target="_blank">{{ item.com }}</a>
            </td>
            <td class="date">
              {{ item.date }}
            </td>
            <td class="comBio">
              {{ item.comBio.slice(0,40) }}
              <span v-if="item.comBio.length >= 40">...</span>
            </td>
            <td class="cancel text-center">
              <button class="btn btn-outline-success btn-sm">
                授權
              </button>
            </td>
          </tr>
        </tbody>
      </table>
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
    };
  },
  components: {
    SortButton, Search,
  },
  created() {
    this.list = [
      {
        comLogoID: 'aa123',
        com: 'Maybelline',
        comURL: 'https://www.maybelline.com.tw/',
        comBio: '來自紐約的MAYBELLINE媚比琳開架底妝提供最完整的底妝質地選擇，不管是霧面還是光澤透亮肌妝感，媚比琳都能滿足妳的需求、輕鬆打造清透無瑕完美妝容！',
        authorize: true,
        date: '2020/7/29',
        // 另外取得的圖片資料
        comLogoURL: 'https://www.maybelline.com.tw/~/media/MNY/Global/Header/logo_1',
      }, {
        comLogoID: 'aa124',
        com: 'MKUP美咖',
        comURL: 'https://www.mkup.tw/',
        comBio: '美妝,保養品,化妝,彩妝,洗臉,眉筆,洗面乳,化妝水.',
        authorize: true,
        date: '2019/11/29',
        // 另外取得的圖片資料
        comLogoURL: 'https://cms.cdn.91app.com/images/original/2202/48b0572c-b9ce-4b1b-915d-b57ce8a1bbee-1593576960-3p2yln8utt_d.png',
      }, {
        comLogoID: 'aa123',
        com: 'KATE',
        comURL: 'https://www.kate-global.net/tw/',
        comBio: 'Kanebo開架彩粧品牌【KATE】官方網站。提供新品情報、彩粧技巧、TVCM、代言人黑木梅紗情報、獨家桌布等相關資訊。',
        authorize: true,
        date: '2019/07/29',
        // 另外取得的圖片資料
        comLogoURL: 'https://kao-h.assetsadobe3.com/is/image/content/dam/sites/kanebo/www-kate-global-net/tw/cmn/cmn-logo-kate-s.png?fmt=png-alpha&wid=320',
      },
    ];
  },
};
</script>
