<template>
    <v-container>
        <v-row style="margin-left: 10px; margin-right: 10px; justify-content: center;">
            <div class="d-flex justify-center align-center">
                <v-menu
                v-for="item in items"
                :key="item.title"
                offset-y
                >
                    <template v-slot:activator="{ attrs, on }">
                        <v-btn
                        color="indigo"
                        outlined
                        class="white--text ma-1"
                        v-bind="attrs"
                        v-on="on"
                        :to="item.route"
                        >
                        {{ item.title }}
                        </v-btn>
                    </template>
                </v-menu>
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
            route: `/profile/${this.user_id}/borrows`
        },
        {
            title: 'KARY I OPŁATY',
            route: `/profile/${this.user_id}/delays`
        },
        {
            title: 'STATYSTYKI',
            route: `/profile/${this.user_id}/statistics`
        },
        {
            title: 'UTWORZONE LISTY',
            route: `/profile/${this.user_id}/lists`
        },
        {
            title: 'ZAPROPONUJ',
            route: `/profile/${this.user_id}/suggestions`
        },
        {
            title: 'ANKIETY',
            route: `/profile/${this.user_id}/questionnaires`
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