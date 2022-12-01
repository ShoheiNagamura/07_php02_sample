<?php
// POSTデータ確認
// var_dump($_POST);
// exit();
if (
    !isset($_POST['todo']) || $_POST['todo'] == '' ||
    !isset($_POST['deadline']) || $_POST['deadline'] == ''
) {
    exit('ParamError');
}

$todo = $_POST['todo'];
$deadline = $_POST['deadline'];

// var_dump($todo);
// DB接続
$dbn = 'mysql:dbname=gsacf_l08_08;charset=utf8mb4;port=3306;host=localhost';
$user = 'root';
$pwd = '';

// DB接続
try {
    $pdo = new PDO($dbn, $user, $pwd);
} catch (PDOException $e) {
    echo json_encode(["db error" => "{$e->getMessage()}"]);
    exit();
}


// SQL作成&実行 :で始まるバインド変数でセットする
$sql = 'INSERT INTO todo_table (id, todo, deadline, created_at, updated_at) VALUES (NULL, :todo, :deadline, now(), now())';

$stmt = $pdo->prepare($sql);

// バインド変数を設定　バインド変数に実際のデータをセット
$stmt->bindValue(':todo', $todo, PDO::PARAM_STR);
$stmt->bindValue(':deadline', $deadline, PDO::PARAM_STR);

// SQL実行（実行に失敗すると `sql error ...` が出力される）
try {
    $status = $stmt->execute();
} catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
}

//画面遷移
header('Location:todo_input.php');
exit();
