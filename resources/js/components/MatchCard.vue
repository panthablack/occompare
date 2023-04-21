<template>
    <Card class="max-w-6xl mx-auto" :contentColor="rangeContentColor" :titleColor="rangeTitleColor">
        <template v-slot:title>
            <h2>
                <span class="font-light">Comparing </span>
                <span class="font-bold text-lg">{{ occupation1.title }}</span>
                <span class="font-light"> and </span>
                <span class="font-bold text-lg">{{ occupation2.title }}</span>
            </h2>
        </template>
        <div class="matchContainer">
            <p class="font-bold text-xl">Total Match: {{ match.total }} ({{ rangeText }})</p>
            <div class="breakdownContainer my-4">
                <h2 class="text-gray-800 font-light mb-8">Please find a detailed breakdown of skill
                    matchups
                    below:</h2>
                <div class="relative overflow-x-auto rounded-md">
                    <table class="table-auto w-full text-sm text-left text-gray-800 dark:text-gray-300">
                        <thead
                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Skill Name</th>
                                <th scope="col" class="px-6 py-3">{{ occupation1.title }}</th>
                                <th scope="col" class="px-6 py-3">{{ occupation2.title }}</th>
                                <!-- <th scope="col" class="px-6 py-3">Skill Match Score</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="skill in match.matchedSkills"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-wrap dark:text-white">
                                    <span class="font-semibold">{{ skill.name }}: </span>
                                    <span>{{ skill.description }}</span>
                                </th>
                                <td class="px-6 py-4">{{ skill.score1 }}</td>
                                <td class="px-6 py-4">{{ skill.score2 }}</td>
                                <!-- <td class="px-6 py-4 font-semibold">{{ skill.comparison }}</td> -->
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <slot />
    </Card>
</template>

<script setup>
import Card from './Card.vue'
import { computed } from 'vue'
import { MATCH_SCORE_RANGES } from '../config/constants'
const { VERY_LOW, LOW, AVERAGE, GOOD, HIGH, VERY_HIGH } = MATCH_SCORE_RANGES

const props = defineProps({
    occupation1: Object,
    occupation2: Object,
    match: Object
})

const isInRange = (v, r) => v >= r[0] && v < r[1]

const rangeText = computed(() => {
    const total = props.match?.total
    if (!total) return null
    else if (total === 0) return 'None'
    else if (total === 100) return 'Perfect'
    else if (isInRange(total, VERY_LOW)) return 'Very Low'
    else if (isInRange(total, LOW)) return 'Low'
    else if (isInRange(total, AVERAGE)) return 'Average'
    else if (isInRange(total, GOOD)) return 'Good'
    else if (isInRange(total, HIGH)) return 'High'
    else if (isInRange(total, VERY_HIGH)) return 'Very High'
    else return 'Out of Range'
})

const rangeContentColor = computed(() => {
    const total = props.match?.total
    if (!total) return 'bg-white'
    else if (total === 0) return 'bg-white'
    else if (total === 100) return 'bg-emerald-200'
    else if (isInRange(total, VERY_LOW)) return 'bg-gray-100'
    else if (isInRange(total, LOW)) return 'bg-orange-200'
    else if (isInRange(total, AVERAGE)) return 'bg-yellow-200'
    else if (isInRange(total, GOOD)) return 'bg-rose-200'
    else if (isInRange(total, HIGH)) return 'bg-teal-200'
    else if (isInRange(total, VERY_HIGH)) return 'bg-emerald-200'
    else return 'bg-white'
})

const rangeTitleColor = computed(() => {
    const total = props.match?.total
    if (!total) return 'bg-white'
    else if (total === 0) return 'bg-white'
    else if (total === 100) return 'bg-emerald-300'
    else if (isInRange(total, VERY_LOW)) return 'bg-gray-200'
    else if (isInRange(total, LOW)) return 'bg-orange-300'
    else if (isInRange(total, AVERAGE)) return 'bg-yellow-300'
    else if (isInRange(total, GOOD)) return 'bg-rose-300'
    else if (isInRange(total, HIGH)) return 'bg-teal-300'
    else if (isInRange(total, VERY_HIGH)) return 'bg-emerald-300'
    else return 'bg-white'
})

</script>
