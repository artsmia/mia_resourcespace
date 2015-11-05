<?php
//$_SERVER['REQUEST_METHOD']==="PUT" ? parse_str(file_get_contents('php://input', false , null, -1 , $_SERVER['CONTENT_LENGTH'] ), $_PUT): $_PUT=array();
$dataobj = getallheaders();
$filename = $dataobj['filename'];
// read contents from the input stream
$inputHandler = fopen("php://input", "r");
// create a temp file where to save data from the input stream
$filepath = '/var/www/filestore/tmp/uploads/'.$filename;
if($dataobj['Content-Range']=="initial chunk"){ 
    if(file_exists($filepath)){
        unlink($filepath);
    }
}
$fileHandler = fopen($filepath, "a+");
chmod($filepath, 0777);

// save data from the input stream
while($data = fgets($inputHandler)) {
	//$buffer = fgets($inputHandler, 4096);
	if (strlen($data) == 0) {
		fclose($inputHandler);
		fclose($fileHandler);
		return true;
	}

	fwrite($fileHandler, $data);
	// $contents = fread($fileHandler, 1024);
}
$data= json_encode($dataobj);
echo($data);
// done
?>

