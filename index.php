<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>People base</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!--Форма заполнения класса People-->
    
    <form action="people.php" method="post">
        <p>Добавление пользователя в базу данных</p>
        <label>Имя:</label>
        <input type="text" minlength="3"name="name" placeholder="Введите имя">
        <label>Фамилия:</label>
        <input type="text" minlength="3"name="surname" placeholder="Введите фамилию">
        <label>Дата рождения:</label>
        <input type="date"  min="01-01-0000" max="28-07-2022"name="birthday" placeholder="Введите дату рождения">
        <label>Пол:</label>
        <input type="number" min=0 max=1 name="sex" placeholder="Введите пол">
        <label>Город рождения:</label>
        <input type="text" minlength="2" name="birthcity" placeholder="Введите город рождения">
        <button name='button'  value="1">Ввод</button>
        <p class="msg">
            <?php echo $_SESSION['check'];
                echo $_SESSION['add'];
                unset($_SESSION['add']);
                unset($_SESSION['check']);
            ?>
        </p>
    </form>

    <!--Форма на удаление человека по айди-->
    <form action="people.php" method="post">
    <p>Удаление пользователя из базы данных</p>
        <label>Айди:</label>
        <input type="text" name="id" placeholder="Введите id">
        <button name='button' value="2">Удалить</button>
        <p class="msg">
            <?php 
                echo $_SESSION['delete'];
                unset($_SESSION['delete']);
            ?>
        </p>
    </form>
    
    <form action="people.php" method="post">
    <p>Подсчёт возраста</p>
        <label>Айди:</label>
        <input type="text" name="id" placeholder="Введите id">
        <button name='button' value="3">Возраст</button>
        <p class="msg">
            <?php 
                echo $_SESSION['age'];
                unset($_SESSION['age']);
            ?>
        </p>
    </form>

    <form action="people.php" method="post">
    <p>Узнать пол</p>
        <label>Айди:</label>
        <input type="text" name="id" placeholder="Введите id">
        <button name='button' value="4">Пол</button>
        <p class="msg">
            <?php 
                echo $_SESSION['sex'];
                unset($_SESSION['sex']);
            ?>
        </p>
    </form>

    <form class="radioq" action="people.php" method="post">
    <p>Форматирование человека</p>
        <label>Айди:</label>
        <input type="text" name="id" placeholder="Введите id">
        <div class="item">
        <label >Только по дню рождения</label>
        <input  type="radio" name="contact" value="birth"><br>
        <label ">Только по полу</label>
        <input type="radio" name="contact" value="sex"><br>
        <label ">И по дню рождения и по полу</label>
        <input type="radio" name="contact" value="birthAndSex"><br>
        </div>
        <button name='button' value="5">Вывод человека</button>
        
        <p class="msg">
            <?php 
                
                echo $_SESSION['form'];
                unset($_SESSION['form']);
            ?>
        </p>
    </form>
</body>
</html>