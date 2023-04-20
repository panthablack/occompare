<template>
    <div
        class="block bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
        <div class="titleBar px-4 py-2 bg-emerald-200">
            <h1 class="font-bold text-xl" v-if="title">{{ title }}</h1>
            <slot name="title" />
        </div>
        <hr>
        <div class="content p-6 bg-emerald-100">
            <slot />
        </div>
    </div>
</template>

<script setup>
defineProps({ title: String })
</script>
