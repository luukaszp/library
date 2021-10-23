<template>
    <div>
        <apexchart ref="realtimeChart" type="pie" :options="options" :series="series" id="chart"></apexchart>
    </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'FavoriteCategory',
  props: ['user_id'],
  data() {
    return {
      borrows: [],
      options: {
        title: {
          text: 'Preferowana kategoria',
          align: 'right',
          floating: true
        },
        legend: {
          position: 'right',
          offsetY: 80
        },
        chart: {
          toolbar: { show: false },
          width: 500,
          type: 'pie'
        },
        labels: [],
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 350
            },
            legend: {
              position: 'right',
              offsetY: 20,
              height: 230
            },
            plotOptions: {
                pie: {
                    offsetY: 20,
                }
            },
          }
        }]
      },
      series: []
    };
  },

  created () {
    axios
      .get(`/api/borrow/getCategory/${this.user_id}`)
      .then((response) => {
        for (let i = 0; i < response.data.length; i++) {
          this.options.labels[i] = `${response.data[i].name}`;
          this.series[i] = parseInt(response.data[i].count, 10);
        }
        this.updateSeriesLine();
      });
  },

  methods: {
    updateSeriesLine () {
      this.$refs.realtimeChart.updateSeries([{
        data: this.series
      }], false, true);
    }
  }
};
</script>
<style scoped>
    #chart {
        width: 600px;
    }
@media only screen and (max-width: 600px) {

}
</style>
