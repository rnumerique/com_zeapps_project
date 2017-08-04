ALTER TABLE `zeapps_project_categories` ADD `color` VARCHAR(7) NOT NULL AFTER `title`;

ALTER TABLE `zeapps_project_rights` DROP `sandbox`, DROP `sprint`;

ALTER TABLE `zeapps_project_rights` ADD `accounting` BOOLEAN NOT NULL AFTER `card`;

UPDATE `zeapps_project_rights` SET `accounting`=1 WHERE `project`= 1





ALTER TABLE `zeapps_project_deadlines` DROP `completed`;

CREATE TABLE `zeapps_project_comments` (
  `id` int(10) unsigned NOT NULL,
  `id_project` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `name_user` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `zeapps_project_documents` (
  `id` int(10) unsigned NOT NULL,
  `id_project` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `zeapps_project_comments`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `zeapps_project_documents`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `zeapps_project_comments`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;

ALTER TABLE `zeapps_project_documents`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;







ALTER TABLE `zeapps_project_documents` ADD `id_user` INT UNSIGNED NOT NULL AFTER `id_project`, ADD `name_user` VARCHAR(255) NOT NULL AFTER `id_user`;

ALTER TABLE `zeapps_project_cards` ADD `priority` VARCHAR(255) NOT NULL AFTER `name_assigned_to`;

ALTER TABLE `zeapps_project_timers`
  DROP `label`,
  DROP `id_category`,
  DROP `id_sprint`;

ALTER TABLE `zeapps_project_timers` ADD `name_project` VARCHAR(255) NOT NULL AFTER `id_project`;

ALTER TABLE `zeapps_project_timers` ADD `name_card` VARCHAR(255) NOT NULL AFTER `id_card`;






ALTER TABLE `zeapps_project_timers` CHANGE `name_card` `label` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;






ALTER TABLE `zeapps_project_card_documents` CHANGE `date` `date` TIMESTAMP NOT NULL DEFAULT '0000-00-00';
ALTER TABLE `zeapps_project_card_comments` CHANGE `date` `date` TIMESTAMP NOT NULL DEFAULT '0000-00-00';
ALTER TABLE `zeapps_project_comments` CHANGE `date` `date` TIMESTAMP NOT NULL DEFAULT '0000-00-00';
ALTER TABLE `zeapps_project_documents` CHANGE `date` `date` TIMESTAMP NOT NULL DEFAULT '0000-00-00';

CREATE TABLE `zeapps_project_card_priorities` (
  `id` int(10) unsigned NOT NULL,
  `label` varchar(255) NOT NULL,
  `color` varchar(7) NOT NULL,
  `sort` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO `zeapps_project_card_priorities` (`id`, `label`, `color`, `sort`, `created_at`, `update_at`, `deleted_at`) VALUES
(1, 'Urgent', '#f2dede', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(2, 'Haut', '#fcf8e3', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(3, 'Normal', '#dff0d8', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(4, 'Bas', '#d9edf7', 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL);

ALTER TABLE `zeapps_project_card_priorities`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `zeapps_project_card_priorities`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;

ALTER TABLE `zeapps_project_cards` CHANGE `priority` `id_priority` INT UNSIGNED NOT NULL;

UPDATE `zeapps_project_cards` SET `id_priority`=3;

ALTER TABLE `zeapps_project_documents` CHANGE `name` `label` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `zeapps_project_card_documents` CHANGE `name` `label` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `zeapps_project_documents` ADD `description` TEXT NOT NULL AFTER `label`;
ALTER TABLE `zeapps_project_card_documents` ADD `description` TEXT NOT NULL AFTER `label`;

ALTER TABLE `zeapps_project_card_documents` ADD `id_user` INT UNSIGNED NOT NULL AFTER `id_card`, ADD `name_user` VARCHAR(255) NOT NULL AFTER `id_user`;

ALTER TABLE `zeapps_project_timers` ADD `time_spent` INT UNSIGNED NOT NULL AFTER `stop_time`;