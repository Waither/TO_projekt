export class Board {
    constructor(containerId) {
        this.container = document.getElementById(containerId);
        if (!this.container) {
            throw new Error(`Nie znaleziono kontenera o id: ${containerId}`);
        }
    }

    render(players, trackDefinition) {
        const boardLayout = trackDefinition.boardLayout;
        // Generowanie tabeli na podstawie boardLayout:
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
    
        // Rysowanie pionków
        players.forEach(player => {
            player.pieces.forEach(piece => {
                if (!piece.finished) {
                    // Tu zamiast boardLayout.mapPosition => trackDefinition.mapPosition
                    const coords = this.mapPositionToCoords(
                        piece.position,
                        player.id,
                        trackDefinition.mapPosition // <--- przekazujesz
                    );
                    if (coords && coords.row !== null) {
                        const cell = this.container.querySelector(
                            `td[data-row="${coords.row}"][data-col="${coords.col}"]`
                        );
                        if (cell) {
                            cell.innerHTML += `<div class="piece" style="background-color:${player.color}"></div>`;
                        }
                    }
                }
            });
        });
    }
    
    mapPositionToCoords(position, playerId, mapPosition) {
        if (position === -1) {
            return { row: null, col: null };
        }
        // Zamiast boardLayout.mapPosition => bezpośrednio mapPosition
        // Ewentualnie, jeśli używasz klucza "position + '_' + playerId":
        const coords = mapPosition[position + '_' + playerId];
        // lub, jeśli wystarczy klucz "position":
        // const coords = mapPosition[position];
        return coords || { row: null, col: null };
    }
    
}
