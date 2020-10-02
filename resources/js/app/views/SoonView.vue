<template>
    <div class="layout">
        <div class="layout__center layout__center--background">
            <div class="container">
                <div class="container container--small">
                    <div class="center">
                        <div class="logo m--b--10">
                            <img class="logo__header" src="assets/i/Logo.png" alt="Logo" />
                        </div>
                    </div>
                    <div class="center">
                        <div class="subtitle subtitle--soon">
                            COMING SOON
                        </div>
                    </div>
                    <div class="seperator seperator--light"></div>
                    <div class="paragraph text--center">
                        Keep up to date with the project
                    </div>
                    <div class="d--flex">
                        <input
                            class="input input--medium input--light"
                            placeholder="Enter your e-mail"
                        />
                        <a href="#" class="button button--medium button--yellow m--l--4">SUBMIT</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
export default Vue.extend({
    name: "SoonView",
});
</script>
