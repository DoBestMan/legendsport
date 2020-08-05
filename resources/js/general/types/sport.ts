export interface Sport {
    id: string;
    name: string;
    provider: string;
}

export class SportOption {
    id: string;
    name: string;

    constructor(id: string, name: string) {
        this.id = id;
        this.name = name;
    }
}
