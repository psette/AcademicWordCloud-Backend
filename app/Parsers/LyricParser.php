<?php

include_once __DIR__  . '/Parser.php';
include_once __DIR__  . '/../Model/Lyric.php';

/**
 * Parser to parse Lyric objects.
 */
class LyricParser implements Parser
{

    /**
    * An assortment of common words that should be removed from frequent lyrics.
    *
    * @var array
    */
    public static $commonWords = array('a','able','about','above','abroad','according','accordingly','across','actually','adj','after','afterwards','again','against','ago','ahead','ain\'t','all','allow','allows','almost','alone','along','alongside','already','also','although','always','am','amid','amidst','among','amongst','an','and','another','any','anybody','anyhow','anyone','anything','anyway','anyways','anywhere','apart','appear','appreciate','appropriate','are','aren\'t','around','as','a\'s','aside','ask','asking','associated','at','available','away','awfully','b','back','backward','backwards','be','became','because','become','becomes','becoming','been','before','beforehand','begin','behind','being','believe','below','beside','besides','best','better','between','beyond','both','brief','but','by','c','came','can','cannot','cant','can\'t','caption','cause','causes','certain','certainly','changes','clearly','c\'mon','co','co.','com','come','comes','concerning','consequently','consider','considering','contain','containing','contains','corresponding','could','couldn\'t','course','c\'s','currently','d','dare','daren\'t','definitely','described','despite','did','didn\'t','different','directly','do','does','doesn\'t','doing','done','don\'t','down','downwards','during','e','each','edu','eg','eight','eighty','either','else','elsewhere','end','ending','enough','entirely','especially','et','etc','even','ever','evermore','every','everybody','everyone','everything','everywhere','ex','exactly','example','except','f','fairly','far','farther','few','fewer','fifth','first','five','followed','following','follows','for','forever','former','formerly','forth','forward','found','four','from','further','furthermore','g','get','gets','getting','given','gives','go','goes','going','gone','got','gotten','greetings','h','had','hadn\'t','half','happens','hardly','has','hasn\'t','have','haven\'t','having','he','he\'d','he\'ll','hello','help','hence','her','here','hereafter','hereby','herein','here\'s','hereupon','hers','herself','he\'s','hi','him','himself','his','hither','hopefully','how','howbeit','however','hundred','i','i\'d','ie','if','ignored','i\'ll','i\'m','immediate','in','inasmuch','inc','inc.','indeed','indicate','indicated','indicates','inner','inside','insofar','instead','into','inward','is','isn\'t','it','it\'d','it\'ll','its','it\'s','itself','i\'ve','j','just','k','keep','keeps','kept','know','known','knows','l','last','lately','later','latter','latterly','least','less','lest','let','let\'s','like','liked','likely','likewise','little','look','looking','looks','low','lower','ltd','m','made','mainly','make','makes','many','may','maybe','mayn\'t','me','mean','meantime','meanwhile','merely','might','mightn\'t','mine','minus','miss','more','moreover','most','mostly','mr','mrs','much','must','mustn\'t','my','myself','n','name','namely','nd','near','nearly','necessary','need','needn\'t','needs','neither','never','neverf','neverless','nevertheless','new','next','nine','ninety','no','nobody','non','none','nonetheless','noone','no-one','nor','normally','not','nothing','notwithstanding','novel','now','nowhere','o','obviously','of','off','often','oh','ok','okay','old','on','once','one','ones','one\'s','only','onto','opposite','or','other','others','otherwise','ought','oughtn\'t','our','ours','ourselves','out','outside','over','overall','own','p','particular','particularly','past','per','perhaps','placed','please','plus','possible','presumably','probably','provided','provides','q','que','quite','qv','r','rather','rd','re','really','reasonably','recent','recently','regarding','regardless','regards','relatively','respectively','right','round','s','said','same','saw','say','saying','says','second','secondly','see','seeing','seem','seemed','seeming','seems','seen','self','selves','sensible','sent','serious','seriously','seven','several','shall','shan\'t','she','she\'d','she\'ll','she\'s','should','shouldn\'t','since','six','so','some','somebody','someday','somehow','someone','something','sometime','sometimes','somewhat','somewhere','soon','sorry','specified','specify','specifying','still','sub','such','sup','sure','t','take','taken','taking','tell','tends','th','than','thank','thanks','thanx','that','that\'ll','thats','that\'s','that\'ve','the','their','theirs','them','themselves','then','thence','there','thereafter','thereby','there\'d','therefore','therein','there\'ll','there\'re','theres','there\'s','thereupon','there\'ve','these','they','they\'d','they\'ll','they\'re','they\'ve','thing','things','think','third','thirty','this','thorough','thoroughly','those','though','three','through','throughout','thru','thus','till','to','together','too','took','toward','towards','tried','tries','truly','try','trying','t\'s','twice','two','u','un','under','underneath','undoing','unfortunately','unless','unlike','unlikely','until','unto','up','upon','upwards','us','use','used','useful','uses','using','usually','v','value','various','versus','very','via','viz','vs','w','want','wants','was','wasn\'t','way','we','we\'d','welcome','well','we\'ll','went','were','we\'re','weren\'t','we\'ve','what','whatever','what\'ll','what\'s','what\'ve','when','whence','whenever','where','whereafter','whereas','whereby','wherein','where\'s','whereupon','wherever','whether','which','whichever','while','whilst','whither','who','who\'d','whoever','whole','who\'ll','whom','whomever','who\'s','whose','why','will','willing','wish','with','within','without','wonder','won\'t','would','wouldn\'t','x','y','yes','yet','you','you\'d','you\'ll','your','you\'re','yours','yourself','yourselves','you\'ve','z','zero');

    /**
    * The Tracks the lyrics to be parsed belong to.
    *
    * @var array of Strings
    */
    var $tracks;

    /**
     * Parses the lyrics string and returns Lyrics.
     *
     * @param string $lyrics The lyrics for a track.
     *
     * @return array Returns an array of Lyrics from $json.
     */
    function parseObject($lyrics)
    {
        // Split $lyrics into words separated by non-letters (except ' and -).
        $words = preg_replace('/[^a-z0-9]+/i', ' ', $lyrics);
        $words = explode(" ", $words);
        // Sanitize words into their root words.
        $strippedWords = $this->stripStopWords($words);
        $lyrics = [];
        foreach ($strippedWords as $word)
        {
            $word = strtolower($word);
            
            $lyric = null;
            if (array_key_exists($word, $lyrics))
            {
                $lyric = $lyrics[$word];
            }
            else
            {
                $lyric = new Lyric();
                $lyric->stringValue = $word;
                $lyric->identifier = $word;
                if (!is_null($this->tracks))
                {
                    $lyric->tracks = $this->tracks;
                }
            }
            $lyric->frequency = $lyric->frequency + 1;
            $lyrics[$word] = $lyric;
        }
        usort($lyrics, ["Lyric", "compareByFrequency"]);

        return $lyrics;
    }

    /**
     * Serializes a Lyric to JSON.
     *
     * @param Lyric $lyric Lyric to be serialized.
     *
     * @return array Returns the JSON representation of the Lyric.
     */
    function serializeObject($lyric)
    {
        $json = [
             "stringValue" => $lyric->stringValue,
             "frequency" => $lyric->frequency,
        ];
        return $json;
    }

    /**
     * Strips stop words from $input.
     *
     * @param array Array of words.
     *
     * @return array $input words with the stop words removed
     *
     */
    public function stripStopWords($input)
    {
        return preg_replace('/\b('.implode('|', self::$commonWords).')\b/','',$input);
    }

    /*
     * Helper function to instantiate the phpMorphy stemmer object
     *
     * @param none
     * @return phpMorphy object
     *
     */
    private function instantiateMorpher()
    {
        // Specify the path to the directory with dictionaries
        $dictionary_dir = '/../dicts';
        // Specify the language for which we use the dictionary.
        $the_lang = 'en_EN';

        // Specify options
        // Note: We should transition to PHPMORPHY_STORAGE_MEM or PHPMORPHY_STORAGE_SHM in actual implementation for speed considerations
        $options = array('storage' => PHPMORPHY_STORAGE_FILE);

        $dict_bundle = new phpMorphy_FilesBundle($dictionary_dir, 'eng');

        $morphy  = new phpMorphy($dict_bundle, $options);
        return $morphy;
    }
}
?>
