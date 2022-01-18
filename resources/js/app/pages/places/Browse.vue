<template>
    <div>
        <h1>Gennemse Steder</h1>

        <v-btn class="mt-3" @click="createDialog = true" color="purple" dark>Opret Sted</v-btn>

        <v-form @submit.prevent="getPlaces" class="mt-5">
            <v-text-field v-model="searchQuery" label="Søg via titel"></v-text-field>
        </v-form>

        <v-list class="mt-3" v-if="places.length">
            <v-list-item @click="openEditDialog(place, index)" v-for="(place, index) in places" :key="place.id">
                <v-list-item-content>
                    {{ place.title }}
                </v-list-item-content>
            </v-list-item>
        </v-list>

        <v-pagination
                v-model="page"
                :length="lastPage"
                v-if="places.length"
                total-visible="10"
                @input="getPlaces"
        ></v-pagination>

        <v-alert class="mt-5" type="info" :value="gotResults && !places.length">
            Ingen resultater
        </v-alert>

        <v-dialog v-model="editDialog" width="500">
            <EditForm @closed="editDialog = false" @updated="editDialog = false" @deleting="isDeleting" :place="currentPlace"></EditForm>
        </v-dialog>
        <v-dialog v-model="createDialog" width="500">
            <CreateForm @closed="createDialog = false" @created="onCreated"></CreateForm>
        </v-dialog>
        <v-dialog v-model="deleteDialog" width="300">
            <DeleteDialog @confirmed="remove" @closed="deleteDialog = false"></DeleteDialog>
        </v-dialog>

    </div>
</template>

<script>
  import EditForm from './EditForm';
  import CreateForm from "./CreateForm";
  import DeleteDialog from "../../../common/ConfirmDialog";
  export default {
    name: "BrowsePlaces",
    components: {
      EditForm,
      CreateForm,
      DeleteDialog
    },
    data() {
      return {
        gotResults: false,
        editDialog: false,
        createDialog: false,
        deleteDialog: false,
        places: [],
        lastPage: null,
        page: 1,
        searchQuery: '',
        currentPlace: null,
        currentPlaceIndex: null
      }
    },
    mounted() {
      if (!this.$store.state.user.company) {
        this.$router.push('/companies/create');
        return;
      }
      this.getPlaces();
    },
    methods: {
      remove() {
        this.$store.dispatch('places/delete', this.currentPlace.id).then(() => {
          this.$notify('Du har nu slettet dette sted');
          this.deleteDialog = false;
          this.places.splice(this.currentPlaceIndex, 1);
        })
      },
      isDeleting() {
        this.editDialog = false;
        this.deleteDialog = true;
      },
      onCreated(place) {
        this.createDialog = false;
        this.places.push(place);
      },
      openEditDialog(place, index) {
        this.editDialog = true;
        this.currentPlace = place;
        this.currentPlaceIndex = index;
      },
      getPlaces() {
        const params = {
          page: this.page,
          paginate: true,
          searchQuery: this.searchQuery
        };
        this.$store.dispatch('places/browse', params).then(response => {
          this.gotResults = true;
          this.places = response.data;
          this.lastPage = response.last_page;
        })
      }
    }
  }
</script>

<style scoped>

</style>
