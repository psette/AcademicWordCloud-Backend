<?php

include_once dirname(__FILE__) . '/../../vendor/autoload.php';

class PDFParser
{
    public static function getTextFromPDF($pdf)
    {
        // Parse pdf file and build necessary objects.
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile('https://www.acm.org/membership/NIC.pdf');

        //NOTE: Not sure why IEEE PDFS are all returning errors
        //$pdf = $parser->parseFile('http://ieeexplore.ieee.org/ielx5/4130641/4130642/04130757.pdf');

        $text = $pdf->getText();
        echo $text;
    }
}
