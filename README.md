# CodexImage
Проект backend сервиса 
## Как использовать
Загрузка изображения осуществляется через форму, результаты загрузки отдаются в формате JSON

Поддерживаемые форматы изображений: .jpg, .png

Доступ к изображениям через файл api.php

Поддерживаемые методы:
* **crop** - Обрезка изображения

Формат запроса:

**api.php?action=*crop*&image_id=*[your_image_id]*&width=*[image_width]*&height=*[image_height]*&x=*[crop_left_position]*&y=*[crop_top_position]***

* **resize** - Изменение размеров изображения

Формат запроса:

**api.php?action=*resize*&image_id=*[your_image_id]*&width=*[image_width]*&height=*[image_height]***

* **full** - Получение исходного изображения<br>Формат запроса:

**api.php?action=*full*&image_id=*[your_image_id]***
