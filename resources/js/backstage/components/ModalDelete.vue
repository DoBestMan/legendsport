<template>
    <div
        :id="'modalDelete' + (indexRowId ? indexRowId : '')"
        class="modal fade show"
        tabindex="-1"
        role="dialog"
    >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body text-left">
                    <p>
                        Confirm the removal of:
                        <strong>{{ textDescription }}</strong>
                    </p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancel
                    </button>

                    <ActionButton
                        type="submit"
                        :form="'formDelete' + (indexRowId ? indexRowId : '')"
                        class="btn-danger"
                        :loading="deleting"
                        @click="destroy"
                    >
                        Delete
                    </ActionButton>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ActionButton from "./ActionButton";

export default {
    name: "ModalDelete",
    props: {
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
    components: { ActionButton },
};
</script>
