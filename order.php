<?php

echo color('blue', "[+]")." Domino's Order Bot - By: GidhanB.A\n";

$headers = array();
$headers[] = 'device-type: android';
$headers[] = 'Accept: application/json';
$headers[] = 'language: in';
$headers[] = 'Content-Type: application/x-www-form-urlencoded';
$headers[] = 'Connection: Keep-Alive';
$headers[] = 'User-Agent: okhttp/3.12.0';
$headers[] = 'token: lER2MLyGC6Go3rNdE7diPVf0umanUuTf8KhVwPB9ViyZJldnsqFhmViQisdcW6s4';

// $piz = "MCCHTP06"; // Chili Chicken
// $piz = "MCSHTP06"; // Chicken Sausage
$piz = "PMBHTP06"; // Beef Rasher

$no = 0;
$xyz = "domino.txt";
$emp = array_filter(explode("\n", file_get_contents($xyz)));
foreach ($emp as $key => $akun) {
	$no++;

	// DATA AKUN
	$pecah = explode("|", $akun);
	$email = trim($pecah[0]);
	$custid = trim($pecah[2]);
	// $nope = "08".mt_rand(1,9).mt_rand(1,9).random(8,0);
	echo color('blue', "[$no]")." Nama: ";
	$nam = trim(fgets(STDIN));
	echo color('blue', "[$no]")." No. Hp: ";
	$nope = trim(fgets(STDIN));
	$nama = explode(" ", $nam);
	$first = trim($nama[0]);
	$last = trim($nama[1]);
	$jam = "2020-11-06 16:00:00";
	// $jam = "now";
	echo color('blue', "[$no]")." Nama: $nam\n";
	echo color('blue', "[$no]")." Email: $email\n\n";

	// LOKASI ORDER
	echo color('blue', "[+]")." ==== LOCATION ====\n";
	$loc = curl('https://www.dominos.co.id/infdominos/api/getConfiguration', null, $headers);
	if (strpos($loc[1], '"status":"success"')) {
		$locs = json_decode($loc[1])->data->area_id->data;
		for ($i=0; $i < count($locs); $i++) {
			$no = $i+1;
			echo color('yellow', "[+]")." $no. ".ucwords(strtolower($locs[$i]->area_name_idn))."\n";
		}
		echo color('blue', "[+]")." ==================\n";
		echo color('green', "[+]")." Silahkan pilih (1-23): ";
		$stor = trim(fgets(STDIN));
		// $stor = 21;
		if (in_array($stor, range(1,23)) == false) die("Hadeh luh!");
		$store = curl('https://www.dominos.co.id/infdominos/api/getStore?area_id='.$stor, null, $headers);
		if (strpos($store[1], '"status":"success"')) {
			echo "\n";
			$infos = json_decode($store[1])->data;
			echo color('blue', "[+]")." ====== STORE =====\n";
			for ($j=0; $j < count($infos); $j++) { 
				$nu = $j+1;
				echo color('blue', "[+]")." $nu. Domino's Pizza ".ucwords(strtolower($infos[$j]->store_title_idn))."\n";
			}
			echo color('blue', "[+]")." ==================\n";
			echo color('green', "[+]")." Silahkan pilih (1-".count($infos)."): ";
			$zoz = trim(fgets(STDIN));
			// $zoz = 1;
			if (in_array($zoz, range(1,count($infos))) == false) die("Hadeh luh!");
			$yolo = $zoz-1;
			$alamat = $infos[$yolo]->store_address_idn;
			$alamatcode = $infos[$yolo]->store_mapping_code;
			$storelong = $infos[$yolo]->store_location_long;
			$storelat = $infos[$yolo]->store_location_lat;
			echo "\n";
		} else {
			die($store[1]);
		}
	} else {
		die($loc[1]);
	}

	// ORDER FORM
	$cek = curl('https://www.dominos.co.id/infdominos/api/getPrice', 'options=%7B%2220926%22%3A%22'.$piz.'%22%7D&qty=1&sku=NEW1000&withtax=1&customer_id='.$custid.'&quote_id=0', $headers);
	if (strpos($cek[1], '"status":"success"')) {
		$add = curl('https://www.dominos.co.id/infdominos/api/setPaymentMethod', 'customer_id='.$custid.'&payment_code=snapmigs&mobile=true', $headers);
		if (strpos($add[1], '"status":"success"')) {
			Order:
			$order = curl('https://www.dominos.co.id/infdominos/api/placeOrderNew', 'survey_address_id='.urlencode($alamat).'&mobile=1&items='.urlencode('[{"sku":"NEW1000","qty":1,"options":"{\"20926\":\"'.$piz.'\"}","parent_sku":"NEW1000","coupon_code":""}]').'&service_method=carryout_carryout&store_code='.$alamatcode.'&firstname='.$first.'&lastname='.$last.'&email='.urlencode($email).'&contact_number='.$nope.'&contact_ext=&contact_type=m&payment_code=snapmigs&order_source=mobile&delivery_time='.$jam.'&remarks=&longitude='.$storelong.'&latitude='.$storelat.'&substreet='.urlencode($alamat).'&tower=null%20null&affiliate_vendor=&deeplink_url=dominos%3A%2F%2Fstatus%2Fru&customer_id='.$custid.'&session_id=e8271137-acc1-4374-8d2a-'.random(12,1).'&fav_order=0&address_id=&unique_token=&recaptcha-response=&checksum=', $headers);
			if (strpos($order[1], 'Order has been placed successfully!')) {
				$rez = json_decode($order[1]);
				$dana = $rez->data->redirect_url;
				$orid = $rez->data->order_id;
				echo color('green', "[+]")." Order success! - OrderId: ".$orid."\n";
				$link = json_decode(file_get_contents("https://wirkel.com/short/api/?key=6kMEwKCcNQyE&url=".urlencode($dana)))->short;
				echo color('blue', "[+]")." Pay via DANA!: $link\n";
				$a = fopen("order.txt","a+");
				fwrite($a, $nam."|".$email."|sarkem123|".$orid."|".$link."\n");
				fclose($a);
				delfirstline($xyz);
			} elseif (strpos($order[1], 'hanya bisa digunakan sekali')) {
				$nope = "08".mt_rand(1,9).mt_rand(1,9).random(8,0);
				$chg = curl('https://www.dominos.co.id/infdominos/api/updatecustomernew', 'customer_id='.$custid.'&prefix=Mr.&firstname='.$first.'&lastname='.$last.'&birthdate=12-12-2000&contact_ext=&contact_type=m&contact_number='.$nope.'&current_password=sarkem123', $headers);
				if (strpos($chg[1], '"status":"success"')) {
					goto Order;
				} else {
					die($chg[1]);
				}
			} elseif (strpos($order[1], 'First time')) {
				continue;
			} else {
				echo color('red', "[+]")." Error: ";
				die($order[1]);
			}
		} else {
			die($add[1]);
		}
	} elseif (strpos($cek[1], 'First time')) {
		continue;
	} else {
		die($cek[1]);
	}
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

function delfirstline($filename) 
	{
	  $file = file($filename);
	  $output = $file[0];
	  unset($file[0]);
	  file_put_contents($filename, $file);
	  return $output;
	}
