# poll
Запуск
- клонировать репозиторий;
- запустить файл run.sh в папке docker;
- после создания контейнера, перейти в контейнер symfony и выполнить в его консоли следующее
    - установить зависимости командой (для линукса вместо yarn использовать npm)
        - composer install
        - yarn install
    - для подключения Vue ( см ссылку https://symfony.com/doc/current/frontend/encore/vuejs.html)
        - yarn encore dev-server
    - скомпилировать фронт  командой
        - yarn run dev
        - перейти в контейнер symfony и выполнить миграции командой
            - php bin/console doctrine:migrations:migrate

  приложение запускается на https://localhost:443 (у меня на  https://localhost )
    

