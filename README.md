.checkout
=========

A Symfony project created on March 15, 2017, 1:30 am.


Przykładowe zapytania możliwe w projekcie. Do użycia w aplikacji REST np. Postman:

PUT
http://restapp.dev/app_dev.php/book/create
Authorization Bearer token
{

"title": "Pan Wlodyjowski"

}

PUT
{
	
"title": "Pan Wlodyjowski",
	
"author": "58e41dee750c9705f1f71bd1"

}

(lista wszystkich ksiazek)
GET
http://restapp.dev/app_dev.php/book/

(podglad ksiazki o danym id)
GET
http://restapp.dev/app_dev.php/book/get/58e4c217d8df271d1c003e9e

(update ksiazki o danym id)
POST
http://restapp.dev/app_dev.php/book/update/58e4c217d8df271d1c003e9e
{
	
"title": "Pan Wlodyjowski 2",
	
"author": "58e41dee750c9705f1f71bd1"

}

(usuniecie ksiazki o danym id)
DELETE
http://restapp.dev/app_dev.php/book/58e4c169d8df271d1c003e9d

(szukanie ksiazki o czastkowym tytule)
GET
http://restapp.dev/app_dev.php/book/search/Pan

(pobranie nowego tokenu)
POST
http://restapp.dev/app_dev.php/user/login_check
{
"_username": "user2",
"_password": "123"
}

(pobranie info o aktualnie zalogowanym uzytkowniku)
GET
http://restapp.dev/app_dev.php/user/info

(rejestracja, min 6 znakow w hasle)
PUT
http://restapp.dev/app_dev.php/user/register
{
"username": "testuser",
"email": "testtest@google.com",
"password": "123456"
}
