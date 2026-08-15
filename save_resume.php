<?php
require_once "config.php";

function arr($k) { return isset($_POST[$k]) && is_array($_POST[$k]) ? $_POST[$k] : []; }
function val($k) { return isset($_POST[$k]) ? trim($_POST[$k]) : ""; }

$full_name = val("full_name");
$email     = val("email");
$phone     = val("phone");
$location  = val("location");
$summary   = val("summary");

if ($full_name === "" || $email === "" || $phone === "") {
  http_response_code(400);
  echo "Missing required fields.";
  exit;
}

try {
  $conn->begin_transaction();

  // Insert resume
  $stmt = $conn->prepare("INSERT INTO resumes (full_name, email, phone, location, summary) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $full_name, $email, $phone, $location, $summary);
  $stmt->execute();
  $resume_id = $stmt->insert_id;
  $stmt->close();

  // Education
  $edu_institution = arr("edu_institution");
  $edu_degree = arr("edu_degree");
  $edu_start = arr("edu_start");
  $edu_end   = arr("edu_end");
  $edu_score = arr("edu_score");
  if (!empty($edu_institution)) {
    $stmt = $conn->prepare("INSERT INTO education (resume_id, institution, degree, start_year, end_year, score) VALUES (?, ?, ?, ?, ?, ?)");
    for ($i=0; $i<count($edu_institution); $i++) {
      $inst = trim($edu_institution[$i] ?? "");
      if ($inst === "") continue;
      $deg = trim($edu_degree[$i] ?? "");
      $st  = trim($edu_start[$i] ?? "");
      $en  = trim($edu_end[$i] ?? "");
      $sc  = trim($edu_score[$i] ?? "");
      $stmt->bind_param("isssss", $resume_id, $inst, $deg, $st, $en, $sc);
      $stmt->execute();
    }
    $stmt->close();
  }

  // Experience
  $exp_company = arr("exp_company");
  $exp_role    = arr("exp_role");
  $exp_start   = arr("exp_start");
  $exp_end     = arr("exp_end");
  $exp_desc    = arr("exp_desc");
  if (!empty($exp_company)) {
    $stmt = $conn->prepare("INSERT INTO experience (resume_id, company, role, start_date, end_date, description) VALUES (?, ?, ?, ?, ?, ?)");
    for ($i=0; $i<count($exp_company); $i++) {
      $co = trim($exp_company[$i] ?? "");
      if ($co === "") continue;
      $ro = trim($exp_role[$i] ?? "");
      $st = trim($exp_start[$i] ?? "");
      $en = trim($exp_end[$i] ?? "");
      $de = trim($exp_desc[$i] ?? "");
      $stmt->bind_param("isssss", $resume_id, $co, $ro, $st, $en, $de);
      $stmt->execute();
    }
    $stmt->close();
  }

  // Projects
  $proj_title = arr("proj_title");
  $proj_tech  = arr("proj_tech");
  $proj_link  = arr("proj_link");
  $proj_desc  = arr("proj_desc");
  if (!empty($proj_title)) {
    $stmt = $conn->prepare("INSERT INTO projects (resume_id, title, tech, link, description) VALUES (?, ?, ?, ?, ?)");
    for ($i=0; $i<count($proj_title); $i++) {
      $ti = trim($proj_title[$i] ?? "");
      if ($ti === "") continue;
      $te = trim($proj_tech[$i] ?? "");
      $li = trim($proj_link[$i] ?? "");
      $de = trim($proj_desc[$i] ?? "");
      $stmt->bind_param("issss", $resume_id, $ti, $te, $li, $de);
      $stmt->execute();
    }
    $stmt->close();
  }

  // Skills
  $skill_name  = arr("skill_name");
  $skill_level = arr("skill_level");
  if (!empty($skill_name)) {
    $stmt = $conn->prepare("INSERT INTO skills (resume_id, skill, level) VALUES (?, ?, ?)");
    for ($i=0; $i<count($skill_name); $i++) {
      $sn = trim($skill_name[$i] ?? "");
      if ($sn === "") continue;
      $sl = trim($skill_level[$i] ?? "Intermediate");
      $stmt->bind_param("iss", $resume_id, $sn, $sl);
      $stmt->execute();
    }
    $stmt->close();
  }

  $conn->commit();
  header("Location: preview.php?id=" . $resume_id);
  exit;
} catch (Exception $e) {
  $conn->rollback();
  http_response_code(500);
  echo "Error saving resume.";
  // For debugging, uncomment the next line:
  // echo $e->getMessage();
}
?>
