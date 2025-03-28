export class GameState {
    constructor() {
        if (GameState._instance) {
            return GameState._instance;
        }
        this.players = [];
        this.currentPlayerIndex = 0;
        GameState._instance = this;
    }

    static getInstance() {
        if (!GameState._instance) {
            new GameState();
        }
        return GameState._instance;
    }
}
