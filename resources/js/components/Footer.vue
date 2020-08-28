<template>
    <div id="app">
            <v-footer color=brown padless>
                <v-col
                        class="text-center white--text"
                        cols="12"
                >
                    <strong> {{ new Date().getFullYear() }} — Biblioteka</strong>
                </v-col>
            </v-footer>
    </div>
</template>

<script>
export default {
  name: 'Footer'
};
</script>

<style scoped>
</style>