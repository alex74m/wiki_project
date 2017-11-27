<?php

namespace App\Services;

use \App\Repository\DbRequest;

class Slug
{

	public static function slugify($text)
	{
		$text = preg_replace('~[^\pL\d]+~u', '-', $text);
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		$text = preg_replace('~[^-\w]+~', '', $text);
		$text = trim($text, '-');
		$text = preg_replace('~-+~', '-', $text);
		$text = strtolower($text);
		$text = str_replace(' ', '-', $text);
		return $text;
	}

	public static function createSlug($text)
	{

		$text = self::slugify($text);
		$lastChar = substr($text, -1, 1);
		settype($lastChar, 'integer');

		if (!is_numeric($lastChar)) {
		  	$text = $text.'-'.'1';  
		}else{
			if (substr($text, -2, 2) == ('-'.$lastChar)) {
				$text = rtrim($text, '-'.$lastChar); 
			}
		   	$lastChar = $lastChar + 1;
		  	$text = $text.'-'.$lastChar;  
		}

		return $text;

	}
}