import { Piece } from './Piece.js';
import { LudoMovementStrategy } from './MovementStrategy.js';

export class PieceFactory {
    static createPiece(id, player) {
        const strategy = new LudoMovementStrategy();
        return new Piece(id, player, strategy);
    }
}
