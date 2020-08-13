import axios from 'axios'

export default {
    state: {
        books: []
    },
    mutations: {
        seetBooks(state, books) {
            state.books = books
        }
    },
    actions: {
        fetchBooks(context) {   
            axios({
                method: 'GET',
                url: 'http://127.0.0.1:8000/api/book/getBooks'
                })
                .then(response => {
                    context.commit('setBooks', response.data)
                })
                .catch(error => {
                    console.log(error)
                })
        },
        storeBook(context, credentials) {
            return new Promise((resolve, reject) => {
                axios({
                    method: 'POST',
                    url: 'http://127.0.0.1:8000/api/store',
                    data: {
                        title: credentials.title,
                        isbn: credentials.isbn,
                        description: credentials.description,
                        publish_year: credentials.publish_year,
                        cover: credentials.cover,
                        author: credentials.author,
                        category: credentials.category,
                        publisher: credentials.publisher,
                    }
                })
                .then(response => {
                    if(response.data.success == true) {
                        alert("Pomyślnie dodano książkę do bazy bibliotecznej!")
                        router.push('/admin-panel/books')
                    }
                    else {
                        alert("Książka o podanych danych już isnieje.")
                    }
                })
                .catch(error => {
                    console.log(error)
                })
            })
        },
    },
    getters : {
        getBooks(state) {
            return state.books
        }
    },
}