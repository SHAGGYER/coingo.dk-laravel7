<template>
    <div>
        <v-card>
            <v-card-title>
                <h1>Firmainformation</h1>
            </v-card-title>
            <v-card-text>
                <v-form @submit.prevent="onSubmit">
                    <Errors :errors="errors"></Errors>
                    <v-row>
                        <v-col sm="6">
                            <v-text-field label="Firmanavn" v-model="title"></v-text-field>

                        </v-col>
                        <v-col sm="6">
                            <v-text-field label="CVR" v-model="cvr"></v-text-field>

                        </v-col>
                    </v-row>

                    <v-divider class="my-5"></v-divider>

                    <v-row>
                        <v-col sm="6">
                            <v-text-field label="Telefon" v-model="phone"></v-text-field>
                        </v-col>
                        <v-col sm="6">
                            <v-text-field label="Email" v-model="email"></v-text-field>
                        </v-col>
                    </v-row>

                    <v-divider class="my-5"></v-divider>

                    <v-text-field v-model="address" label="Adresse"></v-text-field>
                    <v-row>
                        <v-col sm="6">
                            <v-text-field v-model="city" label="By"></v-text-field>
                        </v-col>
                        <v-col sm="6">
                            <v-text-field v-model="zip" label="Postnr."></v-text-field>
                        </v-col>
                    </v-row>
                    <v-autocomplete
                            label="Land"
                            :items="countries"
                            item-text="name"
                            item-value="name"
                            v-model="country"
                    >
                    </v-autocomplete>

                    <v-divider class="my-5"></v-divider>

                    <v-row>
                        <v-col sm="4">
                            <v-text-field label="Reg. Nr." v-model="regNr"></v-text-field>

                        </v-col>
                        <v-col sm="8">
                            <v-text-field label="Kontonr." v-model="accountNumber"></v-text-field>

                        </v-col>
                    </v-row>

                    <v-divider class="my-5"></v-divider>

                    <v-text-field label="Næste Fakturanummer" type="number" v-model="invoiceNumber"></v-text-field>

                    <v-divider class="my-5"></v-divider>

                    <v-btn color="purple" dark type="submit">Gem</v-btn>
                </v-form>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
    import countriesArray from "../../data/countries";
  import Errors from "../../../common/Errors";
  export default {
    name: "Info",
    components: {Errors},
    data() {
      return{
        countries: countriesArray,
        address: '',
        city: '',
        zip: '',
        country: '',
        title:'',
        regNr: '',
        cvr: '',
        accountNumber: '',
        phone: '',
        email: '',
        invoiceNumber: 1,
        errors: []
      }
    },
    i18n: {
      messages: {
        english: {
          company_info: 'Company Information',
          company_name: 'Company Name',
          phone: 'Phone',
          address: 'Address',
          city: 'City',
          zip: 'ZIP',
          country: 'Country',
          account_number: 'Account Nr.',
          next_invoice: 'Next Invoice Number',
          save: 'Save',
          company_name_required: 'Company name is required',
          cvr_required: 'CVR is required',
          invoice_number_required: 'Invoice number is required',
          address_required: 'Address is required',
          city_required: 'City is required',
          zip_required: 'ZIP is required',
          phone_required: 'Phone is required',
          email_required: 'Email is required',
          email_wrong_format: 'Email has wrong format',
          updated: 'You have updated company information'
        },
        danish: {
          company_info: 'Firmainformation',
          company_name: 'Firmanavn',
          phone: 'Telefon',
          address: 'Adresse',
          city: 'By',
          zip: 'Postnr.',
          country: 'Land',
          account_number: 'Kontonr.',
          next_invoice: 'Næste Fakturanummer',
          save: 'Gem',
          company_name_required: 'Firmanavn er påkrævet',
          cvr_required: 'CVR er påkrævet',
          invoice_number_required: 'Fakturanummer er påkrævet',
          address_required: 'Adresse er påkrævet',
          city_required: 'By er påkrævet',
          zip_required: 'Postnr. er påkrævet',
          phone_required: 'Telefon er påkrævet',
          email_required: 'Email er påkrævet',
          email_wrong_format: 'Forkert email format',
          updated: 'Du har nu opdateret firmainformation'
        }
      }
    },
    mounted() {
      if (!this.$store.state.user.company) {
        this.$router.push('/companies/create');
        return;
      }

      this.title = this.$store.state.user.company.title;
      this.regNr = this.$store.state.user.company.reg_nr;
      this.cvr = this.$store.state.user.company.cvr;
      this.accountNumber = this.$store.state.user.company.account_number;
      this.invoiceNumber = this.$store.state.user.company.invoice_number;
      this.address = this.$store.state.user.company.address;
      this.city = this.$store.state.user.company.city;
      this.zip = this.$store.state.user.company.zip;
      this.country = this.$store.state.user.company.country;
      this.phone = this.$store.state.user.company.phone;
      this.email = this.$store.state.user.company.email;
    },
    methods: {
        onSubmit() {
            this.errors = [];
            if (!this.title) this.errors.push('Firmanavn er påkrævet');
            if (!this.cvr) this.errors.push('CVR er påkrævet');
            if (!this.invoiceNumber) this.errors.push('Fakturanummer er påkrævet');
            if (!this.address) this.errors.push('Adresse er påkrævet');
            if (!this.city) this.errors.push('By er påkrævet');
            if (!this.zip) this.errors.push('Postnr. er påkrævet');
            if (!this.phone) this.errors.push('Telefon er påkrævet');
            if (!this.email) this.errors.push('Email er påkrævet');
            else if (!this.$store.state.helpers.validateEmail(this.email)) this.errors.push('Forkert email format');
            if (this.errors.length) return;

            const data = {
              regNr: this.regNr,
              accountNumber: this.accountNumber,
              cvr: this.cvr,
              title: this.title,
              invoiceNumber: this.invoiceNumber,
              address: this.address,
              city: this.city,
              zip: this.zip,
              country: this.country,
              phone: this.phone,
              email: this.email
            };

            this.$store.dispatch('companies/update', data).then(() => {
              this.$notify('Du har nu opdateret firmainformation');
            })
        }
    }
  }
</script>

<style scoped>

</style>
