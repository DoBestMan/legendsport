<template>
    <b-modal title="Finish Event" :visible="!!value" @change="$emit('input', $event)">
        <template v-slot:default>
            Fill out the final score and submit this form to manually finish the below event. <br /><br />
            <strong>{{ textDescription }}</strong> <br /><br />

            <div class="form-row form-group">
                <label for="home" class="col-3 col-form-label">Home Score</label>

                <div class="col-2">
                    <input
                        id="home"
                        name="home"
                        type="text"
                        class="form-control"
                        v-model="homeScore"
                        autofocus
                        required
                    />
                </div>

                <label for="away" class="col-3 offset-1 col-form-label">Away Score</label>

                <div class="col-2">
                    <input
                        id="away"
                        name="away"
                        type="text"
                        class="form-control"
                        v-model="awayScore"
                        autofocus
                        required
                    />
                </div>
            </div>

            Once the cancellation takes effect, all wagers in all tournaments will be graded as a push.
        </template>

        <template v-slot:modal-footer="{ cancel }">
            <button class="btn btn-secondary" @click="cancel()">
                Back
            </button>

            <ActionButton type="submit" class="btn-primary" :loading="deleting" @click="destroy">
                Finish Event
            </ActionButton>
        </template>
    </b-modal>
</template>

<script lang="ts">
import Vue from "vue";
import { BModal } from "bootstrap-vue";
import ActionButton from "../../../general/components/ActionButton.vue";
import FormFeedback from "../../../general/components/FormFeedback.vue";
import notificationStore from "../../stores/notificationStore";

export default Vue.extend({
    name: "ModalFinish",
    data: function () {
        return {
            homeScore: '',
            awayScore: '',
            errors: {
                homeScore: null
            }
        }
    },
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
            if (!this.isValidScore(this.homeScore) || !this.isValidScore(this.awayScore)) {
                notificationStore.errorSync("Invalid values provided for scores");
                return;
            }

            this.$emit("destroy", {
                id: this.value,
                homeScore: parseInt(this.homeScore),
                awayScore: parseInt(this.awayScore)
            });
        },
        isValidScore(str: string) {
            var n = Math.floor(Number(str));
            return n !== Infinity && String(n) === str && n >= 0;
        }
    },
    components: { ActionButton, BModal, FormFeedback },
});
</script>
