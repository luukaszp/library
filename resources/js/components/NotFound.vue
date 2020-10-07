<template>
  <v-container
    class="text-center"
    fill-height
  >
    <v-row align="center">
      <v-col>
        <h1 class="display-2 primary--text">Whoops, 404</h1>

        <p>Strona którą szukasz nie istnieje</p>

        <v-btn
          :to="`/`"
          color="primary"
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