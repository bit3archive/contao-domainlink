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


$GLOBALS['TL_DCA']['tl_page']['palettes']['root'] = preg_replace(
    '#([,;]dns)([,;])#',
    '$1,secureDNS$2',
    $GLOBALS['TL_DCA']['tl_page']['palettes']['root']
);

$GLOBALS['TL_DCA']['tl_page']['fields']['dns']['eval']['tl_class'] .= ' w50';
$GLOBALS['TL_DCA']['tl_page']['fields']['secureDNS'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_page']['secureDNS'],
    'inputType' => 'select',
    'options'   => array('auto', 'insecure', 'secure'),
    'reference' => &$GLOBALS['TL_LANG']['tl_page']['dns_mode'],
    'eval'      => array(
        'includeBlankOption' => true,
        'blankOptionLabel'   => &$GLOBALS['TL_LANG']['tl_page']['dns_mode']['inherit'],
        'tl_class'           => 'w50'
    )
);
