<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/********** CONFIG MENU ************/
$tabMenu = array () ;
$tabMenu["id"] = "com_ze_apps_project_statuses" ;
$tabMenu["space"] = "com_ze_apps_config" ;
$tabMenu["label"] = "Statut de projets" ;
$tabMenu["fa-icon"] = "tasks" ;
$tabMenu["url"] = "/ng/com_zeapps/status" ;
$tabMenu["order"] = 90 ;
$menuLeft[] = $tabMenu ;





/********* insert in essential menu *********/
$tabMenu = array () ;
$tabMenu["label"] = "Projets" ;
$tabMenu["url"] = "/ng/com_zeapps_project/project" ;
$tabMenu["order"] = 50 ;
$menuEssential[] = $tabMenu ;






/********** insert in left menu ************/
$tabMenu = array () ;
$tabMenu["id"] = "com_zeapps_projects_management" ;
$tabMenu["space"] = "com_ze_apps_project" ;
$tabMenu["label"] = "Gestion des projets" ;
$tabMenu["fa-icon"] = "tasks" ;
$tabMenu["url"] = "/ng/com_zeapps_project/project" ;
$tabMenu["order"] = 10 ;
$menuLeft[] = $tabMenu ;


$tabMenu = array () ;
$tabMenu["id"] = "com_zeapps_projects_planning" ;
$tabMenu["space"] = "com_ze_apps_project" ;
$tabMenu["label"] = "Planning" ;
$tabMenu["fa-icon"] = "calendar-o" ;
$tabMenu["url"] = "/ng/com_zeapps_project/planning" ;
$tabMenu["order"] = 20 ;
$menuLeft[] = $tabMenu ;


$tabMenu = array () ;
$tabMenu["id"] = "com_zeapps_projects_mywork" ;
$tabMenu["space"] = "com_ze_apps_project" ;
$tabMenu["label"] = "Mon travail" ;
$tabMenu["fa-icon"] = "briefcase" ;
$tabMenu["url"] = "/ng/com_zeapps_project/mywork" ;
$tabMenu["order"] = 30 ;
$menuLeft[] = $tabMenu ;


$tabMenu = array () ;
$tabMenu["id"] = "com_zeapps_projects_journal" ;
$tabMenu["space"] = "com_ze_apps_project" ;
$tabMenu["label"] = "Journal" ;
$tabMenu["fa-icon"] = "clock-o" ;
$tabMenu["url"] = "/ng/com_zeapps_project/journal" ;
$tabMenu["order"] = 40 ;
$menuLeft[] = $tabMenu ;


$tabMenu = array () ;
$tabMenu["id"] = "com_zeapps_projects_todos" ;
$tabMenu["space"] = "com_ze_apps_project" ;
$tabMenu["label"] = "To-Dos" ;
$tabMenu["fa-icon"] = "list-ul" ;
$tabMenu["url"] = "/ng/com_zeapps_project/todos" ;
$tabMenu["order"] = 50 ;
$menuLeft[] = $tabMenu ;


$tabMenu = array () ;
$tabMenu["id"] = "com_zeapps_projects_archives" ;
$tabMenu["space"] = "com_ze_apps_project" ;
$tabMenu["label"] = "Archives" ;
$tabMenu["fa-icon"] = "archive" ;
$tabMenu["url"] = "/ng/com_zeapps_project/archives" ;
$tabMenu["order"] = 60 ;
$menuLeft[] = $tabMenu ;




/********** insert in top menu ************/
$tabMenu = array () ;
$tabMenu["id"] = "com_zeapps_projects_management" ;
$tabMenu["space"] = "com_ze_apps_project" ;
$tabMenu["label"] = "Gestion des projets" ;
$tabMenu["url"] = "/ng/com_zeapps_project/project" ;
$tabMenu["order"] = 10 ;
$menuHeader[] = $tabMenu ;

$tabMenu = array () ;
$tabMenu["id"] = "com_zeapps_projects_planning" ;
$tabMenu["space"] = "com_ze_apps_project" ;
$tabMenu["label"] = "Planning" ;
$tabMenu["url"] = "/ng/com_zeapps_project/planning" ;
$tabMenu["order"] = 20 ;
$menuHeader[] = $tabMenu ;

$tabMenu = array () ;
$tabMenu["id"] = "com_zeapps_projects_mywork" ;
$tabMenu["space"] = "com_ze_apps_project" ;
$tabMenu["label"] = "Mon travail" ;
$tabMenu["url"] = "/ng/com_zeapps_project/mywork" ;
$tabMenu["order"] = 30 ;
$menuHeader[] = $tabMenu ;

$tabMenu = array () ;
$tabMenu["id"] = "com_zeapps_projects_journal" ;
$tabMenu["space"] = "com_ze_apps_project" ;
$tabMenu["label"] = "Journal" ;
$tabMenu["url"] = "/ng/com_zeapps_project/journal" ;
$tabMenu["order"] = 40 ;
$menuHeader[] = $tabMenu ;

$tabMenu = array () ;
$tabMenu["id"] = "com_zeapps_projects_todos" ;
$tabMenu["space"] = "com_ze_apps_project" ;
$tabMenu["label"] = "To-Dos" ;
$tabMenu["url"] = "/ng/com_zeapps_project/todos" ;
$tabMenu["order"] = 50 ;
$menuHeader[] = $tabMenu ;