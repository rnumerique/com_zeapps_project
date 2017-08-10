ALTER TABLE `zeapps_projects` CHANGE `time_spent` `total_time_spent` INT UNSIGNED NOT NULL;
ALTER TABLE `zeapps_projects` ADD `total_spendings` FLOAT(9,2) UNSIGNED NOT NULL AFTER `total_time_spent`;

UPDATE zeapps_projects
SET zeapps_projects.total_time_spent = (
        SELECT SUM(zeapps_project_timers.time_spent)
        FROM zeapps_project_timers
        WHERE zeapps_project_timers.deleted_at IS NULL AND zeapps_project_timers.id_project = zeapps_projects.id
        GROUP BY zeapps_project_timers.id_project
    )
WHERE zeapps_projects.deleted_at IS NULL;

UPDATE zeapps_projects SET zeapps_projects.total_spendings = 0;

UPDATE zeapps_projects
SET zeapps_projects.total_spendings = (
        SELECT SUM(zeapps_project_timers.time_spent / 60 * zeapps_project_rights.hourly_rate)
        FROM zeapps_project_timers
        LEFT JOIN zeapps_project_rights ON zeapps_project_timers.id_user = zeapps_project_rights.id_user
        WHERE zeapps_project_timers.deleted_at IS NULL AND zeapps_project_timers.id_project = zeapps_projects.id AND zeapps_project_rights.id_project = zeapps_projects.id
        GROUP BY zeapps_project_timers.id_project
    )
WHERE zeapps_projects.deleted_at IS NULL;

UPDATE zeapps_projects
SET zeapps_projects.total_spendings = zeapps_projects.total_spendings + (
        SELECT SUM(zeapps_project_spendings.total)
        FROM zeapps_project_spendings
        WHERE zeapps_project_spendings.deleted_at IS NULL AND zeapps_project_spendings.id_project = zeapps_projects.id
        GROUP BY zeapps_project_spendings.id_project
    )
WHERE zeapps_projects.deleted_at IS NULL;