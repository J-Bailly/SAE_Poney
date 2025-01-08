<?php
declare(strict_types=1);
require 'Classes/Autoloader.php';

Autoloader::register();

use tools\type\Text;
use tools\type\Hidden;
use tools\type\Textarea;
use tools\type\Checkbox;
use tools\type\Label;
use Provider\DataLoaderJson;
use View\Template;

$loader = new DataLoaderJson("Data/model.json");
$form = $loader->getData();


$questions = [];

// ob_start();

foreach($form as $field) {
    $className = 'tools\\type\\'.ucfirst($field['type']);
    // echo new $className($field['name'], $field['required']).PHP_EOL;
    $questions[] = (new $className($field['name'], $field['required']));
}

// $content = ob_get_clean();

// die();

// $text = new Text('myinput', false, 'coucou');
// echo $text->render().PHP_EOL;

// $checkbox = new Checkbox('mycheckbox', true);
// echo $checkbox->render().PHP_EOL;

// $hidden = new Hidden('myhidden');
// echo $hidden->render().PHP_EOL;

// echo new Text('mytexttostring').PHP_EOL;

// echo new Textarea('mytextarea', true, 'default value').PHP_EOL;

// echo new Label('mylabel', true, "1. Quelle est la réponse ultime ?").PHP_EOL;

// echo new Label('mylabel', true, "2. Quelle est la couleur du cheval blanc d'Henri IV ?").PHP_EOL;

// echo new Label('mylabel', true, "3. Quelles sont les couleurs du drapeau français ?").PHP_EOL;



$action = $_REQUEST['action'] ?? false;

ob_start();
switch($action) {
    case 'submit':
        include 'Action/answer.php';
        break;
    default:
        include 'Action/form.php';
        break;
}

$content = ob_get_clean();

$template = new Template("Template");
$template->setLayout("main");
$template->setContent($content);

echo $template->compile();
?>