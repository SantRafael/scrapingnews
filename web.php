<?php

function getConteudo($site, $xpath, $tag, $attributeName, $attribute, $returnAttributeName, $returnAttribute, $conteudo){

    $query = $xpath->query("//$tag");
    $i = 0;
    $j = 0;
    foreach($query as $link){
        if(strpos($link->getAttribute("$attributeName"), "$attribute") === 0){

            if(($site == 'tecmundo') && ($j < 32) &&  ($tag == 'img'|| $tag == 'a')){
                $j++;
                continue;
            }

            if($returnAttribute == 'text')
                $conteudo[$i]["$returnAttributeName"] = $link->textContent;
            else
                $conteudo[$i]["$returnAttributeName"] = $link->getAttribute("$returnAttribute");

            $i++;

        }
    
    }

    return $conteudo;
}

function getContents($url){

    $arrContextOptions=array(
        "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
        ),
    );      

    $siteConteudo = file_get_contents($url, false, stream_context_create($arrContextOptions));

    libxml_use_internal_errors(true);
    
    $dom = new DOMDocument();
    $dom->loadHTML($siteConteudo);

    $xpath = new DOMXPath($dom);

    return $xpath;
}

function getElements($site, $i){
    if($site == 'techtudo'){
        switch($i){
            case 0:
                return ['tag' => 'div', 'attributeName' => 'class', 'attribute' => 'feed-post-body-title', 'returnAttributeName' => 'texto', 'returnAttribute' => 'text'];
            case 1:
                return ['tag' => 'a', 'attributeName' => 'class', 'attribute' => 'feed-post-link', 'returnAttributeName' => 'link', 'returnAttribute' => 'href'];
            case 2:
                return ['tag' => 'img', 'attributeName' => 'class', 'attribute' => 'bstn-fd-picture-image', 'returnAttributeName' => 'img', 'returnAttribute' => 'src'];
        }
    } elseif($site ==  'infomoney'){
        switch($i){
            case 0:
                return ['tag' => 'h3', 'attributeName' => 'class', 'attribute' => 'article-card__headline', 'returnAttributeName' => 'texto', 'returnAttribute' => 'text'];
            case 1:
                return ['tag' => 'a', 'attributeName' => 'class', 'attribute' => 'article-card__headline-link', 'returnAttributeName' => 'link', 'returnAttribute' => 'href'];
            case 2:
                return ['tag' => 'img', 'attributeName' => 'class', 'attribute' => 'aspect-ratio__image', 'returnAttributeName' => 'img', 'returnAttribute' => 'src'];
        }
    } elseif($site == 'tecmundo'){
        switch($i){
            case 0:
                return ['tag' => 'h3', 'attributeName' => 'class', 'attribute' => 'tec--card__title', 'returnAttributeName' => 'texto', 'returnAttribute' => 'text'];
            case 1:
                return ['tag' => 'a', 'attributeName' => 'class', 'attribute' => 'tec--card__title__link', 'returnAttributeName' => 'link', 'returnAttribute' => 'href'];
            case 2:
                return ['tag' => 'img', 'attributeName' => 'class', 'attribute' => 'tec--card__thumb__image', 'returnAttributeName' => 'img', 'returnAttribute' => 'data-src'];
        }
    }
}

function getNews($site, $url){
    $xpath = getContents($url);
    $conteudo = [];

    for($i = 0;$i < 3; $i++){
        $elements = getElements($site, $i);
        $conteudo = getConteudo($site, $xpath, $elements['tag'], $elements['attributeName'], $elements['attribute'], $elements['returnAttributeName'], $elements['returnAttribute'], $conteudo);        
    }

    return $conteudo;
}


$news['techtudo'] = getNews('techtudo', 'https://www.techtudo.com.br/noticias/plantao.html');
$news['infomoney'] = getNews('infomoney', 'https://www.infomoney.com.br/economia/');
$news['tecmundo'] = getNews('tecmundo', 'https://www.tecmundo.com.br/noticias');

echo '<pre>';
print_r($news);
echo '</pre>';