-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2024 at 02:13 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_management_platform`
--

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `CommentID` int(11) NOT NULL,
  `Content` text NOT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `UserID` int(11) NOT NULL,
  `TaskID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`CommentID`, `Content`, `Timestamp`, `UserID`, `TaskID`) VALUES
(36, 'hi', '2024-01-19 15:28:21', 19, 41),
(45, 'hi', '2024-01-23 17:13:20', 18, 43);

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE `file` (
  `FileID` int(11) NOT NULL,
  `FileName` varchar(255) NOT NULL,
  `FileType` varchar(255) DEFAULT NULL,
  `UploadDate` date NOT NULL,
  `FileURL` varchar(255) NOT NULL,
  `UserID` int(11) NOT NULL,
  `TaskID` int(11) NOT NULL,
  `ProjectID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `file`
--

INSERT INTO `file` (`FileID`, `FileName`, `FileType`, `UploadDate`, `FileURL`, `UserID`, `TaskID`, `ProjectID`) VALUES
(75, 'AIproject.txt_65b059d228fc5.txt', 'text/plain', '2024-01-24', '', 19, 41, 33);

-- --------------------------------------------------------

--
-- Table structure for table `invitation`
--

CREATE TABLE `invitation` (
  `InvitationID` int(11) NOT NULL,
  `SenderUserID` int(11) NOT NULL,
  `ReceiverUserID` int(11) NOT NULL,
  `ProjectID` int(11) NOT NULL,
  `Status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `invitation`
--

INSERT INTO `invitation` (`InvitationID`, `SenderUserID`, `ReceiverUserID`, `ProjectID`, `Status`) VALUES
(16, 19, 19, 33, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `ProjectID` int(11) NOT NULL,
  `ProjectName` varchar(255) NOT NULL,
  `Description` text DEFAULT NULL,
  `StartDate` date NOT NULL,
  `EndDate` date DEFAULT NULL,
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`ProjectID`, `ProjectName`, `Description`, `StartDate`, `EndDate`, `UserID`) VALUES
(33, 'Project1', 'testing project1', '2024-01-19', '2024-01-19', 19),
(36, 'p2', 'p', '2024-01-23', '2024-01-23', 19),
(57, 'cacw', 'sca', '2024-01-24', '2024-01-24', 19),
(58, 'ttet', 'evv', '2024-01-24', '2024-01-24', 19),
(59, 'dew', 'dwe', '2024-01-24', '2024-01-24', 19);

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `TaskID` int(11) NOT NULL,
  `TaskName` varchar(255) NOT NULL,
  `Description` text DEFAULT NULL,
  `DueDate` date DEFAULT NULL,
  `Priority` varchar(255) DEFAULT NULL,
  `Status` varchar(255) DEFAULT NULL,
  `UserID` int(11) NOT NULL,
  `ProjectID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`TaskID`, `TaskName`, `Description`, `DueDate`, `Priority`, `Status`, `UserID`, `ProjectID`) VALUES
(41, 'task1', 'testing task1', '2024-01-19', 'medium', 'started', 19, 33),
(43, 't', 't', '2024-01-23', '', '', 19, 36);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `FirstName` varchar(255) NOT NULL,
  `LastName` varchar(255) NOT NULL,
  `Role` varchar(255) DEFAULT NULL,
  `PFPName` varchar(255) NOT NULL,
  `PFPNameOriginal` varchar(255) NOT NULL,
  `PFPSize` varchar(255) NOT NULL,
  `PFPType` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `Username`, `Email`, `Password`, `FirstName`, `LastName`, `Role`, `PFPName`, `PFPNameOriginal`, `PFPSize`, `PFPType`) VALUES
(18, 'motest', 'mo@mo.com', '$2y$10$LYE9tBOl8mzkCfWVgBCLru0c0T1aGG8VhYv/lPbvTIx8H9ZbJbHUm', 'mo', 'test1', NULL, '658076d0430e2.png', 'IMG-20230417-WA0000.png', '597101', 'png'),
(19, 'mo', 'mo@mo.com', '$2y$10$Reeao54ZstMim5Jq6ngcw.H37unL5v4M5wgbQVz6NCm4LrKO283GG', 'mo', 'mo1', NULL, '65aa8afad670a.png', '658076d0430e2.png', '597101', 'png'),
(20, 'j', 'j@j.com', '$2y$10$Y3JOMe1doIRB5i6TeUxszeI.t9Hno.4TicIdklDRxwosI5oXDCdym', 'j', 'j', NULL, '65b061e0dcfca.png', 'MicrosoftTeams-image (1).png', '644537', 'png');

-- --------------------------------------------------------

--
-- Table structure for table `user_project`
--

CREATE TABLE `user_project` (
  `UserProjectID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ProjectID` int(11) NOT NULL,
  `Role` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_project`
--

INSERT INTO `user_project` (`UserProjectID`, `UserID`, `ProjectID`, `Role`) VALUES
(3, 18, 36, NULL),
(4, 18, 33, NULL),
(8, 18, 57, NULL),
(9, 18, 58, NULL),
(10, 18, 59, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_project_role`
--

CREATE TABLE `user_project_role` (
  `UserProjectRoleID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ProjectID` int(11) NOT NULL,
  `Role` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`CommentID`),
  ADD KEY `FK_Comment_Task` (`TaskID`),
  ADD KEY `FK_Comment_User` (`UserID`);

--
-- Indexes for table `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`FileID`),
  ADD KEY `FK_File_User` (`UserID`),
  ADD KEY `FK_File_Task` (`TaskID`),
  ADD KEY `FK_File_Project` (`ProjectID`);

--
-- Indexes for table `invitation`
--
ALTER TABLE `invitation`
  ADD PRIMARY KEY (`InvitationID`),
  ADD KEY `SenderUserID` (`SenderUserID`),
  ADD KEY `ReceiverUserID` (`ReceiverUserID`),
  ADD KEY `ProjectID` (`ProjectID`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`ProjectID`),
  ADD KEY `FK_Project_User` (`UserID`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`TaskID`),
  ADD KEY `FK_Task_User` (`UserID`),
  ADD KEY `FK_Task_Project` (`ProjectID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`);

--
-- Indexes for table `user_project`
--
ALTER TABLE `user_project`
  ADD PRIMARY KEY (`UserProjectID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `FK_UserProject_Project` (`ProjectID`);

--
-- Indexes for table `user_project_role`
--
ALTER TABLE `user_project_role`
  ADD PRIMARY KEY (`UserProjectRoleID`),
  ADD KEY `FK_UserProjectRole_User` (`UserID`),
  ADD KEY `FK_UserProjectRole_Project` (`ProjectID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `CommentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `file`
--
ALTER TABLE `file`
  MODIFY `FileID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `invitation`
--
ALTER TABLE `invitation`
  MODIFY `InvitationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `ProjectID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `TaskID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `user_project`
--
ALTER TABLE `user_project`
  MODIFY `UserProjectID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_project_role`
--
ALTER TABLE `user_project_role`
  MODIFY `UserProjectRoleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_Comment_Task` FOREIGN KEY (`TaskID`) REFERENCES `task` (`TaskID`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_Comment_User` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `file`
--
ALTER TABLE `file`
  ADD CONSTRAINT `FK_File_Project` FOREIGN KEY (`ProjectID`) REFERENCES `project` (`ProjectID`),
  ADD CONSTRAINT `FK_File_Task` FOREIGN KEY (`TaskID`) REFERENCES `task` (`TaskID`),
  ADD CONSTRAINT `FK_File_User` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `invitation`
--
ALTER TABLE `invitation`
  ADD CONSTRAINT `invitation_ibfk_1` FOREIGN KEY (`SenderUserID`) REFERENCES `user` (`UserID`),
  ADD CONSTRAINT `invitation_ibfk_2` FOREIGN KEY (`ReceiverUserID`) REFERENCES `user` (`UserID`),
  ADD CONSTRAINT `invitation_ibfk_3` FOREIGN KEY (`ProjectID`) REFERENCES `project` (`ProjectID`);

--
-- Constraints for table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `FK_Project_User` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `FK_Task_Project` FOREIGN KEY (`ProjectID`) REFERENCES `project` (`ProjectID`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_Task_User` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `user_project`
--
ALTER TABLE `user_project`
  ADD CONSTRAINT `FK_UserProject_Project` FOREIGN KEY (`ProjectID`) REFERENCES `project` (`ProjectID`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_project_ibfk_1` FOREIGN KEY (`ProjectID`) REFERENCES `project` (`ProjectID`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_project_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `user_project_role`
--
ALTER TABLE `user_project_role`
  ADD CONSTRAINT `FK_UserProjectRole_Project` FOREIGN KEY (`ProjectID`) REFERENCES `project` (`ProjectID`),
  ADD CONSTRAINT `FK_UserProjectRole_User` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
