<template>
    <v-container>
        <v-row style="margin-left: 10px; margin-right: 10px">
            <h1>Avatar</h1>
            <h2>Imię i nazwisko</h2>
            <h3>Dane osobowe</h3>
        </v-row>

        <v-divider></v-divider>

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

        <v-row style="margin-left: 10px; margin-right: 10px">
            <router-view></router-view>
        </v-row>
    </v-container>
</template>

<script>
/*eslint-disable*/
export default {
  name: 'Profile',
  props: ['user_id'],
  
  data: () => ({
    items: [
      {
        title: 'WYPOŻYCZENIA',
        route: ''
      },
      {
        title: 'KARY I OPŁATY',
        route: ''
      },
      {
        title: 'STATYSTYKI',
        route: ''
      },
      {
        title: 'UTWORZONE LISTY',
        route: ''
      },
      {
        title: 'ZAPROPONUJ',
        route: ''
      },
      {
        title: 'ANKIETY',
        route: ''
      },
      {
        title: 'PRZEDŁUŻ TERMIN',
        route: ''
      }
    ]
  }),

  computed: {
    
  },

  created () {
    
  },

  methods: {
    
  }
};
</script>