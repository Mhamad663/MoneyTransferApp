<style>
  .form-label{display:block;font-size:.85rem;color:#475569;margin-bottom:.25rem}
  .form-input{width:100%;border:1px solid #e5e7eb;border-radius:.6rem;padding:.6rem .8rem}
  .btn-primary{background:#2563eb;color:#fff;border-radius:.6rem;padding:.6rem 1rem}
</style>
<script>
  const method = document.getElementById('payout_method');
  const bank = document.getElementById('bank_fields');
  const mobile = document.getElementById('mobile_fields');
  const platform = document.getElementById('platform_fields');
  function toggleFields() {
    bank.classList.add('hidden'); mobile.classList.add('hidden'); platform.classList.add('hidden');
    if (method.value === 'bank') bank.classList.remove('hidden');
    if (method.value === 'mobile_wallet') mobile.classList.remove('hidden');
    if (method.value === 'wallet') platform.classList.remove('hidden');
  }
  method?.addEventListener('change', toggleFields);
  toggleFields();
</script>
