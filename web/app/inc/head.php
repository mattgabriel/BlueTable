<?php

class headClass {
	
	function displayHead($title='Dropsar',$description='',$keywords='',$css='',$js='',$otherIncludes=''){
		
		if($description == ''){ $description = 'Meta description'; }
		
		if($keywords == ''){ $keywords = 'Meta keywords'; }
		
		$title='Dropsar';
		
		return '
			<title>' . $title . '</title>
			<meta name="description" content="' . $description . '">
			<meta name="keywords" content="' . $keywords . '">
			<meta name="Robots" content="INDEX,FOLLOW">
			<link href="/css/reset.css" rel="stylesheet" type="text/css">
			<link href="/css/styles.css" rel="stylesheet" type="text/css">
			' . $css . '
			<script type="text/javascript" src="/js/jquery.min.js"></script>
			<script type="text/javascript" src="/js/common.js"></script>
			
			' . $js . '
			' . $otherIncludes . '
			
			<!--[if IE 9]><link href="/css/ie9.css" rel="stylesheet" type="text/css" /><![endif]-->
			<!--[if IE 8]><link href="/css/ie8.css" rel="stylesheet" type="text/css" /><![endif]-->
			<!--[if IE 7]><link href="/css/ie7.css" rel="stylesheet" type="text/css" /><![endif]-->
			
		';
	}
	
}