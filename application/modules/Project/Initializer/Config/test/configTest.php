<?php

include_once MOD . '/Project/Initializer/Initializer.php';
//use _
/*

 */
//project name of stages
$project = new ProjectInit ( ['stage1', 'stage2']);


$stages = [
    'stage1' => new StageInit ( 'documents', ['desk1_1']),
    'stage2' => new StageInit ( 'tasker', ['desk2_1', 'desk2_2'])
];




$desks = [
    'desk1_1' => new DeskInit ( 'docs', ['card1_1']),
    'desk2_1' => new DeskInit ( 'tasker1', ['card2_1', 'card2_2', 'card2_3']),
    'desk2_2' => new DeskInit ( 'tasker2', ['card3_1'])
];
$form1_1 = [
    ['type' => 'text', 'name' => 'text1_1_1', 'text' => 'Адрес'],
    ['type' => 'text', 'name' => 'text1_1_2', 'text' => 'Площадь'],
    ['type' => 'text', 'name' => 'text1_1_3', 'text' => 'ТРЦ'],
    ['type' => 'text', 'name' => 'text1_1_4', 'text' => 'Поле'],
    ['type' => 'file', 'name' => 'file228', 'text' => 'Your mom'],
        ];

$form2_1 = [];

$form2_2 = [
    ['type' => 'file', 'name' => 'file2_2_1', 'text' => 'Фото отчет'],
        ];

$form2_3 = [
    ['type' => 'text', 'name' => 'text2_3_1', 'text' => 'Длина магазина'],
    ['type' => 'text', 'name' => 'text2_3_2', 'text' => 'Ширина магазина'],
    ['type' => 'text', 'name' => 'text2_3_3', 'text' => 'Что-нибудь'],
    ['type' => 'text', 'name' => 'text2_3_4', 'text' => 'И еще что-нибудь'],
        ];

$form3_1 = [
    ['type' => 'file', 'name' => 'file3_1_1', 'text' => 'Фото отчет'],
        ];


$cards = [
    'card1_1' => new CardInit ( 'Карточка 1', $form1_1, ['task1_1'], [], []),
    'card2_1' => new CardInit ( 'Карточка 2', $form2_1, ['task2_1'], [], ['card2_3']),
    'card2_2' => new CardInit ( 'Карточка 3', $form2_2, ['task2_2'], [], ['card2_3']),
    'card2_3' => new CardInit ( 'Карточка 4', $form2_3, ['task2_3'], ['card2_2', 'card2_1'], []),
    'card3_1' => new CardInit ( 'Карточка 5', $form3_1, ['task3_1'], [], [])
];







$tasks = [
    'task1_1' => new TaskInit ( 'franchise', 'coordinator', 'Добавь документ'),
    'task2_1' => new TaskInit ( 'franchise', 'project_manager', 'Таск сделай'),
    'task2_2' => new TaskInit ( 'franchise', 'project_manager', 'Молор, теперь этот сделай'),
    'task2_3' => new TaskInit ( 'franchise', 'project_manager', 'Почти все, остался этот и еще 1'),
    'task3_1' => new TaskInit ( 'franchise', 'project_manager', 'Ты сделал это!'),
];

$initializer = new Initializer ( $project, $stages, $desks, $cards, $tasks);

