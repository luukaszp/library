<template>
  <v-container
    class="text-center"
    fill-height
  >
    <v-row align="center">
      <v-col cols=12>
        <v-img
            style="width: 477px; height: 298px; display: inline-block"
            :src="require('../assets/notfound/404error.jpg')"
        >
        </v-img>
      </v-col>
      <v-col>
        <v-btn
          :to="`/`"
          color="#008D18"
          outlined
        >
          Zabierz mnie stąd!
        </v-btn>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>

</script>