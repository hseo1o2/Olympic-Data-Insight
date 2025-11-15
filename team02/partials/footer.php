<!-- partials/footer.php -->
<hr>
<footer style="text-align:center; margin-top:20px;">
    <p>© Team02 네가만든쿠키 | EPL 24–25 – Football Performance Insight</p>

    <!-- Chart.js (전역 1회 로드) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // super-light helpers (모든 페이지에서 재사용)
  function $(s){ return document.querySelector(s); }
  function labelsOf(rows, k){ return rows.map(r => String(r[k] ?? '')); }
  function numsOf(rows, k){ return rows.map(r => Number(r[k] ?? 0)); }
  function mkChart(sel, type, labels, datasets, opt = {}) {
    const el = $(sel); if (!el) return;
    const ctx = el.getContext('2d');
    return new Chart(ctx, { type, data: { labels, datasets }, options: { responsive: true, ...opt }});
  }
</script>
</body>
</html>