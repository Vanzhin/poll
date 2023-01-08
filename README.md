# poll
Запуск
- клонировать репозиторий;
- запустить файл run.sh в папке docker;
- после создания контейнера, перейти в контейнер symfony и выполнить в его консоли следующее
    - установить зависимости командой
        - composer install
        - yarn run dev
    - для подключения Vue ( см ссылку https://symfony.com/doc/current/frontend/encore/vuejs.html) 
      - yarn encore dev-server
    - перейти в контейнер symfony и выполить миграции командой
        - php bin/console doctrine:migrations:migrate
    

