-- ---------------------------------------------------------------------------
-- OBHS Train Master (train no, train name, coach position)
-- Run once on the active database (u469155742_app).
-- Source: railway coach position sheet, March-26.
-- Global master: shared by every business, no bid scoping.
-- ---------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `obhs_train_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `train_no` varchar(20) NOT NULL COMMENT 'up direction number',
  `train_no_return` varchar(20) NOT NULL DEFAULT '' COMMENT 'down/return direction number',
  `train_name` varchar(255) NOT NULL,
  `coach_position` text NOT NULL COMMENT 'comma separated coach codes, engine to rear',
  `total_coaches` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = disabled',
  `date_time` varchar(255) NOT NULL COMMENT 'unix timestamp (project convention)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_train_no` (`train_no`),
  KEY `idx_train_no_return` (`train_no_return`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `obhs_train_master` (`train_no`,`train_no_return`,`train_name`,`coach_position`) VALUES
('12155','12156','Bhopal Express','H1,A1,A2,B1,B2,B3,B4,M1,S1,S2,S3,S4,S5,S6,S7'),
('20156','20155','NDLS DADN Express','H1,A1,A2,B1,B2,B3,B4,M1,S1,S2,S3,S4,S5,S6,S7'),
('12185','12186','Rewanchal Express','H1,A1,A2,B1,B2,B3,B4,B5,M1,S1,S2,S3,S4,S5,S6,S7'),
('20171','20172','Vande Bharat Express','C1,C2,C3,C4,C5,C6,C7,C8,E1,E2,C9,C10,C11,C12,C13,C14'),
('22192','22191','Indore SF Express','H1,A1,A2,B1,B2,B3,B4,B5,M1,S1,S2,S3,S4,S5,S6,S7'),
('22169','22170','Santragachhi Humsafar Express','B1,B2,B3,B4,B5,B6,B7,B8,B9,B10,B11,B12,B13,B14,S1,S2,S3,S4,S5,S6'),
('22172','22171','Pune Humsafar Express','B1,B2,B3,B4,B5,B6,B7,B8,B9,B10,B11,B12,B13,B14'),
('01665','01666','Agartala Special Fare Special','A1,A2,M1,B1,B2,B3,B4,B5,S1,S2,S3,S4,S5,S6,S7,S8'),
('12183','12184','MBDD Pratapgarh SF Express','A1,B1,B2,B3,B4,S1,S2,S3,S4,S5,S6,S7,S8,S9,S10,S11,S12,S13'),
('22163','22164','Khajuraho Mahamana SF Express','B2,B1,C2,C1,D9,D8,D7,D6,D5,D4,D3,D2,D1'),
('22165','22166','Singrauli Urjadhani Express','H1,A1,A2,B1,B2,B3,B4,B5,M1,S1,S2,S3,S4,S5,S6,S7'),
('22167','22168','Hazrat Nizamuddin Urjadhani Express','H1,A1,A2,B1,B2,B3,B4,B5,M1,S1,S2,S3,S4,S5,S6,S7'),
('11631','11632','Bhopal Dhanbad Express','H1,A1,A2,B1,B2,B3,B4,B5,M1,S1,S2,S3,S4,S5,S6,S7'),
('11633','11634','Chopan Weekly Express','H1,A1,A2,B1,B2,B3,B4,B5,M1,S1,S2,S3,S4,S5,S6,S7')
ON DUPLICATE KEY UPDATE
  `train_no_return`=VALUES(`train_no_return`),
  `train_name`=VALUES(`train_name`),
  `coach_position`=VALUES(`coach_position`);

UPDATE `obhs_train_master`
SET `total_coaches` = LENGTH(`coach_position`) - LENGTH(REPLACE(`coach_position`,',','')) + 1,
    `date_time` = UNIX_TIMESTAMP()
WHERE `coach_position` <> '';
