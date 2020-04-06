<template>
    <button type="button" class="btn text-muted" title="Unavailable" disabled>
        <i class="icon fas fa-ban fa-lg"></i>
    </button>
</template>

<script lang="ts">
import Vue from "vue";

export default Vue.extend({
    name: "DisabledButton",
});
</script>
