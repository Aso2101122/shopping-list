<?php
require_once './dbmanager.php';
$stms = getDb();
$sql = $stms->prepare('select * from shopping_list s where s.id = ?');
$sql->bindValue(1,$_GET['id']);
$sql->execute();
$result = $sql->fetchAll(PDO::FETCH_ASSOC);

//カテゴリーテーブル
$sql = $stms->query('select * from category');
$category_result = $sql->fetchAll(PDO::FETCH_ASSOC);

//場所テーブル
$sql = $stms->query('select * from place');
$place_result = $sql->fetchAll(PDO::FETCH_ASSOC);


//保存ボタンが押されたとき
if(isset($_POST['save'])){
    $sql = $stms->prepare('update shopping_list set name=?, category_id=?, place_id=? where id=?');
    $sql->bindValue(1,$_POST['name']);
    $sql->bindValue(2,$_POST['category']);
    $sql->bindValue(3,$_POST['place']);
    $sql->bindValue(4,$_POST['id']);
    $sql->execute();
    header('Location: ./index.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>買い物リスト：編集</title>
</head>
<body>
<form action="edit.php?id=<?= $_GET['id'] ?>" method="post">
    <span>品名</span>
    <input type="hidden" name="id" value="<?= $result[0]['id'] ?>"/>
    <input type="text" name="name" value="<?= $result[0]['name'] ?>"/>
    <span>カテゴリー：</span>
    <select name="category" id="category-select" onChange="categoryAdd()">
        <?php foreach($category_result as $row):?>
            <?php
            $category_selected = '';
            if($row['category_id'] == $result[0]['category_id']){
                $category_selected = 'selected';
            }
            ?>
            <option value="<?= $row['category_id']?>"<?= $category_selected ?>><?= $row['category_name'] ?></option>
        <?php endforeach; ?>
        <option value="category-add.php">カテゴリを追加</option>
    </select>
    <span>場所：</span>
    <select name="place" id="place-select" onChange="placeAdd()">
        <?php foreach($place_result as $row):?>
            <?php
            $place_selected = '';
            if($row['place_id'] == $result[0]['place_id']){
                $place_selected = 'selected';
            }
            ?>
            <option value="<?= $row['place_id']?>"<?= $place_selected ?>><?= $row['place_name'] ?></option>
        <?php endforeach; ?>
        <option value="place-add.php">場所を追加</option>
    </select>
    <button type="submit" name="save" value="true">保存</button>
</form>
</body>