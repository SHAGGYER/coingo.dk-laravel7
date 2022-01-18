<template>
    <div>
        <h1>Contact Messages</h1>
        <v-list class="mt-5" v-if="contactMessages.length">
            <v-list-item @click="showContactDialog(message)" v-for="(message, index) in contactMessages" :key="message.id">
                Name: {{ message.name }} &middot;
                Created at: {{ message.created_at }}
            </v-list-item>
        </v-list>
        <v-pagination
                v-if="contactMessages.length"
                v-model="page"
                :length="lastPage"
                total-visible="10"
                @input="getContactMessages"
        ></v-pagination>
        <v-alert :value="!contactMessages.length && gotResults" type="info">
            No results.
        </v-alert>

        <v-dialog width="700" v-model="contactDialog">
            <ShowContact v-if="currentMessage" :message="currentMessage"></ShowContact>
        </v-dialog>
    </div>
</template>

<script>
  import ShowContact from "../components/ShowContact";
  export default {
    name: "Contact",
    components: {ShowContact},
    data() {
      return {
        contactMessages: [],
        currentMessage: null,
        contactDialog: false,
        lastPage: null,
        gotResults: false,
        page: 1
      }
    },
    mounted() {
      this.getContactMessages();
    },
    methods: {
      showContactDialog(message) {
        this.currentMessage = message;
        this.contactDialog = true;
      },
      getContactMessages() {
        const params = {
          page: this.page
        };
        this.$store.dispatch('contact/browse', params).then(response => {
          this.contactMessages = response.data;
          this.lastPage = response.last_page;
          this.gotResults = true;
        })
      }
    }
  }
</script>

<style scoped>

</style>
