<template>
  <div>
      <apexchart ref="realtimeChart" width="800" type="bar" :options="options" :series="series"></apexchart>
  </div>
</template>

<script>
export default {
  name: 'BorrowsAmount',
  props: ['user_id'],
  data() {
    return {
      options: {
        xaxis: {
          categories: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec',
            'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień']
          // categories: ['STY', 'LUT', 'MAR', 'KWI', 'MAJ', 'CZE', 'LIP', 'SIE', 'WRZ', 'PAŹ', 'LIS', 'GRU']
        },
        title: {
          text: 'Zestawienie wypożyczeń',
          align: 'center',
          floating: true
        },
        subtitle: {
          text: 'Ilość wypożyczeń w danym miesiącu',
          align: 'center'
        },
        chart: {
          toolbar: { show: false }
        }
      },
      series: [{
        name: 'borrowsAmount',
        data: []
      }]
    };
  },

  computed: {
    borrows() {
      return this.$store.getters.getBorrows;
    }
  },

  created () {
    this.$store.dispatch('fetchBorrowAmount', this.user_id);
  },

  mounted () {
    this.updateChart();
  },

  methods: {
    updateChart() {
      for (let i = 1; i <= 12; i++) {
        this.series[0].data.push(this.borrows[i]);
      }
      this.updateSeriesLine();
    },

    updateSeriesLine () {
      this.$refs.realtimeChart.updateSeries([{
        data: this.series[0].data
      }], false, true);
    }
  }
};
</script>