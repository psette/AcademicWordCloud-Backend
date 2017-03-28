<?php

include_once dirname(__FILE__) . '/../../vendor/autoload.php';

class PDFParser
{
    public static function getTextFromPDF($pdf)
    {
        $cookies_file = dirname(__FILE__). '/../tmp/cookies.txt';

        $curl = curl_init();
        $headers[] = "Accept: */*";
        $headers[] = "Connection: Keep-Alive";
        $headers[] = "Content-type: application/x-www-form-urlencoded;charset=UTF-8";


        curl_setopt($curl, CURLOPT_HTTPHEADER,  $headers);
        curl_setopt($curl, CURLOPT_URL, 'http://ieeexplore.ieee.org/ielx5/4130641/4130642/04130757.pdf');
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookies_file);   // Cookie management.
        curl_setopt($curl, CURLOPT_COOKIEFILE, $cookies_file);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17');
        curl_setopt($curl, CURLOPT_AUTOREFERER, true); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);

        $result = curl_exec($curl);
        curl_close($curl);

        $fp = fopen("temp_file.pdf", "w");
        fwrite($fp, $result);

        // // Parse pdf file and build necessary objects.
        // $doc = new DOMDocument();
        // $doc->loadHTMLFile($pdf);
        // echo $doc->saveHTML();

        // $parser = new \Smalot\PdfParser\Parser();
        // $pdf = $parser->parseFile('https://www.acm.org/membership/NIC.pdf');

        // //NOTE: Not sure why IEEE PDFS are all returning errors
        // //$pdf = $parser->parseFile('http://ieeexplore.ieee.org/ielx5/4130641/4130642/04130757.pdf');

        // $text = $pdf->getText();
        // echo $text;
    }
}
