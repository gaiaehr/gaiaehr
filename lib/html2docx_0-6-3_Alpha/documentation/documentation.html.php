<?php
require_once('../htmltodocx_converter/h2d_htmlconverter.php');
?>
<h1>HTML to docx Converter</h1>

<h2>Table of contents</h2>
<div id="word-table-of-contents" class="table-of-contents">
Placeholder for table of contents.
</div>

<h2>Project website</h2>

<p><a href="http://htmltodocx.codeplex.com/">Project website on Codeplex</a></p>

<h2>Introduction</h2>
<p>This converter converts HTML into Word documents (docx format). The code is written in PHP and works with PHPWord. It is particularly designed to take simple HTML - the kind of HTML typically produced by WYSIWYG editors (such as <a href="http://www.tinymce.com">TinyMCE</a>) or that might be included in a blog - and converts this into a docx Word document. The intention is that the resulting Word document is in a form that is familiar to most people who use Word documents and therefore  easy to use. It is not intended as a way to recreate complex web page layout in a Word document.</p>
<p>This converter requires <a href="http://simplehtmldom.sourceforge.net/">SimpleHTMLDom</a> and <a href="http://phpword.codeplex.com/">PHPWord</a> to function - copies of both of which are included in the release here (although you might want to download the latest versions of these).</p>
<p><strong>Note:</strong> this is alpha code, and so it is still possible that changes could be made that are not compatible with code you may have built on top of it.</p>


<h2>Setting up</h2>

<p>See <a href="show_raw_file.php?file=example.php">example.php</a> for an example of how to use this converter. <a href="../example.php">Download the Word document created by this example</a>. Note you do not need to include the documentation directory on your live production server.</p>


<h2>Creating a "style sheet"</h2>

<p>This converter uses a style sheet in the form of a php array which allows you to assign <a href="http://phpword.codeplex.com/">PHPWord</a> styles to HTML elements, classes and inline styles. <a href="show_raw_file.php?file=example_files/styles.inc">This is an example style sheet used to create the Word document at example.php</a>, and <a href="show_raw_file.php?file=docs_styles.inc">this is the style sheet used to convert this page to a Word document</a>.</p>

<p>Note that all the attribute-values in these arrays are <a href="http://phpword.codeplex.com/">PHPWord</a> attribute-values - you should refer to the PHPWord documentation for more information on these - see: <a href="../phpword/PHPWord_Docs_0.6.2.docx">PHPWord_Docs_0.6.2.docx</a> in the phpword directory.</p>

<p>Measurements are generally in TWIPs (as described in the PHPWord documentation). You can add a width in pixels directly onto an HTML cell tag, e.g.: &lt;td width=200> and this will be converted into TWIPs automatically - converting at 15TWIPs per pixel. Image widths and heights are specified in pixels for PHPWord.</p>

<h2>Elements</h2>

<p><a href="http://htmltodocx.codeplex.com/">htmltodocx</a> currently processes the following elements:</p>

<?php
  $allowed_children = htmltodocx_html_allowed_children($tag = NULL);
  
  $html = '<table><tbody><tr><th width="80">Element</th><th width="560">Allowed child elements</th></tr>';
  
  foreach ($allowed_children as $element => $child_elements) {
    $html .= '<tr><td class="heading"><code>' . $element . '</code></td><td><code>' . implode(', ', $child_elements) . '</code></td></tr>';    
  }
  
  $html .= '</tbody></table>';
  print $html;
?>

<h3>Inheritance</h3>

<p>Attributes which can be inherited follow <a href="http://www.w3.org/TR/CSS21/propidx.html">standard CSS recommendations for inheritance</a>. See the function <code>htmltodocx_inheritable_props()</code>. The following attributes can be inherited:</p>

<?php
  $inheritable_props = htmltodocx_inheritable_props();
  $html = '';
  foreach ($inheritable_props as $property) {
    $html .= '<code>' . $property . '</code><br>'; 
  }
  $html .= '';
  
  print $html;
?>
 
<h2>Special characters</h2>

<p>All HTML entities <a href="http://www.w3schools.com/tags/ref_entities.asp">listed here</a> are supported. For example: &copy; (&amp;copy;), &pound; (&amp;pound;), &reg; (&amp;reg;).</p> 

<h2>Language support</h2>

<p>Note PHPWord does not support utf8 character encoding. The version of PHPWord shipped with the docxtohtml converter is patched to deal with this: all instances of utf8_encode() have been replaced with a new function - utf8encode_dummy() - which simply returns its string argument. <a href="http://phpword.codeplex.com/discussions/261365">Discussion</a>.</p>

<p>For example:</p>

<h3>Russian</h3>
<p>привет!</p>

<h3>Bengali</h3>
<p>আরে!</p>

<h2>Tables</h2>

<p>Note that tables cannot be nested in PHPWord. Nested tables will be displayed as text.</p>

<p>Line 236, Document.php in PHPWord, changed to public function so that lists can be used in a table cell. Note that in any case lists are not currently enabled in htmltodocx converter and "pseudo lists" are used instead - which can have styling applied within each list element.</p>

<h2>Images</h2>

<p>You can align images left, middle, or right, but you don't appear to be able to butt them together on one line with PHPWord - they will appear on different lines. A way around this could be to insert them into cells in a table.</p>

<img class="small-image-size" style="float: left" src="cat.jpg"><img class="small-image-size" style="float: none;" src="dog.jpg"><img class="small-image-size" style="float: right" src="rabbit.jpg">