<?php
require_once('config.php');
require_once('./post/post_paging.php');
$article = array(
    'title' => '환영합니다.',
    'description' => '글을 둘러보거나 작성할 수 있습니다.'
);
$update_link = '';
$delete_link = '';
$author = '';
$date = '';
$post_inner_img = '';
$comment = '';
$filtered_id = '';
if (isset($_GET['id'])) {
    $pnum = $_GET['id'];
    $filtered_id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "SELECT * FROM topic LEFT JOIN member ON topic.author_id = member.id WHERE topic.id={$filtered_id}";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $row = mysqli_fetch_array($result);
    $article['title'] = htmlspecialchars($row['title']);
    $article['description'] = htmlspecialchars($row['description']);
    $article['name'] = htmlspecialchars($row['name']);
    $article['created'] = htmlspecialchars($row['created']);
    $article['author_id'] = htmlspecialchars($row['author_id']);

    $update_link = '<a class="update_link" href="./post/update.php?id=' . $_GET['id'] . '">수정</a>';
    $delete_link = '
    <form action="./post/process_delete.php" method="post" class="delete_link">
        <input type="hidden" name="id" value="' . $_GET['id'] . '">
        <input class="delete_link_inner" type="submit" value="삭제">
    </form>
    ';
    $comment_link ='
    <form action="./comment/comment_create.php" method="POST" class="comment_link">
        <input type="hidden" name="post_num" value="'.$_GET['id'].'">
        <input type="hidden" class="username" name="username" value="'.$userName.'">
        <textarea name="content" class="comm_con"></textarea>
        <input class="comm_btn" type="submit" value="작성">
    </form>
    ';
    $author = $article['name'];
    $author_id = $article['author_id'];
    $date = $article['created'];

    if (($row['img_test'])) {
        $post_inner_img = "<p><img class=\"post_innerImg\" src=\"{$row['img_test']}\"></p>";
    }
}

require_once('./view/html_top.php');
?>
<div id="Wrap" class="wrap_posts">
    <h1><a class="main_title" href="php_post.php">게시판</a></h1>
    <div class="post_box">
        <!-- 로그인해야 글쓰기 가능 -->
    <?php if(isset($_SESSION['id'])){ ?>
        <a class="create" id="create" href="./post/create.php">글쓰기</a>
    <?php } ?>
        <div class="postImg_box" id="postImg_box">
            <?= $list; ?>
        </div>
    </div>
    
    <div id="page_num">
        <ul>
            <?php
            if($page <= 1) {
                echo '<li class="page_red">처음</li>';
            } else {
                echo '<li><a href="?page=1">처음</a></li>';
            }
            if($page > 1) {
                $pre = $page - 1;
                echo '<li><a href="?page='.$pre.'">이전</a></li>';
            }
            for($i = $block_start; $i <= $block_end; $i++){
                if($page == $i){
                    echo '<li class="page_red">'.$i.'</li>';
                } else {
                    echo '<li><a href="?page='.$i.'">'.$i.'</a></li>';
                }
            }
            if($block_num < $total_block){
                $next = $page + 1;
                echo '<li><a href="?page='.$next.'">다음</a></li>';
            }
            if($page >= $total_page){
                echo '<li class="page_red">마지막</li>';
            } else {
                echo '<li><a href="?page='.$total_page.'">마지막</a></li>';
            }
            ?>
        </ul>
    </div>
    <div class="show_box">
    <!-- 게시글 누르기 전/후 -->
    <?php if (!isset($_GET['id'])) { ?>
        <div class="welcome" id="welcome">
            <p><h2>환영합니다.</h2></p>
            <p>글을 둘러보거나 작성할 수 있습니다.</p>
        </div>
    <?php } else { ?>   
        <div class="post_wrapper" id="post_wrapper">
            <!-- 로그인했고 글 선택했을 때 / id 일치해야 수정, 삭제 가능 -->
            <?php if(isset($_SESSION['id'])){ 
                    if($userId == $row['author_id']){
            ?>
                <div class="update_delete">
                    <?= $update_link ?>
                    <?= $delete_link ?>
                </div>
            <?php } 
            } ?> 
            <div class="post_section">
                <p><h2><?= $article['title'] ?></h2></p>
                <p><?=$author?></p>
            </div>
                <?= $post_inner_img ?>
            <div class="post_section react_box">
                <button class="heart">
                    <svg class="reacts heart" fill="#262626" viewBox="0 0 48 48">
                        <path d="M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z"></path>
                        <!-- <path d="M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z"></path> -->
                    </svg>
                </button>
                <a href="#comment_write">
                    <svg class="reacts comment" fill="#262626" viewBox="0 0 48 48">
                        <path d="M47.5 46.1l-2.8-11c1.8-3.3 2.8-7.1 2.8-11.1C47.5 11 37 .5 24 .5S.5 11 .5 24 11 47.5 24 47.5c4 0 7.8-1 11.1-2.8l11 2.8c.8.2 1.6-.6 1.4-1.4zm-3-22.1c0 4-1 7-2.6 10-.2.4-.3.9-.2 1.4l2.1 8.4-8.3-2.1c-.5-.1-1-.1-1.4.2-1.8 1-5.2 2.6-10 2.6-11.4 0-20.6-9.2-20.6-20.5S12.7 3.5 24 3.5 44.5 12.7 44.5 24z"></path>
                    </svg>
                </a>
                <svg class="reacts share" fill="#262626" viewBox="0 0 48 48">
                    <path d="M47.8 3.8c-.3-.5-.8-.8-1.3-.8h-45C.9 3.1.3 3.5.1 4S0 5.2.4 5.7l15.9 15.6 5.5 22.6c.1.6.6 1 1.2 1.1h.2c.5 0 1-.3 1.3-.7l23.2-39c.4-.4.4-1 .1-1.5zM5.2 6.1h35.5L18 18.7 5.2 6.1zm18.7 33.6l-4.4-18.4L42.4 8.6 23.9 39.7z"></path>
                </svg>
                <svg class="reacts mark" fill="#262626" viewBox="0 0 48 48">
                    <path d="M43.5 48c-.4 0-.8-.2-1.1-.4L24 29 5.6 47.6c-.4.4-1.1.6-1.6.3-.6-.2-1-.8-1-1.4v-45C3 .7 3.7 0 4.5 0h39c.8 0 1.5.7 1.5 1.5v45c0 .6-.4 1.2-.9 1.4-.2.1-.4.1-.6.1zM24 26c.8 0 1.6.3 2.2.9l15.8 16V3H6v39.9l15.8-16c.6-.6 1.4-.9 2.2-.9z"></path>
                    <!-- <path d="M43.5 48c-.4 0-.8-.2-1.1-.4L24 28.9 5.6 47.6c-.4.4-1.1.6-1.6.3-.6-.2-1-.8-1-1.4v-45C3 .7 3.7 0 4.5 0h39c.8 0 1.5.7 1.5 1.5v45c0 .6-.4 1.2-.9 1.4-.2.1-.4.1-.6.1z"></path> -->
                </svg>
            </div>
            <div class="post_section">
                <p><?= $article['description'] ?></p>
                <p class="post_date"><?= $date ?></p>
            </div>

        </div>
    <?php } ?>
    </div>
<?php if (isset($_GET['id'])) { ?>
    <div class="comments_box">
        <div class="comments_view" id="comment_view">
            <h3>댓글목록</h3>
            <?php
                $sql = "
                    SELECT * FROM comments
                        WHERE
                            post_num = '{$filtered_id}'

                ";
                $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
                while($comment = mysqli_fetch_array($result)){
            ?>
            <div class="dat_view">
                <div><b><?=$comment['name']?></b></div>
                <div class="dap_to comt_edit dat_content">
                    <?=$comment['contents']?>
                </div>
                <div class="comm_me dap_to dat_created">
                    <?=$comment['created']?>
                </div>
                <!-- 로그인했고 댓글 단 사람만 삭제 가능 -->
                <?php if(isset($_SESSION['id'])){
                        if($userName == $comment['name']){
                ?>
                <!-- <div class="comm_me comm_menu dat_del">
                    <a class="dat_del_btn">삭제</a>
                </div> -->
                <form action="./comment/comment_delete.php" method="POST" class="comment_delete_link">
                    <input type="hidden" name="post_num" value=<?=$filtered_id?>>
                    <input type="hidden" name="comment_num" value=<?=$comment['id']?>>
                    <div class="dat_del">
                        <input class="dat_del_btn" type="submit" value="삭제">
                    </div>
                </form>
                <?php } 
                } ?>
            </div>
            <?php } ?>
            <div id="comment_write"></div>
            <!-- 댓글 달기 -->
            <?php if(isset($_SESSION['id'])){ ?>
            <div class="dat_ins">   
                <div style="margin-top: 10px;">
                    <?=$comment_link?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>
</div>
<script type="text/javascript" src="./js/postImg.js?ver=1"></script>
<script src="./js/slide.js"></script>
<?php
    require_once('./view/bottom.php');
?>
