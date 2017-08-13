<?php
class Controller {
    
    public function __construct(){
        $this->view = new View();
        @session_start();
        $this->changeLang();
        if(!isset($_SESSION['dlang']))
            $_SESSION['dlang'] = DEFAULT_LANG; // default language in /config/cons.php
        if(isset($_SESSION['dlang']))
            $this->dlang = $this->setLang($_SESSION['dlang']);
        else{
            $this->dlang = $this->setLang(DEFAULT_LANG);
        }
    }

    public function __call($method, $args)
    {
        if (isset($this->$method)) {
            $func = $this->$method;
            return call_user_func_array($func, $args);
        }
    }
    
    // يمكن استخدام هذه الدالة في حال كان الباراميتر الاول الممرر
    // للدالة يساوي ( لا شيئ )
    // وهذا يؤدي الى تحويله للصفحة السابقة بدون استخدام الباراميتر
    public function back($arg){        
        if($arg[0] == null){
            header("Location: /".$url[0]."/".$url[1]);
        }
    }
    public function loadModel($name){
        $path = "models/" . strtolower($name) . "_model.php";
        
        if(file_exists($path)){
            require_once($path);
            $modelName = ucfirst($name) . "_Model";
            $this->model = new $modelName();
        }
    }
    /* هذه الدالة تستخدم في حال ما اذا خالف المتغير الشروط المحدد
     * يحول الزائر الي ال URL الموجود في ملف config/pathes.php
     */
    public function withRule($str,$rule){
        
        if(!preg_match($rule,$str)){
            return false;
        }else{
            return true;
        }
    }
    public function redirect($to){
        header("Location: " . $to);
    }

    public function protect($var){
        $var = trim($var);
        if(get_magic_quotes_gpc()){
            return htmlentities(addslashes(stripslashes($var)));
        }else{
            return htmlentities(addslashes($var));
        }
    }
    /* speadly function to do CURL request (post and get) */
    public function curl_do_post($url,$data,$cf,$host,$h = false){
        // curl initializing
        $ch = curl_init();
        // http header request
        if($h === false)
            $h = [
                'Host:' . $host,
                'User-Agent:Mozilla/5.0 (X11; Linux x86_64; rv:"45.0) Gecko/20100101 Firefox/45.0"',
                'Accept:"text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8"',
                'Accept-Language:"en-US,en;q=0.5"',
                'Accept-Encoding:"gzip, deflate"',
                'Connection:"keep-alive"'
            ];
        
        // set options of curl
        curl_setopt_array($ch,array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_COOKIEJAR => $cf, // cookie file
            CURLOPT_COOKIEFILE => $cf,
            CURLOPT_HTTPHEADER => $h,
            CURLOPT_USERAGENT => $h[1],
            CURLOPT_POST => true,
            CURLOPT_URL => $url,
            CURLOPT_POSTFIELDS => $data
        ));
        // prevent any out put
        ob_start(); 
        $output = curl_exec($ch);
        ob_end_clean();
        // close curl proc
        curl_close($ch);
        unset($ch);
        // return the out put of request response
        return $output;
    }
    public function curl_do_get($url,$cf = false){
        // curl initializing
        $ch = curl_init();
        // if using cookie
        if($cf != false)
            curl_setopt($ch,CURLOPT_COOKIEFILE,$cf);
        
        // set options of curl
        curl_setopt_array($ch,array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'User-Agent:Mozilla/5.0 (X11; Linux x86_64; rv:45.0) Gecko/20100101 Firefox/45.0',
            CURLOPT_URL => $url
        ));
        
        $output = curl_exec($ch);
        curl_close($ch);
        unset($ch);
        // return the out put of request response
        return $output; 
    }
    
    // this will reduce the quality of image
    // EX: $new_tmp_img = reduce_img_quality($img_tmp, 40);
    public function reduce_img_quality($img_tmp , $quality) {
        $source = $img_tmp;
        $destination = $img_tmp;
        $info = getimagesize($source);
        if ($info['mime'] == 'image/jpeg') 
            $image = imagecreatefromjpeg($source);
        elseif ($info['mime'] == 'image/gif') 
            $image = imagecreatefromgif($source);
        elseif ($info['mime'] == 'image/png') 
            $image = imagecreatefrompng($source);
        imagejpeg($image, $destination, $quality);
        return $destination;
    }
    
    // get file will uploaded info
    // $file = $this->getFile('the_image_input');
    // then image name = $file->fileName;
    public function getFile($input_name){
        $file = $_FILES[$input_name];
        // file name
        $this->fileName = $file['name'];
        // file tmp name
        $this->fileTmp = $file['tmp_name'];
        // file size
        $this->fileSize = $file['size'];
        // file type
        $this->fileType = $file['type'];
        // errors
        $this->fileError = $file['error'];
        return $this;
    }
    
    // create new directory
    public function newDir($dir, $mode = 0777){
        if(!file_exists($dir)){
            $old_mask = umask(0);
            mkdir($dir, $mode, true);
            umask($old_mask);
        }
    }
    // change lang https://URL.COM/section&dlang=en
    public function changeLang(){
        if(isset($_GET['dlang'])){
            $lang = $_GET['dlang'];
            $true_lang = $this->withRule($lang, '/^[a-z]{2}$/');
            $lang_file = 'lang/' . $lang . '.lang.php';
            if( $true_lang && file_exists($lang_file)) {
                $_SESSION['dlang'] = $lang;
                // $this->redirect(URL . '/' . $_GET['route']);
            }
            
        }
    }

    // Setting up language
    public function setLang($lang = 'en'){
        $lang_file = 'lang/' . $lang . '.lang.php';
        if(file_exists($lang_file))
            require_once($lang_file);
        else
            require_once('lang/en.lang.php');
        $this->dlang = new Lang();
        return $this->dlang;
    }
    // Google ReCaptch
    // Use it $status = $this->googleRecaptcha
    // echo $status->success;
    public function googleReCaptch($secretKey){
        $secret = $secretKey;
        $resp_captch = $_POST['g-recaptcha-response'];
        $remoteip = $_SERVER['REMOTE_ADDR'];
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $resp_captch . "&remoteip" . $remoteip;
        $resp_source = json_decode(file_get_contents($url));
        return $resp_source;
    }
    
    // get birth date of age like 18 , 29 , 13, 33, 50 and it will return (Y-M-D) format
    public function getBirthDateOfAge($age){
        $age_in_ticks = intval($age) * 365 * 24 * 60 * 60;
        $birth_date = time() - $age_in_ticks;
        return date('Y-m-d', $birth_date);
    }
    
    // get age from date
    public function getAgeFromDate($date_as_string){
        $d = strtotime($date_as_string);
		$age_in_tick = time() - $d;
		$age = $age_in_tick / 60 / 60 / 24 / 365;
        $age = number_format($age, 1, '.', '');
		return $age;
    }
	
    public function addNewGet($key_value = false, $overrideGet = false, $resetGet = false, $unset_keys = []){
        $new_url = '';
        $temp_arr = $_GET;
        foreach($unset_keys as $k => $v){
            unset($temp_arr[$v]);
        }
        if($resetGet === true){
            foreach($temp_arr as $k => $v){
                if($k == 'route')
                    continue;
                else
                    unset($temp_arr[$k]);
            }
        }
        if(is_array($key_value)){
            foreach($key_value as $k => $v){
                if(isset($temp_arr[$k])){
                    if($overrideGet === true){
                        $temp_arr[$k] = $v;
                    }
                }else{
                    $temp_arr[$k] = $v;
                }
            }
        }
        foreach($temp_arr as $k => $v){
            if($k == 'route'){
                $new_url .= $v;
            }else{
                $new_url .= "&" . $k . "=" . $v;
            }
        }
        return URL . '/' . $new_url;
    }

    // arabic date
    public function ArabicDate() {
        $months = array("Jan" => "يناير", "Feb" => "فبراير", "Mar" => "مارس", "Apr" => "أبريل", "May" => "مايو", "Jun" => "يونيو", "Jul" => "يوليو", "Aug" => "أغسطس", "Sep" => "سبتمبر", "Oct" => "أكتوبر", "Nov" => "نوفمبر", "Dec" => "ديسمبر");
        $your_date = date('y-m-d'); // The Current Date
        $en_month = date("M", strtotime($your_date));
        foreach ($months as $en => $ar) {
            if ($en == $en_month) { $ar_month = $ar; }
        }
        $find = array ("Sat", "Sun", "Mon", "Tue", "Wed" , "Thu", "Fri");
        $replace = array ("السبت", "الأحد", "الإثنين", "الثلاثاء", "الأربعاء", "الخميس", "الجمعة");
        $ar_day_format = date('D'); // The Current Day
        $ar_day = str_replace($find, $replace, $ar_day_format);
        $standard = array("0","1","2","3","4","5","6","7","8","9");
        $eastern_arabic_symbols = array("٠","١","٢","٣","٤","٥","٦","٧","٨","٩");
        $current_date = date('d').'  '.$ar_month.'  '.date('Y');
        $arabic_date = str_replace($standard , $eastern_arabic_symbols , $current_date);
        return $arabic_date;
    }
    // arabic day
    public function ArabicDay() {
        $find = array ("Sat", "Sun", "Mon", "Tue", "Wed" , "Thu", "Fri");
        $replace = array ("السبت", "الأحد", "الإثنين", "الثلاثاء", "الأربعاء", "الخميس", "الجمعة");
        $ar_day_format = date('D'); // The Current Day
        $ar_day = str_replace($find, $replace, $ar_day_format);
        return $ar_day;
    }

    // generate token
    public function genToken($SessionKey){
        $token = sha1(rand());
        $_SESSION[$SessionKey] = $token;
        return $token;
    }

    // add changelang to get
    public function langToGet($theLang){
        $newGetUrl = URL . '/' . $_GET['route'];
        $_GET['dlang'] = $theLang;
        foreach($_GET as $k => $v){
            if($k == 'route') continue;
            else{
                $newGetUrl .= '&' . $k . '=' . $v;
            }
        }
        echo $newGetUrl;
    }

    public function getBrowser() 
	{ 
	    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
	    $bname = 'Unknown';
	    $platform = 'Unknown';
	    $version= "";

	    //First get the platform?
	    if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
	    }
	    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
	    }
	    elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
	    }

	    // Next get the name of the useragent yes seperately and for good reason
	    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
	    { 
            $bname = 'Internet Explorer'; 
            $ub = "MSIE"; 
	    } 
	    elseif(preg_match('/Firefox/i',$u_agent)) 
	    { 
            $bname = 'Mozilla Firefox'; 
            $ub = "Firefox"; 
	    }
	    elseif(preg_match('/OPR/i',$u_agent)) 
	    { 
            $bname = 'Opera'; 
            $ub = "Opera"; 
	    } 
	    elseif(preg_match('/Chrome/i',$u_agent)) 
	    { 
            $bname = 'Google Chrome'; 
            $ub = "Chrome"; 
	    } 
	    elseif(preg_match('/Safari/i',$u_agent)) 
	    { 
            $bname = 'Apple Safari'; 
            $ub = "Safari"; 
	    } 
	    elseif(preg_match('/Netscape/i',$u_agent)) 
	    { 
            $bname = 'Netscape'; 
            $ub = "Netscape"; 
	    }else{
            $bname = 'Unknown-1'; 
            $ub = "Unknown-1";
		
	    }
	    
	    // finally get the correct version number
	    $known = array('Version', $ub, 'other');
	    $pattern = '#(?<browser>' . join('|', $known) .
                 ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
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
            }
            else {
                if($ub != 'Unknown-1')
                    $version= $matches['version'][1];
            }
	    }
	    else {
            $version= $matches['version'][0];
	    }

	    // check if we have a number
	    if ($version==null || $version=="") {$version="?";}

	    return array(
            'userAgent' => $u_agent,
            'name'      => $bname,
            'ub' => strtolower($ub),
            'version'   => $version,
            'platform'  => $platform,
            'pattern'    => $pattern
	    );
	}
	
    // get days and hrs and m and seconds
    public function getDHMS($total_seconds){
        $d = floor($total_seconds / 86400);
        $total_seconds -= $d * 86400;

        $h = floor(($total_seconds / 3600) % 24);
        $total_seconds -= $h * 3600;
        
        $m = floor(($total_seconds / 60) % 60);
        $total_seconds -= $m * 60;

        $s = $total_seconds % 60;

        return [
            'd' => intval($d),
            'h' => intval($h),
            'm' => intval($m),
            's' => intval($s)
        ];
    }
}   
?>
