<template>
   <div>
       <h1>Lager</h1>

       <v-btn class="mt-3" color="purple" dark @click="print">Udskriv</v-btn>

       <p class="my-5">Antal produkter: {{ items.length }}</p>

       <v-simple-table v-if="items.length" class="mt-5">
           <template v-slot:default>
               <thead>
               <tr>
                   <td>Navn</td>
                   <td>Sted</td>
                   <td>Enhed</td>
                   <td>Pris</td>
                   <td>Antal</td>
                   <td>Total</td>
               </tr>
               </thead>
               <tbody>
               <tr v-for="item in items" :key="item.id">
                   <td>{{ item.title }}</td>
                   <td>{{ item.place ? item.place.title : '-' }}</td>
                   <td>{{ getUnit(item.unit).text }}</td>
                   <td>{{ item.price | price }}</td>
                   <td>{{ item.quantity }}</td>
                   <td>{{ (item.price * item.quantity) | price }}</td>
               </tr>
               </tbody>
               <tfoot>
               <tr>
                   <td></td>
                   <td></td>
                   <td></td>
                   <td></td>
                   <td></td>
                   <td>{{ total }}</td>
               </tr>
               </tfoot>
           </template>
       </v-simple-table>
   </div>
</template>

<script>
    import units from "../../data/units";
  export default {
    name: "Warehouse",
    data() {
      return {
        items: [],
        units
      }
    },
    mounted() {
      if (!this.$store.state.user.company) {
        this.$router.push('/companies/create');
        return;
      }

      this.getCollection();
    },
    filters: {
        price(value) {
          return parseFloat(value).toFixed(2);
        }
    },
    computed: {
      total() {
        let total = 0;
        this.items.forEach(item => {
          total += item.quantity * item.price;
        })

        return total.toFixed(2);
      }
    },
    methods: {
      getUnit(key) {
        return this.units.find(unit => unit.key === key);
      },
      getCollection() {
        this.$store.dispatch('items/browse').then(items => {
          this.items = items;
        })
      },
      print() {
        window.open(BASE_URL + '/items/printWarehouse', '_blank');
      }
    }
  }
</script>

<style scoped>
    thead td {
        font-weight: bold;
    }
</style>
