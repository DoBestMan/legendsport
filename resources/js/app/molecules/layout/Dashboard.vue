<template>
    <div class="dashboard-container">
        <div class="bannersection">
            <div class="container">
                <div class="column">
                    <div class="columnhalf">
                        <h2 style="font-family: Poppins, sans-sarif !important;">
                            Do you have what it takes to be a legend?
                        </h2>
                        <a href="/signup" class="btn-bnner bgtheme">Join Now</a>
                    </div>
                    <div class="columnhalf"></div>
                </div>
            </div>
        </div>

        <div class="joinsection">
            <div class="container">
                <div class="column">
                    <div class="columnhalf">
                        <div class="tournament-content">
                            <h2
                                class="section-title"
                                style="font-family: Poppins, sans-sarif !important;"
                            >
                                Join A <br />Tournament
                            </h2>
                            <a href="/signup" class="btn-bnner bgtheme">Join Now</a>
                        </div>
                    </div>
                    <div class="columnhalf"></div>
                </div>
            </div>
        </div>

        <div class="how-sectiopn" id="how-to-play">
            <div class="container">
                <h2 class="section-title" style="font-family: Poppins, sans-sarif !important;">
                    How To Play
                </h2>
                <div class="column" style="align-items: unset;">
                    <div class="onethird onethird-left">
                        <div class="play-steps">
                            <h4>Step 1</h4>
                            <p>
                                Register for a tournament! Tournaments vary by sports (NFL, MLB,
                                NBA, NHL, Etc.) and by length (Daily, Weekly, Monthly.) We also
                                offer Free4all tournaments that include all sports.
                            </p>
                        </div>

                        <div class="play-steps">
                            <h4>Step 2</h4>
                            <p>Get your virtual chips.</p>
                        </div>

                        <div class="play-steps">
                            <h4>Step 3</h4>
                            <p>
                                Wager your virtual chips. You can place straight wagers or parlays.
                            </p>
                        </div>

                        <div class="play-steps">
                            <h4>Step 4</h4>
                            <p>Re-wager your winning chips from successful wagers.</p>
                        </div>
                    </div>

                    <div class="onethird">
                        <div class="palyimg"><img src="assets/i/howimg.png" /></div>
                    </div>

                    <div class="onethird onethird-right">
                        <div class="play-steps">
                            <h4>Step 5</h4>
                            <p>
                                The player with the most virtual chips at the end of the tournament
                                will be declared the winner and claim top prize.
                            </p>
                        </div>

                        <div class="play-steps">
                            <h4>Step 6</h4>
                            <p>
                                Become a legend and win cash prizes. Prized are paid out in
                                accordance with the tournament standings.
                            </p>
                        </div>

                        <div class="play-steps">
                            <h4>Step 7</h4>
                            <p>Play more tournaments and vault up the leaderboard.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="faq-sectiopn">
            <div class="faqlimage"></div>
            <div class="container">
                <h2 class="section-title" style="font-family: Poppins, sans-sarif !important;">
                    FAQ
                </h2>
                <div class="column">
                    <div class="full">
                        <div class="accordion_container">
                            <div class="tabcard" v-for="faq in faqs" :key="faq.id">
                                <div class="accordion_head">
                                    <span
                                        @click="changeSign(faq.id)"
                                        v-b-toggle="'collapse-' + faq.id"
                                        variant="info"
                                        style="font-family: Poppins, sans-sarif !important;"
                                    >
                                        <span>{{ faq.id }}</span
                                        >{{ faq.title }}</span
                                    >
                                    <span
                                        class="plusminus"
                                        @click="changeSign(faq.id)"
                                        v-b-toggle="'collapse-' + faq.id"
                                        >{{ faq.isActive ? "-" : "+" }}</span
                                    >
                                </div>
                                <b-collapse
                                    visible
                                    accordion="my-accordion"
                                    :id="'collapse-' + faq.id"
                                >
                                    <div class="accordion_body">
                                        <p style="font-family: Poppins, sans-sarif !important;">
                                            {{ faq.body }}
                                        </p>
                                    </div>
                                </b-collapse>
                            </div>
                        </div>
                    </div>

                    <div class="btn-center">
                        <a @click="loadMore" v-if="loadButton" class="btn-bnner bgtheme">
                            Load More
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="testimonial-section" id="reviews">
            <div class="testimonialimage"></div>
            <div class="container" style="cursor: pointer;">
                <h2 class="section-title" style="font-family: Poppins, sans-sarif !important;">
                    Testimonials
                </h2>

                <carousel
                    :dots="false"
                    :responsive="{
                        0: { items: 1 },
                        600: { items: 3 },
                        1000: { items: 3, margin: 30 },
                    }"
                    :margin="10"
                    :center="true"
                    :items="3"
                    :loop="true"
                >
                    <div slot="prev"><span class="prev" style="color: white;">prev</span></div>
                    <div class="item">
                        <div class="testicontent">
                            <div class="quote-icon"></div>
                            <p style="font-family: Poppins, sans-sarif !important;">
                                Since I started playing I have won 4 tournaments and have gotten all
                                my friends to come play. Its our own game of bragging rights when we
                                go back to the office the next day.
                            </p>
                            <span
                                class="author"
                                style="font-family: Poppins, sans-sarif !important;"
                                >- MegatronDET</span
                            >
                        </div>
                    </div>
                    <div class="item">
                        <div class="testicontent">
                            <div class="quote-icon"></div>
                            <p style="font-family: Poppins, sans-sarif !important;">
                                Having the chips update automatically has really changed the game. I
                                have been sports betting for years but having additional competition
                                raises the stakes and the winning potential.
                            </p>
                            <span
                                class="author"
                                style="font-family: Poppins, sans-sarif !important;"
                                >- ADonaldRams</span
                            >
                        </div>
                    </div>
                    <div class="item">
                        <div class="testicontent">
                            <div class="quote-icon"></div>
                            <p style="font-family: Poppins, sans-sarif !important;">
                                Legends is Free to enter and gives away $$.  Cant beat that!
                            </p>
                            <span
                                class="author"
                                style="font-family: Poppins, sans-sarif !important;"
                                >- Wolfofwall122</span
                            >
                        </div>
                    </div>
                    <div class="item">
                        <div class="testicontent">
                            <div class="quote-icon"></div>
                            <p style="font-family: Poppins, sans-sarif !important;">
                                I cant stop playing just so focused on watching the games and seeing
                                myself move higher on the rankings. High placement on the rankings
                                means I can win cash.
                            </p>
                            <span
                                class="author"
                                style="font-family: Poppins, sans-sarif !important;"
                                >- BroncosNation</span
                            >
                        </div>
                    </div>
                    <div class="item">
                        <div class="testicontent">
                            <div class="quote-icon"></div>
                            <p style="font-family: Poppins, sans-sarif !important;">
                                The NFL Sunday daily tournament has given me a reason to watch every
                                game for a myriad of reasons. Changed the game.
                            </p>
                            <span
                                class="author"
                                style="font-family: Poppins, sans-sarif !important;"
                                >- AllSports99</span
                            >
                        </div>
                    </div>
                    <div class="item">
                        <div class="testicontent">
                            <div class="quote-icon"></div>
                            <p style="font-family: Poppins, sans-sarif !important;">
                                The combination of different lines makes it so you can diversify you
                                bets from aggressive to passive. At some point though, you have to
                                go for high stakes wagers to win.
                            </p>
                            <span
                                class="author"
                                style="font-family: Poppins, sans-sarif !important;"
                                >- RowtheboatMN</span
                            >
                        </div>
                    </div>
                    <div class="item">
                        <div class="testicontent">
                            <div class="quote-icon"></div>
                            <p style="font-family: Poppins, sans-sarif !important;">
                                I won $850 so far playing for free. Highly recommend.
                            </p>
                            <span
                                class="author"
                                style="font-family: Poppins, sans-sarif !important;"
                                >- Wolfofwallst</span
                            >
                        </div>
                    </div>
                    <div class="item">
                        <div class="testicontent">
                            <div class="quote-icon"></div>
                            <p style="font-family: Poppins, sans-sarif !important;">
                                Tournament style just crushed regular straight wagers. Not even
                                close.
                            </p>
                            <span
                                class="author"
                                style="font-family: Poppins, sans-sarif !important;"
                                >- Jamal49ers</span
                            >
                        </div>
                    </div>
                    <div class="item">
                        <div class="testicontent">
                            <div class="quote-icon"></div>
                            <p style="font-family: Poppins, sans-sarif !important;">
                                I would rather bet on the teams in a. tournament style than the
                                players. Offers greater intrigue to the end rather than singular
                                focus on a player.
                            </p>
                            <span
                                class="author"
                                style="font-family: Poppins, sans-sarif !important;"
                                >- SteveBalt1999</span
                            >
                        </div>
                    </div>
                    <div class="item">
                        <div class="testicontent">
                            <div class="quote-icon"></div>
                            <p style="font-family: Poppins, sans-sarif !important;">
                                All you have to do is hit a couple of big parlays and you can win
                                free cash. Almost won a grand playing for free.
                            </p>
                            <span
                                class="author"
                                style="font-family: Poppins, sans-sarif !important;"
                                >- rememberTitans</span
                            >
                        </div>
                    </div>
                    <div slot="prev"><span class="prev" style="color: white;">prev</span></div>
                </carousel>
            </div>
        </div>

        <footer class="main-footer">
            <div class="container">
                <div class="foot-column">
                    <div class="footlogo">
                        <a href="/"><img src="assets/i/footlogo.png"/></a>
                    </div>
                    <div class="foolink">
                        <h3 style="font-family: Poppins, sans-sarif !important;">Quick Links</h3>
                        <ul class="footnav">
                            <li>
                                <a
                                    href="/#how-to-play"
                                    style="font-family: Poppins, sans-sarif !important;"
                                    >How To Play</a
                                >
                            </li>
                            <li>
                                <a
                                    href="/terms-of-service"
                                    style="font-family: Poppins, sans-sarif !important;"
                                    >Terms & Conditions</a
                                >
                            </li>
                            <li>
                                <a
                                    href="/#reviews"
                                    style="font-family: Poppins, sans-sarif !important;"
                                    >Reviews</a
                                >
                            </li>
                            <li>
                                <a
                                    href="/about"
                                    style="font-family: Poppins, sans-sarif !important;"
                                    >About</a
                                >
                            </li>
                            <li>
                                <a
                                    href="/privacy-policy"
                                    style="font-family: Poppins, sans-sarif !important;"
                                    >Privacy Policy</a
                                >
                            </li>
                        </ul>
                    </div>
                    <div class="foot-contant">
                        <h3 style="font-family: Poppins, sans-sarif !important;">Contact Us</h3>
                        <p style="font-family: Poppins, sans-sarif !important;">
                            2700 N Military Trail<br />Boca Raton, FL 33431
                        </p>
                        <a
                            style="font-family: Poppins, sans-sarif !important;"
                            href="mailto:support@legendsbet.com"
                            target="_blank"
                            >support@legendsbet.com</a
                        >
                    </div>
                    <div class="footsocial">
                        <h3 style="font-family: Poppins, sans-sarif !important;">
                            Follow Us on Social Media
                        </h3>
                        <ul class="socila-list">
                            <li>
                                <a
                                    href="https://www.facebook.com/LegendsBet-113424260484677/"
                                    target="_blank"
                                >
                                    <img src="assets/i/FB.png" />
                                </a>
                            </li>
                            <li>
                                <a href="https://twitter.com/PlayLegendsBet" target="_blank">
                                    <img src="assets/i/Twitt.png" />
                                </a>
                            </li>
                            <li>
                                <a href="https://www.instagram.com/playlegendsbet/" target="_blank">
                                    <img src="assets/i/IG.png" />
                                </a>
                            </li>
                            <li>
                                <a
                                    href="https://www.linkedin.com/company/legendsbet"
                                    target="_blank"
                                >
                                    <img src="assets/i/IN.png" />
                                </a>
                            </li>
                            <li>
                                <a
                                    href="https://www.tiktok.com/@playlegendsbet?lang=en"
                                    target="_blank"
                                >
                                    <img src="assets/i/tiktok.png" />
                                </a>
                            </li>
                            <li>
                                <a
                                    href="https://www.youtube.com/channel/UCteiraCqy3kI2k56od0qoeA"
                                    target="_blank"
                                >
                                    <img src="assets/i/YT.png" />
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="foot-copy">
                    <p style="font-family: Poppins, sans-sarif !important;">
                        &copy; 2020 <span>LegendsBet</span>. All Rights Reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script>
import carousel from "vue-owl-carousel";
export default {
    name: "Dashboard",
    components: {
        carousel,
    },

    data() {
        return {
            visible: false,
            currentArr: 5,
            oldArr: 0,
            loadButton: true,
            faqs: [
                {
                    id: "01",
                    title: "What is Tournament Betting?",
                    body:
                        "With Tournament Betting, you aren't betting on a single game or against the dreaded house. In\n" +
                        "tournament betting you play against other players and try to outmaneuver them to climb the\n" +
                        "leaderboard. Once all events in the specific tournament are complete the tournament has\n" +
                        "officially ended.",
                    isActive: true,
                },
                {
                    id: "02",
                    title: "How do I get paid?",
                    body:
                        "Simply go to the cashier and enter the amount you with to withdraw ($50 minimum.) Right now we are only processing withdrawals via Bitcoin, so enter your BTC receiving address and you will receive your cash (BTC payment made every Monday around noon eastern).",
                    isActive: false,
                },
                {
                    id: "03",
                    title: "Can I rollover chips to a different tournament?",
                    body:
                        "No, virtual chips are specific to each tournament and have no cash value. Once the event has ended those chips will help to move you up the leaderboard, but once the event is over they are only available to view.",
                    isActive: false,
                },
                {
                    id: "04",
                    title: "Can I pay for additional chips?",
                    body:
                        "Not at the moment, right now only offer freeroll tournaments. But in the future we plan to offer users re-buy events where you will be able to reload if you are running low on chips.*",
                    isActive: false,
                },
                {
                    id: "05",
                    title: " Is there a minimum number of bets?",
                    body: "No, you can place as many or as few bets as you would like.",
                    isActive: false,
                },
                {
                    id: "06",
                    title: " Is there a maximum wager for any bet?",
                    body:
                        "No, you can wager your entire balance at anytime. And BE AGGRESSIVE, that's you best chance to win a tournament and become a legend.",
                    isActive: false,
                },
            ],
        };
    },

    methods: {
        changeSign: function(index) {
            this.faqs.filter(faq => {
                if (index === faq.id) {
                    faq.isActive = !faq.isActive;
                } else {
                    faq.isActive = false;
                }
            });
        },

        loadMore: function() {
            let arr = [
                {
                    id: "07",
                    title: " How do I join the LegendsBet team?",
                    body:
                        "At the moment we aren't hiring, but if you are interested in joining the team please reach out to us via the contact us link and we'd be happy to keep you in mind when a position opens up.",
                    isActive: false,
                },
                {
                    id: "08",
                    title: "Do you have social media?",
                    body:
                        "Yes, please follow us on Twitter, Facebook, LinkedIn and Instagram. Our links can be found in the footer at the bottom of the home page.",
                    isActive: false,
                },
                {
                    id: "09",
                    title: "Can I play with real money?",
                    body:
                        "At the moment we do not offer real money buy-in tournaments, but we do plan to offer them in the near future.",
                    isActive: false,
                },
                {
                    id: "10",
                    title: " How old do you have to be to play?",
                    body:
                        "You must be 21 or older to participate in our tournament for real money prizes.",
                    isActive: false,
                },
                {
                    id: "11",
                    title: " Can I bet once a game has started?",
                    body:
                        "Yes, we offer live lines so if you missed tip-off or first pitch, no worries, bet anytime you'd like.",
                    isActive: false,
                },
                {
                    id: "12",
                    title: "Can I join a tournament late?",
                    body:
                        "Yes, we offer late registering on all of our tournaments, however keep in mind if the event is nearing it's close late registering may not be available. So sign up early and often! Tourney's are FREE!.",
                    isActive: false,
                },
                {
                    id: "13",
                    title: " How can I refer people?",
                    body:
                        "At the moment we don't have a referral program, but if you are enjoying the tournaments, please feel free to give us a like on Facebook, or retweet us on twitter.",
                    isActive: false,
                },
                {
                    id: "14",
                    title: " Do I have to pay taxes on prize winnings?",
                    body:
                        "We do not provide W2-G's on contest winnings, however you should consult your professional tax advisor.",
                    isActive: false,
                },
                {
                    id: "15",
                    title: "Do you have a privacy policy?",
                    body: "Yes, please see the privacy policy here.",
                    isActive: false,
                },
                {
                    id: "16",
                    title: " Do you have a terms of service?",
                    body: "Yes, please see the terms of service here.",
                    isActive: false,
                },
            ];

            for (let i = this.oldArr; i < this.currentArr; i += 1) {
                this.faqs.push(arr[i]);
            }
            this.oldArr += this.currentArr;
            this.currentArr += this.currentArr;

            if (this.faqs.length < this.currentArr) {
                this.loadButton = false;
                return;
            }
        },
    },
};
</script>

<style scoped></style>
