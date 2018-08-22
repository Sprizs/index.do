<?php
error_reporting(0);
include './idn.php';
$whoisservers = json_decode(file_get_contents('./whois-list.json'), true);

function LookupDomain($domain) {
	global $whoisservers;
	$whoisserver = "";

	$dotpos = strpos($domain, ".");
	$domtld = substr($domain, $dotpos + 1);

	$whoisserver = $whoisservers[$domtld];

	if (!$whoisserver) {
		return "Whois Nic Server Not Found";
	}
	$result = QueryWhoisServer($whoisserver, $domain);
	if (!$result) {
		return "Whois Nic Server Empty Response";
	}

	preg_match("/Whois Server: (.*)/", $result, $matches);
	$secondary = @$matches[1];
	if ($secondary) {
		$result = QueryWhoisServer($secondary, $domain);
	}
	return $result;
}
function parse_time($text, $domain) {
	if (strstr($text, 'T')) {
		if (strstr($text, '-')) {
			return substr(str_replace('T', '', $text), 0, 10);
		} else {
			return $text;
		}
	}
	if (strstr($text, ".")) {
		return implode('-', array_reverse(explode('-', str_replace('.', '-', $text))));
	}
	if (strstr($text, '/')) {
		return str_replace('/', '-', $text);
	}
	$_month = explode(',', 'January,February,March,April,May,June,July,August,September,October, November,December');
	$_month_short = explode(',', 'Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sept,Oct, Nov,Dec');

	foreach ($_month as $key => $value) {
		if (strstr($text, $value)) {
			$text = explode(' ', $text);
			$day = intval($text[0]);
			if (strlen($day) == 1) {
				$day = "0" . $day;
			}
			$month = array_search($text[1], $_month) + 1;
			if (strlen($month) == 1) {
				$month = "0" . $month;
			}
			$yeah = intval($text[2]);
			return "$yeah-$month-$day";
		}
	}
	foreach ($_month_short as $key => $value) {
		if (strstr($text, $value)) {
			$text = explode(' ', $text);
			$day = intval($text[0]);
			if (strlen($day) == 1) {
				$day = "0" . $day;
			}
			$month = array_search($text[1], $_month_short) + 1;
			if (strlen($month) == 1) {
				$month = "0" . $month;
			}
			$yeah = intval($text[2]);
			return "$yeah-$month-$day";
		}
	}
}
function parse_registrar_name($data) {
	if (strstr($data, ':')) {
		$name = explode(':', $data)[1];
		return trim($name);
	} elseif (strstr($data, '[Registrant]')) {
		return trim(explode('[Registrant]', $data)[1]);
	} else {
		return trim($data);
	}
}
function QueryWhoisServer($whoisserver, $domain) {
	$port = 43;
	$timeout = 10;
	$fp = @fsockopen($whoisserver, $port, $errno, $errstr, $timeout) or die("Socket Error " . $errno . " - " . $errstr);
	fputs($fp, $domain . "\r\n");
	$out = "";
	while (!feof($fp)) {
		$out .= fgets($fp);
	}
	fclose($fp);
	return $out;
}
if (!@$_GET['domain']) {
	$domain = $argv[1];
} else {
	$domain = $_GET['domain'];
}
if ($domain) {
	$domain_data = [];
	$result = LookupDomain($domain);
	$result = explode(PHP_EOL, $result);
	//print_r($result);
	//Parse Domain Register Date
	foreach ($result as $key => $value) {
		// if (end(explode('.', $domain)) == 'mx') {
		// 	print_r($result);
		// 	return;
		// }
		if (strstr($value, 'Domain Name Commencement Date')) {
			$reg_time = parse_time(trim(explode(':', $value)[1]), $domain);
			$domain_data['register_date'] = $reg_time;
			break;
		}
		if (strstr($value, 'Created On')) {
			$reg_time = trim(explode(':', $value)[1]);
			$domain_data['register_date'] = $reg_time;
			break;
		}
		if (strstr($value, 'status...............: Registered')) {
			$reg_time = parse_time(trim(explode(':', $domain . " " . $result[$key + 1])[1]), $domain);
			$domain_data['register_date'] = $reg_time;
			break;
		}
		if (strstr($value, 'created..............:')) {
			$reg_time = parse_time(trim(explode(':', $value)[1]), $domain);
			$domain_data['register_date'] = $reg_time;
			break;
		}
		if (strstr($value, 'Creation Date')) {
			$reg_time = parse_time(trim(explode(':', $value)[1]), $domain);
			$domain_data['register_date'] = $reg_time;
			break;
		}
		if (strstr($value, 'Registration Time')) {
			$reg_time = parse_time(trim(explode(':', $value)[1]), $domain);
			$domain_data['register_date'] = $reg_time;
			break;
		}
		if (strstr($value, 'Registration date')) {
			$reg_time = parse_time(trim(explode(':', $value)[1]), $domain);
			$domain_data['register_date'] = $reg_time;
			break;
		}
		if (strstr($value, 'Registered on')) {
			print_r(parse_time(trim(explode('Registered on', $value)[1]), $domain));
			break;
		}
		if (strstr($value, 'Registered')) {
			$reg_time = parse_time(trim(explode(':', $value)[1]), $domain);
			$domain_data['register_date'] = $reg_time;
			break;
		}
		if (strstr($value, 'created')) {
			$reg_time = parse_time(trim(explode(':', $value)[1]), $domain);
			$domain_data['register_date'] = $reg_time;
			break;
		}
		if (strstr($value, 'Fecha de registro')) {
			$reg_time = parse_time(trim(explode(':', $value)[1]), $domain);
			$domain_data['register_date'] = $reg_time;
			break;
		}
		if (strstr($value, 'Domain registered')) {
			$reg_time = parse_time(trim(explode(':', $value)[1]), $domain);
			$domain_data['register_date'] = $reg_time;
			break;
		}
		if (strstr($value, 'Created')) {
			$reg_time = parse_time(trim(explode(':', $value)[1]), $domain);
			$domain_data['register_date'] = $reg_time;
			break;
		}
		if (strstr($value, 'registered')) {
			$reg_time = parse_time(trim(explode(':', $value)[1]), $domain);
			$domain_data['register_date'] = $reg_time;
			break;
		}
		if (strstr($value, '[登録年月日]')) {
			$reg_time = parse_time(trim(explode('[登録年月日]', $value)[1]), $domain);
			$domain_data['register_date'] = $reg_time;
			break;
		}
		if (strstr($value, 'Activation')) {
			$reg_time = parse_time(trim(explode(':', $value)[1]), $domain);
			$domain_data['register_date'] = $reg_time;
			break;
		}
	}
	//Parse Domain Registrar Name
	foreach ($result as $key => $value) {
		if (strstr($value, 'Sponsoring Registrar')) {
			$domain_data['registrar_name'] = parse_registrar_name($value);
			break;
		} elseif (strstr($value, 'Registrar Name:')) {
			$domain_data['registrar_name'] = parse_registrar_name($value);
			break;
		} elseif (strstr($value, 'Registrant:')) {
			$domain_data['registrar_name'] = parse_registrar_name($result[$key + 1]);
			break;
		} elseif (strstr($value, 'Registrar:')) {
			$domain_data['registrar_name'] = parse_registrar_name($value);
			break;
		} elseif (strstr($value, '[Registrant]')) {
			$domain_data['registrar_name'] = parse_registrar_name($value);
			break;
		} elseif (strstr($value, 'registrar:')) {
			$domain_data['registrar_name'] = parse_registrar_name($value);
			break;
		} elseif (strstr($value, 'registrar............:')) {
			$domain_data['registrar_name'] = parse_registrar_name($value);
			break;
		}
	}
	//Parse Domain Expire Date
	foreach ($result as $key => $value) {
		if (strstr($value, 'Expiry')) {
			$date = date_parse($value);
			if (strlen($date['month']) == 1) {
				$month = "0" . $date['month'];
			} else {
				$month = $date['month'];
			}
			if (strlen($date['day']) == 1) {
				$day = "0" . $date['day'];
			} else {
				$day = $date['day'];
			}
			$domain_data['expire_date'] = $date['year'] . "-" . $month . "-" . $day;
			break;
		} elseif (strstr($value, 'Expiration Date')) {
			$expire_date = trim(explode(':', $value)[1]);
			$domain_data['expire_date'] = $expire_date;
			break;
		} elseif (strstr($value, '[有効期限]')) {
			$result = explode('[有効期限]', $value);
			$domain_data['expire_date'] = str_replace('/', '-', trim($result[1]));
		} elseif (strstr($value, 'Expires')) {
			$result = explode('Expires: ', $value);
			$domain_data['expire_date'] = parse_time($result[1],123);
		}
	}
	echo json_encode($domain_data);
}