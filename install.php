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

// ----- import tables -----
include_once WB_PATH.'/modules/lib_wblib/wblib/wbQuery.php';
$db = \wblib\wbQuery::getInstance(
    array(
        'host'   => CAT_DB_HOST,
        'user'   => CAT_DB_USERNAME,
        'pass'   => CAT_DB_PASSWORD,
        'dbname' => CAT_DB_NAME,
        'prefix' => CAT_TABLE_PREFIX,
    )
);
$import = file_get_contents( dirname(__FILE__)."/install/structure.sql" );
_ar_import($import);
$import = file_get_contents( dirname(__FILE__)."/install/data.sql" );
_ar_import($import);

function _ar_import($import) {
    global $db;
    $import = preg_replace( "%/\*(.*)\*/%Us", ''              , $import );
    $import = preg_replace( "%^--(.*)\n%mU" , ''              , $import );
    $import = preg_replace( "%^$\n%mU"      , ''              , $import );
    $import = preg_replace( "%cat_%"        , CAT_TABLE_PREFIX, $import );
    $import = preg_replace( "%\r?\n%"       , ''              , $import );
    $import = explode (";", $import);
    foreach ($import as $imp){
        if ($imp != '' && $imp != ' '){
            $ret = $db->query($imp);
        }
    }
}   // end function _ar_import()
