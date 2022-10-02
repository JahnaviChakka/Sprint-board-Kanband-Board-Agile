-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 13, 2022 at 01:09 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `recall`
--

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `sno` int(10) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` longtext NOT NULL,
  `tstamp` datetime NOT NULL DEFAULT current_timestamp(),
  `id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`sno`, `title`, `description`, `tstamp`, `id`) VALUES
(1, 'pranav is champ', 'just a lol', '2022-03-23 11:44:35', '109501823479799371687'),
(4, 'cloud computing', 'recall application development inprogress', '2022-04-03 20:02:13', '101661526643603239071'),
(44, 'Information Theory and Coding - Intro', 'Information is the source of a communication system, whether it is analog or digital. Information theory is a mathematical approach to the study of coding of information along with the quantification, storage, and communication of information.\r\nDiscrete Memoryless Source :\r\nA source from which the data is being emitted at successive intervals, which is independent of previous values, can be termed as discrete memoryless source. This source is discrete as it is not considered for a continuous time interval, but at discrete time intervals.', '2022-04-09 20:57:25', '110673071958317830089'),
(45, 'IaaS in Cloud Computing', 'Infrastructure as a service (IaaS) are online services that provide high-level APIs used to dereference various low-level details of underlying network infrastructure like physical computing resources, location, data partitioning, scaling, security, backup etc. A hypervisor, such as Xen, Oracle VirtualBox, Oracle VM, KVM, VMware ESX/ESXi, or Hyper-V runs the virtual machines as guests. Pools of hypervisors within the cloud operational system can support large numbers of virtual machines and the ability to scale services up and down according to customers\' varying requirements. Typically IaaS involves the use of a cloud orchestration technology like OpenStack, Apache CloudStack or OpenNebula.', '2022-04-09 21:03:21', '110673071958317830089'),
(46, 'Software Testing', 'Software Testing is a method to check whether the actual software product matches expected requirements and to ensure that software product is Defect free. It involves execution of software/system components using manual or automated tools to evaluate one or more properties of interest. The purpose of software testing is to identify errors, gaps or missing requirements in contrast to actual requirements. Some prefer saying Software testing definition as a White Box and Black Box Testing. In simple terms, Software Testing means the Verification of Application Under Test.', '2022-04-09 21:04:15', '110673071958317830089'),
(48, 'Team Software Process', 'In combination with the personal software process (PSP), the team software process (TSP) provides a defined operational process framework that is designed to help teams of managers and engineers organize projects and produce software for products that range in size from small projects of several thousand lines of code (KLOC) to very large projects greater than half a million lines of code.', '2022-04-09 21:25:10', '110673071958317830089'),
(58, 'Team Building', 'Team building creates stronger bonds among the members of a group. The individual members respect each other and their differences and share common goals and expectations.\r\nTeam building can include the daily interaction that employees engage in when working together to carry out the requirements of their jobs. This form of team building is natural and can be assisted if the group takes the time to come up with a set of team norms. These norms help group members know how to appropriately interact on the team and with the rest of the organization.', '2022-04-11 01:25:53', '109616356529090761821'),
(59, 'madhav', 'xyzadcdcdc', '2022-07-06 16:59:02', '110673071958317830089');

-- --------------------------------------------------------

--
-- Table structure for table `organizetask`
--

CREATE TABLE `organizetask` (
  `id` int(11) NOT NULL,
  `task` varchar(65) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `user_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `organizetask`
--

INSERT INTO `organizetask` (`id`, `task`, `type`, `user_id`) VALUES
(22, 'cloud project', 'completed', '110673071958317830089'),
(23, 'cat 2', 'progress', '110673071958317830089'),
(24, 'research paper', 'notstarted', '110673071958317830089'),
(27, 'resume', 'notstarted', '110673071958317830089'),
(28, 'note making module', 'progress', '110673071958317830089'),
(29, 'Kanban board module', 'completed', '110673071958317830089'),
(30, 'GRE Verbal', 'pending', '110673071958317830089'),
(31, 'efe notes', 'pending', '110673071958317830089'),
(37, 'Deploy PDV Project', 'progress', '109616356529090761821'),
(38, 'Learn AWS Services', 'notstarted', '109616356529090761821'),
(39, 'TSP Report Submission', 'completed', '109616356529090761821'),
(40, 'Do EFE Term Paper', 'pending', '109616356529090761821');

-- --------------------------------------------------------

--
-- Table structure for table `passwords`
--

CREATE TABLE `passwords` (
  `id` int(255) NOT NULL,
  `site` varchar(255) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(1000) NOT NULL,
  `favorite` tinyint(1) DEFAULT 0,
  `user_id` varchar(255) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `passwords`
--

INSERT INTO `passwords` (`id`, `site`, `username`, `password`, `favorite`, `user_id`) VALUES
(19, 'https://vtop2.vitap.ac.in/vtop/initialProcess', '19BCE7048', 'Rkx6MUhCSTJxRmR0ZTh5K0dhMVlrdz09OjqLFgPCHqkhPGxBfebeHYAv', 0, '109616356529090761821'),
(20, 'https://www.linkedin.in', 'admin', 'Z1hPRmUwU2ZTM2NTQ3I3RGI1aERYdz09Ojq73GZBVbv1rmNstIun6Yi7', 0, '110673071958317830089');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `google_id` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `google_id`, `name`, `email`, `profile_image`) VALUES
(1, '110673071958317830089', 'Gudi Varaprasad', 'gudi.varaprasad@gmail.com', 'https://lh3.googleusercontent.com/a-/AOh14Gjkn6ath4ved911-BRH6b1zeAGQQdZehXFJBk1CbA=s96-c'),
(2, '109501823479799371687', 'Sesha Pranav', 'seshapranav@gmail.com', 'https://lh3.googleusercontent.com/a-/AOh14GhponNXyCoEHBziYrmD_I6hsFbIvTbbbdAwX54HMw=s96-c'),
(3, '103013404779558319953', 'ANTIDOTE', 'krishpalaparthy6768@gmail.com', 'https://lh3.googleusercontent.com/a-/AOh14GiMmFt4EmWXHiRtlizVCE-d1y6HLl_lnFzNy4hZu-I=s96-c'),
(4, '101661526643603239071', 'Nikhilesh Gunnam', 'nikhileshgunnam@gmail.com', 'https://lh3.googleusercontent.com/a-/AOh14GhCaFXTsykypDb1ZcjOVm37mpeTQ8ftf3tNoj371Q=s96-c'),
(5, '109616356529090761821', 'GUDI VARAPRASAD 19BCE7048', 'varaprasad.19bce7048@vitap.ac.in', 'https://lh3.googleusercontent.com/a-/AOh14GhBlRjmbqlv-bc7u4c1fVu5EG-rGDj5ty9CyGBRLQ=s96-c'),
(6, '101312019815020166375', 'Saketh Saridena', 'saketh.saridena@gmail.com', 'https://lh3.googleusercontent.com/a-/AOh14GiaSNsUvWNT8EZgaWnSIW7hpGb0ehIGBLIqzSm29Mc=s96-c'),
(7, '104327482174821834871', 'DANDU PREETHAM 19BCN7254', 'preetham.19bcn7254@vitap.ac.in', 'https://lh3.googleusercontent.com/a/AItbvmnZQp8o6dNqeRO2pu9Q6U7dBWfiYWTtQcas-Zvf=s96-c');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`sno`);

--
-- Indexes for table `organizetask`
--
ALTER TABLE `organizetask`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `passwords`
--
ALTER TABLE `passwords`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `google_id` (`google_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `sno` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `organizetask`
--
ALTER TABLE `organizetask`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `passwords`
--
ALTER TABLE `passwords`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
