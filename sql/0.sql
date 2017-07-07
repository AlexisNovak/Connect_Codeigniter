-- phpMyAdmin SQL Dump
-- version 3.4.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 05, 2016 at 10:50 AM
-- Server version: 5.5.45
-- PHP Version: 5.3.29

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `6Connect`
--

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

CREATE TABLE IF NOT EXISTS `bids` (
  `bid_id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` varchar(20) NOT NULL,
  `service_row_id` int(11) NOT NULL,
  `courier_id` int(11) NOT NULL,
  `bidding_price` float NOT NULL,
  `remarks` text,
  `is_approved` int(11) NOT NULL DEFAULT '0' COMMENT '0=no 1=yes 2=rejected',
  `status` tinyint(4) NOT NULL COMMENT '1=active 2=withdraw',
  `bidding_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` datetime DEFAULT NULL,
  `approved_date` datetime DEFAULT NULL,
  PRIMARY KEY (`bid_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bid_consignment_relation`
--

CREATE TABLE IF NOT EXISTS `bid_consignment_relation` (
  `bid_id` bigint(20) NOT NULL,
  `consignment_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`,`ip_address`,`user_agent`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `consignments`
--

CREATE TABLE IF NOT EXISTS `consignments` (
  `consignment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `private_id` varchar(32) NOT NULL,
  `public_id` varchar(32) NOT NULL,
  `org_id` int(11) NOT NULL,
  `c_group_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `consignment_type_id` int(10) unsigned NOT NULL,
  `description` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `price` float NOT NULL DEFAULT '0',
  `customer_id` int(10) unsigned NOT NULL,
  `service_id` int(10) unsigned NOT NULL,
  `is_service_assigned` tinyint(4) NOT NULL,
  `is_for_bidding` tinyint(4) NOT NULL COMMENT '1=yes 0=no',
  `is_open_bid` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=closed bid 1=open bid',
  `bidding_deadline` datetime DEFAULT NULL,
  `is_confirmed` tinyint(4) NOT NULL COMMENT '1=yes 0=no',
  `quantity` int(10) unsigned NOT NULL DEFAULT '0',
  `is_bulk` tinyint(4) NOT NULL,
  `length` float NOT NULL,
  `breadth` float NOT NULL,
  `height` float NOT NULL,
  `volume` float NOT NULL,
  `weight` double NOT NULL,
  `collection_address` varchar(100) NOT NULL,
  `is_c_restricted_area` tinyint(1) NOT NULL COMMENT '0=no 1=yes',
  `collection_date` datetime NOT NULL,
  `collection_date_to` datetime NOT NULL,
  `collection_country` varchar(3) CHARACTER SET latin1 NOT NULL,
  `collection_timezone` varchar(40) CHARACTER SET latin1 NOT NULL,
  `delivery_address` varchar(255) NOT NULL,
  `is_d_restricted_area` tinyint(1) NOT NULL COMMENT '0=no 1=yes',
  `delivery_post_code` varchar(6) NOT NULL,
  `delivery_country` varchar(3) CHARACTER SET latin1 NOT NULL,
  `delivery_timezone` varchar(40) CHARACTER SET latin1 NOT NULL,
  `delivery_date` datetime NOT NULL,
  `delivery_date_to` datetime NOT NULL,
  `delivery_contact_name` varchar(200) NOT NULL,
  `delivery_contact_email` varchar(200) NOT NULL,
  `delivery_contact_phone` varchar(50) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `created_user_id` int(10) unsigned NOT NULL,
  `collection_post_code` varchar(6) NOT NULL,
  `collection_contact_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `collection_contact_number` varchar(50) NOT NULL,
  `collection_contact_email` varchar(255) NOT NULL,
  `send_notification_to_consignee` tinyint(4) NOT NULL,
  `consignment_status_id` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `ref` varchar(255) DEFAULT NULL,
  `tags` text,
  `picture` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`consignment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `consignment_activity_log`
--

CREATE TABLE IF NOT EXISTS `consignment_activity_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `activity` text NOT NULL,
  `category` tinyint(4) NOT NULL COMMENT '1= delivery 2=bid 0=default',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `consignment_comments`
--

CREATE TABLE IF NOT EXISTS `consignment_comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `consignment_messages`
--

CREATE TABLE IF NOT EXISTS `consignment_messages` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `courier_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `question` varchar(500) NOT NULL,
  `reply` text,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` datetime DEFAULT NULL,
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `consignment_pod`
--

CREATE TABLE IF NOT EXISTS `consignment_pod` (
  `pod_id` int(11) NOT NULL AUTO_INCREMENT,
  `consignment_id` int(11) NOT NULL,
  `pod_image_url` varchar(300) NOT NULL,
  `is_signature` tinyint(1) NOT NULL,
  `pod_remarks` text NOT NULL,
  `courier_id` int(11) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_on` datetime NOT NULL,
  PRIMARY KEY (`pod_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `consignment_status`
--

CREATE TABLE IF NOT EXISTS `consignment_status` (
  `status_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `display_name` varchar(50) NOT NULL,
  `description` varchar(500) NOT NULL,
  `for_courier` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `consignment_type`
--

CREATE TABLE IF NOT EXISTS `consignment_type` (
  `consignment_type_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `display_name` varchar(50) NOT NULL,
  `description` varchar(500) NOT NULL,
  PRIMARY KEY (`consignment_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_name` varchar(40) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone_number` varchar(30) NOT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `department_name` varchar(100) DEFAULT NULL,
  `address_line1` varchar(100) NOT NULL,
  `address_line2` varchar(100) NOT NULL,
  `postal_code` varchar(6) NOT NULL,
  `country_code` varchar(3) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_on` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE IF NOT EXISTS `country` (
  `code` char(2) NOT NULL,
  `numeric` varchar(3) NOT NULL,
  `country` varchar(80) NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `couriers`
--

CREATE TABLE IF NOT EXISTS `couriers` (
  `courier_id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `access_key` varchar(40) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `reg_address` text NOT NULL,
  `billing_address` text NOT NULL,
  `fullname` varchar(70) NOT NULL,
  `reg_no` varchar(70) DEFAULT NULL,
  `phone` varchar(70) DEFAULT NULL,
  `fax` varchar(70) DEFAULT NULL,
  `support_email` varchar(255) DEFAULT NULL,
  `num_votes` int(10) unsigned NOT NULL DEFAULT '0',
  `total_score` int(10) unsigned NOT NULL DEFAULT '0',
  `rating` int(10) unsigned NOT NULL DEFAULT '0',
  `is_verified` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=not verified 1=verified',
  `is_approved` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=not approved 1=approved',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_on` datetime DEFAULT NULL,
  `resetsenton` datetime DEFAULT NULL,
  PRIMARY KEY (`courier_id`),
  UNIQUE KEY `access_key` (`access_key`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `courier_email_confirmation`
--

CREATE TABLE IF NOT EXISTS `courier_email_confirmation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `verification_code` varchar(255) NOT NULL,
  `sent_time` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=not verified 1=verified',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `courier_rating`
--

CREATE TABLE IF NOT EXISTS `courier_rating` (
  `courier_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `review` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`courier_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `courier_service`
--

CREATE TABLE IF NOT EXISTS `courier_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `service_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `org_id` int(11) NOT NULL DEFAULT '0',
  `display_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `service_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'distance  or volumetric',
  `price` float NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `week_0` tinyint(1) NOT NULL DEFAULT '0',
  `week_1` tinyint(1) NOT NULL DEFAULT '0',
  `week_2` tinyint(1) NOT NULL DEFAULT '0',
  `week_3` tinyint(1) NOT NULL DEFAULT '0',
  `week_4` tinyint(1) NOT NULL DEFAULT '0',
  `week_5` tinyint(1) NOT NULL DEFAULT '0',
  `week_6` tinyint(1) NOT NULL DEFAULT '0',
  `origin` varchar(40) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `description` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `threshold_price` float NOT NULL DEFAULT '0',
  `is_for_bidding` int(11) NOT NULL DEFAULT '0' COMMENT '0=false 1=true',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1=active 2=removed',
  `org_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=new 2=approved 3=rejected',
  `courier_id` int(11) NOT NULL,
  `auto_approve` tinyint(4) NOT NULL,
  `is_public` tinyint(4) NOT NULL COMMENT '0=no 1=yes',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `groupactivity_log`
--

CREATE TABLE IF NOT EXISTS `groupactivity_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `action` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1=add,2=edit',
  `remark` text NOT NULL,
  `update_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `child_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `code` varchar(25) DEFAULT NULL,
  `org_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` tinyint(1) NOT NULL COMMENT '1=active 2=suspended',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

CREATE TABLE IF NOT EXISTS `group_members` (
  `group_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `invitations`
--

CREATE TABLE IF NOT EXISTS `invitations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `org_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL,
  `sent_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iptocountry`
--

CREATE TABLE IF NOT EXISTS `iptocountry` (
  `ip_from` int(10) unsigned NOT NULL,
  `ip_to` int(10) unsigned NOT NULL,
  `country_code` char(2) NOT NULL,
  KEY `country_code` (`country_code`),
  KEY `ip_to` (`ip_to`),
  KEY `ip_from` (`ip_from`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `jobstates`
--

CREATE TABLE IF NOT EXISTS `jobstates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `status_code` int(11) NOT NULL,
  `status_name` varchar(100) NOT NULL,
  `status_description` text NOT NULL,
  `user_type` int(11) NOT NULL,
  `changed_user_id` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `job_acknowledgement`
--

CREATE TABLE IF NOT EXISTS `job_acknowledgement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bid_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `consignment_id` int(11) NOT NULL,
  `price` float NOT NULL,
  `remarks` text,
  `is_approved` tinyint(4) NOT NULL DEFAULT '0',
  `courier_id` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approved_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mass_consignment_info`
--

CREATE TABLE IF NOT EXISTS `mass_consignment_info` (
  `user_id` int(11) NOT NULL,
  `consignment_info` text NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_on` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE IF NOT EXISTS `member` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(24) NOT NULL,
  `email` varchar(160) NOT NULL,
  `password` varchar(60) DEFAULT NULL,
  `root` tinyint(4) NOT NULL DEFAULT '0',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `verifiedon` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastsignedinon` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `resetsenton` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deletedon` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `suspendedon` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` int(11) NOT NULL,
  `language` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `member_details`
--

CREATE TABLE IF NOT EXISTS `member_details` (
  `user_id` bigint(20) unsigned NOT NULL,
  `fullname` varchar(160) DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `country` char(2) DEFAULT NULL,
  `picture` varchar(240) DEFAULT NULL,
  `phone_no` bigint(20) DEFAULT NULL,
  `fax_no` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `template_preference` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=6connect template 2=custom',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `member_permission`
--

CREATE TABLE IF NOT EXISTS `member_permission` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(80) NOT NULL,
  `description` varchar(160) DEFAULT NULL,
  `suspendedon` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `key` (`key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `member_role`
--

CREATE TABLE IF NOT EXISTS `member_role` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `description` varchar(160) DEFAULT NULL,
  `suspendedon` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `notification_settings`
--

CREATE TABLE IF NOT EXISTS `notification_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `notification_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE IF NOT EXISTS `options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `value` longtext NOT NULL,
  `group` varchar(25) NOT NULL DEFAULT 'default_unload',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE IF NOT EXISTS `organizations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `shortname` varchar(20) DEFAULT NULL,
  `hash_code` varchar(32) NOT NULL,
  `website` varchar(255) DEFAULT NULL,
  `description` text,
  `avatar` varchar(255) NOT NULL,
  `open_bid` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=closed bid 1=open bid',
  `allow_api` tinyint(1) NOT NULL COMMENT '0=no 1=allow push request via API',
  `status` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash_code` (`hash_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `organization_members`
--

CREATE TABLE IF NOT EXISTS `organization_members` (
  `org_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `note` text NOT NULL,
  `role_id` int(11) NOT NULL COMMENT '1=admin 2=member',
  `is_superadmin` tinyint(4) NOT NULL DEFAULT '0',
  `added_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(20) NOT NULL,
  UNIQUE KEY `unique_members` (`org_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `partner`
--

CREATE TABLE IF NOT EXISTS `partner` (
  `partner_id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_name` varchar(50) NOT NULL,
  `partner_url` varchar(200) NOT NULL,
  `shortname` varchar(20) NOT NULL,
  `domain` varchar(10) NOT NULL,
  `color_scheme` varchar(200) NOT NULL,
  `owner` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`partner_id`),
  UNIQUE KEY `shortname` (`shortname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `partner_members`
--

CREATE TABLE IF NOT EXISTS `partner_members` (
  `partner_id` bigint(20) NOT NULL,
  `member_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pre_approved_bidders`
--

CREATE TABLE IF NOT EXISTS `pre_approved_bidders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `courier_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '1=accepted, 0=rejected, 2=invited',
  `added_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `recent_contacts`
--

CREATE TABLE IF NOT EXISTS `recent_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_contact_id` int(11) NOT NULL,
  `to_contact_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rel_account_role`
--

CREATE TABLE IF NOT EXISTS `rel_account_role` (
  `user_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `request_courier_service`
--

CREATE TABLE IF NOT EXISTS `request_courier_service` (
  `request_id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `courier_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '1=new 0=rejected 2=approved',
  `remarks` varchar(255) DEFAULT NULL,
  `added_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_on` datetime NOT NULL,
  PRIMARY KEY (`request_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `role_permission`
--

CREATE TABLE IF NOT EXISTS `role_permission` (
  `role_id` bigint(20) unsigned NOT NULL,
  `permission_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `service_bids`
--

CREATE TABLE IF NOT EXISTS `service_bids` (
  `bid_id` int(11) NOT NULL AUTO_INCREMENT,
  `req_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `courier_id` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT 'status 1=new, 2=accepted, 3=rejected,0=withdrawn',
  `added_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`bid_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `service_groups`
--

CREATE TABLE IF NOT EXISTS `service_groups` (
  `service_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `service_members`
--

CREATE TABLE IF NOT EXISTS `service_members` (
  `service_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `service_requests`
--

CREATE TABLE IF NOT EXISTS `service_requests` (
  `req_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `price_range` varchar(200) NOT NULL,
  `delivery_p_m` int(10) unsigned NOT NULL,
  `service_duration` int(11) NOT NULL,
  `payment_term` varchar(500) NOT NULL,
  `other_conditions` varchar(500) NOT NULL,
  `remarks` text NOT NULL,
  `status` int(11) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_on` datetime NOT NULL,
  PRIMARY KEY (`req_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `service_request_log`
--

CREATE TABLE IF NOT EXISTS `service_request_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_id` int(11) NOT NULL,
  `activity` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `service_request_messages`
--

CREATE TABLE IF NOT EXISTS `service_request_messages` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `courier_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `question` varchar(500) NOT NULL,
  `reply` text,
  `type` tinyint(4) NOT NULL COMMENT '1=comment 2=question',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` datetime NOT NULL,
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `service_type`
--

CREATE TABLE IF NOT EXISTS `service_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `shared_contacts`
--

CREATE TABLE IF NOT EXISTS `shared_contacts` (
  `contact_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `shared_person` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(40) NOT NULL,
  `category` tinyint(4) NOT NULL COMMENT '1=order',
  `added_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tip_of_the_day`
--

CREATE TABLE IF NOT EXISTS `tip_of_the_day` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `token_key`
--

CREATE TABLE IF NOT EXISTS `token_key` (
  `user_id` varchar(100) NOT NULL,
  `token` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_tokens`
--

CREATE TABLE IF NOT EXISTS `user_tokens` (
  `user_id` int(11) NOT NULL,
  `access_token` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zoneinfo`
--

CREATE TABLE IF NOT EXISTS `zoneinfo` (
  `zoneinfo` varchar(40) NOT NULL,
  `offset` varchar(16) DEFAULT NULL,
  `summer` varchar(16) DEFAULT NULL,
  `country` char(2) NOT NULL,
  `cicode` varchar(6) NOT NULL,
  `cicodesummer` varchar(6) DEFAULT NULL,
  PRIMARY KEY (`zoneinfo`),
  KEY `country` (`country`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

CREATE TABLE `db_evolutions` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
`version` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `db_evolutions` (name, version) VALUES ('0.sql', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
