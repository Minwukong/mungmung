<?php
require_once('./view/php_connect_root.php');
// require_once('./view/php_connect_h.php');

    settype($_POST['id'], 'integer');
    $filtered = array(
      'id'=>mysqli_real_escape_string($conn, $_POST['id']),
      'title'=>mysqli_real_escape_string($conn, $_POST['title']),
      'description'=>mysqli_real_escape_string($conn, $_POST['description'])
  );

$sql = "
    UPDATE topic
        SET
            title = {$filtered['title']},
            description = {$filtered['description']}
        WHERE
            id = {$filtered['id']}
";
$result = mysqli_query($conn, $sql);

    require_once('./view/html_top.php');
?>
    </div>
    <div id="Wrap" class="wrap_posts">
        <h1><a class="main_title" href="index.php">게시판</a></h1>
        <div class="show_box">
            <div class="cru_box">
            <?php
            if($result === false){
                echo '저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요.';
                error_log(mysqli_error($conn));
            } else {
                echo '성공했습니다.<br><br> <a href="index.php">돌아가기</a>';
            }
            ?>
            </div>
        </div>
<?php
    require_once('./view/bottom.php');
?>