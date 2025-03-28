export class LudoMovementStrategy {
    /**
     * @param piece - obiekt Piece
     * @param diceValue - wyrzucona liczba (1..6)
     * @param trackDefinition - definicja toru (główne pola + home row)
     */
    calculateNextPosition(piece, diceValue, trackDefinition) {
        // Jeśli pionek jest w bazie (-1) i nie wypadła 6 -> brak ruchu
        if (piece.position === -1) {
            if (diceValue === 6) {
                return trackDefinition.startIndex[piece.player.id]; 
            } else {
                return -1; // zostaje w bazie
            }
        }

        // Jeśli pionek jest na głównym torze (0..51)
        if (piece.position >= 0 && piece.position < 52) {
            const nextPos = piece.position + diceValue;
            // Czy przekraczamy 51?
            const wrappedPos = nextPos % 52;
            
            // Sprawdź, czy pionek powinien wejść do "home row"
            // W Ludo typowo: jeśli piece.position >= 'startIndex + 51' to wchodzimy do home row
            // Ale to zależy od koloru i definicji. 
            // Upraszczamy: Gdy "okrążymy" tablicę i dojdziemy do swojego "wejścia do domu", wchodzimy do strefy home.
            // 
            // trackDefinition.homeEntry[piece.player.id] - pole, na którym zaczyna się domek
            // Jeżeli wrappedPos == trackDefinition.homeEntry[piece.player.id], to wchodzimy do home row.
            if (wrappedPos === trackDefinition.homeEntry[piece.player.id]) {
                // Pierwsze pole w domku
                return trackDefinition.homeIndex[piece.player.id];
            } else {
                return wrappedPos;
            }
        }

        // Jeśli pionek jest w home row (>= 100 i < 106 np.)
        if (piece.position >= 100 && piece.position < 106) {
            // W home row: posuwamy się do przodu w linii prostej
            const newPos = piece.position + diceValue;
            // Nie można wyjść poza 105 (ostatnie pole w domku) – jeśli wychodzi, ruch nieważny
            if (newPos > 105) {
                return piece.position; // brak ruchu
            }
            return newPos;
        }

        return piece.position;
    }
}
