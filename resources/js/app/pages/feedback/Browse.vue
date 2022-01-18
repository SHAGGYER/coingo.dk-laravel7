<template>
    <div>
        <h1>Feedback</h1>

        <v-btn color="purple" dark @click="createFeedbackDialog = true">Opret Feedback</v-btn>

        <v-list class="mt-3" v-if="feedbacks.length">
            <v-list-item @click="openFeedbackDialog(feedback, index)" v-for="(feedback, index) in feedbacks" :key="feedback.id">
                <span>{{ feedback.created_at }}</span>
            </v-list-item>
        </v-list>

        <v-pagination
                v-model="page"
                v-if="feedbacks.length"
                :length="lastPage"
                total-visible="10"
                @input="getFeedback"
        ></v-pagination>

        <v-alert class="mt-5" type="info" :value="gotResults && !feedbacks.length">
            Ingen resultater
        </v-alert>

        <v-dialog width="700" v-model="createFeedbackDialog">
            <v-form @submit.prevent="createFeedback">
                <v-card>
                    <v-card-title>Opret Feedback</v-card-title>
                    <v-card-text>
                        <v-textarea v-model="feedbackBody"></v-textarea>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="purple" dark type="submit">Opret</v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
        </v-dialog>
        <v-dialog width="700" v-model="feedbackDialog" v-if="currentFeedback">
            <v-card>
                <v-card-title>Feedback</v-card-title>
                <v-card-text>
                    {{ currentFeedback.body }}
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="error" @click="deleteFeedback">Slet</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
  export default {
    name: "Browse",
    data() {
      return {
        createFeedbackDialog: false,
        feedbackDialog: false,
        lastPage: null,
        page: 1,
        currentFeedback: null,
        currentIndex: null,
        feedbackBody: '',
        feedbacks: [],
        gotResults: false,
      }
    },
    mounted() {
      this.getFeedback();
    },
    methods: {
      getFeedback() {
        const params = {
          page: this.page
        };
        this.$store.dispatch('feedback/browse', params).then(response => {
          this.feedbacks = response.data;
          this.lastPage = response.last_page;
          this.gotResults = true;
        })
      },
      openFeedbackDialog(feedback, index) {
        this.currentFeedback = feedback;
        this.feedbackDialog = true;
        this.currentIndex = index;
      },
      createFeedback() {
        if (!this.feedbackBody) return;
        const data = {
          body: this.feedbackBody
        };
        this.$store.dispatch('feedback/create', data).then(feedback => {
          this.feedbacks.push(feedback);
          this.createFeedbackDialog = false;
          this.feedbackBody = '';
        })
      },
      deleteFeedback() {
        const data = {
          id: this.currentFeedback.id
        };
        this.$store.dispatch('feedback/delete', data).then(() => {
          this.feedbacks.splice(this.currentIndex);
          this.feedbackDialog = false;
          this.$notify('Du har slettet denne feedback');
        })
      }
    }
  }
</script>

<style scoped>

</style>
