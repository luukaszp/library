<template>
  <div>
      <apexchart ref="realtimeChart" type="bar" :options="options" :series="series" id="chart"></apexchart>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'RatingsAmount',
  props: ['user_id'],
  data() {
    return {
      borrows: [],
      options: {
        xaxis: {
          categories: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec',
            'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień']
        },
        title: {
          text: 'Zestawienie ocen',
          align: 'center',
          floating: true
        },
        subtitle: {
          text: 'Ilość ocenionych książek w danym miesiącu',
          align: 'center'
        },
        chart: {
          toolbar: { show: false }
        }
      },
      series: [{
        name: 'Ilość ocen',
        data: []
      }]
    };
  },

  created () {
    axios
      .get(`/api/rating/ratingsAmount/${this.user_id}`)
      .then((response) => {
        for (let i = 1; i <= 12; i++) {
          this.series[0].data.push(response.data[i]);
        }
        this.updateSeriesLine();
      });
  },

  methods: {
    updateSeriesLine () {
      this.$refs.realtimeChart.updateSeries([{
        data: this.series[0].data
      }], false, true);
    }
  }
};
</script>
<style scoped>
    #chart {
        width: 800;
    }
@media only screen and (max-width: 600px) {
    #chart {
        width: 400;
    }
}
</style>
