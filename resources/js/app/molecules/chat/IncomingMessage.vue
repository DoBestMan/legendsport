<template>
    <div class="message">
        <div class="message__avatar">
            <i class="icon icon--person icon--micro"></i>
        </div>
        <div class="message__content">
            <div class="message__content__details">
                <div class="message__content__details__detail">{{ user }}</div>
                <div class="message__content__details__detail">{{ timestamp | toTime }}</div>
                <!-- Todo: check if invited or not
                <div class="tag-frm">
                    <span v-if="!participant" class="tag">
                        Invited
                    </span>
                </div> -->
            </div>
            <div class="message__content__message">
                {{ message }}
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";

export default Vue.extend({
    name: "IncomingMessage",
    props: {
        timestamp: Number,
        user: String,
        message: String,
        participant: Boolean,
    },
});
</script>
