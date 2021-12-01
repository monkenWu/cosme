<template>
  <main class="analysis mt-4">
    <router-view />
    <div class="d-flex box-content mb-4">
      <TabButton
        title="關鍵字文字雲"
        subtitle="Keyword Cloud"
        color="#ffc39f"
        subtitleColor="#fea875"
        :fwiIcon="['fas', 'cloud']"
        @btn-click="views = 'wordCloud'"
      />
      <TabButton
        title="受眾分析"
        subtitle="TA Analysis"
        color="#a0ace6"
        subtitleColor="#a3a4d4"
        :fwiIcon="['fas', 'user-tag']"
        @btn-click="views = 'taDataChart'"
      />
      <TabButton
        title="互動變化"
        subtitle="Engagement"
        color="#74ebd5"
        subtitleColor="#54debd"
        :fwiIcon="['fas', 'chart-line']"
        @btn-click="views = 'engagementChart'"
      />
    </div>
    <div class="route-title">
      <h3>
        平台資料分析
        <button type="button" class="btn-none ml-1">
          <font-awesome-icon :icon="['far', 'question-circle']" />
        </button>
      </h3>
    </div>
    <VueWordCloud
      v-if="views == 'wordCloud'"
      class="mx-auto"
      :spacing="0.25"
      style="height: 430px; width: 70%"
      :words="cloudWords"
      :color="
        ([, weight]) =>
          weight > 10 ? '#ffafbe' : weight > 5 ? '#74ebd5' : '#a0ace6'
      "
      font-family="Microsoft JhengHei"
    />
    <TaDataChart v-if="views == 'taDataChart'" />
    <EngagementChart v-if="views == 'engagementChart'" />
  </main>
</template>

<script>
/* eslint-disable import/no-unresolved */
import VueWordCloud from 'vuewordcloud';
import TabButton from '@/components/TabButton.vue';
import TaDataChart from './AnalysisComponents/TaDataChart.vue';
import EngagementChart from './AnalysisComponents/EngagementChart.vue';

export default {
  components: {
    VueWordCloud,
    TabButton,
    TaDataChart,
    EngagementChart,
  },
  data() {
    return {
      views: 'wordCloud',
      cloudWords: [
        ['日系', 15],
        ['上班', 3],
        ['約會', 7],
        ['夜店', 4],
        ['眼妝', 8],
        ['性感', 7],
        ['可愛', 6],
        ['秋季', 8],
        ['網紅', 5],
        ['韓系', 12],
      ],
    };
  },
};
</script>

<style lang="scss" scoped>
</style>
