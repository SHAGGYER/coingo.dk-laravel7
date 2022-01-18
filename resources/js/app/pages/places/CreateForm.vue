<template>
    <v-form @submit.prevent="onSubmit">
        <v-card>
            <v-card-title>Opret Sted</v-card-title>
            <v-card-text>
                    <Errors :errors="errors"></Errors>
                    <v-text-field label="Titel" v-model="title"></v-text-field>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="error" @click="$emit('closed')">Annullér</v-btn>
                <v-btn color="success" type="submit">Opret</v-btn>
            </v-card-actions>
        </v-card>
    </v-form>
</template>

<script>
  import Errors from "../../../common/Errors";
  export default {
    name: "CreatePlace",
    components: {Errors},
    data() {
      return {
        title: '',
        errors: []
      }
    },
    i18n: {
      messages: {
        english: {
          create_place: 'Create Place',
          create: 'Create',
          title: 'Title',
          title_required: 'Title is required',
          created: 'You have created this place',
          cancel: 'Cancel'
        },
        danish: {
          create_place: 'Opret Sted',
          title: 'Titel',
          create: 'Opret',
          title_required: 'Titel er påkrævet',
          created: 'Du har nu oprettet dette sted',
          cancel: 'Annullér'
        }
      }
    },
    methods: {
      onSubmit() {
        this.errors = [];

        if (!this.title) this.errors.push('Titel er påkrævet');

        if (this.errors.length) return;

        const data = {
          title: this.title
        };

        this.$store.dispatch('places/create', data).then(place => {
          this.$notify('Du har nu oprettet dette sted');
            this.$emit('created', place);
            this.title = '';
        })
      }
    }
  }
</script>

<style scoped>

</style>
