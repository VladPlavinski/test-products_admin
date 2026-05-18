<script setup lang="ts">
import {Link} from "@inertiajs/vue3";

defineProps({
    product: {
        type: Object,
        required: true,
    }
})
</script>

<template>
    <Link   :href="route('product', product.id)"
            class="border border-solid border-gray-400 hover:border-gray-100 hover:bg-gray-100 rounded-xl shadow-lg p-5">
        <div class="flex justify-between">
            <div class="text-2xl font-bold text-gray-800 mb-2 line-clamp-2">{{ product.name }}</div>
            <div class="text-xl font-bold text-green-600 px-3">{{ product.price }}&nbsp;₽</div>
        </div>
        <div class="text-gray-400 text-sm mb-4">
            {{ product.category }}
        </div>
        <div class="text-gray-600 text-lg mb-4 line-clamp-3">
            {{ product.description }}
        </div>
    </Link>
</template>

<style scoped>

</style>
