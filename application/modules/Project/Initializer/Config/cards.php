<?php

include_once MOD . '/Project/Initializer/Config/forms.php';
return
[
    //////////////////////////STAGE 1 Cards/////////////////////////////////////
    //desk 1_1
    //

    'card1_1_0'=>new CardInit('Телефонная аудиенция',$form1_1_0,['task1_1_0'],
    [],
    [
        'card1_1_1'
    ]),

    'card1_1_1'=> new CardInit('Сбор сканов учредительных документов', $form1_1_1, ['task1_1_1'],
            [
                'card1_1_0',
            ],
            [
                'card1_1_2',//подписание ДКК
                'card1_1_3',//подписание договора поставки
                'card1_1_4',//договор услуг
                'card1_1_5',//договор сервиса
            ]),

    'card1_1_2'=> new CardInit('Подписание договора комерческой концессии',$form1_1_2, ['task1_1_2'],//подписание дкк
            [
                'card1_1_1'//заполнение анкеты
            ],
            [
                'card1_1_6',//оплата поушального взноса
            ]),

    'card1_1_3'=> new CardInit('Подписание договора поставки',$form1_1_3, ['task1_1_3'],//подписание договора поставки
            [
                'card1_1_1'//заполнение анкеты
            ],
            [

            ]),

    'card1_1_4'=> new CardInit('Подписание договора услуг по запуску магазина',$form1_1_4, ['task1_1_4'],//подписание договора услуг
            [
                'card1_1_1'//заполнение анкеты
            ],
            [

            ]),

    'card1_1_5'=> new CardInit('Подписание договора сервисного сопровождения',$form1_1_5, ['task1_1_5'],//подписание договора сервиса
            [
                'card1_1_1'//заполнение анкеты
            ],
            [

            ]),

    'card1_1_6'=> new CardInit('Аванс',$form1_1_6, ['task1_1_6'],//оплата поушального взноса
            [
                'card1_1_2'//Подписание ДКК
            ],
            [
                'card1_1_7'
            ]),




    //////////////////////////STAGE 2 Cards/////////////////////////////////////
    //desk2_1

            'card2_1_1'=> new CardInit('Данные по объекту',$form2_1_1, ['task2_1_1'],//данные по объекту+подтверждение!!!!!!!!!!!!!
            [

            ],
            [
                    'card2_1_2'
            ]),


            'card2_1_2'=> new CardInit('Назначение руководителя проекта',$form2_1_2, ['task2_1_2'],//
            [
                   'card2_1_1'
            ],
            [
                    'card2_1_6'
            ]),

            'card2_1_6'=> new CardInit('Назначение выездного тренера',$form2_1_6, ['task2_1_6'],//
            [
                   'card2_1_2'
            ],
            [
                    'card2_1_3'
            ]),

            'card2_1_3'=> new CardInit('Согласование старта проекта',$form2_1_3, ['task2_1_3'],//
            [
                   'card2_1_6'
            ],
            [
                   'card2_1_4'
            ]),

            'card2_1_4'=> new CardInit('Расчет инвест-привлекательности проекта ',$form2_1_4, ['task2_1_4'],//
            [
                   'card2_1_3'
            ],
            [
                   'card2_1_5'
            ]),

            'card2_1_5'=> new CardInit('Договор аренды',$form2_1_5, ['task2_1_5'],//
            [
                   'card2_1_4'
            ],
            [

            ]),





      //////////////////////////STAGE 3 Cards/////////////////////////////////////
      //desk 3_1

      'card3_1_0'=> new CardInit('Разработка штатного расписания',$form3_1_0, ['task3_1_0'],
            [

            ],
            [
                'card3_1_1',
                'card3_1_2',
            ]),


    'card3_1_1'=> new CardInit('Подбор линейного персонала',$form3_1_1, ['task3_1_1'],
            [
                'card3_1_0'
            ],
            [
                'card3_1_3',
            ]),


     'card3_1_2'=> new CardInit('Подбор управляющего магазином',$form3_1_2, ['task3_1_2'],
            [
                'card3_1_0',
            ],
            [
                'card3_1_4',
            ]),

     'card3_1_3'=> new CardInit('Трудоустройство линейного персонала',$form3_1_3, ['task3_1_3'],
            [
                'card3_1_1',
            ],
            [
                'card3_1_5',
                'card3_1_6',
            ]),

     'card3_1_4'=> new CardInit('Трудоустройство управляющего магазином',$form3_1_4, ['task3_1_4'],
            [
                'card3_1_2'
            ],
            [
                'card3_1_5',
                'card3_1_7',
            ]),

     'card3_1_5'=> new CardInit('Заказ униформы',$form3_1_5, ['task3_1_5'],
            [
                'card3_1_4',
                'card3_1_3',
            ],
            [

            ]),

     'card3_1_6'=> new CardInit('Обучение линейного персонала',$form3_1_6, ['task3_1_6'],
            [
                'card3_1_3',
            ],
            [
                'card3_4_8',
                'card3_4_5',
            ]),

     'card3_1_7'=> new CardInit('Обучение управляющего магазином',$form3_1_7, ['task3_1_7'],
            [
                'card3_1_4'
            ],
            [
                'card3_4_8',
            ]),





    //desk3_2

    'card3_2_0'=> new CardInit('Разработка техзадания на ремонт',$form3_2_0, ['task3_2_0'],
            [
                'card3_4_1'
            ],
            [
                'card3_2_1',
            ]),


    'card3_2_1'=> new CardInit('Ремонт помещения',$form3_2_1, ['task3_2_1'],
            [
                'card3_2_0'
            ],
            [
                'card3_2_5',
            ]),




    'card3_2_2'=> new CardInit('Изготовление и монтаж вывески',$form3_2_2, ['task3_2_2'],
            [

            ],
            [

            ]),




    'card3_2_3'=> new CardInit('Навигация в ТЦ',$form3_2_3, ['task3_2_3'],
            [

            ],
            [

            ]),




    'card3_2_4'=> new CardInit('Заказ внутреннего оформления',$form3_2_4, ['task3_2_4'],
            [
                 'card3_4_1',
            ],
            [
                 'card3_2_6',
            ]),




    'card3_2_5'=> new CardInit('Клиннинг',$form3_2_5, ['task3_2_5'],
            [
                'card3_2_1',
            ],
            [
                 'card3_3_4',
                 'card3_5_3',
                 'card3_5_7',

            ]),


    'card3_2_6'=> new CardInit('Монтаж внутреннего оформления',$form3_2_6, ['task3_2_6'],
            [
                'card3_2_4',
                'card3_3_4',
            ],
            [


            ]),




//desk3_3

    'card3_3_1'=> new CardInit('Планировка магазина',$form3_3_1, ['task3_3_1'],
            [

            ],
            [
                'card3_4_1',
                'card3_3_2',
            ]),

    'card3_3_2'=> new CardInit('Заказ торгоборудования',$form3_3_2, ['task3_3_2'],
            [
                'card3_3_1',
            ],
            [
                'card3_3_3',
            ]),

    'card3_3_3'=> new CardInit('Оплата торгоборудования',$form3_3_3, ['task3_3_3'],
            [
                'card3_3_2',
            ],
            [
                'card3_3_4',
            ]),

    'card3_3_4'=> new CardInit('Сборка и расстановка торгоборудования',$form3_3_4, ['task3_3_4'],
            [
                'card3_2_5',
                'card3_3_3',
            ],
            [
                'card3_2_6',
            ]),




    //////desk3_4


    'card3_4_1'=> new CardInit('Зонирование',$form3_4_1, ['task3_4_1'],
            [
                'card3_3_1'
            ],
            [
                'card3_2_0',
                'card3_2_4',
                'card3_4_2',
                'card3_5_1',
                'card3_5_4',
                'card3_6_1',
                'card3_6_2',
                'card3_6_3',
            ]),

    'card3_4_2'=> new CardInit('Установочный заказ',$form3_4_2, ['task3_4_2'],
            [
                'card3_4_1'
            ],
            [
                'card3_4_3',

            ]),

    'card3_4_3'=> new CardInit('Заказ товара под листовку',$form3_4_3, ['task3_4_3'],
            [
                'card3_4_2'
            ],
            [
                'card3_4_4',
                'card3_4_6',
                'card3_8_3',
            ]),

    'card3_4_4'=> new CardInit('Оплата заказа',$form3_4_4, ['task3_4_4'],
            [
                'card3_4_3',
            ],
            [
                'card3_4_5',

            ]),

    'card3_4_5'=> new CardInit('Приемка товара',$form3_4_5, ['task3_4_5'],
            [
                'card3_4_4',
                'card3_1_6',
            ],
            [
                'card3_4_8',
            ]),

    'card3_4_6'=> new CardInit('Подготовка цен для партнера',$form3_4_6, ['task3_4_6'],
            [
                'card3_4_3'
            ],
            [
                'card3_4_7',
            ]),

    'card3_4_7'=> new CardInit('Установка цен в базе партнера',$form3_4_7, ['task3_4_7'],
            [
                'card3_4_6'
            ],
            [
                'card3_4_9',
            ]),

    'card3_4_8'=> new CardInit('Выкладка',$form3_4_8, ['task3_4_8'],
            [
                'card3_4_5',
            ],
            [
                'card3_4_9',
            ]),

    'card3_4_9'=> new CardInit('Расстановка ценников',$form3_4_9, ['task3_4_9'],
            [
                'card3_4_8',
                'card3_4_7',
            ],
            [
                'card3_4_10',
            ]),

    'card3_4_10'=> new CardInit('Уборка/клининг',$form3_4_10, ['task3_4_10'],
            [
                'card3_4_9',
            ],
            [

            ]),

    //////desk3_5


   'card3_5_1'=> new CardInit('Заказ антикражного оборудования+расходники',$form3_5_1, ['task3_5_1'],
            [
                'card3_4_1',
            ],
            [
                'card3_5_2',
            ]),

 'card3_5_2'=> new CardInit('Оплата антикражного оборудования и расходников',$form3_5_2, ['task3_5_2'],
            [
                'card3_5_1',
            ],
            [
                'card3_5_3',
            ]),

 'card3_5_3'=> new CardInit('Монтаж антикражного оборудования',$form3_5_3, ['task3_5_3'],
            [
                'card3_5_2',
                'card3_2_5',
            ],
            [

            ]),

 'card3_5_4'=> new CardInit('Согласование схемы расстановки видеокамер',$form3_5_4, ['task3_5_4'],
            [
                'card3_4_1',
            ],
            [
                'card3_5_5',
            ]),

 'card3_5_5'=> new CardInit('Заказ видеонаблюдения',$form3_5_5, ['task3_5_5'],
            [
                'card3_5_4',
            ],
            [
                'card3_5_6',
            ]),

 'card3_5_6'=> new CardInit('Оплата видеонаблюдения',$form3_5_6, ['task3_5_6'],
            [
                'card3_5_5',
            ],
            [
                'card3_5_7',
            ]),

 'card3_5_7'=> new CardInit('Монтаж видеонаблюдения',$form3_5_7, ['task3_5_7'],
            [
                'card3_5_6',
                'card3_2_5',
            ],
            [

            ]),


  //////desk3_6


   'card3_6_1'=> new CardInit('Кассы',$form3_6_1, ['task3_6_1'],
            [
                'card3_4_1',
            ],
            [

            ]),

 'card3_6_2'=> new CardInit('Эквайринг',$form3_6_2, ['task3_6_2'],
            [
                'card3_4_1',
            ],
            [

            ]),

 'card3_6_3'=> new CardInit('Инкасация',$form3_6_3, ['task3_6_3'],
            [
                'card3_4_1',
            ],
            [

            ]),

 'card3_6_4'=> new CardInit('Штампы и печати',$form3_6_4, ['task3_6_4'],
            [

            ],
            [

            ]),

    //////desk3_7


  'card3_7_1'=> new CardInit('Наличие интернета',$form3_7_1, ['task3_7_1'],
            [

            ],
            [
                'card3_7_2',
            ]),

 'card3_7_2'=> new CardInit('Покупка оргтехники',$form3_7_2, ['task3_7_2'],
            [
                'card3_7_1',
            ],
            [
                'card3_7_3'
            ]),

 'card3_7_3'=> new CardInit('Настройка сети и сборка рабочих мест',$form3_7_3, ['task3_7_3'],
            [
                'card3_7_2',
            ],
            [
                'card3_7_4'
            ]),

 'card3_7_4'=> new CardInit('Настройка базы',$form3_7_4, ['task3_7_4'],
            [
                'card3_7_3',
            ],
            [
                'card3_7_5'
            ]),


 'card3_7_5'=> new CardInit('Тестирование ИТ систем',$form3_7_5, ['task3_7_5'],
            [
                'card3_7_4',
            ],
            [

            ]),

 //////desk3_8


   'card3_8_1'=> new CardInit('Выбор сценария',$form3_8_1, ['task3_8_1'],
            [

            ],
            [
                'card3_8_2'
            ]),

 'card3_8_2'=> new CardInit('Оплата подрядчику',$form3_8_2, ['task3_8_2'],
            [
                'card3_8_1',
            ],
            [

            ]),

 'card3_8_3'=> new CardInit('Подготовка листовки',$form3_8_3, ['task3_8_3'],
            [
                'card3_4_3',
            ],
            [

            ]),


 //////desk4_1

    'card4_1_1'=> new CardInit('Чеклист',$form4_1_1, ['task4_1_1'],
            [

            ],
            [

            ]),

];