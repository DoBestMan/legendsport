<template>
    <NotFound />
</template>

<script lang="ts">
import Vue from "vue";
import NotFound from "../components/NotFound.vue";

export default Vue.extend({
    name: "NotFoundView",
    components: { NotFound },
});
</script>
