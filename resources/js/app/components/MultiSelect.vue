<template>
    <multiselect
        trackBy="id"
        label="name"
        selectLabel=""
        deselectLabel=""
        selectedLabel=""
        :allowEmpty="false"
        :value="formattedValue"
        :options="options"
        v-bind="$attrs"
        v-on="listeners"
    >
        <template v-for="(_, slot) of $scopedSlots" v-slot:[slot]="scope">
            <slot :name="slot" v-bind="scope" />
        </template>
    </multiselect>
</template>

<script lang="ts">
import Vue, { PropOptions } from "vue";
import Multiselect from "vue-multiselect";
import { SelectOption } from "../../general/types/types";

export default Vue.extend({
    name: "MultiSelect",
    components: { Multiselect },
    inheritAttrs: false,

    props: {
        options: Array as PropOptions<SelectOption[]>,
        value: String,
    },

    computed: {
        formattedValue(): SelectOption | null {
            return this.options.find(option => option.id === this.value) ?? null;
        },

        listeners() {
            return {
                ...this.$listeners,
                // @ts-ignore
                input: this.onInput,
            };
        },
    },

    methods: {
        onInput(val: SelectOption | null): void {
            this.$emit("input", val?.id);
        },
    },
});
</script>
