<?php
require_once "config.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id === 0) {
  // fetch latest resume if id not provided
  $res = $conn->query("SELECT id FROM resumes ORDER BY id DESC LIMIT 1");
  if ($row = $res->fetch_assoc()) $id = intval($row['id']);
}

if ($id === 0) {
  echo "<link rel='stylesheet' href='style.css'><div class='container'><p>No resumes found. <a href='index.php'>Create one</a>.</p></div>";
  exit;
}


// fetch data
$stmt = $conn->prepare("SELECT * FROM resumes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resume = $stmt->get_result()->fetch_assoc();
$stmt->close();

$tables = ['education' => [], 'experience' => [], 'projects' => [], 'skills' => []];

foreach (['education','experience','projects','skills'] as $tbl) {
  $stmt = $conn->prepare("SELECT * FROM {$tbl} WHERE resume_id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $tables[$tbl] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CV Preview</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="header">
      <h1><?= htmlspecialchars($resume['full_name']) ?></h1>
      <div class="stack no-print">
        <a class="btn btn-ghost" href="index.php">← Edit / Create new</a>
        <button class="btn btn-primary" onclick="window.print()">Print / Download PDF</button>
      </div>
    </div>
    <div class="stack">
      <span class="badge"><?= htmlspecialchars($resume['email']) ?></span>
      <span class="badge"><?= htmlspecialchars($resume['phone']) ?></span>
      <?php if (!empty($resume['location'])): ?>
        <span class="badge"><?= htmlspecialchars($resume['location']) ?></span>
      <?php endif; ?>
    </div>
    <?php if (!empty($resume['summary'])): ?>
      <p style="margin-top:10px;"><?= nl2br(htmlspecialchars($resume['summary'])) ?></p>
    <?php endif; ?>

    <div class="resume" style="margin-top:18px;">
      <div>
        <h2>Skills</h2>
        <ul>
          <?php foreach ($tables['skills'] as $s): ?>
            <li><?= htmlspecialchars($s['skill']) ?> — <?= htmlspecialchars($s['level']) ?></li>
          <?php endforeach; ?>
        </ul>

        <h2>Education</h2>
        <?php foreach ($tables['education'] as $e): ?>
          <div style="margin-bottom:10px;">
            <strong><?= htmlspecialchars($e['institution']) ?></strong><br>
            <span><?= htmlspecialchars($e['degree']) ?></span><br>
            <small><?= htmlspecialchars($e['start_year']) ?> - <?= htmlspecialchars($e['end_year']) ?>
              <?php if(!empty($e['score'])): ?> • Score: <?= htmlspecialchars($e['score']) ?><?php endif; ?>
            </small>
          </div>
        <?php endforeach; ?>
      </div>
      <div>
        <h2>Experience</h2>
        <?php foreach ($tables['experience'] as $x): ?>
          <div style="margin-bottom:12px;">
            <strong><?= htmlspecialchars($x['role']) ?></strong> — <?= htmlspecialchars($x['company']) ?><br>
            <small><?= htmlspecialchars($x['start_date']) ?> - <?= htmlspecialchars($x['end_date']) ?></small>
            <?php if(!empty($x['description'])): ?>
              <p><?= nl2br(htmlspecialchars($x['description'])) ?></p>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>

        <h2>Projects</h2>
        <?php foreach ($tables['projects'] as $p): ?>
          <div style="margin-bottom:12px;">
            <strong><?= htmlspecialchars($p['title']) ?></strong>
            <?php if(!empty($p['link'])): ?> — <a href="<?= htmlspecialchars($p['link']) ?>"><?= htmlspecialchars($p['link']) ?></a><?php endif; ?><br>
            <?php if(!empty($p['tech'])): ?><small><?= htmlspecialchars($p['tech']) ?></small><?php endif; ?>
            <?php if(!empty($p['description'])): ?>
              <p><?= nl2br(htmlspecialchars($p['description'])) ?></p>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</body>
</html>
