<?php
namespace Utils;
/**
 * Description of StringUtils
 *
 * @author vlada
 */
class StringUtils {
    /** 
     * metoda pro vygenerování náhodného stringu 
     * $lengthPassword int délka řetězce
     * $ENGLISCH_ALPHABET - použité znaky
     */
    public static function GenerateRandomString($lengthPassword = 10,$ENGLISCH_ALPHABET = ENGLISCH_ALPHABET) {
        $randomString = "";
        
        $length = strlen($ENGLISCH_ALPHABET);
        for ($i = 0; $i < $lengthPassword; $i++) {
            $randomString .= $ENGLISCH_ALPHABET[mt_rand(0, $length - 1)];
        }
        return $randomString;
    }
    /**
     * 
     *  */
    public static function EncodeString($string)
    {
        return $string;
    }
    /** metoda pro vygenerování seo stringu 
     * $string vstupní string
     *      */
    public static function SeoString($url,$separator = '-' ) {
        //$url = transliterator_transliterate("Any-Latin; NFD; [:Nonspacing Mark:] Remove; NFC; [:Punctuation:] Remove; Lower();", $url);
        $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
        $special_cases = array( '&' => 'and', "'" => '');
        $url = mb_strtolower( trim( $url ), 'UTF-8' );
        
        $url = str_replace( array_keys($special_cases), array_values( $special_cases), $url );
        $url = preg_replace( $accents_regex, '$1', htmlentities( $url, ENT_QUOTES, 'UTF-8' ) );
        $url = preg_replace("/[^a-z0-9]/u", "$separator", $url);
        $url = preg_replace("/[$separator]+/u", "$separator", $url);
        return $url;
       
    }
    
    public static  function ContainsString($string,$searchValue)
    {
        return (strpos($string, $searchValue) !== FALSE);
    }
    
    public static function StartWidth($string,$searchValue)
    {
         return $string === "" || strrpos($string, $searchValue, -strlen($string)) !== FALSE;
    }
    public static function EndWith($string, $searchValue) {
    // search forward starting from end minus needle length characters
    return $searchValue === "" || (($temp = strlen($string) - strlen($searchValue)) >= 0 && strpos($string, $searchValue, $temp) !== FALSE);
}
    
    public static function RemoveString($string,$searchValue)
    {
        return str_replace($searchValue, "", $string);
    }
    
    public static function HashString($value)
    {
        return MD5(SHA1($value));
    }
    
    public static function RemoveLastChar($string,$removeChars = 1)
    {
        return substr($string,0,-1*$removeChars);
    }
    
    public static function GetLastWord($string)
    {
        $pieces = explode(' ', $string);
        $lastWord = array_pop($pieces);
        return $lastWord;
    }
    
    public static function RemoveLastWord($string)
    {
        $word = self::GetLastWord($string);
        return self::RemoveString($string, $word);
    }
    
    public static function Utf8Convert($string,$sourceEncode)
    {
        return iconv($sourceEncode,'utf-8',$string); 
    }
    
    public static function PriceFormat($price,$format,$locale)
    {
        setlocale(LC_MONETARY ,$locale);
        return \Utils\StringUtils::Utf8Convert(money_format($format, $price),"cp1250");
    }
    public static function NormalizeUrl($url)
    {
        if (!self::StartWidth($url, SERVER_PROTOCOL))
        {
            $url = SERVER_PROTOCOL.$url;
        }
        if (!self::EndWith($url, "/"))
        {
            $url = $url."/";
        }
        return $url;
    }
    
}
