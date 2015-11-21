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
class testModal extends PHPUnit_Framework_TestCase {

  public function testSilaclePrimaireEstUnAutoInCrement_AlorsJeLeCacheDansLeModal(){
   global $config, $cookieTest;
   initDB();
   initLogin();
    $ch = curl_init($config['serverUrl'].'ajax/modal.php?table=user&champs=id');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieTest);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $result = curl_exec($ch);
    $template = file_get_contents(dirname(__FILE__) . '/templates/modal_autoincr.html');
    $this->assertEquals($template, $result);
    curl_close($ch);
  }


  public function testSilaclePrimaireNEstPasUnAutoInCrement_AlorsJeLAfficheDansLeModal(){
   global $config, $cookieTest;
   initDB();
   initLogin();
    $ch = curl_init($config['serverUrl'].'ajax/modal.php?table=setting&champs=cle');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieTest);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $result = curl_exec($ch);
    $template = file_get_contents(dirname(__FILE__) . '/templates/modal_noautoincr.html');
    $this->assertEquals($template, $result);
    curl_close($ch);
  }

}
