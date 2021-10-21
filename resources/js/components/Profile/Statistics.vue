<template>
  <div style="padding-top: 25px">
    <v-carousel
    style="width: 800px"
    hide-delimiters
    show-arrows-on-hover
    id="chart"
    >
      <v-carousel-item>
          <BorrowsAmount v-bind:user_id="user_id"/>
      </v-carousel-item>
      <v-carousel-item>
          <FavoriteAuthor v-bind:user_id="user_id"/>
      </v-carousel-item>
      <v-carousel-item>
          <FavoriteCategory v-bind:user_id="user_id"/>
      </v-carousel-item>
      <v-carousel-item>
          <RatingsAmount v-bind:user_id="user_id"/>
      </v-carousel-item>
    </v-carousel>
  </div>
</template>

<script>
import BorrowsAmount from './Charts/BorrowsAmount.vue';
import FavoriteAuthor from './Charts/FavoriteAuthor.vue';
import FavoriteCategory from './Charts/FavoriteCategory.vue';
import RatingsAmount from './Charts/RatingsAmount.vue';

export default {
  name: 'Statistics',
  props: ['user_id'],
  components: {
    BorrowsAmount, FavoriteAuthor, FavoriteCategory, RatingsAmount
  },
  data () {
    return {
      model: 0
    };
  }
};
</script>

<style>
@media only screen and (max-width: 600px) {
    #chart {
        max-width: 400px;
    }
}
</style>
