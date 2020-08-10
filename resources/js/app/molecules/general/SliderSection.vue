<template>
    <section>
        <div class="layout__content__container__slider">
            <div class="sliders">
                <div class="sliders--desktop">
                    <div class="slider" style="background-image: url(assets/i/slider-1@3x.jpg)">
                        <div class="slider__paginations">
                            <div class="pagination"></div>
                            <div class="pagination pagination--active"></div>
                            <div class="pagination"></div>
                            <div class="pagination"></div>
                            <div class="pagination"></div>
                        </div>
                        <div class="slider__content">
                            <div class="slider__content__title">World Series Win Bonus</div>
                            <div class="slider__content__description">Earn Up To $50 Bonus!</div>
                        </div>
                        <div class="slider__controls">
                            <div class="slider__controls__control">
                                <i class="icon icon--arrow-left icon--color--dark-3"></i>
                            </div>
                            <div class="slider__controls__control">
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
                    <div class="slider" style="background-image: url(assets/i/slider-1@3x.jpg)">
                        <div class="slider__content">
                            <div class="slider__content__title">World Series Win Bonus</div>
                            <div class="slider__content__description">Earn Up To $50 Bonus!</div>
                        </div>
                    </div>
                    <div class="slider" style="background-image: url(assets/i/slider-1@3x.jpg)">
                        <div class="slider__content">
                            <div class="slider__content__title">World Series Win Bonus</div>
                            <div class="slider__content__description">Earn Up To $50 Bonus!</div>
                        </div>
                    </div>
                    <div class="slider" style="background-image: url(assets/i/slider-1@3x.jpg)">
                        <div class="slider__content">
                            <div class="slider__content__title">World Series Win Bonus</div>
                            <div class="slider__content__description">Earn Up To $50 Bonus!</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="paginations d--only--mobile">
                <div class="pagination"></div>
                <div class="pagination pagination--active"></div>
                <div class="pagination"></div>
                <div class="pagination"></div>
                <div class="pagination"></div>
            </div>
        </div>
    </section>
</template>

<script lang="ts">
import Vue from "vue";

export default Vue.extend({
    name: "SliderSection",
});
</script>
