<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$conn = new mysqli("localhost", "root", "", "student");
$fullname = "fullname";    

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);

    }
	
?>


<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Bayero University Staff School</title>
<meta name="description" content="Student results computation system (single-file demo).">
<style>
  /* ---------- Theme tokens ---------- */
  :root{
    --bg:#0b1220; --surface:#0f1830; --card:#0f1b33; --muted:#9aa7c7; --text:#eaf0fb;
    --accent:#6ea8ff; --accent-2:#3be7a1; --danger:#ff6b6b; --glass: rgba(255,255,255,.04);
    --radius:14px; --radius-sm:10px; --shadow:0 12px 40px rgba(2,8,23,.6);
    --glass-strong: rgba(255,255,255,.06);
  }
  [data-theme="light"]{
    --bg:#f6f9ff; --surface:#ffffff; --card:#ffffff; --muted:#64748b; --text:#0f1724;
    --accent:#335dff; --accent-2:#0ea57e; --danger:#d14343; --glass: rgba(15,23,40,.03);
    --shadow:0 10px 30px rgba(15,23,40,.06);
  }

  /* ---------- Base ---------- */
  *{box-sizing:border-box}
  html,body{height:100%}
  body{
    margin:0; font-family:Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
    background:
      radial-gradient(800px 400px at -10% 120%, rgba(59,231,161,.04), transparent 20%),
      radial-gradient(900px 500px at 110% -20%, rgba(110,168,255,.04), transparent 20%),
      var(--bg);
    color:var(--text);
    display:grid; grid-template-columns: 260px 1fr; gap:20px; padding:20px; align-items:start;
    min-height:100vh;
  }

  /* ---------- Sidebar ---------- */
  .sidebar{
    height:calc(100vh - 40px); position:sticky; top:20px; background:linear-gradient(180deg,var(--surface),rgba(255,255,255,.01));
    border-radius:var(--radius); padding:18px; border:1px solid var(--glass-strong); box-shadow:var(--shadow);
    display:flex; flex-direction:column; gap:12px; min-width:200px;
  }
  .brand{ display:flex; gap:12px; align-items:center; }
  .logo{ width:48px; height:48px; border-radius:12px; display:grid; place-items:center; font-weight:800; color:var(--accent-2);
         background:linear-gradient(180deg, rgba(255,255,255,.02), rgba(255,255,255,0.01)); border:1px solid var(--glass); }
  .brand h1{ font-size:16px; margin:0 }
  .brand p{ margin:0; font-size:12px; color:var(--muted) }

  nav.nav{ display:flex; flex-direction:column; gap:6px; margin-top:8px }
  nav.nav a{ display:flex; gap:12px; align-items:center; padding:10px; border-radius:10px; text-decoration:none; color:var(--text); font-weight:600; font-size:14px; border:1px solid transparent }
  nav.nav a svg{ width:18px; height:18px; opacity:.95 }
  nav.nav a:hover{ background:var(--glass) }
  nav.nav a.active{ background:linear-gradient(90deg, rgba(110,168,255,.06), rgba(59,231,161,.03)); border-color:var(--glass-strong) }

  .sidebar .foot{ margin-top:auto; display:flex; gap:8px; align-items:center; justify-content:center }
  .small{ font-size:12px; color:var(--muted) }

  /* ---------- Main ---------- */
  main{ padding:8px 0 24px; overflow:auto; min-height:100vh; }
  .topbar{ display:flex; gap:12px; align-items:center; margin-bottom:18px; }
  .search{
    display:flex; gap:8px; align-items:center; padding:8px; background:var(--card); border-radius:12px; border:1px solid var(--glass);
    min-width:0; flex:1; box-shadow:0 6px 18px rgba(2,8,23,.25);
  }
  .search input{ flex:1; border:0; background:transparent; color:var(--text); outline:none; font-size:14px; padding:6px 8px }
  .top-actions{ display:flex; gap:8px; align-items:center }

  /* ---------- Profile & Summary ---------- */
  .profile{
    display:flex; gap:12px; align-items:center; padding:14px; border-radius:12px; background:linear-gradient(180deg, rgba(255,255,255,.015), transparent);
    border:1px solid var(--glass); margin-bottom:12px;
  }
  .avatar{ width:64px; height:64px; border-radius:12px; background:linear-gradient(180deg,var(--accent),var(--accent-2)); color:white; display:grid; place-items:center; font-weight:800; font-size:20px }
  .profile .meta{ display:flex; flex-direction:column }
  .profile .meta h2{ margin:0; font-size:18px }
  .profile .meta p{ margin:2px 0 0; color:var(--muted); font-size:13px }

  .cards{ display:grid; grid-template-columns:repeat(4, 1fr); gap:12px; margin-bottom:14px }
  @media (max-width:1200px){ .cards{ grid-template-columns:repeat(2,1fr) } }
  @media (max-width:700px){ body{ grid-template-columns: 1fr } .sidebar{ position:fixed; left:0; top:0; bottom:0; transform:translateX(-110%); transition:transform .25s ease; z-index:40 } .sidebar.open{ transform:none } .cards{ grid-template-columns:1fr } }

  .card{ padding:14px; border-radius:12px; background:var(--card); border:1px solid var(--glass); display:flex; flex-direction:column; gap:8px; }
  .card .label{ color:var(--muted); font-size:13px }
  .card .value{ font-size:22px; font-weight:800 }

  /* progress micro */
  .progress{ height:8px; border-radius:999px; background:rgba(255,255,255,.04); overflow:hidden }
  .progress > i{ display:block; height:100%; width:0; background:linear-gradient(90deg,var(--accent), var(--accent-2)); transition:width .8s cubic-bezier(.2,.9,.2,1) }

  /* ---------- Grid layout (table + side panel) ---------- */
  .grid{ display:grid; grid-template-columns: 2fr 1fr; gap:14px; align-items:start }
  @media (max-width:1200px){ .grid{ grid-template-columns:1fr } }

  .panel{ background:var(--card); border-radius:12px; padding:12px; border:1px solid var(--glass); box-shadow:0 10px 30px rgba(2,8,23,.28) }

  /* Controls row */
  .controls{ display:flex; gap:8px; align-items:center; margin-bottom:10px; flex-wrap:wrap }
  .controls select, .controls button{ padding:10px 12px; border-radius:10px; background:transparent; border:1px solid var(--glass); color:var(--text); font-weight:700; cursor:pointer }
  .controls .primary{ background:linear-gradient(180deg,var(--accent),var(--accent-2)); color:white; border:0; box-shadow:0 8px 20px rgba(59,110,255,.12) }

  /* Table */
  .table-wrap{ overflow:auto; max-height:58vh; border-radius:10px; border:1px solid var(--glass) }
  table{ width:100%; border-collapse:collapse; min-width:720px; }
  thead th{ position:sticky; top:0; background:linear-gradient(180deg, rgba(255,255,255,.01), rgba(255,255,255,0)); padding:12px; text-align:left; font-size:13px; color:var(--muted); border-bottom:1px solid var(--glass) }
  tbody td{ padding:12px; border-bottom:1px dashed rgba(255,255,255,.03); font-size:14px }
  tbody tr:hover{ background:rgba(255,255,255,.02) }

  .chip{ display:inline-flex; align-items:center; gap:8px; padding:6px 10px; border-radius:999px; font-weight:700; font-size:13px; border:1px solid var(--glass) }
  .chip.ok{ background:linear-gradient(90deg, rgba(59,231,161,.07), rgba(59,231,161,.03)); color:var(--accent-2) }
  .chip.warn{ background:linear-gradient(90deg, rgba(255,198,88,.07), rgba(255,198,88,.03)); color:var(--accent) }
  .chip.bad{ background:linear-gradient(90deg, rgba(255,107,107,.07), rgba(255,107,107,.03)); color:var(--danger) }

  /* Chart area */
  .chart{ height:220px; border-radius:10px; overflow:hidden; background:linear-gradient(180deg, rgba(255,255,255,.01), transparent); padding:8px }

  /* grade bars */
  .bars{ display:grid; grid-template-columns:repeat(6,1fr); gap:8px; align-items:end; height:160px; margin-top:8px }
  .bar{ display:flex; align-items:end; justify-content:center; padding-bottom:8px; border-radius:8px; background:linear-gradient(180deg, rgba(255,255,255,.01), transparent); position:relative }

  .bar > span{ font-size:12px; position:absolute; top:6px; left:6px; color:var(--muted) }

  /* tiny helpers */
  .muted{ color:var(--muted) }
  .right{ margin-left:auto }
  .visually-hidden{ position:absolute !important; height:1px; width:1px; overflow:hidden; clip:rect(1px,1px,1px,1px); white-space:nowrap }
  footer{ margin-top:18px; color:var(--muted); text-align:center; font-size:13px }

  /* print */
  @media print{
    body{ background:#fff; color:#000; grid-template-columns:1fr; padding:0 }
    .sidebar, .controls, .top-actions, .search, footer{ display:none }
    .panel, .card{ box-shadow:none; border-color:#ddd }
  }
</style>
</head>
<body data-theme="light">


<!-- SIDEBAR -->
<aside class="sidebar" aria-label="Primary navigation">
  <div class="brand" role="banner">
    <div class="logo" aria-hidden="true">BUK</div>
    <div>
      <h1>SMSS BUK</h1>
      <p class="small">Student Record</p>
    </div>
  </div>
  
  

  <nav class="nav" role="navigation" aria-label="Main">
    <a href="view.php" class="active" data-target="dash"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 12l9-9 9 9"/><path d="M9 21V9h6v12"/></svg>View Records</a>
    <a href="delete.html" data-target="courses"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 19h16M4 5h16M4 12h16"/></svg>Search Student</a>
    <a href="edit.html" data-target="results"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>Edit Record</a>
    <a href="addStud.html" data-target="profile"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Add New Student</a>
  </nav>

  <div class="foot">
  
    <a href="logout.php" data-target="profile"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>logout</a>

  </div>
</aside>

<!-- MAIN -->
<main id="app" role="main" aria-label="Student dashboard">
  <div class="topbar" role="region" aria-label="Search and quick actions">
    <div class="search" role="search">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 21l-4.35-4.35"/><circle cx="11" cy="11" r="6"/></svg>
      
    </div>
    <div class="top-actions" role="toolbar" aria-label="Top actions">
      <div class="chip muted" id="notifications" title="Notifications">No Messages</div>
      <div class="chip muted">2023/2024 Session</div>
    </div>
  </div>

  <section class="profile" aria-labelledby="studentName">
    <div class= aria-hidden="true"> <img src="avatar.jpg" 
     alt="School Logo" style="width:120px; height:120px; border-radius:50%; object-fit:cover; border:2px solid #fff; box-shadow:0 2px 6px rgba(0,0,0,0.3);">
</div>
    <div class="meta">
	  
	 <h2>Sufiyanu Alh Sani</h2>

	  
      <p class="muted">Vice Principal Admin Office • Record Officer 1</p>
    </div>
    <div class="right">
     
    </div>
  </section>

  <!-- Summary -->
 
 

      <h3 id="resultsHeading" style="margin:6px 0 10px">Staff Model Secondary School, Bayero University Kano</h3>
      <div class="table-wrap" role="region" aria-label="Course results table">
        
		<img src="bg1.png" alt="School Logo" width="900">
		
      </div>
    </section>

  </div>
</main>

<script>
/* ===================== Demo data & config ===================== */
/* Grade scale and weights: adapt to your school's rules */
const GRADE_WEIGHTS = { A:5, B:4, C:3, D:2, E:1, F:0 };

/* Demo dataset — replace with your API data */
const DATA = [
  {code:'CHM101', title:'Introductory Chemistry', unit:3, grade:'A', semester:'2024/2025 - First'},
  {code:'MTH102', title:'Calculus II', unit:3, grade:'B', semester:'2024/2025 - First'},
  {code:'PHY101', title:'General Physics I', unit:3, grade:'A', semester:'2024/2025 - First'},
  {code:'GST111', title:'Use of English I', unit:2, grade:'C', semester:'2024/2025 - First'},
  {code:'CSC103', title:'Programming Fundamentals', unit:3, grade:'A', semester:'2024/2025 - Second'},
  {code:'GST121', title:'Use of English II', unit:2, grade:'B', semester:'2024/2025 - Second'},
  {code:'BIO102', title:'Cell Biology', unit:3, grade:'B', semester:'2023/2024 - First'},
  {code:'CHM201', title:'Organic Chemistry I', unit:3, grade:'C', semester:'2023/2024 - Second'},
  {code:'MTH201', title:'Linear Algebra', unit:3, grade:'A', semester:'2023/2024 - Second'},
  {code:'PHY202', title:'Electromagnetism', unit:3, grade:'B', semester:'2023/2024 - Second'},
  {code:'CHM202', title:'Inorganic Chemistry', unit:3, grade:'A', semester:'2024/2025 - Second'},
  {code:'GST231', title:'Entrepreneurship', unit:2, grade:'A', semester:'2024/2025 - Second'},
  {code:'CHM203', title:'Analytical Techniques', unit:2, grade:'F', semester:'2024/2025 - Second'},
];

/* App state */
const state = {
  rows: [...DATA],
  filterSemester: 'all',
  search: '',
  sortKey: null,
  sortDir: 'asc',
  cgpaHistory: [3.40, 3.55, 3.62, 3.70]
};

/* ---------- Utility helpers ---------- */
const $ = sel => document.querySelector(sel);
const $$ = sel => Array.from(document.querySelectorAll(sel));

function gradePoint(gr){ return GRADE_WEIGHTS[gr] ?? 0; }

/* ---------- Core computations ---------- */
function statsFor(rows){
  const totals = rows.reduce((acc,r)=>{
    acc.units += r.unit;
    acc.points += gradePoint(r.grade) * r.unit;
    acc.pass += (r.grade==='F'?0:1);
    acc.carry += (r.grade==='F'?1:0);
    acc.grades[r.grade] = (acc.grades[r.grade] || 0) + 1;
    return acc;
  }, { units:0, points:0, pass:0, carry:0, grades:{} });
  const gpa = totals.units ? totals.points / totals.units : 0;
  return { gpa, totals };
}
/* For demo, CGPA = weighted average over all rows (replace with official rules if needed) */
function computeCGPA(allRows){ const { gpa } = statsFor(allRows); return gpa; }
function classFromCGPA(x){
  if(x >= 4.50) return 'First Class';
  if(x >= 3.50) return 'Second Class (Upper)';
  if(x >= 2.40) return 'Second Class (Lower)';
  if(x >= 1.50) return 'Third Class';
  return 'Pass';
}

/* ---------- Rendering ---------- */
const tbody = $('#tbody');
const semesterSel = $('#semester');
const searchInput = $('#search');

function applyFilters(){
  const s = state.search.toLowerCase().trim();
  const sem = state.filterSemester;
  return state.rows.filter(r=> {
    if(sem !== 'all' && r.semester !== sem) return false;
    if(!s) return true;
    return r.code.toLowerCase().includes(s) || r.title.toLowerCase().includes(s) || r.grade.toLowerCase().includes(s);
  });
}

function sortRows(rows){
  if(!state.sortKey) return rows;
  const key = state.sortKey; const dir = state.sortDir;
  return rows.slice().sort((a,b)=>{
    const va = a[key], vb = b[key];
    if(typeof va === 'number' && typeof vb === 'number') return dir==='asc'? va-vb : vb-va;
    return dir==='asc'? String(va).localeCompare(String(vb)) : String(vb).localeCompare(String(va));
  });
}

function renderTable(){
  let rows = applyFilters();
  rows = sortRows(rows);
  tbody.innerHTML = rows.map(r=>{
    const pts = (gradePoint(r.grade)*r.unit).toFixed(1);
    const status = r.grade === 'F' ? `<span class="chip bad">Carryover</span>`
                 : (r.grade === 'E' ? `<span class="chip warn">Marginal</span>` : `<span class="chip ok">Passed</span>`);
    return `<tr>
      <td>${r.code}</td>
      <td>${r.title}</td>
      <td>${r.unit}</td>
      <td>${r.grade}</td>
      <td>${pts}</td>
      <td>${status}</td>
      <td>${r.semester}</td>
    </tr>`;
  }).join('');

  // Stats
  const selected = rows;
  const { gpa, totals } = statsFor(selected);
  const cgpa = computeCGPA(state.rows);
  $('#gpa').textContent = gpa ? gpa.toFixed(2) : '-';
  $('#cgpa').textContent = cgpa ? cgpa.toFixed(2) : '-';
  $('#credits').textContent = totals.units;
  const totalCount = totals.pass + totals.carry;
  $('#passRate').textContent = totalCount ? Math.round((totals.pass/totalCount)*100)+'%' : '-';
  $('#classStanding').textContent = cgpa ? classFromCGPA(cgpa) : '-';
  $('#gpaBar').style.width = (gpa/5*100).toFixed(0)+'%';
  $('#totalCount').textContent = Object.values(totals.grades).reduce((a,b)=>a+b,0);

  renderBars(totals.grades);
  renderTrend([...state.cgpaHistory, cgpa || 0]);
}

/* ---------- Grade distribution bars ---------- */
function renderBars(map){
  const order = ['A','B','C','D','E','F'];
  const bars = $('#gradeBars');
  bars.innerHTML = '';
  const total = Object.values(map).reduce((a,b)=>a+b,0) || 1;
  order.forEach(g=>{
    const n = map[g] || 0;
    const pct = Math.round(n/total*100);
    const el = document.createElement('div');
    el.className = 'bar';
    el.style.height = (Math.max(6, pct*1.2)) + 'px';
    el.style.background = gradientFor(g);
    el.innerHTML = `<span>${g}</span>`;
    bars.appendChild(el);
  });
}
function gradientFor(g){
  switch(g){
    case 'A': return 'linear-gradient(180deg,#3be7a1,#19d19a)';
    case 'B': return 'linear-gradient(180deg,#6ea8ff,#3b82f6)';
    case 'C': return 'linear-gradient(180deg,#ffd36a,#ffb74d)';
    case 'D': return 'linear-gradient(180deg,#ff9b6a,#ff7f50)';
    case 'E': return 'linear-gradient(180deg,#ff7ab2,#ff4fa1)';
    case 'F': return 'linear-gradient(180deg,#ff8b8b,#ff6b6b)';
    default: return 'linear-gradient(180deg,#ccc,#aaa)';
  }
}

/* ---------- Trend chart (simple lightweight plot) ---------- */
function renderTrend(values){
  const cvs = $('#trend');
  const ctx = cvs.getContext('2d');
  const DPR = window.devicePixelRatio || 1;
  const W = cvs.clientWidth * DPR, H = cvs.clientHeight * DPR;
  cvs.width = W; cvs.height = H;
  ctx.clearRect(0,0,W,H);

  if(!values || !values.length) return;
  const pad = 32*DPR;
  const left = pad, right = W - 12*DPR, top = 12*DPR, bottom = H - 24*DPR;
  // grid lines
  ctx.strokeStyle = 'rgba(255,255,255,0.06)'; ctx.lineWidth = 1*DPR;
  for(let i=0;i<=5;i++){
    const y = top + (bottom - top)*(1 - i/5);
    ctx.beginPath(); ctx.moveTo(left, y); ctx.lineTo(right, y); ctx.stroke();
  }
  // path
  ctx.lineWidth = 2.5*DPR; ctx.strokeStyle = '#6ea8ff'; ctx.fillStyle = 'rgba(110,168,255,0.12)';
  ctx.beginPath();
  for(let i=0;i<values.length;i++){
    const x = left + (i/(values.length-1 || 1))*(right-left);
    const y = bottom - (Math.min(5,values[i]) /5)*(bottom-top);
    if(i===0) ctx.moveTo(x,y); else ctx.lineTo(x,y);
  }
  ctx.stroke();
  // fill under curve
  ctx.lineTo(right, bottom); ctx.lineTo(left, bottom); ctx.closePath(); ctx.fill();
  // points
  ctx.fillStyle = '#3be7a1';
  for(let i=0;i<values.length;i++){
    const x = left + (i/(values.length-1 || 1))*(right-left);
    const y = bottom - (Math.min(5,values[i]) /5)*(bottom-top);
    ctx.beginPath(); ctx.arc(x,y,4*DPR,0,Math.PI*2); ctx.fill();
  }
}

/* ---------- Interactions ---------- */
semesterSel.addEventListener('change', ()=> { state.filterSemester = semesterSel.value; renderTable(); });
searchInput.addEventListener('input', e=> { state.search = e.target.value; renderTable(); });

$('#clear').addEventListener('click', ()=>{ semesterSel.value='all'; searchInput.value=''; state.filterSemester='all'; state.search=''; renderTable(); });
$('#export').addEventListener('click', ()=>{ exportCSV(); });
$('#recompute').addEventListener('click', ()=>{ renderTable(); });

/* Sorting on header click */
$$('thead th').forEach(th=>{
  th.style.cursor='pointer';
  th.addEventListener('click', ()=> {
    const key = th.getAttribute('data-key');
    if(state.sortKey === key) state.sortDir = state.sortDir === 'asc' ? 'desc' : 'asc';
    else { state.sortKey = key; state.sortDir = 'asc'; }
    renderTable();
  });
});

/* Theme toggle & print */
$('#toggleTheme').addEventListener('click', ()=>{
  const t = document.body.getAttribute('data-theme') === 'light' ? '#0066cc' : 'light';
  document.body.setAttribute('data-theme', t);
  renderTable();
});
$('#printBtn').addEventListener('click', ()=> window.print());

/* Export CSV */
function exportCSV(){
  const rows = sortRows(applyFilters());
  const header = ['Code','Title','Units','Grade','Points','Status','Semester'];
  const csv = [header.join(',')].concat(rows.map(r=>{
    const pts = gradePoint(r.grade)*r.unit;
    const status = r.grade==='F' ? 'Carryover' : (r.grade==='E' ? 'Marginal' : 'Passed');
    return [r.code, `"${r.title.replace(/"/g,'""')}"`, r.unit, r.grade, pts.toFixed(1), status, r.semester].join(',');
  })).join('\n');
  const blob = new Blob([csv], {type:'text/csv'});
  const a = document.createElement('a'); a.href = URL.createObjectURL(blob); a.download = 'results.csv'; a.click(); URL.revokeObjectURL(a.href);
}

/* ---------- Init render ---------- */
renderTable();

/* Re-render on resize for crisp canvas */
let deb; window.addEventListener('resize', ()=> { clearTimeout(deb); deb = setTimeout(()=> renderTable(), 160); });

/* ---------- Accessibility: keyboard open sidebar on "m" ---------- */
document.addEventListener('keydown', (e) => {
  if(e.key === 'm') document.querySelector('.sidebar').classList.toggle('open');
});
</script>




<a href="logout.php">Logout</a>
</body>
</html>

