// https://nuxt.com/docs/api/configuration/nuxt-config
import path from 'path';
export default defineNuxtConfig({
  devtools: { enabled: true },
  css: [
    "~/assets/css/main.scss",
  ],
  modules: ["@nuxt/ui"],
  alias: {
    'assets': path.resolve(__dirname, './assets')
  }
  
})