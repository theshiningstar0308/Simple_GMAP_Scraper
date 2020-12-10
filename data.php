<?php
$api_key = 'AIzaSyDA23tueQF08Fwy-EDcdIK5UWQO50NBi2Q';
if ($_GET["query"])
{
    $query = $_GET['query'];
    $loc = $_GET['loc'];

    $lower_query = strtolower($query);
    $filename = preg_replace("/[^a-zA-Z0-9]/", "", $lower_query . $loc);
    $filename .= ".csv";
    $filePath = "static/" . $filename;

    if ((0 && is_file($filePath)) && (file_exists($filePath)))
    {
        echo "/" . $filePath;
    }
    else
    {
        //https://www.google.es/maps/search/restaurantes+terrassa/@41.5665875,1.9910308,13z
        if (strlen($loc) > 2)
        {
            $loc1 = explode(",", $loc);
            $query_link = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=" . urlencode($query) . "&location=" . urlencode($loc1[0] . "," . $loc1[1]) . "&key=" . $api_key;
        }
        else
        {
            $query_link = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=" . urlencode($query) . "&key=" . $api_key;
        }
        // https://maps.googleapis.com/maps/api/place/textsearch/json?query=restaurantes+terrassa&location=41.5652509%2C1.9929796&key=AIzaSyDA23tueQF08Fwy-EDcdIK5UWQO50NBi2Q
        $json_data = file_get_contents($query_link);
        $obj = json_decode($json_data, true);
        $fdatas = $obj['results'];
        if ($fdatas)
        {

            $list = array(
                array(
                    "Business Names",
                    'Business Types',
                    'Global Code',
                    'Phone No.',
                    'Website',
                    'GoogleMapUrl',
                    'Address',
                    'Address1',
                    'Address2',
                    'Address3',
                    'Address4',
                    'Address5'
                ) ,
            );
            foreach ($fdatas as $dt)
            {
                $address = $dt['formatted_address'];
                $BNames = $dt['name'];
                $place_id = $dt['place_id'];
                $btype = $dt['types'][0];
                $code = $dt['plus_code']['compound_code'];
                $map_url = 'https://www.google.com/maps/place/?q=place_id:' . $place_id;
                $place_details_url = 'https://maps.googleapis.com/maps/api/place/details/json?place_id=' . $place_id . '&fields=formatted_phone_number,website&key=' . $api_key;
                $place_data = file_get_contents($place_details_url);
                $place_obj = json_decode($place_data, true);
                $place_details = $place_obj['result'];
                $phone = null;
                $website = null;
                if ($place_details)
                {
                    if (array_key_exists("formatted_phone_number", $place_details))
                    {
                        $phone = $place_details['formatted_phone_number'];
                    }
                    if (array_key_exists("website", $place_details))
                    {
                        $website = $place_details['website'];
                    }

                }
                $address_s = explode(",", $address);
                for ($i = count($address_s);$i < 5;$i++)
                {
                    array_push($address_s, null);
                }
                $temp_data = array(
                    $BNames,
                    $btype,
                    $code,
                    $phone,
                    $website,
                    $map_url,
                    $address,
                    $address_s[0],
                    $address_s[1],
                    $address_s[2],
                    $address_s[3],
                    $address_s[4]
                );
                array_push($list, $temp_data);
            }
            $fp = fopen($filePath, 'w');

            foreach ($list as $fields)
            {
                fputcsv($fp, $fields);
            }
            fclose($fp);

            if (array_key_exists("next_page_token", $obj))
            {
                $nextp = $obj['next_page_token'];
                echo '<br>__/' . $filePath . '__<input type="hidden" id="token" name="token" value="' . $nextp . '"><br><button class="btn btn-success center-block" onclick="myFunction()" id="nextpage">Page ';
                exit();
            }
            else
            {
                echo '<br>__/' . $filePath . '__';
                exit();
            }

            //echo "/".$filePath;
            exit();
        }
        else
        {
            echo "Some Error Occured";
            exit();
        }
    }

}
elseif ($_GET["token"])
{
    $token = $_GET['token'];
    $lower_query = strtolower($token);
    $filename = substr(preg_replace("/[^a-zA-Z0-9]/", "", $lower_query . $loc) , 0, 10);
    $filename .= ".csv";
    $filePath = "static/" . $filename;
    $query_link = "https://maps.googleapis.com/maps/api/place/textsearch/json?pagetoken=" . urlencode($token) . "&key=" . $api_key;
    $json_data = file_get_contents($query_link);
    $obj = json_decode($json_data, true);
    $fdatas = $obj['results'];

    if ($fdatas)
    {
        $list = array(
            array(
                "Business Names",
                'Business Types',
                'Global Code',
                'Phone No.',
                'Website',
                'GoogleMapUrl',
                'Address',
                'Address1',
                'Address2',
                'Address3',
                'Address4',
                'Address5'
            ) ,
        );
        foreach ($fdatas as $dt)
        {
            $address = $dt['formatted_address'];
            $BNames = $dt['name'];
            $place_id = $dt['place_id'];
            $btype = $dt['types'][0];
            $code = $dt['plus_code']['compound_code'];
            $map_url = 'https://www.google.com/maps/place/?q=place_id:' . $place_id;
            $place_details_url = 'https://maps.googleapis.com/maps/api/place/details/json?place_id=' . $place_id . '&fields=formatted_phone_number,website&key=' . $api_key;
            $place_data = file_get_contents($place_details_url);
            $place_obj = json_decode($place_data, true);
            $place_details = $place_obj['result'];
            $phone = null;
            $website = null;
            if ($place_details)
            {
                if (array_key_exists("formatted_phone_number", $place_details))
                {
                    $phone = $place_details['formatted_phone_number'];
                }
                if (array_key_exists("website", $place_details))
                {
                    $website = $place_details['website'];
                }

            }
            $address_s = explode(",", $address);
            for ($i = count($address_s);$i < 5;$i++)
            {
                array_push($address_s, null);
            }
            $temp_data = array(
                $BNames,
                $btype,
                $code,
                $phone,
                $website,
                $map_url,
                $address,
                $address_s[0],
                $address_s[1],
                $address_s[2],
                $address_s[3],
                $address_s[4]
            );
            array_push($list, $temp_data);
        }
        $fp = fopen($filePath, 'w');

        foreach ($list as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        if (array_key_exists("next_page_token", $obj))
        {
            $nextp = $obj['next_page_token'];
            echo '<br>__/' . $filePath . '__<input type="hidden" id="token" name="token" value="' . $nextp . '"><br><button class="btn btn-success center-block" onclick="myFunction()" id="nextpage">Page ';
            exit();
        }
        else
        {
            echo '<br>__/' . $filePath . '__';
            exit();
        }
    }

}
else
{
    echo "Some Problem Ocured";
    exit();
}
?>