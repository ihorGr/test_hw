# **Client**

Функционал реализован не до конца.

# **Server**

Взаимодействие c приложением Server осуществляется через АПИ

Сущность Group<br>
1. Получение списка групп <br> 
getGroups ( /api/get_groups POST )
<pre>
{
    "api_key": "db11c36e-afa0-46d9-9a8e-1f1aa7fc797a"
}
</pre>
2. Создание новой группы <br>
add ( /api/add_group POST ) 

<pre>
{
    "api_key": "db11c36e-afa0-46d9-9a8e-1f1aa7fc797a",
    "data": {
        "name": "Group name"
    }
}
</pre>
3. Редактирование группы <br>
edit ( /api/edit_group POST ) <br>
Допустимые поля фильтра: id, name.
<pre>
{
    "api_key": "db11c36e-afa0-46d9-9a8e-1f1aa7fc797a",
    "data": {
        "name": "Group name"
    },
    "filter" : {
    	"name" : "Group name" / "id" : 10 
    }
}
</pre>

4. Удаление группы <br>
delete ( /api/delete_group POST ) <br>
Допустимые поля фильтра: id, name.
<pre>
{
    "api_key": "db11c36e-afa0-46d9-9a8e-1f1aa7fc797a",
    "filter" : {
    	"name" : "Group name" / "id" : 10 
    }
}
</pre>

Сущность User<br>
    
1. Плучение списка всех пользователей <br>
getUsers ( /api/get_users POST )
<pre>
{
    "api_key": "db11c36e-afa0-46d9-9a8e-1f1aa7fc797a"
}
</pre>

2. Получение списка юзеров по id группы <br>
getUsersByGroup ( /api/get_users_by_group/{groupId} POST)

<pre>
{
    "api_key": "db11c36e-afa0-46d9-9a8e-1f1aa7fc797a"
}
</pre>    

3. Создание нового юзера <br>
add ( /api/add_user POST ) <br>
Допустимые поля для data.group: id, name.
<pre>
{
    "api_key": "db11c36e-afa0-46d9-9a8e-1f1aa7fc797a",
    "data": {
    	"name" : "User name",
        "email": "mail@mail.com",
        "group" : {
            "id" : 2 / "name" : "Group name"
        }
    }
}
</pre>

4. Редактирование юзера <br>
edit ( /api/edit_user POST) <br>
Можно редактировать или имя или имейл, 
добавление\удаление юзера в группу реализовано 
в методах addUserGroup и deleteUserGroup. <br>
Фильтр по одному уникальному полю: id или email
        
<pre>
{
    "api_key": "db11c36e-afa0-46d9-9a8e-1f1aa7fc797a",
    "data": {
        "name": "User name", / "email": "mail@mail.com",
    },
    "filter" : {
        "email" : "mail@mail.com" / "id" : 2
    } 
}
</pre>

5. Удаление юзера <br>
delete ( /api/delete_user POST ) <br>
Юзера можно удалять только по одному уникальному полю: id или email
    
<pre>
    {
        "api_key": "db11c36e-afa0-46d9-9a8e-1f1aa7fc797a",
        "filter" : {
            "id" : 2 / "email" : "mail@mail.com"
        }
    }
</pre>
    
6. Добавление юзера в группу <br>
addUserGroup ( /api/add_user_group POST ) <br>
Допустимые поля для data.group: id, name. <br>
Допустимые поля для data.user: id, email.
<pre>
{
    "api_key": "db11c36e-afa0-46d9-9a8e-1f1aa7fc797a",
    "data": {
    	"user" : {
    		"email"  : "mail@mail.com" / "id" : 4
    	},
        "group": {
        	"id" : 4
        }
    }
}
</pre>

7. Удаление юзера из группы <br>
deleteUserGroup ( /api/delete_user_group POST ) <br>
Допустимые поля для data.group: id, name. <br>
Допустимые поля для data.user: id, email.
<pre>
{
    "api_key": "db11c36e-afa0-46d9-9a8e-1f1aa7fc797a",
    "data": {
    	"user" : {
    		"email"  : "mail@mail.com" / "id" : 4
    	},
        "group": {
        	"id" : 4
        }
    }
}
</pre>
