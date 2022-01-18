<template>
    <ShowInvoice type="sell"></ShowInvoice>
</template>

<script>
    import ShowInvoice from "../../../components/ShowInvoice";
  export default {
    name: "Show",
    components: {
      ShowInvoice,
    },
    mounted() {
      if (!this.$store.state.user.company) {
        this.$router.push('/companies/create');
        return;
      }

      if (!this.$store.state.isSubscribed) {
        this.$router.push('/billing');
      }
    },
  }
</script>
