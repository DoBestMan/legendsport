<template>
    <div class="container my-5 mb-4 text-center">
        <h1 class="h1">404</h1>
        <h2 class="h3 mb-4">Page Not Found</h2>
        <p class="text-muted">
            It seems we can’t find the page you are looking for.
            <router-link to="/lobby" class="font-weight-medium" exact>
                Go Back To Homepage </router-link
            ><br />
        </p>
    </div>
</template>

<script lang="ts">
import Vue from "vue";

export default Vue.extend({
    //
});
</script>
