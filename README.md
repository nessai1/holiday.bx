# holiday.bx
Сайт для сравнения текстов до и после.

Директории:
  /mocup - директория с версткой самого сайта и его отдельных элементов;
  /tests - каталог с юнит-тестами. Все кейсы тестирования находятся в каталоге /tests/cases;
  /difference.bx - каталог с самим сайтом.

В каталоге difference.bx: 
  config.json - конфигурационный файл сайта;
  /Database - каталог с сущностями, взаимодействующими с базой данных;
  /Document - каталог с сущностями, представляющими текстовые документы;
  /Exceptions - каталог с исключениями;
  Logger.php - класс для логгирования;
  Router.php - класс для роутинга;
  Compiler.php - класс, сравнивающий текстовые документы;
  FileReader.php - класс, реализующий чтение файлов (text or json).
 
Для формирования бд нужно выполнить скрипт миграции createDatabaseTablesScript.sql, находящийся в корне репозитория
Настройка пользователя БД происходит в конфигурационном файле difference.bx/config.json
  
