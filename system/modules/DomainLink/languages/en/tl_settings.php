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
$GLOBALS['TL_LANG']['tl_settings']['baseDNS']         = array('Base domainname', 'Specify your base domain here, this is used as fallback.');
$GLOBALS['TL_LANG']['tl_settings']['secureDNS']       = array('Secured domain', 'Specify the security policy.');
$GLOBALS['TL_LANG']['tl_settings']['traceDomainLink'] = array('Developer mode', 'Activate the tracing and developer mode of DomainLink. If its activated a tracing log is generated in system/tmp/traceDomainLink-X.log for every single request.');


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

?>