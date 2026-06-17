    </div><!-- /.content -->
  </div><!-- /.main -->
</div><!-- /.admin-wrap -->
<script>
(function(){
  var t = document.getElementById('sidebarToggle');
  var s = document.getElementById('sidebar');
  if (t && s) {
    t.addEventListener('click', function(){ s.classList.toggle('open'); });
    document.addEventListener('click', function(e){
      if (window.innerWidth <= 768 && s.classList.contains('open') &&
          !s.contains(e.target) && e.target !== t) {
        s.classList.remove('open');
      }
    });
  }
  // confirm deletes
  document.querySelectorAll('form[data-confirm]').forEach(function(f){
    f.addEventListener('submit', function(e){
      if (!confirm(f.getAttribute('data-confirm') || 'Are you sure?')) e.preventDefault();
    });
  });
  // tabs (SEO manager)
  document.querySelectorAll('.tab-btn').forEach(function(btn){
    btn.addEventListener('click', function(){
      var target = btn.getAttribute('data-tab');
      document.querySelectorAll('.tab-btn').forEach(function(b){ b.classList.remove('active'); });
      document.querySelectorAll('.tab-panel').forEach(function(p){ p.classList.remove('active'); });
      btn.classList.add('active');
      var panel = document.getElementById(target);
      if (panel) panel.classList.add('active');
    });
  });
})();
</script>
</body>
</html>
