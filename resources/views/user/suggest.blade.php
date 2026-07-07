@extends('layouts.user')

@section('title', 'SuggestIQ — AI Suggestion Engine')

@section('user_content')
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          brand: {
            bg: '#0f1117',
            surface: '#181c27',
            panel: '#1e2335',
            border: '#2a3050',
            accent: '#f97316',
            accentLo: 'rgba(249, 115, 22, 0.12)',
            accentMd: 'rgba(249, 115, 22, 0.25)',
            text: '#e8eaf0',
            muted: '#6b7394',
            success: '#22c55e',
          }
        },
        fontFamily: {
          sans: ['Inter', 'sans-serif'],
          mono: ['JetBrains Mono', 'monospace'],
        }
      }
    }
  }
</script>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
  
  .siq-wrapper {
    font-family: 'Inter', sans-serif;
    background-color: #0f1117;
    background-image: radial-gradient(circle at 50% 0%, rgba(249, 115, 22, 0.08) 0%, transparent 50%);
    min-height: calc(100vh - 150px);
    border-radius: 16px;
    padding: 32px 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .shimmer {
    animation: shimmer 1.5s infinite linear;
    background: linear-gradient(90deg, #1e2335 25%, #2a3050 50%, #1e2335 75%);
    background-size: 200% 100%;
  }
  @keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
  }

  @keyframes pulse-slow {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.4; transform: scale(0.95); }
  }
  .pulse-dot {
    animation: pulse-slow 1.6s infinite ease-in-out;
  }
  
  /* Reset layout constraints for this specific view */
  .container {
      max-width: none !important;
  }
</style>

<div class="siq-wrapper">
  <!-- Header -->
  <header class="w-full max-w-2xl flex items-center justify-between mb-8">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 bg-gradient-to-tr from-brand-accent to-orange-500 rounded-xl flex items-center justify-center shadow-lg shadow-brand-accentLo">
        <svg class="w-5 h-5 text-white" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M2 11L5 8l3 2 4-6 2 2"/>
          <circle cx="14" cy="6" r="1" fill="currentColor" stroke="none"/>
        </svg>
      </div>
      <div>
        <h1 class="text-lg font-bold tracking-tight text-white flex items-center gap-2 m-0 p-0">
          SuggestIQ
        </h1>
        <p class="text-xs text-brand-muted font-mono m-0 p-0">INTELLIGENT RECOMMENDATION SYSTEM</p>
      </div>
    </div>
    
    <div class="flex items-center gap-2">
      <span class="text-[11px] font-mono text-brand-accent bg-brand-accentLo border border-orange-500/30 rounded-md px-2.5 py-1">
        AI POWERED
      </span>
    </div>
  </header>

  <!-- Main Workspace Card -->
  <main class="w-full max-w-2xl bg-brand-surface border border-brand-border rounded-2xl overflow-hidden shadow-2xl shadow-black/40">
    
    <!-- User Input Section -->
    <section class="p-6 border-b border-brand-border">
      <div class="text-[10px] font-mono tracking-widest text-brand-muted uppercase mb-2.5">Your Prompt / Query</div>
      <div class="bg-brand-panel border-2 border-brand-border rounded-xl overflow-hidden focus-within:border-brand-accent focus-within:ring-4 focus-within:ring-brand-accentLo transition-all duration-200">
        <textarea id="userInput" placeholder="What are you looking for? (e.g. 'Highly rated boutique hotels with cozy seating' or 'Best budget places to work remotely')" oninput="updateCount()" class="w-full bg-transparent border-none outline-none text-brand-text text-sm leading-relaxed p-4 h-24 resize-none placeholder-brand-muted focus:ring-0" maxlength="500"></textarea>
        
        <div class="flex items-center justify-between p-3 border-t border-brand-border/60 bg-brand-surface/45">
          <span class="text-xs font-mono text-brand-muted" id="charCount">0 / 500</span>
          <button class="flex items-center gap-2 bg-gradient-to-r from-brand-accent to-orange-600 text-white rounded-lg px-4 py-2 text-xs font-semibold tracking-wide hover:opacity-95 active:scale-[0.98] transition-all cursor-pointer shadow-lg shadow-brand-accentLo border-none" onclick="generate()">
            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 14 14">
              <polygon points="2,2 12,7 2,12"/>
            </svg>
            Generate
          </button>
        </div>
      </div>
    </section>

    <!-- App State/Status Bar -->
    <div class="flex items-center gap-3 px-6 h-10 bg-[#12151f] border-b border-brand-border/80">
      <div class="w-2.5 h-2.5 rounded-full bg-brand-muted" id="statusDot"></div>
      <span class="text-xs font-mono text-brand-muted" id="statusText">Awaiting your prompt above to produce curated cards</span>
    </div>

    <!-- Output Section -->
    <section class="p-6">
      <div class="flex items-center justify-between mb-4">
        <div class="text-[10px] font-mono tracking-widest text-brand-muted uppercase">Structured Suggestions</div>
        <div class="flex items-center gap-2" id="outputMeta" style="display:none">
          <span class="text-xs font-mono text-brand-success bg-brand-success/10 border border-brand-success/20 rounded px-2 py-0.5" id="countBadge">
            0 results
          </span>
        </div>
      </div>

      <!-- Recommendation Chips Grid -->
      <div class="flex flex-wrap gap-3.5 min-h-[64px]" id="chipsWrap">
        <div class="flex flex-col items-center justify-center gap-3 py-8 text-brand-muted w-full border-2 border-dashed border-brand-border/60 rounded-xl bg-brand-panel/30">
          <div class="w-12 h-12 rounded-xl border border-dashed border-brand-border flex items-center justify-center opacity-65">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
            </svg>
          </div>
          <span class="text-xs">Your tailored suggestions will populate here as interactive cards</span>
        </div>
      </div>

      <!-- Focused Recommendation Detail Box -->
      <div id="detailPanel" class="mt-5 hidden animate-fadeIn">
        <!-- Injected via JavaScript -->
      </div>

      <!-- Action Footer Buttons -->
      <div class="flex flex-wrap items-center gap-3 mt-6 border-t border-brand-border/40 pt-5" id="actionRow" style="display:none">
        <button class="flex items-center gap-2 rounded-lg border border-brand-border bg-brand-panel px-4 py-2 text-xs font-semibold text-brand-text hover:border-indigo-500 hover:text-indigo-400 transition-all cursor-pointer" onclick="editMode()">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
          </svg>
          Focus Prompt
        </button>
        <button class="flex items-center gap-2 rounded-lg border border-brand-border bg-brand-panel px-4 py-2 text-xs font-semibold text-brand-text hover:border-brand-accent hover:text-brand-accent transition-all cursor-pointer" onclick="generate()">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
          </svg>
          Re-evaluate
        </button>
        <button class="flex items-center gap-2 rounded-lg border border-brand-border bg-brand-panel px-4 py-2 text-xs font-semibold text-brand-text hover:border-emerald-500 hover:text-emerald-400 transition-all cursor-pointer" id="copyBtn" onclick="copySuggestion()">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5A3.375 3.375 0 006.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0015 2.25h-1.5a2.251 2.251 0 00-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 00-9-9z"/>
          </svg>
          <span id="copyLabel">Copy Selected</span>
        </button>
        <div class="flex-1"></div>
        <button class="flex items-center gap-2 rounded-lg bg-brand-accent hover:opacity-90 disabled:opacity-40 disabled:cursor-not-allowed px-5 py-2.5 text-xs font-bold text-white tracking-wide transition-all cursor-pointer border-none shadow-lg shadow-brand-accentLo" id="useBtn" disabled onclick="useSelected()">
          Use Selected →
        </button>
      </div>
    </section>
  </main>

  <!-- Interactive History Presets -->
  <footer class="w-full max-w-2xl mt-6 flex flex-wrap items-center gap-2 px-1">
    <span class="text-[10px] font-mono text-brand-muted tracking-wider uppercase mr-2">Try Presets:</span>
    <span class="text-xs text-brand-muted hover:text-brand-text bg-brand-surface border border-brand-border rounded-full px-3.5 py-1.5 cursor-pointer transition-all active:scale-[0.98]" onclick="fillPill('Highly rated boutique hotels with a nice view')">
      Boutique Hotels
    </span>
    <span class="text-xs text-brand-muted hover:text-brand-text bg-brand-surface border border-brand-border rounded-full px-3.5 py-1.5 cursor-pointer transition-all active:scale-[0.98]" onclick="fillPill('Best cozy cafes for working remotely with fast WiFi')">
      Work Cafes
    </span>
    <span class="text-xs text-brand-muted hover:text-brand-text bg-brand-surface border border-brand-border rounded-full px-3.5 py-1.5 cursor-pointer transition-all active:scale-[0.98]" onclick="fillPill('Must-visit art galleries and modern museums')">
      Art & Culture
    </span>
  </footer>

  <!-- Custom Notification Toast / Popup System -->
  <div id="toast" class="fixed bottom-6 right-6 max-w-sm bg-brand-panel border border-brand-border rounded-xl p-4 shadow-2xl flex gap-3 transform translate-y-12 opacity-0 pointer-events-none transition-all duration-300 z-50">
    <div class="w-8 h-8 rounded-lg bg-brand-success/15 flex items-center justify-center flex-shrink-0 text-brand-success" id="toastIcon">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
      </svg>
    </div>
    <div>
      <h4 class="text-xs font-bold text-white m-0" id="toastTitle">System Notification</h4>
      <p class="text-[11px] text-brand-muted mt-0.5 mb-0" id="toastMsg">Your action was completed successfully.</p>
    </div>
  </div>

  <!-- Custom Action Modal -->
  <div id="actionModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300 z-50">
    <div class="bg-brand-surface border border-brand-border rounded-2xl w-full max-w-md p-6 transform scale-95 transition-transform duration-300 shadow-2xl">
      <div class="flex items-center gap-3.5 mb-4">
        <div class="w-10 h-10 rounded-xl bg-brand-accentLo flex items-center justify-center text-brand-accent" id="modalIcon">
          <!-- Icon injected dynamically -->
        </div>
        <div>
          <h3 class="text-base font-bold text-white m-0" id="modalTitle">Selected Recommendation</h3>
          <p class="text-xs text-brand-muted m-0" id="modalSub">category details</p>
        </div>
      </div>
      <div class="bg-brand-panel border border-brand-border rounded-xl p-4 mb-5">
        <p class="text-sm text-brand-text leading-relaxed m-0" id="modalDesc"></p>
      </div>
      <div class="flex justify-end gap-3">
        <button onclick="closeModal()" class="px-4 py-2 rounded-lg border border-brand-border text-brand-muted bg-transparent text-xs hover:text-white transition-all cursor-pointer">
          Dismiss
        </button>
        <button onclick="copyCurrentModalText()" class="px-4 py-2 rounded-lg bg-brand-accent text-white border-none text-xs font-semibold hover:opacity-90 transition-all cursor-pointer">
          Copy Info
        </button>
      </div>
    </div>
  </div>

</div>

<script>
  let currentItems = [];
  let selectedIndex = -1;

  const ICONS = {
    hotel: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>`,
    restaurant: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>`,
    attraction: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>`,
    transport: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13" rx="2" ry="2"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>`,
    shop: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>`,
    health: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>`,
    default: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>`,
  };

  function getIcon(type) {
    return ICONS[type] || ICONS.default;
  }

  function updateCount() {
    const text = document.getElementById("userInput").value;
    const countLabel = document.getElementById("charCount");
    countLabel.textContent = `${text.length} / 500`;
    
    if (text.length > 450) {
      countLabel.className = "text-xs font-mono text-brand-accent";
    } else {
      countLabel.className = "text-xs font-mono text-brand-muted";
    }
  }

  function showToast(title, message, isSuccess = true) {
    const toast = document.getElementById('toast');
    const toastTitle = document.getElementById('toastTitle');
    const toastMsg = document.getElementById('toastMsg');
    const toastIcon = document.getElementById('toastIcon');

    toastTitle.textContent = title;
    toastMsg.textContent = message;

    if (isSuccess) {
      toastIcon.className = "w-8 h-8 rounded-lg bg-brand-success/15 flex items-center justify-center flex-shrink-0 text-brand-success";
      toastIcon.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>`;
    } else {
      toastIcon.className = "w-8 h-8 rounded-lg bg-red-500/15 flex items-center justify-center flex-shrink-0 text-red-400";
      toastIcon.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>`;
    }

    toast.classList.remove('translate-y-12', 'opacity-0', 'pointer-events-none');
    toast.classList.add('translate-y-0', 'opacity-100');

    setTimeout(() => {
      toast.classList.add('translate-y-12', 'opacity-0', 'pointer-events-none');
      toast.classList.remove('translate-y-0', 'opacity-100');
    }, 4000);
  }

  function fillPill(text) {
    document.getElementById("userInput").value = text;
    updateCount();
    showToast("Preset Loaded", "Modified target prompt parameters", true);
  }

  function setStatus(state, text) {
    const dot = document.getElementById("statusDot");
    const st = document.getElementById("statusText");
    
    dot.className = "w-2.5 h-2.5 rounded-full transition-all duration-300";
    
    if (state === "thinking") {
      dot.className += " bg-brand-accent pulse-dot shadow-lg shadow-brand-accent";
      st.innerHTML = `<span class="text-white">${text}</span>`;
    } else if (state === "done") {
      dot.className += " bg-brand-success";
      st.innerHTML = text;
    } else {
      dot.className += " bg-brand-muted";
      st.innerHTML = text;
    }
  }

  async function generate() {
    const input = document.getElementById("userInput").value.trim();
    if (!input) {
      showToast("Prompt Empty", "Please type something in the query area", false);
      return;
    }

    const chipsWrap = document.getElementById("chipsWrap");
    const actionRow = document.getElementById("actionRow");
    const outputMeta = document.getElementById("outputMeta");
    const detailPanel = document.getElementById("detailPanel");

    actionRow.style.display = "none";
    outputMeta.style.display = "none";
    detailPanel.style.display = "none";
    selectedIndex = -1;
    document.getElementById("useBtn").disabled = true;

    chipsWrap.innerHTML = `
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 w-full">
        ${Array(4).fill().map(() => `
          <div class="shimmer border border-brand-border rounded-xl h-16 p-3 flex gap-3 items-center">
            <div class="w-10 h-10 rounded-lg bg-brand-border flex-shrink-0"></div>
            <div class="flex-1 flex flex-col gap-2">
              <div class="h-3 w-2/3 bg-brand-border rounded"></div>
              <div class="h-2.5 w-1/3 bg-brand-border rounded"></div>
            </div>
          </div>
        `).join('')}
      </div>
    `;
    
    setStatus("thinking", "Consulting AI intelligence models...");

    try {
      const response = await fetch("{{ route('suggest.generate') }}", {
        method: "POST",
        headers: { 
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ query: input })
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.error || "Server responded with an error");
      }

      currentItems = data;

      if (!Array.isArray(currentItems) || currentItems.length === 0) {
        throw new Error("Formulated data block is structurally corrupt.");
      }

      renderChips(currentItems);

      outputMeta.style.display = "flex";
      document.getElementById("countBadge").textContent = `${currentItems.length} results`;
      actionRow.style.display = "flex";
      setStatus("done", `Parsed <span>${currentItems.length} curated recommendations</span> · Click a card to focus details`);
      showToast("Success", `Loaded ${currentItems.length} curated matches.`, true);

    } catch (err) {
      console.error("Failure processing suggestions pipeline:", err);
      chipsWrap.innerHTML = `
        <div class="flex flex-col items-center justify-center gap-2 py-8 text-brand-muted w-full border-2 border-dashed border-orange-500/35 rounded-xl bg-brand-panel/10">
          <span class="text-xs text-orange-400 font-medium font-mono">Pipeline Failure</span>
          <span class="text-[11px] px-8 text-center text-brand-muted/80 max-w-sm">
            ${err.message || "Failed to process recommendations. Please try again."}
          </span>
        </div>
      `;
      setStatus("error", "Failed to retrieve or parse results.");
      showToast("Error", err.message || "Recommendation generation failed.", false);
    }
  }

  function renderChips(items) {
    const chipsWrap = document.getElementById("chipsWrap");
    chipsWrap.innerHTML = "";
    
    const grid = document.createElement("div");
    grid.className = "grid grid-cols-1 sm:grid-cols-2 gap-3.5 w-full";
    
    items.forEach((item, i) => {
      const chip = document.createElement("div");
      chip.className = "flex items-center gap-3 bg-brand-panel border-2 border-brand-border rounded-xl p-3.5 cursor-pointer transition-all hover:border-brand-accent/60 hover:bg-brand-accentLo/10 active:scale-[0.98] select-none min-w-0 shadow-sm relative overflow-hidden group";
      chip.dataset.index = i;
      chip.innerHTML = `
        <div class="w-10 h-10 rounded-lg bg-brand-border/40 group-hover:bg-brand-accent/20 group-hover:text-brand-accent flex items-center justify-center flex-shrink-0 text-brand-muted transition-colors">
          <div class="w-5 h-5">${getIcon(item.type)}</div>
        </div>
        <div class="flex-1 min-w-0">
          <h4 class="text-sm font-semibold text-white group-hover:text-brand-accent transition-colors truncate m-0 mb-0.5">${item.name}</h4>
          <p class="text-[11px] font-mono text-brand-muted m-0 truncate">${item.sub || item.type}</p>
        </div>
        <div class="w-5 h-5 rounded-full bg-brand-accent items-center justify-center flex-shrink-0 hidden transition-all" id="check-${i}">
          <svg class="w-2.5 h-2.5 text-white" viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="1.5,5 4,7.5 8.5,2.5"/>
          </svg>
        </div>
      `;
      chip.addEventListener("click", () => selectChip(i));
      grid.appendChild(chip);
    });
    
    chipsWrap.appendChild(grid);
  }

  function selectChip(index) {
    for (let i = 0; i < currentItems.length; i++) {
      const itemCheck = document.getElementById(`check-${i}`);
      if (itemCheck) itemCheck.style.display = 'none';
    }

    if (selectedIndex === index) {
      selectedIndex = -1;
      document.getElementById("detailPanel").style.display = "none";
      document.getElementById("useBtn").disabled = true;
      setStatus("done", `<span>${currentItems.length} suggestions found</span> · Select a card for rich details`);
      return;
    }

    selectedIndex = index;
    const targetCheck = document.getElementById(`check-${index}`);
    if (targetCheck) {
        targetCheck.style.display = 'flex';
        targetCheck.classList.remove('hidden');
    }
    
    document.getElementById("useBtn").disabled = false;

    const item = currentItems[index];
    showDetail(item);
    setStatus("done", `Currently targeting: <span class="text-white font-bold">${item.name}</span>`);
  }

  function showDetail(item) {
    const panel = document.getElementById("detailPanel");
    panel.style.display = "block";
    panel.innerHTML = `
      <div class="bg-gradient-to-r from-brand-panel to-brand-panel/85 border border-brand-accent/30 rounded-xl p-5 shadow-lg relative overflow-hidden">
        <div class="absolute -right-8 -bottom-8 w-28 h-28 opacity-[0.03] text-brand-accent">
          ${getIcon(item.type)}
        </div>
        <div class="flex items-center gap-3.5 mb-3">
          <div class="w-11 h-11 rounded-lg bg-brand-accent flex items-center justify-center flex-shrink-0 text-white shadow-md shadow-brand-accentLo">
            <div class="w-5.5 h-5.5">${getIcon(item.type)}</div>
          </div>
          <div>
            <h3 class="text-base font-bold text-white tracking-wide m-0 mb-0.5">${item.name}</h3>
            <p class="text-xs font-mono text-brand-accent/90 m-0">${item.sub || item.type.toUpperCase()}</p>
          </div>
        </div>
        <p class="text-sm leading-relaxed text-brand-text/90 bg-[#161a29]/60 p-3.5 rounded-lg border border-brand-border m-0">${item.desc}</p>
      </div>
    `;
  }

  function editMode() {
    const input = document.getElementById("userInput");
    input.focus();
    input.select();
    showToast("Edit Mode Active", "Target prompt element is active", true);
  }

  function copyToClipboard(text) {
    try {
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text);
      } else {
        const el = document.createElement('textarea');
        el.value = text;
        el.setAttribute('readonly', '');
        el.style.position = 'absolute';
        el.style.left = '-9999px';
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
      }
      return true;
    } catch (err) {
      console.warn("Clipboard permission denied", err);
      return false;
    }
  }

  function copySuggestion() {
    let textToCopy = "";
    if (selectedIndex >= 0) {
      const item = currentItems[selectedIndex];
      textToCopy = `${item.name} (${item.sub}) — ${item.desc}`;
    } else {
      textToCopy = currentItems.map(i => `${i.name} (${i.sub}): ${i.desc}`).join("\n");
    }

    const success = copyToClipboard(textToCopy);
    
    const label = document.getElementById("copyLabel");
    const btn = document.getElementById("copyBtn");

    if (success) {
      btn.classList.add("border-brand-success", "text-brand-success");
      label.textContent = "Copied Successful!";
      showToast("Copied to Clipboard", selectedIndex >= 0 ? "Focused detail captured" : "All suggestions captured", true);
      
      setTimeout(() => {
        btn.classList.remove("border-brand-success", "text-brand-success");
        label.textContent = selectedIndex >= 0 ? "Copy Selected" : "Copy All Suggestions";
      }, 2000);
    } else {
      showToast("Copy Failed", "System security blocked direct clipboard access", false);
    }
  }

  function useSelected() {
    if (selectedIndex < 0) return;
    const item = currentItems[selectedIndex];
    
    const modal = document.getElementById('actionModal');
    const iconWrap = document.getElementById('modalIcon');
    const title = document.getElementById('modalTitle');
    const sub = document.getElementById('modalSub');
    const desc = document.getElementById('modalDesc');

    iconWrap.innerHTML = getIcon(item.type);
    title.textContent = item.name;
    sub.textContent = item.sub ? item.sub.toUpperCase() : "GENERAL OPTION";
    desc.textContent = item.desc;

    modal.classList.remove('opacity-0', 'pointer-events-none');
    const modalBox = modal.querySelector('div');
    modalBox.classList.remove('scale-95');
    modalBox.classList.add('scale-100');
  }

  function closeModal() {
    const modal = document.getElementById('actionModal');
    modal.classList.add('opacity-0', 'pointer-events-none');
    
    const modalBox = modal.querySelector('div');
    modalBox.classList.remove('scale-100');
    modalBox.classList.add('scale-95');
  }

  function copyCurrentModalText() {
    const title = document.getElementById('modalTitle').textContent;
    const sub = document.getElementById('modalSub').textContent;
    const desc = document.getElementById('modalDesc').textContent;
    
    const text = `${title} [${sub}] - ${desc}`;
    if (copyToClipboard(text)) {
      showToast("Copied", "Selected recommendation description copied", true);
      closeModal();
    }
  }
</script>
@endsection
