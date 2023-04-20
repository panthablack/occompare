<template>
    <div class="occupationSelector flex-grow">
        <div class="flex items-center justify-start mb-4">
            <!-- TODO: Either replace with autocomplete (best solution) or
                        style so that inputs match in width. -->
            <input type="search" v-model="search" class="inline-block rounded-full ml-4 pr-8 flex-grow"
                :autofocus="autofocus" :placeholder="showLabel ? '' : label" @input="onInput">
            <SearchIcon class="inline-block relative -left-7" />
            <p v-if="showLabel">
                <label class="text-lg mr-4" :for="getId"> {{ label }}</label>
            </p>
        </div>
        <div class="mx-4">
            <select class="form-select rounded-md w-full" :name="name" :id="getId" v-model="selected"
                @change="onChanged">
                <option v-for="o in optionsWithPlaceholder" :key="o.value" :value="o.value">{{ o.text }}
                </option>
            </select>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import SearchIcon from './icons/SearchIcon.vue'
import { trimmedAndLowered } from '../utilities/strings'

/*
    TODO: Try out https://github.com/Takachi67/vue3-autocomplete#readme for a better
    autocomplete/select solution as this one isn't very ux friendly!

    Also, try @headlessui/vue
*/

//props
const props = defineProps({
    autofocus: Boolean,
    label: { type: String, required: true },
    name: { type: String, required: true },
    options: { type: Array, required: true },
    placeholder: { type: String, default: 'Please select from the following options...' },
    showLabel: Boolean,
})

// emits
const emit = defineEmits(['changed'])

// data
const selected = ref(null)
const search = ref('')

// computed
const getId = computed(() => props.name + 'Selector')

const optionsWithPlaceholder = computed(() => [
    { text: props.placeholder, value: null }, ...filteredOptions.value
])

const filteredOptions = computed(() => {
    const s = trimmedAndLowered(search.value)
    if (!s) return props.options
    else return props.options.filter(o => trimmedAndLowered(o.text).indexOf(s) !== -1)
})

// methods
const onChanged = (e) => emit('changed', e?.target?.value)

const onInput = () => {
    selected.value = null
    emit('changed', null)
}
</script>
