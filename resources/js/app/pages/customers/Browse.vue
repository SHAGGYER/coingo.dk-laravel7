<template>
    <div>
        <h1>Gennemse Kunder</h1>

        <v-btn class="mt-3" @click="createDialog = true" color="purple" dark>Opret Kunde</v-btn>

        <v-form @submit.prevent="getContacts" class="mt-5">
            <v-text-field v-model="searchQuery" label="Søg via navn"></v-text-field>
        </v-form>

        <v-list class="mt-3" v-if="contacts.length">
            <v-list-item @click="openEditDialog(contact, index)" v-for="(contact, index) in contacts" :key="contact.id">
                <v-list-item-content>
                    {{ contact.name }}
                </v-list-item-content>
            </v-list-item>
        </v-list>

        <v-pagination
                v-model="page"
                :length="lastPage"
                v-if="contacts.length"
                total-visible="10"
                @input="getContacts"
        ></v-pagination>

        <v-alert class="mt-5" type="info" :value="gotResults && !contacts.length">
            Ingen resultater
        </v-alert>

        <v-dialog v-model="editDialog" width="700">
            <EditContact @closed="editDialog = false"
                         @updated="editDialog = false"
                         @deleting="isDeleting"
                         :contact="currentContact">
            </EditContact>
        </v-dialog>
        <v-dialog v-model="createDialog" width="700">
            <CreateContact @closed="createDialog = false" type-contact="customer" @created="onCreated"></CreateContact>
        </v-dialog>
        <v-dialog v-model="deleteDialog" width="300">
            <DeleteDialog @confirmed="remove" @closed="deleteDialog = false"></DeleteDialog>
        </v-dialog>

    </div>
</template>

<script>
  import DeleteDialog from "../../../common/ConfirmDialog";
  import EditContact from "../../components/EditContact";
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
        contacts: [],
        lastPage: null,
        page: 1,
        searchQuery: '',
        currentContact: null,
        currentContactIndex: null
      }
    },
    mounted() {
      if (!this.$store.state.user.company) {
        this.$router.push('/companies/create');
        return;
      }
      this.getContacts();
    },
    methods: {
      remove() {
        this.$store.dispatch('contacts/delete', this.currentContact.id).then(() => {
          this.$notify('Du har nu slettet denne kunde');
          this.deleteDialog = false;
          this.contacts.splice(this.currentContactIndex, 1);
        })
      },
      isDeleting() {
        this.editDialog = false;
        this.deleteDialog = true;
      },
      onCreated(contact) {
        this.createDialog = false;
        this.contacts.push(contact);
      },
      openEditDialog(contact, index) {
        this.editDialog = true;
        this.currentContact = contact;
        this.currentContactIndex = index;
      },
      getContacts() {
        const params = {
          page: this.page,
          paginate: true,
          searchQuery: this.searchQuery
        };
        this.$store.dispatch('contacts/browse', params).then(response => {
          this.gotResults = true;
          this.contacts = response.data;
          this.lastPage = response.last_page;
        })
      }
    }
  }
</script>

<style scoped>

</style>
