<?php
/*
	-------------------------------------------------------------------------------------
	Date Created 			:	1/12/2022
	Date Last Modified 		:
	=====================================================================================
	SCREEN					:	index.class.php
	DESCRIPTION				:	This class file store web crawler functions.
	-------------------------------------------------------------------------------------
*/

class CRAWL{
	var $arrCrawledURLs = array();
	var $arrImages = array();
	var $arrInternalLinks = array();
	var $arrExternalLinks = array();
	var $totalPageLoadTime = 0;
	var $strTitles = '';
	var $strContent = '';

	var $arrURLtoCrawl = array();
	var $originalURL = '';

	function crawl_page($url, $pagecnt){
		/*Code to request URL and fetch response time and HTTP status code*/
		$start = microtime(true);
		$html = file_get_contents($url);
		$pageLoadTime = microtime(true)-$start;
		$pageStatusCode = $http_response_header[0];

		/*Set Crawled URL information and Status code*/
		$URLInfo = array();
		if (!in_array($url, $this->arrCrawledURLs)) {
			$URLInfo['URL'] = $url;
			$URLInfo['StatusCode'] = $pageStatusCode;
			array_push($this->arrCrawledURLs, $URLInfo);
		}	

		/*To set total page load time for all the url crawled*/
		$this->totalPageLoadTime += $pageLoadTime;
		
		/*Fetch DOM for given webpage*/
		$dom = new DomDocument('1.0','UTF-8');
		@$dom->loadHTML($html);   

		/*Fetch and store image source information for a web page*/
		$images = $dom->getElementsByTagName('img');   
		foreach ($images as $elements) {
			$src = $elements->getAttribute('data-src'); 
			if (!in_array($src, $this->arrImages)) {
				array_push($this->arrImages, $src);
			}	
		}

		/*Fetch and store internal and external link information for a web page*/
		$anchors = $dom->getElementsByTagName('a');   
		foreach ($anchors as $elements) {
			$href = $elements->getAttribute('href'); 
			if(strlen($href) > 1){
				if(substr($href, 0, 1) == "/"){
					if (!in_array($href, $this->arrInternalLinks)) {
						array_push($this->arrInternalLinks, $href);
					}	
				}else{
					if (!in_array($href, $this->arrExternalLinks)) {
						array_push($this->arrExternalLinks, $href);
					}	
				}
			}
		}

		/*Fetch and store title for a web page*/
		$title = $dom->getElementsByTagName('title')->item(0)->nodeValue;
		$this->strTitles .= ' ' . $title;
		
		/*Fetch and store content for a web page*/
		// Define regx to get rid of style, script etc
		$search = array('@<script[^>]*?>.*?</script>@si',	// Strip out javascript
				   '@<head>.*?</head>@siU',					// Strip out the head section
				   '@<style[^>]*?>.*?</style>@siU',			// Strip out the Style
				   '@<![\s\S]*?--[ \t\n\r]*>@'				// Strip multi-line comments including CDATA
		);
		$content = preg_replace($search, '', $html); 
		$content = str_replace('><', '> <', $content);
		$content = strip_tags($content); 
		$this->strContent  .= ' ' . $content;

		/*Set data for next run*/
		if(strlen($this->originalURL) == 0){
			$this->arrURLtoCrawl = array_slice($this->arrInternalLinks, 0, $pagecnt);
			$this->originalURL = $url;
		}

		//foreach ($this->arrURLtoCrawl as $newurl) {
		if ($pagecnt > 0) {
			$pagecnt--;
			$newurl = $this->originalURL . $this->arrURLtoCrawl[0];
			array_splice($this->arrURLtoCrawl, 0, 1);
			$this->crawl_page($newurl, $pagecnt);
		}	
	}
}
?>