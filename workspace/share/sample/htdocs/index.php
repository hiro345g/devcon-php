<?php
function hello($message)
{
    echo "Hello, devcon-php!, $message";
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>devcon-php</title>
</head>

<body>
    <h1><?php print("devcon-php"); ?></h1>
    <p><?php hello("こんにちは"); ?></p>
    <?php phpinfo(); ?>
</body>

</html>