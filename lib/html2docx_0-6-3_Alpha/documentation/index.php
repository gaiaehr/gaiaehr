<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="content-language" content="en">
<meta name="description" content="htmltodocx converter documentation">
<title>htmltodocx converter documentation</title>
<link rel="stylesheet" type="text/css" href="h2d_style.css">
</head>
<body>
  <div class="container">
    <div class="control"><a href="documentation_download_word.php">Download this page as a Word document</a> (Created using the docxtohtml converter!) </div>
    
    <?php
    
      require_once '../simplehtmldom/simple_html_dom.php';
    
      ob_start(); //start output buffering
      require_once ('documentation.html.php'); //all output goes to buffer
      $buf = ob_get_contents(); //assign buffer to a variable
      ob_end_clean();
  
      $html_dom = new simple_html_dom();
      $html_dom->load($buf);

      $toc = '';
      $last_level = 0;

      foreach($html_dom->find('h1,h2,h3,h4,h5,h6') as $h){
        $inner_text = trim($h->innertext);
        $id =  str_replace(' ','_',$inner_text);
        $id = preg_replace('%[^a-zA-Z0-9_-]%', '', $id);
        
        $h->id= $id; // add id attribute so we can jump to this element
        $level = intval($h->tag[1]);

        if($level > $last_level)
          $toc .= "<ul>";
        else{
          $toc .= str_repeat('</li></ul>', $last_level - $level);
          $toc .= '</li>';
        }
        $toc .= "<li><a href='#{$id}'>{$inner_text}</a>";
        $last_level = $level;
      }

      $toc .= str_repeat('</li></ul>', $last_level);
      
      // Replace placeholder with this:
      $html_dom->find('div[id=word-table-of-contents]', 0)->innertext = $toc;
      $html_with_toc = $html_dom->save();
      
      $html_dom->clear(); 
      unset($html_dom);
      
      print $html_with_toc;
    ?>
    
  </div>
</body>
</html>