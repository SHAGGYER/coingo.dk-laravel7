<template>
    <v-form @submit.prevent="update">
        <v-card>
            <v-card-title
                    class="headline grey lighten-2"
                    primary-title
            >
                Opdatér Sted
            </v-card-title>

            <v-card-text>
                    <Errors class="mt-3" :errors="errors"></Errors>
                    <v-text-field v-model="place.title" label="Titel"></v-text-field>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="error" @click="$emit('closed')">Annullér</v-btn>
                <v-btn color="error" type="button" @click="$emit('deleting')">Slet</v-btn>
                <v-btn type="submit" color="success">Gem</v-btn>
            </v-card-actions>
        </v-card>
    </v-form>
</template>

<script>
  import Errors from "../../../common/Errors";
  export default {
    name: "Create",
    components: {Errors},
    props: ['place'],
    data(){
      return{
        errors: [],
      }
    },
    methods: {
      update() {
        this.errors = [];

        if (!this.place.title) this.errors.push('Titel er påkrævet.');

        if (this.errors.length) return;

        const data = {
          id: this.place.id,
          title: this.place.title,
        };

        this.$store.dispatch('places/update', data).then(() => {
          this.$notify('Du har nu opdateret dette sted');
          this.$emit('updated');
        })
      },
    }
  }
</script>

<style scoped>

</style>
