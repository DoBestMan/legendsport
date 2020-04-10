import Vue from "vue";
import axios from "axios";
import { Sport } from "../../general/types/sport";

export default new Vue({
    data() {
        return {
            isLoading: false,
            isLoaded: false,
            isFailed: false,
            sports: [] as Sport[],
        };
    },

    computed: {
        sportDictionary(): ReadonlyMap<string, string> {
            return new Map(this.sports.map(sport => [sport.id, sport.name]));
        },
    },

    methods: {
        markAsLoading() {
            this.isLoading = true;
        },

        markAsLoaded(sports: Sport[]) {
            this.isLoading = false;
            this.isFailed = false;
            this.isLoaded = true;
            this.sports = sports;
        },

        markAsFailed() {
            this.isLoading = false;
            this.isFailed = true;
        },

        async load() {
            if (!this.sports.length) {
                await this.reload();
            }
        },

        async reload() {
            this.markAsLoading();

            try {
                const response = await axios.get("/api/sports");
                this.markAsLoaded(response.data);
            } catch (e) {
                this.markAsFailed();
            }
        },
    },
});
