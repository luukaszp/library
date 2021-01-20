<template>
    <div>
        <apexchart ref="realtimeChart" type="pie" width="600" :options="options" :series="series"></apexchart>
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