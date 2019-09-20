<?php
	$time = date("Y/m/d H:i:s"); //日付変数
	
	//データベース接続
	$dsn = 'database-name';
	$user = 'user-name';
	$pass = 'password';
	$pdo = new PDO($dsn, $user, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

	//テーブル作成のSQL作成
	$sql = "CREATE TABLE IF NOT EXISTS mission_5"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "str_time TEXT,"
	. "password char(32)"
	.");";
	$stmt = $pdo->query($sql);

	//テーブルの中身確認
	$sql = 'SHOW CREATE TABLE mission_5';
	$result = $pdo -> query($sql);
	foreach ($result as $row) {
		echo $row[1];
	}
	echo "<hr>";

	//以下投稿機能
	if (empty ($_POST["edit"])) {
		if (! empty($_POST["name"]) && ! empty($_POST["comment"]) && ! empty($_POST["submitPass"])) {
		//テーブルにデータ入力
		$sql = $pdo -> prepare("INSERT INTO mission_5 (name, comment, str_time, password) VALUES (:name, :comment, :str_time, :password)");
			$sql -> bindParam(':name', $_POST["name"], PDO::PARAM_STR);
			$sql -> bindParam(':comment', $_POST["comment"], PDO::PARAM_STR);
			$sql -> bindParam(':str_time', $time, PDO::PARAM_STR);
			$sql -> bindParam(':password', $_POST["submitPass"], PDO::PARAM_STR);
		$sql -> execute();
		}
	//ここまで投稿機能
	} else {
		//以下編集差し替え機能
		if (!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["submitPass"]) && !empty($_POST["edit"]))  {
			$sql = 'update mission_5 set name=:name, comment=:comment, str_time=:str_time where id=:id and password=:password';
			$stmt = $pdo -> prepare($sql);
        		$stmt -> bindParam(':name', $_POST["name"], PDO::PARAM_STR);
        		$stmt -> bindParam(':comment', $_POST["comment"], PDO::PARAM_STR);
        		$stmt -> bindParam(':str_time', $time, PDO::PARAM_STR);
        		$stmt -> bindParam(':id', $_POST["edit"], PDO::PARAM_INT);
        		$stmt -> bindParam(':password', $_POST["submitPass"], PDO::PARAM_STR);
        		$stmt -> execute();
		}
	}
	//ここまで編集差し替え機能

	//以下削除機能
	if (isset($_POST["deleteButton"])) {
		if(! empty($_POST["deletePass"]) && ! empty($_POST["deleteNumber"])) {
			$sql = 'delete from mission_5 where id=:id and password=:password';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':id', $_POST["deleteNumber"], PDO::PARAM_INT);
			$stmt->bindParam(':password', $_POST["deletePass"], PDO::PARAM_STR);
			$stmt->execute();
		}
	}
	//ここまで削除機能

	//以下編集のための投稿フォームにデータを表示する機能
	if (isset($_POST["editButton"])) {
		if (! empty($_POST["editNumber"]) && ! empty($_POST["editPass"])) {
			$sql = 'SELECT * FROM mission_5 WHERE id=:id AND password=:password';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':id', $_POST["editNumber"], PDO::PARAM_INT);
			$stmt->bindParam(':password', $_POST["editPass"], PDO::PARAM_STR);
			$stmt->execute();
			$results = $stmt->fetchAll();

			//変数定義
			foreach($results as $row) {
				$editName = $row['name'];
				$editComment = $row['comment'];
				$editNumber = $row['id'];
				$editPass = $row['password'];
			}
		}
	}
	//ここまで編集のための投稿フォームにデータを表示する機能
?>

<html>
<head>
	<meta charaset="utf-8">
</head>

<body>
	<form method="POST" action="https://tb-210233.tech-base.net/mission_5-1.php">
		<p>名前</p>
		<!-- 名前欄-->
		<input type="text" name="name" value="<?php if (isset ($editName)) {echo $editName;} ?>" >
		<!-- コメント欄-->
		<p>コメント</p>
		<input type="text" name="comment" value="<?php if (isset ($editComment)) {echo $editComment;} ?>" >
		<input type="hidden" name="edit" value="<?php if (isset ($_POST["editButton"])) {echo $editNumber;} ?>">
		<p>パスワード</p>
		<input type="text" name="submitPass" value="<?php if (isset ($editPass)) {echo $editPass;} ?>">
		<!-- 送信ボタン-->
		<input type="submit" name="submitButton" value="送信">
	</form>

	<form method="POST" action="https://tb-210233.tech-base.net/mission_5-1.php">
		<!-- 削除番号欄-->
		<p>削除番号</p>
		<input type="number" name="deleteNumber">
		<p>パスワード</p>
		<input type="text" name="deletePass">
		<input type="hidden" name="deleteButton">
		<!-- 削除ボタン-->
		<input type="submit" value="削除">
	</form>

	<form method="POST" action="https://tb-210233.tech-base.net/mission_5-1.php">
		<!-- 編集番号欄-->
		<p>編集番号</p>
		<input type="number" name="editNumber">
		<p>パスワード</p>
		<input type="text" name="editPass">
		<input type="hidden" name="editButton">
		<!-- 編集ボタン-->
		<input type="submit" value="編集">
	</form>

<?php
	//データ表示
	$sql = 'SELECT * FROM mission_5';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row) {
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['str_time'].'<br>';
	}
?>
</body>
</html>