<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <title>Farbpalette</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
      margin: 20px;
    }
    h1, h2 {
      text-align: center;
    }
    .color-container {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      margin-bottom: 2rem;
    }
    .color-box {
      width: 140px;
      margin: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      overflow: hidden;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      text-align: center;
    }
    .color-swatch {
      height: 80px;
    }
    .color-info {
      padding: 8px;
    }
    .color-info strong {
      display: block;
      margin-bottom: 5px;
    }
    .color-info span {
      display: block;
      font-size: 0.85rem;
      color: #555;
    }
    form {
      margin-bottom: 2rem;
    }
    select {
      padding: 8px 12px;
      font-size: 1rem;
    }
  </style>
</head>
<body>

<h1>Farbpalette</h1>

<form method="get">
  <label for="farbe">Wähle eine Farbe:</label>
  <select name="farbe" id="farbe" onchange="this.form.submit()">
    <option value="">-- Bitte wählen --</option>
    <?php
      $csvDir = __DIR__ . '/CSV';
      $csvFiles = glob($csvDir . '/*.csv');

      $translations = [
        'red' => 'Rot',
        'green' => 'Grün',
        'blue' => 'Blau',
        'yellow' => 'Gelb',
        'orange' => 'Orange',
        'pink' => 'Rosa',
        'purple' => 'Lila',
        'violet' => 'Violett',
        'gray' => 'Grau',
        'grey' => 'Grau'
      ];

      foreach ($csvFiles as $filePath) {
          $fileBase = basename($filePath, '.csv');
          $selected = (isset($_GET['farbe']) && $_GET['farbe'] === $fileBase) ? 'selected' : '';
          $label = $translations[strtolower($fileBase)] ?? ucfirst($fileBase);
          echo "<option value=\"$fileBase\" $selected>$label</option>";
      }
    ?>
  </select>
</form>

<?php
if (!empty($_GET['farbe'])) {
    $selected = basename($_GET['farbe']);
    $csvPath = $csvDir . '/' . $selected . '.csv';

    if (file_exists($csvPath)) {
        $deutsch = $translations[strtolower($selected)] ?? ucfirst($selected);
        echo "<h2>$deutsch</h2>";
        echo '<div class="color-container">';

        if (($handle = fopen($csvPath, "r")) !== false) {
            $header = fgetcsv($handle); // Name,RGB,Hex,HSL
            while (($data = fgetcsv($handle)) !== false) {
                [$name, $rgb, $hex, $hsl] = $data;

                echo '
                <div class="color-box">
                  <div class="color-swatch" style="background:' . trim($hex) . '"></div>
                  <div class="color-info">
                    <strong>' . htmlspecialchars($name) . '</strong>
                    <span>' . htmlspecialchars($rgb) . '</span>
                    <span>' . htmlspecialchars($hex) . '</span>
                    <span>' . htmlspecialchars($hsl) . '</span>
                  </div>
                </div>';
            }
            fclose($handle);
        }

        echo '</div>';
    } else {
        echo "<p>Die Datei für <strong>$selected</strong> wurde nicht gefunden.</p>";
    }
}
?>

</body>
</html>
