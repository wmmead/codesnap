<?php

require_once('settings.php');

function seoUrl($string) {
    //Lower case everything
    $string = strtolower($string);
    //Make alphanumeric (removes all other characters)
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    //Clean up multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", " ", $string);
    //Convert whitespaces and underscore to dash
    $string = preg_replace("/[\s_]/", "-", $string);
    return $string;
}

function fix_code($string)
{
	$string = str_replace("&", "&amp;", $string);
	$string = str_replace("<", "&lt;", $string);
	$string = str_replace(">", "&gt;", $string);
	return $string;
}

function pull_data_from_array($data, $item, $itemlength)
{
	$counter = 0;
	$fieldnum = 0;
	foreach($data as $key => $value)
	{
		$test = substr($key, 0, $itemlength);
		if($test == $item)
		{
			$fieldnum++;
			$field = $item.$fieldnum;
			
			if($_POST[$field] != '')
			{
				$counter++;
			}
		}	
	}
	
	$data = array($counter, $fieldnum);
	return $data;
}

function output_codesnippets($handle)
{
	$data = pull_data_from_array($_POST, 'intro', 5);
	$num_rows_with_content = $data[0];
	$num_total_rows = $data[1];
	$counter = 1;

	$piece8 = file_get_contents("pieces/part8.html");
	
	for($i=0; $i<$num_total_rows; $i++)
	{
		//$piece5 = "<div id=\"example" . $counter . "-description\">";
		
		$lang = $_POST['lang'.$counter];
		if($lang == "css")
		{ $language = " lang-css"; }
		else { $language = ""; }
		
		$piece5 = file_get_contents("pieces/part5.html");
		$piece5 = preg_replace("/COUNTER/", $counter, $piece5);
		
		$piece6 = file_get_contents("pieces/part6.html");
		$piece6 = preg_replace("/COUNTER/", $counter, $piece6);
		
		$piece7 = file_get_contents("pieces/part7.html");
		$piece7 = preg_replace("/COUNTER/", $counter, $piece7);
		$piece7 = preg_replace("/LANGUAGE/", $language, $piece7);
		
		fwrite($handle, $piece5);
		$intro = $_POST['intro'.$counter];
		$intro = fix_code($intro);
		fwrite($handle, $intro);
		
		fwrite($handle, $piece6);
		$caption = $_POST['caption'.$counter];
		$caption = fix_code($caption);
		fwrite($handle, $caption);
		
		fwrite($handle, $piece7);
		$code = $_POST['code'.$counter];
		$code = fix_code($code);
		fwrite($handle, $code);
		
		fwrite($handle, $piece8);
		$counter++;
	}
}

if(isset($_POST['mf']))
{
	$piece = file_get_contents("pieces/part1.html");
	$piece2 = file_get_contents("pieces/part2.html");
	$piece3 = file_get_contents("pieces/part3.html");
	$piece4 = file_get_contents("pieces/part4.html");
	$piece9 = file_get_contents("pieces/part9.html");
	
	$pagetitle = $_POST['projecttitle'];
	
	$filename = seoURL($pagetitle);
	
	
	$file = FILE_PATH . $filename . ".html";
	
	if($handle = fopen($file, 'w'))
	{
		
		fwrite($handle, $piece);
		
		fwrite($handle, $pagetitle);
		
		fwrite($handle, $piece2);
		
		fwrite($handle, $pagetitle);
		
		fwrite($handle, $piece3);
		
		fwrite($handle, "</h4>");
		
		output_codesnippets($handle);
		
		fwrite($handle, $piece9);
		
		fclose($handle);
		echo "file successfully written";
	}
	else
	{
		echo "there was an error writing the file";
	}

}

?>