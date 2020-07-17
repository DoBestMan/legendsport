1. Clone repo
2. docker-compose up -d
3. .env.dist > .env

Run inside app container:

4. Composer install 
5. artisan migrate
6. artisan db:seed

add legendsports.local, backstage.legendsports.local to hosts file

Run inside node container:

1. yarn install
2. yarn run dev << node container will do this automatically
