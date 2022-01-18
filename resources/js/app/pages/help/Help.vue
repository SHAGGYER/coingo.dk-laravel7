<template>
    <div>
        <h1>Hjælp</h1>
        <v-row class="mt-3">
            <v-col v-for="(link, index) in links" :key="index" sm="3">
                <v-card>
                    <v-card-title>
                        <v-spacer></v-spacer>
                        <span>{{ link.title }}</span>
                        <v-spacer></v-spacer>
                    </v-card-title>
                    <v-divider class="my-3"></v-divider>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn text color="purple" dark :to="link.href">Se</v-btn>
                        <v-spacer></v-spacer>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>

<script>
  export default {
    name: "Help",
    data() {
      return {
        links: [
          {
            title: 'Steder',
            href: '/help/places',
          },
          {
            title: 'Produkter',
            href: '/help/products',
          },
          {
            title: 'Kunder',
            href: '/help/customers',
          },
          {
            title: 'Leverandører',
            href: '/help/suppliers',
          }
        ]
      }
    }
  }
</script>

<style scoped>

</style>
