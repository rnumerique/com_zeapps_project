CREATE TABLE `zeapps_project_todos` (
  `id` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `label` varchar(255) NOT NULL,
  `done` tinyint(1) NOT NULL,
  `sort` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `zeapps_project_todos`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `zeapps_project_todos`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;

ALTER TABLE `zeapps_project_todos` ADD `id_category` INT UNSIGNED NOT NULL AFTER `id_user`;

CREATE TABLE `zeapps_project_todo_categories` (
  `id` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `label` varchar(255) NOT NULL,
  `sort` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `zeapps_project_todo_categories`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `zeapps_project_todo_categories`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;






ALTER TABLE `zeapps_project_rights` ADD `hourly_rate` FLOAT(9,2) NOT NULL AFTER `project`;