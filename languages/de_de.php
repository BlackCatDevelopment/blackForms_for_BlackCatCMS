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
 *   @copyright       2014, Black Cat Development
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
    'Cancel' => 'Abbrechen',
    'No' => 'Nein',
    'This item is required' => 'Pflichtfeld',
    'Submit' => 'Absenden',
    'Success' => 'Erfolgreich',
    'Yes' => 'Ja',
    '[please choose one...]' => '[bitte eine auswählen...]',
// ----- frontend -----
    'Form submission succeeded' => 'Formular erfolgreich gesendet',
    'No form configured yet' => 'Formular noch nicht konfiguriert',
    'Thank you for yoursubmission!' => 'Vielen Dank für Ihre Mitteilung!',
// ----- preset/form page -----
    'Completely reset' => 'Komplett zurücksetzen',
    'CSS style' => 'CSS Stil',
    'Edit form' => 'Formular anpassen',
    'One option per line. Use <value>|<label> to have labeled options.' => 'Eine Option pro Zeile. <Wert>|<Beschriftung> verwenden, wenn übergebener Wert und Anzeigewert unterschiedlich sind.',
    'Please choose a preset...' => 'Bitte wählen Sie eine Vorlage...',
    'Preset' => 'Vorlage',
    'Reset to preset' => 'Auf Vorlage zurücksetzen',
    'Used if the user does not enter a value' => 'Wird verwendet wenn der Benutzer keinen Wert angibt',
    'Will be shown as little (i) Icon with tooltip on mouse over; keep it short' => 'Wird als kleines (i)-Icon mit einem Tooltip bei Mouseover angezeigt; kurz halten',
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
    'Please note: The recipient will see this mail address in his mail client!'
        => 'Bitte beachten: Der Empfänger sieht diese Mailadresse in seinem Mail Client!',
    'Please note: The recipient will see this name in his mail client'
        => 'Bitte beachten: Der Empfänger sieht diesen Namen in seinem Mail Client',
    'Please note: Replies are not possible as there is no "mail from" address configured in the settings and no CMS email address is set.'
        => 'Hinweis: Das Beantworten ist nicht möglich, da keine "Absender Mailadresse" in den Einstellungen gesetzt ist und es auch keine CMS-Einstellung hierfür gibt.',
    'Your reply was sent.' => 'Antwort wurde gesendet',
    'Unable to send the mail!' => 'Fehler beim Versand der Mail!',
// ----- settings -----
    'Address to use as sender address. Leave blank to use the global (CMS) address.' => 'Adresse für den Absender. Leer lassen, um die globale (CMS) Einstellung zu verwenden.',
    'Address to use for new submission info mail. Leave blank to use the global (CMS) address.' => 'Adresse für Infomail über neue Einträge. Leer lassen, um die globale (CMS) Einstellung zu verwenden.',
    'Allow attachments' => 'Anhänge erlauben',
    'Common settings' => 'Allgemeine Einstellungen',
    'Do you wish to allow to send (=upload) files? Please note: Attachments are potentially harmful and are uploaded to your server!' => 'Wollen Sie den Versand (=Upload) von Anhängen erlauben? Hinweis: Anhänge können schädlich sein und werden auf den Server hochgeladen!',
    'eMail Options' => 'eMail Optionen',
    'For Mojito frontend template, you may try [base], [start] or [redmond], for example' => 'Für Mojito Frontend Template passen z.B. [base], [start] oder [redmond]',
    'How to protect your form. Honeypot (ASP) is non-obstrusive and a workable compromise, as many people feel bothered by Captchas.' => 'Bestimmt den Schutzmechanismus zum Schutz vor Spam. Honeypot ist ein guter Kompromiß. Captchas empfinden viele Besucher als störend.',
    'If enabled, a success email is sent to the form sender.' => 'Wenn aktiviert, wird dem Absender des Formulars eine Bestätigungsmail geschickt.',
    'If yes: Allowed file types (by suffix)' => 'Falls ja: Erlaubte Datei-Typen (nach Endung)',
    'If yes: Max. size for attachments' => 'Falls ja: Max. Größe für Anhänge',
    'If you choose a success page, the success message above will be ignored.' => 'Wird eine Erfolgreich-Seite ausgewählt, wird der obige Erfolgreich Text ignoriert.',
    'Mail body' => 'eMail Text',
    'Mail from address' => 'Absender Mailadresse',
    'Mail from name' => 'Absender Name',
    'Mail subject' => 'Betreff',
    'Mail to address' => 'Mail an (Adresse)',
    'Mail to address field' => 'Eingabefeld mit Mailadresse',
    'no protection' => 'kein Schutz',
    'Protect form with' => 'Schütze Formular mit',
    'Realname to use for sender.' => 'Realname des Absenders.',
    'Reset to defaults' => 'Auf Standardwerte zurücksetzen',
    'Send eMail' => 'eMails verschicken',
    'Send new submissions as mail' => 'Über neue Einträge per Mail informieren',
    'Separate suffixes by comma (,)' => 'Endungen mit Komma (,) trennen',
    'Set the max. size for attachments in Bytes; please note that only digits are allowed' => 'Maximale Größe für Anhänge in Bytes; nur Ziffern sind erlaubt',
    'Show page after finish' => 'Bei Erfolg auf Seite weiterleiten',
    'Subject line to use for the new submission info mail.' => 'Betreffzeile der Infomail über neue Einträge',
    'Success message' => 'Erfolgreich Text',
    'Success Options' => 'Erfolgreich Optionen',
    'This field is required because "allow attachments" is checked' => 'Dieses Feld ist ein Pflichtfeld, da "Anhänge erlauben" aktiviert ist',
    'This field is required because "send mail" is checked' => 'Dieses Feld ist ein Pflichtfeld, da "eMails verschicken" aktiviert ist',
    'UI Theme to use in frontend' => 'UI Theme für das Frontend',
    'WB style captcha' => 'WB Stil Captcha',
    'You may reset the settings to default here.' => 'Die Einstellungen können hiermit auf Standardwerte zurückgesetzt werden.',
    'You may use any form field as part of the success message. Use {$<Fieldname>} as placeholder. To see a list of available field names, open [Mail to address field] dropdown.'
        => 'Alle Formularfelder können als Platzhalter in der Erfolgreich-Mitteilung verwendet werden. Hierzu {$<Fieldname>} als Platzhalter verwenden. Die Liste der Feldnamen ist über das [Eingabefeld mit Mailadresse] Dropdown zu finden.',
// ----- preview -----
    'Preview' => 'Vorschau',
    'This is a preview of your form. The presentation in the frontend may differ.' => 'Dies ist eine Vorschau des Formulars. Die Darstellung im Frontend sieht möglicherweise anders aus.',
// ----- add / edit element dialog -----
    'Add field'   => 'Element hinzufügen',
    'after...'    => 'hinter...',
    'at bottom'   => 'am Ende',
    'Country select' => 'Länderauswahl (Dropdown)',
    'Default value' => 'Standardwert',
    'Edit field (one line)' => 'Eingabefeld (einzeilig)',
    'Element (field) name' => 'Element (Feld) Name',
    'Field'       => 'Feld',
    'Hidden field' => 'Verstecktes Feld',
    'Image select' => 'Bildauswahl (Dropdown)',
    'Label' => 'Beschriftung',
    'on top'      => 'am Anfang',
    'Options (for select/radio/checkbox)' => 'Optionen (für Select/Radio/Checkbox)',
    'Please note: You cannot edit the field name and type as it makes no sense.'
        => 'Hinweis: Der Feldname und -typ kann nicht geändert werden, da das nicht sinnvoll ist.',
    'Required'    => 'Pflichtfeld',
    'Save as new preset' => 'Als neues Preset speichern',
    'Styling is handled via jQuery UI by default. Only add styles here if you really need them.' => 'Das Styling wird via jQuery UI gehandhabt. Hier nur Styles hinzufügen, wenn sie wirklich benötigt werden.',
    'Text field (multiline)' => 'Textfeld (mehrzeilig)',
    'Type' => 'Typ',
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