<template>
    <v-row class="fill-height" style="margin-left: 10px; margin-right: 10px">
        <v-col>
            <v-sheet
                    tile
                    height="64"
                    color="grey lighten-3"
                    class="d-flex"
            >
                <v-toolbar flat color="white">
                <v-btn outlined class="mr-4" color="grey darken-2" @click="setToday">
                    Today
                </v-btn>
                <v-btn
                        icon
                        class="ma-2"
                        @click.native="$refs.calendar.prev()"
                >
                    <v-icon>mdi-chevron-left</v-icon>
                </v-btn>
                    <v-toolbar-title>{{title}}</v-toolbar-title>
                <v-btn
                        icon
                        class="ma-2"
                        @click.native="$refs.calendar.next()"
                >
                    <v-icon>mdi-chevron-right</v-icon>
                </v-btn>
                </v-toolbar>
            </v-sheet>
            <v-sheet height="600">
                <v-calendar
                        ref="calendar"
                        v-model="focus"
                        :weekdays="weekday"
                        type="month"
                        color="blue"
                        :events="getEvents"
                        :event-color="getEventColor"
                        :interval-count = 0
                        @change="updateRange"
                        @click:more="viewDay"
                        @click:date="viewDay"
                ></v-calendar>
            </v-sheet>
        </v-col>

        <v-dialog v-model="dailyModal" scrollable max-width="450">
            <v-card class="daily">

            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
export default {
  name: 'Calendar',
  data: () => ({
    type: 'month',
    focus: '',
    calendarDialog: false,
    dailyModal: false,
    selectedElement: null,
    selectedOpen: false,
    start: null,
    merged: '',
    weekday: [1, 2, 3, 4, 5, 6, 0],
    events: []
  }),
  computed: {
    title () {
      const { start } = this;
      if (!start) {
        return '';
      }
      const startMonth = this.monthFormatter(start);
      const startYear = start.year;
      return `${startMonth} ${startYear}`;
    },
    monthFormatter () {
      return this.$refs.calendar.getFormatter({
        timeZone: 'UTC', month: 'long'
      });
    }
    /* getEvents() {
      const events = [];
      for (let i = 0; i < this.incomes.length; i++) {
        events.push({
          name: `${this.incomes[i].categoryName} - ${this.incomes[i].amount}(${this.incomes[i].currency.shortName})`,
          start: this.incomes[i].timeStamp.slice(0, 10),
          color: '#9090ee'
        });
      }
      for (let i = 0; i < this.expenses.length; i++) {
        events.push({
          name: `${this.expenses[i].categoryName} - ${this.expenses[i].amount}(${this.expenses[i].currency.shortName})`,
          start: this.expenses[i].timeStamp.slice(0, 10),
          color: '#3eb4a7'
        });
      }
      return events;
    } */
  },
  methods: {
    getEventColor (events) {
      return events.color;
    },
    setToday () {
      this.focus = this.today;
    },
    updateRange({ start, end }) {
      this.start = start;
      this.end = end;
    },
    viewDay ({ date }) {
      this.focus = date;
      this.dailyModal = true;
    }
  }
};
</script>

<style scoped>
    .daily {
    text-align: center;
    padding-top: 10px;
    font-weight: bold;
    height: 500px;
    }
</style>