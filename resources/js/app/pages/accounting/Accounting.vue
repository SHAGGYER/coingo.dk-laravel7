<template>
    <div>
        <h1>Regnskab</h1>
        <v-divider class="my-3"></v-divider>

        <LineChart v-if="chartData" :chartdata="chartData"></LineChart>

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
            <h2>Salgstotal inkl. moms: {{ collection.totalSell.toFixed(2) }}</h2>
            <h2>Købstotal inkl. moms: {{ collection.totalBuy.toFixed(2) }}</h2>
            <h2 class="display-2 purple--text mt-5">Resultat: {{ (collection.totalSell - collection.totalBuy).toFixed(2) }}</h2>
        </div>

        <v-alert :value="gotResults && collection && !collection.gotInvoices" type="info" class="mt-5">
            Ingen resultater
        </v-alert>
    </div>
</template>

<script>
  import LineChart from "./LineChart";
  export default {
    name: "Accounting",
    components: {LineChart},
    data() {
        return {
          gotResults: false,
          collection: null,
          startDate: '',
          startDateMenu: false,
          endDate: '',
          endDateMenu: false,
          chartData: null
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

      this.getChartData();
    },
    methods: {
        getChartData() {
            this.$store.dispatch('accounting/getChartData').then(data => {
              const dates = data.dates.map(date => date.month).reverse();
              const sell = data.sell.reverse();
              const buy = data.buy.reverse();
              this.chartData = {
                labels: dates,
                datasets: [
                  {
                    label: 'Salg',
                    backgroundColor: '#f87979',
                    data: sell
                  },
                  {
                    label: 'Køb',
                    backgroundColor: '#3F51B5',
                    data: buy
                  }
                ]
              }
            });
        },
        onSubmit() {
            const params = {
              startDate: this.startDate,
              endDate: this.endDate
            };
            this.getAccounting(params);
        },
        getAccounting(params) {
            this.$store.dispatch('accounting/getAccounting', params).then(collection => {
              this.gotResults = true;
              this.collection = collection;
            })
        }
    }
  }
</script>

<style scoped>

</style>
