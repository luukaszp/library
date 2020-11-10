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
              <v-btn outlined style="border: 0px; text-decoration: none" :to="card.route"><v-card-title style="color: white; font-weight: bold" v-text="card.title"></v-card-title></v-btn>
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
      { title: 'Kalendarz wydarzeń', src: require('../assets/main/calendar.jpg'), route: '/calendar', flex: 4 },
      { title: 'Przeglądaj książki', src: require('../assets/main/books.jpg'), route: '/search', flex: 4 },
      { title: 'Nowe pozycje', src: require('../assets/main/books_new.jpg'), route: '/new', flex: 4 },
      { title: 'Wyszukaj w katalogu', src: require('../assets/main/search_book.jpg'), route: '/catalog', flex: 4 },
      { title: 'Ranking czytelników', src: require('../assets/main/ranking_podium.jpg'), route: '/ranking', flex: 4 }
    ]
  })
};
</script>