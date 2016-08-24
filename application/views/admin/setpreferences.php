<label>
  <input type="text" id="input"/>
  Net ID
</label>
<script>
function goUserPreferences() {
  window.location.href = window.location.href + '/' + document.getElementById('input').value;
}
</script>
<button onclick="goUserPreferences()">Go</button>
