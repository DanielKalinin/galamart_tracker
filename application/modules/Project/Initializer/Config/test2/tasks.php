<?php

//executor varifier text
return
[
    //////////////////////////STAGE 1 Cards/////////////////////////////////////
    //desk 1_1


    'task1_1_1'=> new TaskInit ( 'franchise', 'security_center', 'Заполните анкету'),
    'task1_1_2'=> new TaskInit ( 'franchise', 'coordinator', 'Скачайте и заполните ДКК',3),
    'task1_1_3'=> new TaskInit ( 'franchise', 'coordinator', 'Скачайте и заполните договор поставки',3),
    'task1_1_4'=> new TaskInit ( 'franchise', 'coordinator', 'Скачайте и заполните договор услуг',3),
    'task1_1_5'=> new TaskInit ( 'franchise', 'coordinator', 'Скачайте и заполните договор сервиса',3),
    'task1_1_6'=> new TaskInit ( 'franchise', 'coordinator', 'Оплатите взнос и прикрепите чек',3),



    //////////////////////////STAGE 2 Desks/////////////////////////////////////
    //тут появляется РП
    //desk2_1

     'task2_1_1_1'=> new TaskInit ( 'franchise', 'project_manager', 'Заполните данные по объекту',1),
     'task2_1_1_2'=> new TaskInit ( 'project_manager', 'development_manager', 'Проверьте данные по объекту',2),
     'task2_1_2'=> new TaskInit ( 'franchise', 'project_manager', 'Подпишите договор аренды',7),

     //////////////////////////STAGE 3 Desks/////////////////////////////////////
      //desk 3_1
     'task3_1_1'=> new TaskInit ( 'study_center', 'project_manager', 'Подберите управляющего магазина',14),
     'task3_1_2'=> new TaskInit ( 'study_center', 'project_manager', 'Управляющий магазина обучается',20),
     'task3_1_3'=> new TaskInit ( 'study_center', 'project_manager', 'Подберите линейный персонал',14),
     'task3_1_4'=> new TaskInit ( 'study_center', 'project_manager', 'Линейный персонал обучается',20),
     'task3_1_5'=> new TaskInit ( 'franchise', 'project_manager', 'Устройте на работу линейный персонал',7),
     'task3_1_6'=> new TaskInit ( 'franchise', 'project_manager', 'Устройте на работу управляющего магазином',1),
     'task3_1_7'=> new TaskInit ( 'franchise', 'project_manager', 'Закажите униформу',1),


    //desk3_2
     'task3_2_1'=> new TaskInit ( 'franchise', 'project_manager', 'Отремонтируйте помещение и прекрепите фото',20),
     'task3_2_2'=> new TaskInit ( 'franchise', 'project_manager', 'Помойте помещение и прикрепите фото'),
     'task3_2_3'=> new TaskInit ( 'franchise', 'project_manager', 'Фото вывески'),
     'task3_2_4'=> new TaskInit ( 'franchise', 'project_manager', 'Фото навигации'),
     'task3_2_5'=> new TaskInit ( 'franchise', 'project_manager', 'Внутренне оформление'),


    //desk3_3
     'task3_3_14'=> new TaskInit ( 'technologist', 'project_manager', 'Планировка магазина'),
     'task3_3_1'=> new TaskInit ( 'franchise', 'project_manager', 'Файл с заказом торгоборудования'),
     'task3_3_2'=> new TaskInit ( 'franchise', 'project_manager', 'Оплатите торгоборудование'),
     'task3_3_3'=> new TaskInit ( 'franchise', 'project_manager', 'Соберите и расставьте торгоборудование'),
     'task3_3_4'=> new TaskInit ( 'merchandiser', 'project_manager', 'Разработка зонирования'),
     'task3_3_5'=> new TaskInit ( 'logist', 'project_manager', 'Установочный заказ'),
     'task3_3_6'=> new TaskInit ( 'advert_manager', 'project_manager', 'Заказ товара под листовку'),
     'task3_3_7'=> new TaskInit ( 'franchise', 'project_manager', 'Оплатите заказ'),
     'task3_3_8'=> new TaskInit ( 'franchise', 'project_manager', 'Приемка товаров'),
     'task3_3_9'=> new TaskInit ( 'franchise', 'project_manager', 'Выкладка'),
     'task3_3_10'=> new TaskInit ( 'franchise', 'project_manager', 'Расстановка ценников'),
     'task3_3_11'=> new TaskInit ( 'franchise', 'project_manager', 'Клиннинг'),
     'task3_3_12'=> new TaskInit ( 'price_manager', 'project_manager', 'Подготовкка цен для партнера'),
     'task3_3_13'=> new TaskInit ( 'franchise', 'project_manager', 'Установка цен в базе партнера'),



    //////desk3_4
     'task3_4_1'=> new TaskInit ( 'franchise', 'project_manager', 'Закажите антикражное оборудование'),
     'task3_4_2'=> new TaskInit ( 'franchise', 'project_manager', 'Оплата антикражного оборудование'),
     'task3_4_3'=> new TaskInit ( 'franchise', 'project_manager', 'Монтаж антикражного оборудования'),
     'task3_4_4'=> new TaskInit ( 'franchise', 'project_manager', 'Кассы'),
     'task3_4_5'=> new TaskInit ( 'franchise', 'project_manager', 'Эквайринг'),
     'task3_4_6'=> new TaskInit ( 'franchise', 'project_manager', 'Инкасация'),
     'task3_4_7'=> new TaskInit ( 'franchise', 'project_manager', 'Заказ видеонаблюдения'),
     'task3_4_8'=> new TaskInit ( 'franchise', 'project_manager', 'Оплатите видеонаблюдения'),
     'task3_4_9'=> new TaskInit ( 'franchise', 'project_manager', 'Монтаж видеонаблюдения'),
     'task3_4_10'=> new TaskInit ( 'franchise', 'project_manager', 'Печати и штампы'),
     'task3_4_11'=> new TaskInit ( 'franchise', 'project_manager', 'ИТ инфраструктура'),
     'task3_4_12'=> new TaskInit ( 'franchise', 'project_manager', 'Наличие интернета'),
     'task3_4_13'=> new TaskInit ( 'franchise', 'project_manager', 'Покупка оргтехники'),
     'task3_4_14'=> new TaskInit ( 'franchise', 'project_manager', 'Настройка рабочих мест и сети'),
     'task3_4_15'=> new TaskInit ( 'technical_support', 'project_manager', 'Настройка базы'),
     'task3_4_16'=> new TaskInit ( 'technical_support', 'franchise', 'Тестирование ИТ систем'),


     ///desk3_5

     'task3_5_1'=> new TaskInit ( 'franchise', 'project_manager', 'Подготовка листовок'),
     'task3_5_2'=> new TaskInit ( 'franchise', 'project_manager', 'Сценарий'),
     'task3_5_3'=> new TaskInit ( 'franchise', 'project_manager', 'Оплата подрядчику'),

    //////////////////////////STAGE 4 Desks/////////////////////////////////////
     'task4_1_1'=> new TaskInit ( 'curator', 'project_manager', 'Заполните чеклист'),



];