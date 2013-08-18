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
	'Secured domain',
	'Specify the security policy.'
);


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_page']['dns_mode']['inherit']  = 'use global setting';
$GLOBALS['TL_LANG']['tl_page']['dns_mode']['auto']     = 'auto detect';
$GLOBALS['TL_LANG']['tl_page']['dns_mode']['insecure'] = 'create regular http urls';
$GLOBALS['TL_LANG']['tl_page']['dns_mode']['secure']   = 'create secured https urls';
