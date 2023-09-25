<?php

$filename = "Luật Cán bộ, công chức và Luật Viên chức sửa đổi 2019 số 52_2019_QH14 ban hành ngày 25_11_2019.html";    
$fp1 = fopen($filename, "r");//open file in read mode    

$content1 = fread($fp1, filesize($filename));//read file    
fclose($fp1);

  $html = <<<EOF
  $content1
  EOF;

  //pattern attributes
  $patternAttributes = '/^(.*<div id="right-doc-info">)/s';
  $textAttributes = preg_replace($patternAttributes, '', $html);
  
  $patternAttributes2 = '/(<div class="h6 text-center m-t-20 _700">.+)/ims';
  $textAttributes2 = preg_replace($patternAttributes2, '', $textAttributes);
  
  $patternAttributes3 = '/^(.*<h1 class="tieu_de_vb">Luật Cán bộ, công chức và Luật Viên chức sửa đổi 2019<\/h1>)/s';
  $textAttributes3 = preg_replace($patternAttributes3, '', $textAttributes2);

  $textAttributes4 = strip_tags($textAttributes3, "<b><li>");
  
  //Attributes
  $dom = new DOMDocument;
  $dom->loadHTML($textAttributes4);
  
  $pattern = '/<b>(.*?)<\/b>/s';
  $newLi = preg_replace($pattern,'', $textAttributes4);
  $dom2 = new DOMDocument;
  $dom2->loadHTML($newLi);
  
  
  $keyAttri = $dom->getElementsByTagName('b');
  $valueAttri = $dom2->getElementsByTagName('li');

  $keyAttriArr = [];
  $valueAttriArr = [];

  foreach($keyAttri as $b){
      $key = $b->nodeValue;
      array_push($keyAttriArr,$key);
  }

  foreach($valueAttri as $li){
      $value =$li->nodeValue;
      array_push($valueAttriArr,$value);
  }

  $arrayAttri = array_combine($keyAttriArr,$valueAttriArr);

    //pattern content
    $patternContent = '/^(.*?<a name="dieu_1" id="dieu_1">)/s';
    $textContent = preg_replace($patternContent, '', $html);

    $patternContent2 = '/(<p style="margin-top:6.0pt"><i>&nbsp;.+)/ims';
    $textContent2 = preg_replace($patternContent2, '', $textContent);

    $textContent3 = strip_tags($textContent2, "<b><i>");
    $patternContent3 = '/<\/b><b>/s';

    $textContent4 = preg_replace($patternContent3,'', $textContent3);

    //Content
    $dom3 = new DOMDocument;
    $dom3->loadHTML($textContent4);
    $keyContent = $dom3->getElementsByTagName('b');
    $keyContentArr = [];

    foreach($keyContent as $b){
      $key = array("content"=>$b->nodeValue);
      array_push($keyContentArr, $key);
    }

    function deletecontent()
      {
        return '';
      }

      $textContent5 = preg_replace_callback('/<b>(.*?)<\/b>/s',"deletecontent",$textContent4);
      $valueContentArr = explode("”.",$textContent5);

      if(!empty($keyContentArr)){
        for($i = 0; $i < count($keyContentArr); $i++){
            $keyContentArr[$i]["children"] = $valueContentArr[$i];
        }
      }

      $MergeArr = array_merge($arrayAttri, $keyContentArr);
  
   //convert to json
  $JsonData = json_encode($MergeArr, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  // echo $JsonData;


  //file creation method 1
  // $myFile = fopen("JsonData.json", "a");
  // fwrite($myFile, $JsonData);
  // fclose($myFile);

  //file creation method 2
  $myFile = 'JsonDatabase.json';
  file_put_contents($myFile, $JsonData);
?>
