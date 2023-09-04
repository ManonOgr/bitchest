<template>
  <div>
    <!-- Code pour afficher le graphique en fonction de l'ID de la crypto -->
    <!-- Utilisez l'ID pour récupérer les données du graphique spécifique à cette crypto -->
    <canvas ref="cryptoChartCanvas"></canvas>
  </div>
</template>

<script>
import Chart from "chart.js/auto";
import "chartjs-adapter-date-fns";

export default {
  props: {
    cryptoId: Number, // Prop pour recevoir l'ID de la crypto
  },
  data() {
    return {
      chart: null,
    };
  },
  mounted() {
    // Code pour générer le graphique en fonction de l'ID de la crypto
    // Utilisez l'ID pour récupérer les données du graphique spécifique à cette crypto
    const cryptoData = this.getCryptoData(this.cryptoId);

    // Configuration du graphique
    const ctx = this.$refs.cryptoChartCanvas.getContext("2d");
    this.chart = new Chart(ctx, {
      type: "line",
      data: {
        labels: cryptoData.labels,
        datasets: [
          {
            label: `Cours de la crypto ${this.cryptoId}`,
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
                day: "MMM d", // Format d'affichage de la date
              },
            },
          },
          y: {
            beginAtZero: false,
            title: {
              display: true,
              text: "Variation de cotation",
            },
          },
        },
        title: {
          display: true,
          text: `Cours de la crypto ${this.cryptoId}`,
        },
      },
    });
  },
  methods: {
    // Code pour récupérer les données du graphique spécifique à cette crypto en fonction de son ID
    getCryptoData(cryptoId) {
      // Utilisez l'ID pour générer des données de graphique fictives en utilisant les fonctions PHP
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
    // Fonction pour générer la variation de cotation de la crypto monnaie sur un jour
    getCotationFor(cryptoId) {
      // Utilisez les fonctions PHP pour générer des données de cotation fictives
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
