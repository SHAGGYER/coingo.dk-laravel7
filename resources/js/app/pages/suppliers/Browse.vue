<template>
    <div>
        <h1>Gennemse Leverandører</h1>

        <v-btn class="mt-3" @click="createDialog = true" color="purple" dark>Opret Leverandør</v-btn>

        <v-form @submit.prevent="getSuppliers" class="mt-5">
            <v-text-field v-model="searchQuery" label="Søg via navn"></v-text-field>
        </v-form>

        <v-list class="mt-3" v-if="suppliers.length">
            <v-list-item @click="openEditDialog(supplier, index)" v-for="(supplier, index) in suppliers" :key="supplier.id">
                <v-list-item-content>
                    {{ supplier.name }}
                </v-list-item-content>
            </v-list-item>
        </v-list>

        <v-pagination
                v-model="page"
                :length="lastPage"
                v-if="suppliers.length"
                total-visible="10"
                @input="getSuppliers"
        ></v-pagination>

        <v-alert class="mt-5" type="info" :value="gotResults && !suppliers.length">
            Ingen resultater
        </v-alert>

        <v-dialog v-model="editDialog" width="700">
            <EditContact @closed="editDialog = false" @updated="editDialog = false" @deleting="isDeleting" :contact="currentSupplier"></EditContact>
        </v-dialog>
        <v-dialog v-model="createDialog" width="700">
            <CreateContact @closed="createDialog = false" type-contact="supplier" @created="onCreated"></CreateContact>
        </v-dialog>
        <v-dialog v-model="deleteDialog" width="300">
            <DeleteDialog @confirmed="remove" @closed="deleteDialog = false"></DeleteDialog>
        </v-dialog>
    </div>
</template>

<script>
  import EditContact from "../../components/EditContact";
  import DeleteDialog from "../../../common/ConfirmDialog";
  import CreateContact from "../../components/CreateContact";
  export default {
    name: "Browse",
    components: {
      CreateContact,
      EditContact,
      DeleteDialog
    },
    data() {
      return {
        gotResults: false,
        editDialog: false,
        createDialog: false,
        deleteDialog: false,
        suppliers: [],
        lastPage: null,
        page: 1,
        searchQuery: '',
        currentSupplier: null,
        currentSupplierIndex: null
      }
    },
    mounted() {
      if (!this.$store.state.user.company) {
        this.$router.push('/companies/create');
        return;
      }
      this.getSuppliers();
    },
    methods: {
      remove() {
        this.$store.dispatch('suppliers/delete', this.currentSupplier.id).then(() => {
          this.$notify('Du har nu slettet denne leverandør');
          this.deleteDialog = false;
          this.suppliers.splice(this.currentSupplierIndex, 1);
        })
      },
      isDeleting() {
        this.editDialog = false;
        this.deleteDialog = true;
      },
      onCreated(supplier) {
        this.createDialog = false;
        this.suppliers.push(supplier);
      },
      openEditDialog(supplier, index) {
        this.editDialog = true;
        this.currentSupplier = supplier;
        this.currentSupplierIndex = index;
      },
      getSuppliers() {
        const params = {
          page: this.page,
          paginate: true,
          searchQuery: this.searchQuery
        };
        this.$store.dispatch('suppliers/browse', params).then(response => {
          this.gotResults = true;
          this.suppliers = response.data;
          this.lastPage = response.last_page;
        })
      }
    }
  }
</script>

<style scoped>

</style>
