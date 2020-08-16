import axios from 'axios'

export default {
    state: {
        borrows: []
    },
    mutations: {
        setBorrows(state, borrows) {
            state.borrows = borrows
        }
    },
    actions: {
        borrowBooks(context, credentials) {
            return new Promise((resolve, reject) => {
                axios({
                    method: 'POST',
                    url: 'http://127.0.0.1:8000/api/borrow/addBorrow',
                    data: {
                        reader: credentials.reader,
                        books: credentials.books,
                    }
                })
                .then(response => {
                    if(response.data.success == true) {
                        alert("Wypożyczenie zakończono pomyślnie!")
                        router.push('/admin-panel/borrows')
                    }
                    else {
                        alert("Coś poszło nie tak!")
                    }
                })
                .catch(error => {
                    console.log(error)
                })
            })
        }, 

        fetchBorrows(context) {   
            axios({
                method: 'GET',
                url: 'http://127.0.0.1:8000/api/borrow/getBorrows'
                })
                .then(response => {
                    context.commit('setBorrows', response.data)
                })
                .catch(error => {
                    console.log(error)
                })
        },
    },
    getters : {
        getBorrows(state) {
            return state.borrows
        }
    },
}