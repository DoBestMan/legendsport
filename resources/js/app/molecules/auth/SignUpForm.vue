<template>
    <div class="layout">
        <div class="layout__navbar layout__navbar--transparent">
            <div class="logo" @click="goToHome">
                <img class="logo__header" src="assets/i/Logo.png" alt="Logo" />
            </div>
        </div>
        <div class="layout__center">
            <div class="layout__center__wrapper" style="margin-top: 400px;">
                <h2 class="subtitle subtitle--light text--center">JOIN OUR COMMUNITY</h2>
                <form class="form" @submit.prevent="signUp">
                    <div class="form__control m--b--4">
                        <label class="label label--large">E-MAIL</label>
                        <FormInput
                            id="form-email"
                            inputClass="input input--large"
                            placeHolder="Ex. john@google.com"
                            type="email"
                            autocomplete="email"
                            :errors="errors.email"
                            v-model="email"
                            required
                        />
                        <div class="error" v-if="errors.email">
                            <span v-for="error in errors.email"> {{ error }} </span>
                        </div>
                    </div>
                    <div class="form__control m--b--4">
                        <label class="label label--large">USERNAME</label>
                        <FormInput
                            id="form-name"
                            inputClass="input input--large"
                            placeHolder="Pick a username"
                            type="text"
                            autocomplete="username"
                            :errors="errors.name"
                            v-model="name"
                            required
                        />
                        <div class="error" v-if="errors.name">
                            <span v-for="error in errors.name"> {{ error }} </span>
                        </div>
                    </div>
                    <div class="form__control m--b--4">
                        <label class="label label--large">FIRST NAME</label>
                        <FormInput
                            id="form-first-name"
                            inputClass="input input--large"
                            placeHolder="First name"
                            type="text"
                            autocomplete="firstname"
                            :errors="errors.firstname"
                            v-model="firstname"
                            required
                        />
                        <div class="error" v-if="errors.firstname">
                            <span v-for="error in errors.firstname"> {{ error }} </span>
                        </div>
                    </div>
                    <div class="form__control m--b--4">
                        <label class="label label--large">LAST NAME</label>
                        <FormInput
                            id="form-last-name"
                            inputClass="input input--large"
                            placeHolder="Last name"
                            type="text"
                            autocomplete="lastname"
                            :errors="errors.lastname"
                            v-model="lastname"
                            required
                        />
                        <div class="error" v-if="errors.lastname">
                            <span v-for="error in errors.lastname"> {{ error }} </span>
                        </div>
                    </div>

                    <div class="form__control m--b--4">
                        <label class="label label--large">DATE OF BIRTH</label>
                        <div style="display: flex; justify-content: space-between;">
                            <div class="dropdown" style="width: 30%;">
                                <div class="form__control">
                                    <input
                                        class="input input--large"
                                        type="text"
                                        v-model="day"
                                        readonly="readonly"
                                    />
                                    <div class="form__control__icon--right">
                                        <i class="icon icon--micro icon--down"></i>
                                    </div>
                                </div>
                                <div
                                    class="dropdown__content"
                                    style="z-index: 1000; width: 150px; max-height: 300px; overflow-y: auto;"
                                >
                                    <div
                                        class="dropdown__content__item"
                                        v-for="index in getDaysOfMonth()"
                                        :key="index"
                                        :value="index"
                                        @click="handleSelectDay(`${index}`)"
                                    >
                                        {{ index }}
                                        <i
                                            class="icon icon--smaller icon--check icon--color--yellow-1"
                                            v-if="isSelectedDay(`${index}`)"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="dropdown" style="width: 30%;">
                                <div class="form__control">
                                    <input
                                        class="input input--large"
                                        type="text"
                                        v-model="month"
                                        readonly="readonly"
                                    />
                                    <div class="form__control__icon--right">
                                        <i class="icon icon--micro icon--down"></i>
                                    </div>
                                </div>
                                <div
                                    class="dropdown__content"
                                    style="z-index: 1000; width: 150px; max-height: 300px; overflow-y: auto;"
                                >
                                    <div
                                        class="dropdown__content__item"
                                        v-for="index in monthArray"
                                        :key="index"
                                        :value="index"
                                        @click="handleSelectMonth(index)"
                                    >
                                        {{ index }}
                                        <i
                                            class="icon icon--smaller icon--check icon--color--yellow-1"
                                            v-if="isSelectedMonth(index)"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="dropdown" style="width: 30%;">
                                <div class="form__control">
                                    <input
                                        class="input input--large"
                                        type="text"
                                        v-model="year"
                                        readonly="readonly"
                                    />
                                    <div class="form__control__icon--right">
                                        <i class="icon icon--micro icon--down"></i>
                                    </div>
                                </div>
                                <div
                                    class="dropdown__content"
                                    style="z-index: 1000; width: 150px; max-height: 300px; overflow-y: auto;"
                                >
                                    <div
                                        class="dropdown__content__item"
                                        v-for="index in numberOfYears"
                                        :key="index"
                                        :value="startingYear + (index - 1)"
                                        @click="handleSelectYear(`${startingYear + (index - 1)}`)"
                                    >
                                        {{ startingYear + (index - 1) }}
                                        <i
                                            class="icon icon--smaller icon--check icon--color--yellow-1"
                                            v-if="isSelectedYear(`${startingYear + (index - 1)}`)"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="error" v-if="errors.dob">
                            <span v-for="error in errors.dob"> {{ error }} </span>
                        </div>
                    </div>

                    <div class="form__control m--b--4">
                        <label class="label label--large">PASSWORD</label>
                        <FormInput
                            id="form-password"
                            inputClass="input input--large"
                            placeHolder="Minimum 8 characters"
                            type="password"
                            autocomplete="current-password"
                            :errors="errors.password"
                            v-model="password"
                            minlength="8"
                            required
                        />
                        <div class="error" v-if="errors.password">
                            <span v-for="error in errors.password"> {{ error }} </span>
                        </div>
                    </div>
                    <div class="form__control m--b--10">
                        <label class="label label--large">CONFIRM PASSWORD</label>
                        <FormInput
                            id="form-password-confirmation"
                            inputClass="input input--large"
                            placeHolder="Minimum 8 characters"
                            type="password"
                            autocomplete="current-password"
                            :errors="errors.password_confirmation"
                            v-model="passwordConfirmation"
                            minlength="8"
                            required
                        />
                    </div>
                    <button type="submit" class="button button--large m--b--6">JOIN</button>
                </form>
                <div class="paragraph paragraph--small">
                    Already have an account?
                    <router-link class="link" to="/login">Log in</router-link>
                    <router-link class="link link--back" to="/">Back</router-link>
                </div>
                <div class="seperator"></div>
                <div class="paragraph paragraph--tiny">
                    By creating an account you are agreeing to Legend Sports’s
                    <a href="#" class="link">Terms of Use</a>
                    and
                    <a href="#" class="link">Privacy Policy</a>
                    and to be updated about Legend Sports products, news, and promotions.
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import { AxiosError } from "axios";
import moment from "moment";
import FormInput from "../../components/FormInput.vue";

export default Vue.extend({
    name: "SignUpForm",
    components: { FormInput },

    data() {
        return {
            errors: {},
            name: "",
            firstname: "",
            lastname: "",
            email: "",
            password: "",
            passwordConfirmation: "",
            day: "1",
            month: "01",
            year: "1940",
            monthArray: ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"],
            startingYear: 1940,
            numberOfYears: 81,
        };
    },

    methods: {
        getDaysOfMonth(): number {
            const date = this.year + "-" + this.month;
            return moment(date, "YYYY-MM").daysInMonth();
        },

        goToHome(): void {
            this.$router.push("/");
        },

        async signUp() {
            try {
                await this.$stock.state.api.signUp({
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.passwordConfirmation,
                    firstname: this.firstname,
                    lastname: this.lastname,
                    dob: this.year + "-" + this.month + "-" + this.day,
                });
                this.$stock.dispatch("user/reload");
                this.$router.push("/lobby");
            } catch (e) {
                this.$toast.error((e as AxiosError).response?.data.message);
                this.errors = (e as AxiosError).response?.data.errors ?? {};
            }
        },

        handleSelectDay(day: string): void {
            this.day = day;
        },

        isSelectedDay(day: string): boolean {
            return this.day === day;
        },

        handleSelectMonth(month: string): void {
            this.month = month;
        },

        isSelectedMonth(month: string): boolean {
            return this.month === month;
        },

        handleSelectYear(year: string): void {
            this.year = year;
        },

        isSelectedYear(year: string): boolean {
            return this.year === year;
        },
    },
});
</script>
