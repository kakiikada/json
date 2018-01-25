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
  <?php
  $file = 'json.txt';
  $file_day = 'day.txt';
  //保存コメントデータの読み込み
  if(file_exists($file)){
    $message = json_decode(file_get_contents($file));
  }
  if(file_exists($file_day)){
    $day_date = json_decode(file_get_contents($file_day));
  }
  //コメント投稿されてるor空文字じゃないなら、コメント・日時をファイルに書き込む
  if(isset($_POST['comment']) && $_POST['comment'] != null){
    $message[] = $_POST['comment'];
    $today = getdate();
    //投稿日時は文字列として保存。タイムスタンプに変換して保存したほうが良いのかもしれないけど妥協。
    $day_date[] = "$today[year]年$today[mon]月$today[mday]日 $today[hours]時$today[minutes]分";
  }elseif(!isset($_POST['delete'])){
    echo '<p>コメント未入力は投稿できません</P>';
  }
  ?>
  <?php
  for($i = 0; $i < count($message); $i++ ){ //フラグがあればデータを消す処理
    if(isset($_POST["id=$i"])){
      array_splice($message,$i,1);
      array_splice($day_date,$i,1);
    }
  }
  file_put_contents($file,json_encode($message));//ファイル書き込み
  file_put_contents($file_day,json_encode($day_date));
  ?>
  <?php $id = 0; ?>
  <?php $message_reverse = array_reverse($message); ?><!--最新を上に表示するため、配列を逆にする-->
  <?php $day_date_reverse = array_reverse($day_date); ?>
  <!--コメント入力部分開始-->
  <div class="container">
    <form class = "push"  action="index.php" method="post">
      <input class = "input" type="text" name="comment" placeholder="コメントを入力してください">
      <input class = "submit" type="submit" value="投稿する">
    </form>
    <hr>
  <!--コメント入力部分終わり-->
  <?php foreach($message_reverse as $row): ?><!--書き出し-->
    <p class = "log"><?php echo htmlspecialchars($row); ?></p>
    <form action="index.php" method="post">
      <!--コメを消去するフラグ（配列を逆にしているので、補正をかけている）-->
      <input type="hidden" name="id=<?php echo count($message)- $id -1; ?>">
      <input type="hidden" name="delete"><!--消去時にコメ未入力文章を出さないためにフラグを立てる-->
      <p class = 'day-text'>投稿日時：<?php echo $day_date_reverse[$id]; ?></p>
      <input type="submit" value="消去">
    </form>
    <hr>
    <?php $id++; ?>
  <?php endforeach; ?>
</div>
</body>
</html>
