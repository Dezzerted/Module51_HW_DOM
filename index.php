<?php
// Подключаем HTML-файл и записываем в переменную содержимое
$html = file_get_contents('index.html');

// Создаем объект DOMDocument
$dom = new DOMDocument();

// Загружаем HTML и подавляем предупреждения об ошибках
@$dom->loadHTML($html);

// Массив для хранения результатов
$metaTags = [
    'titles' => [],    
    'descriptions' => [], 
    'keywords' => []     
];

// Итерация 1: Получаем все теги <title>

foreach ($dom->getElementsByTagName('title') as $t) {
    $metaTags['titles'][] = $t->nodeValue;
}

// Итерация 2: Перебираем все meta-теги

foreach ($dom->getElementsByTagName('meta') as $m) {

    $name = $m->getAttribute('name');
    $content = $m->getAttribute('content');

    if ($name === 'description') {
        $metaTags['descriptions'][]= $content;
    } elseif ($name === 'keywords') {
        $metaTags['keywords'][] = $content;
    }
}

// Выводим результат
echo "<pre>";
echo "Titles: " . implode(', ', array_map('htmlspecialchars', $metaTags['titles'])) . "\n";
echo "Descriptions: " . implode(' | ', array_map('htmlspecialchars', $metaTags['descriptions'])) . "\n";
echo "Keywords: " . implode('; ', array_map('htmlspecialchars', $metaTags['keywords'])) . "\n";
echo "</pre>";