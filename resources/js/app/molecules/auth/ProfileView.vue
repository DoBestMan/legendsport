<template>
    <div class="layout">
        <div class="layout__full">
            <div class="container">
                <div class="paging">
                    <div class="paging__item">
                        <i
                            class="icon icon--left icon--large icon--color--light-1 m--r--4"
                            @click="goToHome"
                        ></i>
                        <div class="paging__item__title">Profile</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="profile--card">
                            <div class="profile--card__holder">
                                <div
                                    class="profile--card__holder__image"
                                    style="background-image: url()"
                                >
                                    <div class="profile--card__holder__image__tag">
                                        PRO
                                    </div>
                                </div>
                            </div>
                            <div class="profile--card__username">
                                {{ user && user.name }}
                            </div>
                            <div class="profile--card__edit">
                                <button
                                    class="profile--card__edit__button"
                                    @click="handleEditProfile"
                                >
                                    EDIT PROFILE
                                </button>
                            </div>
                            <div class="seperator seperator--large seperator--marginless"></div>
                            <div class="profile--card__footer">
                                <div class="profile--card__footer__row">
                                    <div class="profile--card__footer__row__label">Full Name</div>
                                    <div class="profile--card__footer__row__value">
                                        Miguel Ángel Collazo
                                    </div>
                                </div>
                                <div class="profile--card__footer__row">
                                    <div class="profile--card__footer__row__label">Nationality</div>
                                    <div class="profile--card__footer__row__value">
                                        PR Puerto Rican
                                    </div>
                                </div>
                                <div class="profile--card__footer__row">
                                    <div class="profile--card__footer__row__label">Residency</div>
                                    <div class="profile--card__footer__row__value">
                                        US Denver, CO
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-8">
                        <div class="card card--row">
                            <div class="progress">
                                <div class="progress__title">LVL 2: PRO</div>
                                <div class="progress__bar">
                                    <div class="progress__bar__value" style="width: 50%"></div>
                                </div>
                                <div class="progress__subtitle">34677/40000</div>
                            </div>
                            <i class="icon icon--massive icon--cup m--l--6"></i>
                        </div>
                        <div class="section">
                            <div class="section__title">
                                Highlights
                            </div>
                        </div>
                        <div class="card card--paddingless">
                            <div class="card__row">
                                <div class="card__row__label">
                                    TOURNAMENT NAME
                                </div>
                                <div class="card__row__value">
                                    1st
                                </div>
                            </div>
                            <div class="card__row">
                                <div class="card__row__label">
                                    TOURNAMENT NAME
                                </div>
                                <div class="card__row__value">
                                    1st
                                </div>
                            </div>
                            <div class="card__row">
                                <div class="card__row__label">
                                    TOURNAMENT NAME
                                </div>
                                <div class="card__row__value">
                                    1st
                                </div>
                            </div>
                        </div>
                        <div class="section">
                            <div class="section__title">
                                Stats
                            </div>
                            <div class="section__item">
                                <div class="switch switch--small">
                                    <div
                                        class="switch__item switch__item--small switch__item--active"
                                    >
                                        GAMES
                                    </div>
                                    <div class="switch__item switch__item--small">
                                        RANKS
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card--paddingless">
                            <div class="card__row card__row--large">
                                <div class="card__row__label">
                                    TOTAL EARNINGS
                                </div>
                                <div class="card__row__value">
                                    $100,000,000
                                </div>
                            </div>
                            <div class="card__row card__row--large">
                                <div class="card__row__label">
                                    AVERAGE BUY-IN
                                </div>
                                <div class="card__row__value">
                                    $250
                                </div>
                            </div>
                            <div class="card__row card__row--large">
                                <div class="card__row__label">
                                    BEST TOURNAMENT FINISH
                                </div>
                                <div class="card__row__value">
                                    1st
                                </div>
                            </div>
                            <div class="card__row card__row--large">
                                <div class="card__row__label">
                                    RETURN ON INVESTMENT
                                </div>
                                <div class="card__row__value">
                                    <div class="pie">
                                        <div
                                            class="pie__line"
                                            style="background: conic-gradient(#f2cd5d 0%, #f2cd5d 18%, transparent 18%, transparent 100%) 50% 50% / 100% 100% no-repeat"
                                        ></div>
                                        <div class="pie__value">
                                            18%
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card__row card__row--large">
                                <div class="card__row__label">
                                    IN THE MONEY RATE
                                </div>
                                <div class="card__row__value">
                                    <div class="pie">
                                        <div
                                            class="pie__line"
                                            style="background: conic-gradient(#f2cd5d 0%, #f2cd5d 18%, transparent 18%, transparent 100%) 50% 50% / 100% 100% no-repeat"
                                        ></div>
                                        <div class="pie__value">
                                            18%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import { User } from "../../../general/types/user";

export default Vue.extend({
    name: "ProfileView",

    computed: {
        user(): User | null {
            return this.$stock.state.user.user;
        },
    },

    methods: {
        goToHome(): void {
            this.$router.push("/");
        },

        handleEditProfile(): void {
            this.$router.push("/profile-edit");
        },
    },
});
</script>
