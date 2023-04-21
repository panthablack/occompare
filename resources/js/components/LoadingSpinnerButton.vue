<template>
    <button type="button"
        class="loadingSpinner inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white transition ease-in-out duration-150 cursor-not-allowed"
        disabled="true">
        <LoadingSpinner />
        Loading...
    </button>
</template>

<script setup>
import LoadingSpinner from './LoadingSpinner.vue'
</script>

<style scoped>
.loadingSpinner {
    background-color: #218083;
}
</style>
