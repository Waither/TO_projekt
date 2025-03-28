export class Board {
    constructor(containerId) {
        this.container = document.getElementById(containerId);
        if (!this.container) {
            throw new Error(`Nie znaleziono kontenera o id: ${containerId}`);
        }
    }

    render(players, boardLayout) {
        // boardLayout to np. tablica 15×15 z informacją: 'track', 'homeR', 'startR', 'blocked', itp.
        const rows = boardLayout.length;
        const cols = boardLayout[0].length;
        let html = `<table class="ludo-board">`;

        for (let r = 0; r < rows; r++) {
            html += `<tr>`;
            for (let c = 0; c < cols; c++) {
                const cellType = boardLayout[r][c];
                html += `<td class="${cellType}" data-row="${r}" data-col="${c}"></td>`;
            }
            html += `</tr>`;
        }
        html += `</table>`;

        this.container.innerHTML = html;

        // Teraz rysujemy pionki
        players.forEach(player => {
            player.pieces.forEach(piece => {
                if (!piece.finished) {
                    const { row, col } = this.mapPositionToCoords(piece.position, player.id, boardLayout);
                    if (row !== null && col !== null) {
                        const cell = this.container.querySelector(`td[data-row="${row}"][data-col="${col}"]`);
                        if (cell) {
                            cell.innerHTML += `<div class="piece" style="background-color:${player.color}"></div>`;
                        }
                    }
                }
            });
        });
        
    }

    /**
     * Zamiana piece.position (np. 0..51, -1, 100..105) na (row, col) w boardLayout
     */
    mapPositionToCoords(position, playerId, boardLayout) {
        if (position === -1) {
            // Baza – w prawdziwej implementacji mamy 4 pola bazy dla każdego gracza
            // Tu dla uproszczenia zwróć null, col – pionek się nie wyświetli
            return { row: null, col: null };
        }

        // Przeszukaj boardLayout i znajdź komórkę, która ma przypisane pole (np. trackIndex=pos)
        // lub homeIndex=pos. Można trzymać w boardLayout obiekt z polami, a nie tylko string.
        // Tu – uproszczenie. Np. w LudoTrack.js masz mapPosition: {0:{row, col}, 1:{row, col}...}
        // W takim wypadku wystarczy:
        const coords = boardLayout.mapPosition[position + '_' + playerId];
        if (coords) {
            return coords;
        }
        return { row: null, col: null };
    }
}
