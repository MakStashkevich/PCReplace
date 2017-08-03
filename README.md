# PCReplace \ Русский
Плагин для замены пк блоков на пе блоки. Очень удобно при портировании карт :+1:

### Как использовать?
Чтобы заменить блоки вам понадобиться **кость** (можно изменить).
При тапе ею будет происходить замена всех пк блоков в определенном радиусе (можно изменить)

В файле [PCReplace](https://github.com/MakStashkevich/PCReplace/blob/master/src/ms/PCReplace.php) можно изменить эти настройки под себя.

```php
const REPLACE_ITEM = 352; //id вещи, которой можно будет тапом заменять блоки
const REPLACE_RADIUS = 15; //радиус, собственно на котором и будут заменяться блоки
```

Также, если вам нужно заменить блоки на всей карте, вы можете использовать команду **/pcreplace.**
Замена будет происходить именно на карте, на которой вы введете команду.

*P.S. После ввода команды, сервер может на некоторое время зависнуть. Это связано с заменой блоков на всех чанках карты. Чем больше у вас карта, тем дольше будут заменяться блоки и тем дольше сервер будет находяться в "зависшем" состоянии.*

### Список того, что хочу добавить:
* Добавить больше блоков для замены (реализованы еще не все)
* Добавить мультиязычность
* Добавить больше функций, помимо замены

***

# PCReplace \ English
Pocketmine plugin to replace PC blocks in PE blocks. Very convenient for porting maps :+1:

### How to use?
To replace the blocks you will need **bone** (you can change).
At tap it will replace all pc blocks in a certain radius (you can change)

In the file [PCReplace](https://github.com/MakStashkevich/PCReplace/blob/master/src/ms/PCReplace.php) you can change these settings for you.

```php
const REPLACE_ITEM = 352; // id of the thing that can be replaced by blocks
const REPLACE_RADIUS = 15; // the radius, on which the blocks will be replaced
```

Also, if you need to replace blocks on the entire map, you can use the **/pcreplace** command
Replacement will occur exactly on the map on which you enter the command.

*P.S. After entering the command, the server may hang for a while. This is due to the replacement of blocks on all the chunks of the card. The more you have a card, the longer blocks will be replaced and the longer the server will be in a "hung" state.*

### TODO:
* Add all PC blocks to replace
* Add multilanguage
* Add more functions
