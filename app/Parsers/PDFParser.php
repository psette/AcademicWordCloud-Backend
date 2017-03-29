<?php

include_once dirname(__FILE__) . '/../../vendor/autoload.php';

class PDFParser
{
    public static function getTextFromPDF($pdf)
    {
        $cookies_file = dirname(__FILE__). '/../tmp/ieee-pdf-access-cookies.txt';
        $parser = new \Smalot\PdfParser\Parser();

        $curl = curl_init();
        $headers[] = "Accept: */*";
        $headers[] = "Connection: Keep-Alive";
        $headers[] = "Content-type: application/x-www-form-urlencoded;charset=UTF-8";


        curl_setopt($curl, CURLOPT_HTTPHEADER,  $headers);
        curl_setopt($curl, CURLOPT_URL, $pdf);
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

        $tmpfile = tempnam(sys_get_temp_dir(), "temp_PDF");

        $handle = fopen($tmpfile, "w");
        fwrite($handle, $result);
        try {

                $pdf = $parser->parseFile("wtf_TEMP2.pdf");

        } catch (Exception $e) {

            return "PDF not parsed";

        }

        fclose($handle);
        unlink($tmpfile);

        $text = $pdf->getText();
        return $text;
    }
    /**
    * Scrapes the DOM of IEEE pdf url to get the actual pdf link.
    *
    * @param Link $Link The pdf link given by the IEEE .
    *
    * @return array Returns the String representation of the link.
    */
    public static function getPDFLinkFromIEEE($link)
    {
        $cookies_file = dirname(__FILE__). '/../tmp/ieee-main-access-cookies.txt';
        $curl = curl_init();
        $headers[] = "Accept: */*";
        $headers[] = "Connection: Keep-Alive";
        $headers[] = "Content-type: application/x-www-form-urlencoded;charset=UTF-8";


        curl_setopt($curl, CURLOPT_HTTPHEADER,  $headers);
        curl_setopt($curl, CURLOPT_URL, $link);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookies_file);   // Cookie management.
        curl_setopt($curl, CURLOPT_COOKIEFILE, $cookies_file);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17');
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);

        $page = curl_exec($curl);

        $locateFrame = explode('.pdf', $page);
        $filePath = explode('"', $locateFrame[0]);
        $lastElement = sizeof($filePath) - 1;

        $pdfPath = $filePath[$lastElement];

        if ( $pdfPath[0] != 'h') {
            $pdfPath = substr($pdfPath, 0, 4) . 'x' . substr($pdfPath, 4);
           $pdf ="http://ieeexplore.ieee.org" . $pdfPath;
        } else {
            $pdf =  $pdfPath;
        }
        $pdf = $pdf . ".pdf";

        return  $pdf;
    }
}
