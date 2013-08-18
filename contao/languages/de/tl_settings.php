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
$GLOBALS['TL_LANG']['tl_settings']['baseDNS']                 = array(
	'Basis Domainname',
	'Hier können Sie den Basis Domainnamen angeben, der genutzt wird, falls es nicht möglich ist den Domainnamen dynamisch zu bestimmen. Dies ist z.B. beim generieren des Newsletters nicht möglich oder im Mehrsprachenbetrieb, bei dem nur die Sprache, aber keine Domain in der Wurzelseite eingetragen ist.'
);
$GLOBALS['TL_LANG']['tl_settings']['secureDNS']               = array(
	'Gesicherte Domain',
	'Hier können Sie angeben, ob geschütze oder ungeschützte URLs generiert werden sollen. Automatisch bestimmt das Protokoll anhand des Requests, die anderen Optionen forcieren einen http:// Link, auch wenn die Seite mit https:// aufgerufen wurde und umgekehrt!'
);
$GLOBALS['TL_LANG']['tl_settings']['traceDomainLink']         = array(
	'Entwicklermodus',
	'Hiermit wird der Tracing und Entwicklermodus von DomainLink aktiviert. Ist dieser aktiviert logt DomainLink zu jedem Request sein Verhalten in ein Log File, dass unter system/logs/traceDomainLink-X.log zu finden ist.'
);
$GLOBALS['TL_LANG']['tl_settings']['forceAbsoluteDomainLink'] = array(
	'Absolute Adressen erzwingen',
	'Aktivieren Sie diese Option werden alle Links in absolute Links konvertiert.'
);


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
