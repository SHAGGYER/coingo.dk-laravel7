<template>
    <div>
        <h1>Moms</h1>
        <v-divider class="my-3"></v-divider>

        <v-form @submit.prevent="onSubmit">
            <v-card>
                <v-card-title>Søg</v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col sm="6">
                            <v-menu
                                    v-model="startDateMenu"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                            v-model="startDate"
                                            label="Startdato"
                                            readonly
                                            v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker v-model="startDate" @input="startDateMenu = false"></v-date-picker>
                            </v-menu>
                        </v-col>
                        <v-col sm="6">
                            <v-menu
                                    v-model="endDateMenu"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                            v-model="endDate"
                                            label="Slutdato"
                                            readonly
                                            v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker v-model="endDate" @input="endDateMenu = false"></v-date-picker>
                            </v-menu>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn type="submit" color="purple" dark>Søg</v-btn>
                </v-card-actions>
            </v-card>
        </v-form>

        <div v-if="collection && collection.gotInvoices" class="mt-5">
            <h2>Salgsmoms: {{ (collection.totalSell - collection.totalCreditNote).toFixed(2) }}</h2>
            <h2>Købsmoms: {{ collection.totalBuy.toFixed(2) }}</h2>

            <h2 class="mt-5">Din moms er: <span class="display-2 purple--text">{{ collection.total.toFixed(2) }}</span></h2>
            <h2 v-if="collection.total < 0" class="purple--text">Du skal have moms tilbage</h2>
            <h2 v-else-if="collection.total > 0" class="purple--text">Du skal betale moms</h2>
            <h2 v-else class="purple--text">Du skal hverken betale eller have moms tilbage</h2>
        </div>

        <v-alert :value="gotResults && collection && !collection.gotInvoices" type="info" class="mt-5">
            Ingen resultater
        </v-alert>
    </div>
</template>

<script>
  export default {
    name: "Vat",
    data(){
      return {
        collection: null,
        startDate: '',
        startDateMenu: false,
        endDate: '',
        endDateMenu: false,
        gotResults: false,
      }
    },
    mounted() {
      if (!this.$store.state.user.company) {
        this.$router.push('/companies/create');
        return;
      }
      if (!this.$store.state.isSubscribed) {
        this.$router.push('/billing');
        return;
      }
    },
    methods:{
      onSubmit() {
        const params = {
          startDate: this.startDate,
          endDate: this.endDate
        };
        this.getVat(params);
      },
      getVat(params) {
        this.$store.dispatch('accounting/getVat', params).then(collection => {
          this.collection = collection;
          this.gotResults = true;
        })
      }
    }
  }
</script>

<style scoped>

</style>
