<template>
    <v-navigation-drawer
            class="purple"
            dark
            v-model="$store.state.sidebarToggle"
            :absolute="$vuetify.breakpoint.smAndDown"
            :temporary="$vuetify.breakpoint.smAndDown"
            :permanent="$vuetify.breakpoint.smAndUp"
            :width="$vuetify.breakpoint.smAndUp ? 400 : 'auto'"
    >
        <h2 class="white--text pa-3 text-center"
            v-if="$store.state.user && $store.state.user.company">
            {{ $store.state.user.company.title }}
        </h2>

        <div class="pa-3" v-if="$store.state.user && $store.state.user.company">
            <v-autocomplete @input="searchInput" prepend-icon="search" label="Søg" v-model="search" :items="searchItems" return-object></v-autocomplete>
        </div>

        <v-list v-if="$store.state.user">
            <div v-if="$store.state.user.company">
                <v-list-item link to="/">
                    <v-list-item-icon>
                        <v-icon>dashboard</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Overblik</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item link to="/company">
                    <v-list-item-icon>
                        <v-icon>mdi-home-currency-usd</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Firma</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-group color="warning" prepend-icon="mdi-bank">
                    <template v-slot:activator>
                        <v-list-item-title>Ressourcer</v-list-item-title>
                    </template>
                    <v-list class="purple darken-2">
                        <v-list-item to="/places">
                            <v-list-item-icon>
                                <v-icon>mdi-align-horizontal-left</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>Steder</v-list-item-content>
                        </v-list-item>
                        <v-list-item to="/products">
                            <v-list-item-icon>
                                <v-icon>mdi-align-horizontal-right</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>Produkter</v-list-item-content>
                        </v-list-item>
                        <v-list-item to="/customers">
                            <v-list-item-icon>
                                <v-icon>mdi-account-heart</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>Kunder</v-list-item-content>
                        </v-list-item>
                        <v-list-item to="/suppliers">
                            <v-list-item-icon>
                                <v-icon>mdi-account-multiple-plus</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>Leverandører</v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-list-group>

                <v-list-item link to="/warehouse">
                    <v-list-item-icon>
                        <v-icon>mdi-cloud</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Lager</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-group color="warning" prepend-icon="mdi-account-badge">
                    <template v-slot:activator>
                        <v-list-item-title>Fakturer</v-list-item-title>
                    </template>
                    <v-list class="purple darken-2">
                        <v-list-item to="/invoices/sell/browse">
                            <v-list-item-icon>
                                <v-icon>mdi-cash-refund</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>Salgsfakturer</v-list-item-content>
                        </v-list-item>
                        <v-list-item to="/invoices/creditNote/browse">
                            <v-list-item-icon>
                                <v-icon>mdi-cash-refund</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>Kreditnotaer</v-list-item-content>
                        </v-list-item>
                        <v-list-item to="/invoices/reminder/browse">
                            <v-list-item-icon>
                                <v-icon>mdi-cash-usd</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>Rykkere</v-list-item-content>
                        </v-list-item>
                        <v-list-item to="/invoices/buy/browse">
                            <v-list-item-icon>
                                <v-icon>mdi-coin-outline</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>Købsfakturer</v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-list-group>


                <v-list-group color="warning" prepend-icon="mdi-account-cash">
                    <template v-slot:activator>
                        <v-list-item-title>Regnskab</v-list-item-title>
                    </template>
                    <v-list class="purple darken-2">
                        <v-list-item to="/accounting/see">
                            <v-list-item-icon>
                                <v-icon>mdi-vector-arrange-below</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>Regnskab Overblik</v-list-item-content>
                        </v-list-item>
                        <v-list-item to="/accounting/vat">
                            <v-list-item-icon>
                                <v-icon>mdi-vector-difference-ab</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>Moms Overblik</v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-list-group>


                <v-list-item link to="/feedback">
                    <v-list-item-icon>
                        <v-icon>feedback</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Feedback</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item link to="/help">
                    <v-list-item-icon>
                        <v-icon>feedback</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Hjælp</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-group color="warning" prepend-icon="mdi-account">
                    <template v-slot:activator>
                        <v-list-item-title>Konto</v-list-item-title>
                    </template>
                    <v-list class="purple darken-2">
                        <v-list-item to="/billing">
                            <v-list-item-icon>
                                <v-icon>mdi-coin-outline</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>Betalinger</v-list-item-content>
                        </v-list-item>
                        <v-list-item to="/settings">
                            <v-list-item-icon>
                                <v-icon>settings</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>Indstillinger</v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-list-group>
            </div>
            <div v-else>
                <v-list-item link to="/companies/create">
                    <v-list-item-icon>
                        <v-icon>create</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Opret Firma</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </div>
        </v-list>

        <template v-slot:append>
            <div class="pa-2">
                <v-btn block @click="logout">Log Ud</v-btn>
            </div>
        </template>
    </v-navigation-drawer>
</template>

<script>
  export default {
    name: "Sidebar",
    data() {
      return {
        search: '',
        searchItems: [
          {
            text: 'Overblik',
            goTo: '/'
          },
          {
            text: 'Steder',
            goTo: '/places'
          },
          {
            text: 'Produkter',
            goTo: '/products'
          },
          {
            text: 'Kunder',
            goTo: '/customers'
          },
          {
            text: 'Leverandører',
            goTo: '/suppliers'
          },
          {
            text: 'Firma',
            goTo: '/company'
          },
          {
            text: 'Lager',
            goTo: '/warehouse'
          },
          {
            text: 'Salgsfakturer',
            goTo: '/invoices/sell/browse'
          },
          {
            text: 'Opret Salgsfaktura',
            goTo: '/invoices/sell/create'
          },
          {
            text: 'Rykkere',
            goTo: '/invoices/reminders/browse'
          },
          {
            text: 'Købsfakturer',
            goTo: '/invoices/buy/browse'
          },
          {
            text: 'Opret Købsfaktura',
            goTo: '/invoices/buy/create'
          },
          {
            text: 'Regnskab Overblik',
            goTo: '/accounting/see'
          },
          {
            text: 'Moms Overblik',
            goTo: '/accounting/vat'
          },
          {
            text: 'Betalinger',
            goTo: '/billing'
          },
          {
            text: 'Feedback',
            goTo: '/feedback'
          },

          {
            text: 'Indstillinger',
            goTo: '/settings'
          },
        ]
      }
    },
    methods: {
      logout() {
        this.$store.dispatch('auth/logout').then(() => {
          window.location = BASE_URL;
        });
      },
      searchInput(item) {
        this.$router.push(item.goTo).catch(err => {});
      }
    }
  }
</script>

<style scoped>

</style>
