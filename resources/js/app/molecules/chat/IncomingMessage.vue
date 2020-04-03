<template>
    <div class="message incoming">
        <div class="message-header">
            <div class="date text-muted">
                {{ timestamp | toTime }}
            </div>
            <div class="user">
                <i class="icon fas fa-user-circle"></i>
                {{ user }}:
            </div>
            <div class="tag-frm">
                <span v-if="!participant" class="tag">
                    Invited
                </span>
            </div>
        </div>

        <div>
            {{ message }}
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
