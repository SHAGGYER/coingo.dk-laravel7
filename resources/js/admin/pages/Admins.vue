<template>
    <div>
        <h1>Admins</h1>

        <v-btn class="mt-3" color="purple" dark @click="createAdminDialog = true">Create Admin</v-btn>

        <v-list class="mt-3">
            <v-list-item @click="showAdminDialog(user, index)" v-for="(user, index) in users" :key="user.id">
                <v-chip v-if="user.root" class="mr-3">Root Admin</v-chip>
                {{ user.name }}
            </v-list-item>
        </v-list>
        <v-pagination
                v-model="page"
                :length="lastPage"
                total-visible="10"
                @input="getAdmins"
        ></v-pagination>
        <v-alert :value="!users.length && gotResults" type="info">
            No results.
        </v-alert>

        <v-dialog width="700" v-model="userDialog">
            <ShowAdmin @deleting="isDeleting" v-if="currentAdmin" :user="currentAdmin"></ShowAdmin>
        </v-dialog>
        <v-dialog width="700" v-model="createAdminDialog">
            <CreateAdmin @created="onAdminCreated"></CreateAdmin>
        </v-dialog>
        <v-dialog width="300" v-model="deleteDialog">
            <ConfirmDialog @confirmed="deleteUser" @closed="deleteDialog = false"></ConfirmDialog>
        </v-dialog>
    </div>
</template>

<script>
  import ShowAdmin from "../components/ShowAdmin";
  import CreateAdmin from "../components/CreateAdmin";
  import ConfirmDialog from "../components/ConfirmDialog";
  export default {
    name: "Admins",
    components: {ConfirmDialog, CreateAdmin, ShowAdmin},
    data() {
      return {
        users: [],
        currentAdmin: null,
        currentIndex: null,
        createAdminDialog: false,
        userDialog: false,
        deleteDialog: false,
        lastPage: null,
        gotResults: false,
        page: 1
      }
    },
    mounted() {
      this.getAdmins();
    },
    methods: {
      isDeleting() {
        this.deleteDialog = true;
        this.userDialog = false;
      },
      onAdminCreated(user) {
        this.createAdminDialog = false;
        this.users.push(user);
      },
      showAdminDialog(user, index) {
        this.currentAdmin = user;
        this.userDialog = true;
        this.currentIndex = index;
      },
      getAdmins() {
        const params = {
          page: this.page
        };
        this.$store.dispatch('admins/browse', params).then(response => {
          this.users = response.data;
          this.lastPage = response.last_page;
          this.gotResults = true;
        })
      },
      deleteUser() {
        const data = {
          id: this.currentAdmin.id
        };
        this.$store.dispatch('admins/delete', data).then(() => {
          this.users.splice(this.currentIndex, 1);
          this.deleteDialog = false;
        })
      }
    }
  }
</script>

<style scoped>

</style>
