<template>
    <section>
        <div class="layout__content__container__slider">
            <div class="sliders">
                <div class="sliders--desktop">
                    <div class="slider" :style="{ 'background-image': `url(${activeImageUrl})` }">
                        <div class="slider__paginations">
                            <div
                                class="pagination"
                                v-for="(src, index) in imageSrcs"
                                :key="index"
                                :class="{
                                    'pagination--active': index === activeImageUrlId,
                                }"
                            />
                        </div>
                        <div class="slider__content">
                            <div class="slider__content__title">{{ sliderTitle }}</div>
                            <div class="slider__content__description">{{ sliderText }}</div>
                        </div>
                        <div class="slider__controls">
                            <div class="slider__controls__control" @click="handleLeft">
                                <i class="icon icon--arrow-left icon--color--dark-3"></i>
                            </div>
                            <div class="slider__controls__control" @click="handleRight">
                                <i class="icon icon--arrow-right icon--color--dark-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="layout__content__container__slider--mobile">
            <div class="sliders">
                <div class="sliders--mobile">
                    <div class="slider" :style="{ 'background-image': `url(${activeImageUrl})` }">
                        <div class="slider__content">
                            <div class="slider__content__title">{{ sliderTitle }}</div>
                            <div class="slider__content__description">{{ sliderText }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="paginations d--only--mobile">
                <div
                    class="pagination"
                    v-for="(src, index) in imageSrcs"
                    :key="index"
                    :class="{
                        'pagination--active': index === activeImageUrlId,
                    }"
                />
            </div>
        </div>
    </section>
</template>

<script lang="ts">
import Vue from "vue";

export default Vue.extend({
    name: "SliderSection",

    data() {
        return {
            activeImageUrlId: 0,
            imageSrcs: [
                "assets/i/slider-1@3x.jpg",
                "assets/i/slider-1@3x.jpg",
                "assets/i/slider-1@3x.jpg",
                "assets/i/slider-1@3x.jpg",
                "assets/i/slider-1@3x.jpg",
            ],
        };
    },

    computed: {
        activeImageUrl(): String {
            return this.imageSrcs[this.activeImageUrlId];
        },
        sliderTitle(): String {
            return '';
        },
        sliderText(): String {
            return '';
        }
    },

    methods: {
        handleLeft(): void {
            if (this.activeImageUrlId === 0) this.activeImageUrlId = this.imageSrcs.length - 1;
            else this.activeImageUrlId -= 1;
        },

        handleRight(): void {
            if (this.activeImageUrlId === this.imageSrcs.length - 1) this.activeImageUrlId = 0;
            else this.activeImageUrlId += 1;
        },
    },
});
</script>
