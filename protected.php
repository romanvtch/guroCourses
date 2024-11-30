<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $filePath = 'courses.json';

    $courses = [];
    if (file_exists($filePath)) {
        $courses = json_decode(file_get_contents($filePath), true);
    }

    $category = $_POST['category'] ?? '';
    $name = $_POST['name'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';

    $modules = [];
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'question') === 0) {
            $index = str_replace('question', '', $key);
            $question = $value;
            $answers = $_POST['answer' . $index] ?? '';
            if (!empty($question) && !empty($answers)) {
                $modules[] = [
                    'question' => $question,
                    'answers' => explode(';', $answers)
                ];
            }
        }
    }

    $newId = count($courses) > 0 ? end($courses)['id'] + 1 : 1;

    $newCourse = [
        'id' => $newId,
        'name' => $name,
        'category' => $category,
        'title' => $title,
        'description' => $description,
        'modules' => $modules
    ];

    $courses[] = $newCourse;

    file_put_contents($filePath, json_encode($courses, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

    echo "Курс успішно додано!";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./assets/style/app.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма курсов</title>
</head>

<body class="pt">
    <header>
        <a href="./" aria-label="На главную">
            <img class="logo" src="./assets/img/logo.webp" alt="Логотип GURO">
        </a>
        <div class="header__content">
            <nav class="nav" aria-label="Основная навигация">
                <a href="./#about" class="nav__link" title="Узнайте, кто мы">Кто мы</a>
                <a href="./#courses" class="nav__link" title="Посмотрите наши курсы">Курсы</a>
                <a href="./#feed" class="nav__link" title="Отзывы наших учеников">Отзывы</a>
                <a href="./#contact" class="nav__link" title="Как с нами связаться">Контакты</a>
            </nav>
            <a href="#comingsoon" class="nav__btn" title="Читайте последние новости">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path
                        d="M7 16h13v1h-13v-1zm13-3h-13v1h13v-1zm0-6h-5v1h5v-1zm0 3h-5v1h5v-1zm-17-8v17.199c0 .771-1 .771-1 0v-15.199h-2v15.98c0 1.115.905 2.02 2.02 2.02h19.958c1.117 0 2.022-.904 2.022-2.02v-17.98h-21zm19 17h-17v-15h17v15zm-9-12h-6v4h6v-4z" />
                </svg>
                <span>Новости</span>
            </a>
            <div class="menu" aria-label="Меню">
                <svg class="open" width="24" height="24" fill="none" stroke="#fff" stroke-linecap="round"
                    stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" aria-label="Открыть меню"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 12h18M3 6h18M3 18h18"></path>
                </svg>
                <svg class="close" width="24" height="24" fill="#fff" viewBox="0 0 20 20" aria-label="Закрыть меню"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414"
                        clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>
    </header>
    <main>
        <section class="send">
            <div class="container">
                <div class="head">
                    <span>Для курсов</span>
                    <h2>Создай свой курс</h2>
                    <p>Внемательно все заполняй !</p>
                </div>
                <form class="form__course" action="" method="post" autocomplete="off">
                    <div class="form__group">
                        <label for="category">Категория:</label>
                        <input type="text" id="category" name="category" required>
                    </div>

                    <div class="form__group">
                        <label for="name">Курс:</label>
                        <input type="text" id="name" name="name" required>
                    </div>

                    <div class="form__group">
                        <label for="title">Название:</label>
                        <input type="text" id="title" name="title" required>
                    </div>

                    <div class="form__group">
                        <label for="description">Описание:</label>
                        <input type="text" id="description" name="description" required>
                    </div>
                    <div class="form__group groups">
                    </div>
                    <div class="form__action">
                        <div class="form__button add">Добавить поле</div>
                        <div class="form__button remove">Удалить поле</div>
                    </div>

                    <button type="submit" class="form__button">Добавить курс</button>
                </form>
            </div>
        </section>
    </main>
    <footer>
        <div class="container">
            <div class="footer__content">
                <div class="footer__main">
                    <div class="logo">
                        <img src="/assets/img/logo.webp" alt="logo">
                    </div>
                    <div class="footer__nav">
                        <a href="/privacy" class="footer-link">Конфиденциальность</a>
                        <a href="/terms" class="footer-link">Условия эксплуатации</a>
                    </div>
                </div>
                <p class="copyright">© Copyright 2024 Guro. Все права защищены</p>
            </div>
        </div>
    </footer>
    <script>
        const groups = document.querySelector('.groups');
        const addButton = document.querySelector('.add');
        const removeButton = document.querySelector('.remove');
        let questionIndex = 1;

        addButton.addEventListener('click', () => {
            const group = document.createElement('div');
            group.classList.add('group');

            const questionLabel = document.createElement('label');
            questionLabel.setAttribute('for', `question${questionIndex}`);
            questionLabel.textContent = `Вопрос модуля ${questionIndex}:`;
            const questionInput = document.createElement('input');
            questionInput.setAttribute('type', 'text');
            questionInput.setAttribute('id', `question${questionIndex}`);
            questionInput.setAttribute('name', `question${questionIndex}`);
            questionInput.required = true;

            const answerLabel = document.createElement('label');
            answerLabel.setAttribute('for', `answer${questionIndex}`);
            answerLabel.textContent = `Ответ модуля ${questionIndex}:`;
            const answerInput = document.createElement('input');
            answerInput.setAttribute('type', 'text');
            answerInput.setAttribute('id', `answer${questionIndex}`);
            answerInput.setAttribute('name', `answer${questionIndex}`);
            answerInput.required = true;

            group.appendChild(questionLabel);
            group.appendChild(questionInput);
            group.appendChild(answerLabel);
            group.appendChild(answerInput);

            groups.appendChild(group);

            questionIndex++;
        });

        removeButton.addEventListener('click', () => {
            const lastGroup = groups.querySelector('.group:last-child');
            if (lastGroup) {
                groups.removeChild(lastGroup);
                questionIndex--;
            }
        });
    </script>
</body>

</html>