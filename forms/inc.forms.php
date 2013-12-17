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

$FORMS = array(

    'reply'   => array(
        array(
            'type'     => 'legend',
            'label'    => 'Send reply',
        ),
        array(
            'type'     => 'hidden',
            'name'     => 'do',
            'value'    => 'entries',
        ),
        array(
            'type'     => 'hidden',
            'name'     => 'reply',
        ),
        array(
            'type'     => 'hidden',
            'name'     => 'page_id',
        ),
        array(
            'type'     => 'hidden',
            'name'     => 'mail_to',
            'allow'    => 'email',
        ),
        array(
            'type'     => 'text',
            'name'     => 'mail_from',
            'label'    => 'Mail from address',
            'allow'    => 'email',
            'required' => true,
            'title'    => 'Please note: The recipient will see this mail address in his mail client!',
        ),
        array(
            'type'     => 'text',
            'name'     => 'mail_from_name',
            'label'    => 'Mail from name',
            'allow'    => 'string',
            'required' => true,
            'title'    => 'Please note: The recipient will see this name in his mail client',
        ),
        array(
            'type'     => 'text',
            'name'     => 'mail_subject',
            'label'    => 'Mail subject',
            'allow'    => 'string',
            'required' => true,
            'value'    => 'Your request',
        ),
        array(
            'type'     => 'textarea',
            'name'     => 'mail_body',
            'label'    => 'Mail body',
            'required' => true,
        ),
    ),

    'preset'  => array(
        array(
            'type'     => 'legend',
            'label'    => 'Please choose a preset...',
        ),
        array(
            'type'     => 'hidden',
            'name'     => 'page_id',
        ),
        array(
            'type'     => 'select',
            'name'     => 'preset',
            'label'    => 'Preset',
            'required' => true,
            'title'    => 'The preset will be used to populate the form with some basic elements. You can add/remove elements later.',
        ),
    ),

    'settings' => array(
        array(
            'type'     => 'legend',
            'label'    => 'Common settings',
        ),
        array(
            'type'     => 'hidden',
            'name'     => 'do',
            'value'    => 'options',
        ),
        array(
            'type'     => 'hidden',
            'name'     => 'page_id',
        ),
        array(
            'type'     => 'radiogroup',
            'name'     => 'captcha',
            'label'    => 'Use Captcha',
            'title'    => 'Uses a Captcha to protect your form against spam',
            'options'  => array( 'y' => 'Yes', 'n' => 'No' ),
            'checked'  => 'y',
        ),
        array(
            'type'     => 'radiogroup',
            'name'     => 'attachments',
            'label'    => 'Allow attachments',
            'title'    => 'Do you wish to allow to send (=upload) files?',
            'options'  => array( 'y' => 'Yes', 'n' => 'No' ),
            'checked'  => 'n',
        ),
        array(
            'type'     => 'text',
            'name'     => 'attachment_maxsize',
            'label'    => 'If yes: Max. size for attachments',
            'title'    => 'Set the max. size for attachments in Bytes; please note that only digits are allowed',
            'allow'    => 'number',
        ),
        array(
            'type'     => 'text',
            'name'     => 'attachment_types',
            'label'    => 'If yes: Allowed file types (by suffix)',
            'title'    => 'Separate suffixes by comma (,)',
        ),
        array(
            'type'     => 'select',
            'name'     => 'ui_theme',
            'label'    => 'UI Theme to use in frontend',
            'options'  => array(
                'base',
                'black-tie',
                'blitzer',
                'cupertino',
                'dark-hive',
                'dot-luv',
                'eggplant',
                'excite-bike',
                'flick',
                'hot-sneaks',
                'humanity',
                'le-frog',
                'mint-choc',
                'overcast',
                'pepper-grinder',
                'redmond',
                'smoothness',
                'south-street',
                'start',
                'sunny',
                'swanky-purse',
                'trontastic',
                'ui-darkness',
                'ui-lightness',
                'vader',
            )
        ),

        array(
            'type'     => 'legend',
            'label'    => 'eMail Options',
        ),
        array(
            'type'     => 'radiogroup',
            'name'     => 'send_mail',
            'label'    => 'Send eMail',
            'title'    => 'Send new submissions as mail',
            'options'  => array( 'y' => 'Yes', 'n' => 'No' ),
            'checked'  => 'y',
        ),
        array(
            'type'     => 'text',
            'name'     => 'mail_to',
            'label'    => 'Mail to address',
            'allow'    => 'email',
        ),
        array(
            'type'     => 'text',
            'name'     => 'mail_from',
            'label'    => 'Mail from address',
            'allow'    => 'email',
        ),
        array(
            'type'     => 'text',
            'name'     => 'mail_from_name',
            'label'    => 'Mail from name',
            'allow'    => 'string',
        ),
        array(
            'type'     => 'text',
            'name'     => 'mail_subject',
            'label'    => 'Mail subject',
            'allow'    => 'string',
        ),

        array(
            'type'     => 'legend',
            'label'    => 'Success Options',
        ),
        array(
            'type'     => 'radiogroup',
            'name'     => 'success_send_mail',
            'label'    => 'Send eMail',
            'title'    => 'If enabled, a success email is sent to the form sender.',
            'options'  => array( 'y' => 'Yes', 'n' => 'No' ),
            'checked'  => 'y',
        ),
        array(
            'type'     => 'select',
            'name'     => 'success_mail_to_field',
            'label'    => 'Mail to address field',
            'options'  => array(),
        ),
        array(
            'type'     => 'text',
            'name'     => 'success_mail_from',
            'label'    => 'Mail from address',
            'allow'    => 'email',
        ),
        array(
            'type'     => 'text',
            'name'     => 'success_mail_from_name',
            'label'    => 'Mail from name',
            'allow'    => 'string',
        ),
        array(
            'type'     => 'text',
            'name'     => 'success_mail_subject',
            'label'    => 'Mail subject',
            'allow'    => 'string',
        ),
        array(
            'type'     => 'textarea',
            'name'     => 'success_mail_body',
            'label'    => 'Mail body',
            'allow'    => 'plain',
        ),
        array(
            'type'     => 'select',
            'name'     => 'success_page',
            'label'    => 'Show page after finish',
            'options'  => array(),
        ),
    ),
);