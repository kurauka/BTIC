-- Database Schema for Bandari Tech & Innovation Club
-- Optimized for Aiven (Inline Primary Keys)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+03:00";

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','member') DEFAULT 'member',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users` (Default Admin)
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`) VALUES
(1, 'admin', 'admin@bandaritechclub.ac.ke', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'); -- Password: password

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `project_url` varchar(255) DEFAULT '#',
  `is_featured` tinyint(1) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `title`, `description`, `category`, `tags`, `is_featured`) VALUES
(1, 'Smart Port Monitoring System', 'IoT-based sensors to track and optimize port operations in real time with data analytics dashboard.', 'IoT', 'IoT, Port Tech', 1),
(2, 'Marine Waste Tracker', 'AI-powered system for identifying and tracking ocean litter for targeted cleanup campaigns.', 'AI', 'AI, Ocean', 1),
(3, 'Digital Navigation Assistant', 'Mobile app designed specifically for cadet navigation training with interactive simulations.', 'App', 'App, Navigation', 1),
(4, 'Renewable Energy for Vessels', 'Solar-assisted power integration system for small boats, reducing fuel dependency and emissions.', 'Green Tech', 'Green Tech, Energy', 1);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `event_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `location` varchar(255) DEFAULT 'Bandari Maritime Academy',
  `tags` varchar(255) DEFAULT NULL,
  `registration_link` varchar(255) DEFAULT '#',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `subtitle`, `description`, `event_date`, `location`, `tags`) VALUES
(1, 'Innovation Week 2026', 'Digital Seas: Smart Solutions for the Blue Economy', 'A week-long celebration of tech, innovation, and maritime excellence bringing together students, industry leaders, and researchers.', '2026-06-12', 'Bandari Maritime Academy, Mombasa', 'Workshops, Hackathon, Exhibition, Networking');

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

DROP TABLE IF EXISTS `programs`;
CREATE TABLE `programs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `icon_class` varchar(50) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `title`, `description`, `display_order`) VALUES
(1, 'Innovation Bootcamps', 'Hands-on training in coding, electronics, and robotics over intensive multi-day sessions.', 1),
(2, 'Digital Skills Workshops', 'Focused sessions on AI, IoT, data science, and 3D design for practical application.', 2),
(3, 'Hackathons & Challenges', 'Solving real maritime and community-based problems under competitive, collaborative conditions.', 3),
(4, 'Tech Talks & Webinars', 'Guest sessions with industry experts, innovators, and maritime technology leaders.', 4),
(5, 'Research & Project Incubation', 'Structured support system for student-led innovation ideas from concept to prototype.', 5),
(6, 'Annual Innovation Fair', 'Showcasing student projects, prototypes, and tech startups to industry professionals.', 6);

-- --------------------------------------------------------

--
-- Table structure for table `stats`
--

DROP TABLE IF EXISTS `stats`;
CREATE TABLE `stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(50) NOT NULL,
  `value` int(11) NOT NULL,
  `suffix` varchar(10) DEFAULT '+',
  `icon_class` varchar(50) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stats`
--

INSERT INTO `stats` (`id`, `label`, `value`, `suffix`, `icon_class`, `display_order`) VALUES
(1, 'Students Trained', 50, '+', 'ri-computer-line', 1),
(2, 'Maritime Projects', 12, '+', 'ri-ship-2-line', 2),
(3, 'Awards Won', 5, '+', 'ri-trophy-line', 3),
(4, 'Industry Partners', 8, '+', 'ri-hand-heart-line', 4),
(5, 'Driving innovation for the maritime sector', 0, '', 'ri-earth-line', 5);

-- --------------------------------------------------------

--
-- Table structure for table `organizers`
--

DROP TABLE IF EXISTS `organizers`;
CREATE TABLE `organizers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT '#',
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

DROP TABLE IF EXISTS `partners`;
CREATE TABLE `partners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `logo_url` varchar(255) NOT NULL,
  `website_url` varchar(255) DEFAULT '#',
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

DROP TABLE IF EXISTS `gallery`;
CREATE TABLE `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `icon_class` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `title`, `icon_class`, `category`) VALUES
(1, 'Robotics Workshop', 'ri-robot-line', 'Workshop'),
(2, 'Hackathon 2025', 'ri-lightbulb-flash-line', 'Event'),
(3, 'Innovation Fair', 'ri-rocket-2-line', 'Event'),
(4, 'Marine Research', 'ri-drop-line', 'Research'),
(5, 'Community Outreach', 'ri-graduation-cap-line', 'Outreach');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `interest_type` varchar(50) DEFAULT NULL,
  `message_body` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `membership_requests`
--

DROP TABLE IF EXISTS `membership_requests`;
CREATE TABLE `membership_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `institution` varchar(100) DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL,
  `year_of_study` varchar(20) DEFAULT NULL,
  `interests` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
