<?php
$img_path = "";
$result = -1;
// 允许上传的图片后缀
$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);
// echo $_FILES["file"]["size"];
$extension = end($temp);     // 获取文件后缀名
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/x-png")
|| ($_FILES["file"]["type"] == "image/png")
|| ($_FILES["file"]["type"] == "image/GIF")
|| ($_FILES["file"]["type"] == "image/JPEG")
|| ($_FILES["file"]["type"] == "image/JPG")
|| ($_FILES["file"]["type"] == "image/PJPEG")
|| ($_FILES["file"]["type"] == "image/X-PNG")
|| ($_FILES["file"]["type"] == "image/PNG"))
&& ($_FILES["file"]["size"] < 2048000)   // 小于 2000 kb
&& in_array($extension, $allowedExts))
{
	if ($_FILES["file"]["error"] > 0)
	{
		echo "错误：: " . $_FILES["file"]["error"] . "<br>";
	}
	else
	{
		// echo "";
		// echo "上传文件名: " . $_FILES["file"]["name"] . "<br>";
		// echo "文件类型: " . $_FILES["file"]["type"] . "<br>";
		// echo "文件大小: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
		//echo "文件临时存储的位置: " . $_FILES["file"]["tmp_name"] . "<br>";
		
		// 判断当期目录下的 upload 目录是否存在该文件
		// 如果没有 upload 目录，你需要创建它，upload 目录权限为 777
		// if (file_exists("upload/" . $_FILES["file"]["name"]))
		// {
		// 	echo $_FILES["file"]["name"] . " 文件已经存在。 ";
		// }
		// else
		// {
			// 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
			move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
			//echo "文件存储在: " . "upload/" . $_FILES["file"]["name"];
			$myFile=fopen("currentFile.txt","w+") or die("Unable to open currentFile!"); 
			fwrite($myFile, $_FILES["file"]["name"]);# 将图片路径写入到文件中，用来和python交互
			fclose($myFile);
			$area = './call.py';
            $cmd = system("python3 $area");
            sleep(3);
            $area = './find_faces.py';
            $cmd = system("python3 $area");
			// $readfile=fopen("./result.txt","r") or exit("Unable to open file!");
			// $result = fgets($readfile);
			// $readfilename = fopen("./filename.txt", "r") or exit("Unable to open file!");
			$img_path = './upload/result_'.$_FILES["file"]["name"];
		// }
	}
}
else
{
	echo "非法的文件格式";
}
$heredoc = <<<EOD
<head>
<link rel="icon" href="./logo.jpg" type="image/x-icon">
<style>@charset "UTF-8";@import './jamey.css';</style>
<head>
<body> 
<div id="test" style="
padding: 20px 10px 10px;
border-radius: 20px;
background-color: #fff;
box-sizing: border-box;
box-shadow: 0 0 20px 2px rgba(0, 0, 0, .1);
-webkit-box-shadow: 0 0 20px 2px rgba(0, 0, 0, .1);
-moz-box-shadow: 0 0 2px 2px rgba(0, 0, 0, .1);
">
<center>
<h1><a id="_0"></a>人脸位置检测</h1>
<p><h4>@姬小野</h4>
</p>
<img src="$img_path" width=666 alt="1.png" /><br><br>
<div style="
    font-size: 28px;
    color: black;
">
<a href="http://wujiahao.online/jamey/face/index.html">返回再次提交</a><br><br><br>
</div>
</center>
</div>
</body>
EOD;
echo $heredoc;
?>