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
            'invalid'  => 'Invalid eMail address, please check your data',
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

    'reset_form'  => array(
        array(
            'type'     => 'hidden',
            'name'     => 'preset_id',
            'value'    => '0',
        ),
        array(
            'type'     => 'hidden',
            'name'     => 'page_id',
            'value'    => '0',
        ),
        array(
            'type'     => 'hidden',
            'name'     => 'section_id',
            'value'    => '0',
        ),
        array(
            'type'     => 'hidden',
            'name'     => 'do',
            'value'    => 'form',
        ),
        array(
            'type'     => 'submit',
            'id'       => 'save_as_preset',
            'label'    => 'Save as new preset'
        ),
        array(
            'type'     => 'submit',
            'name'     => 'reset_to_preset',
            'label'    => 'Reset to preset'
        ),
        array(
            'type'     => 'submit',
            'name'     => 'complete_reset',
            'label'    => 'Completely reset'
        ),
    ),

    'add_element' => array(
        array(
            'type'     => 'hidden',
            'name'     => 'preset_id',
        ),
        array(
            'type'     => 'text',
            'name'     => 'name',
            'label'    => 'Element (field) name',
            'allow'    => 'text',
            'required' => true,
        ),
        array(
            'type'     => 'select',
            'name'     => 'type',
            'label'    => 'Type',
            'options'  => array(
                'text'          => 'Edit field (one line)',
                'textarea'      => 'Text field (multiline)',
                'select'        => 'Select (Dropdown)',
                'radiogroup'    => 'Radiogroup',
                'checkboxgroup' => 'Checkboxgroup',
                'legend'        => 'Legend',
                'info'          => 'Infotext',
                'countryselect' => 'Country select',
                'imageselect'   => 'Image select',
                'hidden'        => 'Hidden field',
            ),
            'class'    => 'uidisabled',
        ),
        array(
            'type'     => 'text',
            'name'     => 'label',
            'label'    => 'Label',
            'allow'    => 'text',
            'required' => true,
        ),
        array(
            'type'     => 'textarea',
            'name'     => 'infotext',
            'label'    => 'Infotext',
            'allow'    => 'text',
            'required' => false,
            'title'    => 'Will be shown as little (i) Icon with tooltip on mouse over; keep it short',
        ),
        array(
            'type'     => 'text',
            'name'     => 'default_value',
            'label'    => 'Default value',
            'allow'    => 'text',
            'title'    => 'Used if the user does not enter a value',
        ),
        array(
            'type'     => 'textarea',
            'name'     => 'options',
            'label'    => 'Options (for select/radio/checkbox)',
            'title'    => 'One option per line. Use <value>|<label> to have labeled options.',
        ),
        array(
            'type'     => 'text',
            'name'     => 'style',
            'label'    => 'CSS style',
            'title'    => 'Styling is handled via jQuery UI by default. Only add styles here if you really need them.',
            'allow'    => 'text',
        ),
        array(
            'type'     => 'radiogroup',
            'name'     => 'required',
            'label'    => 'Required',
            'options'  => array('Y'=>'Yes','N'=>'No'),
            'checked'  => 'N',
        ),
        array(
            'type'     => 'select',
            'name'     => 'where',
            'label'    => 'Position',
            'options'  => array(
                'top'    => 'on top',
                'bottom' => 'at bottom',
                'after'  => 'after...',
            ),
            'selected' => 'after',
            'class'    => 'uidisabled',
        ),
        array(
            'type'     => 'select',
            'name'     => 'after',
            'label'    => 'Field',
            'options'  => array(),
            'class'    => 'uidisabled',
        ),
    ),

    'edit_element' => array(
        array(
            'type'     => 'hidden',
            'name'     => 'preset_id',
        ),
        array(
            'type'     => 'hidden',
            'name'     => 'name',
        ),
        array(
            'type'     => 'text',
            'name'     => 'display_name',
            'label'    => 'Element (field) name',
            'allow'    => 'text',
            'required' => true,
            'disabled' => true,
        ),
        array(
            'type'     => 'hidden',
            'name'     => 'type',
        ),
        array(
            'type'     => 'info',
            'label'    => 'Please note: You cannot edit the field name and type as it makes no sense.',
        ),
        array(
            'type'     => 'text',
            'name'     => 'label',
            'label'    => 'Label',
            'allow'    => 'text',
            'required' => true,
        ),
        array(
            'type'     => 'textarea',
            'name'     => 'infotext',
            'label'    => 'Infotext',
            'title'    => 'Will be shown as little (i) Icon with tooltip on mouse over; keep it short',
            'allow'    => 'text',
            'required' => false,
        ),
        array(
            'type'     => 'text',
            'name'     => 'default_value',
            'label'    => 'Default value',
            'title'    => 'Used if the user does not enter a value',
            'allow'    => 'text',
        ),
        array(
            'type'     => 'textarea',
            'name'     => 'options',
            'label'    => 'Options (for select/radio/checkbox)',
            'title'    => 'One option per line. Use <value>|<label> to have labeled options.',
        ),
        array(
            'type'     => 'text',
            'name'     => 'style',
            'label'    => 'CSS style',
            'title'    => 'Styling is handled via jQuery UI by default. Only add styles here if you really need them.',
            'allow'    => 'text',
        ),
        array(
            'type'     => 'radiogroup',
            'name'     => 'required',
            'label'    => 'Required',
            'options'  => array('Y'=>'Yes','N'=>'No'),
        ),
    ),

    'reset_settings' => array(
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
            'type'     => 'submit',
            'name'     => 'reset_to_defaults',
            'label'    => 'Reset to defaults'
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
            'name'     => 'protection',
            'label'    => 'Protect form with',
            'title'    => 'How to protect your form. Honeypot (ASP) is non-obstrusive and a workable compromise, as many people feel bothered by Captchas.',
            'options'  => array( '' => 'no protection', 'honeypot' => 'Honeypot (ASP)', 'wb_captcha' => 'WB style Captcha' ),
            'checked'  => '',
        ),
        array(
            'type'     => 'radiogroup',
            'name'     => 'attachments',
            'label'    => 'Allow attachments',
            'title'    => 'Do you wish to allow to send (=upload) files? Please note: Attachments are potentially harmful and are uploaded to your server!',
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
            ),
            'title' => 'For Mojito frontend template, you may try [base], [start] or [redmond], for example',
            'class'    => 'uidisabled',
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
            'title'    => 'Address to use for new submission info mail. Leave blank to use the global (CMS) address.',
        ),
        array(
            'type'     => 'text',
            'name'     => 'mail_from',
            'label'    => 'Mail from address',
            'allow'    => 'email',
            'title'    => 'Address to use as sender address. Leave blank to use the global (CMS) address.',
        ),
        array(
            'type'     => 'text',
            'name'     => 'mail_from_name',
            'label'    => 'Mail from name',
            'allow'    => 'string',
            'title'    => 'Realname to use for sender.',
        ),
        array(
            'type'     => 'text',
            'name'     => 'mail_subject',
            'label'    => 'Mail subject',
            'allow'    => 'string',
            'title'    => 'Subject line to use for the new submission info mail.',
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
            'class'    => 'uidisabled',
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
            'type'     => 'textarea',
            'name'     => 'success_message',
            'label'    => 'Success message',
            'allow'    => 'plain',
            'title'    => 'You may use any form field as part of the success message. Use {$<Fieldname>} as placeholder. To see a list of available field names, open [Mail to address field] dropdown.',
        ),
        array(
            'type'     => 'select',
            'name'     => 'success_page',
            'label'    => 'Show page after finish',
            'title'    => 'If you choose a success page, the success message above will be ignored.',
            'options'  => array(),
            'class'    => 'uidisabled',
        ),
    ),
);