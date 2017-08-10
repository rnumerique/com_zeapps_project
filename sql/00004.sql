CREATE TABLE `zeapps_project_quotes` (
  `id` int(10) unsigned NOT NULL,
  `id_project` int(10) unsigned NOT NULL,
  `id_quote` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `zeapps_project_quotes`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `zeapps_project_quotes`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;

  CREATE TABLE `zeapps_project_invoices` (
  `id` int(10) unsigned NOT NULL,
  `id_project` int(10) unsigned NOT NULL,
  `id_invoice` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `zeapps_project_invoices`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `zeapps_project_invoices`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;

CREATE TABLE `zeapps_project_spendings` (
  `id` int(10) unsigned NOT NULL,
  `id_project` int(10) unsigned NOT NULL,
  `label` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `total` float(9,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `zeapps_project_spendings`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `zeapps_project_spendings`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;