<html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<head>「名前」と「コメント」の入力フォーム</head>
<body>
<?php
//パスワードがあっていて編集番号が送信されているときにフォームを編集可能な状態にする
if($_POST["pass3"]="intern"&&!empty($_POST["edit"])){
	$value3=$_POST["edit"];
	//SQLに接続
	$dsn='データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード名';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	//データベースの中の番号を取得
	$sql = 'SELECT * FROM mission2';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
	//番号が編集番号と一致するところをさがす
		if($row['id']==$value3){
			$value1=$row['name'];
			$value2=$row['comment'];
		}
	}
}else{//送信されない場合はなにも表示しない
	$value1="";
	$value2="";
	$value3="";
}
?>			
	
<form action="mission_5-1.test.php" method="post">
	名前:<br>
	<input type="text" name="name" size="30" value="<?php echo $value1; ?>">:<br>
	コメント:<br>		
	<input type="text" name="comment" size="50" value="<?php echo $value2; ?>">:<br>
	<input type="hidden" name="num" size="50" value="<?php echo $value3; ?>">:<br>
	<input type="text" name="pass1" size="50" value="">
	<input type="submit" value="送信"/>:<br>
</form>
<form action="mission_5-1.test.php" method="post">
	<br>削除:		
	<br><input type="text" name="delete" size="10" value="">:<br>
	<input type="text" name="pass2" size="50" value="">:<br>
	<input type="submit" value="削除">:<br>
</form>	

<form action="mission_5-1.test.php" method="post">
	<br>編集:		
	<br><input type="text" name="edit" size="10" value="">:<br>
	<input type="text" name="pass3" size="50" value="">:<br>
	<input type="submit" value="編集">:<br>
</form>	

<?php
//記入例；以下は PHP領域に記載すること。
//毎回接続は必要だから編集番号がhiddenのボックスにあるときの場合分けの前に
$dsn='データベース名';
$user ='ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//テーブルの作成
//AUTO_INCREMENT をつけると、現在格納されている最大の数値に 1 を追加した数値を自動で格納することができます。
$sql = "CREATE TABLE IF NOT EXISTS mission2"
." ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(32),"
. "comment TEXT,"
. "time DATETIME"
.");";
$stmt = $pdo->query($sql);

//投稿フォームに投稿された場合、パスワードがあっているか
if(!empty($_POST["pass1"])&&$_POST["pass1"]=="intern"){
//nameとcommentが送信されるときに定義
	if(!empty($_POST["name"])&&!empty($_POST["comment"])){
		$comment=$_POST["comment"];
		$name=$_POST["name"];
		$time = date('Y/m/d H:i:s');
		//編集するとき
		if(!empty($_POST["num"])){
			//bindParamの引数（:nameなど）は4-2でどんな名前のカラムを設定したかで変える必要がある。
			$id = $_POST["num"] ; //変更する投稿番号
			$sql = 'update mission2 set name=:name,comment=:comment,time=:time where id=:id';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':name', $name, PDO::PARAM_STR);
			$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
			$stmt->bindParam(':time', $time, PDO::PARAM_STR);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
		}else{
			//テーブルへの書き込み、この場合一番下に表示
			$sql = $pdo -> prepare("INSERT INTO mission2 (name, comment, time) VALUES (:name, :comment, :time)");
			$sql -> bindParam(':name', $name, PDO::PARAM_STR);
			$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
			$sql -> bindParam(':time', $time, PDO::PARAM_STR);
			$sql -> execute();
			}
		
	}
	//表示は編集であるかいなかではなく
	$sql = 'SELECT * FROM mission2';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['time'].'<br>';
		echo "<hr>";
		}
}
//削除フォーム
if(!empty($_POST["delete"])&&$_POST["pass2"]=="intern"){
	$id = $_POST["delete"];
	$sql = 'delete from mission2 where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
	//表示
	$sql = 'SELECT * FROM mission2';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['time'].'<br>';
		echo "<hr>";
		}
}
	
	
?>
	
	
?>