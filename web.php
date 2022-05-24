<?php

function getConteudo($xpath, $tag, $attributeName, $attribute, $returnAttributeName, $returnAttribute){

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

    $siteConteudo = file_get_contents('https://www.techtudo.com.br/noticias/plantao.html', false, stream_context_create($arrContextOptions));
    
    libxml_use_internal_errors(true);
    
    $dom = new DOMDocument();
    $dom->loadHTML($siteConteudo);

    $xpath = new DOMXPath($dom);

    return $xpath;
}

function getNews($site, $url){
    $xpath = getContents($url);
    $techtudo = [];

    for($i = 0;$i < 3; $i++)
    {
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

        switch($site){
            case 'techtudo': 
                $techtudo[] = getConteudo($xpath, $tag, $attributeName, $attribute, $returnAttributeName, $returnAttribute);
                break;

        }
        
    }

    return $techtudo;
}

echo '<pre>';
print_r(getNews('techtudo', 'https://www.techtudo.com.br/noticias/plantao.html'));
echo '</pre>';