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
 * Settings
 */
$GLOBALS['TL_CONFIG']['traceDomainLink']         = false;
$GLOBALS['TL_CONFIG']['forceAbsoluteDomainLink'] = false;


/**
 * Incompatible components.
 */
$GLOBALS['DNS']['incompatibleComponents'] = array
(
	'Automator' => array('generateSitemap'),
	'ModuleMaintenance' => true,
	'ModuleChangelanguage' => true,
	'GoogleSitemap' => array('generateSitemap'),
	'ModuleCatalog' => array('generateFilter'),
	'News' => array('generateFiles'),
);


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['generateFrontendUrl'][] = array('DomainLink', 'generateDomainLink');
$GLOBALS['TL_HOOKS']['replaceInsertTags'][]   = array('DomainLink', 'replaceDomainLinkInsertTags');
