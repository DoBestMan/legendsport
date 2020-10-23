<template>
    <div class="layout__full">
        <div class="container" v-if="isDesktop">
            <div class="paging">
                <div class="paging__item">
                    <i
                        class="icon icon--left icon--large icon--color--light-1 m--r--4"
                        @click="goToHome"
                    ></i>
                    <div class="paging__item__title">Cashier</div>
                </div>
            </div>

            <div class="center d--only--desktop">
                <div class="switch m--b--10">
                    <div
                        class="switch__item switch__item--large"
                        :class="{
                            'switch__item--active': isTypeSelected('Deposit'),
                        }"
                        @click="selectType('Withdraw')"
                    >
                        <i class="icon icon--micro icon--deposit m--r--1"></i>
                        DEPOSIT
                    </div>
                    <div
                        class="switch__item switch__item--large"
                        :class="{
                            'switch__item--active': isTypeSelected('Withdraw'),
                        }"
                        @click="selectType('Withdraw')"
                    >
                        <i class="icon icon--micro icon--withdraw m--r--1"></i>
                        WITHDRAW
                    </div>
                </div>
            </div>

            <div class="tabs" v-if="isTypeSelected('Deposit')">
                <div
                    class="tabs__item"
                    :class="{
                        'tabs__item--active': isFormSelected('BankWire'),
                    }"
                    @click="selectForm('BankWire')"
                >
                    <div class="tabs__item__icon">
                        <i
                            class="icon icon--tile icon--cashier-bank"
                            :class="{
                                'icon--color--yellow-2': isFormSelected('BankWire'),
                                'icon--color--light-1': !isFormSelected('BankWire'),
                            }"
                        ></i>
                    </div>
                    <div class="tabs__item__title">Bank Wire</div>
                </div>

                <div
                    class="tabs__item"
                    :class="{
                        'tabs__item--active': isFormSelected('CreditCard'),
                    }"
                    @click="selectForm('CreditCard')"
                >
                    <div class="tabs__item__icon">
                        <i
                            class="icon icon--tile icon--cashier-card"
                            :class="{
                                'icon--color--yellow-2': isFormSelected('CreditCard'),
                                'icon--color--light-1': !isFormSelected('CreditCard'),
                            }"
                        ></i>
                    </div>
                    <div class="tabs__item__title">Credit Card</div>
                </div>

                <div
                    class="tabs__item"
                    :class="{
                        'tabs__item--active': isFormSelected('Paypal'),
                    }"
                    @click="selectForm('Paypal')"
                >
                    <div class="tabs__item__icon">
                        <i
                            class="icon icon--tile icon--cashier-paypal"
                            :class="{
                                'icon--color--yellow-2': isFormSelected('Paypal'),
                                'icon--color--light-1': !isFormSelected('Paypal'),
                            }"
                        ></i>
                    </div>
                    <div class="tabs__item__title">PayPal</div>
                </div>

                <div
                    class="tabs__item"
                    :class="{
                        'tabs__item--active': isFormSelected('Bitcoin'),
                    }"
                    @click="selectForm('Bitcoin')"
                >
                    <div class="tabs__item__icon">
                        <i
                            class="icon icon--tile icon--cashier-bitcoin"
                            :class="{
                                'icon--color--yellow-2': isFormSelected('Bitcoin'),
                                'icon--color--light-1': !isFormSelected('Bitcoin'),
                            }"
                        ></i>
                    </div>
                    <div class="tabs__item__title">Bitcoin</div>
                </div>
            </div>

            <div class="container container--small">
                <BankWireForm v-if="isTypeSelected('Deposit') && isFormSelected('BankWire')" />
                <CreditCardForm v-if="isTypeSelected('Deposit') && isFormSelected('CreditCard')" />
                <PaypalForm v-if="isTypeSelected('Deposit') && isFormSelected('Paypal')" />
                <BitcoinForm v-if="isTypeSelected('Deposit') && isFormSelected('Bitcoin')" />
                <WithdrawForm v-if="isTypeSelected('Withdraw')" />
            </div>
        </div>

        <div class="container" v-else>
            <div class="paging m--b--0">
                <div class="paging__item">
                    <i
                        class="icon icon--left icon--large icon--color--light-1 m--r--4"
                        @click="goToHome"
                    ></i>
                    <div class="paging__item__title">Cashier</div>
                </div>
            </div>
            <div class="center">
                <div class="switch m--b--6 w--100">
                    <div
                        class="switch__item switch__item--large"
                        :class="{
                            'switch__item--active': isTypeSelected('Deposit'),
                        }"
                        @click="selectType('Withdraw')"
                    >
                        <i class="icon icon--micro icon--deposit m--r--1"></i>
                        DEPOSIT
                    </div>
                    <div
                        class="switch__item switch__item--large"
                        :class="{
                            'switch__item--active': isTypeSelected('Withdraw'),
                        }"
                        @click="selectType('Withdraw')"
                    >
                        <i class="icon icon--micro icon--withdraw m--r--1"></i>
                        WITHDRAW
                    </div>
                </div>
            </div>
            <div class="tabs tabs--mobile m--b--4" v-if="isTypeSelected('Deposit')">
                <div class="tabs__item" @click="handleBankWire">
                    <div class="tabs__item__icon">
                        <i class="icon icon--tile icon--cashier-bank icon--color--light-1"></i>
                    </div>
                    <div class="tabs__item__title">Bank Wire</div>
                </div>
                <div class="tabs__item" @click="handleCreditCard">
                    <div class="tabs__item__icon">
                        <i class="icon icon--tile icon--cashier-card icon--color--light-1"></i>
                    </div>
                    <div class="tabs__item__title">Credit Card</div>
                </div>
            </div>
            <div class="tabs tabs--mobile" v-if="isTypeSelected('Deposit')">
                <div class="tabs__item" @click="handlePaypal">
                    <div class="tabs__item__icon">
                        <i class="icon icon--tile icon--cashier-paypal icon--color--light-1"></i>
                    </div>
                    <div class="tabs__item__title">PayPal</div>
                </div>
                <div class="tabs__item" @click="handleBitcoin">
                    <div class="tabs__item__icon">
                        <i class="icon icon--tile icon--cashier-bitcoin icon--color--light-1"></i>
                    </div>
                    <div class="tabs__item__title">Bitcoin</div>
                </div>
            </div>
            <div class="container container--small" v-if="isTypeSelected('Withdraw')">
                <WithdrawForm v-if="isTypeSelected('Withdraw')" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import BankWireForm from "./BankWireForm.vue";
import BitcoinForm from "./BitcoinForm.vue";
import CreditCardForm from "./CreditCardForm.vue";
import PaypalForm from "./PaypalForm.vue";
import WithdrawForm from "./WithdrawForm.vue";
export default Vue.extend({
    name: "CashierView",

    components: {
        BankWireForm,
        BitcoinForm,
        CreditCardForm,
        PaypalForm,
        WithdrawForm,
    },

    data() {
        return {
            selectedFormName: "BankWire",
            selectedTypeName: "Withdraw",
        };
    },

    computed: {
        isDesktop(): boolean {
            if (window.innerWidth > 768) return true;
            return false;
        },
    },

    methods: {
        goToHome(): void {
            this.$router.push("/lobby");
        },

        handleBankWire(): void {
            this.$router.push("/bankwire");
        },

        handleCreditCard(): void {
            this.$router.push("/cc");
        },

        handlePaypal(): void {
            this.$router.push("/paypal");
        },

        handleBitcoin(): void {
            this.$router.push("/bitcoin");
        },

        isFormSelected(selected: string): boolean {
            return this.selectedFormName === selected;
        },

        isTypeSelected(selected: string): boolean {
            return this.selectedTypeName === selected;
        },

        selectForm(selected: string): void {
            this.selectedFormName = selected;
        },

        selectType(selected: string): void {
            this.selectedTypeName = selected;
        },
    },
});
</script>
