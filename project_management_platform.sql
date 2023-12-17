-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2023 at 06:10 PM
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

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE `file` (
  `FileID` int(11) NOT NULL,
  `FileName` varchar(255) NOT NULL,
  `FileType` varchar(255) DEFAULT NULL,
  `UploadDate` date NOT NULL,
  `UserID` int(11) NOT NULL,
  `TaskID` int(11) NOT NULL,
  `ProjectID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
  ADD KEY `FK_UserProject_User` (`UserID`),
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
  MODIFY `CommentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `file`
--
ALTER TABLE `file`
  MODIFY `FileID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `ProjectID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `TaskID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `user_project`
--
ALTER TABLE `user_project`
  MODIFY `UserProjectID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  ADD CONSTRAINT `FK_UserProject_Project` FOREIGN KEY (`ProjectID`) REFERENCES `project` (`ProjectID`),
  ADD CONSTRAINT `FK_UserProject_User` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

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
