1) git clone https://github.com/x86x64/alfa.git .

2) Change the host in /docker/.env and add the host to /etc/hosts

3) docker-compose build --no-cache

4) docker-compose up -d

5) docker exec -it d-app /bin/bash -c "cd laravel && composer install"

6) Open browser
