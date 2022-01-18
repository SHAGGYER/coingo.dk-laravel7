<template>
    <v-card>
        <v-card-title>{{ feedback.user.name }}</v-card-title>
        <v-card-text>
            <p>{{ feedback.body }}</p>
        </v-card-text>
    </v-card>
</template>

<script>
  export default {
    name: "ShowUser",
    props: ['feedback']
  }
</script>

<style scoped>

</style>
