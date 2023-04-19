<script setup>
import {
    Head,
} from '@inertiajs/vue3';

defineProps({
//
});
</script>

<template>
    <Head title="Occompare" />
    <div
        class="brandBackground relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white"
    >
        <h1 class="text-xl">Something</h1>
    </div>
</template>

<style scoped>
/*
Incorporate the original brand color from the original template:
'--brand-color': '#fbb040'
*/
.brandBackground {
    background-color: #fbb040;
}
</style>
