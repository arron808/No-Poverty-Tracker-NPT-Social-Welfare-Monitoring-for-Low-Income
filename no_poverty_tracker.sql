-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 13, 2025 at 03:50 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `no_poverty_tracker`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateAssessment` (IN `p_household_id` INT, IN `p_assessment_date` DATE, IN `p_income_level` DECIMAL(10,2), IN `p_needs` TEXT, IN `p_recommendations` TEXT, IN `p_assessor_name` VARCHAR(100))   BEGIN
    INSERT INTO assessments (household_id, assessment_date, income_level, needs, recommendations, assessor_name)
    VALUES (p_household_id, p_assessment_date, p_income_level, p_needs, p_recommendations, p_assessor_name);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateEnrollment` (IN `p_individual_id` INT, IN `p_program_id` INT, IN `p_enrollment_date` DATE, IN `p_status` VARCHAR(50), IN `p_benefits_received` DECIMAL(10,2))   BEGIN
    INSERT INTO enrollments (individual_id, program_id, enrollment_date, status, benefits_received)
    VALUES (p_individual_id, p_program_id, p_enrollment_date, p_status, p_benefits_received);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateHousehold` (IN `p_head_name` VARCHAR(100), IN `p_address` TEXT, IN `p_region` VARCHAR(100), IN `p_registered_date` DATE)   BEGIN
    INSERT INTO households (head_name, address, region, registered_date)
    VALUES (p_head_name, p_address, p_region, p_registered_date);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateIndividual` (IN `p_household_id` INT, IN `p_name` VARCHAR(100), IN `p_dob` DATE, IN `p_gender` VARCHAR(10), IN `p_education_level` VARCHAR(50), IN `p_employment_status` VARCHAR(50), IN `p_disability` BOOLEAN)   BEGIN
    INSERT INTO individuals (household_id, name, dob, gender, education_level, employment_status, disability)
    VALUES (p_household_id, p_name, p_dob, p_gender, p_education_level, p_employment_status, p_disability);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateProgram` (IN `p_name` VARCHAR(100), IN `p_description` TEXT, IN `p_provider` VARCHAR(100), IN `p_eligibility_criteria` TEXT)   BEGIN
    INSERT INTO programs (name, description, provider, eligibility_criteria)
    VALUES (p_name, p_description, p_provider, p_eligibility_criteria);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteAssessment` (IN `p_assessment_id` INT)   BEGIN
    DELETE FROM assessments WHERE assessment_id = p_assessment_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteEnrollment` (IN `p_enrollment_id` INT)   BEGIN
    DELETE FROM enrollments WHERE enrollment_id = p_enrollment_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteHousehold` (IN `p_household_id` INT)   BEGIN
    DELETE FROM households WHERE household_id = p_household_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteIndividual` (IN `p_individual_id` INT)   BEGIN
    DELETE FROM individuals WHERE individual_id = p_individual_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteProgram` (IN `p_program_id` INT)   BEGIN
    DELETE FROM programs WHERE program_id = p_program_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetAllAssessments` ()   BEGIN
    SELECT * FROM assessments;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetAllEnrollments` ()   BEGIN
    SELECT * FROM enrollments;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetAllHouseholds` ()   BEGIN
    SELECT * FROM households;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetAllIndividuals` ()   BEGIN
    SELECT * FROM individuals;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetAllPrograms` ()   BEGIN
    SELECT * FROM programs;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateAssessment` (IN `p_assessment_id` INT, IN `p_income_level` DECIMAL(10,2), IN `p_needs` TEXT, IN `p_recommendations` TEXT)   BEGIN
    UPDATE assessments
    SET income_level = p_income_level,
        needs = p_needs,
        recommendations = p_recommendations
    WHERE assessment_id = p_assessment_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateEnrollment` (IN `p_enrollment_id` INT, IN `p_status` VARCHAR(50), IN `p_benefits_received` DECIMAL(10,2))   BEGIN
    UPDATE enrollments
    SET status = p_status,
        benefits_received = p_benefits_received
    WHERE enrollment_id = p_enrollment_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateHousehold` (IN `p_household_id` INT, IN `p_head_name` VARCHAR(100), IN `p_address` TEXT, IN `p_region` VARCHAR(100), IN `p_registered_date` DATE)   BEGIN
    UPDATE households
    SET head_name = p_head_name,
        address = p_address,
        region = p_region,
        registered_date = p_registered_date
    WHERE household_id = p_household_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateIndividual` (IN `p_individual_id` INT, IN `p_name` VARCHAR(100), IN `p_dob` DATE, IN `p_gender` VARCHAR(10), IN `p_education_level` VARCHAR(50), IN `p_employment_status` VARCHAR(50), IN `p_disability` BOOLEAN)   BEGIN
    UPDATE individuals
    SET name = p_name,
        dob = p_dob,
        gender = p_gender,
        education_level = p_education_level,
        employment_status = p_employment_status,
        disability = p_disability
    WHERE individual_id = p_individual_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateProgram` (IN `p_program_id` INT, IN `p_name` VARCHAR(100), IN `p_description` TEXT, IN `p_provider` VARCHAR(100), IN `p_eligibility_criteria` TEXT)   BEGIN
    UPDATE programs
    SET name = p_name,
        description = p_description,
        provider = p_provider,
        eligibility_criteria = p_eligibility_criteria
    WHERE program_id = p_program_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `assessments`
--

CREATE TABLE `assessments` (
  `assessment_id` int(11) NOT NULL,
  `household_id` int(11) DEFAULT NULL,
  `assessment_date` date DEFAULT NULL,
  `income_level` decimal(10,2) DEFAULT NULL,
  `needs` text DEFAULT NULL,
  `recommendations` text DEFAULT NULL,
  `assessor_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `enrollment_id` int(11) NOT NULL,
  `individual_id` int(11) DEFAULT NULL,
  `program_id` int(11) DEFAULT NULL,
  `enrollment_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `benefits_received` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `households`
--

CREATE TABLE `households` (
  `household_id` int(11) NOT NULL,
  `head_name` varchar(100) NOT NULL,
  `address` text DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `registered_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `households`
--

INSERT INTO `households` (`household_id`, `head_name`, `address`, `region`, `registered_date`) VALUES
(1, 'Arron', 'lipa ', 'calabarzon', '2025-04-13'),
(2, '1', '1', '1', '2025-04-13'),
(3, 'CHARLES', 'lipa ', 'calabarzon', '2025-04-13');

-- --------------------------------------------------------

--
-- Table structure for table `individuals`
--

CREATE TABLE `individuals` (
  `individual_id` int(11) NOT NULL,
  `household_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `education_level` varchar(50) DEFAULT NULL,
  `employment_status` varchar(50) DEFAULT NULL,
  `disability` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `individuals`
--

INSERT INTO `individuals` (`individual_id`, `household_id`, `name`, `dob`, `gender`, `education_level`, `employment_status`, `disability`) VALUES
(2, 1, 'Ehrin', '2025-04-13', 'Male', 'College', 'n/a', 0),
(3, 2, '2', '2025-04-02', 'Male', '2', '2', 0),
(4, 2, '2', '2025-04-02', 'Male', '2', '2', 0),
(5, 2, '1', '2025-04-13', 'Male', '1', '1', 1),
(6, 2, 'Edlex', '2025-04-13', 'Male', '1', '1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `program_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `provider` varchar(100) DEFAULT NULL,
  `eligibility_criteria` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`program_id`, `name`, `description`, `provider`, `eligibility_criteria`) VALUES
(1, 'Edlex', 'Tag palakpak', 'asd', 'sad'),
(2, '1', '1', '1', NULL),
(3, '1', '11', '1', '1'),
(4, '1', '11', '1', '1'),
(5, 'jsdhas', 'asdasdas', 'asd', 'sad'),
(6, 'Ehrin', 'Cash Assistance ', 'Gov', 'ff');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assessments`
--
ALTER TABLE `assessments`
  ADD PRIMARY KEY (`assessment_id`),
  ADD KEY `household_id` (`household_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`enrollment_id`),
  ADD KEY `individual_id` (`individual_id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `households`
--
ALTER TABLE `households`
  ADD PRIMARY KEY (`household_id`);

--
-- Indexes for table `individuals`
--
ALTER TABLE `individuals`
  ADD PRIMARY KEY (`individual_id`),
  ADD KEY `household_id` (`household_id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`program_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assessments`
--
ALTER TABLE `assessments`
  MODIFY `assessment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `enrollment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `households`
--
ALTER TABLE `households`
  MODIFY `household_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `individuals`
--
ALTER TABLE `individuals`
  MODIFY `individual_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `program_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assessments`
--
ALTER TABLE `assessments`
  ADD CONSTRAINT `assessments_ibfk_1` FOREIGN KEY (`household_id`) REFERENCES `households` (`household_id`) ON DELETE CASCADE;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`individual_id`) REFERENCES `individuals` (`individual_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`program_id`) REFERENCES `programs` (`program_id`) ON DELETE CASCADE;

--
-- Constraints for table `individuals`
--
ALTER TABLE `individuals`
  ADD CONSTRAINT `individuals_ibfk_1` FOREIGN KEY (`household_id`) REFERENCES `households` (`household_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
