-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 03, 2026 at 12:57 PM
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
-- Database: `flavorsync_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE `recipes` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `ingredients` text NOT NULL,
  `instructions` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `category` varchar(50) DEFAULT 'dinner',
  `diet` varchar(50) DEFAULT 'veg',
  `time` int(11) DEFAULT 20,
  `difficulty` varchar(50) DEFAULT 'easy',
  `rating` decimal(3,1) DEFAULT 4.5,
  `image` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`id`, `title`, `ingredients`, `instructions`, `user_id`, `category`, `diet`, `time`, `difficulty`, `rating`, `image`) VALUES
(1, 'Egg Fried Rice', 'rice, egg, onion, soy sauce, oil', 'Heat oil, add beaten eggs, scramble, add cooked rice and soy sauce, stir well.', NULL, 'dinner', 'veg', 20, 'easy', 4.5, ''),
(2, 'Onion Omelette', 'egg, onion, chili, salt, oil', 'Beat eggs with chopped onion, chili, and salt. Fry in hot oil until golden brown.', NULL, 'dinner', 'veg', 20, 'easy', 4.5, ''),
(3, 'Rice Congee', 'rice, water, salt, ginger', 'Boil rice with excess water and ginger on low heat until soft and creamy porridge forms.', NULL, 'dinner', 'veg', 20, 'easy', 4.5, ''),
(4, 'String Hoppers', 'Rice flour, salt, water', 'Make dough, press through mold, steam.', NULL, 'breakfast', 'veg', 30, 'medium', 4.5, ''),
(5, 'Grains made recipes', 'Mixed grains, spices', 'Boil and mix.', NULL, 'breakfast', 'veg', 20, 'easy', 4.5, ''),
(6, 'Paratas', 'Wheat flour, oil, salt, water', 'Knead dough, roll, fry on pan.', NULL, 'breakfast', 'veg', 30, 'medium', 4.5, ''),
(7, 'Rice and Curry', 'Rice, lentils, vegetables, spices, coconut milk', 'Cook rice, prepare various curries.', NULL, 'lunch', 'veg', 60, 'hard', 4.5, ''),
(8, 'Kottu', 'Roti, vegetables, egg, chicken, spices', 'Chop roti and stir fry with ingredients.', NULL, 'dinner', 'nonveg', 25, 'medium', 4.5, ''),
(9, 'Fried Rice', 'Rice, egg, vegetables, soy sauce', 'Stir fry everything together.', NULL, 'dinner', 'nonveg', 20, 'easy', 4.5, ''),
(10, 'Fruit Salad', 'Mixed fruits, sugar', 'Chop fruits and mix.', NULL, 'dessert', 'veg', 10, 'easy', 4.5, ''),
(11, 'Falooda', 'Milk, rose syrup, basil seeds, jelly', 'Mix all ingredients and serve chilled.', NULL, 'dessert', 'veg', 15, 'easy', 4.5, ''),
(12, 'Ice Coffee', 'Coffee, milk, sugar, ice', 'Brew coffee, mix with milk and ice.', NULL, 'dessert', 'veg', 5, 'easy', 4.5, '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'Matheesh', 'matheesha123@gmail.com', '$2y$10$twP/G5XJ1yknSHuIkZ1uMe83UbtJ.jAeIDNEks11q.wJ8CqOVHs8a', '2026-09-03 07:50:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `recipes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
