<template>
    <footer id="footer-frm" class="row">
        <div id="advertising-frm" class="col-4">
            <div id="advertising-image"></div>
        </div>

        <div id="links-frm" class="offset-4 col-3">
            <div class="row">
                <div class="col-4">
                    <div class="links-title">About<span class="separator">|</span></div>

                    <div class="link-frm">
                        <a class="link" href="#">About us</a>
                    </div>

                    <div class="link-frm">
                        <a class="link" href="#">Privacy</a>
                    </div>

                    <div class="link-frm">
                        <a class="link" href="#">Terms of use</a>
                    </div>
                </div>

                <div class="col-4">
                    <div class="links-title">Support<span class="separator">|</span></div>

                    <div class="link-frm">
                        <a class="link" href="#">Contact us</a>
                    </div>

                    <div class="link-frm addMultiline">
                        <a class="link" href="#">Forgot password</a>
                    </div>
                </div>

                <div class="col-4">
                    <div class="links-title">Follow us</div>

                    <div class="link-frm">
                        <a class="link" href="#">
                            <i class="icon fab fa-facebook-square"></i>Facebook
                        </a>
                    </div>

                    <div class="link-frm">
                        <a class="link" href="#">
                            <i class="icon fab fa-twitter-square"></i>Twitter
                        </a>
                    </div>

                    <div class="link-frm">
                        <a class="link" href="#">
                            <i class="icon fab fa-instagram"></i>Instagram
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-1">
            <button type="button" class="btn btn-secondary float-right">
                <i class="fas fa-angle-up"></i>
            </button>
        </div>
    </footer>
</template>

<script lang="ts">
import Vue from "vue";

export default Vue.extend({
    name: "Footer",
});
</script>
