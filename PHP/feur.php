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

?>
<?php $title = "KavalKenny Klub"; ?>

<?php ob_start(); ?>
        <section class="socials">
            <h2>Planning des leçons</h2>
            <table class="tableau">
                <tr>
                    <th>Horaire</th>
                    <th>Lundi</th>
                    <th>Mardi</th>
                    <th>Mercredi</th>
                    <th>Jeudi</th>
                    <th>Vendredi</th>
                    <th>Samedi</th>
                </tr>
                <tr>
                    <td class="heure">9h-10h</td>
                    <td><input type="button" class="planning" value=""></td>
                    <td><input type="button" class="planning" value=""></td>
                    <td><input type="button" class="planning" value="Baby"></td>
                    <td><input type="button" class="planning" value=""></td>
                    <td><input type="button" class="planning" value=""></td>
                    <td><input type="button" class="planning" value="Galop 1/2"></td>
                </tr>
                <tr>
                    <td class="heure">10h-11h</td>
                    <td>Galop 3</td>
                    <td></td>
                    <td></td>
                    <td>Galop 4/5</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="heure">11h-12h</td>
                    <td></td>
                    <td>Initiation</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </section>

        <section class="tarifs">
            <h2>Tarifs</h2>
            <article>
                <h3>Devenir Adhérents</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum id libero viverra, vulputate nisl et, facilisis tortor. Donec vestibulum facilisis metus.</p>
            </article>
            <article>
                <h3>Cours Collectif</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum id libero viverra, vulputate nisl et, facilisis tortor. Donec vestibulum facilisis metus.</p>
            </article>
            <article>
                <h3>Cours Particulier</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum id libero viverra, vulputate nisl et, facilisis tortor. Donec vestibulum facilisis metus.</p>
            </article>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 KavalKenny Klub - Tous droits réservés.</p>
    </footer>
</body>
</html>
<?php $content = ob_get_clean(); ?>

<?php require('Template/template.php') ?>