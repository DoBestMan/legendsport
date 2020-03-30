import { PendingOdd, PendingOddType } from "../../types/window";
import { Odd } from "../../../general/types/odd";
import { Game } from "../../types/game";
import { DeepReadonly } from "../../../general/types/types";

export const americanToDecimalOdd = (odd: number): number => (odd < 0 ? 100 / -odd : odd / 100);

export const calculateWinFromAmericanOdd = (americanOdd: number, bet: number): number =>
    americanToDecimalOdd(americanOdd) * bet;

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
            return game.home_team;

        case PendingOddType.MoneyLineAway:
        case PendingOddType.SpreadAway:
            return game.away_team;

        default:
            return "n/a";
    }
};

export const formatOdd = (value: string): string => `${Number(value) > 0 ? "+" : ""}${value}`;

export const formatCurrency = (value: number): string => {
    const formatter = new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "USD",
    });
    return formatter.format(value / 100);
};
