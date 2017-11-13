<?php
include_once MOD."/File/SystemFiles.php";
//executor varifier text
return
[
    //////////////////////////STAGE 1 Cards/////////////////////////////////////
    //desk 1_1


    'task1_1_0'=> new TaskInit ( 'coordinator', 'franchise','Ожидайте телефонного звонка от представителя',1),
    'task1_1_1'=> new TaskInit ( 'franchise', 'security_center', 'Заполните анкету',100),
    'task1_1_2'=> new TaskInit ( 'franchise', 'coordinator', 'Скачайте и заполните'.DKK,3),
    'task1_1_3'=> new TaskInit ( 'franchise', 'coordinator', 'Скачайте и заполните договор поставки'.DKK,3),
    'task1_1_4'=> new TaskInit ( 'franchise', 'coordinator', 'Скачайте и заполните договор услуг'.DKK,3),
    'task1_1_5'=> new TaskInit ( 'franchise', 'coordinator', 'Скачайте и заполните договор сервиса'.DKK,3),
    'task1_1_6'=> new TaskInit ( 'franchise', 'coordinator', 'Оплатите взнос и прикрепите чек',3),




    //////////////////////////STAGE 2 Desks/////////////////////////////////////
    //тут появляется РП
    //desk2_1

     'task2_1_1'=> new TaskInit ( 'franchise', 'arend_center', 'Заполните данные по объекту',1),
     'task2_1_2'=> new TaskInit ( 'coordinator', 'none', 'Назначьте руководителя проекта',1),
     'task2_1_6'=> new TaskInit ( 'coordinator', 'none', 'Назначьте выездного тренера',1),
     'task2_1_3'=> new TaskInit ( 'arend_center', 'development_manager', 'Согласование старта проекта',2),
     'task2_1_4'=> new TaskInit ( 'analyst', 'project_manager', 'Расчитайте инвест-привлекательность объекта',1),
     'task2_1_5'=> new TaskInit ( 'franchise', 'project_manager', 'Подпишите договор аренды',3),

     //////////////////////////STAGE 3 Desks/////////////////////////////////////
      //desk 3_1
     'task3_1_0'=> new TaskInit ( 'selection_center', 'project_manager', 'Разработайте штатное расписание',2),
     'task3_1_1'=> new TaskInit ( 'selection_center', 'project_manager', 'Подберите управляющего магазина',45),
     'task3_1_2'=> new TaskInit ( 'selection_center', 'project_manager', 'Подберите линейный персонал',30),
     'task3_1_3'=> new TaskInit ( 'franchise', 'project_manager', 'Трудоустройте линейный персонал',2),
     'task3_1_4'=> new TaskInit ( 'franchise', 'project_manager', 'Трудоустройте управляющего магазином',2),
     'task3_1_5'=> new TaskInit ( 'franchise', 'project_manager', 'Закажите униформу',1),
     'task3_1_6'=> new TaskInit ( 'study_center', 'project_manager', 'Обучение линейного персонала',12),
     'task3_1_7'=> new TaskInit ( 'study_center', 'project_manager', 'Обучение управляющего магазином',28),


    //desk3_2
     'task3_2_0'=> new TaskInit ( 'project_manager', 'franchise', 'Составьте техзадание на ремонт',2),
     'task3_2_1'=> new TaskInit ( 'franchise', 'project_manager', 'Отремонтируйте помещение и прекрепите фото',21),
     'task3_2_2'=> new TaskInit ( 'franchise', 'project_manager', 'Фото вывески',39),
     'task3_2_3'=> new TaskInit ( 'franchise', 'project_manager', 'Фото навигации',20),
     'task3_2_4'=> new TaskInit ( 'franchise', 'project_manager', 'Закажите внутреннее оформление',8),
     'task3_2_5'=> new TaskInit ( 'franchise', 'project_manager', 'Клиннинг фотоотчет', 1),
     'task3_2_6'=> new TaskInit ( 'franchise', 'project_manager', 'Монтаж внутреннего офрмления', 2),


    //desk3_3 deadlines
     'task3_3_1'=> new TaskInit ( 'technologist', 'project_manager', 'Планировка магазина',2),
     'task3_3_2'=> new TaskInit ( 'franchise', 'project_manager', 'Закажите торгоборудование',2),
     'task3_3_3'=> new TaskInit ( 'franchise', 'project_manager', 'Оплатите торгоборудование',1),
     'task3_3_4'=> new TaskInit ( 'franchise', 'project_manager', 'Соберите и расставьте ТО',3),


    //desk3_4
     'task3_4_1'=> new TaskInit ( 'merchandiser', 'project_manager', 'Зонирование',2),
     'task3_4_2'=> new TaskInit ( 'logist', 'project_manager', 'Подготовьте установочный заказ',7),
     'task3_4_3'=> new TaskInit ( 'advert_manager', 'project_manager', 'Закажите товар под листовку для открытия',3),
     'task3_4_4'=> new TaskInit ( 'franchise', 'project_manager', 'Оплатите заказ',7),
     'task3_4_5'=> new TaskInit ( 'franchise', 'project_manager', 'Приемка товара',9),
     'task3_4_6'=> new TaskInit ( 'price_manager', 'franchise', 'Подготовка цен на товар',1),
     'task3_4_7'=> new TaskInit ( 'franchise', 'project_manager', 'Прогрузка цен в базу партнера',1),
     'task3_4_8'=> new TaskInit ( 'franchise', 'project_manager', 'Выкладка товара',10),
     'task3_4_9'=> new TaskInit ( 'franchise', 'project_manager', 'Расставьте ценники',2),
     'task3_4_10'=> new TaskInit ( 'franchise', 'project_manager', 'Уберите помещение/клиннинг',1),




    //////desk3_5
     'task3_5_1'=> new TaskInit ( 'franchise', 'project_manager', 'Закажите антикражное оборудование',2),
     'task3_5_2'=> new TaskInit ( 'franchise', 'project_manager', 'Оплата антикражного оборудование',2),
     'task3_5_3'=> new TaskInit ( 'franchise', 'project_manager', 'Монтаж антикражного оборудования',5),
     'task3_5_4'=> new TaskInit ( 'security_center', 'franchise', 'Составьте схему расстановки камер',1),
     'task3_5_5'=> new TaskInit ( 'franchise', 'project_manager', 'Закажите видеонаблюдение',3),
     'task3_5_6'=> new TaskInit ( 'franchise', 'project_manager', 'Оплатите видеонаблюдение',3),
     'task3_5_7'=> new TaskInit ( 'franchise', 'project_manager', 'Установите видеонаблюдение',5),


    ///////desk3_6
     'task3_6_1'=> new TaskInit ( 'franchise', 'project_manager', 'Покупка и регистрация касс',7),
     'task3_6_2'=> new TaskInit ( 'franchise', 'project_manager', 'Заключение договора эквайринга',14),
     'task3_6_3'=> new TaskInit ( 'franchise', 'project_manager', 'Заключите договор инкасации с банком',14),
     'task3_6_4'=> new TaskInit ( 'franchise', 'project_manager', 'Купите печати и штампы',14),



     ///desk3_7
     'task3_7_1'=> new TaskInit ( 'franchise', 'project_manager', 'Интернет не менее 5 Мбит/с',2),
     'task3_7_2'=> new TaskInit ( 'franchise', 'project_manager', 'Заказ оргтехники',3),
     'task3_7_3'=> new TaskInit ( 'franchise', 'project_manager', 'Настройка сети и сборка рабочих мест',3),
     'task3_7_4'=> new TaskInit ( 'technical_support', 'project_manager', 'Настройка базы',3),
     'task3_7_5'=> new TaskInit ( 'technical_support', 'franchise', 'Тестирование ИТ систем',1),


    ///desk3_8
     'task3_8_1'=> new TaskInit ( 'franchise', 'project_manager', 'Выберите сценарий',1),
     'task3_8_2'=> new TaskInit ( 'franchise', 'project_manager', 'Оплата подрядчику',2),
     'task3_8_3'=> new TaskInit ( 'designer', 'project_manager', 'Разработайте макет листовки',2),

    //////////////////////////STAGE 4 Desks/////////////////////////////////////
     'task4_1_1'=> new TaskInit ( 'curator', 'project_manager', 'Заполните чеклист',2),



];