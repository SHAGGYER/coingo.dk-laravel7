<template>
    <v-stepper v-model="step">
        <v-stepper-header>
            <v-stepper-step step="1" :complete="step > 1">Grundlæggende</v-stepper-step>
            <v-divider></v-divider>
            <v-stepper-step step="2" :complete="step > 2">Adresse</v-stepper-step>
            <v-divider></v-divider>
            <v-stepper-step step="3" :complete="step > 3">Bank</v-stepper-step>
            <v-divider></v-divider>
            <v-stepper-step step="3">Færdig</v-stepper-step>
        </v-stepper-header>
        <v-stepper-items>
            <v-stepper-content step="1">
                <v-form @submit.prevent="validateCompany">
                    <v-card>
                        <v-card-text>
                                <Errors :errors="errors"></Errors>
                                <v-text-field v-model="title" label="Firmanavn"></v-text-field>
                                <v-text-field v-model="cvr" label="CVR"></v-text-field>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="purple" dark type="submit">Fortsæt</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-form>
            </v-stepper-content>
            <v-stepper-content step="2">
                <v-form @submit.prevent="validateAddress">
                    <v-card>
                        <v-card-text>
                            <Errors :errors="errors"></Errors>
                            <v-row>
                                <v-col sm="6">
                                    <v-text-field v-model="phone" label="Telefon"></v-text-field>
                                </v-col>
                                <v-col sm="6">
                                    <v-text-field v-model="email" label="Email"></v-text-field>
                                </v-col>
                            </v-row>
                            <v-divider class="my-3"></v-divider>
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
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="info" type="submit">Fortsæt</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-form>
            </v-stepper-content>
            <v-stepper-content step="3">
                <v-form @submit.prevent="validateBank">
                    <v-card>
                        <v-card-text>
                            <v-row>
                                <v-col sm="4">
                                    <v-text-field v-model="regNr" label="Reg Nr."></v-text-field>

                                </v-col>
                                <v-col sm="8">
                                    <v-text-field v-model="accountNumber" label="Kontonr."></v-text-field>

                                </v-col>
                            </v-row>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="info" type="submit">Fortsæt</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-form>
            </v-stepper-content>
            <v-stepper-content step="4">
                <v-alert color="purple" dark :value="true">
                    Du har nu oprettet dit firma. Tryk Fortsæt for at gå videre til oprettelse af et Sted.
                </v-alert>

                <v-btn to="/places" color="purple" dark>Fortsæt</v-btn>
            </v-stepper-content>
        </v-stepper-items>
    </v-stepper>

</template>

<script>
    import countriesArray from "../data/countries";
    import Errors from "../../common/Errors";
  export default {
    name: "CreateCompany",
    components: {
      Errors,
    },
    data() {
      return {
        countries: countriesArray,
        address: '',
        city: '',
        zip: '',
        country: 'Denmark',
        title: '',
        cvr: '',
        orderId: null,
        errors: [],
        step: 1,
        company: null,
        regNr: '',
        accountNumber: '',
        phone: '',
        email: ''
      }
    },
    mounted() {
      if (this.$store.state.user.company) {
        this.$router.push('/');
        return;
      }
    },
    methods: {
      validateCompany() {
        this.errors = [];
        if (!this.title) this.errors.push('Firmanavn er påkrævet');
        if (!this.cvr) this.errors.push('CVR er påkrævet');
        if (this.errors.length) return;

        this.step = 2;
      },
      validateAddress() {
        this.errors = [];
        if (!this.phone) this.errors.push('Telefon er påkrævet');
        if (!this.email) this.errors.push('Email er påkrævet');
        else if (!this.$store.state.helpers.validateEmail(this.email)) this.errors.push('Forkert email format');
        if (!this.address) this.errors.push('Adresse er påkrævet');
        if (!this.city) this.errors.push('By er påkrævet');
        if (!this.zip) this.errors.push('Postnr. er påkrævet');
        if (this.errors.length) return;

        this.step = 3;
      },
      validateBank() {
        const data = {
          title: this.title,
          cvr: this.cvr,
          address: this.address,
          city: this.city,
          zip: this.zip,
          country: this.country,
          regNr: this.regNr,
          accountNumber: this.accountNumber,
          phone: this.phone,
          email: this.email
        };
        this.$store.dispatch('companies/create', data).then(() => {
          this.$notify('Du har nu oprettet dit firma');
          this.step = 4;
        })
      }
    }
  }
</script>

<style scoped>

</style>
