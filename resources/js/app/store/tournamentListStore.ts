import Vue from "vue";
import axios from "axios";
import { empty, intersects } from "../../general/utils/utils";
import {
    BuyInType,
    PlayersLimitType,
    TimeFrame,
    Tournament,
    TournamentType,
} from "../types/tournament";
import { Nullable } from "../../general/types/types";
import { TournamentState } from "../../general/types/tournament";

const matchString = (subject: string, needle?: string): boolean => {
    if (!needle) {
        return true;
    }

    if (!subject) {
        return false;
    }

    return subject.toLowerCase().includes(needle);
};

const matchBuyIn = (expected: BuyInType | null, value: number): boolean => {
    switch (expected) {
        case BuyInType.High:
            return 250 < value;
        case BuyInType.Medium:
            return 50 <= value && value <= 250;
        case BuyInType.Low:
            return 1 <= value && value < 50;
        case BuyInType.Freeroll:
            return value === 0;
        default:
            return true;
    }
};

const matchType = (expected: TournamentType | null, sports: number[]): boolean => {
    switch (expected) {
        case TournamentType.Single:
            return sports.length === 1;
        case TournamentType.Multiple:
            return sports.length > 1;
        default:
            return true;
    }
};

const matchPlayersLimit = (expected: PlayersLimitType | null, value: PlayersLimitType): boolean =>
    !expected || expected === value;

const matchTimeFrame = (expected: TimeFrame | null, value: TimeFrame | null): boolean =>
    !expected || expected === value;

const isUpcoming = (state: TournamentState): boolean =>
    [
        TournamentState.Announced,
        TournamentState.LateRegistering,
        TournamentState.Registering,
    ].includes(state);

const filterTournament = (
    tournament: Tournament,
    search: string,
    sports: number[],
    buyIn: BuyInType | null,
    type: TournamentType | null,
    playersLimit: PlayersLimitType | null,
    upcoming: boolean,
    timeFrame: TimeFrame | null,
): boolean => {
    if (search) {
        return matchString(tournament.name, search);
    }

    return (
        matchBuyIn(buyIn, tournament.buy_in) &&
        matchType(type, tournament.sport_ids) &&
        matchPlayersLimit(playersLimit, tournament.players_limit) &&
        (empty(sports) || intersects(sports, tournament.sport_ids)) &&
        (!upcoming || isUpcoming(tournament.state)) &&
        matchTimeFrame(timeFrame, tournament.time_frame)
    );
};

export default new Vue({
    data() {
        return {
            buyIn: null as Nullable<BuyInType>,
            playersLimit: null as Nullable<PlayersLimitType>,
            timeFrame: null as Nullable<TimeFrame>,
            search: "",
            sports: [] as number[],
            type: null as Nullable<TournamentType>,
            upcoming: false,

            tournaments: [] as Tournament[],
            isLoading: false,
            hasFailed: false,
        };
    },

    computed: {
        filteredTournaments(): Tournament[] {
            const search = this.search.toLowerCase();
            return this.tournaments.filter(tournament =>
                filterTournament(
                    tournament,
                    search,
                    this.sports,
                    this.buyIn,
                    this.type,
                    this.playersLimit,
                    this.upcoming,
                    this.timeFrame,
                ),
            );
        },
    },

    methods: {
        async load(): Promise<void> {
            if (!this.tournaments.length) {
                await this.reload();
            }
        },

        async reload(): Promise<void> {
            this.isLoading = true;

            try {
                const response = await axios.get("/api/tournaments");
                this.tournaments = response.data;
                this.hasFailed = false;
            } catch (e) {
                this.hasFailed = true;
            } finally {
                this.isLoading = false;
            }
        },
    },
});
