<script>
import { Line } from 'vue-chartjs';

export default {
  extends: Line,
  mounted() {
    // Overwriting base render method with actual data.
    this.renderChart({
      labels: ['8/31', '9/7', '9/14', '9/21', '9/28', '10/5', '10/12', '10/19'],
      datasets: [
        {
          label: '貼文互動率',
          backgroundColor: '#ffafbe',
          data: [40, 40, 25, 39, 30, 40, 39, 60],
        },
      ],
    });
  },
};
</script>
