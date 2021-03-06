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

$debug = false;

header('Content-type: application/json');

// *********************************************************************
// check perms
// *********************************************************************
$users = CAT_Users::getInstance();
if ( ! $users->checkPermission('pages', 'pages_modify', false) == true )
{
    $ajax    = array(
        'message'    => $backend->lang()->translate('You do not have the permission to do this.'),
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
        'message'    => $backend->lang()->translate('Invalid data!'),
        'success'    => false
    );
    print json_encode( $ajax );
    exit();
}

include dirname(__FILE__).'/../init.php';

// *********************************************************************
// load form data
// *********************************************************************
$id    = $val->get('_REQUEST','preset_id');
$name  = $val->get('_REQUEST','name');

if($val->get('_REQUEST','do') != 'remove')
{
    $type    = $val->get('_REQUEST','type','string');
    $where   = $val->get('_REQUEST','where','string');
    $after   = $val->get('_REQUEST','after','string');
    $req     = $val->get('_REQUEST','required','string');
    $label   = $val->get('_REQUEST','label','string');
    $default = $val->get('_REQUEST','default_value','string');
    $options = $val->get('_REQUEST','options','string');
}

// *********************************************************************
// get database connection
// *********************************************************************
if(version_compare(CAT_VERSION,'1.2','>=')) {
    $db = \wblib\wbQuery::getInstance(CAT_Helper_DB::getConfig());
}
else {
    $db = \wblib\wbQuery::getInstance(
        array(
            'host'   => CAT_DB_HOST,
            'user'   => CAT_DB_USERNAME,
            'pass'   => CAT_DB_PASSWORD,
            'dbname' => CAT_DB_NAME,
            'prefix' => CAT_TABLE_PREFIX,
            'port'   => CAT_DB_PORT,
        )
    );
}

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

    // *********************************************************************
    // remove an element
    // *********************************************************************
    if($val->get('_REQUEST','do') == 'remove')
    {
        $form->removeElement($name,'FORMS');
        $new_config = serialize($form->getElements(false,false,'FORMS'));
    }
    //
    // save as new preset
    //
    elseif($val->get('_REQUEST','action') == 'save_as_preset' && $val->get('_REQUEST','name'))
    {
        $new_config = serialize($form->getElements(false,false,'FORMS'));
        $db->insert(
            array(
                'tables' => 'mod_blackforms_presets',
                'fields' => array('preset_name','display_name','preset_data'),
                'values' => array(
                    $val->get('_REQUEST','name'),
                    ( $val->get('_REQUEST','display_name') ? $val->get('_REQUEST','display_name') : $val->get('_REQUEST','name') ),
                    $new_config
                ),
            )
        );
    }
    // *********************************************************************
    // edit element
    // *********************************************************************
    else
    {
        $elem = array(
            'type'     => $type,
            'name'     => $name,
            'required' => ( $req == 'Y' ? true : false ),
            'label'    => $label,
        );

        if($val->get('_REQUEST','title')) {
            $elem['title'] = $val->get('_REQUEST','title','string');
        }

        if($type=='radiogroup' || $type=='select' || $type=='checkboxgroup')
        {
            if($options)
            {
                $new_opt = array();
                $lines   = explode("\n",$options);
                if(!is_array($lines)) $lines = array($lines);
                foreach($lines as $line)
                {
                    $line = trim($line);
                    if(substr_count($line,"|"))
                    {
                        list($key,$value) = explode("|",$line,2);
                        $new_opt[$key]=$value;
                    }
                    else
                    {
                        $new_opt[] = $line;
                    }
                }
                $elem['options'] = $new_opt;
            }
            if($default)
                if($type=='select')
                    $elem['selected'] = $default;
                else
                    $elem['checked']  = $default;
        }
        else
        {
            if($default)
                $elem['value'] = $default;
        }

        if($val->get('_REQUEST','submit_edit_element'))
        {
            // check if element already exists
            if(!$form->hasElement($name))
            {
                $ajax    = array(
                    'message'    => $val->lang()->translate('No such field.'),
                    'success'    => false
                );
                print json_encode( $ajax );
                exit();
            }
            // find element
            foreach($data as $i=>$e)
            {
                if(isset($e['name']) && $e['name'] == $name)
                {
                    if($val->get('_REQUEST','html5attr'))
                        $elem['type'] = $val->get('_REQUEST','html5attr');
                    elseif($type=='')
                        $elem['type'] = $e['type'];
                    $data[$i] = $elem;
                    break;
                }
            }
            $new_config = serialize($data);
        }
        else
        {
            $after_elem = NULL;
            switch($where) {
                case 'top':
                    $pos        = 'top';
                    break;
                case 'after':
                    $after_elem = $after;
                    $pos        = 'after';
                    break;
                case 'bottom':
                    $pos        = 'bottom';
                    break;
            }
            $form->addElement($elem,$after_elem,$pos,'FORMS');
            $new_config = serialize($form->getElements(false,false,'FORMS'));
        }
    }

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
            'message'    => $val->lang()->translate('Error saving changes to the DB.')
                         .  ( $debug ? $db->getLastStatement() . ' - ' . $db->getError() : '' ),
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
else
{
    $ajax    = array(
        'message'    => $val->lang()->translate('Invalid data (no such preset)'),
        'success'    => false
    );
    print json_encode( $ajax );
    exit();
}