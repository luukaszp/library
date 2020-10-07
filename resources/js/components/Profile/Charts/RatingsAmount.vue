<template>
  <div>
      <apexchart ref="realtimeChart" width="800" type="bar" :options="options" :series="series"></apexchart>
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
        name: 'ratingsAmount',
        data: []
      }]
    };
  },

  created () {
    axios
      .get(`api/rating/ratingsAmount/${this.user_id}`)
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