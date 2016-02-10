# legit


## Installation

If you don't have homestead installed yet, please follow the instructions laid out in the Laravel documentation and 
pull the vagrant box down for development, [https://laravel.com/docs/master/homestead](https://laravel.com/docs/master/homestead)
 
#### Homestead config
 
```
---
ip: "192.168.10.10"
memory: 4096
cpus: 4
provider: virtualbox

authorize: ~/.ssh/id_rsa.pub

keys:
    - ~/.ssh/id_rsa

folders:
    - map: ~/Documents/workspace/Projects
      to: /home/vagrant/Code
      type: "nfs"

sites:
    - map: legit.app
      to: /home/vagrant/Code/Legit/public  

databases:
    - homestead
    - drivelog
    - cruxcoaches
    - legit

variables:
    - key: 'APP_ENV'
      value: 'local'
    - key: 'APP_DEBUG'
      value: 'true'
 
``` 

#### Install

Run the commands on your local. Clone the repo into your mapped folder for vagrant(see above homestead)
 
```
git clone https://github.com/olx-ssafrica/legit.git
```
```
composer install
```
Write local environment into the project root. See `.env.example`
```
cat > .env <<EOF
>APP_ENV=local
>APP_DEBUG=true
>APP_KEY=SomeRandomKey!!!
>DB_CONNECTION=mysql
>DB_HOST=192.168.10.10
>DB_PORT=3306
>DB_DATABASE=legit
>DB_USERNAME=homestead
>DB_PASSWORD=secret
>CACHE_DRIVER=memcached
>QUEUE_DRIVER=sync
>EOF
```
```
php artisan migrate
```
```
php artisan db:seed
```

### Authorization

All api requests need an auth header to run otherwise you will get a 401 error. If you want to change the default 
country you can do so in your seed files where it sets up a basic country.

```
Authorization: Token apikeysouthafrica
```