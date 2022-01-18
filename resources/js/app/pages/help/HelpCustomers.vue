<template>
    <div>
        <h1>Hjælp: Kunder</h1>

        <v-divider class="my-3"></v-divider>

        <h3>Gennemse Kunder</h3>
        <p>
            Hvis du trykker på Ressourcer -> Kunder, kan du se en oversigt af
            dine steder. Der vises 10 per side, og nederst i midten kan du
            skifte sider.
        </p>

        <v-divider class="my-3"></v-divider>

        <h3>Søg</h3>
        <p>
            Du kan søge i dine Kunder. Blot indtast navnet på den Kunde
            du vil finde, og du vil få vist alle dem, der ligner det indtastede.
        </p>

        <v-divider class="my-3"></v-divider>

        <h3>Oprettelse af Kunde</h3>
        <p>
            Tryk på Opret Kunde øverst. Du vil få vist en dialog, hvor
            du bliver bedt om at indtaste data. Indtast disse data og tryk
            Enter eller på Opret. Kunden vises med det samme på siden, og
            dialogen lukkes.
        </p>

        <v-divider class="my-3"></v-divider>

        <h3>Redigering af Kunde</h3>
        <p>
            Når du trykker på et Kunde i oversigten, vil du få vist en dialog
            med Kundens data. Nu kan du redigere disse data og tryk gem for at gemme.
        </p>
        
        <v-divider class="my-3"></v-divider>

        <h3>Sletning af Kunde</h3>
        <p>
            Du kan slette et Kunde ved at trykke på et Kunde i oversigten, og trykke
            Slet nederst i dialogen.
        </p>
    </div>
</template>

<script>
  export default {
    name: "HelpPlaces"
  }
</script>

<style scoped>

</style>
