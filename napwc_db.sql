-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2024 at 02:51 PM
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
-- Database: `napwc`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `admin_name` varchar(80) NOT NULL,
  `admin_password` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `admin_name`, `admin_password`) VALUES
(3, 'napwcadmin', '$2y$10$7gjqtAQMM6vam/w9pPQMlOHNyG80RQuyA9rwOiuomBI25FMcMA0Pu');

-- --------------------------------------------------------

--
-- Table structure for table `booking_records`
--

CREATE TABLE `booking_records` (
  `ID` int(11) NOT NULL,
  `NAME` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `PHONE` varchar(255) NOT NULL,
  `EMAIL` varchar(255) NOT NULL,
  `DATE` date NOT NULL,
  `ARRIVAL_TIME` time NOT NULL,
  `TIME_OF_LEAVE` time NOT NULL,
  `SECTION` varchar(255) NOT NULL,
  `CONFIRMATION` varchar(15) NOT NULL,
  `archived` tinyint(1) NOT NULL,
  `PRICE` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_records`
--

INSERT INTO `booking_records` (`ID`, `NAME`, `lastname`, `PHONE`, `EMAIL`, `DATE`, `ARRIVAL_TIME`, `TIME_OF_LEAVE`, `SECTION`, `CONFIRMATION`, `archived`, `PRICE`) VALUES
(87, 'Ralph Denzell', 'Tanteo', '09289097027', 'iamralphdenzelltanteo@gmail.com', '2024-02-29', '08:00:00', '17:00:00', 'Shed 1', 'CONFIRMED', 0, 500.00),
(99, 'Ralph Denzell', 'Tanteo', '09289097027', 'iamralphdenzelltanteo@gmail.com', '2024-03-21', '08:00:00', '17:00:00', 'Fishing Village', 'CONFIRMED', 0, 2500.00),
(101, 'Ralph Denzell', 'Tanteo', '09289097027', 'iamralphdenzelltanteo@gmail.com', '2024-03-02', '08:00:00', '17:00:00', 'Fishing Village', 'CONFIRMED', 0, 2500.00),
(109, 'Ralph Denzell', 'Tanteo', '09289097027', 'iamralphdenzelltanteo@gmail.com', '2024-03-08', '08:00:00', '17:00:00', 'Fishing Village', 'CANCELLED', 0, 2500.00),
(110, 'Ralph Denzell', 'Tanteo', '09289097027', 'iamralphdenzelltanteo@gmail.com', '2024-03-22', '08:00:00', '17:00:00', 'Shed 5', 'CANCELLED', 0, 500.00),
(111, 'John Bruce ', 'Balangatan', '09987654321', 'brus@gmail.com', '2024-03-14', '08:00:00', '17:00:00', 'Shed 3', 'CANCELLED', 0, 500.00),
(112, 'Ralph Denzell', 'Tanteo', '09289097027', 'iamralphdenzelltanteo@gmail.com', '2024-03-05', '08:00:00', '17:00:00', 'Amphitheater', 'CANCELLED', 0, 1350.00),
(113, 'Ralph Denzell', 'Tanteo', '09289097027', 'iamralphdenzelltanteo@gmail.com', '2024-03-19', '08:00:00', '17:00:00', 'Shed 1', 'CONFIRMED', 0, 500.00);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event_name` varchar(80) NOT NULL,
  `event_image` varchar(150) NOT NULL,
  `event_description` varchar(150) NOT NULL,
  `status` varchar(255) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_name`, `event_image`, `event_description`, `status`) VALUES
(19, 'Gabay', '../uploads/events/napwcbg1.jpg', 'This will serve as a guide to let the visitors know how to behave when navigating or visiting reservation facilities.', 'active'),
(20, 'Sikap', '../uploads/events/napwcbg.jpg', 'This will serve as a guide to let the visitors know the importance of conservation and reservation facilities.', 'active'),
(21, 'Siklab', '../uploads/events/.event.jpg', 'Month of Horror Celebration ', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` int(11) NOT NULL,
  `facility_name` varchar(255) NOT NULL,
  `PRICING` decimal(10,2) DEFAULT NULL,
  `maxpax` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `facility_name`, `PRICING`, `maxpax`) VALUES
(1, 'Fishing Village', 2500.00, 150),
(2, 'Amphitheater', 1350.00, 100),
(3, 'Tea House', 500.00, 50),
(4, 'Shed 1', 500.00, 5),
(5, 'Shed 2', 500.00, 5),
(6, 'Shed 3', 500.00, 5),
(7, 'Shed 4', 500.00, 5),
(8, 'Shed 5', 500.00, 5);

-- --------------------------------------------------------

--
-- Table structure for table `incident_report`
--

CREATE TABLE `incident_report` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_info` varchar(255) NOT NULL,
  `incident_type` varchar(50) NOT NULL,
  `incident_description` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `incident_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incident_report`
--

INSERT INTO `incident_report` (`id`, `name`, `email`, `contact_info`, `incident_type`, `incident_description`, `timestamp`, `incident_image`) VALUES
(5, 'Ralph Denzell', 'iamralphdenzelltanteo@gmail.com', '09683255256', 'safety', 'A snake was found along the way to fishing village', '2024-02-29 15:16:35', '../uploads/incident/snake report.jpg'),
(6, 'Ralph Denzell', 'iamralphdenzelltanteo@gmail.com', '09683255256', 'security', 'A beehive was spotted at a trail nearby shed 5', '2024-02-29 20:40:44', '../uploads/incident/beehive.jpg'),
(7, 'Ralph Denzell', 'iamralphdenzelltanteo@gmail.com', '09683255256', 'security', 'A beehive was seen near the picnic shed 1. ', '2024-03-02 12:41:37', '../uploads/incident/beehive.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `map`
--

CREATE TABLE `map` (
  `id` int(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `map`
--

INSERT INTO `map` (`id`, `category`, `name`, `image`, `description`, `latitude`, `longitude`) VALUES
(1, 'entrances', 'Gate One', '../map_images/gateone.jpg', 'Welcome to Gate One of Ninoy Aquino Parks and Wildlife Center! This serene entrance leads into a lush oasis in Quezon City, surrounded by vibrant greenery and birdsong. It’s the perfect starting point for leisurely strolls, scenic viewpoints, tranquil ponds, and shaded picnic areas, allowing you to reconnect with nature in the heart of the city.', 14.64961, 121.0461),
(7, 'entrances', 'Gate Two', '../map_images/gatetwo.jpg', 'Gate Two at Ninoy Aquino Parks and Wildlife Center welcomes visitors to a serene oasis in Quezon City. Flanked by lush greenery, this charming entrance leads to a tranquil sanctuary featuring manicured lawns, winding paths, and serene ponds. With its elegant design, Gate Two symbolizes the center\'s commitment to nature, offering an ideal escape for relaxation and memorable experiences.', 14.65209, 121.04525),
(8, 'attractions', 'Bird Cage', '../map_images/bird cage.jpg', 'The Bird Cage at Ninoy Aquino Parks and Wildlife offers a close-up experience with various native bird species in a serene, natural setting. Visitors can observe these beautiful birds in spacious enclosures, promoting wildlife conservation and education while enjoying the park\'s lush surroundings.', 14.64981, 121.04571),
(10, 'maintenance', 'Parking Area', '../map_images/parking.png', 'The parking area at Ninoy Aquino Parks and Wildlife Center offers convenient and spacious parking for visitors. Located near the main entrances, it provides easy access to the park\'s attractions, ensuring a hassle-free experience for those exploring the natural surroundings.', 14.65202, 121.04485),
(11, 'monuments', 'Ninoy Aquino Monument', '../map_images/ninoyaquinomonument.jpg', 'The Ninoy Aquino Monument at Ninoy Aquino Parks and Wildlife Center honors the legacy of Senator Benigno \"Ninoy\" Aquino Jr. This striking statue stands as a symbol of his enduring fight for freedom and democracy, set amidst the park\'s peaceful surroundings, inviting reflection and remembrance.', 14.65041, 121.04524),
(12, 'monuments', 'Biodiversity Museum', '../map_images/biodiversity museum.jpg', 'The Biodiversity Museum at Ninoy Aquino Parks and Wildlife Center showcases the rich variety of the Philippines\' flora and fauna. Through interactive exhibits and displays, visitors can explore the country\'s diverse ecosystems and learn about conservation efforts to protect its natural heritage.', 14.65081, 121.04516),
(13, 'attractions', 'Hornbill Cage', '../map_images/howrnbill cage.jpg', 'The Hornbill Cage at Ninoy Aquino Parks and Wildlife Center houses the majestic hornbill, a unique bird species known for its large beak and striking appearance. Visitors can observe these fascinating birds up close while learning about efforts to conserve their natural habitat.', 14.65019, 121.04517),
(14, 'monuments', 'Admin Building', '../map_images/admin building.jpg', 'The Admin Building at Ninoy Aquino Parks and Wildlife Center serves as the operational hub for park management and visitor services. It houses staff offices and provides information and assistance to ensure a smooth and enjoyable park experience for all guests.', 14.65132, 121.0451),
(15, 'monuments', 'Wildlife Rescue Center', '../map_images/wildlif_rescue_center.png', 'The Wildlife Rescue Center at Ninoy Aquino Parks and Wildlife Center provides care and rehabilitation for injured, confiscated, or surrendered wildlife. Dedicated to conservation, the center offers a safe haven for animals while educating the public on wildlife protection and environmental stewardship.', 14.65153, 121.04574),
(16, 'monuments', 'Park Information Center', '../map_images/parkinformationcenter.jpg', 'The Park Information Center at Ninoy Aquino Parks and Wildlife Center is the go-to spot for visitors seeking guidance. It offers park maps, activity details, and helpful information to ensure a smooth and enjoyable visit while promoting awareness of the park\'s natural attractions.', 14.65044, 121.04561),
(17, 'attractions', 'Philippine Serpent Eagle', '../map_images/serpent eagle.jpg', 'The Philippine Serpent Eagle at Ninoy Aquino Parks and Wildlife Center is a remarkable bird of prey known for its keen hunting skills and sharp vision. Visitors can observe this majestic raptor up close and learn about its role in the Philippines\' ecosystems and ongoing conservation efforts to protect this species.', 14.64998, 121.04475),
(18, 'attractions', 'Flagpole', '../map_images/flagpole.jpg', 'The Flagpole at Ninoy Aquino Parks and Wildlife Center stands proudly at the entrance, symbolizing national pride and unity. It serves as a focal point for visitors, representing the park\'s commitment to conservation and the celebration of Philippine heritage within its lush surroundings.', 14.65056, 121.04519),
(19, 'attractions', 'Brahminy Kite Cage', '../map_images/brahminy.jpg', 'The Brahminy Kite, found at Ninoy Aquino Parks and Wildlife Center, is a stunning bird of prey known for its striking chestnut and white plumage. Often seen soaring gracefully overhead, this raptor plays a vital role in the ecosystem, and visitors can observe its remarkable beauty and learn about its habitat and conservation.', 14.64946, 121.0446),
(20, 'attractions', 'Philippine Eagle Cage', '../map_images/pheagle.jpg', 'The Philippine Eagle Cage at Ninoy Aquino Parks and Wildlife Center is dedicated to the conservation of the critically endangered Philippine eagle, one of the largest and most powerful birds of prey in the world. Visitors can observe these majestic eagles up close while learning about ongoing efforts to protect their habitat and ensure their survival.', 14.64918, 121.0444),
(21, 'attractions', 'Hawk Cage', '../map_images/hawkeagle.jpg', 'The Hawk Cage at Ninoy Aquino Parks and Wildlife Center showcases various hawk species, renowned for their impressive hunting skills and keen eyesight. Visitors can observe these magnificent birds up close while learning about their role in the ecosystem and the importance of wildlife conservation.', 14.64906, 121.04412),
(22, 'maintenance', 'CR 5', '../map_images/cr.png', 'The comfort room (CR) facilities at Ninoy Aquino Parks and Wildlife Center are clean and well-maintained, providing visitors with convenient access to restrooms during their visit. Strategically located throughout the park, these facilities ensure a comfortable experience for all guests exploring the natural surroundings.', 14.64905, 121.04432),
(23, 'maintenance', 'CR 1', '../map_images/cr.png', 'The comfort room (CR) facilities at Ninoy Aquino Parks and Wildlife Center are clean and well-maintained, providing visitors with convenient access to restrooms during their visit. Strategically located throughout the park, these facilities ensure a comfortable experience for all guests exploring the natural surroundings.', 14.65073, 121.04433),
(24, 'maintenance', 'CR 2', '../map_images/cr.png', 'The comfort room (CR) facilities at Ninoy Aquino Parks and Wildlife Center are clean and well-maintained, providing visitors with convenient access to restrooms during their visit. Strategically located throughout the park, these facilities ensure a comfortable experience for all guests exploring the natural surroundings.', 14.65188, 121.04339),
(25, 'maintenance', 'CR 3', '../map_images/cr.png', 'The comfort room (CR) facilities at Ninoy Aquino Parks and Wildlife Center are clean and well-maintained, providing visitors with convenient access to restrooms during their visit. Strategically located throughout the park, these facilities ensure a comfortable experience for all guests exploring the natural surroundings.', 14.65246, 121.04233),
(26, 'maintenance', 'CR 4', '../map_images/cr.png', 'The comfort room (CR) facilities at Ninoy Aquino Parks and Wildlife Center are clean and well-maintained, providing visitors with convenient access to restrooms during their visit. Strategically located throughout the park, these facilities ensure a comfortable experience for all guests exploring the natural surroundings.', 14.65015, 121.04192),
(27, 'facilities', 'Picnic Shed 4', '../map_images/shed.jpg', 'The Picnic Shed at Ninoy Aquino Parks and Wildlife Center offers a cozy and shaded spot for visitors to relax and enjoy meals amidst nature. Surrounded by lush greenery, it\'s the perfect place for families and friends to gather, unwind, and savor the peaceful atmosphere of the park.', 14.65029, 121.04437),
(28, 'facilities', 'Picnic Shed 5', '../map_images/shed.jpg', 'The Picnic Shed at Ninoy Aquino Parks and Wildlife Center offers a cozy and shaded spot for visitors to relax and enjoy meals amidst nature. Surrounded by lush greenery, it\'s the perfect place for families and friends to gather, unwind, and savor the peaceful atmosphere of the park.', 14.64971, 121.0441),
(29, 'facilities', 'Picnic Shed 3', '../map_images/shed.jpg', 'The Picnic Shed at Ninoy Aquino Parks and Wildlife Center offers a cozy and shaded spot for visitors to relax and enjoy meals amidst nature. Surrounded by lush greenery, it\'s the perfect place for families and friends to gather, unwind, and savor the peaceful atmosphere of the park.', 14.65109, 121.04422),
(30, 'facilities', 'Picnic Shed 2', '../map_images/shed.jpg', 'The Picnic Shed at Ninoy Aquino Parks and Wildlife Center offers a cozy and shaded spot for visitors to relax and enjoy meals amidst nature. Surrounded by lush greenery, it\'s the perfect place for families and friends to gather, unwind, and savor the peaceful atmosphere of the park.', 14.65149, 121.0446),
(31, 'facilities', 'Picnic Shed 1', '../map_images/shed.jpg', 'The Picnic Shed at Ninoy Aquino Parks and Wildlife Center offers a cozy and shaded spot for visitors to relax and enjoy meals amidst nature. Surrounded by lush greenery, it\'s the perfect place for families and friends to gather, unwind, and savor the peaceful atmosphere of the park.', 14.65177, 121.04399),
(32, 'facilities', 'Amphitheater', '../map_images/ampitheatre.JPG', 'The Amphitheater at Ninoy Aquino Parks and Wildlife Center is an open-air venue designed for various events and performances. Surrounded by nature, it provides a picturesque setting for educational programs, concerts, and community gatherings, fostering a deeper appreciation for wildlife and the environment.', 14.65033, 121.04382),
(33, 'attractions', 'Grotto', '../map_images/grotto.jpg', 'The Grotto at Ninoy Aquino Parks and Wildlife Center is a serene and picturesque spot featuring a beautifully designed shrine surrounded by lush greenery. This peaceful retreat invites visitors to reflect and meditate while enjoying the calming sounds of nature, making it a perfect escape within the park.', 14.65004, 121.04321),
(34, 'attractions', 'Garden of Paradise', '../map_images/garden of praise.jpg', 'The Garden of Paradise at Ninoy Aquino Parks and Wildlife Center is a tranquil oasis filled with vibrant flowers, lush greenery, and serene pathways. This beautifully landscaped area offers visitors a peaceful retreat to relax, reflect, and immerse themselves in the beauty of nature, making it an ideal spot for leisurely strolls and picnics.', 14.64991, 121.04316),
(35, 'facilities', 'Tea House', '../map_images/teahouse.png', 'The Tea House at Ninoy Aquino Parks and Wildlife Center offers a cozy retreat for visitors to relax and enjoy a selection of beverages and light snacks. Surrounded by lush greenery, this charming spot provides a peaceful ambiance, perfect for unwinding after exploring the park\'s natural attractions.', 14.6502, 121.04218),
(36, 'facilities', 'Fishing Village', '../map_images/fishingvillage.png', 'The Fishing Village at Ninoy Aquino Parks and Wildlife Center offers a charming glimpse into traditional fishing practices. This serene area features picturesque ponds and waterways, allowing visitors to enjoy a peaceful atmosphere while learning about the importance of sustainable fishing and aquatic biodiversity in the Philippines.', 14.65052, 121.04206),
(37, 'attractions', 'Picnic Grove', '../map_images/shed.jpg', 'The Picnic Grove at Ninoy Aquino Parks and Wildlife Center offers a scenic spot for visitors to relax and enjoy nature. Surrounded by lush greenery, this inviting area features picnic tables and shaded spaces, making it perfect for families and friends to gather, unwind, and savor a meal amidst the park\'s tranquil environment.', 14.6509, 121.04178),
(38, 'maintenance', 'Training Center', '../map_images/training center.png', 'The Training Center at Ninoy Aquino Parks and Wildlife Center is designed for educational programs and workshops focused on wildlife conservation and environmental management. It serves as a venue for training park staff, volunteers, and the public, fostering knowledge and skills essential for preserving the Philippines\' rich biodiversity.', 14.65259, 121.04221),
(39, 'maintenance', 'Maintenance', '../map_images/maintenance.png', 'The Maintenance Team at Ninoy Aquino Parks and Wildlife Center ensures the park\'s facilities and landscapes are well-kept and safe for visitors. They are responsible for the upkeep of pathways, gardens, and wildlife enclosures, supporting the center\'s mission to provide a clean and enjoyable environment for everyone.', 14.65267, 121.04202),
(40, 'attractions', 'Gazebo', '../map_images/Gazebo.png', 'The gazebo at Ninoy Aquino Parks and Wildlife Center offers a charming space for relaxation and contemplation amidst nature. Surrounded by lush greenery, it provides a perfect spot for visitors to unwind, enjoy scenic views, or gather with family and friends while exploring the beauty of the park.', 14.65104, 121.04275),
(41, 'monuments', 'Bulwagan Ninoy', '../map_images/bulawagangninoy.png', 'Bulwagan Ninoy at Ninoy Aquino Parks and Wildlife Center is a versatile multi-purpose hall used for events, workshops, and educational programs. Named in honor of Senator Benigno \"Ninoy\" Aquino Jr., this facility promotes environmental awareness and conservation initiatives, serving as a gathering place for the community.', 14.65175, 121.04277),
(42, 'maintenance', 'Basketball Court', '../map_images/basketbnall court.png', 'The Basketball Court at Ninoy Aquino Parks and Wildlife Center offers a lively space for sports enthusiasts to enjoy a game in a natural setting. Surrounded by greenery, this well-maintained court provides a perfect spot for visitors to engage in friendly matches and stay active while enjoying the park\'s serene atmosphere.', 14.65201, 121.044),
(43, 'waypoint', 'Parking Area', '../map_images/parking.png', 'The parking area at Ninoy Aquino Parks and Wildlife Center offers ample space for visitors, ensuring convenient access to the park’s attractions. Strategically located near the main entrances, it provides a hassle-free experience for those exploring the lush landscapes and facilities within the center.', 14.65202, 121.04485),
(44, 'monuments', 'National Wildlife Research & Rescue Center', '../map_images/wildlif_rescue_center.png', 'The National Wildlife Research and Rescue Center at Ninoy Aquino Parks and Wildlife Center focuses on the conservation and rehabilitation of wildlife in the Philippines. It conducts research, provides medical care, and facilitates the rescue of injured or endangered species, playing a crucial role in protecting the country\'s biodiversity.', 14.65174, 121.04536),
(45, 'attractions', 'Crocodile Cage', '../map_images/crocodile cage.jpg', 'The Crocodile Cage at Ninoy Aquino Parks and Wildlife Center provides a safe habitat for these fascinating reptiles, showcasing the Philippine saltwater and freshwater crocodile species. Visitors can observe these powerful creatures up close while learning about their behavior, conservation status, and the importance of protecting their natural habitats.', 14.65135, 121.04592),
(46, 'attractions', 'Serpentarium', '../map_images/SERPENTARIUM.png', 'The Serpentarium at Ninoy Aquino Parks and Wildlife Center is home to a diverse collection of snakes and reptiles, showcasing various species native to the Philippines and beyond. Visitors can learn about these fascinating creatures through informative displays and exhibits, promoting awareness and appreciation for the importance of reptile conservation.', 14.65101, 121.04588),
(47, 'attractions', 'Monkey Cage', '../map_images/monkey.jpg', 'The Monkey Cage at Ninoy Aquino Parks and Wildlife Center offers an engaging glimpse into the lives of various monkey species. Visitors can observe these playful and social animals in a spacious habitat designed to mimic their natural environment, providing insight into their behaviors and the importance of wildlife conservation.', 14.6508, 121.0462),
(48, 'attractions', 'Bird Cage 2', '../map_images/birdcage.jpg', 'The Bird Cage at Ninoy Aquino Parks and Wildlife Center features a diverse collection of native bird species, showcasing the rich avian life of the Philippines. This spacious habitat allows visitors to observe these beautiful birds in a natural setting, promoting awareness of wildlife conservation and the importance of protecting their environments.', 14.65021, 121.04605),
(49, 'attractions', 'Deer Cage', '../map_images/deer.jpg', 'The Deer Cage at Ninoy Aquino Parks and Wildlife Center is home to several species of deer native to the Philippines. Visitors can observe these graceful animals in a naturalistic setting, offering a glimpse into their behavior and habitat while promoting awareness of conservation efforts for these gentle creatures.', 14.65043, 121.04635);

-- --------------------------------------------------------

--
-- Table structure for table `messaging`
--

CREATE TABLE `messaging` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL,
  `message` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messaging`
--

INSERT INTO `messaging` (`id`, `name`, `email`, `message`) VALUES
(1, 'Test', 'test@gmail.com', 'testing'),
(2, 'tester1', 'testing@gmail.com', 'testmessage'),
(3, 'Michael O. Go', 'go@gmail.com', 'Hi there Admin, this is a test message.'),
(4, 'Ralph Denzell L. Tanteo', 'ralph@gmail.com', 'This is still a test message.'),
(5, 'test', 'test@gmail.com', '123'),
(6, 'Jackhammer 3000', '123@gmail.com', '12312312'),
(7, 'Ralph Denzell', 'iamralphdenzelltanteo@gmail.com', 'Hello Admin, this is a test Message'),
(8, 'Ralph Denzell', 'iamralphdenzelltanteo@gmail.com', 'Hi Admin, this is a test message.');

-- --------------------------------------------------------

--
-- Table structure for table `trees`
--

CREATE TABLE `trees` (
  `id` int(11) NOT NULL,
  `tree_name` varchar(100) DEFAULT NULL,
  `scientific_name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `habitat` varchar(255) DEFAULT NULL,
  `distribution` varchar(255) DEFAULT NULL,
  `conservation_status` varchar(50) DEFAULT NULL,
  `tree_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trees`
--

INSERT INTO `trees` (`id`, `tree_name`, `scientific_name`, `description`, `habitat`, `distribution`, `conservation_status`, `tree_picture`) VALUES
(2, 'Apitong', 'Dipterocarpus grandiflorus', 'Apitong is a tall tree with a straight trunk and large, simple leaves. It belongs to the dipterocarp family and produces winged fruits.', 'Dipterocarp forests, often found in lowland areas.', 'Native to Southeast Asia, including the Philippines.', 'Not listed as threatened.', '../tree_images/apitong2.jpg'),
(3, 'Narra', 'Pterocarpus indicus', 'Narra is a large deciduous tree with pinnate leaves and clusters of yellow flowers. The wood is highly valued for its color and durability.', 'Found in various forest types, including dipterocarp and montane forests.', 'Native to Southeast Asia, including the Philippines.', 'Not listed as threatened.', '../tree_images/narra1.jpg'),
(4, 'Philippine Teak', 'Tectona philippinensis', 'Philippine Teak is a deciduous tree with large, opposite leaves. It produces clusters of white flowers. The wood is highly valued for its durability and is used in various applications.', 'It is found in lowland and montane forests.', 'Endemic to the Philippines.', 'Critically Endangered. Conservation efforts are fo', '../tree_images/teak1.jpg'),
(5, 'Philippine Ironwood', 'Xanthostemon verdugonianus', 'Philippine Ironwood is an evergreen tree with lance-shaped leaves and clusters of bright yellow flowers. The wood is dense and durable.', 'Typically found in lowland forests and coastal areas.', 'Endemic to the Philippines.', 'Vulnerable. Threatened by habitat loss due to logg', '../tree_images/ironwood2.jpg'),
(6, 'Tindalo', 'Afzelia rhomboidea', 'Tindalo is a large tree with compound leaves and distinctive rhomboid-shaped leaflets. It produces pods with large seeds.', 'Tindalo trees are commonly found in dipterocarp forests.', 'Native to the Philippines.', 'Near Threatened. Faces threats from logging and ha', '../tree_images/tindalo1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `first_name` varchar(80) NOT NULL,
  `middle_name` varchar(80) NOT NULL,
  `last_name` varchar(80) NOT NULL,
  `organization` varchar(80) NOT NULL,
  `password` varchar(80) NOT NULL,
  `address` varchar(80) NOT NULL,
  `contact` varchar(11) NOT NULL,
  `status` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `first_name`, `middle_name`, `last_name`, `organization`, `password`, `address`, `contact`, `status`, `email`) VALUES
(32, 'John', 'John Bruce ', 'Reataza', 'Balangatan', 'Non-Government', '$2y$10$Jm6M/iMEdrwXlAbHDDAWQ.GJVZaODI683D/lQSGUg/vBQSUNHoJQu', 'Montalban, Rizal', '09987654321', 'Inactive', 'brus@gmail.com'),
(33, 'Ralph', 'Ralph Denzell', 'Legaspi', 'Tanteo', 'Non-Government', '$2y$10$fH9/67fhz/BRhhMas6Iw7u7r2N45pDQ6UTyvvyHlivI4Cwro.LM6m', 'Lubang, Occ. Mindoro', '09289097027', 'Inactive', 'iamralphdenzelltanteo@gmail.com'),
(34, 'Charlene', 'Charlene Joie', 'Mendoza', 'De las Alas', 'Non-Government', '$2y$10$0f46SIGIbPVN3w6oM/.0eu/Pccc.gED68ufBfeVMu7Dv3xyxTQ2Z.', 'Jasmine St.', '09398932236', 'Active', 'hatdog@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_name` (`admin_name`),
  ADD KEY `admin_password` (`admin_password`);

--
-- Indexes for table `booking_records`
--
ALTER TABLE `booking_records`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_name` (`event_name`),
  ADD KEY `event_month` (`event_image`),
  ADD KEY `event_description` (`event_description`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incident_report`
--
ALTER TABLE `incident_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `map`
--
ALTER TABLE `map`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_record` (`name`,`image`,`description`,`latitude`,`longitude`) USING HASH;

--
-- Indexes for table `messaging`
--
ALTER TABLE `messaging`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trees`
--
ALTER TABLE `trees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `first_name` (`first_name`),
  ADD KEY `middle_name` (`middle_name`),
  ADD KEY `last_name` (`last_name`),
  ADD KEY `organization` (`organization`),
  ADD KEY `password` (`password`),
  ADD KEY `address` (`address`),
  ADD KEY `contact` (`contact`),
  ADD KEY `status` (`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `booking_records`
--
ALTER TABLE `booking_records`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `incident_report`
--
ALTER TABLE `incident_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `map`
--
ALTER TABLE `map`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messaging`
--
ALTER TABLE `messaging`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `trees`
--
ALTER TABLE `trees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
