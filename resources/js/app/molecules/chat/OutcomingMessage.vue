<template>
    <div class="message outcoming"><strong>Me:</strong> {{ message }}</div>
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
