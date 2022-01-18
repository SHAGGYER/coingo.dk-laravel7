<template>
    <div>
        <h1>Feedback</h1>
        <v-list class="mt-5" v-if="feedbacks.length">
            <v-list-item @click="showFeedbackDialog(feedback)" v-for="(feedback, index) in feedbacks" :key="feedback.id">
                User: {{ feedback.user.name }} &middot; Created at: {{ feedback.created_at }}
            </v-list-item>
        </v-list>
        <v-pagination
                v-if="feedbacks.length"
                v-model="page"
                :length="lastPage"
                total-visible="10"
                @input="getFeedback"
        ></v-pagination>
        <v-alert :value="!feedbacks.length && gotResults" type="info">
            No results.
        </v-alert>

        <v-dialog width="700" v-model="feedbackDialog">
            <ShowFeedback v-if="currentFeedback" :feedback="currentFeedback"></ShowFeedback>
        </v-dialog>
    </div>
</template>

<script>
  import ShowFeedback from "../components/ShowFeedback";
  export default {
    name: "Feedback",
    components: {ShowFeedback},
    data() {
      return {
        feedbacks: [],
        currentFeedback: null,
        feedbackDialog: false,
        lastPage: null,
        gotResults: false,
        page: 1
      }
    },
    mounted() {
      this.getFeedback();
    },
    methods: {
      showFeedbackDialog(feedback) {
        this.currentFeedback = feedback;
        this.feedbackDialog = true;
      },
      getFeedback() {
        const params = {
          page: this.page
        };
        this.$store.dispatch('feedback/browse', params).then(response => {
          this.feedbacks = response.data;
          this.lastPage = response.last_page;
          this.gotResults = true;
        })
      }
    }
  }
</script>

<style scoped>

</style>
