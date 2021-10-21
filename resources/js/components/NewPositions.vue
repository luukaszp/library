<template>
    <v-container style="text-align: center">
        <v-row style="text-align: center; justify-content: center; padding-bottom: 10px; padding-top: 20px;">
            <h1>Nowości książkowe</h1>
        </v-row>
        <v-row style="justify-content: center; text-align: center" v-if="books.length !=  0">
            <v-col
                md="6"
            >
                <swiper ref="mySwiper" :options="swiperOptions">
                    <swiper-slide
                        v-for="book in books"
                        :key="book.name"
                    >
                        <v-card class="card" style="border: 1px solid black;">
                            <v-card-title style="justify-content: center">
                                <span v-text="book.title"></span>
                            </v-card-title>

                            <v-divider></v-divider>

                            <v-img
                                v-bind:src="('https://library-site.s3.eu-north-1.amazonaws.com/covers/' + book.cover)"
                                width="300px"
                                style="display: inline-block"
                            >
                            </v-img>

                            <v-divider></v-divider>

                            <v-card-text style="justify-content: center; padding: 0; margin-bottom: 15px">
                                <p>Autor: <span v-text="book.authorName + ' ' + book.surname" class="mr-2"></span></p>
                                <p>Kategoria: <span v-text="book.categoryName" class="mr-2"></span></p>
                                <router-link :to="{ name: 'bookview', params: { book_id: book.id } }"><v-btn outlined style="border: 0px; text-decoration: none"><v-card-title style="color: #008D18; font-weight: bold">Zobacz więcej</v-card-title></v-btn></router-link>
                            </v-card-text>
                        </v-card>
                    </swiper-slide>
                    <div class="swiper-button-prev" slot="button-prev"></div>
                    <div class="swiper-button-next" slot="button-next"></div>
                </swiper>
            </v-col>
        </v-row>

        <v-row align="center" style="justify-content: center; text-align: center; padding-top: 55px; display: inline-block" v-if="books.length === 0">
            <h2>Brak nowych książek z ostatnich 10 dni!</h2>
            <v-col style="display: inline-flex;">
            <v-img
                :src="require('../assets/new/owl.png')"
            />
            <div style="padding: 180px 0">
                <h4 style="padding-bottom: 20px">Zapraszamy do przejrzenia katalogu biblioteki!</h4>
                <router-link :to="{ name: 'search' }">
                    <v-btn
                        elevation="24"
                        x-large
                        color=#008D18
                        class="white--text mr-5 mb-6 font-weight-bold"
                    >
                    KATALOG
                    </v-btn>
                </router-link>
            </div>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import { Swiper, SwiperSlide } from 'vue-awesome-swiper';
import 'swiper/css/swiper.css';

export default {
  name: 'carrousel',
  components: {
    Swiper,
    SwiperSlide
  },
  data() {
    return {
      swiperOptions: {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: false,
        pagination: {
          el: '.swiper-pagination',
          clickable: true
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev'
        }
      }
    };
  },

  computed: {
    books() {
      return this.$store.getters.getBooks;
    }
  },

  created () {
    this.$store.dispatch('fetchNewBooks', {});
  },

  methods: {

  }
};
</script>
