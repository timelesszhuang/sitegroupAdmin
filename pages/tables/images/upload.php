<?php
if (is_uploaded_file($_FILES["file_name"]["tmp_name"])) {
    if (move_uploaded_file($_FILES["file_name"]["tmp_name"], $_FILES["file_name"]["name"])) {
        $result = mysql_connect("127.0.0.1", "root", "root");
        mysql_query("use test");
        $file = file_get_contents($_FILES["file_name"]["name"]);
        $base_file = base64_encode($file);
        if (mysql_query("insert into img_birnary (string_content) values ('$base_file')", $result)) {
            $ru = mysql_query("select * from img_birnary limit 0,1");
            while ($r = mysql_fetch_object($ru)) {
                if ($r->string_content) {
                    $model_file = fopen("22.jpg", "w");
                    fwrite($model_file, base64_decode($r->string_content));
                }
            }
        }

//        $model_file=fopen("2.jpg","w");
//        fwrite($model_file,$file);
    }
//        var_dump($_FILES["file_name"]);
}


?>