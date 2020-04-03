import { IEvent } from "./IEvent";

export class NewChatMessage implements IEvent {
    public readonly eventName = "chat.message";

    public constructor(public readonly tournamentId: number, public readonly message: string) {
        //
    }
}
