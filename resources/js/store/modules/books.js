import axios from 'axios'

export default {
    state: {
        books: []
    },
    mutations: {
        setBooks(state, books) {
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
        /*storeBook(context) {
            return new Promise((resolve, reject) => {
                let config = {
                    headers: {
                        'Content-Type' : 'multipart/form-data'
                    }
                }
                axios({
                    method: 'POST',
                    url: 'http://127.0.0.1:8000/api/store',
                    formData,
                    config
                })
                .then(response => {
                    console.log(response.data)
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
        },*/
    },
    getters : {
        getBooks(state) {
            return state.books
        }
    },
}