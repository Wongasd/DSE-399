-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2025 at 02:08 PM
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
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `AuthorID` int(11) NOT NULL,
  `FirstName` varchar(100) DEFAULT NULL,
  `LastName` varchar(100) DEFAULT NULL,
  `Description` varchar(255) NOT NULL,
  `Image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`AuthorID`, `FirstName`, `LastName`, `Description`, `Image`) VALUES
(3, 'Harper', 'Lee', 'An American author known for her profound exploration of racial injustice in the American South. To Kill a Mockingbird is her most celebrated work and a cornerstone of modern literature.', 'db_image/images2.jpg'),
(4, 'George', 'Orwell', 'A British author and journalist, Orwell is renowned for his sharp criticism of totalitarianism and social injustice, as seen in works like 1984 and Animal Farm.', 'db_image/George_Orwell_press_photo.jpg'),
(5, 'Jane', 'Austen', 'An iconic English novelist, Austen is celebrated for her wit and social commentary in exploring themes of love, class, and societal expectations in the 18th and 19th centuries.', 'db_image/Jane_Austen.jpg'),
(6, 'J. R. R.', 'Tolkien', 'An English writer and philologist, Tolkien is hailed as the father of modern fantasy, with works like The Hobbit and The Lord of the Rings influencing countless authors.', 'db_image/J._R._R._Tolkien,_ca._1925.jpg'),
(7, 'Terry', 'Pratchett', 'Terry Pratchett (1948–2015) was a celebrated British author known for his satirical Discworld series, blending humor and fantasy to explore human nature. Knighted in 2009, he remains one of modern literature’s most beloved writers.', 'db_image/TerryPratchett_c_RobWilkins.png');

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
  `Status` varchar(52) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`BookID`, `Title`, `AuthorID`, `GenreID`, `PublisherID`, `PublishedYear`, `Quantity`, `Image`, `Description`, `Status`) VALUES
(15, 'To Kill a Mockingbird', '3', '4', '3', '1960-07-11', 10, 'db_image/to-kill-a-mockingbird-3.jpg', 'A powerful tale of racial injustice and childhood innocence set in the Deep South.', 'Available'),
(16, '1984', '4', '5', '4', '1949-06-08', 10, 'db_image/1984.jpg', 'A chilling portrayal of a totalitarian society and the dangers of political oppression.', 'Available'),
(17, 'Pride and Prejudice', '5', '6', '5', '1813-01-28', 0, 'db_image/9780571337019.jpg', 'A witty exploration of love, class, and family dynamics in 19th-century England.', 'Unavailable'),
(18, 'The Hobbit', '6', '1', '6', '1937-09-21', 50, 'db_image/9780261103344.jpg', 'A prelude to \"The Lord of the Rings,\" chronicling Bilbo Baggins\' adventures.', 'Available'),
(19, 'Good Omens', '7', '2', '7', '1990-05-10', 20, 'db_image/12067.jpg', 'This hilarious novel follows the misadventures of an angel, Aziraphale, and a demon, Crowley, as they team up to prevent the apocalypse. The book is filled with witty humor, absurd situations, and clever commentary on humanity\'s quirks and flaws.', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `GenreID` int(11) NOT NULL,
  `GenreName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`GenreID`, `GenreName`) VALUES
(2, 'Comedy'),
(1, 'Fantasy'),
(4, 'Fiction'),
(6, 'Romance'),
(5, 'Science Fiction');

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE `permission` (
  `PermissionID` int(52) NOT NULL,
  `PermissionName` varchar(52) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`PermissionID`, `PermissionName`) VALUES
(1, 'admin'),
(2, 'user'),
(3, 'librarian');

-- --------------------------------------------------------

--
-- Table structure for table `publishers`
--

CREATE TABLE `publishers` (
  `PublisherID` int(11) NOT NULL,
  `PublisherName` varchar(255) NOT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `Image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `publishers`
--

INSERT INTO `publishers` (`PublisherID`, `PublisherName`, `Address`, `Phone`, `Image`) VALUES
(3, 'J. B. Lippincott & Co.', 'United States', '+601192389437', 'db_image/Lippincott_Logo_1937-768x277.jpg'),
(4, 'Harvill Secker', 'London', '+6519298749', 'db_image/HarvillSecker-700x460.jpg'),
(5, 'Thomas Egerton', 'White Hall', '+601110394850', 'db_image/thomas.jpg'),
(6, 'Allen & Unwin', 'Crows Nest, New South Wales, Australia', '+601139875983', 'db_image/c35427.png'),
(7, 'Workman Publishing Company', '225 Varick StreetNew York City, New York', '+601139816898', 'db_image/workman.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `ReportID` int(52) NOT NULL,
  `GenerateBy` int(52) NOT NULL,
  `ReportType` varchar(52) NOT NULL,
  `GeneratedDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`ReportID`, `GenerateBy`, `ReportType`, `GeneratedDate`) VALUES
(1, 14, 'Excel Report', '2025-10-26'),
(2, 14, 'Excel Report', '2025-10-26'),
(3, 14, 'Excel Report', '2025-10-26'),
(4, 14, 'Excel Report', '2025-10-26'),
(5, 14, 'Excel Report', '2025-10-26'),
(6, 14, 'Excel Report', '2025-10-26'),
(7, 14, 'Excel Report', '2025-10-26'),
(8, 14, 'Excel Report', '2025-10-26'),
(9, 14, 'Excel Report', '2025-10-26'),
(10, 14, 'Excel Report', '2025-10-26'),
(11, 14, 'Excel Report', '2025-10-27');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `TransactionID` int(11) NOT NULL,
  `BookID` varchar(255) NOT NULL,
  `UserID` varchar(255) NOT NULL,
  `Quantity` varchar(50) NOT NULL,
  `BorrowDate` date NOT NULL,
  `ReturnDate` date DEFAULT NULL,
  `DueDate` date NOT NULL,
  `Status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`TransactionID`, `BookID`, `UserID`, `Quantity`, `BorrowDate`, `ReturnDate`, `DueDate`, `Status`) VALUES
(8, '17', '16', '1', '2024-11-23', '2025-10-27', '2024-11-30', 'ReturnedLate'),
(9, '19', '17', '1', '2024-11-23', '2024-11-27', '2024-11-30', 'RETURNED'),
(10, '16', '18', '1', '2024-11-24', '2024-11-27', '2024-12-01', 'RETURNED'),
(11, '16', '19', '1', '2025-10-27', NULL, '2025-11-03', 'PENDING');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `FirstName` varchar(100) DEFAULT NULL,
  `LastName` varchar(100) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `Status` varchar(52) NOT NULL,
  `MembershipDate` date DEFAULT NULL,
  `Permission` varchar(15) NOT NULL,
  `Image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `FirstName`, `LastName`, `Password`, `Email`, `Phone`, `Address`, `Status`, `MembershipDate`, `Permission`, `Image`) VALUES
(14, 'Wong', 'Kit Park', '$2y$10$V5DoELPqLe0f6o3uvydyIe2ArtS26JqPoy4bdQfEcTZ6dL1UKefZ6', 'asd@gmail.com', '+601231312312', 'Malaysia', 'Available', '2024-11-19', '1', ''),
(16, 'Park Kit', 'Wong', '$2y$10$u7HLwdKzwumTiKdgOypXwOCHSipwwWOO0lfcmcwDcBWYYeWTq9/Vu', 'wong@gmail.com', '+601110934890', 'Malaysia', 'Banned', '2024-11-23', '2', 'db_image/phpnet_logo.jpeg'),
(17, 'Ee Fun', 'Tan', '$2y$10$wtqpT0v/30/oJ/jVFqF7BOs8aOVHEp0xRTFlHKFnS4MQoItJNGOgG', 'TanEeFun@gmail.com', '+601130948029', 'malaysia', 'Banned', '2024-11-23', '2', 'db_image/J._R._R._Tolkien,_ca._1925.jpg'),
(18, 'asd', 'Wong', '$2y$10$qAOqRtNhG2CPSo8qpX03c.AGCyfL3T16nSDXKqaodbCEruc1fkJvC', 'wongparkkit@gmail.com', '+601120394898', 'no.8 malaysia', 'Borrowed', '2024-11-24', '2', 'db_image/wong park kit.png'),
(19, 'Wong', '123', '$2y$10$NkADjIzuX9TE1/HlAyuH7OWbQv0UK7aMnkVCTt3druHB3vZOKgnCm', 'asdf@gmail.com', '+601234123411', 'dakljshldfkajhfflak', 'Banned', '2025-10-23', '2', 'db_image/angwy_tenshi.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`AuthorID`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`BookID`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`GenreID`),
  ADD UNIQUE KEY `GenreName` (`GenreName`);

--
-- Indexes for table `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`PermissionID`);

--
-- Indexes for table `publishers`
--
ALTER TABLE `publishers`
  ADD PRIMARY KEY (`PublisherID`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`ReportID`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`TransactionID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `AuthorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `BookID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `GenreID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `permission`
--
ALTER TABLE `permission`
  MODIFY `PermissionID` int(52) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `publishers`
--
ALTER TABLE `publishers`
  MODIFY `PublisherID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `ReportID` int(52) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `TransactionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
