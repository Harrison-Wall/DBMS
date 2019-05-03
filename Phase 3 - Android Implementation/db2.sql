-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2019 at 07:32 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db2`
--

-- --------------------------------------------------------

--
-- Table structure for table `assign`
--

CREATE TABLE `assign` (
  `sec_id` int(11) NOT NULL,
  `ses_id` int(11) NOT NULL,
  `moderator_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `assign`
--

INSERT INTO `assign` (`sec_id`, `ses_id`, `moderator_id`, `material_id`) VALUES
(2, 1, 5, 1),
(2, 1, 5, 11),
(2, 2, 5, 2),
(4, 3, 7, 3),
(4, 4, 7, 4),
(5, 5, 8, 5),
(5, 6, 8, 5),
(8, 7, 10, 7),
(8, 8, 10, 8),
(9, 9, 11, 9),
(9, 10, 11, 10);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `c_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `mentor_grade_req` int(11) NOT NULL,
  `mentee_grade_req` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`c_id`, `title`, `description`, `mentor_grade_req`, `mentee_grade_req`) VALUES
(1, 'Database 1', 'A first course in databases.', 4, 3),
(2, 'Database 2', 'A second course in databases.', 4, 3),
(3, 'Operating Systems', 'A deep dive into OS.', 3, 2),
(4, 'Computer Architecture', 'Learn the inner workings of computers.', 3, 2),
(5, 'Computing 1', 'Begin the path of Computer Science', 2, 1),
(6, 'Computing 2', 'Continue with advanced C techniques.', 2, 1),
(7, 'Calculus 1', 'Measure under a curve.', 2, 1),
(8, 'Mobile App 1', 'Learn how to develop native android applications.', 4, 3),
(9, 'Foundations of Computer Science', 'Understand the origins of Comp Sci.', 3, 2),
(10, 'College Writing 1', 'But words into sentences.', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `enroll`
--

CREATE TABLE `enroll` (
  `sec_id` int(11) NOT NULL,
  `mentee_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `enroll`
--

INSERT INTO `enroll` (`sec_id`, `mentee_id`) VALUES
(2, 20),
(2, 29),
(2, 30),
(2, 40),
(4, 20),
(4, 21),
(4, 22),
(4, 34),
(4, 40),
(5, 18),
(5, 21),
(5, 22),
(5, 29),
(5, 30),
(5, 34),
(5, 40),
(7, 18),
(7, 35),
(7, 40);

-- --------------------------------------------------------

--
-- Table structure for table `material`
--

CREATE TABLE `material` (
  `material_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `assigned_date` date NOT NULL,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `material`
--

INSERT INTO `material` (`material_id`, `title`, `author`, `type`, `url`, `assigned_date`, `notes`) VALUES
(1, 'PHP and MySQL', 'Richard Bachman', 'Book', 'N/A', '2019-03-22', 'Read only chapters 2 and 3.'),
(2, 'Building Web Apps', 'Kyle Demeter', 'Article', 'www.website.com/WebApp', '2019-01-28', 'N/A'),
(3, 'Linux Pocket Guide', 'Linus Torvald', 'Book', 'N/A', '2019-01-29', 'Review common commands.'),
(4, 'Introduction to Operating Systems', 'Timothy Oses', 'Book', 'N/A', '2019-01-29', 'Read chapter 1.'),
(5, 'Bitwise foundations', 'William Bills', 'Article', 'www.uml.edu/~Bills/bwf.pdf', '2019-01-30', 'Read carefully'),
(6, 'Absolue C++', 'Jimothy James', 'Book', 'N/A', '2019-01-31', 'Read chapter 2.'),
(7, 'C++ for dummies.', 'Peter Brast', 'Handout', 'N/A', '2019-01-31', 'N/A'),
(8, 'C++ for smarties', 'Richard Brast', 'Handout', 'N/A', '2019-02-07', 'Read last.'),
(9, 'Pre-calculus', 'Liz Mattis', 'Book', 'www.LizMat.com', '2019-02-01', 'Review chapters 1-12.'),
(10, 'Calculus', 'Liz Mattiz', 'Book', 'www.LizMat.com', '2019-02-08', 'Read chapter 1.'),
(11, 'title', 'aauthor', 'type', 'kjklj', '2019-01-31', '');

-- --------------------------------------------------------

--
-- Table structure for table `mentees`
--

CREATE TABLE `mentees` (
  `mentee_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mentees`
--

INSERT INTO `mentees` (`mentee_id`) VALUES
(18),
(19),
(20),
(21),
(22),
(28),
(29),
(30),
(33),
(34),
(35),
(40);

-- --------------------------------------------------------

--
-- Table structure for table `mentors`
--

CREATE TABLE `mentors` (
  `mentor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mentors`
--

INSERT INTO `mentors` (`mentor_id`) VALUES
(23),
(24),
(25),
(26),
(27),
(28),
(29),
(30),
(33),
(34),
(35);

-- --------------------------------------------------------

--
-- Table structure for table `moderate`
--

CREATE TABLE `moderate` (
  `sec_id` int(11) NOT NULL,
  `moderator_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `moderate`
--

INSERT INTO `moderate` (`sec_id`, `moderator_id`) VALUES
(1, 4),
(2, 4),
(3, 6),
(4, 7),
(5, 8),
(6, 9),
(7, 10),
(8, 11),
(9, 12),
(10, 13);

-- --------------------------------------------------------

--
-- Table structure for table `moderators`
--

CREATE TABLE `moderators` (
  `moderator_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `moderators`
--

INSERT INTO `moderators` (`moderator_id`) VALUES
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12),
(13);

-- --------------------------------------------------------

--
-- Table structure for table `parenting`
--

CREATE TABLE `parenting` (
  `parent_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `parenting`
--

INSERT INTO `parenting` (`parent_id`, `student_id`) VALUES
(4, 18),
(4, 40),
(5, 19),
(6, 20),
(7, 21),
(8, 22),
(9, 23),
(10, 24),
(11, 25),
(12, 26),
(13, 27),
(14, 28),
(15, 29),
(16, 30),
(17, 33),
(31, 34),
(32, 35);

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `parent_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`parent_id`) VALUES
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12),
(13),
(14),
(15),
(16),
(17),
(31),
(32);

-- --------------------------------------------------------

--
-- Table structure for table `participate`
--

CREATE TABLE `participate` (
  `student_id` int(11) NOT NULL,
  `sec_id` int(11) NOT NULL,
  `ses_id` int(11) NOT NULL,
  `participate` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `participate`
--

INSERT INTO `participate` (`student_id`, `sec_id`, `ses_id`, `participate`) VALUES
(18, 9, 9, 1),
(19, 8, 7, 1),
(19, 9, 9, 1),
(20, 4, 3, 1),
(21, 4, 3, 1),
(21, 5, 5, 1),
(22, 4, 3, 1),
(22, 5, 5, 1),
(28, 2, 1, 1),
(29, 2, 1, 1),
(29, 5, 5, 1),
(30, 2, 1, 1),
(30, 5, 5, 1),
(33, 2, 1, 1),
(34, 4, 3, 1),
(34, 5, 5, 1),
(35, 2, 1, 1),
(35, 2, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `moderator_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`moderator_id`, `material_id`) VALUES
(5, 1),
(5, 2),
(5, 11),
(7, 3),
(7, 4),
(8, 5),
(11, 6),
(11, 7),
(11, 8),
(12, 9),
(12, 10);

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `sec_id` int(11) NOT NULL,
  `sec_name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `time_slot_id` int(11) NOT NULL,
  `capacity` int(11) NOT NULL,
  `c_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`sec_id`, `sec_name`, `start_date`, `end_date`, `time_slot_id`, `capacity`, `c_id`) VALUES
(1, 'DataBase_Mon', '2018-09-02', '2018-12-17', 1, 9, 1),
(2, 'DataBase2_Mon', '2019-01-21', '2019-06-06', 2, 9, 2),
(3, 'OS_Tue', '2018-09-04', '2018-12-05', 3, 9, 3),
(4, 'ComputerArchitecture_Tue', '2019-01-22', '2019-06-07', 4, 9, 4),
(5, 'Computing_Wed', '2019-01-23', '2019-06-01', 5, 9, 5),
(6, 'Comp2_Wed', '2017-01-18', '2017-06-03', 6, 9, 6),
(7, 'Calc_Thu', '2019-09-06', '2019-12-13', 7, 9, 7),
(8, 'Mobile_Thu', '2019-01-24', '2019-06-02', 8, 9, 8),
(9, 'Foundations_Fri', '2019-01-25', '2019-06-03', 9, 9, 9),
(10, 'CollegeWriting_Fri', '2017-09-01', '2017-12-15', 10, 9, 10);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `ses_id` int(11) NOT NULL,
  `sec_id` int(11) NOT NULL,
  `ses_name` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `announcement` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`ses_id`, `sec_id`, `ses_name`, `date`, `announcement`) VALUES
(1, 2, 'DB2_1', '2019-04-01', 'Our first class.'),
(2, 2, 'DB2_2', '2019-01-28', 'Project details.'),
(3, 4, 'OS_1', '2019-01-22', 'Introduction to OS.'),
(4, 4, 'OS_2', '2019-01-29', 'Linux overview.'),
(5, 5, 'CA_1', '2019-01-23', 'Intro to architecture.'),
(6, 5, 'CA_2', '2019-01-30', 'Second class.'),
(7, 8, 'COMP2_1', '2019-01-24', 'C++ overview.'),
(8, 8, 'COMP2_2', '2019-01-31', 'Classes in C++'),
(9, 9, 'CALC_1', '2019-01-25', 'What is a derivative.'),
(10, 9, 'CALC_2', '2019-02-01', 'Measuring under the curve.');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `grade` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `grade`) VALUES
(18, 1),
(19, 1),
(20, 3),
(21, 2),
(22, 2),
(23, 2),
(24, 3),
(25, 3),
(26, 3),
(27, 4),
(28, 4),
(29, 4),
(30, 3),
(33, 4),
(34, 2),
(35, 4),
(40, 3);

-- --------------------------------------------------------

--
-- Table structure for table `teach`
--

CREATE TABLE `teach` (
  `sec_id` int(11) NOT NULL,
  `mentor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `teach`
--

INSERT INTO `teach` (`sec_id`, `mentor_id`) VALUES
(2, 28),
(2, 33),
(2, 35),
(4, 26),
(4, 27),
(4, 30),
(5, 24),
(5, 26),
(5, 33),
(7, 26),
(8, 29),
(8, 35),
(9, 29);

-- --------------------------------------------------------

--
-- Table structure for table `time_slot`
--

CREATE TABLE `time_slot` (
  `time_slot_id` int(11) NOT NULL,
  `day_of_the_week` varchar(255) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `time_slot`
--

INSERT INTO `time_slot` (`time_slot_id`, `day_of_the_week`, `start_time`, `end_time`) VALUES
(1, 'Monday', '11:15:00', '12:30:00'),
(2, 'Monday', '02:00:00', '03:45:00'),
(3, 'Tuesday', '10:00:00', '10:50:00'),
(4, 'Tuesday', '05:30:00', '08:45:00'),
(5, 'Wednesday', '11:50:00', '12:30:00'),
(6, 'Wednesday', '12:45:00', '02:00:00'),
(7, 'Thursday', '11:00:00', '12:15:00'),
(8, 'Thursday', '01:45:00', '03:00:00'),
(9, 'Friday', '09:30:00', '10:45:00'),
(10, 'Friday', '11:00:00', '12:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `name`, `phone`, `city`, `state`) VALUES
(4, 'aParent@gmail.com', '1111', 'Alfred Rent', '978-555-5555', 'Lowell', 'Massachusetts'),
(5, 'bParent@gmail.com', '1112', 'Barbara Modero', '603-555-5555', 'Cambridge', 'Massachusetts'),
(6, 'cParent@yahoo.com', '1113', 'Charles Darles', '708-555-5555', 'Seabrook', 'New Hampshire'),
(7, 'dParent@gmail.com', '1114', 'Darla Barlent', '978-555-5555', 'Amesbury', 'Massachusetts'),
(8, 'eParent@gmail.com', '1115', 'Emil Watson', '387-555-5555', 'Lowell', 'Massachusetts'),
(9, 'fParent@gmail.com', '1116', 'Faye Penta', '212-555-5555', 'Nashua', 'New Hampshire'),
(10, 'gParent@gmail.com', '1117', 'George DeSande', '403-555-5555', 'Scranton', 'New Hampshire'),
(11, 'hParent@gmail.com', '1118', 'Hiro Stu', '388-555-5555', 'Jamestown', 'New Hampshire'),
(12, 'iParent@gmail.com', '1119', 'Irene Lemonde', '753-555-5555', 'Concord', 'Massachusetts'),
(13, 'jParent@gmail.com', '1121', 'Joshua Johnson', '908-555-5555', 'Lexington', 'Massachusetts'),
(14, 'kParent@gmail.com', '1122', 'Karen Layton', '345-555-5555', 'Keene', 'New Hampshire'),
(15, 'lParent@gmail.com', '1123', 'Larry Carrie', '123-555-5555', 'Haverhill', 'Massachusetts'),
(16, 'mParent@gmail.com', '1124', 'Milton Post', '455-555-5555', 'Lawrence', 'Massachusetts'),
(17, 'nParent@gmail.com', '1125', 'Nathanial Great', '988-555-5555', 'Andover', 'Massachusetts'),
(18, 'aStudnet@yahoo.com', '9999', 'Arthur Rent', '356-555-5555', 'Lowell', 'Massachusetts'),
(19, 'bStudnet@yahoo.com', '9998', 'Bethany Modero', '763-555-5555', 'Methuen', 'Massachusetts'),
(20, 'cStudnet@yahoo.com', '9997', 'Cameron Dent', '257-555-5555', 'Salisbury', 'Massachusetts'),
(21, 'dStudnet@yahoo.com', '9996', 'Damien Barlent', '245-555-5555', 'Seabrook', 'New Hampshire'),
(22, 'eStudnet@yahoo.com', '9995', 'Emily Watson', '359-555-5555', 'Newbury', 'Massachusetts'),
(23, 'fStudnet@yahoo.com', '9994', 'Frank Penta', '195-555-5555', 'Byfield', 'Massachusetts'),
(24, 'gStudnet@yahoo.com', '9993', 'Georgina DeSane', '438-555-5555', 'Lowell', 'Massachusetts'),
(25, 'hStudnet@yahoo.com', '9992', 'Halie Stu', '324-555-5555', 'Nashua', 'New Hampshire'),
(26, 'iStudnet@yahoo.com', '9991', 'Ira Lemonde', '234-555-5555', 'Keene', 'New Hampshire'),
(27, 'jStudnet@yahoo.com', '9989', 'James Johnson', '690-555-5555', 'Haverhill', 'Massachusetts'),
(28, 'kStudnet@yahoo.com', '9988', 'Kristoff Layton', '674-555-5555', 'Lawrence', 'Massachusetts'),
(29, 'lStudnet@yahoo.com', '9987', 'Lindsay Carrie', '135-555-5555', 'Lowell', 'Massachusetts'),
(30, 'mStudnet@yahoo.com', '9986', 'Mary Post', '296-555-5555', 'Seabrook', 'New Hampshire'),
(31, 'oParent@gmail.com', '1126', 'Olieda Rincet', '986-555-5555', 'Wouburn', 'Massachusetts'),
(32, 'pParent@gmail.com', '1127', 'Paula Peters', '933-555-5555', 'Salem', 'New Hampshire'),
(33, 'nStudent@gmail.com', '9985', 'Naomi Great', '153-555-5555', 'Andover', 'Massachusetts'),
(34, 'oStudent@gmail.com', '9984', 'Oliver Rincet', '927-555-555', 'Woburn', 'Massachusetts'),
(35, 'pStudent@gmail.com', '9983', 'Paul Peters', '129-555-5555', 'Salem', 'New Hampshire'),
(40, 'test@email.com', '1234', 'bobert robert', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assign`
--
ALTER TABLE `assign`
  ADD PRIMARY KEY (`sec_id`,`ses_id`,`moderator_id`,`material_id`),
  ADD KEY `assign_material` (`material_id`),
  ADD KEY `assign_moderator` (`moderator_id`),
  ADD KEY `assign_session` (`ses_id`,`sec_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `enroll`
--
ALTER TABLE `enroll`
  ADD PRIMARY KEY (`sec_id`,`mentee_id`),
  ADD KEY `enroll_mentee` (`mentee_id`);

--
-- Indexes for table `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`material_id`);

--
-- Indexes for table `mentees`
--
ALTER TABLE `mentees`
  ADD PRIMARY KEY (`mentee_id`);

--
-- Indexes for table `mentors`
--
ALTER TABLE `mentors`
  ADD PRIMARY KEY (`mentor_id`);

--
-- Indexes for table `moderate`
--
ALTER TABLE `moderate`
  ADD PRIMARY KEY (`sec_id`,`moderator_id`),
  ADD KEY `moderate_moderator` (`moderator_id`);

--
-- Indexes for table `moderators`
--
ALTER TABLE `moderators`
  ADD PRIMARY KEY (`moderator_id`);

--
-- Indexes for table `parenting`
--
ALTER TABLE `parenting`
  ADD PRIMARY KEY (`parent_id`,`student_id`),
  ADD KEY `parenting_student` (`student_id`);

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`parent_id`);

--
-- Indexes for table `participate`
--
ALTER TABLE `participate`
  ADD PRIMARY KEY (`student_id`,`sec_id`,`ses_id`),
  ADD KEY `paticipate_session` (`sec_id`,`ses_id`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`moderator_id`,`material_id`),
  ADD KEY `post_material` (`material_id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`sec_id`),
  ADD KEY `section_course` (`c_id`),
  ADD KEY `section_timeslot` (`time_slot_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`ses_id`,`sec_id`),
  ADD KEY `session_section` (`sec_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `teach`
--
ALTER TABLE `teach`
  ADD PRIMARY KEY (`sec_id`,`mentor_id`),
  ADD KEY `teach_mentor` (`mentor_id`);

--
-- Indexes for table `time_slot`
--
ALTER TABLE `time_slot`
  ADD PRIMARY KEY (`time_slot_id`);

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
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `material`
--
ALTER TABLE `material`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `sec_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `ses_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `time_slot`
--
ALTER TABLE `time_slot`
  MODIFY `time_slot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assign`
--
ALTER TABLE `assign`
  ADD CONSTRAINT `assign_material` FOREIGN KEY (`material_id`) REFERENCES `material` (`material_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assign_moderator` FOREIGN KEY (`moderator_id`) REFERENCES `moderators` (`moderator_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assign_session` FOREIGN KEY (`ses_id`,`sec_id`) REFERENCES `sessions` (`ses_id`, `sec_id`) ON DELETE CASCADE;

--
-- Constraints for table `enroll`
--
ALTER TABLE `enroll`
  ADD CONSTRAINT `enroll_mentee` FOREIGN KEY (`mentee_id`) REFERENCES `mentees` (`mentee_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enroll_section` FOREIGN KEY (`sec_id`) REFERENCES `sections` (`sec_id`) ON DELETE CASCADE;

--
-- Constraints for table `mentees`
--
ALTER TABLE `mentees`
  ADD CONSTRAINT `mentee_student` FOREIGN KEY (`mentee_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `mentors`
--
ALTER TABLE `mentors`
  ADD CONSTRAINT `mentor_student` FOREIGN KEY (`mentor_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `moderate`
--
ALTER TABLE `moderate`
  ADD CONSTRAINT `moderate_moderator` FOREIGN KEY (`moderator_id`) REFERENCES `moderators` (`moderator_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `moderate_section` FOREIGN KEY (`sec_id`) REFERENCES `sections` (`sec_id`) ON DELETE CASCADE;

--
-- Constraints for table `moderators`
--
ALTER TABLE `moderators`
  ADD CONSTRAINT `moderator_parents` FOREIGN KEY (`moderator_id`) REFERENCES `parents` (`parent_id`) ON DELETE CASCADE;

--
-- Constraints for table `parenting`
--
ALTER TABLE `parenting`
  ADD CONSTRAINT `parenting_parent` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`parent_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `parenting_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `parents`
--
ALTER TABLE `parents`
  ADD CONSTRAINT `parent_user` FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `participate`
--
ALTER TABLE `participate`
  ADD CONSTRAINT `paticipate_session` FOREIGN KEY (`sec_id`,`ses_id`) REFERENCES `sessions` (`sec_id`, `ses_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `paticipate_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_material` FOREIGN KEY (`material_id`) REFERENCES `material` (`material_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_moderator` FOREIGN KEY (`moderator_id`) REFERENCES `moderators` (`moderator_id`) ON DELETE CASCADE;

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `section_course` FOREIGN KEY (`c_id`) REFERENCES `courses` (`c_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `section_timeslot` FOREIGN KEY (`time_slot_id`) REFERENCES `time_slot` (`time_slot_id`) ON DELETE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `session_section` FOREIGN KEY (`sec_id`) REFERENCES `sections` (`sec_id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `student_user` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teach`
--
ALTER TABLE `teach`
  ADD CONSTRAINT `teach_mentor` FOREIGN KEY (`mentor_id`) REFERENCES `mentors` (`mentor_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teach_section` FOREIGN KEY (`sec_id`) REFERENCES `sections` (`sec_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
