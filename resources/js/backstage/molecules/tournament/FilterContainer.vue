<template>
    <div class="row form-group">
        <div class="col-5">
            <label for="sports">Sport</label>
            <SportSelect
                id="sports"
                :sports="sportOptions"
                :value="sports"
                @input="$emit('update:sports', $event)"
            />
        </div>

        <div class="col-4">
            <label for="sportDate">Date game</label>

            <input
                id="sportDate"
                type="date"
                name="sportDate"
                class="form-control"
                style="height: 2.6rem"
                :value="eventDate"
                @input="$emit('update:eventDate', $event.target.value)"
            />
        </div>

        <div class="col-3 button-column">
            <button type="button" class="btn btn-dark" @click="includeAll">
                Include All
            </button>
        </div>
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import SportSelect from "../../../general/components/SportSelect.vue";
import sportStore from "../../stores/sportStore";
import { Sport } from "../../../general/types/sport";

export default Vue.extend({
    name: "FilterContainer",
    components: { SportSelect },

    props: {
        sports: Array as PropType<number[]>,
        eventDate: String,
    },

    computed: {
        sportOptions(): Sport[] {
            return sportStore.sports;
        },
    },

    methods: {
        includeAll() {
            this.$emit("includeAll");
        },
    },
});
</script>

<style>
.button-column {
    display: flex;
    align-items: flex-end;
}
</style>
