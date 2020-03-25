import { PendingOdd, PendingOddType } from "../../types/window";
import { Odd } from "../../../general/types/odd";

export const calculateWinFromAmericanOdd = (americanOdd: number, bet: number): number => {
    if (americanOdd < 0) {
        return (100 / -americanOdd) * bet;
    }

    return (americanOdd / 100) * bet;
};

export const getPendingOddValue = (pendingOdd: PendingOdd, odd: Odd): number => {
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

export const formatOdd = (value: string): string => `${Number(value) > 0 ? "+" : ""}${value}`;
