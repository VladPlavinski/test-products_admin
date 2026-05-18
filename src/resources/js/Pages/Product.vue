<script setup lang="ts">
import {ref, onMounted, reactive} from "vue";
import {Head, Link} from "@inertiajs/vue3";
import axios from "axios";

const product = reactive({});
const isLoading = ref(true);
const title = ref('Детальная страница')

const props = defineProps({
    id: {
        type: Number,
        required: true,
    }
})

const fetchProduct = async (id: number) => {
    try {
        isLoading.value = true;
        const response = await axios.get(`/api/product/${id}`);
        Object.assign(product, response.data.data);
        title.value = product.name;
    } catch (error) {
        console.log(error);
    } finally {
        isLoading.value = false;
    }
}

onMounted(() => {
    fetchProduct(props.id);
});
</script>

<template>
    <div class="container mx-auto px-4 py-8">
        <Head :title="title" />
        <div v-if="isLoading" class="flex justify-center items-center py-10">
            <p class="text-5xl text-gray-300">Загрузка</p>
        </div>

        <div v-if="!isLoading">
            <div class="flex flex-col border border-solid border-gray-400 rounded-xl shadow-lg p-5 text-xl gap-3">
                <Link :href="route('products')" class="text-blue-700 hover:text-blue-400 text-sm">< К списку</Link>
                <div class="text-center text-5xl font-bolder mb-9">{{product.name}}</div>
                <div class="flex"><div class="inline text-md text-gray-400 w-1/6">Категория : </div>{{product.category}}</div>
                <div class="flex"><div class="inline text-md text-gray-400 w-1/6">Создан : </div>{{product.created_at}}</div>
                <div class="flex"><div class="inline text-md text-gray-400 w-1/6">Изменён : </div>{{product.updated_at}}</div>
                <div class="flex"><div class="inline text-md text-gray-400 w-1/6">Цена : </div>{{product.price}}&nbsp;₽</div>
                <div><p class="text-md text-gray-400 py-2">Подробное описание: </p>
                    <div class="border border-solid border-gray-400 rounded-xl shadow-inner p-3">{{product.description}}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
