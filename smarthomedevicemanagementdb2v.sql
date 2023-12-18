-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2023. Dec 18. 07:17
-- Kiszolgáló verziója: 10.4.28-MariaDB
-- PHP verzió: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `smarthomedevicemanagementdb2v`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `camera_settings`
--

CREATE TABLE `camera_settings` (
  `camera_id` int(11) NOT NULL,
  `device_id` int(11) DEFAULT NULL,
  `resolution` varchar(50) NOT NULL,
  `motion_detection` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `camera_settings`
--

INSERT INTO `camera_settings` (`camera_id`, `device_id`, `resolution`, `motion_detection`) VALUES
(1, 2, '1080p', 1),
(2, 4, '720p', 0),
(3, 8, '1080p', 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `devices`
--

CREATE TABLE `devices` (
  `device_id` int(11) NOT NULL,
  `device_type` enum('thermostat','camera','light') NOT NULL,
  `device_name` varchar(255) NOT NULL,
  `status` enum('on','off') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `devices`
--

INSERT INTO `devices` (`device_id`, `device_type`, `device_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'thermostat', 'Living Room Thermostat', 'on', '2023-12-16 18:32:03', '2023-12-16 18:32:03'),
(2, 'camera', 'Front Door Camera', 'off', '2023-12-16 18:32:03', '2023-12-16 18:32:03'),
(3, 'thermostat', 'Bedroom Thermostat', 'off', '2023-12-16 18:32:03', '2023-12-16 18:32:03'),
(4, 'camera', 'Backyard Camera', 'on', '2023-12-16 18:32:03', '2023-12-16 18:32:03'),
(5, 'light', 'Kitchen Light', 'on', '2023-12-16 18:32:03', '2023-12-16 18:32:03'),
(6, 'light', 'Bathroom Light', 'off', '2023-12-16 18:32:03', '2023-12-16 18:32:03'),
(7, 'thermostat', 'Office Thermostat', 'on', '2023-12-16 18:32:03', '2023-12-16 18:32:03'),
(8, 'camera', 'Garage Camera', 'off', '2023-12-16 18:32:03', '2023-12-16 18:32:03'),
(9, 'light', 'Garage Light', 'off', '2023-12-16 18:32:03', '2023-12-16 18:32:03'),
(10, 'light', 'Patio Light', 'off', '2023-12-16 18:32:03', '2023-12-18 06:12:40'),
(12, 'thermostat', 'Guest Room Thermostat', 'off', '2023-12-16 18:32:03', '2023-12-16 18:32:03'),
(13, '', 'Attic pattic', 'off', '2023-12-16 22:57:02', '2023-12-16 22:57:02'),
(14, 'camera', 'Canaon', 'off', '2023-12-17 23:13:56', '2023-12-17 23:13:56');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `light_settings`
--

CREATE TABLE `light_settings` (
  `light_id` int(11) NOT NULL,
  `device_id` int(11) DEFAULT NULL,
  `brightness` int(11) NOT NULL,
  `color` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `light_settings`
--

INSERT INTO `light_settings` (`light_id`, `device_id`, `brightness`, `color`) VALUES
(1, 5, 90, 'White'),
(2, 6, 50, 'Yellow'),
(3, 9, 30, 'Red'),
(4, 10, 100, 'Blue');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `thermostat_settings`
--

CREATE TABLE `thermostat_settings` (
  `thermostat_id` int(11) NOT NULL,
  `device_id` int(11) DEFAULT NULL,
  `temperature` float NOT NULL,
  `mode` enum('heat','cool') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `thermostat_settings`
--

INSERT INTO `thermostat_settings` (`thermostat_id`, `device_id`, `temperature`, `mode`) VALUES
(1, 1, 72.5, 'heat'),
(2, 3, 65, 'cool'),
(3, 7, 75, 'heat'),
(4, 12, 68, 'cool');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`) VALUES
(1, 'alma', '$2y$10$FM2Zev1OxEHjTBRXm1O71OJmrOhDEehHAZv9RrNF25utjG5rkqx8a');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `camera_settings`
--
ALTER TABLE `camera_settings`
  ADD PRIMARY KEY (`camera_id`),
  ADD KEY `device_id` (`device_id`);

--
-- A tábla indexei `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`device_id`);

--
-- A tábla indexei `light_settings`
--
ALTER TABLE `light_settings`
  ADD PRIMARY KEY (`light_id`),
  ADD KEY `device_id` (`device_id`);

--
-- A tábla indexei `thermostat_settings`
--
ALTER TABLE `thermostat_settings`
  ADD PRIMARY KEY (`thermostat_id`),
  ADD KEY `device_id` (`device_id`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `camera_settings`
--
ALTER TABLE `camera_settings`
  MODIFY `camera_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT a táblához `devices`
--
ALTER TABLE `devices`
  MODIFY `device_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT a táblához `light_settings`
--
ALTER TABLE `light_settings`
  MODIFY `light_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT a táblához `thermostat_settings`
--
ALTER TABLE `thermostat_settings`
  MODIFY `thermostat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `camera_settings`
--
ALTER TABLE `camera_settings`
  ADD CONSTRAINT `camera_settings_ibfk_1` FOREIGN KEY (`device_id`) REFERENCES `devices` (`device_id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `light_settings`
--
ALTER TABLE `light_settings`
  ADD CONSTRAINT `light_settings_ibfk_1` FOREIGN KEY (`device_id`) REFERENCES `devices` (`device_id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `thermostat_settings`
--
ALTER TABLE `thermostat_settings`
  ADD CONSTRAINT `thermostat_settings_ibfk_1` FOREIGN KEY (`device_id`) REFERENCES `devices` (`device_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
