<script>
  import { Bar } from 'vue-chartjs'

  export default {
    extends: Bar,
    props: ['chartdata'],
    data: () => ({
      options: {
        responsive: true,
        maintainAspectRatio: false
      }
    }),

    mounted () {
      this.renderChart(this.chartdata, this.options)
    }
  }
</script>

