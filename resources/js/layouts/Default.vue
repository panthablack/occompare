<script setup>
import InfoBar from '../components/InfoBar.vue'
import { author } from '@/config/constants';
</script>

<template>
    <div class="defaultLayoutWrapper brandBackground  selection:bg-rose-900 selection:text-white ">
        <InfoBar :author="author" />
        <div class="contentWrapper relative p-4 min-h-screen bg-center text-gray-950 font-semibold">
            <slot />
        </div>
    </div>
</template>

<style scoped>
/*
Incorporate the original brand color from the original template:
'--brand-color': '#fbb040'
*/
.brandBackground {
    background-color: #e9d8c1;
}
</style>
