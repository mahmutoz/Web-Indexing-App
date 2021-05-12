<?php
$keywords = "";
$freq = "";
$keys = "";
$keywordsResult = "";
$flag = "";
$latestKeys = "";
$counter = array();
$latest = array();
$skors = array();

for ( $i = 0; $i < 2; $i++) {
    $url = isset($_POST['url'][$i]) ? $_POST['url'][$i] : '';
    $latest[$i] = islemler($url);
}

$skors = skorHesapla($latest);

function skorHesapla($latest){
    $temp1 = array();
    $temp2 = array();
    $total = 1;
    $sum = 1.1;

    for($i=0; $i < count($latest); $i++)
    $total += array_sum($latest[$i]);

    $temp1 = $latest[0];
    $temp2 = $latest[1];

    foreach ($temp1 as $key=>$val)
        foreach ($temp2 as $tkey=>$tval){
            if($key == $tkey){
             $sum = $val + $tval + $sum;
            }
        }
    return ($sum/$total) * 100;
}

function islemler($url)
{
    $title = "";
    $h1 = "";
    $h2 = "";
    $h3 = "";
    $arr1 = "";
    $arr2 = "";
    $arr3 = "";
    $arr4 = "";
    $words = "";
    $result = "";
    $temp = array();

    if ( !empty($url) ) {
        $result = file_get_html($url)->plaintext;
        $keywords = file_get_html($url);
    }

    if ( !empty($keywords) ) {
        foreach ( $keywords->find('title') as $element )
            $arr1 = $element->plaintext;
        foreach ( $keywords->find('h1') as $element )
            $arr2 = $element->plaintext;
        foreach ( $keywords->find('h2') as $element )
            $arr3 .= $element->plaintext . " ";
        foreach ( $keywords->find('h3') as $element )
            $arr4 .= $element->plaintext . " ";
    }

    $words = parcala($words, $result);
    $title = parcala($title, $arr1);
    $h1 = parcala($h1, $arr2);
    $h2 = parcala($h2, $arr3);
    $h3 = parcala($h3, $arr4);

// Dizileri birleştirme
    $keys = array_merge($title, $h1, $h2, $h3);

//içerikteki olmasını istemediğimiz karakterler
    $deleteElem = array( '', ' ', '-', '.', ',', '@', '&', '%', '+', '*', '/', '=', '<', '>', '...', '..',
        '!','^','(',')','{','}','[' ,']','_','?',';',':','ve','veya','bu','şu','o','bir','şey','için','ise',
        'ile','mi','de','da','ama','mı','mü','mu','çok','az','a','able','am','is','are','the','an','at','as',
        'away','be','before','bit','but','by','can','can\'t','did','didn\'t','do','does','doesn\'t','don\'t',
        'during','got','go','has','he','his','him','how','I','if','in','into','is','isn\'t','it','its','me',
        'my','no','not','of','one','or','out','other','per','so','to','us','up','we','will','what','who','why',
        'yes','you','your','and','on','were','was','The','with','for','from','had','that','also','there','than',
        'have','ago','this','been','after','our');

    $words = karakterSil($words, $deleteElem);
    $keys = karakterSil($keys, $deleteElem);

    foreach ( $words as $key => $val ) {
        if ( strlen($val) < 2 || strlen($val) > 20 ) {
            unset($words[$key]);
        }
    }

// Frekans hesaplana
    $freq = array_count_values($words);

// Frekansları büyükten küçüğe sıralama
    arsort($freq);
    $freq2 = $freq;

// dizinin ilk 100 elamanını alma
    $freq = array_slice($freq, 0, 100);

// array_flip fonksiyonundaki aynı anahtar değerlerin çarpışmasını önlemek için
    $freq = array_map(function ($item) {
        return $item * rand(0, 1000);
    }, $freq);

//anahtar ve değerleri yer değiştirme
    $freq = array_flip($freq);

// 2 dizide de aynı olan elemanları bulma
    $keywordsResult = array_intersect($freq, $keys);

    if ( count($keywordsResult) < 3 )
        $keywordsResult = array_slice($freq, 0, 5);

    foreach ( $keywordsResult as $kkey => $kval )
        foreach ( $freq2 as $fkey => $fval )
            if ( $kval == $fkey )
                $temp[$kval] = $fval;
    return $temp;
}

// Kelimeleri parçalayıp kelimelerin önündeki/arkasındaki boşlukları temizleme
function parcala($n1, $n2){return $n1 = array_map('trim', array_filter(explode(' ', $n2)));}

function karakterSil($s1, $s2){ return array_diff($s1, $s2);}