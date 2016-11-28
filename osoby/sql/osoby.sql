-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Czas generowania: 28 Lis 2016, 16:30
-- Wersja serwera: 10.1.13-MariaDB
-- Wersja PHP: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `osoby`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `firmy`
--

CREATE TABLE `firmy` (
  `id_firmy` int(11) NOT NULL,
  `firma` varchar(32) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `firmy`
--

INSERT INTO `firmy` (`id_firmy`, `firma`) VALUES
(1, 'Firma 1'),
(2, 'Firma 2');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `miejscowosci`
--

CREATE TABLE `miejscowosci` (
  `id_miejscowosci` int(11) NOT NULL,
  `miejscowosc` varchar(32) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `miejscowosci`
--

INSERT INTO `miejscowosci` (`id_miejscowosci`, `miejscowosc`) VALUES
(1, 'Miejscowość 1'),
(2, 'Miejscowość 2');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `oddzialy_firmy`
--

CREATE TABLE `oddzialy_firmy` (
  `id_oddzialy_firmy` int(11) NOT NULL,
  `oddzial_firmy` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `id_firmy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `oddzialy_firmy`
--

INSERT INTO `oddzialy_firmy` (`id_oddzialy_firmy`, `oddzial_firmy`, `id_firmy`) VALUES
(1, 'Firma 1 A', 1),
(2, 'Firma 1 B', 1),
(3, 'Firma 2 A', 2),
(4, 'Firma 2 B', 2);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `osoby`
--

CREATE TABLE `osoby` (
  `id_osoby` int(11) NOT NULL,
  `nazwisko` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `imie` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `id_miejscowosci` int(11) NOT NULL,
  `data_urodzenia` date NOT NULL,
  `id_oddzialy_firmy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `osoby`
--

INSERT INTO `osoby` (`id_osoby`, `nazwisko`, `imie`, `id_miejscowosci`, `data_urodzenia`, `id_oddzialy_firmy`) VALUES
(1, 'Kowalski', 'Jan', 2, '1989-04-15', 2),
(2, 'Kowalska', 'Janina', 1, '1974-02-08', 3);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `firmy`
--
ALTER TABLE `firmy`
  ADD PRIMARY KEY (`id_firmy`);

--
-- Indexes for table `miejscowosci`
--
ALTER TABLE `miejscowosci`
  ADD PRIMARY KEY (`id_miejscowosci`);

--
-- Indexes for table `oddzialy_firmy`
--
ALTER TABLE `oddzialy_firmy`
  ADD PRIMARY KEY (`id_oddzialy_firmy`);

--
-- Indexes for table `osoby`
--
ALTER TABLE `osoby`
  ADD PRIMARY KEY (`id_osoby`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `firmy`
--
ALTER TABLE `firmy`
  MODIFY `id_firmy` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT dla tabeli `miejscowosci`
--
ALTER TABLE `miejscowosci`
  MODIFY `id_miejscowosci` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT dla tabeli `oddzialy_firmy`
--
ALTER TABLE `oddzialy_firmy`
  MODIFY `id_oddzialy_firmy` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT dla tabeli `osoby`
--
ALTER TABLE `osoby`
  MODIFY `id_osoby` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
