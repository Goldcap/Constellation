<?

define("APPROVED", 1);
define("DECLINED", 2);
define("ERROR", 3);

class gwapi {

	// Initial Setting Functions

	function setLogin($username, $password) {/*{{{*/
		$this->login['username'] = $username;
		$this->login['password'] = $password;
	}/*}}}*/

	function setOrder($orderid,
            $orderdescription,
            $tax,
            $shipping,
            $ponumber,
            $ipaddress) {/*{{{*/
        $this->order['orderid']          = $orderid;
        $this->order['orderdescription'] = $orderdescription;
        $this->order['tax']              = $tax;
        $this->order['shipping']         = $shipping;
        $this->order['ponumber']         = $ponumber;
        $this->order['ipaddress']        = $ipaddress;
	}/*}}}*/

	function setBilling($firstname,
            $lastname,
            $company,
            $address1,
            $address2,
            $city,
            $state,
            $zip,
            $country,
            $phone,
            $fax,
            $email,
            $website) {/*{{{*/
        $this->billing['firstname'] = $firstname;
        $this->billing['lastname']  = $lastname;
        $this->billing['company']   = $company;
        $this->billing['address1']  = $address1;
        $this->billing['address2']  = $address2;
        $this->billing['city']      = $city;
        $this->billing['state']     = $state;
        $this->billing['zip']       = $zip;
        $this->billing['country']   = $country;
        $this->billing['phone']     = $phone;
        $this->billing['fax']       = $fax;
        $this->billing['email']     = $email;
        $this->billing['website']   = $website;
	}/*}}}*/

	function setShipping($firstname,
            $lastname,
            $company,
            $address1,
            $address2,
            $city,
            $state,
            $zip,
            $country,
            $email) {/*{{{*/
        $this->shipping['firstname'] = $firstname;
        $this->shipping['lastname']  = $lastname;
        $this->shipping['company']   = $company;
        $this->shipping['address1']  = $address1;
        $this->shipping['address2']  = $address2;
        $this->shipping['city']      = $city;
        $this->shipping['state']     = $state;
        $this->shipping['zip']       = $zip;
        $this->shipping['country']   = $country;
        $this->shipping['email']     = $email;
	}/*}}}*/

	// Transaction Functions

	function doSale($amount, $ccnumber, $ccexp, $cvv="") {/*{{{*/

		$query  = "";
		// Login Information
		$query .= "username=" . urlencode($this->login['username']) . "&";
		$query .= "password=" . urlencode($this->login['password']) . "&";
		// Sales Information
		$query .= "ccnumber=" . urlencode($ccnumber) . "&";
		$query .= "ccexp=" . urlencode($ccexp) . "&";
		$query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
		$query .= "cvv=" . urlencode($cvv) . "&";
		// Order Information
		$query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
		$query .= "orderid=" . urlencode($this->order['orderid']) . "&";
		$query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
		$query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
		$query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
		$query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
		// Billing Information
		$query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
		$query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
		$query .= "company=" . urlencode($this->billing['company']) . "&";
		$query .= "address1=" . urlencode($this->billing['address1']) . "&";
		$query .= "address2=" . urlencode($this->billing['address2']) . "&";
		$query .= "city=" . urlencode($this->billing['city']) . "&";
		$query .= "state=" . urlencode($this->billing['state']) . "&";
		$query .= "zip=" . urlencode($this->billing['zip']) . "&";
		$query .= "country=" . urlencode($this->billing['country']) . "&";
		$query .= "phone=" . urlencode($this->billing['phone']) . "&";
		$query .= "fax=" . urlencode($this->billing['fax']) . "&";
		$query .= "email=" . urlencode($this->billing['email']) . "&";
		$query .= "website=" . urlencode($this->billing['website']) . "&";
		// Shipping Information
		$query .= "shipping_firstname=" . urlencode($this->shipping['firstname']) . "&";
		$query .= "shipping_lastname=" . urlencode($this->shipping['lastname']) . "&";
		$query .= "shipping_company=" . urlencode($this->shipping['company']) . "&";
		$query .= "shipping_address1=" . urlencode($this->shipping['address1']) . "&";
		$query .= "shipping_address2=" . urlencode($this->shipping['address2']) . "&";
		$query .= "shipping_city=" . urlencode($this->shipping['city']) . "&";
		$query .= "shipping_state=" . urlencode($this->shipping['state']) . "&";
		$query .= "shipping_zip=" . urlencode($this->shipping['zip']) . "&";
		$query .= "shipping_country=" . urlencode($this->shipping['country']) . "&";
		$query .= "shipping_email=" . urlencode($this->shipping['email']) . "&";
		$query .= "type=sale";
		return $this->_doPost($query);

	}/*}}}*/

	function doAuth($amount, $ccnumber, $ccexp, $cvv="") {/*{{{*/

		$query  = "";
		// Login Information
		$query .= "username=" . urlencode($this->login['username']) . "&";
		$query .= "password=" . urlencode($this->login['password']) . "&";
		// Sales Information
		$query .= "ccnumber=" . urlencode($ccnumber) . "&";
		$query .= "ccexp=" . urlencode($ccexp) . "&";
		$query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
		$query .= "cvv=" . urlencode($cvv) . "&";
		// Order Information
		$query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
		$query .= "orderid=" . urlencode($this->order['orderid']) . "&";
		$query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
		$query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
		$query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
		$query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
		// Billing Information
		$query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
		$query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
		$query .= "company=" . urlencode($this->billing['company']) . "&";
		$query .= "address1=" . urlencode($this->billing['address1']) . "&";
		$query .= "address2=" . urlencode($this->billing['address2']) . "&";
		$query .= "city=" . urlencode($this->billing['city']) . "&";
		$query .= "state=" . urlencode($this->billing['state']) . "&";
		$query .= "zip=" . urlencode($this->billing['zip']) . "&";
		$query .= "country=" . urlencode($this->billing['country']) . "&";
		$query .= "phone=" . urlencode($this->billing['phone']) . "&";
		$query .= "fax=" . urlencode($this->billing['fax']) . "&";
		$query .= "email=" . urlencode($this->billing['email']) . "&";
		$query .= "website=" . urlencode($this->billing['website']) . "&";
		// Shipping Information
		$query .= "shipping_firstname=" . urlencode($this->shipping['firstname']) . "&";
		$query .= "shipping_lastname=" . urlencode($this->shipping['lastname']) . "&";
		$query .= "shipping_company=" . urlencode($this->shipping['company']) . "&";
		$query .= "shipping_address1=" . urlencode($this->shipping['address1']) . "&";
		$query .= "shipping_address2=" . urlencode($this->shipping['address2']) . "&";
		$query .= "shipping_city=" . urlencode($this->shipping['city']) . "&";
		$query .= "shipping_state=" . urlencode($this->shipping['state']) . "&";
		$query .= "shipping_zip=" . urlencode($this->shipping['zip']) . "&";
		$query .= "shipping_country=" . urlencode($this->shipping['country']) . "&";
		$query .= "shipping_email=" . urlencode($this->shipping['email']) . "&";
		$query .= "type=auth";
		return $this->_doPost($query);

	}/*}}}*/

	function doCredit($amount, $ccnumber, $ccexp) {/*{{{*/

		$query  = "";
		// Login Information
		$query .= "username=" . urlencode($this->login['username']) . "&";
		$query .= "password=" . urlencode($this->login['password']) . "&";
		// Sales Information
		$query .= "ccnumber=" . urlencode($ccnumber) . "&";
		$query .= "ccexp=" . urlencode($ccexp) . "&";
		$query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
		// Order Information
		$query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
		$query .= "orderid=" . urlencode($this->order['orderid']) . "&";
		$query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
		$query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
		$query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
		$query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
		// Billing Information
		$query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
		$query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
		$query .= "company=" . urlencode($this->billing['company']) . "&";
		$query .= "address1=" . urlencode($this->billing['address1']) . "&";
		$query .= "address2=" . urlencode($this->billing['address2']) . "&";
		$query .= "city=" . urlencode($this->billing['city']) . "&";
		$query .= "state=" . urlencode($this->billing['state']) . "&";
		$query .= "zip=" . urlencode($this->billing['zip']) . "&";
		$query .= "country=" . urlencode($this->billing['country']) . "&";
		$query .= "phone=" . urlencode($this->billing['phone']) . "&";
		$query .= "fax=" . urlencode($this->billing['fax']) . "&";
		$query .= "email=" . urlencode($this->billing['email']) . "&";
		$query .= "website=" . urlencode($this->billing['website']) . "&";
		$query .= "type=credit";
		return $this->_doPost($query);

	}/*}}}*/

	function doOffline($authorizationcode, $amount, $ccnumber, $ccexp) {/*{{{*/

		$query  = "";
		// Login Information
		$query .= "username=" . urlencode($this->login['username']) . "&";
		$query .= "password=" . urlencode($this->login['password']) . "&";
		// Sales Information
		$query .= "ccnumber=" . urlencode($ccnumber) . "&";
		$query .= "ccexp=" . urlencode($ccexp) . "&";
		$query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
		$query .= "authorizationcode=" . urlencode($authorizationcode) . "&";
		// Order Information
		$query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
		$query .= "orderid=" . urlencode($this->order['orderid']) . "&";
		$query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
		$query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
		$query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
		$query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
		// Billing Information
		$query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
		$query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
		$query .= "company=" . urlencode($this->billing['company']) . "&";
		$query .= "address1=" . urlencode($this->billing['address1']) . "&";
		$query .= "address2=" . urlencode($this->billing['address2']) . "&";
		$query .= "city=" . urlencode($this->billing['city']) . "&";
		$query .= "state=" . urlencode($this->billing['state']) . "&";
		$query .= "zip=" . urlencode($this->billing['zip']) . "&";
		$query .= "country=" . urlencode($this->billing['country']) . "&";
		$query .= "phone=" . urlencode($this->billing['phone']) . "&";
		$query .= "fax=" . urlencode($this->billing['fax']) . "&";
		$query .= "email=" . urlencode($this->billing['email']) . "&";
		$query .= "website=" . urlencode($this->billing['website']) . "&";
		// Shipping Information
		$query .= "shipping_firstname=" . urlencode($this->shipping['firstname']) . "&";
		$query .= "shipping_lastname=" . urlencode($this->shipping['lastname']) . "&";
		$query .= "shipping_company=" . urlencode($this->shipping['company']) . "&";
		$query .= "shipping_address1=" . urlencode($this->shipping['address1']) . "&";
		$query .= "shipping_address2=" . urlencode($this->shipping['address2']) . "&";
		$query .= "shipping_city=" . urlencode($this->shipping['city']) . "&";
		$query .= "shipping_state=" . urlencode($this->shipping['state']) . "&";
		$query .= "shipping_zip=" . urlencode($this->shipping['zip']) . "&";
		$query .= "shipping_country=" . urlencode($this->shipping['country']) . "&";
		$query .= "shipping_email=" . urlencode($this->shipping['email']) . "&";
		$query .= "type=offline";
		return $this->_doPost($query);

	}/*}}}*/

	function doCapture($transactionid, $amount =0) {/*{{{*/

		$query  = "";
		// Login Information
		$query .= "username=" . urlencode($this->login['username']) . "&";
		$query .= "password=" . urlencode($this->login['password']) . "&";
		// Transaction Information
		$query .= "transactionid=" . urlencode($transactionid) . "&";
		if ($amount>0) {
			$query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
		}
		$query .= "type=capture";
		return $this->_doPost($query);

	}/*}}}*/

	function doVoid($transactionid) {/*{{{*/

		$query  = "";
		// Login Information
		$query .= "username=" . urlencode($this->login['username']) . "&";
		$query .= "password=" . urlencode($this->login['password']) . "&";
		// Transaction Information
		$query .= "transactionid=" . urlencode($transactionid) . "&";
		$query .= "type=void";
		return $this->_doPost($query);

	}/*}}}*/

	function doRefund($transactionid, $amount = 0) {/*{{{*/

		$query  = "";
		// Login Information
		$query .= "username=" . urlencode($this->login['username']) . "&";
		$query .= "password=" . urlencode($this->login['password']) . "&";
		// Transaction Information
		$query .= "transactionid=" . urlencode($transactionid) . "&";
		if ($amount>0) {
			$query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
		}
		$query .= "type=refund";
		return $this->_doPost($query);

	}/*}}}*/

	function _doPost($query) {/*{{{*/
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://secure.merchantonegateway.com/api/transact.php");
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
		curl_setopt($ch, CURLOPT_POST, 1);

		if (!($data = curl_exec($ch))) {
			return ERROR;
		}
		curl_close($ch);
		unset($ch);
		print "\n$data\n";
		$data = explode("&",$data);
		for($i=0;$i<count($data);$i++) {
			$rdata = explode("=",$data[$i]);
			$this->responses[$rdata[0]] = $rdata[1];
		}
		return $this->responses['response'];
	}/*}}}*/

}

$gw = new gwapi;
$gw->setLogin("demo", "password");
$gw->setBilling("John","Smith","Acme, Inc.","123 Main St","Suite 200", "Beverly Hills",
        "CA","90210","US","555-555-5555","555-555-5556","support@example.com",
        "www.example.com");
$gw->setShipping("Mary","Smith","na","124 Shipping Main St","Suite Ship", "Beverly Hills",
        "CA","90210","US","support@example.com");
$gw->setOrder("1234","Big Order",1, 2, "PO1234","65.192.14.10");

$r = $gw->doSale("50.00","4111111111111111","1010");
print $gw->responses['responsetext'];




?>

