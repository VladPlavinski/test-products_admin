<script setup lang="ts">
import {Link} from "@inertiajs/vue3";
defineProps({
    currentPage:{
        type: Number,
        required: true,
    },
    lastPage:{
        type: Number,
        required: true,
    },
})

const emit = defineEmits<{
    (e: 'changePage', page: number): void;
}>();

const goToPage = (page: number) => {
    emit('changePage', page);
};
</script>

<template>
    <div class="text-center px-5 py-3">
        <div v-if="currentPage > 1" class="inline-flex p-3 text-3xl cursor-pointer" @click="goToPage(currentPage - 1)"><</div>
        <div v-for="page in lastPage"
             class="inline-flex p-3 text-3xl cursor-pointer"
             :class="{ 'text-gray-200 pointer-events-none': page==currentPage}"
             @click="goToPage(page)">{{page}}</div>
        <div v-if="lastPage > currentPage" class="inline-flex p-3 text-3xl cursor-pointer" @click="goToPage(currentPage + 1)">></div>
    </div>
</template>

<style scoped>

</style>
