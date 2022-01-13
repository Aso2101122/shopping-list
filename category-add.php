<?php
require_once 'dbmanager.php';
$stms = getDb();
//カテゴリーテーブル
$sql = $stms->query('select * from category');
$result = $sql->fetchAll(PDO::FETCH_ASSOC);

//追加ボタンが押されたら
if(isset($_POST['add'])){
    //入力チェック
    if(isset($_POST['name']) && !empty($_POST['name'])){
        $sql = $stms->prepare('insert into category(category_name,category_color) values(?,?)');
        $sql->bindValue(1,$_POST['name']);
        $sql->bindValue(2,$_POST['color']);
        $sql->execute();
        $stmt = null;
        header('Location: ./category-add.php');
        exit;
    }
}

//削除ボタンが押されたら
if(isset($_POST['delete'])){
    $sql = $stms->prepare('delete from category where category_id=?');
    $sql->bindValue(1,$_POST['delete']);
    $sql->execute();
    $stmt = null;
    header('Location: ./category-add.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>カテゴリ追加</title>
</head>
<body>
<a href="index.php">戻る</a>
<h1>カテゴリ追加</h1>
<div>
    <form action="./category-add.php" method="post">
        <h2>入力</h2>
        <input type="text" name="name"/>
        <input type="color" name="color"/>
        <button type="submit" name="add">追加</button>
    </form>

</div>

<?php foreach($result as $row):?>
<div class="item-row">
    <form action="category-add.php" method="post">
        <span><?= $row['category_name']?></span>
        <span><?= $row['category_color']?></span>
        <button type="submit" name="delete" value="<?= $row['category_id'] ?>">削除</button>
    </form>
</div>
<?php endforeach; ?>
</body>
</html>
