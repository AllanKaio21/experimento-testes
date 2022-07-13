<?php

namespace allankaio\giitester;


use yii\db\Exception;
use yiibr\brvalidator\CpfValidator;
use yii\validators\Validator;
class helpers
{
    /** The function of checking if an attribute contains a custom rule.
     * @param $atribute
     * @param $modelR
     * @return [bool, rule]
     */
    public function isDefaultValidator($atribute, $modelR)
    {
        $validator = Validator::$builtInValidators;
        foreach ($modelR as $rule) {
            if(is_array($rule[0])){
                if(in_array($atribute, $rule[0])){
                    if(!array_key_exists($rule[1], $validator) && $rule[1] != CpfValidator::className()){
                        return [false, $rule[1]];
                    }
                }
            }else{
                if($atribute == $rule[0]){
                    if(!array_key_exists($rule[1], $validator) && $rule[1] != CpfValidator::className()){
                        return [false, $rule[1]];
                    }
                }
            }
        }
        return [true, null];
    }

    /** Test execution order function.
     * @param $atribute
     * @return bool ->
     * @return integer -> Number of executable tests created.
     */
    public function testerExecOrder()
    {
        $testepath = \Yii::$app->params['testepath'];
        $order = $this->testerOrder();
        $body = ['#! /bin/bash','echo -e "[1] Run all tests  [2] Run a specific test "','echo -n "=> "','read resp','case "$resp" in','   1|1|"")'];
        $nameFile = 'runtests';
        $file = "../$nameFile";
        $break = "\n";
        foreach ($order as $name){
            $dir = "../$testepath/functional/$name";
            if(file_exists($dir)){
                $cmd = '      "vendor/bin/codecept" "run" "'.$testepath.'/functional/'.$name.'" "--steps"';
                array_push($body, $cmd);
            }
        }
        $orderDel = array_reverse($order);
        foreach ($orderDel as $name){
            $dir = "../$testepath/functional/$name"."Delete";
            if(file_exists($dir)){
                $cmd = '      "vendor/bin/codecept" "run" "'.$testepath.'/functional/'.$name.'Delete" "--steps"';
                array_push($body, $cmd);
            }
        }
        array_push($body, '   ;;','   2|2)','      echo -e "Enter test path"','      echo -n "=> "','      read resp','      "vendor/bin/codecept" "run" "$resp"','   ;;', 'esac');
        if (file_exists($file)) {
            $teste = file($file);
            $teste;
            unlink($file);
            $open = fopen($file, "a+");
            $i = 0;
            foreach($body as $line){
                if($i!=0)
                    fwrite($open, $break);
                fwrite($open, $line);
                $i++;
            }
            fclose($open);
            chmod ($file, 0755);
            $i--;
        } else {
            fopen($file, "a+");
            return $this->testerExecOrder();;
        }
        return [true, $i];
    }

    /** Function that checks if a data and reference.
     * @param $keys
     * @param $column
     * @return [boll, string]
     */
    public function isKey($keys, $column){
        if(count($keys)<1) return [false, null];
        foreach ($keys as $key){
            if($key[1] == $column){
                return [true, $key[0]];
            }
        }
        return [false, null];
    }

    /** Function that renames tests delete to the correct order of execution.
     * @return string
     */
    public function testerRename($direct){
        $array = $this->testerOrder();
        $msg = 'File not changed.';
        if(file_exists($direct)){
            for($i = 0; $i < count($array); $i++) {
                $direct2 = $direct.'/Test'.$array[$i].'Cest.php';
                if(file_exists($direct2)) {
                    for($j = 0; $j < count($array); $j++) {
                        $direct4 = $direct . '/' . ($j+1) . 'Test' . $array[$i] . 'Cest.php';
                        if (file_exists($direct4)) unlink($direct4);
                    }
                    $direct3 = $direct . '/' . ($i+1) . 'Test' . $array[$i] . 'Cest.php';
                    $resp = rename($direct2, $direct3);
                    if(!file_exists($direct3))$i--;
                    $msg = 'File changed successfully.';
                }else{
                    $msg = 'File not found. File: '.$direct.'/Test'.$array[$i].'Cest.php';
                }
            }
        }else{
            return['File not found. File: '.$direct];
        }
        return [true, $msg];
    }

    /** Function that renames tests delete to the correct order of execution.
     * @return string
     */
    public function testerRenameDelete($direct){
        $array = $this->testerOrder();
        $array = array_reverse($array);
        $msg = 'File not changed.';
        if(file_exists($direct)){
            for($i = 0; $i < count($array); $i++) {
                $direct2 = $direct.'/Test'.$array[$i].'DeleteCest.php';
                if(file_exists($direct2)) {
                    for($j = 0; $j < count($array); $j++) {
                        $direct4 = $direct . '/' . ($j+1) . 'Test' . $array[$i] . 'DeleteCest.php';
                        if (file_exists($direct4)) unlink($direct4);
                    }
                    $direct3 = $direct . '/' . ($i+1) . 'Test' . $array[$i] . 'DeleteCest.php';
                    $resp = rename($direct2, $direct3);
                    if(!file_exists($direct3))$i--;
                    $msg = 'File changed successfully.';
                }else{
                    $msg = 'File not found. File: '.$direct.'/Test'.$array[$i].'DeleteCest.php';
                }
            }
        }else{
            return['File not found. File: '.$direct];
        }
        return [true, $msg];
    }

    /** Test execution order function.
     * @return string
     */
    public function testerOrder()
    {
        $i = 0;
        $testepath = \Yii::$app->params['testepath'];
        $file = "../$testepath/ordertests.txt";
        if (file_exists($file)) {
            $open = fopen($file, "r");
            while (!feof($open)) {
                $array[$i] = fgets($open);
                $i++;
            }
            fclose($open);
        } else {
            fopen($file, "a+");
            return $this->testerOrder();
        }
        $arrayOrd = [];
        for($i = 0; $i < count($array)-1; $i++){
            $array2[$i] = explode('=>', $array[$i], 2);
            $arrayOrd[$i] = $array2[$i][0];
        }
        if(count($arrayOrd) < 2) return $arrayOrd;
        for($i = 0; $i < count($arrayOrd)-1; $i++){
            for($j = 0; $j < count($array2); $j++)
                if($this->auxOrd($arrayOrd[$i], $arrayOrd[$j]."\r\n", $array2)){
                    $pos = array_search($arrayOrd[$i], $arrayOrd);
                    $pos2 = array_search($arrayOrd[$j], $arrayOrd);
                    if($pos < $pos2) {
                        $aux = $arrayOrd[$pos];
                        $arrayOrd[$pos] = $arrayOrd[$pos2];
                        $arrayOrd[$pos2] = $aux;
                    }
                }
        }
        $arrayOrd = array_unique($arrayOrd);
        $i=0;
        foreach ($arrayOrd as $date) {
            $arrayOrder[$i] = $date;
            $i++;
        }
        return $arrayOrder;
    }

    /** Function that checks whether one value points to another.
     * @param $date
     * @param $date2
     * @param $array
     * @return bool
     */
    public function auxOrd($date, $date2, $array){
        for($i = 0; $i < count($array); $i++){
            if($array[$i][0]==$date && $array[$i][1]==$date2){
                return true;
            }
        }
        return false;
    }

    /** Test execution order function.
     * @param $atribute
     * @return bool
     */
    public function testerSave($atribute)
    {
        $testepath = \Yii::$app->params['testepath'];
        $file = "../$testepath/ordertests.txt";
        for($i=1;$i<count($atribute);$i++) {
            if (file_exists($file)) {
                $open = fopen($file, "a+");
                while (!feof($open)) {
                    $line = fgets($open);
                    if ($line == $atribute[$i]) {
                        return false;
                    }
                }
                fwrite($open, $atribute[$i]);
                fclose($open);
                chmod($file, 0755);
            } else {
                fopen($file, "a+");
                return $this->testerSave($atribute);
            }
        }
        return true;
    }

    /** The function of checking if an attribute is In Range.
     * @param $atribute
     * @param $modelR
     * @return [bool, range]
     */
    public function isInRange($atribute, $modelR)
    {
        for ($i = 0; $i < count($modelR); $i++) {
            if (array_key_exists("range", $modelR[$i])) {
                for ($j = 0; $j < count($modelR[$i][0]); $j++) {
                    if ($modelR[$i][0][$j] == $atribute) {
                        return [true, $modelR[$i]['range'][rand(0,count($modelR[$i]['range'])-1)]];
                    }
                }
            }
        }
        return [false, null];
    }

    /** The function to check if an attribute has a custom message.
     * @param $rule
     * @param $modelR
     * @param $
     * @return [bool, range]
     */
    public function isCustomMessage($modelR, $rule, $name)
    {
        foreach ($modelR as $rules) {
            if($rules[1] == $rule) {
                if(is_array($rules[0])) {
                    if(in_array($name, $rules[0])) {
                        if (array_key_exists("message", $rules)) {
                            return [true, $rules['message']];
                        }
                    }
                }
            }
        }
        return [false, null];
    }

    /** The function of checking if an attribute format contains.
     * @param $atribute
     * @param $modelR
     * @return [bool, string]
     */
    public function isFormatDate($atribute, $modelR)
    {
        for ($i = 0; $i < count($modelR); $i++) {
            if (array_key_exists("format", $modelR[$i])) {
                for ($j = 0; $j < count($modelR[$i][0]); $j++) {
                    if ($modelR[$i][0][$j] == $atribute) {
                        return [true, $modelR[$i]['format']];
                    }
                }
            }
        }
        return [false, null];
    }

    /** The function to check if there is a specific rule.
     * @param $atribute
     * @param $modelR
     * @param $rule
     * @return bool
     */
    public function isThisRule($atribute, $modelR, $rule)
    {
        for ($i = 0; $i < count($modelR); $i++) {
            if (in_array($rule, $modelR[$i])) {
                for ($j = 0; $j < count($modelR[$i][0]); $j++) {
                    if ($modelR[$i][0][$j] == $atribute) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /** Valid cnpj generator function.
     * @return string
     */
    public static function genCnpjValid() {
        $n1 = rand(0, 9);
        $n2 = rand(0, 9);
        $n3 = rand(0, 9);
        $n4 = rand(0, 9);
        $n5 = rand(0, 9);
        $n6 = rand(0, 9);
        $n7 = rand(0, 9);
        $n8 = rand(0, 9);
        $n9 = 0;
        $n10 = 0;
        $n11 = 0;
        $n12 = 1;
        $d1 = $n12 * 2 + $n11 * 3 + $n10 * 4 + $n9 * 5 + $n8 * 6 + $n7 * 7 + $n6 * 8 + $n5 * 9 + $n4 * 2 + $n3 * 3 + $n2 * 4 + $n1 * 5;
        $d1 = 11 - (self::mod($d1, 11) );
        if ($d1 >= 10) {
            $d1 = 0;
        }
        $d2 = $d1 * 2 + $n12 * 3 + $n11 * 4 + $n10 * 5 + $n9 * 6 + $n8 * 7 + $n7 * 8 + $n6 * 9 + $n5 * 2 + $n4 * 3 + $n3 * 4 + $n2 * 5 + $n1 * 6;
        $d2 = 11 - (self::mod($d2, 11) );
        if ($d2 >= 10) {
            $d2 = 0;
        }
        $cnpj = '' . $n1 . $n2 . $n3 . $n4 . $n5 . $n6 . $n7 . $n8 . $n9 . $n10 . $n11 . $n12 . $d1 . $d2;
        return $cnpj;
    }

    /** Check if it's CNPJ.
     * @param $atribute -> Attribute to be verified.
     * @param $modelR -> Model Rules.
     * @return bool
     */
    public function cnpjField($atribute, $modelR){
        foreach ($modelR as $rules) {
            if($rules[0]===$atribute) {
                foreach ($rules as $date) {
                    if ($date === CnpjValidator::className()) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /** Valid cpf generator function.
     * @return string
     */
    public function genCpfValid(){
        $n1 = rand(0, 9);
        $n2 = rand(0, 9);
        $n3 = rand(0, 9);
        $n4 = rand(0, 9);
        $n5 = rand(0, 9);
        $n6 = rand(0, 9);
        $n7 = rand(0, 9);
        $n8 = rand(0, 9);
        $n9 = rand(0, 9);
        $d1 = $n9 * 2 + $n8 * 3 + $n7 * 4 + $n6 * 5 + $n5 * 6 + $n4 * 7 + $n3 * 8 + $n2 * 9 + $n1 * 10;
        $d1 = 11 - (self::mod($d1, 11) );
        if ($d1 >= 10) {
            $d1 = 0;
        }
        $d2 = $d1 * 2 + $n9 * 3 + $n8 * 4 + $n7 * 5 + $n6 * 6 + $n5 * 7 + $n4 * 8 + $n3 * 9 + $n2 * 10 + $n1 * 11;
        $d2 = 11 - (self::mod($d2, 11) );
        if ($d2 >= 10) {
            $d2 = 0;
        }
        $cpf = '' . $n1 . $n2 . $n3 . $n4 . $n5 . $n6 . $n7 . $n8 . $n9 . $d1 . $d2;
        return $cpf;
    }

    /** Check if it's CPF.
     * @param $atribute -> Attribute to be verified.
     * @param $modelR -> Model Rules.
     * @return bool
     */
    public function cpfField($atribute, $modelR){
        foreach ($modelR as $rules) {
            if(is_array($rules[0])){
                if(in_array($atribute, $rules[0]))
                        if ($rules[1] === CpfValidator::className())
                            return true;
            } elseif ($rules[0] === $atribute) {
                    if ($rules[1] === CpfValidator::className())
                        return true;
            }
        }
        return false;
    }

    /** String/Integer generator function between minimum and maximum ranges.
     * @param $min
     * @param $max
     * @return string
     */
    public function genMinOrMax($min, $max, $type){
        $value = '';
        if($type != "integer") {
            if ($max == null) {
                for ($i = 0; $i < $min; $i++) {
                    $value = $value . '' . rand(0, 9);
                }
            } else {
                if ($max > 15)
                    $value = "Form Tester 001";
                else {
                    for ($i = 0; $i < $max; $i++) {
                        $value = $value . '' . rand(0, 9);
                    }
                }
            }
        }else{
            if ($max == null) {
                $value = rand($min, $min + 1);
            } else {
                $value = rand($max - 1, $max);
            }
        }
        return $value;
    }

    /** String/Integer generator function between minimum and maximum ranges Error.
     * @param $min
     * @param $max
     * @return string
     */
    public function genMinOrMaxFail($min, $max, $type){
        $value = '';
        if($type != "integer") {
            if ($max == null) {
                for ($i = 0; $i < $min+1; $i++) {
                    $value = $value . '' . rand(0, 9);
                }
            } else {
                for ($i = 0; $i < $max+1; $i++) {
                    $value = $value . '' . rand(0, 9);
                }
            }
        }else{
            if ($max == null) {
                $value = rand($min - 2, $min - 1);
            } else {
                $value = rand($max, $max + 1);
            }
        }
        return $value;
    }

    /** The function of checking if an attribute is Min or Max.
     * @param $atribute
     * @param $modelR
     * @return [bool, min, max]
     */
    public function isMinOrMax($atribute, $modelR)
    {
        for ($i = 0; $i < count($modelR); $i++) {
            if (array_key_exists("max", $modelR[$i])) {
                for ($j = 0; $j < count($modelR[$i][0]); $j++) {
                    if ($modelR[$i][0][$j] == $atribute) {
                        return [true, null, $modelR[$i]['max']];
                    }
                }
            }elseif (array_key_exists("min", $modelR[$i])) {
                for ($j = 0; $j < count($modelR[$i][0]); $j++) {
                    if ($modelR[$i][0][$j] == $atribute) {
                        return [true, $modelR[$i]['min'], null];
                    }
                }
            }
        }
        return [false, null, null];
    }

    /** The function of checking if an attribute is email.
     * @param $atribute
     * @param $modelR
     * @return bool
     */
    public function emailField($atribute, $modelR)
    {
        foreach ($modelR as $rules) {
            if ($rules[1] == 'email') {
                foreach ($rules[0] as $date) {
                    if ($date == $atribute) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /** The function of checking if an attribute is unique.
     * @param $atribute
     * @param $modelR
     * @return bool
     */
    public function uniqueField($atribute, $modelR)
    {
        foreach ($modelR as $rules) {
            if ($rules[1] == 'unique') {
                foreach ($rules[0] as $date) {
                    if ($date == $atribute) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
    private static function mod($dividendo, $divisor) {
        return round($dividendo - (floor($dividendo / $divisor) * $divisor));
    }
}