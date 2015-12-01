
CREATE TABLE IF NOT EXISTS `$prefix$users` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `login` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `lastlogin` datetime DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL,
  UNIQUE INDEX `idx_$prefix$users_login` (`login`)
  ) ;
CREATE TABLE IF NOT EXISTS `$prefix$config` (
  `cle` varchar(100) PRIMARY KEY,
  `valeur` varchar(100) NOT NULL
  ) ;
INSERT INTO `$prefix$config` VALUES ('version', '0.1');
CREATE TABLE IF NOT EXISTS `$prefix$menu` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nom` varchar(100) NOT NULL,
  `link` varchar(100) NOT NULL,
  `level` tinyint(2) NOT NULL,
  `parent` INT NOT NULL,
  `ordre` INT NOT NULL,
  UNIQUE INDEX `idx_$prefix$menu_nom_parent` (`nom`, `parent`)
  ) ;
INSERT INTO `$prefix$menu` VALUES (1, 'Paramètres', '#', 0, 0, 9999);
INSERT INTO `$prefix$menu` VALUES (NULL, 'Utilisateurs', 'users.php', 1, 1, 1);
INSERT INTO `$prefix$menu` VALUES (NULL, 'Menus', 'users.php', 1, 1, 2);
INSERT INTO `$prefix$menu` VALUES (NULL, 'Paramètres', 'settings.php', 1, 1, 3);
