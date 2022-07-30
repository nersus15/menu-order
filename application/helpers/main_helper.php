<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!method_exists($this, 'response')) {
    function response($message = '', $code = 200, $type = 'success', $format = 'json')
    {
        http_response_code($code);
        $responsse = array();
        if ($code != 200)
            $type = 'Error';

        if(is_object($message))
            $message = (array) $message;
        if (is_string($message) || is_int($message) || is_bool($message))
            $responsse['message'] = $message;
        else
            $responsse = $message;

        if (!isset($message['type']))
            $responsse['type'] = $type;
        else
            $responsse['type'] = $message['type'];

        if($code != 200 && $format == 'json')
            header("message: " . json_encode($responsse));
        
        if ($format == 'json'){
            header('Content-Type: application/json');
            echo json_encode($responsse);
        }
        elseif ($format == 'html') {
            echo '<script> var path = "' . base_url() . '"</script>';
            echo $responsse['message'];
        }
        exit();
    }
}
if(!function_exists('kapitalize')){
    function kapitalize($string, $tipe = 'firstword'){
        if($tipe == 'all'){
            return strtoupper($string);
        }elseif($tipe == 'first'){
            return ucfirst(strtolower($string));
        }elseif($tipe == 'firstword'){
            $str = explode(' ', $string);
            $tmp = [];
            foreach($str as $s) {
                $tmp[] = ucfirst(strtolower($s));
            }

            return join(' ', $tmp);
        }
    }
}
if (!method_exists($this, 'httpmethod')) {
    function httpmethod($method = 'POST')
    {
        return $_SERVER['REQUEST_METHOD'] == $method;
    }
}

if (!method_exists($this, 'sessiondata')) {
    function sessiondata($index = 'login', $kolom = null, $default = null)
    {
        // if (!is_login())
        //     return;
        /** @var CI_Controller $CI */
        $CI =& get_instance();

        $data = $CI->session->userdata($index);
        return( empty($kolom) ? $data : (empty($data[$kolom]) ? $default : $data[$kolom]));
    }
}

if (!method_exists($this, 'waktu')) {
    function waktu($waktu = null, $format = MYSQL_TIMESTAMP_FORMAT)
    {
        $waktu = empty($waktu) ? time() : $waktu;
        return date($format, $waktu);
    }
}
if (!method_exists($this, 'config_sidebar')) {
    function config_sidebar($configName = 'comp', $sidebar, int $activeMenu = null, $subMenuConf = null)
    {
        /** @var CI_Controller $ci */
        $ci =& get_instance();

        $ci->load->config($configName);
        $compConf = $ci->config->item('comp');
        $sidebarConf = $compConf['dore']['sidebar'][$sidebar];
        if(!is_null($activeMenu))
            $sidebarConf['menus'][$activeMenu]['active'] = true;

        if (!empty($subMenuConf)) {
            $sidebarConf['subMenus'][$subMenuConf['sub']]['menus'][$subMenuConf['menu']]['active'] = true;
        }
        
        // Tandai sebagai menu sidebar
        foreach($sidebarConf['menus']  as $k => $m){
            $sidebarConf['menus'][$k]['parrent_element'] = 'sidebar';
            $sidebarConf['menus'][$k]['id'] = '-';

        }

        foreach($sidebarConf['subMenus'] as $k => $sb){
            foreach($sb['menus'] as $k1 => $m){
                $sidebarConf['subMenus'][$k]['menus'][$k1]['parrent_element'] = 'sidebar';
            }
        }
        return $sidebarConf;
    }
}

if (!method_exists($this, 'random')) {
    function random($length = 5, $type = 'string')
    {
        $characters = $type == 'string' ? '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' : '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $type == 'string' ? $randomString : boolval($randomString);
    }
}
if (!method_exists($this, 'is_login')) {
    function is_login($role = null, $wil = null, $callback = null)
    {
        /** @var CI_Controller $ci */
        $ci =& get_instance();
        $userdata = $ci->session->userdata('login'); //sessiondata('login')
        if(!empty($callback) && is_callable($callback))
           return $callback($role, $wil, $userdata);

        if (empty($role) && empty($wil)) {
                return !empty($userdata);
        } elseif (!empty($userdata) && !empty($role) && empty($wil)) {
                return $userdata['user_role'] == $role;
        } elseif (!empty($userdata) && empty($role) && !empty($wil)) {
                return $userdata['wilayah'] == $wil;
        } elseif (!empty($userdata) && !empty($role) && !empty($wil)) {
            return $userdata['wilayah'] == $wil && $userdata['role'] == $role;
        }
    }
}

if (!method_exists($this, 'include_view')) {
    function include_view($path, $data = null)
    {
        // if (is_array($data))
        //     extract($data);
        // include get_path(APPPATH . 'views/' . $path . '.php');
        $ci =& get_instance();
        echo $ci->load->view(get_path($path) .".php", $data, true);
    }
}

if (!method_exists($this, 'rupiah_format')) {
    function rupiah_format($angka)
    {
        $hasil_rupiah = "Rp. " . number_format($angka, 2, ',', '.');
        return $hasil_rupiah;
    }
}


// CONVERT PATH
if(!method_exists($this, 'get_path')){
    function get_path($path){
        return DIRECTORY_SEPARATOR == '/' ? str_replace('\\', '/', $path) : str_replace('/', '\\', $path);

    }
}

if ( ! function_exists('attribut_ke_str'))
{
	function attribut_ke_str($attribute, $delimiter = ' ', $dg_quote = true)
	{
		$str = '';
		if (is_array($attribute)) {
			foreach ($attribute as $key => $value) {
				if ($value !== '0' && empty($value))
					$str .= $key;
				else {
					$str .= $key . '=';
					if (is_array($value))
						$value = implode(' ', $value);
					$str .= $dg_quote ? '"' . $value . '"' : $value;
				}
				$str .= $delimiter;
			}
			
			$str = substr($str, 0, strlen($str) - strlen($delimiter));
		}
		return $str;
	}
}

if ( ! function_exists('str_ke_attribut'))
{
	function str_ke_attribut($str, $delimiter = '/[=\n]/')
	{
		$attribute = array();
		
		$a = preg_split($delimiter, $str, -1, PREG_SPLIT_NO_EMPTY);
		for ($i = 0; $i < count($a); $i+=2) {
			$attribute[$a[$i]] = $a[$i+1];
		}
		return $attribute;
	}
}
if(!function_exists('starWith')){
    function startWith( $haystack, $needle ) {
        $length = strlen( $needle );
        return substr( $haystack, 0, $length ) === $needle;
   }
}

if(!function_exists('endWith')){
    function endWith( $haystack, $needle ) {
        $length = strlen( $needle );
        if( !$length ) {
            return true;
        }
        return substr( $haystack, -$length ) === $needle;
    }
}

if(!function_exists('assets_url')){
    function assets_url($path = null){
        return base_url('assets/' . $path);
    }
}

if(!function_exists('sandi')){
      /**
     * @param String $text
     * @param String $type ['AN', 'AZ'] - [default: 'AN']
     * @return String
     */
    function sandi($text, $type = "AN"){
        $result = null;
        $an = [
            'a' => 'n',
            'b' => 'o',
            'c' => 'p',
            'd' => 'q',
            'e' => 'r',
            'f' => 's',
            'g' => 't',
            'h' => 'u',
            'i' => 'v',
            'j' => 'w',
            'k' => 'x',
            'l' => 'y',
            'm' => 'z',
            'A' => 'N',
            'B' => 'O',
            'C' => 'P',
            'D' => 'Q',
            'E' => 'R',
            'F' => 'S',
            'G' => 'T',
            'H' => 'U',
            'I' => 'V',
            'J' => 'W',
            'K' => 'X',
            'L' => 'Y',
            'M' => 'Z',
            '-' => '+',
            '_' => '=',
            '@' => '#',
            '&' => '!',
            ' ' => '*',
        ];
        $az = [
            'a' => 'z',
            'b' => 'y',
            'c' => 'x',
            'd' => 'w',
            'e' => 'v',
            'f' => 'u',
            'g' => 't',
            'h' => 's',
            'i' => 'r',
            'j' => 'q',
            'k' => 'p',
            'l' => 'o',
            'm' => 'n',
            'n' => 'm',
            'o' => 'l',
            'p' => 'k',
            'q' => 'j',
            'r' => 'i',
            's' => 'h',
            't' => 'g',
            'u' => 'f',
            'v' => 'e',
            'w' => 'd',
            'x' => 'c',
            'y' => 'b',
            'z' => 'a',

            'A' => 'N',
            'B' => 'O',
            'C' => 'P',
            'D' => 'Q',
            'E' => 'R',
            'F' => 'S',
            'G' => 'T',
            'H' => 'U',
            'I' => 'V',
            'J' => 'W',
            'K' => 'X',
            'L' => 'Y',
            'M' => 'Z',

            '-' => '+',
            '_' => '=',
            '@' => '#',
            '&' => '!',
            ' ' => '*',
        ];
        $an_flip = array_flip($an);
        $az_flip = array_flip($az);
        if($type == "AN"){
            foreach(str_split($text) as $char){
                if(isset($an[$char]))
                    $result .= $an[$char];
                elseif(isset($an_flip[$char]))
                    $result .= $an_flip[$char];
            }
        }else if($type == "AZ"){
            foreach(str_split($text) as $char){
                if(isset($az[$char]))
                    $result .= $az[$char];
                elseif(isset($az_flip[$char]))
                    $result .= $az_flip[$char];
            }
        }
        return $result;
    }
}