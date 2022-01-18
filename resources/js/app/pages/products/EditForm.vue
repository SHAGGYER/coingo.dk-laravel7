<template>
    <v-form @submit.prevent="updateItem">
        <v-card>
            <v-card-title
                    class="headline grey lighten-2"
                    primary-title
            >
                Opdatér Produkt
            </v-card-title>
            <v-card-text>
                <Errors :errors="errors"></Errors>

                <v-row>
                    <v-col sm="8">
                        <v-text-field label="Titel" v-model="item.title"></v-text-field>
                    </v-col>
                    <v-col sm="4">
                        <v-text-field label="Varekode" v-model="item.code"></v-text-field>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col sm="6">
                        <v-select label="Sted" v-model="item.place_id" :items="places" item-text="title" item-value="id"></v-select>

                    </v-col>
                    <v-col sm="6">
                        <v-select v-model="item.unit"
                                  item-value="key"
                                  item-text="text"
                                  label="Enhed"
                                  :items="units"></v-select>
                    </v-col>

                </v-row>

                <v-row>
                    <v-col sm="6">
                        <v-text-field v-model="item.price" label="Pris"></v-text-field>

                    </v-col>
                    <v-col sm="6">
                        <v-text-field :disabled="item.unit === 'digital'" v-model="item.quantity" label="Antal"></v-text-field>
                    </v-col>
                </v-row>




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
    import units from "../../data/units";
  import Errors from "../../../common/Errors";
  export default {
    name: "Edit",
    components: {Errors},
    props: ['item'],
    data(){
      return{
        errors: [],
        places: [],
        units,
      }
    },
    mounted() {
      this.getPlaces();
    },
    methods: {
      getPlaces() {
        this.$store.dispatch('places/browse').then(places => {
          this.places = places
        })
      },
      updateItem() {
        this.errors = [];

        if (!this.item.title) this.errors.push('Titel er påkrævet');
        if (!this.item.quantity) this.errors.push('Pris er påkrævet');
        if (!this.item.price) this.errors.push('Antal er påkrævet');

        if (this.errors.length) return;

        const data = {
          title: this.item.title,
          placeId: this.item.place_id,
          price: this.item.price,
          quantity: this.item.quantity,
          id: this.item.id,
          unit: this.item.unit,
          code: this.item.code
        };

        this.$store.dispatch('items/update', data).then(item => {
          this.$notify('Du har nu opdateret dette produkt');
          this.dialog = false;
          this.$emit('updated', item);
        })
      },
    }
  }
</script>

<style scoped>

</style>
