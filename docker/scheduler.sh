while [ true ]
do
  php artisan schedule:run --verbose --no-interaction
  sleep 60
done
