<?php
$css = "

/* Custom Tabs */
.custom-tabs { border-bottom: 2px solid var(--gold); }
.custom-tabs .nav-link { color: var(--text-muted); font-weight: 600; border: none; border-radius: 8px 8px 0 0; padding: 12px 24px; transition: var(--transition); }
.custom-tabs .nav-link:hover { color: var(--primary); background: rgba(201,168,76,0.1); }
.custom-tabs .nav-link.active { color: var(--primary); background: var(--gold); border: none; }
";
file_put_contents('c:/xampp/htdocs/danahibah/assets/css/style.css', $css, FILE_APPEND);
file_put_contents('e:/xampp/htdocs/danahibah/assets/css/style.css', $css, FILE_APPEND);
echo "CSS appended successfully.";
