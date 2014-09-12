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

/**
 * this is for BlackCat Core, while the other one is for wbForms!
 **/
$LANG2 = array();
require dirname(__FILE__).'/de_de.php';
if(count($LANG)) $LANG2 = $LANG;

$LANG = array_merge(
    $LANG2,
    array(
        'Are you sure?' => 'Sind Sie sicher?',
        'Are you sure that you really want to delete this field?' => 'Soll dieses Formularfeld wirklich gelöscht werden?',
        'Are you sure that you really want to DELETE this form?' => 'Soll dieses Formular wirklich KOMPLETT GELÖSCHT werden?',
        'Are you sure that you really want to reset this form?' => 'Soll dieses Formular wirklich auf die Vorlage zurückgesetzt werden?',
        'Date' => 'Datum',
        'Delete selected' => 'Ausgewählte löschen',
        'Entries' => 'Einträge',
        'Export' => 'Exportieren',
        'Exports' => 'Exporte',
        'Filename' => 'Dateiname',
        'Form' => 'Formular',
        'Insert a name' => 'Bitte einen Namen eingeben',
        'Item properties' => 'Elementeigenschaften',
        'Message' => 'Mitteilung',
        'No exports found' => 'Keine Exporte gefunden',
        'Options (&lt;ID&gt;:&lt;Value&gt;)' => 'Optionen (&lt;ID&gt;:&lt;Wert&gt;)',
        'Preview' => 'Vorschau',
        'Reply' => 'Antwort',
        'Responded' => 'Beantwortet',
        'Selected...' => 'Ausgewählte...',
        'Send reply' => 'Beantworten',
        'Size' => 'Größe',
        'Subject' => 'Betreff',
        'Submission details' => 'Eintragsdetails',
        'Submission ID' => 'EintragsID',
        'Submission Date' => 'Datum',
        'Submitted by' => 'Absender',
        'Submission size' => 'Größe der Daten',
        'to file (name)' => 'in Datei (Name)',
));