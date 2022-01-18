<template>
    <div>
        <h1>Gennemse Kreditnotaer</h1>


        <v-alert class="mt-5" :value="true" color="purple" dark>
            <v-icon>mdi-star-four-points</v-icon>
            <span>Dette er en feature, som du kun kan bruge, når du har et aktivt abonnement</span>
        </v-alert>

        <v-form @submit.prevent="getBuyInvoices">
            <v-text-field v-model="searchQuery" label="Søg via fakturanummer"></v-text-field>
        </v-form>

        <v-list class="mt-3" v-if="invoices.length">
            <v-list-item :to="{ name: 'credit-note', params: { id: invoice.id } }" v-for="invoice in invoices" :key="invoice.id">
                <v-chip class="mx-3">Kunde</v-chip> {{ invoice.contact.name }}
                <v-chip class="mx-3">Fakturanr.</v-chip> {{ invoice.invoice_number }}
                <v-chip class="mx-3">Salgsfaktura Nr.</v-chip> {{ invoice.parent ? invoice.parent.invoice_number : 'Ikke fundet' }}
            </v-list-item>
        </v-list>

        <v-pagination
                v-model="page"
                :length="lastPage"
                total-visible="10"
                v-if="invoices.length"
                @input="getBuyInvoices"
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

      this.getBuyInvoices();
    },
    methods: {
      getBuyInvoices() {
        const params = {
          page: this.page,
          paginate: true,
          searchQuery: this.searchQuery,
          type: 'creditNote'
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
