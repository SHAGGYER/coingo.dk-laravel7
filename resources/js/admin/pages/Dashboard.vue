<template>
    <div>
        <h1>Dashboard</h1>

        <v-row>
            <v-col sm="4">
                <v-card>
                    <v-card-title>Users</v-card-title>
                    <v-card-text>
                        <span class="display-4">{{ usersCount }}</span>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col sm="4">
                <v-card>
                    <v-card-title>Admins</v-card-title>
                    <v-card-text>
                        <span class="display-4">{{ adminsCount }}</span>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col sm="4">
                <v-card>
                    <v-card-title>Feedback</v-card-title>
                    <v-card-text>
                        <span class="display-4">{{ feedbackCount }}</span>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>

<script>
  export default {
    name: "Home",
    data() {
      return {
        baseUrl: BASE_URL,
        usersCount: 0,
        adminsCount: 0,
        feedbackCount: 0
      }
    },
    mounted() {
      this.getDashboardData();
    },
    methods: {
      getDashboardData() {
        this.$store.dispatch('admins/getDashboardData').then(response => {
          this.usersCount = response.usersCount;
          this.adminsCount = response.adminsCount;
          this.feedbackCount = response.feedbackCount;
        })
      }
    }
  }
</script>

