-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 12, 2024 at 06:37 PM
-- Wersja serwera: 10.4.28-MariaDB
-- Wersja PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sklep`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `koszyki`
--

CREATE TABLE `koszyki` (
  `id_zamowienia` int(11) NOT NULL,
  `id_produktu` int(11) NOT NULL,
  `id_uzytkownicy` varchar(30) NOT NULL,
  `ilosc_zamow` int(11) NOT NULL,
  `dokonano` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `koszyki`
--

INSERT INTO `koszyki` (`id_zamowienia`, `id_produktu`, `id_uzytkownicy`, `ilosc_zamow`, `dokonano`) VALUES
(3, 3, 'user', 1, 0),
(9, 5, 'admin', 3, 0),
(11, 5, 'user', 1, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `produkty`
--

CREATE TABLE `produkty` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(50) NOT NULL,
  `cena` decimal(10,2) NOT NULL,
  `ilosc` int(11) NOT NULL,
  `opis` text NOT NULL,
  `FilePath` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produkty`
--

INSERT INTO `produkty` (`id`, `nazwa`, `cena`, `ilosc`, `opis`, `FilePath`) VALUES
(2, 'nowy', 1.20, 0, 'paczryk', 'uploads/660e63435b33d_pobrane (1).jpg'),
(3, 'sigma', 200.00, 1, 'sigma', 'uploads/660e642794ff3_sigma.jpg'),
(4, 'tescior', 2.00, 30, 'TRENowanie', 'uploads/660ea23eab848_pobrane (1).jpg'),
(5, 'nowy', 1.20, 20, 'wadas', 'uploads/660ea645e73f3_sigma.jpg'),
(6, 'maciek', 0.02, 1, 'niewolnik maly', 'uploads/660eab4b3f04d_sigma.jpg');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownicy`
--

CREATE TABLE `uzytkownicy` (
  `login` varchar(30) NOT NULL,
  `password` varchar(32) NOT NULL,
  `upr` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uzytkownicy`
--

INSERT INTO `uzytkownicy` (`login`, `password`, `upr`) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3', 'admin'),
('awdaw', '098f6bcd4621d373cade4e832627b4f6', 'user'),
('newUser', '098f6bcd4621d373cade4e832627b4f6', 'user'),
('user', 'ee11cbb19052e40b07aac0ca060c23ee', 'user');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `koszyki`
--
ALTER TABLE `koszyki`
  ADD PRIMARY KEY (`id_zamowienia`);

--
-- Indeksy dla tabeli `produkty`
--
ALTER TABLE `produkty`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`login`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `koszyki`
--
ALTER TABLE `koszyki`
  MODIFY `id_zamowienia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `produkty`
--
ALTER TABLE `produkty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
