# poll
Запуск
- клонировать репозиторий;
- запустить файл run.sh в папке docker;
- после создания контейнера, перейти в директорию symfony и выполнить в консоли следующее
    - установить зависимости командой (для линукса вместо yarn использовать npm)
        - composer install
        - yarn install
    - для подключения Vue ( см ссылку https://symfony.com/doc/current/frontend/encore/vuejs.html)
        - yarn encore dev-server
    - скомпилировать фронт  командой
        - yarn run dev
    - перейти в контейнер symfony и выполнить миграции командой
        - php bin/console doctrine:migrations:migrate
        - php bin/console doctrine:fixtures:load
      - посмотреть маршруты
        - php bin/console debug:router

  приложение запускается на https://localhost:8000 

JWT
- для получения JWT необходимо сделать запрос постом по маршруту вида /api/login_check 
- передать json вида {"username":"nazar45@hotmail.com","password":"123456789"}. если ок, в ответе приходит
json вида 
{"token": "TTT", "refresh_token": "RRR"}
- для того, чтобы получить ответ по маршрутам вида /api/auth/.../... необходимо в заголовке запроса передать Authorization: Bearer TTT, где TTT - полученный ранее токен
иначе вернется 
json вида 
{"code": 401, "message": "JWT Token not found"}
- при истечении времени жизни токена необходимо получить новый токен, делая пост-запрос вида /api/token/refresh с параметром json вида
{"refresh_token": "RRR"}, где RRR - полученный ранее рефреш-токен. если ок, в ответе приходит
json вида
{"token": "NNN", "refresh_token": "RRR"}
- теперь для того, чтобы получить ответ по маршрутам вида /api/auth/.../... необходимо в заголовке запроса передать Authorization: Bearer NNN, где NNN - полученный новый токен
иначе вернется json вида
{"code": 401, "message": "JWT Token not found"}
- рефреш-токен действует до истечения своего срока жизни, после чего необходимо снова логиниться.
- при разлогинивании пользователя нужно выполнить пост-запрос по маршруту /api/token/invalidate, передав json вида
{"refresh_token": "RRR"}, 
- который удаляет рефреш-токен.




    

