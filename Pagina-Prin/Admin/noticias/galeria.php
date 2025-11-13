<?php 
require_once __DIR__ . '/../../../config/admin_guard.php';
require_once __DIR__ . '/../../../config/db.php';

// Obtener todas las imágenes únicas de las noticias
$res = $mysqli->query("SELECT DISTINCT imagen_url FROM noticias WHERE imagen_url IS NOT NULL AND imagen_url != '' ORDER BY imagen_url");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Galería de Imágenes</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Bebas+Neue&display=swap" rel="stylesheet">
  <style>
    :root{ --brand:#f39c12; --brand2:#e67e22 }
    *{box-sizing:border-box}
    body{font-family:Roboto, sans-serif; margin:0; background:#f5f7fa}
    .top{background:linear-gradient(135deg,var(--brand),var(--brand2)); color:#fff; padding:18px 24px; box-shadow:0 6px 20px rgba(243,156,18,.35)}
    .top h1{margin:0; font-family:'Bebas Neue'; font-size:44px; letter-spacing:1px}
    .container{max-width:1400px; margin:24px auto; background:#fff; padding:24px; border-radius:16px; box-shadow:0 10px 28px rgba(0,0,0,.08)}
    .galeria-grid{display:grid; grid-template-columns:repeat(auto-fill, minmax(250px, 1fr)); gap:20px; margin-top:20px}
    .galeria-item{position:relative; border-radius:12px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,.1); cursor:pointer; transition:transform 0.3s, box-shadow 0.3s; background:#f5f5f5; aspect-ratio:16/9}
    .galeria-item:hover{transform:translateY(-4px); box-shadow:0 8px 24px rgba(0,0,0,.2)}
    .galeria-item img{width:100%; height:100%; object-fit:cover; display:block}
    .galeria-item .url-overlay{position:absolute; bottom:0; left:0; right:0; background:linear-gradient(to top, rgba(0,0,0,0.8), transparent); padding:12px; color:#fff; font-size:11px; word-break:break-all; opacity:0; transition:opacity 0.3s}
    .galeria-item:hover .url-overlay{opacity:1}
    .btn-select{position:absolute; top:10px; right:10px; background:var(--brand); color:#fff; border:none; padding:8px 16px; border-radius:8px; font-weight:700; cursor:pointer; opacity:0; transition:opacity 0.3s; z-index:10}
    .galeria-item:hover .btn-select{opacity:1}
    .btn-select:hover{background:var(--brand2)}
    .empty-state{text-align:center; padding:60px 20px; color:#666}
    .empty-state h2{color:#999; margin-bottom:12px}
    .top-header{display:flex; justify-content:space-between; align-items:center; width:100%}
    .top-actions{display:flex; gap:8px; align-items:center}
    .btn{padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:700; display:inline-block}
    .btn-back{background:linear-gradient(135deg,#607d8b,#455a64); color:#fff}
    .btn:hover{opacity:.9; transform:translateY(-1px); box-shadow:0 4px 8px rgba(0,0,0,.2)}
    .info-bar{background:#e3f2fd; padding:12px 16px; border-radius:8px; margin-bottom:20px; color:#1976d2; font-size:14px}
  </style>
</head>
<body>
  <div class="top">
    <div class="top-header">
      <h1>Galería de Imágenes</h1>
      <div class="top-actions">
        <a class="btn btn-back" href="index.php">← Volver</a>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="info-bar">
      <strong>💡 Tip:</strong> Haz clic en "Seleccionar" sobre una imagen para copiar su URL al portapapeles. Luego pégalo en el campo de URL de imagen al crear o editar una noticia.
    </div>
    <?php if ($res->num_rows === 0): ?>
      <div class="empty-state">
        <h2>No hay imágenes en la galería</h2>
        <p>Las imágenes aparecerán aquí cuando crees noticias con URLs de imágenes.</p>
      </div>
    <?php else: ?>
      <div class="galeria-grid">
        <?php while($row = $res->fetch_assoc()): 
          $url = htmlspecialchars($row['imagen_url']);
        ?>
          <div class="galeria-item">
            <img src="<?= $url ?>" alt="Imagen" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\'%3E%3Crect fill=\'%23ddd\' width=\'400\' height=\'300\'/%3E%3Ctext fill=\'%23999\' font-family=\'Arial\' font-size=\'18\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\'%3EImagen no disponible%3C/text%3E%3C/svg%3E'">
            <button class="btn-select" onclick="seleccionarImagen('<?= addslashes($url) ?>')">Seleccionar</button>
            <div class="url-overlay"><?= $url ?></div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>
  </div>

  <script>
    function seleccionarImagen(url) {
      // Si se abrió desde otra ventana, enviar la URL
      if (window.opener) {
        window.opener.postMessage({type: 'imagen_seleccionada', url: url}, '*');
        window.close();
        return;
      }
      
      // Si no, copiar al portapapeles
      navigator.clipboard.writeText(url).then(() => {
        alert('✅ URL copiada al portapapeles:\n\n' + url + '\n\nPega esta URL en el campo de imagen al crear o editar una noticia.');
      }).catch(() => {
        // Fallback para navegadores antiguos
        const textarea = document.createElement('textarea');
        textarea.value = url;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        alert('✅ URL copiada al portapapeles:\n\n' + url + '\n\nPega esta URL en el campo de imagen al crear o editar una noticia.');
      });
    }
  </script>
</body>
</html>

