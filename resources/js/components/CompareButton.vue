<template>
    <button class="px-4 py-2 font-bold capitalize text-slate-100 rounded-full compareButton"
        :disabled="disabled">
        <slot />
    </button>
</template>

<script setup>
defineProps({
    disabled: Boolean
})
</script>

<style scoped>
.compareButton {
    background-color: #218083;
}

.compareButton:disabled {
    background-color: #97ccce85;
}
</style>
