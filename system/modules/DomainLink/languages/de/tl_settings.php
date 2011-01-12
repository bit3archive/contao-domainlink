<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    DomainLink
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_settings']['baseDNS']         = array('Basis Domainname', 'Hier können Sie den Basis Domainnamen angeben, der genutzt wird, falls es nicht möglich ist den Domainnamen dynamisch zu bestimmen. Dies ist z.B. beim generieren des Newsletters nicht möglich oder im Mehrsprachenbetrieb, bei dem nur die Sprache, aber keine Domain in der Wurzelseite eingetragen ist.');
$GLOBALS['TL_LANG']['tl_settings']['secureDNS']       = array('Gesicherte Domain', 'Hier können Sie angeben, ob geschütze oder ungeschützte URLs generiert werden sollen. Automatisch bestimmt das Protokoll anhand des Requests, die anderen Optionen forcieren einen http:// Link, auch wenn die Seite mit https:// aufgerufen wurde und umgekehrt!');
$GLOBALS['TL_LANG']['tl_settings']['traceDomainLink'] = array('Entwicklermodus', 'Hiermit wird der Tracing und Entwicklermodus von DomainLink aktiviert. Ist dieser aktiviert logt DomainLink zu jedem Request sein Verhalten in ein Log File, dass unter system/tmp/traceDomainLink-X.log zu finden ist.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_settings']['dns_legend']        = 'DNS Einstellungen';
$GLOBALS['TL_LANG']['tl_settings']['domainLink_legend'] = 'DomainLink Einstellungen';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_settings']['dns_mode']['auto']     = 'Automatisch';
$GLOBALS['TL_LANG']['tl_settings']['dns_mode']['insecure'] = 'Reguläre HTTP Links erzeugen';
$GLOBALS['TL_LANG']['tl_settings']['dns_mode']['secure']   = 'Gesicherte HTTPs Links erzeugen';

?>