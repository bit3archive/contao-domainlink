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
 * System configuration
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{dns_legend:hide},baseDNS,secureDNS;{domainLink_legend:hide},traceDomainLink,forceAbsoluteDomainLink';

$GLOBALS['TL_DCA']['tl_settings']['fields']['baseDNS'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['baseDNS'],
	'inputType'               => 'text',
	'eval'                    => array('decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
	'save_callback'           => array(array('tl_settings_domainlink', 'saveBaseDNS'))
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['secureDNS'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['secureDNS'],
	'inputType'               => 'select',
	'options'                 => array('auto', 'insecure', 'secure'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_settings']['dns_mode'],
	'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['traceDomainLink'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['traceDomainLink'],
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['forceAbsoluteDomainLink'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['forceAbsoluteDomainLink'],
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50')
);

/**
 * DCA function class
 */
class tl_settings_domainlink
{
	public function saveBaseDNS($strValue)
	{
		$strValue = preg_replace('#/.*#', '', preg_replace('#^\w+://#', '', $strValue));
		if (strlen($strValue)>0 && !preg_match('#^([\w\-]+(\.[\w\-]+)*?|(\d{1,3}\.){3}\d{1,3})(:\d+)?$#', $strValue))
		{
			throw new Exception(sprintf($GLOBALS['TL_LANG']['MSC']['invalidDomain'], $strValue));
		}
		return $strValue;
	}
}
