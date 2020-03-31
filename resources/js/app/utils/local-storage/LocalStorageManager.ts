import { BetTypeTab, StorableWindow } from "../../types/window";

const TABS_KEY = "tabs";

export const saveWindows = (data: StorableWindow[]): void => {
    localStorage.setItem(TABS_KEY, JSON.stringify(data));
};

export const getWindows = (): StorableWindow[] => {
    const content = localStorage.getItem(TABS_KEY);
    if (!content) {
        return [];
    }

    return JSON.parse(content).map((item: any) => ({
        id: item.id,
        selectedSportIds: item.selectedSportIds ?? [],
        selectedBetTypeTab: item.selectedBetTypeTab ?? BetTypeTab.Pending,
        pendingOdds: (item.pendingOdds ?? []).filter(
            (pendingOdd: any) =>
                pendingOdd.tournamentEventId && pendingOdd.eventId && pendingOdd.type,
        ),
    }));
};
