const fs = require('fs');
const file = 'c:/Users/arthu/Downloads/sistema_controle_comandas_imperial_v4_integrado/frontend/public/assets/js/app.js';
let content = fs.readFileSync(file, 'utf8');

if (!content.includes('function formatAssemblers(c)')) {
    content = content.replace(
        'function assemblerInitials(name)',
        'function formatAssemblers(c) { return c.sweetAssemblerName && c.sweetAssemblerName !== c.assemblerName ? esc(c.assemblerName) + " / " + esc(c.sweetAssemblerName) : esc(c.assemblerName); }\n    function assemblerInitials(name)'
    );
}

content = content.replace(/esc\(c\.assemblerName\)/g, 'formatAssemblers(c)');
content = content.replace(/esc\(c\.assemblerName\.split\(' '\)\[0\]\)/g, 'formatAssemblers(c)');

// Also fix renderSweetPendingPanel
content = content.replace(
    /num\(c\.special\?\.sweet\) > 0 && !c\.sweetDelivered/g,
    'num(c.special?.sweet) > 0 && !c.sweetDelivered && !c.sweetAssemblerId'
);

fs.writeFileSync(file, content);
console.log('Done');
