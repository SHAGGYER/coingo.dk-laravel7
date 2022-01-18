<template>
    <div>
        <h1>Gennemse Salgsfakturer</h1>

        <v-alert class="mt-5" :value="true" color="purple" dark>
            <v-icon>mdi-star-four-points</v-icon>
            <span>Dette er en feature, som du kun kan bruge, når du har et aktivt abonnement</span>
        </v-alert>

        <v-btn class="mt-3" to="/invoices/sell/create" color="purple" dark>Opret Salgsfaktura</v-btn>

        <v-form @submit.prevent="getSellInvoices">
            <v-text-field v-model="searchQuery" label="Søg via fakturanummer"></v-text-field>
        </v-form>

        <v-list class="mt-3" v-if="invoices.length">
            <v-list-item :to="{ name: 'sell-invoice', params: { id: invoice.id } }" v-for="invoice in invoices" :key="invoice.id">
                <v-chip class="mx-3" color="purple" dark v-if="invoice.paid">Betalt</v-chip>
                <v-chip class="mx-3">Kunde</v-chip> {{ invoice.contact.name }}
                <v-chip class="mx-3">Betalingsdato</v-chip> {{ invoice.payment_date }}
                <v-chip class="mx-3">Fakturanr.</v-chip> {{ invoice.invoice_number }}

            </v-list-item>
        </v-list>

        <v-pagination
                v-if="invoices.length"
                v-model="page"
                :length="lastPage"
                total-visible="10"
                @input="getSellInvoices"
        ></v-pagination>

        <v-alert class="mt-5" type="info" :value="gotResults && !invoices.length">
            Ingen resultater
        </v-alert>
    </div>
</template>

<script>
  export default {
    name: "Browse",
    data() {
      return {
        gotResults: false,
        invoices: [],
        lastPage: null,
        page: 1,
        searchQuery: ''
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

      this.getSellInvoices();
    },
    methods: {
      getSellInvoices() {
        const params = {
          page: this.page,
          paginate: true,
          searchQuery: this.searchQuery,
          type: 'sell'
        };
        this.$store.dispatch('invoices/browse', params).then(response => {
          this.gotResults = true;
          this.invoices = response.data;
          this.lastPage = response.last_page;
        })
      }
    }
  }
</script>

<style scoped>

</style>
