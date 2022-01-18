<template>
    <v-app>
        <LoadingDialog :loading="$store.state.loading"></LoadingDialog>

        <v-content v-if="initiated">
            <v-layout class="fill-height">
                <Sidebar />
                <v-container fluid>
                    <v-btn v-if="$vuetify.breakpoint.smAndDown" @click="toggleSidebar">
                        <v-icon>menu</v-icon>
                    </v-btn>
                    <v-row>
                        <v-col sm="12" md="10" lg="8">
                            <router-view></router-view>
                        </v-col>
                    </v-row>
                </v-container>
            </v-layout>

        </v-content>
        <notifications position="bottom center" />
    </v-app>
</template>

<script>
    import Sidebar from "../app/components/Sidebar";
    import LoadingDialog from "../common/LoadingDialog";
  export default {
    name: "App",
    data() {
      return {
        initiated: false
      }
    },
    components: {
      LoadingDialog,
      Sidebar,
    },
    mounted() {
      this.$store.dispatch('auth/init').then(() => {
        this.initiated = true;
      })
    },
    methods: {
      toggleSidebar() {
        this.$store.commit('setSidebarToggle');
      }
    }
  }
</script>

<style scoped>

</style>
