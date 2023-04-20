<template>
    <div class="occupationComparison">
        <form @submit.prevent="onSubmit">
            <div class="pageHeading container mx-auto mt-6 mb-8 pl-4">
                <h1 class="text-xl">Occupation Comparison</h1>
                <h2 class="text-lg mb-4 font-normal">Please select 2 occupations to compare:</h2>
            </div>
            <div class="loadingSpinner flex items-center justify-center" v-if="loading">
                <LoadingSpinner />
            </div>
            <div class="container mx-auto mb-6" v-else>
                <div class="grid lg:grid-cols-2 grid-cols-1 gap-4 mb-8">
                    <OccupationSelector name="occupation1" label="Search for an occupation..."
                        :options="occupationsOptions" @changed="onOccupation1Changed" />
                    <OccupationSelector name="occupation2" label="Search for a second occupation..."
                        :options="occupationsOptions" @changed="onOccupation2Changed" />
                </div>
                <div class="compareButtonWrapper text-center">
                    <CompareButton :disabled="!canCompare">
                        {{ canCompare ? 'Compare' : 'Loading...' }}
                    </CompareButton>
                </div>
            </div>
            <div class="text-center" v-if="matched && !isNaN(match) && !matching">
                <MatchCard :occupation1="selectedOccupation1" :occupation2="selectedOccupation2"
                    :match="match" />
            </div>
        </form>
    </div>
</template>

<script setup>
import OccupationSelector from './OccupationSelector.vue'
import LoadingSpinner from './LoadingSpinner.vue'
import MatchCard from './MatchCard.vue'
import CompareButton from './CompareButton.vue'
import api from '../utilities/api'
import { computed, ref } from 'vue'

// data
const occupations = ref([])
const loading = ref(true)
const match = ref(0)
const matched = ref(false)
const matching = ref(false)
const selectedOccupation1 = ref(null)
const selectedOccupation2 = ref(null)

// computed
const occupationsOptions = computed(() => occupations.value.map(o => ({ ...o, value: o.code, text: o.title })))
const canCompare = computed(() => !!selectedOccupation1.value && !!selectedOccupation2.value && !matching.value)

// methods
const fetchOccupations = () => {
    api('/occupations', { method: 'GET' })
        .then(r => occupations.value = r?.data)
        .catch(e => console.log(e.response))
        .finally(() => loading.value = false)
}

const getOccupationByCode = (c) => occupations.value.find(o => o.code === c) || null

const onOccupation1Changed = (e) => selectedOccupation1.value = getOccupationByCode(e)

const onOccupation2Changed = (e) => selectedOccupation2.value = getOccupationByCode(e)

const onSubmit = () => {
    matching.value = true
    api('/compare', {
        method: 'POST', data: {
            occupation1: selectedOccupation1.value.code,
            occupation2: selectedOccupation2.value.code
        }
    }).then((r) => {
        console.log(r)
        match.value = r?.data?.match
        matched.value = true
    }).catch((e) => {
        console.error(e)
    }).finally(() => {
        matching.value = false
    })
}

// created
fetchOccupations()

</script>
