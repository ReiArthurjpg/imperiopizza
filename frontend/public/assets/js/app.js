    const STORAGE_KEY = 'imperial_controle_comandas_v3';
    const SECTORS = ['Montagem', 'Massa', 'Cozinha', 'Forno', 'Despacho', 'Atendimento', 'Estoque', 'Liderança', 'Outros'];
    let state = { people: [], operations: [], globalMassStock: { flourKg: 0, sugarG: 0, saltG: 0, eggs: 0, oilMl: 0, waterL: 0, yeastG: 0 } }; let selectedReportOperationId = null; let productionViewMode = 'list';
    const $ = id => document.getElementById(id);
    const el = { globalDate: $('globalDate'), globalStartDate: $('globalStartDate'), globalEndDate: $('globalEndDate'), phasePill: $('phasePill'), dashboardBanner: $('dashboardBanner'), dashCommands: $('dashCommands'), dashPizzas: $('dashPizzas'), dashKitchen: $('dashKitchen'), dashOven: $('dashOven'), dashDispatch: $('dashDispatch'), dashErrors: $('dashErrors'), dashboardTeam: $('dashboardTeam'), dashboardRank: $('dashboardRank'), dashboardLive: $('dashboardLive'), personForm: $('personForm'), personName: $('personName'), personRole: $('personRole'), editPersonModal: $('editPersonModal'), editPersonForm: $('editPersonForm'), editPersonId: $('editPersonId'), editPersonName: $('editPersonName'), editPersonRole: $('editPersonRole'), peopleChecklist: $('peopleChecklist'), dayTeamGroups: $('dayTeamGroups'), teamOperationNotice: $('teamOperationNotice'), saveTeamBtn: $('saveTeamBtn'), startOperationBtn: $('startOperationBtn'), manageTeamBtn: $('manageTeamBtn'), manageTeamDispatchBtn: $('manageTeamDispatchBtn'), productionGate: $('productionGate'), productionContent: $('productionContent'), productionSubtotals: $('productionSubtotals'), openRegisterCommandBtn: $('openRegisterCommandBtn'), openUpdateCommandsBtn: $('openUpdateCommandsBtn'), updatePendingBadge: $('updatePendingBadge'), productionRecent: $('productionRecent'), commandHistoryPanel: $('commandHistoryPanel'), registerCommandModal: $('registerCommandModal'), assemblerSearch: $('assemblerSearch'), assemblerId: $('assemblerId'), assemblerSuggestions: $('assemblerSuggestions'), assemblerPickerBtn: $('assemblerPickerBtn'), assemblerPickerValue: $('assemblerPickerValue'), assemblerPickerModal: $('assemblerPickerModal'), assemblerPickerSearch: $('assemblerPickerSearch'), assemblerPickerList: $('assemblerPickerList'), pickerManageTeamBtn: $('pickerManageTeamBtn'), commandNumber: $('commandNumber'), pizzaQty: $('pizzaQty'), commandSuggestions: $('commandSuggestions'), commandNote: $('commandNote'), initialOven: $('initialOven'), addCommandBtn: $('addCommandBtn'), prodSearch: $('prodSearch'), prodStatus: $('prodStatus'), prodAssembler: $('prodAssembler'), clearProdFilters: $('clearProdFilters'), productionTableContainer: $('productionTableContainer'), productionGridContainer: $('productionGridContainer'), viewListBtn: $('viewListBtn'), viewGridBtn: $('viewGridBtn'), productionBody: $('productionBody'), productionMobileList: $('productionMobileList'), productionEmpty: $('productionEmpty'), closeKitchenBtn: $('closeKitchenBtn'), reopenKitchenBtn: $('reopenKitchenBtn'), dispatchGate: $('dispatchGate'), dispatchContent: $('dispatchContent'), dispatchSubtotals: $('dispatchSubtotals'), dispatchSearch: $('dispatchSearch'), dispatchFilter: $('dispatchFilter'), clearDispatchFilters: $('clearDispatchFilters'), dispatchGrid: $('dispatchGrid'), dispatchEmpty: $('dispatchEmpty'), finishDayBtn: $('finishDayBtn'), historyList: $('historyList'), reportOverview: $('reportOverview'), reportCards: $('reportCards'), backupBtn: $('backupBtn'), restoreBtn: $('restoreBtn'), restoreFile: $('restoreFile'), editModal: $('editModal'), editForm: $('editForm'), editId: $('editId'), editNumber: $('editNumber'), editQty: $('editQty'), editAssembler: $('editAssembler'), editStatus: $('editStatus'), editNote: $('editNote'), deleteCommandBtn: $('deleteCommandBtn'), errorModal: $('errorModal'), errorForm: $('errorForm'), errorId: $('errorId'), errorType: $('errorType'), errorNote: $('errorNote'), clearErrorBtn: $('clearErrorBtn'), toast: $('toast'), confirmDeleteModal: $('confirmDeleteModal'), sweetAssemblerModal: $('sweetAssemblerModal'), sweetAssemblerForm: $('sweetAssemblerForm'), sweetAssemblerCmdId: $('sweetAssemblerCmdId'), sweetAssemblerCmdNumber: $('sweetAssemblerCmdNumber'), sweetAssemblerCmdQty: $('sweetAssemblerCmdQty'), sweetAssemblerCmdSweetQty: $('sweetAssemblerCmdSweetQty'), sweetAssemblerCmdMainName: $('sweetAssemblerCmdMainName'), sweetAssemblerSelect: $('sweetAssemblerSelect'), overnightCloseModal: $('overnightCloseModal'), closeOvernightModalBtn: $('closeOvernightModalBtn'), cancelOvernightBtn: $('cancelOvernightBtn'), confirmOvernightCloseBtn: $('confirmOvernightCloseBtn') };

    function save() {
      fetch('/api/sync', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(state)
      }).catch(e => {
        console.error("Erro ao salvar", e);
        toast("Erro ao salvar na nuvem.", "error");
      });
    }
    function uid() { return (window.crypto && crypto.randomUUID) ? crypto.randomUUID() : Date.now().toString(36) + Math.random().toString(36).slice(2) }
    function today() { const d = new Date(), o = d.getTimezoneOffset(); return new Date(d.getTime() - o * 60000).toISOString().slice(0, 10) }
    function norm(v) { return String(v || '').normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().trim() }
    function proper(v) { return v.trim().replace(/\s+/g, ' ').split(' ').map(w => w ? w.charAt(0).toUpperCase() + w.slice(1).toLowerCase() : w).join(' ') }
    function esc(v) { return String(v ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;') }
    function formatDate(v) { if (!v) return '—'; const [y, m, d] = v.split('-'); return `${d}/${m}/${y}` }
    function formatTime(v) { return v ? new Date(v).toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' }) : '—' }
    function toast(msg, type = 'ok') { el.toast.textContent = msg; el.toast.className = `toast ${type} show`; clearTimeout(window.__t); window.__t = setTimeout(() => el.toast.classList.remove('show'), 3200) }
    function currentDate() { return el.globalStartDate ? el.globalStartDate.value : today() }
    function currentOperation() { return state.operations.find(o => o.date === currentDate()) || null }
    function getOperation(id) { return state.operations.find(o => o.id == id) || null }
    function getPerson(id) { return state.people.find(p => p.id == id) || null }
    function getCommand(op, id) { return op?.commands.find(c => c.id == id) || null }
    function ensureOperation() { let op = currentOperation(); if (!op) { op = { id: uid(), date: currentDate(), status: 'draft', team: [], startedAt: null, kitchenClosedAt: null, completedAt: null, commands: [] }; state.operations.push(op); save() } return op }
    function phaseLabel(op) { if (!op) return 'Sem operação'; return { draft: 'Equipe em preparação', production_open: 'Cozinha em operação', kitchen_closed: 'Cozinha encerrada · despacho ativo', completed: 'Operação finalizada' }[op.status] || op.status }
    function gateCard(title, description, buttonText, targetPage, iconName = 'play-circle') {
      setTimeout(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); }, 10);
      const btnIcons = { team: 'users', reports: 'bar-chart-2', production: 'chef-hat' };
      const btnIcon = btnIcons[targetPage] || 'arrow-right';
      return `<div class="flex flex-col items-center justify-center text-center bg-white rounded-xl border border-[#E7E7E7] shadow-[0_4px_12px_rgba(0,0,0,0.02)] p-8 md:p-12 min-h-[420px] w-full my-6 animate-fade">
        <div class="w-16 h-16 bg-[#FDECEB] text-[#B5120B] rounded-full flex items-center justify-center mb-6 shadow-sm">
          <i data-lucide="${iconName}" class="w-8 h-8"></i>
        </div>
        <h3 class="text-xl font-bold text-[#171717] mb-2">${title}</h3>
        <p class="text-sm text-[#737373] max-w-md mb-8">${description}</p>
        <button class="px-6 py-3 bg-[#B5120B] text-white text-sm font-semibold rounded-lg hover:bg-[#9a0f09] active:scale-[0.98] transition-all duration-150 shadow-md flex items-center justify-center gap-2" data-go="${targetPage}">
          <i data-lucide="${btnIcon}" class="w-4 h-4"></i>
          ${buttonText}
        </button>
      </div>`;
    }
    function showPage(name) { 
      document.querySelectorAll('.page').forEach(p => p.classList.toggle('active', p.id === `page-${name}`)); 
      document.querySelectorAll('.sidebar-nav-btn').forEach(b => {
        const isActive = b.dataset.page === name;
        b.classList.toggle('active', isActive);
        if (isActive) {
          b.classList.remove('text-gray-600', 'hover:bg-[#FDECEB]', 'hover:text-[#B5120B]');
          b.classList.add('bg-[#FDECEB]', 'text-[#B5120B]');
          const icon = b.querySelector('i, svg');
          if (icon) {
            icon.classList.remove('text-gray-400', 'group-hover:text-[#B5120B]');
            icon.classList.add('text-[#B5120B]');
          }
        } else {
          b.classList.add('text-gray-600', 'hover:bg-[#FDECEB]', 'hover:text-[#B5120B]');
          b.classList.remove('bg-[#FDECEB]', 'text-[#B5120B]');
          const icon = b.querySelector('i, svg');
          if (icon) {
            icon.classList.add('text-gray-400', 'group-hover:text-[#B5120B]');
            icon.classList.remove('text-[#B5120B]');
          }
        }
      });
      document.querySelectorAll('.mobile-nav .nav-btn, .more-menu-item').forEach(b => {
        b.classList.toggle('active', b.dataset.page === name || (name === 'dispatch' && b.dataset.page === 'dispatch') || (name === 'reports' && b.dataset.page === 'reports'));
      });
      
      // Update 'Mais' button active state if one of its children is active
      const moreBtn = document.getElementById('moreMenuBtn');
      if (moreBtn) {
        moreBtn.classList.toggle('active', ['dispatch', 'reports'].includes(name));
      }
      
      // Removed registerCommandModal toggle

      if (name === 'dashboard') { renderDashboard(); fetchTopMontadoresMensal(); }
      if (name === 'team') renderTeam(); 
      if (name === 'production') renderProduction(); 
      if (name === 'dispatch') renderDispatch(); 
      if (name === 'reports') renderReports(); 
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    function renderHeader() { const op = currentOperation(); if (el.phasePill) { el.phasePill.textContent = phaseLabel(op); el.phasePill.className = 'phase-pill'; if (op?.status === 'production_open') el.phasePill.classList.add('open'); if (op?.status === 'kitchen_closed') el.phasePill.classList.add('kitchen-closed'); if (op?.status === 'completed') el.phasePill.classList.add('done') } }
    function stats(op) { const cs = op?.commands || []; return { commands: cs.length, pizzas: cs.reduce((a, c) => a + (Number(c.pizzas) || 1), 0), kitchen: cs.filter(c => c.status === 'cozinha').length, oven: cs.filter(c => c.status === 'forno' || c.status === 'pronto').length, dispatchPending: cs.filter(c => c.status === 'despacho' && c.dispatch?.status !== 'liberado').length, released: cs.filter(c => c.status === 'despacho' && c.dispatch?.status === 'liberado').length, errors: cs.filter(c => c.error?.active).length } }
    function pendingToFinish(op) { return (op?.commands || []).filter(c => c.status !== 'despacho' || c.dispatch?.status !== 'liberado').length }
    function empty(title, text) { return `<div class="empty"><strong>${esc(title)}</strong>${esc(text)}</div>` }
    function teamHtml(team, scrollClass = 'max-h-[300px] overflow-y-auto pr-2 custom-scrollbar') { 
      if (!team?.length) return empty('Equipe não definida', 'Selecione os profissionais do dia.'); 
      return `<div class="space-y-3 ${scrollClass}">${team.map(member => `
        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition-colors -mx-2">
          <div class="flex items-center gap-3">
            <div class="relative">
              <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-semibold text-gray-600 border border-gray-300">
                ${assemblerInitials(member.name)}
              </div>
              <div class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full border-2 border-white bg-emerald-500"></div>
            </div>
            <div>
              <p class="text-sm font-medium text-[#171717]">${esc(member.name)}</p>
              <p class="text-[11px] text-gray-500">${esc(member.role)}</p>
            </div>
          </div>
        </div>`).join('')}</div>`;
    }
    function ranking(op) { const m = {}; (op?.team || []).filter(p => p.role.includes('Montagem')).forEach(p => m[p.personId] = { personId: p.personId, name: p.name, commands: 0, pizzas: 0, errors: 0 }); (op?.commands || []).forEach(c => { m[c.assemblerId] ||= { personId: c.assemblerId, name: c.assemblerName, commands: 0, pizzas: 0, errors: 0 }; const sq = Number(c.special?.sweet) || 0; if (c.sweetAssemblerId && sq > 0) { m[c.assemblerId].commands++; m[c.assemblerId].pizzas += (Number(c.pizzas) || 1); if (c.error?.active) m[c.assemblerId].errors++; m[c.sweetAssemblerId] ||= { personId: c.sweetAssemblerId, name: c.sweetAssemblerName, commands: 0, pizzas: 0, errors: 0 }; m[c.sweetAssemblerId].commands++; m[c.sweetAssemblerId].pizzas += sq; if (c.error?.active) m[c.sweetAssemblerId].errors++; } else { m[c.assemblerId].commands++; m[c.assemblerId].pizzas += Number(c.pizzas) || 1; if (c.error?.active) m[c.assemblerId].errors++; } }); return Object.values(m).sort((a, b) => b.pizzas - a.pizzas || b.commands - a.commands || a.name.localeCompare(b.name, 'pt-BR')) }
    function rankHtml(op) { const r = ranking(op), total = stats(op).pizzas; if (!r.length) return empty('Sem resultado de montagem', 'O ranking aparece após o registro das comandas.'); return `<div class="rank-table">${r.map((x, i) => `<div class="rank-row"><div class="rank-pos">${i + 1}º</div><div class="rank-name"><strong>${esc(x.name)}</strong><small>${total ? ((x.pizzas / total) * 100).toFixed(1) : '0.0'}% das pizzas</small></div><div class="rank-metric"><strong>${x.pizzas}</strong><small>pizzas</small></div><div class="rank-metric"><strong>${x.commands}</strong><small>comandas</small></div><div class="rank-metric"><strong>${x.errors}</strong><small>erros</small></div></div>`).join('')}</div>` }

    function dashboardTop5Html(op) {
      const list = ranking(op).slice(0, 5), total = stats(op).pizzas;
      if (!list.length) return empty('Top 5 ainda vazio', 'O ranking começa após o registro das pizzas.');
      return `<div class="space-y-4">${list.map((x, i) => `
        <div class="flex items-center justify-between group">
          <div class="flex items-center gap-3">
            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold ${i === 0 ? 'bg-[#FFF9E5] text-[#D97706] ring-1 ring-[#FDE68A]' : 'bg-gray-100 text-gray-500'}">
              ${i + 1}
            </div>
            <div>
              <p class="text-sm font-medium text-[#171717] group-hover:text-[#B5120B] transition-colors">${esc(x.name)}</p>
              <p class="text-[11px] text-gray-500">Montador</p>
            </div>
          </div>
          <div class="text-sm font-semibold text-[#171717]">
            ${x.pizzas} <span class="text-xs text-gray-400 font-normal">pizzas</span>
          </div>
        </div>`).join('')}</div>`;
    }
    function dashboardLiveHtml(op) {
      const recent = [...(op?.commands || [])].sort((a, b) => (b.updatedAt || b.createdAt).localeCompare(a.updatedAt || a.createdAt));
      if (!recent.length) return `<div class="dashboard-live-empty">${empty('Sem movimento ainda', 'As últimas comandas aparecerão aqui em tempo real.')}</div>`;

      const statusConfig = {
        cozinha:  { label: 'Na cozinha',  bg: 'bg-blue-50',   text: 'text-blue-700',   dot: 'bg-blue-500',    ring: 'ring-blue-100'   },
        forno:    { label: 'No forno',    bg: 'bg-orange-50', text: 'text-orange-700', dot: 'bg-orange-500',  ring: 'ring-orange-100' },
        despacho: { label: 'Despachada',  bg: 'bg-emerald-50',text: 'text-emerald-700',dot: 'bg-emerald-500', ring: 'ring-emerald-100' },
      };

      return `<div class="space-y-2 max-h-[170px] overflow-y-auto pr-2 custom-scrollbar">${recent.map(c => {
        const cfg = statusConfig[c.status] || statusConfig.cozinha;
        const equiv = (c.pizzas || 0) + (c.special?.volcano || 0) + (c.special?.esfiha || 0);
        const time = formatTime(c.updatedAt || c.createdAt);
        const num = String(c.number).padStart(3, '0');
        const name = esc(c.assemblerName || 'Montador');
        return `
        <div class="flex items-center justify-between px-3 py-2.5 rounded-lg border border-gray-100 bg-gray-50/60 hover:bg-white hover:border-gray-200 hover:shadow-sm transition-all duration-150 group">
          <div class="flex items-center gap-3 min-w-0">
            <div class="relative shrink-0">
              <div class="w-2 h-2 rounded-full ${cfg.dot} ring-4 ${cfg.ring}"></div>
            </div>
            <div class="min-w-0">
              <p class="text-sm font-semibold text-[#171717] group-hover:text-[#B5120B] transition-colors leading-tight">Comanda #${num}</p>
              <p class="text-[11px] text-gray-500 mt-0.5 truncate">${name} · ${time}</p>
            </div>
          </div>
          <div class="flex items-center gap-2 shrink-0">
            <span class="text-xs font-semibold text-[#171717]">${equiv} <span class="font-normal text-gray-400">equiv.</span></span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold ${cfg.bg} ${cfg.text}">${cfg.label}</span>
          </div>
        </div>`;
      }).join('')}</div>`;
    }
    function animateMetric(node, target) {
      if (!node) return;
      target = Number(target) || 0;
      const reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      if (reduce) { node.textContent = target; return }
      if (node._metricFrame) cancelAnimationFrame(node._metricFrame);
      const start = Number(node.textContent) || 0, change = target - start, duration = 340, began = performance.now();
      const tick = now => {
        const p = Math.min(1, (now - began) / duration), eased = 1 - Math.pow(1 - p, 3);
        node.textContent = Math.round(start + change * eased);
        if (p < 1) node._metricFrame = requestAnimationFrame(tick);
        else {
          node.textContent = target;
          node.classList.remove('metric-pop');
          void node.offsetWidth;
          node.classList.add('metric-pop');
        }
      };
      node._metricFrame = requestAnimationFrame(tick);
    }

    function renderPipeline(op) {
      const container = document.getElementById('pipelineContainer');
      if (!container) return;
      if (!op) {
        container.innerHTML = empty('Fluxo inativo', 'Inicie a operação.');
        return;
      }
      const s = stats(op);
      const pipelineSteps = [
        { stage: 'Comandas', count: s.commands, color: 'border-l-blue-500' },
        { stage: 'Cozinha', count: s.kitchen, color: 'border-l-orange-500' },
        { stage: 'Forno', count: s.oven, color: 'border-l-red-500' },
        { stage: 'Despacho', count: s.dispatchPending, color: 'border-l-green-500' },
        { stage: 'Finalizadas', count: s.released, color: 'border-l-gray-500' }
      ];
      
      container.innerHTML = pipelineSteps.map((step, idx) => `
        <div class="shrink-0 w-full sm:w-[130px] md:flex-1 bg-gray-50 rounded-lg p-6 sm:p-4 border border-gray-100 flex flex-col items-center justify-center text-center relative group hover:bg-white hover:border-gray-200 hover:shadow-sm transition-all cursor-pointer snap-start min-h-[140px] sm:min-h-0">
          <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-12 sm:h-8 rounded-r-md bg-gray-300 ${step.color} border-l-4 group-hover:h-16 sm:group-hover:h-12 transition-all"></div>
          <span class="text-4xl sm:text-2xl font-bold text-[#171717] mb-2 sm:mb-1">${step.count}</span>
          <span class="text-sm sm:text-xs font-medium text-gray-500 uppercase tracking-wider">${step.stage}</span>
        </div>
        ${idx < pipelineSteps.length - 1 ? `
          <div class="hidden md:flex text-gray-300 shrink-0">
            <i data-lucide="chevron-right" class="w-5 h-5"></i>
          </div>
        ` : ''}
      `).join('');

      // Auto-scroll para mobile
      if (window.pipelineScrollInterval) clearInterval(window.pipelineScrollInterval);
      if (window.innerWidth < 768) {
        let scrollPos = 0;
        window.pipelineScrollInterval = setInterval(() => {
          if (!container || !document.body.contains(container)) {
            clearInterval(window.pipelineScrollInterval);
            return;
          }
          const maxScroll = container.scrollWidth - container.clientWidth;
          if (maxScroll <= 0) return;
          
          // Rolar a largura exata de um card (que agora é a largura do container) + gap (12px)
          scrollPos += container.clientWidth + 12; 
          if (scrollPos > maxScroll + 10) scrollPos = 0;
          
          container.scrollTo({ left: scrollPos, behavior: 'smooth' });
        }, 2500);

        // Pausa se o usuário tocar
        container.addEventListener('touchstart', () => {
          clearInterval(window.pipelineScrollInterval);
        }, { once: true });
      }
    }


    // ── Top Montadores do Mês (Controlado pelo picker de Mês/Ano do header) ──
    let isMonthPickerInitialized = false;

    function initHeaderMonthPicker() {
      const picker = document.getElementById('headerMonthPicker');
      if (!picker || isMonthPickerInitialized) return;
      isMonthPickerInitialized = true;

      const now = new Date();
      const y = now.getFullYear();
      const m = String(now.getMonth() + 1).padStart(2, '0');
      
      // Define o valor inicial como Mês/Ano atual
      picker.value = `${y}-${m}`;

      // Adiciona listener para recarregar o ranking e KPIs ao mudar o mês
      picker.addEventListener('change', () => {
        fetchTopMontadoresMensal();
        renderDashboard();
      });
    }

    function fetchTopMontadoresMensal() {
      initHeaderMonthPicker();
      const picker = document.getElementById('headerMonthPicker');
      let url = '/api/dashboard/top-montadores-mensal';
      
      if (picker && picker.value) {
        const [ano, mes] = picker.value.split('-');
        url += `?ano=${ano}&mes=${mes}`;
      }

      fetch(url)
        .then(res => res.json())
        .then(list => {
          const container = document.getElementById('dashboardRankMensal');
          if (!container) return;
          if (!list || !list.length) {
            container.innerHTML = empty('Top 5 ainda vazio', 'Nenhum montador fez pizzas neste período.');
            return;
          }
          container.innerHTML = `<div class="space-y-4">${list.slice(0, 5).map((x, i) => `
            <div class="flex items-center justify-between group">
              <div class="flex items-center gap-3">
                <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold ${i === 0 ? 'bg-[#FFF9E5] text-[#D97706] ring-1 ring-[#FDE68A]' : 'bg-gray-100 text-gray-500'}">
                  ${i + 1}
                </div>
                <div>
                  <p class="text-sm font-medium text-[#171717] group-hover:text-[#B5120B] transition-colors">${esc(x.name)}</p>
                  <p class="text-[11px] text-gray-500">Montador</p>
                </div>
              </div>
              <div class="text-sm font-semibold text-[#171717]">
                ${x.pizzas} <span class="text-xs text-gray-400 font-normal">pizzas</span>
              </div>
            </div>`).join('')}</div>`;
        })
        .catch(err => {
          console.error('Erro ranking mensal:', err);
          const container = document.getElementById('dashboardRankMensal');
          if (container) container.innerHTML = empty('Top 5 ainda vazio', 'Erro ao obter dados.');
        });
    }
    // Atualiza o ranking a cada 5 minutos automaticamente
    setInterval(fetchTopMontadoresMensal, 5 * 60 * 1000);

    function renderDashboard() {
      renderHeader();
      const op = currentOperation(), s = stats(op);
      
      const picker = document.getElementById('headerMonthPicker');
      let startDate = '', endDate = '';
      if (picker && picker.value) {
        const [ano, mes] = picker.value.split('-');
        startDate = `${ano}-${mes}-01`;
        endDate = new Date(ano, mes, 0).toISOString().split('T')[0];
      } else {
        const now = new Date();
        const y = now.getFullYear(), m = String(now.getMonth() + 1).padStart(2, '0');
        startDate = `${y}-${m}-01`;
        endDate = new Date(y, now.getMonth() + 1, 0).toISOString().split('T')[0];
      }

      fetch(`/api/dashboard/kpis?start_date=${startDate}&end_date=${endDate}`)
        .then(res => res.json())
        .then(kpis => {
          animateMetric(el.dashCommands, kpis.comandas);
          animateMetric(el.dashKitchen, kpis.cozinha);
          animateMetric(el.dashPizzas, kpis.pizzas);
          animateMetric(el.dashDispatch, kpis.pendentes);
          animateMetric(el.dashErrors, kpis.erros);
        })
        .catch(err => {
          // Fallback to local memory calculations if API is unavailable
          animateMetric(el.dashCommands, s.commands);
          animateMetric(el.dashKitchen, s.kitchen);
          animateMetric(el.dashPizzas, s.pizzas);
          animateMetric(el.dashDispatch, s.dispatchPending);
          animateMetric(el.dashErrors, s.errors);
        });
      


      // (removed duplicate team badge update)
      
      renderPipeline(op);
      
      if (!op) {
        el.dashboardBanner.innerHTML = `<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-4 rounded-xl border bg-gray-50 border-gray-200 w-full">
          <div class="flex items-start sm:items-center gap-3">
            <div class="relative flex h-3 w-3 mt-1 sm:mt-0 shrink-0">
              <span class="relative inline-flex rounded-full h-3 w-3 bg-gray-400"></span>
            </div>
            <div>
              <h3 class="font-semibold text-sm text-gray-700">Operação não iniciada</h3>
              <p class="text-xs text-gray-500 mt-0.5">Cadastre a equipe e abra a produção para começar.</p>
            </div>
          </div>
          <div class="flex w-full sm:w-auto gap-2">
            <button class="flex-1 sm:flex-none justify-center px-4 py-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors" data-go="team">Organizar Equipe</button>
            <button class="flex-1 sm:flex-none justify-center px-4 py-2 text-xs font-medium text-white bg-[#B5120B] rounded-lg hover:bg-[#9a0f09] shadow-sm transition-colors" data-go="team">Abrir Produção</button>
          </div>
        </div>`;
        el.dashboardTeam.innerHTML = empty('Equipe não cadastrada', 'Abra a página Equipe.');
        const rankMensalEl = document.getElementById('dashboardRankMensal');
        if (rankMensalEl) rankMensalEl.innerHTML = empty('Top 5 ainda vazio', 'O ranking será calculado pela quantidade de pizzas.');
        el.dashboardLive.innerHTML = `<div class="dashboard-live-empty">${empty('Sem movimento', 'Inicie a produção para acompanhar as comandas.')}</div>`;
        if (typeof lucide !== 'undefined') lucide.createIcons();
        return;
      }
      const pend = pendingToFinish(op);
      let desc = '';
      if (op.status === 'draft') desc = `Produção em preparação • ${op.team.length} pessoas selecionadas`;
      if (op.status === 'production_open') desc = `Produção iniciada às ${formatTime(op.startedAt)}`;
      if (op.status === 'kitchen_closed') desc = `Cozinha encerrada às ${formatTime(op.kitchenClosedAt)} • ${pend} pendentes`;
      if (op.status === 'completed') desc = `Dia finalizado às ${formatTime(op.completedAt)} • ${s.pizzas} pizzas`;
      
      const isActive = op.status === 'production_open';
      el.dashboardBanner.innerHTML = `<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-4 rounded-xl border ${isActive ? 'bg-[#E7F8F0] border-[#A7F3D0]' : 'bg-gray-50 border-gray-200'} w-full">
        <div class="flex items-start sm:items-center gap-3">
          <div class="relative flex h-3 w-3 mt-1 sm:mt-0 shrink-0">
            ${isActive ? '<span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>' : ''}
            <span class="relative inline-flex rounded-full h-3 w-3 ${isActive ? 'bg-emerald-500' : 'bg-gray-400'}"></span>
          </div>
          <div>
            <h3 class="font-semibold text-sm ${isActive ? 'text-emerald-900' : 'text-gray-700'}">${phaseLabel(op)}</h3>
            <p class="text-xs ${isActive ? 'text-emerald-700' : 'text-gray-500'} mt-0.5">${desc}</p>
          </div>
        </div>
        ${op.status === 'draft' ? `
          <div class="flex w-full sm:w-auto gap-2">
            <button class="flex-1 sm:flex-none justify-center px-4 py-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors" data-go="team">Organizar Equipe</button>
            <button class="flex-1 sm:flex-none justify-center px-4 py-2 text-xs font-medium text-white bg-[#B5120B] rounded-lg hover:bg-[#9a0f09] shadow-sm transition-colors" id="startOperationBtn">Abrir Produção</button>
          </div>
        ` : ''}
      </div>`;
      el.dashboardTeam.innerHTML = teamHtml(op.team);
      el.dashboardLive.innerHTML = dashboardLiveHtml(op);
      
      const pBadge = document.getElementById('dashboardPipelineBadge');
      if (pBadge) {
        if (op.status === 'kitchen_closed' || op.status === 'completed') {
          pBadge.className = 'px-2.5 py-0.5 rounded-full text-xs font-medium border bg-gray-100 text-gray-500 border-gray-300';
          pBadge.textContent = 'Inativo';
        } else {
          pBadge.className = 'px-2.5 py-0.5 rounded-full text-xs font-medium border bg-[#FDECEB] text-[#B5120B] border-[#FCA5A5]';
          pBadge.textContent = 'Fluxo Atual';
        }
      }
      
      const onlineBadge = document.getElementById('dashboardTeamOnlineBadge');
      if (onlineBadge) {
        if (op.status === 'kitchen_closed' || op.status === 'completed') {
          onlineBadge.className = 'px-2.5 py-0.5 rounded-full text-xs font-medium border bg-gray-100 text-gray-500 border-gray-300';
          onlineBadge.textContent = `${op.team.length} Offline`;
        } else {
          onlineBadge.className = 'px-2.5 py-0.5 rounded-full text-xs font-medium border bg-[#E7F8F0] text-[#10B981] border-[#A7F3D0]';
          onlineBadge.textContent = `${op.team.length} Online`;
        }
      }
      
      // Simula Alertas
      const alertsContainer = document.getElementById('alertsContainer');
      if (alertsContainer) {
        const alertsList = [];
        if (s.oven > 3) alertsList.push("🟡 3 comandas ou mais aguardando forno");
        if (s.errors > 0) alertsList.push(`🔴 ${s.errors} erro(s) ou atraso(s) requerendo atenção na operação`);
        
        alertsContainer.innerHTML = alertsList.map(alert => `
          <div class="flex items-center justify-between bg-white px-4 py-2.5 rounded-lg border border-gray-200 shadow-sm">
            <span class="text-sm font-medium text-[#171717] flex items-center gap-2">${alert}</span>
            <button class="text-gray-400 hover:text-gray-600" onclick="this.parentElement.remove()"><i data-lucide="x" class="w-4 h-4"></i></button>
          </div>
        `).join('');
      }
      
      if (typeof lucide !== 'undefined') lucide.createIcons();
    }
    // ── Helpers de cor por setor ──────────────────────────────────────────────
    const SECTOR_COLORS = {
      'Montagem':    { bg: 'bg-blue-50',   text: 'text-blue-700',   ring: 'ring-blue-200'   },
      'Massa':       { bg: 'bg-amber-50',  text: 'text-amber-700',  ring: 'ring-amber-200'  },
      'Cozinha':     { bg: 'bg-orange-50', text: 'text-orange-700', ring: 'ring-orange-200' },
      'Forno':       { bg: 'bg-red-50',    text: 'text-red-700',    ring: 'ring-red-200'    },
      'Despacho':    { bg: 'bg-purple-50', text: 'text-purple-700', ring: 'ring-purple-200' },
      'Atendimento': { bg: 'bg-pink-50',   text: 'text-pink-700',   ring: 'ring-pink-200'   },
      'Estoque':     { bg: 'bg-green-50',  text: 'text-green-700',  ring: 'ring-green-200'  },
      'Liderança':   { bg: 'bg-indigo-50', text: 'text-indigo-700', ring: 'ring-indigo-200' },
      'Outros':      { bg: 'bg-gray-50',   text: 'text-gray-600',   ring: 'ring-gray-200'   },
    };
    function sectorColors(role) { 
      const firstRole = (role || '').split(',')[0].trim();
      return SECTOR_COLORS[firstRole] || SECTOR_COLORS['Outros']; 
    }

    // ── Renderiza um item da lista de profissionais (Tailwind) ────────────────
    function personItemHtml(p, selected, usedIds) {
      const initials = assemblerInitials(p.name);
      const isChecked = selected.has(p.id);
      const isUsed = usedIds.has(p.id);
      const sc = sectorColors(p.role);
      return `
        <div class="flex items-center justify-between p-3 rounded-xl border
                    ${isChecked ? 'border-[#B5120B]/30 bg-[#FDECEB]/30' : 'border-[#E7E7E7] hover:border-[#B5120B]/20 hover:bg-gray-50/60'}
                    transition-all duration-150 group">
          <label class="flex items-center gap-3 flex-1 cursor-pointer min-w-0">
            <input type="checkbox"
                   data-team-id="${p.id}"
                   ${isChecked ? 'checked' : ''}
                   ${isUsed ? 'data-used-in-production="1"' : ''}
                   class="w-4 h-4 accent-[#B5120B] shrink-0 cursor-pointer">
            <div class="flex items-center gap-2.5 min-w-0">
              <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#173F69] to-[#1F6FB2]
                          flex items-center justify-center text-[11px] font-bold text-white shrink-0 ring-2 ring-white shadow-sm">
                ${initials}
              </div>
              <div class="min-w-0">
                <p class="text-sm font-semibold text-[#171717] truncate leading-tight">${esc(p.name)}</p>
                <div class="flex items-center gap-1.5 mt-0.5">
                  <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-semibold
                               ${sc.bg} ${sc.text} ring-1 ${sc.ring}">
                    ${esc(p.role)}
                  </span>
                  ${isUsed ? '<span class="text-[10px] text-[#737373]">· com produção</span>' : ''}
                </div>
              </div>
            </div>
          </label>
          <div class="flex items-center gap-1 shrink-0">
            <button class="opacity-0 group-hover:opacity-100 transition-opacity duration-150 p-1.5 rounded-md text-[#D97706] hover:bg-[#FFF9E5] hover:text-[#B45309]" type="button" data-edit-person="${p.id}" title="Editar profissional">
              <i data-lucide="pencil" class="w-3.5 h-3.5 pointer-events-none"></i>
            </button>
            <button class="opacity-0 group-hover:opacity-100 transition-opacity duration-150
                           p-1.5 rounded-md text-red-400 hover:bg-red-50 hover:text-red-600"
                    type="button"
                    data-remove-person="${p.id}"
                    title="Remover profissional">
              <i data-lucide="trash-2" class="w-3.5 h-3.5 pointer-events-none"></i>
            </button>
          </div>
        </div>`;
    }

    // ── Renderiza o empty state moderno ───────────────────────────────────────
    function emptyTeam(title, text) {
      return `<div class="flex flex-col items-center justify-center py-8 text-center">
        <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mb-3">
          <i data-lucide="users" class="w-5 h-5 text-gray-400"></i>
        </div>
        <p class="text-sm font-semibold text-[#171717] mb-1">${esc(title)}</p>
        <p class="text-xs text-[#737373]">${esc(text)}</p>
      </div>`;
    }

    // ── Atualiza as contagens e badges da tela de equipe ──────────────────────
    function renderTeamKpis(op, allPeople) {
      const peopleCount = document.getElementById('teamPeopleCount');
      if (peopleCount) peopleCount.textContent = allPeople.length;

      const selBadge = document.getElementById('teamSelectedBadge');
      if (selBadge && op) {
        const updateBadge = (count) => {
          selBadge.textContent = count === 0 ? '0 selecionados' : `${count} ${count === 1 ? 'selecionado' : 'selecionados'}`;
          selBadge.className = `px-2.5 py-0.5 rounded-full text-xs font-semibold border transition-all ${
            count > 0
              ? 'bg-[#E7F8F0] text-[#10B981] border-[#A7F3D0]'
              : 'bg-gray-100 text-[#737373] border-gray-200'
          }`;
        };
        
        if (op.status === 'draft') {
          updateBadge(op.team ? op.team.length : 0);
        } else if (op.id) {
          fetch(`/api/operacao/equipe?operacao_id=${op.id}`)
            .then(res => res.json())
            .then(data => {
              if (data.success) {
                updateBadge(data.count || 0);
              }
            })
            .catch(err => console.error('Erro ao buscar quantidade de equipe', err));
        } else {
          updateBadge(0);
        }
      }
    }


    // ── Renderiza o banner de status (substituindo .banner legado) ─────────────
    function renderTeamBanner(op) {
      const notice = el.teamOperationNotice;
      if (!notice) return;

      if (!op) {
        notice.innerHTML = '';
        return;
      }

      const bannerBase = 'flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-xl border';

      if (op.status === 'completed') {
        notice.innerHTML = `
          <div class="${bannerBase} bg-gray-50 border-gray-200">
            <div class="flex items-center gap-3">
              <div class="relative flex h-3 w-3 shrink-0">
                <span class="relative inline-flex rounded-full h-3 w-3 bg-gray-400"></span>
              </div>
              <div>
                <h3 class="font-semibold text-sm text-gray-700">Operação finalizada</h3>
                <p class="text-xs text-gray-500 mt-0.5">A lista de presença está fechada e disponível nos relatórios.</p>
              </div>
            </div>
            <button class="px-4 py-2 text-xs font-semibold text-white bg-[#B5120B] rounded-lg
                           hover:bg-[#9a0f09] transition-colors shadow-sm shrink-0" data-go="reports">
              Abrir relatórios
            </button>
          </div>`;
      } else if (op.status === 'draft') {
        notice.innerHTML = `
          <div class="${bannerBase} bg-blue-50 border-blue-200">
            <div class="flex items-center gap-3">
              <div class="relative flex h-3 w-3 shrink-0">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-[#173F69]"></span>
              </div>
              <div>
                <h3 class="font-semibold text-sm text-[#173F69]">Equipe em preparação</h3>
                <p class="text-xs text-blue-600 mt-0.5">Selecione os profissionais presentes e inicie a operação.</p>
              </div>
            </div>
          </div>`;
      } else if (op.status === 'production_open') {
        notice.innerHTML = `
          <div class="${bannerBase} bg-[#E7F8F0] border-[#A7F3D0]">
            <div class="flex items-center gap-3">
              <div class="relative flex h-3 w-3 shrink-0">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
              </div>
              <div>
                <h3 class="font-semibold text-sm text-emerald-900">Cozinha em operação</h3>
                <p class="text-xs text-emerald-700 mt-0.5">Você pode cadastrar e acionar novas pessoas até finalizar o dia.</p>
              </div>
            </div>
            <button class="px-4 py-2 text-xs font-semibold text-white bg-[#B5120B] rounded-lg
                           hover:bg-[#9a0f09] transition-colors shadow-sm shrink-0" data-go="production">
              Voltar à produção
            </button>
          </div>`;
      } else if (op.status === 'kitchen_closed') {
        notice.innerHTML = `
          <div class="${bannerBase} bg-amber-50 border-amber-200">
            <div class="flex items-center gap-3">
              <div class="relative flex h-3 w-3 shrink-0">
                <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
              </div>
              <div>
                <h3 class="font-semibold text-sm text-amber-900">Cozinha encerrada · despacho ativo</h3>
                <p class="text-xs text-amber-700 mt-0.5">Você pode cadastrar e acionar novas pessoas até finalizar completamente o dia.</p>
              </div>
            </div>
            <button class="px-4 py-2 text-xs font-semibold text-white bg-[#B5120B] rounded-lg
                           hover:bg-[#9a0f09] transition-colors shadow-sm shrink-0" data-go="dispatch">
              Voltar ao despacho
            </button>
          </div>`;
      }
    }

    // ── Filtro em tempo real da lista de profissionais ─────────────────────────
    function filteredPeople() {
      const q = norm(document.getElementById('teamPersonSearch')?.value || '');
      const sector = document.getElementById('teamSectorFilter')?.value || '';
      return [...state.people]
        .sort((a, b) => a.role.localeCompare(b.role, 'pt-BR') || a.name.localeCompare(b.name, 'pt-BR'))
        .filter(p => {
          const matchName   = !q || norm(p.name).includes(q);
          const matchSector = !sector || p.role === sector;
          return matchName && matchSector;
        });
    }

    // ── Renderiza apenas a lista de profissionais (com filtro aplicado) ─────────
    function renderPeopleList() {
      const op = currentOperation();
      const selected = new Set(op?.team.map(t => t.personId) || []);
      const usedIds  = new Set(op?.commands.map(c => c.assemblerId) || []);
      const people   = filteredPeople();

      if (!state.people.length) {
        el.peopleChecklist.innerHTML = emptyTeam('Nenhum profissional cadastrado', 'Use o formulário acima.');
      } else if (!people.length) {
        el.peopleChecklist.innerHTML = emptyTeam('Nenhum resultado', 'Tente outro nome ou setor.');
      } else {
        el.peopleChecklist.innerHTML = people.map(p => personItemHtml(p, selected, usedIds)).join('');
      }
      if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    // ── renderTeam() — orquestrador principal ──────────────────────────────────
    async function renderTeam() {
      try {
        const res = await fetch('/api/profissionais');
        if (res.ok) {
          const data = await res.json();
          if (data.success && data.profissionais) {
            state.people = data.profissionais;
          }
        }
      } catch (e) {
        console.error("Erro ao buscar profissionais", e);
      }
      renderHeader();
      const op = currentOperation();

      // Lista de profissionais
      renderPeopleList();

      // Lista de presença (coluna direita)
      renderCheckedTeam();

      // Botões de ação
      el.saveTeamBtn.disabled = op?.status === 'completed';
      el.startOperationBtn.disabled = !!op && op.status !== 'draft';

      // Atualiza label do botão + ícone dinamicamente
      const startLabel = op?.status === 'production_open' ? 'Operação em andamento'
        : op?.status === 'kitchen_closed' ? 'Cozinha encerrada'
        : op?.status === 'completed'      ? 'Dia finalizado'
        : 'Iniciar operação';
      const startIcon = op?.status === 'production_open' ? 'activity'
        : op?.status === 'completed'      ? 'check-circle'
        : 'play';
      el.startOperationBtn.innerHTML = `<i data-lucide="${startIcon}" class="w-4 h-4"></i> ${startLabel}`;

      // KPIs
      renderTeamKpis(op, state.people);

      // Banner de status
      renderTeamBanner(op);

      // Ícones Lucide
      if (typeof lucide !== 'undefined') lucide.createIcons();
    }
    function checkedTeam() { return [...document.querySelectorAll('[data-team-id]:checked')].map(i => { const p = getPerson(i.dataset.teamId); return { personId: p.id, name: p.name, role: p.role } }) }
    function renderCheckedTeam() {
      // Tenta pegar pelo DOM (checkboxes marcados). Caso o DOM ainda não tenha
      // sido renderizado (ex: ao recarregar a página), usa op.team salvo no estado.
      const fromDom = [...document.querySelectorAll('[data-team-id]:checked')]
        .map(i => { const p = getPerson(i.dataset.teamId); return p ? { personId: p.id, name: p.name, role: p.role } : null })
        .filter(Boolean);
      const op = currentOperation();
      const team = fromDom.length > 0 ? fromDom : (op?.team || []);
      el.dayTeamGroups.innerHTML = teamHtml(team, '');
      // Atualiza badge de selecionados e KPI de presentes
      renderTeamKpis(op, state.people);
      if (typeof lucide !== 'undefined') lucide.createIcons();
    }
    function saveTeam(show = true) {
      const op = ensureOperation();
      if (op.status === 'completed') return toast('A operação já foi finalizada. A equipe não pode mais ser alterada.', 'warn'), false;
      let selected = checkedTeam();
      const usedIds = new Set(op.commands.map(c => c.assemblerId)), kept = [];
      op.team.forEach(p => { if (usedIds.has(p.personId) && !selected.some(x => x.personId === p.personId)) kept.push(p); });
      if (kept.length) {
        selected = [...selected, ...kept];
        kept.forEach(p => { const box = document.querySelector(`[data-team-id="${p.personId}"]`); if (box) box.checked = true; });
        toast('Montadores com produção registrada foram mantidos na equipe.', 'warn');
      }
      op.team = selected;
      save();
      fetch('/api/equipe', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ operacao_id: op.id, team: selected }) })
        .then(res => {
          if (res.ok) {
            renderCheckedTeam();
          }
        });
      renderHeader(); renderCheckedTeam(); renderProduction(); renderDispatch(); renderDashboard();
      if (show && !kept.length) toast(op.status === 'draft' ? 'Equipe salva.' : 'Equipe atualizada durante a operação.');
      return true;
    }

    function renderProduction() { 
      renderHeader(); 
      const op = currentOperation(); 
      if (el.manageTeamBtn) el.manageTeamBtn.disabled = op?.status === 'completed';
      
      const activePage = document.querySelector('.page.active')?.id.replace('page-', '') || 'dashboard';
      const kitchenOpen = op && op.status === 'production_open';
if (!op || op.status === 'draft') { el.productionGate.innerHTML = gateCard("Operação não iniciada", "Cadastre a equipe do dia e inicie a operação para liberar a tela de produção.", "Ir para equipe", "team", "chef-hat"); el.productionContent.classList.add('hidden'); el.closeKitchenBtn.disabled = true; el.reopenKitchenBtn.classList.add('hidden'); if (el.manageTeamBtn) el.manageTeamBtn.classList.add('hidden'); el.closeKitchenBtn.classList.add('hidden'); return } if (op.status === 'completed') { el.productionGate.innerHTML = gateCard("Operação finalizada", "Consulte os relatórios do dia para ver o histórico e resultados.", "Abrir relatórios", "reports", "bar-chart-2"); el.productionContent.classList.add('hidden'); el.closeKitchenBtn.disabled = true; el.reopenKitchenBtn.classList.add('hidden'); if (el.manageTeamBtn) el.manageTeamBtn.classList.add('hidden'); el.closeKitchenBtn.classList.add('hidden'); return } el.productionGate.innerHTML = ''; el.productionContent.classList.remove('hidden'); if (el.manageTeamBtn) el.manageTeamBtn.classList.remove('hidden'); el.closeKitchenBtn.disabled = !kitchenOpen; el.closeKitchenBtn.classList.toggle('hidden', !kitchenOpen); el.reopenKitchenBtn.classList.toggle('hidden', op.status !== 'kitchen_closed'); el.openRegisterCommandBtn.disabled = !kitchenOpen; el.addCommandBtn.disabled = !kitchenOpen; el.assemblerId.disabled = !kitchenOpen; el.commandNumber.disabled = !kitchenOpen; el.pizzaQty.disabled = !kitchenOpen; el.commandNote.disabled = !kitchenOpen; el.initialOven.disabled = !kitchenOpen; renderProductionSub(op); renderAssemblerFilter(op); renderCommandSuggestions(op); renderProductionRecent(op); renderProductionTable(op); if (typeof renderSweetPendingPanel === 'function') renderSweetPendingPanel(); }
    function renderProductionSub(op) { const s = stats(op); const pills = [['list-ordered', 'Comandas', s.commands, ''], ['flame', 'No forno', s.oven, s.oven > 0 ? 'text-orange-600' : ''], ['truck', 'No despacho', s.dispatchPending + s.released, ''], ['alert-triangle', 'Erros', s.errors, s.errors > 0 ? 'text-red-600' : '']]; el.productionSubtotals.innerHTML = pills.map(([icon, label, val, cls]) => `<div class="flex items-center gap-2"><i data-lucide="${icon}" class="w-4 h-4 text-[#737373] shrink-0"></i><span class="text-xs text-[#737373]">${label}</span><span class="text-sm font-bold ${cls || 'text-[#171717]'}">${val}</span></div>`).join('<span class="text-[#E7E7E7]">/</span>'); setTimeout(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); }, 10) }

    function renderProductionRecent(op) { const recent = [...op.commands].sort((a, b) => b.createdAt.localeCompare(a.createdAt)).slice(0, 4), pending = op.commands.filter(c => c.status === 'cozinha' || c.status === 'forno' || c.status === 'pronto').length; if (el.updatePendingBadge) el.updatePendingBadge.textContent = pending; const statusChipClass = c => (c.status === 'forno' || c.status === 'pronto') ? 'bg-orange-100 text-orange-700' : c.status === 'cozinha' ? 'bg-blue-50 text-blue-700' : 'bg-green-50 text-green-700'; if (el.productionRecent) el.productionRecent.innerHTML = recent.length ? recent.map(c => `<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium ${statusChipClass(c)} whitespace-nowrap">#${String(c.number).padStart(3,'0')} · ${formatAssemblers(c)} · ${c.pizzas}🍕</span>`).join('') : `<span class="text-xs text-[#737373] italic">Aguardando primeira comanda…</span>` }
    function openRegisterCommand() { const op = currentOperation(); if (!op || op.status !== 'production_open') return toast('A cozinha não está aberta.', 'warn'); el.assemblerId.innerHTML = '<option value="">Selecione o montador</option>' + assemblers(op).map(p => `<option value="${p.personId}">${esc(p.name)}</option>`).join(''); resetRegistration(); renderCommandSuggestions(op); const body = el.registerCommandModal.querySelector('.register-modal-body'); if (body) body.scrollTop = 0; el.registerCommandModal.classList.add('show'); setTimeout(() => el.assemblerId.focus(), 120) }
    function openCommandUpdates() { const op = currentOperation(); if (!op || !op.commands.length) return toast('Ainda não existem comandas registradas.', 'warn'); el.registerCommandModal?.classList.remove('show'); el.prodStatus.value = op.commands.some(c => c.status === 'forno' || c.status === 'pronto') ? 'forno' : ''; renderProductionTable(op); setTimeout(() => el.commandHistoryPanel.scrollIntoView({ behavior: 'smooth', block: 'start' }), 80) }

    function assemblers(op) { return (op?.team || []).filter(p => p.role.includes('Montagem')) }
    function formatAssemblers(c) { return c.sweetAssemblerName && c.sweetAssemblerName !== c.assemblerName ? esc(c.assemblerName) + " / " + esc(c.sweetAssemblerName) : esc(c.assemblerName); }
    function assemblerInitials(name) { return String(name || '').trim().split(/\s+/).slice(0, 2).map(x => x.charAt(0).toUpperCase()).join('') || 'M' }
    function commandNumberSets(op) { const nums = new Set(op.commands.map(c => c.number)); if (!nums.size) return { missing: [], next: [1, 2, 3, 4, 5, 6, 7, 8] }; const arr = [...nums].sort((a, b) => a - b), min = arr[0], max = arr[arr.length - 1], missing = []; for (let n = Math.max(1, min); n <= max && missing.length < 10; n++)if (!nums.has(n)) missing.push(n); const next = []; for (let n = max + 1; n <= 1000 && next.length < 8; n++)if (!nums.has(n)) next.push(n); return { missing, next } }
    function renderCommandSuggestions(op) { const sets = commandNumberSets(op); el.commandSuggestions.innerHTML = `<h4>${sets.missing.length ? 'Comandas próximas que faltam' : 'Próximas comandas disponíveis'}</h4>${sets.missing.length ? `<div class="num-chips" style="margin-bottom:9px">${sets.missing.map(n => `<button class="num-chip missing" data-num="${n}">${n}</button>`).join('')}</div><h4>Sequência seguinte</h4>` : ''}<div class="num-chips">${sets.next.length ? sets.next.map(n => `<button class="num-chip" data-num="${n}">${n}</button>`).join('') : '<span class="chip">Limite de 1000 atingido</span>'}</div>` }
    function renderAssemblerFilter(op) { 
        const v = el.prodAssembler.value; 
        const html = '<option value="">Todos os montadores</option>' + assemblers(op).map(p => `<option value="${p.personId}">${esc(p.name)}</option>`).join('');
        el.prodAssembler.innerHTML = html; 
        el.prodAssembler.value = v;

        if (el.assemblerId) {
            const v2 = el.assemblerId.value;
            el.assemblerId.innerHTML = '<option value="">Selecione o montador</option>' + assemblers(op).map(p => `<option value="${p.personId}">${esc(p.name)}</option>`).join('');
            el.assemblerId.value = v2;
        }
    }
    function filteredCommands(op) { const q = norm(el.prodSearch.value), st = el.prodStatus.value, a = el.prodAssembler.value, priority = { forno: 0, pronto: 1, cozinha: 2, despacho: 3 }; return [...op.commands].filter(c => (!q || norm(`${c.number} ${c.assemblerName} ${c.sweetAssemblerName || ''} ${c.note || ''} ${c.error?.type || ''}`).includes(q)) && (!st || c.status === st || (st === 'forno' && c.status === 'pronto')) && (!a || c.assemblerId === a || c.sweetAssemblerId === a)).sort((x, y) => (priority[x.status] ?? 9) - (priority[y.status] ?? 9) || y.createdAt.localeCompare(x.createdAt)) }
    function statusText(c) { if (c.status === 'cozinha') return 'Na cozinha'; if (c.status === 'forno') return 'No forno'; if (c.status === 'pronto') return 'Aguardando atendimento'; return c.dispatch?.status === 'liberado' ? 'Liberada pelo despacho' : 'Saiu para o despacho' }
    function statusClass(c) { if (c.status === 'cozinha') return 'b-kitchen'; if (c.status === 'forno') return 'b-oven'; if (c.status === 'pronto') return 'b-dispatch'; return c.dispatch?.status === 'liberado' ? 'b-released' : 'b-dispatch' }
    function updateProductionViewMode() { if (productionViewMode === 'list') { if (el.viewListBtn) { el.viewListBtn.classList.add('bg-white', 'shadow-sm', 'text-[#B5120B]'); el.viewListBtn.classList.remove('text-[#9CA3AF]', 'hover:text-[#4B5563]', 'hover:bg-[#E5E7EB]'); } if (el.viewGridBtn) { el.viewGridBtn.classList.remove('bg-white', 'shadow-sm', 'text-[#B5120B]'); el.viewGridBtn.classList.add('text-[#9CA3AF]', 'hover:text-[#4B5563]', 'hover:bg-[#E5E7EB]'); } if (el.productionTableContainer) el.productionTableContainer.classList.remove('hidden'); if (el.productionGridContainer) el.productionGridContainer.classList.add('hidden'); if (el.productionMobileList) el.productionMobileList.classList.remove('hidden'); } else { if (el.viewGridBtn) { el.viewGridBtn.classList.add('bg-white', 'shadow-sm', 'text-[#B5120B]'); el.viewGridBtn.classList.remove('text-[#9CA3AF]', 'hover:text-[#4B5563]', 'hover:bg-[#E5E7EB]'); } if (el.viewListBtn) { el.viewListBtn.classList.remove('bg-white', 'shadow-sm', 'text-[#B5120B]'); el.viewListBtn.classList.add('text-[#9CA3AF]', 'hover:text-[#4B5563]', 'hover:bg-[#E5E7EB]'); } if (el.productionTableContainer) el.productionTableContainer.classList.add('hidden'); if (el.productionGridContainer) el.productionGridContainer.classList.remove('hidden'); if (el.productionMobileList) el.productionMobileList.classList.add('hidden'); } }
    function renderProductionTable(op) {
      if (typeof window.renderProductionTableOverride === 'function') return window.renderProductionTableOverride(op);
      const cs = filteredCommands(op);
      
      const actionHtml = c => {
        if (c.status === 'cozinha') return `<button class="px-3 py-1.5 text-xs font-semibold text-white bg-[#1F6FB2] rounded-md hover:bg-[#1a5e98] transition-colors shadow-sm" data-cmd-action="next" data-id="${c.id}">Enviar ao forno</button>`;
        if (c.status === 'forno') return `<button class="px-3 py-1.5 text-xs font-semibold text-white bg-[#1F6FB2] rounded-md hover:bg-[#1a5e98] transition-colors shadow-sm" data-cmd-action="next" data-id="${c.id}">Enviar para o atendimento</button><button class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors shadow-sm ml-2" data-cmd-action="back" data-id="${c.id}">Voltar</button>`;
        if (c.status === 'pronto') return `<button class="px-3 py-1.5 text-xs font-semibold text-white bg-[#B5120B] rounded-md hover:bg-[#910e08] transition-colors shadow-sm" data-dispatch-intake="${c.id}">Abrir no atendimento</button><button class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors shadow-sm ml-2" data-cmd-action="back" data-id="${c.id}">Voltar</button>`;
        return `<button class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors shadow-sm" data-cmd-action="back" data-id="${c.id}">Voltar ao atendimento</button>`
      };

      const tableStatusBadge = c => {
        if (c.status === 'cozinha') return 'bg-blue-50 text-[#1F6FB2] border-blue-100';
        if (c.status === 'forno') return 'bg-orange-50 text-orange-600 border-orange-100';
        if (c.status === 'pronto') return 'bg-purple-50 text-purple-600 border-purple-100';
        return c.dispatch?.status === 'liberado' ? 'bg-green-50 text-green-600 border-green-100' : 'bg-purple-50 text-purple-600 border-purple-100';
      };

      el.productionBody.innerHTML = cs.map(c => `
        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors group">
          <td class="py-3 pr-2"><span class="text-sm font-bold text-gray-900">#${String(c.number).padStart(3, '0')}</span></td>
          <td class="py-3 px-2"><span class="text-sm font-medium text-gray-700">${formatAssemblers(c)}</span></td>
          <td class="py-3 px-2 text-center"><span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-100 text-xs font-semibold text-gray-700">${c.pizzas}</span></td>
          <td class="py-3 px-2">
            <div class="flex flex-col gap-1 items-start">
              <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold border ${tableStatusBadge(c)}">${statusText(c)}</span>
              ${c.error?.active ? `<span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold border border-red-100 bg-red-50 text-[#B5120B]">${esc(c.error.type)}</span>` : ''}
            </div>
          </td>
          <td class="py-3 pl-2">
            <div class="flex items-center justify-end gap-2">
              ${actionHtml(c)}
              <button class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors shadow-sm" data-cmd-action="edit" data-id="${c.id}">Editar</button>
              <button class="px-3 py-1.5 text-xs font-medium text-[#B5120B] bg-white border border-red-100 rounded-md hover:bg-red-50 transition-colors shadow-sm" data-cmd-action="error" data-id="${c.id}">${c.error?.active ? 'Editar erro' : 'Erro'}</button>
            </div>
          </td>
        </tr>`).join('');

      const cardsHtml = cs.map(c => `<article class="mobile-command-card" style="margin:0;">
        <div class="mobile-command-top">
          <div>
            <div class="mobile-command-number">#${String(c.number).padStart(3, '0')}</div>
            <div class="mobile-command-meta">Registrada ${formatTime(c.createdAt)} · ${formatAssemblers(c)}</div>
          </div>
          <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold border ${tableStatusBadge(c)}">${statusText(c)}</span>
        </div>
        <div class="mobile-command-main">
          <div><strong>${formatAssemblers(c)}</strong><small>${c.error?.active ? `Erro: ${esc(c.error.type)}` : 'Sem erros'}</small></div>
          <div class="mobile-pizza-count">${c.pizzas}<small>pizza${Number(c.pizzas) === 1 ? '' : 's'}</small></div>
        </div>
        <div class="mobile-command-actions">
          ${actionHtml(c)}
          <button class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors shadow-sm" data-cmd-action="edit" data-id="${c.id}">Editar</button>
          <button class="px-3 py-1.5 text-xs font-medium text-[#B5120B] bg-white border border-red-100 rounded-md hover:bg-red-50 transition-colors shadow-sm" data-cmd-action="error" data-id="${c.id}">${c.error?.active ? 'Editar erro' : 'Erro'}</button>
        </div>
      </article>`).join('');

      if (el.productionMobileList) el.productionMobileList.innerHTML = cardsHtml;
      if (el.productionGridContainer) el.productionGridContainer.innerHTML = cardsHtml;
      if (el.productionEmpty) el.productionEmpty.classList.toggle('hidden', cs.length > 0);
      updateProductionViewMode();
    }
    function resetRegistration() { el.assemblerSearch.value = ''; el.assemblerId.value = ''; el.assemblerPickerValue.textContent = 'Toque para escolher'; el.assemblerPickerBtn.classList.remove('has-value'); el.commandNumber.value = ''; el.pizzaQty.value = 1; el.commandNote.value = ''; el.initialOven.checked = false; el.assemblerPickerModal.classList.remove('show'); setTimeout(() => el.assemblerPickerBtn.focus(), 100) }
    function addCommand() {
      const op = currentOperation();
      if (!op || op.status !== 'production_open') return toast('A cozinha não está aberta.', 'warn');
      const a = assemblers(op).find(p => p.personId === el.assemblerId.value);
      if (!a) return toast('Selecione o montador.', 'error');
      const n = Number(el.commandNumber.value), q = Number(el.pizzaQty.value);
      if (!Number.isInteger(n) || n < 1 || n > 1000) return toast('A comanda deve estar entre 1 e 1000.', 'error');
      if (!Number.isInteger(q) || q < 1 || q > 50) return toast('Informe uma quantidade válida de pizzas.', 'error');
      if (op.commands.some(c => c.number === n)) return toast(`A comanda ${n} já está cadastrada.`, 'warn');
      const now = new Date().toISOString(), status = el.initialOven.checked ? 'forno' : 'cozinha';
      const commandId = uid();
      const newCmd = { id: commandId, number: n, pizzas: q, assemblerId: a.personId, assemblerName: a.name, note: el.commandNote.value.trim(), status, createdAt: now, updatedAt: now, statusTimes: { cozinha: now, forno: status === 'forno' ? now : null, despacho: null }, error: { active: false, type: '', note: '', createdAt: null }, dispatch: { status: 'aguardando', beverage: false, change: false, changeAmount: '', ketchup: false, mayonnaise: false, note: '', checkedAt: null, releasedAt: null } };
      op.commands.push(newCmd);
      save();
      fetch('/api/comandas', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id: commandId, assembler_id: a.personId, assembler_name: a.name, number: n, pizzas: q, note: el.commandNote.value.trim() }) });
      resetRegistration(); renderProduction(); renderDashboard(); toast(`Comanda ${n} registrada com ${q} pizza${q > 1 ? 's' : ''}.`);
    }
    function move(c, dir) {
      const now = new Date().toISOString();
      if (dir === 'next') {
        if (c.status === 'cozinha') { c.status = 'forno'; c.statusTimes.forno = now; }
        else if (c.status === 'forno') { c.status = 'pronto'; c.statusTimes.pronto = now; }
      } else {
        if (c.status === 'despacho') { c.status = 'pronto'; c.dispatch.status = 'aguardando'; c.dispatch.checkedAt = null; c.dispatch.releasedAt = null; }
        else if (c.status === 'pronto') c.status = 'forno';
        else if (c.status === 'forno') c.status = 'cozinha';
      }
      c.updatedAt = now;
      save();
      fetch('/api/comandas/status', { method: 'PUT', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id: c.id, status: c.status }) });
      renderProduction(); renderDispatch(); renderDashboard();
    }
    function openEdit(c) { const op = currentOperation(); el.editId.value = c.id; el.editNumber.value = c.number; el.editQty.value = c.pizzas; el.editStatus.value = c.status; el.editNote.value = c.note || ''; el.editAssembler.innerHTML = assemblers(op).map(p => `<option value="${p.personId}" ${p.personId === c.assemblerId ? 'selected' : ''}>${esc(p.name)}</option>`).join(''); el.editModal.classList.add('show') }
    function openError(c) { el.errorId.value = c.id; el.errorType.value = c.error?.active ? c.error.type : ''; el.errorNote.value = c.error?.active ? c.error.note : ''; el.clearErrorBtn.disabled = !c.error?.active; el.errorModal.classList.add('show') }

    function renderDispatch() { renderHeader(); const op = currentOperation(); el.manageTeamDispatchBtn.disabled = op?.status === 'completed'; if (!op || op.status === 'draft' || op.status === 'production_open') { el.dispatchGate.innerHTML = gateCard(op?.status === 'production_open' ? 'Despacho disponível durante a operação' : 'Operação não iniciada', op?.status === 'production_open' ? 'As comandas enviadas pela cozinha já aparecem abaixo.' : 'Inicie a operação para usar o despacho.', op?.status === 'production_open' ? 'Abrir produção' : 'Ir para equipe', op?.status === 'production_open' ? 'production' : 'team', op?.status === 'production_open' ? 'chef-hat' : 'users'); if (!op || op.status === 'draft') { el.dispatchContent.classList.add('hidden'); el.finishDayBtn.disabled = true; el.manageTeamDispatchBtn.classList.add('hidden'); el.finishDayBtn.classList.add('hidden'); return } } else el.dispatchGate.innerHTML = ''; el.manageTeamDispatchBtn.classList.remove('hidden'); el.finishDayBtn.classList.remove('hidden'); if (op?.status === 'completed') { el.dispatchGate.innerHTML = gateCard("Operação finalizada", "Todos os registros estão disponíveis nos relatórios.", "Abrir relatórios", "reports", "bar-chart-2"); el.manageTeamDispatchBtn.classList.add('hidden'); el.finishDayBtn.classList.add('hidden'); } el.dispatchContent.classList.toggle('hidden', !op || op.status === 'draft'); if (!op) return; const all = op.commands.filter(c => c.status === 'despacho'), w = all.filter(c => c.dispatch.status === 'aguardando').length, ch = all.filter(c => c.dispatch.status === 'conferido').length, r = all.filter(c => c.dispatch.status === 'liberado').length; el.dispatchSubtotals.innerHTML = [['Recebidas', all.length], ['Aguardando', w], ['Conferidas', ch], ['Liberadas', r], ['Faltam zerar', pendingToFinish(op)]].map(([l, v]) => `<div class="subtotal-item"><small>${l}</small><strong>${v}</strong></div>`).join(''); const q = norm(el.dispatchSearch.value), f = el.dispatchFilter.value, cs = all.filter(c => (!q || norm(`${c.number} ${c.assemblerName}`).includes(q)) && (!f || c.dispatch.status === f)).sort((a, b) => ({ aguardando: 0, conferido: 1, liberado: 2 }[a.dispatch.status] - { aguardando: 0, conferido: 1, liberado: 2 }[b.dispatch.status]) || b.statusTimes.despacho.localeCompare(a.statusTimes.despacho)); el.dispatchGrid.innerHTML = cs.map(dispatchCard).join(''); el.dispatchEmpty.classList.toggle('hidden', cs.length > 0); el.finishDayBtn.disabled = op.status !== 'kitchen_closed' || pendingToFinish(op) > 0; el.finishDayBtn.textContent = op.status === 'completed' ? 'Dia finalizado' : pendingToFinish(op) > 0 ? `Faltam ${pendingToFinish(op)} pedidos` : 'Finalizar o dia' }
    function dispatchCard(c) { const d = c.dispatch, cls = d.status === 'liberado' ? 'b-released' : d.status === 'conferido' ? 'b-dispatch' : 'b-oven', label = d.status === 'liberado' ? 'Liberado' : d.status === 'conferido' ? 'Conferido' : 'Aguardando'; return `<article class="dispatch-card ${d.status === 'liberado' ? 'released' : ''}" data-dispatch-card="${c.id}"><div class="dispatch-head"><div><h4>Comanda #${String(c.number).padStart(3, '0')}</h4><p>${c.pizzas} pizza${c.pizzas > 1 ? 's' : ''} · montador ${formatAssemblers(c)} · ${formatTime(c.statusTimes.despacho)}</p></div><span class="badge ${cls}">${label}</span></div><div class="dispatch-options"><label class="option"><input type="checkbox" data-d-field="beverage" ${d.beverage ? 'checked' : ''}> Tem bebida</label><label class="option"><input type="checkbox" data-d-field="change" ${d.change ? 'checked' : ''}> Precisa de troco</label><label class="option"><input type="checkbox" data-d-field="ketchup" ${d.ketchup ? 'checked' : ''}> Ketchup</label><label class="option"><input type="checkbox" data-d-field="mayonnaise" ${d.mayonnaise ? 'checked' : ''}> Maionese</label></div><div class="inline"><div class="field"><label>Troco</label><input data-d-field="changeAmount" type="text" value="${esc(d.changeAmount || '')}" placeholder="Ex.: para R$ 100" ${d.change ? '' : 'disabled'}></div><div class="field"><label>Observação</label><input data-d-field="note" type="text" value="${esc(d.note || '')}" placeholder="Opcional"></div></div><div class="actions end mobile-stack"><button class="btn btn-soft btn-small" data-d-action="save" data-id="${c.id}">Salvar</button><button class="btn btn-orange btn-small" data-d-action="check" data-id="${c.id}">Conferido</button><button class="btn btn-green btn-small" data-d-action="release" data-id="${c.id}">Liberar pedido</button></div></article>` }
    function collectDispatch(card, c) { const get = n => { const x = card.querySelector(`[data-d-field="${n}"]`); return x?.type === 'checkbox' ? x.checked : (x?.value || '').trim() }; c.dispatch.beverage = get('beverage'); c.dispatch.change = get('change'); c.dispatch.changeAmount = c.dispatch.change ? get('changeAmount') : ''; c.dispatch.ketchup = get('ketchup'); c.dispatch.mayonnaise = get('mayonnaise'); c.dispatch.note = get('note'); c.updatedAt = new Date().toISOString() }

    function renderReports() { renderHeader(); const ops = [...state.operations].sort((a, b) => b.date.localeCompare(a.date)); if (!ops.length) { el.historyList.innerHTML = empty('Nenhuma operação', 'Crie a equipe do primeiro dia.'); el.reportOverview.innerHTML = empty('Sem dados', 'Os relatórios aparecerão aqui.'); el.reportCards.innerHTML = ''; return } if (!selectedReportOperationId || !getOperation(selectedReportOperationId)) selectedReportOperationId = ops[0].id; el.historyList.innerHTML = ops.map(o => { const s = stats(o); return `<div class="history-item ${o.id === selectedReportOperationId ? 'selected' : ''}" data-report-op="${o.id}"><h4>${formatDate(o.date)}</h4><p>${phaseLabel(o)} · ${o.team.length} pessoas</p><div class="metric-chips"><span class="metric-chip">${s.pizzas} pizzas</span><span class="metric-chip">${s.errors} erros</span><span class="metric-chip">${s.released} liberadas</span></div></div>` }).join(''); renderReportDetail(getOperation(selectedReportOperationId)) }
    function renderReportDetail(op) { const s = stats(op), errRate = s.pizzas ? ((s.errors / s.pizzas) * 100).toFixed(1) : '0.0'; el.reportOverview.innerHTML = `<div class="page-head" style="margin-bottom:12px"><div><h3 style="font-size:24px">${formatDate(op.date)}</h3><p>${phaseLabel(op)} · início ${formatTime(op.startedAt)} · cozinha ${formatTime(op.kitchenClosedAt)} · final ${formatTime(op.completedAt)}</p></div></div><div class="subtotal"><div class="subtotal-item"><small>Comandas</small><strong>${s.commands}</strong></div><div class="subtotal-item"><small>Pizzas</small><strong>${s.pizzas}</strong></div><div class="subtotal-item"><small>Liberadas</small><strong>${s.released}</strong></div><div class="subtotal-item"><small>Erros</small><strong>${s.errors}</strong></div><div class="subtotal-item"><small>Taxa de erro</small><strong>${errRate}%</strong></div></div><h3 style="margin-top:20px">Ranking da montagem</h3><div style="margin-top:10px">${rankHtml(op)}</div>`; const attendanceReady = op.team.length > 0, productionReady = ['kitchen_closed', 'completed'].includes(op.status); el.reportCards.innerHTML = `<article class="report-card"><h4>Lista de presença</h4><p>Relação da equipe por setor, com campo de assinatura. Disponível após salvar a equipe.</p><div class="actions mobile-stack"><button class="btn btn-soft" data-report-action="download-attendance" ${attendanceReady ? '' : 'disabled'}>Baixar relatório</button><button class="btn btn-primary" data-report-action="print-attendance" ${attendanceReady ? '' : 'disabled'}>Imprimir / Salvar PDF</button></div></article><article class="report-card"><h4>Resultado da montagem</h4><p>Ranking por pizzas, comandas, participação e erros. Disponível após encerrar a cozinha.</p><div class="actions mobile-stack"><button class="btn btn-soft" data-report-action="download-production" ${productionReady ? '' : 'disabled'}>Baixar relatório</button><button class="btn btn-primary" data-report-action="print-production" ${productionReady ? '' : 'disabled'}>Imprimir / Salvar PDF</button></div></article>` }
    function reportShell(title, op, body) {
      return `<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>${esc(title)}</title><style>body{font-family:Arial,sans-serif;color:#173f69;margin:0;padding:24px}header{display:flex;align-items:center;gap:14px;border-bottom:3px solid #f28c28;padding-bottom:12px;margin-bottom:18px}header img{width:68px;height:68px;object-fit:contain}h1{margin:0;font-size:24px}p{margin:4px 0;color:#566b7d}.meta{margin:14px 0;padding:10px 12px;background:#eaf5ff;border-radius:8px}.meta strong{color:#173f69}table{width:100%;border-collapse:collapse;margin-top:14px}th,td{border:1px solid #b9d7ee;padding:9px;text-align:left;font-size:12px}th{background:#eaf5ff}.signature{height:38px}.rank{font-weight:bold;text-align:center}.foot{margin-top:22px;font-size:11px;color:#6f7d8c}@media print{body{padding:0}.no-print{display:none}}@media(max-width:600px){body{padding:14px}table{font-size:11px}th,td{padding:7px}}</style></head><body><header><img src="data:image/png;base64,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"><div><h1>${esc(title)}</h1><p>Imperial Pizza · ${formatDate(op.date)}</p></div></header><div class="meta"><strong>Status:</strong> ${esc(phaseLabel(op))} &nbsp; | &nbsp; <strong>Início:</strong> ${formatTime(op.startedAt)} &nbsp; | &nbsp; <strong>Cozinha:</strong> ${formatTime(op.kitchenClosedAt)} &nbsp; | &nbsp; <strong>Final:</strong> ${formatTime(op.completedAt)}</div>${body}<div class="foot">Relatório gerado pelo Sistema de Controle de Comandas da Imperial Pizza.</div>


<\/body><\/html>`}
    function attendanceReport(op) { const rows = [...op.team].sort((a, b) => a.role.localeCompare(b.role, 'pt-BR') || a.name.localeCompare(b.name, 'pt-BR')).map((p, i) => `<tr><td>${i + 1}</td><td>${esc(p.name)}</td><td>${esc(p.role)}</td><td class="signature"></td></tr>`).join(''); return reportShell('Lista de Presença da Operação', op, `<p><strong>Total da equipe:</strong> ${op.team.length} ${op.team.length === 1 ? 'pessoa' : 'pessoas'}.</p><table><thead><tr><th style="width:45px">Nº</th><th>Nome</th><th>Setor</th><th>Assinatura</th></tr></thead><tbody>${rows}</tbody></table>`) }
    function productionReport(op) { const r = ranking(op), s = stats(op), rows = r.map((x, i) => `<tr><td class="rank">${i + 1}º</td><td>${esc(x.name)}</td><td>${x.pizzas}</td><td>${x.commands}</td><td>${s.pizzas ? ((x.pizzas / s.pizzas) * 100).toFixed(1) : '0.0'}%</td><td>${x.errors}</td></tr>`).join(''); const errors = op.commands.filter(c => c.error?.active).map(c => `<tr><td>#${String(c.number).padStart(3, '0')}</td><td>${formatAssemblers(c)}</td><td>${esc(c.error.type)}</td><td>${esc(c.error.note || '—')}</td></tr>`).join(''); return reportShell('Resultado da Montagem', op, `<p><strong>Total:</strong> ${s.commands} comandas · ${s.pizzas} pizzas · ${s.errors} ocorrências.</p><table><thead><tr><th>Rank</th><th>Montador</th><th>Pizzas</th><th>Comandas</th><th>Participação</th><th>Erros</th></tr></thead><tbody>${rows}</tbody></table><h2 style="margin-top:22px">Ocorrências</h2>${errors ? `<table><thead><tr><th>Comanda</th><th>Montador</th><th>Tipo</th><th>Descrição</th></tr></thead><tbody>${errors}</tbody></table>` : '<p>Nenhuma ocorrência registrada.</p>'}`) }
    function downloadHtml(name, html) { const blob = new Blob([html], { type: 'text/html;charset=utf-8' }), url = URL.createObjectURL(blob), a = document.createElement('a'); a.href = url; a.download = name; document.body.appendChild(a); a.click(); a.remove(); setTimeout(() => URL.revokeObjectURL(url), 1200) }
    function printHtml(html) { const w = window.open('', '_blank'); if (!w) return toast('Permita pop-ups para imprimir o relatório.', 'warn'); w.document.open(); w.document.write(html); w.document.close(); w.focus(); setTimeout(() => w.print(), 350) }
    function backup() { downloadHtml(`backup_imperial_${today()}.json`, JSON.stringify(state, null, 2)) }

    // navegação
    document.addEventListener('click', e => { 
      const n = e.target.closest('[data-page]'); 
      if (n) {
        showPage(n.dataset.page); 
        document.getElementById('sidebar').classList.add('-translate-x-full'); 
        document.getElementById('mobileSidebarOverlay').classList.add('hidden'); 
      }
      const g = e.target.closest('[data-go]'); 
      if (g) {
        showPage(g.dataset.go); 
        document.getElementById('sidebar').classList.add('-translate-x-full'); 
        document.getElementById('mobileSidebarOverlay').classList.add('hidden'); 
      }
      const toggle = e.target.closest('#toggleMobileMenuBtn'); 
      if (toggle) { 
        document.getElementById('sidebar').classList.remove('-translate-x-full'); 
        document.getElementById('mobileSidebarOverlay').classList.remove('hidden'); 
      }
      const overlay = e.target.closest('#mobileSidebarOverlay'); 
      if (overlay) { 
        document.getElementById('sidebar').classList.add('-translate-x-full'); 
        overlay.classList.add('hidden'); 
      }
    }); 
    if (el.globalStartDate) el.globalStartDate.addEventListener('change', () => { el.assemblerId.value = ''; el.assemblerSearch.value = ''; renderAll() });
    if (el.globalEndDate) el.globalEndDate.addEventListener('change', () => { el.assemblerId.value = ''; el.assemblerSearch.value = ''; renderAll() });
    // ── Equipe: Helpers para abrir/fechar modais de forma consistente ──
    function openModal(modalEl) {
      if (!modalEl) return;
      modalEl.classList.add('show');
    }
    function closeModal(modalEl) {
      if (!modalEl) return;
      modalEl.classList.remove('show');
    }

    // ── Equipe: Cadastro de profissional (POST) ──
    el.personForm.addEventListener('submit', async e => { 
      e.preventDefault(); 
      const name = proper(el.personName.value);
      const roleChecks = Array.from(el.personForm.querySelectorAll('input[name="personRole"]:checked')).map(cb => cb.value);
      if (name.length < 2 || roleChecks.length === 0) return toast('Preencha nome e selecione pelo menos um setor.', 'error'); 
      if (roleChecks.length > 3) return toast('Selecione no máximo 3 setores.', 'error');
      const role = roleChecks.join(', ');
      if (state.people.some(p => norm(p.name) === norm(name) && p.role === role)) return toast('Este profissional já está cadastrado com estes setores.', 'warn'); 
      const newPerson = { id: uid(), name, role, createdAt: new Date().toISOString() }; 
      try { 
        const res = await fetch('/api/profissionais', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(newPerson) }); 
        if (!res.ok) throw new Error('Erro ao salvar profissional no servidor'); 
        state.people.push(newPerson); save(); el.personForm.reset(); closeModal($('addPersonModal')); renderTeam(); toast('Profissional cadastrado.'); 
      } catch (err) { toast(err.message, 'error'); } 
    });
    
    // ── Equipe: Edição de profissional (PUT) — handler ÚNICO ──
    el.editPersonForm.addEventListener('submit', async e => {
      e.preventDefault();
      e.stopImmediatePropagation();

      const id = el.editPersonId.value;
      const name = proper(el.editPersonName.value);
      const roleChecks = Array.from(el.editPersonForm.querySelectorAll('input[name="editPersonRole"]:checked')).map(cb => cb.value);

      if (name.length < 2 || roleChecks.length === 0) return toast('Preencha nome e selecione pelo menos um setor.', 'error');
      if (roleChecks.length > 3) return toast('Selecione no máximo 3 setores.', 'error');
      
      const role = roleChecks.join(', ');

      if (state.people.some(p => p.id !== id && norm(p.name) === norm(name) && p.role === role)) {
        return toast('Este profissional já está cadastrado com estes setores.', 'warn');
      }

      try {
        const res = await fetch(`/api/profissionais/${id}`, {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ name, role })
        });
        const result = await res.json();

        if (result.success) {
          // Atualiza estado local
          const person = state.people.find(x => x.id === id);
          if (person) { person.name = name; person.role = role; }

          // Atualiza equipes e comandas em todas as operações
          state.operations.forEach(op => {
            const t = op.team.find(x => x.personId === id);
            if (t) { t.name = name; t.role = role; }
            op.commands.forEach(c => { if (c.assemblerId === id) c.assemblerName = name; });
          });

          save();
          closeModal(el.editPersonModal);
          renderTeam();
          renderProduction();
          renderDispatch();
          renderDashboard();
          toast('Profissional atualizado com sucesso!');
        } else {
          toast(result.error || 'Erro ao atualizar.', 'error');
        }
      } catch (err) {
        console.error(err);
        toast('Erro ao atualizar profissional: ' + err.message, 'error');
      }
    });

    // ── Equipe: Checkbox de presença ──
    el.peopleChecklist.addEventListener('change', e => { const box = e.target.closest('[data-team-id]'); if (box && !box.checked && box.dataset.usedInProduction === '1') { box.checked = true; toast('Este montador já possui produção registrada e deve permanecer na equipe.', 'warn') } renderCheckedTeam() });
    
    // ── Equipe: Clique nos botões de editar/excluir da lista de profissionais ──
    el.peopleChecklist.addEventListener('click', e => {
      // Botão EXCLUIR — abre modal de confirmação
      const removeBtn = e.target.closest('[data-remove-person]');
      if (removeBtn) {
        e.preventDefault();
        e.stopPropagation();
        const p = getPerson(removeBtn.dataset.removePerson);
        if (p) {
          const deleteModal = $('confirmDeleteModal');
          if (deleteModal) {
            $('deletePersonNameLabel').textContent = p.name;
            const confirmBtn = $('confirmDeletePersonBtn');
            if (confirmBtn) {
              confirmBtn.dataset.personId = p.id;
            }
            openModal(deleteModal);
          }
        }
        return;
      }

      // Botão EDITAR — abre modal de edição
      const editBtn = e.target.closest('[data-edit-person]');
      if (editBtn) {
        e.preventDefault();
        e.stopPropagation();
        
        const personId = editBtn.dataset.editPerson;
        const p = getPerson(personId);
        if (!p) {
          toast('Profissional não encontrado.', 'error');
          return;
        }

        // Popula os campos do formulário de edição
        el.editPersonId.value = p.id;
        el.editPersonName.value = p.name;
        
        const currentRoles = p.role ? p.role.split(',').map(r => r.trim()) : [];
        el.editPersonForm.querySelectorAll('input[name="editPersonRole"]').forEach(cb => {
          cb.checked = currentRoles.includes(cb.value);
        });

        // Exibe o modal
        openModal(el.editPersonModal);
      }
    });

    // ── Equipe: Confirmação de EXCLUSÃO (DELETE) ──
    $('confirmDeletePersonBtn').addEventListener('click', async e => {
      const personId = e.currentTarget.dataset.personId;
      if (!personId) return;

      try {
        const res = await fetch(`/api/profissionais/${personId}`, {
          method: 'DELETE'
        });
        const result = await res.json();

        if (result.success) {
          // Remove do estado local
          state.people = state.people.filter(p => p.id !== personId);

          // Remove de todas as equipes e comandas de operações
          state.operations.forEach(op => {
            op.team = op.team.filter(t => t.personId !== personId);
            op.commands = op.commands.filter(c => c.assemblerId !== personId);
          });

          save();
          closeModal($('confirmDeleteModal'));
          renderTeam();
          renderProduction();
          renderDispatch();
          renderDashboard();
          toast('Profissional excluído com sucesso!');
        } else {
          toast(result.error || 'Erro ao excluir.', 'error');
        }
      } catch (err) {
        console.error(err);
        toast('Erro ao excluir profissional: ' + err.message, 'error');
      }
    });

    // ── Equipe: Botões data-close para fechar modais ──
    document.addEventListener('click', e => {
      const closeBtn = e.target.closest('[data-close]');
      if (closeBtn) {
        const closeTarget = closeBtn.dataset.close;
        if (closeTarget === 'addPerson') closeModal($('addPersonModal'));
        if (closeTarget === 'editPerson') closeModal(el.editPersonModal);
        if (closeTarget === 'confirmDelete') closeModal($('confirmDeleteModal'));
        return;
      }
    });

    el.saveTeamBtn.addEventListener('click', () => saveTeam(true)); el.startOperationBtn.addEventListener('click', () => { const op = ensureOperation(); if (op.status !== 'draft') return toast('A operação já foi iniciada.', 'warn'); saveTeam(false); if (!op.team.length) return toast('Selecione a equipe do dia.', 'error'); if (!op.team.some(p => p.role.includes('Montagem'))) return toast('Inclua ao menos um montador.', 'error'); op.status = 'production_open'; op.startedAt = new Date().toISOString(); save(); fetch('/api/operacao/iniciar', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ operacao_id: op.id, startedAt: op.startedAt }) }); showPage('production'); toast('Operação iniciada.') });
    // Filtros da lista de profissionais (busca por nome + setor)
    document.addEventListener('input', e => { if (e.target.id === 'teamPersonSearch') renderPeopleList(); });
    document.addEventListener('change', e => { if (e.target.id === 'teamSectorFilter') renderPeopleList(); });
    // Eventos de modais de equipe (abrir e fechar clicando fora / backdrop)
    document.addEventListener('click', e => {
      // Abrir cadastro
      const btn = e.target.closest('#openAddPersonModalBtn');
      if (btn) {
        openModal($('addPersonModal'));
      }

      // Fechar modais ao clicar no backdrop (fora da caixa do modal)
      const addModal = $('addPersonModal');
      if (addModal && e.target === addModal) {
        closeModal(addModal);
      }

      const editModal = el.editPersonModal;
      if (editModal && e.target === editModal) {
        closeModal(editModal);
      }

      const deleteModal = $('confirmDeleteModal');
      if (deleteModal && e.target === deleteModal) {
        closeModal(deleteModal);
      }
    });

    el.openRegisterCommandBtn.addEventListener('click', openRegisterCommand);
    if (el.openUpdateCommandsBtn) el.openUpdateCommandsBtn.addEventListener('click', openCommandUpdates);
    if (el.manageTeamBtn) el.manageTeamBtn.addEventListener('click', () => showPage('team'));
    if (el.manageTeamDispatchBtn) el.manageTeamDispatchBtn.addEventListener('click', () => showPage('team'));
    document.querySelectorAll('[data-qty]').forEach(b => b.addEventListener('click', () => { let q = Number(el.pizzaQty.value) || 1; q = b.dataset.qty === 'plus' ? Math.min(50, q + 1) : Math.max(1, q - 1); el.pizzaQty.value = q }));
    el.commandSuggestions.addEventListener('click', e => { const b = e.target.closest('[data-num]'); if (b) { el.commandNumber.value = b.dataset.num; el.commandNumber.focus() } });
    el.addCommandBtn.addEventListener('click', addCommand);
    if (document.getElementById('clearCommandBtn')) {
      document.getElementById('clearCommandBtn').addEventListener('click', () => {
        if (typeof resetRegistration === 'function') resetRegistration();
        if (typeof renderCommandSuggestions === 'function') renderCommandSuggestions(currentOperation());
      });
    }
    el.commandNumber.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); addCommand() } });
    [el.prodSearch, el.prodStatus, el.prodAssembler].forEach(x => x.addEventListener(x.tagName === 'INPUT' ? 'input' : 'change', () => { window.currentProductionPage = 1; (window.renderProductionTableOverride || renderProductionTable)(currentOperation()); }));
    if (el.clearProdFilters) el.clearProdFilters.addEventListener('click', () => { el.prodSearch.value = ''; el.prodStatus.value = ''; el.prodAssembler.value = ''; window.currentProductionPage = 1; (window.renderProductionTableOverride || renderProductionTable)(currentOperation()) });
    function handleCommandAction(e) { const b = e.target.closest('[data-cmd-action]'); if (!b) return; const op = currentOperation(), c = getCommand(op, b.dataset.id); if (!c) return; if (b.dataset.cmdAction === 'next' || b.dataset.cmdAction === 'back') move(c, b.dataset.cmdAction); if (b.dataset.cmdAction === 'edit') openEdit(c); if (b.dataset.cmdAction === 'error') openError(c); if (b.dataset.cmdAction === 'sweet-asm') openSweetAssembler(c); }
    el.productionBody.addEventListener('click', handleCommandAction);
    el.productionMobileList.addEventListener('click', handleCommandAction);
    if (el.productionGridContainer) el.productionGridContainer.addEventListener('click', handleCommandAction);
    const sweetPanel = $('sweetPendingPanel');
    if (sweetPanel) sweetPanel.addEventListener('click', handleCommandAction);
    if (el.viewListBtn) el.viewListBtn.addEventListener('click', () => { productionViewMode = 'list'; updateProductionViewMode(); });
    if (el.viewGridBtn) el.viewGridBtn.addEventListener('click', () => { productionViewMode = 'grid'; updateProductionViewMode(); });
    let pendingCloseAction = null;

    function renderOvernightTeamList(op) {
      const container = $('overnightTeamList');
      if (!container) return;
      const team = op?.team || [];
      if (!team.length) {
        container.innerHTML = '<p class="text-xs text-gray-400 italic p-2 text-center">Nenhum profissional cadastrado na equipe de hoje.</p>';
        return;
      }
      container.innerHTML = team.map(p => `
        <label class="flex items-center justify-between p-2.5 rounded-xl border border-gray-200 bg-gray-50/50 hover:bg-indigo-50/40 hover:border-indigo-300 transition-all cursor-pointer select-none">
          <div class="flex items-center gap-3">
            <input type="checkbox" name="overnightTeamMember" value="${p.personId}" checked class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500 cursor-pointer">
            <div>
              <span class="text-sm font-bold text-gray-800 block leading-tight">${esc(p.name)}</span>
              <span class="text-[11px] text-gray-500 font-medium">${esc(p.role)}</span>
            </div>
          </div>
          <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 shrink-0">Online</span>
        </label>
      `).join('');
    }

    function openOvernightCloseModal(actionType) {
      const op = currentOperation();
      if (!op) return;
      pendingCloseAction = actionType;
      const titleEl = $('overnightModalTitle');
      if (titleEl) titleEl.textContent = actionType === 'kitchen' ? 'Encerrar Cozinha' : 'Finalizar Operação do Dia';
      document.querySelectorAll('input[name="overnightWork"]').forEach(r => r.checked = false);
      const teamSec = $('overnightTeamSection');
      if (teamSec) teamSec.classList.add('hidden');
      updateOvernightConfirmButton();
      if (el.overnightCloseModal) el.overnightCloseModal.classList.add('show');
    }

    function closeOvernightCloseModal() {
      pendingCloseAction = null;
      if (el.overnightCloseModal) el.overnightCloseModal.classList.remove('show');
    }

    function updateOvernightConfirmButton() {
      const selected = document.querySelector('input[name="overnightWork"]:checked');
      const btn = el.confirmOvernightCloseBtn;
      const teamSec = $('overnightTeamSection');
      if (!btn) return;
      if (selected) {
        btn.disabled = false;
        btn.classList.remove('bg-gray-300', 'cursor-not-allowed');
        if (selected.value === 'yes') {
          btn.className = 'px-5 py-2.5 text-[13px] font-bold text-white bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] transition-all rounded-xl cursor-pointer shadow-sm';
          btn.textContent = 'Confirmar (Com Madrugada)';
          if (teamSec) {
            teamSec.classList.remove('hidden');
            renderOvernightTeamList(currentOperation());
          }
        } else {
          btn.className = 'px-5 py-2.5 text-[13px] font-bold text-white bg-[#B5120B] hover:bg-[#9a0f09] active:scale-[0.98] transition-all rounded-xl cursor-pointer shadow-sm';
          btn.textContent = 'Confirmar (Sem Madrugada)';
          if (teamSec) teamSec.classList.add('hidden');
        }
      } else {
        btn.disabled = true;
        btn.className = 'px-5 py-2.5 text-[13px] font-bold text-white bg-gray-300 rounded-xl cursor-not-allowed transition-all shadow-xs';
        btn.textContent = 'Selecione uma opção';
        if (teamSec) teamSec.classList.add('hidden');
      }
    }

    if (el.closeKitchenBtn) {
      el.closeKitchenBtn.addEventListener('click', () => {
        const op = currentOperation();
        if (!op || op.status !== 'production_open') return;
        openOvernightCloseModal('kitchen');
      });
    }

    if (el.closeOvernightModalBtn) el.closeOvernightModalBtn.addEventListener('click', closeOvernightCloseModal);
    if (el.cancelOvernightBtn) el.cancelOvernightBtn.addEventListener('click', closeOvernightCloseModal);
    document.querySelectorAll('input[name="overnightWork"]').forEach(radio => radio.addEventListener('change', updateOvernightConfirmButton));

    const selectAllBtn = $('overnightSelectAllBtn');
    if (selectAllBtn) {
      selectAllBtn.addEventListener('click', () => {
        const checkboxes = document.querySelectorAll('input[name="overnightTeamMember"]');
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        checkboxes.forEach(cb => cb.checked = !allChecked);
        selectAllBtn.textContent = allChecked ? 'Marcar todos' : 'Desmarcar todos';
      });
    }

    if (el.confirmOvernightCloseBtn) {
      el.confirmOvernightCloseBtn.addEventListener('click', () => {
        const selected = document.querySelector('input[name="overnightWork"]:checked');
        if (!selected) return toast('Selecione se vai trabalhar de madrugada.', 'error');
        const op = currentOperation();
        if (!op) return;

        const isOvernight = (selected.value === 'yes');
        op.overnightWork = isOvernight;

        if (isOvernight) {
          const checkedMembers = Array.from(document.querySelectorAll('input[name="overnightTeamMember"]:checked')).map(cb => cb.value);
          if (!checkedMembers.length) return toast('Selecione ao menos um profissional para a madrugada.', 'warn');
          
          const overnightTeam = (op.team || []).filter(p => checkedMembers.includes(p.personId));
          op.overnightTeam = overnightTeam;
          op.team = overnightTeam; // Atualiza a equipe para apenas os que vão trabalhar na madrugada
        }

        if (pendingCloseAction === 'kitchen') {
          op.status = 'kitchen_closed';
          op.kitchenClosedAt = new Date().toISOString();
          save();
          if (el.registerCommandModal) el.registerCommandModal.classList.remove('show');
          closeOvernightCloseModal();
          renderAll();
          showPage('reports');
          toast(`Cozinha encerrada (${isOvernight ? `Com madrugada - ${op.overnightTeam?.length || 0} profissionais` : 'Sem madrugada'}).`);
        } else if (pendingCloseAction === 'day') {
          op.status = 'completed';
          op.completedAt = new Date().toISOString();
          save();
          selectedReportOperationId = op.id;
          closeOvernightCloseModal();
          renderAll();
          showPage('reports');
          toast(`Operação do dia finalizada (${isOvernight ? `Com madrugada - ${op.overnightTeam?.length || 0} profissionais` : 'Sem madrugada'}).`);
        }
      });
    }

    el.reopenKitchenBtn.addEventListener('click', () => { const op = currentOperation(); if (!op || op.status !== 'kitchen_closed') return; if (!confirm('Reabrir a cozinha para novos registros?')) return; op.status = 'production_open'; op.kitchenClosedAt = null; save(); renderAll(); toast('Cozinha reaberta.', 'warn') });

    // modais
    document.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', () => {
      const modalName = b.dataset.close + 'Modal';
      const modal = $(modalName);
      if (modal) {
        modal.classList.remove('show');
      }
    }));
    [el.registerCommandModal, el.assemblerPickerModal, el.editModal, el.errorModal, el.editPersonModal, el.confirmDeleteModal, el.sweetAssemblerModal].forEach(m => {
      if (m) {
        m.addEventListener('click', e => { 
          if (e.target === m) {
            m.classList.remove('show');
          }
        });
      }
    });

    const confirmDeletePersonBtn = $('confirmDeletePersonBtn');
    if (confirmDeletePersonBtn) {
      confirmDeletePersonBtn.addEventListener('click', async () => {
        const id = confirmDeletePersonBtn.dataset.personId;
        if (!id) return;
        
        try {
          confirmDeletePersonBtn.disabled = true;
          confirmDeletePersonBtn.textContent = 'Excluindo...';
          
          const res = await fetch(`/api/profissionais/${id}`, {
            method: 'DELETE'
          });
          
          const result = await res.json();
          if (result.success) {
            state.people = state.people.filter(x => x.id !== id);
            
            state.operations.forEach(op => {
              if (op.team) {
                op.team = op.team.filter(t => t.personId !== id);
              }
              if (op.commands) {
                op.commands.forEach(c => {
                  if (c.assemblerId === id) {
                    c.assemblerId = '';
                    c.assemblerName = '';
                  }
                });
              }
            });
            
            save();
            renderTeam();
            if (typeof renderProduction === 'function') renderProduction();
            if (typeof renderDispatch === 'function') renderDispatch();
            if (typeof renderDashboard === 'function') renderDashboard();
            
            const modal = $('confirmDeleteModal');
            if (modal) {
              modal.classList.remove('show');
              modal.style.display = 'none';
            }
            toast('Profissional e registros excluídos com sucesso!', 'ok');
          } else {
            toast(result.error || 'Erro ao excluir profissional.', 'error');
          }
        } catch (err) {
          toast('Erro de rede: ' + err.message, 'error');
        } finally {
          confirmDeletePersonBtn.disabled = false;
          confirmDeletePersonBtn.innerHTML = '<i data-lucide="trash-2" class="w-4 h-4"></i> Excluir Tudo e Confirmar';
          if (window.lucide) lucide.createIcons();
        }
      });
    } el.editForm.addEventListener('submit', e => { e.preventDefault(); const op = currentOperation(), c = getCommand(op, el.editId.value), n = Number(el.editNumber.value), q = Number(el.editQty.value); if (!c) return; if (!Number.isInteger(n) || n < 1 || n > 1000) return toast('Número inválido.', 'error'); if (op.commands.some(x => x.id !== c.id && x.number === n)) return toast('Essa comanda já existe.', 'warn'); if (!Number.isInteger(q) || q < 1 || q > 50) return toast('Quantidade inválida.', 'error'); const a = assemblers(op).find(p => p.personId === el.editAssembler.value); c.number = n; c.pizzas = q; c.assemblerId = a.personId; c.assemblerName = a.name; c.note = el.editNote.value.trim(); const st = el.editStatus.value, now = new Date().toISOString(); if (st !== c.status) { c.status = st; if (st === 'forno') c.statusTimes.forno ||= now; if (st === 'pronto') c.statusTimes.pronto ||= now; if (st === 'despacho') c.statusTimes.despacho ||= now; if (st !== 'despacho') { c.dispatch.status = 'aguardando'; c.dispatch.checkedAt = null; c.dispatch.releasedAt = null } } c.updatedAt = now; save(); el.editModal.classList.remove('show'); renderProduction(); renderDispatch(); renderDashboard(); if(typeof renderSweetPendingPanel==='function') renderSweetPendingPanel(); toast('Comanda atualizada.') }); el.deleteCommandBtn.addEventListener('click', () => { const op = currentOperation(), c = getCommand(op, el.editId.value); if (c && confirm(`Excluir a comanda ${c.number}?`)) { op.commands = op.commands.filter(x => x.id !== c.id); save(); el.editModal.classList.remove('show'); renderProduction(); renderDispatch(); renderDashboard(); toast('Comanda excluída.', 'warn') } }); el.errorForm.addEventListener('submit', e => { e.preventDefault(); const c = getCommand(currentOperation(), el.errorId.value); if (!c || !el.errorType.value) return toast('Selecione o tipo de erro.', 'error'); c.error = { active: true, type: el.errorType.value, note: el.errorNote.value.trim(), createdAt: new Date().toISOString() }; save(); el.errorModal.classList.remove('show'); renderProduction(); renderDashboard(); toast('Erro registrado.', 'warn') }); el.clearErrorBtn.addEventListener('click', () => { const c = getCommand(currentOperation(), el.errorId.value); if (c) { c.error = { active: false, type: '', note: '', createdAt: null }; save(); el.errorModal.classList.remove('show'); renderProduction(); renderDashboard(); toast('Sinalização retirada.') } });

    el.sweetAssemblerForm.addEventListener('submit', e => {
      e.preventDefault();
      const op = currentOperation();
      const c = getCommand(op, el.sweetAssemblerCmdId.value);
      if (!c) return;
      const sa = assemblers(op).find(p => p.personId === el.sweetAssemblerSelect.value);
      c.sweetAssemblerId = sa ? sa.personId : null;
      c.sweetAssemblerName = sa ? sa.name : null;
      c.updatedAt = new Date().toISOString();
      save();
      el.sweetAssemblerModal.classList.remove('show');
      renderProduction();
      renderDispatch();
      renderDashboard();
      if (typeof renderSweetPendingPanel === 'function') renderSweetPendingPanel();
      toast('Montador de doces registrado.');
    });
    // despacho
    [el.dispatchSearch, el.dispatchFilter].forEach(x => x.addEventListener(x.tagName === 'INPUT' ? 'input' : 'change', renderDispatch)); el.clearDispatchFilters.addEventListener('click', () => { el.dispatchSearch.value = ''; el.dispatchFilter.value = ''; renderDispatch() }); el.dispatchGrid.addEventListener('change', e => { if (e.target.dataset.dField === 'change') { const card = e.target.closest('[data-dispatch-card]'), amt = card.querySelector('[data-d-field="changeAmount"]'); amt.disabled = !e.target.checked; if (!e.target.checked) amt.value = '' } }); el.dispatchGrid.addEventListener('click', e => { const b = e.target.closest('[data-d-action]'); if (!b) return; const op = currentOperation(), c = getCommand(op, b.dataset.id), card = e.target.closest('[data-dispatch-card]'); if (!c || !card) return; collectDispatch(card, c); const now = new Date().toISOString(); if (b.dataset.dAction === 'check') { c.dispatch.status = 'conferido'; c.dispatch.checkedAt = now } if (b.dataset.dAction === 'release') { c.dispatch.status = 'liberado'; c.dispatch.checkedAt ||= now; c.dispatch.releasedAt = now } save(); renderDispatch(); renderDashboard(); toast(b.dataset.dAction === 'release' ? 'Pedido liberado.' : 'Conferência salva.') }); if (el.finishDayBtn) el.finishDayBtn.addEventListener('click', () => { const op = currentOperation(); if (!op || op.status !== 'kitchen_closed') return toast('Encerre a cozinha antes de finalizar o dia.', 'warn'); const p = pendingToFinish(op); if (p) return toast(`Ainda existem ${p} pedidos não finalizados.`, 'warn'); openOvernightCloseModal('day'); });
    // relatórios
    el.historyList.addEventListener('click', e => { const x = e.target.closest('[data-report-op]'); if (x) { selectedReportOperationId = x.dataset.reportOp; renderReports() } }); el.reportCards.addEventListener('click', e => { const b = e.target.closest('[data-report-action]'); if (!b) return; const op = getOperation(selectedReportOperationId); if (!op) return; const a = b.dataset.reportAction; if (a === 'download-attendance') downloadHtml(`lista_presenca_${op.date}.html`, attendanceReport(op)); if (a === 'print-attendance') printHtml(attendanceReport(op)); if (a === 'download-production') downloadHtml(`resultado_montagem_${op.date}.html`, productionReport(op)); if (a === 'print-production') printHtml(productionReport(op)) }); el.backupBtn.addEventListener('click', () => { const blob = new Blob([JSON.stringify(state, null, 2)], { type: 'application/json' }), url = URL.createObjectURL(blob), a = document.createElement('a'); a.href = url; a.download = `backup_imperial_${today()}.json`; document.body.appendChild(a); a.click(); a.remove(); setTimeout(() => URL.revokeObjectURL(url), 1000); toast('Backup baixado.') }); el.restoreBtn.addEventListener('click', () => el.restoreFile.click()); el.restoreFile.addEventListener('change', async e => { const f = e.target.files[0]; if (!f) return; try { const d = JSON.parse(await f.text()); if (!Array.isArray(d.people) || !Array.isArray(d.operations)) throw new Error(); if (!confirm('Restaurar este backup e substituir os dados atuais?')) return; state.people = d.people; state.operations = d.operations; save(); selectedReportOperationId = null; renderAll(); toast('Backup restaurado.') } catch (err) { toast('Backup inválido.', 'error') } finally { e.target.value = '' } });

    function renderAll() { 
      renderHeader(); 
      let page = 'dashboard';
      const path = window.location.pathname;
      if (path.includes('/equipe')) page = 'team';
      else if (path.includes('/cozinha')) page = 'production';
      else if (path.includes('/estoque')) page = 'stock';
      else if (path.includes('/comandas')) page = 'dispatch';
      else if (path.includes('/relatorios')) page = 'reports';
      else {
        const activeEl = document.querySelector('.page.active');
        if (activeEl) page = activeEl.id.replace('page-', '');
      }
      showPage(page);
    }
    if (el.globalDate) el.globalDate.value = today();
    if (el.globalStartDate) el.globalStartDate.value = today();
    if (el.globalEndDate) el.globalEndDate.value = today();
    renderAll();

    /* =========================================================
       V4.0 — CAMADA DE INTEGRAÇÃO
       ========================================================= */
    const V4_DEFAULTS = {
      commandMax: 1000,
      defaultPizzaQty: 1,
      volcanoEquivalent: 2,
      esfihaGroup: 5,
      esfihaEquivalent: 2,
      recentCommands: 6
    };
    let MASS_RECIPE = { flourKg: 10, sugarG: 500, saltG: 120, eggs: 10, oilMl: 900, waterL: 3, yeastG: 100 };
    const MASS_UNITS = { flourKg: 'kg', sugarG: 'g', saltG: 'g', eggs: 'un.', oilMl: 'ml', waterL: 'L', yeastG: 'g' };
    const MASS_LABELS = { flourKg: 'Farinha', sugarG: 'Açúcar', saltG: 'Sal', eggs: 'Ovos', oilMl: 'Óleo', waterL: 'Água', yeastG: 'Fermento' };

    Object.assign(el, {
      settingsBtn: $('settingsBtn'), settingsModal: $('settingsModal'), settingCommandMax: $('settingCommandMax'),
      settingDefaultPizzaQty: $('settingDefaultPizzaQty'), settingVolcanoEq: $('settingVolcanoEq'),
      settingEsfihaGroup: $('settingEsfihaGroup'), settingEsfihaEq: $('settingEsfihaEq'),
      settingRecentCommands: $('settingRecentCommands'), saveSettingsBtn: $('saveSettingsBtn'),
      volcanoCheck: $('volcanoCheck'), volcanoQty: $('volcanoQty'), esfihaCheck: $('esfihaCheck'), esfihaQty: $('esfihaQty'),
      sweetCheck: $('sweetCheck'), sweetQty: $('sweetQty'), equivalentPreview: $('equivalentPreview'),
      volcanoRuleText: $('volcanoRuleText'), esfihaRuleText: $('esfihaRuleText'),
      editVolcanoQty: $('editVolcanoQty'), editEsfihaQty: $('editEsfihaQty'), editSweetQty: $('editSweetQty'),

      massGate: $('massGate'), massContent: $('massContent'), massSubtotals: $('massSubtotals'), manageTeamMassBtn: $('manageTeamMassBtn'),
      openMassBatchBtn: $('openMassBatchBtn'), saveMassStockBtn: $('saveMassStockBtn'), massBalanceGrid: $('massBalanceGrid'),
      massStockStatus: $('massStockStatus'), massRecipeChip: $('massRecipeChip'), massBatchHistory: $('massBatchHistory'),
      stockFlourKg: $('stockFlourKg'), stockSugarG: $('stockSugarG'), stockSaltG: $('stockSaltG'), stockEggs: $('stockEggs'),
      stockOilMl: $('stockOilMl'), stockWaterL: $('stockWaterL'), stockYeastG: $('stockYeastG'),
      massBatchModal: $('massBatchModal'), massWorkerSelect: $('massWorkerSelect'), batchFlourKg: $('batchFlourKg'),
      batchSugarG: $('batchSugarG'), batchSaltG: $('batchSaltG'), batchEggs: $('batchEggs'), batchOilMl: $('batchOilMl'),
      batchWaterL: $('batchWaterL'), batchYeastG: $('batchYeastG'), batchNote: $('batchNote'), saveMassBatchBtn: $('saveMassBatchBtn'),

      openDispatchIntakeBtn: $('openDispatchIntakeBtn'), openDispatchQueueBtn: $('openDispatchQueueBtn'),
      ovenReadyBadge: $('ovenReadyBadge'), dispatchQueueBadge: $('dispatchQueueBadge'), dispatchQueuePanel: $('dispatchQueuePanel'),
      dispatchCommandModal: $('dispatchCommandModal'), dispatchModalTitle: $('dispatchModalTitle'),
      dispatchModalSubtitle: $('dispatchModalSubtitle'), dispatchCommandId: $('dispatchCommandId'),
      ovenCommandPickerBlock: $('ovenCommandPickerBlock'), ovenCommandSearch: $('ovenCommandSearch'), ovenCommandList: $('ovenCommandList'),
      dispatchSelectedSummary: $('dispatchSelectedSummary'), dispatchBeverage: $('dispatchBeverage'),
      dispatchChange: $('dispatchChange'), dispatchChangeAmount: $('dispatchChangeAmount'), dispatchKetchup: $('dispatchKetchup'),
      dispatchMayonnaise: $('dispatchMayonnaise'), dispatchNote: $('dispatchNote'), receiveDispatchBtn: $('receiveDispatchBtn'),
      checkDispatchBtn: $('checkDispatchBtn'), outForDeliveryBtn: $('outForDeliveryBtn')
    });

    function upgradeV4() {
      state.settings = { ...V4_DEFAULTS, ...(state.settings || {}) };
      (state.operations || []).forEach(op => {
        op.team = Array.isArray(op.team) ? op.team : [];
        op.commands = Array.isArray(op.commands) ? op.commands : [];
        op.mass = op.mass || { stock: { flourKg: 0, sugarG: 0, saltG: 0, eggs: 0, oilMl: 0, waterL: 0, yeastG: 0 }, stockSaved: false, batches: [] };
        op.mass.stock = { flourKg: 0, sugarG: 0, saltG: 0, eggs: 0, oilMl: 0, waterL: 0, yeastG: 0, ...(op.mass.stock || {}) };
        op.mass.batches = Array.isArray(op.mass.batches) ? op.mass.batches : [];
        op.commands.forEach(c => {
          c.pizzas = Number.isFinite(Number(c.pizzas)) ? Number(c.pizzas) : 1;
          c.special = { volcano: 0, esfiha: 0, sweet: 0, ...(c.special || {}) };
          c.dispatch = { status: 'aguardando', beverage: false, change: false, changeAmount: '', ketchup: false, mayonnaise: false, note: '', receivedAt: null, checkedAt: null, outForDeliveryAt: null, ...(c.dispatch || {}) };
          if (c.dispatch.status === 'liberado') {
            c.dispatch.status = 'entrega';
            c.dispatch.outForDeliveryAt = c.dispatch.outForDeliveryAt || c.dispatch.releasedAt || c.updatedAt || c.createdAt;
          }
          c.dispatch.receivedAt = c.dispatch.receivedAt || c.statusTimes?.despacho || null;
          c.updatedAt = c.updatedAt || c.createdAt;
        });
      });
    }

    function settings() { return state.settings || V4_DEFAULTS }
    function num(v) { const n = Number(v); return Number.isFinite(n) ? n : 0 }
    function fmt(v, dec = 1) { const n = num(v); return Number.isInteger(n) ? String(n) : n.toFixed(dec).replace('.', ',') }
    function commandEquivalent(c) {
      const s = settings(), sp = c?.special || {};
      const physical = Math.max(0, num(c?.pizzas));
      const volcano = Math.max(0, num(sp.volcano));
      const esfiha = Math.max(0, num(sp.esfiha));
      return physical + volcano * Math.max(0, num(s.volcanoEquivalent) - 1) + esfiha * (num(s.esfihaEquivalent) / Math.max(1, num(s.esfihaGroup)));
    }
    function commandTags(c) {
      const sp = c?.special || {}, tags = [];
      if (num(sp.volcano)) tags.push(`Vulcão ${fmt(sp.volcano, 0)}`);
      if (num(sp.esfiha)) tags.push(`${fmt(sp.esfiha, 0)} esfirras`);
      if (num(sp.sweet)) tags.push(`Doce ${fmt(sp.sweet, 0)}`);
      return tags;
    }
    function specialTagsHtml(c) {
      const tags = commandTags(c);
      return tags.length ? `<div class="special-tags">${tags.map(x => `<span class="special-tag">${esc(x)}</span>`).join('')}</div>` : '';
    }
    function ensureMass(op) {
      if (!op) return null;
      if (!op.mass) op.mass = { batches: [] };
      op.mass.stock = { flourKg: 0, sugarG: 0, saltG: 0, eggs: 0, oilMl: 0, waterL: 0, yeastG: 0, ...(op.mass.stock || {}) };
      op.mass.batches = Array.isArray(op.mass.batches) ? op.mass.batches : [];
      return op.mass;
    }
    function massConsumed(op) {
      const total = { flourKg: 0, sugarG: 0, saltG: 0, eggs: 0, oilMl: 0, waterL: 0, yeastG: 0 };
      (ensureMass(op)?.batches || []).forEach(b => Object.keys(total).forEach(k => total[k] += num(b.materials?.[k])));
      return total;
    }
    function massRemaining() {
      if (!state.globalMassStock) state.globalMassStock = { flourKg: 0, sugarG: 0, saltG: 0, eggs: 0, oilMl: 0, waterL: 0, yeastG: 0 };
      return state.globalMassStock;
    }
    function massWorkers(op) { return (op?.team || []).filter(p => p.role === 'Massa') }
    function dispatchDone(c) { return c?.status === 'despacho' && c?.dispatch?.status === 'entrega' }

    stats = function (op) {
      const cs = op?.commands || [];
      return {
        commands: cs.length,
        pizzas: cs.reduce((a, c) => a + commandEquivalent(c), 0),
        physicalPizzas: cs.reduce((a, c) => a + num(c.pizzas), 0),
        volcano: cs.reduce((a, c) => a + num(c.special?.volcano), 0),
        esfihas: cs.reduce((a, c) => a + num(c.special?.esfiha), 0),
        sweet: cs.reduce((a, c) => a + num(c.special?.sweet), 0),
        kitchen: cs.filter(c => c.status === 'cozinha').length,
        oven: cs.filter(c => c.status === 'forno' || c.status === 'pronto').length,
        dispatchPending: cs.filter(c => c.status === 'despacho' && c.dispatch?.status !== 'entrega').length,
        released: cs.filter(dispatchDone).length,
        errors: cs.filter(c => c.error?.active).length
      }
    };
    pendingToFinish = function (op) { return (op?.commands || []).filter(c => !dispatchDone(c)).length };

    ranking = function (op) {
      const m = {};
      (op?.team || []).filter(p => p.role.includes('Montagem')).forEach(p => m[p.personId] = { personId: p.personId, name: p.name, commands: 0, pizzas: 0, physical: 0, errors: 0 });
      (op?.commands || []).forEach(c => {
        m[c.assemblerId] ||= { personId: c.assemblerId, name: c.assemblerName, commands: 0, pizzas: 0, physical: 0, errors: 0 };
        m[c.assemblerId].commands++;
        m[c.assemblerId].pizzas += commandEquivalent(c);
        m[c.assemblerId].physical += num(c.pizzas);
        if (c.error?.active) m[c.assemblerId].errors++;
      });
      return Object.values(m).sort((a, b) => b.pizzas - a.pizzas || b.commands - a.commands || a.name.localeCompare(b.name, 'pt-BR'));
    };
    rankHtml = function (op) {
      const r = ranking(op), total = stats(op).pizzas;
      if (!r.length) return empty('Sem resultado de montagem', 'O ranking aparece após o registro das comandas.');
      return `<div class="rank-table">${r.map((x, i) => `<div class="rank-row">
    <div class="rank-pos">${i + 1}º</div>
    <div class="rank-name"><strong>${esc(x.name)}</strong><small>${total ? ((x.pizzas / total) * 100).toFixed(1) : '0.0'}% da produção equivalente</small></div>
    <div class="rank-metric"><strong>${fmt(x.pizzas)}</strong><small>equiv.</small></div>
    <div class="rank-metric"><strong>${x.commands}</strong><small>comandas</small></div>
    <div class="rank-metric"><strong>${x.errors}</strong><small>erros</small></div>
  </div>`).join('')}</div>`;
    };

    dashboardTop5Html = function (op) {
      const list = ranking(op).slice(0, 5), total = stats(op).pizzas, leader = list[0]?.pizzas || 1;
      if (!list.length) return empty('Top 5 ainda vazio', 'O ranking começa após o registro das pizzas.');
      return `<div class="top5-list">${list.map((x, i) => {
        const participation = total ? ((x.pizzas / total) * 100).toFixed(1) : '0.0';
        const progress = Math.max(8, Math.round((x.pizzas / leader) * 100));
        return `<div class="top5-row" style="--rank-progress:${progress}%">
      <div class="top5-position">${i === 0 ? '★' : `${i + 1}º`}</div>
      <div class="top5-name"><strong>${esc(x.name)}</strong><small>${participation}% · ${x.commands} comandas · ${x.errors} erros</small></div>
      <div class="top5-metrics"><strong>${fmt(x.pizzas)}</strong><small>equiv.</small></div>
    </div>`}).join('')}</div>`;
    };
    dashboardLiveHtml = function (op) {
      const recent = [...(op?.commands || [])].sort((a, b) => (b.updatedAt || b.createdAt).localeCompare(a.updatedAt || a.createdAt)).slice(0, 5);
      if (!recent.length) return `<div class="dashboard-live-empty">${empty('Sem movimento ainda', 'As últimas comandas aparecerão aqui em tempo real.')}</div>`;
      return `<div class="live-sample">${recent.map(c => `<div class="live-item">
    <span class="live-dot"></span>
    <div><strong>Comanda #${String(c.number).padStart(3, '0')} · ${formatAssemblers(c)}</strong><small>${statusText(c)} · ${formatTime(c.updatedAt || c.createdAt)}${specialTagsHtml(c)}</small></div>
    <span class="live-pizzas">${fmt(commandEquivalent(c))} equiv.</span>
  </div>`).join('')}</div>`;
    };

    statusText = function (c) {
      if (c.status === 'cozinha') return 'Na cozinha';
      if (c.status === 'forno') return 'No forno';
      if (c.status === 'pronto') return 'Aguardando atendimento';
      if (c.dispatch?.status === 'entrega') return 'Saiu para entrega';
      if (c.dispatch?.status === 'conferido') return 'Conferido no atendimento';
      return 'No atendimento / despacho';
    };
    statusClass = function (c) {
      if (c.status === 'cozinha') return 'b-kitchen';
      if (c.status === 'forno') return 'b-oven';
      if (c.status === 'pronto') return 'b-dispatch';
      if (c.dispatch?.status === 'entrega') return 'b-released';
      return 'b-dispatch';
    };

    const originalShowPage = showPage;
    showPage = function (name) {
      originalShowPage(name);
      if (name === 'mass') renderMass();
    };

    commandNumberSets = function (op) {
      const max = Math.max(1, num(settings().commandMax) || 1000), nums = new Set(op.commands.map(c => c.number));
      if (!nums.size) return { missing: [], next: Array.from({ length: Math.min(8, max) }, (_, i) => i + 1) };
      const arr = [...nums].sort((a, b) => a - b), min = arr[0], mx = arr[arr.length - 1], missing = [];
      for (let n = Math.max(1, min); n <= mx && missing.length < 10; n++)if (!nums.has(n)) missing.push(n);
      const next = []; for (let n = mx + 1; n <= max && next.length < 8; n++)if (!nums.has(n)) next.push(n);
      return { missing, next };
    };
    renderCommandSuggestions = function (op) {
      const sets = commandNumberSets(op), max = settings().commandMax;
      el.commandSuggestions.innerHTML = `<h4>${sets.missing.length ? 'Comandas próximas que faltam' : 'Próximas comandas disponíveis'}</h4>
  ${sets.missing.length ? `<div class="num-chips" style="margin-bottom:9px">${sets.missing.map(n => `<button class="num-chip missing" data-num="${n}">${n}</button>`).join('')}</div><h4>Sequência seguinte</h4>` : ''}
  <div class="num-chips">${sets.next.length ? sets.next.map(n => `<button class="num-chip" data-num="${n}">${n}</button>`).join('') : `<span class="chip">Limite de ${max} atingido</span>`}</div>`;
    };

    function registrationSpecial() {
      return {
        volcano: el.volcanoCheck.checked ? Math.max(0, num(el.volcanoQty.value)) : 0,
        esfiha: el.esfihaCheck.checked ? Math.max(0, num(el.esfihaQty.value)) : 0,
        sweet: el.sweetCheck.checked ? Math.max(0, num(el.sweetQty.value)) : 0
      };
    }
    function refreshEquivalentPreview() {
      const temp = { pizzas: Math.max(0, num(el.pizzaQty.value)), special: registrationSpecial() };
      el.equivalentPreview.textContent = `${fmt(commandEquivalent(temp))} equiv.`;
    }

    resetRegistration = function () {
      el.assemblerId.value = '';
      el.commandNumber.value = ''; el.pizzaQty.value = Math.max(0, num(settings().defaultPizzaQty)); el.commandNote.value = ''; el.initialOven.checked = false;
      el.volcanoCheck.checked = false; el.esfihaCheck.checked = false; el.sweetCheck.checked = false;
      el.volcanoQty.value = 1; el.esfihaQty.value = Math.max(1, num(settings().esfihaGroup)); el.sweetQty.value = 1;
      el.volcanoQty.disabled = true; el.esfihaQty.disabled = true; el.sweetQty.disabled = true;

      el.volcanoRuleText.textContent = `Cada unidade vale ${fmt(settings().volcanoEquivalent)} pizzas`;
      el.esfihaRuleText.textContent = `${settings().esfihaGroup} esfirras = ${fmt(settings().esfihaEquivalent)} pizzas`;
      el.commandNumber.max = settings().commandMax;
      refreshEquivalentPreview();
      setTimeout(() => el.assemblerId.focus(), 100);
    };

    renderSweetPendingPanel = function () {
      const panel = $('sweetPendingPanel'), list = $('sweetPendingList');
      if (!panel || !list) return;
      const op = currentOperation();
      const pending = op ? op.commands.filter(c => num(c.special?.sweet) > 0 && !c.sweetDelivered && !c.sweetAssemblerId) : [];
      if (!pending.length) { panel.classList.add('hidden'); return; }
      panel.classList.remove('hidden');
      list.innerHTML = pending.map(c => `
        <div class="flex items-center justify-between gap-3 bg-white border border-pink-100 rounded-lg px-3 py-2">
          <div class="flex items-center gap-2 min-w-0 overflow-hidden">
            <span class="text-[13px] font-bold text-gray-800 shrink-0">${String(c.number).padStart(3,'0')}</span>
            <span class="text-[12px] text-gray-500 truncate">${esc(c.sweetAssemblerName || c.assemblerName)}</span>
            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[11px] font-bold bg-pink-100 text-pink-700 shrink-0">${fmt(c.special.sweet,0)} doce${c.special.sweet>1?'s':''}</span>
          </div>
          <button type="button" data-cmd-action="sweet-asm" data-id="${c.id}"
            class="inline-flex items-center px-3 py-1.5 text-[12px] font-semibold text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors shrink-0">
            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5"/></svg>Registrar
          </button>
        </div>
      `).join('');
    };

    markSweetDelivered = function (commandId) {
      const op = currentOperation(); if (!op) return;
      const c = op.commands.find(x => x.id === commandId); if (!c) return;
      c.sweetDelivered = true; save();
      renderSweetPendingPanel();
      toast(`Comanda #${String(c.number).padStart(3,'0')} · doce marcada como entregue.`, 'success');
    };
    window.markSweetDelivered = function (id) { markSweetDelivered(id); };

    openRegisterCommand = function () {
      const op = currentOperation();
      if (!op || op.status !== 'production_open') return toast('A cozinha não está aberta.', 'warn');
      el.assemblerId.innerHTML = '<option value="">Selecione o montador</option>' + assemblers(op).map(p => `<option value="${p.personId}">${esc(p.name)}</option>`).join('');
      resetRegistration(); renderCommandSuggestions(op); renderSweetPendingPanel();
      const body = el.registerCommandModal.querySelector('.register-modal-body'); if (body) body.scrollTop = 0;
      el.registerCommandModal.classList.add('show'); setTimeout(() => el.assemblerId.focus(), 120);
    };



    addCommand = function () {
      const op = currentOperation();
      if (!op || op.status !== 'production_open') return toast('A cozinha não está aberta.', 'warn');
      const a = assemblers(op).find(p => p.personId === el.assemblerId.value);
      if (!a) return toast('Selecione o montador.', 'error');

      const n = Number(el.commandNumber.value), q = Math.max(0, num(el.pizzaQty.value)), sp = registrationSpecial(), max = settings().commandMax;
      if (!Number.isInteger(n) || n < 1 || n > max) return toast(`A comanda deve estar entre 1 e ${max}.`, 'error');
      if (!Number.isInteger(q) || q < 0 || q > 50) return toast('Informe uma quantidade válida de pizzas.', 'error');
      if (op.commands.some(c => c.number === n)) return toast(`A comanda ${n} já está cadastrada.`, 'warn');
      if (sp.volcano > q) return toast('A quantidade de vulcão não pode ser maior que as pizzas físicas.', 'error');
      if (sp.sweet > q) return toast('A quantidade de pizzas doces não pode ser maior que as pizzas físicas.', 'error');
      if (q === 0 && sp.esfiha === 0) return toast('Informe ao menos uma pizza ou esfirras.', 'error');

      const now = new Date().toISOString(), status = el.initialOven.checked ? 'forno' : 'cozinha';
      const cmd = {
        id: uid(), number: n, pizzas: q, special: sp, assemblerId: a.personId, assemblerName: a.name, note: el.commandNote.value.trim(), status,
        createdAt: now, updatedAt: now, statusTimes: { cozinha: now, forno: status === 'forno' ? now : null, despacho: null },
        error: { active: false, type: '', note: '', createdAt: null },
        sweetDelivered: false,
        dispatch: { status: 'aguardando', beverage: false, change: false, changeAmount: '', ketchup: false, mayonnaise: false, note: '', receivedAt: null, checkedAt: null, outForDeliveryAt: null }
      };
      op.commands.push(cmd); save();
      const equivalent = commandEquivalent(cmd);
      resetRegistration(); renderCommandSuggestions(op); renderProduction(); renderDashboard(); renderSweetPendingPanel();
      toast(`Comanda ${n} registrada · ${fmt(equivalent)} pizza${equivalent === 1 ? '' : 's'} equivalente${equivalent === 1 ? '' : 's'}.`);
    };

    renderProductionSub = function (op) {
      const s = stats(op);
      
      const kpis = [
        { label: 'Comandas', value: s.commands, bgStyle: 'bg-blue-50', textStyle: 'text-blue-500', hoverBg: 'group-hover:bg-blue-100', glow: 'bg-blue-100', icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>' },
        { label: 'Pizzas', value: fmt(s.pizzas), bgStyle: 'bg-emerald-50', textStyle: 'text-emerald-500', hoverBg: 'group-hover:bg-emerald-100', glow: 'bg-emerald-100', icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 11V4.234a2 2 0 012-1.986l.283.028a2 2 0 011.666 1.638L16.275 11H11zM11 13v6.766a2 2 0 002 1.986l.283-.028a2 2 0 001.666-1.638L16.275 13H11zM9 11H3.725a2 2 0 00-1.666 1.638l-.283.028A2 2 0 003.762 14.65L9 14.65V11zM9 13H3.725a2 2 0 01-1.666-1.638l-.283-.028A2 2 0 013.762 9.35L9 9.35V13z"></path></svg>' },
        { label: 'No forno', value: s.oven, bgStyle: 'bg-orange-50', textStyle: 'text-orange-500', hoverBg: 'group-hover:bg-orange-100', glow: 'bg-orange-100', icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>' },
        { label: 'Vulcão', value: fmt(s.volcano), bgStyle: 'bg-purple-50', textStyle: 'text-purple-500', hoverBg: 'group-hover:bg-purple-100', glow: 'bg-purple-100', icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>' },
        { label: 'Esfirras', value: fmt(s.esfihas, 0), bgStyle: 'bg-amber-50', textStyle: 'text-amber-500', hoverBg: 'group-hover:bg-amber-100', glow: 'bg-amber-100', icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>' },
        { label: 'Doces', value: fmt(s.sweet), bgStyle: 'bg-pink-50', textStyle: 'text-pink-500', hoverBg: 'group-hover:bg-pink-100', glow: 'bg-pink-100', icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>' },
        { label: 'Erros', value: s.errors, bgStyle: 'bg-red-50', textStyle: 'text-red-500', hoverBg: 'group-hover:bg-red-100', glow: 'bg-red-100', icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>' }
      ];

      el.productionSubtotals.innerHTML = kpis.map(k => `
        <div class="relative overflow-hidden group bg-white border border-[#E5E7EB] rounded-2xl p-4 sm:p-5 shadow-sm transition-all duration-300 hover:shadow-md flex flex-col justify-between min-h-[105px]">
          <div class="absolute -right-6 -top-6 w-24 h-24 ${k.glow} rounded-full blur-2xl opacity-40 group-hover:opacity-80 transition-opacity"></div>
          <div class="relative z-10 flex items-center justify-between mb-2">
            <span class="text-[13px] font-semibold text-[#6B7280] tracking-tight uppercase">${k.label}</span>
            <div class="w-8 h-8 flex items-center justify-center rounded-lg ${k.bgStyle} ${k.textStyle} transition-colors duration-300 ${k.hoverBg}">
              <div class="w-4 h-4 flex items-center justify-center">
                ${k.icon}
              </div>
            </div>
          </div>
          <div class="relative z-10 flex items-baseline">
            <span class="text-3xl leading-none font-extrabold text-[#111827] tracking-tight">${k.value}</span>
          </div>
        </div>
      `).join('');
    };

    renderProductionRecent = function (op) {
      const pending = op.commands.filter(c => c.status === 'cozinha' || c.status === 'forno' || c.status === 'pronto').length;
      if (el.updatePendingBadge) el.updatePendingBadge.textContent = pending;
      
      if (!el.productionRecent) return;
      const recent = [...op.commands].sort((a, b) => b.createdAt.localeCompare(a.createdAt)).slice(0, 4);
      
      const sassChip = c => {
        if (c.status === 'forno') return 'bg-[#FFF7ED] text-[#C2410C] border-[#FFEDD5]';
        if (c.status === 'pronto') return 'bg-purple-50 text-purple-700 border-purple-100';
        if (c.status === 'cozinha') return 'bg-[#EFF6FF] text-[#1D4ED8] border-[#DBEAFE]';
        return 'bg-[#F0FDF4] text-[#15803D] border-[#DCFCE7]';
      };

      el.productionRecent.innerHTML = recent.length ? recent.map(c => `
        <div class="flex items-center gap-2 px-2.5 py-1 rounded-[6px] border ${sassChip(c)} text-[12px] font-medium shadow-sm">
          <span class="font-semibold opacity-90">#${String(c.number).padStart(3,'0')}</span>
          <span class="opacity-30">|</span>
          <span class="truncate max-w-[80px]">${formatAssemblers(c)}</span>
          <span class="opacity-30">|</span>
          <span class="flex items-center gap-0.5">${fmt(commandEquivalent(c))} <span class="opacity-60 text-[10px]">🍕</span></span>
        </div>
      `).join('') : `<span class="text-[13px] text-[#9CA3AF]">Nenhum registro recente</span>`;
    };

    function prodFlowActions(c) {
      if (c.status === 'cozinha') return `<button class="inline-flex items-center px-4 py-2 text-[13px] font-bold text-white bg-orange-600 rounded-lg hover:bg-orange-700 transition-colors shadow-sm whitespace-nowrap" data-cmd-action="next" data-id="${c.id}"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 7 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>Enviar ao forno</button>`;
      if (c.status === 'forno') return `<button class="inline-flex items-center px-4 py-2 text-[13px] font-bold text-white bg-[#1F6FB2] rounded-lg hover:bg-[#1a5e98] transition-colors shadow-sm whitespace-nowrap" data-cmd-action="next" data-id="${c.id}"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>Enviar para o atendimento</button><button class="inline-flex items-center px-4 py-2 text-[13px] font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors shadow-sm whitespace-nowrap ml-2" data-cmd-action="back" data-id="${c.id}"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>Voltar</button>`;
      if (c.status === 'pronto') return `<button class="inline-flex items-center px-4 py-2 text-[13px] font-bold text-white bg-[#B5120B] rounded-lg hover:bg-[#910e08] transition-colors shadow-sm whitespace-nowrap" data-dispatch-intake="${c.id}"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>Abrir no atendimento</button><button class="inline-flex items-center px-4 py-2 text-[13px] font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors shadow-sm whitespace-nowrap ml-2" data-cmd-action="back" data-id="${c.id}"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>Voltar</button>`;
      if (c.dispatch?.status === 'entrega') return `<button class="inline-flex items-center px-4 py-2 text-[13px] font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors shadow-sm whitespace-nowrap" data-open-dispatch="${c.id}"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>Ver entrega</button>`;
      return `<button class="inline-flex items-center px-4 py-2 text-[13px] font-bold text-white bg-purple-600 rounded-lg hover:bg-purple-700 transition-colors shadow-sm whitespace-nowrap" data-open-dispatch="${c.id}"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>Abrir conferência</button><button class="inline-flex items-center px-4 py-2 text-[13px] font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors shadow-sm whitespace-nowrap ml-2" data-cmd-action="back" data-id="${c.id}"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>Voltar ao atendimento</button>`;
    }

    if (typeof window.currentProductionPage === 'undefined') window.currentProductionPage = 1;
    const PROD_PAGE_SIZE = 12;

    window.renderProductionTableOverride = renderProductionTable = function (op) {
      const allCs = filteredCommands(op);
      const totalPages = Math.max(1, Math.ceil(allCs.length / PROD_PAGE_SIZE));
      if (window.currentProductionPage > totalPages) window.currentProductionPage = totalPages;
      const pageStart = (window.currentProductionPage - 1) * PROD_PAGE_SIZE;
      const cs = allCs.slice(pageStart, pageStart + PROD_PAGE_SIZE);

      const tableStatusBadge = c => {
        if (c.status === 'cozinha') return 'bg-blue-50 text-[#1F6FB2] border-blue-100/60';
        if (c.status === 'forno') return 'bg-orange-50 text-orange-600 border-orange-100/60';
        if (c.status === 'pronto') return 'bg-purple-50 text-purple-600 border-purple-100/60';
        return c.dispatch?.status === 'liberado' ? 'bg-green-50 text-green-600 border-green-100/60' : 'bg-purple-50 text-purple-600 border-purple-100/60';
      };

      el.productionBody.innerHTML = cs.map(c => `
        <tr class="bg-white hover:bg-gray-50/80 transition-colors group border-b border-gray-100/60">
          <td class="py-4 pl-5 pr-3 w-full">
            <div class="flex items-center gap-5">
              
              <div class="flex flex-col items-center shrink-0 gap-1.5 min-w-[70px]">
                <div class="flex flex-col items-center justify-center w-14 h-14 bg-blue-50/50 border border-blue-100/50 rounded-[14px]">
                  <span class="text-[10px] font-bold text-[#1F6FB2]/60 uppercase tracking-wider leading-none mb-0.5">CMD</span>
                  <span class="text-[18px] font-black tracking-tighter text-[#1F6FB2] leading-none">${String(c.number).padStart(3, '0')}</span>
                </div>
                <span class="inline-flex items-center text-center px-1.5 py-0.5 rounded-[4px] text-[9px] font-extrabold tracking-wider uppercase whitespace-nowrap border ${tableStatusBadge(c)}">${statusText(c)}</span>
              </div>
              
              <div class="flex flex-col justify-center gap-1">
                <div class="flex items-center gap-2">
                  <span class="text-[15px] font-bold text-gray-900 tracking-tight leading-none">${formatAssemblers(c)}</span>
                  ${specialTagsHtml(c)}
                </div>
                <span class="text-[13px] font-medium text-gray-500 leading-none">
                  ${fmt(commandEquivalent(c))} pizza${Number(commandEquivalent(c)) === 1 ? '' : 's'}
                </span>
                ${c.error?.active ? `<div class="mt-0.5"><span class="inline-flex items-center px-2 py-0.5 rounded-[5px] text-[10px] font-extrabold tracking-widest uppercase border border-red-200 bg-red-50 text-[#B5120B]"><i data-lucide="alert-circle" class="w-[10px] h-[10px] mr-1"></i>Erro: ${esc(c.error.type)}</span></div>` : ''}
              </div>

            </div>
          </td>
          <td class="py-4 pr-5 pl-3 text-right align-middle">
            <div class="flex items-center justify-end gap-2">
              ${prodFlowActions(c)}
              <button class="p-2 text-amber-600 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100 hover:text-amber-700 transition-colors shadow-sm ml-1" data-cmd-action="edit" data-id="${c.id}" title="Editar">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
              </button>
              <button class="p-2 ${c.error?.active ? 'text-white bg-[#B5120B] border-[#B5120B] hover:bg-red-800' : 'text-[#B5120B] bg-red-50 border-red-100 hover:bg-red-100'} border rounded-lg transition-colors shadow-sm" data-cmd-action="error" data-id="${c.id}" title="${c.error?.active ? 'Editar erro' : 'Reportar erro'}">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
              </button>
            </div>
          </td>
        </tr>`).join('');

      const cardsHtml = cs.map(c => `<article class="bg-white rounded-[16px] border border-gray-100/60 shadow-sm hover:shadow-md transition-all flex flex-col p-5 gap-4">
        <!-- Header -->
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="flex flex-col items-center justify-center w-12 h-12 bg-blue-50/50 border border-blue-100/50 rounded-[12px]">
              <span class="text-[9px] font-bold text-[#1F6FB2]/60 uppercase tracking-wider leading-none mb-0.5">CMD</span>
              <span class="text-[16px] font-black tracking-tighter text-[#1F6FB2] leading-none">${String(c.number).padStart(3, '0')}</span>
            </div>
            <div class="flex flex-col">
              <span class="text-[14px] font-bold text-gray-900 leading-none">${formatAssemblers(c)}</span>
              <span class="text-[11px] font-medium text-gray-400 mt-1">${formatTime(c.createdAt)}</span>
            </div>
          </div>
          <span class="inline-flex items-center text-center px-2 py-1 rounded-[6px] text-[10px] font-extrabold tracking-wider uppercase whitespace-nowrap border ${tableStatusBadge(c)}">${statusText(c)}</span>
        </div>

        <!-- Body -->
        <div class="flex items-center justify-between py-3 border-y border-gray-50">
          <div class="flex flex-col">
            <span class="text-[13px] font-medium text-gray-500">Pizzas equivalentes</span>
            <div class="flex items-center gap-2 mt-1">
              <span class="text-[18px] font-black text-gray-900 leading-none">${fmt(commandEquivalent(c))}</span>
              ${specialTagsHtml(c)}
            </div>
          </div>
          ${c.error?.active ? `<div class="flex items-center px-2.5 py-1 rounded-[6px] border border-red-200 bg-red-50 text-[#B5120B]"><svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg><span class="text-[11px] font-bold uppercase tracking-wide">Erro: ${esc(c.error.type)}</span></div>` : `<span class="text-[11px] font-medium text-gray-400">Sem erros</span>`}
        </div>

        <!-- Footer Actions -->
        <div class="flex flex-wrap items-center gap-2 mt-auto">
          ${prodFlowActions(c).replace(/px-4 py-2/g, 'px-3 py-1.5').replace(/ml-2/g, '')}
          <button class="inline-flex items-center px-3 py-1.5 text-[13px] font-semibold text-amber-600 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100 hover:text-amber-700 transition-colors shadow-sm whitespace-nowrap" data-cmd-action="edit" data-id="${c.id}" title="Editar">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>Editar
          </button>
          <button class="inline-flex items-center px-3 py-1.5 text-[13px] font-semibold ${c.error?.active ? 'text-white bg-[#B5120B] border-[#B5120B] hover:bg-red-800' : 'text-[#B5120B] bg-red-50 border-red-100 hover:bg-red-100'} rounded-lg transition-colors shadow-sm whitespace-nowrap border" data-cmd-action="error" data-id="${c.id}" title="${c.error?.active ? 'Editar erro' : 'Reportar erro'}">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>${c.error?.active ? 'Editar erro' : 'Erro'}
          </button>
        </div>
      </article>`).join('');

      if (el.productionMobileList) el.productionMobileList.innerHTML = cardsHtml;
      if (el.productionGridContainer) el.productionGridContainer.innerHTML = cardsHtml;
      if (el.productionEmpty) el.productionEmpty.classList.toggle('hidden', allCs.length > 0);
      updateProductionViewMode();

      // --- Pagination Controls ---
      // totalPages already declared at top of function — do NOT redeclare with const
      const paginationEl = document.getElementById('prodPagination');
      if (!paginationEl) return;

      if (allCs.length === 0) { paginationEl.innerHTML = ''; return; }

      const cur = window.currentProductionPage;
      const pageBtn = (n, label, disabled = false, active = false) =>
        `<button data-prod-page="${n}" class="prod-page-btn min-w-[34px] h-[34px] px-2.5 text-[13px] font-semibold rounded-lg border transition-colors ${
          active
            ? 'bg-[#1F6FB2] text-white border-[#1F6FB2] shadow-sm'
            : disabled
              ? 'text-gray-300 border-gray-100 bg-white cursor-not-allowed'
              : 'text-gray-600 border-gray-200 bg-white hover:bg-blue-50 hover:border-[#1F6FB2] hover:text-[#1F6FB2]'
        }" ${disabled ? 'disabled' : ''}>${label}</button>`;

      let pages = '';
      pages += pageBtn(cur - 1, '&#8249;', cur === 1);
      for (let i = 1; i <= totalPages; i++) {
        if (totalPages <= 7 || i === 1 || i === totalPages || (i >= cur - 1 && i <= cur + 1)) {
          pages += pageBtn(i, i, false, i === cur);
        } else if (i === cur - 2 || i === cur + 2) {
          pages += `<span class="text-gray-400 text-[13px] px-0.5">…</span>`;
        }
      }
      pages += pageBtn(cur + 1, '&#8250;', cur === totalPages);

      paginationEl.innerHTML = `
        <div class="flex items-center justify-between px-5 py-3 border-t border-gray-100 bg-white rounded-b-xl">
          <span class="text-[12px] text-gray-400 font-medium">
            Mostrando ${pageStart + 1}–${Math.min(pageStart + PROD_PAGE_SIZE, allCs.length)} de ${allCs.length} comanda${allCs.length !== 1 ? 's' : ''}
          </span>
          <div class="flex items-center gap-1">${pages}</div>
        </div>`;

      paginationEl.querySelectorAll('.prod-page-btn:not([disabled])').forEach(btn => {
        btn.addEventListener('click', () => {
          window.currentProductionPage = Number(btn.dataset.prodPage);
          (window.renderProductionTableOverride || renderProductionTable)(op);
        });
      });
    };


    move = function (c, dir) {
      const now = new Date().toISOString();
      if (dir === 'next') {
        if (c.status === 'cozinha') { c.status = 'forno'; c.statusTimes.forno = now; }
        else if (c.status === 'forno') { c.status = 'pronto'; c.statusTimes.pronto = now; }
      } else if (dir === 'back') {
        if (c.status === 'despacho') {
          c.status = 'pronto'; c.dispatch.status = 'aguardando'; c.dispatch.receivedAt = null; c.dispatch.checkedAt = null; c.dispatch.outForDeliveryAt = null; c.statusTimes.despacho = null;
        } else if (c.status === 'pronto') {
          c.status = 'forno';
        } else if (c.status === 'forno') {
          c.status = 'cozinha';
        }
      }
      c.updatedAt = now; save(); renderProduction(); renderDispatch(); renderDashboard();
    };

    openEdit = function (c) {
      const op = currentOperation();
      el.editId.value = c.id; el.editNumber.value = c.number; el.editQty.value = num(c.pizzas);
      el.editVolcanoQty.value = num(c.special?.volcano); el.editEsfihaQty.value = num(c.special?.esfiha); el.editSweetQty.value = num(c.special?.sweet);
      el.editStatus.value = c.status; el.editNote.value = c.note || '';
      el.editNumber.max = settings().commandMax;
      el.editAssembler.innerHTML = assemblers(op).map(p => `<option value="${p.personId}" ${p.personId === c.assemblerId ? 'selected' : ''}>${esc(p.name)}</option>`).join('');
      
      const sweetAsm = $('editSweetAssembler');
      const sweetFld = $('editSweetAssemblerField');
      if (sweetAsm && sweetFld) {
          sweetAsm.innerHTML = '<option value="">(Mesmo da salgada)</option>' + assemblers(op).map(p => `<option value="${p.personId}" ${p.personId === c.sweetAssemblerId ? 'selected' : ''}>${esc(p.name)}</option>`).join('');
          const toggle = () => { sweetFld.style.display = num(el.editSweetQty.value) > 0 ? 'block' : 'none'; };
          el.editSweetQty.addEventListener('input', toggle);
          toggle();
      }

      el.editModal.classList.add('show');
    };

    openSweetAssembler = function (c) {
      const op = currentOperation();
      el.sweetAssemblerCmdId.value = c.id;
      el.sweetAssemblerCmdNumber.textContent = String(c.number).padStart(3, '0');
      el.sweetAssemblerCmdQty.textContent = num(c.pizzas);
      el.sweetAssemblerCmdSweetQty.textContent = num(c.special?.sweet);
      el.sweetAssemblerCmdMainName.textContent = c.assemblerName || '-';
      el.sweetAssemblerSelect.innerHTML = '<option value="">Selecione o montador</option>' + assemblers(op).map(p => `<option value="${p.personId}" ${p.personId === c.sweetAssemblerId ? 'selected' : ''}>${esc(p.name)}</option>`).join('');
      el.sweetAssemblerModal.classList.add('show');
    };

    /* ---------- CONFIGURAÇÕES ---------- */
    function openSettings() {
      const s = settings();
      el.settingCommandMax.value = s.commandMax; el.settingDefaultPizzaQty.value = s.defaultPizzaQty;
      el.settingVolcanoEq.value = s.volcanoEquivalent; el.settingEsfihaGroup.value = s.esfihaGroup;
      el.settingEsfihaEq.value = s.esfihaEquivalent; el.settingRecentCommands.value = s.recentCommands;
      el.settingsModal.classList.add('show');
    }
    function saveSettings() {
      const commandMax = Math.round(num(el.settingCommandMax.value)), defaultPizzaQty = Math.round(num(el.settingDefaultPizzaQty.value)),
        volcanoEquivalent = num(el.settingVolcanoEq.value), esfihaGroup = Math.round(num(el.settingEsfihaGroup.value)),
        esfihaEquivalent = num(el.settingEsfihaEq.value), recentCommands = Math.round(num(el.settingRecentCommands.value));
      if (commandMax < 100 || commandMax > 5000) return toast('A numeração máxima deve ficar entre 100 e 5000.', 'error');
      if (defaultPizzaQty < 0 || defaultPizzaQty > 20 || volcanoEquivalent < 1 || esfihaGroup < 1 || esfihaEquivalent <= 0) return toast('Revise as regras informadas.', 'error');
      state.settings = { commandMax, defaultPizzaQty, volcanoEquivalent, esfihaGroup, esfihaEquivalent, recentCommands: Math.min(15, Math.max(3, recentCommands || 6)) };
      save(); el.settingsModal.classList.remove('show'); renderAll(); toast('Configurações atualizadas.');
    }

    /* ---------- MASSAS ---------- */
    function massInputObject(prefix) {
      return {
        flourKg: num(el[prefix + 'FlourKg'].value), sugarG: num(el[prefix + 'SugarG'].value), saltG: num(el[prefix + 'SaltG'].value),
        eggs: num(el[prefix + 'Eggs'].value), oilMl: num(el[prefix + 'OilMl'].value), waterL: num(el[prefix + 'WaterL'].value), yeastG: num(el[prefix + 'YeastG'].value)
      };
    }
    function setMassInputs(prefix, obj) {
      el[prefix + 'FlourKg'].value = num(obj.flourKg); el[prefix + 'SugarG'].value = num(obj.sugarG); el[prefix + 'SaltG'].value = num(obj.saltG);
      el[prefix + 'Eggs'].value = num(obj.eggs); el[prefix + 'OilMl'].value = num(obj.oilMl); el[prefix + 'WaterL'].value = num(obj.waterL); el[prefix + 'YeastG'].value = num(obj.yeastG);
    }
    function massMaterialLine(obj) {
      return Object.keys(MASS_RECIPE).map(k => `${MASS_LABELS[k]} ${fmt(obj[k])} ${MASS_UNITS[k]}`).join(' · ');
    }
    async function renderMass() {
      renderHeader(); const op = currentOperation();
      if (!op || op.status === 'draft') {
        el.massGate.innerHTML = gateCard("Operação não iniciada", "Inicie a operação e selecione os masseiros da equipe do dia.", "Abrir equipe", "team", "users");
        el.massContent.classList.add('hidden'); return;
      }
      el.massGate.innerHTML = ''; el.massContent.classList.remove('hidden');

      try {
        const [kpiRes, stockRes, historyRes, recipeRes] = await Promise.all([
            fetch('/api/mass/kpis').then(r => r.json()),
            fetch('/api/mass/stock').then(r => r.json()),
            fetch('/api/mass/history').then(r => r.json()),
            fetch('/api/mass/recipe').then(r => r.json())
        ]);
        
        if (!kpiRes.success || !historyRes.success) {
             toast('Erro ao carregar dados de massa.', 'error');
             return;
        }

        const kpisData = kpiRes.data;
        const stockData = stockRes.data || { inicial: { flour_kg:0, sugar_g:0, salt_g:0, eggs:0, oil_ml:0, water_l:0, yeast_g:0 }, atual: { flour_kg:0, sugar_g:0, salt_g:0, eggs:0, oil_ml:0, water_l:0, yeast_g:0 } };
        const historyData = historyRes.data || [];
        const workers = massWorkers(op);
        
        if(recipeRes && recipeRes.success) {
             const r = recipeRes.data;
             MASS_RECIPE = { flourKg: r.flour_kg, sugarG: r.sugar_g, saltG: r.salt_g, eggs: r.eggs, oilMl: r.oil_ml, waterL: r.water_l, yeastG: r.yeast_g };
        }

        el.stockFlourKg.value = num(stockData.inicial.flour_kg); 
        el.stockSugarG.value = num(stockData.inicial.sugar_g); 
        el.stockSaltG.value = num(stockData.inicial.salt_g);
        el.stockEggs.value = num(stockData.inicial.eggs); 
        el.stockOilMl.value = num(stockData.inicial.oil_ml); 
        el.stockWaterL.value = num(stockData.inicial.water_l); 
        el.stockYeastG.value = num(stockData.inicial.yeast_g);

        const left = {
          flourKg: stockData.atual.flour_kg,
          sugarG: stockData.atual.sugar_g,
          saltG: stockData.atual.salt_g,
          eggs: stockData.atual.eggs,
          oilMl: stockData.atual.oil_ml,
          waterL: stockData.atual.water_l,
          yeastG: stockData.atual.yeast_g
        };

        const kpis = [
          { label: 'Batidas', value: kpisData.total_batidas, bgStyle: 'bg-indigo-50', textStyle: 'text-indigo-500', hoverBg: 'group-hover:bg-indigo-100', glow: 'bg-indigo-100', icon: '<i data-lucide="layers" class="w-4 h-4"></i>' },
          { label: 'Farinha usada', value: `${fmt(kpisData.total_flour_kg)} kg`, bgStyle: 'bg-red-50', textStyle: 'text-red-500', hoverBg: 'group-hover:bg-red-100', glow: 'bg-red-100', icon: '<i data-lucide="wheat" class="w-4 h-4"></i>' },
          { label: 'Ovos usados', value: fmt(kpisData.total_eggs, 0), bgStyle: 'bg-amber-50', textStyle: 'text-amber-500', hoverBg: 'group-hover:bg-amber-100', glow: 'bg-amber-100', icon: '<i data-lucide="egg" class="w-4 h-4"></i>' },
          { label: 'Óleo usado', value: `${fmt(kpisData.total_oil_ml, 0)} ml`, bgStyle: 'bg-orange-50', textStyle: 'text-orange-500', hoverBg: 'group-hover:bg-orange-100', glow: 'bg-orange-100', icon: '<i data-lucide="droplet" class="w-4 h-4"></i>' },
          { label: 'Farinha rest.', value: `${fmt(left.flourKg)} kg`, bgStyle: 'bg-emerald-50', textStyle: 'text-emerald-500', hoverBg: 'group-hover:bg-emerald-100', glow: 'bg-emerald-100', icon: '<i data-lucide="archive" class="w-4 h-4"></i>' },
          { label: 'Masseiros', value: kpisData.total_masseiros, bgStyle: 'bg-gray-50', textStyle: 'text-gray-500', hoverBg: 'group-hover:bg-gray-100', glow: 'bg-gray-100', icon: '<i data-lucide="users" class="w-4 h-4"></i>' }
        ];

        el.massSubtotals.innerHTML = kpis.map(k => `
          <div class="relative overflow-hidden group bg-white border border-[#E5E7EB] rounded-2xl p-4 sm:p-5 shadow-sm transition-all duration-300 hover:shadow-md flex flex-col justify-between min-h-[105px]">
            <div class="absolute -right-6 -top-6 w-24 h-24 ${k.glow} rounded-full blur-2xl opacity-40 group-hover:opacity-80 transition-opacity"></div>
            <div class="relative z-10 flex items-center justify-between mb-2">
              <span class="text-[13px] font-semibold text-[#6B7280] tracking-tight uppercase">${k.label}</span>
              <div class="w-8 h-8 flex items-center justify-center rounded-lg ${k.bgStyle} ${k.textStyle} transition-colors duration-300 ${k.hoverBg}">
                <div class="w-4 h-4 flex items-center justify-center">
                  ${k.icon}
                </div>
              </div>
            </div>
            <div class="relative z-10 flex items-baseline">
              <span class="text-3xl leading-none font-extrabold text-[#111827] tracking-tight">${k.value}</span>
            </div>
          </div>
        `).join('');

        el.massBalanceGrid.innerHTML = Object.keys(MASS_RECIPE).map(k => `
          <div class="p-3 rounded-lg border ${left[k] < 0 ? 'bg-red-50 border-red-200 text-red-700' : 'bg-gray-50/50 border-[#E7E7E7] text-[#171717]'} ">
            <p class="text-[11px] font-medium text-[#737373] uppercase tracking-wider mb-1">${MASS_LABELS[k]}</p>
            <p class="text-base font-bold">${fmt(left[k])} <span class="text-xs font-normal text-[#737373]">${MASS_UNITS[k]}</span></p>
          </div>
        `).join('');

        el.massStockStatus.textContent = 'Atualizado';
        el.massStockStatus.className = `px-2.5 py-1 rounded-full text-xs font-medium border shrink-0 self-start sm:self-center bg-emerald-50 text-emerald-700 border-emerald-200`;
        if (Object.values(left).some(v => v < 0)) {
          el.massStockStatus.textContent = 'Consumo acima do estoque';
          el.massStockStatus.className = 'px-2.5 py-1 rounded-full text-xs font-medium border shrink-0 self-start sm:self-center bg-red-50 text-red-700 border-red-200';
        }
        el.massRecipeChip.textContent = `Receita padrão: ${fmt(MASS_RECIPE.flourKg)} kg farinha · ${fmt(MASS_RECIPE.eggs)} ovos`;

        el.openMassBatchBtn.disabled = op.status !== 'production_open' || !workers.length;
        el.saveMassStockBtn.disabled = op.status === 'completed';

        el.massBatchHistory.innerHTML = historyData.length ? `${historyData.map((b, i) => `
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-xl border border-[#E7E7E7] hover:bg-gray-50/50 transition-colors">
            <div class="flex items-start gap-3">
              <div class="w-10 h-10 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm shrink-0">
                #${String(historyData.length - i).padStart(2, '0')}
              </div>
              <div>
                <div class="flex flex-wrap items-center gap-2 mb-1">
                  <span class="font-bold text-[#171717]">${esc(b.worker_name)}</span>
                  <span class="text-xs text-[#737373]">• ${formatTime(b.created_at)}</span>
                  ${b.note ? `<span class="px-2 py-0.5 rounded bg-gray-100 text-gray-600 text-[10px] font-medium border border-gray-200">${esc(b.note)}</span>` : ''}
                </div>
                <div class="flex flex-wrap gap-1.5 mt-2">
                  <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-white border border-[#E7E7E7] text-[#4B5563] shadow-sm">Farinha <strong class="text-[#171717]">${fmt(b.flour_kg)}</strong> kg</span>
                  <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-white border border-[#E7E7E7] text-[#4B5563] shadow-sm">Açúcar <strong class="text-[#171717]">${fmt(b.sugar_g)}</strong> g</span>
                  <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-white border border-[#E7E7E7] text-[#4B5563] shadow-sm">Sal <strong class="text-[#171717]">${fmt(b.salt_g)}</strong> g</span>
                  <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-white border border-[#E7E7E7] text-[#4B5563] shadow-sm">Ovos <strong class="text-[#171717]">${fmt(b.eggs)}</strong> un.</span>
                  <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-white border border-[#E7E7E7] text-[#4B5563] shadow-sm">Óleo <strong class="text-[#171717]">${fmt(b.oil_ml)}</strong> ml</span>
                  <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-white border border-[#E7E7E7] text-[#4B5563] shadow-sm">Água <strong class="text-[#171717]">${fmt(b.water_l)}</strong> L</span>
                  <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-white border border-[#E7E7E7] text-[#4B5563] shadow-sm">Fermento <strong class="text-[#171717]">${fmt(b.yeast_g)}</strong> g</span>
                </div>
              </div>
            </div>
          </div>
        `).join('')}` : `
          <div class="p-10 text-center flex flex-col items-center justify-center bg-gray-50/50 rounded-xl border border-dashed border-[#E7E7E7]">
            <div class="w-12 h-12 bg-white border border-[#E7E7E7] shadow-sm rounded-full flex items-center justify-center mb-3">
              <i data-lucide="inbox" class="w-5 h-5 text-gray-400"></i>
            </div>
            <strong class="text-sm text-[#171717] mb-1">Nenhuma batida registrada</strong>
            <p class="text-xs text-[#737373]">Use o botão "Nova batida" para registrar a primeira.</p>
          </div>
        `;
        lucide.createIcons();
      } catch (err) {
        console.error("Erro na busca de dados de massa:", err);
      }
    }
    function openMassBatch() {
      const op = currentOperation(); if (!op || op.status !== 'production_open') return toast('A cozinha precisa estar em operação.', 'warn');
      const workers = massWorkers(op); if (!workers.length) return toast('Acione pelo menos um masseiro na equipe do dia.', 'warn');
      el.massWorkerSelect.innerHTML = workers.map(w => `<option value="${w.personId}">${esc(w.name)}</option>`).join('');
      setMassInputs('batch', MASS_RECIPE); el.batchNote.value = ''; el.massBatchModal.classList.add('show');
    }
    function saveMassStock() {
      const stock = massInputObject('stock');
      if (Object.values(stock).some(v => v < 0)) return toast('O estoque não pode ter valores negativos.', 'error');
      state.globalMassStock = stock; save(); renderMass(); toast('Estoque atualizado com sucesso.');
    }
    function saveMassBatch() {
      const op = currentOperation(); if (!op || op.status !== 'production_open') return toast('A cozinha não está em operação.', 'warn');
      const mass = ensureMass(op);
      const worker = massWorkers(op).find(w => w.personId === el.massWorkerSelect.value); if (!worker) return toast('Selecione o masseiro.', 'error');
      const materials = massInputObject('batch'); if (Object.values(materials).some(v => v < 0)) return toast('Os materiais não podem ser negativos.', 'error');
      const left = massRemaining(), insufficient = Object.keys(materials).filter(k => materials[k] > left[k] + 1e-9);
      if (insufficient.length) return toast(`Estoque insuficiente: ${insufficient.map(k => MASS_LABELS[k]).join(', ')}.`, 'warn');
      Object.keys(materials).forEach(k => { state.globalMassStock[k] = Math.max(0, (state.globalMassStock[k] || 0) - materials[k]); });
      const next = (mass.batches.reduce((m, b) => Math.max(m, num(b.number)), 0) || 0) + 1;
      mass.batches.push({ id: uid(), number: next, workerId: worker.personId, workerName: worker.name, materials, note: el.batchNote.value.trim(), createdAt: new Date().toISOString() });
      save(); el.massBatchModal.classList.remove('show'); renderMass(); toast(`Batida #${next} registrada.`);
    }

    /* ---------- ATENDIMENTO / DESPACHO ---------- */
    function ovenAvailable(op) { return (op?.commands || []).filter(c => c.status === 'pronto') }
    function dispatchQueue(op) { return (op?.commands || []).filter(c => c.status === 'despacho') }
    function resetDispatchFields() {
      el.dispatchBeverage.checked = false; el.dispatchChange.checked = false; el.dispatchChangeAmount.value = ''; el.dispatchChangeAmount.disabled = true;
      el.dispatchKetchup.checked = false; el.dispatchMayonnaise.checked = false; el.dispatchNote.value = '';
    }
    function fillDispatchFields(c) {
      const d = c.dispatch || {};
      el.dispatchBeverage.checked = !!d.beverage; el.dispatchChange.checked = !!d.change; el.dispatchChangeAmount.disabled = !d.change;
      el.dispatchChangeAmount.value = d.changeAmount || ''; el.dispatchKetchup.checked = !!d.ketchup; el.dispatchMayonnaise.checked = !!d.mayonnaise; el.dispatchNote.value = d.note || '';
    }
    function selectedDispatchHtml(c) {
      if (!c) return '';
      return `<div class="bg-gray-50 rounded-xl p-4 border border-[#E7E7E7] mb-4">
        <div class="flex items-center justify-between mb-2">
          <h4 class="text-lg font-bold text-[#171717]">Comanda #${String(c.number).padStart(3, '0')}</h4>
          <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-lg border border-blue-200">${statusText(c)}</span>
        </div>
        <p class="text-sm text-[#737373] mb-3">${formatAssemblers(c)} · ${fmt(commandEquivalent(c))} pizzas equivalentes</p>
        <div class="flex flex-wrap gap-1.5">${specialTagsHtml(c)}</div>
      </div>`;
    }
    function renderOvenPicker(selectedId = '') {
      const op = currentOperation(), q = norm(el.ovenCommandSearch.value), list = ovenAvailable(op).filter(c => !q || norm(`${c.number} ${c.assemblerName}`).includes(q));
      el.ovenCommandList.innerHTML = list.length ? list.map(c => `<button type="button" class="oven-command-option ${c.id === selectedId ? 'selected' : ''}" data-oven-command="${c.id}">
    <strong>#${String(c.number).padStart(3, '0')}</strong>
    <span>${formatAssemblers(c)}<small>${num(c.pizzas)} físicas ${commandTags(c).length ? '· ' + commandTags(c).join(' · ') : ''}</small></span>
    <span class="oven-eq">${fmt(commandEquivalent(c))} equiv.</span>
  </button>`).join('') : empty('Nenhuma comanda no forno', 'Quando a cozinha marcar “entrou no forno”, ela aparecerá aqui.');
    }
    function openDispatchIntake(commandId = '') {
      const op = currentOperation(); if (!op || ['draft', 'completed'].includes(op.status)) return toast('O atendimento não está disponível.', 'warn');
      el.dispatchModalTitle.textContent = 'Puxar comanda do forno'; el.dispatchModalSubtitle.textContent = 'Selecione uma comanda que já entrou no forno.';
      el.ovenCommandPickerBlock.classList.remove('hidden'); el.dispatchCommandId.value = commandId || ''; el.ovenCommandSearch.value = '';
      resetDispatchFields(); renderOvenPicker(commandId);
      const c = commandId ? getCommand(op, commandId) : null;
      el.dispatchSelectedSummary.innerHTML = selectedDispatchHtml(c); el.dispatchSelectedSummary.classList.toggle('show', !!c);
      el.receiveDispatchBtn.classList.remove('hidden'); el.checkDispatchBtn.classList.add('hidden'); el.outForDeliveryBtn.classList.add('hidden');
      el.dispatchCommandModal.classList.add('show');
    }
    function openDispatchExisting(commandId) {
      const op = currentOperation(), c = getCommand(op, commandId); if (!c) return;
      el.dispatchModalTitle.textContent = `Conferir comanda #${String(c.number).padStart(3, '0')}`;
      el.dispatchModalSubtitle.textContent = c.dispatch?.status === 'entrega' ? 'Pedido já saiu para entrega.' : 'Atualize os dados antes da saída para o motoboy.';
      el.ovenCommandPickerBlock.classList.add('hidden'); el.dispatchCommandId.value = c.id; fillDispatchFields(c);
      el.dispatchSelectedSummary.innerHTML = selectedDispatchHtml(c); el.dispatchSelectedSummary.classList.add('show');
      el.receiveDispatchBtn.classList.add('hidden');
      el.checkDispatchBtn.classList.toggle('hidden', c.dispatch?.status === 'entrega');
      el.outForDeliveryBtn.classList.toggle('hidden', c.dispatch?.status === 'entrega');
      el.outForDeliveryBtn.disabled = c.dispatch?.status !== 'conferido';
      el.dispatchCommandModal.classList.add('show');
    }
    function collectDispatchModal(c) {
      c.dispatch.beverage = el.dispatchBeverage.checked; c.dispatch.change = el.dispatchChange.checked; c.dispatch.changeAmount = c.dispatch.change ? el.dispatchChangeAmount.value.trim() : '';
      c.dispatch.ketchup = el.dispatchKetchup.checked; c.dispatch.mayonnaise = el.dispatchMayonnaise.checked; c.dispatch.note = el.dispatchNote.value.trim(); c.updatedAt = new Date().toISOString();
    }
    function dispatchCard(c) {
      const d = c.dispatch || {}, isDelivery = d.status === 'entrega', isChecked = d.status === 'conferido';
      const statusColor = isDelivery ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : isChecked ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'bg-yellow-50 text-yellow-700 border-yellow-200';
      const statusLabel = isDelivery ? 'Saiu para entrega' : isChecked ? 'Conferido' : 'Recebido / aguardando';
      const statusIcon = isDelivery ? 'bike' : isChecked ? 'check-circle-2' : 'clock';

      return `<article class="bg-white rounded-xl border border-[#E7E7E7] shadow-[0_2px_8px_rgba(0,0,0,0.04)] flex flex-col transition-all duration-200 hover:shadow-md ${isDelivery ? 'opacity-75 grayscale-[0.3]' : ''}">
        <div class="p-4 border-b border-[#E7E7E7] bg-gray-50/50">
          <div class="flex items-start justify-between gap-2">
            <div>
              <h4 class="text-lg font-bold text-[#171717] leading-tight">Comanda #${String(c.number).padStart(3, '0')}</h4>
              <p class="text-xs text-[#737373] mt-1 font-medium">${fmt(commandEquivalent(c))} equiv. · ${formatAssemblers(c)}</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] uppercase tracking-wider font-bold border ${statusColor} shrink-0 text-center">
              <i data-lucide="${statusIcon}" class="w-3.5 h-3.5"></i>
              ${statusLabel}
            </span>
          </div>
          <div class="mt-3 flex flex-wrap gap-1.5">
            ${specialTagsHtml(c)}
          </div>
        </div>
        
        <div class="p-4 flex-grow flex flex-col justify-between gap-4">
          <div class="flex flex-wrap gap-2">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-gray-100 text-gray-700">
              <i data-lucide="${d.beverage ? 'cup-soda' : 'ban'}" class="w-3.5 h-3.5 ${d.beverage ? 'text-blue-500' : 'text-gray-400'}"></i>
              ${d.beverage ? 'Bebida' : 'Sem bebida'}
            </span>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-gray-100 text-gray-700">
              <i data-lucide="banknote" class="w-3.5 h-3.5 ${d.change ? 'text-emerald-500' : 'text-gray-400'}"></i>
              ${d.change ? 'Troco ' + esc(d.changeAmount || '') : 'Sem troco'}
            </span>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-gray-100 text-gray-700">
              <i data-lucide="${d.ketchup ? 'check' : 'x'}" class="w-3.5 h-3.5 ${d.ketchup ? 'text-red-500' : 'text-gray-400'}"></i>
              ${d.ketchup ? 'Ketchup' : 'S/ ketchup'}
            </span>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-gray-100 text-gray-700">
              <i data-lucide="${d.mayonnaise ? 'check' : 'x'}" class="w-3.5 h-3.5 ${d.mayonnaise ? 'text-amber-500' : 'text-gray-400'}"></i>
              ${d.mayonnaise ? 'Maionese' : 'S/ maionese'}
            </span>
          </div>
          
          <div>
            ${isDelivery ? `<p class="text-[11px] text-[#737373] mb-3 flex items-center gap-1.5 font-medium"><i data-lucide="history" class="w-3.5 h-3.5"></i> Saiu para o motoboy às ${formatTime(d.outForDeliveryAt)}</p>` : `<p class="text-[11px] text-[#737373] mb-3 flex items-center gap-1.5 font-medium"><i data-lucide="clock" class="w-3.5 h-3.5"></i> Recebida às ${formatTime(d.receivedAt)}</p>`}
            
            <button class="w-full py-2.5 rounded-lg text-sm font-semibold transition-all shadow-sm flex items-center justify-center gap-2 
              ${isDelivery ? 'bg-white border border-[#E7E7E7] text-[#171717] hover:bg-gray-50' : 'bg-[#1F6FB2] text-white hover:bg-[#1a5e98]'}" 
              data-open-dispatch="${c.id}">
              <i data-lucide="${isDelivery ? 'eye' : 'edit-3'}" class="w-4 h-4"></i>
              ${isDelivery ? 'Ver conferência' : 'Abrir conferência'}
            </button>
          </div>
        </div>
      </article>`;
    }

    renderDispatch = function () {
      renderHeader(); const op = currentOperation(); if (el.manageTeamDispatchBtn) el.manageTeamDispatchBtn.disabled = op?.status === 'completed';
      if (!op || op.status === 'draft') {
        el.dispatchGate.innerHTML = gateCard("Operação não iniciada", "Inicie a operação para usar o atendimento.", "Ir para equipe", "team", "users");
        el.dispatchContent.classList.add('hidden'); if (el.finishDayBtn) el.finishDayBtn.disabled = true;
        el.manageTeamDispatchBtn?.classList.add('hidden');
        el.finishDayBtn?.classList.add('hidden');
        return;
      }
      el.dispatchGate.innerHTML = ''; el.dispatchContent.classList.remove('hidden');
      el.manageTeamDispatchBtn?.classList.remove('hidden');
      el.finishDayBtn?.classList.remove('hidden');
      if (op.status === 'completed') {
        el.dispatchGate.innerHTML = gateCard("Operação finalizada", "Todos os registros estão disponíveis nos relatórios.", "Abrir relatórios", "reports", "bar-chart-2");
        el.manageTeamDispatchBtn?.classList.add('hidden');
        el.finishDayBtn?.classList.add('hidden');
      }

      const oven = ovenAvailable(op), all = dispatchQueue(op), waiting = all.filter(c => c.dispatch.status === 'aguardando').length,
        checked = all.filter(c => c.dispatch.status === 'conferido').length, delivery = all.filter(c => c.dispatch.status === 'entrega').length;
      el.ovenReadyBadge.textContent = oven.length; if (el.dispatchQueueBadge) el.dispatchQueueBadge.textContent = waiting + checked;
      
      const kpis = [
        ['No forno', oven.length, 'bg-orange-50', 'text-orange-500', 'group-hover:bg-orange-100', 'bg-orange-100', '<i data-lucide="flame" class="w-4 h-4"></i>'],
        ['Recebidas', all.length, 'bg-blue-50', 'text-blue-600', 'group-hover:bg-blue-100', 'bg-blue-100', '<i data-lucide="inbox" class="w-4 h-4"></i>'],
        ['Aguardando', waiting, 'bg-yellow-50', 'text-yellow-600', 'group-hover:bg-yellow-100', 'bg-yellow-100', '<i data-lucide="clock" class="w-4 h-4"></i>'],
        ['Conferidas', checked, 'bg-indigo-50', 'text-indigo-600', 'group-hover:bg-indigo-100', 'bg-indigo-100', '<i data-lucide="check-square" class="w-4 h-4"></i>'],
        ['Em entrega', delivery, 'bg-emerald-50', 'text-emerald-600', 'group-hover:bg-emerald-100', 'bg-emerald-100', '<i data-lucide="bike" class="w-4 h-4"></i>'],
        ['Faltam zerar', pendingToFinish(op), 'bg-red-50', 'text-red-500', 'group-hover:bg-red-100', 'bg-red-100', '<i data-lucide="alert-circle" class="w-4 h-4"></i>']
      ];
      el.dispatchSubtotals.innerHTML = kpis.map(([label, value, bgStyle, textStyle, hoverBg, glow, icon]) => `
      <div class="relative group bg-white border border-[#E5E7EB] rounded-xl p-4 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md overflow-hidden">
        <div class="absolute -right-4 -top-4 w-16 h-16 rounded-full ${glow} blur-xl opacity-50 group-hover:opacity-80 transition-opacity"></div>
        <div class="flex items-center gap-3 mb-2 relative z-10">
          <div class="w-8 h-8 rounded-lg ${bgStyle} ${textStyle} flex items-center justify-center shrink-0 transition-colors ${hoverBg}">
            ${icon}
          </div>
          <span class="text-[12px] font-bold text-[#737373] uppercase tracking-wider">${label}</span>
        </div>
        <div class="text-2xl font-black text-[#171717] tracking-tight relative z-10">${value}</div>
      </div>
      `).join('');

      const q = norm(el.dispatchSearch.value), f = el.dispatchFilter.value, order = { aguardando: 0, conferido: 1, entrega: 2 };
      const cs = all.filter(c => (!q || norm(`${c.number} ${c.assemblerName}`).includes(q)) && (!f || c.dispatch.status === f))
        .sort((a, b) => order[a.dispatch.status] - order[b.dispatch.status] || (b.dispatch.receivedAt || '').localeCompare(a.dispatch.receivedAt || ''));
      el.dispatchGrid.innerHTML = cs.map(dispatchCard).join(''); el.dispatchEmpty.classList.toggle('hidden', cs.length > 0);
      if (el.finishDayBtn) {
        el.finishDayBtn.disabled = op.status !== 'kitchen_closed' || pendingToFinish(op) > 0;
        el.finishDayBtn.innerHTML = op.status === 'completed' ? '<i data-lucide="check-circle" class="w-4 h-4"></i> Dia finalizado' : pendingToFinish(op) > 0 ? `<i data-lucide="alert-circle" class="w-4 h-4"></i> Faltam ${pendingToFinish(op)} pedidos` : '<i data-lucide="check-circle" class="w-4 h-4"></i> Finalizar o dia';
      }
      
      if (window.lucide) window.lucide.createIcons();
    };

    renderReportDetail = function (op) {
      const s = stats(op), errRate = s.pizzas ? ((s.errors / s.pizzas) * 100).toFixed(1) : '0.0', mass = ensureMass(op), used = massConsumed(op);
      el.reportOverview.innerHTML = `<div class="page-head" style="margin-bottom:12px"><div><h3 style="font-size:24px">${formatDate(op.date)}</h3><p>${phaseLabel(op)} · início ${formatTime(op.startedAt)} · cozinha ${formatTime(op.kitchenClosedAt)} · final ${formatTime(op.completedAt)}</p></div></div>
  <div class="subtotal">
    <div class="subtotal-item"><small>Comandas</small><strong>${s.commands}</strong></div>
    <div class="subtotal-item"><small>Pizzas equiv.</small><strong>${fmt(s.pizzas)}</strong></div>
    <div class="subtotal-item"><small>Doces</small><strong>${fmt(s.sweet)}</strong></div>
    <div class="subtotal-item"><small>Vulcão</small><strong>${fmt(s.volcano)}</strong></div>
    <div class="subtotal-item"><small>Esfirras</small><strong>${fmt(s.esfihas, 0)}</strong></div>
    <div class="subtotal-item"><small>Erros</small><strong>${s.errors}</strong></div>
    <div class="subtotal-item"><small>Batidas de massa</small><strong>${mass.batches.length}</strong></div>
    <div class="subtotal-item"><small>Farinha consumida</small><strong>${fmt(used.flourKg)} kg</strong></div>
  </div>
  <h3 style="margin-top:20px">Ranking da montagem</h3><div style="margin-top:10px">${rankHtml(op)}</div>`;
      const attendanceReady = op.team.length > 0, productionReady = ['kitchen_closed', 'completed'].includes(op.status);
      el.reportCards.innerHTML = `<article class="report-card"><h4>Lista de presença</h4><p>Relação da equipe por setor, com campo de assinatura.</p><div class="actions mobile-stack"><button class="btn btn-soft" data-report-action="download-attendance" ${attendanceReady ? '' : 'disabled'}>Baixar relatório</button><button class="btn btn-primary" data-report-action="print-attendance" ${attendanceReady ? '' : 'disabled'}>Imprimir / Salvar PDF</button></div></article>
  <article class="report-card"><h4>Resultado da montagem</h4><p>Ranking por pizzas equivalentes, comandas, participação, especiais e erros.</p><div class="actions mobile-stack"><button class="btn btn-soft" data-report-action="download-production" ${productionReady ? '' : 'disabled'}>Baixar relatório</button><button class="btn btn-primary" data-report-action="print-production" ${productionReady ? '' : 'disabled'}>Imprimir / Salvar PDF</button></div></article>`;
    };
    renderReports = function () {
      renderHeader(); const ops = [...state.operations].sort((a, b) => b.date.localeCompare(a.date));
      if (!ops.length) { el.historyList.innerHTML = empty('Nenhuma operação', 'Crie a equipe do primeiro dia.'); el.reportOverview.innerHTML = empty('Sem dados', 'Os relatórios aparecerão aqui.'); el.reportCards.innerHTML = ''; return }
      if (!selectedReportOperationId || !getOperation(selectedReportOperationId)) selectedReportOperationId = ops[0].id;
      el.historyList.innerHTML = ops.map(o => { const s = stats(o); return `<div class="history-item ${o.id === selectedReportOperationId ? 'selected' : ''}" data-report-op="${o.id}"><h4>${formatDate(o.date)}</h4><p>${phaseLabel(o)} · ${o.team.length} pessoas</p><div class="metric-chips"><span class="metric-chip">${fmt(s.pizzas)} equiv.</span><span class="metric-chip">${s.errors} erros</span><span class="metric-chip">${s.released} entregas</span></div></div>` }).join('');
      renderReportDetail(getOperation(selectedReportOperationId));
    };
    productionReport = function (op) {
      const r = ranking(op), s = stats(op), rows = r.map((x, i) => `<tr><td class="rank">${i + 1}º</td><td>${esc(x.name)}</td><td>${fmt(x.pizzas)}</td><td>${x.commands}</td><td>${s.pizzas ? ((x.pizzas / s.pizzas) * 100).toFixed(1) : '0.0'}%</td><td>${x.errors}</td></tr>`).join('');
      const errors = op.commands.filter(c => c.error?.active).map(c => `<tr><td>#${String(c.number).padStart(3, '0')}</td><td>${formatAssemblers(c)}</td><td>${esc(c.error.type)}</td><td>${esc(c.error.note || '—')}</td></tr>`).join('');
      return reportShell('Resultado da Montagem', op, `<p><strong>Total:</strong> ${s.commands} comandas · ${fmt(s.pizzas)} pizzas equivalentes · ${fmt(s.physicalPizzas)} pizzas físicas · ${fmt(s.volcano)} vulcão · ${fmt(s.sweet)} doces · ${fmt(s.esfihas, 0)} esfirras · ${s.errors} ocorrências.</p>
  <table><thead><tr><th>Rank</th><th>Montador</th><th>Pizzas equivalentes</th><th>Comandas</th><th>Participação</th><th>Erros</th></tr></thead><tbody>${rows}</tbody></table>
  <h2 style="margin-top:22px">Ocorrências</h2>${errors ? `<table><thead><tr><th>Comanda</th><th>Montador</th><th>Tipo</th><th>Descrição</th></tr></thead><tbody>${errors}</tbody></table>` : '<p>Nenhuma ocorrência registrada.</p>'}`);
    };

    /* ---------- EVENTOS V4 ---------- */
    el.settingsBtn.addEventListener('click', openSettings);
    el.saveSettingsBtn.addEventListener('click', saveSettings);
    document.querySelectorAll('[data-close-v4]').forEach(b => b.addEventListener('click', () => { const name = b.dataset.closeV4; const modal = $(`${name}Modal`); if (modal) modal.classList.remove('show') }));
    [el.settingsModal, el.dispatchCommandModal, el.massBatchModal].forEach(m => m.addEventListener('click', e => { if (e.target === m) m.classList.remove('show') }));

    [el.volcanoCheck, el.esfihaCheck, el.sweetCheck].forEach((box, i) => {
      const input = [el.volcanoQty, el.esfihaQty, el.sweetQty][i];
      box.addEventListener('change', () => { input.disabled = !box.checked; if (box.checked && !num(input.value)) input.value = i === 1 ? settings().esfihaGroup : 1; refreshEquivalentPreview() });
    });
    [el.pizzaQty, el.volcanoQty, el.esfihaQty, el.sweetQty].forEach(x => x.addEventListener('input', refreshEquivalentPreview));

    /* Complementa o submit antigo de edição com os dados especiais. */
    el.editForm.addEventListener('submit', () => {
      const op = currentOperation(), c = getCommand(op, el.editId.value); if (!c) return;
      c.special = { volcano: Math.max(0, num(el.editVolcanoQty.value)), esfiha: Math.max(0, num(el.editEsfihaQty.value)), sweet: Math.max(0, num(el.editSweetQty.value)) };
      save(); renderProduction(); renderDashboard();
    });

    /* Cliques que levam uma comanda do forno para o atendimento, sem mudar o status automaticamente. */
    document.addEventListener('click', e => {
      const intake = e.target.closest('[data-dispatch-intake]'); if (intake) { showPage('dispatch'); setTimeout(() => openDispatchIntake(intake.dataset.dispatchIntake), 80) }
      const open = e.target.closest('[data-open-dispatch]'); if (open) { openDispatchExisting(open.dataset.openDispatch) }
    });

    el.openDispatchIntakeBtn.addEventListener('click', () => openDispatchIntake());
    if (el.openDispatchQueueBtn) el.openDispatchQueueBtn.addEventListener('click', () => el.dispatchQueuePanel.scrollIntoView({ behavior: 'smooth', block: 'start' }));
    el.ovenCommandSearch.addEventListener('input', () => renderOvenPicker(el.dispatchCommandId.value));
    el.ovenCommandList.addEventListener('click', e => {
      const b = e.target.closest('[data-oven-command]'); if (!b) return;
      const c = getCommand(currentOperation(), b.dataset.ovenCommand); el.dispatchCommandId.value = c.id; renderOvenPicker(c.id);
      el.dispatchSelectedSummary.innerHTML = selectedDispatchHtml(c); el.dispatchSelectedSummary.classList.add('show');
    });
    el.dispatchChange.addEventListener('change', () => { el.dispatchChangeAmount.disabled = !el.dispatchChange.checked; if (!el.dispatchChange.checked) el.dispatchChangeAmount.value = '' });

    el.receiveDispatchBtn.addEventListener('click', () => {
      const op = currentOperation(), c = getCommand(op, el.dispatchCommandId.value); if (!c || c.status !== 'pronto') return toast('Selecione uma comanda enviada para o atendimento.', 'warn');
      collectDispatchModal(c); const now = new Date().toISOString(); c.status = 'despacho'; c.statusTimes.despacho = now; c.dispatch.receivedAt = now; c.dispatch.status = 'aguardando';
      save(); el.dispatchCommandModal.classList.remove('show'); renderDispatch(); renderProduction(); renderDashboard(); toast(`Comanda ${c.number} recebida no atendimento.`);
    });
    el.checkDispatchBtn.addEventListener('click', () => {
      const c = getCommand(currentOperation(), el.dispatchCommandId.value); if (!c) return; collectDispatchModal(c);
      c.dispatch.status = 'conferido'; c.dispatch.checkedAt = new Date().toISOString(); save(); openDispatchExisting(c.id); renderDispatch(); renderDashboard(); toast('Comanda conferida.');
    });
    el.outForDeliveryBtn.addEventListener('click', () => {
      const c = getCommand(currentOperation(), el.dispatchCommandId.value); if (!c) return;
      if (c.dispatch.status !== 'conferido') return toast('Marque a comanda como conferida antes da saída.', 'warn');
      collectDispatchModal(c); c.dispatch.status = 'entrega'; c.dispatch.outForDeliveryAt = new Date().toISOString(); save();
      el.dispatchCommandModal.classList.remove('show'); renderDispatch(); renderDashboard(); toast('Pedido saiu para entrega.');
    });

    /* Neutraliza o antigo clique interno de despacho porque a V4 usa janela de conferência. */
    el.dispatchGrid.addEventListener('click', e => {
      const b = e.target.closest('[data-open-dispatch]'); if (b) { e.stopImmediatePropagation(); openDispatchExisting(b.dataset.openDispatch) }
    }, true);

    el.openMassBatchBtn.addEventListener('click', openMassBatch);
    el.saveMassStockBtn.addEventListener('click', saveMassStock);
    el.saveMassBatchBtn.addEventListener('click', saveMassBatch);
    el.massBatchHistory.addEventListener('click', e => {
      const b = e.target.closest('[data-delete-batch]'); if (!b) return;
      const op = currentOperation(), mass = ensureMass(op), batch = mass.batches.find(x => x.id === b.dataset.deleteBatch); if (!batch) return;
      if (!confirm(`Excluir a batida #${batch.number}?`)) return;
      mass.batches = mass.batches.filter(x => x.id !== batch.id); save(); renderMass(); toast('Batida excluída.', 'warn');
    });


    /* Garante que o botão de registro use a lógica V4, e não o listener legado da versão anterior. */
    el.addCommandBtn.addEventListener('click', e => {
      e.preventDefault();
      e.stopImmediatePropagation();
      addCommand();
    }, true);

    /* Substitui o submit legado de edição para permitir comandas somente com esfirras e salvar os especiais. */
    el.editForm.addEventListener('submit', e => {
      e.preventDefault();
      e.stopImmediatePropagation();
      const op = currentOperation(), c = getCommand(op, el.editId.value);
      if (!c) return;
      const n = Number(el.editNumber.value), q = Math.max(0, num(el.editQty.value)), max = settings().commandMax;
      const sp = { volcano: Math.max(0, num(el.editVolcanoQty.value)), esfiha: Math.max(0, num(el.editEsfihaQty.value)), sweet: Math.max(0, num(el.editSweetQty.value)) };
      if (!Number.isInteger(n) || n < 1 || n > max) return toast(`A comanda deve estar entre 1 e ${max}.`, 'error');
      if (op.commands.some(x => x.id !== c.id && x.number === n)) return toast('Essa comanda já existe.', 'warn');
      if (!Number.isInteger(q) || q < 0 || q > 50) return toast('Quantidade de pizzas inválida.', 'error');
      if (sp.volcano > q) return toast('A quantidade de vulcão não pode ser maior que as pizzas físicas.', 'error');
      if (sp.sweet > q) return toast('A quantidade de doces não pode ser maior que as pizzas físicas.', 'error');
      if (q === 0 && sp.esfiha === 0) return toast('Informe ao menos uma pizza ou esfirras.', 'error');
      const a = assemblers(op).find(p => p.personId === el.editAssembler.value); if (!a) return toast('Selecione o montador.', 'error');
      
      const sweetAsm = $('editSweetAssembler');
      const sa = sweetAsm && sweetAsm.value ? assemblers(op).find(p => p.personId === sweetAsm.value) : null;
      c.sweetAssemblerId = sa ? sa.personId : null;
      c.sweetAssemblerName = sa ? sa.name : null;

      c.number = n; c.pizzas = q; c.special = sp; c.assemblerId = a.personId; c.assemblerName = a.name; c.note = el.editNote.value.trim();
      const st = el.editStatus.value, now = new Date().toISOString();
      if (st !== c.status) {
        if (st === 'forno') {
          c.status = 'forno'; c.statusTimes.forno ||= now;
          c.dispatch.status = 'aguardando'; c.dispatch.receivedAt = null; c.dispatch.checkedAt = null; c.dispatch.outForDeliveryAt = null; c.statusTimes.despacho = null;
        } else if (st === 'cozinha') {
          c.status = 'cozinha'; c.dispatch.status = 'aguardando'; c.dispatch.receivedAt = null; c.dispatch.checkedAt = null; c.dispatch.outForDeliveryAt = null; c.statusTimes.despacho = null;
        } else if (st === 'despacho') {
          c.status = 'despacho'; c.statusTimes.despacho ||= now; c.dispatch.receivedAt ||= now;
        }
      }
      c.updatedAt = now; save(); el.editModal.classList.remove('show');
      renderProduction(); renderDispatch(); renderDashboard(); if(typeof renderSweetPendingPanel==='function') renderSweetPendingPanel(); toast('Comanda atualizada.');
    }, true);

    /* Re-renderização final usando as novas regras. */
    renderAll = function () {
      let page = 'dashboard';
      const path = window.location.pathname;
      if (path.includes('/equipe')) page = 'team';
      else if (path.includes('/cozinha')) page = 'production';
      else if (path.includes('/estoque')) page = 'stock';
      else if (path.includes('/comandas')) page = 'dispatch';
      else if (path.includes('/relatorios')) page = 'reports';
      else {
        const activeEl = document.querySelector('.page.active');
        if (activeEl) page = activeEl.id.replace('page-', '');
      }
      if (typeof showPage === 'function') {
         showPage(page);
      }
    };

    function loadState() {
      fetch('/api/init')
        .then(res => res.json())
        .then(data => {
          state = data;
          if (!state.people) state.people = [];
          if (!state.operations) state.operations = [];
          upgradeV4();
          renderAll();
          fetchTopMontadoresMensal(); // Carrega ranking mensal imediatamente ao iniciar
        })
        .catch(err => {
          console.error("Erro API", err);
          toast("Erro ao carregar do backend.", "error");
        });
    }

    const saveMassBatchBtn = document.getElementById('saveMassBatchBtn');
    if (saveMassBatchBtn) {
        saveMassBatchBtn.addEventListener('click', () => {
            const worker_id = document.getElementById('massWorkerSelect').value;
            if (!worker_id) {
                if (typeof toast === 'function') toast("Selecione o masseiro responsável.", "error");
                else alert("Selecione o masseiro responsável.");
                return;
            }

            const payload = {
                worker_id: worker_id,
                flour_kg: parseFloat(document.getElementById('batchFlourKg').value) || 0,
                sugar_g: parseFloat(document.getElementById('batchSugarG').value) || 0,
                salt_g: parseFloat(document.getElementById('batchSaltG').value) || 0,
                eggs: parseInt(document.getElementById('batchEggs').value) || 0,
                oil_ml: parseFloat(document.getElementById('batchOilMl').value) || 0,
                water_l: parseFloat(document.getElementById('batchWaterL').value) || 0,
                yeast_g: parseFloat(document.getElementById('batchYeastG').value) || 0,
                note: document.getElementById('batchNote').value || ''
            };

            const originalBtnHtml = saveMassBatchBtn.innerHTML;
            saveMassBatchBtn.innerHTML = 'Salvando...';
            saveMassBatchBtn.disabled = true;

            fetch('/api/mass-batch', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                saveMassBatchBtn.innerHTML = originalBtnHtml;
                saveMassBatchBtn.disabled = false;

                if (data.success) {
                    if (typeof toast === 'function') toast("Batida de massa salva com sucesso!", "success");
                    else alert("Batida de massa salva com sucesso!");
                    
                    const modal = document.getElementById('massBatchModal');
                    if (modal) modal.classList.remove('active');
                    
                    // Opcional: Atualizar dashboard/status
                    if(typeof loadState === 'function') loadState();
                } else {
                    if (typeof toast === 'function') toast(data.error || "Erro ao salvar batida.", "error");
                    else alert(data.error || "Erro ao salvar batida.");
                }
            })
            .catch(err => {
                saveMassBatchBtn.innerHTML = originalBtnHtml;
                saveMassBatchBtn.disabled = false;
                console.error(err);
                if (typeof toast === 'function') toast("Erro de conexão.", "error");
                else alert("Erro de conexão.");
            });
        });
    }

    const saveMassStockBtn = document.getElementById('saveMassStockBtn');
    if (saveMassStockBtn) {
        saveMassStockBtn.addEventListener('click', () => {
            const payload = {
                flour_kg: parseFloat(document.getElementById('stockFlourKg').value) || 0,
                sugar_g: parseFloat(document.getElementById('stockSugarG').value) || 0,
                salt_g: parseFloat(document.getElementById('stockSaltG').value) || 0,
                eggs: parseInt(document.getElementById('stockEggs').value) || 0,
                oil_ml: parseFloat(document.getElementById('stockOilMl').value) || 0,
                water_l: parseFloat(document.getElementById('stockWaterL').value) || 0,
                yeast_g: parseFloat(document.getElementById('stockYeastG').value) || 0
            };

            const originalBtnHtml = saveMassStockBtn.innerHTML;
            saveMassStockBtn.innerHTML = 'Salvando...';
            saveMassStockBtn.disabled = true;

            fetch('/api/mass-stock', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                saveMassStockBtn.innerHTML = originalBtnHtml;
                saveMassStockBtn.disabled = false;

                if (data.success) {
                    if (typeof toast === 'function') toast("Estoque salvo com sucesso!", "success");
                    else alert("Estoque salvo com sucesso!");
                    
                    if(typeof loadState === 'function') loadState();
                } else {
                    if (typeof toast === 'function') toast(data.error || "Erro ao salvar estoque.", "error");
                    else alert(data.error || "Erro ao salvar estoque.");
                }
            })
            .catch(err => {
                saveMassStockBtn.innerHTML = originalBtnHtml;
                saveMassStockBtn.disabled = false;
                console.error(err);
                if (typeof toast === 'function') toast("Erro de conexão.", "error");
                else alert("Erro de conexão.");
            });
        });
    }

    loadState();
