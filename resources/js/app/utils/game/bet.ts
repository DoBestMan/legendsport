import moment from "moment";
import { PendingOdd, PendingOddType } from "../../types/window";
import { Game } from "../../types/game";
import { DeepReadonly } from "../../../general/types/types";
import { Odd } from "../../types/odd";

export const americanToDecimalOdd = (odd: number): number => (odd < 0 ? 100 / -odd : odd / 100);

export const calculateWinFromAmericanOdd = (americanOdd: number, wager: number): number =>
    americanToDecimalOdd(americanOdd) * wager;

export const getPendingOddValue = (
    pendingOdd: DeepReadonly<PendingOdd>,
    odd: DeepReadonly<Odd>,
): number => {
    switch (pendingOdd.type) {
        case PendingOddType.MoneyLineHome:
            return Number(odd.money_line_home);
        case PendingOddType.MoneyLineAway:
            return Number(odd.money_line_away);
        case PendingOddType.SpreadHome:
            return Number(odd.point_spread_home);
        case PendingOddType.SpreadAway:
            return Number(odd.point_spread_away);
        case PendingOddType.TotalUnder:
            return Number(odd.underline);
        case PendingOddType.TotalOver:
            return Number(odd.overline);
        default:
            return 0;
    }
};

export const getPendingOddTeam = (
    pendingOdd: DeepReadonly<PendingOdd>,
    game: DeepReadonly<Game>,
): string => {
    switch (pendingOdd.type) {
        case PendingOddType.MoneyLineHome:
        case PendingOddType.SpreadHome:
            return game.teamHome;

        case PendingOddType.MoneyLineAway:
        case PendingOddType.SpreadAway:
            return game.teamAway;

        default:
            return "n/a";
    }
};

export const signedNumber = (value: string | number): string =>
    `${Number(value) > 0 ? "+" : ""}${value}`;

export const diffHumanReadable = (value: string): string => {
    const date = moment(value);

    if (!date.isValid()) {
        return "n/a";
    }

    if (date.isBefore()) {
        return "Started";
    }

    return date.fromNow();
};
