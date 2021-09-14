<?php
/**
* Returns recent articles from Facebook Research.
* Example:
* http://www.google.com/search?q=sebsauvage&num=100&complete=0&tbs=qdr:y,sbd:1
*    complete=0&num=100 : get 100 results
*    qdr:y : in past year
*    sbd:1 : sort by date (will only work if qdr: is specified)
*/
class FacebookResearchBridge extends BridgeAbstract {

	const MAINTAINER = 'priyam';
	const NAME = 'Facebook research';
	const URI = 'https://research.fb.com/publications/';
	const CACHE_TIMEOUT = 3600; // 1hr
	const DESCRIPTION = 'Returns most recent results from Facebook Research';

// 	const PARAMETERS = array(array(
// 		'q' => array(
// 			'name' => 'keyword',
// 			'required' => true
// 		)
// 	));

	public function collectData(){

		$html = getSimpleHTMLDOM($this->getURI())
			or returnServerError('No results for this query.');

		$emIsRes = $html->find('div#publications-container', 0);

        // Process articles
		if(!is_null($emIsRes)) {
			foreach($emIsRes->find('div.publication-paper') as $element) {

				$item = array();

                $article_title = trim($element->find('h3', 0)->plaintext);
                $article_uri = self::URI . substr($element->find('a', 0)->href, 1);
//                 $article_thumbnail = array();
                $article_timestamp = strtotime($element->find('span.publication-paper__date', 0)->plaintext);
                $article_author = trim($element->find('span.publication-paper__author', 0)->plaintext);
                $article_content = trim($element->find('div.publication-paper__excerpt', 0)->plaintext);
				$item = array();
				$item['uri'] = $article_uri;
				$item['title'] = $article_title;
				$item['author'] = $article_author;
				$item['timestamp'] = $article_timestamp;
				$item['enclosures'] = array();
				$item['content'] = $article_content;
				$this->items[] = $item;
			}
		}
	}
}
