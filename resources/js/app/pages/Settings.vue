<template>
    <div>
        <h1>Indstillinger</h1>

        <v-btn v-if="!$store.state.user.email_verified" block color="purple" dark @click="resendVerificationDialog = true">
            Send Email Verifikation Igen
        </v-btn>

        <v-form @submit.prevent="changeEmail" class="mt-3">
            <v-card>
                <v-card-title>Skift Email</v-card-title>
                <v-card-text>
                    <Errors :errors="emailErrors"></Errors>
                    <v-text-field v-model="email" label="Email"></v-text-field>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="purple" dark type="submit">Skift</v-btn>
                </v-card-actions>
            </v-card>
        </v-form>

        <v-form @submit.prevent="changePassword" class="mt-3">
            <v-card>
                <v-card-title>Skift Kodeord</v-card-title>
                <v-card-text>
                    <Errors :errors="passwordErrors"></Errors>
                    <v-text-field v-model="password" type="password" label="Kodeord"></v-text-field>
                    <v-text-field v-model="passwordAgain" type="password" label="Kodeord Igen"></v-text-field>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="purple" dark type="submit">Skift</v-btn>
                </v-card-actions>
            </v-card>
        </v-form>

        <v-dialog width="300" v-model="resendVerificationDialog">
            <ConfirmDialog @confirmed="resendVerification" @closed="resendVerificationDialog = false"></ConfirmDialog>
        </v-dialog>
    </div>
</template>

<script>
  import Errors from "../../common/Errors";
  import ConfirmDialog from "../../common/ConfirmDialog";
  export default {
    name: "Settings",
    components: {Errors, ConfirmDialog},
    data() {
      return {
        languages: [
          {
            text: 'English',
            value: 'english'
          },
          {
            text: 'Dansk',
            value: 'danish'
          }
        ],
        resendVerificationDialog: false,
        language: '',
        password: '',
        passwordAgain: '',
        emailErrors: [],
        languageErrors: [],
        passwordErrors: [],
        email: ''
      }
    },
    methods: {
      resendVerification() {
        this.$store.dispatch('users/resendEmailVerification').then(() => {
          this.$notify('Du har sendt verifikationen igen');
          this.resendVerificationDialog = false;
        })
      },
      changeLanguage() {
        this.languageErrors = [];
        if (!this.language) this.languageErrors.push('Sprog er påkrævet');
        if (this.languageErrors.length) return;

        this.$cookies.set('language', this.language);
        location.reload();
      },
      changeEmail() {
        this.emailErrors = [];
        if (!this.email) this.emailErrors.push('Email er påkrævet');
        else if (!this.$store.state.helpers.validateEmail(this.email)) this.emailErrors.push('Email har forkert format');
        if (this.emailErrors.length) return;

        const data = {
          email: this.email
        };
        this.$store.dispatch('users/changeEmail', data).then(() => {
          this.$notify('Du har nu skiftet din email. Tjek din indbakke (også spam) for at verificere den.');
          this.changeEmailDialog = false;
        }).catch(error => this.errors.push(error));
      },
      changePassword() {
        this.passwordErrors = [];
        if (!this.password) this.passwordErrors.push('Kodeord er påkrævet');
        if (!this.passwordAgain) this.passwordErrors.push('Kodeord bekræftelse er påkrævet');
        else if (this.password !== this.passwordAgain) this.passwordErrors.push('Kodeord er ikke ens');
        if (this.passwordErrors.length) return;

        const data = {
          password: this.password
        };
        this.$store.dispatch('users/changePassword', data).then(() => {
          this.$notify('Du har nu skiftet dit kodeord');
          this.password = '';
          this.passwordAgain = '';
        })
      },
    }
  }
</script>

<style scoped>

</style>
