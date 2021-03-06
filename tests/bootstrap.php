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

/**
 * @author saez0pub
 */
global $adminLogin;
global $adminPassword;
global $cookieTest;
global $pid;

//Ne pas oublier de créer un utilisateur mysql pour les tests (etc/config.php)
//create user ihm identified by 'CCCCC';
//grant ALL PRIVILEGES ON ihm.* to 'ihm'@'localhost';
//create database ihm;
//Pour les tests d'ouverture de page, il faut une bdd valide avec le prefix correct installDB.php

//Sur une installation fraiche, il faut un mot de passe pour l'admin
//Pour que les tests fonctionnent, il faut l'applicatif qui tourne et 
//l'utilisateur $adminLogin ainsi que son mot de passe valide
//$_GET["password"] = time() + rand(0, 2000);
$adminLogin = "admintest";
$_GET["login"] = $adminLogin;
$_GET["password"] = $adminLogin;
$adminPassword = password_hash($_GET["password"],PASSWORD_DEFAULT);

include dirname(__FILE__) . '/../lib/common.php';
include_once dirname(__FILE__) . '/../lib/dbInstall.function.php';
$host = "localhost";
$port = 8000;
$docRoot = "../public/";
$config['serverUrl'] = "http://$host:$port/";
$config['db']['prefix'] = 'tests_todelete_' . $config['db']['prefix'];

$command = sprintf(
  'ihm_prefix='.$config['db']['prefix'].'XDEBUG_CONFIG="remote_enable=Off" php -S %s:%d -t %s >/dev/null 2>&1 & echo $!', $host, $port, $docRoot
);

$output = array();
exec($command, $output);
$pid = (int) $output[0];

echo sprintf(
  '%s - Web server started on %s:%d with PID %d', date('r'), $host, $port, $pid
) . PHP_EOL;

//Les tests ne doivent pas être interompus
//$config['stopOnExec'] = FALSE;
//Utilisé pour redirections ou autres
//Ne doit pas être positionné avant car il planterait l'installation de base 
//de données
$_SERVER['HTTP_HOST'] = 'localhost';

reinitDB();
initLogin();
startSession();
foreach (scandir('.') as $file) {
  if (preg_match('/^test.*.php$/', $file)) {
    include dirname(__FILE__) . '/' . $file;
  }
}

function reinitDB() {
  global $db, $config, $adminPassword, $adminLogin;
  //Nettoyage des précedents tests en cas d'interuption
  dropDB();
  initDB();
  upgradeDB(FALSE);
  $ret = $db->query($sql = "INSERT ".$config['db']['prefix']."users VALUES (NULL, '$adminLogin','$adminPassword','',1);");
  return $ret;
}

function initLogin() {
  global $config, $cookieTest;
  $post_array = array('login' => $_GET["login"], 'password' => $_GET["password"], 'remember-me' => 1);
  $cookieTest = tempnam("/tmp", "COOKIE");
  $ch = curl_init($config['serverUrl']);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieTest);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post_array);
  $retour = curl_exec($ch);
  curl_close($ch);
  $user = new user();
  //Initialisation de la session
  $_SESSION[$config['sessionName']]['user'] = $user->getFromLogin($_GET["login"], $_GET["password"]);
}

function initTestTable() {
  global $db, $config;
  $db->query("DROP TABLE IF EXISTS " . $config['db']['prefix'] . "requete_test ");
  $db->query("CREATE TABLE " . $config['db']['prefix'] . "requete_test (
      `id` int(11) NOT NULL AUTO_INCREMENT,      
      `a1` varchar(100) NOT NULL,
      `a2` varchar(100) NOT NULL,
      `a3` varchar(100) NOT NULL,
      `a4` varchar(100) NOT NULL,
      PRIMARY KEY (`id`))");
  $db->query("INSERT INTO " . $config['db']['prefix'] . "requete_test VALUES
      (NULL,1,1,1,1),
      (NULL,2,2,2,2),
      (NULL,3,3,3,3),
      (NULL,4,4,4,4),
      (NULL,5,5,5,5),
      (NULL,1,1,1,2),
      (NULL,1,1,1,3),
      (NULL,1,1,1,4),
      (NULL,1,1,2,1),
      (NULL,1,2,1,1),
      (NULL,1,1,1,6),
      (NULL,1,3,1,1),
      (NULL,1,1,3,1),
      (NULL,1,1,1,3),
      (NULL,1,1,1,1)
    ");
}

register_shutdown_function(function() {
  global $cookieTest, $config, $pid;
  //dropDB();
  //unlink($cookieTest);
  echo sprintf('%s - Killing process with ID %d', date('r'), $pid) . PHP_EOL;
  exec('kill ' . $pid);
});
