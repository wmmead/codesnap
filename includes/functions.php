<?php

require_once('settings.php');


/*********** Database Connection *****************/
function db_connect()
{
	$link = new mysqli(DB_HOST, DB_USER, DB_PWORD, DB_NAME);
	if ($link->connect_errno)
	{
		echo "Failed to connect to MySQL: (" . $link->connect_errno . ") " . $link->connect_error;
	}
	
	return $link;
}

function db_disconnect($link)
{
	if (isset($link))
	{
		mysqli_close($link);
	}
}

/******************* Add Records Functions *************************/

function add_project($link)
{
	if(isset($_POST['mf'])&& $_POST['mf']=='mf')
	{
		$title = safe($link, $_POST['projecttitle']);
		$tags = safe($link, $_POST['tags']);
		
		if( $title != '')
		{
			$query = "insert into projects values('', '1', '$title', NOW(), NOW(), '0', 'temp')";
			mysqli_query($link, $query);
			$lastid = mysqli_insert_id($link);
			
			if($lastid != '0')
			{
				add_project_tags($link, $tags, $lastid);
				add_project_snippets($link, $lastid);
				add_project_file_link($link, $title, $lastid);
				
				/* The function below actually writes the file*/
				write_file($lastid, $title);
				return $lastid;
			}
			else { print "<p class='error'>Error - there was a problem writing to the database.</p>"; return false; }
		}
		else { print "<p class='error'>Error - please provide a title for the project.</p>"; return false;}
	}
}

function add_project_tags($link, $tags, $lastid)
{
	if( $tags != "" )
	{
		$raw_tags = $tags;
		$tags = explode(',', $tags);
		$trimmed_tags = array_map('trim', $tags);
	
		foreach($trimmed_tags as $each_tag)
		{
			$query = "insert into tags values('', '$lastid', '$each_tag')";
			mysqli_query($link, $query);
		}
	}
}

function add_project_snippets($link, $lastid)
{
	$data = pull_data_from_array($_POST, 'intro', 5);
	$num_rows_with_content = $data[0];
	$num_total_rows = $data[1];
	$counter = 1;
	$ordr = 1;
	
	for($i=0; $i<$num_total_rows; $i++)
	{
		$intro = safe($link, $_POST['intro'.$counter]);
		$caption = safe($link, $_POST['caption'.$counter]);
		$lang = safe($link, $_POST['lang'.$counter]);
		$code = safe($link, $_POST['code'.$counter]);
		
		if($code != '')
		{
			$query = "insert into snippets values('', '$lastid', '$ordr', '$intro', '$caption', '$lang', '$code')";
			mysqli_query($link, $query);
			$ordr++;
		}
		
		$counter++;
	}
}

function add_project_file_link($link, $title, $lastid)
{
	$file_link = seoUrl($title) . '-id' . $lastid . '.html';
	
	$query = "update projects set file_link = '$file_link' where id = '$lastid'";
	mysqli_query($link, $query);
}

/******************* File Generation Functions ********************/

function output_codesnippets($handle)
{
	$data = pull_data_from_array($_POST, 'intro', 5);
	$num_rows_with_content = $data[0];
	$num_total_rows = $data[1];
	$counter = 1;

	$piece8 = file_get_contents("includes/pieces/part8.html");
	
	for($i=0; $i<$num_total_rows; $i++)
	{
		//$piece5 = "<div id=\"example" . $counter . "-description\">";
		
		$lang = $_POST['lang'.$counter];
		if($lang == "1")
		{ $language = " lang-css"; }
		else { $language = ""; }
		
		$piece5 = file_get_contents("includes/pieces/part5.html");
		$piece5 = preg_replace("/COUNTER/", $counter, $piece5);
		
		$piece6 = file_get_contents("includes/pieces/part6.html");
		$piece6 = preg_replace("/COUNTER/", $counter, $piece6);
		
		$piece7 = file_get_contents("includes/pieces/part7.html");
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

function write_file($lastid, $title)
{
	$piece = file_get_contents("includes/pieces/part1.html");
	$piece2 = file_get_contents("includes/pieces/part2.html");
	$piece3 = file_get_contents("includes/pieces/part3.html");
	$piece4 = file_get_contents("includes/pieces/part4.html");
	$piece9 = file_get_contents("includes/pieces/part9.html");
	
	$filename = seoURL($title);
	
	$file = FILE_PATH . $filename . '-id' . $lastid . '.html';
	
	if($handle = fopen($file, 'w'))
	{
		
		fwrite($handle, $piece);
		
		fwrite($handle, $title);
		
		fwrite($handle, $piece2);
		
		fwrite($handle, $title);
		
		fwrite($handle, $piece3);
		
		fwrite($handle, "</h4>");
		
		output_codesnippets($handle);
		
		fwrite($handle, $piece9);
		
		fclose($handle);
		//echo "file successfully written";
	}
	else
	{
		//echo "there was an error writing the file";
	}

}

/******************* Read From DB Functions ***********************/

function get_project_details($link, $id)
{
	$query = "
	SELECT
	projects.id,
	projects.title,
	projects.creation_date,
	projects.mod_date,
	projects.file_link,
	users.name
FROM
	projects projects,
	users users
WHERE
	projects.owner_id = users.id AND
	projects.id = $id";
	
	$result = mysqli_query($link, $query);
    $row = mysqli_fetch_row($result);
        
    $field_names = array("id", "title", "creation_date", "mod_date", "file_link", "user_name");
    $details = array_combine($field_names, $row);
    return $details;
}



/******************* Authentication Functions **********************/

function set_code()
{
	if(!isset($_SESSION['validcode']))
	{
		$_SESSION['validcode'] = rand(1000000, 9000000);
		$val = $_SESSION['validcode'];
		return $val;
	}
	else
	{
		$val = $_SESSION['validcode'];
		return $val;
	}
}

function authenticate_user($username, $password, $key, $link)
{
	$code = set_code();
	$query = "select * from users where username='$username' and password = encode('$password', '$key')";
	//print $query;
	$result = mysqli_query($link, $query);
	
	if(mysqli_num_rows($result) > 0)
	{
		$_SESSION['xyz'] = $code;
		
		$row = mysqli_fetch_row($result);
		$_SESSION['id'] = $row[0];
	}
	else
	{
		$_SESSION['xyz'] = "invalid";
	}
}

function session_handler()
{
	session_start(); 
	if (isset($_GET['logoff']))
	{
		session_destroy();
		session_start();
	}
}

function auth_check($link)
{
	$code = set_code();
	if(!isset($_SESSION['xyz']) || $_SESSION['xyz'] != $code)
	{
		if(isset($_POST['username']))
		{
			$username = safe($link, $_POST['username']);
			$password = safe($link, $_POST['password']);
			authenticate_user($username, $password, KEY, $link);
		}
	}	
}

function load_page($link)
{
	$code = set_code();
	if(!isset($_SESSION['xyz']) || $_SESSION['xyz'] != $code)
	{
		include('includes/loginform.php');
		//print $code;
	}
	
	else
	{
		include('includes/pageloader.php');
		//print $code;
	}
}


/******************** Helper Functions *********************/

function jsLoader()
{
	if( isset( $_POST['mf']) && $_POST['mf']=='mf' )
	{
		print '<script type="text/javascript" src="js/iframescript.js"></script>';
	}
	else
	{
		print '<script type="text/javascript" src="js/formscript.js"></script>';
	}
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

function fix_date($the_date)
{
	date_default_timezone_set(TZ);
	$the_date = date('M jS, Y g:i A', strtotime($the_date));
	return $the_date;
}

function safe($link, $value) 
{
	$value = trim($value);
	$value= mysqli_real_escape_string($link, $value);
	return $value;
}


?>