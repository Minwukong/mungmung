<?php
$conn = mysqli_connect(
  'localhost',
  'root',
  'qmffhrcpdls',
  'opentutorials');

$sql = "SELECT * FROM topic";
$result = mysqli_query($conn, $sql);
$list = '';
$date = '';
while($row = mysqli_fetch_array($result)){ 
    $escaped_title = htmlspecialchars($row['title']);
    $escaped_date = htmlspecialchars($row['created']);
    $list = $list."<li><a href=\"index.php?id={$row['id']}\">{$escaped_title}</a></li>";
    $date = $date."<li>{$escaped_date}</li>";
}
$article = array(
    'title'=>'Welcome',
    'description'=>'Hello, web'
);
$update_link = '';
$delete_link = '';
$author = '';
if(isset($_GET['id'])) {
    $filtered_id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "SELECT * FROM topic LEFT JOIN author ON topic.author_id = author.id WHERE topic.id={$filtered_id}";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $article['title'] = htmlspecialchars($row['title']);
    $article['description'] = htmlspecialchars($row['description']);
    $article['name'] = htmlspecialchars($row['name']);

    $update_link = '<a class="update_link" href="update.php?id='.$_GET['id'].'">수정</a>';
    $delete_link = '
    <form action="process_delete.php" method="post" class="delete_link">
        <input type="hidden" name="id" value="'.$_GET['id'].'">
        <input type="submit" value="삭제" id="delete_link">
    </form>
    ';
    $author = "<p>by {$article['name']}</p>";
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시판</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1><a class="main_title" href="index.php">게시판</a></h1>
    <div class="post_box">
    <a class="create" id="create" href="create.php">글쓰기</a>
    <ul class="list_ul"><?=$list;?></ul>
    <ul class="date_ul"><?=$date;?></ul>
    </div>
    <div class="show_box">
        <div class="update_delete">
            <?=$update_link?>
            <?=$delete_link?>
        </div>
        <h2><?=$article['title']?></h2>
        <?=$article['description']?>
        <?=$author?>
    </div>
</body>
</html>