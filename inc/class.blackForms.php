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

// wblib2 autoloader
spl_autoload_register(function($class)
{
    $file = str_replace('\\','/',CAT_PATH).'/modules/lib_wblib/'.str_replace(array('\\','_'), array('/','/'), $class).'.php';
    if (file_exists($file)) {
        @require $file;
    }
    // next in stack
});

include dirname(__FILE__).'/../init.php';

class blackForms {

    private $bcf_dbh  = NULL;
    private $form     = NULL;
    private $settings = array();
    private $entry    = array();

    /**
     *
     * @access public
     * @return
     **/
    public function __construct()
    {
        // initialize database connections
        $this->bcf_dbh = \wblib\wbQuery::getInstance(
            array(
                'host'   => CAT_DB_HOST,
                'user'   => CAT_DB_USERNAME,
                'pass'   => CAT_DB_PASSWORD,
                'dbname' => CAT_DB_NAME,
                'prefix' => CAT_TABLE_PREFIX,
            )
        );

        // initialize templates
        global $_tpl_data;
        $_tpl_data = array();

        // initialize form
        $this->form = \wblib\wbForms::getInstanceFromFile('inc.forms.php',dirname(__FILE__).'/../forms');
        $this->form->set('lang_path',dirname(__FILE__).'/../languages');
        $this->form->set('wblib_url',WBLIB_URL);
        $this->form->setAttr('action',$_SERVER['SCRIPT_NAME']);

        \wblib\wbFormsJQuery::set('core_cdn',CAT_URL.'/modules/lib_jquery/jquery-core/jquery-core.min.js');
        \wblib\wbFormsJQuery::set('ui_cdn',CAT_URL.'/modules/lib_jquery/jquery-ui/ui/jquery-ui.min.js');
    }   // end function __construct()

    /**
     *
     * @access public
     * @return
     **/
    public function backend()
    {
        global $_tpl_data;

        // always use UITheme "start" in BE
        \wblib\wbFormsJQuery::set('ui_theme','start');

        $this->count_exports();

        // count items
        $_tpl_data['item_count'] = $this->getItemCount();
        $_tpl_data['version']    = CAT_Helper_Addons::getModuleVersion('blackForms');

        // do not allow to open any tab if there is no form yet
        if(!$this->hasform())
        {
            $this->select_preset();
        }
        else
        {
            if(isset($_REQUEST['do']))
            {
                switch ($_REQUEST['do'])
                {
                    case 'options':
                        $this->options();
                        break;
                    case 'preview':
                        $this->preview();
                        break;
                    case 'entries':
                        $this->entries();
                        break;
                    case 'exports':
                        $this->exports();
                        break;
                    case 'form':
                        $this->editform();
                	   	break;
                }
            }
            else
            {
                $this->entries();
            }
        }

        global $parser;
        $parser->output('be_modify.tpl',$_tpl_data);

    }   // end function backend()

    /**
     *
     * @access public
     * @return
     **/
    public function frontend()
    {
        global $_tpl_data, $parser, $section_id;

        \wblib\wbFormsJQuery::set('ui_theme',$this->get_settings('ui_theme','start'));

        $r = $this->load_form();
        if(!count($r))
        {
            $parser->output('fe_view',array('is_error'=>1,'info'=>$this->form->t('No form configured yet')));
        }
        else
        {
            if($this->form->isSent() && $this->form->isValid())
            {
                $user_id = CAT_Users::get_user_id();
                if(!$user_id) $user_id = 0;
                $this->bcf_dbh->insert(
                    array(
                        'tables' => 'mod_blackforms_submissions',
                        'fields' => array('section_id','submitted_when','submitted_by','data_serialized'),
                        'values' => array($section_id,time(),$user_id,serialize($this->form->getData(1)))
                    )
                );
                if($this->bcf_dbh->isError())
                {
                    $this->form->setInfo($this->form->t('Sorry, but we are unable to save your submission!').' '.$this->bcf_dbh->getError());
                }
                else
                {
                    $settings = $this->get_settings();
                    if(isset($settings['success_page']) && $settings['success_page'] !== '' && $settings['success_page'] !== '0')
                    {
                        echo "<script type='text/javascript'>location.href='".CAT_Helper_Page::getLink($settings['success_page'])."';</script>";
                    }
                    else
                    {
                        $_tpl_data['info'] = $this->form->t('Form submission succeeded');
                        $_tpl_data['content'] = $this->form->t($settings['success_message']);
                    }
                }
            }
            else
            {
                $this->form->setData($this->form->getData(1));
                $_tpl_data['form'] = $this->form->getForm();
            }
            global $parser;
            $parser->output('fe_view.tpl',$_tpl_data);
        }
    }   // end function frontend()
    
    /**
     * retrieve settings from DB
     *
     * @access public
     * @return array
     **/
    private function get_settings($key=NULL,$default=NULL)
    {
        global $section_id;
        if(!count($this->settings))
        {
            $set = $this->bcf_dbh->search(
                array(
                    'tables' => 'mod_blackforms_settings',
                    'where'  => 'section_id == ?',
                    'params' => $section_id
                )
            );
            if(count($set))
                foreach($set as $i => $s)
                    $this->settings[$s['option_name']] = $s['option_value'];
        }
        if($key)
            return isset($this->settings[$key])
                   ? $this->settings[$key]
                   : ( ($default) ? $default : NULL )
                   ;
        return $this->settings;
    }   // end function get_settings()

    /**
     * set options
     **/
    private function options()
    {
    	global $_tpl_data, $page_id, $section_id;

        // field selection for mail_to field
        $this->load_form();
        $fields = $this->form->getElements(1,1,'FORMS');
        $fsel   = array();
        foreach(array_values($fields) as $item)
            $fsel[$item['name']] = $item['name'];
        $page_select = CAT_Helper_Page::getPageSelect(1);

        // add option 'none' to page select
        $page_select[0] = $this->form->t('[please choose one...]');

        // create form and set default values
        $this->form->setForm('settings');
        $this->form->setAttr('action',$_SERVER['SCRIPT_NAME']);
        $this->form->getElement('page_id')->setAttr('value',$page_id);
        $this->form->getElement('success_page')->setAttr('options',$page_select);
        $this->form->getElement('success_page')->setAttr('index_as_value',true);
        $this->form->getElement('success_mail_to_field')->setAttr('options',$fsel);

        // check if SecurImage is present
        if(CAT_Helper_Addons::isModuleInstalled('lib_securimage'))
        {
            $this->form->getElement('protection')->addOption('bc_captcha','SecurImage Captcha');
        }

        // set current values
        $current = $this->get_settings();
        foreach($current as $key => $value)
        {
            $elem = $this->form->getElement($key);
            if(is_object($elem))
                $elem->setValue($value);
        }

        $_tpl_data['current_tab'] = 'options';

        // if the form is sent...
        if($this->form->isSent() && $this->form->isValid())
        {
            // ...get data
            $options = $this->form->getData();
            $errors  = array();
            // delete old settings
            $this->bcf_dbh->delete(
                array(
                    'tables' => 'mod_blackforms_settings',
                    'where'  => 'section_id == ?',
                    'params' => $section_id
                )
            );
            // save new settings
            foreach($options as $key => $value)
            {
                if(!array_key_exists($key,$current))
                {
                    $this->bcf_dbh->insert(
                        array(
                            'tables' => 'mod_blackforms_settings',
                            'values' => array($section_id,$key,$value)
                        )
                    );
                }
                else
                {
                    $this->bcf_dbh->replace(
                        array(
                            'tables' => 'mod_blackforms_settings',
                            'values' => array($section_id,$key,$value)
                        )
                    );
                }
                if($this->bcf_dbh->isError())
                    $errors[] = $this->bcf_dbh->getError();
                else
                    $current[$key] = $value;
            }
            if(!count($errors))
                $this->form->setInfo('Success');
            else
                $this->form->setError('Kaputt');
        }

        $this->form->setData($current);
        $this->form->getElement('success_page')->setValue($current['success_page']);

        $_tpl_data['form'] = $this->form->getForm();

    }   // function options()

    /**
     * default view
     **/
    private function editform()
    {
        // make sure we already have a form here
        global $_tpl_data, $section_id, $page_id;
        $_tpl_data['current_tab'] = 'form';

        if(!$this->hasform())
            return $this->select_preset();

        // load preview
        $data   = $this->preview(true);
        $config = ($data['config'] != '') // modified
                ?  $data['config']    // original preset
                :  $data['preset_data']
                ;
        $config = unserialize($config);

        $this->form->setForm('reset_form');
        $_tpl_data['info']
            = 'Verwendete Vorlage: '
            . $data['preset_name']
            . '<br />'
            . $this->form->getForm()
            ;

        $this->form->setForm($data['preset_name']);
        $elements = $this->form->getElements(true,true,'FORMS');
        $options  = array();
        foreach($elements as $e)
            $options[$e['name']] = $e['label'];

        $this->form->setForm('add_element');
        $this->form->set('add_buttons',false);
        $this->form->getElement('after')->setAttr('options',$options);
        $this->form->getElement('preset_id')->setValue($data['preset_id']);

        $_tpl_data['add_form'] = $this->form->getForm('add_element');

        $this->form->setForm('edit_element');
        $this->form->set('add_buttons',false);
        $this->form->setAttr('form_width','100%');
        $_tpl_data['edit_form'] = $this->form->getForm();

    }   // end function editform()

    /**
     * list presets
     **/
    private function select_preset()
    {
        global $_tpl_data, $section_id, $page_id;
        $_tpl_data['current_tab'] = 'form';
        $this->form->setForm('preset');
        if($this->form->isSent() && $this->form->isValid())
        {
            $config = $this->bcf_dbh->search(
                array(
                    'tables' => 'mod_blackforms_presets',
                    'where'  => 'preset_id == ?',
                    'params' => array($_POST['preset']),
                )
            );
            $this->bcf_dbh->insert(
                array(
                    'tables' => 'mod_blackforms_forms',
                    'fields' => array('section_id','preset','config'),
                    'values' => array($section_id,$_POST['preset'],$config[0]['preset_data'])
                )
            );
            if(!$this->bcf_dbh->isError())
                return $this->options();
        }

        $presets = $this->bcf_dbh->search(
            array(
                'tables' => 'mod_blackforms_presets',
            )
        );
        $presets_select = array();

        for($i=0;$i<count($presets);$i++)
        {
            $presets_select[$presets[$i]['preset_id']] = $this->form->t($presets[$i]['display_name']);
        }
        // show presets
        $this->form->getElement('page_id')->setAttr('value',$page_id);
        $this->form->getElement('preset')->setAttr('options',$presets_select);
        $_tpl_data['form'] = $this->form->getForm();
    }   // end function select_preset()

    /**
     * show preview of the form
     **/
    private function preview($return_config=false)
    {
        // make sure we already have a form here
        global $_tpl_data, $section_id, $page_id;
        $r = $this->bcf_dbh->search(
            array(
                'fields' => array('preset_id','preset_name','preset_data','config'),
                'tables' => array(
                    'mod_blackforms_forms',
                    'mod_blackforms_presets',
                ),
                'join'   => 't1.preset == t2.preset_id',
                'where'  => 'section_id == ?',
                'params' => $section_id
            )
        );

        if(!count($r))
            return select_preset();

        $config = ($r[0]['config'] != '') // modified
                ?  $r[0]['config']    // original preset
                :  $r[0]['preset_data']
                ;

        $this->form->configure($r[0]['preset_name'],unserialize($config));
        $this->form->setForm($r[0]['preset_name']);

        $_tpl_data['form'] = '<h1>'.$this->form->t('Preview').'</h1>'
                           . '<div class="bform_info">'.$this->form->t('This is a preview of your form. The presentation in the frontend may differ.').'</div>'
                           . $this->form->getForm();

        if($return_config)
            return $r[0];
        else
            return true;
    }   // end function editform()

    /**
     * manage entries
     **/
    private function entries()
    {
    	global $_tpl_data, $section_id, $page_id, $parser;
        $_tpl_data['current_tab'] = 'entries';

        // view details
        if(isset($_GET['view']) && is_numeric($_GET['view']))
        {
            $this->entry_details();
        }
        elseif(isset($_REQUEST['reply']) && is_numeric($_REQUEST['reply']))
        {
            $this->entry_reply();
        }
        else
        {
            $r = $this->load_entries();
            if(!count($r))
            {
                $_tpl_data['info'] = $this->form->t('No entries so far');
            }
            else
            {
                // check for delete / export action
                if(isset($_REQUEST['action']) && $_REQUEST['action'] != '')
                {
                    if($_REQUEST['action'] == 'export')
                    {
                        $result = $this->export_entries($r);
                        if(!$result) $_tpl_data['is_error'] = 1;
                        else         $_tpl_data['info'] = $this->form->t('Success');
                        $this->count_exports();
                    }
                    elseif($_REQUEST['action'] == 'delete')
                    {
                        $result = $this->delete_entries($r);
                        if(count($result))
                        {
                            $_tpl_data['is_error'] = 1;
                            $_tpl_data['info']     = implode('<br />',$result);
                        }
                        else
                        {
                            $_tpl_data['info'] = $this->form->t('DELETE Success');
                            $r = $this->load_entries();
                            $_tpl_data['item_count'] = $this->getItemCount();
                        }
                    }
                }

                $_tpl_data['content'] = $parser->get('be_entries',array('entries'=>$r));

            }
        }
    }   // function entries()

    /**
     *
     * @access private
     * @return
     **/
    private function entry_details($hide_buttons=false)
    {
        global $_tpl_data, $parser;
        $r = $this->bcf_dbh->search(
            array(
                'tables' => 'mod_blackforms_submissions',
                'where'  => 'submission_id == ?',
                'params' => $_GET['view']
            )
        );
        if(!count($r))
        {
            $_tpl_data['info'] = $this->form->t('Invalid submission ID! (No such item.)');
        }
        else
        {
            // load the form; allows to replace field names by labels
            $replies     = array();
            $form        = $this->load_form();
            $data        = unserialize($r[0]['data_serialized']);
            $this->entry = $data;
            for($i=0;$i<count($form);$i++)
            {
                if(isset($form[$i]['name']) && isset($data[$form[$i]['name']]))
                {
                    $data[$this->form->t($form[$i]['label'])] = $data[$form[$i]['name']];
                    unset($data[$form[$i]['name']]);
                }
            }
            if($r[0]['submitted_by'] > 0)
                $r[0]['submitted_by'] = CAT_Users::get_user_details($r[0]['submitted_by'],'display_name');
            else
                $r[0]['submitted_by'] = $this->form->t('Guest (not logged in)');

            // get replies (if any)
            $rep = $this->bcf_dbh->search(
                array(
                    'tables' => 'mod_blackforms_replies',
                    'where'  => 'submission_id == ?',
                    'params' => $_GET['view']
                )
            );
            $allow_reply = true;
            if($this->get_settings('mail_from')=='')
                $allow_reply = false;
            if(count($rep))
            {
                $allow_reply = false;
                $this->form->setForm('reply');
                $fields = $this->form->getElements(1,1);
                $map    = array();
                foreach($fields as $i => $f)
                {
                    $map[$f['name']] = $this->form->t($f['label']);
                }
                foreach($rep as $reply)
                {
                    $rep_data = unserialize($reply['data_serialized']);
                    foreach($rep_data as $key => $value)
                    {
                        if($key == 'mail_to')
                        {
                            unset($rep_data[$key]);
                            continue;
                        }
                        if(isset($map[$key]))
                        {
                            $rep_data[$map[$key]] = $value;
                            unset($rep_data[$key]);
                        }
                    }
                    $replies[] = $rep_data;
                }
            }
            $_tpl_data['content']
                = $parser->get('be_view',array('entry'=>$r[0],'data'=>$data,'hide_buttons'=>$hide_buttons,'replies'=>$replies,'allow_reply'=>$allow_reply));
        }
    }   // end function entry_details()
    
    /**
     *
     * @access private
     * @return
     **/
    private function entry_reply()
    {
        global $page_id, $section_id, $_tpl_data;

        $_GET['view'] = $_REQUEST['reply'];
        $this->entry_details(true);

        $mail_field = $this->get_settings('success_mail_to_field');
        $mail_to    = $this->entry[$mail_field];

        $this->form->setForm('reply');
        $this->form->setAttr('action',$_SERVER['SCRIPT_NAME']);
        $this->form->getElement('page_id')->setAttr('value',$page_id);
        $this->form->getElement('reply')->setAttr('value',$_REQUEST['reply']);
        $this->form->getElement('mail_to')->setAttr('value',$mail_to);
        $this->form->getElement('mail_from')->setValue($this->get_settings('mail_from'));
        $this->form->getElement('mail_from_name')->setValue($this->get_settings('mail_from_name'));

        if($this->form->isSent() && $this->form->isValid())
        {
            $user_id = CAT_Users::get_user_id();
            $data    = $this->form->getData(1);
            foreach(array('do','reply','page_id') as $f)
                unset($data[$f]);
            $this->bcf_dbh->insert(
                array(
                    'tables' => 'mod_blackforms_replies',
                    'fields' => array('submission_id','section_id','submitted_when','submitted_by','data_serialized'),
                    'values' => array($_REQUEST['reply'],$section_id,time(),$user_id,serialize($data))
                )
            );
            if($this->bcf_dbh->isError())
            {
                $this->form->setInfo($this->form->t('Sorry, but we are unable to save your submission!').' '.$this->bcf_dbh->getError());
            }
            else
            {
                $mailer = CAT_Helper_Mail::getInstance();
				if(is_object($mailer))
				{
                    try {
                        $mailer->sendMail( $data['mail_from'], $data['mail_to'], $data['mail_subject'], $data['mail_body'], $data['mail_from_name'] );
                        $_tpl_data['info'] = $this->form->t('Your reply was sent.');
                        return $this->entry_details();
                    }
                    catch( Exception $e ) {
                        $this->form->setError($this->form->t('Unable to send the mail!'));
                    }
				}
            }

        }

        $_tpl_data['content'] .= $this->form->getForm();
    }   // end function entry_reply()

    /**
     *
     * @access private
     * @return
     **/
    private function count_exports()
    {
        global $_tpl_data;
        // count exports (for backend tab)
        $path   = CAT_Helper_Directory::sanitizePath(dirname(__FILE__).'/../export/');
        $files  = CAT_Helper_Directory::getInstance()->setSuffixFilter(array('csv'))
                  ->findFiles('.*\.csv',$path,true);
        $_tpl_data['exp_count'] = count($files);
    }   // end function count_exports()

    /**
     *
     * @access public
     * @return
     **/
    private function exports()
    {
        global $_tpl_data, $section_id, $page_id, $parser;
        $_tpl_data['current_tab'] = 'exports';
        $path   = CAT_Helper_Directory::sanitizePath(dirname(__FILE__).'/../export/');
        $return = array();

        if(isset($_REQUEST['delete']) && count($_REQUEST['delete']))
        {
            foreach($_REQUEST['delete'] as $file)
            {
                unlink($path.'/'.$file);
            }
        }

        // delete single file; this is always an AJAX call!
        if(isset($_REQUEST['del']))
        {
            $success = false;
            if(file_exists($path.'/'.$_REQUEST['del']))
            {
                unlink($path.'/'.$_REQUEST['del']);
                $success = true;
            }
            $ajax	= array(
        		'message'	=> 'done',
        		'success'	=> $success
        	);
        	print json_encode( $ajax );
        	exit();
        }

        $files  = CAT_Helper_Directory::getInstance()
                  ->setSuffixFilter(array('csv'))
                  ->findFiles('.*\.csv',$path,true);

        if(count($files))
        {
            foreach($files as $file)
            {
                $file = pathinfo($file,PATHINFO_BASENAME);
                $return[] = array(
                    'filename' => $file,
                    'size'     => CAT_Helper_Directory::getSize($path.'/'.$file,true),
                    'date'     => CAT_Helper_Directory::getModdate($path.'/'.$file),
                );
            }
        }
        $_tpl_data['content'] = $parser->get('be_exports',array('entries'=>$return));

        // update tab
        $this->count_exports();

    }   // end function exports()

    /**
     *
     * @access public
     * @return
     **/
    private function hasform()
    {
        global $section_id;
        $r = $this->bcf_dbh->search(
            array(
                'tables' => 'mod_blackforms_forms',
                'where'  => 'section_id == ?',
                'params' => $section_id
            )
        );
        if(!count($r))
            return false;
        else
            return true;
    }   // end function hasform()

    /**
     *
     * @access public
     * @return
     **/
    private function delete_entries($r)
    {
        $err = array();
        for($i=0;$i<count($r);$i++)
        {
            if(
                   ( isset($_POST['toggle_boxes']) && $_POST['toggle_boxes'] == 'on' )
                || ( isset($_REQUEST['items']) && in_array($r[$i]['submission_id'],$_REQUEST['items']) )
            ) {
                $this->bcf_dbh->delete(
                    array(
                        'tables' => 'mod_blackforms_submissions',
                        'where'  => 'submission_id == ?',
                        'params' => $r[$i]['submission_id']
                    )
                );
                if($this->bcf_dbh->isError())
                    $err[] = $this->bcf_dbh->getError();
            }
        }
        return $err;
    }   // end function delete_entries()

    /**
     *
     * @access public
     * @return
     **/
    private function export_entries($r)
    {
        $csv = array();
        for($i=0;$i<count($r);$i++)
        {
            if(
                   ( isset($_POST['toggle_boxes']) && $_POST['toggle_boxes'] == 'on' )
                || ( isset($_POST['items']) && in_array($r[$i]['submission_id'],$_POST['items']) )
            ) {
                $tmp = unserialize($r[$i]['data_serialized']);
                if(is_array($tmp))
                    $csv[] = CAT_Helper_CSV::arrayToCsv($tmp);
            }
        }
        // add header line
        $line = unserialize($r[0]['data_serialized']);
        array_unshift($csv, CAT_Helper_CSV::arrayToCsv(array_keys($line)));
        if(!isset($_POST['export_filename']) || $_POST['export_filename'] == '')
            $filename = dirname(__FILE__).'/../export/export_'.strftime('%Y_%m_%d_%H_%M_%S',time()).'.csv';
        else
            $filename = dirname(__FILE__).'/../export/'.$this->check_filename($_POST['export_filename']);
        // save
        $fh = fopen($filename,'w');
        if(!is_resource($fh))
            return false;
        fwrite($fh,implode("\n",$csv));
        fclose($fh);
        return true;
    }   // end function export_entries()

    /**
     *
     * @access public
     * @return
     **/
    private function getItemCount()
    {
        global $section_id;
        $r = $this->bcf_dbh->search(
            array(
                'tables' => 'mod_blackforms_submissions',
                'where'  => 'section_id == ?',
                'params' => $section_id
            )
        );
        return count($r);
    }   // end function getItemCount()
    
    /**
     *
     * @access public
     * @return
     **/
    private function check_filename($filename)
    {
        $filename = CAT_Helper_Directory::sanitizeFilename($filename);
        // check for suffix
        if(pathinfo($filename,PATHINFO_EXTENSION) !== 'csv')
            $filename .= '.csv';
        return $filename;
    }   // end function check_filename()

    /**
     *
     * @access private
     * @return
     **/
    private function load_entries()
    {
        global $section_id;
        $r = $this->bcf_dbh->search(
            array(
                'fields' => array(
                    't1.*',
                    '(SELECT COUNT(*) FROM cat_mod_blackforms_replies AS t2 WHERE t2.submission_id = t1.submission_id ) AS replies'
                ),
                'tables' => 'mod_blackforms_submissions',
                'where'  => 'section_id == ?',
                'params' => $section_id
            )
        );
        if(count($r))
        {
            for($i=0;$i<count($r);$i++)
            {
                $r[$i]['size'] = strlen((string)$r[$i]['data_serialized']);
                if($r[$i]['submitted_by'] > 0)
                    $r[$i]['submitted_by'] = CAT_Users::get_user_details($r[$i]['submitted_by'],'display_name');
                else
                    $r[$i]['submitted_by'] = $this->form->t('Guest (not logged in)');
            }
        }
        return $r;
    }   // end function load_entries()
    
    /**
     * load form
     *
     * @access private
     * @return
     **/
    private function load_form()
    {
        global $section_id;
        $r = $this->bcf_dbh->search(
            array(
                'fields' => array('preset','preset_name','preset_data','config'),
                'tables' => array(
                    'mod_blackforms_forms',
                    'mod_blackforms_presets',
                ),
                'join'   => 't1.preset == t2.preset_id',
                'where'  => 'section_id == ?',
                'params' => $section_id
            )
        );
        if(count($r))
        {
            $r      = $r[0];
            $config = ($r['config'] != '') 
                    ?  $r['config']        // modified
                    :  $r['preset_data']   // original preset
                    ;
            $config = unserialize($config);
            $this->form->configure($r['preset'],$config);
            $this->form->setForm($r['preset']);
            // enable ASP?
            if($this->get_settings('protection') == 'honeypot')
            {
                $this->form->set('honeypot_prefix','bcf_hp_');
                $this->form->createHoneypots(2);
            }
            return $config;
        }
        return array();
    }   // end function load_form()

}