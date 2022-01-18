<template>
    <v-form @submit.prevent="onSubmit">
        <v-card>
            <v-card-title>Opret Produkt</v-card-title>
            <v-card-text>
                    <Errors :errors="errors"></Errors>
                    <v-row>
                        <v-col sm="8">
                            <v-text-field label="Titel" v-model="title"></v-text-field>
                        </v-col>
                        <v-col sm="4">
                            <v-text-field label="Varekode" v-model="code"></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col sm="6">
                            <v-select :loading="loading" label="Sted" v-model="placeId" :items="places" item-text="title" item-value="id"></v-select>

                        </v-col>
                        <v-col sm="6">
                            <v-select v-model="unit"
                                      item-value="key"
                                      item-text="text"
                                      label="Enhed"
                                      :items="units"></v-select>
                        </v-col>

                    </v-row>
                    <v-row>
                        <v-col sm="6">
                            <v-text-field type="number" v-model="price" label="Pris"></v-text-field>

                        </v-col>
                        <v-col sm="6">
                            <v-text-field :disabled="unit === 'digital'" type="number" v-model="quantity" label="Antal"></v-text-field>

                        </v-col>
                    </v-row>
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
    import units from "../../data/units";
  import Errors from "../../../common/Errors";
  export default {
    name: "Create",
    components: {Errors},
    data() {
      return {
        title: '',
        errors: [],
        places: [],
        placeId: '',
        price: '',
        quantity: '',
        unit: 'piece',
        units,
        code: '',
        loading: false,
      }
    },
    mounted() {
      this.getPlaces();
    },
    methods: {
      getPlaces() {
        this.loading = true;
        this.$store.dispatch('places/browse').then(places => {
          this.loading = false;
          this.places = places;
        })
      },
      onSubmit() {
        this.errors = [];

        if (!this.title) this.errors.push('Titel er påkrævet');
        if (!this.price) this.errors.push('Pris er påkrævet');

        if (this.errors.length) return;

        const data = {
          title: this.title,
          placeId: this.placeId,
          price: this.price,
          quantity: this.quantity,
          unit: this.unit,
          code: this.code
        };

        this.$store.dispatch('items/create', data).then(item => {
            this.$notify('Du har nu oprettet dette produkt');
            this.title = '';
            this.placeId = '';
            this.price = '';
            this.quantity = '';
            this.unit = 'piece';
            this.code = '';
            this.$emit('created', item);
        })
      }
    }
  }
</script>

<style scoped>

</style>
