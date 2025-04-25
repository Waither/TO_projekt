# TO_projekt - System Monitorowania Urządzeń

## Opis projektu
Projekt **TO_projekt** to system monitorowania urządzeń sieciowych, takich jak serwery, routery i przełączniki. Aplikacja umożliwia dodawanie urządzeń, monitorowanie ich statusu, analizowanie wydajności oraz zarządzanie alertami. System został zaprojektowany zgodnie z zasadami programowania obiektowego i wykorzystuje wiele wzorców projektowych, aby zapewnić modularność, elastyczność i łatwość rozbudowy.

Aplikacja składa się z trzech głównych warstw zgodnie z architekturą **MVC (Model-View-Controller)**:
- **Model**: Reprezentuje dane i logikę biznesową, np. urządzenia (`Device`, `Server`, `Router`, `SwitchDevice`) oraz monitorowanie (`Monitor`).
- **View**: Odpowiada za prezentację danych użytkownikowi, np. wyświetlanie statusów urządzeń i alertów (`View`).
- **Controller**: Zarządza przepływem danych między modelem a widokiem oraz obsługuje logikę aplikacji (`Controller`).

---

## Funkcjonalności
- Dodawanie urządzeń sieciowych (serwery, routery, przełączniki).
- Monitorowanie statusu urządzeń w czasie rzeczywistym (w aktualnym stanie są to wartości losowane lub dostarczane ręcznie, a nie z realnych urządzeń).
- Analiza wydajności urządzeń z wykorzystaniem różnych strategii (prosta i zaawansowana analiza).
- Zarządzanie alertami i powiadamianie o problemach.
- Logowanie statusów urządzeń do pliku.
- Dynamiczne przełączanie strategii analizy statusu.

---

## Uruchomienie projektu z wykorzystaniem Dockera

### Wymagania wstępne
- Zainstalowany **Docker** na Twoim systemie.

### Instrukcja
1. Sklonuj repozytorium projektu:
    ```bash
    git clone https://github.com/Waither/TO_projekt.git
    cd TO_projekt
    ```

2. Uruchom kontenery Dockera za pomocą `docker-compose`:
    ```bash
    docker compose up --build
    ```

3. Po zakończeniu budowania i uruchomienia kontenerów aplikacja będzie dostępna pod adresem:  
   [`http://localhost:8080`](http://localhost:8080)

---

## Wykorzystane wzorce projektowe

### 1. Singleton
- **Opis**: Zapewnia, że istnieje tylko jedna instancja klasy w całej aplikacji.
- **Miejsce implementacji**:  
  - Klasa `Monitor` (`src/Monitor.php`).

### 2. Strategia (Strategy)
- **Opis**: Umożliwia dynamiczną zmianę sposobu analizy statusu urządzeń.
- **Miejsce implementacji**:  
  - Interfejs `StatusAnalysisStrategy` (`src/StatusAnalysisStrategy.php`).  
  - Klasy `SimpleStatusAnalysis` (`src/SimpleStatusAnalysis.php`) i `AdvancedStatusAnalysis` (`src/AdvancedStatusAnalysis.php`).

### 3. Fabryka (Factory Method)
- **Opis**: Umożliwia tworzenie obiektów różnych typów urządzeń w zunifikowany sposób.
- **Miejsce implementacji**:  
  - Klasa `DeviceFactory` (`src/DeviceFactory.php`).

### 4. Obserwator (Observer)
- **Opis**: Pozwala na powiadamianie obserwatorów o zmianach w stanie systemu.
- **Miejsce implementacji**:  
  - Klasa `Subject` (`src/Subject.php`).  
  - Interfejs `ObserverInterface` (`src/ObserverInterface.php`).  
  - Klasy `AlertNotifier` (`src/AlertNotifier.php`) i `AlertLogger` (`src/AlertLogger.php`).

### 5. MVC (Model-View-Controller)
- **Opis**: Organizuje aplikację w trzy warstwy: Model, Widok i Kontroler.
- **Miejsce implementacji**:  
  - **Model**: Klasy `Device`, `Server`, `Router`, `SwitchDevice`, `Monitor` (`src/Device.php`, `src/Server.php`, `src/Router.php`, `src/Switch.php`, `src/Monitor.php`).  
  - **View**: Klasa `View` (`src/View.php`) – wykorzystuje metodę `analyzeSpecifics()` z klasy `Device` do dynamicznego renderowania szczegółów urządzeń.  
  - **Controller**: Klasa `Controller` (`src/Controller.php`).

### 6. Szablonowa Metoda (Template Method)
- **Opis**: Definiuje szkielet algorytmu w klasie bazowej, a szczegóły implementacji pozostawia klasom pochodnym.
- **Miejsce implementacji**:  
  - Klasa `Device` (`src/Device.php`) definiuje metodę `analyzeSpecifics()`, która jest implementowana w klasach `Server`, `Router` i `SwitchDevice`.

---

## Autor
- Gąsior Maciej