<template>
    <v-container>
        <v-row style="margin-left: 10px; margin-right: 10px; justify-content: center; padding-bottom: 10px">
            <div class="d-flex justify-center align-center">
                <v-tabs
                    color="deep-purple accent-4"
                    icons-and-text
                    centered
                >
                        <v-tab
                        color="indigo"
                        v-for="item in items"
                        :key="item.title"
                        :to="item.route"
                        style="text-decoration: none"
                        >
                        {{ item.title }}
                        <v-icon>{{ item.icon }}</v-icon>
                        </v-tab>
                </v-tabs>
            </div>
        </v-row>

        <v-divider></v-divider>

        <v-row style="justify-content: center">
            <router-view></router-view>
        </v-row>
    </v-container>
</template>

<script>
/*eslint-disable*/
export default {
  name: 'ProfileTabs',
  props: ['user_id'],
  
  data() {
    return {
        items: [
        {
            title: 'WYPOŻYCZENIA',
            route: `/profile/${this.user_id}/borrows`,
            icon: 'mdi-book-multiple'
        },
        {
            title: 'KARY I OPŁATY',
            route: `/profile/${this.user_id}/delays`,
            icon: 'mdi-cash-minus'
        },
        {
            title: 'STATYSTYKI',
            route: `/profile/${this.user_id}/statistics`,
            icon: 'mdi-chart-bar'
        },
        {
            title: 'ULUBIONE',
            route: `/profile/${this.user_id}/favourites`,
            icon: 'mdi-heart'
        },
        {
            title: 'ZASUGERUJ',
            route: `/profile/${this.user_id}/suggestions`,
            icon: 'mdi-lightbulb-on'
        },
        {
            title: 'ANKIETY',
            route: `/profile/${this.user_id}/questionnaires`,
            icon: 'mdi-comment-question-outline'
        }
        ]
  }},

  computed: {
    readers() {
      return this.$store.getters.getReaders;
    }
  },

  created () {
    this.$store.dispatch('fetchOneReader', this.user_id);
  },

  methods: {
    
  }
};
</script>