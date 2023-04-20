<template>
    <Card class="max-w-6xl mx-auto">
        <template v-slot:title>
            <h2>
                <span class="font-light">Comparing </span>
                <span class="font-bold text-lg">{{ occupation1.title }}</span>
                <span class="font-light"> and </span>
                <span class="font-bold text-lg">{{ occupation2.title }}</span>
            </h2>
        </template>
        <div class="matchContainer">
            <p>Total Match: {{ match }}</p>
        </div>
        <slot />
    </Card>
</template>

<script setup>
import Card from './Card.vue'

const props = defineProps({
    occupation1: Object,
    occupation2: Object,
    match: Number
})

</script>
