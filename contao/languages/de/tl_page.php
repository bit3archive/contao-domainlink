<?php

/**
 * DomainLink extension for the Contao Open Source CMS
 *
 * Copyright (C) 2013 bit3 UG <http://bit3.de>
 *
 * @package DomainLink
 * @author  Tristan Lins <tristan.lins@bit3.de>
 * @link    http://bit3.de
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_page']['secureDNS'] = array(
	'Gesicherte Domain',
	'Hier können Sie angeben, ob geschütze oder ungeschützte URLs generiert werden sollen. Automatisch bestimmt das Protokoll anhand des Requests, die anderen Optionen forcieren einen http:// Link, auch wenn die Seite mit https:// aufgerufen wurde und umgekehrt!'
);


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_page']['dns_mode']['inherit'] = 'Globale Einstellung verwenden';
$GLOBALS['TL_LANG']['tl_page']['dns_mode']['auto'] = 'Automatisch';
$GLOBALS['TL_LANG']['tl_page']['dns_mode']['insecure'] = 'Reguläre HTTP Links erzeugen';
$GLOBALS['TL_LANG']['tl_page']['dns_mode']['secure'] = 'Gesicherte HTTPs Links erzeugen';
