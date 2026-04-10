<?php
$files=['ui','admin','pagination'];
$flatten=function(array $items,string $prefix='') use (&$flatten): array {
    $out=[];
    foreach($items as $key=>$value){
        $full=$prefix===''?$key:$prefix.'.'.$key;
        if(is_array($value)){
            $out=array_merge($out,$flatten($value,$full));
        } else {
            $out[]=$full;
        }
    }
    return $out;
};
foreach($files as $file){
    $uk=include __DIR__."/resources/lang/uk/{$file}.php";
    $en=include __DIR__."/resources/lang/en/{$file}.php";
    $ukKeys=$flatten($uk);
    $enKeys=$flatten($en);
    $missingEn=array_values(array_diff($ukKeys,$enKeys));
    $missingUk=array_values(array_diff($enKeys,$ukKeys));
    echo $file,': missing_in_en=',count($missingEn),', missing_in_uk=',count($missingUk),PHP_EOL;
}
