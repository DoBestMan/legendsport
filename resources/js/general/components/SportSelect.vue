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
        placeholder="Type to filter..."
        :closeOnSelect="false"
        :options="options"
        :value="formattedSelectedSports"
        :limit="0"
        @input="changeSports"
        multiple
    >
        <template v-slot:placeholder>
            Select sports
        </template>

        <template v-slot:singleLabel>
            {{ label }}
        </template>

        <template v-slot:limit>
            <span></span>
        </template>
    </multiselect>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import Multiselect from "vue-multiselect";
import { Sport } from "../types/sport";
export default Vue.extend({
    name: "SportSelect",
    components: { Multiselect },
    props: {
        value: Array as PropType<string[]>,
        sports: Array as PropType<Sport[]>,
    },
    computed: {
        options(): any[] {
            return [
                {
                    name: this.value.length === this.sports.length ? "Deselect all" : "Select all",
                    items: this.sports,
                },
            ];
        },
        sportsMap(): ReadonlyMap<string, Sport> {
            return new Map(this.sports.map(sport => [sport.id, sport]));
        },
        formattedSelectedSports(): Sport[] {
            return this.value.map(sportId => this.sportsMap.get(sportId)!);
        },
        label(): string {
            if (this.value.length === this.sports.length) {
                return "All";
            }
            if (this.value.length === 1) {
                return this.formattedSelectedSports[0].name;
            }
            if (this.value.length > 1) {
                return "Custom";
            }
            return "";
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
