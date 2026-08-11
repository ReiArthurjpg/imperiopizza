    <section id="page-team" class="page">
      <div class="page-head">
        <div>
          <h2>Equipe do dia</h2>
          <p>Cadastre profissionais, marque a presença e atualize a equipe até o encerramento completo.</p>
        </div>
      </div>
      <div id="teamOperationNotice" class="team-live-note"></div>
      <div class="grid sidebar">
        <div class="grid">
          <article class="card">
            <h3>Cadastrar profissional</h3>
            <p class="sub">O cadastro fica disponível para os próximos dias.</p>
            <form id="personForm">
              <div class="field"><label for="personName">Nome</label><input id="personName" type="text" maxlength="80"
                  placeholder="Nome do colaborador" required></div>
              <div class="field"><label for="personRole">Setor</label><select id="personRole" required>
                  <option value="">Selecione</option>
                  <option>Montagem</option>
                  <option>Massa</option>
                  <option>Cozinha</option>
                  <option>Forno</option>
                  <option>Despacho</option>
                  <option>Atendimento</option>
                  <option>Estoque</option>
                  <option>Liderança</option>
                  <option>Outros</option>
                </select></div><button class="btn btn-primary btn-wide" type="submit">Cadastrar</button>
            </form>
          </article>
          <article class="card">
            <h3>Profissionais cadastrados</h3>
            <p class="sub">Marque quem está presente na data selecionada.</p>
            <div id="peopleChecklist" class="check-list"></div>
          </article>
        </div>
        <div class="grid">
          <article class="card">
            <h3>Lista de presença da operação</h3>
            <p class="sub">A seleção abaixo será usada no dashboard e nos relatórios.</p>
            <div id="dayTeamGroups"></div>
            <div class="actions end mobile-stack" style="margin-top:16px"><button id="saveTeamBtn"
                class="btn btn-soft">Salvar equipe</button><button id="startOperationBtn"
                class="btn btn-primary">Iniciar operação</button></div>
          </article>
          <article class="card">
            <h3>Fluxo do dia</h3>
            <p class="sub">A operação possui duas finalizações independentes.</p>
            <div class="chips"><span class="chip">1. Iniciar operação</span><span class="chip">2. Registrar
                produção</span><span class="chip">3. Encerrar cozinha</span><span class="chip">4. Continuar
                despacho</span><span class="chip">5. Zerar pendências</span><span class="chip">6. Finalizar o dia</span>
            </div>
          </article>
        </div>
      </div>
    </section>


