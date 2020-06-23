<?php

	$headers = array("TokenID", "name", "description","image", "meta_url", "external_url", "animal", "colorvalue");

	//Open/create the file
	$fh = fopen("cryptostamp2.csv","w");

	//Create the headers
	fputcsv($fh, $headers);
	fclose($fh);

	//In the loop, the CS2 api data is read using the tokenid 0-239999 and the values are logged in the cryptostamp.csv file.
    for($counter = 0; $counter <=239999; $counter++) {

    //URL to send the request to
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://crypto.post.at/CS2/meta/'.$counter);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    //Execute the request and fetch the response.Reset cURL. Check for Errors
    $output = curl_exec($ch);

    curl_reset($ch);
    $ch = curl_close($ch);

    if($output === FALSE){
        echo "cURL ERROR: ".curl_error($ch);
    }


    $jsondata = json_decode($output, true);

    echo $counter;
    echo ",";

    $data = array(
        "TokenID" => $counter,
        "name" => $jsondata['name'],
        "description" => $jsondata['description'],
        "image" => $jsondata['image'],
        "meta_url" => 'https://crypto.post.at/CS2/meta/'.$counter,
        "external_url" => $jsondata['external_url'],
        "animal" => $jsondata['attributes'][1]['value'],
        "colorvalue" => $jsondata['attributes'][0]['value'],
    );

    $fh = fopen("cryptostamp2.csv","a");
    fputcsv($fh, $data,",",'"');
    fclose($fh);
}
?>