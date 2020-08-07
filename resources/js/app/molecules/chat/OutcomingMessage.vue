<template>
    <div class="message message--self">
        <div class="message__content">
            <!-- Todo: to get time -->
            <div class="message__content__details">
                <div class="message__content__details__detail"></div>
                <div class="message__content__details__detail">11.10 am</div>
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
    name: "OutcomingMessage",
    props: {
        message: String,
    },
});
</script>
