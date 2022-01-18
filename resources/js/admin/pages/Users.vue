<template>
    <div>
        <h1>Users</h1>

        <v-form @submit.prevent="getUsers">
            <v-card>
                <v-card-text>
                    <v-text-field v-model="searchQuery" label="Search by name or email"></v-text-field>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="purple" dark type="submit">Search</v-btn>
                </v-card-actions>
            </v-card>
        </v-form>

        <v-list class="mt-5" v-if="users.length">
            <v-list-item @click="showUserDialog(user, index)" v-for="(user, index) in users" :key="user.id">
                Name: {{ user.name }} &middot; Email: {{ user.email }}
            </v-list-item>
        </v-list>
        <v-pagination
                v-if="users.length"
                v-model="page"
                :length="lastPage"
                total-visible="10"
                @input="getUsers"
        ></v-pagination>
        <v-alert class="mt-3" :value="!users.length && gotResults" type="info">
            No results.
        </v-alert>

        <v-dialog width="700" v-model="userDialog">
            <ShowUser @resume="resumeDialog = true"
                      @cancel="cancelDialog = true"
                      v-if="currentUser"
                      :user="currentUser">
            </ShowUser>
        </v-dialog>
        <v-dialog width="300" v-model="resumeDialog">
            <ConfirmDialog @confirmed="resumeSubscription" @closed="resumeDialog = false"></ConfirmDialog>
        </v-dialog>
        <v-dialog width="300" v-model="cancelDialog">
            <ConfirmDialog @confirmed="cancelSubscription" @closed="cancelDialog = false"></ConfirmDialog>
        </v-dialog>
    </div>
</template>

<script>
  import ShowUser from "../components/ShowUser";
  import ConfirmDialog from "../components/ConfirmDialog";
  export default {
    name: "Users",
    components: {ConfirmDialog, ShowUser},
    data() {
      return {
        users: [],
        currentUser: null,
        currentIndex: null,
        userDialog: false,
        resumeDialog: false,
        cancelDialog: false,
        lastPage: null,
        gotResults: false,
        page: 1,
        searchQuery: ''
      }
    },
    mounted() {
      this.getUsers();
    },
    methods: {
      showUserDialog(user, index) {
        this.currentUser = user;
        this.currentIndex = index;
        this.userDialog = true;
      },
      getUsers() {
        const params = {
          page: this.page,
          searchQuery: this.searchQuery
        };
        this.$store.dispatch('users/browse', params).then(response => {
          this.users = response.data;
          this.lastPage = response.last_page;
          this.gotResults = true;
        })
      },
      resumeSubscription(){
        const data = {
          id: this.currentUser.id
        };

        this.$store.dispatch('users/resumeSubscription', data).then(() => {
          this.currentUser.onGracePeriod = false;
          this.resumeDialog = false;
        })
      },
      cancelSubscription() {
        const data = {
          id: this.currentUser.id
        };

        this.$store.dispatch('users/cancelSubscription', data).then(subscription => {
          this.currentUser.onGracePeriod = true;
          this.currentUser.subscription = subscription;
          this.cancelDialog = false;
        })
      }
    }
  }
</script>

<style scoped>

</style>
