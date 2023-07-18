<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RamenStory</title>
</head>
<body>
    <form enctype="multipart/form-data" action="RamenStory.php" method="post">
        タイトル：<input type="text" name="title"><br />
        <input type="file" name="file_data">
        <input type="submit" name="File送信"  balue="File送信">
    </form>

    <?php
        $dbconn = pg_connect("host=localhost dbname=s2122039 user=s2122039 " .
            "password=yM8wWMEH") or die('Could not connect: ' . pg_last_error());
        // アップロードファイル情報を表示する。
        if ( isset($_FILES['file_data'])){
            echo "アップロードファイル名 : " , $_FILES["file_data"]["name"] , "<BR>";
            echo "MIME タイプ: " , $_FILES["file_data"]["type"] , "<BR>";
            echo "ファイルサイズ: " , $_FILES["file_data"]["size"] , "<BR>";
            echo "テンポラリファイル名: " , $_FILES["file_data"]["tmp_name"] , "<BR>";
            echo "エラーコード: " , $_FILES["file_data"]["error"] , "<BR>";
            $nfn=time() . "_" . getmypid() . "." .
                pathinfo($_FILES["file_data"]["name"], PATHINFO_EXTENSION);
            // アップロードファイルを格納するファイルパスを指定,uploads フォルダの場合。
            //同フォルダは 777 にすること


            $filename = "./uploads/" . $nfn;
            if ( $_FILES["file_data"]["size"] === 0 ) {
                echo "ファイルはアップロードされてません! " .
                    "アップロードファイルを指定してください。";
            }else{
                // アップロードファイルされたテンポラリファイルをファイル格納パスにコピーする
                $result=@move_uploaded_file($_FILES["file_data"]["tmp_name"],
                $filename);
            if($result === true){
                $title=$_POST['title'];
                echo "アップロード成功 (" . $title . ")!! <br>";
                $sql="insert into gupload (title,filename) values('" . $title .
                     "','" . $nfn . "');";
                $result = pg_query($sql) or die('Query failed: ' . pg_last_error());
            }else{
                echo "アップロード失敗!!<br>";
                }
            }
        }
        $sql="select * from gupload order by gid DESC";
        $result = pg_query($sql) or die('Query failed: ' . pg_last_error());
        
        while ($line = pg_fetch_array($result)) {
            echo $line[0], "<br>";
            echo $line[1], "<br>";
            echo $line[2], "<br>";
            echo "<img src=\"./uploads/$line[2]\">";
        }
    
    ?>
    
</body>
</html>