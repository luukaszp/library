<template>
    <div>
        <apexchart ref="realtimeChart" type="pie" :options="options" :series="series" id="chart"></apexchart>
    </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'FavoriteAuthor',
  props: ['user_id'],
  data() {
    return {
      borrows: [],
      options: {
        title: {
          text: 'Ulubiony autor',
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
              width: 200
            },
            legend: {
              position: 'right',
              offsetY: 0,
              height: 230
            }
          }
        }]
      },
      series: []
    };
  },

  created () {
    axios
      .get(`/api/borrow/getAuthors/${this.user_id}`)
      .then((response) => {
        for (let i = 0; i < response.data.length; i++) {
          this.options.labels[i] = `${response.data[i].name} ${response.data[i].surname}`;
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
<style>
@media only screen and (max-width: 600px) {
    #chart {
        max-width: 300px;
    }
}
</style>
