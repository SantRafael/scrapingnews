<?php

function getConteudo($xpath, $tag, $attributeName, $attribute, $returnAttributeName, $returnAttribute, $conteudo){

    $query = $xpath->query("//$tag");
    $i = 0;
    foreach($query as $link){

        if(strpos($link->getAttribute("$attributeName"), "$attribute") === 0){

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
                $tag                 = 'div';
                $attributeName       = 'class';
                $attribute           = 'feed-post-body-title';
                $returnAttributeName = 'texto';
                $returnAttribute     = 'text';
                break;
            case 1:
                $tag                 = 'a';
                $attributeName       = 'class';
                $attribute           = 'feed-post-link';
                $returnAttributeName = 'link';
                $returnAttribute     = 'href';
                break;
            case 2:
                $tag                 = 'img';
                $attributeName       = 'class';
                $attribute           = 'bstn-fd-picture-image';
                $returnAttributeName = 'img';
                $returnAttribute     = 'src';
                break;                                
        }
    } elseif('infomoney'){
        switch($i){
            case 0:
                $tag                 = 'h3';
                $attributeName       = 'class';
                $attribute           = 'article-card__headline';
                $returnAttributeName = 'texto';
                $returnAttribute     = 'text';
                break;
            case 1:
                $tag                 = 'a';
                $attributeName       = 'class';
                $attribute           = 'article-card__headline-link';
                $returnAttributeName = 'link';
                $returnAttribute     = 'href';
                break;
            case 2:
                $tag                 = 'img';
                $attributeName       = 'class';
                $attribute           = 'aspect-ratio__image';
                $returnAttributeName = 'img';
                $returnAttribute     = 'src';
                break;                                
        }
    }

    $elements = [
        'tag'                 => $tag,
        'attributeName'       => $attributeName,
        'attribute'           => $attribute,
        'returnAttributeName' => $returnAttributeName,
        'returnAttribute'     => $returnAttribute       
    ];

    return $elements;

}

function getNews($site, $url){
    $xpath = getContents($url);
    $conteudo = [];

    for($i = 0;$i < 3; $i++){
        $elements = getElements($site, $i);
        $conteudo = getConteudo($xpath, $elements['tag'], $elements['attributeName'], $elements['attribute'], $elements['returnAttributeName'], $elements['returnAttribute'], $conteudo);        
    }

    return $conteudo;
}

echo '<pre>';
print_r(getNews('techtudo', 'https://www.techtudo.com.br/noticias/plantao.html'));
echo '</pre>';

echo '<pre>';
print_r(getNews('infomoney', 'https://www.infomoney.com.br/economia/'));
echo '</pre>';