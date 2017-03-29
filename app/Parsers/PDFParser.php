<?php

include_once dirname(__FILE__) . '/../../vendor/autoload.php';

class PDFParser
{
    /**
     * Extracts text from a PDF.
     *
     * @param PDF $Pdf The PDF to be extracted.
     *
     * @return array Returns the text representation of the PDF.
     */
    public static function getTextFromPDF($pdf)
    {
        // Parse pdf file and build necessary objects.
        $parser = new \Smalot\PdfParser\Parser();

        $pdf = $parser->parseFile($pdf);
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

        //$link = $link . '&tag=1'
        $page = file_get_contents($link);
        //echo htmlentities($page);

        //locate the embed html by separating the html string into two parts
        $locateEmbedArray = explode('<embed id="plugin', $page);

        //locate the beginning string of the src of the pdf
        $srcArray = explode('src="', $locateEmbedArray[1]);

        //srcArray[1] should contain http://ieeexplore.ieee.org/ielx2/597/5570/00213554.pdf?tp=&arnumber=213554&isnumber=5570" plus more

        $src = explode('"', $srcArray[1]);

        return $src[0];
        echo "hello <br>";
    }
}
