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
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class testCRUD extends PHPUnit_Framework_TestCase {

  public function testJePeuxFaireUnInsertSelectUpdateDelete() {
    $menu = new dbIhm('menu');
    $columns=array(
        'nom' => 'testJePeuxFaireUnInsert',
        'link' => 'testJePeuxFaireUnInsert',
        'level' => '0',
        'parent' => '0',
        'ordre' => '0'
    );
    $template = $columns;
    $id = $menu->insert($columns);
    $this->assertNotEquals(FALSE, $id);
    $this->assertGreaterThan(0, $id);
    $ligne = $menu->getFromID($id);
    $template['id'] = $id;
    $this->assertEquals($template, $ligne);
    $columns['nom'] = 'testJePeuxFaireUnUpdate';
    $menu->update($id, $columns);
    $ligne = $menu->getFromID($id);
    $this->assertNotEquals($template, $ligne);
    $template['nom'] = $columns['nom'];
    $this->assertEquals($template, $ligne);
    $menu->delete($id);
    $ligne = $menu->getFromID($id);
    $this->assertEquals(FALSE, $ligne);
    }
    
  public function testJeNePeuxPasFaireUnInsertUpdateSurUnIdQuiEstEnAutoIncrement() {
    global $db, $config;
    $post = 'requete_test_autoincr';
    $table = $config['db']['prefix'] . "$post";
    $db->query("DROP TABLE IF EXISTS $table");
    $db->query("CREATE TABLE $table (
        `id` int(11) NOT NULL AUTO_INCREMENT, 
        PRIMARY KEY (`id`))
        AUTO_INCREMENT = 1000");
    $menu = new dbIhm($post);
    $columns=array(
        'id' => 100
    );
    $template = $columns;
    $id = $menu->insert($columns);
    $this->assertEquals(0, $id);
    $res = $menu->update(100,$columns);
    $this->assertEquals(0, $res);
    $db->query("DROP TABLE IF EXISTS $table");
  }
  
  public function testJePeuxFaireUnInsertUpdateSurUnIdQuiNEstPasEnAutoIncrement() {
    global $db, $config;
    $post = 'requete_test_autoincr';
    $table = $config['db']['prefix'] . "$post";
    $db->query("DROP TABLE IF EXISTS $table");
    $db->query("CREATE TABLE $table (
        `id` varchar(255) NOT NULL,
        PRIMARY KEY (`id`))");
    $menu = new dbIhm($post);
    $columns=array(
        'id' => 'testJePeuxFaireUnInsert'
    );
    $template = $columns;
    $id = $menu->insert($columns);
    $this->assertEquals('testJePeuxFaireUnInsert', $id);
    $ligne = $menu->getFromID($id);
    $template['id'] = $id;
    $this->assertEquals($template, $ligne);
    $columns=array(
        'id' => 'testJePeuxFaireUnInsert1'
    );
    $res = $menu->update('testJePeuxFaireUnInsert',$columns);
    $this->assertNotEquals(FALSE, $res);
    $this->assertNotEquals(NULL, $res);
    $ligne = $menu->getFromID($columns['id']);
    $template['id'] = $columns['id'];
    $this->assertEquals($template, $ligne);
    $db->query("DROP TABLE IF EXISTS $table");
  }
}
