// https://nuxt.com/docs/api/configuration/nuxt-config
import {resolve} from "path";
export default defineNuxtConfig({
  devtools: { enabled: true },
  css: [
    "~/assets/css/main.scss",
  ],
  modules: ["@nuxt/ui"],
 
  
})