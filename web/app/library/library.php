<?php
require_once(APP_PATH.'library/config.php');

class libraryClass {
	public $config;
	
	function __construct(){
		$this->config = new ConfigClass();
	}
	
	function secondsToDaysHoursMinsSecs($inputSeconds){ //returns array
	    $secondsInAMinute = 60;
	    $secondsInAnHour  = 60 * $secondsInAMinute;
	    $secondsInADay    = 24 * $secondsInAnHour;
	
	    // extract days
	    $days = floor($inputSeconds / $secondsInADay);
	
	    // extract hours
	    $hourSeconds = $inputSeconds % $secondsInADay;
	    $hours = floor($hourSeconds / $secondsInAnHour);
	
	    // extract minutes
	    $minuteSeconds = $hourSeconds % $secondsInAnHour;
	    $minutes = floor($minuteSeconds / $secondsInAMinute);
	
	    // extract the remaining seconds
	    $remainingSeconds = $minuteSeconds % $secondsInAMinute;
	    $seconds = ceil($remainingSeconds);
	
	    // return the final array
	    $obj = array(
	        'd' => (int) $days,
	        'h' => (int) $hours,
	        'm' => (int) $minutes,
	        's' => (int) $seconds,
	    );
	    return $obj;
	}

	
	function error($string){
		return '<div class="error"><p>' . $string . '</p></div>';
	}
	
	function outputCleanString($string){
		//user generated data has htmlentities, addslashes... applied to it
		//in order to display it back we need to remove the special chars added
		$string = stripslashes($string);
		$string = html_entity_decode($string);
		$string = nl2br($string);
		return $string;
	}
	
	function menuURL($string){
		$return = str_replace(' ','_',$string);
		$return = str_replace('\'','',$return);
		return strtolower($return);
	}
	
	function menuFriendly($string){
		$return = str_replace('_',' ',$string);
		return $return;
	}
	
	function shortenString($string,$lenght=50){
		return substr($string,0,$lenght);
	}
	
	function shortenStringAddDots($string,$lenght=50){
		if(strlen($string) > $lenght){
			return substr($string,0,$lenght) . '...';
		} else {
			return substr($string,0,$lenght);
		}
	}
	
	function cropPhotoInTwoSizes($name,$uniqueId,$folder,$x2,$x1){ //$x2 and $x1 should be an array withw width and height: (200,200)
		if(!empty($_FILES[$name])) { 
			require_once(APP_PATH.'library/SimpleImage.php');
			$temporaryfile = $_FILES[$name]['tmp_name']; //get the real name of the file
			$uploadedfile = $_FILES[$name]['name']; //get the real name of the file
			$extension = end(explode('.', $uploadedfile)); //get the extension of the file (last item of array)
			
			if($extension == 'jpg' || $extension == 'JPG' || $extension == 'jpeg' || $extension == 'JPEG' || 
				$extension == 'png' || $extension == 'PNG' || $extension == 'gif' || $extension == 'GIF'){
				//it's a valid image
				move_uploaded_file($temporaryfile,$folder.$uploadedfile); //save the uploaded file first and then convert it
				$image = new SimpleImage();
				$image->load($folder.$uploadedfile);
				
				//resize them
				$image->resize($x2[0],$x2[1]);
				$image->save($folder.$uniqueId.'_x2.jpg');
				
				$image->resize($x1[0],$x1[1]);
				$image->save($folder.$uniqueId.'_x1.jpg');
				
				unlink($folder.$uploadedfile); //delete the original file	
				return $uniqueId; //all files saved as jpg		
			} else {
				return 'fileExtensionError';
			}
		} else {
			return 'emptyFileError';
		}
	}
	
	function cropPhotoInTwoSquareSizes($name,$uniqueId,$folder,$x2,$x1){ //$x2 and $x1 should be an int with width: (200)
		if(!empty($_FILES[$name])) { 
			require_once(APP_PATH.'library/SimpleImage.php');
			$temporaryfile = $_FILES[$name]['tmp_name']; //get the real name of the file
			$uploadedfile = $_FILES[$name]['name']; //get the real name of the file
			$extension = end(explode('.', $uploadedfile)); //get the extension of the file (last item of array)
			
			if($extension == 'jpg' || $extension == 'JPG' || $extension == 'jpeg' || $extension == 'JPEG' || 
				$extension == 'png' || $extension == 'PNG' || $extension == 'gif' || $extension == 'GIF'){
				//it's a valid image
				move_uploaded_file($temporaryfile,$folder.$uploadedfile); //save the uploaded file first and then convert it
				$image = new SimpleImage();
				$image->load($folder.$uploadedfile);
				
				//resize them
				$image->square($x2);
				$image->save($folder.$uniqueId.'_x2.jpg');
				
				$image->square($x1);
				$image->save($folder.$uniqueId.'_x1.jpg');
				
				unlink($folder.$uploadedfile); //delete the original file	
				return $uniqueId; //all files saved as jpg		
			} else {
				return 'fileExtensionError';
			}
		} else {
			return 'emptyFileError';
		}
	}
	
	function cropPhotoInOneSize($name,$filename,$folder,$x2){ //$x2 should be an array with width and height: (200,200)
		if(!empty($_FILES[$name])) { 
			require_once(APP_PATH.'library/SimpleImage.php');
			$temporaryfile = $_FILES[$name]['tmp_name']; //get the real name of the file
			$uploadedfile = $_FILES[$name]['name']; //get the real name of the file
			$extension = end(explode('.', $uploadedfile)); //get the extension of the file (last item of array)
			
			if($extension == 'jpg' || $extension == 'JPG' || $extension == 'jpeg' || $extension == 'JPEG' || 
				$extension == 'png' || $extension == 'PNG' || $extension == 'gif' || $extension == 'GIF'){
				//it's a valid image
				move_uploaded_file($temporaryfile,$folder.$uploadedfile); //save the uploaded file first and then convert it
				$image = new SimpleImage();
				$image->load($folder.$uploadedfile);
				
				//resize it
				$image->resize($x2[0],$x2[1]);
				$image->save($folder.$filename.'.jpg');
				
				unlink($folder.$uploadedfile); //delete the original file	
				return $uniqueId; //all files saved as jpg		
			} else {
				return 'fileExtensionError';
			}
		} else {
			return 'emptyFileError';
		}
	}
	
	function resizePhotoInTwoSizes($name,$uniqueId,$folder,$x2,$x1){ //$x2 and $x1 should be the width of both images
		if(!empty($_FILES[$name])) { 
			require_once(APP_PATH.'library/SimpleImage.php');
			$temporaryfile = $_FILES[$name]['tmp_name']; //get the real name of the file
			$uploadedfile = $_FILES[$name]['name']; //get the real name of the file
			$extension = end(explode('.', $uploadedfile)); //get the extension of the file (last item of array)
			
			if($extension == 'jpg' || $extension == 'JPG' || $extension == 'jpeg' || $extension == 'JPEG' || 
				$extension == 'png' || $extension == 'PNG' || $extension == 'gif' || $extension == 'GIF'){
				//it's a valid image
				move_uploaded_file($temporaryfile,$folder.$uploadedfile); //save the uploaded file first and then convert it
				$image = new SimpleImage();
				$image->load($folder.$uploadedfile);
				
				//resize them
				$image->resizeToWidth($x2);
				$image->save($folder.$uniqueId.'_x2.jpg');
				
				$image->resizeToWidth($x1);
				$image->save($folder.$uniqueId.'_x1.jpg');
				
				unlink($folder.$uploadedfile); //delete the original file	
				return $uniqueId; //all files saved as jpg		
			} else {
				return 'fileExtensionError';
			}
		} else {
			return 'emptyFileError';
		}
	}
	
	function saveImage($name,$filename,$folder){ 
		if(!empty($_FILES[$name])) { 
			require_once(APP_PATH.'library/SimpleImage.php');
			$temporaryfile = $_FILES[$name]['tmp_name']; //get the real name of the file
			$uploadedfile = $_FILES[$name]['name']; //get the real name of the file
			$extension = end(explode('.', $uploadedfile)); //get the extension of the file (last item of array)
			
			if($extension == 'jpg' || $extension == 'JPG' || $extension == 'jpeg' || $extension == 'JPEG' || 
				$extension == 'png' || $extension == 'PNG' || $extension == 'gif' || $extension == 'GIF'){
				//it's a valid image
				move_uploaded_file($temporaryfile,$folder.$uploadedfile); //save the uploaded file first and then convert it
				
				$this->removePngTransparency($folder.$uploadedfile); //you can remove this
				
				$image = new SimpleImage();
				$image->load($folder.$uploadedfile);
				
				//save it
				$image->save($folder.$filename.'.jpg');
				
				unlink($folder.$uploadedfile); //delete the original file	
				return $filename; //all files saved as jpg		
			} else {
				return 'fileExtensionError';
			}
		} else {
			return 'emptyFileError';
		}
	}
	
	function removePngTransparency($imagePath){
		// Get the original image.
		$src = imagecreatefrompng($imagePath);
		
		// Get the width and height.
		$width = imagesx($src);
		$height = imagesy($src);
		
		// Create a white background, the same size as the original.
		$bg = imagecreatetruecolor($width, $height);
		$white = imagecolorallocate($bg, 255, 255, 255);
		imagefill($bg, 0, 0, $white);
		
		// Merge the two images.
		imagecopyresampled(
			$bg, $src,
			0, 0, 0, 0,
			$width, $height,
			$width, $height);
		
		// Save the finished image.
		imagepng($bg, $imagePath, 0);
	}
	
	function generatePassword($length = 8){
		// start with a blank password
		$password = "";
		//define posible characters
		$possible = "12346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
		// we refer to the length of $possible a few times, so let's grab it now
		$maxlength = strlen($possible);
		// check for length overflow and truncate if necessary
		if ($length > $maxlength) { $length = $maxlength; }
		// set up a counter for how many characters are in the password so far
		$i = 0; 
		// add random characters to $password until $length is reached
		while($i < $length){ 
		  // pick a random character from the possible ones
		  $char = substr($possible, mt_rand(0, $maxlength-1), 1);
		  // have we already used this character in $password?
		  if (!strstr($password, $char)) { 
			// no, so it's OK to add it onto the end of whatever we've already got...
			$password .= $char;
			// ... and increase the counter by one
			$i++;
		  }
		}
		return $password;
	  }//usage: echo generatePassword(5);  ---- Returns: cDg6w
	  
	  function countriesList(){
		return array("Afghanistan","Albania","Algeria","Andorra","Angola","Antigua and Barbuda","Argentina","Armenia","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bhutan","Bolivia","Bosnia and Herzegovina","Botswana","Brazil","Brunei","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Canada","Cape Verde","Central African Republic","Chad","Chile","China","Colombi","Comoros","Congo (Brazzaville)","Congo","Costa Rica","Cote d'Ivoire","Croatia","Cuba","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","East Timor (Timor Timur)","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Fiji","Finland","France","Gabon","Gambia, The","Georgia","Germany","Ghana","Greece","Grenada","Guatemala","Guinea","Guinea-Bissau","Guyana","Haiti","Honduras","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Israel","Italy","Jamaica","Japan","Jordan","Kazakhstan","Kenya","Kiribati","Korea, North","Korea, South","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Mauritania","Mauritius","Mexico","Micronesia","Moldova","Monaco","Mongolia","Morocco","Mozambique","Myanmar","Namibia","Nauru","Nepa","Netherlands","New Zealand","Nicaragua","Niger","Nigeria","Norway","Oman","Pakistan","Palau","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal","Qatar","Romania","Russia","Rwanda","Saint Kitts and Nevis","Saint Lucia","Saint Vincent","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia and Montenegro","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","Spain","Sri Lanka","Sudan","Suriname","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Togo","Tonga","Trinidad and Tobago","Tunisia","Turkey","Turkmenistan","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States","Uruguay","Uzbekistan","Vanuatu","Vatican City","Venezuela","Vietnam","Yemen","Zambia","Zimbabwe");
	}
	
	function timeAgo($datefrom,$dateto=-1){
		$datefrom = strtotime($datefrom);
		// Defaults and assume if 0 is passed in that
		// its an error rather than the epoch
		
		if($datefrom<=0) { return "A long time ago"; }
		if($dateto==-1) { $dateto = time(); }
		
		// Calculate the difference in seconds betweeen
		// the two timestamps
		
		$difference = $dateto - $datefrom;
		
		// If difference is less than 60 seconds,
		// seconds is a good interval of choice
		
		if($difference < 60){
			$interval = "s";
		}
		
		// If difference is between 60 seconds and
		// 60 minutes, minutes is a good interval
		elseif($difference >= 60 && $difference<60*60){
			$interval = "n";
		}
		
		// If difference is between 1 hour and 24 hours
		// hours is a good interval
		elseif($difference >= 60*60 && $difference<60*60*24){
			$interval = "h";
		}
		
		// If difference is between 1 day and 7 days
		// days is a good interval
		elseif($difference >= 60*60*24 && $difference<60*60*24*7){
			$interval = "d";
		}
		
		// If difference is between 1 week and 30 days
		// weeks is a good interval
		elseif($difference >= 60*60*24*7 && $difference < 60*60*24*30){
			$interval = "ww";
		}
		
		// If difference is between 30 days and 365 days
		// months is a good interval, again, the same thing
		// applies, if the 29th February happens to exist
		// between your 2 dates, the function will return
		// the 'incorrect' value for a day
		elseif($difference >= 60*60*24*30 && $difference < 60*60*24*365){
			$interval = "m";
		}
		
		// If difference is greater than or equal to 365
		// days, return year. This will be incorrect if
		// for example, you call the function on the 28th April
		// 2008 passing in 29th April 2007. It will return
		// 1 year ago when in actual fact (yawn!) not quite
		// a year has gone by
		elseif($difference >= 60*60*24*365){
			$interval = "y";
		}
		
		// Based on the interval, determine the
		// number of units between the two dates
		// From this point on, you would be hard
		// pushed telling the difference between
		// this function and DateDiff. If the $datediff
		// returned is 1, be sure to return the singular
		// of the unit, e.g. 'day' rather 'days'
		
		switch($interval){
		case "m": $months_difference = floor($difference / 60 / 60 / 24 / 29);
		while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto){
			$months_difference++;
		}
		$datediff = $months_difference;
		
		// We need this in here because it is possible
		// to have an 'm' interval and a months
		// difference of 12 because we are using 29 days
		// in a month
		
		if($datediff==12){
			$datediff--;
		}
		
		$res = ($datediff==1) ? "$datediff month ago" : "$datediff months ago";
		break;
		
		case "y":
		$datediff = floor($difference / 60 / 60 / 24 / 365);
		$res = ($datediff==1) ? "$datediff year ago" : "$datediff
		years ago";
		break;
		
		case "d":
		$datediff = floor($difference / 60 / 60 / 24);
		$res = ($datediff==1) ? "$datediff day ago" : "$datediff
		days ago";
		break;
		
		case "ww":
		$datediff = floor($difference / 60 / 60 / 24 / 7);
		$res = ($datediff==1) ? "$datediff week ago" : "$datediff
		weeks ago";
		break;
		
		case "h":
		$datediff = floor($difference / 60 / 60);
		$res = ($datediff==1) ? "$datediff hour ago" : "$datediff
		hours ago";
		break;
		
		case "n":
		$datediff = floor($difference / 60);
		$res = ($datediff==1) ? "$datediff minute ago" :
		"$datediff minutes ago";
		break;
		
		case "s":
		$datediff = $difference;
		$res = ($datediff==1) ? "$datediff second ago" :
		"$datediff seconds ago";
		break;
		}
		return $res;
	} //usage: echo timeAgo(1981-12-20 23:59:59,strtotime(date("Y-m-d H:i:s"))+7200); ---- Returns: 50 minutes ago
	
	
	function sendEmail($from='',$fromName='',$email,$firstName,$subject,$blurb,$templateName='emptyLayout'){
		require_once(APP_PATH.'library/emailTemplate.php');
		$template = new emailTemplate();
		
		if($from == ''){ $from = 'admin@websiteName.com'; }
		if($fromName == ''){ $fromName = 'websiteName'; }
		
		//////////////////////////SEND THE EMAIL
		require_once(APP_PATH.'library/phpmailer.php');
		$mail             = new PHPMailer();
		
		//smpt
		/*$mail->IsSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp.mandrillapp.com';  				  	// Specify main and backup server
		$mail->SMTPAuth = true;                               	// Enable SMTP authentication
		$mail->Username = 'user@domain.com';        			// SMTP username
		$mail->Password = 'password';           					// SMTP password
		$mail->Port = '587';						          		// SMTP port (default is 25)
		$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted*/

		//or normal mail
		$mail->IsSendmail(); // telling the class to use SendMail transport
		
		$mail->From       = $from;
		$mail->FromName   = $fromName;
		$mail->Subject    = $subject;
		$mail->MsgHTML($template->$templateName($blurb));
		$mail->AddAddress($email, $firstName);	
		
		if(!$mail->Send()) {
		  return $message_info = "Mailer Error: " . $mail->ErrorInfo;
		  //return 'Error';
		} else {
		  return $message_info = "Message sent to $email!";
		  //return 'Sent';
		}	
	}

	function safeDivisionByZero($x,$y){
		if($x == 0 || $y == 0){
			return 0;
		} else {
			return $x / $y;
		}
	}

}