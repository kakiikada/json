<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="base.css">
  <title>Twitterみたいにコメント残して保存する練習</title>
</head>
<body>
<?php require 'header.php' ?>
<div class="container">
  <form action="index.php" method="post">
    <input type="text" name="comment" placeholder="コメントを入力してください">
    <input type="submit" value="投稿する">
  </form>
  <hr>
  <?php
  $file = 'json.txt';
  if(file_exists($file)){
    $message = json_decode(file_get_contents($file));
  }
  if(isset($_POST['comment']) && $_POST['comment'] != null){
    $message[] = $_POST['comment'];
  }elseif(!isset($_POST['delete'])){
    echo '<p>コメント未入力は投稿できません</P>';
  }
  ?>
  <?php
  for($i = 0; $i < 100; $i++ ){
    if(isset($_POST["id=$i"])){
      array_splice($message,$i,1);
    }
  }
  file_put_contents($file,json_encode($message));//ファイル書き込み
  ?>
  <?php
  $id = 0;
  ?>
  <?php $message_reverse = array_reverse($message) ?>
  <?php foreach($message_reverse as $row): ?><!--書き出し-->
    <p><?php echo htmlspecialchars($row); ?></p>
    <form action="index.php" method="post">
      <input type="hidden" name="id=<?php echo count($message)- $id -1; ?>">
      <input type="hidden" name="delete">
      <input type="submit" value="消去">
    </form>
    <hr>
    <?php $id++; ?>
  <?php endforeach; ?>
</div>
</body>
</html>
