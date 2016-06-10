<?php
/**
 *
 * Program/Function Name:      NumToWords
 * Filename:                   numtowords.php
 * Description:                Convert and return integer number into words. Range: 100 Billion
 * Usage:          
 *             
 *             num_to_words($integer_value);
 *             
 *     
 * Author/Programmer:          Arjon Jason A. Castro 
 * Email Add:                  ajcastro29@gmail.com
 * Contact No.:                +639099258893
 * Address:                    096 Purok 2 Bañag, Daraga, Albay, 4501, Philippines
 * Date:                       April 20, 2013
 */

function NumWord($digit)
{
    $word = array(
        'Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 
        'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'
    );

    return $word[$digit];
}
function SecNumWord($sec_num)
{
    $sec_num_word = array(
        'Twenty', 'Thirty', 'Fourty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'
    );

    return $sec_num_word[$sec_num - 2];
}
function ToWords($value)
{
    $num_in_words = "";
    if (isset($value[2]) and $value[0] != 0) {
        $num_in_words .= NumWord($value[0]) . " Hundred ";
    }
    if (isset($value[1]) and $value[strlen($value) - 2] > 1) {
        $num_in_words .= SecNumWord($value[strlen($value) - 2]) . " ";
    }
    if (isset($value[1]) and $value[strlen($value) - 2] == 1) {
        $num_in_words .= NumWord(IntVal($value[strlen($value) - 2] . $value[strlen($value) - 1]));
    }
    if ((!(isset($value[1]) and $value[strlen($value) - 2] == 1) and $value[strlen($value) - 1] != 0) or strlen($value) == 1) {
        $num_in_words .= NumWord($value[strlen($value) - 1]) . "";
    }
    
    return $num_in_words;
}
/**
 * Convert integer into words.
 *
 * @param $num integer Integer number to be converted
 */
function num_to_words($num)
{
    list($num) = explode('.', $num . ".0");
    $string       = strrev("$num");
    $num_in_words = "";
    $place        = "";
    
    for ($i = 0; $i < strlen($string); $i += 3) {
        if ($i >= 9) {
            $place = 'Billion';
        } else if ($i >= 6) {
            $place = 'Million';
        } else if ($i >= 3) {
            $place = 'Thousand';
        }
        if ((isset($string[$i + 2]) and $string[$i + 2] != 0) or (isset($string[$i + 1]) and $string[$i + 1] != 0) or (isset($string[$i]) and $string[$i] != 0)) {
            $num_in_words = ToWords(strrev(substr($string, $i, 3))) . " $place " . $num_in_words;
        }
    }
    
    return rtrim($num_in_words);
}

/**
 * Convert money into words.
 *
 * @param  float  $amount
 * @param  string  $currency
 * @param  boolean $includeOnlySuffix
 * @return string
 */
function money_to_words($amount, $currency = 'Pesos', $includeOnlySuffix = true)
{
    list($whole, $cents) = explode('.', $amount . ".00");
    
    if (strlen($cents) < 2)
        $cents .= '0';
    else
        $cents = substr($cents, 0, 2);
    
    $centLabel = ($cents > 1) ? 'Centavos' : 'Centavo';
    $wholeStr  = num_to_words($whole);
    $centsStr  = (intval($cents) > 0) ? 'And ' . num_to_words($cents) . " {$centLabel} " : '';
    
    return "{$wholeStr} {$currency} {$centsStr}" . ($includeOnlySuffix ? 'Only' : '');
}

// // Sample Run: Number to Words From 100 up to 1500
// for($i=0;$i<=100;$i++)
//  echo "$i -> ".num_to_words("$i")."\n";        
