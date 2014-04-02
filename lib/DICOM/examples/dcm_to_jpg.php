<?PHP
#
# Creates a jpeg and jpeg thumbnail of a DICOM file 
#

require_once('../class_dicom.php');

$file = 'dean.dcm';

if(!$file) {
  print "USAGE: ./dcm_to_jpg.php <FILE>\n";
  exit;
}

if(!file_exists($file)) {
  print "$file: does not exist\n";
  exit;
}

$job_start = time();

$d = new dicom_convert;
$d->file = $file;
$d->dcm_to_jpg();
$d->dcm_to_tn();

system("ls -lsh $file*");

$job_end = time();
$job_time = $job_end - $job_start;
print "Created JPEG and thumbnail $file in $job_time seconds.\n";
