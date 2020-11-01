<template>
    <v-container style="text-align: center">
        <v-row style="text-align: center; justify-content: center; padding-bottom: 10px; padding-top: 20px;">
            <h1>Nowości książkowe</h1>
        </v-row>
        <v-row style="justify-content: center; text-align: center" v-if="books.length != 0">
            <v-col
                cols="10"
            >
                <swiper ref="mySwiper" :options="swiperOptions" style="height: 650px">
                    <swiper-slide
                        v-for="book in books"
                        :key="book.name"
                        style="margin-left: 30px; margin-right: 30px"
                    >
                        <v-card class="card" style="border: 1px solid black; width: 460px">
                            <v-card-title style="justify-content: center">
                                <span v-text="book.title"></span>
                            </v-card-title>

                            <v-divider></v-divider>

                            <v-img
                                v-bind:src="('../storage/' + book.cover)"
                                height="300px"
                                width="300px"
                                style="display: inline-block"
                            >
                            </v-img>

                            <v-divider></v-divider>

                            <v-card-text style="justify-content: center; padding: 0; margin-bottom: 15px">
                                <p>Autor: <span v-text="book.authorName + ' ' + book.surname" class="mr-2"></span></p>
                                <p>Kategoria: <span v-text="book.categoryName" class="mr-2"></span></p>
                                <router-link :to="{ name: 'bookview', params: { book_id: book.id } }"><v-btn outlined style="border: 0px; text-decoration: none"><v-card-title style="color: blue; font-weight: bold">Zobacz więcej</v-card-title></v-btn></router-link>
                            </v-card-text>
                        </v-card>
                    </swiper-slide>
                    <div class="swiper-pagination" slot="pagination"></div>
                    <div class="swiper-button-prev" slot="button-prev"></div>
                    <div class="swiper-button-next" slot="button-next"></div>
                </swiper>
            </v-col>
        </v-row>

        <v-row align="center" style="justify-content: center; text-align: center; padding-top: 55px; display: inline-block" v-if="books.length === 0">
            <h2>Brak nowych książek z ostatnich 10 dni!</h2>
            <v-icon x-large>mdi-emoticon-confused-outline</v-icon>
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
        slidesPerView: 2,
        spaceBetween: 30,
        loop: true,
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