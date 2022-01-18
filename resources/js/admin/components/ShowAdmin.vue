<template>
    <v-card>
        <v-card-title>{{ user.name }}</v-card-title>
        <v-card-text>
            <p v-if="user.root">Root Admin</p>
            <p>Email: {{ user.email }}</p>
        </v-card-text>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn :disabled="!!user.root" color="error" @click="$emit('deleting')">Delete</v-btn>
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
