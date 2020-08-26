<template>
    <div class="layout">
        <div class="layout__full">
            <div class="container">
                <div class="paging">
                    <div class="paging__item">
                        <i
                            class="icon icon--left icon--large icon--color--light-1 m--r--4"
                            @click="handleCancel"
                        ></i>
                        <div class="paging__item__title">Edit Profile</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="profile--card m--b--10">
                            <div
                                class="profile--card__holder profile--card__holder--padding-only-mobile"
                            >
                                <div
                                    class="profile--card__holder__image"
                                    style="background-image: url()"
                                >
                                    <div class="profile--card__holder__image__edit">
                                        <i class="icon icon--massive icon--camera"></i>
                                    </div>
                                    <div class="profile--card__holder__image__tag">
                                        PRO
                                    </div>
                                </div>
                            </div>
                            <div class="d--only--desktop">
                                <div class="profile--card__username m--b--0">
                                    {{ user && user.name }}
                                </div>
                                <div class="seperator seperator--large seperator--marginless"></div>
                                <div class="profile--card__footer">
                                    <div class="profile--card__footer__row">
                                        <div class="profile--card__footer__row__label">
                                            Full Name
                                        </div>
                                        <div class="profile--card__footer__row__value">
                                            Miguel Ángel Collazo
                                        </div>
                                    </div>
                                    <div class="profile--card__footer__row">
                                        <div class="profile--card__footer__row__label">
                                            Nationality
                                        </div>
                                        <div class="profile--card__footer__row__value">
                                            PR Puerto Rican
                                        </div>
                                    </div>
                                    <div class="profile--card__footer__row">
                                        <div class="profile--card__footer__row__label">
                                            Residency
                                        </div>
                                        <div class="profile--card__footer__row__value">
                                            US Denver, CO
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <form class="form">
                            <div class="section">
                                <div class="section__title">
                                    Account Information
                                </div>
                            </div>
                            <div class="row m--b--2">
                                <div class="col-xs-12 col-md-6">
                                    <label class="label label--large">USERNAME</label>
                                    <div class="form__control m--b--4">
                                        <input class="input input--large" />
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <label class="label label--large">EMAIL</label>
                                    <div class="form__control m--b--4">
                                        <input class="input input--large" />
                                    </div>
                                </div>
                            </div>
                            <div class="row m--b--6">
                                <div class="col-xs-12 col-md-6">
                                    <label class="label label--large">PASSWORD</label>
                                    <div class="form__control m--b--4">
                                        <input class="input input--large" type="password" />
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <label class="label label--large">CONFIRM PASSWORD</label>
                                    <div class="form__control m--b--4">
                                        <input class="input input--large" type="password" />
                                    </div>
                                </div>
                            </div>
                            <div class="section">
                                <div class="section__title">
                                    Personal Information
                                </div>
                            </div>
                            <div class="row m--b--2">
                                <div class="col-xs-12 col-md-6">
                                    <label class="label label--large">FIRST NAME</label>
                                    <div class="form__control m--b--4">
                                        <input class="input input--large" />
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <label class="label label--large">LAST NAME</label>
                                    <div class="form__control m--b--4">
                                        <input class="input input--large" />
                                    </div>
                                </div>
                            </div>
                            <div class="row m--b--6">
                                <div class="col-xs-12 col-md-6">
                                    <label class="label label--large">NATIONALITY</label>
                                    <div class="dropdown">
                                        <div class="form__control m--b--4">
                                            <input
                                                class="input input--large"
                                                type="text"
                                                value="Puerto Rico"
                                                readonly="readonly"
                                            />
                                            <div class="form__control__icon--right">
                                                <i class="icon icon--micro icon--down"></i>
                                            </div>
                                        </div>
                                        <div class="dropdown__content">
                                            <div class="dropdown__content__item">
                                                Puerto Rico
                                                <i
                                                    class="icon icon--smaller icon--check icon--color--yellow-1"
                                                ></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <label class="label label--large">RESIDENCY</label>
                                    <div class="dropdown">
                                        <div class="form__control m--b--4">
                                            <input
                                                class="input input--large"
                                                type="text"
                                                value="Denver, CO"
                                                readonly="readonly"
                                            />
                                            <div class="form__control__icon--right">
                                                <i class="icon icon--micro icon--down"></i>
                                            </div>
                                        </div>
                                        <div class="dropdown__content">
                                            <div class="dropdown__content__item">
                                                Denver, CO
                                                <i
                                                    class="icon icon--smaller icon--check icon--color--yellow-1"
                                                ></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row center-xs m--b--10 d--only--desktop">
                                <button
                                    class="button button--large button--inline button--border"
                                    @click="handleCancel"
                                >
                                    CANCEL
                                </button>
                                <a href="#" class="button button--large button--inline">SAVE</a>
                            </div>
                            <div class="fixed--margin d--only--mobile"></div>
                            <div class="fixed d--only--mobile">
                                <div class="fixed__item" @click="handleCancel">
                                    CANCEL
                                </div>
                                <div class="fixed__item fixed__item--green">
                                    SAVE
                                </div>
                            </div>
                        </form>
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
    name: "ProfileEdit",

    computed: {
        user(): User | null {
            return this.$stock.state.user.user;
        },
    },

    methods: {
        handleCancel(): void {
            this.$router.push("/profile");
        },
    },
});
</script>
