export function createPlusTrackDefinition() {
    const size = 11;
    // Tworzymy tablicę 15×15 wypełnioną "blocked" (niewykorzystane pola)
    const boardLayout = Array.from({ length: size }, () =>
        Array.from({ length: size }, () => 'blocked')
    );

    const mapPosition = {};

    let trackIndex = 0;
    function markTrack(r, c) {
        boardLayout[r][c] = 'track';
        mapPosition[trackIndex] = { row: r, col: c };
        trackIndex++;
    }

    // Tworzenie mapy toru w kształcie krzyża
    for (let i = 0; i < size; i++) {
        if (i !== 5) {
            markTrack(4, i); // Horizontal arms
            markTrack(6, i); // Horizontal arms
            markTrack(i, 4); // Vertical arms
            markTrack(i, 6); // Vertical arms
        }
    }

    // Mark the center and endpoints of the cross
    [[0, 5], [5, 0], [5, 10], [10, 5]].forEach(([r, c]) => markTrack(r, c));

    /**
     * startIndex, homeEntry, homeIndex – WARTOŚCI PRZYKŁADOWE!
     * - Nie będą miały logicznego sensu w klasycznym Ludo (52 pola, 4 domki).
     * - Jeśli chcesz je wykorzystać w MovementStrategy, dostosuj do własnej koncepcji.
     */
    const startIndex = [0, 14, 15, 28];

    // Poniżej przykładowe wartości homeEntry / homeIndex – możesz je zmienić lub pominąć
    const homeEntry = [7, 7, 7, 7]; // na razie fikcyjne, np. środek
    const homeIndex = [100, 110, 120, 130];

    /**
     * Opcjonalnie: możesz dodać kilka pól "home" w centrum (np. obok (7,7))
     * i zmapować je do pozycji 100..105. 
     * Poniżej przykładowo 2 pola "home" dla gracza 0:
     */
    boardLayout[5][4] = 'home';
    mapPosition[100] = { row: 7, col: 6 };
    boardLayout[5][3] = 'home';
    mapPosition[101] = { row: 7, col: 5 };

    //    Gracz 1: (7,8) i (7,9)
    boardLayout[5][6] = 'home';
    mapPosition[110] = { row: 7, col: 8 };
    boardLayout[5][7] = 'home';
    mapPosition[111] = { row: 7, col: 9 };

    //    Gracz 2: (6,7) i (5,7)
    boardLayout[4][5] = 'home';
    mapPosition[120] = { row: 6, col: 7 };
    boardLayout[3][5] = 'home';
    mapPosition[121] = { row: 5, col: 7 };

    //    Gracz 3: (8,7) i (9,7)
    boardLayout[6][5] = 'home';
    mapPosition[130] = { row: 8, col: 7 };
    boardLayout[7][5] = 'home';
    mapPosition[131] = { row: 9, col: 7 };

    // Zwracamy obiekt zdefiniowany zgodnie z MovementStrategy
    return {
        boardLayout,  // 2D array: 'blocked', 'track', 'home'...
        startIndex,   // TYLKO przykładowe
        homeEntry,    // TYLKO przykładowe
        homeIndex,    // TYLKO przykładowe
        mapPosition   // trackIndex -> {row, col}
    };
}
