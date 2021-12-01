<script>
import { Pie } from 'vue-chartjs';

export default {
  extends: Pie,
  mounted() {
    // Overwriting base render method with actual data.
    this.renderChart({
      labels: ['日系', '約會', '甜美', '可愛', '淡妝', '自然'],
      datasets: [
        {
          backgroundColor: ['#ffafbe', '#e6dee9', '#ffc39f', '#a0ace6', '#bdc2e8', '#74ebd5'],
          data: [40, 20, 12, 39, 10, 40],
        },
      ],
    });
  },
};
</script>
