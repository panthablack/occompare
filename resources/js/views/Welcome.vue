<script setup>
import { Head } from '@inertiajs/vue3';
import DefaultLayout from '../layouts/Default.vue'
import OccupationComparison from '../components/OccupationComparison.vue'
</script>

<template>
    <Head title="Occompare" />
    <DefaultLayout>
        <OccupationComparison />
    </DefaultLayout>
</template>
