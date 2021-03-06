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
	include(CAT_PATH.'/framework/class.secure.php');
} else {
	$root = "../";
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= "../";
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) {
		include($root.'/framework/class.secure.php');
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}

header('Content-type: application/json');

// *********************************************************************
// check perms
// *********************************************************************
$users = CAT_Users::getInstance();
if ( ! $users->checkPermission('pages', 'pages_modify', false) == true )
{
    $ajax    = array(
        'message'    => $users->lang()->translate('You do not have the permission to do this.'),
        'success'    => false
    );
    print json_encode( $ajax );
    exit();
}

// *********************************************************************
// Get page id
// *********************************************************************
$val = CAT_Helper_Validate::getInstance();
$page_id = $val->get('_REQUEST', 'page_id', 'numeric');

if ($page_id=='')
{
    $ajax    = array(
        'message'    => $users->lang()->translate('Invalid data!'),
        'success'    => false
    );
    print json_encode( $ajax );
    exit();
}

// *********************************************************************
// wblib2 autoloader
// *********************************************************************
spl_autoload_register(function($class)
{
    $file = str_replace('\\','/',CAT_PATH).'/modules/lib_wblib/'.str_replace(array('\\','_'), array('/','/'), $class).'.php';
    if (file_exists($file)) {
        @require $file;
    }
    // next in stack
});

// *********************************************************************
// get database connection
// *********************************************************************
$db = \wblib\wbQuery::getInstance(
    array(
        'host'   => CAT_DB_HOST,
        'user'   => CAT_DB_USERNAME,
        'pass'   => CAT_DB_PASSWORD,
        'dbname' => CAT_DB_NAME,
        'prefix' => CAT_TABLE_PREFIX,
    )
);
$id    = $val->get('_REQUEST','preset_id');

// *********************************************************************
// get current preset data
// *********************************************************************
$r = $db->search(
    array(
        'fields' => array('preset_id','preset_name','preset_data','config'),
        'tables' => array(
            'mod_blackforms_forms',
            'mod_blackforms_presets',
        ),
        'join'   => 't1.preset == t2.preset_id',
        'where'  => 'preset_id == ?',
        'params' => $id
    )
);

if(count($r))
{
    $config = ($r[0]['config'] != '')
            ?  $r[0]['config']        // original preset
            :  $r[0]['preset_data']   // modified form
            ;
    $data   = unserialize($config);
    $form   = \wblib\wbForms::getInstance();
    $form->configure($r[0]['preset_name'],$data);
    $form->setForm($r[0]['preset_name']);

    // figure out position
    $prev = $val->get('_REQUEST','prev');
    $next = $val->get('_REQUEST','next');
    $pos  = $prev;

    if(!$prev && $next) // top
        $pos = 'top';

    $form->moveElement($val->get('_REQUEST','id'),$pos,'FORMS');
    $new_config = serialize($form->getElements(false,false,'FORMS'));

    $db->update(
        array(
            'tables' => 'mod_blackforms_forms', // ****************************************
            'fields' => array('config','is_changed'),
            'values' => array($new_config,'Y'),
            'where'  => 'preset == ?',
            'params' => array($id)
        )
    );

    if($db->isError())
    {
        $ajax    = array(
            'message'    => $val->lang()->translate('Error saving changes to the DB.'),
            'success'    => false
        );
        print json_encode( $ajax );
        exit();
    }
    else
    {
        $ajax    = array(
            'message'    => 'Success',
            'success'    => true
        );
        print json_encode( $ajax );
        exit();
    }
}