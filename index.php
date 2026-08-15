<?php /* Online Resume / CV Builder - Form */ ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CV Builder - Create</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>Online Resume / CV Builder</h1>
      <div class="stack">
        <a class="btn btn-ghost" href="preview.php">Preview latest</a>
      </div>
    </div>
    <p>Fill your details below and click <strong>Save & Preview</strong>. Use the <em>Add another</em> buttons to add multiple entries.</p>
    <form action="save_resume.php" method="POST">
      <h2>Basics</h2>
      <div class="form-row">
        <div>
          <label>Full Name</label>
          <input type="text" name="full_name" required>
        </div>
        <div>
          <label>Email</label>
          <input type="email" name="email" required>
        </div>
      </div>
      <div class="form-row">
        <div>
          <label>Phone</label>
          <input type="text" name="phone" required>
        </div>
        <div>
          <label>Location</label>
          <input type="text" name="location" placeholder="City, Country">
        </div>
      </div>
      <div class="form-row-1">
        <div>
          <label>Professional Summary</label>
          <textarea name="summary" placeholder="2–4 line summary highlighting your strengths"></textarea>
        </div>
      </div>

      <hr>
      <h2>Education</h2>
      <div id="education-list">
        <div class="section edu-item">
          <div class="form-row">
            <div><label>Institution</label><input name="edu_institution[]" type="text" required></div>
            <div><label>Degree</label><input name="edu_degree[]" type="text"></div>
          </div>
          <div class="form-row">
            <div><label>Start Year</label><input name="edu_start[]" type="text" placeholder="YYYY"></div>
            <div><label>End Year</label><input name="edu_end[]" type="text" placeholder="YYYY or Present"></div>
          </div>
          <div class="form-row-1">
            <div><label>Score</label><input name="edu_score[]" type="text" placeholder="CGPA/Percentage"></div>
          </div>
        </div>
      </div>
      <button type="button" class="btn btn-ghost no-print" onclick="addBlock('education')">+ Add another education</button>

      <hr>
      <h2>Experience</h2>
      <div id="experience-list">
        <div class="section exp-item">
          <div class="form-row">
            <div><label>Company</label><input name="exp_company[]" type="text"></div>
            <div><label>Role</label><input name="exp_role[]" type="text"></div>
          </div>
          <div class="form-row">
            <div><label>Start Date</label><input name="exp_start[]" type="text" placeholder="e.g., Jun 2024"></div>
            <div><label>End Date</label><input name="exp_end[]" type="text" placeholder="e.g., Present"></div>
          </div>
          <div class="form-row-1">
            <div><label>Description</label><textarea name="exp_desc[]" placeholder="What did you build/achieve? Use action verbs."></textarea></div>
          </div>
        </div>
      </div>
      <button type="button" class="btn btn-ghost no-print" onclick="addBlock('experience')">+ Add another experience</button>

      <hr>
      <h2>Projects</h2>
      <div id="projects-list">
        <div class="section proj-item">
          <div class="form-row">
            <div><label>Title</label><input name="proj_title[]" type="text"></div>
            <div><label>Tech</label><input name="proj_tech[]" type="text" placeholder="HTML, CSS, C, SQL"></div>
          </div>
          <div class="form-row">
            <div><label>Link</label><input name="proj_link[]" type="url" placeholder="https://..."></div>
            <div></div>
          </div>
          <div class="form-row-1">
            <div><label>Description</label><textarea name="proj_desc[]" placeholder="1–3 bullet highlights or a short paragraph"></textarea></div>
          </div>
        </div>
      </div>
      <button type="button" class="btn btn-ghost no-print" onclick="addBlock('projects')">+ Add another project</button>

      <hr>
      <h2>Skills</h2>
      <div id="skills-list">
        <div class="section skill-item">
          <div class="form-row">
            <div><label>Skill</label><input name="skill_name[]" type="text" placeholder="e.g., C, SQL, HTML, CSS"></div>
            <div><label>Level</label>
              <select name="skill_level[]">
                <option>Beginner</option>
                <option selected>Intermediate</option>
                <option>Advanced</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <button type="button" class="btn btn-ghost no-print" onclick="addBlock('skills')">+ Add another skill</button>

      <hr>
      <button type="submit" class="btn btn-primary">Save & Preview</button>
    </form>
  </div>

<script>
function addBlock(section) {
  const map = {
    education: 'education-list',
    experience: 'experience-list',
    projects: 'projects-list',
    skills: 'skills-list'
  };
  const list = document.getElementById(map[section]);
  const first = list.children[0];
  const clone = first.cloneNode(true);
  // clear inputs in clone
  Array.from(clone.querySelectorAll('input, textarea')).forEach(el => { el.value = ''; });
  list.appendChild(clone);
}
</script>
</body>
</html>
