<template>
    <div>
      <h1>Graphique Crypto-monnaies</h1>
      <chart></chart>
    </div>
  </template>
  
  <script>
  import Chart from "@/components/CryptoChart.vue"; // Assurez-vous d'importer le composant correctement
  
  export default {
    components: {
      Chart,
    },
  };
  </script>
  