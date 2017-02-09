CREATE TABLE `zeapps_projects` (
  `id` int(10) unsigned NOT NULL,
  `id_company` int(10) unsigned NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `id_user_project_manager` int(10) unsigned NOT NULL,
  `name_user_project_manager` varchar(100) NOT NULL,
  `project_name` varchar(150) NOT NULL,
  `priority` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `archived_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `zeapps_projects`
--
ALTER TABLE `zeapps_projects`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `zeapps_projects`
--
ALTER TABLE `zeapps_projects`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;

CREATE TABLE `zeapps_project_sections` (
  `id` int(10) unsigned NOT NULL,
  `id_project` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `order_section` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `zeapps_project_sections`
--
ALTER TABLE `zeapps_project_sections`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `zeapps_project_sections`
--
ALTER TABLE `zeapps_project_sections`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;

CREATE TABLE `zeapps_project_tasks` (
  `id` int(10) unsigned NOT NULL,
  `id_project` int(10) unsigned NOT NULL DEFAULT '0',
  `id_section` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `completed` enum('Y','N') NOT NULL DEFAULT 'N',
  `progress` tinyint(3) unsigned NOT NULL,
  `order_section` int(10) unsigned NOT NULL,
  `id_assigned_to` int(10) unsigned NOT NULL DEFAULT '0',
  `name_assigned_to` varchar(100) NOT NULL,
  `due_date` date NOT NULL,
  `estimated_time_hours` decimal(7,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `zeapps_project_tasks`
--
ALTER TABLE `zeapps_project_tasks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `zeapps_project_tasks`
--
ALTER TABLE `zeapps_project_tasks`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;