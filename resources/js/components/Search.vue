<template>
  <v-container>
    <v-data-iterator
      :items="books"
      :items-per-page.sync="itemsPerPage"
      :page="page"
      :search="search"
      :sort-by="sortBy.toLowerCase()"
      :sort-desc="sortDesc"
      hide-default-footer
    >
      <template v-slot:header>
        <v-toolbar
          dark
          color="#008D18"
          class="mb-1"
        >
          <v-text-field
            v-model="search"
            clearable
            flat
            solo-inverted
            hide-details
            prepend-inner-icon="mdi-magnify"
            label="Wyszukaj"
          ></v-text-field>
          <template v-if="$vuetify.breakpoint.mdAndUp">
            <v-spacer></v-spacer>
            <v-select
              v-model="sortBy"
              flat
              solo-inverted
              hide-details
              :items="keys"
              prepend-inner-icon="mdi-sort"
              label="Sortuj według"
            ></v-select>
            <v-spacer></v-spacer>
            <v-btn-toggle
              v-model="sortDesc"
              mandatory
            >
              <v-btn
                large
                depressed
                color="#008D18"
                :value="false"
              >
                <v-icon>mdi-arrow-up</v-icon>
              </v-btn>
              <v-btn
                large
                depressed
                color="#008D18"
                :value="true"
              >
                <v-icon>mdi-arrow-down</v-icon>
              </v-btn>
            </v-btn-toggle>
          </template>
        </v-toolbar>
      </template>

      <template v-slot:default="props">
        <v-row>
            <v-col
            v-for="item in props.items"
            :key="item.name"
            cols="12"
            sm="6"
            md="4"
            lg="3"
            style="display: flex; justify-content: center"
            >
            <v-card>
                <v-card-title style="justify-content: center">
                    <span v-text="item.title"></span>
                </v-card-title>

                <v-divider></v-divider>

                <v-img
                style="width: 300px; height: 365px"
                v-bind:src="('../storage/' + item.cover)"
                >
                </v-img>

                <v-divider></v-divider>

                <v-card-text style="justify-content: center; text-align: center">
                    <p>Autor: <span v-text="item.authorName + ' ' + item.surname" class="mr-2"></span></p>
                    <p>Kategoria: <span v-text="item.categoryName" class="mr-2"></span></p>
                    <p>Dostępna ilość książek: <v-chip v-text="item.amount" :color="getColor(item.amount)" dark></v-chip></p>
                    <v-divider></v-divider>
                    <template v-if="item.id">
                        <router-link :to="{ name: 'bookview', params: { book_id: item.id } }"><v-btn outlined style="border: 0px; text-decoration: none"><v-card-title style="color: #008D18; font-weight: bold">Zobacz więcej</v-card-title></v-btn></router-link>
                    </template>
                </v-card-text>
            </v-card>
            </v-col>
        </v-row>
        </template>

      <template v-slot:footer>
        <v-row class="mt-2" align="center" justify="center">
          <span class="grey--text">Items per page</span>
          <v-menu offset-y>
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                dark
                text
                color="#008D18"
                class="ml-2"
                v-bind="attrs"
                v-on="on"
              >
                {{ itemsPerPage }}
                <v-icon>mdi-chevron-down</v-icon>
              </v-btn>
            </template>
            <v-list>
              <v-list-item
                v-for="(number, index) in itemsPerPageArray"
                :key="index"
                @click="updateItemsPerPage(number)"
              >
                <v-list-item-title>{{ number }}</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>

          <v-spacer></v-spacer>

          <span
            class="mr-4
            grey--text"
          >
            Page {{ page }} of {{ numberOfPages }}
          </span>
          <v-btn
            fab
            dark
            color="#008D18"
            class="mr-1"
            @click="formerPage"
          >
            <v-icon>mdi-chevron-left</v-icon>
          </v-btn>
          <v-btn
            fab
            dark
            color="#008D18"
            class="ml-1"
            @click="nextPage"
            style="margin-right: 15px"
          >
            <v-icon>mdi-chevron-right</v-icon>
          </v-btn>
        </v-row>
      </template>
    </v-data-iterator>
  </v-container>
</template>

<script>
/*eslint-disable*/
export default {
  data: () => ({
    itemsPerPageArray: [4, 8, 12],
    search: '',
    filter: {},
    sortDesc: false,
    page: 1,
    books: [],
    itemsPerPage: 4,
    sortBy: 'title',
    keys: [
      'Title',
      'authorName',
      'surname',
      'categoryName',
      'amount'
    ],
    items: [
      {
        title: '',
        authorName: '',
        surname: '',
        categoryName: '',
        isbn: '',
        description: '',
        publish_year: '',
        publisherName: '',
        amount: '',
        cover: ''
      }
    ]
  }),

  computed: {
    numberOfPages () {
      return Math.ceil(this.books.length / this.itemsPerPage);
    },
    filteredKeys () {
      return this.keys.filter((key) => key !== 'Name');
    }
  },

  watch: {
    dialog (val) {
      val || this.close();
    }
  },

  methods: {
    setData(books) {
      this.books = books;
    }, 
    nextPage () {
      if (this.page + 1 <= this.numberOfPages) this.page += 1;
    },
    formerPage () {
      if (this.page - 1 >= 1) this.page -= 1;
    },
    updateItemsPerPage (number) {
      this.itemsPerPage = number;
    },
    getColor (amount) {
      if (amount === '0') return 'red';
      if (amount === '1' || amount === '2') return 'orange';
      return 'green';
    }
  },

  beforeRouteEnter (to, from, next) {
    axios
     .get('/api/book/getBooks')
     .then(response => {
       next(vm => vm.setData(response.data));
   });
  },
};
</script>