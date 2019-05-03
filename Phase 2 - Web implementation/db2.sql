/*
* Harrison Wall
* COMP 3100 - Database 2
* Phase 2 - SQL commands
*/

--- Create statements based on phase one answer provided
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for assign
-- ----------------------------
DROP TABLE IF EXISTS `assign`;
CREATE TABLE `assign` (
  `sec_id` int(11) NOT NULL,
  `ses_id` int(11) NOT NULL,
  `moderator_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  PRIMARY KEY (`sec_id`,`ses_id`,`moderator_id`,`material_id`),
  CONSTRAINT `assign_material` FOREIGN KEY (`material_id`) REFERENCES `material` (`material_id`) ON DELETE CASCADE,
  CONSTRAINT `assign_moderator` FOREIGN KEY (`moderator_id`) REFERENCES `moderators` (`moderator_id`) ON DELETE CASCADE,
  CONSTRAINT `assign_session` FOREIGN KEY (`ses_id`, `sec_id`) REFERENCES `sessions` (`ses_id`, `sec_id`) ON DELETE CASCADE
) DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for courses
-- ----------------------------
DROP TABLE IF EXISTS `courses`;
CREATE TABLE `courses` (
  `c_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `mentor_grade_req` int(11) NOT NULL,
  `mentee_grade_req` int(11) NOT NULL,
  PRIMARY KEY (`c_id`)
) DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for enroll
-- ----------------------------
DROP TABLE IF EXISTS `enroll`;
CREATE TABLE `enroll` (
  `sec_id` int(11) NOT NULL,
  `mentee_id` int(11) NOT NULL,
  PRIMARY KEY (`sec_id`,`mentee_id`),
  CONSTRAINT `enroll_mentee` FOREIGN KEY (`mentee_id`) REFERENCES `mentees` (`mentee_id`) ON DELETE CASCADE,
  CONSTRAINT `enroll_section` FOREIGN KEY (`sec_id`) REFERENCES `sections` (`sec_id`) ON DELETE CASCADE
) DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for material
-- ----------------------------
DROP TABLE IF EXISTS `material`;
CREATE TABLE `material` (
  `material_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `assigned_date` date NOT NULL,
  `notes` text,
  PRIMARY KEY (`material_id`)
) DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mentees
-- ----------------------------
DROP TABLE IF EXISTS `mentees`;
CREATE TABLE `mentees` (
  `mentee_id` int(11) NOT NULL,
  PRIMARY KEY (`mentee_id`),
  CONSTRAINT `mentee_student` FOREIGN KEY (`mentee_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE
) DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mentors
-- ----------------------------
DROP TABLE IF EXISTS `mentors`;
CREATE TABLE `mentors` (
  `mentor_id` int(11) NOT NULL,
  PRIMARY KEY (`mentor_id`),
  CONSTRAINT `mentor_student` FOREIGN KEY (`mentor_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE
) DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for moderate
-- ----------------------------
DROP TABLE IF EXISTS `moderate`;
CREATE TABLE `moderate` (
  `sec_id` int(11) NOT NULL,
  `moderator_id` int(11) NOT NULL,
  PRIMARY KEY (`sec_id`,`moderator_id`),
  CONSTRAINT `moderate_moderator` FOREIGN KEY (`moderator_id`) REFERENCES `moderators` (`moderator_id`) ON DELETE CASCADE,
  CONSTRAINT `moderate_section` FOREIGN KEY (`sec_id`) REFERENCES `sections` (`sec_id`) ON DELETE CASCADE
) DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for moderator
-- ----------------------------
DROP TABLE IF EXISTS `moderators`;
CREATE TABLE `moderators` (
  `moderator_id` int(11) NOT NULL,
  PRIMARY KEY (`moderator_id`),
  CONSTRAINT `moderator_parents` FOREIGN KEY (`moderator_id`) REFERENCES `parents` (`parent_id`) ON DELETE CASCADE
) DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for parenting
-- ----------------------------
DROP TABLE IF EXISTS `parenting`;
CREATE TABLE `parenting` (
  `parent_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  PRIMARY KEY (`parent_id`,`student_id`),
  CONSTRAINT `parenting_parent` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`parent_id`) ON DELETE CASCADE,
  CONSTRAINT `parenting_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE
) DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for parents
-- ----------------------------
DROP TABLE IF EXISTS `parents`;
CREATE TABLE `parents` (
  `parent_id` int(11) NOT NULL,
  PRIMARY KEY (`parent_id`),
  CONSTRAINT `parent_user` FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`)
) DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for participate
-- ----------------------------
DROP TABLE IF EXISTS `participate`;
CREATE TABLE `participate` (
  `student_id` int(11) NOT NULL,
  `sec_id` int(11) NOT NULL,
  `ses_id` int(11) NOT NULL,
  `participate` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`student_id`,`sec_id`,`ses_id`),
  CONSTRAINT `paticipate_session` FOREIGN KEY (`sec_id`, `ses_id`) REFERENCES `sessions` (`sec_id`, `ses_id`) ON DELETE CASCADE,
  CONSTRAINT `paticipate_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE
) DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for post
-- ----------------------------
DROP TABLE IF EXISTS `post`;
CREATE TABLE `post` (
  `moderator_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  PRIMARY KEY (`moderator_id`,`material_id`),
  CONSTRAINT `post_material` FOREIGN KEY (`material_id`) REFERENCES `material` (`material_id`) ON DELETE CASCADE,
  CONSTRAINT `post_moderator` FOREIGN KEY (`moderator_id`) REFERENCES `moderators` (`moderator_id`) ON DELETE CASCADE
) DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sections
-- ----------------------------
DROP TABLE IF EXISTS `sections`;
CREATE TABLE `sections` (
  `sec_id` int(11) NOT NULL AUTO_INCREMENT,
  `sec_name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `time_slot_id` int(11) NOT NULL,
  `capacity` int(11) NOT NULL,
  `c_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`sec_id`),
  CONSTRAINT `section_course` FOREIGN KEY (`c_id`) REFERENCES `courses` (`c_id`) ON DELETE CASCADE,
  CONSTRAINT `section_timeslot` FOREIGN KEY (`time_slot_id`) REFERENCES `time_slot` (`time_slot_id`) ON DELETE CASCADE
) DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `ses_id` int(11) NOT NULL AUTO_INCREMENT,
  `sec_id` int(11) NOT NULL,
  `ses_name` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `announcement` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ses_id`,`sec_id`),
  CONSTRAINT `session_section` FOREIGN KEY (`sec_id`) REFERENCES `sections` (`sec_id`) ON DELETE CASCADE
) DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for students
-- ----------------------------
DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `grade` int(11) DEFAULT NULL,
  PRIMARY KEY (`student_id`),
  CONSTRAINT `student_user` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for teach
-- ----------------------------
DROP TABLE IF EXISTS `teach`;
CREATE TABLE `teach` (
  `sec_id` int(11) NOT NULL,
  `mentor_id` int(11) NOT NULL,
  PRIMARY KEY (`sec_id`,`mentor_id`),
  CONSTRAINT `teach_mentor` FOREIGN KEY (`mentor_id`) REFERENCES `mentors` (`mentor_id`) ON DELETE CASCADE,
  CONSTRAINT `teach_section` FOREIGN KEY (`sec_id`) REFERENCES `sections` (`sec_id`) ON DELETE CASCADE
) DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for time_slot
-- ----------------------------
DROP TABLE IF EXISTS `time_slot`;
CREATE TABLE `time_slot` (
  `time_slot_id` int(11) NOT NULL AUTO_INCREMENT,
  `day_of_the_week` varchar(255) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  PRIMARY KEY (`time_slot_id`)
) DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;


--- Insertions into Users
INSERT INTO `users`(`email`, `password`, `name`, `phone`, `city`, `state`) 
VALUES  ('aParent@gmail.com', '1111', 'Alfred Rent', '978-555-5555', 'Lowell', 'Massachusetts'),
        ('bParent@gmail.com', '1112', 'Barbara Modero', '603-555-5555', 'Cambridge', 'Massachusetts'),
        ('cParent@yahoo.com', '1113', 'Charles Darles', '708-555-5555', 'Seabrook', 'New Hampshire'), 
        ('dParent@gmail.com', '1114', 'Darla Barlent', '978-555-5555', 'Amesbury', 'Massachusetts'), 
        ('eParent@gmail.com', '1115', 'Emil Watson', '387-555-5555', 'Lowell', 'Massachusetts'), 
        ('fParent@gmail.com', '1116', 'Faye Penta', '212-555-5555', 'Nashua', 'New Hampshire'), 
        ('gParent@gmail.com', '1117', 'George DeSande', '403-555-5555', 'Scranton', 'New Hampshire'), 
        ('hParent@gmail.com', '1118', 'Hiro Stu', '388-555-5555', 'Jamestown', 'New Hampshire'), 
        ('iParent@gmail.com', '1119', 'Irene Lemonde', '753-555-5555', 'Concord', 'Massachusetts'), 
        ('jParent@gmail.com', '1121', 'Joshua Johnson', '908-555-5555', 'Lexington', 'Massachusetts'), 
        ('kParent@gmail.com', '1122', 'Karen Layton', '345-555-5555', 'Keene', 'New Hampshire'), 
        ('lParent@gmail.com', '1123', 'Larry Carrie', '123-555-5555', 'Haverhill', 'Massachusetts'), 
        ('mParent@gmail.com', '1124', 'Milton Post', '455-555-5555', 'Lawrence', 'Massachusetts'), 
        ('nParent@gmail.com', '1125', 'Nathanial Great', '988-555-5555', 'Andover', 'Massachusetts'), 
        ('aStudnet@yahoo.com', '9999', 'Arthur Rent', '356-555-5555', 'Lowell', 'Massachusetts'), 
        ('bStudnet@yahoo.com', '9998', 'Bethany Modero', '763-555-5555', 'Methuen', 'Massachusetts'), 
        ('cStudnet@yahoo.com', '9997', 'Cameron Dent', '257-555-5555', 'Salisbury', 'Massachusetts'), 
        ('dStudnet@yahoo.com', '9996', 'Damien Barlent', '245-555-5555', 'Seabrook', 'New Hampshire'), 
        ('eStudnet@yahoo.com', '9995', 'Emily Watson', '359-555-5555', 'Newbury', 'Massachusetts'), 
        ('fStudnet@yahoo.com', '9994', 'Frank Penta', '195-555-5555', 'Byfield', 'Massachusetts'), 
        ('gStudnet@yahoo.com', '9993', 'Georgina DeSane', '438-555-5555', 'Lowell', 'Massachusetts'), 
        ('hStudnet@yahoo.com', '9992', 'Halie Stu', '324-555-5555', 'Nashua', 'New Hampshire'), 
        ('iStudnet@yahoo.com', '9991', 'Ira Lemonde', '234-555-5555', 'Keene', 'New Hampshire'), 
        ('jStudnet@yahoo.com', '9989', 'James Johnson', '690-555-5555', 'Haverhill', 'Massachusetts'), 
        ('kStudnet@yahoo.com', '9988', 'Kristoff Layton', '674-555-5555', 'Lawrence', 'Massachusetts'), 
        ('lStudnet@yahoo.com', '9987', 'Lindsay Carrie', '135-555-5555', 'Lowell', 'Massachusetts'), 
        ('mStudnet@yahoo.com', '9986', 'Mary Post', '296-555-5555', 'Seabrook', 'New Hampshire')
        ('oParent@gmail.com', '1126', 'Olieda Rincet', '986-555-5555', 'Wouburn', 'Massachusetts'),
        ('pParent@gmail.com', '1127', 'Paula Peters', '933-555-5555', 'Salem', 'New Hampshire'),
        ('nStudent@gmail.com', '9985', 'Naomi Great', '153-555-5555', 'Andover', 'Massachusetts'),
        ('oStudent@gmail.com', '9984', 'Oliver Rincet', '927-555-555', 'Woburn', 'Massachusetts'),
        ('pStudent@gmail.com', '9983', 'Paul Peters', '129-555-5555', 'Salem', 'New Hampshire')

--- Insertions into Parents
INSERT INTO `parents`(`parent_id`) VALUES (4), (5), (6), (7), (8), (9), (10), (11), (12), (13), (14), (15), (16), (17), (31), (32)

--- Insetions into Students
INSERT INTO `students`(`student_id`, `grade`) VALUES (18,1), (19,1), (20,3), (21,2), (22,2), (23,2), (24,3), (25,3), (26,3), (27,4), (28,4),
          (29, 3), (30, 3),(33, 4), (34, 2), (35, 4)

--- Insetions into Parenting
INSERT INTO `parenting`(`parent_id`, `student_id`) VALUES (4,18), (5,19), (6,20), (7,21), (8,22), (9,23), (10,24), (11,25), (12,26), (13,27), (14,28),
          (15,29), (16,30), (17,33), (31,34), (32,35)

--- Insertions into Courses
INSERT INTO `courses`(`title`, `description`, `mentor_grade_req`, `mentee_grade_req`), 
VALUES ('Database 1', 'A first course in databases.', 4, 3), 
  ('Database 2', 'The second course in databases.', 4, 3),
  ('Mobile App 1', 'Learn how to develop native android applications.', 4, 3),
  ('Operating Systems', 'A deep dive into OS.', 3, 2),
  ('Computer Architecture', 'Learn the inner workings of computers.', 3, 2),
  ('Foundations of Computer Science', 'Understand the origins of Comp Sci.', 3, 2),
  ('Computing 1', 'Begin the path of Computer Science', 2, 1),
  ('Computing 2', 'Continue with advanced C techniques.', 2, 1),
  ('Calculus 1', 'Measure under a curve.', 2, 1),
  ('College Writing 1', 'But words into sentences.', 2, 1)

--- Insertions into time_slot
INSERT INTO `time_slot`(`time_slot_id`, `day_of_the_week`, `start_time`, `end_time`) 
VALUES (1, 'Monday', '11:15:00', '12:30:00'), (2, 'Monday', '2:00:00', '3:45:00'),
       (3, 'Tuesday', '10:00:00', '10:50:00'), (4, 'Tuesday', '5:30:00', '8:45:00'),
       (5, 'Wednesday', '11:50:00', '12:30:00'), (6, 'Wednesday', '12:45:00', '2:00:00'),
       (7, 'Thursday', '11:00:00', '12:15:00'), (8, 'Thursday', '1:45:00', '3:00:00'),
       (9, 'Friday', '09:30:00', '10:45:00'), (10, 'Friday', '11:00:00', '12:30:00')

--- Insertions into Sections
INSERT INTO `sections`(`sec_id`, `sec_name`, `start_date`, `end_date`, `time_slot_id`, `capacity`, `c_id`) 
VALUES (1, 'DataBase_Mon', '2018-09-02', '2018-12-17', 1, 9, 1),
  (2, 'DataBase2_Mon', '2019-01-21', '2019-05-06', 2, 9, 2),
  (3, 'MobileApp_Tue', '2018-09-04', '2019-12-05', 3, 9, 3),
  (4, 'OperatingSystems_Tue', '2019-01-22', '2019-05-07', 4, 9, 4),
  (5, 'Architecture_Wed', '2019-01-23', '2019-05-01', 5, 9, 5),
  (6, 'Foundations_Wed', '2017-01-18', '2017-05-03', 6, 9, 6),
  (7, 'Computing_Thu', '2018-09-06', '2018-12-13', 7, 9, 7),
  (8, 'Computing2_Thu', '2019-01-24', '2019-05-02', 8, 9, 8),
  (9, 'Calc_Fri', '2019-01-25', '2019-05-03', 9, 9, 9),
  (10, 'CollegeWriting_Fri', '2017-09-01', '2017-12-15', 10, 9, 10)

--- Insertions into sessions
INSERT INTO `sessions`(`ses_id`, `sec_id`, `ses_name`, `date`, `announcement`) 
VALUES (1, 2, 'DB2_1', '2019-04-01', 'Our first class.'),
	(2, 2, 'DB2_2', '2019-01-28', 'Project details.'),
    (3, 4, 'OS_1', '2019-01-22', 'Introduction to OS.'),
    (4, 4, 'OS_2', '2019-01-29', 'Linux overview.'),
    (5, 5, 'CA_1', '2019-01-23', 'Intro to architecture.'),
    (6, 5, 'CA_2', '2019-01-30', 'Second class.'),
    (7, 8, 'COMP2_1', '2019-01-24', 'C++ overview.'),
    (8, 8, 'COMP2_2', '2019-01-31', 'Classes in C++'),
    (9, 9, 'CALC_1', '2019-01-25', 'What is a derivative.'),
    (10, 9, 'CALC_2', '2019-02-01', 'Measuring under the curve.')

--- Insertions into Material
INSERT INTO `material`(`material_id`, `title`, `author`, `type`, `url`, `assigned_date`, `notes`) 
VALUES (1, 'PHP and MySQL', 'Richard Bachman', 'Book', 'N/A', '2019-03-22', 'Read only chapters 2 and 3.'),
  (2, 'Building Web Apps', 'Kyle Demeter', 'Article', 'www.website.com/WebApp', '2019-01-28', 'N/A'),
  (3, 'Linux Pocket Guide', 'Linus Torvald', 'Book', 'N/A', '2019-01-29', 'Review common commands.'),
  (4, 'Introduction to Operating Systems', 'Timothy Oses', 'Book', 'N/A', '2019-01-29', 'Read chapter 1.'),
  (5, 'Bitwise foundations', 'William Bills', 'Article', 'www.uml.edu/~Bills/bwf.pdf', '2019-01-30', 'Read carefully'),
  (6, 'Absolue C++', 'Jimothy James', 'Book', 'N/A', '2019-01-31', 'Read chapter 2.'),
  (7, 'C++ for dummies.', 'Peter Brast', 'Handout', 'N/A', '2019-01-31', 'N/A'),
  (8, 'C++ for smarties', 'Richard Brast', 'Handout', 'N/A', '2019-02-07', 'Read last.'),
  (9, 'Pre-calculus', 'Liz Mattis', 'Book', 'www.LizMat.com', '2019-02-01', 'Review chapters 1-12.'),
  (10, 'Calculus', 'Liz Mattiz', 'Book', 'www.LizMat.com', '2019-02-08', 'Read chapter 1.')

--- Insertions into Mentors
INSERT INTO `mentors`(`mentor_id`) VALUES (23), (24), (25), (26), (27), (28), (29), (30), (33), (34), (35)

--- Insertions into Mentees
INSERT INTO `mentees`(`mentee_id`) VALUES (18), (19), (20), (21), (22), (28), (29), (30), (33), (34), (35)

--- Insetion into enroll
INSERT INTO `enroll`(`sec_id`, `mentee_id`) 
VALUES (5,18), (2,20), (4,20), (4,21), (5,21), (4,22), (5,22), (2,29), (2,30), (5,29), (5,30), (4,34), (5,34)

--- Insertion into teach
INSERT INTO `teach`(`sec_id`, `mentor_id`) 
VALUES (5,24), (4,26), (5,26), (4,27), (2,28), (8,29), (9,29), (4,30), (2,33), (5,33), (2,35)

--- Insert into Moderators
INSERT INTO `moderators`(`moderator_id`) VALUES (4), (5), (6), (7), (8), (9), (10), (11), (12), (13)

--- Insert into moderate
INSERT INTO `moderate`(`sec_id`, `moderator_id`) VALUES (1,4), (2,5), (3,6), (4,7), (5,8), (6,9), (7,10), (8,11), (9,12), (10,13)

--- Insert into post
INSERT INTO `post`(`moderator_id`, `material_id`) VALUES (5,1), (5,2), (7,3), (7,4), (8,5), (11,6), (11,7), (11,8), (12,9), (12,10)

--- Insert into assign
INSERT INTO `assign`(`sec_id`, `ses_id`, `moderator_id`, `material_id`) 
VALUES (2, 1, 5, 1), 
	(2, 2, 5, 2),
  (4, 3, 7, 3),
  (4, 4, 7, 4),
  (5, 5, 8, 5),
  (5, 6, 8, 5),
  (8, 7, 10, 7),
  (8, 8, 10, 8),
  (9, 9, 11, 9),
  (9, 10, 11, 10)

--- Insert into participate
INSERT INTO `participate`(`student_id`, `sec_id`, `ses_id`, `participate`) 
VALUES (18, 9, 9, 1), (19, 8, 7, 1), (19, 9, 9, 1), (20, 4, 3, 1), (21, 4, 3, 1),
	(21, 5, 5, 1), (22, 4, 3, 1), (22, 5, 5, 1), (28, 2, 1, 2), (29, 2, 1, 1), (29, 5, 5, 1), 
  (30, 2, 1, 1), (30, 5, 5, 1), (33, 2, 1, 2), (34, 4, 3, 1), (34, 5, 5, 1), (35, 2, 1, 2)