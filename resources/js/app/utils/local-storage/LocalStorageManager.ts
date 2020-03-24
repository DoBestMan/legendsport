const TABS_KEY = "tabs";

export interface StorableWindow {
    id: number;
    selectedSportIds?: number[];
    selectedBetting?: BetTypeTab;
}

export enum BetTypeTab {
    Pending = "Pending",
    History = "History",
    Straight = "Straight",
    Parlay = "Parlay",
}

export const saveWindows = (data: StorableWindow[]): void => {
    localStorage.setItem(TABS_KEY, JSON.stringify(data));
};

export const getWindows = (): StorableWindow[] => {
    const content = localStorage.getItem(TABS_KEY);
    return content ? JSON.parse(content) : [];
};
