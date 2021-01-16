<template>
    <v-container fluid>
        <v-card
            max-width="575"
            class="mx-auto"
        >
            <v-card-title style="justify-content: center">Ranking czytelników na miesiąc '<strong>{{ new Date().toLocaleString('default', { month: 'long' }) }} </strong>'</v-card-title>
            <v-img
            :src="require('../assets/ranking/winners_podium.jpg')"
            height="300px"
            dark
            >
            </v-img>
            <v-divider></v-divider>
            <v-list>
            <v-subheader style="justify-content: space-between"><p>Pozycja</p><p>Ilość wypożyczeń</p></v-subheader>
                <v-list-item-group>
                    <template v-for="(item, index) in borrows">
                        <v-list-item :key="item.id">
                            <v-list-item-icon v-if="item.status === 'up'">
                                <v-icon color="green">mdi-arrow-up-bold</v-icon>
                                <strong style="margin-top: 3px; margin-left: 5px" v-text="item.position"></strong>
                            </v-list-item-icon>

                            <v-list-item-icon v-if="item.status === 'down'">
                                <v-icon color="red">mdi-arrow-down-bold</v-icon>
                                <strong style="margin-top: 3px; margin-left: 5px" v-text="item.position"></strong>
                            </v-list-item-icon>

                            <v-list-item-icon v-if="item.status === 'same'">
                                <v-icon color="blue">mdi-minus</v-icon>
                                <strong style="margin-top: 3px; margin-left: 5px" v-text="item.position"></strong>
                            </v-list-item-icon>

                             <v-list-item-icon v-if="item.status === 'new'">
                                <v-icon color="yellow">mdi-star</v-icon>
                                <strong style="margin-top: 3px; margin-left: 5px" v-text="item.position"></strong>
                            </v-list-item-icon>

                            <v-list-item-avatar>
                                <v-img :src="'../storage/' + item.avatar"></v-img>
                            </v-list-item-avatar>

                            <v-list-item-content style="padding-left: 10px">
                                <v-list-item-title v-text="item.name + ' ' + item.surname"></v-list-item-title>
                                <v-list-item-subtitle v-html="'Miejsce '+ ++index"></v-list-item-subtitle>
                            </v-list-item-content>

                            <v-list-item-action>
                                <v-list-item-title v-text="item.count"></v-list-item-title>
                            </v-list-item-action>
                        </v-list-item>
                    </template>
                </v-list-item-group>
            </v-list>
        </v-card>
    </v-container>
</template>

<script>
export default {
  data: () => ({

  }),

  computed: {
    borrows() {
      return this.$store.getters.getBorrows;
    }
  },

  created () {
    this.$store.dispatch('fetchMonth', {});
  }
};
</script>