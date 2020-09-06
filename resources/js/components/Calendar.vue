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
                        color="purple"
                        :events="getEvents"
                        :event-color="getEventColor"
                        :interval-count = 0
                        @change="updateRange"
                        @click:more="viewDay"
                        @click:date="viewDay"
                ></v-calendar>
                <v-toolbar>
                    <h1 class="headline mr-10">Legenda</h1>
                    <v-chip class="mr-6" v-for="value in legendInfo()" v-bind:key="value.id" :color="value.color" style="font-weight: bold" @click="showEvent(value)">{{value.type}}</v-chip>
                    <v-chip class="mr-6" color="#B5651D" style="font-weight: bold" @click="allEvents()">Wszystkie</v-chip>
                </v-toolbar>
            </v-sheet>
        </v-col>

        <v-dialog v-model="dailyModal" max-width="500">
            <v-sheet
                class="mx-auto"
                elevation="8"
                max-width="500"
                style="text-align: center"
            >
                <v-carousel
                cycle
                class="pa-8"
                hide-delimiter-background
                show-arrows
                height="300"
                >
                <v-carousel-item
                    v-for="value in eventInfo()"
                    :key="value.id"
                >
                    <v-card
                    :color="value.color"
                    class="ma-4"
                    width="405"
                    >
                    <v-row
                        align="center"
                        justify="center"
                    >
                        <p style="padding-top: 5px;">{{value.type}}</p>
                    </v-row>
                    <v-row
                        align="center"
                        justify="center"
                    >
                        <h3 style="font-weight: bold; line-height: 4.8">{{value.name}}</h3>
                    </v-row>
                    <v-row
                        align="center"
                        justify="center"
                    >
                        <span>Serdecznie zapraszamy na godzinę {{value.time}}!</span>
                    </v-row>
                    </v-card>
                </v-carousel-item>
                </v-carousel>
            </v-sheet>
        </v-dialog>
    </v-row>
</template>

<script>
export default {
  name: 'Calendar',
  data: () => ({
    type: 'month',
    focus: '',
    dailyModal: false,
    selectedElement: null,
    selectedOpen: false,
    start: null,
    merged: '',
    eventColor: '',
    weekday: [1, 2, 3, 4, 5, 6, 0],
    libraryEvent: [],
    librarySelected: [],
    colors: ['blue', 'green', 'orange', 'grey darken-1', 'red', 'yellow', 'pink']
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
    },
    events() {
      return this.$store.getters.getEvents;
    },
    types() {
      return this.$store.getters.getTypes;
    },
    getEvents() { // change computed with method
      // eslint-disable-next-line vue/no-side-effects-in-computed-properties
      this.libraryEvent = [];
      for (let i = 0; i < this.events.length; i++) {
        for (let j = 0; j < this.types.length; j++) {
          if (this.events[i].typeName === this.types[j].name) {
            // eslint-disable-next-line vue/no-side-effects-in-computed-properties
            this.eventColor = this.colors[j];
          }
        }
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        this.libraryEvent.push({
          name: `${this.events[i].time} - ${this.events[i].name}`,
          start: this.events[i].date,
          color: this.eventColor
        });
      }
      if (this.librarySelected.length > 0) {
        return this.librarySelected;
      }
      return this.libraryEvent;
    }
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
    },
    eventInfo () {
      const daily = [];
      for (let i = 0; i < this.events.length; i++) {
        if (this.events[i].date === this.focus) {
          daily.push({
            name: this.events[i].name,
            type: this.events[i].typeName,
            time: this.events[i].time,
            color: this.libraryEvent[i].color
          });
        }
      }
      return daily;
    },
    legendInfo () {
      const legend = [];
      let color = [];
      let type = [];
      for (let i = 0; i < this.events.length; i++) {
        color[i] = this.libraryEvent[i].color;
        type[i] = this.events[i].typeName;
      }

      color = Array.from(new Set(color));
      type = Array.from(new Set(type));

      for (let i = 0; i < color.length; i++) {
        legend.push({
          type: type[i],
          color: color[i]
        });
      }
      return legend;
    },
    showEvent (value) {
      this.librarySelected = [];
      for (let i = 0; i < this.libraryEvent.length; i++) {
        if (this.libraryEvent[i].color === value.color) {
          this.librarySelected.push({
            name: `${this.events[i].time} - ${this.events[i].name}`,
            start: this.events[i].date,
            color: this.libraryEvent[i].color
          });
        }
      }
      return this.librarySelected;
    },
    allEvents() {
      this.librarySelected = [];
      for (let i = 0; i < this.events.length; i++) {
        for (let j = 0; j < this.types.length; j++) {
          if (this.events[i].typeName === this.types[j].name) {
            // eslint-disable-next-line vue/no-side-effects-in-computed-properties
            this.eventColor = this.colors[j];
          }
        }
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        this.librarySelected.push({
          name: `${this.events[i].time} - ${this.events[i].name}`,
          start: this.events[i].date,
          color: this.eventColor
        });
      }
      return this.librarySelected;
    }
  }
};
</script>

<style scoped>

</style>