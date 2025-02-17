Step to Install
git clone https://github.com/aftabhafeez9/Laravel-Tenancy.git
composer install 
php artisan migrate

and run the following API Requests 


**Register New User**
curl --location 'http://localhost/tenantApp/public/api/register' \
--form 'name="zaki minhas"' \
--form 'email="zaki@test.com"' \
--form 'password="123456789"'

**Approve User**
curl --location --request POST 'http://localhost/tenantApp/public/api/admin/approve/2'

**Login Tenant**
curl --location 'http://localhost/tenantApp/public/api/tenantlogin' \
--form 'email="zaki@test.com"' \
--form 'password="123456789"'

**Create Product**
curl --location 'http://localhost/tenantApp/public/api/createproduct' \
--header 'Authorization: Bearer auth_token' \
--form 'name="Product 2"' \
--form 'price="20"'

**Product List**
curl --location 'http://localhost/tenantApp/public/api/products' \
--header 'Authorization: Bearer 2|AfaO00awDiooDwCRaw451UYjLmXgbeEIWUD8yh0vce13c3bf'

**Create Order**
curl --location 'http://localhost/tenantApp/public/api/orders' \
--header 'Authorization: Bearer 2|AfaO00awDiooDwCRaw451UYjLmXgbeEIWUD8yh0vce13c3bf' \
--form 'product_id="1"' \
--form 'quantity="5"'
