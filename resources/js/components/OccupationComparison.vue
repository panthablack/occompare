<template>
    <div class="occupationComparison">
        <form>
            <h1 class="text-xl">Occupation Comparison</h1>
            <h2 class="text-lg mb-4 font-normal">Please select 2 occupations to compare:</h2>
            <div class="loadingSpinner flex items-center justify-center" v-if="loading">
                <LoadingSpinner />
            </div>
            <div class="container mx-auto" v-else>
                <div class="flex items-center justify-around mb-8">
                    <OccupationSelector name="occupation1" label="Occupation 1"
                        :options="occupationsOptions" @changed="onOccupation1Changed" />
                    <OccupationSelector name="occupation2" label="Occupation 2"
                        :options="occupationsOptions" @changed="onOccupation2Changed" />
                </div>
            </div>
            <div class="flex items-center justify-around" v-if="matched">
                <MatchCard>
                    <h1>I am content</h1>
                </MatchCard>
            </div>
        </form>
    </div>
</template>

<script setup>
import OccupationSelector from './OccupationSelector.vue'
import LoadingSpinner from './LoadingSpinner.vue'
import MatchCard from './MatchCard.vue'
import api from '../utilities/api'
import { computed, ref } from 'vue'

const occupations = ref([])
const loading = ref(true)
const matched = ref(false)

const occupationsOptions = computed(() => occupations.value.map(o => ({ ...o, value: o.code, text: o.title })))

const fetchOccupations = () => {
    api('/api/occupations', { method: 'GET' })
        .then(r => occupations.value = r?.data)
        .catch(e => console.log(e.response))
        .finally(() => loading.value = false)
}

fetchOccupations()

const onOccupation1Changed = (e) => console.log('onOccupation1Changed', e)
const onOccupation2Changed = (e) => console.log('onOccupation2Changed', e)


</script>

<!--

    LEGACY CODE FOR REFERENCE

<template>
    <div class="container py-3">
        <div class="row">
            <div class="col-12 text-center">
                <div class="form">
                    <div class="form-group row">
                        <div class="col-md-5">
                            <label>Occupation 1</label>
                            <select-occupation v-model="occupation_1"></select-occupation>
                        </div>
                        <div class="col-md-5">
                            <label>Occupation 2</label>
                            <select-occupation v-model="occupation_2"></select-occupation>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-danger btn-block mt-4" @click.prevent="compare"
                                :disabled="!occupation_1 || !occupation_2 || loading">
                                <template v-if="loading">
                                    <i class="fa fa-refresh fa-spin"></i>
                                </template>
                                <template v-else>
                                    Compare
                                </template>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <template v-if="match && !loading">
                <div class="col-12 text-center">
                    <h1>{{ match }}%</h1>
                </div>
            </template>
            <template v-else-if="!match && !loading">
                <div class="col-12 text-center">
                    Please select two Occupations from above and click Compare
                </div>
            </template>
            <template v-else-if="loading">
                <div class="col-12 text-center">
                    Please wait...
                </div>
            </template>
        </div>

 *********************************************************************************************
 ******* Use this space to visualise and present the result/breakdown or whatever you see fit
 *********************************************************************************************

    </div>
</template>

<script>
import SelectOccupation from '../components/form-controls/SelectOccupation';
export default {
    name: 'home-page',
    components: {
        SelectOccupation
    },
    data() {
        return {
            loading: false,
            occupation_1: null,
            occupation_2: null,
            match: null
        }
    },
    methods: {
        compare() {
            this.loading = true;
            this.axios.post('/api/compare', {
                occupation_1: this.occupation_1,
                occupation_2: this.occupation_2
            }).then((response) => {
                this.loading = false;
                this.match = response.data.match;
            }).catch(() => {
                this.loading = false;
            });
        }
    }
}
</script>

<style lang="scss" scoped>
.form-group {
    label {
        font-size: 0.8rem;
        text-align: left;
        display: block;
        margin-bottom: 0.2rem
    }
}
</style>

-->
