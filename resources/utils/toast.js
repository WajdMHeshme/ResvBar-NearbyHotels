export function showToast(text, timeout = 2500) {
  const id = 'toast-' + Date.now();
  const container = document.createElement('div');
  container.id = id;
  container.className = 'fixed bottom-6 left-1/2 transform -translate-x-1/2 z-60';
  container.innerHTML = `
    <div class="inline-block bg-slate-800 text-white text-sm px-4 py-2 rounded-md shadow-lg">
      ${text}
    </div>
  `;
  document.body.appendChild(container);
  setTimeout(() => {
    const el = document.getElementById(id);
    if (el) el.remove();
  }, timeout);
}
