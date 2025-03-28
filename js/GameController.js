import { GameState } from './GameState.js';
import { Player } from './Player.js';
import { Board } from './Board.js';
import { Dice } from './Dice.js';
import { createPlusTrackDefinition } from './LudoTrack.js';

export class GameController {
    constructor(containerId) {
        this.state = GameState.getInstance();
        this.board = new Board(containerId);
        this.dice = new Dice();

        // Tworzymy definicję toru
        this.trackDefinition = createPlusTrackDefinition();

        // Tworzymy graczy (4)
        const player0 = new Player(1, 'red');
        const player1 = new Player(2, 'blue');
        const player2 = new Player(3, 'yellow');
        const player3 = new Player(4, 'green');

        this.state.players = [player0, player1, player2, player3];
        this.state.currentPlayerIndex = 0;

        this.state.players.forEach((player, i) => {
            player.pieces.forEach(piece => {
                piece.position = this.trackDefinition.startIndex[i]; // Na przykład, jeśli startIndex[i] to pozycja startowa gracza i
            });
        });

        this.setupUI();
        this.render();
    }

    setupUI() {
        const diceButton = document.getElementById('dice-button');
        if (diceButton) {
            diceButton.addEventListener('click', () => this.rollDice());
        }
    }

    render() {
        // Renderujemy planszę i pionki
        this.board.render(this.state.players, this.trackDefinition.boardLayout);
        this.updateStatus();
    }

    updateStatus() {
        const statusDiv = document.getElementById('status');
        if (statusDiv) {
            const currentPlayer = this.state.players[this.state.currentPlayerIndex];
            statusDiv.textContent = `Tura gracza ${currentPlayer.id} (${currentPlayer.color})`;
        }
    }

    rollDice() {
        const value = this.dice.roll();
        const diceResult = document.getElementById('dice-result');
        if (diceResult) {
            diceResult.textContent = `Kostka: ${value}`;
        }
        this.handleMove(value);
    }

    handleMove(diceValue) {
        const currentPlayer = this.state.players[this.state.currentPlayerIndex];

        // Na potrzeby przykładu – ruszamy ZAWSZE pierwszy pionek, który nie jest finished
        // W realnej grze dajemy wybór, jeśli jest kilka możliwych pionków.
        const piece = currentPlayer.pieces.find(p => !p.finished);
        if (!piece) {
            // Wszystkie pionki gracza ukończyły bieg?
            this.checkWin(currentPlayer);
            this.endTurn();
            return;
        }

        piece.move(diceValue, this.trackDefinition);
        if (piece.finished) {
            // Czy wszystkie pionki gracza skończyły?
            if (currentPlayer.allFinished()) {
                this.checkWin(currentPlayer);
                return;
            }
        }

        this.render();
        this.endTurn();
    }

    endTurn() {
        this.state.currentPlayerIndex = (this.state.currentPlayerIndex + 1) % this.state.players.length;
        this.render();
    }    

    checkWin(player) {
        if (player.allFinished()) {
            alert(`Gracz ${player.id} (${player.color}) WYGRAŁ!`);
        }
    }
}
