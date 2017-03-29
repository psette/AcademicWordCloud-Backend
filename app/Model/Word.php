<?php

/**
 * Words from Paper
 */
class Word
{
    /**
     * Word in Paper.
     *
     * @var string
     */
    var $identifier;

    /**
     * The frequency of the word in the Paper.
     *
     * @var int
     */
    var $frequencyOfWord;

    /**
     * All of the papers that the word appears in. 
     *
     * @var papers
     */
    var $papers;

    /**
     * A Map of every paper with the word and the frequency of the word.
     *
     * @var paperWordFrequency
     */
    var $paperWordFrequency;

    /**
     * The Word constructor.
     */
    function __construct(){
        $this->papers = [];
        $this->paperWordFrequency = [];
    }

    function parseWord(){
        //call parse function on paper to get array of words

        //parse the string -> turn into array of words 
        //$comp = preg_split('/ +/', $var);
        $paperName = "Paper Identifer goes hereeee";

        $s="Your name is one of the most important words for you to know. You hear your name whenever
a person says it. We like it if it is whispered to you in a nice tone for something you have done.
We love hearing the sound of our name repeated over and over.";
      //  $arr = explode(" ", $s);
        $wordList = preg_split('/ +/', $s);

        // print_r( array_count_values(str_word_count($s, 1)) );
//         echo '<pre>';
//   print_r( array_count_values(str_word_count($s, 1)) );
// echo '</pre>';

        $wordFrequencyInPaper = array_count_values(str_word_count($s, 1)) ;
        $words = [];
        foreach($wordFrequencyInPaper as $nugget => $value){
            $word = new Word();
            echo "FIRST" . $nugget;
            echo "SECOND" . $value;
            $word->$identifier= $nugget;
            $word->frequencyOfWord = $value;
            array_push($words, $word);
            //make a Word 
            //set identifier
            //set frequency 
        }

        echo '<pre>';
        print_r( $words );
        echo '</pre>';
       //  $words = [];
        
//         foreach($wordList as $nugget){
//             if(array_key_exists($nugget, $words)){
//                 $words[$nugget] = $words[$nugget] +1;

//         }else{
//             $newWord = new Word();
//             $newWord -> $identifier = $nugget;
//             $newWord -> $frequencyOfWord = 1;
//             $words[$newWord -> $word] = $words[$newWord -> $frequencyOfWord]; 
//         }
//         }
       
//         echo '<pre>';
//   print_r( $wordList );  
// echo '</pre>';
           


        }
        
        
        
       
        // start going through array. 
        //if word in map-> increase frequency
        //if word not in map-> add word and set freq to 1 

        //put word objects in here
     //   $words = [];

        //see if the *word* in wordList exists in words as word object
        //if not, add to words, word = key, val = freq
        //if it is, increment freq val



        // if (is_null($papers))
        // {
        //     return $words;
        // }

        // foreach ($papers as $paper)
        // {
        //     foreach ($paper->frequentwords as $frequentLyric)
        //     {
        //         $lyric = null;

        //         if (array_key_exists($frequentLyric->stringValue, $words))
        //         {
        //             $lyric = $words[$frequentLyric->stringValue];
        //         }
        //         else
        //         {
        //             $lyric = new Lyric();
        //             $lyric->stringValue = $frequentLyric->stringValue;
        //             $lyric->identifier = $frequentLyric->stringValue;
        //         }

        //         $lyric->frequency = $lyric->frequency + $frequentLyric->frequency;
        //         $lyric->papers->attach($paper);

        //         $words[$frequentLyric->stringValue] = $lyric;
        //     }
        // }

        // usort($words, ["Lyric", "compareByFrequency"]);

        // return $words;

    
}
