<template>
  <v-container fluid>
      <v-row>
        <v-col
          v-for="card in cards"
          :key="card.title"
          :cols="card.flex"
        >
          <v-card>
            <v-img
              :src="card.src"
              class="white--text align-end"
              gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
              height="400px"
            >
              <v-card-title v-text="card.title"></v-card-title>
            </v-img>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
</template>

<script>
/* eslint-disable */
export default {
  data: () => ({
    cards: [
      { title: 'Kalendarz wydarzeń', src: require('../assets/main/calendar.jpg'), flex: 6 },
      { title: 'Przeglądaj książki', src: require('../assets/main/books.jpg'), flex: 6 },
      { title: 'Nowe pozycje', src: require('../assets/main/books_new.jpg'), flex: 6 },
      { title: 'Wyszukaj w katalogu', src: 'https://cdn.vuetifyjs.com/images/cards/house.jpg', flex: 6 },
      { title: 'Favorite road trips', src: 'https://cdn.vuetifyjs.com/images/cards/road.jpg', flex: 6 },
      { title: 'Best airlines', src: 'https://cdn.vuetifyjs.com/images/cards/plane.jpg', flex: 6 }
    ]
  })
};
</script>