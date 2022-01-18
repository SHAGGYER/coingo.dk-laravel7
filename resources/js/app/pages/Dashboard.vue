<template>
    <div>
        <h1>Overblik</h1>

        <v-row>
            <v-col sm="4">
                <v-card>
                    <v-card-title>Steder</v-card-title>
                    <v-card-text>
                        <span class="display-4">{{ placesCount }}</span>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col sm="4">
                <v-card>
                    <v-card-title>Produkter</v-card-title>
                    <v-card-text>
                        <span class="display-4">{{ productsCount }}</span>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col sm="4">
                <v-card>
                    <v-card-title>Kunder</v-card-title>
                    <v-card-text>
                        <span class="display-4">{{ customersCount }}</span>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col sm="4">
                <v-card>
                    <v-card-title>Leverandører</v-card-title>
                    <v-card-text>
                        <span class="display-4">{{ suppliersCount }}</span>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col sm="4">
                <v-card>
                    <v-card-title>Salgsfakturer</v-card-title>
                    <v-card-text>
                        <span class="display-4">{{ sellInvoicesCount }}</span>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col sm="4">
                <v-card>
                    <v-card-title>Rykkere</v-card-title>
                    <v-card-text>
                        <span class="display-4">{{ remindersCount }}</span>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col sm="4">
                <v-card>
                    <v-card-title>Købfakturer</v-card-title>
                    <v-card-text>
                        <span class="display-4">{{ buyInvoicesCount }}</span>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>

<script>
  export default {
    name: "Dashboard",
    data() {
      return {
        placesCount: 0,
        productsCount: 0,
        customersCount: 0,
        suppliersCount: 0,
        sellInvoicesCount: 0,
        remindersCount: 0,
        buyInvoicesCount: 0
      }
    },
    mounted() {
      if (!this.$store.state.user.company) {
        this.$router.push('/companies/create');
        return;
      }

      this.getDashboardData();
    },
    methods: {
      getDashboardData() {
        this.$store.dispatch('users/getDashboardData').then(response => {
          this.placesCount = response.placesCount;
          this.productsCount = response.productsCount;
          this.customersCount = response.customersCount;
          this.suppliersCount = response.suppliersCount;
          this.sellInvoicesCount = response.sellInvoicesCount;
          this.remindersCount = response.remindersCount;
          this.buyInvoicesCount = response.buyInvoicesCount;
        })
      }
    }
  }
</script>

<style scoped>

</style>
