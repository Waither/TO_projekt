export class Piece {
    constructor(id, player, movementStrategy) {
        this.id = id;
        this.player = player;
        this.movementStrategy = movementStrategy;
        this.position = -1; // startowo w bazie
        this.finished = false;
    }

    move(diceValue, trackDefinition) {
        if (this.finished) return;
        const newPos = this.movementStrategy.calculateNextPosition(this, diceValue, trackDefinition);
        this.position = newPos;
        // Jeśli newPos == 105, to znaczy pionek dotarł do końca domku
        if (newPos === 105) {
            this.finished = true;
        }
    }
}
