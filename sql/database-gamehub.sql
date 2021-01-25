-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Gen 25, 2021 alle 20:20
-- Versione del server: 10.4.14-MariaDB
-- Versione PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gamehub`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `annunci`
--

CREATE TABLE `annunci` (
  `id` int(11) NOT NULL,
  `email_utente` varchar(200) NOT NULL,
  `piattaforma` varchar(50) NOT NULL,
  `titolo` varchar(100) NOT NULL,
  `testo` varchar(500) DEFAULT NULL,
  `path_immagine` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `annunci`
--

INSERT INTO `annunci` (`id`, `email_utente`, `piattaforma`, `titolo`, `testo`, `path_immagine`) VALUES
(6, 'amatomarco111@gmail.com', 'PS5', 'Cyberpunk 2077', 'test', '600723a4413455.52309020.jpg'),
(7, 'amatomarco111@gmail.com', 'Nintendo Switch', 'Super Mario Odissey', 'testo prova ciao ciao', '600daf53242d00.13067060.jpg'),
(8, 'amatomarco111@gmail.com', 'Xbox series X', 'Yakuza: Like a dragon', 'testo ciaoc aofe jioòweji òaefjoi aòwjfkòmk vdlsòkvmdsò ldkmasdò aikfew àafkewàp f,a wkla lòaàc,lwef ,lwe cafeokcfewo fkoew kfe kofew àwfa', '600db0d6cff896.97399951.jpeg');

-- --------------------------------------------------------

--
-- Struttura della tabella `piattaforme`
--

CREATE TABLE `piattaforme` (
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `piattaforme`
--

INSERT INTO `piattaforme` (`nome`) VALUES
('Nintendo 3DS'),
('Nintendo Switch'),
('PS5'),
('Xbox series X');

-- --------------------------------------------------------

--
-- Struttura della tabella `preferiti`
--

CREATE TABLE `preferiti` (
  `id_annuncio` int(11) NOT NULL,
  `email_utente` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `preferiti`
--

INSERT INTO `preferiti` (`id_annuncio`, `email_utente`) VALUES
(6, 'amatomarco111@gmail.com'),
(6, 'd@a.i'),
(7, 'd@a.i');

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `data_di_nascita` date NOT NULL,
  `genere` enum('M','F') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`email`, `password`, `data_di_nascita`, `genere`) VALUES
('amatomarco111@gmail.com', '$2y$10$eO4sNGtrCZST4RBbthU8A.qONJo4pPNXKMdtNYj88wy2ItuCWAgNq', '1999-04-22', 'M'),
('d@a.i', '$2y$10$7SZXcJhZXB70aZ.W1cn0/OGMGOlBDAwX0KN36UbXhJxEspED/TwmW', '1978-05-12', 'F');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `annunci`
--
ALTER TABLE `annunci`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email_utente` (`email_utente`),
  ADD KEY `piattaforma` (`piattaforma`);

--
-- Indici per le tabelle `piattaforme`
--
ALTER TABLE `piattaforme`
  ADD PRIMARY KEY (`nome`);

--
-- Indici per le tabelle `preferiti`
--
ALTER TABLE `preferiti`
  ADD PRIMARY KEY (`id_annuncio`,`email_utente`),
  ADD KEY `email_utente` (`email_utente`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `annunci`
--
ALTER TABLE `annunci`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `annunci`
--
ALTER TABLE `annunci`
  ADD CONSTRAINT `annunci_ibfk_1` FOREIGN KEY (`email_utente`) REFERENCES `utenti` (`email`),
  ADD CONSTRAINT `annunci_ibfk_2` FOREIGN KEY (`piattaforma`) REFERENCES `piattaforme` (`nome`);

--
-- Limiti per la tabella `preferiti`
--
ALTER TABLE `preferiti`
  ADD CONSTRAINT `preferiti_ibfk_1` FOREIGN KEY (`id_annuncio`) REFERENCES `annunci` (`id`),
  ADD CONSTRAINT `preferiti_ibfk_2` FOREIGN KEY (`email_utente`) REFERENCES `utenti` (`email`),
  ADD CONSTRAINT `preferiti_ibfk_3` FOREIGN KEY (`id_annuncio`) REFERENCES `annunci` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
