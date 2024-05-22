<script setup lang="ts">
import { getFilteredData, getParamsArrayFromObj, getParamsUrlString } from '~/helpers';
//routes
const route = useRoute();
const router = useRouter();

// params from routes
const paramsFromUrl = router.currentRoute.value.query;
const paramsToString = getParamsUrlString(getParamsArrayFromObj(paramsFromUrl));

//refs to save data
const products = ref<Product[]>([]);
const filters = ref<Filter[]>([]);

const checkedNames = ref<{name:string,item:string}[]>(getParamsArrayFromObj(paramsFromUrl));

// fetch to set data
const fetchAndSetProductsAndFilters = async (id: string | string [], params: string) => {
    try {
      const data = await getFilteredData(id, params);
      products.value = data[0].products;
      filters.value = data[0].filters;
    } catch (error) {
        console.error("Error fetching data:", error);
    }
};
// function who set data when page open
(async () => {
    await fetchAndSetProductsAndFilters(route.params.id, paramsToString);
})();

//watch every time change filters refresh values of filters and products
watch(checkedNames, () => {

  const urlParams = getParamsUrlString(checkedNames.value);

  router.replace(`${route.params.id}?${urlParams}`);
  
  fetchAndSetProductsAndFilters(route.params.id, urlParams);
});
</script>


<template>
  <div v-if="!products && !filters"></div>
  <div v-else class="bg-white">
  <div>
  
    <div class="relative z-40 lg:hidden transition-opacity ease-linear duration-300 opacity-100" role="dialog" aria-modal="true">
      
      <div class="fixed inset-0 bg-black bg-opacity-25"></div>

      <div class="fixed inset-0 z-40 flex">
        <div class="relative ml-auto flex h-full w-full max-w-xs flex-col overflow-y-auto bg-white py-4 pb-6 shadow-xl">
          <div class="flex items-center justify-between px-4">
            <h2 class="text-lg font-medium text-gray-900">Filters</h2>
            <button type="button" class="-mr-2 flex h-10 w-10 items-center justify-center p-2 text-gray-400 hover:text-gray-500">
              <span class="sr-only">Close menu</span>
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <form class="mt-4">
            <div class="border-t border-gray-200 pb-4 pt-4" v-for="filter in filters" :key="filter.name">
              <fieldset>
                <legend class="w-full px-2">
              
                  <button type="button" class="flex w-full items-center justify-between p-2 text-gray-400 hover:text-gray-500" aria-controls="filter-section-0" aria-expanded="false">
                    <span class="text-sm font-medium text-gray-900">{{ filter.name }}</span>
                    <span class="ml-6 flex h-7 items-center">
                      <svg class="rotate-0 h-5 w-5 transform" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                      </svg>
                    </span>
                  </button>
                </legend>
              </fieldset>
              <div class="px-4 pb-2 pt-4" id="filter-section-0">
                  <div class="space-y-6">
                    <div class="flex items-center" v-for="item in filter.items" :key="item.name">
                      <input v-model="checkedNames" id="color-0-mobile" name="color[]" :value="{'name':filter.table_name,'item':item.name}" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                      <label for="color-0-mobile" class="ml-3 text-sm text-gray-500">{{ item.name }}</label>
                    </div>
                  </div>
                </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <main class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
      <div class="border-b border-gray-200 pb-10">
        <h1 class="text-4xl font-bold tracking-tight text-gray-900">{{route.params.id}}</h1>
      </div>
      <div class="pt-12 lg:grid lg:grid-cols-3 lg:gap-x-8 xl:grid-cols-4">
        <aside>
          <h2 class="sr-only">Filters</h2>
          <button type="button" class="inline-flex items-center lg:hidden">
            <span class="text-sm font-medium text-gray-700">Filters</span>
            <svg class="ml-1 h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
            </svg>
          </button>

          <div class="hidden lg:block">
            <form class="space-y-10 divide-y divide-gray-200">
              <div v-for="(filter, index) in filters" :key="filter.name" v-bind:class = "(index==0)?'':'pt-10'">
                <fieldset>
                  <legend class="block text-sm font-medium text-gray-900">{{ filter.name }}</legend>
                  <div class="space-y-3 pt-6">
                    <div class="flex items-center relative" v-for="item in filter.items" :key="item.name">
                      <input v-model="checkedNames" :id="item.name" :name="item.name" :value="{'name':filter.table_name,'item':item.name}" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                      <label :for="item.name" class="ml-3 text-sm text-gray-600">{{ item.name }}</label>
                      <span class="right-0 absolute text-sm text-gray-600">{{ item.count }}</span>
                    </div>
                  </div>
                </fieldset>
              </div>
            </form>
          </div>
        </aside>
        <div class="mt-6 lg:col-span-2 lg:mt-0 xl:col-span-3">
          <ProductMap :products="products" />
        </div>
      </div>
    </main>
  </div>
</div>
</template>