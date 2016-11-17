<?php

/**
 *    _    _         _   ___
 *   | |__| |__ _ __| |_| __|__ _ _ _ __  ___
 *   | '_ \ / _` / _| / / _/ _ \ '_| '  \(_-<
 *   |_.__/_\__,_\__|_\_\_|\___/_| |_|_|_/__/
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 3 of the License, or (at
 *   your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful, but
 *   WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 *   General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program; if not, see <http://www.gnu.org/licenses/>.
 *
 *   @author          Bianka Martinovic
 *   @copyright       2013, Black Cat Development
 *   @link            http://blackcat-cms.org
 *   @license         http://www.gnu.org/licenses/gpl.html
 *   @category        CAT_Modules
 *   @package         blackForms
 *
 */

if (defined('CAT_PATH')) {
    if (defined('CAT_VERSION')) include(CAT_PATH.'/framework/class.secure.php');
} elseif (file_exists($_SERVER['DOCUMENT_ROOT'].'/framework/class.secure.php')) {
    include($_SERVER['DOCUMENT_ROOT'].'/framework/class.secure.php');
} else {
    $subs = explode('/', dirname($_SERVER['SCRIPT_NAME']));    $dir = $_SERVER['DOCUMENT_ROOT'];
    $inc = false;
    foreach ($subs as $sub) {
        if (empty($sub)) continue; $dir .= '/'.$sub;
        if (file_exists($dir.'/framework/class.secure.php')) {
            include($dir.'/framework/class.secure.php'); $inc = true;    break;
        }
    }
    if (!$inc) trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
}

include_once dirname(__FILE__).'/../../../config.php';
include_once CAT_PATH.'/modules/lib_wblib/wblib/wbFormsWizard.php';

// wblib2 autoloader
spl_autoload_register(function($class)
{
#echo "autloading -$class-<br />";
    $file = str_replace('\\','/',dirname(__FILE__)).'/../../lib_wblib/'.str_replace(array('\\','_'), array('/','/'), $class).'.php';
#echo "file: $file<br />";
    if (file_exists($file)) {
        @require $file;
    }
    // next in stack
});

if(isset($_GET['section_id']) && is_numeric($_GET['section_id']))
{
     $db = \wblib\wbQuery::getInstance(
        array(
            'host'   => CAT_DB_HOST,
            'user'   => CAT_DB_USERNAME,
            'pass'   => CAT_DB_PASSWORD,
            'dbname' => CAT_DB_NAME,
            'prefix' => CAT_TABLE_PREFIX,
        )
    );
    $config = $db->search(
        array(
            'fields' => array('preset_name','config'),
            'tables' => array(
                'mod_blackforms_forms',
                'mod_blackforms_presets',
            ),
            'join'   => 't1.preset == t2.preset_id',
            'where'  => 'section_id == ?',
            'params' => $_GET['section_id']
        )
    );
    $w = wblib\wbFormsWizard::getInstance();
    $w->set('wblib_url','http://localhost/_projects/bcwa/modules/lib_wblib/wblib');
    $w->set('passthru_url','http://localhost/_projects/bcwa/modules/blackForms/ajax/save.php');
    $w->set('config',array('section_id'=>$_GET['section_id']));
    $w->show(unserialize($config[0]['config']));
}
else
{
    echo "Nothing to show here...";
}


