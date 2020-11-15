<?php

echo color('blue', "[+]")." Domino's Account Creator - By: GidhanB.A\n";

$headers = array();
$headers[] = 'device-type: android';
$headers[] = 'Accept: application/json';
$headers[] = 'language: in';
$headers[] = 'Content-Type: application/x-www-form-urlencoded';
$headers[] = 'Connection: Keep-Alive';
$headers[] = 'User-Agent: okhttp/3.12.0';
$headers[] = 'token: lER2MLyGC6Go3rNdE7diPVf0umanUuTf8KhVwPB9ViyZJldnsqFhmViQisdcW6s4';

Yaha:
echo "\n";
$list = ["algary.xyz", "anakharam.online", "antikored.xyz", "ditinggal.online", "estetik.pw", "gcantikored.pw", "hokyaa.site", "kokkamugak.online", "ladang.site", "posisiku.pw", "wesgebe.xyz", "wirkelantikored.site"];
$dom = $list[array_rand($list)];
$data = file_get_contents("https://wirkel.com/data.php?qty=1&domain=".$dom);
$datas = json_decode($data);
$nama = explode(" ", file_get_contents("https://wirkel.com/domino.txt"));
$first = trim($nama[0]);
$last = trim($nama[1]);
$email = $datas->result[0]->email;
$nope = "08".mt_rand(1,9).mt_rand(1,9).random(8,0);

echo color('blue', "[+]")." Nama: $first $last\n";
echo color('blue', "[+]")." Email: $email\n";
$reg = curl('https://www.dominos.co.id/infdominos/api/register', 'email='.urlencode($email).'&password=sarkem123&password_confirmation=sarkem123&prefix=No&firstname='.$first.'&lastname='.$last.'&birthdate=12%2F12%2F2000&contact=&contact_number='.$nope.'&contact_type=m&chknewsletter=false&contact_ext=No', $headers);
if (strpos($reg[1], '"status":"success"')) {
	echo color('green', "[+]")." Registration successfuly!\n";
	echo color('yellow', "[+]")." Checking email";
	$emails = explode("@", $email);
	$emailx = "surl=".trim($emails[1])."%2F".trim($emails[0]);
	$xixi = array();
	$xixi[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:74.0) Gecko/20100101 Firefox/74.0';
	$xixi[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8';
	$xixi[] = 'Accept-Language: en-US,en;q=0.5';
	$xixi[] = 'Connection: keep-alive';
	$xixi[] = 'Cookie: '.$emailx;
	Awal:
	$xyz = curl('https://generator.email/', null, $xixi, true);
	if (strpos($xyz[1], "DOMINO'S PIZZA INDONESIA")) {
		echo "\n";
		$kode = get_between($xyz[1], '<strong>Activation Code:</strong> ', '<br />');
		$ver = curl('https://www.dominos.co.id/infdominos/api/customerActivation', 'email='.urlencode($email).'&activation_code='.$kode, $headers);
		if (strpos($ver[1], 'Activation success')) {
			echo color('green', "[+]")." Activation successfuly!\n";
			$custid = json_decode($ver[1])->data->customer_id;
			$save = file_get_contents("https://wirkel.com/input.php?email=".$email."&custid=".$custid);
			if ($save == "mantap") echo color('green', "[+]")." Saved on wirkel.com!\n";
			goto Yaha;
		} else {
			die($ver[1]);
		}
	} else {
		echo ".";
		goto Awal;
	}
} elseif (strpos($reg[1], 'Request unsuccessful') || strpos($reg[1], 'Loading')) {
	echo "\n";
	echo color('yellow', "[+]")." LIMIT IP! - Please on/off your mobile data!\n";
	echo color('yellow', "[+]")." Continue? (y/n): ";
	$yn = trim(fgets(STDIN));
	if ($yn == 'y') {
		goto Yaha;
	} else {
		die();
	}
} else {
	die($reg[1]);
}

function curl($url,$post,$headers,$follow=false,$method=null)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if ($follow == true) curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		if ($method !== null) curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		if ($headers !== null) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		if ($post !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$result = curl_exec($ch);
		$header = substr($result, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		$body = substr($result, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
		$cookies = array();
		foreach($matches[1] as $item) {
		  parse_str($item, $cookie);
		  $cookies = array_merge($cookies, $cookie);
		}
		return array (
		$header,
		$body,
		$cookies
		);
	}

function get_between($string, $start, $end) 
    {
        $string = " ".$string;
        $ini = strpos($string,$start);
        if ($ini == 0) return "";
        $ini += strlen($start);
        $len = strpos($string,$end,$ini) - $ini;
        return substr($string,$ini,$len);
    }

function random($length,$a) 
    {
        $str = "";
        if ($a == 0) {
            $characters = array_merge(range('0','9'));
        }elseif ($a == 1) {
            $characters = array_merge(range('0','9'),range('a','z'));
        }
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }

function color($color = "default" , $text)
    {
        $arrayColor = array(
            'red'       => '1;31',
            'green'     => '1;32',
            'yellow'    => '1;33',
            'blue'      => '1;34',
        );  
        return "\033[".$arrayColor[$color]."m".$text."\033[0m";
    }