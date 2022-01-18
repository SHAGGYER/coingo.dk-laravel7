<template>
    <v-navigation-drawer
            class="purple"
            dark
            permanent
            width="400"
    >
        <v-list>
            <v-list-item link to="/">
                <v-list-item-icon>
                    <v-icon>dashboard</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title>Dashboard</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-list-item link to="/users">
                <v-list-item-icon>
                    <v-icon>person</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title>Users</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-list-item link to="/contact-messages">
                <v-list-item-icon>
                    <v-icon>feedback</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title>Contact Messages</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-list-item link to="/feedback">
                <v-list-item-icon>
                    <v-icon>feedback</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title>Feedback</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-list-item link to="/admins">
                <v-list-item-icon>
                    <v-icon>lock</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title>Admins</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </v-list>

        <template v-slot:append>
            <div class="pa-2">
                <v-btn block @click="logout">Logout</v-btn>
            </div>
        </template>
    </v-navigation-drawer>
</template>

<script>
  export default {
    name: "Sidebar",
    methods: {
      logout() {
        this.$store.dispatch('auth/logout').then(() => {
          window.location = BASE_URL + '/admin';
        });
      }
    }
  }
</script>

<style scoped>

</style>
