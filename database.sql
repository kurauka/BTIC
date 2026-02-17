-- Database Schema for Bandari Tech & Innovation Club
-- Generated: 2026-02-17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+03:00";

--
-- Database: `bandari_tech_club`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','member') DEFAULT 'member',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP
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

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL, -- Comma-separated tags
  `image_url` varchar(255) DEFAULT NULL,
  `project_url` varchar(255) DEFAULT '#',
  `is_featured` tinyint(1) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP
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

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `event_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `location` varchar(255) DEFAULT 'Bandari Maritime Academy',
  `tags` varchar(255) DEFAULT NULL,
  `registration_link` varchar(255) DEFAULT '#',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP
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

CREATE TABLE `programs` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `icon_class` varchar(50) DEFAULT NULL, -- Remix Icon class if needed
  `display_order` int(11) DEFAULT 0
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

CREATE TABLE `stats` (
  `id` int(11) NOT NULL,
  `label` varchar(50) NOT NULL,
  `value` int(11) NOT NULL,
  `suffix` varchar(10) DEFAULT '+',
  `icon_class` varchar(50) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stats`
--

INSERT INTO `stats` (`id`, `label`, `value`, `suffix`, `icon_class`, `display_order`) VALUES
(1, 'Students Trained', 50, '+', 'ri-computer-line', 1),
(2, 'Maritime Projects', 12, '+', 'ri-ship-2-line', 2),
(3, 'Awards Won', 5, '+', 'ri-trophy-line', 3),
(4, 'Industry Partners', 8, '+', 'ri-hand-heart-line', 4),
(5, 'Driving innovation for the maritime sector', 0, '', 'ri-earth-line', 5); -- Special case for the "Blue Economy" block

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL, -- Caption
  `image_url` varchar(255) DEFAULT NULL, -- Or icon class for placeholder
  `icon_class` varchar(50) DEFAULT NULL, -- Using icons for now
  `category` varchar(50) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP
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

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `interest_type` varchar(50) DEFAULT NULL,
  `message_body` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

ALTER TABLE `users` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`), ADD UNIQUE KEY `email` (`email`);
ALTER TABLE `projects` ADD PRIMARY KEY (`id`);
ALTER TABLE `events` ADD PRIMARY KEY (`id`);
ALTER TABLE `programs` ADD PRIMARY KEY (`id`);
ALTER TABLE `stats` ADD PRIMARY KEY (`id`);
ALTER TABLE `gallery` ADD PRIMARY KEY (`id`);
ALTER TABLE `messages` ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

ALTER TABLE `users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `projects` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
ALTER TABLE `events` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `programs` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
ALTER TABLE `stats` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE `gallery` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE `messages` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

COMMIT;
