<template>
  <v-dialog v-model="loading" persistent width="300">
    <v-card color="purple" dark>
      <v-card-text class="pt-3">
        <span class="mt-3">Vent venligst...</span>
        <v-progress-linear indeterminate color="white" class="mt-3 mb-0"></v-progress-linear>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  props: ["loading"],
};
</script>

<style>
</style>
