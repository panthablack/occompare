<template>
    <button class="px-4 py-2 font-bold capitalize text-slate-100 rounded-full compareButton"
        :disabled="disabled">
        <div class="flex">

            <slot />
            <LoadingSpinner v-if="loading" class="ml-4" />
        </div>
    </button>
</template>

<script setup>
import LoadingSpinner from './LoadingSpinner.vue'

defineProps({
    disabled: Boolean,
    loading: Boolean,
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
