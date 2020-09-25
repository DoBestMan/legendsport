<template>
    <b-modal title="Start Event" :visible="!!value" @change="$emit('input', $event)">
        <template v-slot:default>
            Clicking confirm below will mark the following event as in play. <br /><br />
            <strong>{{ textDescription }}</strong> <br /><br />
            This will not effect the automatic scores, finishing or grading of an event.
        </template>

        <template v-slot:modal-footer="{ cancel }">
            <button class="btn btn-secondary" @click="cancel()">
                Back
            </button>

            <ActionButton type="submit" class="btn-success" :loading="deleting" @click="destroy">
                Start Event
            </ActionButton>
        </template>
    </b-modal>
</template>

<script lang="ts">
import Vue from "vue";
import { BModal } from "bootstrap-vue";
import ActionButton from "../../../general/components/ActionButton.vue";

export default Vue.extend({
    name: "ModalStart",
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
