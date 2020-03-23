const TABS_KEY = "tabs";

export interface StorableTab {
    id: number;
    selectedSportIds?: number[];
    selectedBetting?: BettingType;
}

export enum BettingType {
    Pending = "Pending",
    History = "History",
    Straight = "Straight",
    Parlay = "Parlay",
}

export const saveTabs = (data: StorableTab[]): void => {
    localStorage.setItem(TABS_KEY, JSON.stringify(data));
};

export const getTabs = (): StorableTab[] => {
    const content = localStorage.getItem(TABS_KEY);
    return content ? JSON.parse(content) : [];
};
