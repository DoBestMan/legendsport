import { Result } from "../../types/result";

export const getScoreHome = (
    event: { externalId: string; scoreHome: number },
    results: ReadonlyMap<string, Result>,
) => results.get(event.externalId)?.home ?? event.scoreHome ?? 0;
export const getScoreAway = (
    event: { externalId: string; scoreAway: number },
    results: ReadonlyMap<string, Result>,
) => results.get(event.externalId)?.away ?? event.scoreAway ?? 0;
