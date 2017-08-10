ALTER TABLE `zeapps_projects` CHANGE `archived` `archived` TIMESTAMP NULL DEFAULT NULL;

UPDATE `zeapps_projects` SET `archived`= null WHERE 1;

ALTER TABLE `zeapps_project_cards` CHANGE `step` `step` INT(1) UNSIGNED NOT NULL;

UPDATE `zeapps_project_cards` SET `step`= 1 WHERE completed = 'N';
UPDATE `zeapps_project_cards` SET `step`= 4 WHERE completed = 'Y';

ALTER TABLE `zeapps_project_cards`
  DROP `completed`,
  DROP `completed_at`;
