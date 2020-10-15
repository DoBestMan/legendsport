import Vue from "vue";
import axios from "axios";
import {setup} from "../utils/setup.js";
import ModalDelete from "../components/ModalDelete";
import ModalAvailableEventList from "../molecules/tournament/ModalAvailableEventList";
import SelectedEventList from "../molecules/tournament/SelectedEventList";
import TournamentForm from "../molecules/tournament/TournamentForm";
import notificationStore from "../stores/notificationStore";
import FullLoader from "../components/FullLoader";
import loaderStore from "../stores/loaderStore";
import sportStore from "../stores/sportStore";
import ActionButton from "../../general/components/ActionButton";
import ModalComplete from "../molecules/tournament/ModalComplete";

setup();

sportStore.load().catch(console.error);

new Vue({
    el: "#main",

    components: {
        ActionButton,
        FullLoader,
        ModalAvailableEventList,
        ModalDelete,
        ModalComplete,
        SelectedEventList,
        TournamentForm,
    },

    data: {
        name: "",
        playersLimit: "",
        buyIn: 0,
        commission: 0,
        chips: 0,
        lateRegister: "",
        interval: "",
        lateRegisterValue: "",
        prizePool: "",
        prizePoolValue: "",
        state: "",
        selectedEvents: [],
        timeFrame: "",
        errors: {},
        minBots: 0,
        maxBots: 0,
        addBots: 0,
        playerBots: 1,
        autoEnd: 0,

        isModalAvailableEventListVisible: false,
        modalDeleteId: null,
        modalDeleteDescription: null,
        modalCompleteId: null,
        modalCompleteDescription: null,
    },

    created() {
        this.name = phpVars.name;
        this.buyIn = phpVars.buyIn / 100;
        this.selectedEvents = phpVars.apiSelectedSports || [];

        if (this.chips != "" || phpVars.config == "") {
            this.chips = phpVars.chips;
            this.commission = phpVars.commission / 100;
        } else {
            this.commission = phpVars.config["commission"] / 100;
            this.chips = phpVars.config["chips"];
        }

        this.lateRegister = phpVars.lateRegister;
        this.interval = phpVars.interval;
        this.lateRegisterValue = phpVars.value;
        this.prizePool = phpVars.prizePool;
        this.prizePoolValue = phpVars.prizePoolValue / 100;
        this.playersLimit = phpVars.playersLimit;
        this.state = phpVars.state;
        this.timeFrame = phpVars.timeFrame;
        this.minBots = phpVars.minBots;
        this.maxBots = phpVars.maxBots;
        this.addBots = phpVars.addBots;
        this.playerBots = phpVars.playerBots;
        this.autoEnd = phpVars.autoEnd;
    },

    mounted() {
        notificationStore.loadAndShow();
    },

    computed: {
        tournamentId() {
            return location.pathname.toString().split("/")[2];
        },
    },

    methods: {
        showModalAvailableEventList() {
            this.isModalAvailableEventListVisible = true;
        },

        includeEvent(event) {
            if (this.selectedEvents.every(item => item.external_id !== event.external_id)) {
                this.selectedEvents.push(event);
            }
        },

        removeEvent(event) {
            this.selectedEvents = this.selectedEvents.filter(
                selected => selected.external_id !== event.external_id,
            );
        },

        openDeleteModal(id, description) {
            this.modalDeleteId = id;
            this.modalDeleteDescription = description;
        },

        closeDeleteModal() {
            this.modalDeleteId = null;
            this.modalDeleteDescription = null;
        },

        openCompleteModal(id, description) {
            this.modalCompleteId = id;
            this.modalCompleteDescription = description;
        },

        closeCompleteModal() {
            this.modalCompleteId = null;
            this.modalCompleteDescription = null;
        },

        async deleteTournament(tournamentId) {
            loaderStore.show();
            try {
                await axios.delete(`/tournaments/${tournamentId}`);
                this.closeDeleteModal();
                notificationStore.info("Tournament's been deleted.");
                window.location = "/tournaments";
            } catch (e) {
                notificationStore.errorSync(e.response.data.message);
            } finally {
                loaderStore.hide();
            }
        },

        async completeTournament(tournamentId) {
            loaderStore.show();
            try {
                await axios.post(`/tournaments/${tournamentId}/check-complete`);
                this.closeCompleteModal();
                notificationStore.info("Tournament will now be checked and if it meets the criteria, marked as completed.");
                window.location = "/tournaments";
            } catch (e) {
                notificationStore.errorSync(e.response.data.message);
            } finally {
                loaderStore.hide();
            }
        },

        async createTournament() {
            loaderStore.show();

            try {
                await axios.post("/tournaments", {
                    ApiData: this.selectedEvents,
                    name: this.name,
                    players_limit: this.playersLimit,
                    buy_in: this.buyIn * 100,
                    chips: this.chips,
                    commission: this.commission * 100,
                    late_register: this.playersLimit == "Unlimited" ? this.lateRegister : "",
                    late_register_rule: {
                        interval: this.playersLimit == "Unlimited" ? this.interval : "",
                        value: this.playersLimit == "Unlimited" ? this.lateRegisterValue : "",
                    },
                    prize_pool: {
                        type: this.prizePool || "",
                        fixed_value: this.prizePoolValue * 100,
                    },
                    state: this.state,
                    time_frame: this.timeFrame,
                    auto_end: this.autoEnd,
                    bots: {
                        min: this.minBots * 1,
                        max: this.maxBots * 1,
                        add: this.addBots * 1,
                        player: this.playerBots * 1,
                    }
                });

                notificationStore.info("New tournament's been created.");
                window.location = "/tournaments";
            } catch (e) {
                this.errors = e.response.data.errors;
                notificationStore.errorSync(e.response.data.message);
            } finally {
                loaderStore.hide();
            }
        },

        async updateTournament() {
            loaderStore.show();

            try {
                await axios.patch(`/tournaments/${this.tournamentId}`, {
                    ApiData: this.selectedEvents,
                    name: this.name,
                    players_limit: this.playersLimit,
                    buy_in: this.buyIn * 100,
                    chips: this.chips,
                    commission: this.commission * 100,
                    late_register: this.playersLimit == "Unlimited" ? this.lateRegister : "",
                    late_register_rule: {
                        interval: this.playersLimit == "Unlimited" ? this.interval : "",
                        value: this.playersLimit == "Unlimited" ? this.lateRegisterValue : "",
                    },
                    prize_pool: {
                        type: this.prizePool || "",
                        fixed_value: this.prizePoolValue * 100,
                    },
                    state: this.state,
                    time_frame: this.timeFrame,
                    auto_end: this.autoEnd,
                    bots: {
                        min: this.minBots * 1,
                        max: this.maxBots * 1,
                        add: this.addBots * 1,
                        player: this.playerBots * 1,
                    }
                });

                notificationStore.info("Tournament's been updated.");
                window.location = "/tournaments";
            } catch (e) {
                this.errors = e.response.data.errors;
                notificationStore.errorSync(e.response.data.message);
            } finally {
                loaderStore.hide();
            }
        },
    },
});
