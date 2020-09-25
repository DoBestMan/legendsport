<template>
    <b-modal title="Cancel Event" :visible="!!value" @change="$emit('input', $event)">
        <template v-slot:default>
            Clicking confirm below will mark the following event as cancelled. <br /><br />
            <strong>{{ textDescription }}</strong> <br /><br />
            Once the cancellation takes effect, all wagers in all tournaments will be graded as a push.
        </template>

        <template v-slot:modal-footer="{ cancel }">
            <button class="btn btn-secondary" @click="cancel()">
                Back
            </button>

            <ActionButton type="submit" class="btn-danger" :loading="deleting" @click="destroy">
                Cancel Event
            </ActionButton>
        </template>
    </b-modal>
</template>

<script lang="ts">
import Vue from "vue";
import { BModal } from "bootstrap-vue";
import ActionButton from "../../../general/components/ActionButton.vue";

export default Vue.extend({
    name: "ModalCancel",
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
