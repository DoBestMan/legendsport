<template>
    <b-modal title="Delete" :visible="!!value" @change="$emit('input', $event)">
        <template v-slot:default>
            Confirm the removal of:
            <strong>{{ textDescription }}</strong>
        </template>

        <template v-slot:modal-footer="{ cancel }">
            <button class="btn btn-secondary" @click="cancel()">
                Cancel
            </button>

            <ActionButton type="submit" class="btn-danger" :loading="deleting" @click="destroy">
                Delete
            </ActionButton>
        </template>
    </b-modal>
</template>

<script lang="ts">
import Vue from "vue";
import { BModal } from "bootstrap-vue";
import ActionButton from "../../general/components/ActionButton.vue";

export default Vue.extend({
    name: "ModalDelete",
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
