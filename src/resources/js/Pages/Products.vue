<script setup lang="ts">
import {ref, onMounted, watch} from "vue";
import {Head} from "@inertiajs/vue3";
import axios from "axios";
import ProductCard from "@/Components/ProductCard.vue";
import Paginator from "@/Components/Paginator.vue";

let initialized = false;
const STORAGE_KEY = 'products_list_settings';
const debounceDelay = 1000;

const products = ref([]);
const categories = ref([]);
const isLoading = ref(false);
const meta = ref({
    last_page: 1,
    current_page: 1,
});
const search = ref('');
const selectedCategory = ref(0);
let debouncer = null;

const fetchCategories = async () => {
    try {
        const response = await axios.get('/api/categories');
        categories.value = response.data.data;
    } catch (error) {
        console.log(error)
    }
}

const fetchProducts = async (page?: number) => {
    isLoading.value = true;
    const fetchingPage = page ?? meta.value.current_page;
    try {
        const response = await axios.get('/api/products', {
            params: {
                page: fetchingPage,
                category_id: selectedCategory.value,
                search: search.value,
            }
        })

        products.value = response.data.data;
        meta.value = response.data.meta;

        if (initialized) {
            saveSettingsToSession();
        }
    } catch (error) {
        console.log(error)
    } finally {
        isLoading.value = false
    }
}
const sendSearch = () => {
    if (debouncer) {
        clearTimeout(debouncer);
    }
    debouncer = setTimeout(() => {
        fetchProducts(1);
    }, debounceDelay)
};

const saveSettingsToSession = () => {
    sessionStorage.setItem(STORAGE_KEY, JSON.stringify({
        search: search.value,
        selectedCategory: selectedCategory.value,
        currentPage: meta.value.current_page
    }));
};

const loadSettingsFromSession = () => {
    let stored = sessionStorage.getItem(STORAGE_KEY);
    if(stored) {
        let settings = JSON.parse(stored);
        search.value = settings.search;
        selectedCategory.value = settings.selectedCategory;
        meta.value.current_page = settings.currentPage;
    }
};

watch(search, () => {
    if(!initialized) return;

    meta.value.current_page = 1;
    sendSearch();
});

watch(selectedCategory, () => {
    if(!initialized) return;

    meta.value.current_page = 1;
    fetchProducts();
})

watch(() => meta.value.current_page, (newPage, oldPage) => {
    if(!initialized) return;
    if(newPage !== oldPage) {
        fetchProducts(newPage);
    }
});

onMounted(async () => {
    isLoading.value = true;
    loadSettingsFromSession();
    await fetchCategories();
    await fetchProducts();
    initialized = true;
});
</script>

<template>
    <Head title="Список товаров" />
    <div class="container mx-auto px-4 py-8">
        <div v-if="isLoading" class="flex justify-center items-center py-10">
            <p class="text-5xl text-gray-300">Загрузка</p>
        </div>

        <div v-if="!isLoading">
            <div class="my-5">
                <div class="text-center text-8xl p-3 m-5 text-gray-400 font-bold">Список товаров</div>
                <div class="flex items-center gap-6 justify-center">
                    <div>
                        <label for="category" class="px-3">Категория:</label>
                        <select class="rounded-full" name="category" v-model="selectedCategory">
                            <option value="0">Любая</option>
                            <option v-for="category in categories" :value="category.id">{{category.name}}</option>
                        </select>
                    </div>
                    <div>
                        <input class="rounded-full w-96" type="text" name="search"
                               v-model="search" placeholder="Введите для поиска">
                    </div>
                </div>
            </div>
            <div v-if="products.length" class="grid grid-cols-1 px-10 gap-3">
                <ProductCard v-for="product in products" :product="product" />
            </div>
            <div v-if="!products.length" class="flex justify-center items-center py-10">
                <p class="text-5xl text-gray-300">Товары не найдены</p>
            </div>
            <Paginator v-if="products.length" :last-page="meta.last_page" :current-page="meta.current_page" @changePage="fetchProducts"/>
        </div>
    </div>
</template>

<style scoped>

</style>
