<?php
use Gregwar\Image\Image;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Specific {

	public static function GetPages() {
	    global $dba;
	    $data  = array();
	    $pages = $dba->query('SELECT * FROM pages')->fetchAll();
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

	public static function Academic() {
	    global $TEMP;
	    return $TEMP['#loggedin'] === false ? false : $TEMP['#user']['role'] == 2 ? true : false;
	}

	public static function Teacher() {
	    global $TEMP;
	    return $TEMP['#loggedin'] === false ? false : $TEMP['#user']['role'] == 1 ? true : false;
	}

	public static function Student() {
	    global $TEMP;
	    return $TEMP['#loggedin'] === false ? false : $TEMP['#user']['role'] == 0 ? true : false;
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
	        if($data['from'] == 'cover'){
	            $full_cover = $file.'_full.'.$ext;
	            Image::open($filename)->save($full_cover);
	        }
	        if (!empty($data['crop'])) {
	            Image::open($filename)->zoomCrop($data['crop']['width'], $data['crop']['height'])->save($filename, $ext, 60);
	        }
	        Image::open($filename)->save($filename, $ext, 80);
	        return $filename;
	    }
	}

	public static function Settings() {
	    global $dba;
	    $data  = array();
	    $settings = $dba->query('SELECT * FROM settings')->fetchAll();
	    foreach ($settings as $value) {
	        $data[$value['name']] = $value['value'];
	    }
	    return $data;
	}

	public static function Bubbles($data = array()){
	    global $dba;
	    $data = array();
	    $users = $dba->query("SELECT MAX(id) FROM users")->fetchArray();
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

	public static function Data($by_id = 0, $type = 1) {
	    global $TEMP, $dba;

	    if($type == 1){
	        $user = $dba->query('SELECT * FROM users WHERE id = "'.$by_id.'"')->fetchArray();
	    } else if($type == 2){
	        $user = $dba->query('SELECT * FROM users WHERE user_id = "'.$by_id.'"')->fetchArray();
	    } else if($type == 3){
	        $session_id = !empty($_SESSION['session_id']) ? $_SESSION['session_id'] : $_COOKIE['session_id'];
	        $by_id = $dba->query('SELECT by_id FROM sessions WHERE session_id = "'.$session_id.'"')->fetchArray();
	        $user = $dba->query('SELECT * FROM users WHERE id = '.$by_id)->fetchArray();
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


	    $user['program'] = $dba->query('SELECT max(id) FROM enrolled e WHERE user_id = '.$user['id'].' AND (SELECT id FROM programs WHERE id = e.program_id) = id LIMIT 1')->fetchArray();

	    $user['period'] = $dba->query('SELECT max(id) FROM periods p WHERE (SELECT period_id FROM enrolled WHERE user_id = '.$user['id'].' AND period_id = p.id) = id LIMIT 1')->fetchArray();

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

	    $content = $data['message_body'];
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

	public static function Url($params = '') {
	    global $site_url;
	    return $site_url . '/' . $params;
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

	public static function DateFormat($ptime) {
	    global $TEMP; 
	    $date = date("j-m-Y", $ptime); 
	    $month = strtolower(strftime("%B", strtotime($date))); 
	    $month = $TEMP['#word'][$month];
	    $B = mb_substr($month, 0, 3, 'UTF-8');     
	    $dateFinaly = strftime("%e " . $B . ". %Y", strtotime($date));
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
	        $data['sql'] = $dba->query('SELECT * FROM words'.$query.' LIMIT ? OFFSET ?', $TEMP['#settings']['data_load_limit'], $page)->fetchAll();
	        $data['total_pages'] = $dba->totalPages;
	    } else {
	        $sql = $dba->query('SELECT word,'.$language.' FROM words')->fetchAll();
	    }
	    if($type == 0){
	        foreach ($sql as $value) {
	            $data[$value['word']] = $value[$language];
	        }
	    }
	    return $data;
	}

	public static function Language($lang = 'en'){
		global $TEMP, $dba;
		if (isset($lang) && !empty($lang)) {
		    $lang = strtolower($lang);
		    if (in_array($lang, array('es', 'en'))) {
		        $language = $lang;
		    }
		}
		if(empty($language)){
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

	public static function IsOwner($by_id) {
	    global $TEMP;
	    if ($TEMP['#loggedin'] === true) {
	        if ($TEMP['#user']['id'] == $by_id) {
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
	        if ($dba->query('SELECT COUNT(*) FROM sessions WHERE session_id = "'.self::Filter($_SESSION['session_id']).'"')->fetchArray() > 0) {
	            return true;
	        }
	    } else if (isset($_COOKIE['session_id']) && !empty($_COOKIE['session_id'])) {
	        if ($dba->query('SELECT COUNT(*) FROM sessions WHERE session_id = "'.self::Filter($_COOKIE['session_id']).'"')->fetchArray() > 0) {
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

	public static function Sitemap($background = false){
		global $dba, $TEMP;
		$dbaLimit = 45000;
		$videos = $dba->query('SELECT COUNT(*) FROM videos WHERE privacy = 0 AND approved = 1 AND deleted = 0')->fetchArray();
		if(empty($videos)){
			return false;
		}
		$time = time();
		if($background == true){
			self::PostCreate(array(
				'status' => 200,
                'message' => $TEMP['#word']['sitemap_being_generated_may_take_few_minutes'],
                'time' => self::DateFormat($time)
			));
		}
		$limit = ceil($videos / $dbaLimit);
		$sitemap_x = '<?xml version="1.0" encoding="UTF-8"?>
		                <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		$sitemap_index = '<?xml version="1.0" encoding="UTF-8"?>
		                    <sitemapindex  xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" >';
		for ($i=1; $i <= $limit; $i++) {            
		  $sitemap_index .= "\n<sitemap>
		                          <loc>" . self::Url("sitemaps/sitemap-$i.xml") . "</loc>
		                          <lastmod>" . date('c') . "</lastmod>
		                        </sitemap>";
		  $paginate = $dba->query('SELECT * FROM videos WHERE privacy = 0 AND approved = 1 AND deleted = 0 ORDER BY id ASC LIMIT ? OFFSET ?', $dbaLimit, $i)->fetchAll();
		  foreach ($paginate as $value) {
		    $video = self::Video($value);
		    $sitemap_x .= '<url>
		                    <loc>' . $video['url'] . '</loc>
		                    <lastmod>' . date('c', $video['time']). '</lastmod>
		                    <changefreq>monthly</changefreq>
		                    <priority>0.8</priority>
		                  </url>' . "\n";
		  }
		  $sitemap_x .= "\n</urlset>";
		  file_put_contents("sitemaps/sitemap-$i.xml", $sitemap_x);
		  $sitemap_x = '<?xml version="1.0" encoding="UTF-8"?>
		                  <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'; 
		}
		$sitemap_index .= '</sitemapindex>';
		$file_final = file_put_contents('sitemap-index.xml', $sitemap_index);
		$dba->query('UPDATE settings SET value = "'.$time.'" WHERE name = "last_sitemap"');
		return true;
	}
}
?>