<template>
    <div>
        <h1>Hjælp: Steder</h1>

        <v-divider class="my-3"></v-divider>

        <h3>Gennemse Steder</h3>
        <p>
            Hvis du trykker på Ressourcer -> Steder, kan du se en oversigt af
            dine steder. Der vises 10 per side, og nederst i midten kan du
            skifte sider.
        </p>

        <v-divider class="my-3"></v-divider>

        <h3>Søg</h3>
        <p>
            Du kan søge i dine Steder. Blot indtast navnet på det Sted du vil
            finde, og du vil få vist alle dem, der ligner det indtastede.
        </p>

        <v-divider class="my-3"></v-divider>

        <h3>Oprettelse af Sted</h3>
        <p>
            Tryk på Opret Sted øverst. Du vil få vist en dialog, hvor
            du bliver bedt om at indtaste et navn. Indtast et navn og tryk
            Enter eller på Opret. Stedet vises med det samme på siden, og
            dialogen lukkes.
        </p>

        <v-divider class="my-3"></v-divider>

        <h3>Redigering af Sted</h3>
        <p>
            Når du trykker på et Sted i oversigten, vil du få vist en dialog
            med Stedets data. Nu kan du redigere navnet og tryk gem for at gemme.
        </p>

        <v-divider class="my-3"></v-divider>

        <h3>Sletning af Sted</h3>
        <p>
            Du kan slette et Sted ved at trykke på et Sted i oversigten, og trykke
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
