-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql:3306
-- Generation Time: Apr 26, 2025 at 03:03 PM
-- Wersja serwera: 8.0.42
-- Wersja PHP: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `to_projekt`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `tb_device`
--

CREATE TABLE `tb_device` (
  `ID` int NOT NULL,
  `type` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `IP` varchar(15) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `cpu` int NOT NULL,
  `ram` int NOT NULL,
  `disk` int NOT NULL,
  `server_services` json DEFAULT NULL,
  `router_protocol` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `router_activeConnections` int DEFAULT NULL,
  `router_interfaces` json DEFAULT NULL,
  `switch_ports` json DEFAULT NULL,
  `tracked` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Zrzut danych tabeli `tb_device`
--

INSERT INTO `tb_device` (`ID`, `type`, `name`, `IP`, `status`, `cpu`, `ram`, `disk`, `server_services`, `router_protocol`, `router_activeConnections`, `router_interfaces`, `switch_ports`, `tracked`) VALUES
(1, 'server', 'Server 1', '192.168.1.3', 1, 36, 62, 44, '[\"HTTP\", \"FTP\"]', NULL, 0, NULL, NULL, 1),
(4, 'server', 'Server 2', '192.168.1.4', 0, 0, 0, 0, '[\"SSH\", \"MySQL\"]', NULL, 0, NULL, NULL, 1),
(7, 'server', 'Server 3', '192.168.1.7', 1, 39, 13, 10, '[\"DNS\", \"SMTP\"]', NULL, 0, NULL, NULL, 1),
(10, 'server', 'Localhost Server', '127.0.0.1', 0, 0, 0, 0, '[\"IIS\"]', NULL, 0, NULL, NULL, 1),
(11, 'router', 'Router 1', '192.168.1.1', 1, 25, 42, 44, NULL, 'RIP', 22, '{\"eth0\": \"up\", \"eth1\": \"down\", \"eth2\": \"up\"}', NULL, 1),
(12, 'router', 'Router 2', '192.168.1.5', 1, 38, 45, 87, NULL, 'BGP', 22, '{\"eth0\": \"down\", \"eth1\": \"down\", \"eth2\": \"up\"}', NULL, 1),
(13, 'router', 'Router 3', '192.168.1.8', 0, 0, 0, 0, NULL, 'EIGRP', 0, '{\"eth0\": \"up\", \"eth1\": \"down\", \"eth2\": \"down\"}', NULL, 1),
(14, 'switch', 'Switch 1', '192.168.1.2', 1, 45, 15, 96, NULL, NULL, 0, NULL, '[false, false, true, true, false, false, false, true, false, true, true, false, true, true, true, true, false, false, true, true, false, true, true, false, false, false, true, true, false, false, true, false, true, true, false, false, true, false, false, true, false, false, false, true, true, true, false, false]', 1),
(15, 'switch', 'Switch 2', '192.168.1.6', 0, 0, 0, 0, NULL, NULL, 0, NULL, '[true, true, true, false, false, false, true, true, false, false, true, false, true, true, false, true, true, true, true, false, true, true, false, true, false, false, true, true, false, false, false, true, false, true, false, false, true, false, false, false, false, false, true, true, false, true, true, true]', 1),
(16, 'switch', 'Switch 3', '192.168.1.9', 0, 0, 0, 0, NULL, NULL, 0, NULL, '[false, true, true, true, true, false, true, true, false, false, true, false]', 1);

--
-- Wyzwalacze `tb_device`
--
DELIMITER $$
CREATE TRIGGER `before_insert_devices` BEFORE INSERT ON `tb_device` FOR EACH ROW BEGIN
    IF NEW.cpu IS NULL THEN
        SET NEW.cpu = FLOOR(10 + (RAND() * 91));
    END IF;
    IF NEW.ram IS NULL THEN
        SET NEW.ram = FLOOR(10 + (RAND() * 91));
    END IF;
    IF NEW.disk IS NULL THEN
        SET NEW.disk = FLOOR(10 + (RAND() * 91));
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_tb_device` BEFORE UPDATE ON `tb_device` FOR EACH ROW BEGIN
    -- wszystkie deklaracje muszą być na początku
    DECLARE i                  INT     DEFAULT 0;
    DECLARE len                INT     DEFAULT 0;
    DECLARE new_ports          JSON    DEFAULT JSON_ARRAY();
    DECLARE j                  INT     DEFAULT 0;
    DECLARE len_ifaces         INT     DEFAULT 0;
    DECLARE iface_keys         JSON;
    DECLARE iface_key          VARCHAR(255);
    DECLARE new_ifaces         JSON    DEFAULT JSON_OBJECT();

    -- 1) Aktualizacja cpu, ram, disk i router_activeConnections
    IF OLD.status = 0 AND NEW.status = 1 THEN
        SET NEW.cpu = FLOOR(RAND()*91) + 10;
        SET NEW.ram = FLOOR(RAND()*91) + 10;
        SET NEW.disk = FLOOR(RAND()*91) + 10;
        IF NEW.type = 'router' THEN
            SET NEW.router_activeConnections = FLOOR(RAND()*31) + 40;
        END IF;
    ELSEIF OLD.status = 1 AND NEW.status = 0 THEN
        SET NEW.cpu = 0;
        SET NEW.ram = 0;
        SET NEW.disk = 0;
        IF NEW.type = 'router' THEN
            SET NEW.router_activeConnections = 0;
        END IF;
    END IF;

    -- 2) Aktualizacja switch_ports JSON tylko dla urządzeń typu 'switch'
    IF NEW.type = 'switch' 
       AND ((OLD.status = 0 AND NEW.status = 1) OR (OLD.status = 1 AND NEW.status = 0))
    THEN
        IF NEW.switch_ports IS NOT NULL THEN
            SET len = JSON_LENGTH(NEW.switch_ports);
        END IF;
        SET i = 0;
        SET new_ports = JSON_ARRAY();
        WHILE i < len DO
            IF OLD.status = 0 AND NEW.status = 1 THEN
                SET new_ports = JSON_ARRAY_APPEND(new_ports, '$', RAND() < 0.5);
            ELSE
                SET new_ports = JSON_ARRAY_APPEND(new_ports, '$', FALSE);
            END IF;
            SET i = i + 1;
        END WHILE;
        SET NEW.switch_ports = new_ports;
    END IF;

    -- 3) Aktualizacja router_interfaces JSON tylko dla urządzeń typu 'router'
    IF NEW.type = 'router' 
       AND ((OLD.status = 0 AND NEW.status = 1) OR (OLD.status = 1 AND NEW.status = 0))
    THEN
        SET iface_keys   = JSON_KEYS(NEW.router_interfaces);
        SET len_ifaces   = JSON_LENGTH(iface_keys);
        SET new_ifaces   = JSON_OBJECT();
        SET j            = 0;
        WHILE j < len_ifaces DO
            SET iface_key = JSON_UNQUOTE(JSON_EXTRACT(iface_keys, CONCAT('$[', j, ']')));
            IF OLD.status = 0 AND NEW.status = 1 THEN
                -- 70% szans na 'up', 30% na 'down'
                SET new_ifaces = JSON_SET(
                    new_ifaces,
                    CONCAT('$.', iface_key),
                    IF(RAND() < 0.7, 'up', 'down')
                );
            ELSE
                -- ustaw wszystkie na 'down'
                SET new_ifaces = JSON_SET(new_ifaces, CONCAT('$.', iface_key), 'down');
            END IF;
            SET j = j + 1;
        END WHILE;
        SET NEW.router_interfaces = new_ifaces;
    END IF;

END
$$
DELIMITER ;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `tb_device`
--
ALTER TABLE `tb_device`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `IP` (`IP`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `tb_device`
--
ALTER TABLE `tb_device`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

DELIMITER $$
--
-- Zdarzenia
--
CREATE DEFINER=`root`@`%` EVENT `update_device_status` ON SCHEDULE EVERY 1 MINUTE STARTS '2025-04-26 13:08:39' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
  UPDATE `tb_device`
  SET
    status = IF(
               RAND() < 0.1,   -- 10% szans
               1 - status,     -- zmień 0→1 lub 1→0
               status          -- w pozostałych przypadkach bez zmian
             );
END$$

CREATE DEFINER=`root`@`%` EVENT `update_tb_device` ON SCHEDULE EVERY 2 SECOND STARTS '2025-04-26 13:11:22' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    -- 1) Aktualizacja cpu/ram/disk/router_activeConnections
    UPDATE `tb_device`
    SET
        cpu = IF(status = 0, 0,
                 LEAST(100, GREATEST(10, cpu + (FLOOR(RAND()*11) - 5)))),
        ram = IF(status = 0, 0,
                 LEAST(100, GREATEST(10, ram + (FLOOR(RAND()*11) - 5)))),
        disk = IF(status = 0, 0,
                  LEAST(100, GREATEST(10, disk + (FLOOR(RAND()*11) - 5)))),
        router_activeConnections = IF(
            type = 'router' AND status = 1,
            LEAST(120,
                  GREATEST(20,
                           router_activeConnections + (FLOOR(RAND()*5) - 2)
                  )
            ),
            0
        );

    -- 2) 20% szans na flip każdego interface w JSON dla routerów
    BEGIN
        DECLARE done_router   BOOLEAN DEFAULT FALSE;
        DECLARE r_id          INT;
        DECLARE cur_ifaces    JSON;
        DECLARE iface_keys    JSON;
        DECLARE iface_count   INT;
        DECLARE iface_idx     INT;
        DECLARE key_name      VARCHAR(255);
        DECLARE curr_val      VARCHAR(10);
        DECLARE new_ifaces    JSON;

        DECLARE router_cursor CURSOR FOR
            SELECT id, router_interfaces
              FROM `tb_device`
             WHERE type = 'router';
        DECLARE CONTINUE HANDLER FOR NOT FOUND SET done_router = TRUE;

        OPEN router_cursor;
        router_loop: LOOP
            FETCH router_cursor INTO r_id, cur_ifaces;
            IF done_router THEN
                LEAVE router_loop;
            END IF;

            SET iface_keys  = JSON_KEYS(cur_ifaces);
            SET iface_count = JSON_LENGTH(iface_keys);
            SET new_ifaces  = JSON_OBJECT();
            SET iface_idx   = 0;

            WHILE iface_idx < iface_count DO
                SET key_name = JSON_UNQUOTE(
                                  JSON_EXTRACT(iface_keys,
                                               CONCAT('$[', iface_idx, ']')
                                  )
                              );
                SET curr_val = JSON_UNQUOTE(
                                  JSON_EXTRACT(cur_ifaces,
                                               CONCAT('$.', key_name)
                                  )
                              );
                IF RAND() < 0.2 THEN
                    SET curr_val = IF(curr_val = 'up', 'down', 'up');
                END IF;
                SET new_ifaces = JSON_SET(
                                     new_ifaces,
                                     CONCAT('$.', key_name),
                                     curr_val
                                 );
                SET iface_idx = iface_idx + 1;
            END WHILE;

            UPDATE `tb_device`
               SET router_interfaces       = new_ifaces,
                   router_activeConnections =
                       IF(JSON_SEARCH(new_ifaces, 'one', 'up') IS NULL,
                          0,
                          router_activeConnections
                       )
             WHERE id = r_id;
        END LOOP;
        CLOSE router_cursor;
    END;

    -- 3) 10% szans na flip każdego portu true/false dla switchy
    BEGIN
        DECLARE done_sw     BOOLEAN DEFAULT FALSE;
        DECLARE s_id        INT;
        DECLARE cur_ports   JSON;
        DECLARE port_count  INT;
        DECLARE port_idx    INT;
        DECLARE port_val    JSON;
        DECLARE new_val     JSON;
        DECLARE new_ports   JSON;

        DECLARE switch_cursor CURSOR FOR
            SELECT id, switch_ports
              FROM `tb_device`
             WHERE type = 'switch';
        DECLARE CONTINUE HANDLER FOR NOT FOUND SET done_sw = TRUE;

        OPEN switch_cursor;
        switch_loop: LOOP
            FETCH switch_cursor INTO s_id, cur_ports;
            IF done_sw THEN
                LEAVE switch_loop;
            END IF;

            SET port_count = JSON_LENGTH(cur_ports);
            SET new_ports  = JSON_ARRAY();
            SET port_idx   = 0;

            WHILE port_idx < port_count DO
                SET port_val = JSON_EXTRACT(cur_ports, CONCAT('$[', port_idx, ']'));
                IF RAND() < 0.1 THEN
                    SET new_val = IF(
                        JSON_UNQUOTE(port_val) = 'true',
                        CAST('false' AS JSON),
                        CAST('true'  AS JSON)
                    );
                ELSE
                    SET new_val = port_val;
                END IF;
                SET new_ports = JSON_ARRAY_APPEND(new_ports, '$', new_val);
                SET port_idx = port_idx + 1;
            END WHILE;

            UPDATE `tb_device`
               SET switch_ports = new_ports
             WHERE id = s_id;
        END LOOP;
        CLOSE switch_cursor;
    END;

END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
