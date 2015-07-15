1. INTRO:

pdfformfiller 1.0-alpha is a command line utility for filling in Adobe PDF Forms.

For WWW: A php example, that uses fast Unix pipes (and no disk write permissions required on the host), is provided.

It support UTF-8 (Russian, etc.).

Proper escaping functions are provided in the php example.

Well known pdftk utility can be used for filling in PDF Forms.
However, I was not able to get the version pdftk1.4 to work with UTF-8.
It's XFDF format support UTF-8 encoding, and assumes Adobe uses an UTF-8 font by default. Whereas, Adobe Readers (at least upto version X) do not, and UTF-8 text is entered by pdftk but is not shown in its form until user clicks on the form and edits it.

In PdfFormFiller, you can use the -font option to specify a UTF-8 font to use to fill in the forms to resolve this issue.

Also, our fields input file format is much simpler then XFDF of pdftk that requires XML parsing.

Based on the Belgian iText library v. 5.2.0, http://www.itextpdf.com/


1.1 BUSSNESS CASE or INTENDED USAGE:

If you need to automate paperwork via WWW. 

   I. You get a adobe PDF document, for example:
	a. Download a form from government or corporate website.
	b. Print into PDF file a form in another format with almost any software such as Miscrosoft Word.
	c. Scan an existing OLD pre-digital age form printed on a paper.

   II. Get a full versions of Adobe Acrobat (not just the free Reader), and draw PDF Forms on pdf document 
	you have obtained in step I. 
	Name your Form fields (if there are too many of them and default names do not make sense. Otherwise, 
	php script will be more cryptic).
	
	Make sure to save the document with "Extended Features" enabled in Acrobat. 
	Otherwise, the pdf form will not be savable: http://smallbusiness.chron.com/make-fillable-pdf-forms-savable-29822.html      


   III. Create any html page on server that has a <form action="generate_pdf.php">
		<input name="file" type="hidden" value="fill_pdf_my_file"></input>
		<input name="ADDRESS" type="textfield"></input>  ...  

</form> with <input>'s for all PDF FORM fields you are filling.

   IV. Add filename (from step II) to the list of allowed pdf files, line 9 of generate_pdf.php: 
	$allowed_files = array('pdf_file_1', 'pdf_file_2');

   V. Copy and rename function fill_pdf_file_1($pipe) to, for exapmle, "fill_pdf_my_file", and modify it, as your form requires.

   VI. Add the renamed function name (from step V.) to the switch of line  of generate_pdf.php: 
	switch ($_REQUEST['file']) {

   VII. TO DEBUG: Look at the 'list', 'env_vars', "env_vars", 'dump_fields' that you can pass via the $_REQUEST['pff_op']:
		line 24 of generate_pdf.php:
			if ($_REQUEST['pff_op'] == 'list')
	
	PdfFormFiller.java generate_pdf.php integration does pretty good job of passing errors reported by iText library 
	back to the www browser.

   VIII. On some operating systems, for example FreeBSD, open_proc() is not run in a shell and thus a PATH with 'usr/local/bin' 
	 and CWD are not set. You'll have to set them manually, as on lines 69-72 of generate_pdf.php.

   IX.	generate_pdf.php will send back to the browser the pdf file from step II. filled with input value of step III.



2. Compilation and USAGE:

For now, you need JAVA installed on the server, then you either to compile PdfFormFiller.java with Netbeans or javac.

Make sure that iText library, itext-xtra-5.x.0.jar and itextpdf-5.x.0.jar, are accessible to JAVA,
e.g. these are placed in the "lib" subfolder of the current folder.

Get latest ones from: https://sourceforge.net/projects/itext/files/iText/

Compile PdfFormFiller.java

Then from the command line you give command (to see usage help):

java -jar pdfformfiller.jar



2.1 WWW USAGE:
See "generate_pdf.php" for an example of clever usage of this utility from web. 
It uses fast Unix pipes and no disk write permissions required on the host.


3. TODO:
Ideally, we should compile iText and my code with GCJ - the native code java compiler, and package it
with common unix distributions, as the famous pdftk utility was. However, I did not succeed to compile new iText
from the first try.
If we compile it with GCJ, I think the command line will be the fastest php-java interface:
http://stackoverflow.com/questions/7364057/php-and-java-integration-within-codeigniter
http://stackoverflow.com/questions/2128619/run-java-class-file-from-php-script-on-a-website
