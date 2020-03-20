<template>
    <div class="scrollable">
        <div v-show="canMoveLeft" class="scroller scroller-left" @click="moveLeft">
            <i class="icon fas fa-chevron-left"></i>
        </div>
        <div
            class="scroller scroller-right"
            :class="{ invisible: !canMoveRight }"
            @click="moveRight"
        >
            <i class="icon fas fa-chevron-right"></i>
        </div>

        <div class="wrapper" ref="wrapper">
            <div class="list" :style="listStyle" ref="list">
                <slot />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";

export default Vue.extend({
    name: "HorizontallyScrollable",

    data() {
        return {
            offset: 0,
            overflowSize: 0,
        };
    },

    mounted() {
        window.addEventListener("resize", this.updateOverflowSize);
    },

    beforeDestroy: function() {
        window.removeEventListener("resize", this.updateOverflowSize);
    },

    updated() {
        this.updateOverflowSize();
    },

    computed: {
        listStyle(): object {
            return {
                left: `${this.offset}px`,
            };
        },

        remainingHidden(): number {
            return this.overflowSize + this.offset;
        },

        stepSize(): number {
            const stepSize = Math.ceil(this.overflowSize / 2);
            return Math.max(stepSize, 100);
        },

        canMoveLeft(): boolean {
            return this.offset < 0;
        },

        canMoveRight(): boolean {
            return this.remainingHidden > 0;
        },
    },

    methods: {
        moveRight() {
            this.offset -= Math.min(this.stepSize, this.remainingHidden);
        },

        moveLeft() {
            this.offset += Math.min(-this.offset, this.stepSize);
        },

        updateOverflowSize() {
            this.overflowSize = Math.max(
                0,
                // @ts-ignore
                this.$refs.list.offsetWidth - this.$refs.wrapper.offsetWidth,
            );
        },
    },
});
</script>
