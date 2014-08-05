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

$LANG = array(
// ----- globals -----
    'This item is required' => 'Pflichtfeld',
    'Submit' => 'Absenden',
    'Success' => 'Erfolgreich',
    '[please choose one...]' => '[bitte eine auswählen...]',
// ----- frontend -----
    'Form submission succeeded' => 'Formular erfolgreich gesendet',
    'No form configured yet' => 'Formular noch nicht konfiguriert',
    'Thank you for yoursubmission!' => 'Vielen Dank für Ihre Mitteilung!',
// ----- preset/form page -----
    'Edit form' => 'Formular anpassen',
    'Please choose a preset...' => 'Bitte wählen Sie eine Vorlage...',
    'Preset' => 'Vorlage',
    'Reset to Preset' => 'Auf Vorlage zurücksetzen',
// ----- entries page -----
    'Export successful' => 'Export erfolgreich',
    'Guest (not logged in)' => 'Besucher (nicht angemeldet)',
    'Invalid submission ID! (No such item.)' => 'Ungültige Eintrags-ID! (Eintrag nicht gefunden.)',
    'No entries so far' => 'Keine Einträge gefunden',
    'Send reply' => 'Beantworten',
    'The preset will be used to populate the form with some basic elements. You can add/remove elements later.'
        => 'Das Preset wird verwendet um das Formular mit einigen Standardelementen zu füllen. Sie können später Elemente hinzufügen oder entfernen.',
    'View entry details' => 'Details ansehen',
    'Your request' => 'Ihre Anfrage',
// ----- entry details page -----
    'Your reply was sent.' => 'Antwort wurde gesendet',
    'Unable to send the mail!' => 'Fehler beim Versand der Mail!',
// ----- settings -----
    'Allow attachments' => 'Anhänge erlauben',
    'Common settings' => 'Allgemeine Einstellungen',
    'Do you wish to allow to send (=upload) files? Please note: Attachments are potentially harmful and are uploaded to your server!' => 'Wollen Sie den Versand (=Upload) von Anhängen erlauben? Hinweis: Anhänge können schädlich sein und werden auf den Server hochgeladen!',
    'eMail Options' => 'eMail Optionen',
    'How to protect your form. Honeypot (ASP) is non-obstrusive and a workable compromise, as many people feel bothered by Captchas.' => 'Bestimmt den Schutzmechanismus zum Schutz vor Spam. Honeypot ist ein guter Kompromiß. Captchas empfinden viele Besucher als störend.',
    'If enabled, a success email is sent to the form sender.' => 'Wenn aktiviert, wird dem Absender des Formulars eine Bestätigungsmail geschickt.',
    'If yes: Allowed file types (by suffix)' => 'Falls ja: Erlaubte Datei-Typen (nach Endung)',
    'If yes: Max. size for attachments' => 'Falls ja: Max. Größe für Anhänge',
    'Mail body' => 'eMail Text',
    'Mail from address' => 'Absender Mailadresse',
    'Mail from name' => 'Absender Name',
    'Mail subject' => 'Betreff',
    'Mail to address' => 'Mail an (Adresse)',
    'Mail to address field' => 'Eingabefeld mit Mailadresse',
    'no protection' => 'kein Schutz',
    'Protect form with' => 'Schütze Formular mit',
    'Send eMail' => 'eMails verschicken',
    'Send new submissions as mail' => 'Über neue Einträge per Mail informieren',
    'Separate suffixes by comma (,)' => 'Endungen mit Komma (,) trennen',
    'Set the max. size for attachments in Bytes; please note that only digits are allowed' => 'Maximale Größe für Anhänge in Bytes; nur Ziffern sind erlaubt',
    'Show page after finish' => 'Bei Erfolg auf Seite weiterleiten',
    'Success message' => 'Erfolgreich Text',
    'Success Options' => 'Erfolgreich Optionen',
    'This field is required because "allow attachments" is checked' => 'Dieses Feld ist ein Pflichtfeld, da "Anhänge erlauben" aktiviert ist',
    'This field is required because "send mail" is checked' => 'Dieses Feld ist ein Pflichtfeld, da "eMails verschicken" aktiviert ist',
    'UI Theme to use in frontend' => 'UI Theme für das Frontend',
    'WB style captcha' => 'WB Stil Captcha',
// ----- preview -----
    'Preview' => 'Vorschau',
    'This is a preview of your form. The presentation in the frontend may differ.' => 'Dies ist eine Vorschau des Formulars. Die Darstellung im Frontend sieht möglicherweise anders aus.',
// ----- add / edit element dialog -----
    'Element (field) name' => 'Element (Feld) Name',
    'Field'       => 'Feld',
    'on top'      => 'am Anfang',
    'at bottom'   => 'am Ende',
    'after...'    => 'hinter...',
    'Required'    => 'Pflichtfeld',
    'Edit field (one line)' => 'Eingabefeld (einzeilig)',
    'Text field (multiline)' => 'Textfeld (mehrzeilig)',
    'Please note: You cannot edit the field name because it makes old form submissions invalid'
        => 'Hinweis: Der Feldname kann nicht geändert werden, da sonst bestehende Einträge ungültig werden',
// ----- presets ------
    'Standard contact form with 4 fields'
        => 'Standard Kontaktformular mit 4 Feldern',
    'Standard shipping form'
        => 'Standard Versandformular',
    'Sender name' => 'Absender Name',
    'Please enter your full name here' => 'Bitte hier den Absendernamen angeben',
    'Sender eMail' => 'Absender eMail',
    'Please enter your eMail address, so we can send you a reply' => 'Bitte die Absender-Mailadresse angeben, so dass eine Antwort möglich ist',
    'Subject' => 'Betreff',
    'Please enter an eMail subject' => 'Bitte einen Betreff angeben',
    'Message' => 'Mitteilung',
    'Please enter your message here. No HTML or other markup is allowed.' => 'Bitte hier die Mitteilung eingeben. HTML oder andere Markups sind nicht zulässig.',
    'Company name' => 'Firma',
    'Title' => 'Anrede',
    'Telephone number' => 'Telefonnummer',
    'Faksimile number' => 'Faxnummer',
    'Street' => 'Straße',
    'City' => 'Ort',
    'Zip code' => 'Postleitzahl',
    'Country' => 'Land',
    'Contact data' => 'Kontaktdaten',
    'Shipping address' => 'Versandadresse',
);