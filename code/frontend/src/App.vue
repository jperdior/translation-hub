<template>
  <div id="app">
    <img alt="Vue logo" src="./assets/logo.png" style="max-height: 150px;">
    <SearchTranslation/>
  </div>
</template>

<script>
import SearchTranslation from "@/components/SearchTranslation";

export default {
  name: 'App',
  components: {
    SearchTranslation
  }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
