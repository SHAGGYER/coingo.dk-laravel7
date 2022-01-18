<template>
    <v-card>
        <v-card-title>{{ user.name }}</v-card-title>
        <v-card-text>
            <p>Email: {{ user.email }}</p>
            <p>Subscribed: {{ user.isSubscribed ? 'Yes' : 'No' }}</p>
            <p v-if="user.isSubscribed && user.subscription">Subscription Plan: {{ user.subscription.stripe_plan }}</p>
            <p>On Grace Period: {{ user.onGracePeriod ? 'Yes' : 'No' }}</p>
            <p v-if="user.onGracePeriod">Subscription ends at: {{ user.subscription.ends_at }}</p>
        </v-card-text>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="warning"
                   v-if="user.onGracePeriod"
                   @click="$emit('resume')">
                Resume Subscription
            </v-btn>
            <v-btn color="error"
                   v-if="user.isSubscribed && !user.onGracePeriod"
                   @click="$emit('cancel')">
                Cancel Subscription
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
  export default {
    name: "ShowUser",
    props: ['user']
  }
</script>

<style scoped>

</style>
