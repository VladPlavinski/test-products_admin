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
    <Link
        :href="route('product', product.id)"
        class="border border-solid border-gray-400 hover:border-gray-100 hover:bg-gray-100 rounded-xl shadow-lg p-5">
        <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">
            {{ product.name }}
        </h3>
        <p class="text-gray-400 text-sm mb-4 line-clamp-3">
            {{ product.category }}
        </p>
        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
            {{ product.description }}
        </p>
        <div class="text-2xl font-bold text-green-600">
            {{ product.price }} ₽
        </div>
    </Link>
</template>

<style scoped>

</style>
