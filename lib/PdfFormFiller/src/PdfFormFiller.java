/**
 * Created by ernesto on 9/17/14.
 */

import java.io.*;
import java.io.OutputStream;
import java.util.*;
import java.util.Map;
import java.util.Scanner;
import com.itextpdf.text.pdf.*;
import com.itextpdf.text.*;

class WrongParamsExeption extends Exception {}

public class PdfFormFiller {
    static Boolean verbose;

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args){
        String document, operation = "fill", fields = null, font = null, output = null;
        Boolean flatten = false;
        verbose = false;

        try {
            if (args.length < 1)
                throw new WrongParamsExeption();
            document = args[0];
            for(int i=1; i<args.length; i++){
                if (args[i].equals("-v")){
                    verbose = true;
                }else if (args[i].equals("-flatten")){
                    flatten = true;
                }else if (args[i].equals("-l")){
                    operation = "list";
                }else if (args[i].equals("-f")){
                    if (i + 1 >= args.length)
                        throw new WrongParamsExeption();
                    fields = args[++i];
                }else if (args[i].equals("-font")){
                    if (i + 1 >= args.length)
                        throw new WrongParamsExeption();
                    font = args[++i];
                }else if (i + 1 == args.length){
                    output = args[i];
                } else{
                    throw new WrongParamsExeption();
                }
            }

            fillPDFFile(document, output, fields, font, operation, flatten, verbose);

        } catch (WrongParamsExeption e){
            if (e.getMessage() != null)
                System.out.println(e.getMessage());
            System.out.println("USAGE: pdfformfiller document.pdf [ -l ] [ -v ] [ -f fields_filename ] [ -font font_file ] [ -flatten] [ output.pdf ]\n\n" +
                            "    document.pdf - name of source pdf file (required).\n" +
                            "    -l - only list availible fields in document.pdf.\n" +
                            "    -v - verbose. Use to debug the fields_filename file. \n" +
                            "    -f fields_filename - name of file with the list of fields values to apply to document.pdf. \n" +
                            "                         if ommited, stdin is used.\n" +
                            "    -font font_file - font to use. Needed UTF-8 support, e.g. cyrillic and non-latin alphabets.\n" +
                            "    -flatten - Flatten pdf forms (convert them to text disabling editing in PDF Reader).\n" +
                            "    output.pdf - name of output file. If omitted, the output if sent to stdout. \n\n" +
                            "fields_filename file can be in UTF-8 as is of the following format:\n" +
                            "    On each line, one entry consists of 'field name' followed by value of that field without any quotes.\n" +
                            "    Any number of whitespaces allowed before 'field name', and one space separates 'field name' and its value.\n" +
                            "    In value, newline characters should be encoded as \"\\n\",\n" +
                            "    'U+2029 utf-8 E280A9 : PARAGRAPH SEPARATOR PS' should be encoded as \"\\p\",\n" +
                            "    and '\\' characters should be escaped as \"\\\\\".\n" +
                            "    For checkboxes, values are 'Yes'/'Off'.\n\n" +
                            "    Based on the Belgian iText library v. 5.2.0, http://www.itextpdf.com/\n"
            );
            System.exit(1);
        }

    }


    public static void fillPDFFile(String pdf_filename_in, String pdf_filename_out, String fields_filename){
        fillPDFFile(pdf_filename_in, pdf_filename_out, fields_filename, null, "fill", false, false);
    }

    public static void fillPDFFile(String pdf_filename_in, String pdf_filename_out, String fields_filename, String font_file, String op, Boolean flatten, Boolean verbose) {
        OutputStream os;
        PdfStamper stamp;
        try {
            PdfReader reader = new PdfReader(pdf_filename_in);

            if (pdf_filename_out != null) {
                os = new FileOutputStream(pdf_filename_out);
            } else {
                os = System.out;
            }

            stamp = new PdfStamper(reader, os, '\0');

            AcroFields form = stamp.getAcroFields();

            if (op.equals("list")){
                formList(form);
            } else {
                if (font_file != null){
                    BaseFont bf = BaseFont.createFont(font_file, BaseFont.IDENTITY_H, true);
                    form.addSubstitutionFont(bf);
                }
                Map<String, String> fields = readFile(fields_filename);
                for (Map.Entry<String, String> entry : fields.entrySet()) {
                    if (verbose)
                        System.out.println("Field name = '" + entry.getKey() + "', New field value: '" + entry.getValue() + "'");
                    form.setField(entry.getKey(), entry.getValue());
                }

                stamp.setFormFlattening(flatten);
                stamp.close();
            }
        } catch (FileNotFoundException e) {
            System.err.println("FileNotFoundException: " + e.getMessage());
            System.exit(2);
        } catch (IOException e) {
            System.err.println("Input output error: " + e.getMessage());
            System.exit(3);
        } catch (DocumentException e) {
            System.err.println("Error while processing document: " + e.getMessage());
            System.exit(4);
        }
    }

    public static void formList(AcroFields form){
        Map<String, AcroFields.Item> map = form.getFields();
        System.out.println("Field names:");
        for (Map.Entry<String, AcroFields.Item> entry : map.entrySet())
            System.out.println(entry.getKey());
        System.out.println("END: Field names");
    }

    /**
     * <var>filename</var> file can be in UTF-8 and in of the following format:<br><br>
     *  On each line, one entry consists of <i>field name</i> followed by value of that field without any quotes. <br>
     *  Any number of whitespaces allowed before <i>field name</i> and between <i>field name</i> and its value.<br>
     *  In value, newline characters should be encoded as \n
     *  and '\' characters should be escaped as "\\". <br>
     *  For checkboxes, values are 'Yes'/'Off'."<br>
     *
     * @param filename name of file with fields and their values.
     * @return
     * @throws java.io.FileNotFoundException
     */
    public static Map<String, String> readFile(String filename) throws java.io.FileNotFoundException{
        Map<String, String> fields = new HashMap<String, String>();
        String s, v;
        String[] t;
        Scanner input;

        if (filename != null)
            //input = new Scanner(new File(filename));
            input = new Scanner(new BufferedReader(new FileReader(filename)));
        else
            input = new Scanner(System.in);

        int i = 1;
        while(input.hasNext()) {
            s = input.nextLine().trim();
            t = s.split("\\s", 2);
            if (t.length == 2){
                // Unescape "\n":
                v = unescape(t[1]);
                fields.put(t[0], v);
            } else {
                if (verbose)
                    System.out.println("Line " + i + ": " + s + "\nskipped.");
            }
            i++;
        }
        IOException ex = input.ioException();
        if (ex != null)
            ex.printStackTrace(System.out);

        if (verbose)
            System.out.println( (i - 1) + " lines from " + (filename == null ? "stdin" : filename) +  " parsed.");
        input.close();
        return fields;
    }

    /**
     * Unescapes "\n", etc.
     *
     * @param str
     * @return resuling string.
     */
    public static String unescape(String str){
        String out = "";
        char ch, next;

        if (str == null) {
            return null;
        }
        final int length = str.length();
        for (int offset = 0; offset < length; ) {

            ch = str.charAt(offset);

            if ((ch == '\\') && ((offset + 1) < length)){
                next = str.charAt(offset + 1);
                switch (next){
                    case '\\':
                        out += '\\';
                        break;
                    case 'n':
                        out += '\n';
                        break;
                    case 'p':
                        // U+2029 utf-8 E280A9 : PARAGRAPH SEPARATOR PS
                        out += '\u2029';
                        break;
                    default:{
                        out += (ch + next);
                    }
                }
                offset++;
            } else
                out += ch;

            offset++;
        }

        return out;
    }

}