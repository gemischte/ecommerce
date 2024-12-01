-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2024-12-01 09:45:51
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `test`
--

-- --------------------------------------------------------

--
-- 資料表結構 `register`
--

CREATE TABLE `register` (
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- 傾印資料表的資料 `register`
--

INSERT INTO `register` (`username`, `email`, `password`) VALUES
('AWDWDaaa2123', 'AWDWDaaa2123@www.www', '$2y$10$XH/rjOn7uaTT64BZi8qTHOelCZEm4Uy9BhccJAdJbPNQYsR/yzADu'),
('XZaa7878', 'XZaa7878@qq.dwd', '$2y$10$rUFXK83ILWM1LXfXkDUb4.tqAdVcdP9KXPjddmSum6u3wMvk/pIZG'),
('AWe12316532', 'AWe12316532@qq.q', '$2y$10$pCAWGlmvEU6Ypubg.JUH8ezl6On7I9eW5cEfmqcjUJ/UBAMR2mRUe'),
('QDWwd45', 'QDWwd45@ww.w', '$2y$10$5XIWJfAGpYflQnUfM8M8huvJqm/Seekec1Z4yYBPYi962cQ.CFxI6'),
('Axdwd132', 'axdwd@gmail.com', '$2y$10$qA9bTElPP5hmR1eX9.XjFOuAic3sN.zwl2AV9vVo/MCQeXrmJQ/uu');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
