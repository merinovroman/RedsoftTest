# RedsoftTest

* ������ ���������� � �������������� Docker



###������ ������ �� ID
* <http://localhost/geiId.php?id=2>

###������ ������� �� ��������� ��������� � ��������
* <http://localhost/getElementName.php?search=�������>

###������ ������� �� �������������/��������������
* <http://localhost/getManufactureElement.php?search=����>
* <http://localhost/getManufactureElement.php?search[]=����&search[]=SACHS>

###������ ������� �� ������� (������ ������)
* <http://localhost/getElementBySection.php?sectionId=6>

###������ ������� �� ������� � ��������� ��������
* <http://localhost/getElementSectionTree.php?sectionId=3>

## Requirements
* Docker

## Install

Clone repo

```
$ git clone https://github.com/merinovroman/RedsoftTest.git
```

������ Docker
```
$ cd ./docker-redsoft
$ docker-compose up
```