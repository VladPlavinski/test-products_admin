<script setup lang="ts">
import {ref, onMounted} from "vue";
const props = defineProps({
    currentPage:{
        type: Number,
        required: true,
    },
    lastPage:{
        type: Number,
        required: true,
    },
    partSize:{
        type: Number,
        default: 5,
    }
})

const separateView = ref(false);
let partials = {
    'start': [],
    'middle': [],
    'end': [],
};
let pages = ref([]);
onMounted(() => {
    if(props.lastPage > props.partSize * 3){
        let delta = Math.floor(props.partSize / 2);
        let prePages = [];
        for (let i = 1; i <= props.partSize; i++) {
            prePages.push(i);
        }
        if(props.currentPage <= props.partSize - delta || props.currentPage >= props.lastPage - props.partSize + delta)
        {
            let centralPage = Math.floor(props.lastPage / 2);
            for (let i = centralPage - delta; i <= centralPage + delta; i++) {
                if(!prePages.includes(i) && i > 0) prePages.push(i);
            }
        }
        for (let i = props.currentPage - delta; i <= props.currentPage + delta; i++) {
            if(!prePages.includes(i) && i > 0) prePages.push(i);
        }
        for (let i = props.lastPage - props.partSize + 1; i <= props.lastPage; i++){
            if(!prePages.includes(i)) prePages.push(i);
        }
        prePages.forEach((value, index) => {
            pages.value.push(value);
            if(prePages[index+1] - 1 > value){
                pages.value.push('...')
            }
        });
    } else {
        for (let i = 1; i <= props.lastPage; i++) {
            pages.value.push(i);
        }
    }
});

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
        <div v-for="page in pages"
             class="inline-flex p-3 text-3xl"
             :class="{ 'text-gray-200 pointer-events-none': page==currentPage, 'cursor-pointer': typeof page === 'number'}"
             @click="typeof page === 'number' ? goToPage(page) : '' ;">{{page}}</div>
        <div v-if="lastPage > currentPage" class="inline-flex p-3 text-3xl cursor-pointer" @click="goToPage(currentPage + 1)">></div>
    </div>
</template>

<style scoped>

</style>
