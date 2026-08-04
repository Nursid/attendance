-- ---------------------------------------------------------------------------
-- OBHS Feedback System schema
-- Run once on the active database (u469155742_app).
-- Reuses the existing `complain` table for auto-created complaint records
-- (linked through obhs_feedback.complaint_id).
-- ---------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `obhs_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bid` int(11) NOT NULL DEFAULT 0 COMMENT 'business id (login.id of company)',
  `uid` int(11) NOT NULL DEFAULT 0 COMMENT 'staff/janitor login.id who collected feedback',
  `janitor_name` varchar(255) NOT NULL DEFAULT '',

  -- Journey details
  `train_no` varchar(20) NOT NULL,
  `train_name` varchar(255) NOT NULL DEFAULT '',
  `coach_no` varchar(20) NOT NULL,
  `journey_date` date NOT NULL,
  `boarding_station` varchar(100) NOT NULL DEFAULT '',
  `destination_station` varchar(100) NOT NULL DEFAULT '',

  -- Passenger details
  `pnr_no` varchar(20) NOT NULL DEFAULT '',
  `seat_no` varchar(20) NOT NULL DEFAULT '',
  `passenger_name` varchar(255) NOT NULL,
  `passenger_mobile` varchar(20) NOT NULL DEFAULT '',
  `passenger_email` varchar(255) NOT NULL DEFAULT '',

  -- Service ratings: 4=Very Good, 3=Good, 2=Poor, 1=Not Attended (0 = legacy/not recorded)
  `rating_toilet_cleaning` tinyint(1) NOT NULL DEFAULT 0,
  `rating_compartment_cleaning` tinyint(1) NOT NULL DEFAULT 0,
  `rating_toiletries_availability` tinyint(1) NOT NULL DEFAULT 0,
  `rating_behaviour` tinyint(1) NOT NULL DEFAULT 0,

  -- PSI = (total score / 12) x 100, Not Attended contributes 0 (server calculated)
  `psi_score` decimal(5,2) NOT NULL DEFAULT 0.00,

  `feedback_type` varchar(20) NOT NULL DEFAULT 'Feedback' COMMENT 'Feedback | Complaint',
  `remarks` text DEFAULT NULL,

  -- GPS + photo
  `latitude` varchar(50) NOT NULL DEFAULT '',
  `longitude` varchar(50) NOT NULL DEFAULT '',
  `location` varchar(255) NOT NULL DEFAULT '',
  `photo` varchar(255) NOT NULL DEFAULT '',

  `complaint_id` int(11) NOT NULL DEFAULT 0 COMMENT 'complain.id when auto complaint created',
  `status` varchar(20) NOT NULL DEFAULT 'Pending' COMMENT 'Pending | Working | Done',
  `date_time` varchar(255) NOT NULL COMMENT 'unix timestamp (project convention)',

  PRIMARY KEY (`id`),
  KEY `idx_bid_jdate` (`bid`,`journey_date`),
  KEY `idx_train` (`train_no`),
  KEY `idx_coach` (`coach_no`),
  KEY `idx_uid` (`uid`),
  KEY `idx_type_status` (`feedback_type`,`status`),
  KEY `idx_complaint` (`complaint_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
