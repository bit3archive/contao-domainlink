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
	'Base domainname',
	'Specify your base domain here, this is used as fallback.'
);
$GLOBALS['TL_LANG']['tl_settings']['secureDNS']               = array(
	'Secured domain',
	'Specify the security policy.'
);
$GLOBALS['TL_LANG']['tl_settings']['traceDomainLink']         = array(
	'Developer mode',
	'Activate the tracing and developer mode of DomainLink. If its activated a tracing log is generated in system/logs/traceDomainLink-X.log for every single request.'
);
$GLOBALS['TL_LANG']['tl_settings']['forceAbsoluteDomainLink'] = array(
	'Force absolute URLs',
	'Activate this option to force all URLs to be absolute.'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_settings']['dns_legend']        = 'DNS settings';
$GLOBALS['TL_LANG']['tl_settings']['domainLink_legend'] = 'DomainLink settings';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_settings']['dns_mode']['auto']     = 'auto detect';
$GLOBALS['TL_LANG']['tl_settings']['dns_mode']['insecure'] = 'create regular http urls';
$GLOBALS['TL_LANG']['tl_settings']['dns_mode']['secure']   = 'create secured https urls';
