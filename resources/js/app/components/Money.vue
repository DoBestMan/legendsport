<template>
    <span>$&nbsp;{{ value.toFixed(2) }}</span>
</template>

<script lang="ts">
import Vue from "vue";

export default Vue.extend({
    name: "Money",
    props: {
        value: Number,
    },
});
</script>
