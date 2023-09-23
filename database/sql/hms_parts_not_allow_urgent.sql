-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2021 at 05:36 PM
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
-- Table structure for table `hms_parts_not_allow_urgent`
--

CREATE TABLE `hms_parts_not_allow_urgent` (
  `part_no` varchar(50) DEFAULT '',
  `part_name_en` varchar(255) DEFAULT '',
  `part_name_vn` varchar(255) DEFAULT '',
  `category` varchar(50) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hms_parts_not_allow_urgent`
--

INSERT INTO `hms_parts_not_allow_urgent` (`part_no`, `part_name_en`, `part_name_vn`, `category`) VALUES
('06410KFL850', 'DAMPER SET,WHEEL', 'Cao su giảm chấn bánh xe', 'GR'),
('', '', '', ''),
('06430GCE305', 'SHOE SET,BRAKE', 'Bộ má phanh', 'PM'),
('06430K44V80', 'SHOE SET,BRAKE', 'Bộ má phanh', 'PM'),
('06430KPH900', 'SHOE SET,BRAKE', 'Bộ má phanh', 'PM'),
('06430KVB950', 'SHOE SET,BRAKE', 'Bộ má phanh sau', 'PM'),
('06435K01902', 'PAD SET, RR.', 'Bộ má phanh dầu sau', 'PM'),
('06435K0RV01', 'PAD SET,RR BRAKE', 'Bộ má phanh dầu sau', 'PM'),
('06435K56N01', 'PAD SET,RR', 'Bộ má phanh dầu sau', 'PM'),
('06435KPPT01', 'PAD SET_RR', 'Bộ má phanh dầu sau', 'PM'),
('', '', '', ''),
('', '', '', ''),
('', '', '', ''),
('06455K0RV01', 'PAD SET,FR', 'Bộ má phanh trước', 'PM'),
('06455K20911', 'PAD SET, FR', 'Bộ má phanh', 'PM'),
('06455K40F12', 'PAD SET, FR.', 'Má phanh trước', 'PM'),
('06455K56N01', 'PAD SET, FR. BRAKE', 'Bộ má phanh', 'PM'),
('', '', '', ''),
('06455KREK02', 'PAD SET,FR', 'Bộ má phanh dầu trước', 'PM'),
('06455KVB951', 'PAD SET,FR BRAKE', 'Bộ má phanh dầu trước', 'PM'),
('06455KVBT01', 'PAD SET FR BRAKE', 'Bộ má phanh dầu trước', 'PM'),
('06455KWB601', 'PAD SET FR BRAKE', 'Bộ má phanh dầu', 'PM'),
('', '', '', ''),
('11394KWB920', 'GSKT,R CRANKCASE COVER', 'Gioăng nắp máy phải', 'GR'),
('11395KPH901', 'GASKET L CRANK CASE COVER', 'Gioăng nắp máy trái', 'GR'),
('11395KWB920', 'GSKT,L CRANKCASE COVER', 'Gioăng nắp máy trái', 'GR'),
('12191KVB901', 'GASKET,CYLINDER', 'Gioăng chân xy lanh', 'GR'),
('12191KWB920', 'GASKET,CYLINDER', 'Gioăng chân xy lanh', 'GR'),
('12209GB4681', 'SEAL VALVE STEM', 'Phớt chắn dầu thân xu páp', 'GR'),
('12209GB4682', 'SEAL VALVE STEM', 'Phớt chắn dầu thân xu páp', 'GR'),
('', '', '', ''),
('', '', '', ''),
('13111087000', 'PIN,PISTON', 'Chốt piston', 'GR'),
('14401436003', 'CHAIN CAM', 'Xích cam (90mắt)', 'PM'),
('14401KFM900', 'CHAIN CAM (88L)', 'Xích cam (88mắt)', 'PM'),
('14500KYZ900', 'ARM COMP,CAM CHAIN TENSION', 'Cần căng xích cam', 'GR'),
('14502086000', 'ROLLER CAM CHAIN TENSIONER', 'Bánh căng xích cam', 'GR'),
('14541GB4681', 'SPRING,TENSIONER', 'Lò xo căng xích cam', 'GR'),
('14550GB0911', 'PUSH ROD COMP TENSIONER', 'Thanh căng xích cam', 'GR'),
('14566086030', 'HEAD TENSIONER PUSH ROD', 'Cao su đầu thanh căng xích cam', 'GR'),
('14610086010', 'ROLLER COMP CAM CHAIN GUIDE', 'Bánh dẫn xích cam', 'GR'),
('', '', '', ''),
('16700K44V01', 'PUMP UNIT, FUEL', 'Cụm bơm xăng', 'GR'),
('16910KFM902', 'STRAINER COMP FUEL', 'Lọc xăng', 'PM'),
('17205GN5900', 'ELEMENT AIR/C', 'Tấm lọc khí', 'PM'),
('17210GGE900', 'ELEMENT COMP,AIR/C', 'Tấm lọc khí', 'PM'),
('17210K01900', 'ELEMENT COMP,AIR/C', 'Tấm lọc gió', 'PM'),
('', '', '', ''),
('17210K0RV00', 'ELEMENT COMP,AIR/C', 'Tấm lọc khí', 'PM'),
('', '', '', ''),
('17210K1NV00', 'ELEMENT COMP,AIR/C', 'Tấm lọc khí', 'PM'),
('17210K29900', 'ELEMENT COMP,AIR/C', 'Tấm lọc khí', 'PM'),
('17210K2CV00', 'ELEMENT COMP,AIR/C', 'Tấm lọc khí', 'PM'),
('', '', '', ''),
('17210K56V00', 'ELEMENT COMP., AIR CLEANER', 'Tấm lọc khí', 'PM'),
('', '', '', ''),
('17210K73V40', 'ELEMENT COMP,AIR/C', 'Tấm lọc khí', 'PM'),
('17210K77V00', 'ELEMENT COMP., AIR CLEANER', 'Tấm lọc khí', 'PM'),
('17210KPH900', 'ELEMENT COMP,AIR/C', 'Tấm lọc khí', 'PM'),
('17210KVB930', 'ELEMENT COMP,AIR/C', 'Tấm lọc khí', 'PM'),
('17210KVGV20', 'ELEMENT COMP., AIR CLEANER', 'Tấm lọc khí', 'PM'),
('17210KWWB20', 'ELEMENT COMP,AIR/C', 'Tấm lọc khí', 'PM'),
('17210KWZ900', 'ELEMENT COMP,AIR/C', 'Tấm lọc khí', 'PM'),
('', '', '', ''),
('17210KZR600', 'ELEMENT COMP,AIR/C', 'Tấm lọc khí', 'PM'),
('17220KZLE00', ',ELEMENT SET, AIR CLEANER(WL)', 'Bộ lọc khí', 'PM'),
('', '', '', ''),
('18318K29900ZA', 'PROTECTOR, MUFFLER NH-411M', 'Tấm chắn nhiệt ống xả*NH411M*', 'BP'),
('18318K44V00', 'PROTECTOR, MUFFLER', 'Tấm cách nhiệt a ống xả', 'BP'),
('18318K66V00', 'PROTECTOR_MUFFLER', 'Tấm cách nhiệt ống xả', 'BP'),
('18318KVG950', 'PROTECTOR A, MUFFLER', 'Tấm cách nhiệt A ống xả', 'BP'),
('21395KVB901', 'GASKET,MISSION CASE', 'Gioăng hộp số', 'GR'),
('22011KVBT00', '3IECE SET, SLIDE', 'Bộ kẹp trượt', 'GR'),
('22011KWN900', 'PIECE SET,SLIDE', 'Bộ kẹp trượt', 'GR'),
('22100KWN900', 'OUTER COMP,CLUTCH', 'Nồi ly hợp', 'GR'),
('22102K01900', 'FACE, DRIVE', 'Đĩa ép sơ cấp', 'GR'),
('', '', '', ''),
('22102KZR600', 'FACE,DRIVE', 'Đĩa ép sơ cấp', 'GR'),
('22105KVY900', 'BOSS, DRIVE FACE', 'Lõi trượt', 'GR'),
('22105KWN900', 'BOSS,DRIVE FACE', 'Lõi trượt puly chủ động', 'GR'),
('', '', '', ''),
('22110K01900', 'FACE COMP., MOVABLE DRIVE', 'Má động pu ly chủ động', 'GR'),
('22110K44V00', 'FACE COMP., MOVABLE DRIVE', 'Đĩa nângsơ cấp', 'GR'),
('22110KVB900', 'FACE COMP.,MOVABLE DRIVE', 'Má động pu ly chủ động', 'GR'),
('22110KZR601', 'FACE COMP,MOVABLE DRIVE', 'Má động pu ly chủ động', 'GR'),
('22123K44V00', 'ROLLER SET, WEIGHT', 'Bộ bi văng', 'GR'),
('22123KVB900', 'ROLLER SET, WEIGHT', 'Bộ bi văng', 'GR'),
('22123KWN710', 'ROLLER SET WEIGHT', 'Bộ bi văng', 'GR'),
('22123KWN900', 'ROLLER SET,WEIGHT', 'Bộ bi văng', 'GR'),
('', '', '', ''),
('', '', '', ''),
('22321KWW742', 'PLATE_CLUTCH', 'Đĩa sắt ly hợp', 'GR'),
('22535K44V00', 'WEIGHT SET, CLUTCH', 'Bộ guốc văng ly hợp', 'GR'),
('22535KVB900', 'WEIGHT SET,CLUTCH', 'Guốc văng ly hợp sơ cấp', 'GR'),
('', '', '', ''),
('22804GB2000', 'RUBBER,CLUTCH DAMPER', 'Cao su giảm chấn guốc văng', 'GR'),
('23100GFM901', 'BELT,DRIVE', 'Dây đai truyền động', 'PM'),
('', '', '', ''),
('', '', '', ''),
('', '', '', ''),
('23100K0SV01', 'BELT,DRIVE', 'Dây đai truyền chuyển động', 'PM'),
('23100K1NV01', 'BELT,DRIVE', 'Dây đai truyền chuyển động', 'PM'),
('23100K35V01', 'BELT,DRIVE', 'Dây đai truyền chuyển động', 'PM'),
('23100K44V01', 'BELT, DRIVE', 'Dây đai truyền chuyển động', 'PM'),
('23100KVB901', 'BELT,DRIVE', 'Dây đai truyền động', 'PM'),
('23100KZL931', 'BELT,DRIVE', 'Dây đai truyền chuyển động', 'PM'),
('23100KZR601', 'BELT, DRIVE(BANDO)', 'Dây đai truyền chuyển động', 'PM'),
('23801KBP900', 'SPROCKET_DRIVE 15T', 'Nhông trước 15T', 'PM'),
('28120KVB901', 'PINION ASSY, STARTER', 'Bộ bánh răng khởi động', 'GR'),
('', '', '', ''),
('31200KPW901', 'MOTOR ASSY STARTER', 'Cụm môtơ đề', 'GR'),
('', '', '', ''),
('31201KPW901', 'BRUSH TERMINAL SET', 'Chổi than', 'GR'),
('31210K90VC1', 'MOTOR UNIT,START', 'Cụm mô tơ máy đề', 'GR'),
('31210KTL780', 'MOTOR UNIT STARTER', 'Cụm mô tơ máy đề', 'GR'),
('31210KVB951', 'MOTOR UNIT,START', 'Mô tơ đề', 'GR'),
('31500K01901', 'BATTERY(GTZ6V)(GS YUASA)', 'Bình ắc quy GTZ6V', 'PM'),
('31500KPHB31', 'BATTERY (YTZ5S) 3AH', 'Bình ắc quy (GTZ5S12V 3.5A)', 'PM'),
('', '', '', ''),
('31916KWB601', 'SPARK PLUG (CPR6EA-9S)(NGK)', 'Bugi (CPR6EA9S)(NGK)', 'PM'),
('31917K0RV01', 'SPARK PLUG LMAR8L-9', 'Bugi LMAR8L-9', 'PM'),
('31917KPH901', 'PLUG, SPARK (CPR7EA-9) (NGK)', 'Bugi (CPR7EA9) (NGK)', 'PM'),
('31917KWPD01', 'SPARK PLUG MR7C-9N', 'Bugi MR7C-9N', 'PM'),
('31918K44V01', 'PLUG, SPARK(MR8C-9N)(NGK)', 'Bugi 58', 'PM'),
('31919K25601', 'SPARK PLUG MR9C-9N', 'Bugi MR9C-9N', 'PM'),
('', '', '', ''),
('31926KWB601', 'SPARK PLUG (U20EPR9S) (DENSO)', 'Bugi (U20EPR9S)(DENSO)', 'PM'),
('31927KPH901', 'PLUG, SPARK (U22EPR9) (DENSO)', 'Bugi (U22EPR9)(DENSO)', 'PM'),
('32101GW3780', 'BRUSHI SET', 'Chổi than mô tơ đề', 'GR'),
('33120KTL641', 'UNIT HEAD LIGHT', 'Cụm đèn pha', 'BP'),
('33402KTL641', 'LENS COMP R FR WINKER', 'Nắp đèn xi nhan trước phải', 'BP'),
('33420K44V81', 'FR WINKER UNIT,R', 'Đèn xi nhan trước phải', 'BP'),
('33452KTL641', 'LENS COMP L FR WINKER', 'Nắp đèn xi nhan trước trái', 'BP'),
('', '', '', ''),
('33710KTL641', 'FENDER BASE COMP', 'Bộ chóa đèn & chắn bùn sau', 'BP'),
('34901K57V01', 'BULB, HEADLIGHT(HS1)', 'Bóng đèn pha trước', 'BP'),
('34901KRS901', 'BULB HEAD LIGHT', 'Bóng đèn trước', 'BP'),
('34905KANW01', 'BULB, WINKER (12V 10W)', 'Bóng đèn xi nhan (12V10W)', 'BP'),
('34906GB6921', 'BULB,TAIL LIGHT', 'Bóng đèn hậu (12V18/5W)', 'BP'),
('34908GA7701', 'BULB,WEDGE BASE (12V3.4W)', 'Bóng đèn T10 (12V 3.4W)', 'BP'),
('35150KWWA01', 'SW UNIT,LIGHTING', 'Công tắc đèn pha', 'GR'),
('35150KYZV02', 'SW UNIT,LIGHTING', 'Công tắc đèn pha', 'GR'),
('35160K12V81', 'SW UNIT,START(WATER PROOF)', 'Công tắc khởi động', 'GR'),
('', '', '', ''),
('38110KWWB21', 'HORN COMP', 'Còi xe', 'GR'),
('40530K56N12', 'CHAIN, DRIVE(KMC)(428HG-120L)', 'Xích KMC 428-120', 'PM'),
('40545001000', 'CAP CHAIN CASE PEEF HOLE', 'Nắp kiểm tra xích', 'GR'),
('41200K56V01', 'SPROCKET COMP., FINAL DRIVEN', 'Nhông tải sau', 'PM'),
('41200K56V50', 'SPRKT COMP,FINAL DRIVEN 44T J', 'Nhông tải sau', 'PM'),
('42711GGEYK0', 'TIRE 100/90-10 61J (YOKOHAMA)', 'Lốp sau (100/9010 61J) (YOKOHAMA)', 'PM'),
('42711K01902', 'TIRE.REAR (IRC)', 'Lốp sau', 'PM'),
('42711K29IR0', 'TIRE, RR (100/90-14M/C 57P) (INOUE)', 'Lốp sau (100/90-14M/C 57P) (INOUE)', 'PM'),
('', '', '', ''),
('42711KTM972', 'TIRE,RR.(IRC)(80/90-17M/C 50P)', 'Lốp sau (80/9017 MC 50P NR69)', 'PM'),
('42711KVGV41', 'TIRE,RR (IRC)(90/90-14M/C 46P)', 'Lốp sau (IRC)(90/9014 46P)', 'PM'),
('42712KWWB22', 'TUBE, TIRE (VEE)(80/90-17)', 'Săm xe (80/9017)', 'PM'),
('44301KVG950', 'AXLE,FR WHEEL', 'Trục bánh trước', 'GR'),
('44711GGEYK0', 'TIRE 90/90-12 44J (YOKOHAMA)', 'Lốp trước (90/9012 44J) (YOKOHAMA)', 'PM'),
('44711K01902', 'TIRE,FRONT(IRC)', 'Lốp trước', 'PM'),
('44711K29IR0', 'TIRE, FR (80/90-16M/C 43P) (INOUE)', 'Lốp trước (80/90-16M/C 43P) (INOUE)', 'PM'),
('44711K56V01', 'TIRE, FR.(IRC)', 'Lốp trước (IRC)', 'PM'),
('44711KTM972', 'TIRE,FR(IRC)', 'Lốp trước (IRC)(70/10017 40P N', 'PM'),
('', '', '', ''),
('44711KWWB21', 'TIRE,FRONT IRC (70/90-17 MC 38', 'Lốp trước IRC (70/9017 MC 38P)', 'PM'),
('44712KWWB22', 'TUBE,TIRE VEE (70/90-17)', 'Săm trước VEE (70/9017)', 'PM'),
('44800KVB910', 'GEAR BOX ASSY,SPDMT', 'Hộp bánh răng đo tốc độ', 'GR'),
('44800KWB600', 'GEAR BOX ASSY,SPDMT', 'Hộp bánh răng đo tốc độ', 'GR'),
('44800KWW650', 'GEAR BOX ASSY,SPDMT', 'Hộp bánh răng đo tốc độ', 'GR'),
('44830GGE900', 'CABLE COMP,SPDMT', 'Dây công tơ mét', 'GR'),
('44830KTL690', 'CABLE COMP,SPDMT', 'Dây công tơ mét', 'GR'),
('', '', '', ''),
('44830KWW640', 'CABLE ASSY, SPDMT', 'Dây công tơ mét', 'GR'),
('44830KWWB30', 'CABLE COMP,SPDMT', 'Dây công tơ mét', 'GR'),
('44830KZLE00', 'CABLE COMP,SPDMT', 'Dây công tơ mét', 'GR'),
('45132166016', 'BUSH,PIN', 'Đệm cao su chốt trượt', 'GR'),
('45133MA3006', 'BOOT,B', 'Phớt chắn bụi chốt trượt B', 'GR'),
('45251KWWB11', 'DISK FRONT BRAKE', 'Đĩa phanh dầu trước', 'GR'),
('45351KVG951', 'DISK,FR BRAKE', 'Đĩa phanh trước', 'GR'),
('', '', '', ''),
('45530KVY911', 'CYLINDER SET, MASTER', 'Xylanh phanh CBS', 'GR'),
('5053AKPHE20', 'BAR COMP SIDE STAND SET', 'Bộ chân chống bên', 'GR'),
('50661GN8920', 'RUBBER,STEP', 'Cao su để chân chính', 'GR'),
('50661KWB600', 'RUBBER,STEP', 'Cao su để chân chính', 'GR'),
('5071AGN5830', 'STEP SET R', 'Cụm để chân sau phải', 'GR'),
('', '', '', ''),
('51490GAA305', 'SEAL SET,FR.FORK', 'Bộ phớt giảm xóc trước', 'GR'),
('51490KGH901', 'SEAL SET FR FORK', 'Bộ phớt giảm xóc trước', 'GR'),
('52400KTL681', 'CUSHION ASSY., RR', 'Bộ giảm xóc sau', 'GR'),
('53140KVBT00', 'GRIP COMP., THROTTLE', 'Tay ga', 'BP'),
('53140KWW620', 'GRIP COMP,THORT', 'Tay ga', 'BP'),
('53166K29900', 'GRIP,L HANDLE', 'Tay nắm bên trái', 'BP'),
('53166KWB600', 'GRIP,L HANDLE', 'Tay nắm bên trái', 'BP'),
('53175KET921', 'LEVER R STRG HNDL', 'Tay phanh bên phải', 'BP'),
('', '', '', ''),
('53175KVB921', 'LEVER,R STRG HANDLE', 'Tay phanh bên phải', 'BP'),
('53175KWWC00', 'LEVER,R STRG HNDL', 'Tay phanh bên phải', 'BP'),
('53178KVB920', 'LEVER,L STRG HANDLE', 'Tay phanh bên trái', 'BP'),
('53178KZL940', 'LEVER L, STRG HANDLE', 'Tay phanh bên trái', 'BP'),
('53178MCT006', 'LEVER COMP.L HNDL', 'Tay phanh trái', 'BP'),
('', '', '', ''),
('53205K89V00ZB', 'COVER_HANDLE FR(D', 'Ốp tay lái trước *NHB55P*', 'BP'),
('53206K89V00ZA', 'COVER, RR. HANDLE', 'Nắp sau tay lái *NH1*', 'BP'),
('53206KTL640', 'COVER, HANDLE RR', 'Nắp sau tay lái', 'BP'),
('53206KVG950', 'COVER,HANDLE RR', 'Nắp sau tay lái', 'BP'),
('53206KZLE00ZA', 'COVER,HANDLE RR NH-411M', 'Nắp sau tay lái *NH411M*', 'BP'),
('', '', '', ''),
('5321AGN5900', 'RACE,STRG SET', 'Bộ bát phuốc', 'GR'),
('61200KTL640', 'FENDER B,FR.', 'Chắn bùn B trước', 'BP'),
('64250K89V00ZA', 'COVER SET, L. MAIN PIPE SIDE', 'Bộ ốp yếm trái *NH411M*', 'BP'),
('64308K44V00ZA', 'COVER SET, FR.(WL)*NH-1*', 'Bộ mặt nạ trước *NH-1*', 'BP'),
('64310K03M60ZA', 'COVER,MAIN PIPE NH1', 'Ốp ống yếm chính NH1', 'BP'),
('64310K44V00ZA', 'STEP, FLOOR *YR286R*', 'Bộ ốp sàn để chân *YR286R*', 'BP'),
('64310K44V00ZB', 'STEP, FLOOR *NH1*', 'Bộ ốp sàn để chân *NH1*', 'BP'),
('64311K66V00', 'STEP_R FLOOR     COWL', 'Tấm bắt sàn phải', 'BP'),
('64321K66V00', 'STEP_L FLOOR     NANCE', 'Tấm bắt sàn trái', 'BP'),
('', '', '', ''),
('64530K66V00', 'COVER_UNDER      R_REAR', 'Tấm ốp sàn dưới', 'BP'),
('72148S04000', 'BATTERY(CR2032)', 'Pin điều khiển', 'BP'),
('80131K44V00ZA', 'COVER, L. RR. WINKER*NH1*', 'Nắp ốp xi nhan sau trái *NH1*', 'BP'),
('81132KTF670', 'HOOK,LUGGAGE', 'Móc treo đồ', 'BP'),
('', '', '', ''),
('', '', '', ''),
('88110K03M60', 'MIRROR ASSY,R BACK', 'Bộ gương phải', 'BP'),
('88110KVGV40', 'MIRROR ASSY,R BACK', 'Bộ gương phải', 'BP'),
('88114K29900', 'BOOT,MIRROR', 'Cao su chụp chân gương', 'BP'),
('88120K03M60', 'MIRROR ASSY,L BACK', 'Bộ gương trái', 'BP'),
('88120K12900', 'MIRROR ASSY,L BACK', 'Cụm gương trái', 'BP'),
('88120K29901', 'MIRROR ASSY, L ASSY', 'Cụm gương trái', 'BP'),
('', '', '', ''),
('88120KZLE00', 'MIRROR ASSY,L BACK', 'Bộ gương trái', 'BP'),
('90116SP0003', 'CLIP, BUMPER SEAL', 'Chốt cài', 'GR'),
('90301GFCB50', 'LOCK NUT', 'Đai ốc chân gương (ren ngược)', 'GR'),
('90302KWWA00', 'NUT, SPRING 4MM', 'Đai ốc 4MM', 'GR'),
('', '', '', ''),
('90407259000', 'PACKING,DRAIN COCK', 'Đệm nhôm ốc xả dầu 12,5x20mm', 'GR'),
('90666K59A11', 'CLIP, SNAP FIT (PO)', 'Kẹp ốp yếm trước', 'GR'),
('90677KANT00', 'NUT,CLIP 5MM', 'Đai ốc kẹp 5mm', 'GR'),
('91001KCW003', 'BEARING,NEEDLE,20X29X18', 'Vòng bi đũa 20x29x18', 'GR'),
('91002GA7701', 'BEARING,RADIALBALL,6902U(NACHI', 'Vòng bi 6902U', 'GR'),
('91005KVB900', 'BEARING COMP., RADIAL BALL', 'Vòng bi', 'GR'),
('91201GB4691', 'OIL SEAL 30X42X4.5', 'Phớt dầu 30x42x4.5', 'GR'),
('', '', '', ''),
('91202GCC000', 'OIL SEAL, 20.8X53X9', 'Phớt dầu 20.8x53x9', 'GR'),
('91202KJ9003', 'OIL SEAL 20X32X6', 'Phớt dầu 20X32X6', 'GR'),
('91202KVB901', 'OIL SEAL 20.8X52X6X7.5', 'Phớt dầu 20.8X52X6X7.5', 'GR'),
('91202KWN901', 'OIL SEAL,26X45X6', 'Phớt dầu 26x45x6', 'GR'),
('', '', '', ''),
('91251KPH901', 'DUST SEAL,21X37X7', 'Phớt moay ơ trước 21x37x7', 'GR'),
('91251KZR601', 'DUST SEAL,21X32X5', 'Phớt chắn bụi', 'GR'),
('91255KVB901', 'SEAL,OIL 29X44X7', 'Phớt dầu 29X44X7', 'GR'),
('91302KEV900', 'O RING 30.8X3', 'Phớt O nắp xu páp 30,8x3', 'GR'),
('', '', '', ''),
('91509GE2760', 'SCREW PAN 5X11.5', 'Vít 5x11.5', 'GR'),
('9390334380', 'SCREW, TAPPING, 4X12', 'Vít tự ren 4x12', 'GR'),
('9410912000', 'WASHER, DRAIN PLUG, 12MM', 'Đệm bu lông xả nhớt 12MM', 'GR'),
('961406201010', 'BEARING,BALL,RADIAL,6201', 'Vòng bi 6201', 'GR'),
('961406203010', 'BEARING.BALL RADIAL 6203', 'Vòng bi 6203', 'GR'),
('961406301010', 'BEARING,BALL,RADIAL,6301', 'Vòng bi 6301', 'GR'),
('9717221157F0', 'SPOKE A 12X158.5', 'Nan hoa 12x158,5(trong)', 'GR'),
('', '', '', ''),
('9760241153P0', 'SPOKE A 10X156.5', 'Nan hoa A10x156,5(ngoài)', 'GR'),
('9760421157F0', 'SPOKE A 12X158.5', 'Nan hoa A10x158,5(ngoài)', 'GR'),
('9805656713', 'PLUG, SPARK (C6HSA) ( NGK)', 'BUGI (C6HSA)(NGK)', 'PM'),
('9805656723', 'SPARKPLUG(U20FSU)', 'BUGI (U20FSU)(DENSO)', 'PM'),
('H0640KRS900', 'CHAIN KIT WAVE', 'Bộ nhông xích wave alpha cũ', 'PM'),
('H0640KTL640', 'CHAIN KIT NEW WAVE.', 'Bộ nhông xích wave rssxvalpha', 'PM'),
('H0640KTM970', 'CHAIN KIT FUTURE NEO.', 'Bộ nhông xích future NEOFIII', 'PM'),
('H0640KWWY10', 'CHAIN KIT NEW WAVE 110.', 'Bộ nhông xích WAEVE 110', 'PM'),
('H0640KYZ900', 'CHAIN KIT NEW FUTURE.', 'Bộ nhông xích Future mới', 'PM');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
