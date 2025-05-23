<?php

// Создаем класс с подключенным интерфейсом Iterator
class DomIterator implements Iterator {
    private int $position = 0;
    private DOMNodeList $nodes;
    
    public function __construct(DOMNodeList $nodes) {
        $this->nodes = $nodes;
        $this->rewind();
    }
    
    public function rewind(): void {
        $this->position = 0;
    }
    
    public function current(): DOMElement {
        return $this->nodes->item($this->position);
    }
    
    public function key(): int {
        return $this->position;
    }
    
    public function next(): void {
        $this->position++;
    }
    
    public function valid(): bool {
        return $this->position < $this->nodes->length;
    }
}

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

// Итерация 'title'
$titleIterator = new DomIterator($dom->getElementsByTagName('title'));

foreach ($titleIterator as $t) {
    $metaTags['titles'][] = $t->nodeValue;
}

// Итерация 'description' и 'keywords'
$metaIterator = new DomIterator($dom->getElementsByTagName('meta'));

foreach ($metaIterator as $m) {

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