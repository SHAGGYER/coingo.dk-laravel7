<template>
    <v-card>
        <v-card-title class="headline">Are you sure</v-card-title>
        <v-card-actions>
            <v-spacer></v-spacer>

            <v-btn
                    color="red darken-1"
                    text
                    @click="$emit('closed')"
            >
                No
            </v-btn>

            <v-btn
                    color="green darken-1"
                    text
                    @click="$emit('confirmed')"
            >
                Yes
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
  export default {
    name: "ConfirmDialog",
  }
</script>

<style scoped>

</style>
