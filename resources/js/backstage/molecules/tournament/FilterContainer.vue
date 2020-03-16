<template>
    <div class="row form-group">
        <div class="col-5">
            <label for="sports">Sport</label>
            <SportSelect id="sports" :value="selectedSports" @input="changeSports"></SportSelect>
        </div>

        <div class="col-4">
            <label for="sportDate">Date game</label>

            <input
                id="sportDate"
                type="date"
                name="sportDate"
                class="form-control"
                style="height: 2.6rem"
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

export default Vue.extend({
    name: "FilterContainer",
    components: { SportSelect },
    props: {
        selectedSports: Array as PropType<number[]>,
    },
    methods: {
        changeSports(sportsIds: number[]) {
            this.$emit("update:selectedSports", sportsIds);
        },

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
