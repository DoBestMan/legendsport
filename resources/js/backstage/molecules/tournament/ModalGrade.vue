<template>
    <b-modal title="Grade tournament events" :visible="!!value" @change="$emit('input', $event)">
        <template v-slot:default>
            Clicking confirm will trigger the system to check all events in the following tournament
            <strong>{{ textDescription }}</strong> and grade any bets which have a result but haven't been
            graded yet.
        </template>

        <template v-slot:modal-footer="{ cancel }">
            <button class="btn btn-secondary" @click="cancel()">
                Cancel
            </button>

            <ActionButton type="submit" class="btn-success" :loading="deleting" @click="destroy">
                Grade
            </ActionButton>
        </template>
    </b-modal>
</template>

<script lang="ts">
import Vue from "vue";
import { BModal } from "bootstrap-vue";
import ActionButton from "../../../general/components/ActionButton.vue";

export default Vue.extend({
    name: "ModalGrade",
    props: {
        value: [Boolean, Number, String],
        deleting: {
            type: Boolean,
            default: false,
        },
        indexRowId: Number,
        textDescription: String,
    },
    methods: {
        destroy() {
            this.$emit("destroy");
        },
    },
    components: { ActionButton, BModal },
});
</script>
