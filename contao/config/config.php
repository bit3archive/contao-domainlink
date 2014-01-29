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
 * Settings
 */
$GLOBALS['TL_CONFIG']['traceDomainLink']         = false;
$GLOBALS['TL_CONFIG']['forceAbsoluteDomainLink'] = false;


/**
 * Incompatible components.
 */
$GLOBALS['DNS']['incompatibleComponents'] = array
(
	'Automator'                => array('generateSitemap'),
	'Controller'               => array('redirectToFrontendPage'),
	'ModuleMaintenance'        => true,
	'ModuleChangelanguage'     => true,
	'PageTeaser'               => true,
	'GoogleSitemap'            => array('generateSitemap'),
	'ModuleCatalog'            => array('generateFilter'),
	'News'                     => array('generateFiles'),
	'Contao\RebuildIndex'      => true,
	'Contao\Automator'         => array('generateSitemap'),
	'Contao\Controller'        => array('redirectToFrontendPage'),
	'Contao\ModuleMaintenance' => true,
	'Contao\News'              => true,
);


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['generateFrontendUrl'][] = array('DomainLink', 'generateDomainLink');
$GLOBALS['TL_HOOKS']['replaceInsertTags'][]   = array('DomainLink', 'replaceDomainLinkInsertTags');
