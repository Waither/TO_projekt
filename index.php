<?php
session_start();

/* === DEFINICJE KLAS I INTERFEJSÓW === */

/** Interfejs figury szachowej */
interface ChessPiece {
    public function getName();
    public function getPosition();
    public function move($direction);
    public function setPosition($position);
}

/** Interfejs strategii ruchu */
interface MovementStrategy {
    public function calculateNewPosition($position, $direction);
}

/** Strategia ruchu króla – obsługuje ruchy w 8 kierunkach */
class KingMovementStrategy implements MovementStrategy {
    public function calculateNewPosition($position, $direction) {
        $moves = [
            'N'  => [-1,  0],
            'NE' => [-1,  1],
            'E'  => [ 0,  1],
            'SE' => [ 1,  1],
            'S'  => [ 1,  0],
            'SW' => [ 1, -1],
            'W'  => [ 0, -1],
            'NW' => [-1, -1],
        ];
        if (!isset($moves[$direction])) {
            return $position; // Nieprawidłowy kierunek – brak zmiany
        }
        $delta = $moves[$direction];
        $newRow = $position['row'] + $delta[0];
        $newCol = $position['col'] + $delta[1];
        // Sprawdzamy, czy nowa pozycja mieści się w granicach planszy
        if ($newRow < 0 || $newRow > 7 || $newCol < 0 || $newCol > 7) {
            return $position;
        }
        return ['row' => $newRow, 'col' => $newCol];
    }
}

/** Klasa reprezentująca króla */
class King implements ChessPiece {
    private $position;
    private $movementStrategy;
    
    public function __construct($position) {
        $this->position = $position;
        $this->movementStrategy = new KingMovementStrategy();
    }
    
    public function getName() {
        return "King";
    }
    
    public function getPosition() {
        return $this->position;
    }
    
    public function setPosition($position) {
        $this->position = $position;
    }
    
    public function move($direction) {
        $newPosition = $this->movementStrategy->calculateNewPosition($this->position, $direction);
        $this->position = $newPosition;
    }
}

/** Factory do tworzenia figur szachowych */
class ChessPieceFactory {
    public static function createPiece($type, $position) {
        switch (strtolower($type)) {
            case 'king':
                return new King($position);
            default:
                throw new Exception("Nieznany typ figury: $type");
        }
    }
}

/** Klasa odpowiedzialna za generowanie planszy */
class Board {
    // Generuje planszę (HTML) z atrybutami data-row i data-col
    public static function getBoardHtml(ChessPiece $piece) {
        $piecePosition = $piece->getPosition();
        $html = '<table id="chessboard">';
        for ($row = 0; $row < 8; $row++) {
            $html .= '<tr>';
            for ($col = 0; $col < 8; $col++) {
                $isWhite = (($row + $col) % 2 == 0);
                $class = $isWhite ? 'white' : 'black';
                $html .= "<td class=\"$class\" data-row=\"$row\" data-col=\"$col\">";
                if ($row == $piecePosition['row'] && $col == $piecePosition['col']) {
                    $html .= '♔';
                }
                $html .= "</td>";
            }
            $html .= '</tr>';
        }
        $html .= '</table>';
        return $html;
    }
}

/* === PRZETWARZANIE ŻĄDAŃ AJAX === */

// Ustawiamy pozycję króla w sesji – domyślnie E1 (wiersz 7, kolumna 4)
if (!isset($_SESSION['king_position'])) {
    $_SESSION['king_position'] = ['row' => 7, 'col' => 4];
}

// Obsługa ruchu – przychodzą współrzędne klikniętego pola
if (isset($_POST['targetRow']) && isset($_POST['targetCol'])) {
    $targetRow = intval($_POST['targetRow']);
    $targetCol = intval($_POST['targetCol']);
    
    $kingPosition = $_SESSION['king_position'];
    $rowDiff = $targetRow - $kingPosition['row'];
    $colDiff = $targetCol - $kingPosition['col'];
    
    // Ruch króla: jeden krok w dowolnym kierunku (różnice nie większe niż 1, ale nie zerowe)
    if (abs($rowDiff) <= 1 && abs($colDiff) <= 1 && !($rowDiff === 0 && $colDiff === 0)) {
        $direction = '';
        if ($rowDiff === -1) {
            if ($colDiff === -1) $direction = 'NW';
            elseif ($colDiff === 0) $direction = 'N';
            elseif ($colDiff === 1) $direction = 'NE';
        } elseif ($rowDiff === 0) {
            if ($colDiff === -1) $direction = 'W';
            elseif ($colDiff === 1) $direction = 'E';
        } elseif ($rowDiff === 1) {
            if ($colDiff === -1) $direction = 'SW';
            elseif ($colDiff === 0) $direction = 'S';
            elseif ($colDiff === 1) $direction = 'SE';
        }
        $king = ChessPieceFactory::createPiece('king', $kingPosition);
        $king->move($direction);
        $_SESSION['king_position'] = $king->getPosition();
    }
    
    $king = ChessPieceFactory::createPiece('king', $_SESSION['king_position']);
    $boardHtml = Board::getBoardHtml($king);
    header('Content-Type: application/json');
    echo json_encode(['board' => $boardHtml, 'kingPosition' => $_SESSION['king_position']]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Szachy - Wybór ruchu króla</title>
    <style>
        table { border-collapse: collapse; }
        td {
            width: 60px;
            height: 60px;
            text-align: center;
            vertical-align: middle;
            border: 1px solid #333;
            font-size: 36px;
            cursor: pointer;
            position: relative;
        }
        .white { background-color: #f0d9b5; }
        .black { background-color: #b58863; }
        .highlight {
            background-color: rgba(0,0,0,0.5) !important;
        }
        .selected {
            outline: 2px solid red;
        }
    </style>
</head>
<body>
    <h1>Wybierz ruch króla</h1>
    <div id="board">
        <?php
            $king = ChessPieceFactory::createPiece('king', $_SESSION['king_position']);
            echo Board::getBoardHtml($king);
        ?>
    </div>
    <script>
        // Flaga informująca, czy król został wybrany
        let kingSelected = false;
        let kingPosition = {row: null, col: null};

        // Oblicza dozwolone ruchy dla króla (wszystkie 8 sąsiadujących pól)
        function getAllowedMoves(row, col) {
            const offsets = [
                [-1, -1], [-1, 0], [-1, 1],
                [0, -1],           [0, 1],
                [1, -1],  [1, 0],  [1, 1]
            ];
            let allowed = [];
            offsets.forEach(offset => {
                const newRow = row + offset[0];
                const newCol = col + offset[1];
                if (newRow >= 0 && newRow < 8 && newCol >= 0 && newCol < 8) {
                    allowed.push({row: newRow, col: newCol});
                }
            });
            return allowed;
        }

        // Usuwa podświetlenia i atrybuty z komórek
        function clearHighlights() {
            document.querySelectorAll('td').forEach(cell => {
                cell.classList.remove('highlight');
                cell.classList.remove('selected');
                cell.removeAttribute('data-allowed');
            });
        }

        // Podświetla dozwolone pola na podstawie aktualnej pozycji króla
        function highlightAllowedMoves(row, col) {
            const allowed = getAllowedMoves(row, col);
            allowed.forEach(move => {
                const selector = 'td[data-row="' + move.row + '"][data-col="' + move.col + '"]';
                const cell = document.querySelector(selector);
                if (cell) {
                    cell.classList.add('highlight');
                    cell.setAttribute('data-allowed', 'true');
                }
            });
        }

        // Dołącza zdarzenia kliknięcia do komórek planszy
        function attachClickEvents() {
            document.querySelectorAll('td').forEach(cell => {
                cell.addEventListener('click', function() {
                    const row = parseInt(this.getAttribute('data-row'));
                    const col = parseInt(this.getAttribute('data-col'));
                    // Jeśli król nie jest wybrany i kliknięto na komórkę z królem
                    if (!kingSelected) {
                        if (this.textContent.trim() === '♔') {
                            kingSelected = true;
                            kingPosition = {row: row, col: col};
                            this.classList.add('selected');
                            highlightAllowedMoves(row, col);
                        }
                    } else {
                        // Jeśli król jest wybrany i kliknięto na dozwolone pole
                        if (this.getAttribute('data-allowed') === 'true') {
                            const targetRow = row;
                            const targetCol = col;
                            fetch('', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: 'targetRow=' + encodeURIComponent(targetRow) + '&targetCol=' + encodeURIComponent(targetCol)
                            })
                            .then(response => response.json())
                            .then(data => {
                                document.getElementById('board').innerHTML = data.board;
                                kingSelected = false;
                                attachClickEvents();
                            });
                        } else {
                            // Kliknięto inne pole – anuluj wybór
                            clearHighlights();
                            kingSelected = false;
                        }
                    }
                });
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            attachClickEvents();
        });
    </script>
</body>
</html>
