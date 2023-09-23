-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2021 at 09:33 AM
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
-- Table structure for table `hms_part_order_plan_detail`
--

CREATE TABLE `hms_part_order_plan_detail` (
  `line_number` varchar(5) default null,
  `part_no` varchar(50) default '',
  `part_description` varchar(255),
  `quantity_requested` double(10,2) default null,
  `status` varchar(50),
  `part_category` varchar(16) default '',
  `remarks` varchar(255) default null,
  `dnp` double(10,2) default null,
  `total_amount` varchar(11) default '',
  `abnormal_status` varchar(0) default null,
  `allocated_qty` double(10,2) default null,
  `so_no` varchar(50) default null,
  `back_order_qty` varchar(50) default null,
  `eta` varchar(50) default null,
  `etd` varchar(50) default null,
  `sap_status` varchar(50) default null,
  `cancel_quantity` double(10,2) default null,
  `dnp_discount_rate` varchar(50) default null,
  `noticemark` varchar(50) default null,
  `order_number` varchar(50) default '',
  `weighted_average` varchar(50) default null
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hms_part_order_plan_detail`
--

insert into `hms_part_order_plan_detail` (`line_number`, `part_no`, `part_description`, `quantity_requested`, `status`, `part_category`, `remarks`, `dnp`, `total_amount`, `abnormal_status`, `allocated_qty`, `so_no`, `back_order_qty`, `eta`, `etd`, `sap_status`, `cancel_quantity`, `dnp_discount_rate`, `noticemark`, `order_number`, `weighted_average`) values
('1', '2320AGAH305', 'Bộ tuýp mỡ', '2', 'New', 'NonDrop Shipment', NULL, '120000', '₫240,000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PO-14003-02-2110-00020', '1'),
('1.1', '2320AGAH305GR', 'Tuýp mỡ', '20', 'New', 'NonDrop Shipment', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PO-14003-02-2110-00020', '10'),
('2', '08CMCHIC60BOX', 'Thùng dung dịch rửa kim phun (20 chai)', '1', 'New', 'NonDrop Shipment', NULL, '860000', '₫860,000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PO-14003-02-2110-00020', '1'),
('2.1', '08CMCHIC60PCS', 'Nước rửa kim phun xăng 60ml', '20', 'New', 'NonDrop Shipment', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PO-14003-02-2110-00020', '20'),
('1', '082342MAK1LVC', 'Thùng dầu nhớt SL cho động cơ xe PKL 1.2', '2', 'New', 'Drop Shipment', NULL, '5424000', '₫10,848,000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PO-14003-02-2110-00018', '1'),
('1.1', '082342MAK1LV1', 'Dầu nhớt SL cho động cơ xe PKL 1.2L', '48', 'New', 'Drop Shipment', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PO-14003-02-2110-00018', '24'),
('2', '082322MAK9LVC', 'Thùng dầu nhớt SL cho động cơ xe số 1.2L', '4', 'New', 'Drop Shipment', NULL, '2124000', '₫8,496,000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PO-14003-02-2110-00018', '1'),
('2.1', '082322MAK9LV1', 'Dầu nhớt SL cho động cơ xe số 1.2L', '96', 'New', 'Drop Shipment', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PO-14003-02-2110-00018', '24'),
(NULL, '', '', NULL, '', '', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL),
('3.1', '082322MAK8LV1', 'Dầu nhớt SL cho động cơ xe số 0.8L', '48', 'New', 'Drop Shipment', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PO-14003-02-2110-00018', '24'),
('1', '04911K97J00', 'Thân máy trái (KF30E)', '15', 'New', 'NonDrop Shipment', NULL, '760000', '₫11,400,000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PO-14003-02-2110-00016', '15'),
('2', '00X4FK66A100', 'Sách hướng dẫn sử dụng', '15', 'New', 'NonDrop Shipment', NULL, '5000', '₫75,000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PO-14003-02-2110-00016', '15'),
('3', '04801K0G900ZB', 'Bộ ốp thân phải *PB415P*', '15', 'New', 'NonDrop Shipment', NULL, '1023000', '₫15,345,000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PO-14003-02-2110-00016', '15'),
('1', '06111KTG641', 'Bộ gioăng xy lanh', '1', 'New', 'NonDrop Shipment', NULL, '591571', '₫591,571', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PO-14003-02-2110-00015', '1');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
