<template>
    <b-modal title="Check tournament completion" :visible="!!value" @change="$emit('input', $event)">
        <template v-slot:default>
            Clicking confirm will trigger the system to check the following tournament
            <strong>{{ textDescription }}</strong> if all events are complete, auto end is set and all bets have
            been graded, the tournament will be marked as completed and winnings will be paid out.
        </template>

        <template v-slot:modal-footer="{ cancel }">
            <button class="btn btn-secondary" @click="cancel()">
                Cancel
            </button>

            <ActionButton type="submit" class="btn-success" :loading="checkingCompletion" @click="destroy">
                Check
            </ActionButton>
        </template>
    </b-modal>
</template>

<script lang="ts">
import Vue from "vue";
import { BModal } from "bootstrap-vue";
import ActionButton from "../../../general/components/ActionButton.vue";

export default Vue.extend({
    name: "ModalComplete",
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
