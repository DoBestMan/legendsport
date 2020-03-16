<template>
    <div class="spinner-section">
        <div class="spinner-border text-muted">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
</template>

<script lang="ts">
export default {
    name: "SpinnerSection",
};
</script>

<style lang="scss" scoped>
.spinner-section {
    background-color: #fff;
    box-shadow: 0 0 1rem 1rem #fff;
    opacity: 0.5;
    display: flex;
    justify-content: center;
    align-items: center;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    animation: fadeIn 0.6s;
}
</style>
