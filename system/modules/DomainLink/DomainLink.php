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
 * Class DomainLink
 *
 * Finden und erstellen von Domainübergreifenden Links.
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    DomainLink
 */
class DomainLink extends Controller
{
	/**
	 * Singleton
	 */
	private static $objInstance = null;


	/**
	 * Singleton
	 */
	public static function getInstance()
	{
		if (self::$objInstance == null)
		{
			self::$objInstance = new DomainLink();
		}
		return self::$objInstance;
	}

	
	/**
	 * DNS page related cache.
	 * @var array
	 */
	protected $arrDNSCache = array();

	
	/**
	 * DNS security related cache.
	 * @var array
	 */
	protected $arrSecurityCache = array();


	/**
	 * The trace file.
	 */
	protected $file = null;

	/**
	 * Initialize the object
	 */
	protected function __construct() {
		parent::__construct();
		$this->import('Database');
		
		if ($GLOBALS['TL_CONFIG']['traceDomainLink'])
		{
			$this->file = TL_ROOT . '/system/logs/traceDomainLink-' . time() . '-r'  . rand() . '.log';
		}
	}
	
	
	/**
	 * Search recursive the page dns.
	 * @param array
	 * @return string
	 */
	public function findPageDNS($objPage) {
		if ($objPage != null) {
			// inherit page details
			if (is_array($objPage))
			{
				$objPage = $this->getPageDetails($objPage['id']);
			}
			
			// use cached dns
			if (isset($this->arrDNSCache[$objPage->id]))
			{
				return $this->arrDNSCache[$objPage->id];
			}
			// the current page is the root page
			else if ($objPage->type == 'root')
			{
				if (strlen($objPage->dns))
				{
					$this->arrSecurityCache[$objPage->id] = $objPage->secureDNS;
					return $this->arrDNSCache[$objPage->id] = $objPage->dns;
				}
			}
			// search for a root page with defined dns
			else
			{
				if (!is_array($objPage->trail))
				{
					$objPage = $this->getPageDetails($objPage->id);
				}
				$arrTrail = $objPage->trail;
				if (is_array($arrTrail) && count($arrTrail) >  0)
				{
					$objRootPage = $this->Database->execute("
							SELECT
								*
							FROM
								`tl_page`
							WHERE
									`id` IN (" . implode(',', $arrTrail) . ")
								AND `type`='root'
								AND `dns`!=''
							ORDER BY
								`id`=" . implode(',`id`=', $arrTrail) . "
							LIMIT
								1");
					if ($objRootPage->next())
					{
						foreach ($arrTrail as $intId)
						{
							$this->arrSecurityCache[$intId] = $objRootPage->secureDNS;
							$this->arrDNSCache[$intId] = $objRootPage->dns;
						}
						return $this->arrDNSCache[$intId];
					}
				}
			}
		}
		
		// no page dns found, use base dns
		if (!empty($GLOBALS['TL_CONFIG']['baseDNS']))
		{
			return $GLOBALS['TL_CONFIG']['baseDNS'];
		}
		
		// no base dns defined, use request dns
		else
		{
			$xhost = $this->Environment->httpXForwardedHost;
			return (!empty($xhost) ? $xhost . '/' : '') . $this->Environment->httpHost;
		}
	}
	
	
	/**
	 * Search recursive the page security.
	 * @param array
	 * @return string
	 */
	public function findPageSecurity($objPage) {
		if ($objPage != null) {
			// inherit page details
			if (is_array($objPage))
			{
				$objPage = $this->getPageDetails($objPage['id']);
			}
			
			// use cached security
			if (isset($this->arrSecurityCache[$objPage->id]))
			{
				return $this->arrSecurityCache[$objPage->id];
			}
			// the current page is the root page
			else if ($objPage->type == 'root')
			{
				if (!empty($objPage->dns))
				{
					$this->arrDNSCache[$objPage->id] = $objPage->dns;
					return $this->arrSecurityCache[$objPage->id] = $objPage->secureDNS ? $objPage->secureDNS : $GLOBALS['TL_CONFIG']['secureDNS'];
				}
			}
			// search for a root page with defined dns security
			else
			{
				if (!is_array($objPage->trail))
				{
					$objPage = $this->getPageDetails($objPage->id);
				}
				$arrTrail = $objPage->trail;
				if (is_array($arrTrail) && count($arrTrail) >  0)
				{
					$objRootPage = $this->Database->execute("
							SELECT
								*
							FROM
								`tl_page`
							WHERE
									`id` IN (" . implode(',', $arrTrail) . ")
								AND `type`='root'
								AND `dns`!=''
							ORDER BY
								`id`=" . implode(',`id`=', $arrTrail) . "
							LIMIT
								1");
					if ($objRootPage->next())
					{
						foreach ($arrTrail as $intId)
						{
							$this->arrSecurityCache[$intId] = $objRootPage->secureDNS ? $objRootPage->secureDNS : $GLOBALS['TL_CONFIG']['secureDNS'];
							$this->arrDNSCache[$intId] = $objRootPage->dns;
						}
						return $this->arrSecurityCache[$intId];
					}
				}
			}
		}
		
		// no page dns security found, use global dns security
		if (!empty($GLOBALS['TL_CONFIG']['secureDNS']))
		{
			return $GLOBALS['TL_CONFIG']['secureDNS'];
		}
		
		// no global dns security defined, use auto mode
		else
		{
			return 'auto';
		}
	}
	
	
	/**
	 * Replace insert tags with their values
	 * @param string
	 * @param bool
	 * @return string
	 */
	public function replaceDomainLinkInsertTags($strBuffer, $blnCache=false)
	{
		global $objPage;

		switch ($strBuffer)
		{
		case 'dns::domain':
			return $this->findPageDNS($objPage != null ? $objPage->row() : null);
		}

		return false;
	}

	
	/**
	 * Absolutize an url.
	 * 
	 * @param string
	 * @param Database_Result
	 * @return string
	 */
	public function absolutizeUrl($strUrl, Database_Result $objPage = null)
	{
		if (!$objPage)
		{
			$objPage = &$GLOBALS['objPage'];
		}

		if (!preg_match('/^#/', $strUrl) && !preg_match('#^(\w+://)#', $strUrl) && !preg_match('#^\{\{[^\}]*_url[^\}]*\}\}$#', $strUrl))
		{
			// find the target page dns
			$strDns = $this->findPageDNS($objPage);
			
			// find the protocol
			switch ($this->findPageSecurity($objPage))
			{
			case 'insecure':
				$strProtocol = 'http';
				if ($this->Environment->ssl)
				{
					$blnForce = true;
				}
				break;

			case 'secure':
				$strProtocol = 'https';
				if (!$this->Environment->ssl)
				{
					$blnForce = true;
				}
				break;
				
			default:
			case 'auto':
				if ($this->Environment->ssl)
				{
					$strProtocol = 'https';
				}
				else
				{
					$strProtocol = 'http';
				}
				break;
			}
			
			$strUrl = $strProtocol . '://' . $strDns . ($strUrl[0] == '/' ? '' : $GLOBALS['TL_CONFIG']['websitePath'] . '/') . $strUrl;
		}
		return $strUrl;
	}
	
	/**
	 * Generate an absolute url if the domain of the target page is different from the domain of the current page.
	 * 
	 * @param array
	 * @param string
	 * @param string
	 * @return string
	 */
	public function generateDomainLink($arrRow, $strParams, $strUrl, $blnForce = false)
	{
		$arrTrace = debug_backtrace();
		
		if ($GLOBALS['TL_CONFIG']['traceDomainLink'])
		{
			$strTraceFile = fopen($this->file, 'a');
			fwrite($strTraceFile, "URL: $strUrl\nbacktrace\n");
			foreach ($arrTrace as $arrCall)
			{
				fwrite($strTraceFile, "  call $arrCall[class]$arrCall[type]$arrCall[function]() from $arrCall[file][$arrCall[line]]\n");
			}
		}

		// check for incompatible calls and cancel processing
		foreach ($arrTrace as $arrCall)
		{
			if (	isset($GLOBALS['DNS']['incompatibleComponents'][$arrCall['class']])
				&&	(	$GLOBALS['DNS']['incompatibleComponents'][$arrCall['class']] === true
					||	in_array($arrCall['function'], $GLOBALS['DNS']['incompatibleComponents'][$arrCall['class']])))
			{
				if ($GLOBALS['TL_CONFIG']['traceDomainLink'])
				{
					fwrite($strTraceFile, "Result: cancel processing, incompatibility check\n");
					fwrite($strTraceFile, "\n\n");
					fclose($strTraceFile);
				}
				return $strUrl;
			}
		}
		
		global $objPage;
		if (!preg_match('#^(\w+://)#', $strUrl) && !preg_match('#^\{\{[^\}]*_url[^\}]*\}\}$#', $strUrl))
		{
			// find the current page dns
			$strCurrent = $this->findPageDNS($objPage);
			// find the target page dns
			$strTarget = $this->findPageDNS($arrRow);
			// force absolute url
			$blnForce = $blnForce || $GLOBALS['TL_CONFIG']['forceAbsoluteDomainLink'] ? true : $strCurrent != $strTarget;
			// find the protocol
			switch ($this->findPageSecurity($arrRow))
			{
			case 'insecure':
				$strProtocol = 'http';
				if ($this->Environment->ssl)
				{
					$blnForce = true;
				}
				break;

			case 'secure':
				$strProtocol = 'https';
				if (!$this->Environment->ssl)
				{
					$blnForce = true;
				}
				break;
				
			default:
			case 'auto':
				if ($this->Environment->ssl)
				{
					$strProtocol = 'https';
				}
				else
				{
					$strProtocol = 'http';
				}
				break;
			}
			if (strlen($strTarget) && $blnForce) {
				$strUrl = $strProtocol . '://' . $strTarget . ($strUrl[0] == '/' ? '' : $GLOBALS['TL_CONFIG']['websitePath'] . '/') . $strUrl;
				if ($GLOBALS['TL_CONFIG']['traceDomainLink'])
				{
					fwrite($strTraceFile, "Result: rewrite url to $strUrl\n");
				}
			}
		}
		if ($GLOBALS['TL_CONFIG']['traceDomainLink'])
		{
			fwrite($strTraceFile, "\n\n");
			fclose($strTraceFile);
		}
		return $strUrl;
	}
	
}
?>