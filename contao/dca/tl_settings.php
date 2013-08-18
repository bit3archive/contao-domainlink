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
 * System configuration
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{dns_legend:hide},baseDNS,secureDNS;{domainLink_legend:hide},traceDomainLink,forceAbsoluteDomainLink';

$GLOBALS['TL_DCA']['tl_settings']['fields']['baseDNS'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_settings']['baseDNS'],
	'inputType'     => 'text',
	'eval'          => array('decodeEntities' => true, 'maxlength' => 255, 'tl_class' => 'w50'),
	'save_callback' => array(array('tl_settings_domainlink', 'saveBaseDNS'))
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['secureDNS'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_settings']['secureDNS'],
	'inputType' => 'select',
	'options'   => array('auto', 'insecure', 'secure'),
	'reference' => &$GLOBALS['TL_LANG']['tl_settings']['dns_mode'],
	'eval'      => array('tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['traceDomainLink'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_settings']['traceDomainLink'],
	'inputType' => 'checkbox',
	'eval'      => array('tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['forceAbsoluteDomainLink'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_settings']['forceAbsoluteDomainLink'],
	'inputType' => 'checkbox',
	'eval'      => array('tl_class' => 'w50')
);

/**
 * DCA function class
 */
class tl_settings_domainlink
{
	public function saveBaseDNS($strValue)
	{
		$strValue = preg_replace('#/.*#', '', preg_replace('#^\w+://#', '', $strValue));
		if (
			strlen($strValue) > 0 &&
			!preg_match(
				'#^([\w\-]+(\.[\w\-]+)*?|(\d{1,3}\.){3}\d{1,3})(:\d+)?$#',
				$strValue
			)
		) {
			throw new Exception(sprintf($GLOBALS['TL_LANG']['MSC']['invalidDomain'], $strValue));
		}
		return $strValue;
	}
}
