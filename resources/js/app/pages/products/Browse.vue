<template>
    <div>
        <h1>Gennemse Produkter</h1>

        <v-btn class="mt-3" @click="createDialog = true" color="purple" dark>Opret Produkt</v-btn>

        <v-form @submit.prevent="getItems" class="mt-5">
            <v-text-field v-model="searchQuery" label="Søg via titel eller varekode"></v-text-field>
        </v-form>

        <v-list class="mt-3" v-if="items.length">
            <v-list-item @click="openEditDialog(item, index)" v-for="(item, index) in items" :key="item.id">
                <span>{{ item.title }}</span>
                <span v-if="item.code">&nbsp; &middot; &nbsp;</span>
                <span v-if="item.code">Varekode: {{ item.code }}</span>
                <span v-if="item.place">&nbsp; &middot; &nbsp;</span>
                <span v-if="item.place">Sted: {{ item.place.title }}</span>
            </v-list-item>
        </v-list>

        <v-pagination
                v-model="page"
                v-if="items.length"
                :length="lastPage"
                total-visible="10"
                @input="getItems"
        ></v-pagination>

        <v-alert class="mt-5" type="info" :value="gotResults && !items.length">
            Ingen resultater
        </v-alert>

        <v-dialog v-model="createDialog" width="700">
            <CreateForm @closed="createDialog = false" @created="onCreated"></CreateForm>
        </v-dialog>
        <v-dialog v-model="editDialog" width="700">
            <EditForm @closed="editDialog = false" @updated="editDialog=false" @deleting="isDeleting" :item="currentItem"></EditForm>
        </v-dialog>
        <v-dialog v-model="deleteDialog" width="300">
            <DeleteDialog @closed="deleteDialog = false" @confirmed="remove"></DeleteDialog>
        </v-dialog>
    </div>
</template>

<script>
  import EditForm from './EditForm'
  import CreateForm from "./CreateForm"
  import DeleteDialog from "../../../common/ConfirmDialog";
  export default {
    name: "BrowseItems",
    components: {
      CreateForm,
      EditForm,
      DeleteDialog
    },
    data() {
      return {
        gotResults: false,
        items: [],
        lastPage: null,
        page: 1,
        searchQuery: '',
        createDialog: false,
        editDialog: false,
        deleteDialog: false,
        currentItem: null,
        currentItemIndex: null
      }
    },
    mounted() {
      if (!this.$store.state.user.company) {
        this.$router.push('/companies/create');
        return;
      }
      this.getItems();
    },
    methods: {
      remove() {
        this.$store.dispatch('items/delete', this.currentItem.id).then(() => {
          this.$notify('Du har nu slettet dette produkt');
          this.deleteDialog = false;
          this.items.splice(this.currentItemIndex, 1);
        })
      },
      isDeleting() {
        this.editDialog = false;
        this.deleteDialog = true;
      },
      openEditDialog(item, index) {
        this.editDialog = true;
        this.currentItem = item;
        this.currentItemIndex = index;
      },
      onCreated(item) {
        this.createDialog = false;
        this.items.push(item);
      },
      getItems() {
        const params = {
          page: this.page,
          paginate: true,
          searchQuery: this.searchQuery
        };
        this.$store.dispatch('items/browse', params).then(response => {
          this.gotResults = true;
          this.items = response.data;
          this.lastPage = response.last_page;
        })
      }
    }
  }
</script>

<style scoped>

</style>
