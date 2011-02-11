<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * DomainLink
 * Copyright (C) 2011 Tristan Lins
 *
 * Extension for:
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
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    DomainLink
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_page']['wwwDNS'] = array('www. zum Domainnamen hinzufügen', 'Fügt die Subdomain www. vor dem Domainnamen hinzu (aus example.com wird www.example.com).');
$GLOBALS['TL_LANG']['tl_page']['secureDNS']       = array('Gesicherte Domain', 'Hier können Sie angeben, ob geschütze oder ungeschützte URLs generiert werden sollen. Automatisch bestimmt das Protokoll anhand des Requests, die anderen Optionen forcieren einen http:// Link, auch wenn die Seite mit https:// aufgerufen wurde und umgekehrt!');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_page']['dns_mode']['inherit']  = 'Globale Einstellung verwenden';
$GLOBALS['TL_LANG']['tl_page']['dns_mode']['auto']     = 'Automatisch';
$GLOBALS['TL_LANG']['tl_page']['dns_mode']['insecure'] = 'Reguläre HTTP Links erzeugen';
$GLOBALS['TL_LANG']['tl_page']['dns_mode']['secure']   = 'Gesicherte HTTPs Links erzeugen';

?>