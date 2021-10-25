<template>
    <v-container style="text-align: center">
        <v-row style="text-align: center; justify-content: center; padding-bottom: 10px; padding-top: 20px;">
            <h1>Nowości książkowe</h1>
        </v-row>
        <v-row style="justify-content: center; text-align: center" v-if="books.length != 0">
            <v-col
                md="6"
            >
                <swiper ref="mySwiper" :options="swiperOptions">
                    <swiper-slide
                        v-for="(book, index) in books"
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
                                style="display: inline-block; margin-top: 10px"
                            >
                            </v-img>

                            <v-divider></v-divider>

                            <v-card-text style="justify-content: center; padding: 0; margin-bottom: 15px">
                                <p>Kategoria: <span v-text="book.categoryName" class="mr-2"></span></p>
                                <template>
                                    <router-link :to="{ name: 'bookview', params: { book_id: book_id[index].id} }"><v-btn outlined style="border: 0px; text-decoration: none"><v-card-title style="color: #008D18; font-weight: bold">Zobacz więcej</v-card-title></v-btn></router-link>
                                </template>
                            </v-card-text>
                        </v-card>
                    </swiper-slide>
                    <div class="swiper-button-prev" slot="button-prev"></div>
                    <div class="swiper-button-next" slot="button-next"></div>
                </swiper>
            </v-col>
        </v-row>

        <v-row id="empty" align="center" style="justify-content: center; text-align: center; padding-top: 55px" v-if="books.length === 0">
            <h2>Brak nowych książek z ostatnich 10 dni!</h2>
            <v-col id="image">
            <v-img
                :src="require('../assets/new/owl.png')"
            />
            <div id="catalog">
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
      book_id: [],
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
    },
    setData() {
    axios.get('/api/book/getBookID').then(response => {
       this.book_id = response.data;
    })
    }
  },

  created () {
    this.$store.dispatch('fetchNewBooks', {});
    this.setData();
  },

  methods: {

  }
};
</script>
<style scoped>
        #empty {
            display: inline-block;
        }
        #image {
            display: inline-flex;
        }
        #catalog {
            padding: 180px 0;
        }
    @media only screen and (max-width: 600px) {
        #empty {
            display: flex;
        }
        #image {
            display: inline-block;
        }
        #catalog {
            padding: 0;
        }
    }
</style>
