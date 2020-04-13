# RedsoftTest

* Проект реализован с использованием Docker



###Выдача товара по ID
* <http://localhost/geiId.php?id=2>

###Выдача товаров по вхождению подстроки в названии
* <http://localhost/getElementName.php?search=газовый>

###Выдача товаров по производителю/производителям
* <http://localhost/getManufactureElement.php?search=СААЗ>
* <http://localhost/getManufactureElement.php?search[]=СААЗ&search[]=SACHS>

###Выдача товаров по разделу (только раздел)
* <http://localhost/getElementBySection.php?sectionId=6>

###Выдача товаров по разделу и вложенным разделам
* <http://localhost/getElementSectionTree.php?sectionId=3>

## Requirements
* Docker

## Install

Clone repo

```
$ git clone https://github.com/merinovroman/RedsoftTest.git
```

Запуск Docker
```
$ cd ./docker-redsoft
$ docker-compose up
```