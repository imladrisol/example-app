## CRUD API Laravel9 project with Swagger documentation

### run project:
- ./vendor/bin/sail build
- ./vendor/bin/sail up
### run in laravel container:
- php artisan migrate 
- php artisan migrate --env=testing 
- php artisan l5-swagger:generate
- API Documentation: http://localhost/api/documentation
- Run test: php artisan test tests/Feature/IncidentTest.php
