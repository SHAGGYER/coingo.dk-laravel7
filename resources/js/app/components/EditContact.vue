<template>
    <v-form @submit.prevent="onSubmit">
        <v-card>
            <v-card-title>Opdatér {{ contact.type_contact === 'customer' ? 'Kunde' : 'Leverandør' }}</v-card-title>
            <v-card-text>
                    <Errors :errors="errors"></Errors>
                    <v-text-field v-model="contact.name" label="Navn"></v-text-field>
                    <v-text-field v-model="contact.address" label="Adresse"></v-text-field>
                    <v-row>
                        <v-col sm="4">
                            <v-text-field v-model="contact.zip" label="Postnummer"></v-text-field>
                        </v-col>
                        <v-col sm="4">
                            <v-text-field v-model="contact.city" label="By"></v-text-field>
                        </v-col>
                        <v-col sm="4">
                            <v-autocomplete
                                    label="Land"
                                    :items="countries"
                                    item-text="name"
                                    item-value="name"
                                    v-model="contact.country"
                            >
                            </v-autocomplete>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col sm="6">
                            <v-text-field v-model="contact.email" label="Email"></v-text-field>

                        </v-col>
                        <v-col sm="6">
                            <v-text-field v-model="contact.phone" label="Telefon"></v-text-field>

                        </v-col>
                    </v-row>
                    <div v-if="contact.type === 'company'">
                        <v-divider></v-divider>
                        <v-row>
                            <v-col sm="6">
                                <v-text-field v-model="contact.cvr" label="CVR"></v-text-field>
                            </v-col>
                            <v-col sm="6">
                                <v-text-field v-model="contact.ean" label="EAN"></v-text-field>
                            </v-col>
                        </v-row>
                        <v-text-field v-model="contact.website" label="Hjemmeside"></v-text-field>
                    </div>
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
    import countriesArray from "../data/countries";
    import Errors from "../../common/Errors";
  export default {
    name: "EditContact",
    props: ['contact'],
    components: {Errors},
    data() {
      return {
        errors: [],
        type: 'company',
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
        country: '',
        email: '',
        phone: '',
        cvr: '',
        ean: '',
        website: '',
        countries: countriesArray
      }
    },
    i18n: {
      messages: {
        english: {
          update: 'Update',
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
          updated: 'You have updated this contact',
          delete: 'Delete',
          save: 'Save',
          cancel: 'Cancel'
        },
        danish: {
          update: 'Opdatér',
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
          updated: 'Du har nu opdateret denne kontakt',
          delete: 'Slet',
          save: 'Gem',
          cancel: 'Annullér'
        }
      }
    },
    methods: {
      onSubmit() {
        this.errors = [];

        if (!this.contact.name) this.errors.push('Navn er påkrævet');
        if (!this.contact.address) this.errors.push('Adresse er påkrævet');
        if (!this.contact.city) this.errors.push('By er påkrævet');
        if (!this.contact.country) this.errors.push('Land er påkrævet');

        if (this.errors.length) return;

        const data = {
          name: this.contact.name,
          address: this.contact.address,
          zip: this.contact.zip,
          city: this.contact.city,
          country: this.contact.country,
          email: this.contact.email,
          phone: this.contact.phone,
          cvr: this.contact.cvr,
          ean: this.contact.ean,
          website: this.contact.website,
          id: this.contact.id
        };

        this.$store.dispatch('contacts/update', data).then(() => {
          this.$notify('Du har nu opdateret denne kontakt');
          this.$emit('updated');
        })
      }
    }
  }
</script>

<style scoped>

</style>
