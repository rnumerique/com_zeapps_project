<?php
defined('BASEPATH') OR exit('No direct script access allowed');



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
$tabMenu["order"] = 1 ;
$menuLeft[] = $tabMenu ;


$tabMenu = array () ;
$tabMenu["id"] = "com_zeapps_projects_planning" ;
$tabMenu["space"] = "com_ze_apps_project" ;
$tabMenu["label"] = "Planning" ;
$tabMenu["fa-icon"] = "calendar-o" ;
$tabMenu["url"] = "/ng/com_zeapps_project/planning" ;
$tabMenu["order"] = 2 ;
$menuLeft[] = $tabMenu ;

$tabMenu = array () ;
$tabMenu["id"] = "com_zeapps_projects_sandbox" ;
$tabMenu["space"] = "com_ze_apps_project" ;
$tabMenu["label"] = "Bac à sable" ;
$tabMenu["fa-icon"] = "lightbulb-o" ;
$tabMenu["url"] = "/ng/com_zeapps_project/sandbox" ;
$tabMenu["order"] = 3 ;
$menuLeft[] = $tabMenu ;


$tabMenu = array () ;
$tabMenu["id"] = "com_zeapps_projects_backlog" ;
$tabMenu["space"] = "com_ze_apps_project" ;
$tabMenu["label"] = "Backlog" ;
$tabMenu["fa-icon"] = "list-alt" ;
$tabMenu["url"] = "/ng/com_zeapps_project/backlog" ;
$tabMenu["order"] = 4 ;
$menuLeft[] = $tabMenu ;


$tabMenu = array () ;
$tabMenu["id"] = "com_zeapps_projects_sprint" ;
$tabMenu["space"] = "com_ze_apps_project" ;
$tabMenu["label"] = "Sprint" ;
$tabMenu["fa-icon"] = "trello" ;
$tabMenu["url"] = "/ng/com_zeapps_project/sprint" ;
$tabMenu["order"] = 5 ;
$menuLeft[] = $tabMenu ;




/********** insert in top menu ************/
$tabMenu = array () ;
$tabMenu["id"] = "com_zeapps_projects_management" ;
$tabMenu["space"] = "com_ze_apps_project" ;
$tabMenu["label"] = "Gestion des projets" ;
$tabMenu["url"] = "/ng/com_zeapps_project/project" ;
$tabMenu["order"] = 1 ;
$menuHeader[] = $tabMenu ;

$tabMenu = array () ;
$tabMenu["id"] = "com_zeapps_projects_planning" ;
$tabMenu["space"] = "com_ze_apps_project" ;
$tabMenu["label"] = "Planning" ;
$tabMenu["url"] = "/ng/com_zeapps_project/planning" ;
$tabMenu["order"] = 2 ;
$menuHeader[] = $tabMenu ;

$tabMenu = array () ;
$tabMenu["id"] = "com_zeapps_projects_sprint" ;
$tabMenu["space"] = "com_ze_apps_project" ;
$tabMenu["label"] = "Sprint" ;
$tabMenu["url"] = "/ng/com_zeapps_project/sprint" ;
$tabMenu["order"] = 3 ;
$menuHeader[] = $tabMenu ;