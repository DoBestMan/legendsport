export interface ChatMessage {
    id: string;
    message: string;
    tournamentId: number;
    userId: number;
    userName: string;
    timestamp: number;
    isParticipant?: boolean;
}
