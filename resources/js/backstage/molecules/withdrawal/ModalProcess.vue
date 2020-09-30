<template>
    <b-modal title="Process payment" :visible="!!value" @change="$emit('input', $event)">
        <template v-slot:default>
            Payment of {{ textDescription }}<br />
            <br />
            Confirm that you have sent the payment, this will remove the withdrawal from the list of pending withdrawals. <br />
            <br />
            <strong>Payment must be sent manually, this only marks the payment as sent.</strong>
        </template>

        <template v-slot:modal-footer="{ cancel }">
            <button class="btn btn-secondary" @click="cancel()">
                Back
            </button>

            <ActionButton type="submit" class="btn-success" :loading="loading" @click="destroy">
                Process Payment
            </ActionButton>
        </template>
    </b-modal>
</template>

<script lang="ts">
import Vue from "vue";
import { BModal } from "bootstrap-vue";
import ActionButton from "../../../general/components/ActionButton.vue";

export default Vue.extend({
    name: "ModalProcess",
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
