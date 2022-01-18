<template>
    <v-form @submit.prevent="onSubmit">
        <v-card>
            <v-card-title>Opret {{ typeContact === 'customer' ? 'Kunde' : 'Leverandør' }}</v-card-title>
            <v-card-text>
                <Errors :errors="errors"></Errors>

                <v-select label="Type" v-model="type" :items="types" item-value="key" item-text="name"></v-select>

                <v-text-field v-model="name" label="Navn"></v-text-field>
                <v-text-field v-model="address" label="Adresse"></v-text-field>

                <v-row>
                    <v-col sm="4">
                        <v-text-field v-model="zip" label="Postnummer"></v-text-field>
                    </v-col>
                    <v-col sm="4">
                        <v-text-field v-model="city" label="By"></v-text-field>
                    </v-col>
                    <v-col sm="4">
                        <v-autocomplete
                                label="Land"
                                :items="countries"
                                item-text="name"
                                item-value="name"
                                v-model="country"
                        >
                        </v-autocomplete>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col sm="6">
                        <v-text-field v-model="email" label="Email"></v-text-field>
                    </v-col>
                    <v-col sm="6">
                        <v-text-field v-model="phone" label="Telefon"></v-text-field>
                    </v-col>
                </v-row>


                <div v-if="type === 'company'">
                    <v-divider></v-divider>

                    <v-row>
                        <v-col sm="6">
                            <v-text-field v-model="cvr" label="CVR"></v-text-field>
                        </v-col>
                        <v-col sm="6">
                            <v-text-field v-model="ean" label="EAN"></v-text-field>
                        </v-col>
                    </v-row>

                    <v-text-field v-model="website" label="Hjemmeside"></v-text-field>
                </div>
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
    import countries from '../data/countries';
    import Errors from "../../common/Errors";
  export default {
    name: "CreateContact",
    props: ['typeContact'],
    components: {Errors},
    data() {
      return {
        countries,
        errors: [],
        type: '',
        types: [
          {
            key: 'company',
            name: 'Firma'
          },
          {
            key: 'private',
            name: 'Privatperson'
          }
        ],
        name: '',
        address: '',
        zip: '',
        city: '',
        country: 'DK',
        email: '',
        phone: '',
        cvr: '',
        ean: '',
        website: ''
      }
    },
    i18n: {
      messages: {
        english: {
          create: 'Create',
          customer: 'Customer',
          supplier: 'Supplier',
          type: 'Type',
          name: 'Name',
          address: 'Address',
          zip: 'ZIP',
          city: 'City',
          country: 'Country',
          phone: 'Phone',
          website: 'Website',
          type_required: 'Type is required',
          name_required: 'Name is required',
          address_required: 'Address is required',
          city_required: 'City is required',
          country_required: 'Country is required',
          created: 'You have created this contact',
          cancel: 'Cancel'
        },
        danish: {
          create: 'Opret',
          customer: 'Kunde',
          supplier: 'Leverandør',
          type: 'Type',
          name: 'Navn',
          address: 'Adresse',
          zip: 'Postnummer',
          city: 'By',
          country: 'Land',
          phone: 'Telefon',
          website: 'Hjemmeside',
          type_required: 'Type er påkrævet',
          name_required: 'Navn er påkrævet',
          address_required: 'Adresse er påkrævet',
          city_required: 'By er påkrævet',
          country_required: 'Land er påkrævet',
          created: 'Du har nu oprettet denne kontakt',
          cancel: 'Annullér'
        }
      }
    },
    methods: {
      onSubmit() {
        this.errors = [];

        if (!this.type) this.errors.push('Type er påkrævet');
        if (!this.name) this.errors.push('Navn er påkrævet');
        if (!this.address) this.errors.push('Adresse er påkrævet');
        if (!this.city) this.errors.push('By er påkrævet');
        if (!this.country) this.errors.push('Land er påkrævet');

        if (this.errors.length) return;

        const data = {
          typeContact: this.typeContact,
          type: this.type,
          name: this.name,
          address: this.address,
          zip: this.zip,
          city: this.city,
          country: this.country,
          email: this.email,
          phone: this.phone,
          cvr: this.cvr,
          ean: this.ean,
          website: this.website
        };

        this.$store.dispatch('contacts/create', data).then(contact => {
          this.$notify('Du har nu oprettet denne kontakt');
          this.$emit('created', contact);
          this.name = '';
          this.type = 'company';
          this.address = '';
          this.zip = '';
          this.city = '';
          this.country = '';
          this.email = '';
          this.phone = '';
          this.cvr = '';
          this.ean = '';
          this.website = '';
        })
      }
    }
  }
</script>

<style scoped>

</style>
