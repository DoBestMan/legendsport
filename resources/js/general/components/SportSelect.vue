<template>
    <!-- <multiselect
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
    </multiselect> -->

    <div class="dropdown">
        <div class="form__control">
            <input class="input" type="text" :value="formattedSelectedSports" readonly="readonly" />
            <div class="form__control__icon--right">
                <i class="icon icon--micro icon--down"></i>
            </div>
        </div>
        <div class="dropdown__content">
            <div
                class="dropdown__content__item"
                v-for="option in options"
                :key="option.name"
                @click="selectedOption(option)"
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
import Vue, { PropType } from "vue";
import Multiselect from "vue-multiselect";
import { Sport } from "../types/sport";

export default Vue.extend({
    name: "SportSelect",
    components: { Multiselect },

    props: {
        value: Array as PropType<Sport[]>,
        sports: Array as PropType<Sport[]>,
    },

    model: {
        prop: "value",
        event: "change",
    },

    data() {
        return {
            selectedVal: [] as Sport[],
        };
    },

    computed: {
        options(): any[] {
            return [
                {
                    name: this.value.length === this.sports.length ? "Deselect all" : "Select all",
                    items: this.sports,
                },
                ...this.sports,
            ];
        },

        // sportsMap(): ReadonlyMap<string, Sport> {
        //     return new Map(this.sports.map(sport => [sport.id, sport]));
        // },

        formattedSelectedSports(): string {
            return this.value.map((sport: Sport) => sport.name).join(", ");
        },

        label(): string {
            if (this.value.length === this.sports.length) {
                return "All";
            }

            if (this.value.length === 1) {
                return this.value[0].name;
            }

            if (this.value.length > 1) {
                return "Custom";
            }

            return "";
        },
    },

    methods: {
        // changeSports(sports: Sport[]) {
        //     const sportsIds = (sports || []).map(item => item.id);
        //     this.$emit("input", sportsIds);
        // },

        selectedOption(option: Sport): void {
            console.log("selected Option: ", option);
            var check = 0;
            for (var i = 0; i < this.selectedVal.length; i += 1) {
                if (this.selectedVal[i].id === option.id) {
                    this.selectedVal.splice(i, 1);
                    check = 1;
                    break;
                }
            }
            if (check === 0) {
                this.selectedVal.push(option);
            }

            this.$emit("change", this.selectedVal);
        },

        isSelectedOption(option: Sport): boolean {
            if (!this.selectedVal || this.selectedVal.length === 0) return false;
            const result = this.selectedVal.find(v => v.id === option.id);
            if (result) return true;
            return false;
        },
    },
});
</script>
