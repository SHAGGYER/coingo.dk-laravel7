<template>
    <v-card>
        <v-card-title>{{ message.name }}</v-card-title>
        <v-card-text>
            <p>{{ message.email }}</p>
            <p>{{ message.body }}</p>
        </v-card-text>
    </v-card>
</template>

<script>
  export default {
    name: "ShowUser",
    props: ['message']
  }
</script>

<style scoped>

</style>
