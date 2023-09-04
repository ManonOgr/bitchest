<template>
    <v-dialog v-model="dialog" max-width="800px">
      <v-card>
        <v-card-title>
          <span class="headline">{{ selectedCrypto.name }}</span>
          <v-spacer></v-spacer>
          <v-btn icon @click="closeDialog">
           X
          </v-btn>
        </v-card-title>
        <v-card-text>
          <canvas ref="cryptoChartCanvas"></canvas>
        </v-card-text>
      </v-card>
    </v-dialog>
  </template>
  
  <script>
  import Chart from "chart.js/auto";
  import "chartjs-adapter-date-fns";

  
  export default {
    props: {
      selectedCrypto: Object, // Prop pour recevoir les données de la crypto sélectionnée
    },
    data() {
      return {
        dialog: true,
        chart: null,
      };
    },
    watch: {
      dialog(val) {
        if (!val) {
          // Fermer le dialogue
          this.$emit("close");
        }
      },
    },
    mounted() {
      this.generateCryptoChart();
    },
    methods: {
      generateCryptoChart() {
        // Code pour générer le graphique en fonction des données de la crypto sélectionnée
        const cryptoData = this.getCryptoData(this.selectedCrypto.id);
  
        // Configuration du graphique
        const ctx = this.$refs.cryptoChartCanvas.getContext("2d");
        this.chart = new Chart(ctx, {
          type: "line",
          data: {
            labels: cryptoData.labels,
            datasets: [
              {
                label: `Cours de ${this.selectedCrypto.name}`,
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
              text: `Cours de ${this.selectedCrypto.name}`,
            },
          },
        });
      },
      // Fonction pour générer des données de graphique fictives pour la crypto sélectionnée
      getCryptoData(cryptoId) {
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
        const cryptoName = `Crypto ${cryptoId}`;
        return (
          (Math.random() > 0.4 ? 1 : -1) *
          (Math.random() > 0.49
            ? cryptoName.charCodeAt(0)
            : cryptoName.charCodeAt(cryptoName.length - 1)) *
          (Math.floor(Math.random() * 10 + 1) * 0.01)
        ).toFixed(2);
      },
      closeDialog() {
        this.dialog = false;
      },
    },
  };
  </script>
  
  <style scoped>
  /* Ajoutez vos styles CSS spécifiques au composant ici */
  </style>
  