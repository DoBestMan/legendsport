<template>
    <multiselect
        selectLabel=""
        deselectLabel=""
        selectGroupLabel=""
        deselectGroupLabel=""
        trackBy="id"
        label="name"
        groupValues="items"
        groupLabel="name"
        :groupSelect="true"
        placeholder="Select sports"
        :closeOnSelect="false"
        :options="sports"
        :value="formattedSelectedSports"
        @input="changeSports"
        multiple
    ></multiselect>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import Multiselect from "vue-multiselect";
import { Sport, sports, sportsMap } from "../utils/sportUtils";

export default Vue.extend({
    name: "SportSelect",
    components: { Multiselect },

    props: {
        value: Array as PropType<number[]>,
    },

    computed: {
        sports() {
            return [
                {
                    name: this.value.length === sports.length ? "Deselect all" : "Select all",
                    items: sports,
                },
            ];
        },

        formattedSelectedSports(): Sport[] {
            return this.value.map(sportId => sportsMap.get(sportId)!);
        },
    },

    methods: {
        changeSports(sports: Sport[]) {
            const sportsIds = (sports || []).map(item => item.id);
            this.$emit("input", sportsIds);
        },
    },
});
</script>
