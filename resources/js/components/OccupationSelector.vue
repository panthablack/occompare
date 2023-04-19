<template>
    <div class="occupationSelector">
        <p>
            <label class="text-lg" :for="getId"> {{ label }}</label>
        </p>
        <select class="form-select" :name="name" :id="getId" v-model="selected" @change="onChanged">
            <option v-for="o in optionsWithPlaceholder" :key="o.value" :value="o.value">{{ o.text }}
            </option>
        </select>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue'

const props = defineProps({
    label: { type: String, required: true },
    name: { type: String, required: true },
    options: { type: Array, required: true },
    placeholder: { type: String, default: 'Please select from the following options...' },
})

const selected = ref(null)

const getId = computed(() => props.name + 'Selector')
const optionsWithPlaceholder = computed(() => [
    { text: props.placeholder, value: null }, ...props.options
])

const emit = defineEmits(['changed'])

const onChanged = (e) => emit('changed', e?.target?.value)
</script>
