<?
    session_start();

/**
 * Класс People
 * Конструктор класса принимает на вход массив, полученный с запроса post. 
 * В консрукторе есть два варианта использования,с 6ю входными параметрами и 2мя. 
 * Два входных параметра получается тогда когда пользователь вводит айди и нажимает на кнопку. 1й параметр это айди человека, 2й параметр номер кнопки
 * исходя из нажатого номера кнопки выбирается вариант действия, (5 кнопок - 5 методов из задания, ещё один метод из задания находится в конструкторе и вызывается автоматически)
 * 
 * 
 * 
 * 
 * 
 */

    class People
    {
        public $id;                         //айди
        public $name;                       //имя
        public $surname;                    //фамилия
        public $birthday;                    //день рождения
        public $sex;                         //пол
        public $birthcity;                    //город рождения

        function __construct($array)        //конструктор 
        {
            
            $count=count($array);   //количество эл. массива переданного через post
        
            if($count==6)           //6 параметров на входе, значит пришёл массив со всеми данными пользователя
            {
            $this->name=$array['name']; 
            $this->surname=$array['surname'];
            $this->birthday=$array['birthday'];
            $this->sex=$array['sex'];
            $this->birthcity=$array['birthcity'];
            }
            else if($count==2)      //
            {
                $check=0;
                $id=$array['id'];
                $connect = mysqli_connect('localhost', 'root', '', 'people');
                if (!$connect) {
                    die("Ошибка: " . mysqli_connect_error());
                }
                $sql="SELECT * FROM people WHERE id='$id'";
                $result = mysqli_query($connect, $sql);
                while($user=mysqli_fetch_array($result)){
                    $this->id=$user['id'];
                    $this->name=$user['name'];
                    $this->surname=$user['surname'];
                    $this->birthday=$user['birthday'];
                    $this->sex=$user['sex'];
                    $this->birthcity=$user['birthcity'];
                    $check=1;
                }
                return $check;


            }
        }
        
        public function getBirthday(){          //метод получения поля дня рождения
            return $this->birthday;
        }
        public function getSex(){               //метод получения поля пол
            return $this->sex;
        }

        public function saveToDatabase()        //метод сохранения данных в базу данных
        {
            
            $connect = mysqli_connect('localhost', 'root', '', 'people');       //коннект
            if (!$connect) {                                                    
            die("Ошибка: " . mysqli_connect_error());                           //стоп программы + вывод ошибки если не удалось законектиться
            }
            //запрос на вставку основных полей класса в базу данных
            $sql = "INSERT INTO people (id, name, surname, birthday, sex, birthcity) VALUES (NULL, '$this->name', '$this->surname', '$this->birthday', '$this->sex', '$this->birthcity')";
            if(mysqli_query($connect, $sql)){
                $_SESSION['add']='Данные успешно отправлены в базу данных';
            } else{
                echo "Ошибка: " . mysqli_error($connect); 
                $_SESSION['add']='Данные не отправлены';
            }
            mysqli_close($connect);             //закрыть коннект с базой данных
        }

        public function deleteIdFromDatabase()                  //удалить элемент по айди из базы данных
        {
            $connect = mysqli_connect('localhost', 'root', '', 'people');       
            if (!$connect) {
                die("Ошибка: " . mysqli_connect_error());
            }
            $id=$_POST['id'];
            $sql="DELETE * FROM people WHERE id = '$id'";             //запрос на удаление по айди
            if($connect->query($sql)){
                $_SESSION['delete']='Пользователь с id: ' .$id.' удалён';
                
            }
            else{
                echo "Ошибка: " . $connect->error;
                $_SESSION['delete']='Удаление не сработало';
            }
            mysqli_close($connect);
        }

        public static function get_age($birth){                     //статическая функция рассчитыающая разницу между текущей датой и датой рождения пользователя, для определения его возраста
            $diff = date( 'Ymd' ) - date( 'Ymd', strtotime($birth) );
            $_SESSION['age']=('Возраст:'. (substr( $diff, 0, -4 )));
        }

        public static function get_sex($sex){                       //определение пола 
            if ($sex==0){
                $_SESSION['sex']=('Пол: женщина');
            }
            elseif ($sex==1) {
                $_SESSION['sex']=('Пол: мужчина');
            }
            
        }   

        public function formatHuman($radio){                    //метод форматирования человека с преобразованием возраста и(или) пола
            $formatPerson=new stdClass();       //создаём stdClass со всеми полями из начального класса и заполняем их
            $formatPerson->id=$this->id;
            $formatPerson->name=$this->name;
            $formatPerson->surname=$this->surname;
            $formatPerson->birthcity=$this->birthcity;
            switch ($radio) {                   
                case 'birth':                                   //если пользователь выбрал форматировать только по дню рождения
                    $birth=$this->birthday;
                    $diff = date( 'Ymd' ) - date( 'Ymd', strtotime($birth) );
                    $diff=(substr( $diff, 0, -4 ));
                    $formatPerson->birthday=$diff;
                    $formatPerson->sex=$this->sex;
                    break;
                case 'sex':                                     //если пользователь выбрал форматировать только по полу
                    $formatPerson->birthday=$this->birthday;
                    ($this->sex==0) ? $formatPerson->sex='женщина' : $formatPerson->sex='мужчина';
                    break;
                case 'birthAndSex':                             //если пользователь выбрал форматировать и по дню рождения и по полу
                    $birth=$this->birthday;
                    $diff = date( 'Ymd' ) - date( 'Ymd', strtotime($birth) );
                    $diff=(substr( $diff, 0, -4 ));
                    $formatPerson->birthday=$diff;
                    ($this->sex==0) ? $formatPerson->sex='женщина' : $formatPerson->sex='мужчина';
                    break;    
            }
            $formatPerson->birthcity=$this->birthcity;  
            print_r ($formatPerson);                            //вывод данных
            // header('Location: index.php');
            return $formatPerson;
        }
    }

    function buttonAdd($array){                                     //функция проверяющая стоит ли выполняться методу сохранения в базу данных
        $err=0;
            if(preg_match('/[a-zа-я]/i',$array['name']) && preg_match('/[a-zа-я]/i',$array['surname']) &&   
             preg_match('/[a-zа-я]/i',$array['birthcity']) )                                  //проверка имени на только буквы(английские-русские)
            {
                $err=0;
            }
            else
            {
                $_SESSION['check']=('В имени/фамилии/городе должны быть только буквы!');        //если оказались символы отличные от а-я А-Я a-z A-Z
                    $err=1;                                                                      //счетчик ошибки
            }

            if($err==0){                                                            //ошибок нет->выполняем функционал
                $human=new People($array);
                $human->saveToDatabase();               
            }
            header('Location: index.php');                                              //выход на главную страницу
    }

    function buttonDelete($array){                                              //функция проверяющая стоит ли выполняться методу удаления из базы данных                                                     
        $human=new People($array);
        $birth=$human->getBirthday();                                           //получаем день рождения, с помощью него проходим дальше валидацию, того что поля в объекте не пустые
        if($birth>0)                                                            //поле не пустое
        {
            $human->deleteIdFromDatabase();                                     //удаление из базы данных
        }
        else{                                                                   //поле пустое
            $_SESSION['delete']='Пользователь с таким айди не найден!';
        }
        header('Location: index.php');                                                  //выход на главную страницу
    }

    function buttonAge($array){                                                  //функция проверяющая стоит ли выполняться методу преобразования даты рождения в возраст    
        $human=new People($array);
        $birth=$human->getBirthday();                                            //получаем день рождения
        if($birth>0)                                                             //поле не пустое
        {
            People::get_age($birth);                                             //static преобразование
        }
        else{                                                                    //поле пустое
            $_SESSION['age']='Пользователь с таким айди не найден!';
        }
        
        header('Location: index.php');                                           //выход на главную
    }

    function buttonSex($array){                                                  //функция проверяющая стоит ли выполняться методу преобразования из двоичной в текстовую
        $human=new People($array);
        $sex=$human->getSex();                                                   //получение поля пола
        $birth=$human->getBirthday();                                            //получаем день рождения
        if($birth>0)                                                             //поле не пустое
        {
            People::get_sex($sex);                                               //static преобразование
        }
        else{
            $_SESSION['sex']='Пользователь с таким айди не найден!';             //поле пустое
        }

        header('Location: index.php');                                           //выход на главную
    }

    function buttonForm($array){                                                 //функция проверяющая стоит ли выполняться методу форматирования человека
        $radio=$array['contact'];                                                //запоминаем вариант выбора radio button
        unset($array['contact']);                                                //удаляем чтобы стало 2 элемента и можно было использовать конструктор с 2мя входными
        $human=new People($array);                                  
        $birth=$human->getBirthday();                                            //получаем день рождения
        if($birth>0)                                                             //поле не пустое
        {
            $formatPerson=$human->formatHuman($radio);                           //вызов функция форматирования
        }
        else{
            $_SESSION['form']='Пользователь с таким айди не найден!';
            header('Location: index.php');
        }
        }
    



     $array =$_POST;                                            //массив введенных данных
     $button=$_POST['button'];                                  //номер нажатой кнопки
    switch($button)
    {
        case 1:                                                     //кнопка 1 - добавление в базу данных
            buttonAdd($array);
            break;
        case 2:                                                     //кнопка 2 - удаление из базы данных по айди
            buttonDelete($array);
            break;
        case 3:                                                     //кнопка 3 - подсчет возраста
            buttonAge($array);
            break;
        case 4:                                                     //кнопка 4 -  узнать пол 
            buttonSex($array);
            break;
        case 5:                                                     //кнопка 5 - форматирование человека с преобразованием возраст и (или) пола
            buttonForm($array);
            break;
    }
