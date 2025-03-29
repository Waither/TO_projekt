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

## Wykorzystane wzorce projektowe

### 1. Singleton
- **Opis**: Zapewnia, że istnieje tylko jedna instancja klasy w całej aplikacji.
- **Miejsce implementacji**:  
  - Klasa `Monitor` (`src/Monitor.php`).

---

### 2. Strategia (Strategy)
- **Opis**: Umożliwia dynamiczną zmianę sposobu analizy statusu urządzeń.
- **Miejsce implementacji**:  
  - Interfejs `StatusAnalysisStrategy` (`src/StatusAnalysisStrategy.php`).  
  - Klasy `SimpleStatusAnalysis` (`src/SimpleStatusAnalysis.php`) i `AdvancedStatusAnalysis` (`src/AdvancedStatusAnalysis.php`).

---

### 3. Fabryka (Factory Method)
- **Opis**: Umożliwia tworzenie obiektów różnych typów urządzeń w zunifikowany sposób.
- **Miejsce implementacji**:  
  - Klasa `DeviceFactory` (`src/DeviceFactory.php`).

---

### 4. Obserwator (Observer)
- **Opis**: Pozwala na powiadamianie obserwatorów o zmianach w stanie systemu.
- **Miejsce implementacji**:  
  - Klasa `Subject` (`src/Subject.php`).  
  - Interfejs `ObserverInterface` (`src/ObserverInterface.php`).  
  - Klasy `AlertNotifier` (`src/AlertNotifier.php`) i `AlertLogger` (`src/AlertLogger.php`).

---

### 5. Dekorator (Decorator)
- **Opis**: Rozszerza funkcjonalność obiektów w sposób dynamiczny.
- **Miejsce implementacji**:  
  - Klasa `AdvancedStatusAnalysis` (`src/AdvancedStatusAnalysis.php`) – dodaje alerty do statusu urządzeń.

---

### 6. MVC (Model-View-Controller)
- **Opis**: Organizuje aplikację w trzy warstwy: Model, Widok i Kontroler.
- **Miejsce implementacji**:  
  - **Model**: Klasy `Device`, `Server`, `Router`, `SwitchDevice`, `Monitor` (`src/Device.php`, `src/Server.php`, `src/Router.php`, `src/Switch.php`, `src/Monitor.php`).  
  - **View**: Klasa `View` (`src/View.php`).  
  - **Controller**: Klasa `Controller` (`src/Controller.php`).

---

### 7. Szablonowa Metoda (Template Method)
- **Opis**: Definiuje ogólną strukturę algorytmu w klasie bazowej, a szczegóły implementacji pozostawia klasom pochodnym.
- **Miejsce implementacji**:  
  - Klasa `Device` (`src/Device.php`) jako klasa bazowa.  
  - Klasy pochodne: `Server` (`src/Server.php`), `Router` (`src/Router.php`), `SwitchDevice` (`src/Switch.php`).

---

### 8. Kompozyt (Composite)
- **Opis**: Umożliwia traktowanie grupy obiektów i pojedynczych obiektów w ten sam sposób.
- **Miejsce implementacji**:  
  - Klasa `Controller` (`src/Controller.php`) zarządza różnymi komponentami systemu, takimi jak `Monitor`, `Log`, `ConfigurationManager`, `AlertNotifier`.

---

### 9. Adapter (Adapter)
- **Opis**: Zapewnia zunifikowany interfejs do współpracy z różnymi klasami.
- **Miejsce implementacji**:  
  - Klasa `ConfigurationManager` (`src/ConfigurationManager.php`) – dostarcza zunifikowany sposób konfiguracji urządzeń.
