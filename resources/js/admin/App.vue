<template>
    <v-app>
        <LoadingDialog :loading="$store.state.loading"></LoadingDialog>

        <v-content v-if="initiated">
            <v-layout class="fill-height">
                <Sidebar />
                <v-container fluid>
                    <v-row>
                        <v-col sm="12" md="10" lg="8">
                            <router-view></router-view>
                        </v-col>
                    </v-row>
                </v-container>
            </v-layout>

        </v-content>
    </v-app>
</template>

<script>

  import LoadingDialog from "./components/LoadingDialog";
  import Sidebar from "./components/Sidebar";
  export default {
    name: "App",
    data() {
      return {
        initiated: false
      }
    },
    components: {
      LoadingDialog,
      Sidebar
    },
    mounted() {
      this.init();
    },
    methods: {
      init() {
        this.$store.dispatch('auth/init').then(() => {
          this.initiated = true;
        })
      }
    }
  }
</script>

<style scoped>

</style>
