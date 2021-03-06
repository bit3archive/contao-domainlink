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
 * Class DomainLink
 *
 * Finden und erstellen von Domainübergreifenden Links.
 *
 * @package DomainLink
 */
class DomainLink extends Controller
{
	/**
	 * Singleton
	 *
	 * @var DomainLink
	 */
	private static $objInstance = null;


	/**
	 * Singleton
	 */
	public static function getInstance()
	{
		if (self::$objInstance == null) {
			self::$objInstance = new DomainLink();
		}
		return self::$objInstance;
	}


	/**
	 * DNS page related cache.
	 *
	 * @var array
	 */
	protected $arrDNSCache = array();


	/**
	 * DNS security related cache.
	 *
	 * @var array
	 */
	protected $arrSecurityCache = array();


	/**
	 * Page trail related cache.
	 *
	 * @var array
	 */
	protected $arrTrailCache = array();


	/**
	 * The trace file.
	 *
	 * @var string
	 */
	protected $file = null;

	/**
	 * Initialize the object
	 */
	protected function __construct()
	{
		parent::__construct();
		$this->import('Database');

		if ($GLOBALS['TL_CONFIG']['traceDomainLink']) {
			$this->file   = TL_ROOT . '/system/logs/traceDomainLink-' . time() . '-r' . rand() . '.log';
			$strTraceFile = fopen($this->file, 'a');
			fwrite($strTraceFile, "Request URL: " . $this->Environment->request . "\n\n");
			fclose($strTraceFile);
		}
	}

	/**
	 * 
	 * @param type $objPage
	 * @return array
	 */
	public function getPageTrail($intId) {

		if (!strlen($intId) ||$intId < 1)
		{
			return array();
		}
		
		//check for cached results
		if (isset($this->arrTrailCache[$intId]))
		{
			return $this->arrTrailCache[$intId];
		}
		
		$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
						->limit(1)
						->execute($intId);

		if ($objPage->numRows < 1)
		{
			return array();
		}
		
		$arrTrail =  ($objPage->type != 'root') ? $this->getPageTrail($objPage->pid) : array();
		$arrTrail[] = $objPage->id;
		
		return $this->arrTrailCache[$intId] = $arrTrail;
	}

	/**
	 * Search recursive the page dns.
	 *
	 * @param $objPage array|Database_Result
	 *
	 * @return string
	 */
	public function findPageDNS($objPage)
	{
		if ($objPage != null) {
			$intId = is_array($objPage) ? $objPage['id'] : $objPage->id;

			// use cached dns
			if (isset($this->arrDNSCache[$intId])) {
				return $this->arrDNSCache[$intId];
			}

			// get pageObjects
			if (is_array($objPage))
			{
				$objPage = $this->Database->prepare('SELECT * FROM tl_page WHERE id = ?')->execute($objPage['id']);
			}

			// the current page is the root page
			if ($objPage->type == 'root') {
				if (strlen($objPage->dns)) {
					$this->arrSecurityCache[$objPage->id] = $objPage->secureDNS;
					return $this->arrDNSCache[$objPage->id] = $objPage->dns;
				}
			}
			// search for a root page with defined dns
			else {
				$arrTrail = (!is_array($objPage->trail)) ? $this->getPageTrail($objPage->id) : $objPage->trail;
				if (is_array($arrTrail) && count($arrTrail) > 0) {
					$objRootPage = $this->Database->execute(
						"
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
								1"
					);
					if ($objRootPage->next()) {
						foreach ($arrTrail as $intId) {
							$this->arrSecurityCache[$intId] = $objRootPage->secureDNS;
							$this->arrDNSCache[$intId]      = $objRootPage->dns;
						}
						return $this->arrDNSCache[$intId];
					}
				}
			}

			// no page dns found, use base dns
			if (!empty($GLOBALS['TL_CONFIG']['baseDNS'])) {
				$this->arrDNSCache[$intId] = $GLOBALS['TL_CONFIG']['baseDNS'];
			}

			// no base dns defined, use request dns
			else {
				$xhost                     = $this->Environment->httpXForwardedHost;
				$this->arrDNSCache[$intId] = (!empty($xhost) ? $xhost . '/' : '') . $this->Environment->httpHost;
			}

			return $this->arrDNSCache[$intId];
		}

		// no page dns found, use base dns
		if (!empty($GLOBALS['TL_CONFIG']['baseDNS'])) {
			return $GLOBALS['TL_CONFIG']['baseDNS'];
		}

		// no base dns defined, use request dns
		else {
			$xhost = $this->Environment->httpXForwardedHost;
			return (!empty($xhost) ? $xhost . '/' : '') . $this->Environment->httpHost;
		}
	}


	/**
	 * Search recursive the page security.
	 *
	 * @param $objPage array|Database_Result
	 *
	 * @return string
	 */
	public function findPageSecurity($objPage)
	{
		if ($objPage != null) {
			$intId = is_array($objPage) ? $objPage['id'] : $objPage->id;

			// use cached security
			if (isset($this->arrSecurityCache[$intId])) {
				return $this->arrSecurityCache[$intId];
			}

			// get pageObject
			if (is_array($objPage))
			{
				$objPage = $this->Database->prepare('SELECT * FROM tl_page WHERE id = ?')->execute($objPage['id']);
			}

			// the current page is the root page
			if ($objPage->type == 'root') {
				if (!empty($objPage->dns)) {
					$this->arrDNSCache[$objPage->id] = $objPage->dns;
					return $this->arrSecurityCache[$objPage->id] = $objPage->secureDNS ? $objPage->secureDNS
						: $GLOBALS['TL_CONFIG']['secureDNS'];
				}
			}
			// search for a root page with defined dns security
			else {
				$arrTrail = (!is_array($objPage->trail)) ? $this->getPageTrail($objPage->id) : $objPage->trail;
				if (is_array($arrTrail) && count($arrTrail) > 0) {
					$objRootPage = $this->Database->execute(
						"
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
								1"
					);
					if ($objRootPage->next()) {
						foreach ($arrTrail as $intId) {
							$this->arrSecurityCache[$intId] = $objRootPage->secureDNS ? $objRootPage->secureDNS
								: $GLOBALS['TL_CONFIG']['secureDNS'];
							$this->arrDNSCache[$intId]      = $objRootPage->dns;
						}
						return $this->arrSecurityCache[$intId];
					}
				}
			}

			// no page dns security found, use global dns security
			if (!empty($GLOBALS['TL_CONFIG']['secureDNS'])) {
				$this->arrSecurityCache[$intId] = $GLOBALS['TL_CONFIG']['secureDNS'];
			}

			// no global dns security defined, use auto mode
			else {
				$this->arrSecurityCache[$intId] = 'auto';
			}

			return $this->arrSecurityCache[$intId];
		}

		// no page dns security found, use global dns security
		if (!empty($GLOBALS['TL_CONFIG']['secureDNS'])) {
			return $GLOBALS['TL_CONFIG']['secureDNS'];
		}

		// no global dns security defined, use auto mode
		else {
			return 'auto';
		}
	}


	/**
	 * Replace insert tags with their values
	 *
	 * @param $strBuffer string
	 * @param $blnCache  boolean
	 *
	 * @return string
	 */
	public function replaceDomainLinkInsertTags($strBuffer, $blnCache = false)
	{
		global $objPage;

		switch ($strBuffer) {
			case 'dns::domain':
				return $this->findPageDNS($objPage != null ? $objPage->row() : null);
		}

		return false;
	}


	/**
	 * Absolutize all URLs of href,src attributes and URL() attributes of styles
	 *
	 * @param $strHtml string
	 * @param $objPage Database_Result|null
	 *
	 * @return string
	 */
	public function absolutizeHTML($strHtml, Database_Result $objPage = null)
	{
		$objHelper = new DomainLinkHtmlReplaceHelper($this, $objPage);

		// replace href and src attributes like href="myLink.html"
		$strHtml = preg_replace_callback(
			'~(href|src)=(["\'])([^"\']+)(["\'])~i',
			array($objHelper, 'replaceHrefSrc'),
			$strHtml
		);
		// replace style-url attributes like url('myImage.jpg')
		$strHtml = preg_replace_callback(
			'~(url\(["\']?)([^"\'\)]+)(["\']?\))~i',
			array($objHelper, 'replaceCssUrl'),
			$strHtml
		);

		return $strHtml;
	}


	/**
	 * Absolutize an url.
	 *
	 * @param $strUrl  string
	 * @param $objPage Database_Result
	 *
	 * @return string
	 */
	public function absolutizeUrl($strUrl, Database_Result $objPage = null)
	{
		if (!$objPage) {
			$objPage = & $GLOBALS['objPage'];
		}

		// decode encoded mailto: urls
		$strUrl = html_entity_decode($strUrl);

		if (!preg_match('/^#/', $strUrl) && !preg_match('#^(\w+:)#', $strUrl) && !preg_match(
				'#^\{\{.*\}\}$#',
				$strUrl
			)
		) {
			// find the target page dns
			$strDns = $this->findPageDNS($objPage);

			// find the protocol
			switch ($this->findPageSecurity($objPage)) {
				case 'insecure':
					$strProtocol = 'http';
					if ($this->Environment->ssl) {
						$blnForce = true;
					}
					break;

				case 'secure':
					$strProtocol = 'https';
					if (!$this->Environment->ssl) {
						$blnForce = true;
					}
					break;

				default:
				case 'auto':
					if ($this->Environment->ssl) {
						$strProtocol = 'https';
					}
					else {
						$strProtocol = 'http';
					}
					break;
			}

			$strUrl = $strProtocol . '://' . $strDns . ($strUrl[0] == '/' ? ''
					: $GLOBALS['TL_CONFIG']['websitePath'] . '/') . $strUrl;
		}
		return $strUrl;
	}

	/**
	 * Generate an absolute url if the domain of the target page is different from the domain of the current page.
	 *
	 * @param $arrRow    array
	 * @param $strParams string
	 * @param $strUrl    string
	 * @param $blnForce  boolean
	 *
	 * @return string
	 */
	public function generateDomainLink($arrRow, $strParams, $strUrl, $blnForce = false)
	{
		$arrTrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

		if ($GLOBALS['TL_CONFIG']['traceDomainLink']) {
			$strTraceFile = fopen($this->file, 'a');
			fwrite($strTraceFile, "URL: $strUrl\nbacktrace\n");
			foreach ($arrTrace as $arrCall) {
				fwrite(
					$strTraceFile,
					"  call $arrCall[class]$arrCall[type]$arrCall[function]() from $arrCall[file][$arrCall[line]]\n"
				);
			}
		}

		// check for incompatible calls and cancel processing
		foreach ($arrTrace as $arrCall) {
			if (isset($GLOBALS['DNS']['incompatibleComponents'][$arrCall['class']])
				&& ($GLOBALS['DNS']['incompatibleComponents'][$arrCall['class']] === true
					|| in_array($arrCall['function'], $GLOBALS['DNS']['incompatibleComponents'][$arrCall['class']]))
			) {
				if ($GLOBALS['TL_CONFIG']['traceDomainLink']) {
					fprintf(
						$strTraceFile,
						"Result: cancel processing, %s:%s was rejected as incompatible\n",
						$arrCall['class'],
						$arrCall['function']
					);
					fwrite($strTraceFile, "\n\n");
					fclose($strTraceFile);
				}
				return $strUrl;
			}
			if ($GLOBALS['TL_CONFIG']['traceDomainLink']) {
				fprintf(
					$strTraceFile,
					"Check compatibility of %s:%s was accepted\n",
					$arrCall['class'],
					$arrCall['function']
				);
			}
		}

		global $objPage;
		if (!preg_match('#^(\w+:)#', $strUrl) && !preg_match('#^\{\{.*\}\}$#', $strUrl)) {
			// find the current page dns
			$strCurrent = $this->findPageDNS($objPage);
			// find the target page dns
			$strTarget = $this->findPageDNS($arrRow);
			// force absolute url
			$blnForce = $blnForce || $GLOBALS['TL_CONFIG']['forceAbsoluteDomainLink'] ? true
				: $strCurrent != $strTarget;
			// find the protocol
			switch ($this->findPageSecurity($arrRow)) {
				case 'insecure':
					$strProtocol = 'http';
					if ($this->Environment->ssl) {
						$blnForce = true;
					}
					break;

				case 'secure':
					$strProtocol = 'https';
					if (!$this->Environment->ssl) {
						$blnForce = true;
					}
					break;

				default:
				case 'auto':
					if ($this->Environment->ssl) {
						$strProtocol = 'https';
					}
					else {
						$strProtocol = 'http';
					}
					break;
			}
			if (strlen($strTarget) && $blnForce) {
				$strUrl = $strProtocol . '://' . $strTarget . ($strUrl[0] == '/' ? ''
						: $GLOBALS['TL_CONFIG']['websitePath'] . '/') . $strUrl;
				if ($GLOBALS['TL_CONFIG']['traceDomainLink']) {
					fwrite($strTraceFile, "Result: rewrite url to $strUrl\n");
				}
			}
		}
		if ($GLOBALS['TL_CONFIG']['traceDomainLink']) {
			fwrite($strTraceFile, "\n\n");
			fclose($strTraceFile);
		}

		return $strUrl;
	}
}


/**
 * Class DomainLinkHtmlReplaceHelper
 *
 * Helper class for absolutizeHtml replace callbacks.
 *
 * @package DomainLink
 */
class DomainLinkHtmlReplaceHelper
{
	/**
	 * @var DomainLink
	 */
	protected $objDomainLink;

	/**
	 * @var Database_Result|null
	 */
	protected $objPage;


	/**
	 * Create a new helper object.
	 *
	 * @param $objDomainLink DomainLink
	 * @param $objPage       Database_Result|null
	 */
	public function __construct(DomainLink $objDomainLink, Database_Result $objPage)
	{
		$this->objDomainLink = $objDomainLink;
		$this->objPage       = $objPage;
	}


	/**
	 * replace callback
	 *
	 * @param $arrMatch array
	 *
	 * @return string
	 */
	public function replaceHrefSrc($arrMatch)
	{
		return $arrMatch[1] . '=' . $arrMatch[2] . $this->objDomainLink->absolutizeUrl(
			$arrMatch[3],
			$this->objPage
		) . $arrMatch[4];
	}


	/**
	 * replace callback
	 *
	 * @param $arrMatch array
	 *
	 * @return string
	 */
	public function replaceCssUrl($arrMatch)
	{
		return $arrMatch[1] . $this->objDomainLink->absolutizeUrl($arrMatch[2], $this->objPage) . $arrMatch[3];
	}
}
