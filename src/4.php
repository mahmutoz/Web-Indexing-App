<?php
$keywords = "";
$freq = "";
$keys = "";
$keywordsResult = "";
$latestKeys = "";
$counter = array();
$latest = array();
$skor = 0;
$allUrls = array();
$urls = array();
$ikinciDerece = array();
$ucuncuDerece = array();
$totalUrls = array();
$sayac = true;
$wholeUrls = array();
$yaz = array();

$allUrls[] = isset($_POST['url']) ? $_POST['url'] : '';
$sites = isset($_POST['sites']) ? nl2br($_POST['sites']) : '';
$sites = array_map('trim', array_filter(explode('<br />', $sites)));
$allUrls = array_merge($allUrls, $sites);

foreach ( $allUrls as $key => $val ) {
    if ( !empty($val) ) {
        $ikinciDerece[] = urlBul($val);
    }
}

foreach ( $ikinciDerece as $key => $val ) {
    foreach ( $val as $ikey => $ival ) {
        if ( !empty($ival) ) {
            $ucuncuDerece[] = urlBul($ival);
        }
    }
}

function urlBul($urls)
{
    $tempArr = array();
    $x = "";
    global $totalUrls;
    $flag = 0;

    $html = file_get_html($urls);
    if ( !empty($html) ) {
        foreach ( $html->find('a') as $element ) {
            $x .= $element->href . " ";
        }
    }

    $tempArr = array_map('trim', array_filter(explode(' ', $x)));;

    foreach ( $tempArr as $key => $val ) {
        if ( (strpos($val, "http") === false) || $val == $urls ) {
            unset($tempArr[$key]);
        }
    }

    $tempArr = urlDuzenle($tempArr, $totalUrls, $urls);
    $tempArr = array_slice($tempArr, 6, 3);

    foreach ( $tempArr as $key => $val )
        array_push($totalUrls, $val);

    // Key'leri baştan sıralama
    return array_values($tempArr);
}

function urlDuzenle($tempArr, $totalUrls, $urls)
{
    foreach ( $tempArr as $key => $value ) {
        foreach ( $totalUrls as $tkey => $tval ) {
            if ( ($tval == $value) || ($value == "") || ($value == $urls) )
                unset($tempArr[$key]);
        }
    }
    return array_values($tempArr);
}

$w = array_merge($allUrls, $ikinciDerece, $ucuncuDerece);
$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($w));
foreach ( $it as $v ) {
    $wholeUrls[] = $v;
}

function anahtarKelimeAnalizi($gelenUrl)
{
    global $sayac;
    global $allUrls;
//    if ( $sayac == true ) {
    $latest[0] = islemler($allUrls[0]);
//    }
    $latest[1] = islemler($gelenUrl);
    return $latest;
}

function islemler($gelenUrl)
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

    if ( !empty($gelenUrl) ) {
        $result = file_get_html($gelenUrl)->plaintext;
        $keywords = file_get_html($gelenUrl);
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
function parcala($n1, $n2)
{
    return $n1 = array_map('trim', array_filter(explode(' ', $n2)));
}

function karakterSil($s1, $s2)
{
    return array_diff($s1, $s2);
}

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