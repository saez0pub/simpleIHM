<?php

/*
 * Copyright (C) 2014 saez0pub
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

global $config;
//Hack COOKIE ovverrides POST overrides GET
foreach ($_COOKIE as $key => $value) {
  $_POST[$key] = $value;
}
foreach ($_POST as $key => $value) {
  $_GET[$key] = trim(strip_tags($value));
}
//Versionapplicative
$config['version'] = '0.2';

//DB Connexion
$config['db']['user'] = 'ihm';
$config['db']['pass'] = 'IlVaudraitMieuxLeChanger';
$config['db']['host'] = 'localhost';
$config['db']['port'] = '3306';
$config['db']['name'] = 'ihm';
$config['db']['prefix'] = 'ihm_';

//Nom de la session
$config['sessionName'] = 'ihm';

//Nom de l'Ihm
$config['IhmName'] = 'Ihm';

//Expiration des cookies
//Par défaut : 1 mois
$config['cookieTime'] = time() + 60 * 60 * 24;

//Est ce que l'on arrête le chargepment de la page en cas de problème sur les 
//variables postées ? recommendé : TRUE 
//FALSE est surtout utilisé pour les tests Unitaires
$config['stopOnExec'] = TRUE;

//Configuration des vérifications de sécurité
$config['securite']['redirect'] = 'int';
$config['securite']['login'] = 'alphanum';
$config['securite']['password'] = 'mysqlChecked';
$config['securite']['passwordmd5'] = 'password_hash';
$config['securite']['PHPSESSID'] = 'alphanum';
$config['securite']['remember-me'] = 'digit';
$config['securite']['XDEBUG_SESSION'] = 'ascii';
$config['securite']['XDEBUG_SESSION_START'] = 'ascii';
$config['securite']['XDEBUG_SESSION_STOP_NO_EXEC'] = 'ascii';
$config['securite']['table'] = 'alphanum';
$config['securite']['champs'] = 'ascii';
$config['securite']['id'] = 'int';
$config['securite']['action'] = 'int';
$config['securite']['actionTxt'] = 'alphanum';
//champ sutilisés dans les update ajax
$config['securite']['inputid'] = 'int';
$config['securite']['inputTable'] = 'alphanum-_';
$config['securite']['inputChamps'] = 'alphanum-_';
$config['securite']['nom'] = 'mysqlChecked';
$config['securite']['content'] = 'mysqlChecked';
$config['securite']['command'] = 'ascii';
$config['securite']['args'] = 'ascii';
$config['securite']['type'] = 'alphanum';
$config['securite']['scenario_id'] = 'int';
$config['securite']['cle'] = 'ascii';
$config['securite']['inputcle'] = 'alphanum-_';
$config['securite']['valeur'] = 'ascii';
$config['securite']['inputscenario_id'] = 'alphanum-_';

$config['allowed_modals'][] = 'user';
$config['allowed_modals'][] = 'setting';

//Liste des javascripts a charger par défaut
$config['js'][] = 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js';
$config['js'][] = './js/bootstrap.min.js';
$config['js'][] = './js/main.js';


//Hack Pourri pour que les tests unitaires en petent pas tout
if(isset($_SERVER['SERVER_SOFTWARE']) && preg_match('/PHP .* Development Server/', $_SERVER['SERVER_SOFTWARE'])){
  $config['db']['prefix'] = 'tests_todelete_' . $config['db']['prefix'];
}