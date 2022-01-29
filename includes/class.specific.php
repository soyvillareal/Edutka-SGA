<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Specific {

	public static function GetPages() {
	    global $dba;
	    $data  = array();
	    $pages = $dba->query('SELECT * FROM page')->fetchAll();
	    foreach ($pages as $value) {
	        $data['page'][$value['type']] = array('html' => htmlspecialchars_decode($value['text']),
	    										  'decor' => self::GetFile($value['decor'], 2),
	    										  'hexco' => $value['hexco'],
	    										  'defoot' => $value['defoot']);
	        $data['active'][$value['type']] = $value['active'];
	    }
	    return $data;
	}

	public static function GetFile($file, $type = 1){
	    global $TEMP;
	    if (empty($file)) {
	        return '';
	    }
	    $theme = '';
	    if($type == 2){
	        $theme = 'themes/'.$TEMP['#settings']['theme'].'/';
	    }
	    return self::Url($theme.$file);
	}

	public static function Admin() {
	    global $TEMP;
	    return $TEMP['#loggedin'] === false ? false : $TEMP['#user']['role'] == 'admin' ? true : false;
	}

	public static function Academic() {
	    global $TEMP;
	    return $TEMP['#loggedin'] === false ? false : $TEMP['#user']['role'] == 'academic' ? true : false;
	}

	public static function Teacher() {
	    global $TEMP;
	    return $TEMP['#loggedin'] === false ? false : $TEMP['#user']['role'] == 'teacher' ? true : false;
	}

	public static function Student() {
	    global $TEMP;
	    return $TEMP['#loggedin'] === false ? false : $TEMP['#user']['role'] == 'student' ? true : false;
	}

	function CompressImage($source_url, $destination_url, $quality) {
	    $info = getimagesize($source_url);
	    if ($info['mime'] == 'image/jpeg') {
	        $image = @imagecreatefromjpeg($source_url);
	        @imagejpeg($image, $destination_url, $quality);
	    } elseif ($info['mime'] == 'image/gif') {
	        $image = @imagecreatefromgif($source_url);
	        @imagegif($image, $destination_url, $quality);
	    } elseif ($info['mime'] == 'image/png') {
	        $image = @imagecreatefrompng($source_url);
	        @imagepng($image, $destination_url);
	    }
	}

	function ResizeImage($max_width, $max_height, $source_file, $dst_dir, $quality = 80) {
	    $imgsize = @getimagesize($source_file);
	    $width   = $imgsize[0];
	    $height  = $imgsize[1];
	    $mime    = $imgsize['mime'];
	    switch ($mime) {
	        case 'image/gif':
	            $image_create = "imagecreatefromgif";
	            $image        = "imagegif";
	            break;
	        case 'image/png':
	            $image_create = "imagecreatefrompng";
	            $image        = "imagepng";
	            break;
	        case 'image/jpeg':
	            $image_create = "imagecreatefromjpeg";
	            $image        = "imagejpeg";
	            break;
	        default:
	            return false;
	            break;
	    }
	    $dst_img    = @imagecreatetruecolor($max_width, $max_height);
	    $src_img    = $image_create($source_file);
	    $width_new  = $height * $max_width / $max_height;
	    $height_new = $width * $max_height / $max_width;
	    if ($width_new > $width) {
	        $h_point = (($height - $height_new) / 2);
	        @imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
	    } else {
	        $w_point = (($width - $width_new) / 2);
	        @imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
	    }
	    @imagejpeg($dst_img, $dst_dir, $quality);
	    if ($dst_img)
	        @imagedestroy($dst_img);
	    if ($src_img)
	        @imagedestroy($src_img);
	}

	public static function CreateDirImage(){
		if (!file_exists('uploads/images/' . date('Y'))) {
	        mkdir('uploads/images/' . date('Y'), 0777, true);
	    }
	    if (!file_exists('uploads/images/' . date('Y') . '/' . date('m'))) {
	        mkdir('uploads/images/' . date('Y') . '/' . date('m'), 0777, true);
	    }
	    return 'uploads/images/' . date('Y') . '/' . date('m');
	}

	public static function UploadImage($data = array()){
	    global $TEMP;
	    $filepath = self::CreateDirImage();
	    if (empty($data)) {
	        return false;
	    }
	    if (isset($data['file']) && !empty($data['file'])) {
	        $data['file'] = $data['file'];
	    }
	    $ext    = pathinfo($data['name'], PATHINFO_EXTENSION);
	    if (!in_array($ext, array('png','jpg','jpeg', 'gif')) || !in_array($data['type'], array('image/png', 'image/jpeg', 'image/gif'))) {
	        return array(
	            'error' => $TEMP['#word']['file_not_supported']
	        );
	    }
	    $file = $filepath . '/' . sha1(time()) . '_' . date('d') . '_' . self::RandomKey() . '_' . $data['from'];
	    $filename    = "$file.$ext";
	    if (move_uploaded_file($data['file'], $filename)) {
	    	@self::CompressImage($filename, $filename, 50);
            @self::ResizeImage($data['crop']['width'], $data['crop']['height'], $filename, $filename, 60);
	        return $filename;
	    }
	}

	public static function UploadPDF($data = array()){
	    global $TEMP;

	    if (!file_exists('uploads/rules/' . $data['id'])) {
	        mkdir('uploads/rules/' . $data['id'], 0777, true);
	    }
	    $filepath = 'uploads/rules/' . $data['id'];

	    if (empty($data)) {
	        return false;
	    }
	    if (isset($data['file']) && !empty($data['file'])) {
	        $data['file'] = $data['file'];
	    }
	    $ext = pathinfo($data['name'], PATHINFO_EXTENSION);
	    if ($ext != 'pdf' || $data['type'] != 'application/pdf') {
	        return array(
	            'error' => $TEMP['#word']['file_not_supported']
	        );
	    }
	    $file = $filepath . '/' . md5(sha1(time()) . '_' . date('d') . '_' . self::RandomKey());
	    $filename = "$file.$ext";
	    if (move_uploaded_file($data['file'], $filename)) {
	        return $filename;
	    } else {
	    	return 'NADA mi pana :(';
	    }
	}


	public static function Settings() {
	    global $dba;
	    $data  = array();
	    $settings = $dba->query('SELECT * FROM setting')->fetchAll();
	    foreach ($settings as $value) {
	        $data[$value['name']] = $value['value'];
	    }
	    return $data;
	}

	public static function Bubbles($data = array()){
	    global $dba;
	    $data = array();
	    $users = $dba->query("SELECT MAX(id) FROM user")->fetchArray();
	    if(!empty($users)){
	    	$count = 10;
		    $data = array();
		    for ($a = 0; $a < $count; $a++) {
		        $rand = rand(1, $users);
		        if (!in_array($rand, $data['rands'])) {
		            $bubble = self::Data($rand);
		            if(!empty($bubble)){
		                $data['avatar'][] = $bubble['avatar'];
		                $data['rands'][] = $rand;
		            } else {
		                $a = $a-1;
		            }
		        }
		    }
	    }
	    return $data;  
	}

	public static function Data($user_id = 0, $type = 1) {
	    global $TEMP, $dba;

	    if($type == 1){
	        $user = $dba->query('SELECT * FROM user WHERE id = "'.$user_id.'"')->fetchArray();
	    } else if($type == 2){
	        $user = $dba->query('SELECT * FROM user WHERE user_id = "'.$user_id.'"')->fetchArray();
	    } else if($type == 3){
	        $session_id = !empty($_SESSION['session_id']) ? $_SESSION['session_id'] : $_COOKIE['session_id'];
	        $user_id = $dba->query('SELECT user_id FROM session WHERE session_id = "'.$session_id.'"')->fetchArray();
	        $user = $dba->query('SELECT * FROM user WHERE id = '.$user_id)->fetchArray();
	    }
	    
	    if (empty($user)) {
	        return false;
	    }
	    $user['full_name'] = $user['names'].' '.$user['surnames'];
	    $date_birthday = explode("-", date('d-m-Y', $user['date_birthday']));

	    $user['birthday'] = $date_birthday[0];
	    $user['birthday_month'] = $date_birthday[1];
	    $user['birthday_year'] = $date_birthday[2];

	    $user['age'] = date("md") < $date_birthday[1].$date_birthday[0] ? date("Y")-$date_birthday[2]-1 : date("Y")-$date_birthday[2];

	    $user['ex_avatar'] = $user['avatar'];
	    
	    $type = 1;
	    if($user['avatar'] == 'images/default-avatar.jpg' || $user['avatar'] == 'images/default-favatar.jpg'){
	        $type = 2;
	    }
	    $user['avatar'] = self::GetFile($user['avatar'], $type);

	    $user['url']    = self::Url('user/' . $user['user_id']);
	    $user['date_time'] = self::DateFormat($user['time']);
	    $user['time'] = self::DateString($user['time']);

	    $user['provinces']  = $TEMP['#provinces'][$user['province']];
	    $user['municipalities']  = $TEMP['#municipalities'][$user['municipality']];
	    $user['program'] = $dba->query('SELECT max(program_id) FROM enrolled WHERE type = "program" AND user_id = '.$user['id'])->fetchArray();

	    $gender = $TEMP['#word']['male'];
	    if($user['gender'] == 2){
	        $gender = $TEMP['#word']['female'];
	    }
	    $user['gender_text'] = $gender;
	    return $user;
	}

	public static function SendEmail($data = array()) {
	    global $TEMP;

	    $mail = new PHPMailer();
	    $subject = self::Filter($data['subject']);
	    if(empty($data['is_html']) || !isset($data['is_html'])){
	    	$data['is_html'] = false;
	    }
	    if ($TEMP['#settings']['server_type'] == 'smtp') {
	        $mail->isSMTP();
	        $mail->Host        = $TEMP['#settings']['smtp_host'];
	        $mail->SMTPAuth    = true;
	        $mail->Username    = $TEMP['#settings']['smtp_username'];
	        $mail->Password    = $TEMP['#settings']['smtp_password'];
	        $mail->SMTPSecure  = $TEMP['#settings']['smtp_encryption'];
	        $mail->Port        = $TEMP['#settings']['smtp_port'];
	        $mail->SMTPOptions = array(
	            'ssl' => array(
	                'verify_peer' => false,
	                'verify_peer_name' => false,
	                'allow_self_signed' => true
	            )
	        );
	    } else {
	        $mail->IsMail();
	    }

	    $content = $data['text_body'];
	    if($data['is_html'] == true){
	    	$TEMP['title'] = $subject;
		    $TEMP['body'] = $content;
		    $content = self::Maket('emails/content');
	    }
	    $mail->IsHTML($data['is_html']);
	    if(!empty($data['reply_to'])){
	    	$mail->addReplyTo($data['reply_to'], $data['from_name']);
	    }
	    $mail->setFrom(self::Filter($data['from_email']), $data['from_name']);
	    $mail->addAddress(self::Filter($data['to_email']), $data['to_name']);
	    $mail->Subject = $subject;
	    $mail->CharSet = $data['charSet'];
	    $mail->MsgHTML($content);
	    if ($mail->send()) {
	        return true;
	    }
	    return false;
	}

	public static function SendNotification($data = array()){
	    global $TEMP, $dba;
	    if (empty($data) || !is_array($data) || $TEMP['#user']['id'] == $data['to_id'] || $dba->query('SELECT COUNT(*) FROM notification WHERE from_id = '.$data['from_id'].' AND to_id = '.$data['to_id'].' AND course_id = '.$data['course_id'].' AND type = '.$data['type'])->fetchArray() > 0) {
	        return false;
	    }

	    $to_user = self::Data($data['to_id']);
	    $from_user = $dba->query('SELECT names FROM user WHERE id = '.$data['from_id'])->fetchArray();
	    $type = str_replace("'", "", $data['type']);
	    $course_id = str_replace("'", "", $data['course_id']);
	    $course = $dba->query('SELECT name FROM course WHERE id = '.$course_id)->fetchArray();
	    $notifycon = $TEMP['#notifycon'][$type];

		$TEMP['name'] = $to_user['names'];
		$text = strtolower($notifycon['text']);
	    $TEMP['text'] = "<b>$from_user</b> $text: <b>$course</b>";
	    $TEMP['url'] = $notifycon['url'];
	    $TEMP['footer'] = $TEMP['#word']['just_ignore_this_message'];
	    self::SendEmail(array(
	        'from_email' => $TEMP['#settings']['smtp_username'],
	        'from_name' => $TEMP['#settings']['title'],
	        'to_email' => $to_user['email'],
	        'to_name' => $to_user['full_name'],
	        'subject' => str_replace("#REPLACE#", $TEMP['#settings']['title'], $notifycon['title']),
	        'charSet' => 'UTF-8',
	        'text_body' => self::Maket('emails/includes/notification'),
	        'is_html' => true
	    ));

	    return $dba->query('INSERT INTO notification ('.implode(',', array_keys($data)).') VALUES ('.implode(',', array_values($data)).')')->returnStatus();
	}

	public static function Url($params = '') {
	    global $site_url;
	    return $site_url . '/' . $params;
	}

	public static function ReturnUrl() {
		$params = "";
		if(!empty($_SERVER["REQUEST_URI"])){
			$url = (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on' ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
			$params = "login?return=".urlencode($url);
		}
		return self::Url($params);
	}

	public static function ValidateDates($id, $pos, $validate = 0){
		global $dba;

		$rdate = false;
		$dates = $dba->query('SELECT dates FROM period WHERE id = '.$id)->fetchArray();
		if(!empty($dates)){
        	$data = json_decode($dates, true)[$pos];
        	if($validate == 0){
        		if(!empty($data)){
	        		$rdate = time() > strtotime($data);
        		}
	        } else {
	        	$rdate = time() > strtotime($data) || empty($data);
	        	if($validate == 1){
	        		$rdate = strtotime($data) > time() || empty($data);
	        	}
	        }
		}

        return $rdate;
	}

	public static function ComposeDates($date = array()) {
		global $TEMP;
		
		if(empty($date)){
			$date = ["", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", ""];
		}
		
		$date = json_decode($date, true);
		$dates = array();
        $dates[0] = $date[0];
        $dates[1] = array($date[1], $date[2]);
        $dates[2] = array($date[3], $date[4]);
        $dates[3] = $date[5];
        $dates[4] = $date[6];
        $dates[5] = $date[7];
        $dates[6] = array($date[8], $date[9]);
        $dates[7] = $date[10];
        $dates[8] = array($date[11], $date[12]);
        $dates[9] = $date[13];
        $dates[10] = $date[14];
        $dates[11] = $date[15];
        $dates[12] = $date[16];
        $dates[13] = $date[17];

        foreach ($dates as $key => $value) {
            if (is_array($value)){
                foreach ($value as $k => $val) {
                    if(empty($val)) {
                        $dates[$key][$k] = $TEMP['#word']['undefined'];
                    } else {
                        $val = strtotime($val);
                        $dates[$key][$k] = self::DateFormat($val, true);
                    }
                }
            } else {
                if(empty($value)) {
                    $dates[$key] = $TEMP['#word']['undefined'];
                } else {
                    $value = strtotime($value);
                    $dates[$key] = self::DateFormat($value, true);
                }
            }
        }

        return $dates;
	}

	public static function GetSessions($value = array()){
	    $data = array();
	    $data['ip'] = 'Unknown';
	    $data['browser'] = 'Unknown';
	    $data['platform'] = 'Unknown';
	    if (!empty($value['details'])) {
	        $session = json_decode($value['details'], true);
	        $data['ip'] = $session['ip'];
	        $data['browser'] = $session['name'];
	        $data['platform'] = ucfirst($session['platform']);
	    }
	    return $data;
	}

	public static function RandomKey($minlength = 12, $maxlength = 20) {
		$length = mt_rand($minlength, $maxlength);
	    return substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"), 0, $length);
	}

	public static function TokenSession() {
	    $token = md5(self::RandomKey(60, 70));
	    if (!empty($_SESSION['session_id'])) {
	        return $_SESSION['session_id'];
	    }
	    $_SESSION['session_id'] = $token;
	    return $token;
	}

	public static function DateString($time, $is_string = true) {
	    global $TEMP;
	    if($is_string == true){
	        $diff = time() - $time;
	        if ($diff < 1) {
	            return $TEMP['#word']['now'];
	        }
	    } else {
	        $diff = $time - time();
	    }
	    $dates = array(
	        31536000 => array($TEMP['#word']['year'], $TEMP['#word']['years']),
	        2592000 => array($TEMP['#word']['month'], $TEMP['#word']['months']),
	        86400 => array($TEMP['#word']['day'], $TEMP['#word']['days']),
	        3600 => array($TEMP['#word']['hour'], $TEMP['#word']['hours']),
	        60 => array($TEMP['#word']['minute'], $TEMP['#word']['minutes']),
	        1 => array($TEMP['#word']['second'], $TEMP['#word']['seconds'])
	    );
	    foreach ($dates as $key => $value) {
	        $was = $diff/$key;
	        if ($was >= 1) {
	            $was_int = intval($was);
	            $string = $was_int > 1 ? $value[1] : $value[0];
	            return in_array($TEMP['#language'], array('es')) ? "{$TEMP['#word']['does']} $was_int $string" : "$was_int $string {$TEMP['#word']['does']}";
	        }
	    }
	}

	public static function DateFormat($ptime, $complete = false) {
	    global $TEMP; 
	    $date = date("j-m-Y", $ptime); 
	    $month = strtolower(strftime("%B", strtotime($date))); 
	    $month = $TEMP['#word'][$month];
	    $B = mb_substr($month, 0, 3, 'UTF-8');
	    $dateFinaly = strftime("%e " . $B . ". %Y", strtotime($date));
	    if($complete == true){
	    	$dateFinaly = strftime("%e {$TEMP['#word']['of']} " . $month . " {$TEMP['#word']['of']} %Y", strtotime($date));
	    }
	    return $dateFinaly;
	}

	public static function Words($language = 'en', $type = 0, $paginate = false, $page = 1, $keyword = ''){
	    global $TEMP, $dba;
	    $data   = array();
	    $query = '';
	    if(!empty($keyword)){
	        $query = " WHERE word LIKE '%$keyword%' OR `$language` LIKE '%$keyword%'";
	    }
	    if($paginate == true){
	        $data['sql'] = $dba->query('SELECT * FROM word'.$query.' LIMIT ? OFFSET ?', $TEMP['#settings']['data_load_limit'], $page)->fetchAll();
	        $data['total_pages'] = $dba->totalPages;
	    } else {
	        $sql = $dba->query('SELECT word,'.$language.' FROM word')->fetchAll();
	    }
	    if($type == 0){
	        foreach ($sql as $value) {
	            $data[$value['word']] = $value[$language];
	        }
	    }
	    return $data;
	}

	public static function Languages($query = 'type') {
	    global $dba;
	    $data = array();
	    $langs = $dba->query("DESCRIBE word")->fetchAll();
	    foreach ($langs as $lang) {
	        $data[] = $lang['Field'];
	    }
	    unset($data[0]);
	    return $data;
	}

	public static function Language($lang = 'en'){
		global $TEMP, $dba;
		if ($TEMP['#loggedin'] == true) {
		    if (!empty($TEMP['#user']['language']) && in_array($TEMP['#user']['language'], $TEMP['#languages'])) {
		        $language = $TEMP['#user']['language'];
		    }
		}
		if (isset($lang) && !empty($lang)) {
		    $lang = strtolower($lang);
		    if (in_array($lang, $TEMP['#languages'])) {
		        $language = $_SESSION['language'] = $lang;
		        if ($TEMP['#loggedin'] == true) {
		            $dba->query('UPDATE user SET language = "'.$lang.'" WHERE id = '.$TEMP['#user']['id']);
		        }
		    }
		}
		if (empty($language)) {
		    $language = $TEMP['#settings']['language'];
		}
		return $language;
	}

	public static function GetClientIp() {
	    foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'] as $value) {
	        if (array_key_exists($value, $_SERVER) ) {
	            foreach (array_map('trim', explode(',', $_SERVER[$value])) as $ip) {
	                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE || FILTER_FLAG_NO_RES_RANGE) !== false) {
	                    return $ip;
	                }
	            }
	        }
	    }
	    return '?';
	}

	public static function IsOwner($user_id) {
	    global $TEMP;
	    if ($TEMP['#loggedin'] === true) {
	        if ($TEMP['#user']['id'] == $user_id) {
	            return true;
	        }
	    }
	    return false;
	}

	public static function BrowserDetails() {
	    $u_agent = $_SERVER['HTTP_USER_AGENT'];
	    $is_mobile = false;
	    $bname = 'Unknown';
	    $platform = 'Unknown';
	    $version = "";

	    // Is mobile platform?
	    if (preg_match("/(android|Android|ipad|iphone|IPhone|ipod)/i", $u_agent)) {
	        $is_mobile = true;
	    }

	    // First get the platform?
	    // First get the platform?
		if (preg_match('/linux/i', $u_agent)) {
		    $platform = 'Linux';
		} elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
		    $platform = 'Mac';
		} elseif (preg_match('/windows|win32/i', $u_agent)) {
		    $platform = 'Windows';
		} else if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', $u_agent)){
			$platform = 'Mobile';
		} else if(preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', $u_agent)){
			$platform = 'Tablet';
		}


	    // Next get the name of the useragent yes seperately and for good reason
	    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) {
	        $bname = 'Internet Explorer';
	        $ub = "MSIE";
	    } elseif(preg_match('/Firefox/i',$u_agent)) {
	        $bname = 'Mozilla Firefox';
	        $ub = "Firefox";
	    } elseif(preg_match('/Chrome/i',$u_agent)) {
	        $bname = 'Google Chrome';
	        $ub = "Chrome";
	    } elseif(preg_match('/Safari/i',$u_agent)) {
	        $bname = 'Apple Safari';
	        $ub = "Safari";
	    } elseif(preg_match('/Opera/i',$u_agent)) {
	        $bname = 'Opera';
	        $ub = "Opera";
	    } elseif(preg_match('/Netscape/i',$u_agent)) {
	        $bname = 'Netscape';
	        $ub = "Netscape";
	    }

	    // finally get the correct version number
	    $known = array('Version', $ub, 'other');
	    $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
	    if (!preg_match_all($pattern, $u_agent, $matches)) {
	        // we have no matching number just continue
	    }
	    // see how many we have
	    $i = count($matches['browser']);
	    if ($i != 1) {
	        //we will have two since we are not using 'other' argument yet
	        //see if version is before or after the name
	        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
	            $version= $matches['version'][0];
	        } else {
	            $version= $matches['version'][1];
	        }
	    } else {
	        $version= $matches['version'][0];
	    }

	    // check if we have a number
	    if ($version == null || $version == "") {
	        $version="?";
	    }
	    return array(
	        'validate' => array(
	            'is_mobile' => $is_mobile
	        ),
	        'details' => array(
	            'ip' => self::GetClientIp(),
	            'userAgent' => $u_agent,
	            'name' => $bname,
	            'version' => $version,
	            'platform'  => $platform,
	            'pattern' => $pattern
	        )
	    );
	}

	public static function CheckRecaptcha($token){
		global $TEMP;
		 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => $TEMP['#settings']['recaptcha_private_key'], 'response' => self::Filter($token))));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);

		return json_decode($response, true);
	}

	public static function GetComposeRule($rules, $html = false){
		global $TEMP;
	    if($html == false){
	    	$rules = str_replace('<br>', "\n", $rules);
	    	$rules = str_replace('<br />', "\n", $rules);
	    } else {
	    	$rules = htmlspecialchars_decode($rules);
	    	if (preg_match_all('/{\#(.+?)->(.+?)}/i', $rules, $rls)) {
	            foreach ($rls[0] as $key => $rl) {
	            	if(in_array($rls[1][$key], $TEMP['#rules'])){
	            		$rules = str_replace($rl, $rls[2][$key], $rules);
	            	}
	            }
	        }
	    	$rules = preg_replace('/\=\=\=(.+?)\=\=\=/i', "$1", $rules);
	    	$rules = preg_replace('/{\>\>(.+?)\<\<}/i', '$1', $rules);
	    }
	    return $rules;
	}

	public static function DestroyMaket(){
	    global $TEMP;
	    unset($TEMP['!data']);
	    foreach ($TEMP as $key => $value) {
	        if(substr($key, 0, 1) === '!'){
	            unset($TEMP[$key]);
	        }
	    }
	    return $TEMP;
	}

	public static function Maket($page){
	    global $TEMP;
	    $file = "./themes/".$TEMP['#settings']['theme']."/html/$page.html";
	    if(!file_exists($file)){
	    	exit("No found: $file");
	    }
	    ob_start();
	    require($file);
	    $html = ob_get_contents();
	    ob_end_clean();

	    $page = preg_replace_callback('/{\$word->(.+?)}/i', function($matches) use ($TEMP) {
	        return (isset($TEMP['#word'][$matches[1]])?addslashes($TEMP['#word'][$matches[1]]):"");
	    }, $html);
	    $page = preg_replace_callback('/{\$settings->(.+?)}/i', function($matches) use ($TEMP) {
	        return (isset($TEMP['#settings'][$matches[1]])?$TEMP['#settings'][$matches[1]]:"");
	    }, $page);
	    $page = preg_replace_callback('/{\$theme->\{(.+?)\}}/i', function($matches) use ($TEMP) {
	        return self::Url("themes/".$TEMP['#settings']['theme']."/".$matches[1]);
	    }, $page);
	    $page = preg_replace_callback('/{\$url->\{(.+?)\}}/i', function($matches) use ($TEMP) {
	        return self::Url($matches[1]!="home"?$matches[1]:"");
	    }, $page);
	    $page = preg_replace_callback('/{\$data->(.+?)}/i', function($matches) use ($TEMP) {
	        return (isset($TEMP['data'][$matches[1]])?$TEMP['data'][$matches[1]]:"");
	    }, $page);
	    $page = preg_replace_callback('/{\#([a-zA-Z0-9_]+)}/i', function($matches) use ($TEMP) {
	        $match = $TEMP["#".$matches[1]];
	        if(is_bool($match)){
	        	$match = json_encode($match);
	        }
	        return (isset($match)?$match:"");
	    }, $page);
	    $page = preg_replace_callback('/{\$([a-zA-Z0-9_]+)}/i', function($matches) use ($TEMP) {
	    	$match = $TEMP[$matches[1]];
	    	if(is_bool($match)){
	        	$match = json_encode($match);
	        }
	        return (isset($TEMP[$matches[1]])?$match:"");
	    }, $page);

	    if ($TEMP['#loggedin'] === true) {
	        $page = preg_replace_callback('/{\$me->(.+?)}/i', function($matches) use ($TEMP) {
	            return (isset($TEMP['#user'][$matches[1]])) ? $TEMP['#user'][$matches[1]] : '';
	        }, $page);
	    }
	    $page = preg_replace_callback('/{\!data->(.+?)}/i', function($matches) use ($TEMP) {
	        $match = $TEMP['!data'][$matches[1]];
	        return (isset($match)?$match:"");
	    }, $page);
	    $page = preg_replace_callback('/{\!([a-zA-Z0-9_]+)}/i', function($matches) use ($TEMP) {
	        $match = $TEMP["!".$matches[1]];
	    	if(is_bool($match)){
	        	$match = json_encode($match);
	        }
	        return (isset($match)?$match:"");
	    }, $page);
	    return $page;
	}

	public static function Logged() {
		global $dba;
	    if (isset($_SESSION['session_id']) && !empty($_SESSION['session_id'])) {
	        if ($dba->query('SELECT COUNT(*) FROM session WHERE session_id = "'.self::Filter($_SESSION['session_id']).'"')->fetchArray() > 0) {
	            return true;
	        }
	    } else if (isset($_COOKIE['session_id']) && !empty($_COOKIE['session_id'])) {
	        if ($dba->query('SELECT COUNT(*) FROM session WHERE session_id = "'.self::Filter($_COOKIE['session_id']).'"')->fetchArray() > 0) {
	            return true;
	        }
	    }
	    return false;
	}

	public static function Filter($input){
	    global $dba;
	    if(!empty($input)){
	    	$input = mysqli_real_escape_string($dba->returnConnection(), $input);
		    $input = htmlspecialchars($input, ENT_QUOTES);
		    $input = str_replace('\r\n', " <br>", $input);
		    $input = str_replace('\n\r', " <br>", $input);
		    $input = str_replace('\r', " <br>", $input);
		    $input = str_replace('\n', " <br>", $input);
		    $input = stripslashes($input);
	    }
	    return $input;
	}
}
?>