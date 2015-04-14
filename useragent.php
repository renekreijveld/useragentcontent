<?php
/**
 * @copyright   Copyright (c)2015 DSD business internet / gakijken.nl
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 *              Based on the excellent work of Pieter-Jan de Vries, Open Source Design
 */

defined('_JEXEC') or die();

jimport('joomla.plugin.plugin');

class plgContentUseragent extends JPlugin
{
	/*
	 * Any content between the tags will be rendered 
	 * only if the visitors browser is a desktop
	 */
	private function processDevice($text, $ualayout, $layout) {
		// Search for this tag in the content
		switch ($layout) {
			case 'desktop':
				$regex = "#{(!?)ifdesktop}(.*?){/ifdesktop}#s";
				break;			
			case 'tablet':
				$regex = "#{(!?)iftablet}(.*?){/iftablet}#s";
				break;			
			case 'mobile':
				$regex = "#{(!?)ifmobile}(.*?){/ifmobile}#s";
				break;			
		}
		$isDevice = ($ualayout == $layout ? true : false);

		$text = preg_replace_callback($regex, function ($match) use ($isDevice) {
			if ($match[1]) {
				return $isDevice ? '' : $match[2];
			} else {
				return $isDevice ? $match[2] : '';
			}
		}, $text);

		return $text;
	}

	public function onContentPrepare($context, &$article, &$params, $page=0) {
		if (!class_exists('Mobile_Detect')) {
			include_once(dirname(__FILE__).'/lib/Mobile_Detect.php');
		}
		$detect = new Mobile_Detect();
		$ualayout = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'desktop');
		if (($detect->is('Bot')) || ($detect->is('MobileBot'))) $ualayout = 'bot';

		$this->article = $article;

		if (isset($article->text)) {
			$text = $article->text;
		} else if (isset($article->introtext)) {
			$text = $article->introtext;
		} else {
			$text = '';
		}

		if (!empty($text)) {
			// Check whether the plugin should process or not
			if (JString::strpos($text, '{ifdesktop') !== false || JString::strpos($text, '{!ifdesktop')) {
				$text = $this->processDevice($text, $ualayout, "desktop");
			}

			if (JString::strpos($text, '{iftablet') !== false || JString::strpos($text, '{!iftablet')) {
				$text = $this->processDevice($text, $ualayout, "tablet");
			}

			if (JString::strpos($text, '{ifmobile') !== false || JString::strpos($text, '{!ifmobile')) {
				$text = $this->processDevice($text, $ualayout, "mobile");
			}

			if (isset($article->introtext)){
				$article->introtext = $text;
			}
			if (isset($article->text)){
				$article->text = $text;
			}
		}
		
		return '';
	}
}