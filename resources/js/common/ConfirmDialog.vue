<template>
    <v-card>
        <v-card-title class="headline">Er du sikker?</v-card-title>
        <v-card-actions>
            <v-spacer></v-spacer>

            <v-btn
                    color="red darken-1"
                    text
                    @click="$emit('closed')"
            >
                Nej
            </v-btn>

            <v-btn
                    color="green darken-1"
                    text
                    @click="$emit('confirmed')"
            >
                Ja
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
  export default {
    name: "DeleteDialog",
    props: ['config'],
  }
</script>

<style scoped>

</style>
