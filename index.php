<?php

ini_set("display_errors", 0);
ini_set("display_startup_errors", 0);
error_reporting(E_ALL);

class Player
{
    public $name;
    public $coins;

    public function __construct($name, $coins)
    {
        $this->name = $name;
        $this->coins = $coins;
    }
}

class Game
{
    private $player1;
    private $player2;
    private $flips = 1;

    public function flip()
    {
        return rand(0, 1) ? "орел" : "решка";
    }

    public function __construct($player1, $player2)
    {
        $this->player1 = $player1;
        $this->player2 = $player2;
    }

    public function start()
    {
        while (true) {
            if ($this->flip() == "орел") {
                $this->player1->coins++;
                $this->player2->coins--;
            } else {
                $this->player1->coins--;
                $this->player2->coins++;
            }
            if ($this->player1->coins == 0 || $this->player2->coins == 0) {
                return $this->end();
            }
            $this->flips++;
        }
    }

    public function winner()
    {
        if ($this->player1->coins > $this->player2->coins) {
            return $this->player1;
        } else {
            return $this->player2;
        }
    }

    public function end()
    {
        echo "Game over. <br>
            Победитель: {$this->winner()->name} <br>
            Количество подбрасываний: {$this->flips}";
    }

}

class Errors
{
    static $error = array("errorName" => "Введите правильно имя (только буквы)",
        "errorCoins" => "Положительное число больше ноля");
}

class ValidField
{
    const NAME = "/^[a-z]+$/i";
}

$name1 = $_POST["name1"];
$name2 = $_POST["name2"];
$coins1 = $_POST["coins1"];
$coins2 = $_POST["coins2"];

if (isset($_POST["get"])) {
    if ($name1 == "" || !preg_match(ValidField::NAME, $name1)) {
        $response["errorName1"] = Errors::$error["errorName"];
    }
    if ($name2 == "" || !preg_match(ValidField::NAME, $name2)) {
        $response["errorName2"] = Errors::$error["errorName"];
    }
    if ($coins2 < 0) {
        $response["errorCoins2"] = Errors::$error["errorCoins"];
    }
    if ($coins1 < 0) {
        $response["errorCoins1"] = Errors::$error["errorCoins"];
    }
    if (empty($response)) {
        $game = new Game(
            new Player($name1, $coins1),
            new Player($name2, $coins2)
        );
        session_start();
    }
}

?>

<!Doctype html>
<meta charset="utf-8">
<head>
    <title>Орел или решка</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .count {
            word-break: break-word;
            width: 400px;
        }

        body {
            margin: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: auto;
        }

        form {
            display: flex;
            flex-direction: column;
            width: 400px;
        }

        button {
            margin: 10px 0;
            padding: 5px;
        }

        input {
            margin: 5px 0;
            padding: 5px;
        }

        label {
            margin: 5px;
        }

        p {
            color: #4614dc;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
<form action="index.php" method="post">
    <label>Имя первого игрока</label>
    <input type="text" name="name1" placeholder="Введите имя" value="<?php echo $name1; ?>">
    <p><?php echo $response["errorName1"] ?></p>
    <label>Число монет первого игрока</label>
    <input type="number" name="coins1" placeholder="Введите количество монет" value="<?php echo $coins1; ?>">
    <p><?php echo $response["errorCoins1"] ?></p>
    <label>Имя второго игрока</label>
    <input type="text" name="name2" placeholder="Введите имя" value="<?php echo $name2; ?>">
    <p><?php echo $response["errorName2"] ?></p>
    <label>Число монет второго игрока</label>
    <input type="number" name="coins2" placeholder="Введите количество монет" value="<?php echo $coins2; ?>">
    <p><?php echo $response["errorCoins2"] ?></p>
    <button type="submit" name="get" class="sign-btn">Играем</button>
    <p class="count" style="color: #0048ff"><?php $game->start(); ?></p>

</form>
</body>
</html>
