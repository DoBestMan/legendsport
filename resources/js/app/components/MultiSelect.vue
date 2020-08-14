<template>
    <div class="dropdown">
        <div class="form__control">
            <input
                class="input"
                type="text"
                :value="selectedVal ? selectedVal : 'All'"
                readonly="readonly"
            />
            <div class="form__control__icon--right">
                <i class="icon icon--micro icon--down"></i>
            </div>
        </div>
        <div class="dropdown__content">
            <div
                class="dropdown__content__item"
                v-for="option in options"
                :key="option.id"
                @click="handleSelectOption(option)"
            >
                {{ option.name }}
                <i
                    class="icon icon--smaller icon--check icon--color--yellow-1"
                    v-if="isSelectedOption(option)"
                ></i>
            </div>
        </div>
    </div>
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

    model: {
        prop: "value",
        event: "change",
    },

    data() {
        return {
            selectedVal: "" as string | null,
        };
    },

    computed: {
        formattedValue(): SelectOption | null {
            return this.options.find(option => option.id === this.value) ?? null;
        },
    },

    methods: {
        handleSelectOption(val: SelectOption): void {
            this.selectedVal = val.id;
            this.$emit("change", val.id);
        },

        isSelectedOption(val: SelectOption): boolean {
            if (!this.selectedVal && val.id === null) return true;
            return this.selectedVal === val.id;
        },
    },
});
</script>
