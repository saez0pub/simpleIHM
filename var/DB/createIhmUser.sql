-- Script a adapter en fonction du paramétrage que l'on va mettre dans le 
-- fichier etc/config.php
-- Si la résolution d'hotes est désactivée dans MySQL, il faut remplacer
-- localhost par 127.0.0.1 ( ou par l'IP si la base MySQL n'est pas sur le 
-- serveur Web

create user 'ihm'@'localhost' identified by 'IlVaudraitMieuxLeChanger';
-- L'installation et l'upgrade de la base de données est faite par php, il faut 
-- avoir des droits de faire beaucoup dur la BDD.
grant ALL PRIVILEGES ON ihm.* to 'ihm'@'localhost';
create database ihm CHARACTER SET utf8 COLLATE utf8_general_ci;;
