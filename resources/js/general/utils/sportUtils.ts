export interface Sport {
    id: number;
    name: string;
}

export const sports: ReadonlyArray<Sport> = [
    { id: 0, name: "MLB" },
    { id: 1, name: "NBA" },
    { id: 2, name: "NCAAB" },
    { id: 3, name: "NCAAF" },
    { id: 4, name: "NFL" },
    { id: 5, name: "NHL" },
    { id: 7, name: "SOCCER" },
    { id: 11, name: "MMA (UFC)" },
    { id: 14, name: "KHL" },
    { id: 15, name: "AHL" },
    { id: 16, name: "SHL" },
    { id: 17, name: "SHL" },
    { id: 29, name: "XFL" },
];

export const sportsMap: ReadonlyMap<number, Sport> = new Map(
    sports.map(sport => [sport.id, sport]),
);

export const getSportName = (sportId: number): string =>
    sportsMap.get(sportId)?.name ?? String(sportId);
