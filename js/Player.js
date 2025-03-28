import { PieceFactory } from './PieceFactory.js';

export class Player {
    constructor(id, color) {
        this.id = id;
        this.color = color;
        this.pieces = [];
        
        // Domyślnie 4 pionki
        for (let i = 0; i < 4; i++) {
            this.pieces.push(PieceFactory.createPiece(i, this));
        }
    }
}
