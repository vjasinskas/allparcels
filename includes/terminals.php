<?php

class Terminals
{
    static public function carriersArray()
    {
        return [
            'POST24_LT' => 'Omniva LT',
            'POST24_LV' => 'Omniva LV',
            'POST24_EE' => 'Omniva EE',
            'LP_EXPRESS' => 'LP Express',
            'lvpost'  => 'lvpost',
            'VENIPAK' => 'Venipak',
            'DPD_LT' => 'DPD LT',
            'DPD_LV' => 'DPD LV',
            'DPD_EE' => 'DPD EE',
            'UPS' => 'UPS LT',
            'UPS_LV' => 'UPS LV',
            'UPS_EE' => 'UPS EE',
            'TNT_LT' => 'TNT LT',
            'TNT_LV' => 'TNT LV',
            'TNT_EE' => 'TNT EE',
            'DHL' => 'DHL',
            'LT_POST' => 'LT paštas',
            'ITELLA' => 'Itella',
        ];
    }

    /**
     * Get terminals list from toaster
     *
     * @return mixed
     */
    static public function getTerminalsList($identifier=null){
        global $wpdb;

        if($identifier == null)
            $terminals=$wpdb->get_results( "SELECT * FROM wp_allparcels_terminals;",ARRAY_A );
        else
	        $terminals=$wpdb->get_results( "SELECT * FROM wp_allparcels_terminals WHERE identifier='$identifier';",ARRAY_A );

       if(empty($terminals) or time() - (int)$terminals[0]['data'] > 86400){
	        Terminals::updateTerminalsList();
	       if($identifier == null)
		       $terminals=$wpdb->get_results( "SELECT * FROM wp_allparcels_terminals;",ARRAY_A );
	       else
		       $terminals=$wpdb->get_results( "SELECT * FROM wp_allparcels_terminals WHERE identifier='$identifier';",ARRAY_A );
       }
        return json_encode($terminals);
    }

	static public function getListForSelect($courier, $city, $type){
		global $wpdb;
		return $wpdb->get_results( "SELECT * FROM wp_allparcels_terminals WHERE isActive = 1 AND courierIdentifier = '$courier' AND city = '$city' AND type = '$type';",ARRAY_A );
	}

	static public function updateTerminalsList(){
		global $wpdb;

		$wpdb->query("CREATE TABLE IF NOT EXISTS `wp_allparcels_terminals` (
	      `id` int(11) NOT NULL auto_increment,         
	      `identifier` varchar(250) COLLATE utf8_lithuanian_ci NOT NULL default '' UNIQUE,     
	      `name` varchar(250) COLLATE utf8_lithuanian_ci NOT NULL default '',
	      `address` varchar(250) COLLATE utf8_lithuanian_ci NOT NULL default '',
	      `postCode` varchar(250) COLLATE utf8_lithuanian_ci NOT NULL default '',
	      `city` varchar(250) COLLATE utf8_lithuanian_ci NOT NULL default '',
	      `countryCode` varchar(250) COLLATE utf8_lithuanian_ci NOT NULL default '',
	      `courierIdentifier` varchar(250) COLLATE utf8_lithuanian_ci NOT NULL default '',
	      `type` int(5) NOT NULL ,
	      `comment` varchar(250) COLLATE utf8_lithuanian_ci NOT NULL default '',
	      `isActive` tinyint(1) NOT NULL,
	      `data` varchar(250) NOT NULL default '',
	       PRIMARY KEY  (`id`)
	        );"
		);

		$wpdb->get_results( "truncate wp_allparcels_terminals;" );

		if(get_option( 'allparcels_api' )=='')
			return false;

		$url = 'https://toast.allparcels.com/api/parcel_terminals.json?showAll=1';
		$token =get_option( 'allparcels_api' );
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		$headers = [];
		$headers[] = 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0';
		$headers[] = 'token: ' . $token;
		$headers[] = 'X-Requested-With: XMLHttpRequest';
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($curl);
		curl_close($curl);

		$terminalList = json_decode ( $result, true );

		foreach ($terminalList ['terminals'] as  $value) {
			$wpdb->query(
				"INSERT INTO wp_allparcels_terminals 
					(identifier,name,address,postCode,city, countryCode,courierIdentifier,type,comment,isActive,data) 
					VALUES ('".$value['identifier']."','".$value['name']."',
			                '".$value['address']."','".$value['postCode']."',
			                '".$value['city']."','".$value['countryCode']."',
			                '".$value['courierIdentifier']."','".$value['type']."',
			                '".$value['comment']."','".$value['isActive']."',
			                '".time()."') 
	                ON DUPLICATE KEY UPDATE 
	                    name = '".$value['name']."',address = '".$value['address']."',
	                    postCode = '".$value['postCode']."', city = '".$value['city']."', 
	                    countryCode = '".$value['countryCode']."', courierIdentifier = '".$value['courierIdentifier']."',
	                    type = '".$value['type']."', comment = '".$value['comment']."',
	                    isActive = '".$value['isActive']."', data = '".time()."';"
			);
		}

		return true;
	}

}