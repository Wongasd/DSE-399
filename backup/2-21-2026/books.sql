-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 21, 2026 at 12:03 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `BookID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `AuthorID` varchar(255) NOT NULL,
  `GenreID` varchar(255) NOT NULL,
  `PublisherID` varchar(255) NOT NULL,
  `PublishedYear` date DEFAULT NULL,
  `Quantity` int(11) DEFAULT 1,
  `Image` varchar(52) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Status` varchar(52) NOT NULL,
  `TypeID` varchar(52) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`BookID`, `Title`, `AuthorID`, `GenreID`, `PublisherID`, `PublishedYear`, `Quantity`, `Image`, `Description`, `Status`, `TypeID`) VALUES
(15, 'To Kill a Mockingbird', '3', '4', '3', '1960-07-11', 10, 'db_image/to-kill-a-mockingbird-3.jpg', 'A powerful tale of racial injustice and childhood innocence set in the Deep South.', 'Available', '1'),
(16, '1984', '4', '5', '4', '1949-06-08', 10, 'db_image/1984.jpg', 'A chilling portrayal of a totalitarian society and the dangers of political oppression.', 'Available', '1'),
(17, 'Pride and Prejudice', '5', '6', '5', '1813-01-28', 0, 'db_image/9780571337019.jpg', 'A witty exploration of love, class, and family dynamics in 19th-century England.', 'Unavailable', '1'),
(18, 'The Hobbit', '6', '1', '6', '1937-09-21', 50, 'db_image/9780261103344.jpg', 'A prelude to \"The Lord of the Rings,\" chronicling Bilbo Baggins\' adventures.', 'Available', '1'),
(19, 'Good Omens', '7', '2', '7', '1990-05-10', 20, 'db_image/12067.jpg', 'This hilarious novel follows the misadventures of an angel, Aziraphale, and a demon, Crowley, as they team up to prevent the apocalypse. The book is filled with witty humor, absurd situations, and clever commentary on humanity\'s quirks and flaws.', 'Available', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`BookID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `BookID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
