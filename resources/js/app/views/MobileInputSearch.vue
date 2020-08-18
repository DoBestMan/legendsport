<template>
    <div class="layout__content__container__mobile">
        <div class="layout__content__container__mobile__switch f--1">
            <div class="form__control d--flex f--1">
                <div class="form__control__icon form__control__icon--left">
                    <i class="icon icon--search icon--micro"></i>
                </div>
                <input
                    class="input input--large input--padding--left f--1"
                    placeholder="Search.."
                    v-model="search"
                />
            </div>
        </div>
        <div class="layout__content__container__mobile__icons">
            <i class="icon icon--circular m--l--4" @click="handleInputSearch"></i>
        </div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import { mapFields } from "../store/utils";
export default Vue.extend({
    name: "MobileInputSearch",

    computed: {
        ...mapFields("tournamentList", ["search"]),
    },

    methods: {
        handleInputSearch() {
            this.$emit("handleInputSearch");
        },
    },
});
</script>
