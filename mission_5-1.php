<html>
	<head>
		<b>mission_5-1</b>
		<meta charset="UTF-8">
	</head>
	<body>
		<form method="post" action="mission_5-1.php">

		<p><b>おすすめの旅行先教えてください！（国内・国外どちらでも可）</b></p>

		<?php
			$dsn = 'mysql:dbname=（データベース名）;host=localhost';
			$user = '(ユーザー名)';
			$password = '（パスワード）';
			$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
			$sql = "CREATE TABLE IF NOT EXISTS tb1"
			." ("
			. "id INT AUTO_INCREMENT PRIMARY KEY,"
			. "name char(32),"
			. "comment TEXT,"
			. "time TEXT,"
			. "pass TEXT"
			.");";
			$stmt = $pdo->query($sql);
		?>

		<?php
			if(!empty($_POST["edit"]) && !empty($_POST["edi_num"]) && !empty($_POST["edi_pass"])){
				$tmp_edi = $_POST["edi_num"];
				$edi_error = false;
				if(!is_numeric($_POST["edi_num"]))
					$tmp_edi = mb_convert_kana($tmp_edi,"n");
				$sql = 'SELECT * FROM tb1';
				$stmt = $pdo->query($sql);
				$results = $stmt->fetchAll();
				foreach ($results as $row){
					if($row['id'] == intval($tmp_edi) && $row['pass'] !== $_POST["edi_pass"])
						$edi_error = true;
				}
				if($edi_error == false){
					foreach ($results as $row){
						if($row['id'] == intval($tmp_edi)){
							$edi_id = $row['id'];
							$edi_name = $row['name'];
							$edi_comment = $row['comment'];
						}
					}
				}
			}
		?>

		<p>名前：<input type="text" name="name" value="<?php if(isset($edi_name)){echo $edi_name;}?>"></p>

		<p>旅行先：<input type="text" name="comment" value="<?php if(isset($edi_comment)){echo $edi_comment;}?>"></p>
		
		<p>パスワード：<input type="text" name="sub_pass"></p>

		<p><input type="submit" name="sub_name" value="送信する"></p>

		<p>削除したい投稿番号：<input type="text" name="del_num" maxlength=3 size=3></p>

		<p>パスワード：<input type="text" name="del_pass"></p>
 
		<p><input type="submit" name="delete" value="削除する"></p>

		<p>編集したい投稿番号：<input type="text" name="edi_num" maxlength=3 size=3></p>

		<p>パスワード：<input type="text" name="edi_pass"></p>

		<p><input type="submit" name="edit" value="編集"></p>

		<p><input type="hidden" name="edi_num_store" maxlength=3 size=3 value = "<?php if(isset($edi_id)){echo $edi_id;}?>"></p>

		<?php
			if(!empty($_POST["delete"]) && !empty($_POST["del_num"]) && !empty($_POST["del_pass"])){
				$del_error = false;
				$tmp_del=$_POST["del_num"];
				if(!is_numeric($_POST["del_num"]))
					$tmp_del = mb_convert_kana($tmp_del,"n");
				$sql = 'SELECT * FROM tb1';
				$stmt = $pdo->query($sql);
				$results = $stmt->fetchAll();
				foreach ($results as $row){
					if($row['id'] == intval($tmp_del) && $row['pass'] !== $_POST["del_pass"])
						$del_error = true;
				}
				if($del_error == false){
					$sql = 'delete from tb1 where id=:id';
					$stmt = $pdo->prepare($sql);
					$stmt->bindParam(':id', $tmp_del, PDO::PARAM_INT);
					$stmt->execute();
				}
			}
			
			if(!empty($_POST["comment"])&&!empty($_POST["name"])&&!empty($_POST["sub_name"])){
				if(!empty($_POST["edi_num_store"])){
					$tmp_store = $_POST["edi_num_store"];
					if(!is_numeric($_POST["edi_num_store"]))
						$tmp_store = mb_convert_kana($tmp_store,"n");
					$time = date('Y/m/d H:i:s');
					$sql = 'update tb1 set name=:name,comment=:comment,time=:time,pass=:pass where id=:id';
					$stmt = $pdo->prepare($sql);
					$stmt->bindParam(':name', $_POST["name"], PDO::PARAM_STR);
					$stmt->bindParam(':comment', $_POST["comment"], PDO::PARAM_STR);
					$stmt->bindParam(':time', $time, PDO::PARAM_STR);
					$stmt->bindParam(':pass', $_POST["sub_pass"], PDO::PARAM_STR);
					$stmt->bindParam(':id', $tmp_store, PDO::PARAM_INT);
					$stmt->execute();
				}
				else {
					$sql = $pdo -> prepare("INSERT INTO tb1 (name, comment, time, pass) VALUES (:name, :comment, :time, :pass)");
					$sql -> bindParam(':name', $name, PDO::PARAM_STR);
					$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
					$sql -> bindParam(':time', $time, PDO::PARAM_STR);
					$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
					$name = $_POST["name"];
					$comment = $_POST["comment"];
					$time = date('Y/m/d H:i:s');
					$pass = $_POST["sub_pass"];
					$sql -> execute();
				}
			}

			$sql = 'SELECT * FROM tb1';
			$stmt = $pdo->query($sql);
			$results = $stmt->fetchAll();
			foreach ($results as $row){
				//$rowの中にはテーブルのカラム名が入る
				echo $row['id'].' ';
				echo $row['name'].' ';
				echo $row['comment'].' ';
				echo $row['time'].'<br>';
			}
		?>
		</form>
	</body>
</html>