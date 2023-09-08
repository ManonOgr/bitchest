<template>
  <div>
    <canvas ref="cryptoChartCanvas"></canvas>
  </div>
</template>

<script>
import Chart from "chart.js/auto";
import "chartjs-adapter-date-fns";

export default {
  props: {
    cryptoId: Number, // Prop to receive the crypto ID
  },
  data() {
    return {
      chart: null,
    };
  },
  mounted() {
    // Code to generate the chart based on the crypto ID
    // Use the ID to fetch data for the specific crypto chart
    const cryptoData = this.getCryptoData(this.cryptoId);

    // Chart configuration
    const ctx = this.$refs.cryptoChartCanvas.getContext("2d");
    this.chart = new Chart(ctx, {
      type: "line",
      data: {
        labels: cryptoData.labels,
        datasets: [
          {
            label: `Prix ${this.cryptoId} Crypto`,
            data: cryptoData.values,
            borderColor: "rgba(75, 192, 192, 1)",
            borderWidth: 1,
            fill: false,
          },
        ],
      },
      options: {
        scales: {
          x: {
            type: "time",
            time: {
              unit: "day",
              displayFormats: {
                day: "MMM d", // Date display format
              },
            },
          },
          y: {
            beginAtZero: false,
            title: {
              display: true,
              text: "Price Variation",
            },
          },
        },
        title: {
          display: true,
          text: `Crypto ${this.cryptoId} Price`,
        },
      },
    });
  },
  methods: {
    // Code to fetch data for the specific crypto chart based on its ID
    getCryptoData(cryptoId) {
      // Use the ID to generate fictional chart data using PHP-like functions
      const labels = [];
      const values = [];
      for (let i = 0; i < 30; i++) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        const formattedDate = date.toISOString().slice(0, 10);
        labels.unshift(formattedDate);
        values.unshift(this.getCotationFor(cryptoId));
      }

      return { labels, values };
    },
    // Function to generate crypto currency price variation for a day
    getCotationFor(cryptoId) {
      // Use PHP-like functions to generate fictional price data
      const cryptoName = `Crypto ${cryptoId}`;
      return (
        (Math.random() > 0.4 ? 1 : -1) *
        (Math.random() > 0.49
          ? cryptoName.charCodeAt(0)
          : cryptoName.charCodeAt(cryptoName.length - 1)) *
        (Math.floor(Math.random() * 10 + 1) * 0.01)
      ).toFixed(2);
    },
  },
};
</script>
