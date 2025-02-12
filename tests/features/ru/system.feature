#language: ru
@skip
Функционал: Системные команды

    Сценарий: Тестирование выполнения
        Пусть я выполняю "true"
        Тогда команда должна выполниться успешно
        Пусть я выполняю "false"
        Тогда команда должна выполниться неуспешно

    Сценарий: Тестирование времени выполнения
        Пусть я выполняю "sleep 1"
        Тогда команда должна выполняться менее чем 2 секунды

        Пусть я выполняю "sleep 2"
        Тогда команда должна выполняться более чем 1 секунду

    Сценарий: Тестирование вывода
        Пусть я выполняю "echo 'Hello world'"
        Тогда вывод должен содержать "Hello world"
        И вывод должен содержать "Hel.*ld"
        И вывод не должен содержать "Hello John"
        И вывод не должен содержать "Hel.*hn"

    Сценарий: Тестирование полного вывода
        Пусть я выполняю "echo 'Hello world\nHow are you?'"
        Тогда вывод должен быть:
        """
        Hello world
        How are you?
        """
        И вывод не должен быть:
        """
        Hello John
        How are you?
        """

    Сценарий: Тестирование выполнения из корня проекта
        Пусть я выполняю "bin/behat --help"

    Сценарий: Создание файлов
        Когда я создаю файл "tests/fixtures/test" с содержимым:
        """
        A new file
        """
        Тогда выведи содержимое файла "tests/fixtures/test"
