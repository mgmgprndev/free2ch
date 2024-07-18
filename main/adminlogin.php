<?php

if($_SERVER['REQUEST_METHOD'] == "POST"){
?>
<html>
    <head>
        <?php require($_SERVER['DOCUMENT_ROOT'] . '/shared/head.php'); ?>
    </head>
    <body>
        <h1>このページは管理者ログインページです</h1>
        <?php

        require_once('/var/www/id.php');

        if(isset($_POST["logout"]) && $_POST["logout"] == "yes" ){
            echo "ログアウトしました。<a href='/adminlogin.php'>ログインに戻る。</a>";
            newSession();
            exit;
        }

        $users = [
            'example_user' => 'example_password'
        ];

        $username = isset($_POST['userid']) ? $_POST['userid'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if (isset($users[$username]) && $users[$username] === $password) {
            newSessionOfAdmin($username);
            echo "ログインしました。";
        }else {
            echo "ログインに失敗しました。<a href='/adminlogin.php'>ログインに戻る。</a>";
        }
        ?>
    </body>
</html>
<?php
}else  {
?>
<html>
    <head>
        <?php require($_SERVER['DOCUMENT_ROOT'] . '/shared/head.php'); ?>
    </head>
    <body>
        <h1>このページは管理者ログインページです</h1>
        <?php 
        require_once('/var/www/util.php');
        session_start();
        if(isset($_SESSION["userkey"])){
            $user = VerifyTable::where('userkey', $_SESSION["userkey"])->first();
            if($user){
                echo "現在、" . $user->useruuid . "としてログインしており、管理者権限を保有" . ( $user->isadmin == 0 ? "していません" : "しています。" );
            }else {
                echo "セッションが無効です。";
            }
        }else {
            echo "セッションが無効です。";
        }
        ?>
        <div class="formdiv">
            <form action="/adminlogin.php" method="POST">
                <input type="text" name="userid" placeholder="ID">
                <input type="password" name="password" placeholder="PASSWORD">
                <button>Login</button>
            </form>

            <form action="/adminlogin.php" method="POST">
                <input type="hidden" name="logout" value="yes">
                <button>ログアウト</button>
            </form>
        </div>
    </body>
</html>

<?php } ?>
<a href='/'>ホームへ戻る。</a>