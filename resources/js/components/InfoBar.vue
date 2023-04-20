<template>
    <div class="infoBar px-6 py-3 text-gray-200">
        <span>This assignment is completed by: {{ author }}</span>
    </div>
</template>

<script setup>
defineProps({ author: String, required: true })
</script>

<style scoped>
.infoBar {
    background-color: #218083;
}
</style>
