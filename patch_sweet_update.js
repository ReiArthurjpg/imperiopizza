const fs = require('fs');
const file = 'c:/Users/arthu/Downloads/sistema_controle_comandas_imperial_v4_integrado/frontend/public/assets/js/app.js';
let content = fs.readFileSync(file, 'utf8');

content = content.replace(/renderDashboard\(\);\s*toast\('Comanda atualizada.'\)/g, "renderDashboard(); if(typeof renderSweetPendingPanel==='function') renderSweetPendingPanel(); toast('Comanda atualizada.')");

fs.writeFileSync(file, content);
console.log('Done');
