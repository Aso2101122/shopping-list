<?php
require_once './dbmanager.php';
$stms = getDb();
//カテゴリーテーブル
$sql = $stms->query('select * from place');
$result = $sql->fetchAll(PDO::FETCH_ASSOC);

//追加ボタンが押されたら
if(isset($_POST['add'])){
    //入力チェック
    if(isset($_POST['name']) && !empty($_POST['name'])){
        $sql = $stms->prepare('insert into place(place_name) values(?)');
        $sql->bindValue(1,$_POST['name']);
        $sql->execute();
        $stmt = null;
        header('Location: ./place-add.php');
        exit();
    }
}

//削除ボタンが押されたら
if(isset($_POST['delete'])){
    $sql = $stms->prepare('delete from place where place_id=?');
    $sql->bindValue(1,$_POST['delete']);
    $sql->execute();
    $stmt = null;
    header('Location: ./place-add.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>場所追加</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="main">
    <a href="index.php">戻る</a>
    <h1>場所追加</h1>
    <div>
        <form action="./place-add.php" method="post">
            <h2>入力</h2>
            <input type="text" name="name"/>
            <button type="submit" name="add">追加</button>
        </form>

    </div>

    <?php foreach($result as $row):?>
        <div class="item-row">
            <form action="place-add.php" method="post">
                <span><?= $row['place_name']?></span>
                <button type="submit" name="delete" value="<?= $row['place_id'] ?>">削除</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
