<?php
/**
file: logoff.php
version: 1
author: vincent.shi
path: /module/base/

description:
destroy user login information and leave the site

usage:


*/
unset($_SESSION['employee_id']);
unset($_SESSION['employee_no']);
unset($_SESSION['employee_name']);
unset($_SESSION['employee_shortname']);
unset($_SESSION['alternative_employee_id']);
unset($_SESSION['employee_email']);
unset($_SESSION['employee_country_id']);
unset($_SESSION['employee_location_id']);
unset($_SESSION['employee_department_id']);
unset($_SESSION['employee_ifleave']);
unset($_SESSION['if_announced']);

unset($_SESSION);

session_destroy();