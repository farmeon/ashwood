<?php
require_once(__DIR__.DIRECTORY_SEPARATOR.'src/classes/db.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'src/classes/Review.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'src/classes/User.php');
$db = \dbconn\Db::getInstance();
$dbconn = $db->getConnection();
session_start();
 
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <!--[if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script><![endif]-->
    <title></title>
	<meta charset="UTF-8">
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="/css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="/js/script.js"></script>
</head>
<body>
<div class="wrapper">

    <header class="header">
        <div class="auth">
            <?php if (isset($_SESSION['ids'])) : ?>
                <a class="logout" href="#">Выход</a>
            <?php else : ?>
                <a href="#modal1" class="open_modal">Регистрация</a>
                <a href="#modal2" class="open_modal">Авторизация</a>
            <?php endif; ?>
        </div>
    </header><!-- .header-->

    <div class="middle">
        <div class="container">
            <main class="content">
                <div class="message-block">
                    <?php
                    $result = new \Otv\Review($dbconn);
                    $review = $result->getList();
                    foreach ($review as $key => $val) :

                        ?>
                        <div class="review-block">
                            <p><?php echo $val['login']?></p>
                            <div>
                                <?php echo $val['content']?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (isset($_SESSION['ids'])) : ?>
                    <div class="add-review">
                        <p>Оставить сообщение</p>
                        <form id="review_form"   name="review_form" method="post" action="">
                            <input type="hidden" name="action" value="add_post">
                            <input type="hidden" name="ids" value="<?php echo $_SESSION['ids']?>">
                            <div>
                                <textarea id="textarea" name="message" required></textarea>
                            </div>
                            <input type="submit" value="Отправить">
                        </form>
                    </div>
                <?php else : ?>
                    <div class="error-mess"><p>Чтобы оставить свое мнение, необходимо войти или зарегистрироваться</p></div>
                <?php endif; ?>

            </main><!-- .content -->
        </div><!-- .container-->

        <aside class="left-sidebar">

            <strong>Left Sidebar:</strong> Integer velit. Vestibulum nisi nunc, accumsan ut, vehicula sit amet, porta a, mi. Nam nisl tellus, placerat eget, posuere eget, egestas eget, dui. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In elementum urna a eros. Integer iaculis. Maecenas vel elit.
        </aside><!-- .left-sidebar -->

    </div><!-- .middle-->

</div><!-- .wrapper -->

<footer class="footer">

</footer><!-- .footer -->

<div id="modal1" class="modal_div">
    <span class="modal_close">X</span>
    <div class="form-table">
        <span>Регистрация</span>
        <div class="response"></div>
        <form id="register_form" class="form_reg" name="register_form" method="post" action="">
            <input type="hidden" name="action" value="registration">
            <div>
                <label for="usrname">Логин:</label>
                <input type="text" id="usrname" name="usrname" required>
            </div>
            <div>
                <label for="psw">Пароль:</label>
                <input type="password" id="psw" name="psw" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{4,}" title="Пароль должен содержать 4 символов -  1 числовое значение и 1 заглавную букву" required>
            </div>
            <div>
                <label for="psw">E-Mail:</label>
                <input name="email" type="email"  required>
            </div>
            <div>
                <input id="reg_butt" type="submit" value="Отправить">
            </div>
        </form>
    </div>
</div>

<div id="modal2" class="modal_div">
    <span class="modal_close">X</span>
    <div class="form-table">
        <span>Авторизация</span>
        <div class="response"></div>
        <form id="auth_form" class="form_reg" name="auth_form" method="post" action="">
            <input type="hidden" name="action" value="authorization">
            <div>
                <label for="usrname">Логин:</label>
                <input type="text" id="usrname2" name="usrname" required>
            </div>
            <div>
                <label for="psw">Пароль:</label>
                <input type="password" id="psw2" name="psw" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{4,}" title="Пароль должен содержать 4 символов -  1 числовое значение и 1 заглавную букву" required>
            </div>
            <div>
                <input type="submit" value="Отправить">
            </div>
        </form>
    </div>
</div>

<div id="overlay"></div>


</body>
</html>