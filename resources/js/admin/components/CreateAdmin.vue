<template>
    <v-form @submit.prevent="onSubmit">
        <v-card>
            <v-card-title>Create Admin</v-card-title>
            <v-card-text>
                <Errors :errors="errors"></Errors>
                <v-text-field v-model="name" label="Name"></v-text-field>
                <v-text-field v-model="email" label="Email"></v-text-field>
                <v-text-field v-model="password" type="password" label="Password"></v-text-field>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="purple" dark type="submit">Create</v-btn>
            </v-card-actions>
        </v-card>
    </v-form>
</template>

<script>
  import Errors from "../../common/Errors";
  export default {
    name: "CreateAdmin",
    components: {Errors},
    data() {
      return {
        name: '',
        email: '',
        password: '',
        errors: []
      }
    },
    methods: {
      onSubmit() {
        this.errors = [];
        if (!this.name) this.errors.push('Name is required');
        if (!this.email) this.errors.push('Email is required');
        if (!this.$store.state.helpers.validateEmail(this.email)) this.errors.push('Email has wrong format');
        if (!this.password) this.errors.push('Password is required');
        if (this.errors.length) return;

        const data = {
          name: this.name,
          email: this.email,
          password: this.password
        };

        this.$store.dispatch('admins/create', data).then(user => {
          this.$emit('created', user);
          this.name = '';
          this.email = '';
          this.password = '';
        })
      }
    }
  }
</script>

<style scoped>

</style>
