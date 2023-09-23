-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2021 at 11:32 AM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hvn2001`
--

-- --------------------------------------------------------

--
-- Table structure for table `hms_service_results`
--

CREATE TABLE `hms_service_results` (
  `Source` varchar(20) DEFAULT '',
  `SR_Closed_Date__Time` varchar(20) DEFAULT '',
  `Job_Card` varchar(50) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Account_Phone` varchar(20) DEFAULT '',
  `Created_By` varchar(50) DEFAULT '',
  `Division` varchar(50) DEFAULT NULL,
  `First_Sale_Date` varchar(20) DEFAULT '',
  `Reason_for_Cancellation` varchar(255) DEFAULT NULL,
  `Selling_Dealer` varchar(50) DEFAULT '',
  `Last_Visit_date` varchar(20) DEFAULT '',
  `Last_Visit_Km` varchar(20) DEFAULT NULL,
  `Warranty_Expiry_Date` varchar(20) DEFAULT '',
  `SR` varchar(50) DEFAULT '',
  `Service_Type` varchar(50) DEFAULT '',
  `Frame` varchar(50) DEFAULT '',
  `Current_Kms` varchar(20) DEFAULT NULL,
  `Last_Name` varchar(50) DEFAULT '',
  `First_Name` varchar(50) DEFAULT '',
  `Company` varchar(20) DEFAULT '',
  `Status` varchar(20) DEFAULT '',
  `Operation` varchar(20) DEFAULT NULL,
  `Other_Vehicle_Types` varchar(50) DEFAULT NULL,
  `Plate` varchar(50) DEFAULT '',
  `Parts_Price_List` varchar(22) DEFAULT '',
  `Service_Receptionist_attended_Date_Time` varchar(20) DEFAULT '',
  `Booking_Date` varchar(20) DEFAULT NULL,
  `Labour_Rate_List` varchar(50) DEFAULT '',
  `Repeat_Complaint_Flag` varchar(1) DEFAULT '',
  `Repeat_Complaint_Reason` varchar(50) DEFAULT NULL,
  `SR_Created_Date__Time` varchar(20) DEFAULT '',
  `Latest_Contact_No` varchar(50) DEFAULT NULL,
  `Type_of_Contact` varchar(50) DEFAULT NULL,
  `Committed` varchar(50) DEFAULT NULL,
  `Fuel_Level` varchar(50) DEFAULT NULL,
  `Loyalty_Card` varchar(50) DEFAULT NULL,
  `Service_campaign_Recall_Code` varchar(50) DEFAULT NULL,
  `Temporary_Frame_No` varchar(50) DEFAULT NULL,
  `Observation` varchar(50) DEFAULT NULL,
  `Summary` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hms_service_results`
--

INSERT INTO `hms_service_results` (`Source`, `SR_Closed_Date__Time`, `Job_Card`, `Description`, `Account_Phone`, `Created_By`, `Division`, `First_Sale_Date`, `Reason_for_Cancellation`, `Selling_Dealer`, `Last_Visit_date`, `Last_Visit_Km`, `Warranty_Expiry_Date`, `SR`, `Service_Type`, `Frame`, `Current_Kms`, `Last_Name`, `First_Name`, `Company`, `Status`, `Operation`, `Other_Vehicle_Types`, `Plate`, `Parts_Price_List`, `Service_Receptionist_attended_Date_Time`, `Booking_Date`, `Labour_Rate_List`, `Repeat_Complaint_Flag`, `Repeat_Complaint_Reason`, `SR_Created_Date__Time`, `Latest_Contact_No`, `Type_of_Contact`, `Committed`, `Fuel_Level`, `Loyalty_Card`, `Service_campaign_Recall_Code`, `Temporary_Frame_No`, `Observation`, `Summary`) VALUES
('Maintenance', '17/09/2021 14:38', NULL, NULL, '', 'HCR004_14003', NULL, '########', NULL, 'HONG HANH', '', NULL, '', 'SR-14003-02-2109-00027', 'General Repair - OC', 'RLHJF8108AA0019939', '100', 'VAN A', 'NGUYEN', '', 'New', NULL, NULL, '', 'Spare Price List-14003', '########', NULL, 'Labour Rate List', 'N', NULL, '17/09/2021 14:38', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('Maintenance', '17/09/2021 14:38', NULL, NULL, '', 'HCR004_14003', NULL, '########', NULL, 'HONG HANH', '########', '8754', '########', 'SR-14003-02-2109-00028', 'OTHERS', 'RLHJA3829GY015665', '50', 'VAN A', 'NGUYEN', '', 'New', NULL, NULL, '88E139719', 'Spare Price List-14003', '########', NULL, 'Labour Rate List', 'N', NULL, '17/09/2021 14:42', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('Maintenance', '17/09/2021 14:38', NULL, NULL, '6,67E+23', 'HCR004_14003', NULL, '', NULL, 'HONG HANH', '########', '200', '########', 'SR-14003-02-2109-00029', 'General Repair - HR', 'RLHJF24019Y123717', '34343434', 'VAN A', 'NGUYEN', '*NGA', 'New', NULL, NULL, '0', 'Spare Price List-14003', '########', NULL, 'Labour Rate List', 'N', NULL, '17/09/2021 17:44', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
