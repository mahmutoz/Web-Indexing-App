<?php

$url = isset($_POST['url']) ? $_POST['url'] : '';

$result = "";
if ($url)
    $result = file_get_html($url)->plaintext;

// Kelimeleri parçalayıp kelimelerin önündeki/arkasındaki boşlukları temizleme
$words = array_map('trim',array_filter(explode(' ',$result)));

//içerikteki alınmasını istemediğimiz karakterler
$deleteElem = array( '', ' ', '-', '.', ',', '@', '&', '%', '+', '*', '/', '=', '<', '>', '...', '..',
    '!','^','(',')','{','}','[' ,']','_','?',';',':','ve','veya','bu','şu','o','bir','şey','için','ise',
    'ile','mi','de','da','ama','mı','mü','mu','çok','az','a','able','am','is','are','the','an','at','as',
    'away','be','before','bit','but','by','can','can\'t','did','didn\'t','do','does','doesn\'t','don\'t',
    'during','got','go','has','he','his','him','how','I','if','in','into','is','isn\'t','it','its','me',
    'my','no','not','of','one','or','out','other','per','so','to','us','up','we','will','what','who','why',
    'yes','you','your','and','on','were','was','The','with','for','from','had','that','also','there','than',
    'have','ago','this','been','after','our');
$newWords = array_diff($words,$deleteElem);


foreach ($newWords as $key=>$val){
    if(strlen($val) < 2 || strlen($val) > 20){
        unset($newWords[$key]);
    }
}

// Frekans hesaplana
$freq = array_count_values($newWords);

// Frekansları büyükten küçüğe sıralama
arsort($freq);