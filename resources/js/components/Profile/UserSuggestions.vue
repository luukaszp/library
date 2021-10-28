<template>
    <v-form
        ref="form"
        v-model="valid"
        style="border: 1px solid black; padding: 25px; text-align: center; margin-top: 12px"
    >
        <v-select
        v-model="select"
        :items="items"
        :rules="[v => !!v || 'Rodzaj jest wymagany!']"
        label="Rodzaj sugestii"
        required
        ></v-select>

        <v-textarea
        autocomplete="Opis"
        v-model="description"
        :rules="descriptionRules"
        label="Opis sugestii"
        required
        ></v-textarea>

        <v-checkbox
        v-model="checkbox"
        :rules="[v => !!v || 'Aby kontynuować musisz zaznaczyć pole!']"
        label="Zgadzam się na przetwarzanie moich danych osobowych"
        required
        ></v-checkbox>

        <v-btn
        :disabled="!valid"
        color="success"
        class="mr-4"
        @click="validate"
        >
        Wyślij
        </v-btn>
    </v-form>
</template>

<script>
import axios from 'axios';

export default {
  data: () => ({
    valid: true,
    description: '',
    descriptionRules: [
      (v) => !!v || 'Opis jest wymagany!',
      (v) => v.length >= 40 || 'Opis jest za krótki!'
    ],
    select: null,
    items: [
      'Nowa pozycja książkowa',
      'Nowa funkcja',
      'Błąd na stronie',
      'Inne'
    ],
    checkbox: false
  }),

  methods: {
    validate () {
      if (this.$refs.form.validate()) {
        axios.post('/api/suggestions/add', {
          type: this.select,
          description: this.description
        })
          .then((response) => {
            if (response.data.success === true) {
              this.$swal('Dodano', 'Pomyślnie zgłoszono nową sugestię! Dziękujemy!', 'success');
            } else {
              this.$swal('Błąd', 'Coś poszło nie tak...', 'error');
            }
          })
          .catch((error) => {
            console.log(error);
          });
      }
      this.$refs.form.reset();
    }
  }
};
</script>
