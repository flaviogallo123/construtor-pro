<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Construtor Pro - Editor Completo</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root {
    --bg-primary: #0a0a0a;
    --bg-secondary: #111111;
    --bg-tertiary: #1a1a1a;
    --bg-hover: #27272a;
    --border: #27272a;
    --text: #fafafa;
    --text-secondary: #a1a1aa;
    --text-tertiary: #71717a;
    --accent: #3b82f6;
    --accent-hover: #2563eb;
    --accent-glow: rgba(59, 130, 246, 0.15);
    --success: #10b981;
    --warning: #f59e0b;
    --error: #ef4444;
    --purple: #8b5cf6;
}
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Inter', sans-serif; background: var(--bg-primary); color: var(--text); height: 100vh; overflow: hidden; }

.navbar { background: var(--bg-secondary); border-bottom: 1px solid var(--border); padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; z-index: 1000; }
.navbar-left, .navbar-right { display: flex; align-items: center; gap: 12px; }
.brand { display: flex; align-items: center; gap: 10px; font-size: 20px; font-weight: 800; font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #fff 0%, #3b82f6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.brand-icon { width: 36px; height: 36px; background: linear-gradient(135deg, #3b82f6, #8b5cf6); border-radius: 10px; display: flex; align-items: center; justify-content: center; -webkit-text-fill-color: white; }
.nav-btn { padding: 8px 16px; border-radius: 8px; border: none; cursor: pointer; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 6px; transition: all 0.3s; white-space: nowrap; text-decoration: none; color: var(--text); }
.nav-btn.secondary { background: rgba(255,255,255,0.05); border: 1px solid var(--border); }
.nav-btn.secondary:hover { background: rgba(255,255,255,0.1); }
.nav-btn.primary { background: linear-gradient(135deg, #10b981, #059669); color: white; }
.nav-btn.primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(16,185,129,0.3); }
.nav-btn.editor-btn { background: linear-gradient(135deg, #8b5cf6, #ec4899); color: white; }
.nav-btn.editor-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(139,92,246,0.4); }
.nav-btn:disabled { opacity: 0.5; cursor: not-allowed; }

.view-container { display: none; height: calc(100vh - 57px); }
.view-container.active { display: flex; flex-direction: column; }

.editor-layout { display: flex; flex: 1; overflow: hidden; }
.sidebar-left { width: 280px; background: var(--bg-secondary); border-right: 1px solid var(--border); overflow-y: auto; flex-shrink: 0; display: flex; flex-direction: column; }
.sidebar-tabs { display: flex; border-bottom: 1px solid var(--border); }
.sidebar-tab { flex: 1; padding: 12px; background: transparent; border: none; color: var(--text-tertiary); cursor: pointer; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; transition: all 0.2s; }
.sidebar-tab.active { color: var(--accent); border-bottom: 2px solid var(--accent); }
.sidebar-content { flex: 1; overflow-y: auto; padding: 12px; }
.sidebar-panel { display: none; }
.sidebar-panel.active { display: block; }
.section-header { padding: 8px 4px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-tertiary); margin-top: 8px; }
.widget-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; }

.widget-item { 
    padding: 12px 8px; 
    background: var(--bg-tertiary); 
    border: 1px solid var(--border); 
    border-radius: 8px; 
    cursor: grab; 
    text-align: center; 
    transition: all 0.2s; 
    user-select: none;
}
.widget-item:hover { 
    background: var(--bg-hover); 
    border-color: var(--accent); 
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
}
.widget-item:active { cursor: grabbing; }
.widget-item i { font-size: 20px; color: var(--accent); margin-bottom: 6px; display: block; }
.widget-item span { font-size: 10px; color: var(--text-secondary); }

.canvas-area { flex: 1; position: relative; overflow: auto; background: #f5f6f8; background-image: radial-gradient(circle, #e0e0e0 1px, transparent 1px); background-size: 20px 20px; }
.canvas { width: 100%; min-height: 100%; position: relative; padding: 40px 20px; transition: all 0.3s; background: white; }
.canvas.tablet { max-width: 768px; margin: 40px auto; box-shadow: 0 0 40px rgba(0,0,0,0.1); }
.canvas.mobile { max-width: 375px; margin: 40px auto; box-shadow: 0 0 40px rgba(0,0,0,0.1); }

.canvas.drag-over {
    background: rgba(16, 185, 129, 0.1);
    border: 3px dashed var(--success);
}

.responsive-bar { position: absolute; top: 10px; left: 50%; transform: translateX(-50%); background: var(--bg-secondary); border: 1px solid var(--border); padding: 6px; border-radius: 10px; display: flex; gap: 4px; z-index: 100; }
.responsive-bar button { padding: 8px 14px; background: transparent; border: none; color: var(--text-secondary); cursor: pointer; border-radius: 6px; font-size: 12px; display: flex; align-items: center; gap: 5px; }
.responsive-bar button.active { background: var(--accent); color: white; }

.sidebar-right { width: 320px; background: var(--bg-secondary); border-left: 1px solid var(--border); overflow-y: auto; flex-shrink: 0; display: flex; flex-direction: column; }
.right-tabs { display: flex; border-bottom: 1px solid var(--border); }
.right-tab { flex: 1; padding: 12px; background: transparent; border: none; color: var(--text-tertiary); cursor: pointer; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; transition: all 0.2s; }
.right-tab.active { color: var(--accent); border-bottom: 2px solid var(--accent); }
.right-content { flex: 1; overflow-y: auto; padding: 16px; }
.right-panel { display: none; }
.right-panel.active { display: block; }

.el-button .sbc-button-align {
    width: 100% !important;
    min-width: 0 !important;
    display: flex !important;
    align-items: center !important;
    box-sizing: border-box !important;
}
.el-button .editor-button {
    display: inline-flex !important;
    flex: 0 0 auto;
    align-items: center;
    justify-content: center;
    box-sizing: border-box !important;
    text-decoration: var(--btn-decoration, none);
    cursor: pointer;
    transition: all var(--btn-transition, .25s) ease;
    transform: var(--btn-transform, none);
    opacity: var(--btn-opacity, 1);
    border-style: var(--btn-border-style, solid) !important;
    border-width: var(--btn-border-width, 0px) !important;
    border-color: var(--btn-border, transparent) !important;
    background: var(--btn-bg, #3b82f6) !important;
    color: var(--btn-text, #fff) !important;
}
.el-button .editor-button[style*='width:100%'] {
    flex-basis: 100%;
}

.el-button .editor-button:hover {
    background: var(--btn-bg-hover, var(--btn-bg)) !important;
    color: var(--btn-text-hover, var(--btn-text)) !important;
    border-color: var(--btn-border-hover, var(--btn-border)) !important;
    transform: var(--btn-hover-transform, none);
    box-shadow: var(--btn-hover-shadow, none) !important;
}
.el-button .editor-button .button-icon { line-height: 1; display:inline-flex; align-items:center; }

/* =========================================================
   IMAGEM PROFISSIONAL
   ========================================================= */
.sbc-image-wrap {
    position: relative !important;
    display: flex !important;
    align-items: center !important;
    justify-content: var(--img-justify, center) !important;
    width: 100% !important;
    max-width: 100% !important;
    box-sizing: border-box !important;
    overflow: visible;
}
.sbc-image-wrap .sbc-image-link {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    max-width: 100% !important;
    line-height: 0 !important;
}
.sbc-image-wrap .sbc-editor-image {
    display: block !important;
    max-width: 100% !important;
    transition: transform var(--img-transition, .3s) ease, filter .3s ease, opacity .3s ease, box-shadow .3s ease;
    transform: translateZ(0);
}
.sbc-image-wrap:hover .sbc-editor-image {
    transform: var(--img-hover-transform, none);
    filter: var(--img-hover-filter, none);
}
.sbc-image-wrap .sbc-image-link {
    text-decoration: none;
    color: inherit;
}

/* =========================================================
   ALINHAMENTO PADRÃO DOS ELEMENTOS
   ========================================================= */
.sbc-element-align {
    width: 100% !important;
    max-width: 100% !important;
    box-sizing: border-box !important;
}
.sbc-icon-align {
    width: 100% !important;
    display: flex !important;
    align-items: center !important;
    box-sizing: border-box !important;
}

.sbc-icon-align .sbc-editor-icon {
    flex: 0 0 auto !important;
    width: auto !important;
    height: auto !important;
    max-width: none !important;
    max-height: none !important;
}
.sbc-button-align {
    width: 100% !important;
    box-sizing: border-box !important;
}



/* =========================================================
   FORMULÁRIO PROFISSIONAL
   ========================================================= */
.sbc-form-shell{
    width:100%;
    display:flex;
    box-sizing:border-box;
}
.sbc-form-shell[data-align="left"]{justify-content:flex-start;}
.sbc-form-shell[data-align="center"]{justify-content:center;}
.sbc-form-shell[data-align="right"]{justify-content:flex-end;}
.sbc-form-card{
    width:100%;
    max-width:100%;
    box-sizing:border-box;
    background:#fff;
    border-style:solid;
    border-width:0;
    border-color:transparent;
    border-radius:12px;
    padding:24px;
}
.sbc-form-card .sbc-form-title{
    margin:0 0 8px;
    font-family:Inter,sans-serif;
    font-size:26px;
    line-height:1.2;
    font-weight:700;
    color:#111827;
}
.sbc-form-card .sbc-form-description{
    margin:0 0 20px;
    font-family:Inter,sans-serif;
    font-size:14px;
    line-height:1.6;
    color:#6b7280;
}
.sbc-form-grid{
    display:grid;
    gap:14px;
}
.sbc-form-field{
    display:flex;
    flex-direction:column;
    gap:6px;
}
.sbc-form-field label{
    font-family:Inter,sans-serif;
    font-size:13px;
    line-height:1.3;
    font-weight:600;
    color:var(--form-label-color,#374151);
}
.sbc-form-field input,
.sbc-form-field textarea,
.sbc-form-field select{
    width:100%;
    box-sizing:border-box;
    font-family:Inter,sans-serif;
    font-size:14px;
    color:var(--form-text-color,#111827);
    background:var(--form-input-bg,#fff);
    outline:none;
    transition:border-color .2s,box-shadow .2s,background .2s;
}
.sbc-form-field textarea{resize:vertical;min-height:110px;}
.sbc-form-field input::placeholder,
.sbc-form-field textarea::placeholder{color:var(--form-placeholder-color,#9ca3af);opacity:1;}
.sbc-form-field input:focus,
.sbc-form-field textarea:focus,
.sbc-form-field select:focus{
    border-color:#3b82f6 !important;
    box-shadow:0 0 0 3px rgba(59,130,246,.12);
}
.sbc-form-submit{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    cursor:pointer;
    font-family:Inter,sans-serif;
    font-weight:600;
    transition:transform .2s,box-shadow .2s,opacity .2s;
}
.sbc-form-submit:hover{opacity:.95;}

/* =========================================================
   WIDGETS DO EDITOR
   ========================================================= */

/* Estrutura geral dos elementos */
.el-section,
.el-column,
.el-container,
.el-heading,
.el-text,
.el-image,
.el-button,
.el-video,
.el-icon,
.el-divider,
.el-spacer,
.el-form,
.el-faq,
.el-testimonials,
.el-pricing,
.el-counters,
.el-features,
.el-carousel,
.el-gallery,
.el-team,
.el-timeline,
.el-tabs,
.el-accordion,
.el-progress,
.el-newsletter,
.el-hero,
.el-navbar,
.el-footer,
.el-logo {
    position: relative;
    transition: all 0.2s;
}

/* Moldura de edição */
.el-section,
.el-column,
.el-container,
.el-heading,
.el-text,
.el-image,
.el-button,
.el-video,
.el-icon,
.el-divider,
.el-spacer,
.el-form,
.el-faq,
.el-testimonials,
.el-pricing,
.el-counters,
.el-features,
.el-carousel,
.el-gallery,
.el-team,
.el-timeline,
.el-tabs,
.el-accordion,
.el-progress,
.el-newsletter,
.el-hero,
.el-navbar,
.el-footer,
.el-logo {
    border: 2px dashed #bbb;
    margin-bottom: 8px;
    background: rgba(250, 250, 250, 0.5);
    padding: 10px;
    box-sizing: border-box;
}

/* =========================================================
   CONTEÚDO INTERNO
   NÃO colocar moldura/padding de edição aqui
   ========================================================= */

.el-content {
    position: static !important;
    width: 100%;
    min-width: 0;
    margin: 0 !important;
    padding: 0 !important;
    border: 0 !important;
    background: transparent !important;
    box-sizing: border-box;
}

/* =========================================================
   DIVISOR
   ========================================================= */

.el-divider {
    width: 100% !important;
    min-width: 0 !important;
    box-sizing: border-box !important;
}

.el-divider > .el-content {
    width: 100% !important;
    min-width: 0 !important;
    padding: 0 !important;
    margin: 0 !important;
    border: 0 !important;
    background: transparent !important;
}

/* Não deixar elementos internos do divisor
   herdarem a aparência dos widgets */
.el-divider > .el-content > div {
    border: 0;
    margin: 0;
    box-sizing: border-box;
}

/* =========================================================
   DEPOIMENTOS — ESTRUTURA VERTICAL E RESPONSIVA
   Cabeçalho sempre acima da grade.
   Os cards nunca ficam estreitos demais: a grade se adapta
   à largura real disponível do widget.
   ========================================================= */

.el-testimonials > .el-content {
    display: block !important;
    width: 100% !important;
    min-width: 0 !important;
    max-width: 100% !important;
    box-sizing: border-box !important;
}

.sbc-testimonials-shell {
    display: flex !important;
    flex-direction: column !important;
    align-items: stretch !important;
    position: relative !important;
    container-type: inline-size;
    width: 100%;
    max-width: 100%;
    min-width: 0;
    box-sizing: border-box;
    clear: both !important;
    float: none !important;
    grid-column: 1 / -1 !important;
}
.sbc-testimonials-shell > header {
    display: block !important;
    flex: 0 0 auto !important;
    position: relative !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0;
    box-sizing: border-box;
    float: none !important;
    clear: both !important;
    float: none !important;
    order: initial !important;
}
.sbc-testimonials-grid {
    width: 100% !important;
    flex: 0 0 auto !important;
    max-width: 100% !important;
    min-width: 0 !important;
    display: grid !important;
    grid-auto-flow: row !important;
    grid-auto-rows: auto !important;
    align-items: stretch !important;
    justify-items: stretch !important;
    box-sizing: border-box !important;
    clear: both !important;
    float: none !important;
    margin: 0 !important;
    padding: 0 !important;
}
.sbc-testimonial-card {
    width: auto !important;
    min-width: 0 !important;
    max-width: none !important;
    box-sizing: border-box !important;
    overflow: hidden;
    word-break: break-word;
    overflow-wrap: anywhere;
    justify-self: stretch !important;
}
.sbc-testimonial-card img {
    max-width: 100%;
}

.sbc-testimonials-grid { width: 100%; }
.sbc-testimonials-grid .sbc-testimonial-card { min-width: 0 !important; }

/* Responsividade baseada na largura REAL do widget/coluna, e não apenas na tela.
   Isso evita cards espremidos quando o Depoimentos estiver dentro de uma coluna estreita. */
@container (max-width: 760px) {
    .sbc-testimonials-shell[data-cols="4"] .sbc-testimonials-grid,
    .sbc-testimonials-shell[data-cols="3"] .sbc-testimonials-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }
}
@container (max-width: 520px) {
    .sbc-testimonials-shell[data-cols="4"] .sbc-testimonials-grid,
    .sbc-testimonials-shell[data-cols="3"] .sbc-testimonials-grid,
    .sbc-testimonials-shell[data-cols="2"] .sbc-testimonials-grid {
        grid-template-columns: 1fr !important;
    }
}
.sbc-testimonial-card:hover { background: var(--testimonial-hover) !important; transform: translateY(calc(var(--testimonial-lift) * -1)); }

.el-spacer {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    flex: 0 0 auto !important;
    align-self: stretch !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
    padding: 0 !important;
    box-sizing: border-box !important;
}

.el-spacer > .el-content {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    padding: 0 !important;
    margin: 0 !important;
    border: 0 !important;
    background: transparent !important;
    box-sizing: border-box !important;
    display: block !important;
}

.el-spacer .sbc-spacer-inner {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    display: block !important;
    box-sizing: border-box !important;
}

/* =========================================================
   HOVER
   ========================================================= */

.el-section:hover,
.el-column:hover,
.el-container:hover,
.el-heading:hover,
.el-text:hover,
.el-image:hover,
.el-button:hover,
.el-video:hover,
.el-icon:hover,
.el-divider:hover,
.el-spacer:hover,
.el-form:hover,
.el-faq:hover,
.el-testimonials:hover,
.el-pricing:hover,
.el-counters:hover,
.el-features:hover,
.el-carousel:hover,
.el-gallery:hover,
.el-team:hover,
.el-timeline:hover,
.el-tabs:hover,
.el-accordion:hover,
.el-progress:hover,
.el-newsletter:hover,
.el-hero:hover,
.el-navbar:hover,
.el-footer:hover,
.el-logo:hover {
    border-color: var(--accent);
    background: rgba(59, 130, 246, 0.03);
}

/* =========================================================
   SELECIONADO
   ========================================================= */

.el-section.selected,
.el-column.selected,
.el-container.selected,
.el-heading.selected,
.el-text.selected,
.el-image.selected,
.el-button.selected,
.el-video.selected,
.el-icon.selected,
.el-divider.selected,
.el-spacer.selected,
.el-form.selected,
.el-faq.selected,
.el-testimonials.selected,
.el-pricing.selected,
.el-counters.selected,
.el-features.selected,
.el-carousel.selected,
.el-gallery.selected,
.el-team.selected,
.el-timeline.selected,
.el-tabs.selected,
.el-accordion.selected,
.el-progress.selected,
.el-newsletter.selected,
.el-hero.selected,
.el-navbar.selected,
.el-footer.selected,
.el-logo.selected {
    border-color: var(--accent);
    border-style: solid;
    box-shadow: 0 0 0 2px var(--accent-glow);
    background: rgba(59, 130, 246, 0.05);
}

/* =========================================================
   BOTÕES DE CONTROLE
   ========================================================= */

.el-delete,
.el-duplicate {
    box-sizing: border-box !important;
    margin: 0 !important;
    padding: 0 !important;
}


/* =========================================================
   PARTES INTERNAS EDITÁVEIS
   ========================================================= */
[data-id] [data-sbc-part] {
    outline: 1px dashed transparent;
    outline-offset: 3px;
    transition: outline-color .12s ease, background-color .12s ease;
}
[data-id] [data-sbc-part]:hover {
    outline-color: rgba(59,130,246,.55);
}
[data-id] [data-sbc-part].sbc-part-selected {
    outline: 2px solid var(--accent) !important;
    outline-offset: 3px !important;
    box-shadow: 0 0 0 3px rgba(59,130,246,.10);
}

.sbc-part-label {
    position: absolute;
    top: -23px;
    left: 0;
    z-index: 9999;
    padding: 3px 7px;
    border-radius: 5px;
    background: var(--accent);
    color: #fff;
    font-size: 9px;
    font-weight: 700;
    line-height: 1;
    pointer-events: none;
    white-space: nowrap;
}

.el-section { padding: 40px 20px; min-height: 100px; width: 100%; box-sizing: border-box; }
.el-column { padding: 15px; min-height: 80px; flex: 1 1 0%; min-width: 0; box-sizing: border-box; }
.el-container { padding: 20px; min-height: 80px; width: 100%; box-sizing: border-box; }

[data-id]:hover {
    border-color: var(--accent);
    background: rgba(59, 130, 246, 0.03);
}

[data-id].selected {
    border-color: var(--accent);
    border-style: solid;
    box-shadow: 0 0 0 2px var(--accent-glow);
    background: rgba(59, 130, 246, 0.05);
}

.dragging {
    opacity: 0.3 !important;
    border: 2px dashed var(--accent) !important;
    background: rgba(59, 130, 246, 0.1) !important;
}

.drag-over {
    border: 3px dashed var(--success) !important;
    background: rgba(16, 185, 129, 0.2) !important;
    box-shadow: inset 0 0 20px rgba(16, 185, 129, 0.3);
}

.columns-container { 
    display: flex; 
    gap: 15px; 
    flex-wrap: wrap; 
    width: 100%; 
}

.el-content { width: 100%; min-width: 0; box-sizing: border-box; display: block; }
.el-divider { width: 100% !important; min-width: 0 !important; max-width: 100% !important; box-sizing: border-box !important; }
.el-divider > .el-content { width: 100% !important; min-width: 0 !important; max-width: 100% !important; padding: 0 !important; margin: 0 !important; border: 0 !important; background: transparent !important; box-sizing: border-box !important; display: block !important; }
.el-divider .sbc-divider { width: 100% !important; min-width: 0 !important; max-width: 100% !important; display: flex !important; flex-direction: row !important; align-items: center !important; box-sizing: border-box !important; }
.el-divider .sbc-divider-line, .el-divider .sbc-divider-side { flex-shrink: 1; box-sizing: border-box; }

.el-heading .el-content, .el-text .el-content { width: 100% !important; display: block !important; text-align: inherit; }
.el-heading .el-content > h2, .el-text .el-content > p { display: block !important; width: 100% !important; max-width: 100% !important; box-sizing: border-box; }
.el-video { display: block !important; min-width: 0 !important; max-width: 100% !important; flex: 0 0 auto !important; box-sizing: border-box; overflow: visible !important; }
.el-video .el-content { display: block !important; width: 100% !important; min-width: 0 !important; max-width: 100% !important; box-sizing: border-box; overflow: visible !important; }
.el-video .sbc-element-align { width: 100% !important; min-width: 0 !important; max-width: 100% !important; box-sizing: border-box; display: block !important; overflow: visible !important; }
.el-video iframe, .el-video video { display: block !important; width: 100% !important; max-width: 100% !important; }

.widgets-container {
    display: flex;
    flex-direction: column;
    gap: 8px;
    width: 100%;
}
.column-drop-placeholder {
    min-height: 80px;
    border: 2px dashed #cbd5e1;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    font-size: 12px;
    pointer-events: none;
    transition: all 0.2s;
}
.el-column.drag-over .column-drop-placeholder {
    border-color: var(--success);
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
}
/* O alvo do arraste é calculado pelo retângulo, não por hit-testing do DOM. */
body.sbc-widget-pointer-dragging .el-column,
body.sbc-widget-pointer-dragging .el-container {
    transition: box-shadow .08s ease, background .08s ease;
}
@media (max-width: 768px) { 
    .columns-container { flex-direction: column; } 
    .el-column { flex: 1 1 100% !important; } 
}

.el-delete, .el-duplicate {
    z-index: 9999 !important;
    pointer-events: auto !important; 
    position: absolute; 
    top: -12px; 
    width: 24px; 
    height: 24px; 
    cursor: pointer; 
    display: none; 
    align-items: center; 
    justify-content: center; 
    font-size: 12px; 
    z-index: 100;
    padding: 0;
    line-height: 1;
    box-shadow: 0 2px 4px rgba(0,0,0,0.3);
    border: 2px solid white;
    border-radius: 50%;
}

.el-delete { 
    right: -12px; 
    background: var(--error); 
    color: white; 
}

.el-duplicate {
    right: 18px;
    background: var(--accent);
    color: white;
}

[data-id]:hover > .el-delete,
[data-id].selected > .el-delete,
[data-id]:hover > .el-duplicate,
[data-id].selected > .el-duplicate { 
    display: flex !important; 
}

.prop-section { margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid var(--border); }
.prop-section:last-child { border-bottom: none; }
.prop-title { font-size: 10px; font-weight: 700; color: var(--text-tertiary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; display: flex; align-items: center; gap: 5px; }
.prop-group { margin-bottom: 10px; }
.sbc-form-field-editor-item.sbc-form-field-dragging{opacity:.55;transform:scale(.99);cursor:grabbing;}
.sbc-form-field-editor-item.sbc-form-field-drop-target{border-color:var(--accent)!important;box-shadow:0 0 0 2px var(--accent-glow);transform:translateY(1px);}
.prop-label { font-size: 10px; font-weight: 600; color: var(--text-tertiary); text-transform: uppercase; margin-bottom: 5px; display: block; }
.prop-input, .prop-select { width: 100%; padding: 8px; background: var(--bg-tertiary); border: 1px solid var(--border); border-radius: 6px; color: var(--text); font-size: 12px; font-family: 'Inter', sans-serif; }
.prop-input:focus, .prop-select:focus { outline: none; border-color: var(--accent); }
.color-row { display: flex; align-items: center; gap: 8px; background: var(--bg-tertiary); padding: 6px; border-radius: 6px; border: 1px solid var(--border); }
.color-row input[type="color"] { width: 32px; height: 32px; border: none; border-radius: 5px; cursor: pointer; background: none; }
.color-row input[type="text"] { flex: 1; background: var(--bg-primary); border: 1px solid var(--border); border-radius: 5px; padding: 6px 8px; color: var(--text); font-size: 11px; font-family: monospace; }

.notification { position: fixed; top: 70px; right: 20px; background: var(--success); color: white; padding: 12px 18px; border-radius: 8px; z-index: 10000; font-size: 13px; animation: slideIn 0.3s; }
.notification.error { background: var(--error); }
.notification.info { background: var(--accent); }
@keyframes sbcDividerPulse { 0%,100% { transform:scale(1); } 50% { transform:scale(1.12); } }
@keyframes sbcDividerBounce { 0%,100% { transform:translateY(0); } 30% { transform:translateY(-7px); } 60% { transform:translateY(0); } 80% { transform:translateY(-3px); } }
@keyframes sbcDividerSpin { from { transform:rotate(0deg); } to { transform:rotate(360deg); } }
@keyframes sbcDividerFloat { 0%,100% { transform:translateY(0); } 50% { transform:translateY(-5px); } }
@keyframes sbcDividerShake { 0%,100% { transform:translateX(0); } 25% { transform:translateX(-4px); } 50% { transform:translateX(4px); } 75% { transform:translateX(-3px); } }
.sbc-divider-icon-animated.sbc-divider-anim-pulse { animation:sbcDividerPulse 1.8s ease-in-out infinite !important; }
.sbc-divider-icon-animated.sbc-divider-anim-bounce { animation:sbcDividerBounce 1.4s ease-in-out infinite !important; }
.sbc-divider-icon-animated.sbc-divider-anim-spin { animation:sbcDividerSpin 2.2s linear infinite !important; }
.sbc-divider-icon-animated.sbc-divider-anim-float { animation:sbcDividerFloat 2s ease-in-out infinite !important; }
.sbc-divider-icon-animated.sbc-divider-anim-shake { animation:sbcDividerShake 1.2s ease-in-out infinite !important; }
@media (prefers-reduced-motion: reduce) {
  .sbc-divider-icon-animated { animation:none !important; }
}
@keyframes slideIn { from { opacity: 0; transform: translateX(100px); } to { opacity: 1; transform: translateX(0); } }

.modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); backdrop-filter: blur(10px); z-index: 2000; justify-content: center; align-items: center; }
.modal.active { display: flex; }
.modal-content { background: var(--bg-secondary); border: 1px solid var(--border); padding: 24px; border-radius: 12px; width: 90%; max-width: 700px; max-height: 80vh; overflow-y: auto; }
.modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--border); }
.close-modal { background: var(--bg-tertiary); border: none; width: 28px; height: 28px; border-radius: 7px; color: var(--text-secondary); cursor: pointer; }
.btn-cancel { background: var(--bg-tertiary); color: var(--text); padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; }
.btn-confirm { background: var(--accent); color: white; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; }

.template-card { background: var(--bg-tertiary); border: 1px solid var(--border); border-radius: 8px; padding: 12px; cursor: pointer; transition: all 0.3s; }
.template-card:hover { border-color: var(--accent); transform: translateY(-2px); }

.auto-save-indicator { position: fixed; bottom: 20px; left: 20px; background: var(--success); color: white; padding: 8px 16px; border-radius: 20px; font-size: 12px; z-index: 1000; display: none; align-items: center; gap: 6px; }
.auto-save-indicator.active { display: flex; }

@media (max-width: 1400px) { .sidebar-left { width: 240px; } .sidebar-right { width: 280px; } }
@media (max-width: 1200px) { .sidebar-right { display: none; } }

/* =========================================================
   PROTEÇÃO FINAL DO DIVISOR
   ========================================================= */

.el-divider .sbc-divider {
    display: flex !important;
    flex-direction: row !important;
    align-items: center !important;
    width: 100% !important;
    min-width: 0 !important;
    max-width: 100% !important;
    height: auto !important;
    min-height: 0 !important;
    padding: 0 !important;
    margin-top: 0 !important;
    margin-right: 0 !important;
    margin-bottom: 0 !important;
    margin-left: 0 !important;
    border: 0 !important;
    background: transparent !important;
    box-sizing: border-box !important;
}

.el-divider .sbc-divider-line {
    display: block !important;
    flex: 0 0 auto !important;
    width: auto;
    min-width: 0;
    padding: 0 !important;
    margin: 0 !important;
    box-sizing: border-box !important;
}

.el-divider .sbc-divider-side {
    display: block !important;
    flex: 1 1 0 !important;
    min-width: 0 !important;
    height: 0 !important;
    padding: 0 !important;
    margin: 0 !important;
    box-sizing: border-box !important;
}

.el-divider .sbc-divider-decoration {
    flex: 0 0 auto !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: auto !important;
    height: auto !important;
    box-sizing: border-box !important;
}

/* O conteúdo interno nunca pode virar flex-column */
.el-divider > .el-content {
    display: block !important;
    flex-direction: initial !important;
    width: 100% !important;
    min-width: 0 !important;
}

.sbc-faq-prop-item.drag-over{border-color:var(--accent)!important;box-shadow:0 0 0 1px var(--accent-glow) inset,0 4px 14px rgba(0,0,0,.08);}
.sbc-faq-question{position:relative;transition:background-color .2s ease,color .2s ease;}
.sbc-faq-question:hover{background:var(--faq-q-hover)!important;}
.sbc-faq-question.is-open{background:var(--faq-q-open)!important;color:var(--faq-q-open-color)!important;}
.sbc-faq-icon{display:inline-flex;align-items:center;justify-content:center;min-width:24px;width:24px;height:24px;flex:0 0 24px;transition:transform .2s ease,color .2s ease;font-weight:700;}
.sbc-faq-answer{overflow:hidden;transition:opacity .2s ease,transform .2s ease,max-height .28s ease;}
.sbc-faq-answer.is-open{opacity:1;transform:translateY(0);}
.sbc-faq-answer.is-closed{opacity:0;transform:translateY(-4px);}
</style>

<style>
@media (max-width: 1024px){
  .sbc-footer-grid{grid-template-columns:repeat(2,minmax(0,1fr)) !important;}
}
@media (max-width: 640px){
  .sbc-footer-grid{grid-template-columns:1fr !important;}
}
</style>

<style>
[data-id].sbc-insert-target {
    outline: 2px solid rgba(16,185,129,.55) !important;
    outline-offset: -2px;
}
</style>

<style id="sbc-responsive-100-final">
.canvas{container-type:inline-size;container-name:pagecanvas;overflow-x:hidden}
.canvas,.canvas *{box-sizing:border-box}
.canvas [data-id],.canvas [data-id]>.el-content{min-width:0!important;max-width:100%}
.canvas [data-id] img,.canvas [data-id] video,.canvas [data-id] iframe,.canvas [data-id] svg,.canvas [data-id] canvas{max-width:100%!important}
.canvas [data-id] h1,.canvas [data-id] h2,.canvas [data-id] h3,.canvas [data-id] h4,.canvas [data-id] h5,.canvas [data-id] h6,.canvas [data-id] p,.canvas [data-id] span,.canvas [data-id] a,.canvas [data-id] li,.canvas [data-id] label,.canvas [data-id] button{overflow-wrap:anywhere;word-break:break-word}
.canvas .columns-container{width:100%!important;max-width:100%!important;min-width:0!important;display:flex!important;flex-wrap:wrap!important}
.canvas .el-section,.canvas .el-column,.canvas .el-container,.canvas .el-content,.canvas .widgets-container{min-width:0!important;max-width:100%!important}
.canvas .sbc-testimonials-shell,.canvas .sbc-pricing-shell,.canvas .sbc-counters-shell,.canvas .sbc-features-shell,.canvas .sbc-team-shell,.canvas .sbc-gallery-shell,.canvas .sbc-timeline-shell,.canvas .sbc-newsletter-shell,.canvas .sbc-carousel-shell,.canvas .sbc-tabs-shell,.canvas .sbc-accordion-shell,.canvas .sbc-form-shell,.canvas .sbc-navbar-shell,.canvas .sbc-hero-shell{container-type:inline-size;container-name:widget;max-width:100%!important;min-width:0!important}
.canvas .sbc-pricing-grid,.canvas .sbc-team-grid,.canvas .sbc-gallery-grid,.canvas .sbc-testimonials-grid{width:100%!important;max-width:100%!important;min-width:0!important}
.canvas .sbc-testimonial-card,.canvas .sbc-pricing-card,.canvas .sbc-feature-card,.canvas .sbc-counter-card,.canvas .sbc-team-card,.canvas .sbc-gallery-item,.canvas .sbc-accordion-item{min-width:0!important;max-width:100%!important;overflow-wrap:anywhere;word-break:break-word}
.canvas button,.canvas .nav-btn,.canvas .sbc-form-submit{max-width:100%}
.canvas .el-button .editor-button{max-width:100%!important;white-space:normal!important;overflow-wrap:anywhere}

@container pagecanvas (max-width:900px){
 .sbc-pricing-grid,.sbc-team-grid,.sbc-gallery-grid{grid-template-columns:repeat(2,minmax(0,1fr))!important}
 .sbc-features-shell>div,.sbc-counters-shell>div{grid-template-columns:repeat(2,minmax(0,1fr))!important}
 .sbc-form-grid{grid-template-columns:repeat(2,minmax(0,1fr))!important}
 .sbc-hero-shell>div:not([aria-hidden="true"]){width:min(100%,92%)!important;max-width:100%!important}
}
@container pagecanvas (max-width:600px){
 .sbc-pricing-grid,.sbc-team-grid,.sbc-gallery-grid,.sbc-features-shell>div,.sbc-counters-shell>div,.sbc-form-grid{grid-template-columns:1fr!important}
 .sbc-pricing-card,.sbc-team-card,.sbc-feature-card,.sbc-counter-card,.sbc-testimonial-card,.sbc-gallery-item{width:100%!important;min-width:0!important;max-width:100%!important}
 .sbc-hero-shell{padding-left:clamp(16px,5vw,30px)!important;padding-right:clamp(16px,5vw,30px)!important}
 .sbc-hero-shell>div:not([aria-hidden="true"]){width:100%!important;max-width:100%!important}
 .sbc-hero-shell h1{font-size:clamp(24px,8vw,42px)!important;line-height:1.08!important}
 .sbc-hero-shell p{font-size:clamp(14px,4vw,18px)!important;line-height:1.5!important}
 .sbc-navbar-shell img{max-width:min(240px,55vw)!important;height:auto!important}
 .sbc-tabs-shell>div:first-child{width:100%!important;overflow-x:auto!important;flex-wrap:nowrap!important}
 .sbc-tabs-shell>div:first-child>button{flex:0 0 auto!important;white-space:nowrap!important}
 .sbc-carousel-shell{overflow:hidden!important}
 .sbc-carousel-shell .sbc-carousel-viewport,.sbc-carousel-shell .sbc-carousel-track,.sbc-carousel-shell .sbc-carousel-slide{min-width:0!important;max-width:100%!important}
 .sbc-carousel-shell .sbc-carousel-slide>div:last-child{padding:16px!important}
 .el-heading .el-content>h1,.el-heading .el-content>h2,.el-heading .el-content>h3{font-size:clamp(22px,7vw,34px)!important;line-height:1.12!important}
 .el-text .el-content>p{font-size:clamp(14px,4vw,18px)!important;line-height:1.55!important}
 .sbc-form-card{padding:clamp(14px,4vw,24px)!important}
 .sbc-form-title{font-size:clamp(20px,6vw,30px)!important}
 .sbc-form-description{font-size:clamp(13px,3.7vw,16px)!important}
}

.canvas.tablet{width:min(768px,100%)!important;max-width:768px!important}
.canvas.mobile{width:min(375px,100%)!important;max-width:375px!important;padding-left:12px!important;padding-right:12px!important}
.canvas.mobile [data-id],.canvas.tablet [data-id]{max-width:100%!important;min-width:0!important}
.canvas.mobile .el-column{flex:1 1 100%!important;width:100%!important;max-width:100%!important}
.canvas.mobile .el-section,.canvas.mobile .el-column,.canvas.mobile .el-container{padding-left:clamp(8px,4vw,20px)!important;padding-right:clamp(8px,4vw,20px)!important}
.canvas [data-id]{overflow-x:clip}

/* =========================================================
   RESPONSIVIDADE REAL V2
   Baseada no modo do editor/preview e na largura disponível.
   Não altera os dados dos widgets; apenas reorganiza a apresentação.
   ========================================================= */

/* Regra estrutural: nenhum widget pode criar largura mínima maior
   que a coluna/viewport onde está inserido. */
.canvas,
.preview-content .canvas {
    min-width: 0 !important;
    width: 100% !important;
}

.canvas *,
.preview-content .canvas * {
    box-sizing: border-box;
    min-width: 0;
    max-width: 100%;
}

.canvas img,
.canvas video,
.canvas iframe,
.canvas svg,
.preview-content img,
.preview-content video,
.preview-content iframe,
.preview-content svg {
    max-width: 100%;
}

/* Textos longos nunca devem escapar horizontalmente. */
.canvas h1,
.canvas h2,
.canvas h3,
.canvas h4,
.canvas h5,
.canvas h6,
.canvas p,
.canvas span,
.canvas a,
.preview-content h1,
.preview-content h2,
.preview-content h3,
.preview-content h4,
.preview-content h5,
.preview-content h6,
.preview-content p,
.preview-content span,
.preview-content a {
    overflow-wrap: anywhere;
}

/* Tablet: grids passam para 2 colunas. */
:where(.canvas.tablet, .preview-frame.preview-tablet) .sbc-features-shell > div[style*="display:grid"],
:where(.canvas.tablet, .preview-frame.preview-tablet) .sbc-counters-shell > div[style*="display:grid"],
:where(.canvas.tablet, .preview-frame.preview-tablet) .sbc-pricing-grid,
:where(.canvas.tablet, .preview-frame.preview-tablet) .sbc-team-grid,
:where(.canvas.tablet, .preview-frame.preview-tablet) .sbc-gallery-grid,
:where(.canvas.tablet, .preview-frame.preview-tablet) .sbc-testimonials-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
}

/* Mobile: absolutamente um card por linha. */
:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-features-shell > div[style*="display:grid"],
:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-counters-shell > div[style*="display:grid"],
:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-pricing-grid,
:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-team-grid,
:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-gallery-grid,
:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-testimonials-grid {
    grid-template-columns: 1fr !important;
}

/* Formulário */
:where(.canvas.tablet, .preview-frame.preview-tablet) .sbc-form-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
}

:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-form-grid {
    grid-template-columns: 1fr !important;
}

/* Timeline horizontal. */
:where(.canvas.tablet, .preview-frame.preview-tablet) .sbc-timeline-shell > div[style*="grid-template-columns"] {
    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
}

:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-timeline-shell > div[style*="grid-template-columns"] {
    grid-template-columns: 1fr !important;
}

:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-timeline-shell article[style*="grid-template-columns"] {
    display: block !important;
}

:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-timeline-shell article[style*="grid-template-columns"] > div {
    grid-column: auto !important;
    grid-row: auto !important;
    width: 100% !important;
    max-width: 100% !important;
    text-align: left !important;
}

:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-timeline-shell article[style*="grid-template-columns"] > div:nth-child(2) {
    display: none !important;
}

/* Tabs: os botões não esmagam o texto; o cabeçalho pode deslizar horizontalmente. */
:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-tabs-shell > div:first-child {
    flex-wrap: nowrap !important;
    overflow-x: auto !important;
    overflow-y: hidden !important;
    width: 100% !important;
    max-width: 100% !important;
    padding-bottom: 4px !important;
}

:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-tabs-shell > div:first-child > button {
    flex: 0 0 auto !important;
    max-width: 85vw !important;
    white-space: normal !important;
}

/* Hero */
:where(.canvas.tablet, .preview-frame.preview-tablet) .sbc-hero-shell h1 {
    font-size: 40px !important;
}

:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-hero-shell {
    min-height: 320px !important;
    padding: 42px 18px !important;
}

:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-hero-shell h1 {
    font-size: 30px !important;
    line-height: 1.12 !important;
    letter-spacing: -.015em !important;
}

:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-hero-shell p {
    font-size: 15px !important;
    line-height: 1.5 !important;
    margin-bottom: 20px !important;
}

:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-hero-shell a {
    max-width: 100% !important;
    white-space: normal !important;
}

/* Título e Texto */
:where(.canvas.tablet, .preview-frame.preview-tablet) .el-heading .el-content > h2 {
    font-size: clamp(24px, 4vw, 36px) !important;
}

:where(.canvas.mobile, .preview-frame.preview-mobile) .el-heading .el-content > h2 {
    font-size: 28px !important;
    line-height: 1.18 !important;
}

:where(.canvas.mobile, .preview-frame.preview-mobile) .el-text .el-content > p {
    font-size: 15px !important;
    line-height: 1.55 !important;
}

/* Botões: nunca ultrapassam a largura disponível. */
:where(.canvas.mobile, .preview-frame.preview-mobile) .el-button,
:where(.canvas.mobile, .preview-frame.preview-mobile) .el-button .sbc-button-align,
:where(.canvas.mobile, .preview-frame.preview-mobile) .el-button .editor-button {
    max-width: 100% !important;
    min-width: 0 !important;
}

:where(.canvas.mobile, .preview-frame.preview-mobile) .el-button .editor-button {
    white-space: normal !important;
    text-align: center !important;
    overflow-wrap: anywhere !important;
}

/* Navbar */
:where(.canvas.tablet, .preview-frame.preview-tablet) .sbc-navbar-shell,
:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-navbar-shell {
    width: 100% !important;
    max-width: 100% !important;
}

:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-navbar-shell > div:first-child {
    gap: 8px !important;
    padding-left: 12px !important;
    padding-right: 12px !important;
}

/* Imagens */
:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-image-wrap,
:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-image-link,
:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-editor-image {
    max-width: 100% !important;
}

:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-editor-image {
    width: auto !important;
}

/* Carousel */
/* A altura vem do próprio widget. O valor configurado no painel não deve ser
   substituído por um 260px fixo no mobile. */
:where(.canvas.tablet, .preview-frame.preview-tablet, .canvas.mobile, .preview-frame.preview-mobile) .sbc-carousel-slide {
    min-width: 0 !important;
}


:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-carousel-slide > div:last-child {
    padding: 18px !important;
}

:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-carousel-slide > div:last-child > div {
    max-width: 100% !important;
}

:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-carousel-slide > div:last-child > div > div:first-child {
    font-size: 24px !important;
}

:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-carousel-slide > div:last-child > div > div:nth-child(2) {
    font-size: 14px !important;
}

/* Galeria / cards / pricing: conteúdo interno jamais pode forçar a coluna. */
:where(.canvas.mobile, .preview-frame.preview-mobile)
.sbc-gallery-item,
:where(.canvas.mobile, .preview-frame.preview-mobile)
.sbc-pricing-card,
:where(.canvas.mobile, .preview-frame.preview-mobile)
.sbc-feature-card,
:where(.canvas.mobile, .preview-frame.preview-mobile)
.sbc-counter-card,
:where(.canvas.mobile, .preview-frame.preview-mobile)
.sbc-team-card,
:where(.canvas.mobile, .preview-frame.preview-mobile)
.sbc-testimonial-card {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
}

/* Espaçamentos externos em telas pequenas. */
:where(.canvas.tablet, .preview-frame.preview-tablet) .el-section {
    padding-left: 18px !important;
    padding-right: 18px !important;
}

:where(.canvas.mobile, .preview-frame.preview-mobile) .el-section {
    padding: 24px 14px !important;
}

:where(.canvas.mobile, .preview-frame.preview-mobile) .el-column {
    padding: 10px !important;
}

:where(.canvas.mobile, .preview-frame.preview-mobile) .el-container {
    padding: 12px !important;
}

/* Overflow horizontal controlado: nenhum widget cria uma página lateral. */
.canvas,
.preview-stage,
.preview-frame,
.preview-content {
    overflow-x: hidden;
}

:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-form-card,
:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-newsletter-shell,
:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-navbar-shell,
:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-hero-shell,
:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-features-shell,
:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-counters-shell,
:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-pricing-shell,
:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-team-shell,
:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-timeline-shell,
:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-tabs-shell,
:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-gallery-shell,
:where(.canvas.mobile, .preview-frame.preview-mobile) .sbc-carousel-shell {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
}

/* Preview: as próprias áreas de viewport acompanham o dispositivo. */
.preview-frame.preview-mobile {
    width: min(375px, calc(100vw - 32px)) !important;
}

.preview-frame.preview-tablet {
    width: min(768px, calc(100vw - 32px)) !important;
}

</style>

<style>

/* =========================================================
   RESPONSIVIDADE FLUIDA GLOBAL
   Tudo se adapta à largura REAL disponível.
   ========================================================= */

/* Base anti-estouro */
html, body {
    max-width: 100%;
    overflow-x: hidden !important;
}

.canvas-area,
.canvas,
.el-section,
.el-column,
.el-container,
.el-content,
[data-id] {
    max-width: 100% !important;
    min-width: 0 !important;
    box-sizing: border-box !important;
}

/* Estruturas */
.canvas {
    overflow-x: hidden !important;
}

.columns-container {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    display: flex !important;
    flex-wrap: wrap !important;
}

.el-column {
    flex: 1 1 0 !important;
    width: 0 !important;
}

/* Conteúdo textual nunca força largura */
.el-heading .el-content,
.el-text .el-content,
.el-heading h1,
.el-heading h2,
.el-heading h3,
.el-heading h4,
.el-heading h5,
.el-heading h6,
.el-text p,
.el-text div,
.sbc-hero-shell h1,
.sbc-hero-shell p {
    max-width: 100% !important;
    min-width: 0 !important;
    overflow-wrap: anywhere !important;
    word-break: normal !important;
}

/* Imagens e mídia */
img,
video,
iframe,
canvas,
svg {
    max-width: 100% !important;
}

.el-image img,
.sbc-image-wrap img,
.el-video iframe,
.el-video video {
    width: 100%;
    height: auto;
    max-width: 100% !important;
}

/* Botões */
.el-button,
.el-button .sbc-button-align,
.el-button .editor-button,
.sbc-form-submit {
    max-width: 100% !important;
    box-sizing: border-box !important;
}

.el-button .editor-button {
    overflow-wrap: anywhere !important;
}

/* Todos os grids internos dos widgets:
   usam minmax(0, 1fr), evitando cards esmagados */
.sbc-features-grid,
.sbc-counters-grid,
.sbc-pricing-grid,
.sbc-team-grid,
.sbc-gallery-grid,
.sbc-testimonials-grid,
.sbc-footer-grid,
.sbc-form-grid {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    box-sizing: border-box !important;
}

.sbc-features-grid > *,
.sbc-counters-grid > *,
.sbc-pricing-grid > *,
.sbc-team-grid > *,
.sbc-gallery-grid > *,
.sbc-testimonials-grid > *,
.sbc-footer-grid > *,
.sbc-form-grid > * {
    min-width: 0 !important;
    max-width: 100% !important;
    box-sizing: border-box !important;
    overflow-wrap: anywhere !important;
}

/* Recursos / cards gerais */
.sbc-feature-card,
.sbc-counter-card,
.sbc-pricing-card,
.sbc-team-card,
.sbc-testimonial-card,
.sbc-gallery-item {
    min-width: 0 !important;
    max-width: 100% !important;
    box-sizing: border-box !important;
}

/* Carrossel */
.sbc-carousel-shell,
.sbc-carousel-viewport,
.sbc-carousel-track {
    max-width: 100% !important;
    min-width: 0 !important;
    box-sizing: border-box !important;
}

.sbc-carousel-shell {
    overflow: hidden !important;
}

/* Tabs: se não couber, rola horizontalmente sem quebrar o layout */
.sbc-tabs-shell > div:first-child {
    max-width: 100% !important;
    overflow-x: auto !important;
    overflow-y: hidden !important;
    flex-wrap: nowrap !important;
    scrollbar-width: thin;
}

.sbc-tabs-shell > div:first-child > button {
    flex: 0 0 auto !important;
    white-space: nowrap !important;
}

/* Newsletter */
.sbc-newsletter-shell > div,
.sbc-newsletter-shell form {
    max-width: 100% !important;
    min-width: 0 !important;
    box-sizing: border-box !important;
}

/* Acordion / FAQ */
.sbc-accordion-shell,
.sbc-accordion-list,
.sbc-faq-shell,
.sbc-faq-list {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    box-sizing: border-box !important;
}

.sbc-accordion-question,
.sbc-faq-question,
.sbc-accordion-answer,
.sbc-faq-answer {
    max-width: 100% !important;
    min-width: 0 !important;
    overflow-wrap: anywhere !important;
}

/* Hero: escala fluida */
.sbc-hero-shell h1 {
    font-size: clamp(24px, 5vw, 64px) !important;
}

.sbc-hero-shell p {
    font-size: clamp(13px, 1.8vw, 20px) !important;
}

/* Navbar */
.sbc-navbar-shell,
.sbc-navbar-shell > div,
.sbc-navbar-desktop-links,
.sbc-navbar-mobile-panel {
    max-width: 100% !important;
    min-width: 0 !important;
    box-sizing: border-box !important;
}

.sbc-navbar-desktop-links {
    flex-wrap: wrap !important;
}

.sbc-navbar-link {
    max-width: 100% !important;
    overflow-wrap: anywhere !important;
}

/* Formulário: qualquer campo pode encolher */
.sbc-form-shell,
.sbc-form-card,
.sbc-form-grid,
.sbc-form-field {
    min-width: 0 !important;
    max-width: 100% !important;
    box-sizing: border-box !important;
}

/* Linha de formulário que originalmente era horizontal */
.sbc-form-card .sbc-form-submit,
.sbc-form-card input,
.sbc-form-card select,
.sbc-form-card textarea {
    max-width: 100% !important;
}

/* =========================================================
   TABLET
   ========================================================= */
@media (max-width: 1024px) {

    .canvas {
        padding: 32px 16px !important;
    }

    .el-section {
        padding: 28px 16px !important;
    }

    .el-column {
        padding: 12px !important;
    }

    .el-container {
        padding: 16px !important;
    }

    /* Qualquer grid de cards */
    .sbc-features-grid,
    .sbc-counters-grid,
    .sbc-pricing-grid,
    .sbc-team-grid,
    .sbc-testimonials-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }

    .sbc-gallery-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }

    /* Recursos */
    .sbc-features-grid {
        gap: 16px !important;
    }

    .sbc-feature-card {
        padding: 18px !important;
    }

    /* Tipografia */
    .sbc-feature-card > div,
    .sbc-counter-card > div,
    .sbc-team-card > div {
        max-width: 100% !important;
    }

    /* Newsletter e linhas horizontais */
    .sbc-newsletter-shell form > div,
    .sbc-newsletter-shell .sbc-newsletter-form-row {
        flex-wrap: wrap !important;
    }
}

/* =========================================================
   MOBILE
   ========================================================= */
@media (max-width: 640px) {

    .canvas {
        padding: 20px 10px !important;
    }

    .el-section {
        padding: 22px 10px !important;
    }

    .el-column {
        padding: 10px !important;
        flex-basis: 100% !important;
        width: 100% !important;
    }

    .el-container {
        padding: 12px !important;
    }

    /* Regra principal:
       o layout usa a largura disponível, não uma largura fixa */
    .sbc-features-grid,
    .sbc-counters-grid,
    .sbc-pricing-grid,
    .sbc-team-grid,
    .sbc-gallery-grid,
    .sbc-testimonials-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 10px !important;
    }

    /* Quando a área real ficar muito estreita,
       automaticamente cai para 1 coluna */
    /* faixa intermediária */
}

@media (max-width: 430px) {

        .sbc-features-grid,
        .sbc-counters-grid,
        .sbc-pricing-grid,
        .sbc-team-grid,
        .sbc-gallery-grid,
        .sbc-testimonials-grid {
            grid-template-columns: 1fr !important;
    }

/* Recursos */
    .sbc-feature-card {
        padding: 14px !important;
        border-radius: 12px !important;
    }

    .sbc-feature-icon-box {
        width: 56px !important;
        height: 56px !important;
        max-width: 56px !important;
    }

    .sbc-feature-card .feature-icon-anim {
        font-size: 22px !important;
    }

    .sbc-feature-card > div:nth-last-child(2) {
        font-size: clamp(12px, 3.8vw, 17px) !important;
        line-height: 1.25 !important;
    }

    .sbc-feature-card > div:last-child {
        font-size: clamp(10px, 3.2vw, 14px) !important;
        line-height: 1.45 !important;
    }

    /* Títulos de seção */
    .sbc-features-shell > header > div:first-child,
    .sbc-counters-shell > header > div:first-child,
    .sbc-team-shell > header > div:first-child {
        font-size: clamp(20px, 6vw, 30px) !important;
    }

    .sbc-features-shell > header > div:last-child,
    .sbc-counters-shell > header > div:last-child,
    .sbc-team-shell > header > div:last-child {
        font-size: clamp(12px, 3.5vw, 15px) !important;
    }

    /* Preços */
    .sbc-pricing-card {
        padding: 16px !important;
    }

    /* Galeria */
    .sbc-gallery-grid {
        grid-auto-rows: auto !important;
    }

    .sbc-gallery-item img {
        height: auto !important;
        aspect-ratio: 4 / 3;
        object-fit: cover !important;
    }

    /* Timeline: remove a estrutura de 3 colunas */
    .sbc-timeline-shell article {
        grid-template-columns: 42px minmax(0, 1fr) !important;
        gap: 10px !important;
    }

    .sbc-timeline-shell article > div:first-child,
    .sbc-timeline-shell article > div:last-child {
        grid-column: 2 !important;
        grid-row: 1 !important;
        text-align: left !important;
    }

    .sbc-timeline-shell article > div:nth-child(2) {
        grid-column: 1 !important;
        grid-row: 1 !important;
        justify-self: start !important;
    }

    .sbc-timeline-shell > div > div[aria-hidden="true"] {
        left: 20px !important;
        transform: none !important;
    }

    /* Newsletter: empilha campo e botão */
    .sbc-newsletter-shell form > div,
    .sbc-newsletter-form-row {
        flex-direction: column !important;
        width: 100% !important;
    }

    .sbc-newsletter-shell form > div > *,
    .sbc-newsletter-form-row > * {
        width: 100% !important;
        max-width: 100% !important;
        flex: 1 1 100% !important;
    }

    /* Tabs */
    .sbc-tabs-shell > div:first-child {
        gap: 6px !important;
    }

    /* Navbar */
    .sbc-navbar-shell > div:first-child {
        gap: 8px !important;
        padding-left: 10px !important;
        padding-right: 10px !important;
    }
}

/* Faixa ultra-estreita */
@media (max-width: 360px) {
    .sbc-features-grid,
    .sbc-counters-grid,
    .sbc-pricing-grid,
    .sbc-team-grid,
    .sbc-gallery-grid,
    .sbc-testimonials-grid {
        grid-template-columns: 1fr !important;
    }

    .el-section {
        padding-left: 8px !important;
        padding-right: 8px !important;
    }

    .el-column {
        padding-left: 8px !important;
        padding-right: 8px !important;
    }
}

</style>

<style id="sbc-responsive-v4-final">
/* =========================================================
   SBC RESPONSIVIDADE V4 — MOBILE REAL
   Esta camada fica POR ÚLTIMO para vencer regras antigas.
   Não altera os valores salvos; somente a apresentação mobile.
   ========================================================= */

/* O canvas do editor/preview nunca cria uma largura maior que a tela. */
.canvas.mobile,
.preview-frame.preview-mobile .canvas {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
    padding-left: 10px !important;
    padding-right: 10px !important;
    overflow-x: hidden !important;
}

/* Toda estrutura principal ocupa somente o espaço realmente disponível. */
.canvas.mobile .el-section,
.canvas.mobile .el-column,
.canvas.mobile .el-container,
.canvas.mobile .el-content,
.canvas.mobile .widgets-container,
.preview-frame.preview-mobile .el-section,
.preview-frame.preview-mobile .el-column,
.preview-frame.preview-mobile .el-container,
.preview-frame.preview-mobile .el-content,
.preview-frame.preview-mobile .widgets-container {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    box-sizing: border-box !important;
}

/* MOBILE: qualquer grid passa a uma coluna.
   Inclui grids gerados com style="grid-template-columns:...". */
.canvas.mobile [style*="grid-template-columns"],
.preview-frame.preview-mobile [style*="grid-template-columns"],
.canvas.mobile .sbc-features-grid,
.canvas.mobile .sbc-counters-grid,
.canvas.mobile .sbc-pricing-grid,
.canvas.mobile .sbc-team-grid,
.canvas.mobile .sbc-gallery-grid,
.canvas.mobile .sbc-testimonials-grid,
.preview-frame.preview-mobile .sbc-features-grid,
.preview-frame.preview-mobile .sbc-counters-grid,
.preview-frame.preview-mobile .sbc-pricing-grid,
.preview-frame.preview-mobile .sbc-team-grid,
.preview-frame.preview-mobile .sbc-gallery-grid,
.preview-frame.preview-mobile .sbc-testimonials-grid {
    grid-template-columns: minmax(0, 1fr) !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
}

/* Cada card ocupa toda a largura disponível. */
.canvas.mobile .sbc-feature-card,
.canvas.mobile .sbc-counter-card,
.canvas.mobile .sbc-pricing-card,
.canvas.mobile .sbc-team-card,
.canvas.mobile .sbc-gallery-item,
.canvas.mobile .sbc-testimonial-card,
.canvas.mobile .sbc-accordion-item,
.preview-frame.preview-mobile .sbc-feature-card,
.preview-frame.preview-mobile .sbc-counter-card,
.preview-frame.preview-mobile .sbc-pricing-card,
.preview-frame.preview-mobile .sbc-team-card,
.preview-frame.preview-mobile .sbc-gallery-item,
.preview-frame.preview-mobile .sbc-testimonial-card,
.preview-frame.preview-mobile .sbc-accordion-item {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    box-sizing: border-box !important;
}

/* Corrige especificamente o widget que estava ficando esmagado. */
.canvas.mobile .sbc-features-grid,
.preview-frame.preview-mobile .sbc-features-grid {
    grid-template-columns: 1fr !important;
    gap: 12px !important;
}

.canvas.mobile .sbc-features-grid .sbc-feature-card,
.preview-frame.preview-mobile .sbc-features-grid .sbc-feature-card {
    padding: 14px !important;
    text-align: left !important;
}

.canvas.mobile .sbc-features-grid .sbc-feature-icon-box,
.preview-frame.preview-mobile .sbc-features-grid .sbc-feature-icon-box {
    width: 52px !important;
    height: 52px !important;
    margin: 0 0 10px !important;
}

.canvas.mobile .sbc-features-grid .sbc-feature-icon-box i,
.preview-frame.preview-mobile .sbc-features-grid .sbc-feature-icon-box i {
    font-size: 21px !important;
}

.canvas.mobile .sbc-features-grid .sbc-feature-card > div:nth-child(2),
.preview-frame.preview-mobile .sbc-features-grid .sbc-feature-card > div:nth-child(2) {
    font-size: clamp(14px, 4.4vw, 17px) !important;
    line-height: 1.25 !important;
    white-space: normal !important;
    overflow-wrap: anywhere !important;
}

.canvas.mobile .sbc-features-grid .sbc-feature-card > div:nth-child(3),
.preview-frame.preview-mobile .sbc-features-grid .sbc-feature-card > div:nth-child(3) {
    font-size: clamp(11px, 3.5vw, 14px) !important;
    line-height: 1.5 !important;
    white-space: normal !important;
    overflow-wrap: anywhere !important;
}

/* Elementos individuais: nunca podem manter uma largura fixa maior que a coluna. */
.canvas.mobile [data-id],
.preview-frame.preview-mobile [data-id] {
    max-width: 100% !important;
    min-width: 0 !important;
    box-sizing: border-box !important;
    overflow-wrap: anywhere !important;
}

.canvas.mobile [data-id] > .el-content,
.preview-frame.preview-mobile [data-id] > .el-content {
    max-width: 100% !important;
    min-width: 0 !important;
    box-sizing: border-box !important;
}

/* Imagens, vídeos e embeds se ajustam proporcionalmente. */
.canvas.mobile img,
.canvas.mobile video,
.canvas.mobile iframe,
.canvas.mobile svg,
.canvas.mobile canvas,
.preview-frame.preview-mobile img,
.preview-frame.preview-mobile video,
.preview-frame.preview-mobile iframe,
.preview-frame.preview-mobile svg,
.preview-frame.preview-mobile canvas {
    max-width: 100% !important;
    height: auto !important;
}

/* Títulos e textos diminuem progressivamente sem quebrar palavras de forma estranha. */
.canvas.mobile h1,
.preview-frame.preview-mobile h1 {
    font-size: clamp(22px, 7vw, 32px) !important;
    line-height: 1.12 !important;
}
.canvas.mobile h2,
.preview-frame.preview-mobile h2 {
    font-size: clamp(20px, 6vw, 28px) !important;
    line-height: 1.16 !important;
}
.canvas.mobile h3,
.preview-frame.preview-mobile h3 {
    font-size: clamp(18px, 5.2vw, 24px) !important;
    line-height: 1.2 !important;
}
.canvas.mobile p,
.canvas.mobile li,
.preview-frame.preview-mobile p,
.preview-frame.preview-mobile li {
    font-size: clamp(12px, 3.8vw, 16px) !important;
    line-height: 1.5 !important;
}

/* Botões: podem quebrar em duas linhas, mas nunca sair da tela. */
.canvas.mobile button,
.canvas.mobile .editor-button,
.canvas.mobile .nav-btn,
.preview-frame.preview-mobile button,
.preview-frame.preview-mobile .editor-button,
.preview-frame.preview-mobile .nav-btn {
    max-width: 100% !important;
    min-width: 0 !important;
    white-space: normal !important;
    overflow-wrap: anywhere !important;
    box-sizing: border-box !important;
}

/* Containers flexíveis não podem criar uma segunda página horizontal. */
.canvas.mobile .columns-container,
.canvas.mobile .widgets-container,
.preview-frame.preview-mobile .columns-container,
.preview-frame.preview-mobile .widgets-container {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    box-sizing: border-box !important;
    overflow-x: hidden !important;
}

/* Formulários: um campo por linha. */
.canvas.mobile .sbc-form-grid,
.preview-frame.preview-mobile .sbc-form-grid {
    grid-template-columns: 1fr !important;
    width: 100% !important;
}

/* No Mobile, a timeline deixa de tentar manter duas metades lado a lado. */
.canvas.mobile .sbc-timeline-shell article,
.preview-frame.preview-mobile .sbc-timeline-shell article {
    display: block !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
}

/* Accordion / tabs / navbar */
.canvas.mobile .sbc-tabs-shell,
.canvas.mobile .sbc-navbar-shell,
.canvas.mobile .sbc-accordion-shell,
.preview-frame.preview-mobile .sbc-tabs-shell,
.preview-frame.preview-mobile .sbc-navbar-shell,
.preview-frame.preview-mobile .sbc-accordion-shell {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
}

/* Segurança final contra overflow causado por estilos inline internos. */
.canvas.mobile * ,
.preview-frame.preview-mobile * {
    box-sizing: border-box !important;
    min-width: 0;
}

@media (max-width: 390px) {
    .canvas.mobile,
    .preview-frame.preview-mobile .canvas {
        padding-left: 7px !important;
        padding-right: 7px !important;
    }

    .canvas.mobile .el-section,
    .preview-frame.preview-mobile .el-section {
        padding-left: 9px !important;
        padding-right: 9px !important;
    }

    .canvas.mobile .el-column,
    .preview-frame.preview-mobile .el-column {
        padding-left: 7px !important;
        padding-right: 7px !important;
    }
}
</style>

<style id="sbc-responsive-final-fix">
/* Responsividade final: baseada no modo do canvas, não na largura da janela do editor. */
.canvas.sbc-responsive-desktop { width:100%; max-width:100%; min-width:0; }

.canvas.sbc-responsive-tablet,
.canvas.sbc-responsive-mobile { width:100%; max-width:100%; min-width:0; overflow-x:hidden; }

.canvas.sbc-responsive-tablet .columns-container {
    display:flex !important; flex-direction:row !important; flex-wrap:wrap !important;
    width:100% !important; max-width:100% !important; min-width:0 !important; gap:12px !important;
}
.canvas.sbc-responsive-tablet .el-column {
    flex:1 1 0 !important; width:auto !important; max-width:100% !important; min-width:0 !important;
}
.canvas.sbc-responsive-tablet .widgets-container {
    display:flex !important; flex-direction:column !important; align-items:stretch !important;
    width:100% !important; max-width:100% !important; min-width:0 !important;
}
.canvas.sbc-responsive-tablet [data-id]:not(.el-section):not(.el-column):not(.el-container),
.canvas.sbc-responsive-mobile [data-id]:not(.el-section):not(.el-column):not(.el-container) {
    max-width:100% !important; min-width:0 !important; box-sizing:border-box !important;
}

/* Mobile: uma coluna por linha e um widget por largura disponível. */
.canvas.sbc-responsive-mobile .columns-container {
    display:flex !important; flex-direction:column !important; flex-wrap:nowrap !important;
    width:100% !important; max-width:100% !important; min-width:0 !important; gap:0 !important;
}
.canvas.sbc-responsive-mobile .el-column {
    flex:0 0 100% !important; width:100% !important; max-width:100% !important; min-width:0 !important;
}
.canvas.sbc-responsive-mobile .widgets-container {
    display:flex !important; flex-direction:column !important; align-items:stretch !important;
    width:100% !important; max-width:100% !important; min-width:0 !important; gap:10px !important;
}
.canvas.sbc-responsive-mobile [data-id]:not(.el-section):not(.el-column):not(.el-container) { width:100% !important; }

/* Preview usa o mesmo motor, mas nunca herda o modo atual do editor. */
.preview-frame.preview-tablet .canvas { width:100% !important; max-width:100% !important; min-width:0 !important; }
.preview-frame.preview-mobile .canvas { width:100% !important; max-width:100% !important; min-width:0 !important; }
</style>

<style id="sbc-structural-layout-fix-v8">
/* V8: widgets são blocos; somente section/column/container controlam o layout estrutural. */
.canvas .el-heading, .canvas .el-text, .canvas .el-image, .canvas .el-button,
.canvas .el-video, .canvas .el-icon, .canvas .el-divider, .canvas .el-spacer,
.canvas .el-form, .canvas .el-faq, .canvas .el-testimonials, .canvas .el-pricing,
.canvas .el-counters, .canvas .el-features, .canvas .el-carousel, .canvas .el-gallery,
.canvas .el-team, .canvas .el-timeline, .canvas .el-tabs, .canvas .el-accordion,
.canvas .el-progress, .canvas .el-newsletter, .canvas .el-hero, .canvas .el-navbar,
.canvas .el-footer, .canvas .el-logo,
.preview-frame .el-heading, .preview-frame .el-text, .preview-frame .el-image, .preview-frame .el-button,
.preview-frame .el-video, .preview-frame .el-icon, .preview-frame .el-divider, .preview-frame .el-spacer,
.preview-frame .el-form, .preview-frame .el-faq, .preview-frame .el-testimonials, .preview-frame .el-pricing,
.preview-frame .el-counters, .preview-frame .el-features, .preview-frame .el-carousel, .preview-frame .el-gallery,
.preview-frame .el-team, .preview-frame .el-timeline, .preview-frame .el-tabs, .preview-frame .el-accordion,
.preview-frame .el-progress, .preview-frame .el-newsletter, .preview-frame .el-hero, .preview-frame .el-navbar,
.preview-frame .el-footer, .preview-frame .el-logo {
    display:block !important;
    width:100% !important;
    max-width:100% !important;
    min-width:0 !important;
    float:none !important;
    clear:both !important;
    flex:none !important;
    box-sizing:border-box !important;
}

/* O conteúdo de cada widget também começa uma nova linha. */
.canvas .el-content, .preview-frame .el-content {
    display:block !important;
    width:100% !important;
    max-width:100% !important;
    min-width:0 !important;
    flex:none !important;
    box-sizing:border-box !important;
}

/* Recursos: cabeçalho sempre acima do grid. */
.canvas .el-features .sbc-features-shell,
.preview-frame .el-features .sbc-features-shell {
    display:block !important;
    width:100% !important;
}
.canvas .el-features .sbc-features-shell > header,
.preview-frame .el-features .sbc-features-shell > header {
    display:block !important;
    width:100% !important;
    max-width:100% !important;
    clear:both !important;
}

/* Preview é uma camada de tela inteira, independentemente do layout do editor. */
.preview-overlay, .preview-overlay.active {
    position:fixed !important;
    inset:0 !important;
    width:100vw !important;
    height:100vh !important;
    min-height:100vh !important;
    max-height:none !important;
    margin:0 !important;
    padding:0 !important;
    z-index:2147483647 !important;
    overflow:hidden !important;
}
.preview-overlay.active { display:flex !important; flex-direction:column !important; }
.preview-overlay .preview-stage {
    flex:1 1 auto !important;
    width:100% !important;
    min-width:0 !important;
    min-height:0 !important;
    height:auto !important;
    overflow:auto !important;
}
.preview-overlay .preview-frame {
    flex:0 0 auto !important;
    margin:0 auto !important;
    min-height:calc(100vh - 54px) !important;
}
.preview-overlay .preview-frame.preview-desktop { width:100% !important; }
.preview-overlay .preview-frame.preview-tablet { width:min(768px, calc(100vw - 32px)) !important; }
.preview-overlay .preview-frame.preview-mobile { width:min(375px, calc(100vw - 32px)) !important; }

/* Breakpoints baseados na largura real do frame do preview. */
.preview-frame .sbc-features-grid, .preview-frame .sbc-counters-grid,
.preview-frame .sbc-pricing-grid, .preview-frame .sbc-team-grid,
.preview-frame .sbc-gallery-grid, .preview-frame .sbc-testimonials-grid {
    width:100% !important; min-width:0 !important; max-width:100% !important;
    grid-template-columns:repeat(3,minmax(0,1fr)) !important;
}
.preview-frame.preview-tablet .sbc-features-grid,
.preview-frame.preview-tablet .sbc-counters-grid,
.preview-frame.preview-tablet .sbc-pricing-grid,
.preview-frame.preview-tablet .sbc-team-grid,
.preview-frame.preview-tablet .sbc-gallery-grid,
.preview-frame.preview-tablet .sbc-testimonials-grid {
    grid-template-columns:repeat(2,minmax(0,1fr)) !important;
}
.preview-frame.preview-mobile .sbc-features-grid,
.preview-frame.preview-mobile .sbc-counters-grid,
.preview-frame.preview-mobile .sbc-pricing-grid,
.preview-frame.preview-mobile .sbc-team-grid,
.preview-frame.preview-mobile .sbc-gallery-grid,
.preview-frame.preview-mobile .sbc-testimonials-grid {
    grid-template-columns:1fr !important;
}
</style>

<style id="sbc-layout-engine-final">
/* =========================================================
   MOTOR FINAL DE LAYOUT
   Estrutura: section -> column/container -> widgets.
   Widgets comuns ocupam a largura disponível e nunca podem
   encolher como colunas. O modo responsivo é controlado pela
   classe do canvas, não pelo tamanho da janela do editor.
   ========================================================= */
.canvas .widgets-container{
    display:flex!important;
    flex-direction:column!important;
    align-items:stretch!important;
    width:100%!important;
    max-width:100%!important;
    min-width:0!important;
    gap:10px!important;
}
.canvas .widgets-container > [data-id]{
    display:block!important;
    width:100%!important;
    max-width:100%!important;
    min-width:0!important;
    flex:0 0 auto!important;
    align-self:stretch!important;
    box-sizing:border-box!important;
}
/* Widgets que possuem largura própria */
.canvas .widgets-container > .el-image,
.canvas .widgets-container > .el-button{
    align-self:flex-start!important;
}
.canvas .el-features,
.canvas .el-testimonials,
.canvas .el-pricing,
.canvas .el-counters,
.canvas .el-team,
.canvas .el-gallery,
.canvas .el-faq,
.canvas .el-form,
.canvas .el-timeline,
.canvas .el-tabs,
.canvas .el-accordion,
.canvas .el-carousel,
.canvas .el-newsletter,
.canvas .el-hero,
.canvas .el-navbar,
.canvas .el-footer,
.canvas .el-video,
.canvas .el-heading,
.canvas .el-text,
.canvas .el-divider,
.canvas .el-spacer,
.canvas .el-icon{
    flex:0 0 auto!important;
    min-width:0!important;
    max-width:100%!important;
    box-sizing:border-box!important;
}
.canvas .el-features,
.canvas .el-testimonials,
.canvas .el-pricing,
.canvas .el-counters,
.canvas .el-team,
.canvas .el-gallery,
.canvas .el-faq,
.canvas .el-form,
.canvas .el-timeline,
.canvas .el-tabs,
.canvas .el-accordion,
.canvas .el-carousel,
.canvas .el-newsletter,
.canvas .el-hero,
.canvas .el-navbar,
.canvas .el-footer,
.canvas .el-video,
.canvas .el-heading,
.canvas .el-text,
.canvas .el-divider,
.canvas .el-spacer,
.canvas .el-icon{
    width:100%!important;
}
.canvas .el-image{max-width:100%!important;min-width:0!important;flex:0 0 auto!important;}

/* Desktop */
.canvas.sbc-responsive-desktop .columns-container{
    display:flex!important;
    flex-direction:row!important;
    flex-wrap:wrap!important;
    align-items:stretch!important;
    width:100%!important;
    max-width:100%!important;
    min-width:0!important;
    gap:15px!important;
}
.canvas.sbc-responsive-desktop .el-column{
    min-width:0!important;
    max-width:100%!important;
    flex:1 1 0!important;
    width:auto!important;
}

/* Tablet: colunas lado a lado, mas com no máximo duas por linha. */
.canvas.sbc-responsive-tablet .columns-container{
    display:grid!important;
    grid-template-columns:repeat(2,minmax(0,1fr))!important;
    gap:12px!important;
    width:100%!important;
    max-width:100%!important;
    min-width:0!important;
}
.canvas.sbc-responsive-tablet .el-column{
    width:auto!important;
    max-width:100%!important;
    min-width:0!important;
    flex:none!important;
}

/* Mobile: UMA coluna por linha. */
.canvas.sbc-responsive-mobile .columns-container{
    display:flex!important;
    flex-direction:column!important;
    flex-wrap:nowrap!important;
    align-items:stretch!important;
    gap:0!important;
    width:100%!important;
    max-width:100%!important;
    min-width:0!important;
}
.canvas.sbc-responsive-mobile .el-column{
    display:block!important;
    width:100%!important;
    max-width:100%!important;
    min-width:0!important;
    flex:0 0 100%!important;
    margin-left:0!important;
    margin-right:0!important;
}
.canvas.sbc-responsive-mobile .widgets-container > [data-id]{
    width:100%!important;
    max-width:100%!important;
    min-width:0!important;
    flex:0 0 auto!important;
}
.canvas.sbc-responsive-mobile .el-features,
.canvas.sbc-responsive-mobile .el-testimonials,
.canvas.sbc-responsive-mobile .el-pricing,
.canvas.sbc-responsive-mobile .el-counters,
.canvas.sbc-responsive-mobile .el-team,
.canvas.sbc-responsive-mobile .el-gallery,
.canvas.sbc-responsive-mobile .el-faq,
.canvas.sbc-responsive-mobile .el-form,
.canvas.sbc-responsive-mobile .el-timeline,
.canvas.sbc-responsive-mobile .el-tabs,
.canvas.sbc-responsive-mobile .el-accordion,
.canvas.sbc-responsive-mobile .el-carousel,
.canvas.sbc-responsive-mobile .el-newsletter,
.canvas.sbc-responsive-mobile .el-hero,
.canvas.sbc-responsive-mobile .el-navbar,
.canvas.sbc-responsive-mobile .el-footer,
.canvas.sbc-responsive-mobile .el-video,
.canvas.sbc-responsive-mobile .el-heading,
.canvas.sbc-responsive-mobile .el-text{
    width:100%!important;
    max-width:100%!important;
    min-width:0!important;
    flex:0 0 auto!important;
}
/* O recurso é um grid interno: no celular 1 card por linha. */
.canvas.sbc-responsive-mobile .sbc-features-shell > header,
.canvas.sbc-responsive-mobile .sbc-testimonials-shell > header{
    display:block!important;
    width:100%!important;
    max-width:100%!important;
}
.canvas.sbc-responsive-mobile .sbc-features-grid,
.canvas.sbc-responsive-mobile .sbc-counters-grid,
.canvas.sbc-responsive-mobile .sbc-pricing-grid,
.canvas.sbc-responsive-mobile .sbc-team-grid,
.canvas.sbc-responsive-mobile .sbc-gallery-grid,
.canvas.sbc-responsive-mobile .sbc-testimonials-grid,
.canvas.sbc-responsive-mobile .sbc-form-grid{
    display:grid!important;
    grid-template-columns:minmax(0,1fr)!important;
    width:100%!important;
    max-width:100%!important;
    min-width:0!important;
}
.canvas.sbc-responsive-mobile .sbc-feature-card,
.canvas.sbc-responsive-mobile .sbc-counter-card,
.canvas.sbc-responsive-mobile .sbc-pricing-card,
.canvas.sbc-responsive-mobile .sbc-team-card,
.canvas.sbc-responsive-mobile .sbc-gallery-item,
.canvas.sbc-responsive-mobile .sbc-testimonial-card{
    width:100%!important;
    max-width:100%!important;
    min-width:0!important;
}
</style>


<style id="sbc-responsive-v9-final">
/* =========================================================
   V9 FINAL — RESPONSIVIDADE SEM ESMAGAMENTO
   Mobile = 1 coluna / Tablet = 2 colunas / Desktop = original.
   O Preview abre em uma nova aba e usa a largura do dispositivo.
   ========================================================= */

/* EDITOR: o modo mobile mostra um viewport de telefone real.
   Tablet pode ocupar toda a área central, como solicitado. */
.canvas.sbc-responsive-mobile,
.canvas.mobile {
    width: min(390px, calc(100% - 24px)) !important;
    max-width: 390px !important;
    min-width: 0 !important;
    margin-left: auto !important;
    margin-right: auto !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
    overflow-x: hidden !important;
}
.canvas.sbc-responsive-tablet,
.canvas.tablet {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
}

/* A estrutura externa SEMPRE se reorganiza antes dos widgets. */
.canvas.sbc-responsive-mobile .columns-container,
.canvas.mobile .columns-container {
    display: flex !important;
    flex-direction: column !important;
    flex-wrap: nowrap !important;
    align-items: stretch !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    gap: 0 !important;
}
.canvas.sbc-responsive-mobile .columns-container > .el-column,
.canvas.mobile .columns-container > .el-column {
    display: block !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    flex: 0 0 100% !important;
    margin: 0 !important;
}

/* Impede estilos inline antigos (33%, 25%, grid etc.) de recriar colunas estreitas. */
.canvas.sbc-responsive-mobile .el-column,
.canvas.mobile .el-column {
    flex-basis: 100% !important;
    inline-size: 100% !important;
}
.canvas.sbc-responsive-mobile .el-column > .widgets-container,
.canvas.mobile .el-column > .widgets-container {
    display: flex !important;
    flex-direction: column !important;
    align-items: stretch !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    gap: 12px !important;
}

/* Widgets dentro de uma coluna não podem virar uma segunda grade estreita. */
.canvas.sbc-responsive-mobile .widgets-container > [data-id],
.canvas.mobile .widgets-container > [data-id] {
    display: block !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    flex: 0 0 auto !important;
}

/* Grids internos dos widgets: mobile = 1 por linha. */
.canvas.sbc-responsive-mobile .sbc-features-grid,
.canvas.sbc-responsive-mobile .sbc-counters-grid,
.canvas.sbc-responsive-mobile .sbc-pricing-grid,
.canvas.sbc-responsive-mobile .sbc-team-grid,
.canvas.sbc-responsive-mobile .sbc-gallery-grid,
.canvas.sbc-responsive-mobile .sbc-testimonials-grid,
.canvas.sbc-responsive-mobile .sbc-form-grid,
.canvas.mobile .sbc-features-grid,
.canvas.mobile .sbc-counters-grid,
.canvas.mobile .sbc-pricing-grid,
.canvas.mobile .sbc-team-grid,
.canvas.mobile .sbc-gallery-grid,
.canvas.mobile .sbc-testimonials-grid,
.canvas.mobile .sbc-form-grid {
    display: grid !important;
    grid-template-columns: minmax(0, 1fr) !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    gap: 12px !important;
}

.canvas.sbc-responsive-mobile .sbc-feature-card,
.canvas.sbc-responsive-mobile .sbc-counter-card,
.canvas.sbc-responsive-mobile .sbc-pricing-card,
.canvas.sbc-responsive-mobile .sbc-team-card,
.canvas.sbc-responsive-mobile .sbc-gallery-item,
.canvas.sbc-responsive-mobile .sbc-testimonial-card,
.canvas.mobile .sbc-feature-card,
.canvas.mobile .sbc-counter-card,
.canvas.mobile .sbc-pricing-card,
.canvas.mobile .sbc-team-card,
.canvas.mobile .sbc-gallery-item,
.canvas.mobile .sbc-testimonial-card {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    box-sizing: border-box !important;
}

/* Mobile: fonte menor e proporcional. */
.canvas.sbc-responsive-mobile h1,
.canvas.mobile h1 { font-size: clamp(24px, 7vw, 30px) !important; line-height: 1.12 !important; }
.canvas.sbc-responsive-mobile h2,
.canvas.mobile h2 { font-size: clamp(21px, 6.2vw, 27px) !important; line-height: 1.16 !important; }
.canvas.sbc-responsive-mobile h3,
.canvas.mobile h3 { font-size: clamp(18px, 5.2vw, 22px) !important; line-height: 1.2 !important; }
.canvas.sbc-responsive-mobile p,
.canvas.mobile p,
.canvas.sbc-responsive-mobile li,
.canvas.mobile li { font-size: clamp(13px, 3.7vw, 15px) !important; line-height: 1.5 !important; }
.canvas.sbc-responsive-mobile .sbc-feature-card > div:nth-child(2),
.canvas.mobile .sbc-feature-card > div:nth-child(2) { font-size: clamp(15px, 4.2vw, 18px) !important; line-height: 1.25 !important; }
.canvas.sbc-responsive-mobile .sbc-feature-card > div:nth-child(3),
.canvas.mobile .sbc-feature-card > div:nth-child(3) { font-size: clamp(13px, 3.6vw, 15px) !important; line-height: 1.5 !important; }

/* Tablet: duas colunas sem esmagar o conteúdo. */
.canvas.sbc-responsive-tablet .columns-container,
.canvas.tablet .columns-container {
    display: grid !important;
    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    gap: 16px !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
}
.canvas.sbc-responsive-tablet .columns-container > .el-column,
.canvas.tablet .columns-container > .el-column {
    width: auto !important;
    max-width: 100% !important;
    min-width: 0 !important;
    flex: none !important;
}

/* Preview em nova aba: viewport limpo e sem overlay dentro do editor. */
#previewFrame.mobile { width: min(390px, calc(100vw - 24px)) !important; max-width: 390px !important; }
#previewFrame.tablet { width: min(100vw, 1024px) !important; max-width: 100% !important; }
#previewFrame.mobile > .canvas,
#previewFrame.tablet > .canvas { width: 100% !important; max-width: 100% !important; min-width: 0 !important; }
#previewFrame.mobile > .canvas .columns-container {
    display: flex !important;
    flex-direction: column !important;
    flex-wrap: nowrap !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
}
#previewFrame.mobile > .canvas .columns-container > .el-column {
    display: block !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    flex: 0 0 100% !important;
    margin: 0 !important;
}
#previewFrame.mobile > .canvas .widgets-container {
    display: flex !important;
    flex-direction: column !important;
    align-items: stretch !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
}
#previewFrame.mobile > .canvas .sbc-features-grid,
#previewFrame.mobile > .canvas .sbc-counters-grid,
#previewFrame.mobile > .canvas .sbc-pricing-grid,
#previewFrame.mobile > .canvas .sbc-team-grid,
#previewFrame.mobile > .canvas .sbc-gallery-grid,
#previewFrame.mobile > .canvas .sbc-testimonials-grid,
#previewFrame.mobile > .canvas .sbc-form-grid {
    grid-template-columns: minmax(0, 1fr) !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
}
<style id="sbc-structure-responsive-final-fix">
/* Colunas reais: o conteúdo sempre usa a largura da coluna pai. */
.columns-container{display:flex;flex-wrap:wrap;width:100%;max-width:100%;min-width:0;box-sizing:border-box;}
.columns-container > .el-column{flex:1 1 0;min-width:0;max-width:100%;width:auto;box-sizing:border-box;}
.el-column > .widgets-container{width:100%;max-width:100%;min-width:0;box-sizing:border-box;display:flex;flex-direction:column;align-items:stretch;}
.el-column > .widgets-container > [data-id]{width:100%;max-width:100%;min-width:0;box-sizing:border-box;}
.el-column .sbc-features-grid,.el-column .sbc-counters-grid,.el-column .sbc-pricing-grid,.el-column .sbc-team-grid,.el-column .sbc-gallery-grid,.el-column .sbc-testimonials-grid{width:100%!important;max-width:100%!important;min-width:0!important;grid-template-columns:repeat(auto-fit,minmax(min(180px,100%),1fr))!important;}
.preview-frame.preview-mobile,#previewFrame.mobile{width:375px!important;max-width:100vw!important;min-width:0!important;}
.preview-frame.preview-mobile > .canvas,#previewFrame.mobile > .canvas{width:100%!important;max-width:100%!important;min-width:0!important;margin:0!important;padding-left:12px!important;padding-right:12px!important;box-sizing:border-box!important;overflow-x:hidden!important;}
.preview-frame.preview-mobile .columns-container,#previewFrame.mobile .columns-container{display:flex!important;flex-direction:column!important;flex-wrap:nowrap!important;width:100%!important;gap:0!important;}
.preview-frame.preview-mobile .columns-container > .el-column,#previewFrame.mobile .columns-container > .el-column{width:100%!important;max-width:100%!important;flex:0 0 100%!important;min-width:0!important;}
.preview-frame.preview-mobile .sbc-features-grid,#previewFrame.mobile .sbc-features-grid{grid-template-columns:1fr!important;width:100%!important;max-width:100%!important;min-width:0!important;}
.preview-frame.preview-mobile .sbc-feature-card,#previewFrame.mobile .sbc-feature-card{width:100%!important;max-width:100%!important;min-width:0!important;box-sizing:border-box!important;}
</style>

<style id="sbc-pointer-drag-fix">
.widget-item.sbc-pointer-dragging { opacity:.55 !important; transform:scale(.98); cursor:grabbing !important; }
</style>

<style id="sbc-responsive-mobile-final-v9">
/* V9 — MOBILE REAL: nunca deixar o widget de Recursos espremido */
#previewFrame.mobile > .canvas .el-section,
#previewFrame.mobile > .canvas .columns-container,
#previewFrame.mobile > .canvas .el-column,
#previewFrame.mobile > .canvas .widgets-container,
#previewFrame.mobile > .canvas .el-features,
#previewFrame.mobile > .canvas .el-features > .el-content,
#previewFrame.mobile > .canvas .el-features .sbc-features-shell {
  width:100% !important;
  max-width:none !important;
  min-width:0 !important;
  box-sizing:border-box !important;
}

#previewFrame.mobile > .canvas .columns-container {
  display:flex !important;
  flex-direction:column !important;
  flex-wrap:nowrap !important;
  gap:0 !important;
  align-items:stretch !important;
}

#previewFrame.mobile > .canvas .columns-container > .el-column {
  display:block !important;
  width:100% !important;
  max-width:none !important;
  min-width:0 !important;
  flex:0 0 100% !important;
  grid-column:auto !important;
  margin:0 !important;
}

#previewFrame.mobile > .canvas .el-features {
  display:block !important;
  flex:none !important;
  width:100% !important;
  max-width:none !important;
  min-width:0 !important;
  margin:0 !important;
  padding:0 !important;
}

#previewFrame.mobile > .canvas .el-features > .el-content {
  display:block !important;
  width:100% !important;
  max-width:none !important;
  min-width:0 !important;
  margin:0 !important;
  padding:0 !important;
}

#previewFrame.mobile > .canvas .el-features .sbc-features-shell {
  display:block !important;
  position:relative !important;
  width:100% !important;
  max-width:none !important;
  min-width:0 !important;
  margin:0 !important;
  padding:18px 16px !important;
  float:none !important;
  clear:both !important;
  box-sizing:border-box !important;
}

#previewFrame.mobile > .canvas .el-features .sbc-features-shell > header {
  display:block !important;
  position:static !important;
  float:none !important;
  clear:both !important;
  width:100% !important;
  max-width:none !important;
  min-width:0 !important;
  margin:0 0 18px !important;
  padding:0 !important;
  text-align:center !important;
  box-sizing:border-box !important;
  grid-column:auto !important;
  flex:none !important;
}

#previewFrame.mobile > .canvas .el-features .sbc-features-shell > header > div:first-child {
  display:block !important;
  width:100% !important;
  max-width:none !important;
  min-width:0 !important;
  font-size:30px !important;
  line-height:1.12 !important;
  word-break:normal !important;
  overflow-wrap:break-word !important;
  white-space:normal !important;
  box-sizing:border-box !important;
}

#previewFrame.mobile > .canvas .el-features .sbc-features-shell > header > div:last-child {
  display:block !important;
  width:100% !important;
  max-width:none !important;
  min-width:0 !important;
  font-size:15px !important;
  line-height:1.5 !important;
  word-break:normal !important;
  overflow-wrap:break-word !important;
  white-space:normal !important;
  box-sizing:border-box !important;
}

#previewFrame.mobile > .canvas .el-features .sbc-features-grid {
  display:grid !important;
  position:static !important;
  float:none !important;
  clear:both !important;
  width:100% !important;
  max-width:none !important;
  min-width:0 !important;
  grid-template-columns:minmax(0,1fr) !important;
  grid-auto-flow:row !important;
  gap:12px !important;
  margin:0 !important;
  padding:0 !important;
  box-sizing:border-box !important;
}

#previewFrame.mobile > .canvas .el-features .sbc-features-grid > .sbc-feature-card {
  display:block !important;
  width:100% !important;
  max-width:none !important;
  min-width:0 !important;
  grid-column:auto !important;
  grid-row:auto !important;
  margin:0 !important;
  box-sizing:border-box !important;
  overflow:hidden !important;
  word-break:normal !important;
  overflow-wrap:break-word !important;
}

#previewFrame.mobile > .canvas .el-features .sbc-feature-card > div {
  max-width:100% !important;
  min-width:0 !important;
  word-break:normal !important;
  overflow-wrap:break-word !important;
  white-space:normal !important;
}
</style>

</head>
<body>

<nav class="navbar">
    <div class="navbar-left">
        <div class="brand"><div class="brand-icon"><i class="fas fa-cube"></i></div><span>Construtor Pro</span></div>
        <button onclick="showEditor()" class="nav-btn editor-btn"><i class="fas fa-paint-brush"></i> Editor Visual</button>
        <button type="button" onclick="window.__sbcOpenPreview('desktop')" class="nav-btn preview-btn" id="btn-preview"><i class="fas fa-eye"></i> Preview</button>
        <button onclick="savePage()" class="nav-btn primary"><i class="fas fa-save"></i> Salvar</button>
    </div>
    <div class="navbar-right">
        <button onclick="undo()" class="nav-btn secondary" id="btn-undo" disabled><i class="fas fa-undo"></i> Desfazer</button>
        <button onclick="redo()" class="nav-btn secondary" id="btn-redo" disabled><i class="fas fa-redo"></i> Refazer</button>
        <button onclick="clearCanvas()" class="nav-btn secondary"><i class="fas fa-trash"></i> Limpar</button>
    </div>
</nav>

<div id="editor-view" class="view-container active">
    <div class="editor-layout">
        <aside class="sidebar-left">
            <div class="sidebar-tabs">
                <button class="sidebar-tab active">Widgets</button>
            </div>
            <div class="sidebar-content">
                <div class="sidebar-panel active">
                    <div class="section-header">Estrutura</div>
                    <div class="widget-grid">
                        <div class="widget-item" onclick="openStructureModal('section')"><i class="fas fa-layer-group"></i><span>Seção</span></div>
                        <div class="widget-item" onclick="openStructureModal('column')"><i class="fas fa-table-columns"></i><span>Coluna</span></div>
                        <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'container')"><i class="fas fa-box"></i><span>Container</span></div>
                    </div>

                    <details class="widget-zone zone-header" open>
                        <summary><span>Cabeçalho</span><span class="zone-subtitle">Topo do site</span></summary>
                        <div class="widget-grid">
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'navbar')"><i class="fas fa-bars"></i><span>Navbar</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'logo')"><i class="fas fa-copyright"></i><span>Logo</span></div>
                        </div>
                    </details>

                    <details class="widget-zone zone-highlight" open>
                        <summary><span>Destaque</span><span class="zone-subtitle">Hero / abertura</span></summary>
                        <div class="widget-grid">
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'hero')"><i class="fas fa-image"></i><span>Hero</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'heading')"><i class="fas fa-heading"></i><span>Título</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'text')"><i class="fas fa-font"></i><span>Texto</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'button')"><i class="fas fa-circle"></i><span>Botão</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'image')"><i class="fas fa-image"></i><span>Imagem</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'video')"><i class="fas fa-video"></i><span>Vídeo</span></div>
                        </div>
                    </details>

                    <details class="widget-zone zone-content" open>
                        <summary><span>Bloco 1 · Conteúdo</span><span class="zone-subtitle">Base da página</span></summary>
                        <div class="widget-grid">
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'heading')"><i class="fas fa-heading"></i><span>Título</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'text')"><i class="fas fa-font"></i><span>Texto</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'image')"><i class="fas fa-image"></i><span>Imagem</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'button')"><i class="fas fa-circle"></i><span>Botão</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'video')"><i class="fas fa-video"></i><span>Vídeo</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'icon')"><i class="fas fa-star"></i><span>Ícone</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'divider')"><i class="fas fa-minus"></i><span>Divisor</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'spacer')"><i class="fas fa-arrows-alt-v"></i><span>Espaço</span></div>
                        </div>
                    </details>

                    <details class="widget-zone zone-ads" open>
                        <summary><span>Publicidade</span><span class="zone-subtitle">Banners, vídeos e Ads</span></summary>
                        <div class="widget-grid">
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'bannerAd')"><i class="fas fa-panorama"></i><span>Banner</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'videoAd')"><i class="fas fa-clapperboard"></i><span>Vídeo Ad</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'adEmbed')"><i class="fas fa-code"></i><span>Ads / Código</span></div>
                        </div>
                    </details>

                    <details class="widget-zone zone-resources" open>
                        <summary><span>Bloco 2 · Recursos</span><span class="zone-subtitle">Prova e apresentação</span></summary>
                        <div class="widget-grid">
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'features')"><i class="fas fa-star"></i><span>Recursos</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'counters')"><i class="fas fa-chart-line"></i><span>Contadores</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'testimonials')"><i class="fas fa-quote-right"></i><span>Depoimentos</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'team')"><i class="fas fa-users"></i><span>Equipe</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'carousel')"><i class="fas fa-images"></i><span>Carrossel</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'gallery')"><i class="fas fa-th"></i><span>Galeria</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'timeline')"><i class="fas fa-stream"></i><span>Timeline</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'progress')"><i class="fas fa-tasks"></i><span>Progress</span></div>
                        </div>
                    </details>

                    <details class="widget-zone zone-content">
                        <summary><span>Bloco 3 · Interação</span><span class="zone-subtitle">Conteúdo expansível</span></summary>
                        <div class="widget-grid">
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'tabs')"><i class="fas fa-folder-open"></i><span>Tabs</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'accordion')"><i class="fas fa-list"></i><span>Accordion</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'faq')"><i class="fas fa-question-circle"></i><span>FAQ</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'pricing')"><i class="fas fa-tags"></i><span>Preços</span></div>
                        </div>
                    </details>

                    <details class="widget-zone zone-conversion" open>
                        <summary><span>Conversão</span><span class="zone-subtitle">Captação e ação</span></summary>
                        <div class="widget-grid">
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'form')"><i class="fas fa-envelope"></i><span>Form</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'newsletter')"><i class="fas fa-paper-plane"></i><span>Newsletter</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'pricing')"><i class="fas fa-tags"></i><span>Preços</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'button')"><i class="fas fa-circle"></i><span>Botão</span></div>
                        </div>
                    </details>

                    <details class="widget-zone zone-footer" open>
                        <summary><span>Rodapé</span><span class="zone-subtitle">Final do site</span></summary>
                        <div class="widget-grid">
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'footer')"><i class="fas fa-shoe-prints"></i><span>Rodapé</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'logo')"><i class="fas fa-copyright"></i><span>Logo</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'text')"><i class="fas fa-font"></i><span>Texto</span></div>
                            <div class="widget-item" draggable="true" ondragstart="dragSidebarWidget(event, 'button')"><i class="fas fa-circle"></i><span>Botão</span></div>
                        </div>
                    </details>
                </div>
            </div>
        </aside>

        <div class="canvas-area">
            <div class="responsive-bar">
                <button class="active" onclick="setResponsive('desktop', this)"><i class="fas fa-desktop"></i> Desktop</button>
                <button onclick="setResponsive('tablet', this)"><i class="fas fa-tablet"></i> Tablet</button>
                <button onclick="setResponsive('mobile', this)"><i class="fas fa-mobile"></i> Mobile</button>
            </div>
            <div class="canvas" id="canvas">
                <p style="text-align:center;color:#999;padding:40px;">Arraste widgets da sidebar ou clique neles para adicionar</p>
            </div>
        </div>

        <aside class="sidebar-right">
            <div class="right-tabs">
                <button class="right-tab active">Propriedades</button>
            </div>
            <div class="right-content">
                <div class="right-panel active">
                    <div id="propertiesContent">
                        <p style="color: var(--text-tertiary); text-align: center; padding: 20px; font-size: 12px;">Selecione um elemento</p>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>


<div class="preview-overlay" id="previewOverlay" style="display:none;">
    <div class="preview-toolbar">
        <div class="preview-toolbar-left">
            <span class="preview-title"><i class="fas fa-eye"></i> Visualização da página</span>
            <button type="button" class="preview-device-btn active" onclick="setPreviewDevice('desktop', this)">
                <i class="fas fa-desktop"></i> Desktop
            </button>
            <button type="button" class="preview-device-btn" onclick="setPreviewDevice('tablet', this)">
                <i class="fas fa-tablet"></i> Tablet
            </button>
            <button type="button" class="preview-device-btn" onclick="setPreviewDevice('mobile', this)">
                <i class="fas fa-mobile"></i> Mobile
            </button>
        </div>
        <div class="preview-toolbar-right">
            <button type="button" class="preview-close" onclick="closePreview()">
                <i class="fas fa-times"></i> Fechar Preview
            </button>
        </div>
    </div>

    <div class="preview-stage" id="previewStage">
        <div class="preview-frame" id="previewFrame">
            <div class="preview-content" id="previewContent"></div>
        </div>
    </div>
</div>

<div class="modal" id="structureModal">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h3>Selecione a Estrutura</h3>
            <button class="close-modal" onclick="closeModal('structureModal')">&times;</button>
        </div>
        <div style="display: flex; gap: 8px; margin-bottom: 20px;">
            <button class="btn-confirm" id="tab-section" onclick="switchStructureTab('section')">Seções</button>
            <button class="btn-cancel" id="tab-column" onclick="switchStructureTab('column')">Colunas</button>
        </div>
        <div id="structureGrid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;"></div>
    </div>
</div>

<div class="auto-save-indicator" id="autoSaveIndicator"><i class="fas fa-save"></i> Salvando...</div>

<style id="sbc-responsive-v6">
/* V6 — camada final de responsividade estrutural */
.canvas.sbc-responsive-mobile,.preview-frame.preview-mobile .canvas{width:100%!important;max-width:100%!important;min-width:0!important;overflow-x:hidden!important}
.canvas.sbc-responsive-mobile .columns-container,.preview-frame.preview-mobile .canvas .columns-container{display:flex!important;flex-direction:column!important;flex-wrap:nowrap!important;width:100%!important;max-width:100%!important;min-width:0!important}
.canvas.sbc-responsive-mobile .el-column,.preview-frame.preview-mobile .canvas .el-column{flex:0 0 100%!important;width:100%!important;max-width:100%!important;min-width:0!important}
.canvas.sbc-responsive-mobile .widgets-container,.preview-frame.preview-mobile .canvas .widgets-container{display:flex!important;flex-direction:column!important;align-items:stretch!important;width:100%!important;max-width:100%!important;min-width:0!important}
.canvas.sbc-responsive-mobile [data-id]:not(.el-section):not(.el-column):not(.el-container),.preview-frame.preview-mobile .canvas [data-id]:not(.el-section):not(.el-column):not(.el-container){width:100%!important;max-width:100%!important;min-width:0!important;box-sizing:border-box!important}
.canvas.sbc-responsive-mobile .el-content,.preview-frame.preview-mobile .canvas .el-content,.canvas.sbc-responsive-mobile .el-content>*,.preview-frame.preview-mobile .canvas .el-content>*{max-width:100%!important;min-width:0!important;box-sizing:border-box!important}
.canvas.sbc-responsive-mobile .sbc-features-shell,.preview-frame.preview-mobile .canvas .sbc-features-shell{display:block!important;width:100%!important;max-width:100%!important;min-width:0!important}
.canvas.sbc-responsive-mobile .sbc-features-shell>header,.preview-frame.preview-mobile .canvas .sbc-features-shell>header{display:block!important;width:100%!important;max-width:100%!important;min-width:0!important}
.canvas.sbc-responsive-mobile .sbc-features-grid,.preview-frame.preview-mobile .canvas .sbc-features-grid,.canvas.sbc-responsive-mobile .sbc-counters-grid,.preview-frame.preview-mobile .canvas .sbc-counters-grid,.canvas.sbc-responsive-mobile .sbc-pricing-grid,.preview-frame.preview-mobile .canvas .sbc-pricing-grid,.canvas.sbc-responsive-mobile .sbc-team-grid,.preview-frame.preview-mobile .canvas .sbc-team-grid,.canvas.sbc-responsive-mobile .sbc-gallery-grid,.preview-frame.preview-mobile .canvas .sbc-gallery-grid,.canvas.sbc-responsive-mobile .sbc-testimonials-grid,.preview-frame.preview-mobile .canvas .sbc-testimonials-grid,.canvas.sbc-responsive-mobile .sbc-form-grid,.preview-frame.preview-mobile .canvas .sbc-form-grid{display:grid!important;grid-template-columns:minmax(0,1fr)!important;width:100%!important;max-width:100%!important;min-width:0!important}
.canvas.sbc-responsive-mobile .sbc-feature-card,.preview-frame.preview-mobile .canvas .sbc-feature-card,.canvas.sbc-responsive-mobile .sbc-counter-card,.preview-frame.preview-mobile .canvas .sbc-counter-card,.canvas.sbc-responsive-mobile .sbc-pricing-card,.preview-frame.preview-mobile .canvas .sbc-pricing-card,.canvas.sbc-responsive-mobile .sbc-team-card,.preview-frame.preview-mobile .canvas .sbc-team-card,.canvas.sbc-responsive-mobile .sbc-testimonial-card,.preview-frame.preview-mobile .canvas .sbc-testimonial-card,.canvas.sbc-responsive-mobile .sbc-gallery-item,.preview-frame.preview-mobile .canvas .sbc-gallery-item{width:100%!important;max-width:100%!important;min-width:0!important;box-sizing:border-box!important}
.canvas.sbc-responsive-mobile img,.canvas.sbc-responsive-mobile video,.canvas.sbc-responsive-mobile iframe,.canvas.sbc-responsive-mobile svg,.canvas.sbc-responsive-mobile canvas,.preview-frame.preview-mobile .canvas img,.preview-frame.preview-mobile .canvas video,.preview-frame.preview-mobile .canvas iframe,.preview-frame.preview-mobile .canvas svg,.preview-frame.preview-mobile .canvas canvas{max-width:100%!important;box-sizing:border-box!important}
@media(max-width:430px){.canvas.sbc-responsive-mobile .el-section,.preview-frame.preview-mobile .canvas .el-section{padding-left:6px!important;padding-right:6px!important}.canvas.sbc-responsive-mobile .el-column,.preview-frame.preview-mobile .canvas .el-column{padding-left:5px!important;padding-right:5px!important}}
</style>

<style id="sbc-responsive-v7-text">
/* V7 — texto responsivo: quebra entre palavras, nunca no meio da palavra */
.canvas.sbc-responsive-mobile h1,
.canvas.sbc-responsive-mobile h2,
.canvas.sbc-responsive-mobile h3,
.canvas.sbc-responsive-mobile h4,
.canvas.sbc-responsive-mobile h5,
.canvas.sbc-responsive-mobile h6,
.canvas.sbc-responsive-mobile p,
.canvas.sbc-responsive-mobile span,
.canvas.sbc-responsive-mobile a,
.canvas.sbc-responsive-mobile li,
.canvas.sbc-responsive-mobile label,
.preview-frame.preview-mobile .canvas h1,
.preview-frame.preview-mobile .canvas h2,
.preview-frame.preview-mobile .canvas h3,
.preview-frame.preview-mobile .canvas h4,
.preview-frame.preview-mobile .canvas h5,
.preview-frame.preview-mobile .canvas h6,
.preview-frame.preview-mobile .canvas p,
.preview-frame.preview-mobile .canvas span,
.preview-frame.preview-mobile .canvas a,
.preview-frame.preview-mobile .canvas li,
.preview-frame.preview-mobile .canvas label {
    word-break: normal !important;
    overflow-wrap: break-word !important;
    hyphens: none !important;
    white-space: normal !important;
}

/* Conteúdo dos widgets */
.canvas.sbc-responsive-mobile .el-heading .el-content > *,
.canvas.sbc-responsive-mobile .el-text .el-content > *,
.preview-frame.preview-mobile .canvas .el-heading .el-content > *,
.preview-frame.preview-mobile .canvas .el-text .el-content > * {
    word-break: normal !important;
    overflow-wrap: break-word !important;
    hyphens: none !important;
    white-space: normal !important;
}

/* Cards: não quebrar palavras normais só porque o espaço é pequeno. */
.canvas.sbc-responsive-mobile .sbc-feature-card *,
.canvas.sbc-responsive-mobile .sbc-counter-card *,
.canvas.sbc-responsive-mobile .sbc-pricing-card *,
.canvas.sbc-responsive-mobile .sbc-team-card *,
.canvas.sbc-responsive-mobile .sbc-testimonial-card *,
.canvas.sbc-responsive-mobile .sbc-gallery-item *,
.preview-frame.preview-mobile .canvas .sbc-feature-card *,
.preview-frame.preview-mobile .canvas .sbc-counter-card *,
.preview-frame.preview-mobile .canvas .sbc-pricing-card *,
.preview-frame.preview-mobile .canvas .sbc-team-card *,
.preview-frame.preview-mobile .canvas .sbc-testimonial-card *,
.preview-frame.preview-mobile .canvas .sbc-gallery-item * {
    word-break: normal !important;
    overflow-wrap: break-word !important;
    hyphens: none !important;
}
<style>
.widget-item.sbc-pointer-dragging { opacity:.55 !important; transform:scale(.97); cursor:grabbing !important; }
body.sbc-widget-pointer-dragging, body.sbc-widget-pointer-dragging * { cursor:grabbing !important; }
</style>
</style>
<script>
let pageContent = [];
let selectedElementId = null;
let selectedPart = null; // parte interna selecionada: {id, key, label, selector}

let selectedInsertTargetId = null;
let currentStructureType = 'section';
let undoStack = [];
let redoStack = [];
let draggedElementId = null;
let canvasDropBound = false;
let currentDropHighlight = null;
let activeDropTargetId = null;
let sidebarDraggedWidgetType = null;
let widgetDropLock = false;
let pointerWidgetDrag = null;
let pointerWidgetDragBound = false;

function getPointerDropTarget(x, y) {
    // NÃO usa elementFromPoint(): widgets, overlays e os próprios controles
    // do editor podem ficar por cima do alvo visual. Em vez disso, escolhemos
    // a coluna/container cujo retângulo realmente contém o ponteiro.
    if (!Number.isFinite(x) || !Number.isFinite(y)) return null;

    const candidates = Array.from(document.querySelectorAll(
        '#canvas .el-column[data-id], #canvas .el-container[data-id]'
    ));

    const inside = candidates.filter(el => {
        const r = el.getBoundingClientRect();
        return r.width > 0 && r.height > 0 &&
            x >= r.left && x <= r.right &&
            y >= r.top && y <= r.bottom;
    });

    if (!inside.length) return null;

    // Se houver container + coluna sobrepostos, escolhe o alvo mais interno
    // (menor área). Isso garante que um widget solto dentro de uma coluna vá
    // para a coluna, e não para o container/section pai.
    inside.sort((a, b) => {
        const ra = a.getBoundingClientRect();
        const rb = b.getBoundingClientRect();
        return (ra.width * ra.height) - (rb.width * rb.height);
    });

    return inside[0]?.dataset.id || null;
}

function updatePointerWidgetDropTarget(x, y) {
    if (!pointerWidgetDrag) return null;
    const targetId = getPointerDropTarget(x, y) || normalizeDropTargetId(selectedInsertTargetId) || resolveSelectedInsertTarget();
    pointerWidgetDrag.targetId = targetId || null;
    if (targetId) {
        activeDropTargetId = targetId;
        const targetEl = document.querySelector(`[data-id="${CSS.escape(targetId)}"]`);
        setDropHighlight(targetEl);
    } else {
        setDropHighlight(null);
    }
    return targetId;
}

function startPointerWidgetDrag(item, type, e) {
    pointerWidgetDrag = {
        item,
        type,
        pointerId: e.pointerId,
        startX: e.clientX,
        startY: e.clientY,
        dragging: false,
        targetId: null
    };
    try { item.setPointerCapture(e.pointerId); } catch (_) {}
}

function finishPointerWidgetDrag(e) {
    const drag = pointerWidgetDrag;
    if (!drag) return;
    pointerWidgetDrag = null;
    try { drag.item.releasePointerCapture(drag.pointerId); } catch (_) {}
    drag.item.classList.remove('sbc-pointer-dragging');

    if (!drag.dragging) {
        clearDropHighlights();
        return;
    }

    const targetId = drag.targetId || getPointerDropTarget(e.clientX, e.clientY) || normalizeDropTargetId(selectedInsertTargetId) || resolveSelectedInsertTarget();
    clearDropHighlights();
    document.getElementById('canvas')?.classList.remove('drag-over');

    if (targetId) {
        addWidgetToContainer(drag.type, targetId);
    } else if (drag.type === 'section' || drag.type === 'container') {
        addWidgetFromSidebar(drag.type);
    } else {
        showNotification('Solte o widget dentro de uma coluna ou container.', 'error');
    }
}

const urlParams = new URLSearchParams(window.location.search);
const PAGE_ID = urlParams.get('page') || 'default';
const SITE_ID = urlParams.get('site') || '';

const fontFamilies = ['Inter','Poppins','Roboto','Montserrat','Oswald','Playfair Display','Bebas Neue','Pacifico','Lobster','Righteous','Orbitron','Arial','Georgia','Verdana','Times New Roman','Courier New'];
let customFonts = [];

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, ch => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[ch]));
}
function escapeAttr(value) { return escapeHtml(value); }
function fontFormatFromFile(name) {
    const ext = (String(name).split('.').pop() || '').toLowerCase();
    return ext === 'woff2' ? 'woff2' : ext === 'woff' ? 'woff' : ext === 'ttf' ? 'truetype' : ext === 'otf' ? 'opentype' : '';
}
function loadCustomFonts() {
    try { customFonts = JSON.parse(localStorage.getItem('construtorCustomFonts') || '[]'); } catch(e) { customFonts = []; }
    applyCustomFonts();
}
function saveCustomFonts() {
    try { localStorage.setItem('construtorCustomFonts', JSON.stringify(customFonts)); return true; }
    catch(e) { showNotification('Não foi possível salvar a fonte. O arquivo pode ser grande demais.', 'error'); return false; }
}
function getAllFonts() { return [...fontFamilies, ...customFonts.map(f => f.name)].filter((v,i,a) => a.indexOf(v) === i); }
function getCustomFontsCSS() {
    return customFonts.map(f => `@font-face{font-family:"${escapeHtml(f.name)}";src:url("${f.dataUrl}") format("${f.format}");font-weight:${f.weight || '100 900'};font-style:${f.style || 'normal'};font-display:swap;}`).join('\n');
}
function applyCustomFonts() {
    let style = document.getElementById('custom-fonts-style');
    if (!style) { style = document.createElement('style'); style.id = 'custom-fonts-style'; document.head.appendChild(style); }
    style.textContent = getCustomFontsCSS();
}
function handleFontUpload(input) {
    const file = input.files?.[0];
    input.value = '';
    if (!file) return;
    const format = fontFormatFromFile(file.name);
    if (!format) { showNotification('Use uma fonte WOFF2, WOFF, TTF ou OTF.', 'error'); return; }
    const reader = new FileReader();
    reader.onload = e => {
        const base = file.name.replace(/\.[^.]+$/, '').replace(/[-_]+/g, ' ').trim() || 'Minha Fonte';
        let name = base, n = 2;
        while (customFonts.some(f => f.name.toLowerCase() === name.toLowerCase())) name = base + ' ' + n++;
        customFonts.push({ id:'font-' + Date.now(), name, fileName:file.name, format, dataUrl:e.target.result, weight:'100 900', style:'normal' });
        if (saveCustomFonts()) {
            applyCustomFonts();
            if (selectedElementId) updateProperties(selectedElementId);
            showNotification(`Fonte "${name}" adicionada!`);
        }
    };
    reader.onerror = () => showNotification('Erro ao ler a fonte.', 'error');
    reader.readAsDataURL(file);
}
function removeCustomFont(id) {
    const font = customFonts.find(f => f.id === id);
    if (!font) return;
    if (!confirm(`Remover a fonte "${font.name}"?`)) return;
    customFonts = customFonts.filter(f => f.id !== id);
    saveCustomFonts(); applyCustomFonts();
    if (selectedElementId) updateProperties(selectedElementId);
    renderCanvas();
    showNotification('Fonte removida!');
}

window.addEventListener('load', async () => {
    const initialCanvas = document.getElementById('canvas');
    if (initialCanvas) {
        initialCanvas.dataset.responsiveMode = 'desktop';
        initialCanvas.classList.remove('tablet', 'mobile');
    }
    loadCustomFonts();
    bindGlobalWidgetDropSafety();
    bindCanvasDropOnce();
    await loadData();
    normalizeBeforeCanvasRender();
    renderCanvas();
    saveState();
});

function getStorageKey() {
    return `construtorData_page_${PAGE_ID}`;
}

function findElementById(elements, id) {
    for (const el of elements) {
        if (el.id === id) return el;
        if (el.children?.length) {
            const found = findElementById(el.children, id);
            if (found) return found;
        }
    }
    return null;
}

function findParentOfId(elements, id, parent = null) {
    for (const el of elements) {
        if (el.id === id) return parent;
        if (el.children?.length) {
            const found = findParentOfId(el.children, id, el);
            if (found !== undefined) return found;
        }
    }
    return undefined;
}

function isDropContainer(type) {
    return ['column', 'container'].includes(type);
}


// =========================================================
// GARANTIA ESTRUTURAL: SECTION -> COLUMN/CONTAINER -> WIDGET
// =========================================================
// Alguns layouts antigos podem ter widgets gravados diretamente dentro de
// uma Section. Antes de renderizar ou inserir qualquer coisa, normalizamos
// essa estrutura para que o widget nunca apareça fora da Column.
function normalizePageStructure(elements) {
    if (!Array.isArray(elements)) return;

    for (const el of elements) {
        if (!el || !Array.isArray(el.children)) continue;

        if (el.type === 'section') {
            let childContainers = el.children.filter(child => child && ['column','container'].includes(child.type));
            let directWidgets = el.children.filter(child => child && !['column','container'].includes(child.type));

            if (directWidgets.length) {
                let target = childContainers[0];
                if (!target) {
                    target = {
                        id: 'el-column-' + Date.now() + '-' + Math.random().toString(36).slice(2,8),
                        type: 'column',
                        content: '',
                        styles: { padding: '15px', flex: '1 1 0%' },
                        children: []
                    };
                    childContainers.unshift(target);
                }
                if (!Array.isArray(target.children)) target.children = [];
                target.children.push(...directWidgets);
                el.children = childContainers;
            }
        }

        normalizePageStructure(el.children);
    }
}

function normalizeBeforeCanvasRender() {
    normalizePageStructure(pageContent);
}

function isDescendantOf(ancestorId, nodeId) {
    const ancestor = findElementById(pageContent, ancestorId);
    if (!ancestor?.children?.length) return false;
    for (const child of ancestor.children) {
        if (child.id === nodeId) return true;
        if (isDescendantOf(child.id, nodeId)) return true;
    }
    return false;
}

function resolveDropTarget(event, el) {
    if (el.type === 'column' || el.type === 'container') {
        return el.id;
    }

    if (el.type === 'section') {
        const columnEl = event?.target?.closest?.('.el-column');
        if (columnEl?.dataset.id) return columnEl.dataset.id;

        const child = (el.children || []).find(item => ['column', 'container'].includes(item.type));
        if (child) return child.id;

        return el.id;
    }

    return null;
}


function resolveDropTargetAtPoint(x, y, draggedId = null) {
    if (!Number.isFinite(x) || !Number.isFinite(y)) return null;
    const points = document.elementsFromPoint(x, y) || [];

    // The element directly under the mouse is frequently a widget/card.
    // Walk every element in the stack until we find its real column/container.
    for (const point of points) {
        const columnEl = point?.closest?.('.el-column');
        if (columnEl?.dataset.id && columnEl.dataset.id !== draggedId) {
            return columnEl.dataset.id;
        }
        const containerEl = point?.closest?.('.el-container');
        if (containerEl?.dataset.id && containerEl.dataset.id !== draggedId) {
            return containerEl.dataset.id;
        }
    }
    return null;
}

function normalizeDropTargetId(targetId) {
    if (!targetId) return null;
    const target = findElementById(pageContent, targetId);
    if (!target) return null;
    if (isDropContainer(target.type)) return target.id;
    if (target.type === 'section') {
        const child = (target.children || []).find(item => ['column', 'container'].includes(item.type));
        return child ? child.id : null;
    }
    const parent = findParentOfId(pageContent, target.id);
    if (parent && isDropContainer(parent.type)) return parent.id;
    return null;
}

function resolveCanvasDropTarget(event, draggedId = null) {
    const pointTarget = resolveDropTargetAtPoint(event?.clientX, event?.clientY, draggedId);
    if (pointTarget) return normalizeDropTargetId(pointTarget);

    const target = event?.target;
    const columnEl = target?.closest?.('.el-column');
    if (columnEl?.dataset.id && columnEl.dataset.id !== draggedId) return normalizeDropTargetId(columnEl.dataset.id);

    const containerEl = target?.closest?.('.el-container');
    if (containerEl?.dataset.id && containerEl.dataset.id !== draggedId) return normalizeDropTargetId(containerEl.dataset.id);

    if (activeDropTargetId && activeDropTargetId !== draggedId) {
        const active = findElementById(pageContent, activeDropTargetId);
        if (active && isDropContainer(active.type)) return active.id;
    }

    if (selectedInsertTargetId && selectedInsertTargetId !== draggedId) {
        const selected = findElementById(pageContent, selectedInsertTargetId);
        if (selected && isDropContainer(selected.type)) return selected.id;
    }

    if (selectedElementId && selectedElementId !== draggedId) {
        const selected = findElementById(pageContent, selectedElementId);
        if (selected && isDropContainer(selected.type)) return selected.id;
        if (selected?.type === 'section') return normalizeDropTargetId(selected.id);
    }
    return null;
}

function setDropHighlight(element) {
    if (currentDropHighlight && currentDropHighlight !== element) {
        currentDropHighlight.classList.remove('drag-over');
    }
    if (element) {
        element.classList.add('drag-over');
        currentDropHighlight = element;
    } else {
        currentDropHighlight = null;
    }
}

function clearDropHighlights() {
    document.querySelectorAll('.drag-over').forEach(el => el.classList.remove('drag-over'));
    currentDropHighlight = null;
    activeDropTargetId = null;
}

let __sbcServerSaveTimer = null;
let __sbcServerSaveBusy = false;
let __sbcServerSavePending = false;

function scheduleServerAutoSave() {
    if (!PAGE_ID || PAGE_ID === 'default') return;
    clearTimeout(__sbcServerSaveTimer);
    __sbcServerSaveTimer = setTimeout(() => {
        const payload = {
            pageId: parseInt(PAGE_ID, 10),
            elements: [{
                type: '_layout',
                content: JSON.stringify(pageContent),
                x: 0, y: 0, width: 100, height: 100, styles: {}
            }]
        };

        if (__sbcServerSaveBusy) {
            __sbcServerSavePending = true;
            return;
        }
        __sbcServerSaveBusy = true;
        fetch('/construtor-sites/api/save-elements', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
            keepalive: true
        })
        .catch(() => {})
        .finally(() => {
            __sbcServerSaveBusy = false;
            if (__sbcServerSavePending) {
                __sbcServerSavePending = false;
                scheduleServerAutoSave();
            }
        });
    }, 650);
}

function saveData() {
    const payload = { pageContent, pageId: PAGE_ID, siteId: SITE_ID, updatedAt: Date.now() };
    try {
        localStorage.setItem(getStorageKey(), JSON.stringify(payload));
        return true;
    } catch (err) {
        // QuotaExceededError costuma acontecer quando o usuário adiciona
        // vídeos/imagens em Data URL. Não deixe isso interromper o editor.
        if (err && (err.name === 'QuotaExceededError' || err.code === 22 || String(err.message || '').toLowerCase().includes('quota'))) {
            try {
                const compact = JSON.parse(JSON.stringify(payload, (key, value) => {
                    if (typeof value === 'string' && value.length > 180000 && value.startsWith('data:')) {
                        return '__ARQUIVO_LOCAL_GRANDE__';
                    }
                    return value;
                }));
                localStorage.setItem(getStorageKey(), JSON.stringify(compact));
            } catch (fallbackErr) {
                console.warn('LocalStorage sem espaço. As alterações continuam disponíveis para salvar no servidor.', fallbackErr);
            }
            scheduleServerAutoSave();
            return false;
        }
        console.warn('Falha ao salvar rascunho local:', err);
        scheduleServerAutoSave();
        return false;
    }
    scheduleServerAutoSave();
    return true;
}

async function loadData() {
    // O rascunho local pode estar mais novo que o servidor (o autosave do
    // servidor é assíncrono). Primeiro guardamos o rascunho local para não
    // perder uma alteração feita há poucos segundos ao dar F5.
    let localData = null;
    let localUpdatedAt = 0;
    try {
        const rawStorage = localStorage.getItem(getStorageKey()) || localStorage.getItem('construtorData');
        if (rawStorage) {
            const parsed = JSON.parse(rawStorage);
            if (parsed?.pageContent && Array.isArray(parsed.pageContent)) {
                localData = parsed;
                localUpdatedAt = Number(parsed.updatedAt || 0) || 0;
            }
        }
    } catch (err) {
        console.warn('Rascunho local inválido; tentando servidor.', err);
    }

    if (PAGE_ID && PAGE_ID !== 'default') {
        try {
            const res = await fetch(`/construtor-sites/api/elements/${PAGE_ID}`);
            if (res.ok) {
                const elements = await res.json();
                const layoutEl = elements.find(e => e.type === '_layout');
                if (layoutEl?.content) {
                    try {
                        const parsedLayout = JSON.parse(layoutEl.content);
                        if (Array.isArray(parsedLayout)) {
                            // Se existe um rascunho local criado depois do
                            // último carregamento, ele é o estado correto para
                            // esta sessão. Caso contrário, usamos o servidor.
                            if (localData && localUpdatedAt > 0) {
                                pageContent = localData.pageContent;
                            } else {
                                pageContent = parsedLayout;
                            }
                            return;
                        }
                        console.warn('Conteúdo do servidor não está em formato de lista; tentando localStorage.');
                    } catch (parseErr) {
                        console.warn('Conteúdo do servidor está corrompido/truncado; tentando localStorage.', parseErr);
                    }
                }
            }
        } catch (err) {
            console.warn('Não foi possível carregar do servidor, usando localStorage.', err);
        }
    }

    if (localData?.pageContent && Array.isArray(localData.pageContent)) {
        pageContent = localData.pageContent;
    }
}

function bindGlobalWidgetDropSafety() {
    if (window.__sbcGlobalWidgetDropSafetyBound) return;
    window.__sbcGlobalWidgetDropSafetyBound = true;

    document.addEventListener('dragover', (e) => {
        if (window.__sbcUseDragV6) return;
        const widgetType = sidebarDraggedWidgetType || (() => {
            try { return e.dataTransfer.getData('widgetType'); } catch (_) { return ''; }
        })();
        if (!widgetType) return;

        const targetId = resolveDropTargetAtPoint(e.clientX, e.clientY, null);
        if (targetId) {
            activeDropTargetId = targetId;
            const targetEl = document.querySelector(`[data-id="${CSS.escape(targetId)}"]`);
            setDropHighlight(targetEl);
            e.dataTransfer.dropEffect = 'copy';
            e.preventDefault();
        }
    }, true);

    document.addEventListener('drop', (e) => {
        if (window.__sbcUseDragV6) return;
        const widgetType = sidebarDraggedWidgetType || (() => {
            try { return e.dataTransfer.getData('widgetType'); } catch (_) { return ''; }
        })();
        if (!widgetType) return;

        const targetId = activeDropTargetId
            || resolveDropTargetAtPoint(e.clientX, e.clientY, null)
            || normalizeDropTargetId(selectedInsertTargetId)
            || resolveSelectedInsertTarget();

        if (!targetId) return;

        // Impede que um handler de um widget filho transforme o drop em
        // criação na raiz. A inserção será feita exatamente no alvo salvo.
        e.preventDefault();
        e.stopPropagation();
        clearDropHighlights();
        addWidgetToContainer(widgetType, targetId);
    }, true);

    document.addEventListener('dragend', () => {
        activeDropTargetId = null;
        clearDropHighlights();
    }, true);
}

function bindCanvasDropOnce() {
    if (canvasDropBound) return;
    const canvas = document.getElementById('canvas');
    if (!canvas) return;

    canvas.addEventListener('dragover', (e) => {
        if (window.__sbcUseDragV6) return;
        e.preventDefault();
        e.stopPropagation();

        const widgetType = sidebarDraggedWidgetType || e.dataTransfer.getData('widgetType');
        const draggedId = widgetType ? null : draggedElementId;
        const targetId = resolveDropTargetAtPoint(e.clientX, e.clientY, draggedId)
            || resolveCanvasDropTarget(e, draggedId);

        if (targetId) {
            activeDropTargetId = targetId;
            const targetEl = document.querySelector(`[data-id="${CSS.escape(targetId)}"]`);
            setDropHighlight(targetEl);
            e.dataTransfer.dropEffect = widgetType ? 'copy' : 'move';
        } else {
            activeDropTargetId = null;
            clearDropHighlights();
            canvas.classList.add('drag-over');
        }
    });

    canvas.addEventListener('dragleave', (e) => {
        if (!canvas.contains(e.relatedTarget)) {
            canvas.classList.remove('drag-over');
            clearDropHighlights();
        }
    });

    canvas.addEventListener('drop', (e) => {
        if (window.__sbcUseDragV6) return;
        e.preventDefault();
        e.stopPropagation();
        canvas.classList.remove('drag-over');

        const widgetType = e.dataTransfer.getData('widgetType') || sidebarDraggedWidgetType;
        const sourceId = e.dataTransfer.getData('text/plain');
        const draggedId = widgetType ? null : sourceId;
        const targetId = activeDropTargetId
            || resolveDropTargetAtPoint(e.clientX, e.clientY, draggedId)
            || resolveCanvasDropTarget(e, draggedId);

        clearDropHighlights();

        if (widgetType) {
            const safeTargetId = targetId
                || normalizeDropTargetId(selectedInsertTargetId)
                || resolveSelectedInsertTarget();
            if (safeTargetId) {
                addWidgetToContainer(widgetType, safeTargetId);
            } else if (widgetType === 'container' || widgetType === 'section') {
                addWidgetFromSidebar(widgetType);
            } else {
                createSectionWithWidget(widgetType);
            }
        } else if (sourceId) {
            if (targetId) moveElementToContainer(sourceId, targetId);
            else moveElementToRoot(sourceId);
        }
    });

    canvasDropBound = true;
}

function showEditor() {
    document.getElementById('editor-view').classList.add('active');
    renderCanvas();
}

function dragSidebarWidget(e, type) {
    sidebarDraggedWidgetType = type;

    try {
        e.dataTransfer.setData('widgetType', type);
        e.dataTransfer.setData('text/plain', '');
        e.dataTransfer.effectAllowed = 'copy';
        e.dataTransfer.dropEffect = 'copy';
    } catch (err) {
        console.warn('Não foi possível preparar o arraste do widget.', err);
    }

    const source = e.currentTarget;
    source?.addEventListener('dragend', () => {
        setTimeout(() => { sidebarDraggedWidgetType = null; }, 0);
        clearDropHighlights();
        document.getElementById('canvas')?.classList.remove('drag-over');
    }, { once: true });
}

function openStructureModal(type) {
    currentStructureType = type;
    switchStructureTab(type);
    document.getElementById('structureModal').classList.add('active');
}

function switchStructureTab(type) {
    currentStructureType = type;
    document.getElementById('tab-section').className = type === 'section' ? 'btn-confirm' : 'btn-cancel';
    document.getElementById('tab-column').className = type === 'column' ? 'btn-confirm' : 'btn-cancel';
    renderStructureGrid();
}

function renderStructureGrid() {
    const grid = document.getElementById('structureGrid');
    grid.innerHTML = '';
    if (currentStructureType === 'section') {
        const structures = [
            { label: '1 Coluna', columns: [12] },
            { label: '2 Colunas', columns: [6, 6] },
            { label: '3 Colunas', columns: [4, 4, 4] },
            { label: '4 Colunas', columns: [3, 3, 3, 3] },
            { label: '2/3 + 1/3', columns: [8, 4] },
            { label: '1/3 + 2/3', columns: [4, 8] },
        ];
        structures.forEach(struct => {
            const option = document.createElement('div');
            option.className = 'template-card';
            option.onclick = () => createSectionFromStructure(struct);
            let previewHTML = '<div style="display:flex;gap:4px;margin-bottom:10px;height:40px;">';
            struct.columns.forEach(col => {
                previewHTML += `<div style="flex:${col};background:var(--accent);border-radius:4px;"></div>`;
            });
            previewHTML += '</div>';
            option.innerHTML = previewHTML + `<div style="font-size:12px;">${struct.label}</div>`;
            grid.appendChild(option);
        });
    } else {
        const structures = [
            { label: '1 Widget', widgets: 1 },
            { label: '2 Widgets', widgets: 2 },
            { label: '3 Widgets', widgets: 3 },
            { label: '4 Widgets', widgets: 4 },
        ];
        structures.forEach(struct => {
            const option = document.createElement('div');
            option.className = 'template-card';
            option.onclick = () => createColumnFromStructure(struct);
            let previewHTML = '<div style="display:flex;flex-direction:column;gap:4px;margin-bottom:10px;height:60px;">';
            for (let i = 0; i < struct.widgets; i++) {
                previewHTML += `<div style="flex:1;background:var(--success);border-radius:4px;"></div>`;
            }
            previewHTML += '</div>';
            option.innerHTML = previewHTML + `<div style="font-size:12px;">${struct.label}</div>`;
            grid.appendChild(option);
        });
    }
}

function createSectionFromStructure(structure) {
    closeModal('structureModal');
    saveState();
    const sectionId = 'el-' + Date.now();
    const section = {
        id: sectionId,
        type: 'section',
        content: '',
        styles: { padding: '40px 20px', minHeight: '100px', bgColor: '#ffffff' },
        children: []
    };
    structure.columns.forEach((colWidth, index) => {
        const columnId = 'el-' + (Date.now() + index);
        const column = {
            id: columnId,
            type: 'column',
            content: '',
            styles: { padding: '15px', flex: `${colWidth} 1 0%` },
            children: []
        };
        section.children.push(column);
    });
    pageContent.push(section);
    renderCanvas();
    saveData();
    showNotification(`Seção criada!`);
}

function createColumnFromStructure(structure) {
    closeModal('structureModal');
    if (!selectedElementId) {
        showNotification('Selecione uma seção primeiro!', 'error');
        return;
    }
    const parent = findElementById(pageContent, selectedElementId);
    if (!parent || !['section', 'column', 'container'].includes(parent.type)) {
        showNotification('Selecione uma seção, coluna ou container!', 'error');
        return;
    }
    const targetId = isDropContainer(parent.type) ? parent.id : (parent.children?.[0]?.id);
    if (!targetId) {
        showNotification('Selecione uma coluna válida!', 'error');
        return;
    }
    saveState();
    for (let i = 0; i < structure.widgets; i++) {
        const widgetId = 'el-' + (Date.now() + i);
        const widget = {
            id: widgetId,
            type: 'text',
            content: 'Lorem ipsum dolor sit amet.',
            styles: { fontSize: '16', fontFamily: 'Inter', color: '#666666', textAlign: 'left' },
            children: []
        };
        const target = findElementById(pageContent, targetId);
        if (target) {
            if (!target.children) target.children = [];
            target.children.push(widget);
        }
    }
    renderCanvas();
    saveData();
    showNotification(`Widgets adicionados!`);
}

function addContainer() {
    saveState();
    const containerId = 'el-' + Date.now();
    const container = {
        id: containerId,
        type: 'container',
        content: '',
        styles: { padding: '20px', minHeight: '80px', bgColor: '#f8f9fa' },
        children: []
    };
    pageContent.push(container);
    renderCanvas();
    saveData();
    showNotification('Container adicionado!');
}

function addWidget(type) {
    saveState();
    const id = 'el-' + Date.now();
    const el = { id, type, content: getDefaultContent(type), styles: getDefaultStyles(type), children: [] };
    
    if (selectedElementId) {
        const parent = findElementById(pageContent, selectedElementId);
        if (parent && isDropContainer(parent.type)) {
            if (!parent.children) parent.children = [];
            parent.children.push(el);
        } else if (parent?.type === 'section' && parent.children?.length) {
            parent.children[0].children = parent.children[0].children || [];
            parent.children[0].children.push(el);
        } else {
            pageContent.push(el);
        }
    } else {
        pageContent.push(el);
    }
    
    renderCanvas();
    saveData();
    showNotification(`${type} adicionado!`);
}

function getDefaultContent(type) {
    const contents = {
        heading: 'Título Principal',
        text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        image: 'https://placehold.co/600x400',
        button: 'Clique Aqui',
        video: '',
        bannerAd: '',
        videoAd: '',
        adEmbed: '',
        icon: 'fa-star',
        divider: '',
        spacer: '50',
        form: '<h3>Formulário</h3><input placeholder="Nome"><input placeholder="Email"><textarea placeholder="Mensagem"></textarea><button>Enviar</button>',
        faq: '<h3>Perguntas Frequentes</h3><div><strong>Pergunta 1?</strong><p>Resposta 1</p></div>',
        testimonials: '<h3>Depoimentos</h3><p>"Excelente serviço!" - Maria</p>',
        pricing: '<h3>Planos</h3><div>Básico - R$ 29</div>',
        counters: '<h3>Contadores</h3><div>500+ Clientes</div>',
        features: '<h3>Recursos</h3><div>Rápido, Seguro, Responsivo</div>',
        carousel: '<h3>Carrossel</h3><div>Slide 1 | Slide 2 | Slide 3</div>',
        gallery: '<h3>Galeria</h3><div>Imagem 1 | Imagem 2 | Imagem 3</div>',
        team: '<h3>Equipe</h3><div>João - CEO | Maria - Designer</div>',
        timeline: '<h3>Timeline</h3><div>2020: Início | 2021: Crescimento</div>',
        tabs: '<h3>Tabs</h3><div>Tab 1 | Tab 2 | Tab 3</div>',
        tabs: {
            title: 'Abas',
            description: 'Organize seu conteúdo em abas.',
            activeIndex: 0,
            alignment: 'left',
            width: '100', widthUnit: '%',
            tabStyle: 'underline',
            tabBg: '#f8fafc', tabActiveBg: '#ffffff', tabColor: '#475569', tabActiveColor: '#3b82f6',
            contentBg: '#ffffff', contentColor: '#374151',
            borderColor: '#e5e7eb', borderWidth: '1', radius: '10', padding: '18', gap: '4',
            items: [
                {title:'Aba 1', content:'Conteúdo da primeira aba.'},
                {title:'Aba 2', content:'Conteúdo da segunda aba.'},
                {title:'Aba 3', content:'Conteúdo da terceira aba.'}
            ]
        },
        accordion: '<h3>Accordion</h3><div><strong>Item 1</strong><p>Conteúdo 1</p></div>',
        progress: '<h3>Progresso</h3><div>Desenvolvimento: 85%</div>',
        newsletter: '<h3>Newsletter</h3><input placeholder="Email"><button>Assinar</button>',
        hero: '<h1>Bem-vindo</h1><p>Crie sites incríveis</p><button>Começar Agora</button>',
        navbar: '<div style="display:flex;justify-content:space-between;padding:16px;"><strong>Logo</strong><span>Home | Sobre | Contato</span></div>',
        footer: '<div style="text-align:center;padding:20px;">© 2026 Empresa</div>',
        logo: '<div style="font-size:24px;font-weight:800;font-family:Poppins;">Logo</div>'
    };
    return contents[type] || '';
}

function getDefaultStyles(type) {
    const styles = {
        heading: { fontSize: '32', fontFamily: 'Poppins', color: '#212529', textAlign: 'left', alignment: 'left', textTransform: 'none', textDecoration: 'none', textShadow: false, textShadowX: '0', textShadowY: '2', textShadowBlur: '8', textShadowColor: 'rgba(0,0,0,0.15)' },
        text: { fontSize: '16', fontFamily: 'Inter', color: '#666666', textAlign: 'left', alignment: 'left', textTransform: 'none', textDecoration: 'none', textShadow: false, textShadowX: '0', textShadowY: '1', textShadowBlur: '4', textShadowColor: 'rgba(0,0,0,0.12)' },
        image: { imageWidth: '100', imageHeight: 'auto', objectFit: 'cover', borderRadius: '8', borderRadiusTL: '8', borderRadiusTR: '8', borderRadiusBR: '8', borderRadiusBL: '8', opacity: '1', align: 'center', link: '', targetBlank: false, alt: 'Imagem', loading: 'lazy', shadow: false, shadowX: '0', shadowY: '6', shadowBlur: '18', shadowSpread: '0', shadowColor: 'rgba(0,0,0,0.18)', hoverEffect: 'none', hoverScale: '1.03', hoverTransition: '0.3', grayscale: '0', brightness: '1', contrast: '1', saturate: '1' },
        logo: { imageUrl: '', alt: 'Logo', text: 'Minha Empresa', logoType: 'image', height: '54', maxWidth: '260', alignment: 'left', link: '', targetBlank: false, textColor: '#111827', fontSize: '32', fontWeight: '800', letterSpacing: '0', textTransform: 'none', radius: '0', opacity: '1', shadow: false, shadowColor: 'rgba(0,0,0,0.18)', shadowX: '0', shadowY: '4', shadowBlur: '12', shadowSpread: '0' },
        button: { bgColor: '#3b82f6', bgColorHover: '#2563eb', textColor: '#ffffff', textColorHover: '#ffffff', borderColor: 'transparent', borderColorHover: 'transparent', borderStyle: 'solid', borderWidth: '0', borderRadius: '8', borderRadiusTL: '8', borderRadiusTR: '8', borderRadiusBR: '8', borderRadiusBL: '8', fontSize: '16', fontFamily: 'Inter', fontWeight: '600', letterSpacing: '0', lineHeight: '1.2', paddingTop: '12', paddingRight: '24', paddingBottom: '12', paddingLeft: '24', width: 'auto', widthUnit: 'px', height: 'auto', align: 'left', icon: '', iconPosition: 'before', iconGap: '8', shadow: false, shadowX: '0', shadowY: '4', shadowBlur: '12', shadowSpread: '0', shadowColor: 'rgba(0,0,0,0.18)', hoverTransform: 'none', hoverShadow: true, hoverScale: '1.02', hoverTransition: '0.25', opacity: '1', gradient: false, gradientEnd: '#60a5fa', gradientDirection: 'to right', link: '', targetBlank: false, textDecoration: 'none' },
        icon: { icon: 'fa-star', iconColor: '#3b82f6', iconSize: '48', alignment: 'center', iconBgColor: 'transparent', iconPadding: '0', iconMarginTop: '0', iconMarginBottom: '0', iconRadius: '8', iconOpacity: '1' },
        divider: { 
            color: '#cbd5e1', 
            thickness: '2', 
            lineStyle: 'solid', 
            margin: '40', 
            width: '100', 
            alignment: 'center',
            useGradient: false,
            gradientStart: '#3b82f6',
            gradientEnd: '#8b5cf6',
            gradientDirection: 'to right',
            opacity: '100',
            shadow: false,
            shadowX: '0',
            shadowY: '2',
            shadowBlur: '6',
            shadowSpread: '0',
            shadowColor: 'rgba(0,0,0,0.2)',
            height: '0',
            decorStyle: 'simple',
            decorContent: 'fa-star',
            decorType: 'icon',
            decorBgColor: '#ffffff',
            decorTextColor: '#3b82f6',
            decorSize: '24',
            decorAnimation: 'none',
            decorPadding: '12',
            decorFontWeight: '600',
            decorLetterSpacing: '0',
            decorTextTransform: 'none'
        },
        spacer: { height: '50', heightTablet: '40', heightMobile: '30', visibleDesktop: true, visibleTablet: true, visibleMobile: true },
        pricing: {
            title: 'Planos e preços',
            description: 'Escolha o plano ideal para você.',
            plans: [
                { name: 'Básico', price: '29', period: '/mês', description: 'Para começar', features: ['1 site', 'Suporte básico', 'Recursos essenciais'], buttonText: 'Escolher plano', buttonLink: '#', highlighted: false, badge: 'Mais escolhido' },
                { name: 'Profissional', price: '59', period: '/mês', description: 'Para empresas', features: ['5 sites', 'Suporte prioritário', 'Recursos avançados'], buttonText: 'Escolher plano', buttonLink: '#', highlighted: true, badge: 'Mais popular' },
                { name: 'Premium', price: '99', period: '/mês', description: 'Para equipes', features: ['Sites ilimitados', 'Suporte premium', 'Todos os recursos'], buttonText: 'Escolher plano', buttonLink: '#', highlighted: false, badge: 'Premium' }
            ],
            width: '100', widthUnit: '%', alignment: 'center', columns: '3', gap: '20',
            titleColor: '#111827', titleFontSize: '30', descriptionColor: '#6b7280', descriptionFontSize: '14',
            cardBg: '#ffffff', cardBorderStyle: 'solid', cardBorderWidth: '1', cardBorderColor: '#e5e7eb', cardRadius: '16', cardPadding: '24',
            planNameColor: '#111827', planNameFontSize: '18', descriptionPlanColor: '#6b7280', descriptionPlanFontSize: '13',
            priceColor: '#111827', priceFontSize: '36', periodColor: '#6b7280', featureColor: '#374151', checkColor: '#22c55e',
            buttonBg: '#111827', buttonTextColor: '#ffffff', buttonBgHover: '#2563eb', buttonRadius: '9', buttonPaddingY: '11', buttonPaddingX: '16',
            highlightedBg: '#ffffff', highlightedBorderColor: '#3b82f6', highlightedBorderWidth: '2', badgeBg: '#3b82f6', badgeColor: '#ffffff',
            shadow: true, shadowX: '0', shadowY: '5', shadowBlur: '18', shadowSpread: '0', shadowColor: 'rgba(0,0,0,0.06)',
            highlightedShadow: 'rgba(59,130,246,0.12)', hoverBg: '#f8fafc', hoverLift: '3'
        },
        counters: {
            title: 'Nossos números', description: 'Resultados que mostram nossa experiência.', width: '100', widthUnit: '%', alignment: 'center', columns: '3', gap: '20',
            titleColor: '#111827', titleFontSize: '28', descriptionColor: '#6b7280', descriptionFontSize: '14',
            numberColor: '#111827', numberFontSize: '42', numberWeight: '800', labelColor: '#4b5563', labelFontSize: '14',
            prefixColor: '#111827', suffixColor: '#111827', iconColor: '#3b82f6', iconSize: '28',
            cardBg: '#ffffff', cardBorderStyle: 'solid', cardBorderWidth: '1', cardBorderColor: '#e5e7eb', cardRadius: '14', cardPadding: '24',
            shadow: false, shadowX: '0', shadowY: '6', shadowBlur: '18', shadowSpread: '0', shadowColor: 'rgba(0,0,0,0.10)',
            hoverBg: '#f8fafc', hoverLift: '3', animate: true, duration: '1200', separator: false, separatorColor: '#e5e7eb',
            items: [
                { value: '500', prefix: '', suffix: '+', label: 'Clientes', icon: 'fa-users' },
                { value: '120', prefix: '', suffix: '+', label: 'Projetos', icon: 'fa-briefcase' },
                { value: '98', prefix: '', suffix: '%', label: 'Satisfação', icon: 'fa-heart' }
            ]
        },
        features: {
            title: 'Por que escolher nossos serviços?',
            description: 'Confira os principais benefícios que oferecemos.',
            width: '100', widthUnit: '%', alignment: 'center', columns: '3', gap: '20', sectionBg: '#ffffff', sectionRadius: '0', sectionPaddingY: '0',
            titleColor: '#111827', titleFontSize: '28', descriptionColor: '#6b7280', descriptionFontSize: '14',
            cardBg: '#ffffff', cardBorderStyle: 'solid', cardBorderWidth: '1', cardBorderColor: '#e5e7eb', cardRadius: '14', cardPadding: '24',
            iconColor: '#3b82f6', iconBg: '#eff6ff', iconSize: '28', iconBoxSize: '76', iconRadius: '50', iconBorderStyle: 'none', iconBorderWidth: '0', iconBorderColor: '#dbeafe', iconHoverAnimation: 'none',
            nameColor: '#111827', nameFontSize: '18', textColor: '#4b5563', textFontSize: '14', cardTextAlign: 'center',
            shadow: false, shadowX: '0', shadowY: '6', shadowBlur: '18', shadowSpread: '0', shadowColor: 'rgba(0,0,0,0.10)',
            hoverBg: '#f8fafc', hoverLift: '3',
            items: [
                { name: 'Rápido', description: 'Seu site pronto com agilidade.', icon: 'fa-bolt' },
                { name: 'Seguro', description: 'Boas práticas e proteção para o seu projeto.', icon: 'fa-shield-halved' },
                { name: 'Responsivo', description: 'Visual perfeito em computador e celular.', icon: 'fa-mobile-screen' }
            ]
        },
        team: {
            title: 'Nossa equipe', description: 'Conheça as pessoas por trás do nosso trabalho.',
            width: '100', widthUnit: '%', alignment: 'center', columns: '3', gap: '20',
            titleColor: '#111827', titleFontSize: '30', descriptionColor: '#6b7280', descriptionFontSize: '14',
            cardBg: '#ffffff', cardBorderStyle: 'solid', cardBorderWidth: '1', cardBorderColor: '#e5e7eb', cardRadius: '14', cardPadding: '24',
            nameColor: '#111827', nameFontSize: '18', roleColor: '#6b7280', roleFontSize: '13', textColor: '#4b5563', textFontSize: '14',
            avatarSize: '84', avatarRadius: '50', avatarBg: '#eef2f7',
            shadow: false, shadowX: '0', shadowY: '6', shadowBlur: '18', shadowSpread: '0', shadowColor: 'rgba(0,0,0,0.10)',
            hoverBg: '#f8fafc', hoverLift: '3',
            items: [
                { name: 'João Silva', role: 'CEO', description: 'Especialista em estratégia e inovação.', avatar: '', linkedin: '', instagram: '', facebook: '', website: '' },
                { name: 'Maria Souza', role: 'Designer', description: 'Responsável por experiências digitais e visuais.', avatar: '', linkedin: '', instagram: '', facebook: '', website: '' },
                { name: 'Ana Costa', role: 'Desenvolvedora', description: 'Transforma ideias em projetos rápidos e eficientes.', avatar: '', linkedin: '', instagram: '', facebook: '', website: '' }
            ]
        },
        gallery: {
            title: 'Nossa galeria', description: 'Confira alguns dos nossos trabalhos.', width: '100', widthUnit: '%', alignment: 'center', columns: '3', gap: '16', rowGap: '16', imageHeight: '220', objectFit: 'cover', borderRadius: '12', borderWidth: '0', borderStyle: 'none', borderColor: '#e5e7eb', opacity: '1',
            titleColor: '#111827', titleFontSize: '28', descriptionColor: '#6b7280', descriptionFontSize: '14',
            caption: true, lightbox: true, linkOpen: false, shadow: false, shadowX: '0', shadowY: '6', shadowBlur: '18', shadowSpread: '0', shadowColor: 'rgba(0,0,0,0.14)', hover: 'zoom', hoverScale: '1.03',
            images: [
                {src:'https://placehold.co/800x600?text=Imagem+1', alt:'Imagem 1', title:'Imagem 1', link:''},
                {src:'https://placehold.co/800x600?text=Imagem+2', alt:'Imagem 2', title:'Imagem 2', link:''},
                {src:'https://placehold.co/800x600?text=Imagem+3', alt:'Imagem 3', title:'Imagem 3', link:''}
            ]
        },
        carousel: {
            title: 'Carrossel', description: 'Confira nossos destaques.', width: '100', widthUnit: '%', alignment: 'center',
            slides: [
                {src:'https://placehold.co/1200x600?text=Slide+1',alt:'Slide 1',title:'Slide 1',text:'Descrição do primeiro slide.',buttonText:'Saiba mais',buttonLink:'#'},
                {src:'https://placehold.co/1200x600?text=Slide+2',alt:'Slide 2',title:'Slide 2',text:'Descrição do segundo slide.',buttonText:'Conheça',buttonLink:'#'},
                {src:'https://placehold.co/1200x600?text=Slide+3',alt:'Slide 3',title:'Slide 3',text:'Descrição do terceiro slide.',buttonText:'Ver detalhes',buttonLink:'#'}
            ],
            slidesVisible:'1', gap:'16', autoplay:false, autoplayInterval:'4000', pauseOnHover:true, loop:true, showArrows:true, showDots:true,
            transition:'slide', transitionDuration:'500', slideHeight:'360', objectFit:'cover', borderRadius:'14', shadow:false, shadowX:'0', shadowY:'6', shadowBlur:'18', shadowSpread:'0', shadowColor:'rgba(0,0,0,0.14)',
            overlay:true, overlayColor:'rgba(0,0,0,0.35)', titleColor:'#ffffff', textColor:'#ffffff', buttonBg:'#3b82f6', buttonColor:'#ffffff'
        },
        video: { videoUrl: '', videoSource: 'auto', width: '100', widthUnit: '%', aspectRatio: '16:9', objectFit: 'cover', controls: true, autoplay: false, muted: false, loop: false, borderRadius: '8', poster: '', alignment: 'center', opacity: '1', shadow: false, shadowX: '0', shadowY: '6', shadowBlur: '18', shadowSpread: '0', shadowColor: 'rgba(0,0,0,0.18)' },
        bannerAd: { imageUrl: 'https://placehold.co/1200x400?text=Banner+Publicidade', link: '', targetBlank: true, title: 'Espaço publicitário', description: 'Seu banner aqui', buttonText: '', buttonLink: '', width: '100', widthUnit: '%', height: '220', alignment: 'center', objectFit: 'cover', borderRadius: '12', overlay: true, overlayColor: 'rgba(0,0,0,.25)', titleColor: '#ffffff', titleFontSize: '28', descriptionColor: '#ffffff', descriptionFontSize: '15', buttonBg: '#3b82f6', buttonColor: '#ffffff', opacity: '1', shadow: false, shadowX: '0', shadowY: '6', shadowBlur: '18', shadowSpread: '0', shadowColor: 'rgba(0,0,0,.18)', autoplay: false, autoplayInterval: '5000', pauseOnHover: true, transition: 'fade', bannerSlides: [] },
        videoAd: { videoUrl: '', videoSource: 'auto', poster: '', link: '', targetBlank: true, title: 'Publicidade em vídeo', description: '', buttonText: 'Saiba mais', buttonLink: '', width: '100', widthUnit: '%', aspectRatio: '16:9', objectFit: 'cover', controls: true, autoplay: false, muted: true, loop: false, borderRadius: '12', alignment: 'center', overlay: true, overlayColor: 'rgba(0,0,0,.28)', titleColor: '#ffffff', descriptionColor: '#ffffff', buttonBg: '#3b82f6', buttonColor: '#ffffff', opacity: '1', shadow: false, shadowX: '0', shadowY: '6', shadowBlur: '18', shadowSpread: '0', shadowColor: 'rgba(0,0,0,.18)' },
        adEmbed: { code: '', iframeUrl: '', width: '100', widthUnit: '%', height: '250', alignment: 'center', background: '#ffffff', borderRadius: '8', borderWidth: '1', borderColor: '#e5e7eb', opacity: '1' },
        form: { formTitle: 'Fale conosco', formDescription: 'Preencha os dados abaixo e entraremos em contato.', buttonText: 'Enviar mensagem', alignment: 'left', width: '100', widthUnit: '%', columns: '1', labelColor: '#374151', textColor: '#111827', placeholderColor: '#9ca3af', inputBg: '#ffffff', inputBorderColor: '#d1d5db', inputBorderWidth: '1', inputRadius: '8', inputPaddingY: '11', inputPaddingX: '13', inputFontSize: '14', buttonBg: '#3b82f6', buttonBgHover: '#2563eb', buttonTextColor: '#ffffff', buttonRadius: '8', buttonPaddingY: '12', buttonPaddingX: '20', buttonFontSize: '15', buttonFullWidth: false, titleColor: '#111827', titleFontSize: '26', descriptionColor: '#6b7280', descriptionFontSize: '14', cardBg: '#ffffff', cardBorderStyle: 'none', cardBorderWidth: '0', cardBorderColor: '#e5e7eb', cardRadius: '12', cardPadding: '24', shadow: false, shadowX: '0', shadowY: '8', shadowBlur: '24', shadowSpread: '0', shadowColor: 'rgba(0,0,0,0.12)' },
        hero: {
            title: 'Transforme suas ideias em realidade',
            description: 'Uma apresentação impactante para destacar seu negócio, produto ou projeto.',
            buttonText: 'Começar agora',
            buttonLink: '#',
            buttonTargetBlank: false,
            bgType: 'gradient',
            bgColor: '#0f172a',
            bgColor2: '#2563eb',
            gradientDirection: 'to right',
            bgImage: '',
            overlay: true,
            overlayColor: 'rgba(0,0,0,0.28)',
            contentAlign: 'center',
            minHeight: '480',
            paddingY: '70',
            paddingX: '24',
            contentWidth: '100',
            titleColor: '#ffffff',
            titleFontSize: '52',
            titleWeight: '800',
            descriptionColor: '#e2e8f0',
            descriptionFontSize: '18',
            descriptionLineHeight: '1.6',
            buttonBg: '#3b82f6',
            buttonBgHover: '#2563eb',
            buttonColor: '#ffffff',
            buttonRadius: '10',
            buttonPaddingY: '13',
            buttonPaddingX: '24',
            buttonFontSize: '15',
            borderRadius: '0',
            shadow: false,
            shadowX: '0', shadowY: '8', shadowBlur: '24', shadowSpread: '0', shadowColor: 'rgba(0,0,0,0.18)'
        },
        navbar: {
            brand: 'Minha Empresa',
            logoUrl: '',
            logoAlt: 'Logo',
            logoHeight: '34',
            items: [
                {label:'Início', link:'#'},
                {label:'Sobre', link:'#sobre'},
                {label:'Serviços', link:'#servicos'},
                {label:'Contato', link:'#contato'}
            ],
            alignment: 'space-between',
            width: '100', widthUnit: '%',
            navBg: '#ffffff',
            navColor: '#1f2937',
            navHoverColor: '#3b82f6',
            navActiveColor: '#3b82f6',
            navBorderColor: '#e5e7eb',
            navBorderWidth: '1',
            navRadius: '0',
            navPaddingY: '14',
            navPaddingX: '20',
            itemGap: '24',
            fontSize: '14',
            fontWeight: '600',
            linkUnderline: 'none',
            shadow: false,
            shadowX: '0', shadowY: '4', shadowBlur: '14', shadowSpread: '0', shadowColor: 'rgba(0,0,0,0.08)',
            mobileBreakpoint: '1024',
            mobileMenu: 'hamburger',
            mobileButtonType: 'icon',
            mobileIcon: 'bars',
            mobilePosition: 'right',
            mobileButtonSize: '18',
            mobileButtonWidth: '42',
            mobileButtonHeight: '38',
            mobileButtonRadius: '8',
            mobileButtonBg: '#ffffff',
            mobileButtonColor: '#1f2937',
            mobileBg: '#ffffff',
            mobileItemAlign: 'left',
            mobileItemGap: '4',
            mobileItemPaddingY: '10',
            sticky: false
        },
        footer: {
            brand: 'Minha Empresa',
            logoUrl: '',
            logoAlt: 'Logo',
            description: 'Uma empresa focada em qualidade, inovação e resultados.',
            columns: [
                { title: 'Empresa', items: [
                    { label: 'Sobre nós', link: '#sobre' },
                    { label: 'Serviços', link: '#servicos' },
                    { label: 'Contato', link: '#contato' }
                ] },
                { title: 'Suporte', items: [
                    { label: 'FAQ', link: '#faq' },
                    { label: 'Privacidade', link: '#privacidade' },
                    { label: 'Termos', link: '#termos' }
                ] }
            ],
            contactTitle: 'Contato',
            email: 'contato@empresa.com',
            phone: '(00) 0000-0000',
            address: 'Sua cidade - SP',
            showSocial: true,
            instagram: '',
            facebook: '',
            linkedin: '',
            youtube: '',
            copyright: '© 2026 Minha Empresa. Todos os direitos reservados.',
            alignment: 'left',
            width: '100', widthUnit: '%',
            footerBg: '#111827',
            footerColor: '#f9fafb',
            mutedColor: '#9ca3af',
            accentColor: '#3b82f6',
            borderColor: '#243244',
            borderWidth: '1',
            paddingY: '48',
            paddingX: '24',
            contentWidth: '1200',
            logoHeight: '34',
            titleFontSize: '15',
            textFontSize: '14',
            copyrightFontSize: '12',
            columnGap: '36',
            radius: '0',
            shadow: false,
            shadowX: '0', shadowY: '-4', shadowBlur: '18', shadowSpread: '0', shadowColor: 'rgba(0,0,0,0.18)',
            stackMobile: true
        },
        newsletter: {
            title: 'Receba nossas novidades', description: 'Assine nossa newsletter e fique por dentro das novidades.',
            width: '100', widthUnit: '%', alignment: 'center',
            layout: 'inline', inputType: 'email', showName: false,
            emailPlaceholder: 'Seu melhor e-mail', namePlaceholder: 'Seu nome', buttonText: 'Assinar newsletter', successMessage: 'Obrigado! Inscrição realizada com sucesso.',
            actionUrl: '#', targetBlank: false,
            sectionBg: '#f8fafc', cardBg: '#ffffff', titleColor: '#111827', descriptionColor: '#6b7280',
            inputBg: '#ffffff', inputColor: '#111827', placeholderColor: '#9ca3af', inputBorderColor: '#d1d5db', inputBorderWidth: '1',
            buttonBg: '#3b82f6', buttonColor: '#ffffff', buttonBgHover: '#2563eb',
            titleFontSize: '28', descriptionFontSize: '14', inputFontSize: '14', buttonFontSize: '14',
            inputHeight: '44', buttonHeight: '44', inputRadius: '8', buttonRadius: '8', gap: '10',
            cardRadius: '16', cardPadding: '26', sectionPaddingY: '0', sectionRadius: '0',
            shadow: false, shadowX: '0', shadowY: '8', shadowBlur: '24', shadowSpread: '0', shadowColor: 'rgba(0,0,0,0.10)',
            consent: '', consentColor: '#6b7280', consentFontSize: '12'
        },
        faq: { title: 'Perguntas Frequentes', description: 'Encontre respostas para as dúvidas mais comuns.', width: '100', widthUnit: '%', alignment: 'left', questionBg: '#f8fafc', questionHoverBg: '#eef4ff', questionOpenBg: '#eef4ff', questionColor: '#111827', questionOpenColor: '#111827', questionFontSize: '15', answerBg: '#ffffff', answerColor: '#4b5563', answerFontSize: '14', answerLineHeight: '1.65', borderColor: '#e5e7eb', borderWidth: '1', radius: '10', itemSpacing: '10', paddingY: '16', paddingX: '18', titleColor: '#111827', titleFontSize: '28', descriptionColor: '#6b7280', descriptionFontSize: '14', iconColor: '#3b82f6', iconType: 'chevron', iconPosition: 'right', multipleOpen: false, shadow: false, shadowX: '0', shadowY: '4', shadowBlur: '16', shadowSpread: '0', shadowColor: 'rgba(0,0,0,0.10)' }
        ,accordion: { title: 'Perguntas e respostas', description: 'Organize informações em blocos expansíveis.', width: '100', widthUnit: '%', alignment: 'left', questionBg: '#ffffff', questionHoverBg: '#f8fafc', questionOpenBg: '#eff6ff', questionColor: '#111827', questionOpenColor: '#1d4ed8', questionFontSize: '15', answerBg: '#ffffff', answerColor: '#4b5563', answerFontSize: '14', answerLineHeight: '1.65', borderColor: '#e5e7eb', borderWidth: '1', radius: '10', itemSpacing: '10', paddingY: '16', paddingX: '18', titleColor: '#111827', titleFontSize: '28', descriptionColor: '#6b7280', descriptionFontSize: '14', iconColor: '#3b82f6', iconType: 'plus', iconPosition: 'right', multipleOpen: false, shadow: false, shadowX: '0', shadowY: '4', shadowBlur: '16', shadowSpread: '0', shadowColor: 'rgba(0,0,0,0.10)', items: [ {question:'O que é este serviço?', answer:'Explique aqui os detalhes do serviço.', open:true}, {question:'Como funciona?', answer:'Descreva de forma simples como funciona.', open:false}, {question:'Posso editar depois?', answer:'Sim. Você pode editar conteúdo e aparência a qualquer momento.', open:false} ] }
        ,progress: {
            title: 'Nossos conhecimentos', description: 'Veja nosso nível de experiência em diferentes áreas.',
            width: '100', widthUnit: '%', alignment: 'left',
            titleColor: '#111827', titleFontSize: '28', descriptionColor: '#6b7280', descriptionFontSize: '14',
            labelColor: '#111827', labelFontSize: '14', labelWeight: '600', valueColor: '#475569', valueFontSize: '13',
            trackColor: '#e5e7eb', fillColor: '#3b82f6', fillColorEnd: '#60a5fa',
            barHeight: '12', radius: '999', itemSpacing: '18', paddingY: '0', paddingX: '0',
            barStyle: 'solid', showPercent: true, showValueOnTrack: false,
            animate: true, animationDuration: '900', shadow: false, shadowColor: 'rgba(0,0,0,0.10)', shadowBlur: '8',
            items: [
                {label:'Desenvolvimento Web', value:'90'},
                {label:'WordPress', value:'85'},
                {label:'UI/UX Design', value:'75'}
            ]
        }
        ,timeline: {
            title: 'Nossa história',
            description: 'Conheça os principais momentos da nossa trajetória.',
            width: '100', widthUnit: '%', alignment: 'center',
            orientation: 'vertical', columns: '1', gap: '28',
            lineColor: '#dbe3ef', lineWidth: '3', markerBg: '#3b82f6', markerColor: '#ffffff',
            cardBg: '#ffffff', cardBorderStyle: 'solid', cardBorderWidth: '1', cardBorderColor: '#e5e7eb',
            cardRadius: '14', cardPadding: '20',
            titleColor: '#111827', titleFontSize: '28', descriptionColor: '#6b7280', descriptionFontSize: '14',
            dateColor: '#3b82f6', dateFontSize: '13', itemTitleColor: '#111827', itemTitleFontSize: '18',
            itemTextColor: '#4b5563', itemTextFontSize: '14',
            shadow: false, shadowX: '0', shadowY: '6', shadowBlur: '18', shadowSpread: '0', shadowColor: 'rgba(0,0,0,0.10)',
            items: [
                {date:'2024', title:'Fundação da empresa', text:'Iniciamos nossa jornada com foco em qualidade e inovação.', icon:'fa-flag'},
                {date:'2025', title:'Primeiro grande projeto', text:'Conquistamos novos clientes e ampliamos nossa atuação.', icon:'fa-rocket'},
                {date:'2026', title:'Expansão', text:'Novos produtos, novos desafios e uma equipe ainda maior.', icon:'fa-chart-line'}
            ]
        }
        ,testimonials: {
            title: 'O que nossos clientes dizem',
            description: 'Veja algumas experiências de quem já confiou em nosso trabalho.',
            width: '100', widthUnit: '%', alignment: 'center',
            columns: '3', gap: '20',
            cardBg: '#ffffff', cardBorderStyle: 'solid', cardBorderWidth: '1', cardBorderColor: '#e5e7eb',
            cardRadius: '14', cardPadding: '24',
            textColor: '#374151', nameColor: '#111827', roleColor: '#6b7280', starColor: '#f59e0b',
            textFontSize: '15', nameFontSize: '16', roleFontSize: '13',
            avatarSize: '56', avatarRadius: '50',
            shadow: true, shadowX: '0', shadowY: '6', shadowBlur: '18', shadowSpread: '0', shadowColor: 'rgba(0,0,0,0.10)',
            hoverBg: '#f8fafc', hoverLift: '3',
            titleColor: '#111827', titleFontSize: '30', descriptionColor: '#6b7280', descriptionFontSize: '14',
            items: [
                { text: 'Excelente atendimento e resultado acima do esperado.', name: 'Maria Silva', role: 'Cliente', avatar: '', rating: 5 },
                { text: 'Equipe profissional, rápida e muito atenciosa.', name: 'João Souza', role: 'Empresário', avatar: '', rating: 5 },
                { text: 'Nos ajudaram a colocar nosso projeto no ar com muita qualidade.', name: 'Ana Costa', role: 'Cliente', avatar: '', rating: 5 }
            ]
        }
    };
    return styles[type] || {};
}

function renderCanvas() {
    normalizeBeforeCanvasRender();
    const canvas = document.getElementById('canvas');
    if (!canvas) return;
    canvas.innerHTML = '';

    if (pageContent.length === 0) {
        canvas.innerHTML = '<p style="text-align:center;color:#999;padding:40px;">Arraste widgets da sidebar para dentro das colunas ou crie uma seção</p>';
        return;
    }

    pageContent.forEach(el => {
        canvas.appendChild(createDOMElement(el));
    });

    if (selectedElementId) {
        const selectedEl = document.querySelector(`[data-id="${selectedElementId}"]`);
        if (selectedEl) selectedEl.classList.add('selected');
    }

    if (selectedPart?.id) {
        const partEl = document.querySelector(`[data-id="${selectedPart.id}"] [data-sbc-part="${selectedPart.key}"]`);
        if (partEl) partEl.classList.add('sbc-part-selected');
    }

    // O DOM é recriado sempre que o editor renderiza. Reaplicamos o modo
    // responsivo depois da renderização para que nenhum estilo inline antigo
    // volte a apertar os elementos no Mobile/Tablet.
    const mode = canvas.classList.contains('mobile')
        ? 'mobile'
        : (canvas.classList.contains('tablet') ? 'tablet' : 'desktop');
    requestAnimationFrame(() => { applyResponsiveEngine(canvas, mode); pageContent.forEach(el => { const node = canvas.querySelector(`[data-id=\"${CSS.escape(el.id)}\"]`); if (node) applyElementResponsiveStyles(node, el, mode); }); });
}

function createSectionWithWidget(widgetType) {
    saveState();
    const sectionId = 'el-' + Date.now();
    const columnId = 'el-' + (Date.now() + 1);
    const widgetId = 'el-' + (Date.now() + 2);
    const section = {
        id: sectionId,
        type: 'section',
        content: '',
        styles: { padding: '40px 20px', minHeight: '100px', bgColor: '#ffffff' },
        children: [{
            id: columnId,
            type: 'column',
            content: '',
            styles: { padding: '15px', flex: '12 1 0%' },
            children: [{
                id: widgetId,
                type: widgetType,
                content: getDefaultContent(widgetType),
                styles: getDefaultStyles(widgetType),
                children: []
            }]
        }]
    };
    pageContent.push(section);
    renderCanvas();
    saveData();
    showNotification(`${widgetType} adicionado em nova seção!`);
}



function resolveSelectedInsertTarget() {
    if (!selectedElementId) return null;

    const selected = findElementById(pageContent, selectedElementId);
    if (!selected) return null;

    if (selected.type === 'column' || selected.type === 'container') {
        return selected.id;
    }

    if (selected.type === 'section') {
        const child = (selected.children || []).find(item => item.type === 'column' || item.type === 'container');
        return child ? child.id : selected.id;
    }

    // Se um widget interno estiver selecionado, usa o pai de inserção.
    const parent = findParentOfId(pageContent, selected.id);
    if (parent?.type === 'column' || parent?.type === 'container') return parent.id;
    if (parent?.type === 'section') {
        const child = (parent.children || []).find(item => item.type === 'column' || item.type === 'container');
        return child ? child.id : parent.id;
    }

    return null;
}

function insertWidgetIntoSelectedTarget(type) {
    let targetId = selectedInsertTargetId || resolveSelectedInsertTarget();

    if (!targetId && selectedElementId) {
        const selected = findElementById(pageContent, selectedElementId);
        if (selected?.type === 'section') {
            const column = (selected.children || []).find(x => x.type === 'column');
            if (column) {
                targetId = column.id;
            } else {
                if (!selected.children) selected.children = [];
                const newColumn = {
                    id: 'el-column-' + Date.now() + '-' + Math.random().toString(36).slice(2, 8),
                    type: 'column',
                    content: '',
                    styles: { padding: '15px', flex: '1 1 0%' },
                    children: []
                };
                selected.children.push(newColumn);
                targetId = newColumn.id;
            }
        }
    }

    if (!targetId) {
        createSectionWithWidget(type);
        return;
    }

    const target = findElementById(pageContent, targetId);
    if (!target || !['column', 'container'].includes(target.type)) {
        createSectionWithWidget(type);
        return;
    }

    saveState();

    const newWidget = {
        id: 'el-widget-' + Date.now() + '-' + Math.random().toString(36).slice(2, 8),
        type,
        content: getDefaultContent(type),
        styles: getDefaultStyles(type),
        children: []
    };

    if (!Array.isArray(target.children)) target.children = [];
    target.children.push(newWidget);

    // Mantém a seção/coluna escolhida como alvo de inserção.
    selectedInsertTargetId = target.id;
    selectedElementId = target.id;

    renderCanvas();
    updateProperties(target.id);
    saveData();
    showNotification(`${type} adicionado!`);
}

function addSidebarWidgetByClick(type) {
    insertWidgetIntoSelectedTarget(type);
}

function bindSidebarWidgetClicks() {
    if (window.__sbcUseDragV6) return;
    const sidebar = document.querySelector('.sidebar-content');
    if (!sidebar) return;
    if (window.__sbcSidebarMouseBound) return;
    window.__sbcSidebarMouseBound = true;

    // IMPORTANTE: os widgets da barra lateral não usam drag & drop HTML5.
    // O arraste é feito com mouse events para funcionar de forma consistente
    // ao sair da sidebar e entrar no canvas.
    sidebar.querySelectorAll('.widget-item[draggable="true"]').forEach(item => {
        const dragStart = item.getAttribute('ondragstart') || '';
        const match = dragStart.match(/dragSidebarWidget\(event,\s*'([^']+)'\)/);
        if (!match) return;
        item.dataset.widgetType = match[1];
        item.setAttribute('draggable', 'false');
        item.removeAttribute('ondragstart');
        item.style.userSelect = 'none';
        item.style.webkitUserDrag = 'none';
        item.style.touchAction = 'none';
    });

    const getItem = target => target?.closest?.('.sidebar-content .widget-item[data-widget-type]') || null;
    let press = null;
    let suppressClickUntil = 0;

    function clearPointerState() {
        if (press?.item) press.item.classList.remove('sbc-pointer-dragging');
        document.body.classList.remove('sbc-widget-pointer-dragging');
        clearDropHighlights();
        activeDropTargetId = null;
        document.getElementById('canvas')?.classList.remove('drag-over');
        press = null;
    }

    sidebar.addEventListener('mousedown', e => {
        if (e.button !== 0) return;
        const item = getItem(e.target);
        if (!item) return;
        const type = item.dataset.widgetType;
        if (!type) return;

        press = {
            item,
            type,
            startX: e.clientX,
            startY: e.clientY,
            dragging: false,
            targetId: null
        };
    }, true);

    document.addEventListener('mousemove', e => {
        if (!press) return;

        const dx = e.clientX - press.startX;
        const dy = e.clientY - press.startY;
        if (!press.dragging && Math.hypot(dx, dy) < 6) return;

        if (!press.dragging) {
            press.dragging = true;
            suppressClickUntil = Date.now() + 700;
            press.item.classList.add('sbc-pointer-dragging');
            document.body.classList.add('sbc-widget-pointer-dragging');
        }

        e.preventDefault();

        // Primeiro tenta a coluna/container exatamente sob o mouse.
        // Se o mouse estiver sobre um widget interno, closest() sobe até a
        // coluna/container pai. Se estiver fora por alguns pixels, usa o alvo
        // selecionado como fallback.
        // Durante o ARRaste, o destino é exclusivamente o elemento sob o
        // ponteiro. Não usamos o elemento selecionado como fallback, porque
        // isso fazia o widget ser inserido em outro lugar quando o mouse
        // ainda não estava realmente sobre a coluna.
        const targetId = getPointerDropTarget(e.clientX, e.clientY);

        press.targetId = targetId || null;
        activeDropTargetId = press.targetId;

        if (targetId) {
            const targetEl = document.querySelector(`[data-id="${CSS.escape(targetId)}"]`);
            setDropHighlight(targetEl);
        } else {
            clearDropHighlights();
        }
    }, true);

    document.addEventListener('mouseup', e => {
        if (!press) return;

        const drag = press;
        press = null;

        if (!drag.dragging) {
            return;
        }

        e.preventDefault();
        e.stopPropagation();
        drag.item.classList.remove('sbc-pointer-dragging');
        document.body.classList.remove('sbc-widget-pointer-dragging');

        // No mouseup também exigimos que o ponteiro esteja realmente dentro
        // de uma coluna/container. O alvo salvo durante o movimento continua
        // válido se o ponteiro não tiver saído dele.
        const targetId = getPointerDropTarget(e.clientX, e.clientY) || drag.targetId;

        clearDropHighlights();
        activeDropTargetId = null;
        document.getElementById('canvas')?.classList.remove('drag-over');

        if (targetId) {
            addWidgetToContainer(drag.type, targetId);
        } else if (drag.type === 'section' || drag.type === 'container') {
            addWidgetFromSidebar(drag.type);
        } else {
            showNotification('Selecione uma coluna ou container antes de arrastar o widget.', 'error');
        }
    }, true);

    document.addEventListener('mouseleave', e => {
        // Não cancela o arraste ao sair da janela; o mouseup continua sendo
        // tratado pelo document quando o ponteiro retorna.
    }, true);

    // Clique simples: adiciona no alvo selecionado, exatamente como antes.
    sidebar.addEventListener('click', e => {
        const item = getItem(e.target);
        if (!item) return;
        if (Date.now() < suppressClickUntil) {
            e.preventDefault();
            e.stopPropagation();
            return;
        }
        if (e.target.closest('input, textarea, select, button, label, option')) return;
        const type = item.dataset.widgetType;
        if (!type) return;
        e.preventDefault();
        e.stopPropagation();
        addSidebarWidgetByClick(type);
    }, true);
}

function addWidgetFromSidebar(type) {
    if (type === 'section') {
        openStructureModal('section');
        return;
    }
    if (type === 'container') {
        saveState();
        const container = {
            id: 'el-' + Date.now(),
            type: 'container',
            content: '',
            styles: { padding: '20px', minHeight: '80px', bgColor: '#f8f9fa' },
            children: []
        };
        pageContent.push(container);
        renderCanvas();
        saveData();
        showNotification('Container adicionado!');
        return;
    }

    if (selectedElementId) {
        const target = findElementById(pageContent, selectedElementId);
        if (target && isDropContainer(target.type)) {
            addWidgetToContainer(type, target.id);
            return;
        }
        if (target?.type === 'section' && target.children?.length) {
            addWidgetToContainer(type, target.children[0].id);
            return;
        }
    }

    createSectionWithWidget(type);
}

// =========================================================
// DIVISOR AVANÇADO
// =========================================================

function buildDividerHTML(s) {
    s = s || {};

    const num = (v, fallback) => {
        const n = parseFloat(v);
        return Number.isFinite(n) ? n : fallback;
    };

    const isOn = (v) => v === true || v === 1 || v === '1' || String(v).toLowerCase() === 'true' || String(v).toLowerCase() === 'on' || String(v).toLowerCase() === 'yes';

    const thickness = Math.max(1, Math.round(num(s.thickness, 2)));
    const margin = Math.max(0, Math.round(num(s.margin, 40)));

    let widthPct = num(s.width, 100);
    if (String(s.width).toLowerCase() === 'full' || String(s.width).toLowerCase() === 'auto') widthPct = 100;
    widthPct = Math.min(100, Math.max(1, widthPct));

    let opacityRaw = num(s.opacity, 100);
    if (opacityRaw <= 1) opacityRaw *= 100;
    const opacityVal = Math.min(100, Math.max(0, opacityRaw)) / 100;

    const lineStyle = ['solid','dashed','dotted','double','groove','ridge','inset','outset'].includes(s.lineStyle) ? s.lineStyle : 'solid';

    // O painel usa flex-start/flex-end, enquanto versões antigas usavam left/right.
    // Aceitamos os dois formatos para preservar dados já salvos.
    const alignmentRaw = String(s.alignment || 'center').toLowerCase();
    const alignment = alignmentRaw === 'flex-start' || alignmentRaw === 'left' ? 'left'
        : alignmentRaw === 'flex-end' || alignmentRaw === 'right' ? 'right'
        : 'center';

    const color = s.color || '#cbd5e1';
    const useGradient = isOn(s.useGradient);
    const gradientStart = s.gradientStart || '#3b82f6';
    const gradientEnd = s.gradientEnd || '#8b5cf6';
    const gradientDirection = s.gradientDirection || 'to right';

    const baseBackground = useGradient
        ? `linear-gradient(${gradientDirection}, ${gradientStart}, ${gradientEnd})`
        : color;

    let shadowCSS = 'none';
    if (isOn(s.shadow)) {
        shadowCSS = `${num(s.shadowX,0)}px ${num(s.shadowY,2)}px ${num(s.shadowBlur,6)}px ${num(s.shadowSpread,0)}px ${s.shadowColor || 'rgba(0,0,0,.2)'}`;
    }

    const outerStyle = [
        'display:flex !important',
        'align-items:center !important',
        'width:100% !important',
        'min-width:0 !important',
        `justify-content:${alignment === 'left' ? 'flex-start' : alignment === 'right' ? 'flex-end' : 'center'} !important`,
        'padding:0 !important',
        'box-sizing:border-box !important',
        `opacity:${opacityVal} !important`,
        `margin:${margin}px 0 !important`
    ].join(';');

    let background = baseBackground;

    if (lineStyle === 'dashed') {
        // Gradiente também usa as duas cores nos traços.
        background = useGradient
            ? `repeating-linear-gradient(90deg, ${gradientStart} 0 14px, ${gradientEnd} 14px 28px, transparent 28px 38px)`
            : `repeating-linear-gradient(90deg, ${color} 0 14px, transparent 14px 24px)`;
    } else if (lineStyle === 'dotted') {
        const dot = Math.max(2, thickness);
        const gap = Math.max(4, thickness * 2);
        background = useGradient
            ? `repeating-linear-gradient(90deg, ${gradientStart} 0 ${dot}px, ${gradientEnd} ${dot}px ${dot*2}px, transparent ${dot*2}px ${dot*2+gap}px)`
            : `repeating-linear-gradient(90deg, ${color} 0 ${dot}px, transparent ${dot}px ${dot + gap}px)`;
    }

    const is3D = ['groove','ridge','inset','outset'].includes(lineStyle);

    const lineStyleCSS = [
        'display:block !important',
        `height:${Math.max(1, thickness)}px !important`,
        `min-height:${Math.max(1, thickness)}px !important`,
        `background:${is3D ? 'transparent' : background} !important`,
        'outline:0 !important',
        'padding:0 !important',
        'margin:0 !important',
        `border-radius:${Math.max(1, thickness)}px !important`,
        `box-shadow:${shadowCSS} !important`,
        'box-sizing:border-box !important',
        'flex:0 0 auto !important',
        ...(is3D
            ? ['border:0 !important', `border-top:${Math.max(2, thickness)}px ${lineStyle} ${color} !important`]
            : ['border:0 !important'])
    ].join(';');

    const simpleLineStyle = [
        lineStyleCSS,
        `width:${widthPct}% !important`,
        `max-width:${widthPct}% !important`
    ].join(';');

    const groupJustify = alignment === 'left' ? 'flex-start' : alignment === 'right' ? 'flex-end' : 'center';
    const groupStyle = [
        'display:flex !important',
        'flex-direction:row !important',
        'align-items:center !important',
        `justify-content:${groupJustify} !important`,
        `width:${widthPct}% !important`,
        `max-width:${widthPct}% !important`,
        'min-width:0 !important',
        'padding:0 !important',
        'box-sizing:border-box !important',
        'margin:0 !important'
    ].join(';');

    const decorType = s.decorType || 'icon';
    const decorContent = s.decorContent || 'fa-star';
    const decorSize = Math.max(1, Math.round(num(s.decorSize, 24)));
    const decorPadding = Math.max(0, Math.round(num(s.decorPadding, 12)));
    const decorBgColor = s.decorBgColor || '#ffffff';
    const decorTextColor = s.decorTextColor || '#3b82f6';
    const decorIconOpacity = Math.max(0, Math.min(100, num(s.decorIconOpacity, 100)));
    const decorAnimation = ['none','pulse','bounce','spin','float','shake'].includes(String(s.decorAnimation || 'none')) ? String(s.decorAnimation || 'none') : 'none';
    const decorAnimationClass = decorAnimation !== 'none' ? ` sbc-divider-anim-${decorAnimation}` : '';
    const decorStyle = s.decorStyle || 'simple';
    const hasDecor = decorStyle !== 'simple' && decorContent !== '';

    if (!hasDecor) {
        return `<div class="sbc-divider" style="${outerStyle};justify-content:${groupJustify} !important;"><div class="sbc-divider-line" style="${simpleLineStyle}"></div></div>`;
    }

    const decorFontWeight = ['300','400','500','600','700','800','900'].includes(String(s.decorFontWeight)) ? String(s.decorFontWeight) : '600';
    const decorLetterSpacing = num(s.decorLetterSpacing, 0);
    const decorTextTransform = ['none','uppercase','lowercase','capitalize'].includes(s.decorTextTransform) ? s.decorTextTransform : 'none';

    let decorHTML = decorType === 'icon'
        ? `<i class="fas ${escapeAttr(decorContent)}${decorAnimationClass ? ' sbc-divider-icon-animated' + decorAnimationClass : ''}" style="font-size:${decorSize}px;color:${escapeAttr(decorTextColor)};line-height:1;font-weight:${decorFontWeight};opacity:${decorIconOpacity / 100};display:inline-block;transform-origin:center;"></i>`
        : `<span style="font-size:${decorSize}px;color:${escapeAttr(decorTextColor)};font-weight:${decorFontWeight};font-family:Inter,sans-serif;letter-spacing:${decorLetterSpacing}px;text-transform:${decorTextTransform};line-height:1;white-space:nowrap;">${escapeHtml(decorContent)}</span>`;

    const decorShape = s.decorShape || 'circle';
    const decorBox = decorSize + (decorPadding * 2);
    const decorRadius = Math.max(0, Math.min(50, num(s.decorRadius, 10)));
    const shapeRadius = decorShape === 'circle' ? '50%' : decorShape === 'square' ? '0' : decorShape === 'rounded' ? `${decorRadius}px` : decorShape === 'pill' ? '999px' : '0';
    const shapeSizeCSS = decorShape === 'circle' || decorShape === 'square' || decorShape === 'rounded'
        ? `width:${decorBox}px;height:${decorBox}px;`
        : decorShape === 'pill'
            ? `min-width:${Math.max(decorBox, decorSize + decorPadding * 3)}px;min-height:${decorBox}px;`
            : `min-width:${decorBox}px;min-height:${decorBox}px;`;
    const decorBorder = isOn(s.decorBorder) ? `${Math.max(1, Math.round(num(s.decorBorderWidth,1)))}px solid ${s.decorBorderColor || '#3b82f6'}` : '0';
    const decorShadow = isOn(s.decorShadow) ? '0 2px 8px rgba(0,0,0,.18)' : 'none';
    const decorBackground = decorShape === 'none' ? 'transparent' : decorBgColor;

    const decorWrap = `<span class="sbc-divider-decoration" style="display:inline-flex;align-items:center;justify-content:center;flex:0 0 auto;${shapeSizeCSS}padding:${decorPadding}px;margin:0 12px;background:${escapeAttr(decorBackground)};border:${escapeAttr(decorBorder)};border-radius:${shapeRadius};box-shadow:${decorShadow};box-sizing:border-box;line-height:1;">${decorHTML}</span>`;

    const sideStyle = [
        lineStyleCSS,
        'flex:1 1 0 !important',
        'width:auto !important',
        'max-width:none !important',
        'min-width:0 !important'
    ].join(';');

    // Cada lado recebe o mesmo gradiente. O layout agora respeita a largura e o alinhamento.
    return `<div class="sbc-divider" style="${outerStyle}"><div class="sbc-divider-group" style="${groupStyle}"><div class="sbc-divider-side" style="${sideStyle}"></div>${decorWrap}<div class="sbc-divider-side" style="${sideStyle}"></div></div></div>`;
}


const FORM_FIELD_TYPES = [
    { value:'text', label:'Texto', icon:'fa-font' },
    { value:'email', label:'E-mail', icon:'fa-envelope' },
    { value:'tel', label:'Telefone', icon:'fa-phone' },
    { value:'url', label:'URL / Site', icon:'fa-link' },
    { value:'number', label:'Número', icon:'fa-hashtag' },
    { value:'password', label:'Senha', icon:'fa-lock' },
    { value:'date', label:'Data', icon:'fa-calendar' },
    { value:'time', label:'Hora', icon:'fa-clock' },
    { value:'datetime-local', label:'Data e hora', icon:'fa-calendar-days' },
    { value:'textarea', label:'Área de texto', icon:'fa-align-left' },
    { value:'select', label:'Lista de opções', icon:'fa-list' },
    { value:'radio', label:'Escolha única', icon:'fa-circle-dot' },
    { value:'checkbox', label:'Múltipla escolha', icon:'fa-square-check' },
    { value:'file', label:'Upload de arquivo', icon:'fa-paperclip' },
    { value:'color', label:'Cor', icon:'fa-palette' },
    { value:'cpf', label:'CPF', icon:'fa-id-card' },
    { value:'cnpj', label:'CNPJ', icon:'fa-building' },
    { value:'cep', label:'CEP', icon:'fa-location-dot' },
    { value:'hidden', label:'Campo oculto', icon:'fa-eye-slash' }
];
const FORM_FIELD_TYPE_VALUES = FORM_FIELD_TYPES.map(t => t.value);

function getFormTypeLabel(type) {
    return FORM_FIELD_TYPES.find(t => t.value === type)?.label || 'Texto';
}

function getFormFields(formStyles) {
    const defaults = [
        { label: 'Nome', type: 'text', placeholder: 'Digite seu nome', required: true },
        { label: 'E-mail', type: 'email', placeholder: 'seuemail@exemplo.com', required: true },
        { label: 'Telefone', type: 'tel', placeholder: '(00) 00000-0000', required: false },
        { label: 'Mensagem', type: 'textarea', placeholder: 'Como podemos ajudar?', required: true }
    ];
    const raw = formStyles?.formFields;
    if (!Array.isArray(raw) || raw.length === 0) return defaults;
    return raw.map((f, i) => ({
        label: String(f?.label ?? defaults[i]?.label ?? `Campo ${i + 1}`),
        type: FORM_FIELD_TYPE_VALUES.includes(String(f?.type)) ? String(f.type) : 'text',
        placeholder: String(f?.placeholder ?? ''),
        required: f?.required !== false,
        options: Array.isArray(f?.options) ? f.options.map(v => String(v)) : [],
        value: String(f?.value ?? ''),
        width: String(f?.width ?? '100'),
        fieldAlign: ['left','center','right'].includes(String(f?.fieldAlign)) ? String(f.fieldAlign) : 'left'
    })).slice(0, 12);
}

function defaultFormFieldForType(type, index) {
    const map = {
        text: ['Texto','Digite aqui'],
        email: ['E-mail','seuemail@exemplo.com'],
        tel: ['Telefone','(00) 00000-0000'],
        url: ['Site','https://'],
        number: ['Número','Digite um número'],
        password: ['Senha','Digite sua senha'],
        date: ['Data',''],
        time: ['Hora',''],
        'datetime-local': ['Data e hora',''],
        textarea: ['Mensagem','Como podemos ajudar?'],
        select: ['Selecione uma opção',''],
        radio: ['Escolha uma opção',''],
        checkbox: ['Aceite ou opções',''],
        file: ['Anexo',''],
        color: ['Cor',''],
        cpf: ['CPF','000.000.000-00'],
        cnpj: ['CNPJ','00.000.000/0000-00'],
        cep: ['CEP','00000-000'],
        hidden: ['Campo oculto','']
    };
    const pair = map[type] || map.text;
    return {
        label: pair[0] === 'Texto' && index > 0 ? `Campo ${index + 1}` : pair[0],
        type: FORM_FIELD_TYPE_VALUES.includes(type) ? type : 'text',
        placeholder: pair[1],
        required: !['checkbox','hidden','file'].includes(type),
        options: ['select','radio','checkbox'].includes(type) ? ['Opção 1','Opção 2'] : [],
        value: '',
        width: '100',
        fieldAlign: 'left'
    };
}
function formColorValid(value, fallback) {
    const v = String(value ?? '').trim();
    return v || fallback;
}

function ensureFormFields(styles) {
    if (!styles || !Array.isArray(styles.formFields) || styles.formFields.length === 0) {
        styles.formFields = [
            { label: 'Nome', type: 'text', placeholder: 'Digite seu nome', required: true, width: '100', fieldAlign: 'left' },
            { label: 'E-mail', type: 'email', placeholder: 'seuemail@exemplo.com', required: true, width: '100', fieldAlign: 'left' },
            { label: 'Telefone', type: 'tel', placeholder: '(00) 00000-0000', required: false, width: '100', fieldAlign: 'left' },
            { label: 'Mensagem', type: 'textarea', placeholder: 'Como podemos ajudar?', required: true, width: '100', fieldAlign: 'left' }
        ];
    }
    return styles.formFields;
}

function updateFormField(id, index, prop, value) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'form') return;
    el.styles = el.styles || {};
    const fields = ensureFormFields(el.styles);
    if (!fields[index]) return;
    saveState();

    if (prop === 'required') {
        fields[index].required = !!value;
    } else if (prop === 'type') {
        const nextType = FORM_FIELD_TYPE_VALUES.includes(String(value)) ? String(value) : 'text';
        fields[index].type = nextType;
        if (['select','radio','checkbox'].includes(nextType) && !Array.isArray(fields[index].options)) {
            fields[index].options = ['Opção 1','Opção 2'];
        }
        if (!['select','radio','checkbox'].includes(nextType)) fields[index].options = [];
    } else if (prop === 'fieldAlign') {
        fields[index].fieldAlign = ['left','center','right'].includes(String(value)) ? String(value) : 'left';
    } else if (prop === 'width') {
        const n = parseFloat(value);
        fields[index].width = Number.isFinite(n) ? String(Math.min(100, Math.max(25, n))) : '100';
    } else {
        fields[index][prop] = String(value ?? '');
    }
    el.styles.formFields = fields;
    renderCanvas();
    saveData();
    selectedElementId = id;
    updateProperties(id);
    requestAnimationFrame(() => {
        const selected = document.querySelector(`[data-id="${CSS.escape(id)}"]`);
        if (selected) selected.classList.add('selected');
    });
}

function addFormField(id, type = 'text') {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'form') return;
    el.styles = el.styles || {};
    const fields = ensureFormFields(el.styles);
    if (fields.length >= 20) {
        showNotification('O formulário pode ter no máximo 20 campos.', 'error');
        return;
    }
    saveState();
    fields.push(defaultFormFieldForType(String(type), fields.length));
    el.styles.formFields = fields;
    renderCanvas();
    saveData();
    selectedElementId = id;
    updateProperties(id);
}

function removeFormField(id, index) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'form') return;
    el.styles = el.styles || {};
    const fields = ensureFormFields(el.styles);
    if (!fields[index]) return;
    if (fields.length <= 1) {
        showNotification('O formulário precisa ter pelo menos 1 campo.', 'error');
        return;
    }
    saveState();
    fields.splice(index, 1);
    el.styles.formFields = fields;
    renderCanvas();
    saveData();
    selectedElementId = id;
    updateProperties(id);
}

function reorderFormField(id, fromIndex, toIndex) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'form') return;
    el.styles = el.styles || {};
    const fields = ensureFormFields(el.styles);
    if (fromIndex === toIndex || !fields[fromIndex] || toIndex < 0 || toIndex >= fields.length) return;

    const moved = fields.splice(fromIndex, 1)[0];
    fields.splice(toIndex, 0, moved);
    el.styles.formFields = fields;

    renderCanvas();
    saveData();
    selectedElementId = id;
    updateProperties(id);
    requestAnimationFrame(() => {
        const selected = document.querySelector(`[data-id="${CSS.escape(id)}"]`);
        if (selected) selected.classList.add('selected');
    });
}

function setupFormFieldDrag(event, id, index) {
    event.stopPropagation();
    event.dataTransfer.effectAllowed = 'move';
    event.dataTransfer.setData('application/x-sbc-form-field', JSON.stringify({ id, index }));
    event.currentTarget.classList.add('sbc-form-field-dragging');
}

function finishFormFieldDrag(event) {
    event.stopPropagation();
    event.currentTarget.classList.remove('sbc-form-field-dragging');
    document.querySelectorAll('.sbc-form-field-editor-item.sbc-form-field-drop-target')
        .forEach(el => el.classList.remove('sbc-form-field-drop-target'));
}

function handleFormFieldDragOver(event, id, index) {
    if (!event.dataTransfer.types.includes('application/x-sbc-form-field')) return;
    event.preventDefault();
    event.stopPropagation();
    event.dataTransfer.dropEffect = 'move';
    event.currentTarget.classList.add('sbc-form-field-drop-target');
}

function handleFormFieldDragLeave(event) {
    event.stopPropagation();
    event.currentTarget.classList.remove('sbc-form-field-drop-target');
}

function handleFormFieldDrop(event, id, toIndex) {
    event.preventDefault();
    event.stopPropagation();
    event.currentTarget.classList.remove('sbc-form-field-drop-target');

    const raw = event.dataTransfer.getData('application/x-sbc-form-field');
    if (!raw) return;
    try {
        const data = JSON.parse(raw);
        if (data && data.id === id) reorderFormField(id, Number(data.index), Number(toIndex));
    } catch (err) {
        console.warn('Não foi possível reordenar o campo do formulário.', err);
    }
}

function updateFormFieldOptions(id, index, rawValue) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'form') return;
    el.styles = el.styles || {};
    const fields = ensureFormFields(el.styles);
    if (!fields[index]) return;
    saveState();
    fields[index].options = String(rawValue ?? '').split('\n').map(v => v.trim()).filter(Boolean).slice(0, 30);
    el.styles.formFields = fields;
    renderCanvas();
    saveData();
    selectedElementId = id;
    updateProperties(id);
}

function ensureFaqItems(styles) {
    styles = styles || {};
    if (!Array.isArray(styles.faqItems) || !styles.faqItems.length) {
        styles.faqItems = [
            { question: 'Pergunta 1?', answer: 'Resposta 1', open: false },
            { question: 'Pergunta 2?', answer: 'Resposta 2', open: false }
        ];
    }
    return styles.faqItems;
}


function reorderFaqItem(id, fromIndex, toIndex) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'faq') return;
    el.styles = el.styles || {};
    const items = ensureFaqItems(el.styles);
    const from = Number(fromIndex);
    const to = Number(toIndex);
    if (!Number.isInteger(from) || !Number.isInteger(to) || from < 0 || to < 0 || from >= items.length || to >= items.length || from === to) return;

    saveState();
    const moved = items.splice(from, 1)[0];
    items.splice(to, 0, moved);
    el.styles.faqItems = items;
    selectedElementId = id;
    renderCanvas();
    saveData();
    updateProperties(id);
}

function handleFaqDragStart(event, id, index) {
    event.stopPropagation();
    event.dataTransfer.effectAllowed = 'move';
    event.dataTransfer.setData('application/x-sbc-faq-item', JSON.stringify({ id, index }));
    event.currentTarget.style.opacity = '0.55';
}

function handleFaqDragEnd(event) {
    event.stopPropagation();
    event.currentTarget.style.opacity = '1';
    document.querySelectorAll('.sbc-faq-prop-item').forEach(el => el.classList.remove('drag-over'));
}

function handleFaqDragOver(event, id, targetIndex) {
    const raw = event.dataTransfer.getData('application/x-sbc-faq-item');
    if (!raw) return;
    event.preventDefault();
    event.stopPropagation();
    event.dataTransfer.dropEffect = 'move';
    event.currentTarget.classList.add('drag-over');
}

function handleFaqDragLeave(event) {
    if (event.currentTarget.contains(event.relatedTarget)) return;
    event.currentTarget.classList.remove('drag-over');
}

function handleFaqDrop(event, id, targetIndex) {
    event.preventDefault();
    event.stopPropagation();
    event.currentTarget.classList.remove('drag-over');
    document.querySelectorAll('.sbc-faq-prop-item').forEach(el => el.classList.remove('drag-over'));
    const raw = event.dataTransfer.getData('application/x-sbc-faq-item');
    if (!raw) return;
    try {
        const data = JSON.parse(raw);
        if (data && data.id === id) {
            const from = Number(data.index);
            let to = Number(targetIndex);
            if (from < to) to--;
            if (from !== to) reorderFaqItem(id, from, to);
        }
    } catch (err) {
        console.warn('Não foi possível reordenar a pergunta do FAQ.', err);
    }
}

function addFaqItem(id) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'faq') return;
    el.styles = el.styles || {};
    saveState();
    const items = ensureFaqItems(el.styles);
    if (items.length >= 50) {
        showNotification('Limite de 50 perguntas atingido.');
        return;
    }
    items.push({ question: 'Nova pergunta?', answer: 'Escreva a resposta aqui.', open: false });
    el.styles.faqItems = items;
    renderCanvas();
    saveData();
    selectedElementId = id;
    updateProperties(id);
}

function updateFaqItem(id, index, prop, value) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'faq') return;
    el.styles = el.styles || {};
    const items = ensureFaqItems(el.styles);
    if (!items[index]) return;
    saveState();
    items[index][prop] = value;
    el.styles.faqItems = items;
    renderCanvas();
    saveData();
    selectedElementId = id;
    updateProperties(id);
}

function removeFaqItem(id, index) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'faq') return;
    el.styles = el.styles || {};
    const items = ensureFaqItems(el.styles);
    if (!items[index]) return;
    saveState();
    items.splice(index, 1);
    if (!items.length) items.push({ question: 'Nova pergunta?', answer: 'Escreva a resposta aqui.', open: false });
    el.styles.faqItems = items;
    renderCanvas();
    saveData();
    selectedElementId = id;
    updateProperties(id);
}

function toggleFaqItem(id, index) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'faq') return;
    el.styles = el.styles || {};
    const items = ensureFaqItems(el.styles);
    if (!items[index]) return;
    if (el.styles.multipleOpen !== true) items.forEach((item, i) => item.open = i === index ? !item.open : false);
    else items[index].open = !items[index].open;
    el.styles.faqItems = items;
    renderCanvas();
    saveData();
    selectedElementId = id;
    const selected = document.querySelector(`[data-id="${CSS.escape(id)}"]`);
    if (selected) selected.classList.add('selected');
}

function ensureProgressItems(styles) {
    styles = styles || {};
    if (!Array.isArray(styles.items) || !styles.items.length) {
        styles.items = [
            {label:'Desenvolvimento Web', value:'90'},
            {label:'WordPress', value:'85'},
            {label:'UI/UX Design', value:'75'}
        ];
    }
    styles.items = styles.items.map((item, i) => ({
        label: String(item?.label ?? `Item ${i+1}`),
        value: String(item?.value ?? '0')
    }));
    return styles.items;
}

function addProgressItem(id) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'progress') return;
    el.styles = el.styles || {};
    const items = ensureProgressItems(el.styles);
    if (items.length >= 30) { showNotification('Limite de 30 barras atingido.', 'error'); return; }
    saveState();
    items.push({label:`Novo item ${items.length+1}`, value:'50'});
    el.styles.items = items;
    renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}

function updateProgressItem(id, index, prop, value) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'progress') return;
    el.styles = el.styles || {};
    const items = ensureProgressItems(el.styles);
    if (!items[index]) return;
    saveState();
    if (prop === 'value') {
        const num = parseFloat(value);
        items[index][prop] = Number.isFinite(num) ? String(Math.min(100, Math.max(0, num))) : '0';
    } else {
        items[index][prop] = value;
    }
    el.styles.items = items;
    renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}

function removeProgressItem(id, index) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'progress') return;
    el.styles = el.styles || {};
    const items = ensureProgressItems(el.styles);
    if (!items[index]) return;
    saveState();
    items.splice(index,1);
    if (!items.length) items.push({label:'Novo item', value:'50'});
    el.styles.items = items;
    renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}

let progressDragState = null;
function startProgressDrag(event, id, index) {
    progressDragState = {id, index};
    event.dataTransfer.effectAllowed = 'move';
    event.dataTransfer.setData('text/plain', String(index));
}
function allowProgressDrop(event) {
    event.preventDefault();
    event.stopPropagation();
    event.dataTransfer.dropEffect = 'move';
}
function dropProgress(event, id, target) {
    event.preventDefault(); event.stopPropagation();
    if (!progressDragState || progressDragState.id !== id) return;
    const from = progressDragState.index;
    progressDragState = null;
    let to = target;
    if (from < to) to--;
    reorderProgressItem(id, from, to);
}
function reorderProgressItem(id, from, to) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'progress') return;
    const items = ensureProgressItems(el.styles || {});
    if (from < 0 || to < 0 || from >= items.length || to >= items.length || from === to) return;
    saveState();
    const [item] = items.splice(from,1);
    items.splice(to,0,item);
    el.styles.items = items;
    renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}

function buildProgressHTML(el) {
    const s = el.styles || {};
    const items = ensureProgressItems(s);
    const align = ['left','center','right'].includes(s.alignment) ? s.alignment : 'left';
    let width = '100%';
    const wn = parseFloat(s.width);
    if (Number.isFinite(wn) && wn > 0) {
        const unit = s.widthUnit === 'px' ? 'px' : '%';
        width = `${unit === 'px' ? Math.min(2000, Math.max(160, wn)) : Math.min(100, Math.max(1, wn))}${unit}`;
    }
    const clampNum = (v,min,max,def) => { const n=parseFloat(v); return Number.isFinite(n) ? Math.min(max,Math.max(min,n)) : def; };
    const titleSize = clampNum(s.titleFontSize,10,72,28);
    const descSize = clampNum(s.descriptionFontSize,10,30,14);
    const labelSize = clampNum(s.labelFontSize,8,32,14);
    const valueSize = clampNum(s.valueFontSize,8,32,13);
    const barHeight = clampNum(s.barHeight,2,60,12);
    const radius = clampNum(s.radius,0,80,999);
    const gap = clampNum(s.itemSpacing,0,80,18);
    const padY = clampNum(s.paddingY,0,80,0);
    const padX = clampNum(s.paddingX,0,80,0);
    const shadow = s.shadow ? `0 ${clampNum(s.shadowBlur,0,50,8)/2}px ${clampNum(s.shadowBlur,0,50,8)}px ${escapeAttr(s.shadowColor || 'rgba(0,0,0,0.10)')}` : 'none';
    const barStyle = ['solid','gradient','striped'].includes(s.barStyle) ? s.barStyle : 'solid';
    const fillBase = s.fillColor || '#3b82f6';
    const fillEnd = s.fillColorEnd || '#60a5fa';
    const fillBg = barStyle === 'gradient'
        ? `linear-gradient(90deg,${escapeAttr(fillBase)},${escapeAttr(fillEnd)})`
        : (barStyle === 'striped'
            ? `repeating-linear-gradient(135deg,${escapeAttr(fillBase)} 0px,${escapeAttr(fillBase)} 10px,${escapeAttr(fillEnd)} 10px,${escapeAttr(fillEnd)} 20px)`
            : escapeAttr(fillBase));
    const itemHTML = items.map((item,index) => {
        const value = Math.min(100,Math.max(0,parseFloat(item.value)||0));
        const shadowBar = s.shadow ? `0 0 ${Math.max(0,clampNum(s.shadowBlur,0,50,8))}px ${escapeAttr(s.shadowColor || 'rgba(0,0,0,0.10)')}` : 'none';
        const valueHTML = s.showPercent ? `<span style="color:${escapeAttr(s.valueColor||'#475569')};font-size:${valueSize}px;font-weight:600;line-height:1;">${value}%</span>` : '';
        const trackStyle = `width:100%;height:${barHeight}px;background:${escapeAttr(s.trackColor||'#e5e7eb')};border-radius:${radius}px;overflow:hidden;box-shadow:${shadow};`;
        const fillStyle = `width:${value}%;height:100%;background:${fillBg};border-radius:${radius}px;transition:width ${Math.max(100,clampNum(s.animationDuration,100,5000,900))}ms ease;${s.shadow ? `box-shadow:${shadowBar};` : ''}`;
        const inTrack = s.showValueOnTrack && s.showPercent ? `<span style="position:absolute;right:8px;top:50%;transform:translateY(-50%);font:700 ${Math.max(8,valueSize-2)}px/1 Inter,Arial,sans-serif;color:#fff;pointer-events:none;">${value}%</span>` : '';
        return `<div class="sbc-progress-item" style="margin:0 0 ${index===items.length-1?0:gap}px;padding:0 ${padX}px ${padY}px;box-sizing:border-box;">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:8px;">
                <span style="color:${escapeAttr(s.labelColor||'#111827')};font-size:${labelSize}px;font-weight:${escapeAttr(s.labelWeight||'600')};line-height:1.35;">${escapeHtml(item.label||'')}</span>
                ${valueHTML}
            </div>
            <div style="${trackStyle}position:relative;"><div style="${fillStyle}"></div>${inTrack}</div>
        </div>`;
    }).join('');
    const shell = `width:${width};max-width:100%;margin:0 ${align==='left'?'auto 0':align==='right'?'0 0 auto':'auto'};box-sizing:border-box;text-align:${align};`;
    return `<section class="sbc-progress-shell" style="${shell}">
        ${s.title ? `<h3 style="margin:0 0 8px;color:${escapeAttr(s.titleColor||'#111827')};font-size:${titleSize}px;font-weight:800;line-height:1.2;">${escapeHtml(s.title)}</h3>` : ''}
        ${s.description ? `<p style="margin:0 0 20px;color:${escapeAttr(s.descriptionColor||'#6b7280')};font-size:${descSize}px;line-height:1.55;">${escapeHtml(s.description)}</p>` : ''}
        <div class="sbc-progress-list">${itemHTML}</div>
    </section>`;
}

function buildFAQHTML(el) {
    const s = el.styles || {};
    const items = ensureFaqItems(s);
    const align = ['left','center','right'].includes(s.alignment) ? s.alignment : 'left';
    let width = '100%';
    const n = parseFloat(s.width);
    if (Number.isFinite(n) && n > 0) {
        const unit = s.widthUnit === 'px' ? 'px' : '%';
        const safe = unit === '%' ? Math.min(100, Math.max(1, n)) : Math.min(2000, Math.max(1, n));
        width = `${safe}${unit}`;
    }
    const cardShadow = s.shadow ? `${parseFloat(s.shadowX ?? 0)||0}px ${parseFloat(s.shadowY ?? 4)||0}px ${Math.max(0,parseFloat(s.shadowBlur ?? 16)||0)}px ${parseFloat(s.shadowSpread ?? 0)||0}px ${escapeAttr(s.shadowColor || 'rgba(0,0,0,.10)')}` : 'none';
    const itemRadius = Math.max(0, Math.min(60, parseFloat(s.radius ?? 10) || 10));
    const borderWidth = Math.max(0, Math.min(10, parseFloat(s.borderWidth ?? 1) || 0));
    const gap = Math.max(0, Math.min(80, parseFloat(s.itemSpacing ?? 10) || 0));
    const qPadY = Math.max(4, Math.min(50, parseFloat(s.paddingY ?? 16) || 16));
    const qPadX = Math.max(6, Math.min(80, parseFloat(s.paddingX ?? 18) || 18));
    const qFont = Math.max(10, Math.min(40, parseFloat(s.questionFontSize ?? 15) || 15));
    const aFont = Math.max(10, Math.min(40, parseFloat(s.answerFontSize ?? 14) || 14));
    const aLine = Math.max(1, Math.min(3, parseFloat(s.answerLineHeight ?? 1.65) || 1.65));
    const iconType = ['chevron','plus','angle','minus'].includes(s.iconType) ? s.iconType : 'chevron';
    const iconPos = s.iconPosition === 'left' ? 'left' : 'right';
    const wrapper = `width:${escapeAttr(width)};max-width:100%;margin-left:${align==='center'||align==='right'?'auto':'0'};margin-right:${align==='center'||align==='left'?'auto':'0'};`;
    const title = escapeHtml(s.title || 'Perguntas Frequentes');
    const desc = escapeHtml(s.description || '');
    const itemsHTML = items.map((item, index) => {
        const open = !!item.open;
        let iconHTML = '';
        if (iconType === 'plus') iconHTML = open ? '−' : '+';
        else if (iconType === 'minus') iconHTML = open ? '−' : '+';
        else if (iconType === 'angle') iconHTML = '<i class="fas fa-angle-down"></i>';
        else iconHTML = '<i class="fas fa-chevron-down"></i>';
        const iconTransform = (iconType === 'chevron' || iconType === 'angle') && open ? 'rotate(180deg)' : 'none';
        const iconNode = `<span class="sbc-faq-icon" style="color:${escapeAttr(s.iconColor || '#3b82f6')};transform:${iconTransform};">${iconHTML}</span>`;
        const textNode = `<span style="min-width:0;flex:1;">${escapeHtml(item.question || 'Pergunta')}</span>`;
        const buttonChildren = iconPos === 'left' ? `${iconNode}${textNode}` : `${textNode}${iconNode}`;
        const answerStyle = `max-height:${open?'1000px':'0px'};display:block;padding:${open?qPadY:0}px ${qPadX}px;background:${escapeAttr(s.answerBg || '#fff')};color:${escapeAttr(s.answerColor || '#4b5563')};font:${aFont}/ ${aLine} Inter,Arial,sans-serif;box-sizing:border-box;`;
        return `<div class="sbc-faq-item" style="margin-bottom:${gap}px;border:${borderWidth}px solid ${escapeAttr(s.borderColor || '#e5e7eb')};border-radius:${itemRadius}px;overflow:hidden;box-shadow:${cardShadow};background:${escapeAttr(s.answerBg || '#fff')};">
            <button type="button" class="sbc-faq-question${open?' is-open':''}" onclick="event.stopPropagation();toggleFaqItem('${el.id}',${index})" style="width:100%;display:flex;align-items:center;justify-content:space-between;gap:12px;text-align:left;border:0;background:${escapeAttr(s.questionBg || '#f8fafc')};color:${escapeAttr(open ? (s.questionOpenColor || s.questionColor || '#111827') : (s.questionColor || '#111827'))};--faq-q-hover:${escapeAttr(s.questionHoverBg || '#eef4ff')};--faq-q-open:${escapeAttr(s.questionOpenBg || s.questionBg || '#f8fafc')};--faq-q-open-color:${escapeAttr(s.questionOpenColor || s.questionColor || '#111827')};padding:${qPadY}px ${qPadX}px;cursor:pointer;font:600 ${qFont}px/1.35 Inter,Arial,sans-serif;box-sizing:border-box;">
                ${buttonChildren}
            </button>
            <div class="sbc-faq-answer ${open?'is-open':'is-closed'}" style="${answerStyle}">${escapeHtml(item.answer || '')}</div>
        </div>`;
    }).join('');
    return `<div class="sbc-faq-shell" style="${wrapper}">
        <div class="sbc-faq-title" style="color:${escapeAttr(s.titleColor || '#111827')};font-size:${Math.max(10,parseFloat(s.titleFontSize ?? 28)||28)}px;font-weight:800;line-height:1.2;margin:0 0 8px;">${title}</div>
        ${desc ? `<div class="sbc-faq-description" style="color:${escapeAttr(s.descriptionColor || '#6b7280')};font-size:${Math.max(10,parseFloat(s.descriptionFontSize ?? 14)||14)}px;line-height:1.5;margin:0 0 18px;">${desc}</div>` : ''}
        <div class="sbc-faq-list">${itemsHTML}</div>
    </div>`;
}


function ensureAccordionItems(styles) {
    styles = styles || {};
    if (!Array.isArray(styles.items) || !styles.items.length) {
        styles.items = [
            { question:'O que é este serviço?', answer:'Explique aqui os detalhes do serviço.', open:true },
            { question:'Como funciona?', answer:'Descreva de forma simples como funciona.', open:false },
            { question:'Posso editar depois?', answer:'Sim. Você pode editar conteúdo e aparência a qualquer momento.', open:false }
        ];
    }
    styles.items = styles.items.map((item, index) => ({
        question: String(item?.question ?? `Pergunta ${index + 1}`),
        answer: String(item?.answer ?? `Resposta ${index + 1}`),
        open: !!item?.open
    }));
    return styles.items;
}

function addAccordionItem(id) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'accordion') return;
    el.styles = el.styles || {};
    const items = ensureAccordionItems(el.styles);
    if (items.length >= 30) { showNotification('Limite de 30 itens atingido.','error'); return; }
    saveState();
    items.push({question:`Nova pergunta ${items.length + 1}`, answer:'Escreva a resposta aqui.', open:false});
    el.styles.items = items;
    renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}

function updateAccordionItem(id, index, prop, value) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'accordion') return;
    el.styles = el.styles || {};
    const items = ensureAccordionItems(el.styles);
    if (!items[index]) return;
    saveState();
    items[index][prop] = prop === 'open' ? !!value : String(value ?? '');
    el.styles.items = items;
    renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}

function removeAccordionItem(id, index) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'accordion') return;
    el.styles = el.styles || {};
    const items = ensureAccordionItems(el.styles);
    if (!items[index]) return;
    if (items.length <= 1) { showNotification('Mantenha pelo menos 1 item.','error'); return; }
    saveState();
    items.splice(index,1);
    if (!el.styles.multipleOpen && !items.some(x=>x.open)) items[0].open = true;
    el.styles.items = items;
    renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}

let accordionDragState = null;
function startAccordionDrag(event,id,index){
    accordionDragState = {id,index};
    event.dataTransfer.effectAllowed = 'move';
    event.dataTransfer.setData('text/plain', String(index));
}
function allowAccordionDrop(event){ event.preventDefault(); event.stopPropagation(); event.dataTransfer.dropEffect='move'; }
function dropAccordion(event,id,target){
    event.preventDefault(); event.stopPropagation();
    if (!accordionDragState || accordionDragState.id !== id) return;
    const from = accordionDragState.index;
    accordionDragState = null;
    let to = target;
    if (from < to) to--;
    reorderAccordionItem(id, from, to);
}
function reorderAccordionItem(id, from, to){
    const el = findElementById(pageContent,id);
    if (!el || el.type !== 'accordion') return;
    const items = ensureAccordionItems(el.styles || {});
    if (from < 0 || to < 0 || from >= items.length || to >= items.length || from === to) return;
    saveState();
    const [item] = items.splice(from,1);
    items.splice(to,0,item);
    el.styles.items = items;
    renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}

function toggleAccordionItem(id,index){
    const el = findElementById(pageContent,id);
    if (!el || el.type !== 'accordion') return;
    el.styles = el.styles || {};
    const items = ensureAccordionItems(el.styles);
    if (!items[index]) return;
    saveState();
    const willOpen = !items[index].open;
    if (!el.styles.multipleOpen) items.forEach((item,i)=>{ item.open = i===index ? willOpen : false; });
    else items[index].open = willOpen;
    el.styles.items = items;
    renderCanvas(); saveData();
    selectedElementId = id;
    const selected = document.querySelector(`[data-id="${CSS.escape(id)}"]`);
    if (selected) selected.classList.add('selected');
}

function buildAccordionHTML(el) {
    const s = el.styles || {};
    const items = ensureAccordionItems(s);
    const align = ['left','center','right'].includes(s.alignment) ? s.alignment : 'left';
    let width = '100%';
    const n = parseFloat(s.width);
    if (Number.isFinite(n) && n > 0) {
        const unit = s.widthUnit === 'px' ? 'px' : '%';
        const safe = unit === '%' ? Math.min(100, Math.max(1, n)) : Math.min(2000, Math.max(1, n));
        width = `${safe}${unit}`;
    }
    const itemRadius = Math.max(0, Math.min(60, parseFloat(s.radius ?? 10) || 10));
    const borderWidth = Math.max(0, Math.min(10, parseFloat(s.borderWidth ?? 1) || 0));
    const gap = Math.max(0, Math.min(80, parseFloat(s.itemSpacing ?? 10) || 0));
    const padY = Math.max(4, Math.min(50, parseFloat(s.paddingY ?? 16) || 16));
    const padX = Math.max(6, Math.min(80, parseFloat(s.paddingX ?? 18) || 18));
    const qFont = Math.max(10, Math.min(40, parseFloat(s.questionFontSize ?? 15) || 15));
    const aFont = Math.max(10, Math.min(40, parseFloat(s.answerFontSize ?? 14) || 14));
    const aLine = Math.max(1, Math.min(3, parseFloat(s.answerLineHeight ?? 1.65) || 1.65));
    const iconType = ['chevron','angle','plus','minus'].includes(s.iconType) ? s.iconType : 'plus';
    const iconPos = s.iconPosition === 'left' ? 'left' : 'right';
    const shadow = s.shadow ? `${parseFloat(s.shadowX ?? 0)||0}px ${parseFloat(s.shadowY ?? 4)||4}px ${Math.max(0,parseFloat(s.shadowBlur ?? 16)||16)}px ${parseFloat(s.shadowSpread ?? 0)||0}px ${escapeAttr(s.shadowColor || 'rgba(0,0,0,.10)')}` : 'none';
    const wrapper = `width:${escapeAttr(width)};max-width:100%;margin-left:${align==='center'||align==='right'?'auto':'0'};margin-right:${align==='center'||align==='left'?'auto':'0'};`;
    const iconHTML = (open) => {
        if (iconType === 'plus') return open ? '−' : '+';
        if (iconType === 'minus') return open ? '−' : '+';
        if (iconType === 'angle') return '<i class="fas fa-angle-down"></i>';
        return '<i class="fas fa-chevron-down"></i>';
    };
    const itemsHTML = items.map((item,index)=>{
        const open = !!item.open;
        const rotate = (iconType === 'chevron' || iconType === 'angle') && open ? 'rotate(180deg)' : 'none';
        const iconNode = `<span class="sbc-accordion-icon" style="color:${escapeAttr(s.iconColor || '#3b82f6')};transform:${rotate};transition:transform .2s ease;flex:0 0 auto;">${iconHTML(open)}</span>`;
        const label = `<span style="min-width:0;flex:1;">${escapeHtml(item.question || 'Pergunta')}</span>`;
        const children = iconPos === 'left' ? `${iconNode}${label}` : `${label}${iconNode}`;
        const answerStyle = `display:block;max-height:${open?'1000px':'0px'};overflow:hidden;padding:${open?padY:0}px ${padX}px;background:${escapeAttr(s.answerBg || '#fff')};color:${escapeAttr(s.answerColor || '#4b5563')};font:${aFont}px/${aLine} Inter,Arial,sans-serif;box-sizing:border-box;transition:max-height .22s ease,padding .22s ease;`;
        return `<div class="sbc-accordion-item${open?' is-open':''}" style="margin-bottom:${gap}px;border:${borderWidth}px solid ${escapeAttr(s.borderColor || '#e5e7eb')};border-radius:${itemRadius}px;overflow:hidden;box-shadow:${shadow};background:${escapeAttr(s.answerBg || '#fff')};">
            <button type="button" class="sbc-accordion-question${open?' is-open':''}" onclick="event.stopPropagation();toggleAccordionItem('${el.id}',${index})" style="width:100%;display:flex;align-items:center;justify-content:space-between;gap:12px;text-align:left;border:0;background:${escapeAttr(open ? (s.questionOpenBg || s.questionBg || '#fff') : (s.questionBg || '#fff'))};color:${escapeAttr(open ? (s.questionOpenColor || s.questionColor || '#111827') : (s.questionColor || '#111827'))};--acc-hover:${escapeAttr(s.questionHoverBg || '#f8fafc')};padding:${padY}px ${padX}px;cursor:pointer;font:600 ${qFont}px/1.35 Inter,Arial,sans-serif;box-sizing:border-box;transition:background .2s ease,color .2s ease;">
                ${children}
            </button>
            <div class="sbc-accordion-answer ${open?'is-open':'is-closed'}" style="${answerStyle}">${escapeHtml(item.answer || '')}</div>
        </div>`;
    }).join('');
    return `<div class="sbc-accordion-shell" style="${wrapper}">
        <div style="color:${escapeAttr(s.titleColor || '#111827')};font-size:${Math.max(10,parseFloat(s.titleFontSize ?? 28)||28)}px;font-weight:800;line-height:1.2;margin:0 0 8px;">${escapeHtml(s.title || 'Perguntas e respostas')}</div>
        ${s.description ? `<div style="color:${escapeAttr(s.descriptionColor || '#6b7280')};font-size:${Math.max(10,parseFloat(s.descriptionFontSize ?? 14)||14)}px;line-height:1.5;margin:0 0 18px;">${escapeHtml(s.description)}</div>` : ''}
        <div class="sbc-accordion-list">${itemsHTML}</div>
    </div>`;
}


function ensureTestimonialsItems(styles) {
    styles = styles || {};
    if (!Array.isArray(styles.items)) styles.items = [];
    if (!styles.items.length) {
        styles.items.push(
            { text: 'Excelente atendimento e resultado acima do esperado.', name: 'Maria Silva', role: 'Cliente', avatar: '', rating: 5 },
            { text: 'Equipe profissional, rápida e muito atenciosa.', name: 'João Souza', role: 'Empresário', avatar: '', rating: 5 },
            { text: 'Nos ajudaram a colocar nosso projeto no ar com muita qualidade.', name: 'Ana Costa', role: 'Cliente', avatar: '', rating: 5 }
        );
    }
    return styles.items;
}

function addTestimonialItem(id) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'testimonials') return;
    el.styles = el.styles || {};
    const items = ensureTestimonialsItems(el.styles);
    if (items.length >= 30) { showNotification('Limite de 30 depoimentos atingido.','error'); return; }
    saveState();
    items.push({ text:'Escreva o depoimento aqui.', name:'Novo cliente', role:'Cliente', avatar:'', rating:5 });
    el.styles.items = items;
    renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}

function updateTestimonialItem(id, index, prop, value) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'testimonials') return;
    el.styles = el.styles || {};
    const items = ensureTestimonialsItems(el.styles);
    if (!items[index]) return;
    saveState();
    items[index][prop] = value;
    el.styles.items = items;
    renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}

function removeTestimonialItem(id, index) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'testimonials') return;
    el.styles = el.styles || {};
    const items = ensureTestimonialsItems(el.styles);
    if (!items[index]) return;
    saveState();
    items.splice(index,1);
    if (!items.length) items.push({ text:'Escreva o depoimento aqui.', name:'Novo cliente', role:'Cliente', avatar:'', rating:5 });
    el.styles.items = items;
    renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}

function reorderTestimonialItem(id, from, to) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'testimonials') return;
    el.styles = el.styles || {};
    const items = ensureTestimonialsItems(el.styles);
    if (from < 0 || to < 0 || from >= items.length || to >= items.length || from === to) return;
    saveState();
    const [item] = items.splice(from,1);
    items.splice(to,0,item);
    el.styles.items = items;
    renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}

function handleTestimonialAvatarUpload(input, id, index) {
    const file = input.files?.[0];
    input.value='';
    if (!file) return;
    if (!file.type.startsWith('image/')) { showNotification('Selecione uma imagem.','error'); return; }
    if (file.size > 2.5 * 1024 * 1024) { showNotification('Prefira uma imagem de até 2,5 MB.','error'); return; }
    const reader = new FileReader();
    reader.onload = e => updateTestimonialItem(id,index,'avatar',e.target.result);
    reader.onerror = () => showNotification('Erro ao ler a imagem.','error');
    reader.readAsDataURL(file);
}

function buildTestimonialsHTML(el) {
    const s = el.styles || {};
    const items = ensureTestimonialsItems(s);
    const align = ['left','center','right'].includes(s.alignment) ? s.alignment : 'center';
    let width = '100%';
    const wn = parseFloat(s.width);
    if (Number.isFinite(wn) && wn > 0) width = `${s.widthUnit === 'px' ? Math.min(2000,wn) : Math.min(100,Math.max(1,wn))}${s.widthUnit === 'px'?'px':'%'}`;
    const cols = ['1','2','3','4'].includes(String(s.columns)) ? String(s.columns) : '3';
    const gap = Math.max(0,Math.min(80,parseFloat(s.gap ?? 20)||0));
    const cardRadius = Math.max(0,Math.min(60,parseFloat(s.cardRadius ?? 14)||0));
    const cardPadding = Math.max(8,Math.min(80,parseFloat(s.cardPadding ?? 24)||0));
    const cardBorderWidth = Math.max(0,Math.min(10,parseFloat(s.cardBorderWidth ?? 1)||0));
    const avatarSize = Math.max(24,Math.min(180,parseFloat(s.avatarSize ?? 56)||56));
    const avatarRadius = Math.max(0,Math.min(50,parseFloat(s.avatarRadius ?? 50)||50));
    const shadow = s.shadow ? `${parseFloat(s.shadowX??0)||0}px ${parseFloat(s.shadowY??6)||0}px ${Math.max(0,parseFloat(s.shadowBlur??18)||0)}px ${parseFloat(s.shadowSpread??0)||0}px ${s.shadowColor || 'rgba(0,0,0,.10)'}` : 'none';
    const cssGrid = `display:grid!important;width:100%!important;max-width:100%!important;min-width:0;grid-template-columns:repeat(${cols},minmax(0,1fr));grid-auto-flow:row;gap:${gap}px;align-items:stretch;justify-items:stretch;box-sizing:border-box;`;
    const wrapper = `display:block!important;position:relative!important;width:${width};max-width:100%;min-width:0;margin-left:${align==='center'||align==='right'?'auto':'0'};margin-right:${align==='center'||align==='left'?'auto':'0'};box-sizing:border-box;clear:both!important;float:none!important;`;
    const title = escapeHtml(s.title || 'O que nossos clientes dizem');
    const desc = escapeHtml(s.description || '');
    const cards = items.map((item,index)=>{
        const rating = Math.max(0,Math.min(5,parseInt(item.rating ?? 5,10)||0));
        const stars = '★'.repeat(rating) + '☆'.repeat(5-rating);
        const avatar = item.avatar
            ? `<img src="${escapeAttr(item.avatar)}" alt="${escapeAttr(item.name||'Cliente')}" style="width:${avatarSize}px;height:${avatarSize}px;object-fit:cover;border-radius:${avatarRadius}%;display:block;">`
            : `<div style="width:${avatarSize}px;height:${avatarSize}px;border-radius:${avatarRadius}%;display:flex;align-items:center;justify-content:center;background:#e5e7eb;color:#6b7280;font-size:${Math.max(14,avatarSize*.32)}px;font-weight:800;">${escapeHtml((item.name||'C').trim().charAt(0).toUpperCase())}</div>`;
        return `<article class="sbc-testimonial-card" draggable="false" style="background:${escapeAttr(s.cardBg||'#fff')};border:${cardBorderWidth}px ${escapeAttr(s.cardBorderStyle||'solid')} ${escapeAttr(s.cardBorderColor||'#e5e7eb')};border-radius:${cardRadius}px;padding:${cardPadding}px;box-shadow:${escapeAttr(shadow)};--testimonial-hover:${escapeAttr(s.hoverBg||'#f8fafc')};--testimonial-lift:${Math.max(0,Math.min(12,parseFloat(s.hoverLift??3)||3))}px;transition:transform .2s ease,background .2s ease,box-shadow .2s ease;">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">${avatar}<div style="min-width:0;"><div style="color:${escapeAttr(s.nameColor||'#111827')};font-size:${Math.max(10,parseFloat(s.nameFontSize??16)||16)}px;font-weight:700;line-height:1.25;">${escapeHtml(item.name||'Cliente')}</div><div style="color:${escapeAttr(s.roleColor||'#6b7280')};font-size:${Math.max(9,parseFloat(s.roleFontSize??13)||13)}px;line-height:1.35;">${escapeHtml(item.role||'')}</div></div></div>
            <div style="color:${escapeAttr(s.starColor||'#f59e0b')};font-size:15px;letter-spacing:1px;margin-bottom:10px;" aria-label="${rating} estrelas">${stars}</div>
            <div style="color:${escapeAttr(s.textColor||'#374151')};font-size:${Math.max(10,parseFloat(s.textFontSize??15)||15)}px;line-height:1.65;">“${escapeHtml(item.text||'')}”</div>
        </article>`;
    }).join('');
    return `<section class="sbc-testimonials-shell" data-cols="${cols}" style="${wrapper}">
        <header style="display:block!important;position:relative!important;width:100%!important;max-width:100%!important;box-sizing:border-box;margin:0 0 22px 0!important;padding:0!important;clear:both!important;float:none!important;text-align:${align};"><div style="color:${escapeAttr(s.titleColor||'#111827')};font-size:${Math.max(14,parseFloat(s.titleFontSize??30)||30)}px;font-weight:800;line-height:1.2;margin-bottom:8px;">${title}</div>${desc?`<div style="color:${escapeAttr(s.descriptionColor||'#6b7280')};font-size:${Math.max(10,parseFloat(s.descriptionFontSize??14)||14)}px;line-height:1.5;">${desc}</div>`:''}</header>
        <div class="sbc-testimonials-grid" style="${cssGrid}">${cards}</div>
    </section>`;
}

function ensurePricingPlans(styles) {
    styles = styles || {};
    if (!Array.isArray(styles.plans)) styles.plans = [];
    if (!styles.plans.length) {
        styles.plans.push(
            { name:'Básico', price:'29', period:'/mês', description:'Para começar', features:['1 site','Suporte básico','Recursos essenciais'], buttonText:'Escolher plano', buttonLink:'#', highlighted:false, badge:'Mais escolhido' },
            { name:'Profissional', price:'59', period:'/mês', description:'Para empresas', features:['5 sites','Suporte prioritário','Recursos avançados'], buttonText:'Escolher plano', buttonLink:'#', highlighted:true, badge:'Mais popular' },
            { name:'Premium', price:'99', period:'/mês', description:'Para equipes', features:['Sites ilimitados','Suporte premium','Todos os recursos'], buttonText:'Escolher plano', buttonLink:'#', highlighted:false, badge:'Premium' }
        );
    }
    return styles.plans;
}

function addPricingPlan(id) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'pricing') return;
    el.styles = el.styles || {};
    const plans = ensurePricingPlans(el.styles);
    if (plans.length >= 12) { showNotification('Limite de 12 planos atingido.','error'); return; }
    saveState();
    plans.push({ name:'Novo plano', price:'0', period:'/mês', description:'Descrição do plano', features:['Benefício 1','Benefício 2'], buttonText:'Escolher plano', buttonLink:'#', highlighted:false, badge:'Destaque' });
    el.styles.plans = plans;
    renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}

function updatePricingPlan(id, index, prop, value) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'pricing') return;
    el.styles = el.styles || {};
    const plans = ensurePricingPlans(el.styles);
    if (!plans[index]) return;
    saveState();
    plans[index][prop] = value;
    el.styles.plans = plans;
    renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}

function updatePricingFeatures(id, index, value) {
    const lines = String(value || '').split(/\r?\n/).map(v=>v.trim()).filter(Boolean).slice(0,30);
    updatePricingPlan(id, index, 'features', lines);
}

function removePricingPlan(id, index) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'pricing') return;
    el.styles = el.styles || {};
    const plans = ensurePricingPlans(el.styles);
    if (!plans[index]) return;
    if (plans.length <= 1) { showNotification('Mantenha pelo menos 1 plano.','error'); return; }
    saveState();
    plans.splice(index,1);
    el.styles.plans = plans;
    renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}

function reorderPricingPlan(id, from, to) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'pricing') return;
    el.styles = el.styles || {};
    const plans = ensurePricingPlans(el.styles);
    if (from < 0 || to < 0 || from >= plans.length || to >= plans.length || from === to) return;
    saveState();
    const [item] = plans.splice(from,1);
    plans.splice(to,0,item);
    el.styles.plans = plans;
    renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}

function ensureCounterItems(styles) {
    styles = styles || {};
    if (!Array.isArray(styles.items) || styles.items.length === 0) {
        styles.items = [
            { value:'500', prefix:'', suffix:'+', label:'Clientes', icon:'fa-users' },
            { value:'120', prefix:'', suffix:'+', label:'Projetos', icon:'fa-briefcase' },
            { value:'98', prefix:'', suffix:'%', label:'Satisfação', icon:'fa-heart' }
        ];
    }
    return styles.items.slice(0, 16);
}
function addCounterItem(id) {
    const el=findElementById(pageContent,id); if(!el||el.type!=='counters') return;
    const items=ensureCounterItems(el.styles); if(items.length>=16){showNotification('Limite de 16 contadores atingido.','error');return;}
    items.push({value:'0',prefix:'',suffix:'',label:'Novo contador',icon:'fa-star'}); el.styles.items=items;
    renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}
function updateCounterItem(id,index,prop,value){
    const el=findElementById(pageContent,id); if(!el||el.type!=='counters') return; const items=ensureCounterItems(el.styles); if(!items[index]) return;
    items[index][prop]=value; el.styles.items=items; renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}
function removeCounterItem(id,index){
    const el=findElementById(pageContent,id); if(!el||el.type!=='counters') return; const items=ensureCounterItems(el.styles); if(items.length<=1){showNotification('Mantenha pelo menos 1 contador.','error');return;}
    items.splice(index,1); el.styles.items=items; renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}
let counterDragState=null;
function startCounterDrag(e,id,index){counterDragState={id,index};e.dataTransfer.effectAllowed='move';e.dataTransfer.setData('text/plain',String(index));}
function allowCounterDrop(e){e.preventDefault();e.dataTransfer.dropEffect='move';}
function dropCounter(e,id,to){e.preventDefault();if(!counterDragState||counterDragState.id!==id)return;let from=counterDragState.index;counterDragState=null;if(from===to)return;const el=findElementById(pageContent,id);if(!el)return;const items=ensureCounterItems(el.styles);const [it]=items.splice(from,1);items.splice(to,0,it);el.styles.items=items;renderCanvas();saveData();selectedElementId=id;updateProperties(id);}
function buildCountersHTML(el){
    const s=el.styles||{}; const items=ensureCounterItems(s); const align=['left','center','right'].includes(s.alignment)?s.alignment:'center';
    const wn=parseFloat(s.width); const width=Number.isFinite(wn)&&wn>0?`${s.widthUnit==='px'?Math.min(2000,wn):Math.min(100,Math.max(1,wn))}${s.widthUnit==='px'?'px':'%'}`:'100%';
    const cols=Math.min(4,Math.max(1,parseInt(s.columns||3,10)||3)); const gap=Math.min(80,Math.max(0,parseFloat(s.gap||20)||20));
    const wrapper=`display:block!important;width:${width};max-width:100%;margin-left:${align==='center'||align==='right'?'auto':'0'};margin-right:${align==='center'||align==='left'?'auto':'0'};box-sizing:border-box;`;
    const cards=items.map((item,i)=>{const val=escapeHtml(item.value??'0'), pre=escapeHtml(item.prefix||''), suf=escapeHtml(item.suffix||''); const icon=item.icon?`<i class="fas ${escapeAttr(item.icon)}" style="font-size:${Math.max(10,parseFloat(s.iconSize||28)||28)}px;color:${escapeAttr(s.iconColor||'#3b82f6')};margin-bottom:10px;"></i>`:''; const sep=(s.separator&&i<items.length-1)?`<div style="width:1px;background:${escapeAttr(s.separatorColor||'#e5e7eb')};position:absolute;right:-${gap/2}px;top:15%;height:70%;"></div>`:''; return `<article class="sbc-counter-card" style="position:relative;min-width:0;background:${escapeAttr(s.cardBg||'#fff')};border:${escapeAttr(s.cardBorderWidth||1)}px ${escapeAttr(s.cardBorderStyle||'solid')} ${escapeAttr(s.cardBorderColor||'#e5e7eb')};border-radius:${Math.max(0,parseFloat(s.cardRadius||14)||0)}px;padding:${Math.max(0,parseFloat(s.cardPadding||24)||24)}px;box-sizing:border-box;text-align:center;box-shadow:${s.shadow?`${s.shadowX||0}px ${s.shadowY||6}px ${s.shadowBlur||18}px ${s.shadowSpread||0}px ${escapeAttr(s.shadowColor||'rgba(0,0,0,0.10)')}`:'none'};--counter-hover:${escapeAttr(s.hoverBg||'#f8fafc')};--counter-lift:${Math.max(0,parseFloat(s.hoverLift||3)||3)}px;">${icon}<div class="sbc-counter-value" data-target="${escapeAttr(item.value??'0')}" style="color:${escapeAttr(s.numberColor||'#111827')};font-size:${Math.max(14,parseFloat(s.numberFontSize||42)||42)}px;font-weight:${parseInt(s.numberWeight||800,10)||800};line-height:1.1;">${pre}${val}${suf}</div><div style="margin-top:8px;color:${escapeAttr(s.labelColor||'#4b5563')};font-size:${Math.max(9,parseFloat(s.labelFontSize||14)||14)}px;">${escapeHtml(item.label||'Contador')}</div>${sep}</article>`}).join('');
    return `<section class="sbc-counters-shell" style="${wrapper}"><header style="width:100%;text-align:${align};margin:0 0 22px;box-sizing:border-box;"><div style="font-size:${Math.max(14,parseFloat(s.titleFontSize||28)||28)}px;font-weight:800;color:${escapeAttr(s.titleColor||'#111827')};margin-bottom:8px;">${escapeHtml(s.title||'Nossos números')}</div><div style="font-size:${Math.max(10,parseFloat(s.descriptionFontSize||14)||14)}px;color:${escapeAttr(s.descriptionColor||'#6b7280')};">${escapeHtml(s.description||'')}</div></header><div style="display:grid;grid-template-columns:repeat(${cols},minmax(0,1fr));gap:${gap}px;">${cards}</div></section>`;
}
function bindCounters(root){
    root?.querySelectorAll('.sbc-counter-card').forEach(card=>{card.addEventListener('mouseenter',()=>{card.style.transform='translateY(-'+(parseFloat(card.style.getPropertyValue('--counter-lift'))||3)+'px)';card.style.background=getComputedStyle(card).getPropertyValue('--counter-hover')||'#f8fafc'});card.addEventListener('mouseleave',()=>{card.style.transform='';card.style.background=''});});
    const vals=root?.querySelectorAll('.sbc-counter-value');
    if(!vals || !vals.length) return;
    const owner = root.closest('[data-id]');
    const counterId = owner?.dataset?.id || null;
    const counterEl = counterId ? findElementById(pageContent, counterId) : null;
    const shouldAnimate = counterEl ? counterEl.styles?.animate !== false : false;
    if(!shouldAnimate) return;
    const duration = Math.max(200, parseFloat(counterEl.styles?.duration || 1200) || 1200);
    vals.forEach(node=>{
        const raw=node.dataset.target||'0';
        const end=parseFloat(String(raw).replace(/[^0-9.+-]/g,''));
        if(!Number.isFinite(end)) return;
        const text=node.textContent || '';
        const prefix=text.match(/^[^0-9-]*/)?.[0]||'';
        const suffix=text.match(/[^0-9.\-+]*$/)?.[0]||'';
        const start=performance.now();
        function tick(now){
            const p=Math.min(1,(now-start)/duration);
            const eased=1-Math.pow(1-p,3);
            node.textContent=prefix+Math.round(end*eased).toLocaleString('pt-BR')+suffix;
            if(p<1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    });
}

function ensureFeatureItems(styles){
    styles = styles || {};
    if(!Array.isArray(styles.items)) styles.items=[];
    if(!styles.items.length){
        styles.items.push(
            {name:'Rápido',description:'Seu site pronto com agilidade.',icon:'fa-bolt'},
            {name:'Seguro',description:'Boas práticas e proteção para o seu projeto.',icon:'fa-shield-halved'},
            {name:'Responsivo',description:'Visual perfeito em computador e celular.',icon:'fa-mobile-screen'}
        );
    }
    return styles.items;
}
function addFeatureItem(id){
    const el=findElementById(pageContent,id); if(!el||el.type!=='features') return;
    const items=ensureFeatureItems(el.styles); if(items.length>=20){showNotification('Limite de 20 recursos atingido.','error');return;}
    saveState(); items.push({name:'Novo recurso',description:'Descrição do recurso',icon:'fa-star'});
    el.styles.items=items; renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}
function updateFeatureItem(id,index,prop,value){
    const el=findElementById(pageContent,id); if(!el||el.type!=='features') return;
    const items=ensureFeatureItems(el.styles); if(!items[index]) return;
    saveState(); items[index][prop]=value; el.styles.items=items; renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}
function removeFeatureItem(id,index){
    const el=findElementById(pageContent,id); if(!el||el.type!=='features') return;
    const items=ensureFeatureItems(el.styles); if(!items[index]) return;
    if(items.length<=1){showNotification('Mantenha pelo menos 1 recurso.','error');return;}
    saveState(); items.splice(index,1); el.styles.items=items; renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}
let featureDragState=null;
function startFeatureDrag(e,id,index){featureDragState={id,index};e.dataTransfer.effectAllowed='move';e.dataTransfer.setData('text/plain',String(index));}
function allowFeatureDrop(e){e.preventDefault();e.dataTransfer.dropEffect='move';}
function dropFeature(e,id,to){e.preventDefault();if(!featureDragState||featureDragState.id!==id)return;let from=featureDragState.index;featureDragState=null;if(from===to)return;const el=findElementById(pageContent,id);if(!el)return;const items=ensureFeatureItems(el.styles);const [item]=items.splice(from,1);items.splice(to,0,item);el.styles.items=items;renderCanvas();saveData();selectedElementId=id;updateProperties(id);}
function buildFeaturesHTML(el){
    const s=el.styles||{};
    // O tamanho definido na parte interna responsiva precisa entrar no HTML
    // que o builder gera. Assim o valor funciona no editor e no Preview real.
    const __featureMode = getResponsiveDeviceMode();
    const __featureParts = s.parts || {};
    const __featurePart = (key, prop, fallback) => {
        const v = __featureParts?.[key]?.responsive?.[__featureMode]?.[prop];
        return (v !== undefined && v !== null && String(v).trim() !== '') ? v : fallback;
    };
    const __featureResponsive = s.responsive?.[__featureMode] || {};
    // A PARTE INTERNA é a fonte principal para o título/descrição.
    // O campo "Tamanho" do painel grava em styles.parts; se houver um
    // valor ali, ele sempre vence qualquer valor antigo em styles.responsive.
    const __featureTitleSize = __featurePart('features.title','fontSize', __featureResponsive.titleFontSize ?? 28);
    const __featureTitleLine = __featurePart('features.title','lineHeight', __featureResponsive.titleLineHeight ?? 1.2);
    const __featureDescSize = __featurePart('features.description','fontSize', __featureResponsive.descriptionFontSize ?? 14);
    const __featureDescLine = __featurePart('features.description','lineHeight', __featureResponsive.descriptionLineHeight ?? 1.5); const items=ensureFeatureItems(s);
    const align=['left','center','right'].includes(s.alignment)?s.alignment:'center';
    const cardAlign=['left','center','right'].includes(s.cardTextAlign)?s.cardTextAlign:align;
    const wn=parseFloat(s.width); const width=Number.isFinite(wn)&&wn>0?`${s.widthUnit==='px'?Math.min(2000,wn):Math.min(100,Math.max(1,wn))}${s.widthUnit==='px'?'px':'%'}`:'100%';
    const cols=Math.min(4,Math.max(1,parseInt(s.columns||3,10)||3)); const gap=Math.min(80,Math.max(0,parseFloat(s.gap||20)||20));
    const shadow=s.shadow?`${parseFloat(s.shadowX||0)||0}px ${parseFloat(s.shadowY||6)||6}px ${Math.max(0,parseFloat(s.shadowBlur||18)||18)}px ${parseFloat(s.shadowSpread||0)||0}px ${s.shadowColor||'rgba(0,0,0,.10)'}`:'none';
    const wrapper=`display:block!important;position:relative!important;width:${width};max-width:100%;min-width:0;margin-left:${align==='center'||align==='right'?'auto':'0'};margin-right:${align==='center'||align==='left'?'auto':'0'};box-sizing:border-box;clear:both!important;float:none!important;background:${escapeAttr(s.sectionBg||'#fff')};border-radius:${Math.max(0,parseFloat(s.sectionRadius||0)||0)}px;padding:${Math.max(0,parseFloat(s.sectionPaddingY||0)||0)}px 0;`;
    const iconSize=Math.max(10,parseFloat(s.iconSize||28)||28);
    const iconBox=Math.max(iconSize+16,Math.min(180,parseFloat(s.iconBoxSize||76)||76));
    const iconRadius=Math.max(0,Math.min(50,parseFloat(s.iconRadius??50)||0));
    const iconBorderStyle=['solid','dashed','dotted','double','none'].includes(s.iconBorderStyle)?s.iconBorderStyle:'none';
    const iconBorderWidth=Math.max(0,Math.min(8,parseFloat(s.iconBorderWidth||0)||0));
    const iconAnim=['none','pulse','rotate','bounce'].includes(s.iconHoverAnimation)?s.iconHoverAnimation:'none';
    const cards=items.map((item)=>{
        const icon=item.icon||'fa-star';
        return `<article class="sbc-feature-card" style="position:relative;min-width:0;background:${escapeAttr(s.cardBg||'#fff')};border:${Math.max(0,parseFloat(s.cardBorderWidth||1)||0)}px ${escapeAttr(s.cardBorderStyle||'solid')} ${escapeAttr(s.cardBorderColor||'#e5e7eb')};border-radius:${Math.max(0,parseFloat(s.cardRadius||14)||0)}px;padding:${Math.max(8,parseFloat(s.cardPadding||24)||24)}px;box-sizing:border-box;text-align:${cardAlign};box-shadow:${escapeAttr(shadow)};--feature-hover:${escapeAttr(s.hoverBg||'#f8fafc')};--feature-lift:${Math.max(0,Math.min(20,parseFloat(s.hoverLift||3)||3))}px;transition:transform .2s ease,background .2s ease,box-shadow .2s ease;">
            <div class="sbc-feature-icon-box" style="width:${iconBox}px;height:${iconBox}px;border-radius:${iconRadius}%;background:${escapeAttr(s.iconBg||'#eff6ff')};border:${iconBorderWidth}px ${iconBorderStyle} ${escapeAttr(s.iconBorderColor||'#dbeafe')};display:flex;align-items:center;justify-content:center;margin:${cardAlign==='left'?'0':cardAlign==='right'?'0 0 16px auto':'0 auto 16px'};box-sizing:border-box;">
                <i class="fas ${escapeAttr(icon)} ${iconAnim!=='none'?'feature-icon-anim':''}" data-feature-animation="${iconAnim}" style="font-size:${iconSize}px;color:${escapeAttr(s.iconColor||'#3b82f6')};"></i>
            </div>
            <div data-sbc-part="features.${items.indexOf(item)}.title" style="color:${escapeAttr(s.nameColor||'#111827')};font-size:${Math.max(11,parseFloat(s.nameFontSize||18)||18)}px;font-weight:800;line-height:1.3;margin-bottom:8px;word-break:break-word;">${escapeHtml(item.name||'Recurso')}</div>
            <div data-sbc-part="features.${items.indexOf(item)}.description" style="color:${escapeAttr(s.textColor||'#4b5563')};font-size:${Math.max(10,parseFloat(s.textFontSize||14)||14)}px;line-height:1.6;word-break:break-word;">${escapeHtml(item.description||'')}</div>
        </article>`;
    }).join('');
    return `<section class="sbc-features-shell" style="${wrapper}">
        <header style="display:block!important;width:100%!important;box-sizing:border-box;margin:0 0 24px;padding:0;text-align:${align};"><div data-sbc-part="features.title" style="font-size:${Math.max(1,parseFloat(__featureTitleSize)||28)}px!important;font-weight:800;line-height:${escapeAttr(__featureTitleLine)}!important;color:${escapeAttr(s.titleColor||'#111827')};margin-bottom:8px;word-break:break-word;">${escapeHtml(s.title||'Por que escolher nossos serviços?')}</div><div data-sbc-part="features.description" style="font-size:${Math.max(1,parseFloat(__featureDescSize)||14)}px!important;line-height:${escapeAttr(__featureDescLine)}!important;color:${escapeAttr(s.descriptionColor||'#6b7280')};word-break:break-word;">${escapeHtml(s.description||'')}</div></header>
        <div class="sbc-features-grid" style="display:grid;grid-template-columns:repeat(${cols},minmax(0,1fr));gap:${gap}px;width:100%;max-width:100%;min-width:0;box-sizing:border-box;align-items:stretch;">${cards}</div>
    </section>`;
}
function bindFeaturesHover(root){root?.querySelectorAll('.sbc-feature-card').forEach(card=>{const icon=card.querySelector('.sbc-feature-icon-box');const iconNode=card.querySelector('.feature-icon-anim');card.addEventListener('mouseenter',()=>{card.style.transform='translateY(-'+(parseFloat(card.style.getPropertyValue('--feature-lift'))||3)+'px)';card.style.background=getComputedStyle(card).getPropertyValue('--feature-hover')||'#f8fafc';if(iconNode){const type=iconNode.getAttribute('data-feature-animation');iconNode.style.animation=type==='pulse'?'featurePulse .8s ease':type==='rotate'?'featureRotate .6s ease':type==='bounce'?'featureBounce .8s ease':'';}});card.addEventListener('mouseleave',()=>{card.style.transform='';card.style.background='';if(iconNode)iconNode.style.animation=''})});}
if(!document.getElementById('sbc-feature-anim-style')){const st=document.createElement('style');st.id='sbc-feature-anim-style';st.textContent='@keyframes featurePulse{0%,100%{transform:scale(1)}50%{transform:scale(1.15)}}@keyframes featureRotate{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}@keyframes featureBounce{0%,100%{transform:translateY(0)}35%{transform:translateY(-7px)}65%{transform:translateY(2px)}}';document.head.appendChild(st)}

function ensureTimelineItems(styles){
    styles = styles || {};
    if(!Array.isArray(styles.items)) styles.items=[];
    if(!styles.items.length){
        styles.items.push(
            {date:'2024',title:'Fundação da empresa',text:'Iniciamos nossa jornada com foco em qualidade e inovação.',icon:'fa-flag'},
            {date:'2025',title:'Primeiro grande projeto',text:'Conquistamos novos clientes e ampliamos nossa atuação.',icon:'fa-rocket'},
            {date:'2026',title:'Expansão',text:'Novos produtos, novos desafios e uma equipe ainda maior.',icon:'fa-chart-line'}
        );
    }
    return styles.items;
}
function addTimelineItem(id){
    const el=findElementById(pageContent,id); if(!el||el.type!=='timeline') return;
    const items=ensureTimelineItems(el.styles); if(items.length>=30){showNotification('Limite de 30 eventos atingido.','error');return;}
    saveState(); items.push({date:'2026',title:'Novo evento',text:'Descrição do evento',icon:'fa-circle'});
    el.styles.items=items; renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}
function updateTimelineItem(id,index,prop,value){
    const el=findElementById(pageContent,id); if(!el||el.type!=='timeline') return;
    const items=ensureTimelineItems(el.styles); if(!items[index]) return;
    saveState(); items[index][prop]=value; el.styles.items=items; renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}
function removeTimelineItem(id,index){
    const el=findElementById(pageContent,id); if(!el||el.type!=='timeline') return;
    const items=ensureTimelineItems(el.styles); if(!items[index]) return;
    if(items.length<=1){showNotification('Mantenha pelo menos 1 evento.','error');return;}
    saveState(); items.splice(index,1); el.styles.items=items; renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}
let timelineDragState=null;
function startTimelineDrag(e,id,index){timelineDragState={id,index};e.dataTransfer.effectAllowed='move';e.dataTransfer.setData('text/plain',String(index));}
function allowTimelineDrop(e){e.preventDefault();e.dataTransfer.dropEffect='move';}
function dropTimeline(e,id,to){
    e.preventDefault();
    if(!timelineDragState||timelineDragState.id!==id) return;
    const from=timelineDragState.index; timelineDragState=null;
    if(from===to) return;
    const el=findElementById(pageContent,id); if(!el) return;
    const items=ensureTimelineItems(el.styles); const [item]=items.splice(from,1); items.splice(to,0,item);
    el.styles.items=items; renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}
function buildTimelineHTML(el){
    const s=el.styles||{}, items=ensureTimelineItems(s);
    const align=['left','center','right'].includes(s.alignment)?s.alignment:'center';
    const wn=parseFloat(s.width); const width=Number.isFinite(wn)&&wn>0?`${s.widthUnit==='px'?Math.min(2000,wn):Math.min(100,Math.max(1,wn))}${s.widthUnit==='px'?'px':'%'}`:'100%';
    const gap=Math.min(100,Math.max(8,parseFloat(s.gap||28)||28));
    const lineColor=s.lineColor||'#dbe3ef', lineWidth=Math.min(12,Math.max(1,parseFloat(s.lineWidth||3)||3));
    const markerBg=s.markerBg||'#3b82f6', markerColor=s.markerColor||'#fff';
    const cardBg=s.cardBg||'#fff', cardRadius=Math.max(0,Math.min(60,parseFloat(s.cardRadius||14)||14)), cardPadding=Math.max(8,Math.min(100,parseFloat(s.cardPadding||20)||20));
    const borderStyle=['solid','dashed','dotted','double','none'].includes(s.cardBorderStyle)?s.cardBorderStyle:'solid';
    const borderWidth=Math.max(0,Math.min(10,parseFloat(s.cardBorderWidth||1)||0));
    const shadow=s.shadow?`${parseFloat(s.shadowX||0)||0}px ${parseFloat(s.shadowY||6)||0}px ${Math.max(0,parseFloat(s.shadowBlur||18)||18)}px ${parseFloat(s.shadowSpread||0)||0}px ${s.shadowColor||'rgba(0,0,0,.10)'}`:'none';
    const header=`<header style="width:100%;text-align:${align};margin:0 0 26px;box-sizing:border-box;"><div style="font-size:${Math.max(14,parseFloat(s.titleFontSize||28)||28)}px;font-weight:800;line-height:1.2;color:${escapeAttr(s.titleColor||'#111827')};margin-bottom:8px;">${escapeHtml(s.title||'Nossa história')}</div>${s.description?`<div style="font-size:${Math.max(10,parseFloat(s.descriptionFontSize||14)||14)}px;line-height:1.5;color:${escapeAttr(s.descriptionColor||'#6b7280')};">${escapeHtml(s.description||'')}</div>`:''}</header>`;
    if((s.orientation||'vertical')==='horizontal'){
        const cols=Math.min(4,Math.max(1,parseInt(s.columns||3,10)||3));
        const cards=items.map(it=>`<article style="min-width:0;background:${escapeAttr(cardBg)};border:${borderWidth}px ${borderStyle} ${escapeAttr(s.cardBorderColor||'#e5e7eb')};border-radius:${cardRadius}px;padding:${cardPadding}px;box-shadow:${shadow};box-sizing:border-box;"><div style="width:38px;height:38px;border-radius:50%;background:${escapeAttr(markerBg)};color:${escapeAttr(markerColor)};display:flex;align-items:center;justify-content:center;margin-bottom:12px;"><i class="fas ${escapeAttr(it.icon||'fa-circle')}"></i></div><div style="color:${escapeAttr(s.dateColor||'#3b82f6')};font-size:${Math.max(9,parseFloat(s.dateFontSize||13)||13)}px;font-weight:800;margin-bottom:6px;">${escapeHtml(it.date||'')}</div><div style="color:${escapeAttr(s.itemTitleColor||'#111827')};font-size:${Math.max(11,parseFloat(s.itemTitleFontSize||18)||18)}px;font-weight:800;margin-bottom:7px;">${escapeHtml(it.title||'Evento')}</div><div style="color:${escapeAttr(s.itemTextColor||'#4b5563')};font-size:${Math.max(10,parseFloat(s.itemTextFontSize||14)||14)}px;line-height:1.6;">${escapeHtml(it.text||'')}</div></article>`).join('');
        return `<section class="sbc-timeline-shell" style="width:${width};max-width:100%;min-width:0;margin-left:${align==='center'||align==='right'?'auto':'0'};margin-right:${align==='center'||align==='left'?'auto':'0'};box-sizing:border-box;">${header}<div style="display:grid;grid-template-columns:repeat(${cols},minmax(0,1fr));gap:${gap}px;align-items:stretch;">${cards}</div></section>`;
    }
    const rows=items.map((it,i)=>`<article style="position:relative;display:grid;grid-template-columns:1fr 56px 1fr;align-items:center;gap:16px;"><div style="${i%2===0?'grid-column:1;text-align:right;':'grid-column:3;text-align:left;'}"><div style="display:inline-block;max-width:100%;background:${escapeAttr(cardBg)};border:${borderWidth}px ${borderStyle} ${escapeAttr(s.cardBorderColor||'#e5e7eb')};border-radius:${cardRadius}px;padding:${cardPadding}px;box-shadow:${shadow};box-sizing:border-box;"><div style="color:${escapeAttr(s.dateColor||'#3b82f6')};font-size:${Math.max(9,parseFloat(s.dateFontSize||13)||13)}px;font-weight:800;margin-bottom:6px;">${escapeHtml(it.date||'')}</div><div style="color:${escapeAttr(s.itemTitleColor||'#111827')};font-size:${Math.max(11,parseFloat(s.itemTitleFontSize||18)||18)}px;font-weight:800;margin-bottom:7px;">${escapeHtml(it.title||'Evento')}</div><div style="color:${escapeAttr(s.itemTextColor||'#4b5563')};font-size:${Math.max(10,parseFloat(s.itemTextFontSize||14)||14)}px;line-height:1.6;">${escapeHtml(it.text||'')}</div></div></div><div style="grid-column:2;grid-row:1;width:40px;height:40px;border-radius:50%;background:${escapeAttr(markerBg)};color:${escapeAttr(markerColor)};display:flex;align-items:center;justify-content:center;justify-self:center;z-index:2;box-shadow:0 0 0 5px rgba(255,255,255,.95);"><i class="fas ${escapeAttr(it.icon||'fa-circle')}"></i></div><div style="${i%2===0?'grid-column:3;':'grid-column:1;grid-row:1;'}"></div></article>`).join('');
    return `<section class="sbc-timeline-shell" style="width:${width};max-width:100%;min-width:0;margin-left:${align==='center'||align==='right'?'auto':'0'};margin-right:${align==='center'||align==='left'?'auto':'0'};box-sizing:border-box;">${header}<div style="position:relative;display:flex;flex-direction:column;gap:${gap}px;"><div aria-hidden="true" style="position:absolute;top:0;bottom:0;left:50%;transform:translateX(-50%);width:${lineWidth}px;background:${escapeAttr(lineColor)};border-radius:999px;pointer-events:none;z-index:1;"></div>${rows}</div></section>`;
}

function ensureTeamItems(styles){
    styles = styles || {};
    if(!Array.isArray(styles.items)) styles.items=[];
    if(!styles.items.length){
        styles.items.push(
            {name:'João Silva',role:'CEO',description:'Especialista em estratégia e inovação.',avatar:'',linkedin:'',instagram:'',facebook:'',website:''},
            {name:'Maria Souza',role:'Designer',description:'Responsável por experiências digitais e visuais.',avatar:'',linkedin:'',instagram:'',facebook:'',website:''},
            {name:'Ana Costa',role:'Desenvolvedora',description:'Transforma ideias em projetos rápidos e eficientes.',avatar:'',linkedin:'',instagram:'',facebook:'',website:''}
        );
    }
    return styles.items;
}
function addTeamItem(id){
    const el=findElementById(pageContent,id); if(!el||el.type!=='team') return;
    const items=ensureTeamItems(el.styles); if(items.length>=20){showNotification('Limite de 20 membros atingido.','error');return;}
    saveState(); items.push({name:'Novo membro',role:'Cargo',description:'Descrição do membro',avatar:'',linkedin:'',instagram:'',facebook:'',website:''});
    el.styles.items=items; renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}
function updateTeamItem(id,index,prop,value){
    const el=findElementById(pageContent,id); if(!el||el.type!=='team') return;
    const items=ensureTeamItems(el.styles); if(!items[index]) return;
    saveState(); items[index][prop]=value; el.styles.items=items; renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}
function removeTeamItem(id,index){
    const el=findElementById(pageContent,id); if(!el||el.type!=='team') return;
    const items=ensureTeamItems(el.styles); if(!items[index]) return;
    if(items.length<=1){showNotification('Mantenha pelo menos 1 membro.','error');return;}
    saveState(); items.splice(index,1); el.styles.items=items; renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}
let teamDragState=null;
function startTeamDrag(e,id,index){teamDragState={id,index};e.dataTransfer.effectAllowed='move';e.dataTransfer.setData('text/plain',String(index));}
function allowTeamDrop(e){e.preventDefault();e.dataTransfer.dropEffect='move';}
function dropTeam(e,id,to){
    e.preventDefault();
    if(!teamDragState||teamDragState.id!==id) return;
    const from=teamDragState.index; teamDragState=null;
    if(from===to) return;
    const el=findElementById(pageContent,id); if(!el) return;
    const items=ensureTeamItems(el.styles); const [item]=items.splice(from,1); items.splice(to,0,item);
    el.styles.items=items; renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}
function handleTeamAvatarUpload(input,id,index){
    const file=input.files?.[0]; input.value=''; if(!file) return;
    if(!file.type.startsWith('image/')){showNotification('Selecione uma imagem.','error');return;}
    const reader=new FileReader();
    reader.onload=e=>{ const el=findElementById(pageContent,id); if(!el) return; const items=ensureTeamItems(el.styles); if(!items[index]) return; saveState(); items[index].avatar=e.target.result; el.styles.items=items; renderCanvas(); saveData(); selectedElementId=id; updateProperties(id); };
    reader.onerror=()=>showNotification('Erro ao ler a imagem.','error');
    reader.readAsDataURL(file);
}
function bindTeamHover(root){
    root?.querySelectorAll('.sbc-team-card').forEach(card=>{
        card.addEventListener('mouseenter',()=>{card.style.transform='translateY(-'+(parseFloat(card.style.getPropertyValue('--team-lift'))||3)+'px)';card.style.background=getComputedStyle(card).getPropertyValue('--team-hover')||'#f8fafc';});
        card.addEventListener('mouseleave',()=>{card.style.transform='';card.style.background='';});
    });
}
function buildTeamHTML(el){
    const s=el.styles||{}; const items=ensureTeamItems(s);
    const align=['left','center','right'].includes(s.alignment)?s.alignment:'center';
    const wn=parseFloat(s.width); const width=Number.isFinite(wn)&&wn>0?`${s.widthUnit==='px'?Math.min(2000,wn):Math.min(100,Math.max(1,wn))}${s.widthUnit==='px'?'px':'%'}`:'100%';
    const cols=Math.min(4,Math.max(1,parseInt(s.columns||3,10)||3)); const gap=Math.min(80,Math.max(0,parseFloat(s.gap||20)||20));
    const shadow=s.shadow?`${parseFloat(s.shadowX||0)||0}px ${parseFloat(s.shadowY||6)||6}px ${Math.max(0,parseFloat(s.shadowBlur||18)||18)}px ${parseFloat(s.shadowSpread||0)||0}px ${s.shadowColor||'rgba(0,0,0,.10)'}`:'none';
    const cardBorderStyle=['solid','dashed','dotted','double','none'].includes(s.cardBorderStyle)?s.cardBorderStyle:'solid';
    const cardBorderWidth=Math.max(0,Math.min(10,parseFloat(s.cardBorderWidth||1)||0));
    const cardRadius=Math.max(0,Math.min(60,parseFloat(s.cardRadius||14)||14));
    const cardPadding=Math.max(8,Math.min(100,parseFloat(s.cardPadding||24)||24));
    const avatarSize=Math.max(32,Math.min(200,parseFloat(s.avatarSize||84)||84));
    const avatarRadius=Math.max(0,Math.min(50,parseFloat(s.avatarRadius??50)||0));
    const cards=items.map(item=>{
        const avatar=item.avatar ? `<img src="${escapeAttr(item.avatar)}" alt="${escapeAttr(item.name||'Membro')}" style="width:${avatarSize}px;height:${avatarSize}px;object-fit:cover;border-radius:${avatarRadius}%;display:block;margin:0 auto 16px;">` : `<div style="width:${avatarSize}px;height:${avatarSize}px;border-radius:${avatarRadius}%;background:${escapeAttr(s.avatarBg||'#eef2f7')};display:flex;align-items:center;justify-content:center;margin:0 auto 16px;color:${escapeAttr(s.nameColor||'#111827')};font-size:${Math.max(16,avatarSize*0.34)}px;font-weight:800;">${escapeHtml((item.name||'M').trim().charAt(0).toUpperCase())}</div>`;
        const socials=[['linkedin','fab fa-linkedin-in'],['instagram','fab fa-instagram'],['facebook','fab fa-facebook-f'],['website','fas fa-globe']].filter(([k])=>item[k]);
        const socialHtml=socials.length?`<div style="display:flex;gap:8px;justify-content:center;margin-top:14px;flex-wrap:wrap;">${socials.map(([k,ic])=>`<a href="${escapeAttr(item[k])}" target="_blank" rel="noopener" style="width:32px;height:32px;border-radius:50%;background:#f3f4f6;color:${escapeAttr(s.nameColor||'#111827')};display:flex;align-items:center;justify-content:center;text-decoration:none;" onclick="event.stopPropagation();"> <i class="${ic}"></i></a>`).join('')}</div>`:'';
        return `<article class="sbc-team-card" style="position:relative;min-width:0;box-sizing:border-box;background:${escapeAttr(s.cardBg||'#fff')};border:${cardBorderWidth}px ${cardBorderStyle} ${escapeAttr(s.cardBorderColor||'#e5e7eb')};border-radius:${cardRadius}px;padding:${cardPadding}px;box-shadow:${shadow};text-align:center;--team-hover:${escapeAttr(s.hoverBg||'#f8fafc')};--team-lift:${Math.max(0,Math.min(20,parseFloat(s.hoverLift||3)||3))}px;transition:transform .2s ease,background .2s ease,box-shadow .2s ease;">
            ${avatar}
            <div style="color:${escapeAttr(s.nameColor||'#111827')};font-size:${Math.max(11,parseFloat(s.nameFontSize||18)||18)}px;font-weight:800;line-height:1.3;margin-bottom:5px;word-break:break-word;">${escapeHtml(item.name||'Membro')}</div>
            <div style="color:${escapeAttr(s.roleColor||'#6b7280')};font-size:${Math.max(9,parseFloat(s.roleFontSize||13)||13)}px;line-height:1.4;margin-bottom:10px;">${escapeHtml(item.role||'')}</div>
            <div style="color:${escapeAttr(s.textColor||'#4b5563')};font-size:${Math.max(10,parseFloat(s.textFontSize||14)||14)}px;line-height:1.6;word-break:break-word;">${escapeHtml(item.description||'')}</div>
            ${socialHtml}
        </article>`;
    }).join('');
    const responsiveGrid=`display:grid;grid-template-columns:repeat(${cols},minmax(0,1fr));gap:${gap}px;width:100%;max-width:100%;min-width:0;align-items:stretch;box-sizing:border-box;`;
    return `<section class="sbc-team-shell" style="display:block!important;position:relative!important;width:${width};max-width:100%;min-width:0;margin-left:${align==='center'||align==='right'?'auto':'0'};margin-right:${align==='center'||align==='left'?'auto':'0'};box-sizing:border-box;clear:both!important;float:none!important;">
        <header style="display:block!important;width:100%!important;box-sizing:border-box;margin:0 0 24px;padding:0;text-align:${align};"><div style="font-size:${Math.max(14,parseFloat(s.titleFontSize||30)||30)}px;font-weight:800;line-height:1.2;color:${escapeAttr(s.titleColor||'#111827')};margin-bottom:8px;word-break:break-word;">${escapeHtml(s.title||'Nossa equipe')}</div><div style="font-size:${Math.max(10,parseFloat(s.descriptionFontSize||14)||14)}px;line-height:1.5;color:${escapeAttr(s.descriptionColor||'#6b7280')};word-break:break-word;">${escapeHtml(s.description||'')}</div></header>
        <div class="sbc-team-grid" style="${responsiveGrid}">${cards}</div>
    </section>`;
}


function ensureTabsItems(styles) {
    styles = styles || {};
    if (!Array.isArray(styles.items)) styles.items = [];
    if (!styles.items.length) {
        styles.items = [
            {title:'Aba 1', content:'Conteúdo da primeira aba.'},
            {title:'Aba 2', content:'Conteúdo da segunda aba.'},
            {title:'Aba 3', content:'Conteúdo da terceira aba.'}
        ];
    }
    styles.items = styles.items.slice(0, 20).map((it, i) => ({
        title: String(it?.title ?? `Aba ${i+1}`),
        content: String(it?.content ?? '')
    }));
    return styles.items;
}
function addTabItem(id) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'tabs') return;
    el.styles = el.styles || {};
    const items = ensureTabsItems(el.styles);
    if (items.length >= 20) { showNotification('Limite de 20 abas atingido.','error'); return; }
    saveState();
    items.push({title:`Aba ${items.length+1}`, content:'Novo conteúdo da aba.'});
    el.styles.items = items;
    el.styles.activeIndex = items.length - 1;
    renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}
function updateTabItem(id, index, prop, value) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'tabs') return;
    el.styles = el.styles || {};
    const items = ensureTabsItems(el.styles);
    if (!items[index] || !['title','content'].includes(prop)) return;
    saveState();
    items[index][prop] = String(value ?? '');
    el.styles.items = items;
    renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}
function removeTabItem(id, index) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'tabs') return;
    el.styles = el.styles || {};
    const items = ensureTabsItems(el.styles);
    if (!items[index]) return;
    if (items.length <= 1) { showNotification('Mantenha pelo menos 1 aba.','error'); return; }
    saveState();
    items.splice(index,1);
    const ai = Math.min(Number(el.styles.activeIndex || 0), items.length-1);
    el.styles.items = items; el.styles.activeIndex = ai;
    renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}
let tabsDragState = null;
function startTabsDrag(event, id, index) {
    tabsDragState = {id,index};
    event.dataTransfer.effectAllowed='move';
    event.dataTransfer.setData('text/plain', String(index));
}
function allowTabsDrop(event) { event.preventDefault(); event.dataTransfer.dropEffect='move'; }
function dropTabs(event, id, targetIndex) {
    event.preventDefault();
    if (!tabsDragState || tabsDragState.id !== id) return;
    let from = tabsDragState.index, to = targetIndex;
    tabsDragState = null;
    if (from < to) to--;
    const el = findElementById(pageContent,id);
    if (!el || el.type !== 'tabs') return;
    el.styles = el.styles || {};
    const items = ensureTabsItems(el.styles);
    if (from===to || from<0 || to<0 || from>=items.length || to>=items.length) return;
    saveState();
    const [item]=items.splice(from,1); items.splice(to,0,item);
    el.styles.items=items;
    renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}
function setActiveTab(id,index) {
    const el = findElementById(pageContent,id);
    if (!el || el.type !== 'tabs') return;
    el.styles = el.styles || {};
    const items = ensureTabsItems(el.styles);
    if (!items[index]) return;
    el.styles.activeIndex=index;
    renderCanvas();
    saveData();
    selectedElementId=id;
    updateProperties(id);
}
function buildTabsHTML(el) {
    const s = el.styles || {};
    const items = ensureTabsItems(s);
    const active = Math.min(items.length-1, Math.max(0, parseInt(s.activeIndex ?? 0,10) || 0));
    const align = ['left','center','right'].includes(s.alignment) ? s.alignment : 'left';
    const wn = parseFloat(s.width); const unit = s.widthUnit === 'px' ? 'px' : '%';
    const width = Number.isFinite(wn) && wn > 0 ? `${unit==='px'?Math.min(2000,wn):Math.min(100,Math.max(1,wn))}${unit}` : '100%';
    const radius = Math.min(60, Math.max(0, parseFloat(s.radius ?? 10) || 0));
    const borderWidth = Math.min(10, Math.max(0, parseFloat(s.borderWidth ?? 1) || 0));
    const pad = Math.min(80, Math.max(0, parseFloat(s.padding ?? 18) || 0));
    const gap = Math.min(30, Math.max(0, parseFloat(s.gap ?? 4) || 0));
    const tabStyle = ['underline','pill','box'].includes(s.tabStyle) ? s.tabStyle : 'underline';
    const tabHeader = items.map((it,i)=>{
        const activeNow=i===active;
        const activeColor = s.tabActiveColor || '#3b82f6';
        const normalColor = s.tabColor || '#475569';
        const activeBg = s.tabActiveBg || (tabStyle==='pill' ? '#eff6ff' : '#ffffff');
        const normalBg = s.tabBg || '#f8fafc';
        let style = `display:inline-flex;align-items:center;justify-content:center;padding:11px 16px;cursor:pointer;font:600 14px/1.2 Inter,Arial,sans-serif;color:${escapeAttr(activeNow?activeColor:normalColor)};background:${escapeAttr(activeNow?activeBg:normalBg)};box-sizing:border-box;transition:all .2s ease;`;
        if(tabStyle==='underline') {
            style += `border:0;border-bottom:2px solid ${activeNow?escapeAttr(activeColor):'transparent'};border-radius:0;`;
        } else if(tabStyle==='pill') {
            const pillBg = activeNow ? activeBg : (s.tabBg || '#f8fafc');
            style += `border:${Math.max(1,borderWidth)}px solid ${escapeAttr(activeNow?activeColor:(s.borderColor||'#e5e7eb'))};border-radius:999px;background:${escapeAttr(pillBg)};padding:10px 18px;`;
        } else {
            const boxBg = activeNow ? activeBg : (s.tabBg || '#ffffff');
            style += `border:${Math.max(1,borderWidth)}px solid ${escapeAttr(activeNow?activeColor:(s.borderColor||'#dbe1e8'))};border-radius:${Math.max(4,radius)}px;background:${escapeAttr(boxBg)};box-shadow:${activeNow?'0 2px 8px rgba(0,0,0,.08)':'0 1px 3px rgba(0,0,0,.04)'};`;
        }
        return `<button type="button" onclick="event.stopPropagation();setActiveTab('${el.id}',${i})" style="${style}">${escapeHtml(it.title||`Aba ${i+1}`)}</button>`;
    }).join('');
    const activeContent = items[active]?.content || '';
    const box = `width:${width};max-width:100%;margin-left:${align==='center'||align==='right'?'auto':'0'};margin-right:${align==='center'||align==='left'?'auto':'0'};box-sizing:border-box;`;
    return `<section class="sbc-tabs-shell" style="${box}"><div style="display:flex;flex-wrap:wrap;gap:${gap}px;align-items:flex-end;margin-bottom:${gap}px;">${tabHeader}</div><div style="background:${escapeAttr(s.contentBg||'#ffffff')};color:${escapeAttr(s.contentColor||'#374151')};border:${borderWidth}px solid ${escapeAttr(s.borderColor||'#e5e7eb')};border-radius:${radius}px;padding:${pad}px;box-sizing:border-box;min-height:90px;line-height:1.6;">${escapeHtml(activeContent).replace(/\n/g,'<br>')}</div></section>`;
}

function buildPricingHTML(el) {
    const s = el.styles || {};
    const plans = ensurePricingPlans(s);
    const align = ['left','center','right'].includes(s.alignment) ? s.alignment : 'center';
    const wn = parseFloat(s.width);
    const width = Number.isFinite(wn) && wn > 0 ? `${s.widthUnit === 'px' ? Math.min(2000,wn) : Math.min(100,Math.max(1,wn))}${s.widthUnit === 'px' ? 'px' : '%'}` : '100%';
    const cols = Math.min(4, Math.max(1, parseInt(s.columns || 3,10) || 3));
    const gap = Math.min(80, Math.max(0, parseFloat(s.gap || 20) || 20));
    const shadow = s.shadow ? `${parseFloat(s.shadowX||0)}px ${parseFloat(s.shadowY||5)}px ${parseFloat(s.shadowBlur||18)}px ${parseFloat(s.shadowSpread||0)}px ${s.shadowColor||'rgba(0,0,0,.06)'}` : 'none';
    const wrapper = `display:block!important;position:relative!important;width:${width};max-width:100%;min-width:0;margin-left:${align==='center'||align==='right'?'auto':'0'};margin-right:${align==='center'||align==='left'?'auto':'0'};box-sizing:border-box;clear:both!important;float:none!important;`;
    const borderStyle = ['solid','dashed','dotted','double','none'].includes(s.cardBorderStyle) ? s.cardBorderStyle : 'solid';
    const borderWidth = Math.min(20, Math.max(0, parseFloat(s.cardBorderWidth||1) || 0));
    const radius = Math.min(80, Math.max(0, parseFloat(s.cardRadius||16) || 0));
    const cardPadding = Math.min(100, Math.max(0, parseFloat(s.cardPadding||24) || 0));
    const featureFont = Math.min(30, Math.max(11, parseFloat(s.featureFontSize||14) || 14));
    const buttonRadius = Math.min(50, Math.max(0, parseFloat(s.buttonRadius||9) || 0));
    const buttonPy = Math.min(50, Math.max(0, parseFloat(s.buttonPaddingY||11) || 0));
    const buttonPx = Math.min(80, Math.max(0, parseFloat(s.buttonPaddingX||16) || 0));
    const cards = plans.map((plan,index) => {
        const features = Array.isArray(plan.features) ? plan.features : [];
        const featureHTML = features.map(f => `<li style="display:flex;gap:8px;align-items:flex-start;line-height:1.45;margin:0 0 8px;color:${escapeAttr(s.featureColor||'#374151')};font-size:${featureFont}px;"><span style="color:${escapeAttr(s.checkColor||'#22c55e')};font-weight:800;flex:0 0 auto;">✓</span><span>${escapeHtml(f)}</span></li>`).join('');
        const highlighted = !!plan.highlighted;
        const cardBorder = highlighted ? `${Math.max(1,parseFloat(s.highlightedBorderWidth||2)||2)}px solid ${escapeAttr(s.highlightedBorderColor||'#3b82f6')}` : `${borderWidth}px ${borderStyle} ${escapeAttr(s.cardBorderColor||'#e5e7eb')}`;
        const cardShadow = highlighted ? `0 10px 30px ${escapeAttr(s.highlightedShadow||'rgba(59,130,246,.12)')}` : shadow;
        const badge = highlighted && plan.badge ? `<div style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);padding:5px 10px;border-radius:999px;background:${escapeAttr(s.badgeBg||'#3b82f6')};color:${escapeAttr(s.badgeColor||'#ffffff')};font-size:11px;font-weight:800;white-space:nowrap;z-index:2;">${escapeHtml(plan.badge)}</div>` : '';
        const buttonBg = highlighted ? (s.buttonBgHighlight || s.highlightedBorderColor || '#3b82f6') : (s.buttonBg||'#111827');
        return `<article class="sbc-pricing-card" style="position:relative;min-width:0;box-sizing:border-box;background:${escapeAttr(highlighted ? (s.highlightedBg||s.cardBg||'#fff') : (s.cardBg||'#fff'))};border:${cardBorder};border-radius:${radius}px;padding:${cardPadding}px;box-shadow:${cardShadow};height:100%;display:flex;flex-direction:column;transition:transform .25s ease,background .25s ease,box-shadow .25s ease;--price-hover-bg:${escapeAttr(s.hoverBg||'#f8fafc')};--price-hover-lift:${Math.min(20,Math.max(0,parseFloat(s.hoverLift||3)||3))}px;">
            ${badge}
            <div style="font-size:${Math.max(12,parseFloat(s.planNameFontSize||18)||18)}px;font-weight:800;color:${escapeAttr(s.planNameColor||'#111827')};margin-bottom:7px;">${escapeHtml(plan.name || 'Plano')}</div>
            <div style="font-size:${Math.max(11,parseFloat(s.descriptionPlanFontSize||13)||13)}px;color:${escapeAttr(s.descriptionPlanColor||'#6b7280')};min-height:20px;margin-bottom:14px;line-height:1.45;">${escapeHtml(plan.description || '')}</div>
            <div style="display:flex;align-items:baseline;gap:4px;margin-bottom:18px;"><span style="font-size:${Math.max(22,parseFloat(s.priceFontSize||36)||36)}px;font-weight:800;color:${escapeAttr(s.priceColor||'#111827')};line-height:1.1;">R$ ${escapeHtml(plan.price || '0')}</span><span style="font-size:13px;color:${escapeAttr(s.periodColor||'#6b7280')};">${escapeHtml(plan.period || '')}</span></div>
            <ul style="list-style:none;margin:0 0 20px;padding:0;flex:1;">${featureHTML}</ul>
            <a href="${escapeAttr(plan.buttonLink || '#')}" style="display:block;text-align:center;text-decoration:none;padding:${buttonPy}px ${buttonPx}px;border-radius:${buttonRadius}px;background:${escapeAttr(buttonBg)};color:${escapeAttr(s.buttonTextColor||'#fff')};font-weight:700;box-sizing:border-box;transition:background .2s ease,transform .2s ease;">${escapeHtml(plan.buttonText || 'Escolher plano')}</a>
        </article>`;
    }).join('');
    const grid = `display:grid!important;grid-template-columns:repeat(${cols},minmax(0,1fr));gap:${gap}px;width:100%;max-width:100%;min-width:0;align-items:stretch;box-sizing:border-box;`;
    const titleSize = Math.max(16,parseFloat(s.titleFontSize||30)||30);
    const descSize = Math.max(11,parseFloat(s.descriptionFontSize||14)||14);
    return `<section class="sbc-pricing-shell" style="${wrapper}">
        <header style="display:block!important;position:relative!important;width:100%!important;max-width:100%!important;box-sizing:border-box;margin:0 0 24px!important;padding:0!important;clear:both!important;float:none!important;text-align:${align};"><div style="font-size:${titleSize}px;font-weight:800;line-height:1.2;color:${escapeAttr(s.titleColor||'#111827')};margin-bottom:8px;">${escapeHtml(s.title || 'Planos e preços')}</div><div style="font-size:${descSize}px;line-height:1.5;color:${escapeAttr(s.descriptionColor||'#6b7280')};">${escapeHtml(s.description || '')}</div></header>
        <div class="sbc-pricing-grid" style="${grid}">${cards}</div>
    </section>`;
}

let pricingDragState = null;
function startPricingDrag(event, id, index) {
    pricingDragState = { id, index };
    event.dataTransfer.effectAllowed = 'move';
    event.dataTransfer.setData('text/plain', String(index));
}
function allowPricingDrop(event) { event.preventDefault(); event.dataTransfer.dropEffect='move'; }
function dropPricing(event, id, targetIndex) {
    event.preventDefault();
    if (!pricingDragState || pricingDragState.id !== id) return;
    let from = pricingDragState.index;
    let to = targetIndex;
    pricingDragState = null;
    if (from < to) to--;
    reorderPricingPlan(id, from, to);
}

function bindTestimonialsHover(root) {
    if (!root) return;
    root.querySelectorAll('.sbc-testimonial-card').forEach(card=>{
        card.addEventListener('mouseenter',()=>{ card.style.transform='translateY(-' + (parseFloat(getComputedStyle(card).getPropertyValue('--testimonial-lift'))||3) + 'px)'; card.style.background=getComputedStyle(card).getPropertyValue('--testimonial-hover') || '#f8fafc'; });
        card.addEventListener('mouseleave',()=>{ card.style.transform=''; card.style.background=''; });
    });
}

function bindPricingHover(root) {
    if (!root) return;
    root.querySelectorAll('.sbc-pricing-card').forEach(card=>{
        card.addEventListener('mouseenter',()=>{ card.style.transform='translateY(calc(-1 * var(--price-hover-lift)))'; card.style.background=getComputedStyle(card).getPropertyValue('--price-hover-bg') || ''; });
        card.addEventListener('mouseleave',()=>{ card.style.transform=''; card.style.background=''; });
    });
}

function buildFooterHTML(el) {
    const s = el.styles || {};
    const escH = escapeHtml;
    const escA = escapeAttr;
    const width = Math.max(1, Math.min(2000, parseFloat(s.width) || 100));
    const widthUnit = s.widthUnit === 'px' ? 'px' : '%';
    const py = Math.max(12, Math.min(160, parseFloat(s.paddingY) || 48));
    const px = Math.max(10, Math.min(100, parseFloat(s.paddingX) || 24));
    const contentWidth = Math.max(320, Math.min(2000, parseFloat(s.contentWidth) || 1200));
    const logoHeight = Math.max(18, Math.min(80, parseFloat(s.logoHeight) || 34));
    const titleSize = Math.max(11, Math.min(28, parseFloat(s.titleFontSize) || 15));
    const textSize = Math.max(11, Math.min(22, parseFloat(s.textFontSize) || 14));
    const copySize = Math.max(10, Math.min(18, parseFloat(s.copyrightFontSize) || 12));
    const gap = Math.max(8, Math.min(100, parseFloat(s.columnGap) || 36));
    const radius = Math.max(0, Math.min(60, parseFloat(s.radius) || 0));
    const borderWidth = Math.max(0, Math.min(8, parseFloat(s.borderWidth) || 0));
    const shadow = s.shadow ? `${parseFloat(s.shadowX)||0}px ${parseFloat(s.shadowY)||-4}px ${Math.max(0,parseFloat(s.shadowBlur)||18)}px ${parseFloat(s.shadowSpread)||0}px ${escA(s.shadowColor||'rgba(0,0,0,.18)')}` : 'none';
    const align = ['left','center','right'].includes(s.alignment) ? s.alignment : 'left';
    const columns = Array.isArray(s.columns) ? s.columns : [];
    const socialItems = [
        ['instagram', s.instagram, 'fa-instagram'],
        ['facebook', s.facebook, 'fa-facebook-f'],
        ['linkedin', s.linkedin, 'fa-linkedin-in'],
        ['youtube', s.youtube, 'fa-youtube']
    ];
    const logoHtml = s.logoUrl
        ? `<img src="${escA(s.logoUrl)}" alt="${escA(s.logoAlt || 'Logo')}" style="display:block;height:${logoHeight}px;width:auto;max-width:240px;object-fit:contain;">`
        : `<div style="font:800 ${Math.max(18,logoHeight-4)}px/1.1 Poppins,Inter,Arial,sans-serif;color:${escA(s.footerColor||'#f9fafb')};">${escH(s.brand ?? '')}</div>`;
    const linksColumns = columns.map(col => {
        const items = Array.isArray(col?.items) ? col.items : [];
        return `<div style="min-width:150px;flex:1 1 0;box-sizing:border-box;">
            <div style="font-size:${titleSize}px;font-weight:800;color:${escA(s.footerColor||'#f9fafb')};margin:0 0 14px;">${escH(col?.title || 'Links')}</div>
            <div style="display:flex;flex-direction:column;gap:9px;">
                ${items.map(item => `<a href="${escA(item?.link || '#')}" style="font-size:${textSize}px;color:${escA(s.mutedColor||'#9ca3af')};text-decoration:none;line-height:1.45;transition:color .2s ease;">${escH(item?.label || 'Link')}</a>`).join('')}
            </div>
        </div>`;
    }).join('');
    const socialHtml = s.showSocial ? socialItems.map(([name,url,icon]) => url ? `<a href="${escA(url)}" target="_blank" rel="noopener noreferrer" title="${name}" style="width:34px;height:34px;border:1px solid ${escA(s.borderColor||'#243244')};border-radius:50%;display:inline-flex;align-items:center;justify-content:center;color:${escA(s.mutedColor||'#9ca3af')};text-decoration:none;"><i class="fab ${icon}"></i></a>` : '').join('') : '';
    const contactHtml = `<div style="min-width:190px;flex:1 1 0;box-sizing:border-box;">
        <div style="font-size:${titleSize}px;font-weight:800;color:${escA(s.footerColor||'#f9fafb')};margin:0 0 14px;">${escH(s.contactTitle || 'Contato')}</div>
        <div style="display:flex;flex-direction:column;gap:8px;color:${escA(s.mutedColor||'#9ca3af')};font-size:${textSize}px;line-height:1.5;">
            ${s.email ? `<div><i class="fas fa-envelope" style="width:18px;color:${escA(s.accentColor||'#3b82f6')};"></i>${escH(s.email)}</div>` : ''}
            ${s.phone ? `<div><i class="fas fa-phone" style="width:18px;color:${escA(s.accentColor||'#3b82f6')};"></i>${escH(s.phone)}</div>` : ''}
            ${s.address ? `<div><i class="fas fa-location-dot" style="width:18px;color:${escA(s.accentColor||'#3b82f6')};"></i>${escH(s.address)}</div>` : ''}
        </div>
        ${socialHtml ? `<div style="display:flex;gap:8px;margin-top:16px;flex-wrap:wrap;">${socialHtml}</div>` : ''}
    </div>`;
    const top = `<div class="sbc-footer-grid" style="display:grid;grid-template-columns:minmax(220px,1.35fr) repeat(${Math.max(0,columns.length)},minmax(150px,1fr)) minmax(190px,1fr);gap:${gap}px;align-items:start;">
        <div style="min-width:0;">
            <div style="margin-bottom:12px;">${logoHtml}</div>
            ${s.description ? `<div style="font-size:${textSize}px;color:${escA(s.mutedColor||'#9ca3af')};line-height:1.65;max-width:360px;">${escH(s.description)}</div>` : ''}
        </div>
        ${linksColumns}${contactHtml}
    </div>`;
    const copyright = `<div style="margin-top:30px;padding-top:18px;border-top:${borderWidth}px solid ${escA(s.borderColor||'#243244')};text-align:${align};font-size:${copySize}px;color:${escA(s.mutedColor||'#9ca3af')};line-height:1.5;">${escH(s.copyright || '')}</div>`;
    return `<footer class="sbc-footer-shell" style="width:${width}${widthUnit};max-width:100%;margin:0 auto;background:${escA(s.footerBg||'#111827')};color:${escA(s.footerColor||'#f9fafb')};border:${borderWidth}px solid ${escA(s.borderColor||'#243244')};border-radius:${radius}px;box-shadow:${shadow};box-sizing:border-box;overflow:hidden;">
        <div style="max-width:${contentWidth}px;margin:0 auto;padding:${py}px ${px}px;box-sizing:border-box;">${top}${copyright}</div>
    </footer>`;
}

function buildNavbarHTML(el) {
    const s = el.styles || {};
    const escH = escapeHtml;
    const escA = escapeAttr;
    const items = Array.isArray(s.items) && s.items.length ? s.items : [
        {label:'Início', link:'#'},
        {label:'Sobre', link:'#sobre'},
        {label:'Contato', link:'#contato'}
    ];
    const navBg = s.navBg || '#ffffff';
    const navColor = s.navColor || '#1f2937';
    const hoverColor = s.navHoverColor || '#3b82f6';
    const activeColor = s.navActiveColor || hoverColor;
    const borderColor = s.navBorderColor || '#e5e7eb';
    const borderWidth = Math.max(0, Math.min(8, parseFloat(s.navBorderWidth) || 0));
    const radius = Math.max(0, Math.min(60, parseFloat(s.navRadius) || 0));
    const py = Math.max(4, Math.min(50, parseFloat(s.navPaddingY) || 14));
    const px = Math.max(4, Math.min(60, parseFloat(s.navPaddingX) || 20));
    const gap = Math.max(4, Math.min(80, parseFloat(s.itemGap) || 24));
    const fontSize = Math.max(9, Math.min(30, parseFloat(s.fontSize) || 14));
    const weight = ['400','500','600','700','800'].includes(String(s.fontWeight)) ? String(s.fontWeight) : '600';
    const logoHeight = Math.max(18, Math.min(80, parseFloat(s.logoHeight) || 34));
    const mobileBreakpoint = Math.max(480, Math.min(1400, parseFloat(s.mobileBreakpoint) || 1024));
    const mobileButtonType = ['icon','icon-text','text'].includes(String(s.mobileButtonType)) ? String(s.mobileButtonType) : 'icon';
    const mobileIcon = ({bars:'bars',list:'list',grid:'grip',ellipsis:'ellipsis',menu:'bars'}[String(s.mobileIcon)] || 'bars');
    const mobilePosition = String(s.mobilePosition) === 'left' ? 'left' : 'right';
    const mobileButtonSize = Math.max(12, Math.min(32, parseFloat(s.mobileButtonSize) || 18));
    const mobileButtonWidth = Math.max(30, Math.min(100, parseFloat(s.mobileButtonWidth) || 42));
    const mobileButtonHeight = Math.max(28, Math.min(70, parseFloat(s.mobileButtonHeight) || 38));
    const mobileButtonRadius = Math.max(0, Math.min(30, parseFloat(s.mobileButtonRadius) || 8));
    const mobileItemAlign = ['left','center','right'].includes(String(s.mobileItemAlign)) ? String(s.mobileItemAlign) : 'left';
    const mobileItemGap = Math.max(0, Math.min(30, parseFloat(s.mobileItemGap) || 4));
    const mobileItemPaddingY = Math.max(4, Math.min(30, parseFloat(s.mobileItemPaddingY) || 10));
    const navId = `navbar-${String(el.id).replace(/[^a-zA-Z0-9_-]/g,'')}`;
    const sticky = s.sticky ? 'position:sticky;top:0;z-index:50;' : '';
    const shadow = s.shadow ? `${parseFloat(s.shadowX)||0}px ${parseFloat(s.shadowY)||4}px ${Math.max(0,parseFloat(s.shadowBlur)||14)}px ${parseFloat(s.shadowSpread)||0}px ${escA(s.shadowColor||'rgba(0,0,0,0.08)')}` : 'none';
    const alignMode = ['space-between','left','center','right'].includes(s.alignment) ? s.alignment : 'space-between';
    const justify = alignMode === 'left' ? 'flex-start' : (alignMode === 'right' ? 'flex-end' : (alignMode === 'center' ? 'center' : 'space-between'));
    const underline = s.linkUnderline || 'none';
    const logoHtml = s.logoUrl
        ? `<img src="${escA(s.logoUrl)}" alt="${escA(s.logoAlt || 'Logo')}" style="display:block;height:${logoHeight}px;width:auto;max-width:240px;object-fit:contain;">`
        : `<span style="font:800 ${Math.min(34,fontSize+10)}px/1.1 Poppins,Inter,Arial,sans-serif;color:${escA(navColor)};white-space:nowrap;">${escH(s.brand ?? '')}</span>`;
    const links = items.map((item,i)=>{
        const label = escH(item?.label || `Item ${i+1}`);
        const href = escA(item?.link || '#');
        const target = item?.targetBlank ? '_blank' : '_self';
        return `<a href="${href}" target="${target}" ${target==='_blank'?'rel="noopener noreferrer"':''} class="sbc-navbar-link" style="color:${escA(navColor)};font:${weight} ${fontSize}px/1.2 Inter,Arial,sans-serif;text-decoration:${underline};padding:8px 4px;transition:color .2s ease,background .2s ease;white-space:nowrap;">${label}</a>`;
    }).join('');

    const mobileToggleLabel = mobileButtonType === 'icon-text' ? 'Menu' : (mobileButtonType === 'text' ? 'Menu' : '');
    const mobileToggleInner = mobileButtonType === 'text'
        ? `<span class="sbc-navbar-toggle-label" style="font:700 ${mobileButtonSize}px/1 Inter,Arial,sans-serif;">Menu</span>`
        : `<i class="fas fa-${mobileIcon}" data-navbar-icon="${mobileIcon}" style="font-size:${mobileButtonSize}px;"></i>${mobileToggleLabel ? `<span class="sbc-navbar-toggle-label" style="font:700 ${mobileButtonSize}px/1 Inter,Arial,sans-serif;margin-left:7px;">${mobileToggleLabel}</span>` : ''}`;
    const mobileToggle = `<button type="button" class="sbc-navbar-toggle" aria-label="Abrir menu" aria-expanded="false" data-navbar-icon="${mobileIcon}" data-navbar-button-type="${mobileButtonType}" style="display:none;width:${mobileButtonWidth}px;height:${mobileButtonHeight}px;border:1px solid ${escA(borderColor)};border-radius:${mobileButtonRadius}px;background:${escA(s.mobileButtonBg||s.mobileBg||navBg)};color:${escA(s.mobileButtonColor||navColor)};cursor:pointer;font-size:${mobileButtonSize}px;align-items:center;justify-content:center;box-sizing:border-box;white-space:nowrap;">${mobileToggleInner}</button>`;
    const logoAnchor = `<a href="#" style="display:inline-flex;align-items:center;text-decoration:none;flex:0 0 auto;">${logoHtml}</a>`;
    const desktopLinks = `<div class="sbc-navbar-desktop-links" style="display:flex;align-items:center;justify-content:${justify==='space-between'?'flex-end':justify};gap:${gap}px;flex-wrap:wrap;">${links}</div>`;
    const mobileRow = mobilePosition === 'left' ? `${mobileToggle}${logoAnchor}${desktopLinks}` : `${logoAnchor}${desktopLinks}${mobileToggle}`;
    return `<nav id="${escA(navId)}" class="sbc-navbar-shell" style="${sticky}width:100%;background:${escA(navBg)};border:${borderWidth}px solid ${escA(borderColor)};border-radius:${radius}px;box-shadow:${shadow};box-sizing:border-box;">
        <div style="display:flex;align-items:center;justify-content:${justify};gap:${gap}px;min-height:52px;padding:${py}px ${px}px;box-sizing:border-box;">
            ${mobileRow}
        </div>
        <div class="sbc-navbar-mobile-panel" style="display:none;border-top:1px solid ${escA(borderColor)};padding:10px ${px}px;background:${escA(s.mobileBg||navBg)};">
            <div style="display:flex;flex-direction:column;align-items:stretch;gap:${mobileItemGap}px;text-align:${mobileItemAlign};">${links.replace(/padding:8px 4px/g,`padding:${mobileItemPaddingY}px 6px;text-align:${mobileItemAlign};width:100%;box-sizing:border-box;`)}</div>
        </div>
        <style>
            #${navId} .sbc-navbar-link:hover{color:${escA(hoverColor)} !important;}
            #${navId} .sbc-navbar-link:focus{color:${escA(activeColor)} !important;outline:2px solid ${escA(hoverColor)};outline-offset:2px;border-radius:5px;}
            @media (max-width:${mobileBreakpoint}px){
                #${navId} > div:first-child{justify-content:space-between !important;}
                #${navId} .sbc-navbar-desktop-links{display:none !important;}
                #${navId} .sbc-navbar-toggle{display:flex !important;}
                #${navId}.sbc-navbar-mobile-open .sbc-navbar-mobile-panel{display:block !important;}
                #${navId}.sbc-navbar-mobile-open .sbc-navbar-toggle .fa-bars:before{content:"\\f00d";}
                #${navId} .sbc-navbar-mobile-panel .sbc-navbar-link{display:block;width:100%;padding:10px 6px;border-radius:6px;box-sizing:border-box;text-align:${mobileItemAlign};}
                #${navId} .sbc-navbar-mobile-panel .sbc-navbar-link:hover{background:rgba(59,130,246,.08);}
            }

            /* No editor, o modo Tablet/Mobile deve sempre usar o hambúrguer,
               independentemente da largura real da janela do navegador. */
            .canvas.tablet #${navId} > div:first-child,
            .canvas.mobile #${navId} > div:first-child{justify-content:space-between !important;}
            .canvas.tablet #${navId} .sbc-navbar-desktop-links,
            .canvas.mobile #${navId} .sbc-navbar-desktop-links{display:none !important;}
            .canvas.tablet #${navId} .sbc-navbar-toggle,
            .canvas.mobile #${navId} .sbc-navbar-toggle{display:flex !important;}
            .canvas.tablet #${navId}.sbc-navbar-mobile-open .sbc-navbar-mobile-panel,
            .canvas.mobile #${navId}.sbc-navbar-mobile-open .sbc-navbar-mobile-panel{display:block !important;}
            .canvas.tablet #${navId} .sbc-navbar-mobile-panel .sbc-navbar-link,
            .canvas.mobile #${navId} .sbc-navbar-mobile-panel .sbc-navbar-link{display:block;width:100%;padding:10px 6px;border-radius:6px;box-sizing:border-box;text-align:${mobileItemAlign};}
        
/* =========================================================
   ORGANIZAÇÃO DOS WIDGETS POR PARTES DA PÁGINA
   ========================================================= */
.widget-zone {
    margin-bottom: 10px;
    border: 1px solid var(--border);
    border-radius: 9px;
    background: rgba(255,255,255,.015);
    overflow: hidden;
}

.widget-zone summary {
    list-style: none;
    cursor: pointer;
    padding: 10px 11px;
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text);
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .65px;
    user-select: none;
}

.widget-zone summary::-webkit-details-marker {
    display: none;
}

.widget-zone summary::before {
    content: "▸";
    color: var(--accent);
    font-size: 11px;
    transition: transform .18s ease;
}

.widget-zone[open] > summary::before {
    transform: rotate(90deg);
}

.widget-zone summary .zone-subtitle {
    display: block;
    margin-left: auto;
    color: var(--text-tertiary);
    font-size: 8px;
    font-weight: 600;
    text-transform: none;
    letter-spacing: 0;
}

.widget-zone .widget-grid {
    padding: 0 8px 8px;
}

.widget-zone.zone-header summary { color: #c4b5fd; }
.widget-zone.zone-highlight summary { color: #93c5fd; }
.widget-zone.zone-content summary { color: #60a5fa; }
.widget-zone.zone-resources summary { color: #34d399; }
.widget-zone.zone-conversion summary { color: #fbbf24; }
.widget-zone.zone-footer summary { color: #f9a8d4; }

.widget-zone .widget-item {
    min-height: 68px;
}


/* =========================================================
   PREVIEW
   ========================================================= */
.nav-btn.preview-btn {
    background: linear-gradient(135deg, #0ea5e9, #2563eb);
    color: white;
}
.nav-btn.preview-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(37,99,235,.35);
}

.preview-overlay {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 100000;
    background: #f5f6f8;
}
.preview-overlay.active {
    display: flex;
    flex-direction: column;
}
.preview-toolbar {
    height: 54px;
    flex: 0 0 54px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding: 8px 14px;
    background: #111827;
    color: #fff;
    border-bottom: 1px solid #273244;
}
.preview-toolbar-left,
.preview-toolbar-right {
    display: flex;
    align-items: center;
    gap: 8px;
}
.preview-title {
    font-size: 13px;
    font-weight: 700;
}
.preview-device-btn {
    border: 1px solid #374151;
    background: #1f2937;
    color: #d1d5db;
    border-radius: 7px;
    padding: 7px 10px;
    cursor: pointer;
    font-size: 11px;
}
.preview-device-btn.active,
.preview-device-btn:hover {
    background: #2563eb;
    border-color: #2563eb;
    color: #fff;
}
.preview-close {
    border: 0;
    background: #ef4444;
    color: #fff;
    border-radius: 7px;
    padding: 8px 12px;
    cursor: pointer;
    font-size: 11px;
    font-weight: 700;
}
.preview-stage {
    flex: 1;
    overflow: auto;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 24px;
    background:
        radial-gradient(circle at 1px 1px, #d7dce3 1px, transparent 0) 0 0 / 20px 20px,
        #eef1f5;
}
.preview-frame {
    position: relative;
    width: 100%;
    min-height: calc(100vh - 102px);
    background: #fff;
    box-shadow: 0 15px 45px rgba(0,0,0,.18);
    transition: width .2s ease;
    overflow: hidden;
}
.preview-frame.preview-tablet { width: 768px; }
.preview-frame.preview-mobile { width: 375px; }
.preview-content {
    width: 100%;
    min-height: 100%;
    background: #fff;
}
.preview-overlay .el-delete,
.preview-overlay .el-duplicate,
.preview-overlay .column-drop-placeholder,
.preview-overlay [data-id].selected,
.preview-overlay [data-id].drag-over,
.preview-overlay .drag-over {
    outline: none !important;
    box-shadow: none !important;
}
.preview-overlay [data-id] {
    border: 0 !important;
    margin-bottom: 0 !important;
}
.preview-overlay .el-section,
.preview-overlay .el-column,
.preview-overlay .el-container {
    position: relative !important;
}



/* =========================================================
   RECURSOS — RESPONSIVIDADE DEFINITIVA
   ========================================================= */
.sbc-features-shell {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    box-sizing: border-box !important;
    container-type: inline-size !important;
    overflow: visible !important;
}

.sbc-features-grid {
    display: grid !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
    gap: 20px !important;
    box-sizing: border-box !important;
    align-items: stretch !important;
}

.sbc-features-grid .sbc-feature-card {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    box-sizing: border-box !important;
    overflow: hidden !important;
    overflow-wrap: anywhere !important;
    word-break: normal !important;
}

.sbc-features-grid .sbc-feature-card > * {
    max-width: 100% !important;
    box-sizing: border-box !important;
}

/* Tablet simulado pelo editor */
.canvas.tablet .sbc-features-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    gap: 16px !important;
}

.canvas.tablet .sbc-features-grid .sbc-feature-card {
    padding: 18px !important;
}

.canvas.tablet .sbc-features-grid .sbc-feature-icon-box {
    width: 64px !important;
    height: 64px !important;
    margin-bottom: 12px !important;
}

.canvas.tablet .sbc-features-grid .sbc-feature-card > div:nth-child(2) {
    font-size: 17px !important;
    line-height: 1.3 !important;
}

.canvas.tablet .sbc-features-grid .sbc-feature-card > div:nth-child(3) {
    font-size: 13px !important;
    line-height: 1.55 !important;
}

/* Mobile simulado pelo editor */
.canvas.mobile .sbc-features-shell {
    padding-left: 0 !important;
    padding-right: 0 !important;
}

.canvas.mobile .sbc-features-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    gap: 10px !important;
}

.canvas.mobile .sbc-features-grid .sbc-feature-card {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    padding: 12px 10px !important;
    text-align: center !important;
}

.canvas.mobile .sbc-features-grid .sbc-feature-icon-box {
    width: 58px !important;
    height: 58px !important;
    margin: 0 auto 12px !important;
}

.canvas.mobile .sbc-features-grid .sbc-feature-icon-box i {
    font-size: 23px !important;
}

.canvas.mobile .sbc-features-grid .sbc-feature-card > div:nth-child(2) {
    font-size: 15px !important;
    line-height: 1.25 !important;
    white-space: normal !important;
    overflow-wrap: normal !important;
    word-break: normal !important;
}

.canvas.mobile .sbc-features-grid .sbc-feature-card > div:nth-child(3) {
    font-size: 12px !important;
    line-height: 1.45 !important;
    white-space: normal !important;
    overflow-wrap: normal !important;
    word-break: normal !important;
}

.canvas.mobile .sbc-features-shell > header {
    margin-bottom: 18px !important;
}

.canvas.mobile .sbc-features-shell > header > div:first-child {
    font-size: 24px !important;
    line-height: 1.18 !important;
}

.canvas.mobile .sbc-features-shell > header > div:last-child {
    font-size: 13px !important;
    line-height: 1.5 !important;
}

/* Responsividade também quando a página for exibida fora do modo simulado */
@container (max-width: 760px) {
    .sbc-features-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 16px !important;
    }
}

@container (max-width: 500px) {
    .sbc-features-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 10px !important;
    }

    .sbc-features-grid .sbc-feature-card {
        padding: 12px 10px !important;
        text-align: center !important;
    }

    .sbc-features-grid .sbc-feature-icon-box {
        width: 58px !important;
        height: 58px !important;
        margin: 0 auto 12px !important;
    }
}

@media (max-width: 768px) {
    .sbc-features-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 16px !important;
    }
}

@media (max-width: 520px) {
    .sbc-features-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 10px !important;
    }

    .sbc-features-grid .sbc-feature-card {
        padding: 12px 10px !important;
        text-align: center !important;
    }
}

</style>
    </nav>`;
}

function buildHeroHTML(el) {
    const s = el.styles || {};
    const escH = escapeHtml;
    const escA = escapeAttr;
    const title = escH(s.title || 'Transforme suas ideias em realidade');
    const description = escH(s.description || 'Uma apresentação impactante para destacar seu negócio, produto ou projeto.');
    const buttonText = escH(s.buttonText || 'Começar agora');
    const buttonLink = escA(s.buttonLink || '#');
    const align = ['left','center','right'].includes(s.contentAlign) ? s.contentAlign : 'center';
    const bgType = ['color','gradient','image'].includes(s.bgType) ? s.bgType : 'gradient';
    const minHeight = Math.max(180, Math.min(1000, parseFloat(s.minHeight) || 480));
    const py = Math.max(0, Math.min(200, parseFloat(s.paddingY) || 70));
    const px = Math.max(0, Math.min(120, parseFloat(s.paddingX) || 24));
    const titleSize = Math.max(18, Math.min(96, parseFloat(s.titleFontSize) || 52));
    const descSize = Math.max(11, Math.min(36, parseFloat(s.descriptionFontSize) || 18));
    const titleWeight = ['400','500','600','700','800','900'].includes(String(s.titleWeight)) ? String(s.titleWeight) : '800';
    const buttonRadius = Math.max(0, Math.min(60, parseFloat(s.buttonRadius) || 10));
    const buttonPy = Math.max(6, Math.min(30, parseFloat(s.buttonPaddingY) || 13));
    const buttonPx = Math.max(10, Math.min(60, parseFloat(s.buttonPaddingX) || 24));
    const buttonSize = Math.max(11, Math.min(28, parseFloat(s.buttonFontSize) || 15));
    const br = Math.max(0, Math.min(80, parseFloat(s.borderRadius) || 0));
    const contentWidth = Math.min(100, Math.max(20, parseFloat(s.contentWidth) || 100));
    const shadow = s.shadow ? `${s.shadowX||0}px ${s.shadowY||8}px ${s.shadowBlur||24}px ${s.shadowSpread||0}px ${escA(s.shadowColor||'rgba(0,0,0,0.18)')}` : 'none';

    const bgImage = String(s.bgImage || '').trim();
    let background = '';
    if (bgType === 'color') {
        background = `background:${escA(s.bgColor || '#0f172a')};`;
    } else if (bgType === 'image') {
        // A imagem é renderizada em uma camada <img> para funcionar de forma
        // confiável com URLs do WordPress, caminhos relativos e data URLs.
        background = `background:${escA(s.bgColor || '#0f172a')};`;
    } else {
        background = `background:linear-gradient(${escA(s.gradientDirection || 'to right')},${escA(s.bgColor || '#0f172a')},${escA(s.bgColor2 || '#2563eb')});`;
    }

    const imageLayer = (bgType === 'image' && bgImage)
        ? `<img aria-hidden="true" src="${escA(bgImage)}" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center;display:block;z-index:0;pointer-events:none;" onerror="this.style.display='none';">`
        : '';

    const overlay = (s.overlay && (bgType === 'image' || bgType === 'gradient'))
        ? `<div aria-hidden="true" style="position:absolute;inset:0;background:${escA(s.overlayColor || 'rgba(0,0,0,0.28)')};pointer-events:none;z-index:1;"></div>`
        : '';
    const hoverId = `hero-${String(el.id).replace(/[^a-zA-Z0-9_-]/g,'')}`;
    const target = s.buttonTargetBlank ? '_blank' : '_self';

    return `<section class="sbc-hero-shell" id="${escA(hoverId)}" style="position:relative;overflow:hidden;${background}min-height:${minHeight}px;padding:${py}px ${px}px;box-sizing:border-box;border-radius:${br}px;box-shadow:${shadow};display:flex;align-items:center;justify-content:center;">
        ${imageLayer}
        ${overlay}
        <div style="position:relative;z-index:2;width:${contentWidth}%;max-width:1100px;margin:0 auto;text-align:${align};">
            <h1 style="margin:0 0 16px;color:${escA(s.titleColor||'#ffffff')};font:700 ${titleSize}px/${Math.max(1,parseFloat(s.titleLineHeight)||1.12)} Poppins,Inter,Arial,sans-serif;font-weight:${titleWeight};letter-spacing:-.02em;">${title}</h1>
            <p style="margin:0 auto 28px;max-width:820px;color:${escA(s.descriptionColor||'#e2e8f0')};font:400 ${descSize}px/${Math.max(1,parseFloat(s.descriptionLineHeight)||1.6)} Inter,Arial,sans-serif;">${description}</p>
            <a href="${buttonLink}" target="${target}" rel="${target==='_blank'?'noopener noreferrer':''}" style="display:inline-flex;align-items:center;justify-content:center;text-decoration:none;background:${escA(s.buttonBg||'#3b82f6')};color:${escA(s.buttonColor||'#ffffff')};border-radius:${buttonRadius}px;padding:${buttonPy}px ${buttonPx}px;font:700 ${buttonSize}px/1.2 Inter,Arial,sans-serif;box-sizing:border-box;transition:background .2s ease,transform .2s ease;" onmouseenter="this.style.background='${escA(s.buttonBgHover||'#2563eb')}';this.style.transform='translateY(-1px)'" onmouseleave="this.style.background='${escA(s.buttonBg||'#3b82f6')}';this.style.transform='translateY(0)'">${buttonText}</a>
        </div>
    </section>`;
}

/* =========================================================
   EDIÇÃO DE PARTES INTERNAS DO WIDGET
   ========================================================= */
function getResponsiveDeviceMode() {
    if (window.__sbcRenderModeOverride === 'mobile' || window.__sbcRenderModeOverride === 'tablet' || window.__sbcRenderModeOverride === 'desktop') {
        return window.__sbcRenderModeOverride;
    }
    const canvas = document.getElementById('canvas');
    if (!canvas) return 'desktop';
    if (canvas.classList.contains('mobile')) return 'mobile';
    if (canvas.classList.contains('tablet')) return 'tablet';
    return 'desktop';
}

function ensurePartStyles(el) {
    el.styles = el.styles || {};
    el.styles.parts = el.styles.parts || {};
    return el.styles.parts;
}

function partDefinition(el, node) {
    if (!node) return null;
    const type = el.type;
    if (node.dataset.sbcPart) return {key: node.dataset.sbcPart, label: node.dataset.sbcPartLabel || 'Parte'};
    if (['heading','text'].includes(type)) {
        if (node.matches('.el-content > h1,.el-content > h2,.el-content > h3,.el-content > h4,.el-content > h5,.el-content > h6')) return {key:'text',label:'Texto'};
        if (node.matches('.el-content > p')) return {key:'text',label:'Texto'};
    }
    if (type === 'button' && node.matches('a,button')) return {key:'button',label:'Botão'};
    if (type === 'logo' && node.matches('img')) return {key:'image',label:'Imagem'};
    if (type === 'logo' && node.matches('a,span,strong,div')) return {key:'text',label:'Texto da logo'};
    return null;
}

function markEditableParts(div, el) {
    // Fase 2: cada parte visual importante recebe uma identificação própria.
    // A chave é única mesmo quando existem vários cards/itens no mesmo widget.
    const add = (node, key, label) => {
        if (!node || node.closest('.el-delete,.el-duplicate')) return;
        node.dataset.sbcPart = key;
        node.dataset.sbcPartLabel = label;
        node.style.setProperty('cursor', 'pointer', 'important');
        node.addEventListener('click', e => selectInternalPart(e, el.id, key, label), true);
    };

    if (['heading','text'].includes(el.type)) {
        const node = div.querySelector('.el-content > h1,.el-content > h2,.el-content > h3,.el-content > h4,.el-content > h5,.el-content > h6,.el-content > p');
        add(node, 'text', el.type === 'heading' ? 'Título' : 'Texto');
        return;
    }

    if (el.type === 'button') {
        add(div.querySelector('.el-content a,.el-content button,a,button'), 'button', 'Botão');
        return;
    }

    if (el.type === 'logo') {
        add(div.querySelector('.sbc-logo-shell img'), 'image', 'Imagem da logo');
        add(div.querySelector('.sbc-logo-shell span'), 'text', 'Texto da logo');
        return;
    }

    if (el.type === 'hero') {
        add(div.querySelector('.sbc-hero-shell > div[style*="z-index:2"] h1'), 'hero.title', 'Hero — Título');
        add(div.querySelector('.sbc-hero-shell > div[style*="z-index:2"] p'), 'hero.description', 'Hero — Descrição');
        add(div.querySelector('.sbc-hero-shell > div[style*="z-index:2"] a'), 'hero.button', 'Hero — Botão');
        add(div.querySelector('.sbc-hero-shell > img:not([aria-hidden="true"])'), 'hero.image', 'Hero — Imagem');
        // A imagem de fundo do Hero usa aria-hidden, mas continua selecionável no editor.
        add(div.querySelector('.sbc-hero-shell > img[aria-hidden="true"]'), 'hero.background', 'Hero — Imagem de fundo');
        return;
    }

    if (el.type === 'features') {
        const shell = div.querySelector('.sbc-features-shell');
        add(shell?.querySelector('header > div:first-child'), 'features.title', 'Recursos — Título');
        add(shell?.querySelector('header > div:nth-child(2)'), 'features.description', 'Recursos — Descrição');
        shell?.querySelectorAll('.sbc-feature-card').forEach((card, index) => {
            add(card.querySelector('.sbc-feature-icon-box i'), `features.${index}.icon`, `Recurso ${index+1} — Ícone`);
            add(card.querySelector('.sbc-feature-icon-box'), `features.${index}.iconBox`, `Recurso ${index+1} — Caixa do ícone`);
            const textNodes = card.querySelectorAll(':scope > div:not(.sbc-feature-icon-box)');
            add(textNodes[0], `features.${index}.title`, `Recurso ${index+1} — Título`);
            add(textNodes[1], `features.${index}.description`, `Recurso ${index+1} — Descrição`);
        });
        return;
    }

    if (el.type === 'team') {
        const shell = div.querySelector('.sbc-team-shell');
        add(shell?.querySelector('header > div:first-child,h2,h3'), 'team.title', 'Equipe — Título');
        add(shell?.querySelector('header > div:nth-child(2)'), 'team.description', 'Equipe — Descrição');
        shell?.querySelectorAll('.sbc-team-card').forEach((card, index) => {
            add(card.querySelector('img'), `team.${index}.image`, `Equipe ${index+1} — Foto`);
            add(card.querySelector('h3,h4,strong'), `team.${index}.title`, `Equipe ${index+1} — Nome`);
            add(card.querySelector('p'), `team.${index}.description`, `Equipe ${index+1} — Texto`);
            add(card.querySelector('.sbc-team-role'), `team.${index}.role`, `Equipe ${index+1} — Cargo`);
        });
        return;
    }

    if (el.type === 'gallery') {
        const shell = div.querySelector('.sbc-gallery-shell');
        add(shell?.querySelector('header > div:first-child,h2,h3'), 'gallery.title', 'Galeria — Título');
        add(shell?.querySelector('header > div:nth-child(2)'), 'gallery.description', 'Galeria — Descrição');
        shell?.querySelectorAll('img').forEach((img, index) => add(img, `gallery.${index}.image`, `Galeria ${index+1} — Imagem`));
        return;
    }

    if (el.type === 'testimonials') {
        const shell = div.querySelector('.sbc-testimonials-shell');
        add(shell?.querySelector('header > div:first-child,h2,h3'), 'testimonials.title', 'Depoimentos — Título');
        add(shell?.querySelector('header > div:nth-child(2)'), 'testimonials.description', 'Depoimentos — Descrição');
        shell?.querySelectorAll('.sbc-testimonial-card').forEach((card, index) => {
            add(card.querySelector('img'), `testimonials.${index}.image`, `Depoimento ${index+1} — Foto`);
            add(card.querySelector('p'), `testimonials.${index}.description`, `Depoimento ${index+1} — Texto`);
            add(card.querySelector('h4,h3,strong'), `testimonials.${index}.title`, `Depoimento ${index+1} — Nome`);
        });
        return;
    }

    // Widgets com cartões/itens: também deixamos textos e ícones individuais.
    const genericMap = {
        counters: ['.sbc-counters-shell','counters'],
        pricing: ['.sbc-pricing-shell','pricing'],
        timeline: ['.sbc-timeline-shell','timeline'],
        accordion: ['.sbc-accordion-shell','accordion'],
        faq: ['.sbc-faq-shell','faq'],
        progress: ['.sbc-progress-shell','progress']
    };
    const gm = genericMap[el.type];
    if (gm) {
        const shell = div.querySelector(gm[0]);
        add(shell?.querySelector('header > div:first-child,h2,h3,.sbc-faq-title'), `${gm[1]}.title`, `${gm[1]} — Título`);
        add(shell?.querySelector('header > div:nth-child(2),.sbc-faq-description'), `${gm[1]}.description`, `${gm[1]} — Descrição`);
        shell?.querySelectorAll('i').forEach((node,index) => add(node, `${gm[1]}.${index}.icon`, `${gm[1]} ${index+1} — Ícone`));
        shell?.querySelectorAll('img').forEach((node,index) => add(node, `${gm[1]}.${index}.image`, `${gm[1]} ${index+1} — Imagem`));
        return;
    }
}

function selectInternalPart(event, id, key, label) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    selectedElementId = id;
    selectedPart = {id, key, label};
    document.querySelectorAll('[data-sbc-part].sbc-part-selected').forEach(n => n.classList.remove('sbc-part-selected'));
    const root = document.querySelector(`[data-id="${CSS.escape(id)}"]`);
    const part = root?.querySelector(`[data-sbc-part="${CSS.escape(key)}"]`);
    if (part) part.classList.add('sbc-part-selected');
    if (root) root.classList.add('selected');
    updateProperties(id);
}

function getPartStyleValue(el, key, mode, prop) {
    const parts = ensurePartStyles(el);
    return parts?.[key]?.responsive?.[mode]?.[prop];
}

function setFeaturePartFontSizeLive(id, key, value, mode = getResponsiveDeviceMode()) {
    const el = findElementById(pageContent, id);
    if (!el || el.type !== 'features' || (key !== 'features.title' && key !== 'features.description')) {
        return setPartStyleLive(id, key, 'fontSize', value, mode);
    }

    const clean = value == null ? '' : String(value).trim();
    const parts = ensurePartStyles(el);
    parts[key] = parts[key] || {};
    parts[key].responsive = parts[key].responsive || {};
    parts[key].responsive[mode] = parts[key].responsive[mode] || {};

    if (clean === '') delete parts[key].responsive[mode].fontSize;
    else parts[key].responsive[mode].fontSize = clean;

    // Recursos possui dois caminhos históricos para o tamanho do título.
    // Gravamos os dois, mas o valor da Parte interna continua sendo a fonte
    // principal. Isso evita que uma propriedade antiga sobrescreva a nova.
    el.styles = el.styles || {};
    el.styles.responsive = el.styles.responsive || {};
    el.styles.responsive[mode] = el.styles.responsive[mode] || {};
    const shortKey = key === 'features.title' ? 'titleFontSize' : 'descriptionFontSize';
    if (clean === '') {
        delete el.styles.responsive[mode][shortKey];
    } else {
        // Somente o breakpoint atual recebe o valor. Nunca gravamos no
        // styles.titleFontSize/descriptionFontSize global, pois isso faria
        // Celular contaminar Tablet e Desktop.
        el.styles.responsive[mode][shortKey] = clean;
    }

    selectedElementId = id;
    selectedPart = {id, key, label: selectedPart?.label || key};

    const roots = Array.from(document.querySelectorAll(`[data-id="${CSS.escape(id)}"]`));
    roots.forEach(root => {
        const node = root.querySelector(`[data-sbc-part="${CSS.escape(key)}"]`);
        if (!node) return;
        if (clean === '') {
            node.style.removeProperty('font-size');
        } else {
            const n = parseFloat(clean);
            if (Number.isFinite(n)) node.style.setProperty('font-size', `${n}px`, 'important');
        }
        // Reaplica somente a parte responsiva, depois do estilo direto.
        applyPartResponsiveStyles(root, el, mode);
    });

    // Se o Preview já estiver aberto, atualizamos a aba existente.
    // Não esperamos o usuário clicar novamente em Preview.
    clearTimeout(window.__sbcFeaturePreviewRefreshTimer);
    window.__sbcFeaturePreviewRefreshTimer = setTimeout(() => {
        try {
            if (window.__sbcPreviewTab && !window.__sbcPreviewTab.closed) {
                openPreview(previewDeviceMode || mode);
            }
        } catch (e) {}
    }, 120);

    try { saveData(); } catch (e) {}
}

function setPartStyleLive(id, key, prop, value, mode = getResponsiveDeviceMode()) {
    const el = findElementById(pageContent, id);
    if (!el || !key) return;

    const parts = ensurePartStyles(el);
    parts[key] = parts[key] || {};
    parts[key].responsive = parts[key].responsive || {};
    parts[key].responsive[mode] = parts[key].responsive[mode] || {};

    const clean = value == null ? '' : String(value).trim();
    if (clean === '') delete parts[key].responsive[mode][prop];
    else parts[key].responsive[mode][prop] = clean;

    // Para Recursos, o título/descrição possuem também propriedades próprias
    // responsivas. Mantemos as duas fontes sincronizadas para que nenhuma
    // renderização posterior volte ao tamanho antigo.
    if (el.type === 'features' && (key === 'features.title' || key === 'features.description')) {
        el.styles.responsive = el.styles.responsive || {};
        el.styles.responsive[mode] = el.styles.responsive[mode] || {};
        const rp = key === 'features.title' ? 'title' : 'description';
        if (prop === 'fontSize') {
            if (clean === '') delete el.styles.responsive[mode][rp + 'FontSize'];
            else el.styles.responsive[mode][rp + 'FontSize'] = clean;
        }
        if (prop === 'lineHeight') {
            if (clean === '') delete el.styles.responsive[mode][rp + 'LineHeight'];
            else el.styles.responsive[mode][rp + 'LineHeight'] = clean;
        }
    }

    selectedElementId = id;
    selectedPart = {id, key, label: selectedPart?.label || key};

    // IMPORTANTE: o HTML do widget Recursos é montado pelo builder.
    // Portanto, além de alterar o DOM atual, reconstruímos o canvas para que
    // o novo valor passe pelo mesmo caminho usado na criação inicial do widget.
    // Isso elimina o caso em que o campo mostra o novo número, mas a frase
    // continua com o tamanho antigo.
    if (typeof renderCanvas === 'function') {
        try { renderCanvas(); } catch (e) { console.warn('Falha ao atualizar canvas ao editar parte interna:', e); }
    }

    // Atualização DIRETA do elemento visível.
    // enquanto o usuário digita, evitando que o DOM substitua o campo e,
    // principalmente, garantindo que o número digitado seja aplicado agora.
    const root = document.querySelector(`[data-id="${CSS.escape(id)}"]`);
    const part = root?.querySelector(`[data-sbc-part="${CSS.escape(key)}"]`);
    if (part) {
        const pxProps = new Set(['fontSize','letterSpacing','marginTop','marginRight','marginBottom','marginLeft','paddingTop','paddingRight','paddingBottom','paddingLeft','width','height','maxWidth','minWidth','iconSize','iconBoxSize','iconGap','iconPadding','borderWidth','borderRadius']);
        const cssProp = prop.replace(/([A-Z])/g, '-$1').toLowerCase();
        if (clean === '') {
            part.style.removeProperty(cssProp);
        } else {
            let cssValue = clean;
            if (pxProps.has(prop) && cssValue !== 'auto' && cssValue !== '100%' && cssValue !== 'fit-content' && cssValue !== 'full' && !/[a-z%]+$/i.test(cssValue)) cssValue += 'px';
            part.style.setProperty(cssProp, cssValue, 'important');
        }
    }

    // O título do Recursos pode ser recriado por outras rotinas do editor.
    // Aplicamos também pelo seletor do widget atual.
    if (el.type === 'features' && (key === 'features.title' || key === 'features.description')) {
        const selector = key === 'features.title'
            ? '.sbc-features-shell > header > div:first-child'
            : '.sbc-features-shell > header > div:nth-child(2)';
        const node = root?.querySelector(selector);
        if (node && prop === 'fontSize' && clean !== '') node.style.setProperty('font-size', `${parseFloat(clean) || 0}px`, 'important');
        if (node && prop === 'lineHeight' && clean !== '') node.style.setProperty('line-height', clean, 'important');
    }

    // Salva sem reconstruir o painel. Assim o valor já fica no estado atual
    // mesmo antes do usuário sair do campo.
    try { saveData(); } catch (e) {}

    // Se o Preview estiver aberto em outra aba, atualize a mesma aba imediatamente.
    // O usuário não precisa fechar/reabrir o Preview para enxergar o tamanho novo.
    if (el.type === 'features' && (key === 'features.title' || key === 'features.description') && typeof openPreview === 'function') {
        try {
            const previewTab = window.__sbcPreviewTab;
            if (previewTab && !previewTab.closed) {
                openPreview(mode);
            }
        } catch (e) {}
    }
}

function setPartStyle(id, key, prop, value, mode = getResponsiveDeviceMode()) {
    const el = findElementById(pageContent, id);
    if (!el || !key) return;
    saveState();
    const parts = ensurePartStyles(el);
    parts[key] = parts[key] || {};
    parts[key].responsive = parts[key].responsive || {};
    parts[key].responsive[mode] = parts[key].responsive[mode] || {};
    if (value === '' || value === null || value === undefined) delete parts[key].responsive[mode][prop];
    else parts[key].responsive[mode][prop] = value;
    if (el.type === 'features' && (key === 'features.title' || key === 'features.description')) {
        el.styles.responsive = el.styles.responsive || {};
        el.styles.responsive[mode] = el.styles.responsive[mode] || {};
        const rp = key === 'features.title' ? 'title' : 'description';
        if (prop === 'fontSize') {
            const n = parseFloat(String(value).replace(',', '.'));
            if (Number.isFinite(n)) el.styles.responsive[mode][rp + 'FontSize'] = String(n);
            else delete el.styles.responsive[mode][rp + 'FontSize'];
        } else if (prop === 'lineHeight') {
            const n = parseFloat(String(value).replace(',', '.'));
            if (Number.isFinite(n)) el.styles.responsive[mode][rp + 'LineHeight'] = String(n);
        }
    }
    renderCanvas();
    saveData();
    selectedElementId = id;
    selectedPart = {id, key, label: selectedPart?.label || key};
    updateProperties(id);
}

function clearPartStyleMode(id, key, mode = getResponsiveDeviceMode()) {
    const el = findElementById(pageContent, id);
    if (!el?.styles?.parts?.[key]?.responsive?.[mode]) return;
    saveState();
    delete el.styles.parts[key].responsive[mode];
    renderCanvas();
    saveData();
    selectedElementId = id;
    updateProperties(id);
}

function applyPartResponsiveStyles(root, el, modeOverride) {
    const parts = el?.styles?.parts;
    if (!parts || !root) return;
    const mode = (modeOverride === 'desktop' || modeOverride === 'tablet' || modeOverride === 'mobile')
        ? modeOverride
        : getResponsiveDeviceMode();

    Object.entries(parts).forEach(([key, data]) => {
        const node = root.querySelector(`[data-sbc-part="${CSS.escape(key)}"]`);
        const styles = data?.responsive?.[mode];
        if (!node || !styles) return;

        const px = [
            'fontSize','letterSpacing','marginTop','marginRight','marginBottom','marginLeft',
            'paddingTop','paddingRight','paddingBottom','paddingLeft','width','height','maxWidth','minWidth',
            'iconSize','iconBoxSize','iconGap','iconPadding','borderWidth','borderRadius'
        ];

        Object.entries(styles).forEach(([prop,val]) => {
            if (val === '' || val == null) return;
            let cssProp = prop.replace(/([A-Z])/g,'-$1').toLowerCase();
            let cssVal = String(val).trim();
            if (prop === 'width' && cssVal === 'full') cssVal = '100%';
            if (px.includes(prop) && cssVal !== 'auto' && cssVal !== '100%' && cssVal !== 'fit-content' && cssVal !== 'full' && !/[a-z%]+$/i.test(cssVal)) {
                cssVal += 'px';
            }
            node.style.setProperty(cssProp, cssVal, 'important');
        });
    });
}

function getInternalPartData(el, key) {
    const parts = key.split('.');
    const group = parts[0];
    const index = Number(parts[1]);
    const field = parts[2];
    const s = el.styles || {};
    if (!Number.isInteger(index) || !field || !Array.isArray(s.items)) return null;
    const item = s.items[index];
    if (!item) return null;
    const map = { title:'name', description:'description', icon:'icon', image:'avatar', role:'role', text:'text' };
    const prop = map[field] || field;
    if (Object.prototype.hasOwnProperty.call(item, prop)) return { item, prop, value: item[prop] ?? '', index };
    return null;
}

function updateInternalPartContent(id, key, value) {
    const el = findElementById(pageContent, id);
    if (!el || !key) return;
    const direct = {
        'features.title':'title','features.description':'description',
        'team.title':'title','team.description':'description',
        'gallery.title':'title','gallery.description':'description',
        'testimonials.title':'title','testimonials.description':'description',
        'counters.title':'title','counters.description':'description',
        'pricing.title':'title','pricing.description':'description',
        'timeline.title':'title','timeline.description':'description',
        'accordion.title':'title','accordion.description':'description',
        'faq.title':'title','faq.description':'description',
        'progress.title':'title','progress.description':'description',
        'hero.title':'title','hero.description':'description','hero.button':'buttonText'
    };
    const directProp = direct[key];
    saveState();
    if (directProp) {
        el.styles = el.styles || {};
        el.styles[directProp] = value;
    } else {
        const data = getInternalPartData(el, key);
        if (!data) return;
        data.item[data.prop] = value;
    }
    renderCanvas();
    saveData();
    selectedElementId = id;
    selectedPart = {id, key, label: selectedPart?.label || key};
    updateProperties(id);
}

function renderInternalPartProperties(el) {
    if (!selectedPart || selectedPart.id !== el.id) return '';
    const key = selectedPart.key;
    const mode = getResponsiveDeviceMode();
    const title = selectedPart.label || key;
    const val = prop => getPartStyleValue(el,key,mode,prop) ?? '';
    const escv = v => escapeAttr(v);
    const data = getInternalPartData(el, key);
    const directContent = {
        'features.title': el.styles?.title, 'features.description': el.styles?.description,
        'team.title': el.styles?.title, 'team.description': el.styles?.description,
        'gallery.title': el.styles?.title, 'gallery.description': el.styles?.description,
        'testimonials.title': el.styles?.title, 'testimonials.description': el.styles?.description,
        'counters.title': el.styles?.title, 'counters.description': el.styles?.description,
        'pricing.title': el.styles?.title, 'pricing.description': el.styles?.description,
        'timeline.title': el.styles?.title, 'timeline.description': el.styles?.description,
        'accordion.title': el.styles?.title, 'accordion.description': el.styles?.description,
        'faq.title': el.styles?.title, 'faq.description': el.styles?.description,
        'progress.title': el.styles?.title, 'progress.description': el.styles?.description,
        'hero.title': el.styles?.title, 'hero.description': el.styles?.description, 'hero.button': el.styles?.buttonText
    };
    const contentValue = Object.prototype.hasOwnProperty.call(directContent,key) ? (directContent[key] ?? '') : (data?.value ?? '');
    const isContentEditable = Object.prototype.hasOwnProperty.call(directContent,key) || !!data;
    const contentBlock = isContentEditable ? `<div class="prop-group"><label class="prop-label">Conteúdo</label><textarea class="prop-input" rows="3" onchange="updateInternalPartContent('${el.id}','${escapeAttr(key)}',this.value)">${escapeHtml(contentValue)}</textarea></div>` : '';
    const colorValue = val('color');
    return `<div class="prop-section" style="border:1px solid var(--accent);border-radius:9px;margin-bottom:10px;">
        <div class="prop-title" style="color:var(--accent);"><i class="fas fa-crosshairs"></i> ${escapeHtml(title)}</div>
        <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:9px;">
            <span style="font-size:10px;padding:4px 7px;border-radius:999px;background:var(--accent-glow);color:var(--accent);font-weight:700;">${mode === 'desktop' ? '🖥 Desktop' : mode === 'tablet' ? '📱 Tablet' : '📱 Celular'}</span>
            <span style="font-size:10px;padding:4px 7px;border-radius:999px;background:var(--bg-tertiary);color:var(--text-secondary);">Parte interna</span>
        </div>
        ${contentBlock}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">
            <div class="prop-group"><label class="prop-label">Tamanho</label><input type="number" class="prop-input" placeholder="Padrão" value="${escv(val('fontSize'))}" oninput="setFeaturePartFontSizeLive('${el.id}','${escapeAttr(key)}',this.value)" onblur="setPartStyle('${el.id}','${escapeAttr(key)}','fontSize',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Altura da linha</label><input type="number" step="0.1" class="prop-input" placeholder="Padrão" value="${escv(val('lineHeight'))}" oninput="setPartStyleLive('${el.id}','${escapeAttr(key)}','lineHeight',this.value)" onblur="setPartStyle('${el.id}','${escapeAttr(key)}','lineHeight',this.value)"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">
            <div class="prop-group"><label class="prop-label">Largura</label><input class="prop-input" placeholder="Padrão / auto" value="${escv(val('width'))}" onchange="setPartStyle('${el.id}','${escapeAttr(key)}','width',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Largura máxima</label><input class="prop-input" placeholder="Padrão" value="${escv(val('maxWidth'))}" onchange="setPartStyle('${el.id}','${escapeAttr(key)}','maxWidth',this.value)"></div>
        </div>
        <div class="prop-group"><label class="prop-label">Alinhamento</label><select class="prop-select" onchange="setPartStyle('${el.id}','${escapeAttr(key)}','textAlign',this.value)">
            <option value="" ${val('textAlign')===''?'selected':''}>Padrão</option><option value="left" ${val('textAlign')==='left'?'selected':''}>Esquerda</option><option value="center" ${val('textAlign')==='center'?'selected':''}>Centro</option><option value="right" ${val('textAlign')==='right'?'selected':''}>Direita</option>
        </select></div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">
            <div class="prop-group"><label class="prop-label">Margem superior</label><input class="prop-input" placeholder="Padrão" value="${escv(val('marginTop'))}" onchange="setPartStyle('${el.id}','${escapeAttr(key)}','marginTop',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Margem inferior</label><input class="prop-input" placeholder="Padrão" value="${escv(val('marginBottom'))}" onchange="setPartStyle('${el.id}','${escapeAttr(key)}','marginBottom',this.value)"></div>
        </div>
        ${colorValue !== '' ? `<div class="prop-group"><label class="prop-label">Cor personalizada</label><div class="color-row"><input type="color" value="${escv(/^#([0-9a-f]{6}|[0-9a-f]{3})$/i.test(colorValue)?colorValue:'#000000')}" onchange="setPartStyle('${el.id}','${escapeAttr(key)}','color',this.value)"><input class="prop-input" value="${escv(colorValue)}" onchange="setPartStyle('${el.id}','${escapeAttr(key)}','color',this.value)"></div></div>` : ''}
        <button type="button" onclick="clearPartStyleMode('${el.id}','${escapeAttr(key)}')" style="width:100%;height:32px;border:1px solid var(--border);background:var(--bg-tertiary);color:var(--text-secondary);border-radius:6px;cursor:pointer;font-size:10px;">Limpar ajustes desta tela</button>
    </div>`;
}

function createDOMElement(el) {
    const div = document.createElement('div');
    div.className = `el-${el.type}`;
    if (selectedElementId === el.id) div.classList.add('selected');
    div.dataset.id = el.id;
    div.setAttribute('draggable', 'true');
    
    // Seleção normal do elemento.
    div.addEventListener('click', (e) => {
        e.stopPropagation();
        if (!div.classList.contains('was-dragged')) {
            selectElement(el.id);
        }
        div.classList.remove('was-dragged');
    });

    // LOGO: capturamos o clique no próprio widget, antes de qualquer
    // elemento pai (seção/coluna/container) ou link interno.
    // Isso restaura a seleção da Logo sem alterar as propriedades existentes.
    if (el.type === 'logo') {
        const selectLogo = (e) => {
            if (e.button !== undefined && e.button !== 0) return;
            e.preventDefault();
            e.stopPropagation();
            selectElement(el.id);
        };
        div.addEventListener('pointerdown', selectLogo, true);
        div.addEventListener('mousedown', selectLogo, true);
        div.addEventListener('click', selectLogo, true);

        // No editor, o link da logo não deve navegar. O wrapper continua
        // podendo ser arrastado pela alça de edição.
        div.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                selectElement(el.id);
            }, true);
        });
    }
    
    div.addEventListener('dragstart', (e) => {
        draggedElementId = el.id;
        div.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', el.id);
        setTimeout(() => {
            div.style.opacity = '0.3';
        }, 0);
    });
    
    div.addEventListener('dragend', () => {
        div.classList.remove('dragging');
        div.style.opacity = '1';
        draggedElementId = null;
        clearDropHighlights();
        document.getElementById('canvas')?.classList.remove('drag-over');
    });

    // O formulário contém controles interativos reais. Não deixe o wrapper
    // draggable capturar o clique/arraste ao clicar dentro de inputs, selects,
    // textarea, labels ou no botão de envio, pois isso dificulta a digitação.
    if (el.type === 'form') {
        div.addEventListener('pointerdown', (e) => {
            const interactive = e.target.closest('input, textarea, select, button, label, option');
            if (!interactive) return;
            div.setAttribute('draggable', 'false');
            e.stopPropagation();
        }, true);

        div.addEventListener('focusout', (e) => {
            if (!div.contains(e.relatedTarget)) {
                div.setAttribute('draggable', 'true');
            }
        }, true);

        div.addEventListener('focusin', (e) => {
            if (e.target.closest('input, textarea, select')) {
                div.setAttribute('draggable', 'false');
            }
        }, true);

        div.addEventListener('click', (e) => {
            if (e.target.closest('input, textarea, select, button, label, option')) {
                e.stopPropagation();
            }
        }, true);
    }
    
    const isContainer = ['section', 'column', 'container'].includes(el.type);
    if (isContainer) {
        div.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const widgetType = sidebarDraggedWidgetType || e.dataTransfer.getData('widgetType');
            const targetId = resolveDropTargetAtPoint(e.clientX, e.clientY, widgetType ? null : draggedElementId)
                || (isDropContainer(el.type) ? el.id : resolveDropTarget(e, el));
            if (!targetId) return;
            activeDropTargetId = targetId;
            e.dataTransfer.dropEffect = widgetType ? 'copy' : 'move';
            const targetEl = document.querySelector(`[data-id="${CSS.escape(targetId)}"]`);
            setDropHighlight(targetEl || div);
        });
        
        div.addEventListener('dragleave', (e) => {
            if (div.contains(e.relatedTarget)) return;
            div.classList.remove('drag-over');
        });
        
        div.addEventListener('drop', (e) => {
            e.preventDefault();
            e.stopPropagation();
            clearDropHighlights();
            document.getElementById('canvas')?.classList.remove('drag-over');
            
            const widgetType = e.dataTransfer.getData('widgetType') || sidebarDraggedWidgetType;
            const sourceId = e.dataTransfer.getData('text/plain');
            const targetId = activeDropTargetId
                || resolveDropTargetAtPoint(e.clientX, e.clientY, widgetType ? null : sourceId)
                || (isDropContainer(el.type) ? el.id : resolveDropTarget(e, el));
            
            const safeTargetId = targetId
                || normalizeDropTargetId(selectedInsertTargetId)
                || resolveSelectedInsertTarget();
            if (!safeTargetId) return;

            if (widgetType) {
                if (widgetType === 'section') {
                    createSectionFromStructure({ columns: [12] });
                } else if (widgetType === 'container') {
                    addWidgetToContainer('container', safeTargetId);
                } else {
                    addWidgetToContainer(widgetType, safeTargetId);
                }
            } else if (sourceId && sourceId !== targetId && sourceId !== el.id) {
                moveElementToContainer(sourceId, targetId);
            }
        });
    }
    
    /* =========================================================
   APLICAÇÃO DOS ESTILOS DO ELEMENTO
   ========================================================= */

if (el.styles && typeof el.styles === 'object') {

    Object.keys(el.styles).forEach(key => {

        /*
         * Alguns estilos são propriedades internas
         * do widget e NÃO devem ser aplicados no wrapper.
         */
        const internalWidgetStyles = [
            'color',
            'thickness',
            'lineStyle',
            'margin',
            'width',
            'alignment',
            'useGradient',
            'gradientStart',
            'gradientEnd',
            'gradientDirection',
            'opacity',
            'shadow',
            'shadowX',
            'shadowY',
            'shadowBlur',
            'shadowSpread',
            'shadowColor',
            'height',
            'decorStyle',
            'decorContent',
            'decorType',
            'decorBgColor',
            'decorTextColor',
            'decorSize',
            'decorPadding',
            'decorFontWeight',
            'decorLetterSpacing',
            'decorTextTransform',
            'decorShape','decorBorder','decorBorderWidth','decorBorderColor','decorShadow','decorIconOpacity','decorRadius','decorAnimation',
            'widthUnit','textTransform','textDecoration','textShadow','textShadowX','textShadowY','textShadowBlur','textShadowColor',
            'title','description','faqItems','questionBg','questionHoverBg','questionOpenBg','questionColor','questionOpenColor','questionFontSize','answerBg','answerColor','answerFontSize','answerLineHeight','borderColor','borderWidth','radius','itemSpacing','paddingY','paddingX','titleColor','titleFontSize','descriptionColor','descriptionFontSize','iconColor','iconType','iconPosition','multipleOpen','shadow','shadowX','shadowY','shadowBlur','shadowSpread','shadowColor',
            'imageWidth','imageHeight','objectFit','borderRadius','borderRadiusTL','borderRadiusTR','borderRadiusBR','borderRadiusBL','opacity','align','link','targetBlank','alt','loading','shadow','shadowX','shadowY','shadowBlur','shadowSpread','shadowColor','hoverEffect','hoverScale','hoverTransition','grayscale','brightness','contrast','saturate',
            'slides','bannerSlides','slidesVisible','gap','autoplay','autoplayInterval','pauseOnHover','loop','showArrows','showDots','transition','transitionDuration','slideHeight','objectFit','borderRadius','shadow','shadowX','shadowY','shadowBlur','shadowSpread','shadowColor','overlay','overlayColor','titleColor','textColor','buttonBg','buttonColor','title','description','widthUnit','alignment','columns','cardBg','cardBorderStyle','cardBorderWidth','cardBorderColor','cardRadius','cardPadding','textColor','nameColor','roleColor','starColor','textFontSize','nameFontSize','roleFontSize','avatarSize','avatarRadius','hoverBg','hoverLift','titleColor','titleFontSize','descriptionColor','descriptionFontSize','heroTitle','heroDescription','heroButton','buttonText','buttonLink','buttonTargetBlank','bgType','bgColor2','gradientDirection','bgImage','overlay','overlayColor','contentAlign','minHeight','paddingY','paddingX','contentWidth','titleWeight','descriptionLineHeight','buttonBg','buttonBgHover','buttonColor','buttonRadius','buttonPaddingY','buttonPaddingX','buttonFontSize','borderRadius'
        ];

        /*
         * No Divisor, esses estilos são tratados
         * exclusivamente pelo buildDividerHTML().
         */
        if (el.type === 'divider' && internalWidgetStyles.includes(key)) {
            return;
        }

        let cssKey = key
            .replace(/([A-Z])/g, '-$1')
            .toLowerCase();

        let value = el.styles[key];

        if (value === null || value === undefined || value === '') {
            return;
        }

        if (key === 'bgColor') {
            div.style.backgroundColor = value;

        } else if (key === 'flex') {
            div.style.flex = value;

        } else {

            /*
             * Converte alguns valores numéricos para px
             * quando necessário.
             */
            const pxProperties = [
                'padding',
                'paddingTop',
                'paddingRight',
                'paddingBottom',
                'paddingLeft',
                'marginTop',
                'marginRight',
                'marginBottom',
                'marginLeft',
                'borderRadius',
                'fontSize',
                'letterSpacing'
            ];

            if (
                pxProperties.includes(key) &&
                typeof value === 'number'
            ) {
                value = `${value}px`;
            }

            div.style.setProperty(cssKey, value);
        }
    });
}

/*
 * POSIÇÃO REAL DO ELEMENTO
 *
 * O alinhamento do conteúdo interno (ex.: text-align) não move o widget.
 * Aqui alinhamos o próprio widget dentro do container pai.
 */
if (!['section','column','container','divider','spacer'].includes(el.type)) {
    const rawElementAlign = el.styles?.elementAlign || el.styles?.alignment || el.styles?.align || 'left';
    const elementAlign = rawElementAlign === 'center' ? 'center' : (rawElementAlign === 'right' ? 'right' : 'left');
    div.style.alignSelf = elementAlign === 'center' ? 'center' : (elementAlign === 'right' ? 'flex-end' : 'flex-start');

    // Elementos com largura própria continuam usando sua largura configurada.
    if (el.type === 'logo') {
        div.style.width = '100%';
        div.style.maxWidth = '100%';
    } else if (el.type === 'image') {
        const iw = el.styles?.imageWidth;
        if (iw === 'auto' || iw === '' || iw == null) {
            div.style.width = 'fit-content';
            div.style.maxWidth = '100%';
        } else {
            const pct = Math.max(1, Math.min(100, parseFloat(iw) || 100));
            div.style.width = `${pct}%`;
            div.style.maxWidth = '100%';
        }
    } else if (el.type === 'button') {
        // O próprio widget recebe a largura configurada e é posicionado
        // dentro do container por margem automática. Isso faz esquerda /
        // centro / direita funcionar mesmo quando o pai é um bloco comum.
        const bsLayout = el.styles || {};
        const rawBtnWidth = bsLayout.width;
        let widgetWidth = 'fit-content';
        if (rawBtnWidth !== undefined && rawBtnWidth !== null && rawBtnWidth !== '' && rawBtnWidth !== 'auto') {
            if (rawBtnWidth === 'full') {
                widgetWidth = '100%';
            } else {
                const n = parseFloat(rawBtnWidth);
                if (Number.isFinite(n) && n > 0) {
                    const unit = bsLayout.widthUnit === '%' ? '%' : 'px';
                    const safe = unit === '%' ? Math.min(100, Math.max(1, n)) : Math.min(2000, Math.max(1, n));
                    widgetWidth = `${safe}${unit}`;
                }
            }
        }
        const widgetAlign = ['left','center','right'].includes(bsLayout.align) ? bsLayout.align : 'left';
        div.style.width = widgetWidth;
        div.style.maxWidth = '100%';
        div.style.marginLeft = widgetAlign === 'center' || widgetAlign === 'right' ? 'auto' : '0';
        div.style.marginRight = widgetAlign === 'center' || widgetAlign === 'left' ? 'auto' : '0';
    } else if (el.type === 'gallery') {
        const gsLayout = el.styles || {};
        let galleryWidgetWidth = '100%';
        const raw = gsLayout.width;
        if (raw !== undefined && raw !== null && raw !== '' && raw !== 'auto') {
            const n = parseFloat(raw);
            if (Number.isFinite(n) && n > 0) {
                const unit = gsLayout.widthUnit === 'px' ? 'px' : '%';
                const safe = unit === '%' ? Math.min(100, Math.max(1, n)) : Math.min(2000, Math.max(160, n));
                galleryWidgetWidth = `${safe}${unit}`;
            }
        }
        const galleryAlign = ['left','center','right'].includes(gsLayout.alignment) ? gsLayout.alignment : 'center';
        div.style.width = galleryWidgetWidth;
        div.style.maxWidth = '100%';
        div.style.marginLeft = galleryAlign === 'center' || galleryAlign === 'right' ? 'auto' : '0';
        div.style.marginRight = galleryAlign === 'center' || galleryAlign === 'left' ? 'auto' : '0';
    } else if (el.type === 'carousel') {
        // IMPORTANTE: o carrossel precisa usar os valores do breakpoint atual.
        // Antes este bloco lia apenas el.styles, então Tablet/Mobile e os
        // campos responsivos podiam aparecer no painel mas não alterar o widget.
        const baseCarouselStyles = el.styles || {};
        const currentCarouselMode = getResponsiveDeviceMode();
        const carouselResponsive = baseCarouselStyles.responsive?.[currentCarouselMode] || {};
        const csLayout = { ...baseCarouselStyles, ...carouselResponsive };

        let carouselWidth = '100%';
        const raw = csLayout.width;
        if (raw !== undefined && raw !== null && raw !== '' && raw !== 'auto') {
            const n = parseFloat(String(raw).replace(',', '.'));
            if (Number.isFinite(n) && n > 0) {
                const unit = String(csLayout.widthUnit || '%') === 'px' ? 'px' : '%';
                const safe = unit === '%' ? Math.min(100, Math.max(1, n)) : Math.min(2000, Math.max(1, n));
                carouselWidth = `${safe}${unit}`;
            }
        }

        const rawMaxWidth = csLayout.maxWidth;
        let carouselMaxWidth = '100%';
        if (rawMaxWidth !== undefined && rawMaxWidth !== null && rawMaxWidth !== '' && rawMaxWidth !== 'auto') {
            const maxText = String(rawMaxWidth).trim();
            const maxNum = parseFloat(maxText.replace(',', '.'));
            if (Number.isFinite(maxNum) && maxNum > 0) {
                carouselMaxWidth = /%$/.test(maxText) ? `${Math.min(100, maxNum)}%` : `${Math.min(2000, maxNum)}px`;
            }
        }

        const carouselAlign = ['left','center','right'].includes(csLayout.alignment) ? csLayout.alignment : 'center';
        const marginLeft = csLayout.marginLeft !== undefined && csLayout.marginLeft !== '' ? String(csLayout.marginLeft) : '';
        const marginRight = csLayout.marginRight !== undefined && csLayout.marginRight !== '' ? String(csLayout.marginRight) : '';

        // Todas as regras abaixo são inline !important porque existem regras
        // estruturais globais que deixam widgets com width:100%.
        div.style.setProperty('width', carouselWidth, 'important');
        div.style.setProperty('max-width', carouselMaxWidth, 'important');
        div.style.setProperty('min-width', '0', 'important');
        div.style.setProperty('flex', '0 0 auto', 'important');
        div.style.setProperty('box-sizing', 'border-box', 'important');

        if (marginLeft) div.style.setProperty('margin-left', marginLeft, 'important');
        else div.style.setProperty('margin-left', carouselAlign === 'center' || carouselAlign === 'right' ? 'auto' : '0', 'important');
        if (marginRight) div.style.setProperty('margin-right', marginRight, 'important');
        else div.style.setProperty('margin-right', carouselAlign === 'center' || carouselAlign === 'left' ? 'auto' : '0', 'important');

        // O conteúdo interno acompanha exatamente a largura do wrapper.
        const carouselShell = div.querySelector('.sbc-carousel-shell');
        if (carouselShell) {
            carouselShell.style.setProperty('width', '100%', 'important');
            carouselShell.style.setProperty('max-width', '100%', 'important');
            carouselShell.style.setProperty('min-width', '0', 'important');
            carouselShell.style.setProperty('box-sizing', 'border-box', 'important');
        }
    } else if (el.type === 'tabs') {
        const tsLayout = el.styles || {};
        const raw = tsLayout.width; let tabsWidth='100%';
        if(raw !== undefined && raw !== null && raw !== '' && raw !== 'auto'){ const n=parseFloat(raw); if(Number.isFinite(n)&&n>0){ const unit=tsLayout.widthUnit==='px'?'px':'%'; const safe=unit==='%'?Math.min(100,Math.max(1,n)):Math.min(2000,Math.max(160,n)); tabsWidth=`${safe}${unit}`; }}
        const tabsAlign=['left','center','right'].includes(tsLayout.alignment)?tsLayout.alignment:'left';
        div.style.width=tabsWidth; div.style.maxWidth='100%'; div.style.marginLeft=tabsAlign==='center'||tabsAlign==='right'?'auto':'0'; div.style.marginRight=tabsAlign==='center'||tabsAlign==='left'?'auto':'0';
    } else if (el.type === 'faq') {
        const fsLayout = el.styles || {};
        const faqWidthRaw = fsLayout.width;
        let faqWidgetWidth = '100%';
        if (faqWidthRaw !== undefined && faqWidthRaw !== null && faqWidthRaw !== '' && faqWidthRaw !== 'auto') {
            const n = parseFloat(faqWidthRaw);
            if (Number.isFinite(n) && n > 0) {
                const unit = fsLayout.widthUnit === 'px' ? 'px' : '%';
                const safe = unit === '%' ? Math.min(100, Math.max(1, n)) : Math.min(2000, Math.max(1, n));
                faqWidgetWidth = `${safe}${unit}`;
            }
        }
        const faqAlign = ['left','center','right'].includes(fsLayout.alignment) ? fsLayout.alignment : 'left';
        div.style.width = faqWidgetWidth;
        div.style.maxWidth = '100%';
        div.style.marginLeft = faqAlign === 'center' || faqAlign === 'right' ? 'auto' : '0';
        div.style.marginRight = faqAlign === 'center' || faqAlign === 'left' ? 'auto' : '0';
    } else if (el.type === 'features') {
        const fsLayout = el.styles || {};
        let featureWidth = '100%';
        const raw = fsLayout.width;
        if (raw !== undefined && raw !== null && raw !== '' && raw !== 'auto') {
            const n = parseFloat(raw);
            if (Number.isFinite(n) && n > 0) {
                const unit = fsLayout.widthUnit === 'px' ? 'px' : '%';
                const safe = unit === '%' ? Math.min(100, Math.max(1, n)) : Math.min(2000, Math.max(160, n));
                featureWidth = `${safe}${unit}`;
            }
        }
        const featureAlign = ['left','center','right'].includes(fsLayout.alignment) ? fsLayout.alignment : 'center';
        div.style.width = featureWidth; div.style.maxWidth='100%';
        div.style.marginLeft = featureAlign === 'center' || featureAlign === 'right' ? 'auto' : '0';
        div.style.marginRight = featureAlign === 'center' || featureAlign === 'left' ? 'auto' : '0';
    } else if (el.type === 'accordion') {
        const as = el.styles || {};
        let accordionWidth = '100%';
        const raw = as.width;
        if (raw !== undefined && raw !== null && raw !== '' && raw !== 'auto') {
            const n = parseFloat(raw);
            if (Number.isFinite(n) && n > 0) {
                const unit = as.widthUnit === 'px' ? 'px' : '%';
                const safe = unit === '%' ? Math.min(100, Math.max(1, n)) : Math.min(2000, Math.max(1, n));
                accordionWidth = `${safe}${unit}`;
            }
        }
        const accordionAlign = ['left','center','right'].includes(as.alignment) ? as.alignment : 'left';
        div.style.width = accordionWidth; div.style.maxWidth='100%';
        div.style.marginLeft = accordionAlign === 'center' || accordionAlign === 'right' ? 'auto' : '0';
        div.style.marginRight = accordionAlign === 'center' || accordionAlign === 'left' ? 'auto' : '0';
    } else if (el.type === 'progress') {
        const ps = el.styles || {};
        let progressWidth = '100%';
        const raw = ps.width;
        if (raw !== undefined && raw !== null && raw !== '' && raw !== 'auto') {
            const n = parseFloat(raw);
            if (Number.isFinite(n) && n > 0) {
                const unit = ps.widthUnit === 'px' ? 'px' : '%';
                const safe = unit === '%' ? Math.min(100, Math.max(1, n)) : Math.min(2000, Math.max(160, n));
                progressWidth = `${safe}${unit}`;
            }
        }
        const progressAlign = ['left','center','right'].includes(ps.alignment) ? ps.alignment : 'left';
        div.style.width = progressWidth; div.style.maxWidth='100%';
        div.style.marginLeft = progressAlign === 'center' || progressAlign === 'right' ? 'auto' : '0';
        div.style.marginRight = progressAlign === 'center' || progressAlign === 'left' ? 'auto' : '0';
    } else if (el.type === 'hero') {
        const hs = el.styles || {};
        let heroWidth = '100%';
        const raw = hs.contentWidth;
        if (raw !== undefined && raw !== null && raw !== '' && raw !== 'auto') {
            const n = parseFloat(raw);
            if (Number.isFinite(n) && n > 0) heroWidth = `${Math.min(100, Math.max(1, n))}%`;
        }
        const heroAlign = ['left','center','right'].includes(hs.contentAlign) ? hs.contentAlign : 'center';
        div.style.width = '100%';
        div.style.maxWidth = '100%';
        div.style.marginLeft = heroAlign === 'center' || heroAlign === 'right' ? 'auto' : '0';
        div.style.marginRight = heroAlign === 'center' || heroAlign === 'left' ? 'auto' : '0';
    } else if (el.type === 'navbar') {
        const ns = el.styles || {};
        let navbarWidth = '100%';
        const raw = ns.width;
        if (raw !== undefined && raw !== null && raw !== '' && raw !== 'auto') {
            const n = parseFloat(raw);
            if (Number.isFinite(n) && n > 0) {
                const unit = ns.widthUnit === 'px' ? 'px' : '%';
                const safe = unit === '%' ? Math.min(100, Math.max(1, n)) : Math.min(2000, Math.max(160, n));
                navbarWidth = `${safe}${unit}`;
            }
        }
        const navAlign = ['left','center','right'].includes(ns.alignment) ? ns.alignment : 'center';
        div.style.width = navbarWidth; div.style.maxWidth='100%';
        div.style.marginLeft = navAlign === 'center' || navAlign === 'right' ? 'auto' : '0';
        div.style.marginRight = navAlign === 'center' || navAlign === 'left' ? 'auto' : '0';
    } else if (el.type === 'newsletter') {
        const ns = el.styles || {};
        let newsletterWidth = '100%';
        const raw = ns.width;
        if (raw !== undefined && raw !== null && raw !== '' && raw !== 'auto') {
            const n = parseFloat(raw);
            if (Number.isFinite(n) && n > 0) {
                const unit = ns.widthUnit === 'px' ? 'px' : '%';
                const safe = unit === '%' ? Math.min(100, Math.max(1, n)) : Math.min(2000, Math.max(160, n));
                newsletterWidth = `${safe}${unit}`;
            }
        }
        const newsletterAlign = ['left','center','right'].includes(ns.alignment) ? ns.alignment : 'center';
        div.style.width = newsletterWidth; div.style.maxWidth='100%';
        div.style.marginLeft = newsletterAlign === 'center' || newsletterAlign === 'right' ? 'auto' : '0';
        div.style.marginRight = newsletterAlign === 'center' || newsletterAlign === 'left' ? 'auto' : '0';
    } else if (el.type === 'heading' || el.type === 'text' || el.type === 'icon') {
        div.style.width = 'fit-content';
        div.style.maxWidth = '100%';
    }
}

/*
 * O wrapper do Divisor deve ocupar 100% da largura.
 * A largura real da linha é controlada por buildDividerHTML().
 */
if (el.type === 'divider') {
    div.style.setProperty('width', '100%', 'important');
    div.style.setProperty('min-width', '0', 'important');
    div.style.setProperty('height', 'auto', 'important');
    div.style.setProperty('box-sizing', 'border-box', 'important');
    div.style.setProperty('padding', '10px', 'important');
}

/*
 * Espaço: somente a altura é variável. A largura nunca depende de
 * alignment, width ou estilos herdados de outros widgets.
 */
if (el.type === 'spacer') {
    div.style.setProperty('width', '100%', 'important');
    div.style.setProperty('max-width', '100%', 'important');
    div.style.setProperty('min-width', '0', 'important');
    div.style.setProperty('flex', '0 0 auto', 'important');
    div.style.setProperty('align-self', 'stretch', 'important');
    div.style.setProperty('margin-left', '0', 'important');
    div.style.setProperty('margin-right', '0', 'important');
    div.style.setProperty('padding', '0', 'important');
    div.style.setProperty('box-sizing', 'border-box', 'important');
}

/*
 * Vídeo
 * Mantemos o bloco responsivo, mas não forçamos o wrapper externo a
 * 100% para que o alinhamento do próprio elemento possa funcionar.
 */
if (el.type === 'video') {
    const vs = el.styles || {};
    const rawWidth = vs.width;
    let videoWidgetWidth = '100%';
    if (rawWidth !== undefined && rawWidth !== null && rawWidth !== '' && rawWidth !== 'auto') {
        const n = parseFloat(rawWidth);
        if (Number.isFinite(n) && n > 0) {
            const unit = vs.widthUnit === '%' ? '%' : 'px';
            const safe = unit === '%' ? Math.min(100, Math.max(1, n)) : Math.min(2000, Math.max(120, n));
            videoWidgetWidth = `${safe}${unit}`;
        }
    }
    const videoAlignment = ['left','center','right'].includes(vs.alignment) ? vs.alignment : 'center';
    div.style.setProperty('width', videoWidgetWidth, 'important');
    div.style.setProperty('max-width', '100%', 'important');
    div.style.setProperty('min-width', '0', 'important');
    div.style.setProperty('box-sizing', 'border-box', 'important');
    // Margens automáticas posicionam o próprio bloco do vídeo e funcionam
    // mesmo quando o container pai não estiver com flex alinhável.
    div.style.setProperty('margin-left', videoAlignment === 'left' ? '0' : 'auto', 'important');
    div.style.setProperty('margin-right', videoAlignment === 'right' ? '0' : 'auto', 'important');
}

    const deleteBtn = document.createElement('button');
    deleteBtn.className = 'el-delete';
    deleteBtn.innerHTML = '<i class="fas fa-times"></i>';
    deleteBtn.addEventListener('click', (e) => { 
        e.stopPropagation(); 
        deleteElement(el.id); 
    });
    div.appendChild(deleteBtn);

    const duplicateBtn = document.createElement('button');
    duplicateBtn.className = 'el-duplicate';
    duplicateBtn.innerHTML = '<i class="fas fa-copy"></i>';
    duplicateBtn.addEventListener('click', (e) => { 
        e.stopPropagation(); 
        duplicateElement(el.id); 
    });
    div.appendChild(duplicateBtn);
    
    let contentHTML = '';
    if (el.type === 'heading') {
        contentHTML = `<h2 style="font-size:${el.styles.fontSize || 32}px;font-family:'${escapeAttr(el.styles.fontFamily || 'Poppins')}';font-weight:${el.styles.fontWeight || 700};font-style:${el.styles.fontStyle || 'normal'};line-height:${el.styles.lineHeight || 1.2};letter-spacing:${el.styles.letterSpacing || 0}px;color:${el.styles.color || '#212529'};text-align:${el.styles.textAlign || 'left'};margin:0;">${escapeHtml(el.content ?? '')}</h2>`;
    } else if (el.type === 'text') {
        contentHTML = `<p style="font-size:${el.styles.fontSize || 16}px;font-family:'${escapeAttr(el.styles.fontFamily || 'Inter')}';font-weight:${el.styles.fontWeight || 400};font-style:${el.styles.fontStyle || 'normal'};line-height:${el.styles.lineHeight || 1.6};letter-spacing:${el.styles.letterSpacing || 0}px;color:${el.styles.color || '#666'};text-align:${el.styles.textAlign || 'left'};margin:0;">${escapeHtml(el.content ?? '')}</p>`;
    } else if (el.type === 'bannerAd') {
        const bannerMode = getResponsiveDeviceMode();
        const bannerStyles = { ...(el.styles || {}), ...((el.styles || {}).responsive?.[bannerMode] || {}) };
        contentHTML = buildBannerAdHTML(bannerStyles);
    } else if (el.type === 'videoAd') {
        contentHTML = buildVideoAdHTML(el.styles || {});
    } else if (el.type === 'adEmbed') {
        contentHTML = buildAdEmbedHTML(el.styles || {});
    } else if (el.type === 'video') {
        const videoAlign = ['left','center','right'].includes(el.styles?.alignment) ? el.styles.alignment : 'center';
        const videoJustify = videoAlign === 'left' ? 'flex-start' : videoAlign === 'right' ? 'flex-end' : 'center';
        contentHTML = `<div class="sbc-element-align" style="display:flex !important;justify-content:${videoJustify} !important;">${buildVideoHTML(el.styles || {})}</div>`;
    } else if (el.type === 'image') {
        const is = el.styles || {};
        const imgSrc = el.content || 'https://placehold.co/600x400';
        const imgWidth = is.imageWidth === 'auto' ? 'auto' : `${Math.max(1, Math.min(100, parseFloat(is.imageWidth ?? 100) || 100))}%`;
        const imgHeight = is.imageHeight === 'auto' || is.imageHeight === '' || is.imageHeight == null ? 'auto' : `${Math.max(1, parseFloat(is.imageHeight) || 300)}px`;
        const imgRadius = `${Math.max(0, parseFloat(is.borderRadiusTL ?? is.borderRadius ?? 8) || 0)}px ${Math.max(0, parseFloat(is.borderRadiusTR ?? is.borderRadius ?? 8) || 0)}px ${Math.max(0, parseFloat(is.borderRadiusBR ?? is.borderRadius ?? 8) || 0)}px ${Math.max(0, parseFloat(is.borderRadiusBL ?? is.borderRadius ?? 8) || 0)}px`;
        const imgOpacity = Math.max(0, Math.min(1, parseFloat(is.opacity ?? 1) || 0));
        const imgShadow = is.shadow ? `${parseFloat(is.shadowX ?? 0) || 0}px ${parseFloat(is.shadowY ?? 6) || 0}px ${Math.max(0, parseFloat(is.shadowBlur ?? 18) || 0)}px ${parseFloat(is.shadowSpread ?? 0) || 0}px ${is.shadowColor || 'rgba(0,0,0,.18)'}` : 'none';
        const effect = is.hoverEffect || 'none';
        const hoverTransform = effect === 'scale' ? `scale(${parseFloat(is.hoverScale ?? 1.03) || 1.03})` : effect === 'up' ? 'translateY(-4px)' : effect === 'down' ? 'translateY(4px)' : 'none';
        const grayscale = Math.max(0, Math.min(100, parseFloat(is.grayscale ?? 0) || 0));
        const brightness = Math.max(0, parseFloat(is.brightness ?? 1) || 1);
        const contrast = Math.max(0, parseFloat(is.contrast ?? 1) || 1);
        const saturate = Math.max(0, parseFloat(is.saturate ?? 1) || 1);
        const baseFilter = `grayscale(${grayscale}%) brightness(${brightness}) contrast(${contrast}) saturate(${saturate})`;
        const hoverFilter = effect === 'bright' ? `grayscale(${grayscale}%) brightness(${Math.max(brightness, 1.12)}) contrast(${contrast}) saturate(${saturate})` : baseFilter;
        const safeId = String(el.id).replace(/[^a-zA-Z0-9_-]/g, '_');
        const align = ['left','center','right'].includes(is.align) ? is.align : 'center';
        const justify = align === 'left' ? 'flex-start' : align === 'right' ? 'flex-end' : 'center';
        const objectFit = ['cover','contain','fill','none'].includes(is.objectFit) ? is.objectFit : 'cover';
        const wrapperStyle = `width:100%;max-width:100%;--img-justify:${justify};--img-transition:${Math.max(0, parseFloat(is.hoverTransition ?? .3) || .3)}s;--img-hover-transform:${hoverTransform};--img-hover-filter:${hoverFilter};`;
        const imgStyle = `width:${imgWidth};max-width:100%;height:${imgHeight};object-fit:${objectFit};border-radius:${imgRadius};display:block;vertical-align:middle;opacity:${imgOpacity};box-shadow:${imgShadow};filter:${baseFilter};`;
        const target = is.targetBlank ? ' target="_blank" rel="noopener noreferrer"' : '';
        const imageTag = `<img class="sbc-editor-image" src="${escapeAttr(imgSrc)}" alt="${escapeAttr(is.alt || 'Imagem')}" loading="${is.loading === 'eager' ? 'eager' : 'lazy'}" style="${imgStyle}">`;
        const wrapped = is.link ? `<a class="sbc-image-link" href="${escapeAttr(is.link)}"${target}>${imageTag}</a>` : imageTag;
        contentHTML = `<div class="sbc-image-wrap sbc-image-wrap-${safeId}" style="${wrapperStyle}">${wrapped}</div>`;
    } else if (el.type === 'button') {
        const bs = el.styles || {};
        const btnText = bs.textColor || '#ffffff';
        const btnBg = bs.bgColor || '#3b82f6';
        const btnBgHover = bs.bgColorHover || '#2563eb';
        const btnTextHover = bs.textColorHover || btnText;
        const borderColor = bs.borderColor || 'transparent';
        const borderHover = bs.borderColorHover || borderColor;
        const radius = `${bs.borderRadiusTL ?? bs.borderRadius ?? 8}px ${bs.borderRadiusTR ?? bs.borderRadius ?? 8}px ${bs.borderRadiusBR ?? bs.borderRadius ?? 8}px ${bs.borderRadiusBL ?? bs.borderRadius ?? 8}px`;
        const shadow = bs.shadow ? `${bs.shadowX || 0}px ${bs.shadowY || 4}px ${bs.shadowBlur || 12}px ${bs.shadowSpread || 0}px ${bs.shadowColor || 'rgba(0,0,0,.18)'}` : 'none';
        const hoverShadow = bs.hoverShadow ? (bs.shadow ? shadow : `0 6px 16px rgba(0,0,0,.18)`) : 'none';
        const transform = bs.hoverTransform === 'scale' ? `scale(${bs.hoverScale || 1.02})` : (bs.hoverTransform === 'up' ? 'translateY(-2px)' : (bs.hoverTransform === 'down' ? 'translateY(2px)' : 'none'));
        const directions = ['to right','to left','to bottom','to top','135deg','45deg'];
        const direction = directions.includes(bs.gradientDirection) ? bs.gradientDirection : 'to right';
        const bg = bs.gradient ? `linear-gradient(${direction}, ${btnBg}, ${bs.gradientEnd || '#60a5fa'})` : btnBg;
        const icon = bs.icon ? `<i class="fas ${escapeAttr(bs.icon)} button-icon"></i>` : '';
        const gap = `${bs.iconGap ?? 8}px`;
        const inner = bs.iconPosition === 'after' ? `${escapeHtml(el.content ?? '')}${icon}` : `${icon}${escapeHtml(el.content ?? '')}`;

        // Largura digitada pelo usuário: auto, px ou %.
        let width = 'auto';
        if (bs.width !== undefined && bs.width !== null && bs.width !== '' && bs.width !== 'auto') {
            if (bs.width === 'full') {
                width = '100%';
            } else {
                const n = parseFloat(bs.width);
                if (Number.isFinite(n) && n > 0) {
                    const unit = bs.widthUnit === '%' ? '%' : 'px';
                    width = `${Math.min(unit === '%' ? 1000 : 2000, Math.max(1, n))}${unit}`;
                }
            }
        }
        const height = bs.height === 'auto' || bs.height === '' || bs.height == null ? 'auto' : `${Math.max(1, parseFloat(bs.height) || 1)}px`;
        const borderWidth = Math.min(20, Math.max(0, parseFloat(bs.borderWidth ?? 0) || 0));
        const borderStyle = ['solid','dashed','dotted','double','none'].includes(bs.borderStyle) ? bs.borderStyle : 'solid';
        const link = bs.link ? escapeAttr(bs.link) : '#';
        const target = bs.targetBlank ? ' target="_blank" rel="noopener noreferrer"' : '';
        const fontFamily = escapeAttr(bs.fontFamily || 'Inter');
        // A posição é controlada pelo wrapper externo. O botão ocupa 100% desse
        // wrapper quando existe largura configurada; em automático, ele mantém
        // apenas o tamanho necessário para o conteúdo.
        const buttonAlign = ['left','center','right'].includes(bs.align) ? bs.align : 'left';
        const explicitWidth = width !== 'auto';
        const buttonRenderWidth = explicitWidth ? '100%' : 'auto';
        const buttonHTML = `<a class="editor-button" href="${link}"${target} style="--btn-bg:${escapeAttr(bg)};--btn-bg-hover:${escapeAttr(btnBgHover)};--btn-text:${escapeAttr(btnText)};--btn-text-hover:${escapeAttr(btnTextHover)};--btn-border:${escapeAttr(borderColor)};--btn-border-hover:${escapeAttr(borderHover)};--btn-border-width:${borderWidth}px;--btn-border-style:${borderStyle};--btn-hover-transform:${escapeAttr(transform)};--btn-hover-shadow:${escapeAttr(hoverShadow)};--btn-transition:${escapeAttr(bs.hoverTransition || .25)}s;--btn-opacity:${escapeAttr(bs.opacity || 1)};--btn-decoration:${escapeAttr(bs.textDecoration || 'none')};background:${escapeAttr(bg)} !important;color:${escapeAttr(btnText)} !important;border-width:${borderWidth}px !important;border-style:${borderStyle} !important;border-color:${escapeAttr(borderColor)} !important;border-radius:${radius};font-size:${escapeAttr(bs.fontSize || 16)}px;font-family:'${fontFamily}';font-weight:${escapeAttr(bs.fontWeight || 600)};letter-spacing:${escapeAttr(bs.letterSpacing || 0)}px;line-height:${escapeAttr(bs.lineHeight || 1.2)};padding:${escapeAttr(bs.paddingTop || 12)}px ${escapeAttr(bs.paddingRight || 24)}px ${escapeAttr(bs.paddingBottom || 12)}px ${escapeAttr(bs.paddingLeft || 24)}px;width:${buttonRenderWidth};max-width:100%;height:${escapeAttr(height)};box-shadow:${escapeAttr(shadow)};gap:${escapeAttr(gap)};text-align:center;display:flex !important;box-sizing:border-box !important;">${inner}</a>`;
        contentHTML = `<div class="sbc-button-align" style="width:100% !important;display:flex !important;align-items:center !important;box-sizing:border-box !important;">${buttonHTML}</div>`;
    } else if (el.type === 'icon') {
        const is = el.styles || {};
        const iconAlign = ['left','center','right'].includes(is.alignment) ? is.alignment : 'center';
        const iconJustify = iconAlign === 'left' ? 'flex-start' : iconAlign === 'right' ? 'flex-end' : 'center';
        const iconSize = Math.max(8, Math.min(300, parseFloat(is.iconSize ?? 48) || 48));
        const iconPadding = Math.max(0, Math.min(200, parseFloat(is.iconPadding ?? 0) || 0));
        const iconRadius = Math.max(0, Math.min(200, parseFloat(is.iconRadius ?? 8) || 0));
        const iconOpacity = Math.max(0, Math.min(1, parseFloat(is.iconOpacity ?? 1) || 0));
        const iconBg = String(is.iconBgColor || 'transparent');
        const marginTop = Math.max(-200, Math.min(200, parseFloat(is.iconMarginTop ?? 0) || 0));
        const marginBottom = Math.max(-200, Math.min(200, parseFloat(is.iconMarginBottom ?? 0) || 0));
        contentHTML = `<div class="sbc-icon-align" style="justify-content:${iconJustify} !important;">` +
            `<i class="fas sbc-editor-icon ${escapeAttr(is.icon || 'fa-star')}" style="font-size:${iconSize}px;color:${escapeAttr(is.iconColor || '#3b82f6')};background:${escapeAttr(iconBg)};padding:${iconPadding}px;border-radius:${iconRadius}px;opacity:${iconOpacity};margin-top:${marginTop}px;margin-bottom:${marginBottom}px;line-height:1;display:inline-flex;align-items:center;justify-content:center;box-sizing:content-box;"></i>` +
            `</div>`;
    } else if (el.type === 'divider') {
        contentHTML = buildDividerHTML(el.styles || {});

    } else if (el.type === 'form') {
        const fs = el.styles || {};
        const fields = getFormFields(fs);
        const formAlign = ['left','center','right'].includes(fs.alignment) ? fs.alignment : 'left';
        let formWidth = '100%';
        if (fs.width !== undefined && fs.width !== null && fs.width !== '' && fs.width !== 'auto') {
            const n = parseFloat(fs.width);
            if (Number.isFinite(n) && n > 0) {
                const unit = fs.widthUnit === '%' ? '%' : 'px';
                const safe = unit === '%' ? Math.min(100, Math.max(1, n)) : Math.min(2000, Math.max(160, n));
                formWidth = `${safe}${unit}`;
            }
        }
        const cols = String(fs.columns || '1') === '2' ? 2 : 1;
        const inputBorderWidth = Math.min(10, Math.max(0, parseFloat(fs.inputBorderWidth ?? 1) || 0));
        const cardBorderWidth = Math.min(10, Math.max(0, parseFloat(fs.cardBorderWidth ?? 0) || 0));
        const cardShadow = fs.shadow ? `${parseFloat(fs.shadowX ?? 0) || 0}px ${parseFloat(fs.shadowY ?? 8) || 0}px ${Math.max(0, parseFloat(fs.shadowBlur ?? 24) || 0)}px ${parseFloat(fs.shadowSpread ?? 0) || 0}px ${formColorValid(fs.shadowColor,'rgba(0,0,0,.12)')}` : 'none';
        const inputStyle = [
            `background:${formColorValid(fs.inputBg,'#ffffff')}`,
            `color:${formColorValid(fs.textColor,'#111827')}`,
            `border:${inputBorderWidth}px solid ${formColorValid(fs.inputBorderColor,'#d1d5db')}`,
            `border-radius:${Math.max(0, parseFloat(fs.inputRadius ?? 8) || 0)}px`,
            `padding:${Math.max(2, parseFloat(fs.inputPaddingY ?? 11) || 11)}px ${Math.max(4, parseFloat(fs.inputPaddingX ?? 13) || 13)}px`,
            `font-size:${Math.max(8, parseFloat(fs.inputFontSize ?? 14) || 14)}px`
        ].join(';');
        const fieldHTML = fields.map((f, idx) => {
            const label = escapeHtml(f.label);
            const fieldWidth = Math.min(100, Math.max(25, parseFloat(f.width ?? 100) || 100));
            const fieldAlign = ['left','center','right'].includes(f.fieldAlign) ? f.fieldAlign : 'left';
            let fieldLayoutStyle = '';
            if (cols === 2) {
                fieldLayoutStyle = fieldWidth <= 50 ? 'grid-column:span 1;' : 'grid-column:1 / -1;';
            } else {
                const justify = fieldAlign === 'center' ? 'center' : fieldAlign === 'right' ? 'end' : 'start';
                fieldLayoutStyle = `width:${fieldWidth}%;justify-self:${justify};`;
            }
            const fieldWrap = (inner) => `<div class="sbc-form-field" style="${fieldLayoutStyle}">${inner}</div>`;
            const placeholder = escapeAttr(f.placeholder);
            const req = f.required ? ' required' : '';
            const fieldId = `sbc-form-${el.id}-${idx}`;
            const common = `id="${fieldId}" style="${inputStyle}"`;
            const options = (f.options?.length ? f.options : ['Opção 1','Opção 2']);

            if (f.type === 'hidden') {
                return `<input type="hidden" ${common} value="${escapeAttr(f.value || '')}">`;
            }
            if (f.type === 'textarea') {
                return fieldWrap(`<label for="${fieldId}">${label}${f.required ? ' <span style="color:#ef4444">*</span>' : ''}</label><textarea ${common} placeholder="${placeholder}"${req}></textarea>`);
            }
            if (f.type === 'select') {
                const optionHTML = options.map(o => `<option value="${escapeAttr(o)}">${escapeHtml(o)}</option>`).join('');
                return fieldWrap(`<label for="${fieldId}">${label}${f.required ? ' <span style="color:#ef4444">*</span>' : ''}</label><select ${common}${req}><option value="">Selecione...</option>${optionHTML}</select>`);
            }
            if (f.type === 'radio' || f.type === 'checkbox') {
                const radioName = `sbc-form-${el.id}-${idx}`;
                const choiceHTML = options.map((o, oi) => {
                    const choiceId = `${radioName}-${oi}`;
                    const inputType = f.type === 'radio' ? 'radio' : 'checkbox';
                    const choiceReq = f.required && oi === 0 ? ' required' : '';
                    return `<label for="${choiceId}" style="display:flex;align-items:center;gap:8px;font-size:${Math.max(8, parseFloat(fs.inputFontSize ?? 14) || 14)}px;color:${formColorValid(fs.textColor,'#111827')};font-weight:400;text-transform:none;letter-spacing:0;line-height:1.35;margin:0 0 7px;cursor:pointer;"><input id="${choiceId}" type="${inputType}" name="${radioName}" value="${escapeAttr(o)}"${choiceReq}> <span>${escapeHtml(o)}</span></label>`;
                }).join('');
                return fieldWrap(`<label>${label}${f.required ? ' <span style="color:#ef4444">*</span>' : ''}</label><div>${choiceHTML}</div>`);
            }
            if (f.type === 'file') {
                return fieldWrap(`<label for="${fieldId}">${label}${f.required ? ' <span style="color:#ef4444">*</span>' : ''}</label><input type="file" ${common}${req}>`);
            }
            const typeMap = { cpf:'text', cnpj:'text', cep:'text' };
            const inputType = typeMap[f.type] || f.type;
            const specialAttrs = f.type === 'cpf' ? ' inputmode="numeric" maxlength="14"' : (f.type === 'cnpj' ? ' inputmode="numeric" maxlength="18"' : (f.type === 'cep' ? ' inputmode="numeric" maxlength="9"' : ''));
            const modeAttrs = f.type === 'number' ? ' step="any"' : '';
            return fieldWrap(`<label for="${fieldId}">${label}${f.required ? ' <span style="color:#ef4444">*</span>' : ''}</label><input ${common} type="${escapeAttr(inputType)}" placeholder="${placeholder}"${specialAttrs}${modeAttrs}${req}>`);
        }).join('');
        const cardStyle = [
            `--form-label-color:${formColorValid(fs.labelColor,'#374151')}`,
            `--form-text-color:${formColorValid(fs.textColor,'#111827')}`,
            `--form-placeholder-color:${formColorValid(fs.placeholderColor,'#9ca3af')}`,
            `--form-input-bg:${formColorValid(fs.inputBg,'#ffffff')}`,
            `background:${formColorValid(fs.cardBg,'#ffffff')}`,
            `border-style:${['none','solid','dashed','dotted','double'].includes(fs.cardBorderStyle) ? fs.cardBorderStyle : 'none'}`,
            `border-width:${cardBorderWidth}px`,
            `border-color:${formColorValid(fs.cardBorderColor,'#e5e7eb')}`,
            `border-radius:${Math.max(0, parseFloat(fs.cardRadius ?? 12) || 0)}px`,
            `padding:${Math.max(0, parseFloat(fs.cardPadding ?? 24) || 0)}px`,
            `box-shadow:${cardShadow}`
        ].join(';');
        const buttonStyle = [
            `background:${formColorValid(fs.buttonBg,'#3b82f6')}`,
            `color:${formColorValid(fs.buttonTextColor,'#ffffff')}`,
            `border:0`,
            `border-radius:${Math.max(0, parseFloat(fs.buttonRadius ?? 8) || 0)}px`,
            `padding:${Math.max(2, parseFloat(fs.buttonPaddingY ?? 12) || 12)}px ${Math.max(4, parseFloat(fs.buttonPaddingX ?? 20) || 20)}px`,
            `font-size:${Math.max(8, parseFloat(fs.buttonFontSize ?? 15) || 15)}px`,
            `width:${fs.buttonFullWidth ? '100%' : 'auto'}`
        ].join(';');
        contentHTML = `<div class="sbc-form-shell" data-align="${formAlign}"><form class="sbc-form-card" style="width:${formWidth};${cardStyle}" onsubmit="return false;">` +
            `<h3 class="sbc-form-title" style="color:${formColorValid(fs.titleColor,'#111827')};font-size:${Math.max(10, parseFloat(fs.titleFontSize ?? 26) || 26)}px;">${escapeHtml(fs.formTitle || 'Fale conosco')}</h3>` +
            `<p class="sbc-form-description" style="color:${formColorValid(fs.descriptionColor,'#6b7280')};font-size:${Math.max(10, parseFloat(fs.descriptionFontSize ?? 14) || 14)}px;">${escapeHtml(fs.formDescription || '')}</p>` +
            `<div class="sbc-form-grid" style="grid-template-columns:repeat(${cols},minmax(0,1fr));">` +
            fieldHTML +
            `</div>` +
            `<button type="submit" class="sbc-form-submit" style="${buttonStyle}">${escapeHtml(fs.buttonText || 'Enviar mensagem')}</button>` +
            `</form></div>`;
    } else if (el.type === 'spacer') {
        const ss = el.styles || {};
        const canvasMode = getResponsiveDeviceMode();
        const rawHeight = canvasMode === 'mobile' ? (ss.heightMobile ?? ss.height ?? 30) : (canvasMode === 'tablet' ? (ss.heightTablet ?? ss.height ?? 40) : (ss.height ?? 50));
        const spacerHeight = Math.max(0, Math.min(1000, parseFloat(rawHeight) || 0));
        contentHTML = `<div class="sbc-spacer-inner" style="display:block;width:100%;max-width:100%;height:${spacerHeight}px;margin:0;padding:0;box-sizing:border-box;"></div>`;
    } else if (el.type === 'placeholder') {
        contentHTML = `<div style="padding:20px;text-align:center;color:#999;">${el.content}</div>`;
    } else if (el.type === 'faq') {
        contentHTML = buildFAQHTML(el);
    } else if (el.type === 'testimonials') {
        contentHTML = buildTestimonialsHTML(el);
    } else if (el.type === 'features') {
        contentHTML = buildFeaturesHTML(el);
    } else if (el.type === 'pricing') {
        contentHTML = buildPricingHTML(el);
    } else if (el.type === 'gallery') {
        contentHTML = buildGalleryHTML(el);
    } else if (el.type === 'carousel') {
        contentHTML = buildCarouselHTML(el);
    } else if (el.type === 'counters') {
        contentHTML = buildCountersHTML(el);
    } else if (el.type === 'features') {
        contentHTML = buildFeaturesHTML(el);
    } else if (el.type === 'team') {
        contentHTML = buildTeamHTML(el);
    } else if (el.type === 'timeline') {
        contentHTML = buildTimelineHTML(el);
    } else if (el.type === 'tabs') {
        contentHTML = buildTabsHTML(el);
    } else if (el.type === 'accordion') {
        contentHTML = buildAccordionHTML(el);
    } else if (el.type === 'progress') {
        contentHTML = buildProgressHTML(el);
    } else if (el.type === 'newsletter') {
        contentHTML = buildNewsletterHTML(el);
    } else if (el.type === 'hero') {
        contentHTML = buildHeroHTML(el);
    } else if (el.type === 'navbar') {
        contentHTML = buildNavbarHTML(el);
    } else if (el.type === 'footer') {
        contentHTML = buildFooterHTML(el);
    } else if (el.type === 'logo') {
        const ls = el.styles || {};
        const logoType = ['image','text'].includes(ls.logoType) ? ls.logoType : 'image';
        const height = Math.max(18, Math.min(180, parseFloat(ls.height) || 54));
        const maxWidth = Math.max(60, Math.min(800, parseFloat(ls.maxWidth) || 260));
        const align = ['left','center','right'].includes(ls.alignment) ? ls.alignment : 'left';
        const logoOpacity = Math.max(0.1, Math.min(1, parseFloat(ls.opacity) || 1));
        const radius = Math.max(0, Math.min(80, parseFloat(ls.radius) || 0));
        const shadow = ls.shadow ? `0 ${Number(ls.shadowY ?? 4)}px ${Number(ls.shadowBlur ?? 12)}px ${Number(ls.shadowSpread ?? 0)}px ${escapeAttr(ls.shadowColor || 'rgba(0,0,0,.18)')}` : 'none';
        const inner = logoType === 'image' && ls.imageUrl
            ? `<img src="${escapeAttr(ls.imageUrl)}" alt="${escapeAttr(ls.alt || 'Logo')}" style="display:block;max-width:100%;width:auto;height:${height}px;object-fit:contain;border-radius:${radius}px;opacity:${logoOpacity};box-shadow:${shadow};">`
            : `<span style="display:inline-block;max-width:100%;color:${escapeAttr(ls.textColor || '#111827')};font:${Math.max(8,parseFloat(ls.fontWeight)||800)} ${Math.max(10,parseFloat(ls.fontSize)||32)}px/1.1 Poppins,Inter,Arial,sans-serif;letter-spacing:${parseFloat(ls.letterSpacing)||0}px;text-transform:${escapeAttr(ls.textTransform || 'none')};opacity:${logoOpacity};">${escapeHtml(ls.text ?? '')}</span>`;
        const linked = ls.link ? `<a href="${escapeAttr(ls.link)}" target="${ls.targetBlank ? '_blank' : '_self'}" ${ls.targetBlank ? 'rel="noopener noreferrer"' : ''} style="display:inline-block;text-decoration:none;max-width:100%;">${inner}</a>` : inner;
        contentHTML = `<div class="sbc-logo-shell" data-logo-widget="true" style="width:100%;max-width:100%;display:flex;justify-content:${align==='center'?'center':(align==='right'?'flex-end':'flex-start')};box-sizing:border-box;cursor:pointer;"><div style="max-width:${maxWidth}px;line-height:0;">${linked}</div></div>`;
    }
    
    if (contentHTML) {
        const contentDiv = document.createElement('div');
        contentDiv.className = 'el-content';

        const textAlign = ['left','center','right','justify'].includes(el.styles?.textAlign)
            ? el.styles.textAlign
            : 'left';

        if (el.type === 'heading' || el.type === 'text') {
            contentDiv.style.width = '100%';
            contentDiv.style.maxWidth = '100%';
            contentDiv.style.minWidth = '0';
            contentDiv.style.display = 'block';
            contentDiv.style.boxSizing = 'border-box';
            contentDiv.style.setProperty('text-align', textAlign, 'important');
        }

        contentDiv.innerHTML = contentHTML;
        if (el.type === 'testimonials') bindTestimonialsHover(contentDiv);
        if (el.type === 'team') bindTeamHover(contentDiv);
        if (el.type === 'pricing') bindPricingHover(contentDiv);
        if (el.type === 'gallery') bindGalleryHover(contentDiv);
        if (el.type === 'carousel') bindCarousel(contentDiv);
        if (el.type === 'bannerAd') bindBannerAd(contentDiv);
        if (el.type === 'counters') bindCounters(contentDiv);
        if (el.type === 'features') bindFeaturesHover(contentDiv);
        if (el.type === 'navbar') {
            const navShell = contentDiv.querySelector('.sbc-navbar-shell');
            const navToggle = contentDiv.querySelector('.sbc-navbar-toggle');
            if (navShell && navToggle) {
                navToggle.addEventListener('click', (ev) => {
                    ev.preventDefault();
                    ev.stopPropagation();
                    const isOpen = navShell.classList.toggle('sbc-navbar-mobile-open');
                    navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    navToggle.setAttribute('aria-label', isOpen ? 'Fechar menu' : 'Abrir menu');
                    const icon = navToggle.querySelector('[data-navbar-icon]');
                    const label = navToggle.querySelector('.sbc-navbar-toggle-label');
                    if (icon) {
                        if (isOpen) { icon.className = 'fas fa-xmark'; icon.removeAttribute('data-navbar-icon'); }
                        else { const chosen = navToggle.dataset.navbarIcon || 'bars'; icon.className = 'fas fa-' + chosen; icon.setAttribute('data-navbar-icon', chosen); }
                    }
                    if (label && navToggle.dataset.navbarButtonType === 'text') label.textContent = isOpen ? 'Fechar' : 'Menu';
                });
            }
        }

    
    if (el.type === 'spacer') {
            const mode = getResponsiveDeviceMode();
            const visibleKey = mode === 'mobile' ? 'visibleMobile' : (mode === 'tablet' ? 'visibleTablet' : 'visibleDesktop');
            if (el.styles?.[visibleKey] === false) div.style.display = 'none';
        }

        if (el.type === 'heading' || el.type === 'text') {
            const textNode = contentDiv.querySelector('h2, p');
            if (textNode) {
                textNode.style.display = 'block';
                textNode.style.width = '100%';
                textNode.style.maxWidth = '100%';
                textNode.style.marginLeft = '0';
                textNode.style.marginRight = '0';
                textNode.style.boxSizing = 'border-box';
                textNode.style.setProperty('text-align', textAlign, 'important');
            }
        }

        div.appendChild(contentDiv);
    }

    if (el.type === 'column' && (!el.children || el.children.length === 0)) {
        const placeholder = document.createElement('div');
        placeholder.className = 'column-drop-placeholder';
        placeholder.textContent = 'Arraste widgets aqui';
        div.appendChild(placeholder);
    }

    if (el.children && el.children.length > 0) {
        const container = document.createElement('div');
        container.className = el.type === 'section' ? 'columns-container' : 'widgets-container';
        el.children.forEach(child => {
            container.appendChild(createDOMElement(child));
        });
        div.appendChild(container);
    }

    markEditableParts(div, el);
    applyPartResponsiveStyles(div, el, getResponsiveDeviceMode());
    if (el.type === 'features') {
        const __m = getResponsiveDeviceMode();
        const __r = el.styles?.responsive?.[__m] || {};
        const __t = __r.titleFontSize ?? el.styles?.parts?.['features.title']?.responsive?.[__m]?.fontSize;
        const __d = __r.descriptionFontSize ?? el.styles?.parts?.['features.description']?.responsive?.[__m]?.fontSize;
        const __tl = __r.titleLineHeight ?? el.styles?.parts?.['features.title']?.responsive?.[__m]?.lineHeight;
        const __dl = __r.descriptionLineHeight ?? el.styles?.parts?.['features.description']?.responsive?.[__m]?.lineHeight;
        const __headTitle = div.querySelector('.sbc-features-shell > header > div:first-child');
        const __headDesc = div.querySelector('.sbc-features-shell > header > div:nth-child(2)');
        if (__headTitle && __t !== undefined && __t !== '') __headTitle.style.setProperty('font-size', `${parseFloat(__t)||28}px`, 'important');
        if (__headDesc && __d !== undefined && __d !== '') __headDesc.style.setProperty('font-size', `${parseFloat(__d)||14}px`, 'important');
        if (__headTitle && __tl !== undefined && __tl !== '') __headTitle.style.setProperty('line-height', String(__tl), 'important');
        if (__headDesc && __dl !== undefined && __dl !== '') __headDesc.style.setProperty('line-height', String(__dl), 'important');
    }
    return div;
}

function addWidgetToContainer(widgetType, containerId) {
    normalizeBeforeCanvasRender();
    // Regra estrutural: widget nunca é filho direto de Section.
    // Se qualquer mecanismo antigo passar o id da Section, convertemos para
    // a primeira Column/Container antes de inserir.
    const requestedTarget = findElementById(pageContent, containerId);
    if (requestedTarget?.type === 'section') {
        let child = (requestedTarget.children || []).find(item => ['column','container'].includes(item.type));
        if (!child) {
            child = {
                id: 'el-' + (Date.now() + 1) + '-' + Math.random().toString(36).slice(2,8),
                type: 'column', content: '',
                styles: { padding: '15px', flex: '1 1 0%' },
                children: []
            };
            requestedTarget.children = requestedTarget.children || [];
            requestedTarget.children.push(child);
        }
        containerId = child.id;
    }
    if (widgetDropLock) return;
    widgetDropLock = true;
    setTimeout(() => { widgetDropLock = false; }, 180);

    const target = findElementById(pageContent, containerId);

    if (!target) {
        widgetDropLock = false;
        showNotification('Destino não encontrado!', 'error');
        return;
    }

    let finalTargetId = containerId;

    if (target.type === 'section') {
        let firstContainer = (target.children || []).find(item => ['column', 'container'].includes(item.type));

        if (!firstContainer) {
            firstContainer = {
                id: 'el-' + (Date.now() + 1),
                type: 'column',
                content: '',
                styles: { padding: '15px', flex: '1 1 0%' },
                children: []
            };
            target.children = target.children || [];
            target.children.push(firstContainer);
        }

        finalTargetId = firstContainer.id;
    } else if (!isDropContainer(target.type)) {
        showNotification('Solte o widget dentro de uma seção, coluna ou container.', 'error');
        return;
    }

    saveState();

    const newWidget = {
        id: 'el-' + Date.now(),
        type: widgetType,
        content: getDefaultContent(widgetType),
        styles: getDefaultStyles(widgetType),
        children: []
    };

    function addToContainer(elements, targetId) {
        for (const el of elements) {
            if (el.id === targetId) {
                if (!el.children) el.children = [];
                el.children.push(newWidget);
                return true;
            }
            if (el.children && addToContainer(el.children, targetId)) return true;
        }
        return false;
    }

    if (!addToContainer(pageContent, finalTargetId)) {
        showNotification('Não foi possível inserir o widget no destino.', 'error');
        return;
    }

    selectedElementId = newWidget.id;
    selectedInsertTargetId = finalTargetId;
    renderCanvas();
    updateProperties(newWidget.id);
    saveData();
    showNotification(`${widgetType} adicionado!`);
}

function moveElementToContainer(sourceId, targetContainerId) {
    const source = findElementById(pageContent, sourceId);
    const target = findElementById(pageContent, targetContainerId);

    if (!source) {
        showNotification('Elemento não encontrado!', 'error');
        return;
    }
    if (!target || !isDropContainer(target.type)) {
        showNotification('Solte dentro de uma coluna ou container!', 'error');
        return;
    }
    if (sourceId === targetContainerId || isDescendantOf(sourceId, targetContainerId)) {
        showNotification('Não é possível mover um elemento para dentro dele mesmo!', 'error');
        return;
    }

    saveState();

    let sourceElement = null;
    function findAndRemove(elements, id) {
        for (let i = 0; i < elements.length; i++) {
            if (elements[i].id === id) {
                sourceElement = elements.splice(i, 1)[0];
                return true;
            }
            if (elements[i].children && findAndRemove(elements[i].children, id)) return true;
        }
        return false;
    }

    if (!findAndRemove(pageContent, sourceId) || !sourceElement) {
        showNotification('Não foi possível retirar o elemento da posição atual.', 'error');
        return;
    }

    const destination = findElementById(pageContent, targetContainerId);
    if (!destination || !isDropContainer(destination.type)) {
        // Segurança: se o destino deixou de existir, restaura o elemento na raiz.
        pageContent.push(sourceElement);
        showNotification('Destino inválido. O elemento foi mantido.', 'error');
        renderCanvas();
        return;
    }

    if (!destination.children) destination.children = [];
    destination.children.push(sourceElement);
    selectedElementId = sourceElement.id;
    selectedInsertTargetId = destination.id;
    renderCanvas();
    updateProperties(sourceElement.id);
    saveData();
    showNotification('Elemento movido com sucesso!');
}

function moveElementToRoot(sourceId) {
    saveState();
    
    let sourceElement = null;
    
    function findAndRemove(elements, id) {
        for (let i = 0; i < elements.length; i++) {
            if (elements[i].id === id) {
                sourceElement = elements.splice(i, 1)[0];
                return true;
            }
            if (elements[i].children && findAndRemove(elements[i].children, id)) {
                return true;
            }
        }
        return false;
    }
    
    findAndRemove(pageContent, sourceId);
    
    if (sourceElement) {
        pageContent.push(sourceElement);
        renderCanvas();
        saveData();
        showNotification('Elemento movido!');
    }
}

function selectElement(id) {
    selectedElementId = id;

    const selected = findElementById(pageContent, id);
    if (selected?.type === 'section') {
        const child = (selected.children || []).find(item => item.type === 'column' || item.type === 'container');
        selectedInsertTargetId = child ? child.id : selected.id;
    } else if (selected && (selected.type === 'column' || selected.type === 'container')) {
        selectedInsertTargetId = selected.id;
    } else {
        const parent = selected ? findParentOfId(pageContent, selected.id) : null;
        if (parent?.type === 'column' || parent?.type === 'container') {
            selectedInsertTargetId = parent.id;
        } else if (parent?.type === 'section') {
            const child = (parent.children || []).find(item => item.type === 'column' || item.type === 'container');
            selectedInsertTargetId = child ? child.id : parent.id;
        } else {
            selectedInsertTargetId = null;
        }
    }

    updateProperties(id);
    renderCanvas();
}

function deleteElement(id) {
    const target = findElementById(pageContent, id);
    if (!target) return;
    if (!confirm('Remover este elemento?')) return;

    saveState();

    let removed = false;
    function removeFromTree(elements, targetId) {
        const index = elements.findIndex(el => el.id === targetId);
        if (index !== -1) {
            elements.splice(index, 1);
            removed = true;
            return true;
        }
        for (const el of elements) {
            if (el.children && el.children.length && removeFromTree(el.children, targetId)) return true;
        }
        return false;
    }

    // Coluna é filha direta da seção: remover a coluna não remove a seção
    // nem as outras colunas. Se for a última, a seção fica vazia para receber
    // uma nova coluna/widget.
    if (target.type === 'column') {
        const parent = findParentOfId(pageContent, id);
        if (parent?.type === 'section' && Array.isArray(parent.children)) {
            const index = parent.children.findIndex(child => child.id === id && child.type === 'column');
            if (index !== -1) {
                parent.children.splice(index, 1);
                removed = true;
            }
        }
    } else {
        removeFromTree(pageContent, id);
    }

    if (!removed) {
        showNotification('Não foi possível remover o elemento.', 'error');
        return;
    }

    selectedElementId = null;
    selectedInsertTargetId = null;
    selectedPart = null;
    renderCanvas();
    updateProperties(null);
    saveData();
    showNotification(target.type === 'column' ? 'Coluna removida. A seção foi mantida.' : 'Elemento removido!');
}

function duplicateElement(id) {
    saveState();
    
    function findAndDuplicate(elements, targetId) {
        for (let i = 0; i < elements.length; i++) {
            if (elements[i].id === targetId) {
                const clone = JSON.parse(JSON.stringify(elements[i]));
                clone.id = 'el-' + Date.now();
                function regenerateIds(el) {
                    el.id = 'el-' + Date.now() + Math.random();
                    if (el.children) {
                        el.children.forEach(regenerateIds);
                    }
                }
                regenerateIds(clone);
                elements.splice(i + 1, 0, clone);
                return true;
            }
            if (elements[i].children && findAndDuplicate(elements[i].children, targetId)) return true;
        }
        return false;
    }
    
    findAndDuplicate(pageContent, id);
    renderCanvas();
    saveData();
    showNotification('Elemento duplicado!');
}

function saveState() {
    undoStack.push(JSON.parse(JSON.stringify(pageContent)));
    if (undoStack.length > 50) undoStack.shift();
    redoStack = [];
    updateUndoRedoButtons();
}

function undo() {
    if (undoStack.length <= 1) return;
    redoStack.push(JSON.parse(JSON.stringify(pageContent)));
    pageContent = undoStack.pop();
    selectedElementId = null;
    renderCanvas();
    updateProperties(null);
    saveData();
    updateUndoRedoButtons();
    showNotification('Desfeito!', 'info');
}

function redo() {
    if (redoStack.length === 0) return;
    undoStack.push(JSON.parse(JSON.stringify(pageContent)));
    pageContent = redoStack.pop();
    selectedElementId = null;
    renderCanvas();
    updateProperties(null);
    saveData();
    updateUndoRedoButtons();
    showNotification('Refeito!', 'info');
}

function updateUndoRedoButtons() {
    document.getElementById('btn-undo').disabled = undoStack.length <= 1;
    document.getElementById('btn-redo').disabled = redoStack.length === 0;
}

// Helper global para os seletores de cor usados pelas propriedades dos widgets.
// Aceita valores HEX e também valores CSS como rgba().
function colorInputGlobal(prop, fallback, id) {
    const el = findElementById(pageContent, id);
    const styles = el?.styles || {};
    const raw = String(styles[prop] || fallback || '#000000');
    const picker = /^#[0-9a-f]{6}$/i.test(raw) ? raw : (String(fallback).match(/^#[0-9a-f]{6}$/i) ? String(fallback) : '#000000');
    const safeId = escapeAttr(id);
    const safeProp = escapeAttr(prop);
    const safeValue = escapeAttr(raw);
    const safePicker = escapeAttr(picker);
    return `<div class="color-row"><input type="color" value="${safePicker}" oninput="updateStyle('${safeId}','${safeProp}',this.value)"><input type="text" class="prop-input" value="${safeValue}" onchange="updateStyle('${safeId}','${safeProp}',this.value)"></div>`;
}

// Compatibilidade com versões anteriores do editor: alguns patches antigos
// usavam o nome responsiveStyles. Mantemos um helper sem depender de uma variável
// global inexistente, evitando que um widget interrompa a renderização inteira.
function getElementResponsiveStyles(el, mode = getResponsiveDeviceMode()) {
    const styles = el?.styles || {};
    return styles.responsive?.[mode] || {};
}

function ensureResponsiveStyles(el) {
    el.styles = el.styles || {};
    el.styles.responsive = el.styles.responsive || {};
    ['desktop','tablet','mobile'].forEach(mode => { el.styles.responsive[mode] = el.styles.responsive[mode] || {}; });
    return el.styles.responsive;
}

/*
 * Tudo que for visual/layout pode ser independente por breakpoint.
 * Conteúdo e dados do widget continuam globais (texto, links, slides etc.).
 */
const RESPONSIVE_STYLE_PROPS = new Set([
    'width','widthUnit','height','slideHeight','maxWidth','maxHeight','minHeight',
    'margin','marginTop','marginRight','marginBottom','marginLeft',
    'padding','paddingTop','paddingRight','paddingBottom','paddingLeft','paddingX','paddingY',
    'gap','columnGap','rowGap','itemGap','itemSpacing',
    'fontFamily','fontSize','fontWeight','fontStyle','lineHeight','letterSpacing','textTransform','textDecoration',
    'textAlign','alignment','color','bgColor','bgType','gradient','gradientDirection','bgImage',
    'opacity','brightness','contrast','grayscale','saturate',
    'borderWidth','borderStyle','borderColor','borderRadius','borderRadiusTL','borderRadiusTR','borderRadiusBL','borderRadiusBR',
    'boxShadow','shadow','shadowX','shadowY','shadowBlur','shadowSpread','shadowColor',
    'textShadow','textShadowX','textShadowY','textShadowBlur','textShadowColor',
    'display','position','top','right','bottom','left','zIndex','overflow',
    'alignItems','alignSelf','justifyContent','justifySelf','flexDirection','flexWrap','flex','gridTemplateColumns',
    'objectFit','objectPosition','aspectRatio',
    'iconSize','iconGap','iconMarginTop','iconMarginBottom','iconPadding','iconRadius','iconOpacity',
    'iconBoxSize','iconPosition','iconBorderWidth','iconBorderStyle',
    'buttonFontSize','buttonHeight','buttonPaddingX','buttonPaddingY','buttonRadius','buttonFullWidth',
    'titleFontSize','titleWeight','descriptionFontSize','descriptionLineHeight','textFontSize','labelFontSize',
    'nameFontSize','roleFontSize','answerFontSize','answerLineHeight','questionFontSize','numberFontSize','numberWeight','valueFontSize',
    'cardPadding','cardRadius','cardTextAlign','cardBorderWidth','cardBorderStyle',
    'inputBorderWidth','inputFontSize','inputHeight','inputPaddingX','inputPaddingY','inputRadius',
    'navBorderWidth','navPaddingX','navPaddingY','navRadius','logoHeight','mobileButtonHeight','mobileButtonRadius','mobileButtonSize',
    'mobileButtonWidth','mobileItemAlign','mobileItemGap','mobileItemPaddingY','mobilePosition','sectionPaddingY','sectionRadius',
    'barHeight','thickness','lineWidth','lineStyle','radius','contentWidth','contentAlign','contentBg',
    'tabActiveColor','tabColor','tabStyle','hoverLift','hoverScale','hoverShadow','hoverTransform','hoverTransition'
]);

function isResponsiveStyleProp(prop) {
    return RESPONSIVE_STYLE_PROPS.has(String(prop));
}

function getResponsiveStyleValue(el, prop, mode = getResponsiveDeviceMode()) {
    const styles = el?.styles || {}, responsive = styles.responsive || {};
    if (responsive[mode] && Object.prototype.hasOwnProperty.call(responsive[mode], prop)) return responsive[mode][prop];
    return styles[prop] ?? '';
}

function normalizeResponsiveCssValue(prop, value) {
    if (value === null || value === undefined) return '';
    const v = String(value).trim();
    if (!v) return '';
    // Largura responsiva é armazenada sem unidade. A unidade fica em widthUnit.
    // Isso evita o bug em que 40 + % era salvo como 40px e depois ignorava %.
    if (prop === 'width') {
        const n = parseFloat(v.replace(',', '.'));
        return Number.isFinite(n) ? String(n) : '';
    }
    const pxProps = ['height','slideHeight','maxWidth','maxHeight','minHeight','marginTop','marginRight','marginBottom','marginLeft','paddingTop','paddingRight','paddingBottom','paddingLeft','borderRadius','borderRadiusTL','borderRadiusTR','borderRadiusBL','borderRadiusBR','fontSize','letterSpacing','iconSize','iconGap','iconMarginTop','iconMarginBottom','iconPadding','iconRadius','iconBoxSize','buttonFontSize','buttonHeight','buttonPaddingX','buttonPaddingY','buttonRadius','titleFontSize','descriptionFontSize','textFontSize','labelFontSize','nameFontSize','roleFontSize','answerFontSize','answerLineHeight','questionFontSize','numberFontSize','valueFontSize','inputBorderWidth','inputFontSize','inputHeight','inputPaddingX','inputPaddingY','inputRadius','navBorderWidth','navPaddingX','navPaddingY','navRadius','logoHeight','mobileButtonHeight','mobileButtonRadius','mobileButtonSize','mobileButtonWidth','mobileItemGap','mobileItemPaddingY','sectionPaddingY','sectionRadius','barHeight','thickness','lineWidth','radius','cardPadding','cardRadius'];
    if (pxProps.includes(prop) && /^-?\d+(?:[.,]\d+)?$/.test(v)) return v.replace(',', '.') + 'px';
    return v;
}

function setResponsiveStyleLive(id, prop, value, mode = getResponsiveDeviceMode()) {
    const el = findElementById(pageContent, id); if (!el) return;
    const r = ensureResponsiveStyles(el);
    const v = normalizeResponsiveCssValue(prop, value);
    if (v === '') delete r[mode][prop]; else r[mode][prop] = v;
    // Atualiza somente o canvas. O painel não é reconstruído enquanto o usuário
    // digita, evitando perder o foco/cursor e fazendo o Preview acompanhar na hora.
    renderCanvas();
    saveData();
    selectedElementId = id;
}

function setResponsiveStyle(id, prop, value, mode = getResponsiveDeviceMode()) {
    const el = findElementById(pageContent, id); if (!el) return;
    saveState();
    const r = ensureResponsiveStyles(el);
    const v = normalizeResponsiveCssValue(prop, value);
    if (v === '') delete r[mode][prop]; else r[mode][prop] = v;
    renderCanvas(); saveData(); selectedElementId = id; updateProperties(id);
}

function clearResponsiveMode(id, mode = getResponsiveDeviceMode()) {
    const el = findElementById(pageContent, id); if (!el?.styles?.responsive?.[mode]) return;
    saveState(); el.styles.responsive[mode] = {}; renderCanvas(); saveData(); selectedElementId = id; updateProperties(id);
}

function renderResponsiveProperties(el) {
    const mode = getResponsiveDeviceMode();
    const value = prop => getResponsiveStyleValue(el, prop, mode);
    const escv = v => escapeAttr(v ?? '');
    const input = (label, prop, type='text', extra='') => `<div class="prop-group"><label class="prop-label">${label}</label><input ${extra} type="${type}" class="prop-input" value="${escv(value(prop))}" placeholder="Padrão" oninput="setResponsiveStyleLive('${escapeAttr(el.id)}','${prop}',this.value)" onblur="setResponsiveStyle('${escapeAttr(el.id)}','${prop}',this.value)"></div>`;
    const select = (label, prop, options) => `<div class="prop-group"><label class="prop-label">${label}</label><select class="prop-select" onchange="setResponsiveStyle('${escapeAttr(el.id)}','${prop}',this.value)">${options.map(([v,l]) => `<option value="${v}" ${String(value(prop))===v?'selected':''}>${l}</option>`).join('')}</select></div>`;
    const color = (label, prop, fallback='#000000') => {
        const raw = String(value(prop) || fallback);
        const picker = /^#[0-9a-f]{6}$/i.test(raw) ? raw : fallback;
        return `<div class="prop-group"><label class="prop-label">${label}</label><div class="color-row"><input type="color" value="${escv(picker)}" onchange="setResponsiveStyle('${escapeAttr(el.id)}','${prop}',this.value)"><input type="text" class="prop-input" value="${escv(raw)}" placeholder="Padrão" onchange="setResponsiveStyle('${escapeAttr(el.id)}','${prop}',this.value)"></div></div>`;
    };
    return `<div class="prop-section" style="border:1px solid var(--accent);border-radius:9px;margin-bottom:10px;">
        <div class="prop-title" style="color:var(--accent);"><i class="fas fa-mobile-alt"></i> Propriedades responsivas</div>
        <div style="display:flex;gap:5px;margin-bottom:8px;">${['desktop','tablet','mobile'].map(m => `<button type="button" onclick="setResponsive('${m}',this)" style="flex:1;height:31px;border:1px solid ${mode===m?'var(--accent)':'var(--border)'};background:${mode===m?'var(--accent-glow)':'var(--bg-tertiary)'};color:${mode===m?'var(--accent)':'var(--text-secondary)'};border-radius:6px;cursor:pointer;font-size:10px;"><i class="fas ${m==='desktop'?'fa-desktop':m==='tablet'?'fa-tablet-alt':'fa-mobile-alt'}"></i> ${m==='desktop'?'Desktop':m==='tablet'?'Tablet':'Mobile'}</button>`).join('')}</div>
        <div style="font-size:10px;color:var(--text-tertiary);margin-bottom:9px;">Todos os ajustes visuais abaixo pertencem somente ao <strong>${mode==='desktop'?'Desktop':mode==='tablet'?'Tablet':'Mobile'}</strong>. Se não houver ajuste específico, o dispositivo usa o valor padrão.</div>

        <div style="font-size:10px;color:var(--accent);font-weight:700;margin:8px 0 5px;">DIMENSÕES E ESPAÇAMENTO</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">${input('Largura','width')}${select('Unidade da largura','widthUnit',[['','Padrão'],['%','%'],['px','px']])}</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">${input(el.type==='carousel'?'Altura do slide':'Altura',el.type==='carousel'?'slideHeight':'height',el.type==='carousel'?'number':'text',el.type==='carousel'?'min="1" max="2000" step="1"':'')}${input('Largura máxima','maxWidth')}</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">${input('Altura máxima','maxHeight')}${input('Altura mínima','minHeight')}</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">${input('Margem superior','marginTop')}${input('Margem inferior','marginBottom')}</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">${input('Margem esquerda','marginLeft')}${input('Margem direita','marginRight')}</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">${input('Padding superior','paddingTop')}${input('Padding inferior','paddingBottom')}</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">${input('Padding esquerda','paddingLeft')}${input('Padding direita','paddingRight')}</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">${input('Gap','gap')}${input('Espaço entre colunas','columnGap')}</div>

        <div style="font-size:10px;color:var(--accent);font-weight:700;margin:10px 0 5px;">TIPOGRAFIA E ALINHAMENTO</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">${input('Tamanho da fonte','fontSize','number','min="1" step="0.1"')}${input('Peso','fontWeight','number','min="100" max="900" step="100"')}</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">${input('Altura da linha','lineHeight','number','step="0.1"')}${input('Espaçamento das letras','letterSpacing')}</div>
        ${select('Família da fonte','fontFamily',[['','Padrão'],...getAllFonts().map(f=>[f,f])])}
        ${select('Estilo da fonte','fontStyle',[['','Padrão'],['normal','Normal'],['italic','Itálico']])}
        ${select('Transformação','textTransform',[['','Padrão'],['none','Normal'],['uppercase','MAIÚSCULAS'],['lowercase','minúsculas'],['capitalize','Primeira letra']])}
        ${select('Decoração','textDecoration',[['','Padrão'],['none','Nenhuma'],['underline','Sublinhado'],['line-through','Riscado']])}
        ${select('Alinhamento do texto','textAlign',[['','Padrão'],['left','Esquerda'],['center','Centro'],['right','Direita'],['justify','Justificado']])}
        ${select('Alinhamento do elemento','alignment',[['','Padrão'],['left','Esquerda'],['center','Centro'],['right','Direita']])}
        ${color('Cor do texto','color','#212529')}

        <div style="font-size:10px;color:var(--accent);font-weight:700;margin:10px 0 5px;">FUNDO, BORDA E VISUAL</div>
        ${color('Cor de fundo','bgColor','#ffffff')}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">${input('Borda','borderWidth')}${select('Estilo da borda','borderStyle',[['','Padrão'],['none','Nenhuma'],['solid','Sólida'],['dashed','Tracejada'],['dotted','Pontilhada']])}</div>
        ${color('Cor da borda','borderColor','#dddddd')}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">${input('Raio da borda','borderRadius','number','min="0" step="1"')}${input('Opacidade','opacity','number','min="0" max="1" step="0.01"')}</div>
        ${color('Cor da sombra','shadowColor','#000000')}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">${input('Sombra X','shadowX','number','step="1"')}${input('Sombra Y','shadowY','number','step="1"')}</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">${input('Desfoque da sombra','shadowBlur','number','min="0" step="1"')}${input('Expansão da sombra','shadowSpread','number','step="1"')}</div>

        <div style="font-size:10px;color:var(--accent);font-weight:700;margin:10px 0 5px;">ÍCONE / IMAGEM / VÍDEO</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">${input('Tamanho do ícone','iconSize','number','min="1" step="1"')}${input('Espaço do ícone','iconGap','number','min="0" step="1"')}</div>
        ${select('Ajuste da imagem','objectFit',[['','Padrão'],['cover','Preencher'],['contain','Conter'],['fill','Esticar'],['none','Original']])}
        ${select('Posição da imagem','objectPosition',[['','Padrão'],['center','Centro'],['top','Topo'],['bottom','Baixo'],['left','Esquerda'],['right','Direita']])}

        <button type="button" onclick="clearResponsiveMode('${escapeAttr(el.id)}')" style="width:100%;height:32px;border:1px solid var(--border);background:var(--bg-tertiary);color:var(--text-secondary);border-radius:6px;cursor:pointer;font-size:10px;margin-top:8px;">Limpar ajustes desta tela</button>
    </div>`;
}

function applyElementResponsiveStyles(root, el, mode = getResponsiveDeviceMode()) {
    if (!root || !el) return;
    const r = el.styles?.responsive?.[mode];
    if (!r) return;
    const set = (node, prop, value) => { if (node && value !== undefined && value !== '') node.style.setProperty(prop, String(value), 'important'); };
    const map = {
        width:'width',height:'height',maxWidth:'max-width',maxHeight:'max-height',minHeight:'min-height',
        margin:'margin',marginTop:'margin-top',marginRight:'margin-right',marginBottom:'margin-bottom',marginLeft:'margin-left',
        padding:'padding',paddingTop:'padding-top',paddingRight:'padding-right',paddingBottom:'padding-bottom',paddingLeft:'padding-left',
        gap:'gap',columnGap:'column-gap',rowGap:'row-gap',
        fontFamily:'font-family',fontSize:'font-size',fontWeight:'font-weight',fontStyle:'font-style',lineHeight:'line-height',letterSpacing:'letter-spacing',
        textTransform:'text-transform',textDecoration:'text-decoration',textAlign:'text-align',color:'color',
        backgroundColor:'background-color',borderWidth:'border-width',borderStyle:'border-style',borderColor:'border-color',borderRadius:'border-radius',
        opacity:'opacity',boxShadow:'box-shadow',position:'position',top:'top',right:'right',bottom:'bottom',left:'left',zIndex:'z-index',overflow:'overflow',
        alignItems:'align-items',alignSelf:'align-self',justifyContent:'justify-content',justifySelf:'justify-self',flexDirection:'flex-direction',flexWrap:'flex-wrap',flex:'flex',
        objectFit:'object-fit',objectPosition:'object-position',aspectRatio:'aspect-ratio'
    };
    Object.entries(map).forEach(([k,css]) => { if (Object.prototype.hasOwnProperty.call(r,k)) set(root,css,r[k]); });

    // Alguns widgets usam nomes próprios em vez de CSS direto.
    if (Object.prototype.hasOwnProperty.call(r,'bgColor')) set(root,'background-color',r.bgColor);
    if (Object.prototype.hasOwnProperty.call(r,'alignment')) {
        const a = r.alignment;
        if (a === 'center') { set(root,'margin-left','auto'); set(root,'margin-right','auto'); }
        else if (a === 'right') { set(root,'margin-left','auto'); set(root,'margin-right','0'); }
        else if (a === 'left') { set(root,'margin-left','0'); set(root,'margin-right','auto'); }
    }
    if (Object.prototype.hasOwnProperty.call(r,'shadowColor') || Object.prototype.hasOwnProperty.call(r,'shadowX') || Object.prototype.hasOwnProperty.call(r,'shadowY') || Object.prototype.hasOwnProperty.call(r,'shadowBlur') || Object.prototype.hasOwnProperty.call(r,'shadowSpread')) {
        const base = el.styles || {};
        const color = r.shadowColor ?? base.shadowColor ?? 'rgba(0,0,0,.15)';
        const x = r.shadowX ?? base.shadowX ?? 0, y = r.shadowY ?? base.shadowY ?? 6, blur = r.shadowBlur ?? base.shadowBlur ?? 18, spread = r.shadowSpread ?? base.shadowSpread ?? 0;
        set(root,'box-shadow',`${x}px ${y}px ${blur}px ${spread}px ${color}`);
    }
    if (Object.prototype.hasOwnProperty.call(r,'textShadowColor') || Object.prototype.hasOwnProperty.call(r,'textShadowX') || Object.prototype.hasOwnProperty.call(r,'textShadowY') || Object.prototype.hasOwnProperty.call(r,'textShadowBlur')) {
        const base = el.styles || {};
        const color = r.textShadowColor ?? base.textShadowColor ?? 'rgba(0,0,0,.15)';
        const x = r.textShadowX ?? base.textShadowX ?? 0, y = r.textShadowY ?? base.textShadowY ?? 2, blur = r.textShadowBlur ?? base.textShadowBlur ?? 4;
        root.querySelectorAll('h1,h2,h3,h4,h5,h6,p,a,button,span,label').forEach(n=>set(n,'text-shadow',`${x}px ${y}px ${blur}px ${color}`));
    }

    if (el.type === 'carousel') {
        // O carrossel usa width + widthUnit. Nunca aplique somente o número,
        // porque 50 + % deve continuar sendo 50%, e não 50px.
        if (Object.prototype.hasOwnProperty.call(r,'width')) {
            const rawW = String(r.width ?? '').trim();
            const unit = r.widthUnit === 'px' ? 'px' : (r.widthUnit === '%' ? '%' : '');
            // Compatibilidade: versões anteriores gravavam o número responsivo
            // como "40px" mesmo quando a unidade escolhida era %. Aqui a unidade
            // selecionada pelo usuário sempre vence.
            const numericW = parseFloat(rawW.replace(',', '.'));
            let w = rawW;
            if (Number.isFinite(numericW) && unit) w = String(numericW) + unit;
            set(root,'width',w);
            if (!Object.prototype.hasOwnProperty.call(r,'maxWidth')) set(root,'max-width','100%');
        }
        if (Object.prototype.hasOwnProperty.call(r,'widthUnit') && !Object.prototype.hasOwnProperty.call(r,'width')) {
            // widthUnit sozinho não altera a largura; evita interferir no valor existente.
        }
        if (Object.prototype.hasOwnProperty.call(r,'textAlign')) set(root,'text-align',r.textAlign);
        if (Object.prototype.hasOwnProperty.call(r,'alignment')) {
            const a = r.alignment;
            set(root,'align-self', a === 'center' ? 'center' : (a === 'right' ? 'flex-end' : 'flex-start'));
            set(root,'margin-left', a === 'center' || a === 'right' ? 'auto' : '0');
            set(root,'margin-right', a === 'center' || a === 'left' ? 'auto' : '0');
        }
        const h = Object.prototype.hasOwnProperty.call(r,'slideHeight') ? r.slideHeight : (Object.prototype.hasOwnProperty.call(r,'height') ? r.height : '');
        if (h !== '') {
            set(root,'height',h);
            root.querySelectorAll('.sbc-carousel-frame,.sbc-carousel-viewport,.sbc-carousel-track,.sbc-carousel-slide').forEach(n => set(n,'height',h));
        }
        // Gap e demais propriedades do carrossel também respeitam o breakpoint.
        if (Object.prototype.hasOwnProperty.call(r,'gap')) {
            root.querySelectorAll('.sbc-carousel-track').forEach(n => set(n,'gap',r.gap));
        }
    }
    if (Object.prototype.hasOwnProperty.call(r,'fontSize')) root.querySelectorAll('h1,h2,h3,h4,h5,h6,p,a,button,span,label').forEach(n=>set(n,'font-size',r.fontSize));
    if (Object.prototype.hasOwnProperty.call(r,'lineHeight')) root.querySelectorAll('h1,h2,h3,h4,h5,h6,p,a,button,span,label').forEach(n=>set(n,'line-height',r.lineHeight));

    // Partes internas (título, descrição, cards, ícones etc.) também precisam
    // receber o breakpoint no Preview. Esta chamada é intencionalmente no final
    // para vencer estilos inline criados pelos builders dos widgets.
    applyPartResponsiveStyles(root, el, mode);
}

function updateProperties(id) {
    const container = document.getElementById('propertiesContent');
    if (!container) return;
    if (!id) {
        container.innerHTML = '<p style="color:var(--text-tertiary);text-align:center;padding:20px;font-size:12px;">Selecione um elemento</p>';
        return;
    }
    const el = findElementById(pageContent, id);
    if (!el) return;
    el.styles = el.styles || {};
    const responsiveMode = getResponsiveDeviceMode();
    const responsiveOverrides = el.styles.responsive?.[responsiveMode] || {};
    // Todas as propriedades visuais existentes no painel passam a mostrar
    // o valor do dispositivo atual, sem alterar os dados originais.
    const viewEl = { ...el, styles: { ...el.styles, ...responsiveOverrides, responsive: el.styles.responsive } };
    const esc = escapeAttr;
    let html = `
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-info-circle"></i> Informações</div>
            <div class="prop-group"><label class="prop-label">Tipo</label>
                <span style="padding:6px 10px;background:var(--accent-glow);border:1px solid var(--accent);border-radius:5px;font-weight:600;color:var(--accent);font-size:11px;">${escapeHtml(viewEl.type)}</span>
            </div>
        </div>`;

    html += renderInternalPartProperties(viewEl);
    html += renderResponsiveProperties(el);

    if (['heading','text'].includes(viewEl.type)) {
        const isHeading = viewEl.type === 'heading';
        const defaults = isHeading ? {size:'32', family:'Poppins', color:'#212529', weight:'700', line:'1.2'} : {size:'16', family:'Inter', color:'#666666', weight:'400', line:'1.6'};
        const currentFamily = viewEl.styles.fontFamily || defaults.family;
        html += `
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-font"></i> Conteúdo</div>
            <div class="prop-group"><label class="prop-label">Texto</label>
                <textarea class="prop-input" rows="3" onchange="updateStyle('${viewEl.id}','content',this.value)">${escapeHtml(viewEl.content || '')}</textarea>
            </div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-font"></i> Tipografia</div>
            <div class="prop-group"><label class="prop-label">Font Family</label>
                <select class="prop-select" onchange="updateStyle('${viewEl.id}','fontFamily',this.value)">
                    ${getAllFonts().map(f => `<option value="${esc(f)}" ${currentFamily === f ? 'selected' : ''}>${escapeHtml(f)}</option>`).join('')}
                </select>
            </div>
            <div class="prop-group">
                <label class="prop-label">Adicionar fonte baixada</label>
                <label style="display:block;padding:12px;border:1px dashed var(--border);border-radius:8px;text-align:center;cursor:pointer;background:var(--bg-tertiary);">
                    <i class="fas fa-upload" style="color:var(--accent);margin-right:6px;"></i> Enviar WOFF2, WOFF, TTF ou OTF
                    <input type="file" accept=".woff2,.woff,.ttf,.otf,font/woff2,font/woff,font/ttf,font/otf" style="display:none" onchange="handleFontUpload(this)">
                </label>
            </div>
            ${customFonts.length ? `<div class="prop-group"><label class="prop-label">Fontes adicionadas</label>${customFonts.map(f => `<div style="display:flex;align-items:center;justify-content:space-between;gap:8px;padding:7px 9px;margin-top:5px;background:var(--bg-tertiary);border:1px solid var(--border);border-radius:6px;font-family:'${esc(f.name)}';"><span>${escapeHtml(f.name)}</span><button type="button" onclick="removeCustomFont('${esc(f.id)}')" style="background:none;border:0;color:var(--error);cursor:pointer;">×</button></div>`).join('')}</div>` : ''}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                <div class="prop-group"><label class="prop-label">Tamanho (px)</label><input type="number" min="1" class="prop-input" value="${esc(viewEl.styles.fontSize || defaults.size)}" onchange="updateStyle('${viewEl.id}','fontSize',this.value)"></div>
                <div class="prop-group"><label class="prop-label">Peso</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','fontWeight',this.value)">${['300','400','500','600','700','800','900'].map(w => `<option value="${w}" ${(viewEl.styles.fontWeight || defaults.weight) === w ? 'selected' : ''}>${w}</option>`).join('')}</select></div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                <div class="prop-group"><label class="prop-label">Altura da linha</label><input type="number" step="0.1" min="0.5" class="prop-input" value="${esc(viewEl.styles.lineHeight || defaults.line)}" onchange="updateStyle('${viewEl.id}','lineHeight',this.value)"></div>
                <div class="prop-group"><label class="prop-label">Espaçamento (px)</label><input type="number" step="0.1" class="prop-input" value="${esc(viewEl.styles.letterSpacing || '0')}" onchange="updateStyle('${viewEl.id}','letterSpacing',this.value)"></div>
            </div>
            <div class="prop-group"><label class="prop-label">Estilo</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','fontStyle',this.value)"><option value="normal" ${(viewEl.styles.fontStyle || 'normal') === 'normal' ? 'selected' : ''}>Normal</option><option value="italic" ${viewEl.styles.fontStyle === 'italic' ? 'selected' : ''}>Itálico</option></select></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                <div class="prop-group"><label class="prop-label">Transformação</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','textTransform',this.value)"><option value="none" ${(viewEl.styles.textTransform || 'none') === 'none' ? 'selected' : ''}>Normal</option><option value="uppercase" ${viewEl.styles.textTransform === 'uppercase' ? 'selected' : ''}>MAIÚSCULAS</option><option value="lowercase" ${viewEl.styles.textTransform === 'lowercase' ? 'selected' : ''}>minúsculas</option><option value="capitalize" ${viewEl.styles.textTransform === 'capitalize' ? 'selected' : ''}>Primeira letra</option></select></div>
                <div class="prop-group"><label class="prop-label">Decoração</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','textDecoration',this.value)"><option value="none" ${(viewEl.styles.textDecoration || 'none') === 'none' ? 'selected' : ''}>Nenhuma</option><option value="underline" ${viewEl.styles.textDecoration === 'underline' ? 'selected' : ''}>Sublinhado</option><option value="line-through" ${viewEl.styles.textDecoration === 'line-through' ? 'selected' : ''}>Riscado</option></select></div>
            </div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Sombra do texto</span><input type="checkbox" ${viewEl.styles.textShadow ? 'checked' : ''} onchange="updateStyle('${viewEl.id}','textShadow',this.checked)"></label></div>
            ${viewEl.styles.textShadow ? `<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px;"><div><label class="prop-label">X</label><input type="number" class="prop-input" value="${esc(viewEl.styles.textShadowX ?? '0')}" onchange="updateStyle('${viewEl.id}','textShadowX',this.value)"></div><div><label class="prop-label">Y</label><input type="number" class="prop-input" value="${esc(viewEl.styles.textShadowY ?? (isHeading ? '2' : '1'))}" onchange="updateStyle('${viewEl.id}','textShadowY',this.value)"></div><div><label class="prop-label">Desfoque</label><input type="number" min="0" class="prop-input" value="${esc(viewEl.styles.textShadowBlur ?? (isHeading ? '8' : '4'))}" onchange="updateStyle('${viewEl.id}','textShadowBlur',this.value)"></div></div>
            <div class="prop-group"><label class="prop-label">Cor da sombra</label><div class="color-row"><input type="color" value="#000000" onchange="updateStyle('${viewEl.id}','textShadowColor',this.value)"><input type="text" value="${esc(viewEl.styles.textShadowColor || (isHeading ? 'rgba(0,0,0,0.15)' : 'rgba(0,0,0,0.12)'))}" onchange="updateStyle('${viewEl.id}','textShadowColor',this.value)"></div></div>` : ''}
            <div class="prop-group"><label class="prop-label">Cor</label><div class="color-row"><input type="color" value="${esc(viewEl.styles.color || defaults.color)}" onchange="updateStyle('${viewEl.id}','color',this.value)"><input type="text" value="${esc(viewEl.styles.color || defaults.color)}" onchange="updateStyle('${viewEl.id}','color',this.value)"></div></div>
            <div class="prop-group">
                <label class="prop-label">Posição do elemento</label>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:5px;">
                    ${[['left','Esquerda','fa-arrow-left'],['center','Centro','fa-arrows-left-right'],['right','Direita','fa-arrow-right']].map(([a,l,icon]) => `<button type="button" title="${l}" onclick="updateStyle('${viewEl.id}','alignment','${a}')" style="height:34px;border:1px solid ${(viewEl.styles.alignment || 'left') === a ? 'var(--accent)' : 'var(--border)'};background:${(viewEl.styles.alignment || 'left') === a ? 'var(--accent-glow)' : 'var(--bg-tertiary)'};color:${(viewEl.styles.alignment || 'left') === a ? 'var(--accent)' : 'var(--text-secondary)'};border-radius:6px;cursor:pointer;"><i class="fas ${icon}"></i></button>`).join('')}
                </div>
            </div>
            <div class="prop-group">
                <label class="prop-label">Alinhamento do texto</label>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:5px;">
                    ${[['left','Esquerda','fa-align-left'],['center','Centro','fa-align-center'],['right','Direita','fa-align-right']].map(([a,l,icon]) => `<button type="button" title="${l}" onclick="updateStyle('${viewEl.id}','textAlign','${a}')" style="height:34px;border:1px solid ${(viewEl.styles.textAlign || 'left') === a ? 'var(--accent)' : 'var(--border)'};background:${(viewEl.styles.textAlign || 'left') === a ? 'var(--accent-glow)' : 'var(--bg-tertiary)'};color:${(viewEl.styles.textAlign || 'left') === a ? 'var(--accent)' : 'var(--text-secondary)'};border-radius:6px;cursor:pointer;"><i class="fas ${icon}"></i></button>`).join('')}
                </div>
            </div>
        </div>`;
    }

    if (viewEl.type === 'form') {
        const s = viewEl.styles || {};
        const colorInput = (prop, fallback) => {
            const raw = s[prop] || fallback;
            const picker = /^#[0-9a-f]{6}$/i.test(String(raw)) ? raw : fallback;
            return `<div class="color-row"><input type="color" value="${esc(picker)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"><input type="text" value="${esc(raw)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"></div>`;
        };
        const alignButtons = [['left','Esq.','fa-align-left'],['center','Centro','fa-align-center'],['right','Dir.','fa-align-right']]
            .map(([a,l,i]) => `<button type="button" onclick="updateStyle('${viewEl.id}','alignment','${a}')" style="height:34px;border:1px solid ${(s.alignment||'left')===a?'var(--accent)':'var(--border)'};background:${(s.alignment||'left')===a?'var(--accent-glow)':'var(--bg-tertiary)'};color:var(--text-secondary);border-radius:6px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:4px;"><i class="fas ${i}"></i><span style="font-size:10px">${l}</span></button>`).join('');
        html += `
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-envelope"></i> Conteúdo</div>
            <div class="prop-group"><label class="prop-label">Título</label><input class="prop-input" value="${esc(s.formTitle || 'Fale conosco')}" onchange="updateStyle('${viewEl.id}','formTitle',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Descrição</label><textarea class="prop-input" style="min-height:70px;resize:vertical" onchange="updateStyle('${viewEl.id}','formDescription',this.value)">${escapeHtml(s.formDescription || '')}</textarea></div>
            <div class="prop-group"><label class="prop-label">Texto do botão</label><input class="prop-input" value="${esc(s.buttonText || 'Enviar mensagem')}" onchange="updateStyle('${viewEl.id}','buttonText',this.value)"></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-list-alt"></i> Campos do formulário</div>
            <div class="prop-group">
                <div style="font-size:10px;color:var(--text-tertiary);line-height:1.5;margin-bottom:8px;">Crie e edite os campos que o cliente verá no formulário. Você pode definir rótulo, tipo, placeholder e obrigatoriedade.</div>
                ${ensureFormFields(s).map((f,i) => {
                    const typeLabel = getFormTypeLabel(f.type);
                    return `<div class="sbc-form-field-editor-item" draggable="true" ondragstart="setupFormFieldDrag(event,'${viewEl.id}',${i})" ondragend="finishFormFieldDrag(event)" ondragover="handleFormFieldDragOver(event,'${viewEl.id}',${i})" ondragleave="handleFormFieldDragLeave(event)" ondrop="handleFormFieldDrop(event,'${viewEl.id}',${i})" style="border:1px solid var(--border);background:var(--bg-tertiary);border-radius:8px;padding:10px;margin-bottom:8px;transition:border-color .15s,box-shadow .15s,transform .15s;cursor:grab;">
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:6px;margin-bottom:7px;">
                            <div style="display:flex;align-items:center;gap:7px;min-width:0;">
                                <span title="Arraste para reorganizar" style="color:var(--text-tertiary);font-size:12px;cursor:grab;"><i class="fas fa-grip-vertical"></i></span>
                                <strong style="font-size:11px;color:var(--text-primary);">Campo ${i+1}</strong>
                                <span style="font-size:9px;color:var(--text-tertiary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${escapeHtml(f.label || getFormTypeLabel(f.type))}</span>
                            </div>
                            <button type="button" title="Excluir campo" onclick="event.stopPropagation();removeFormField('${viewEl.id}',${i})" style="border:0;background:transparent;color:var(--error);cursor:pointer;font-size:14px;flex:0 0 auto;">&times;</button>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">
                            <div class="prop-group" style="margin:0;"><label class="prop-label">Rótulo</label><input class="prop-input" value="${esc(f.label)}" onchange="updateFormField('${viewEl.id}',${i},'label',this.value)"></div>
                            <div class="prop-group" style="margin:0;"><label class="prop-label">Tipo</label><select class="prop-select" onchange="updateFormField('${viewEl.id}',${i},'type',this.value)">${FORM_FIELD_TYPES.map(t=>`<option value="${t.value}" ${f.type===t.value?'selected':''}>${t.label}</option>`).join('')}</select></div>
                        </div>
                        <div class="prop-group" style="margin-top:6px;"><label class="prop-label">Placeholder</label><input class="prop-input" value="${esc(f.placeholder || '')}" onchange="updateFormField('${viewEl.id}',${i},'placeholder',this.value)"></div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-top:7px;">
                            <div class="prop-group" style="margin:0;"><label class="prop-label">Largura</label><select class="prop-select" onchange="updateFormField('${viewEl.id}',${i},'width',this.value)"><option value="25" ${String(f.width||'100')==='25'?'selected':''}>25%</option><option value="50" ${String(f.width||'100')==='50'?'selected':''}>50%</option><option value="75" ${String(f.width||'100')==='75'?'selected':''}>75%</option><option value="100" ${String(f.width||'100')==='100'?'selected':''}>100%</option></select></div>
                            <div class="prop-group" style="margin:0;"><label class="prop-label">Alinhamento</label><select class="prop-select" onchange="updateFormField('${viewEl.id}',${i},'fieldAlign',this.value)"><option value="left" ${String(f.fieldAlign||'left')==='left'?'selected':''}>Esquerda</option><option value="center" ${String(f.fieldAlign||'left')==='center'?'selected':''}>Centro</option><option value="right" ${String(f.fieldAlign||'left')==='right'?'selected':''}>Direita</option></select></div>
                        </div>
                        <label style="display:flex;align-items:center;justify-content:space-between;margin-top:7px;"><span style="font-size:10px;color:var(--text-secondary);">Campo obrigatório</span><input type="checkbox" ${f.required ? 'checked' : ''} onchange="updateFormField('${viewEl.id}',${i},'required',this.checked)"></label>
                        ${['select','radio','checkbox'].includes(f.type) ? `<div class="prop-group" style="margin-top:7px;"><label class="prop-label">Opções (uma por linha)</label><textarea class="prop-input" rows="3" style="min-height:64px;resize:vertical" onchange="updateFormFieldOptions('${viewEl.id}',${i},this.value)">${escapeHtml((f.options || []).join('\n'))}</textarea></div>` : ''}
                    </div>`;
                }).join('')}
                <div style="margin-top:10px;padding-top:10px;border-top:1px solid var(--border);">
                    <label class="prop-label">Adicionar novo campo</label>
                    <div style="display:grid;grid-template-columns:1fr auto;gap:6px;">
                        <select class="prop-select" id="form-add-type-${viewEl.id}">${FORM_FIELD_TYPES.map(t=>`<option value="${t.value}">${t.label}</option>`).join('')}</select>
                        <button type="button" title="Adicionar campo" onclick="addFormField('${viewEl.id}', document.getElementById('form-add-type-${viewEl.id}').value)" style="width:42px;height:36px;border:1px solid var(--accent);background:var(--accent-glow);color:var(--accent);border-radius:7px;cursor:pointer;font-size:14px;"><i class="fas fa-plus"></i></button>
                    </div>
                    <div style="font-size:9px;color:var(--text-tertiary);line-height:1.4;margin-top:5px;">Escolha qualquer tipo e adicione ao formulário. Até 20 campos.</div>
                </div>
            </div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-ruler-horizontal"></i> Layout</div>
            <div class="prop-group"><label class="prop-label">Largura</label>
                <div style="display:grid;grid-template-columns:1fr 90px;gap:6px">
                    <input type="number" min="1" max="2000" class="prop-input" value="${esc(s.width ?? '100')}" onchange="updateStyle('${viewEl.id}','width',this.value)">
                    <select class="prop-select" onchange="updateStyle('${viewEl.id}','widthUnit',this.value)">
                        <option value="%" ${s.widthUnit !== 'px' ? 'selected' : ''}>%</option>
                        <option value="px" ${s.widthUnit === 'px' ? 'selected' : ''}>px</option>
                    </select>
                </div>
            </div>
            <div class="prop-group"><label class="prop-label">Alinhamento</label><div style="display:grid;grid-template-columns:repeat(3,1fr);gap:5px">${alignButtons}</div></div>
            <div class="prop-group"><label class="prop-label">Organização dos campos</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','columns',this.value)"><option value="1" ${String(s.columns||'1')==='1'?'selected':''}>1 coluna — largura individual</option><option value="2" ${String(s.columns)==='2'?'selected':''}>2 colunas — lado a lado</option></select></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-heading"></i> Título e descrição</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Título (px)</label><input type="number" min="10" max="100" class="prop-input" value="${esc(s.titleFontSize ?? 26)}" oninput="updateStyleLive('${viewEl.id}','titleFontSize',this.value)" onblur="updateStyle('${viewEl.id}','titleFontSize',this.value)"></div><div class="prop-group"><label class="prop-label">Descrição (px)</label><input type="number" min="10" max="40" class="prop-input" value="${esc(s.descriptionFontSize ?? 14)}" oninput="updateStyleLive('${viewEl.id}','descriptionFontSize',this.value)" onblur="updateStyle('${viewEl.id}','descriptionFontSize',this.value)"></div></div>
            <div class="prop-group"><label class="prop-label">Cor do título</label>${colorInput('titleColor','#111827')}</div>
            <div class="prop-group"><label class="prop-label">Cor da descrição</label>${colorInput('descriptionColor','#6b7280')}</div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-keyboard"></i> Campos</div>
            <div class="prop-group"><label class="prop-label">Cor dos labels</label>${colorInput('labelColor','#374151')}</div>
            <div class="prop-group"><label class="prop-label">Cor do texto</label>${colorInput('textColor','#111827')}</div>
            <div class="prop-group"><label class="prop-label">Cor de fundo</label>${colorInput('inputBg','#ffffff')}</div>
            <div class="prop-group"><label class="prop-label">Cor da borda</label>${colorInput('inputBorderColor','#d1d5db')}</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px">
                <div class="prop-group"><label class="prop-label">Borda (px)</label><input type="number" min="0" max="10" class="prop-input" value="${esc(s.inputBorderWidth ?? 1)}" onchange="updateStyle('${viewEl.id}','inputBorderWidth',this.value)"></div>
                <div class="prop-group"><label class="prop-label">Raio (px)</label><input type="number" min="0" max="50" class="prop-input" value="${esc(s.inputRadius ?? 8)}" onchange="updateStyle('${viewEl.id}','inputRadius',this.value)"></div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px">
                <div class="prop-group"><label class="prop-label">Padding vertical</label><input type="number" min="2" max="50" class="prop-input" value="${esc(s.inputPaddingY ?? 11)}" onchange="updateStyle('${viewEl.id}','inputPaddingY',this.value)"></div>
                <div class="prop-group"><label class="prop-label">Padding horizontal</label><input type="number" min="4" max="60" class="prop-input" value="${esc(s.inputPaddingX ?? 13)}" onchange="updateStyle('${viewEl.id}','inputPaddingX',this.value)"></div>
            </div>
            <div class="prop-group"><label class="prop-label">Fonte dos campos (px)</label><input type="number" min="8" max="30" class="prop-input" value="${esc(s.inputFontSize ?? 14)}" onchange="updateStyle('${viewEl.id}','inputFontSize',this.value)"></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-paper-plane"></i> Botão</div>
            <div class="prop-group"><label class="prop-label">Cor de fundo</label>${colorInput('buttonBg','#3b82f6')}</div>
            <div class="prop-group"><label class="prop-label">Cor do texto</label>${colorInput('buttonTextColor','#ffffff')}</div>
            <div class="prop-group"><label class="prop-label">Cor no hover</label>${colorInput('buttonBgHover','#2563eb')}</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Raio (px)</label><input type="number" min="0" max="50" class="prop-input" value="${esc(s.buttonRadius ?? 8)}" onchange="updateStyle('${viewEl.id}','buttonRadius',this.value)"></div><div class="prop-group"><label class="prop-label">Fonte (px)</label><input type="number" min="8" max="40" class="prop-input" value="${esc(s.buttonFontSize ?? 15)}" onchange="updateStyle('${viewEl.id}','buttonFontSize',this.value)"></div></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Padding vertical</label><input type="number" min="2" max="50" class="prop-input" value="${esc(s.buttonPaddingY ?? 12)}" onchange="updateStyle('${viewEl.id}','buttonPaddingY',this.value)"></div><div class="prop-group"><label class="prop-label">Padding horizontal</label><input type="number" min="4" max="80" class="prop-input" value="${esc(s.buttonPaddingX ?? 20)}" onchange="updateStyle('${viewEl.id}','buttonPaddingX',this.value)"></div></div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Botão largura total</span><input type="checkbox" ${s.buttonFullWidth ? 'checked' : ''} onchange="updateStyle('${viewEl.id}','buttonFullWidth',this.checked)"></label></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-window-maximize"></i> Cartão</div>
            <div class="prop-group"><label class="prop-label">Fundo</label>${colorInput('cardBg','#ffffff')}</div>
            <div class="prop-group"><label class="prop-label">Tipo de borda</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','cardBorderStyle',this.value)">${['none','solid','dashed','dotted','double'].map(v=>`<option value="${v}" ${(s.cardBorderStyle||'none')===v?'selected':''}>${v}</option>`).join('')}</select></div>
            <div class="prop-group"><label class="prop-label">Espessura da borda (px)</label><input type="number" min="0" max="10" class="prop-input" value="${esc(s.cardBorderWidth ?? 0)}" onchange="updateStyle('${viewEl.id}','cardBorderWidth',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Cor da borda</label>${colorInput('cardBorderColor','#e5e7eb')}</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Raio (px)</label><input type="number" min="0" max="60" class="prop-input" value="${esc(s.cardRadius ?? 12)}" onchange="updateStyle('${viewEl.id}','cardRadius',this.value)"></div><div class="prop-group"><label class="prop-label">Padding (px)</label><input type="number" min="0" max="80" class="prop-input" value="${esc(s.cardPadding ?? 24)}" onchange="updateStyle('${viewEl.id}','cardPadding',this.value)"></div></div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Ativar sombra</span><input type="checkbox" ${s.shadow ? 'checked' : ''} onchange="updateStyle('${viewEl.id}','shadow',this.checked); updateProperties('${viewEl.id}')"></label></div>
            ${s.shadow ? `<div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">X</label><input type="number" class="prop-input" value="${esc(s.shadowX ?? 0)}" onchange="updateStyle('${viewEl.id}','shadowX',this.value)"></div><div class="prop-group"><label class="prop-label">Y</label><input type="number" class="prop-input" value="${esc(s.shadowY ?? 8)}" onchange="updateStyle('${viewEl.id}','shadowY',this.value)"></div><div class="prop-group"><label class="prop-label">Desfoque</label><input type="number" min="0" class="prop-input" value="${esc(s.shadowBlur ?? 24)}" onchange="updateStyle('${viewEl.id}','shadowBlur',this.value)"></div><div class="prop-group"><label class="prop-label">Espalhar</label><input type="number" class="prop-input" value="${esc(s.shadowSpread ?? 0)}" onchange="updateStyle('${viewEl.id}','shadowSpread',this.value)"></div></div><div class="prop-group"><label class="prop-label">Cor da sombra</label>${colorInput('shadowColor','rgba(0,0,0,0.12)')}</div>` : ''}
        </div>`;
    }

    if (viewEl.type === 'faq') {
        const s = viewEl.styles;
        const colorInputFaq = (prop, fallback) => `<div style="display:grid;grid-template-columns:44px 1fr;gap:7px;align-items:center;"><input type="color" value="${esc((s[prop] || fallback).slice(0,7))}" oninput="updateStyle('${viewEl.id}','${prop}',this.value)" style="width:44px;height:34px;padding:2px;border:1px solid var(--border);border-radius:7px;background:var(--bg-tertiary);"><input class="prop-input" value="${esc(s[prop] || fallback)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"></div>`;
        html += `
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-circle-question"></i> Conteúdo do FAQ</div>
            <div class="prop-group"><label class="prop-label">Título</label><input class="prop-input" value="${esc(s.title || 'Perguntas Frequentes')}" onchange="updateStyle('${viewEl.id}','title',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Descrição</label><textarea class="prop-input" rows="3" onchange="updateStyle('${viewEl.id}','description',this.value)">${escapeHtml(s.description || '')}</textarea></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-list"></i> Perguntas e respostas</div>
            <div class="prop-group" style="font-size:10px;color:var(--text-tertiary);line-height:1.5;">Edite cada pergunta e resposta diretamente. Depois, clique em <b>Adicionar pergunta</b> para criar novas.</div>
            <div style="font-size:10px;color:var(--text-tertiary);margin-bottom:8px;">Arraste pelo ícone ☷ para alterar a ordem. Clique nos campos normalmente para editar.</div>
            ${ensureFaqItems(s).map((item,i) => `
                <div class="sbc-faq-prop-item" draggable="false" ondragover="handleFaqDragOver(event,'${viewEl.id}',${i})" ondragleave="handleFaqDragLeave(event)" ondrop="handleFaqDrop(event,'${viewEl.id}',${i})" style="border:1px solid var(--border);background:var(--bg-tertiary);border-radius:8px;padding:10px;margin-bottom:8px;transition:border-color .15s,box-shadow .15s;">
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;margin-bottom:8px;">
                        <div style="display:flex;align-items:center;gap:8px;min-width:0;">
                            <button type="button" title="Arrastar para reordenar" draggable="true" ondragstart="handleFaqDragStart(event,'${viewEl.id}',${i})" ondragend="handleFaqDragEnd(event)" style="border:1px solid var(--border);background:var(--bg);color:var(--text-secondary);width:30px;height:28px;border-radius:6px;cursor:grab;flex:0 0 auto;touch-action:none;">☷</button>
                            <strong style="font-size:11px;">Pergunta ${i+1}</strong>
                        </div>
                        <button type="button" title="Excluir" onclick="removeFaqItem('${viewEl.id}',${i})" style="border:0;background:transparent;color:var(--error);cursor:pointer;font-size:16px;">&times;</button>
                    </div>
                    <div class="prop-group" style="margin:0 0 7px;"><label class="prop-label">Pergunta</label><input class="prop-input" value="${esc(item.question || '')}" onchange="updateFaqItem('${viewEl.id}',${i},'question',this.value)"></div>
                    <div class="prop-group" style="margin:0;"><label class="prop-label">Resposta</label><textarea class="prop-input" rows="3" onchange="updateFaqItem('${viewEl.id}',${i},'answer',this.value)">${escapeHtml(item.answer || '')}</textarea></div>
                    <label style="display:flex;align-items:center;justify-content:space-between;margin-top:8px;"><span style="font-size:10px;color:var(--text-secondary);">Começar aberta</span><input type="checkbox" ${item.open?'checked':''} onchange="updateFaqItem('${viewEl.id}',${i},'open',this.checked)"></label>
                </div>`).join('')}
            <button type="button" onclick="addFaqItem('${viewEl.id}')" style="width:100%;height:36px;border:1px solid var(--accent);background:var(--accent-glow);color:var(--accent);border-radius:7px;cursor:pointer;font-weight:700;"><i class="fas fa-plus"></i> Adicionar pergunta</button>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-ruler-horizontal"></i> Layout</div>
            <div style="display:grid;grid-template-columns:1fr 90px;gap:6px"><div class="prop-group"><label class="prop-label">Largura</label><input type="number" min="1" max="2000" class="prop-input" value="${esc(s.width ?? 100)}" onchange="updateStyle('${viewEl.id}','width',this.value)"></div><div class="prop-group"><label class="prop-label">Unidade</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','widthUnit',this.value)"><option value="%" ${s.widthUnit !== 'px'?'selected':''}>%</option><option value="px" ${s.widthUnit === 'px'?'selected':''}>px</option></select></div></div>
            <div class="prop-group"><label class="prop-label">Alinhamento</label><div style="display:grid;grid-template-columns:repeat(3,1fr);gap:5px"><button type="button" class="prop-btn ${s.alignment==='left'?'active':''}" onclick="updateStyle('${viewEl.id}','alignment','left')">Esq.</button><button type="button" class="prop-btn ${s.alignment==='center'?'active':''}" onclick="updateStyle('${viewEl.id}','alignment','center')">Centro</button><button type="button" class="prop-btn ${s.alignment==='right'?'active':''}" onclick="updateStyle('${viewEl.id}','alignment','right')">Dir.</button></div></div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Permitir várias abertas</span><input type="checkbox" ${s.multipleOpen?'checked':''} onchange="updateStyle('${viewEl.id}','multipleOpen',this.checked)"></label></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-palette"></i> Aparência</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Título (px)</label><input type="number" min="10" max="72" class="prop-input" value="${esc(s.titleFontSize ?? 28)}" oninput="updateStyleLive('${viewEl.id}','titleFontSize',this.value)" onblur="updateStyle('${viewEl.id}','titleFontSize',this.value)"></div><div class="prop-group"><label class="prop-label">Descrição (px)</label><input type="number" min="10" max="30" class="prop-input" value="${esc(s.descriptionFontSize ?? 14)}" oninput="updateStyleLive('${viewEl.id}','descriptionFontSize',this.value)" onblur="updateStyle('${viewEl.id}','descriptionFontSize',this.value)"></div></div>
            <div class="prop-group"><label class="prop-label">Cor do título</label>${colorInputFaq('titleColor','#111827')}</div>
            <div class="prop-group"><label class="prop-label">Cor da descrição</label>${colorInputFaq('descriptionColor','#6b7280')}</div>
            <div class="prop-group"><label class="prop-label">Fundo da pergunta</label>${colorInputFaq('questionBg','#f8fafc')}</div>
            <div class="prop-group"><label class="prop-label">Cor da pergunta</label>${colorInputFaq('questionColor','#111827')}</div>
            <div class="prop-group"><label class="prop-label">Fundo da resposta</label>${colorInputFaq('answerBg','#ffffff')}</div>
            <div class="prop-group"><label class="prop-label">Cor da resposta</label>${colorInputFaq('answerColor','#4b5563')}</div>
            <div class="prop-group"><label class="prop-label">Cor da borda</label>${colorInputFaq('borderColor','#e5e7eb')}</div>
            <div class="prop-group"><label class="prop-label">Cor do ícone</label>${colorInputFaq('iconColor','#3b82f6')}</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Ícone</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','iconType',this.value)"><option value="chevron" ${(s.iconType||'chevron')==='chevron'?'selected':''}>Chevron</option><option value="angle" ${s.iconType==='angle'?'selected':''}>Ângulo</option><option value="plus" ${s.iconType==='plus'?'selected':''}>Mais / menos</option></select></div><div class="prop-group"><label class="prop-label">Posição do ícone</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','iconPosition',this.value)"><option value="right" ${(s.iconPosition||'right')==='right'?'selected':''}>Direita</option><option value="left" ${s.iconPosition==='left'?'selected':''}>Esquerda</option></select></div></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Pergunta (px)</label><input type="number" min="10" max="40" class="prop-input" value="${esc(s.questionFontSize ?? 15)}" onchange="updateStyle('${viewEl.id}','questionFontSize',this.value)"></div><div class="prop-group"><label class="prop-label">Resposta (px)</label><input type="number" min="10" max="40" class="prop-input" value="${esc(s.answerFontSize ?? 14)}" onchange="updateStyle('${viewEl.id}','answerFontSize',this.value)"></div></div>
            <div class="prop-group"><label class="prop-label">Cor ao passar o mouse</label>${colorInputFaq('questionHoverBg','#eef4ff')}</div>
            <div class="prop-group"><label class="prop-label">Fundo da pergunta aberta</label>${colorInputFaq('questionOpenBg','#eef4ff')}</div>
            <div class="prop-group"><label class="prop-label">Cor da pergunta aberta</label>${colorInputFaq('questionOpenColor','#111827')}</div>
            <div class="prop-group"><label class="prop-label">Altura da linha da resposta</label><input type="number" min="1" max="3" step="0.05" class="prop-input" value="${esc(s.answerLineHeight ?? 1.65)}" onchange="updateStyle('${viewEl.id}','answerLineHeight',this.value)"></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Borda (px)</label><input type="number" min="0" max="10" class="prop-input" value="${esc(s.borderWidth ?? 1)}" onchange="updateStyle('${viewEl.id}','borderWidth',this.value)"></div><div class="prop-group"><label class="prop-label">Raio (px)</label><input type="number" min="0" max="60" class="prop-input" value="${esc(s.radius ?? 10)}" onchange="updateStyle('${viewEl.id}','radius',this.value)"></div></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Espaço entre itens</label><input type="number" min="0" max="60" class="prop-input" value="${esc(s.itemSpacing ?? 10)}" onchange="updateStyle('${viewEl.id}','itemSpacing',this.value)"></div><div class="prop-group"><label class="prop-label">Padding vertical</label><input type="number" min="4" max="50" class="prop-input" value="${esc(s.paddingY ?? 16)}" onchange="updateStyle('${viewEl.id}','paddingY',this.value)"></div></div>
            <div class="prop-group"><label class="prop-label">Padding horizontal</label><input type="number" min="6" max="80" class="prop-input" value="${esc(s.paddingX ?? 18)}" onchange="updateStyle('${viewEl.id}','paddingX',this.value)"></div>
        </div>`;
    }

    if (viewEl.type === 'video') {
        const s = viewEl.styles;
        html += `
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-video"></i> Vídeo</div>
            <div class="prop-group">
                <label class="prop-label">URL do vídeo</label>
                <div style="display:flex;gap:6px;align-items:stretch;">
                    <input type="url" id="video-url-${viewEl.id}" class="prop-input" style="flex:1;" value="${esc(s.videoUrl || '')}" placeholder="YouTube, Vimeo, MP4, WebM, OGG, HLS ou URL de incorporação" onchange="updateStyle('${viewEl.id}','videoUrl',this.value)" onkeydown="if(event.key==='Enter'){updateStyle('${viewEl.id}','videoUrl',this.value);event.preventDefault();}">
                    <button type="button" class="nav-btn primary" style="padding:8px 10px;" onclick="applyVideoUrl('${viewEl.id}')">Aplicar</button>
                </div>
                <small style="display:block;color:var(--text-tertiary);font-size:10px;line-height:1.5;margin-top:6px;">Aceita YouTube, YouTube Shorts, Vimeo, MP4, WebM, OGG/OGV, M4V, MOV e outras URLs de vídeo suportadas pelo navegador.</small>
            </div>
            <div class="prop-group">
                <label class="prop-label">Arquivo de vídeo (opcional)</label>
                <label style="display:block;padding:12px;border:1px dashed var(--border);border-radius:8px;text-align:center;cursor:pointer;background:var(--bg-tertiary);">
                    <i class="fas fa-cloud-upload-alt" style="color:var(--accent);margin-right:6px;"></i> Escolher vídeo do computador
                    <input type="file" accept="video/*" style="display:none" onchange="handleVideoFileUpload(this,'${viewEl.id}')">
                </label>
            </div>
            ${s.videoFileName ? `<div style="font-size:10px;color:var(--success);margin:-4px 0 10px;">Arquivo: ${escapeHtml(s.videoFileName)}</div>` : ''}
            <div class="prop-section">
                <div class="prop-title"><i class="fas fa-ruler-horizontal"></i> Tamanho e posicionamento</div>
                <div style="display:grid;grid-template-columns:1fr 92px;gap:8px;align-items:end;">
                    <div class="prop-group">
                        <label class="prop-label">Largura</label>
                        <input type="text" class="prop-input" value="${esc(s.width ?? '100')}" placeholder="Ex.: 700 ou 80%" onchange="updateStyle('${viewEl.id}','width',this.value)">
                    </div>
                    <div class="prop-group">
                        <label class="prop-label">Unidade</label>
                        <select class="prop-select" onchange="updateStyle('${viewEl.id}','widthUnit',this.value)">
                            <option value="px" ${s.widthUnit === 'px' ? 'selected' : ''}>px</option>
                            <option value="%" ${(s.widthUnit || '%') === '%' ? 'selected' : ''}>%</option>
                        </select>
                    </div>
                </div>
                <small style="display:block;color:var(--text-tertiary);font-size:10px;line-height:1.5;margin-top:-2px;">Você pode informar qualquer largura em px ou %. Ex.: 640 px, 75%.</small>
                <div class="prop-group">
                    <label class="prop-label">Alinhamento</label>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:5px;">
                        ${[['left','Esq.','fa-align-left'],['center','Centro','fa-align-center'],['right','Dir.','fa-align-right']].map(([a,l,i])=>`<button type="button" title="${l}" onclick="updateStyle('${viewEl.id}','alignment','${a}')" style="height:34px;border:1px solid ${(s.alignment||'center')===a?'var(--accent)':'var(--border)'};background:${(s.alignment||'center')===a?'var(--accent-glow)':'var(--bg-tertiary)'};color:${(s.alignment||'center')===a?'var(--accent)':'var(--text-secondary)'};border-radius:6px;cursor:pointer;"><i class="fas ${i}"></i></button>`).join('')}
                    </div>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                <div class="prop-group"><label class="prop-label">Proporção</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','aspectRatio',this.value)">${['16:9','4:3','1:1','21:9'].map(a => `<option value="${a}" ${(s.aspectRatio || '16:9') === a ? 'selected' : ''}>${a}</option>`).join('')}</select></div>
                <div class="prop-group"><label class="prop-label">Ajuste da imagem</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','objectFit',this.value)"><option value="cover" ${(s.objectFit || 'cover') === 'cover' ? 'selected' : ''}>Preencher</option><option value="contain" ${s.objectFit === 'contain' ? 'selected' : ''}>Conter</option><option value="fill" ${s.objectFit === 'fill' ? 'selected' : ''}>Esticar</option></select></div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                <div class="prop-group"><label class="prop-label">Raio (px)</label><input type="number" min="0" max="80" class="prop-input" value="${esc(s.borderRadius ?? '8')}" onchange="updateStyle('${viewEl.id}','borderRadius',this.value)"></div>
                <div class="prop-group"><label class="prop-label">Opacidade</label><input type="number" min="0" max="1" step="0.05" class="prop-input" value="${esc(s.opacity ?? '1')}" onchange="updateStyle('${viewEl.id}','opacity',this.value)"></div>
            </div>
            <div class="prop-group"><label class="prop-label">Poster / Capa URL</label><input type="url" class="prop-input" value="${esc(s.poster || '')}" placeholder="https://..." onchange="updateStyle('${viewEl.id}','poster',this.value)"></div>
            <div class="prop-group"><div style="display:flex;align-items:center;justify-content:space-between;"><span class="prop-label" style="margin:0;">Ativar sombra</span><label class="toggle-switch"><input type="checkbox" ${s.shadow ? 'checked' : ''} onchange="updateStyle('${viewEl.id}','shadow',this.checked)"><span class="toggle-slider"></span></label></div></div>
            ${s.shadow ? `<div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                <div class="prop-group"><label class="prop-label">X</label><input type="number" class="prop-input" value="${esc(s.shadowX ?? '0')}" onchange="updateStyle('${viewEl.id}','shadowX',this.value)"></div>
                <div class="prop-group"><label class="prop-label">Y</label><input type="number" class="prop-input" value="${esc(s.shadowY ?? '6')}" onchange="updateStyle('${viewEl.id}','shadowY',this.value)"></div>
                <div class="prop-group"><label class="prop-label">Desfoque</label><input type="number" min="0" class="prop-input" value="${esc(s.shadowBlur ?? '18')}" onchange="updateStyle('${viewEl.id}','shadowBlur',this.value)"></div>
                <div class="prop-group"><label class="prop-label">Espalhamento</label><input type="number" class="prop-input" value="${esc(s.shadowSpread ?? '0')}" onchange="updateStyle('${viewEl.id}','shadowSpread',this.value)"></div>
            </div>
            <div class="prop-group"><label class="prop-label">Cor da sombra</label><input type="text" class="prop-input" value="${esc(s.shadowColor ?? 'rgba(0,0,0,0.28)')}" onchange="updateStyle('${viewEl.id}','shadowColor',this.value)"></div>` : ''}
            <div class="prop-section">
                <div class="prop-title"><i class="fas fa-sliders"></i> Reprodução</div>
                ${[['controls','Controles'],['autoplay','Autoplay'],['muted','Silenciado'],['loop','Repetir (Loop)']].map(([p,l]) => `<div class="prop-group"><div style="display:flex;align-items:center;justify-content:space-between;"><span class="prop-label" style="margin:0;">${l}</span><label class="toggle-switch"><input type="checkbox" ${s[p] ? 'checked' : ''} onchange="updateStyle('${viewEl.id}','${p}',this.checked)"><span class="toggle-slider"></span></label></div></div>`).join('')}
            </div>
        </div>`;
    }

    if (viewEl.type === 'image') {
        const s = viewEl.styles || {};
        const colorInput = (prop, fallback) => { const raw = s[prop] || fallback; const picker = /^#[0-9a-f]{6}$/i.test(raw) ? raw : fallback; return `<div class="color-row"><input type="color" value="${esc(picker)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"><input type="text" value="${esc(raw)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"></div>`; };
        html += `
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-image"></i> Conteúdo</div>
            <div class="prop-group"><label class="prop-label">URL da imagem</label><input type="url" class="prop-input" value="${esc(viewEl.content || '')}" placeholder="https://..." onchange="updateStyle('${viewEl.id}','content',this.value)"></div>
            <div class="prop-group">
                <label class="prop-label">Ou enviar do computador</label>
                <label style="display:block;padding:12px;border:1px dashed var(--border);border-radius:8px;text-align:center;cursor:pointer;background:var(--bg-tertiary);">
                    <i class="fas fa-cloud-upload-alt" style="color:var(--accent);margin-right:6px;"></i> Escolher imagem
                    <input type="file" accept="image/*" style="display:none" onchange="handleImageUpload(this,'${viewEl.id}')">
                </label>
            </div>
            <div class="prop-group"><label class="prop-label">Texto alternativo (ALT)</label><input type="text" class="prop-input" value="${esc(s.alt || 'Imagem')}" placeholder="Descrição da imagem" onchange="updateStyle('${viewEl.id}','alt',this.value)"></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-expand-arrows-alt"></i> Tamanho</div>
            <div class="prop-group"><label class="prop-label">Largura</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','imageWidth',this.value)">${[['100','100%'],['75','75%'],['50','50%'],['25','25%'],['auto','Automática']].map(([v,l])=>`<option value="${v}" ${(String(s.imageWidth ?? '100')===v)?'selected':''}>${l}</option>`).join('')}</select></div>
            <div class="prop-group"><label class="prop-label">Altura (px)</label><input type="number" min="0" class="prop-input" value="${esc(s.imageHeight ?? 'auto')}" placeholder="auto" onchange="updateStyle('${viewEl.id}','imageHeight',this.value || 'auto')"></div>
            <div class="prop-group"><label class="prop-label">Ajuste da imagem</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','objectFit',this.value)"><option value="cover" ${(s.objectFit||'cover')==='cover'?'selected':''}>Preencher (cover)</option><option value="contain" ${s.objectFit==='contain'?'selected':''}>Conter (contain)</option><option value="fill" ${s.objectFit==='fill'?'selected':''}>Esticar (fill)</option><option value="none" ${s.objectFit==='none'?'selected':''}>Original</option></select></div>
            <div class="prop-group"><label class="prop-label">Alinhamento</label><div style="display:grid;grid-template-columns:repeat(3,1fr);gap:5px;">${[['left','Esquerda','fa-align-left'],['center','Centro','fa-align-center'],['right','Direita','fa-align-right']].map(([a,l,i])=>`<button type="button" title="${l}" onclick="updateStyle('${viewEl.id}','align','${a}')" style="height:34px;border:1px solid ${(s.align||'center')===a?'var(--accent)':'var(--border)'};background:${(s.align||'center')===a?'var(--accent-glow)':'var(--bg-tertiary)'};color:${(s.align||'center')===a?'var(--accent)':'var(--text-secondary)'};border-radius:6px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:5px;"><i class="fas ${i}"></i><span style="font-size:10px;">${l}</span></button>`).join('')}</div></div>
            <div class="prop-group"><label class="prop-label">Opacidade</label><input type="range" min="0" max="1" step="0.01" value="${esc(s.opacity ?? 1)}" oninput="updateStyle('${viewEl.id}','opacity',this.value)" style="width:100%;"></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-border-style"></i> Cantos</div>
            <div class="prop-group"><label class="prop-label">Arredondamento geral (px)</label><input type="number" min="0" class="prop-input" value="${esc(s.borderRadius ?? 8)}" onchange="updateStyle('${viewEl.id}','borderRadius',this.value); updateStyle('${viewEl.id}','borderRadiusTL',this.value); updateStyle('${viewEl.id}','borderRadiusTR',this.value); updateStyle('${viewEl.id}','borderRadiusBR',this.value); updateStyle('${viewEl.id}','borderRadiusBL',this.value)"></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">${[['borderRadiusTL','Superior esquerdo'],['borderRadiusTR','Superior direito'],['borderRadiusBR','Inferior direito'],['borderRadiusBL','Inferior esquerdo']].map(([p,l])=>`<div class="prop-group"><label class="prop-label">${l}</label><input type="number" min="0" class="prop-input" value="${esc(s[p] ?? s.borderRadius ?? 8)}" onchange="updateStyle('${viewEl.id}','${p}',this.value)"></div>`).join('')}</div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-link"></i> Link</div>
            <div class="prop-group"><label class="prop-label">URL ao clicar</label><input type="url" class="prop-input" value="${esc(s.link || '')}" placeholder="https://..." onchange="updateStyle('${viewEl.id}','link',this.value)"></div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Abrir em nova aba</span><input type="checkbox" ${s.targetBlank?'checked':''} onchange="updateStyle('${viewEl.id}','targetBlank',this.checked)"></label></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-magic"></i> Efeitos</div>
            <div class="prop-group"><label class="prop-label">Efeito ao passar o mouse</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','hoverEffect',this.value)"><option value="none" ${(s.hoverEffect||'none')==='none'?'selected':''}>Nenhum</option><option value="scale" ${s.hoverEffect==='scale'?'selected':''}>Zoom</option><option value="up" ${s.hoverEffect==='up'?'selected':''}>Elevar</option><option value="down" ${s.hoverEffect==='down'?'selected':''}>Descer</option><option value="bright" ${s.hoverEffect==='bright'?'selected':''}>Iluminar</option></select></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;"><div class="prop-group"><label class="prop-label">Zoom</label><input type="number" min="1" step="0.01" class="prop-input" value="${esc(s.hoverScale ?? 1.03)}" onchange="updateStyle('${viewEl.id}','hoverScale',this.value)"></div><div class="prop-group"><label class="prop-label">Transição (s)</label><input type="number" min="0" step="0.05" class="prop-input" value="${esc(s.hoverTransition ?? .3)}" onchange="updateStyle('${viewEl.id}','hoverTransition',this.value)"></div></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-adjust"></i> Filtros</div>
            <div class="prop-group"><label class="prop-label">Escala de cinza (%)</label><input type="range" min="0" max="100" value="${esc(s.grayscale ?? 0)}" oninput="updateStyle('${viewEl.id}','grayscale',this.value)" style="width:100%;"></div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:6px;"><div class="prop-group"><label class="prop-label">Brilho</label><input type="number" min="0" step="0.05" class="prop-input" value="${esc(s.brightness ?? 1)}" onchange="updateStyle('${viewEl.id}','brightness',this.value)"></div><div class="prop-group"><label class="prop-label">Contraste</label><input type="number" min="0" step="0.05" class="prop-input" value="${esc(s.contrast ?? 1)}" onchange="updateStyle('${viewEl.id}','contrast',this.value)"></div><div class="prop-group"><label class="prop-label">Saturação</label><input type="number" min="0" step="0.05" class="prop-input" value="${esc(s.saturate ?? 1)}" onchange="updateStyle('${viewEl.id}','saturate',this.value)"></div></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-cloud"></i> Sombra</div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Ativar sombra</span><input type="checkbox" ${s.shadow?'checked':''} onchange="updateStyle('${viewEl.id}','shadow',this.checked)"></label></div>
            ${s.shadow ? `<div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">${[['shadowX','X'],['shadowY','Y'],['shadowBlur','Desfoque'],['shadowSpread','Espalhar']].map(([p,l])=>`<div class="prop-group"><label class="prop-label">${l}</label><input type="number" class="prop-input" value="${esc(s[p] ?? 0)}" onchange="updateStyle('${viewEl.id}','${p}',this.value)"></div>`).join('')}</div><div class="prop-group"><label class="prop-label">Cor da sombra</label>${colorInput('shadowColor','rgba(0,0,0,0.18)')}</div>` : ''}
        </div>`;
    }

    if (viewEl.type === 'icon') {
        const s = viewEl.styles || {};
        const iconVisuals = [
            ['fa-star','Estrela'],['fa-heart','Coração'],['fa-user','Usuário'],['fa-check','Check'],['fa-xmark','X'],
            ['fa-house','Casa'],['fa-gear','Configuração'],['fa-bars','Menu'],['fa-magnifying-glass','Buscar'],['fa-envelope','E-mail'],
            ['fa-phone','Telefone'],['fa-location-dot','Local'],['fa-camera','Câmera'],['fa-image','Imagem'],['fa-video','Vídeo'],
            ['fa-play','Play'],['fa-pause','Pause'],['fa-download','Download'],['fa-upload','Upload'],['fa-link','Link'],
            ['fa-arrow-right','Direita'],['fa-arrow-left','Esquerda'],['fa-arrow-up','Cima'],['fa-arrow-down','Baixo'],['fa-plus','Mais'],
            ['fa-minus','Menos'],['fa-circle-info','Info'],['fa-circle-question','Ajuda'],['fa-bell','Aviso'],['fa-lock','Cadeado']
        ];
        const colorInput = (prop, fallback) => {
            const raw = s[prop] || fallback;
            const picker = /^#[0-9a-f]{6}$/i.test(raw) ? raw : fallback;
            return `<div class="color-row"><input type="color" value="${esc(picker)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"><input type="text" value="${esc(raw)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"></div>`;
        };
        html += `
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-icons"></i> Ícone</div>
            <div class="prop-group">
                <label class="prop-label">Escolha o ícone</label>
                <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:6px;max-height:190px;overflow:auto;padding:3px 2px;">
                    ${iconVisuals.map(([v,l]) => `<button type="button" title="${l}" onclick="updateStyle('${viewEl.id}','icon','${v}')" style="height:42px;border:1px solid ${(s.icon||'fa-star')===v?'var(--accent)':'var(--border)'};background:${(s.icon||'fa-star')===v?'var(--accent-glow)':'var(--bg-tertiary)'};color:${(s.icon||'fa-star')===v?'var(--accent)':'var(--text-secondary)'};border-radius:7px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:18px;"><i class="fas ${v}"></i></button>`).join('')}
                </div>
            </div>
        </div>

        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-arrows-left-right"></i> Posicionamento</div>
            <div class="prop-group">
                <label class="prop-label">Alinhamento</label>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px;">
                    ${[['left','Esquerda','fa-align-left'],['center','Centro','fa-align-center'],['right','Direita','fa-align-right']].map(([a,l,i])=>`<button type="button" title="${l}" onclick="updateStyle('${viewEl.id}','alignment','${a}')" style="height:36px;border:1px solid ${(s.alignment||'center')===a?'var(--accent)':'var(--border)'};background:${(s.alignment||'center')===a?'var(--accent-glow)':'var(--bg-tertiary)'};color:${(s.alignment||'center')===a?'var(--accent)':'var(--text-secondary)'};border-radius:7px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:5px;"><i class="fas ${i}"></i><span style="font-size:10px;">${l}</span></button>`).join('')}
                </div>
            </div>
        </div>

        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-ruler-combined"></i> Tamanho e espaçamento</div>
            <div class="prop-group">
                <label class="prop-label">Tamanho do ícone (px)</label>
                <input type="number" min="8" max="300" class="prop-input" value="${esc(s.iconSize ?? 48)}" onchange="updateStyle('${viewEl.id}','iconSize',this.value)">
            </div>
            <div class="prop-group">
                <label class="prop-label">Espaço interno / área do ícone (px)</label>
                <input type="number" min="0" max="200" class="prop-input" value="${esc(s.iconPadding ?? 0)}" onchange="updateStyle('${viewEl.id}','iconPadding',this.value)">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                <div class="prop-group"><label class="prop-label">Margem superior (px)</label><input type="number" min="-200" max="200" class="prop-input" value="${esc(s.iconMarginTop ?? 0)}" onchange="updateStyle('${viewEl.id}','iconMarginTop',this.value)"></div>
                <div class="prop-group"><label class="prop-label">Margem inferior (px)</label><input type="number" min="-200" max="200" class="prop-input" value="${esc(s.iconMarginBottom ?? 0)}" onchange="updateStyle('${viewEl.id}','iconMarginBottom',this.value)"></div>
            </div>
            <div class="prop-group"><label class="prop-label">Arredondamento (px)</label><input type="number" min="0" max="200" class="prop-input" value="${esc(s.iconRadius ?? 8)}" onchange="updateStyle('${viewEl.id}','iconRadius',this.value)"></div>
        </div>

        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-palette"></i> Cores</div>
            <div class="prop-group"><label class="prop-label">Cor do ícone</label>${colorInput('iconColor','#3b82f6')}</div>
            <div class="prop-group"><label class="prop-label">Fundo do ícone</label>${colorInput('iconBgColor','transparent')}</div>
            <div class="prop-group"><label class="prop-label">Opacidade</label><input type="range" min="0" max="1" step="0.01" value="${esc(s.iconOpacity ?? 1)}" oninput="updateStyle('${viewEl.id}','iconOpacity',this.value)" style="width:100%;"></div>
        </div>`;
    }

    if (viewEl.type === 'divider') {
        const s = viewEl.styles || {};
        const colorInput = (prop, fallback) => { const raw = s[prop] || fallback; const picker = /^#[0-9a-f]{6}$/i.test(raw) ? raw : fallback; return `<div class="color-row"><input type="color" value="${esc(picker)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"><input type="text" value="${esc(raw)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"></div>`; };
        
        html += `
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-minus"></i> Linha</div>
            <div class="prop-group"><label class="prop-label">Espessura (px)</label><input type="number" min="1" max="40" class="prop-input" value="${esc(s.thickness || '2')}" onchange="updateStyle('${viewEl.id}','thickness',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Altura extra (px)</label><input type="number" min="0" max="100" class="prop-input" value="${esc(s.height || '0')}" onchange="updateStyle('${viewEl.id}','height',this.value)"><small style="color:var(--text-tertiary);font-size:9px;">Espaço vertical adicional</small></div>
            <div class="prop-group"><label class="prop-label">Estilo da Linha</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','lineStyle',this.value)">
                ${['solid','dashed','dotted','double','groove','ridge','inset','outset'].map(v => `<option value="${v}" ${(s.lineStyle || 'solid') === v ? 'selected' : ''}>${v}</option>`).join('')}
            </select></div>
            <div class="prop-group"><label class="prop-label">Cor da Linha</label>${colorInput('color', '#cbd5e1')}</div>
            <div class="prop-group"><label class="prop-label">Opacidade (%)</label><input type="number" min="0" max="100" class="prop-input" value="${esc(s.opacity || '100')}" onchange="updateStyle('${viewEl.id}','opacity',this.value)"></div>
        </div>

        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-palette"></i> Gradiente</div>
            <div class="prop-group">
                <label style="display:flex;align-items:center;justify-content:space-between">
                    <span class="prop-label" style="margin:0">Usar gradiente</span>
                    <input type="checkbox" ${s.useGradient ? 'checked' : ''} onchange="updateStyle('${viewEl.id}','useGradient',this.checked)">
                </label>
            </div>
            ${s.useGradient ? `
            <div class="prop-group"><label class="prop-label">Cor Inicial</label>${colorInput('gradientStart', '#3b82f6')}</div>
            <div class="prop-group"><label class="prop-label">Cor Final</label>${colorInput('gradientEnd', '#8b5cf6')}</div>
            <div class="prop-group"><label class="prop-label">Direção</label>
                <select class="prop-select" onchange="updateStyle('${viewEl.id}','gradientDirection',this.value)">
                    ${[['to right','→ Direita'],['to left','← Esquerda'],['to bottom','↓ Baixo'],['to top','↑ Cima'],['135deg','↘ Diagonal'],['45deg','↗ Diagonal']].map(([v,l]) => `<option value="${v}" ${(s.gradientDirection || 'to right') === v ? 'selected' : ''}>${l}</option>`).join('')}
                </select>
            </div>
            <div class="prop-group">
                <div style="height:24px;border-radius:6px;background:linear-gradient(${s.gradientDirection || 'to right'}, ${s.gradientStart || '#3b82f6'}, ${s.gradientEnd || '#8b5cf6'});border:1px solid var(--border);"></div>
            </div>
            ` : ''}
        </div>

        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-cloud"></i> Sombra</div>
            <div class="prop-group">
                <label style="display:flex;align-items:center;justify-content:space-between">
                    <span class="prop-label" style="margin:0">Ativar sombra</span>
                    <input type="checkbox" ${s.shadow ? 'checked' : ''} onchange="updateStyle('${viewEl.id}','shadow',this.checked)">
                </label>
            </div>
            ${s.shadow ? `
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">
                <div class="prop-group"><label class="prop-label">X</label><input type="number" class="prop-input" value="${esc(s.shadowX || 0)}" onchange="updateStyle('${viewEl.id}','shadowX',this.value)"></div>
                <div class="prop-group"><label class="prop-label">Y</label><input type="number" class="prop-input" value="${esc(s.shadowY || 2)}" onchange="updateStyle('${viewEl.id}','shadowY',this.value)"></div>
                <div class="prop-group"><label class="prop-label">Desfoque</label><input type="number" class="prop-input" value="${esc(s.shadowBlur || 6)}" onchange="updateStyle('${viewEl.id}','shadowBlur',this.value)"></div>
                <div class="prop-group"><label class="prop-label">Espalhar</label><input type="number" class="prop-input" value="${esc(s.shadowSpread || 0)}" onchange="updateStyle('${viewEl.id}','shadowSpread',this.value)"></div>
            </div>
            <div class="prop-group"><label class="prop-label">Cor da sombra</label>${colorInput('shadowColor', 'rgba(0,0,0,0.2)')}</div>
            ` : ''}
        </div>

        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-magic"></i> Estilo Decorativo</div>
            <div class="prop-group"><label class="prop-label">Tipo</label>
                <select class="prop-select" onchange="updateStyle('${viewEl.id}','decorStyle',this.value)">
                    <option value="simple" ${(s.decorStyle || 'simple') === 'simple' ? 'selected' : ''}>Simples (apenas linha)</option>
                    <option value="centered" ${s.decorStyle === 'centered' ? 'selected' : ''}>Com elemento central</option>
                </select>
            </div>
            ${s.decorStyle === 'centered' ? `
            <div class="prop-group"><label class="prop-label">Conteúdo do elemento</label>
                <select class="prop-select" onchange="updateStyle('${viewEl.id}','decorType',this.value)">
                    <option value="icon" ${(s.decorType || 'icon') === 'icon' ? 'selected' : ''}>Ícone</option>
                    <option value="text" ${s.decorType === 'text' ? 'selected' : ''}>Texto</option>
                </select>
            </div>
            ${s.decorType === 'icon' ? `
            <div class="prop-group"><label class="prop-label">Escolher ícone</label>
                ${(() => {
                    const iconVisuals = [
                        ['fa-star','Estrela'],['fa-heart','Coração'],['fa-thumbs-up','Curtir'],['fa-fire','Fogo'],
                        ['fa-bolt','Raio'],['fa-trophy','Troféu'],['fa-crown','Coroa'],['fa-gem','Joia'],
                        ['fa-arrow-right','Direita'],['fa-arrow-left','Esquerda'],['fa-arrow-up','Cima'],['fa-arrow-down','Baixo'],
                        ['fa-chevron-right','Chevron direita'],['fa-chevron-left','Chevron esquerda'],['fa-check','Check'],['fa-check-double','Check duplo'],
                        ['fa-xmark','X'],['fa-plus','Mais'],['fa-minus','Menos'],['fa-circle-check','Check círculo'],
                        ['fa-gear','Configurações'],['fa-bars','Menu'],['fa-magnifying-glass','Pesquisar'],['fa-house','Casa'],
                        ['fa-user','Usuário'],['fa-lock','Cadeado'],['fa-bell','Notificação'],['fa-calendar','Calendário'],
                        ['fa-clock','Relógio'],['fa-phone','Telefone'],['fa-envelope','E-mail'],['fa-comment','Comentário'],
                        ['fa-share-nodes','Compartilhar'],['fa-link','Link'],['fa-globe','Globo'],['fa-cart-shopping','Carrinho'],
                        ['fa-store','Loja'],['fa-tag','Etiqueta'],['fa-credit-card','Cartão'],['fa-gift','Presente'],
                        ['fa-code','Código'],['fa-laptop','Notebook'],['fa-mobile-screen','Celular'],['fa-wifi','Wi-Fi'],
                        ['fa-database','Banco de dados'],['fa-cloud','Nuvem'],['fa-download','Download'],['fa-upload','Upload'],
                        ['fa-play','Play'],['fa-image','Imagem'],['fa-camera','Câmera'],['fa-info','Informação'],
                        ['fa-circle-info','Info círculo'],['fa-question','Pergunta'],['fa-circle-question','Pergunta círculo'],
                        ['fa-exclamation','Exclamação'],['fa-circle','Círculo'],['fa-diamond','Diamante']
                    ];
                    const currentIcon = s.decorContent || 'fa-star';
                    return `<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:6px;max-height:250px;overflow-y:auto;padding:4px 2px;">${iconVisuals.map(([v,l]) => `
                        <button type="button" title="${l}" onclick="updateStyle('${viewEl.id}','decorContent','${v}')" style="height:42px;border:1px solid ${currentIcon===v?'var(--accent)':'var(--border)'};background:${currentIcon===v?'var(--accent-glow)':'var(--bg-tertiary)'};color:${currentIcon===v?'var(--accent)':'var(--text-secondary)'};border-radius:7px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:17px;transition:.15s;">
                            <i class="fas ${v}"></i>
                        </button>`).join('')}</div>`;
                })()}
            </div>
            <div class="prop-group"><label class="prop-label">Ícone personalizado</label>
                <input type="text" class="prop-input" value="${esc(s.decorContent || 'fa-star')}" onchange="updateStyle('${viewEl.id}','decorContent',this.value)" placeholder="fa-star, fa-heart...">
            </div>
            ` : `
            <div class="prop-group"><label class="prop-label">Texto</label>
                <input type="text" class="prop-input" value="${esc(s.decorContent || '')}" onchange="updateStyle('${viewEl.id}','decorContent',this.value)" placeholder="Digite o texto...">
            </div>
            `}
            <div class="prop-group"><label class="prop-label">Formato</label>
                <select class="prop-select" onchange="updateStyle('${viewEl.id}','decorShape',this.value)">
                    <option value="circle" ${(s.decorShape || 'circle') === 'circle' ? 'selected' : ''}>Círculo</option>
                    <option value="square" ${s.decorShape === 'square' ? 'selected' : ''}>Quadrado</option>
                    <option value="rounded" ${s.decorShape === 'rounded' ? 'selected' : ''}>Arredondado</option>
                    <option value="pill" ${s.decorShape === 'pill' ? 'selected' : ''}>Pílula</option>
                    <option value="none" ${s.decorShape === 'none' ? 'selected' : ''}>Sem fundo</option>
                </select>
            </div>
            <div class="prop-group">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
                    <label class="prop-label" style="margin:0">Tamanho do ícone</label>
                    <span id="divider-icon-size-${viewEl.id}" style="font-size:12px;color:var(--text-secondary);">${esc(s.decorSize || 24)} px</span>
                </div>
                <input type="range" min="8" max="80" step="1" value="${esc(s.decorSize || 24)}" style="width:100%;" oninput="updateDividerIconControl('${viewEl.id}','decorSize',this.value,'divider-icon-size-${viewEl.id}',' px')">
            </div>
            <div class="prop-group">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
                    <label class="prop-label" style="margin:0">Espaçamento interno</label>
                    <span id="divider-icon-padding-${viewEl.id}" style="font-size:12px;color:var(--text-secondary);">${esc(s.decorPadding || 12)} px</span>
                </div>
                <input type="range" min="0" max="40" step="1" value="${esc(s.decorPadding || 12)}" style="width:100%;" oninput="updateDividerIconControl('${viewEl.id}','decorPadding',this.value,'divider-icon-padding-${viewEl.id}',' px')">
            </div>
            ${s.decorType === 'text' ? `
            <div class="prop-group"><label class="prop-label">Peso do texto</label>
                <select class="prop-select" onchange="updateStyle('${viewEl.id}','decorFontWeight',this.value)">
                    ${['300','400','500','600','700','800','900'].map(w => `<option value="${w}" ${(String(s.decorFontWeight || '600') === w) ? 'selected' : ''}>${w}</option>`).join('')}
                </select>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">
                <div class="prop-group"><label class="prop-label">Espaçamento</label><input type="number" step="0.5" class="prop-input" value="${esc(s.decorLetterSpacing ?? 0)}" onchange="updateStyle('${viewEl.id}','decorLetterSpacing',this.value)"></div>
                <div class="prop-group"><label class="prop-label">Transformação</label>
                    <select class="prop-select" onchange="updateStyle('${viewEl.id}','decorTextTransform',this.value)">
                        <option value="none" ${(!s.decorTextTransform || s.decorTextTransform === 'none') ? 'selected' : ''}>Normal</option>
                        <option value="uppercase" ${s.decorTextTransform === 'uppercase' ? 'selected' : ''}>MAIÚSCULAS</option>
                        <option value="lowercase" ${s.decorTextTransform === 'lowercase' ? 'selected' : ''}>minúsculas</option>
                        <option value="capitalize" ${s.decorTextTransform === 'capitalize' ? 'selected' : ''}>Primeira letra</option>
                    </select>
                </div>
            </div>` : ''}
            <div class="prop-group"><label class="prop-label">Cor do elemento</label>${colorInput('decorTextColor', '#3b82f6')}</div>
            <div class="prop-group"><label class="prop-label">Fundo do elemento</label>${colorInput('decorBgColor', '#ffffff')}</div>
            <div class="prop-group">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
                    <label class="prop-label" style="margin:0">Opacidade do ícone</label>
                    <span id="divider-icon-opacity-${viewEl.id}" style="font-size:12px;color:var(--text-secondary);">${esc(s.decorIconOpacity ?? 100)}%</span>
                </div>
                <input type="range" min="0" max="100" step="1" value="${esc(s.decorIconOpacity ?? 100)}" style="width:100%;" oninput="updateDividerIconControl('${viewEl.id}','decorIconOpacity',this.value,'divider-icon-opacity-${viewEl.id}','%')">
            </div>
            ${s.decorType === 'icon' ? `
            <div class="prop-group">
                <label class="prop-label">Animação do ícone</label>
                <select class="prop-select" onchange="updateStyle('${viewEl.id}','decorAnimation',this.value)">
                    <option value="none" ${(!s.decorAnimation || s.decorAnimation === 'none') ? 'selected' : ''}>Sem animação</option>
                    <option value="pulse" ${s.decorAnimation === 'pulse' ? 'selected' : ''}>Pulso</option>
                    <option value="bounce" ${s.decorAnimation === 'bounce' ? 'selected' : ''}>Quicar</option>
                    <option value="spin" ${s.decorAnimation === 'spin' ? 'selected' : ''}>Girar</option>
                    <option value="float" ${s.decorAnimation === 'float' ? 'selected' : ''}>Flutuar</option>
                    <option value="shake" ${s.decorAnimation === 'shake' ? 'selected' : ''}>Tremer</option>
                </select>
            </div>` : ''}
            ${s.decorShape === 'rounded' ? `
            <div class="prop-group">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
                    <label class="prop-label" style="margin:0">Arredondamento</label>
                    <span id="divider-icon-radius-${viewEl.id}" style="font-size:12px;color:var(--text-secondary);">${esc(s.decorRadius ?? 10)} px</span>
                </div>
                <input type="range" min="0" max="50" step="1" value="${esc(s.decorRadius ?? 10)}" style="width:100%;" oninput="updateDividerIconControl('${viewEl.id}','decorRadius',this.value,'divider-icon-radius-${viewEl.id}',' px')">
            </div>` : ''}
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Borda</span><input type="checkbox" ${s.decorBorder ? 'checked' : ''} onchange="updateStyle('${viewEl.id}','decorBorder',this.checked)"></label></div>
            ${s.decorBorder ? `<div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;"><div class="prop-group"><div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;"><label class="prop-label" style="margin:0">Espessura</label><span id="divider-icon-border-${viewEl.id}" style="font-size:12px;color:var(--text-secondary);">${esc(s.decorBorderWidth || 1)} px</span></div><input type="range" min="1" max="8" step="1" value="${esc(s.decorBorderWidth || 1)}" style="width:100%;" oninput="updateDividerIconControl('${viewEl.id}','decorBorderWidth',this.value,'divider-icon-border-${viewEl.id}',' px')"></div><div class="prop-group"><label class="prop-label">Cor da borda</label>${colorInput('decorBorderColor', '#3b82f6')}</div></div>` : ''}
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Sombra</span><input type="checkbox" ${s.decorShadow ? 'checked' : ''} onchange="updateStyle('${viewEl.id}','decorShadow',this.checked)"></label></div>
            ` : ''}
        </div>

        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-arrows-alt"></i> Layout</div>
            <div class="prop-group"><label class="prop-label">Margem Superior e Inferior (px)</label><input type="number" min="0" max="200" class="prop-input" value="${esc(s.margin || '40')}" onchange="updateStyle('${viewEl.id}','margin',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Largura (%)</label><input type="number" min="10" max="100" class="prop-input" value="${esc(s.width || '100')}" onchange="updateStyle('${viewEl.id}','width',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Alinhamento</label>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:5px;">
                    ${[['left','Esq.'],['center','Centro'],['right','Dir.']].map(([a,l])=>`<button type="button" onclick="updateStyle('${viewEl.id}','alignment','${a}')" style="height:34px;border:1px solid ${(s.alignment||'center')===a?'var(--accent)':'var(--border)'};background:${(s.alignment||'center')===a?'var(--accent-glow)':'var(--bg-tertiary)'};color:var(--text-secondary);border-radius:6px;cursor:pointer">${l}</button>`).join('')}
                </div>
            </div>
        </div>`;
    }

    if (viewEl.type === 'spacer') {
        const s = viewEl.styles || {};
        const desktopH = Number.isFinite(parseFloat(s.height)) ? Math.max(0, Math.min(1000, parseFloat(s.height))) : 50;
        const tabletH = Number.isFinite(parseFloat(s.heightTablet)) ? Math.max(0, Math.min(1000, parseFloat(s.heightTablet))) : desktopH;
        const mobileH = Number.isFinite(parseFloat(s.heightMobile)) ? Math.max(0, Math.min(1000, parseFloat(s.heightMobile))) : tabletH;
        html += `
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-arrows-alt-v"></i> Espaçamento responsivo</div>
            <div class="prop-group">
                <label class="prop-label">Desktop (px)</label>
                <input type="number" min="0" max="1000" class="prop-input" value="${esc(desktopH)}" onchange="updateStyle('${viewEl.id}','height',this.value)">
            </div>
            <div class="prop-group">
                <label class="prop-label">Tablet (px)</label>
                <input type="number" min="0" max="1000" class="prop-input" value="${esc(tabletH)}" onchange="updateStyle('${viewEl.id}','heightTablet',this.value)">
            </div>
            <div class="prop-group">
                <label class="prop-label">Mobile (px)</label>
                <input type="number" min="0" max="1000" class="prop-input" value="${esc(mobileH)}" onchange="updateStyle('${viewEl.id}','heightMobile',this.value)">
            </div>
            <small style="color:var(--text-tertiary);font-size:10px;line-height:1.4;">Use valores diferentes para controlar o espaço em cada dispositivo.</small>
        </div>`;

        html += `
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-eye"></i> Visibilidade</div>
            ${[['visibleDesktop','Desktop'],['visibleTablet','Tablet'],['visibleMobile','Mobile']].map(([prop,label])=>`
                <div class="prop-group">
                    <label style="display:flex;align-items:center;justify-content:space-between;gap:8px;">
                        <span class="prop-label" style="margin:0;">Mostrar no ${label}</span>
                        <input type="checkbox" ${s[prop] !== false ? 'checked' : ''} onchange="updateStyle('${viewEl.id}','${prop}',this.checked); updateProperties('${viewEl.id}')">
                    </label>
                </div>
            `).join('')}
            <small style="color:var(--text-tertiary);font-size:10px;">Desative para ocultar o espaço naquele dispositivo.</small>
        </div>`;
    }

    if (viewEl.type === 'button') {
        const s = viewEl.styles || {};
        const fonts = getAllFonts();
        const colorInput = (prop, fallback) => { const raw = s[prop] || fallback; const picker = /^#[0-9a-f]{6}$/i.test(raw) ? raw : fallback; return `<div class="color-row"><input type="color" value="${esc(picker)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"><input type="text" value="${esc(raw)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"></div>`; };
        html += `
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-mouse-pointer"></i> Conteúdo</div>
            <div class="prop-group"><label class="prop-label">Texto</label><input type="text" class="prop-input" value="${esc(viewEl.content || '')}" onchange="updateStyle('${viewEl.id}','content',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Link / URL</label><input type="url" class="prop-input" placeholder="https://..." value="${esc(s.link || '')}" onchange="updateStyle('${viewEl.id}','link',this.value)"></div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Abrir em nova aba</span><input type="checkbox" ${s.targetBlank ? 'checked' : ''} onchange="updateStyle('${viewEl.id}','targetBlank',this.checked)"></label></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-icons"></i> Ícone</div>
            <div class="prop-group"><label class="prop-label">Classe Font Awesome</label><input class="prop-input" placeholder="fa-star, fa-arrow-right..." value="${esc(s.icon || '')}" onchange="updateStyle('${viewEl.id}','icon',this.value)"></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;"><div class="prop-group"><label class="prop-label">Posição</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','iconPosition',this.value)"><option value="before" ${s.iconPosition !== 'after' ? 'selected' : ''}>Antes</option><option value="after" ${s.iconPosition === 'after' ? 'selected' : ''}>Depois</option></select></div><div class="prop-group"><label class="prop-label">Espaço (px)</label><input type="number" class="prop-input" value="${esc(s.iconGap ?? 8)}" onchange="updateStyle('${viewEl.id}','iconGap',this.value)"></div></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-font"></i> Tipografia</div>
            <div class="prop-group"><label class="prop-label">Font Family</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','fontFamily',this.value)">${fonts.map(f => `<option value="${esc(f)}" ${(s.fontFamily || 'Inter') === f ? 'selected' : ''}>${escapeHtml(f)}</option>`).join('')}</select></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;"><div class="prop-group"><label class="prop-label">Tamanho (px)</label><input type="number" min="1" class="prop-input" value="${esc(s.fontSize || 16)}" onchange="updateStyle('${viewEl.id}','fontSize',this.value)"></div><div class="prop-group"><label class="prop-label">Peso</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','fontWeight',this.value)">${['300','400','500','600','700','800','900'].map(w=>`<option value="${w}" ${(s.fontWeight || '600')===w?'selected':''}>${w}</option>`).join('')}</select></div></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;"><div class="prop-group"><label class="prop-label">Espaçamento (px)</label><input type="number" step="0.1" class="prop-input" value="${esc(s.letterSpacing || 0)}" onchange="updateStyle('${viewEl.id}','letterSpacing',this.value)"></div><div class="prop-group"><label class="prop-label">Altura linha</label><input type="number" step="0.1" class="prop-input" value="${esc(s.lineHeight || 1.2)}" onchange="updateStyle('${viewEl.id}','lineHeight',this.value)"></div></div>
            <div class="prop-group"><label class="prop-label">Cor do texto</label>${colorInput('textColor','#ffffff')}</div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-palette"></i> Cores</div>
            <div class="prop-group"><label class="prop-label">Fundo</label>${colorInput('bgColor','#3b82f6')}</div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Usar gradiente</span><input type="checkbox" ${s.gradient ? 'checked' : ''} onchange="updateStyle('${viewEl.id}','gradient',this.checked)"></label></div>
            <div class="prop-group"><label class="prop-label">Cor final do gradiente</label>${colorInput('gradientEnd','#60a5fa')}</div>
            <div class="prop-group"><label class="prop-label">Direção</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','gradientDirection',this.value)">${['to right','to left','to bottom','to top','135deg','45deg'].map(v=>`<option value="${v}" ${(s.gradientDirection||'to right')===v?'selected':''}>${v}</option>`).join('')}</select></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-arrows-alt-h"></i> Tamanho e espaçamento</div>
            <div class="prop-group"><label class="prop-label">Largura</label><div style="display:grid;grid-template-columns:1fr 90px;gap:8px;"><input type="number" min="1" step="1" class="prop-input" value="${esc(s.width && s.width !== 'auto' && s.width !== 'full' ? s.width : '')}" placeholder="Automática" onchange="updateStyle('${viewEl.id}','width',this.value || 'auto')"><select class="prop-select" onchange="updateStyle('${viewEl.id}','widthUnit',this.value)"><option value="px" ${(s.widthUnit||'px')==='px'?'selected':''}>px</option><option value="%" ${s.widthUnit==='%'?'selected':''}>%</option></select></div><small style="color:var(--text-tertiary);font-size:10px;">Digite a largura que quiser. Deixe vazio para automática.</small></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;"><div class="prop-group"><label class="prop-label">Altura (px)</label><input type="number" min="0" class="prop-input" value="${esc(s.height ?? 'auto')}" onchange="updateStyle('${viewEl.id}','height',this.value)"></div><div class="prop-group"><label class="prop-label">Opacidade</label><input type="number" min="0" max="1" step="0.1" class="prop-input" value="${esc(s.opacity ?? 1)}" onchange="updateStyle('${viewEl.id}','opacity',this.value)"></div></div>
            <label class="prop-label">Padding (px)</label><div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">${[['paddingTop','Topo',12],['paddingRight','Direita',24],['paddingBottom','Baixo',12],['paddingLeft','Esquerda',24]].map(([p,l,d])=>`<div class="prop-group"><label class="prop-label">${l}</label><input type="number" min="0" class="prop-input" value="${esc(s[p] ?? d)}" onchange="updateStyle('${viewEl.id}','${p}',this.value)"></div>`).join('')}</div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-border-style"></i> Bordas</div>
            <div class="prop-group"><label class="prop-label">Tipo</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','borderStyle',this.value)">${['solid','dashed','dotted','double','none'].map(v=>`<option value="${v}" ${(s.borderStyle||'solid')===v?'selected':''}>${v}</option>`).join('')}</select></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;"><div class="prop-group"><label class="prop-label">Espessura (px)</label><input type="number" min="0" max="20" class="prop-input" value="${esc(s.borderWidth ?? 0)}" onchange="updateStyle('${viewEl.id}','borderWidth',this.value)"></div><div class="prop-group"><label class="prop-label">Cor</label>${colorInput('borderColor','transparent')}</div></div>
            <div class="prop-group"><label class="prop-label">Raio dos cantos (px)</label><div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">${[['borderRadiusTL','Sup. Esq.'],['borderRadiusTR','Sup. Dir.'],['borderRadiusBR','Inf. Dir.'],['borderRadiusBL','Inf. Esq.']].map(([p,l])=>`<div><small>${l}</small><input type="number" min="0" class="prop-input" value="${esc(s[p] ?? s.borderRadius ?? 8)}" onchange="updateStyle('${viewEl.id}','${p}',this.value)"></div>`).join('')}</div></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-mouse"></i> Hover</div>
            <div class="prop-group"><label class="prop-label">Fundo no Hover</label>${colorInput('bgColorHover','#2563eb')}</div>
            <div class="prop-group"><label class="prop-label">Texto no Hover</label>${colorInput('textColorHover','#ffffff')}</div>
            <div class="prop-group"><label class="prop-label">Borda no Hover</label>${colorInput('borderColorHover','transparent')}</div>
            <div class="prop-group"><label class="prop-label">Efeito</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','hoverTransform',this.value)"><option value="none" ${(s.hoverTransform||'none')==='none'?'selected':''}>Nenhum</option><option value="up" ${s.hoverTransform==='up'?'selected':''}>Elevar</option><option value="down" ${s.hoverTransform==='down'?'selected':''}>Descer</option><option value="scale" ${s.hoverTransform==='scale'?'selected':''}>Zoom</option></select></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;"><div class="prop-group"><label class="prop-label">Zoom</label><input type="number" min="1" step="0.01" class="prop-input" value="${esc(s.hoverScale || 1.02)}" onchange="updateStyle('${viewEl.id}','hoverScale',this.value)"></div><div class="prop-group"><label class="prop-label">Transição (s)</label><input type="number" min="0" step="0.05" class="prop-input" value="${esc(s.hoverTransition || .25)}" onchange="updateStyle('${viewEl.id}','hoverTransition',this.value)"></div></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-adjust"></i> Sombra</div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Ativar sombra</span><input type="checkbox" ${s.shadow ? 'checked' : ''} onchange="updateStyle('${viewEl.id}','shadow',this.checked)"></label></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">${[['shadowX','X'],['shadowY','Y'],['shadowBlur','Desfoque'],['shadowSpread','Espalhamento']].map(([p,l])=>`<div class="prop-group"><label class="prop-label">${l}</label><input type="number" class="prop-input" value="${esc(s[p] ?? 0)}" onchange="updateStyle('${viewEl.id}','${p}',this.value)"></div>`).join('')}</div>
            <div class="prop-group"><label class="prop-label">Cor da sombra</label>${colorInput('shadowColor','rgba(0,0,0,0.18)')}</div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Sombra no Hover</span><input type="checkbox" ${s.hoverShadow !== false ? 'checked' : ''} onchange="updateStyle('${viewEl.id}','hoverShadow',this.checked)"></label></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-align-center"></i> Alinhamento</div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:5px;">${[['left','Esq.'],['center','Centro'],['right','Dir.']].map(([a,l])=>`<button type="button" onclick="updateStyle('${viewEl.id}','align','${a}')" style="height:34px;border:1px solid ${(s.align||'left')===a?'var(--accent)':'var(--border)'};background:${(s.align||'left')===a?'var(--accent-glow)':'var(--bg-tertiary)'};color:var(--text-secondary);border-radius:6px;cursor:pointer">${l}</button>`).join('')}</div>
        </div>`;
    }

    if (viewEl.type === 'counters') {
        const s=viewEl.styles||{}; const items=ensureCounterItems(s); const colorInput=(prop,fb)=>{const raw=s[prop]||fb; const picker=/^#[0-9a-f]{6}$/i.test(raw)?raw:fb; return `<div class="color-row"><input type="color" value="${esc(picker)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"><input type="text" value="${esc(raw)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"></div>`};
        html += `<div class="prop-section"><div class="prop-title"><i class="fas fa-chart-line"></i> Conteúdo</div><div class="prop-group"><label class="prop-label">Título</label><input class="prop-input" value="${esc(s.title||'Nossos números')}" onchange="updateStyle('${viewEl.id}','title',this.value)"></div><div class="prop-group"><label class="prop-label">Descrição</label><textarea class="prop-input" rows="2" onchange="updateStyle('${viewEl.id}','description',this.value)">${escapeHtml(s.description||'')}</textarea></div></div>`;
        html += `<div class="prop-section"><div class="prop-title"><i class="fas fa-list-ol"></i> Contadores</div><div style="font-size:10px;color:var(--text-tertiary);margin-bottom:8px;">Arraste pelo ☷ para mudar a ordem.</div>${items.map((it,i)=>`<div ondragover="allowCounterDrop(event)" ondrop="dropCounter(event,'${viewEl.id}',${i})" style="background:var(--bg-tertiary);border:1px solid var(--border);border-radius:8px;padding:10px;margin-bottom:8px;"><div style="display:flex;align-items:center;gap:7px;margin-bottom:8px;"><button type="button" draggable="true" ondragstart="startCounterDrag(event,'${viewEl.id}',${i})" style="width:32px;height:30px;border:1px solid var(--border);background:var(--bg-secondary);color:var(--text-secondary);border-radius:6px;cursor:grab;">☷</button><strong style="font-size:11px;flex:1;">Contador ${i+1}</strong><button type="button" onclick="removeCounterItem('${viewEl.id}',${i})" style="border:0;background:none;color:var(--error);font-size:18px;cursor:pointer;">×</button></div><div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Valor</label><input class="prop-input" value="${esc(it.value??'0')}" onchange="updateCounterItem('${viewEl.id}',${i},'value',this.value)"></div><div class="prop-group"><label class="prop-label">Rótulo</label><input class="prop-input" value="${esc(it.label||'')}" onchange="updateCounterItem('${viewEl.id}',${i},'label',this.value)"></div></div><div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Prefixo</label><input class="prop-input" value="${esc(it.prefix||'')}" placeholder="R$" onchange="updateCounterItem('${viewEl.id}',${i},'prefix',this.value)"></div><div class="prop-group"><label class="prop-label">Sufixo</label><input class="prop-input" value="${esc(it.suffix||'')}" placeholder="+ / %" onchange="updateCounterItem('${viewEl.id}',${i},'suffix',this.value)"></div></div><div class="prop-group"><label class="prop-label">Ícone (Font Awesome)</label><input class="prop-input" value="${esc(it.icon||'')}" placeholder="fa-users" onchange="updateCounterItem('${viewEl.id}',${i},'icon',this.value)"></div></div>`).join('')}<button type="button" onclick="addCounterItem('${viewEl.id}')" style="width:100%;height:36px;border:1px solid var(--accent);background:var(--accent-glow);color:var(--accent);border-radius:7px;cursor:pointer;font-weight:700;">+ Adicionar contador</button></div>`;
        html += `<div class="prop-section"><div class="prop-title"><i class="fas fa-th-large"></i> Layout</div><div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Largura</label><div style="display:grid;grid-template-columns:1fr 65px;gap:5px;"><input type="number" min="1" max="2000" class="prop-input" value="${esc(s.width??100)}" onchange="updateStyle('${viewEl.id}','width',this.value)"><select class="prop-select" onchange="updateStyle('${viewEl.id}','widthUnit',this.value)"><option value="%" ${(s.widthUnit||'%')==='%'?'selected':''}>%</option><option value="px" ${s.widthUnit==='px'?'selected':''}>px</option></select></div></div><div class="prop-group"><label class="prop-label">Colunas</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','columns',this.value)">${['1','2','3','4'].map(v=>`<option value="${v}" ${String(s.columns||3)===v?'selected':''}>${v}</option>`).join('')}</select></div></div><div class="prop-group"><label class="prop-label">Alinhamento</label><div style="display:grid;grid-template-columns:repeat(3,1fr);gap:5px;">${[['left','Esq.'],['center','Centro'],['right','Dir.']].map(([a,l])=>`<button type="button" onclick="updateStyle('${viewEl.id}','alignment','${a}')" style="height:34px;border:1px solid ${(s.alignment||'center')===a?'var(--accent)':'var(--border)'};background:${(s.alignment||'center')===a?'var(--accent-glow)':'var(--bg-tertiary)'};color:var(--text-secondary);border-radius:6px;cursor:pointer;">${l}</button>`).join('')}</div></div><div class="prop-group"><label class="prop-label">Espaço entre cards</label><input type="number" min="0" max="80" class="prop-input" value="${esc(s.gap??20)}" onchange="updateStyle('${viewEl.id}','gap',this.value)"></div></div>`;
        html += `<div class="prop-section"><div class="prop-title"><i class="fas fa-palette"></i> Visual</div><div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Tamanho do número</label><input type="number" min="12" max="120" class="prop-input" value="${esc(s.numberFontSize??42)}" onchange="updateStyle('${viewEl.id}','numberFontSize',this.value)"></div><div class="prop-group"><label class="prop-label">Peso</label><input type="number" min="300" max="900" step="100" class="prop-input" value="${esc(s.numberWeight??800)}" onchange="updateStyle('${viewEl.id}','numberWeight',this.value)"></div></div><div class="prop-group"><label class="prop-label">Cor do número</label>${colorInput('numberColor','#111827')}</div><div class="prop-group"><label class="prop-label">Cor do rótulo</label>${colorInput('labelColor','#4b5563')}</div><div class="prop-group"><label class="prop-label">Cor do ícone</label>${colorInput('iconColor','#3b82f6')}</div></div>`;
        html += `<div class="prop-section"><div class="prop-title"><i class="fas fa-border-all"></i> Cartão</div><div class="prop-group"><label class="prop-label">Fundo</label>${colorInput('cardBg','#ffffff')}</div><div class="prop-group"><label class="prop-label">Tipo de borda</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','cardBorderStyle',this.value)">${['solid','dashed','dotted','double','none'].map(v=>`<option value="${v}" ${(s.cardBorderStyle||'solid')===v?'selected':''}>${v}</option>`).join('')}</select></div><div class="prop-group"><label class="prop-label">Espessura (px)</label><input type="number" min="0" max="10" class="prop-input" value="${esc(s.cardBorderWidth??1)}" onchange="updateStyle('${viewEl.id}','cardBorderWidth',this.value)"></div><div class="prop-group"><label class="prop-label">Raio (px)</label><input type="number" min="0" max="60" class="prop-input" value="${esc(s.cardRadius??14)}" onchange="updateStyle('${viewEl.id}','cardRadius',this.value)"></div><div class="prop-group"><label class="prop-label">Padding (px)</label><input type="number" min="8" max="80" class="prop-input" value="${esc(s.cardPadding??24)}" onchange="updateStyle('${viewEl.id}','cardPadding',this.value)"></div></div>`;
        html += `<div class="prop-section"><div class="prop-title"><i class="fas fa-bolt"></i> Animação</div><div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Animar números</span><input type="checkbox" ${s.animate!==false?'checked':''} onchange="updateStyle('${viewEl.id}','animate',this.checked)"></label></div><div class="prop-group"><label class="prop-label">Duração (ms)</label><input type="number" min="200" max="5000" step="100" class="prop-input" value="${esc(s.duration??1200)}" onchange="updateStyle('${viewEl.id}','duration',this.value)"></div></div>`;
        html += `<div class="prop-section"><div class="prop-title"><i class="fas fa-cloud"></i> Sombra</div><div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Ativar sombra</span><input type="checkbox" ${s.shadow?'checked':''} onchange="updateStyle('${viewEl.id}','shadow',this.checked)"></label></div><div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">${[['shadowX','X'],['shadowY','Y'],['shadowBlur','Desfoque'],['shadowSpread','Espalhamento']].map(([pr,la])=>`<div class="prop-group"><label class="prop-label">${la}</label><input type="number" class="prop-input" value="${esc(s[pr]??0)}" onchange="updateStyle('${viewEl.id}','${pr}',this.value)"></div>`).join('')}</div><div class="prop-group"><label class="prop-label">Cor da sombra</label>${colorInput('shadowColor','rgba(0,0,0,0.10)')}</div></div>`;
    }

    if (viewEl.type === 'testimonials') {
        const s = viewEl.styles || {};
        const items = ensureTestimonialsItems(s);
        const colorInput = (prop, fallback) => {
            const raw = s[prop] || fallback;
            const picker = /^#[0-9a-f]{6}$/i.test(raw) ? raw : fallback;
            return `<div class="color-row"><input type="color" value="${esc(picker)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"><input type="text" value="${esc(raw)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"></div>`;
        };
        html += `
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-comments"></i> Conteúdo</div>
            <div class="prop-group"><label class="prop-label">Título</label><input class="prop-input" value="${esc(s.title || 'O que nossos clientes dizem')}" onchange="updateStyle('${viewEl.id}','title',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Descrição</label><textarea class="prop-input" rows="2" onchange="updateStyle('${viewEl.id}','description',this.value)">${escapeHtml(s.description || '')}</textarea></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-list"></i> Depoimentos</div>
            <div style="font-size:10px;color:var(--text-tertiary);margin-bottom:8px;">Arraste pelo botão ☷. Edite os campos normalmente.</div>
            ${items.map((item,index)=>`<div draggable="true" ondragstart="startTestimonialDrag(event,'${viewEl.id}',${index})" ondragover="allowTestimonialDrop(event)" ondrop="dropTestimonial(event,'${viewEl.id}',${index})" style="background:var(--bg-tertiary);border:1px solid var(--border);border-radius:8px;padding:10px;margin-bottom:8px;">
                <div style="display:flex;align-items:center;gap:7px;margin-bottom:8px;"><button type="button" title="Arrastar" style="width:32px;height:30px;border:1px solid var(--border);background:var(--bg-secondary);color:var(--text-secondary);border-radius:6px;cursor:grab;">☷</button><strong style="font-size:11px;flex:1;">Depoimento ${index+1}</strong><button type="button" onclick="removeTestimonialItem('${viewEl.id}',${index})" style="border:0;background:none;color:var(--error);font-size:18px;cursor:pointer;">×</button></div>
                <div class="prop-group"><label class="prop-label">Depoimento</label><textarea class="prop-input" rows="3" onchange="updateTestimonialItem('${viewEl.id}',${index},'text',this.value)">${escapeHtml(item.text || '')}</textarea></div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Nome</label><input class="prop-input" value="${esc(item.name || '')}" onchange="updateTestimonialItem('${viewEl.id}',${index},'name',this.value)"></div><div class="prop-group"><label class="prop-label">Cargo / empresa</label><input class="prop-input" value="${esc(item.role || '')}" onchange="updateTestimonialItem('${viewEl.id}',${index},'role',this.value)"></div></div>
                <div class="prop-group"><label class="prop-label">Foto</label><input class="prop-input" value="${esc(item.avatar || '')}" placeholder="URL da foto" onchange="updateTestimonialItem('${viewEl.id}',${index},'avatar',this.value)"></div>
                <label style="display:block;padding:9px;border:1px dashed var(--border);border-radius:7px;text-align:center;cursor:pointer;margin-bottom:8px;background:var(--bg-secondary);"><i class="fas fa-upload" style="color:var(--accent);margin-right:5px;"></i> Enviar foto <input type="file" accept="image/*" style="display:none" onchange="handleTestimonialAvatarUpload(this,'${viewEl.id}',${index})"></label>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Avaliação (0–5)</label><input type="number" min="0" max="5" step="1" class="prop-input" value="${esc(item.rating ?? 5)}" onchange="updateTestimonialItem('${viewEl.id}',${index},'rating',this.value)"></div></div>
            </div>`).join('')}
            <button type="button" onclick="addTestimonialItem('${viewEl.id}')" style="width:100%;height:36px;border:1px solid var(--accent);background:var(--accent-glow);color:var(--accent);border-radius:7px;cursor:pointer;font-weight:700;">+ Adicionar depoimento</button>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-th-large"></i> Layout</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;"><div class="prop-group"><label class="prop-label">Largura</label><div style="display:grid;grid-template-columns:1fr 70px;gap:6px;"><input class="prop-input" type="number" min="1" value="${esc(s.width ?? '100')}" onchange="updateStyle('${viewEl.id}','width',this.value)"><select class="prop-select" onchange="updateStyle('${viewEl.id}','widthUnit',this.value)"><option value="%" ${(s.widthUnit||'%')==='%'?'selected':''}>%</option><option value="px" ${s.widthUnit==='px'?'selected':''}>px</option></select></div></div><div class="prop-group"><label class="prop-label">Colunas</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','columns',this.value)">${['1','2','3','4'].map(v=>`<option value="${v}" ${String(s.columns||'3')===v?'selected':''}>${v} ${v==='1'?'coluna':'colunas'}</option>`).join('')}</select></div></div>
            <div class="prop-group"><label class="prop-label">Alinhamento</label><div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px;">${[['left','Esq.'],['center','Centro'],['right','Dir.']].map(([a,l])=>`<button type="button" onclick="updateStyle('${viewEl.id}','alignment','${a}')" style="height:34px;border:1px solid ${(s.alignment||'center')===a?'var(--accent)':'var(--border)'};background:${(s.alignment||'center')===a?'var(--accent-glow)':'var(--bg-tertiary)'};color:var(--text-secondary);border-radius:6px;cursor:pointer;">${l}</button>`).join('')}</div></div>
            <div style="margin-top:8px;padding:9px 10px;border:1px dashed var(--border);border-radius:7px;color:var(--text-tertiary);font-size:10px;line-height:1.45;">As colunas se adaptam automaticamente à largura disponível. Em áreas menores, 4/3 colunas passam para 2 e depois 1, evitando cards espremidos.</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;"><div class="prop-group"><label class="prop-label">Espaço entre cards (px)</label><input type="number" min="0" max="80" class="prop-input" value="${esc(s.gap ?? 20)}" onchange="updateStyle('${viewEl.id}','gap',this.value)"></div><div class="prop-group"><label class="prop-label">Arredondamento (px)</label><input type="number" min="0" max="60" class="prop-input" value="${esc(s.cardRadius ?? 14)}" onchange="updateStyle('${viewEl.id}','cardRadius',this.value)"></div></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-palette"></i> Cores</div>
            <div class="prop-group"><label class="prop-label">Fundo dos cards</label>${colorInput('cardBg','#ffffff')}</div><div class="prop-group"><label class="prop-label">Cor do texto</label>${colorInput('textColor','#374151')}</div><div class="prop-group"><label class="prop-label">Cor do nome</label>${colorInput('nameColor','#111827')}</div><div class="prop-group"><label class="prop-label">Cor do cargo</label>${colorInput('roleColor','#6b7280')}</div><div class="prop-group"><label class="prop-label">Cor das estrelas</label>${colorInput('starColor','#f59e0b')}</div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-text-height"></i> Tipografia</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;"><div class="prop-group"><label class="prop-label">Título (px)</label><input type="number" min="10" class="prop-input" value="${esc(s.titleFontSize ?? 30)}" oninput="updateStyleLive('${viewEl.id}','titleFontSize',this.value)" onblur="updateStyle('${viewEl.id}','titleFontSize',this.value)"></div><div class="prop-group"><label class="prop-label">Descrição (px)</label><input type="number" min="10" class="prop-input" value="${esc(s.descriptionFontSize ?? 14)}" oninput="updateStyleLive('${viewEl.id}','descriptionFontSize',this.value)" onblur="updateStyle('${viewEl.id}','descriptionFontSize',this.value)"></div></div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:6px;"><div class="prop-group"><label class="prop-label">Texto</label><input type="number" min="10" class="prop-input" value="${esc(s.textFontSize ?? 15)}" onchange="updateStyle('${viewEl.id}','textFontSize',this.value)"></div><div class="prop-group"><label class="prop-label">Nome</label><input type="number" min="10" class="prop-input" value="${esc(s.nameFontSize ?? 16)}" onchange="updateStyle('${viewEl.id}','nameFontSize',this.value)"></div><div class="prop-group"><label class="prop-label">Cargo</label><input type="number" min="9" class="prop-input" value="${esc(s.roleFontSize ?? 13)}" onchange="updateStyle('${viewEl.id}','roleFontSize',this.value)"></div></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-image"></i> Avatar</div>
            <div class="prop-group"><label class="prop-label">Tamanho (px)</label><input type="number" min="24" max="180" class="prop-input" value="${esc(s.avatarSize ?? 56)}" onchange="updateStyle('${viewEl.id}','avatarSize',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Formato</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','avatarRadius',this.value)"><option value="50" ${String(s.avatarRadius??50)==='50'?'selected':''}>Circular</option><option value="20" ${String(s.avatarRadius??50)==='20'?'selected':''}>Arredondado</option><option value="0" ${String(s.avatarRadius??50)==='0'?'selected':''}>Quadrado</option></select></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-icons"></i> Ícone</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Tamanho do ícone (px)</label><input type="number" min="10" max="120" class="prop-input" value="${val(s.iconSize ?? 28)}" onchange="updateStyle('${viewEl.id}','iconSize',this.value)"></div><div class="prop-group"><label class="prop-label">Tamanho do círculo (px)</label><input type="number" min="40" max="180" class="prop-input" value="${val(s.iconBoxSize ?? 76)}" onchange="updateStyle('${viewEl.id}','iconBoxSize',this.value)"></div></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Formato</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','iconRadius',this.value)"><option value="50" ${String(s.iconRadius??50)==='50'?'selected':''}>Circular</option><option value="20" ${String(s.iconRadius??50)==='20'?'selected':''}>Arredondado</option><option value="0" ${String(s.iconRadius??50)==='0'?'selected':''}>Quadrado</option></select></div><div class="prop-group"><label class="prop-label">Animação no hover</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','iconHoverAnimation',this.value)"><option value="none" ${(s.iconHoverAnimation||'none')==='none'?'selected':''}>Nenhuma</option><option value="pulse" ${s.iconHoverAnimation==='pulse'?'selected':''}>Pulso</option><option value="rotate" ${s.iconHoverAnimation==='rotate'?'selected':''}>Girar</option><option value="bounce" ${s.iconHoverAnimation==='bounce'?'selected':''}>Saltar</option></select></div></div>
            <div class="prop-group"><label class="prop-label">Tipo de borda do ícone</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','iconBorderStyle',this.value)">${['solid','dashed','dotted','double','none'].map(v=>`<option value="${v}" ${(s.iconBorderStyle||'none')===v?'selected':''}>${v}</option>`).join('')}</select></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Espessura da borda (px)</label><input type="number" min="0" max="8" class="prop-input" value="${val(s.iconBorderWidth ?? 0)}" onchange="updateStyle('${viewEl.id}','iconBorderWidth',this.value)"></div><div class="prop-group"><label class="prop-label">Cor da borda</label>${colorInput('iconBorderColor','#dbeafe')}</div></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-border-all"></i> Cartão</div>
            <div class="prop-group"><label class="prop-label">Tipo de borda</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','cardBorderStyle',this.value)">${['solid','dashed','dotted','double','none'].map(v=>`<option value="${v}" ${(s.cardBorderStyle||'solid')===v?'selected':''}>${v}</option>`).join('')}</select></div>
            <div class="prop-group"><label class="prop-label">Espessura (px)</label><input type="number" min="0" max="10" class="prop-input" value="${esc(s.cardBorderWidth ?? 1)}" onchange="updateStyle('${viewEl.id}','cardBorderWidth',this.value)"></div><div class="prop-group"><label class="prop-label">Cor da borda</label>${colorInput('cardBorderColor','#e5e7eb')}</div>
            <div class="prop-group"><label class="prop-label">Padding (px)</label><input type="number" min="8" max="80" class="prop-input" value="${esc(s.cardPadding ?? 24)}" onchange="updateStyle('${viewEl.id}','cardPadding',this.value)"></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-wand-magic-sparkles"></i> Hover</div>
            <div class="prop-group"><label class="prop-label">Fundo no Hover</label>${colorInput('hoverBg','#f8fafc')}</div><div class="prop-group"><label class="prop-label">Elevação (px)</label><input type="number" min="0" max="12" class="prop-input" value="${esc(s.hoverLift ?? 3)}" onchange="updateStyle('${viewEl.id}','hoverLift',this.value)"></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-cloud"></i> Sombra</div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Ativar sombra</span><input type="checkbox" ${s.shadow?'checked':''} onchange="updateStyle('${viewEl.id}','shadow',this.checked)"></label></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">${[['shadowX','X'],['shadowY','Y'],['shadowBlur','Desfoque'],['shadowSpread','Espalhamento']].map(([p,l])=>`<div class="prop-group"><label class="prop-label">${l}</label><input type="number" class="prop-input" value="${esc(s[p] ?? 0)}" onchange="updateStyle('${viewEl.id}','${p}',this.value)"></div>`).join('')}</div>
            <div class="prop-group"><label class="prop-label">Cor da sombra</label>${colorInput('shadowColor','rgba(0,0,0,0.10)')}</div>
        </div>`;
    }

    if (viewEl.type === 'features') {
        const s = viewEl.styles || {};
        const items = ensureFeatureItems(s);
        const val = (v) => escapeAttr(v == null ? '' : String(v));
        const colorInput = (prop, fallback) => {
            const raw = String(s[prop] || fallback);
            const picker = /^#[0-9a-f]{6}$/i.test(raw) ? raw : fallback;
            return `<div class="color-row"><input type="color" value="${escapeAttr(picker)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"><input type="text" class="prop-input" value="${escapeAttr(raw)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"></div>`;
        };
        html += `
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-star"></i> Recursos</div>
            <div class="prop-group"><label class="prop-label">Título</label><input class="prop-input" value="${val(s.title || '')}" onchange="updateStyle('${viewEl.id}','title',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Descrição</label><textarea class="prop-input" rows="2" onchange="updateStyle('${viewEl.id}','description',this.value)">${escapeHtml(s.description || '')}</textarea></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-list"></i> Itens</div>
            <div style="font-size:10px;color:var(--text-tertiary);line-height:1.5;margin-bottom:10px;">Adicione, edite, exclua e arraste pelo ☷ para mudar a ordem.</div>
            ${items.map((item,index)=>`<div style="background:var(--bg-tertiary);border:1px solid var(--border);border-radius:8px;padding:10px;margin-bottom:9px;" ondragover="allowFeatureDrop(event)" ondrop="dropFeature(event,'${viewEl.id}',${index})">
                <div style="display:flex;align-items:center;gap:7px;margin-bottom:9px;"><button type="button" draggable="true" title="Arrastar" ondragstart="startFeatureDrag(event,'${viewEl.id}',${index})" style="width:32px;height:30px;border:1px solid var(--border);background:var(--bg-secondary);color:var(--text-secondary);border-radius:6px;cursor:grab;">☷</button><strong style="font-size:11px;flex:1;">Recurso ${index+1}</strong><button type="button" onclick="removeFeatureItem('${viewEl.id}',${index})" style="border:0;background:none;color:var(--error);font-size:18px;cursor:pointer;">×</button></div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Nome</label><input class="prop-input" value="${val(item.name || '')}" onchange="updateFeatureItem('${viewEl.id}',${index},'name',this.value)"></div><div class="prop-group"><label class="prop-label">Ícone (Font Awesome)</label><input class="prop-input" value="${val(item.icon || 'fa-star')}" placeholder="fa-star" onchange="updateFeatureItem('${viewEl.id}',${index},'icon',this.value)"></div></div>
                <div class="prop-group"><label class="prop-label">Descrição</label><textarea class="prop-input" rows="2" onchange="updateFeatureItem('${viewEl.id}',${index},'description',this.value)">${escapeHtml(item.description || '')}</textarea></div>
            </div>`).join('')}
            <button type="button" onclick="addFeatureItem('${viewEl.id}')" style="width:100%;height:36px;border:1px solid var(--accent);background:var(--accent-glow);color:var(--accent);border-radius:7px;cursor:pointer;font-weight:700;"><i class="fas fa-plus"></i> Adicionar recurso</button>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-th-large"></i> Layout</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Largura</label><div style="display:grid;grid-template-columns:1fr 65px;gap:6px;"><input type="number" min="1" max="2000" class="prop-input" value="${val(s.width ?? 100)}" onchange="updateStyle('${viewEl.id}','width',this.value)"><select class="prop-select" onchange="updateStyle('${viewEl.id}','widthUnit',this.value)"><option value="%" ${(s.widthUnit||'%')==='%'?'selected':''}>%</option><option value="px" ${s.widthUnit==='px'?'selected':''}>px</option></select></div></div><div class="prop-group"><label class="prop-label">Colunas</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','columns',this.value)">${['1','2','3','4'].map(v=>`<option value="${v}" ${String(s.columns||'3')===v?'selected':''}>${v} ${v==='1'?'coluna':'colunas'}</option>`).join('')}</select></div></div>
            <div class="prop-group"><label class="prop-label">Alinhamento</label><div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px;">${[['left','Esq.'],['center','Centro'],['right','Dir.']].map(([a,l])=>`<button type="button" onclick="updateStyle('${viewEl.id}','alignment','${a}')" style="height:34px;border:1px solid ${(s.alignment||'center')===a?'var(--accent)':'var(--border)'};background:${(s.alignment||'center')===a?'var(--accent-glow)':'var(--bg-tertiary)'};color:var(--text-secondary);border-radius:6px;cursor:pointer;">${l}</button>`).join('')}</div></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Espaço entre cards (px)</label><input type="number" min="0" max="80" class="prop-input" value="${val(s.gap ?? 20)}" onchange="updateStyle('${viewEl.id}','gap',this.value)"></div><div class="prop-group"><label class="prop-label">Raio dos cards (px)</label><input type="number" min="0" max="60" class="prop-input" value="${val(s.cardRadius ?? 14)}" onchange="updateStyle('${viewEl.id}','cardRadius',this.value)"></div></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Alinhamento do conteúdo dos cards</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','cardTextAlign',this.value)"><option value="left" ${s.cardTextAlign==='left'?'selected':''}>Esquerda</option><option value="center" ${(s.cardTextAlign||'center')==='center'?'selected':''}>Centro</option><option value="right" ${s.cardTextAlign==='right'?'selected':''}>Direita</option></select></div><div class="prop-group"><label class="prop-label">Fundo da seção</label>${colorInput('sectionBg','#ffffff')}</div></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Raio da seção (px)</label><input type="number" min="0" max="80" class="prop-input" value="${val(s.sectionRadius ?? 0)}" onchange="updateStyle('${viewEl.id}','sectionRadius',this.value)"></div><div class="prop-group"><label class="prop-label">Espaçamento vertical da seção (px)</label><input type="number" min="0" max="120" class="prop-input" value="${val(s.sectionPaddingY ?? 0)}" onchange="updateStyle('${viewEl.id}','sectionPaddingY',this.value)"></div></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-palette"></i> Cores</div>
            <div class="prop-group"><label class="prop-label">Cor do título</label>${colorInput('titleColor','#111827')}</div>
            <div class="prop-group"><label class="prop-label">Cor da descrição</label>${colorInput('descriptionColor','#6b7280')}</div>
            <div class="prop-group"><label class="prop-label">Fundo dos cards</label>${colorInput('cardBg','#ffffff')}</div>
            <div class="prop-group"><label class="prop-label">Cor dos ícones</label>${colorInput('iconColor','#3b82f6')}</div>
            <div class="prop-group"><label class="prop-label">Fundo dos ícones</label>${colorInput('iconBg','#eff6ff')}</div>
            <div class="prop-group"><label class="prop-label">Cor dos títulos dos cards</label>${colorInput('nameColor','#111827')}</div>
            <div class="prop-group"><label class="prop-label">Cor dos textos</label>${colorInput('textColor','#4b5563')}</div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-text-height"></i> Tipografia</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Título (px)</label><input type="number" min="10" class="prop-input" value="${val(s.titleFontSize ?? 28)}" oninput="updateStyleLive('${viewEl.id}','titleFontSize',this.value)" onblur="updateStyle('${viewEl.id}','titleFontSize',this.value)"></div><div class="prop-group"><label class="prop-label">Descrição (px)</label><input type="number" min="10" class="prop-input" value="${val(s.descriptionFontSize ?? 14)}" oninput="updateStyleLive('${viewEl.id}','descriptionFontSize',this.value)" onblur="updateStyle('${viewEl.id}','descriptionFontSize',this.value)"></div></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;"><div class="prop-group"><label class="prop-label">Nome</label><input type="number" min="10" class="prop-input" value="${val(s.nameFontSize ?? 18)}" onchange="updateStyle('${viewEl.id}','nameFontSize',this.value)"></div><div class="prop-group"><label class="prop-label">Texto</label><input type="number" min="10" class="prop-input" value="${val(s.textFontSize ?? 14)}" onchange="updateStyle('${viewEl.id}','textFontSize',this.value)"></div></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-border-all"></i> Cartão</div>
            <div class="prop-group"><label class="prop-label">Tipo de borda</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','cardBorderStyle',this.value)">${['solid','dashed','dotted','double','none'].map(v=>`<option value="${v}" ${(s.cardBorderStyle||'solid')===v?'selected':''}>${v}</option>`).join('')}</select></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Espessura (px)</label><input type="number" min="0" max="10" class="prop-input" value="${val(s.cardBorderWidth ?? 1)}" onchange="updateStyle('${viewEl.id}','cardBorderWidth',this.value)"></div><div class="prop-group"><label class="prop-label">Padding (px)</label><input type="number" min="8" max="100" class="prop-input" value="${val(s.cardPadding ?? 24)}" onchange="updateStyle('${viewEl.id}','cardPadding',this.value)"></div></div>
            <div class="prop-group"><label class="prop-label">Cor da borda</label>${colorInput('cardBorderColor','#e5e7eb')}</div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-magic"></i> Hover</div>
            <div class="prop-group"><label class="prop-label">Fundo no Hover</label>${colorInput('hoverBg','#f8fafc')}</div>
            <div class="prop-group"><label class="prop-label">Elevação (px)</label><input type="number" min="0" max="20" class="prop-input" value="${val(s.hoverLift ?? 3)}" onchange="updateStyle('${viewEl.id}','hoverLift',this.value)"></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-cloud"></i> Sombra</div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Ativar sombra</span><input type="checkbox" ${s.shadow?'checked':''} onchange="updateStyle('${viewEl.id}','shadow',this.checked)"></label></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">${[['shadowX','X'],['shadowY','Y'],['shadowBlur','Desfoque'],['shadowSpread','Espalhamento']].map(([p,l])=>`<div class="prop-group"><label class="prop-label">${l}</label><input type="number" class="prop-input" value="${val(s[p] ?? 0)}" onchange="updateStyle('${viewEl.id}','${p}',this.value)"></div>`).join('')}</div>
            <div class="prop-group"><label class="prop-label">Cor da sombra</label>${colorInput('shadowColor','rgba(0,0,0,0.10)')}</div>
        </div>`;
    }

    if (viewEl.type === 'timeline') {
        const s = viewEl.styles || {};
        const items = ensureTimelineItems(s);
        const val = (v='') => escapeAttr(v == null ? '' : String(v));
        const colorInput = (prop, fallback) => {
            const raw = s[prop] || fallback;
            const picker = /^#[0-9a-f]{6}$/i.test(raw) ? raw : fallback;
            return `<div class="color-row"><input type="color" value="${esc(picker)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"><input type="text" value="${esc(raw)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"></div>`;
        };
        html += `
        <div class="prop-section"><div class="prop-title"><i class="fas fa-stream"></i> Conteúdo</div>
            <div class="prop-group"><label class="prop-label">Título</label><input class="prop-input" value="${val(s.title||'Nossa história')}" onchange="updateStyle('${viewEl.id}','title',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Descrição</label><textarea class="prop-input" rows="2" onchange="updateStyle('${viewEl.id}','description',this.value)">${escapeHtml(s.description||'')}</textarea></div>
        </div>
        <div class="prop-section"><div class="prop-title"><i class="fas fa-list"></i> Eventos</div>
            <div style="font-size:10px;color:var(--text-tertiary);margin-bottom:8px;">Arraste pelo botão ☷ para alterar a ordem.</div>
            ${items.map((item,index)=>`<div style="border:1px solid var(--border);background:var(--bg-tertiary);border-radius:8px;padding:10px;margin-bottom:8px;" ondragover="allowTimelineDrop(event)" ondrop="dropTimeline(event,'${viewEl.id}',${index})">
                <div style="display:flex;align-items:center;gap:7px;margin-bottom:8px;"><button type="button" draggable="true" title="Arrastar" ondragstart="startTimelineDrag(event,'${viewEl.id}',${index})" style="width:32px;height:30px;border:1px solid var(--border);background:var(--bg-secondary);color:var(--text-secondary);border-radius:6px;cursor:grab;">☷</button><strong style="font-size:11px;flex:1;">Evento ${index+1}</strong><button type="button" onclick="removeTimelineItem('${viewEl.id}',${index})" style="border:0;background:none;color:var(--error);font-size:18px;cursor:pointer;">×</button></div>
                <div style="display:grid;grid-template-columns:90px 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Data</label><input class="prop-input" value="${val(item.date||'')}" onchange="updateTimelineItem('${viewEl.id}',${index},'date',this.value)"></div><div class="prop-group"><label class="prop-label">Título</label><input class="prop-input" value="${val(item.title||'')}" onchange="updateTimelineItem('${viewEl.id}',${index},'title',this.value)"></div></div>
                <div class="prop-group"><label class="prop-label">Descrição</label><textarea class="prop-input" rows="3" onchange="updateTimelineItem('${viewEl.id}',${index},'text',this.value)"></textarea></div>
                <div class="prop-group"><label class="prop-label">Ícone</label><select class="prop-select" onchange="updateTimelineItem('${viewEl.id}',${index},'icon',this.value)">
                    ${[
                        ['fa-circle','Círculo'],['fa-flag','Bandeira'],['fa-rocket','Foguete'],['fa-star','Estrela'],['fa-check','Check'],['fa-heart','Coração'],['fa-user','Pessoa'],['fa-users','Equipe'],['fa-building','Empresa'],['fa-briefcase','Trabalho'],['fa-bullseye','Objetivo'],['fa-trophy','Prêmio'],['fa-lightbulb','Ideia'],['fa-calendar','Calendário'],['fa-clock','Relógio'],['fa-location-dot','Localização'],['fa-house','Casa'],['fa-graduation-cap','Educação'],['fa-book','Livro'],['fa-medal','Medalha'],['fa-chart-line','Crescimento'],['fa-arrow-up','Subida'],['fa-arrow-right','Avanço'],['fa-check-circle','Concluído'],['fa-circle-play','Início'],['fa-bolt','Energia'],['fa-cog','Configuração'],['fa-phone','Telefone'],['fa-envelope','E-mail'],['fa-globe','Globo']
                    ].map(([v,l])=>`<option value="${v}" ${String(item.icon||'fa-circle')===v?'selected':''}>${l}</option>`).join('')}
                </select></div>
            </div>`).join('')}
            <button type="button" onclick="addTimelineItem('${viewEl.id}')" style="width:100%;height:36px;border:1px solid var(--accent);background:var(--accent-glow);color:var(--accent);border-radius:7px;cursor:pointer;font-weight:700;"><i class="fas fa-plus"></i> Adicionar evento</button>
        </div>
        <div class="prop-section"><div class="prop-title"><i class="fas fa-th-large"></i> Layout</div>
            <div class="prop-group"><label class="prop-label">Orientação</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','orientation',this.value)"><option value="vertical" ${(s.orientation||'vertical')==='vertical'?'selected':''}>Vertical</option><option value="horizontal" ${s.orientation==='horizontal'?'selected':''}>Horizontal</option></select></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Largura</label><div style="display:grid;grid-template-columns:1fr 65px;gap:6px;"><input type="number" min="1" max="2000" class="prop-input" value="${val(s.width??100)}" onchange="updateStyle('${viewEl.id}','width',this.value)"><select class="prop-select" onchange="updateStyle('${viewEl.id}','widthUnit',this.value)"><option value="%" ${(s.widthUnit||'%')==='%'?'selected':''}>%</option><option value="px" ${s.widthUnit==='px'?'selected':''}>px</option></select></div></div><div class="prop-group"><label class="prop-label">Colunas (horizontal)</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','columns',this.value)">${['1','2','3','4'].map(v=>`<option value="${v}" ${String(s.columns||3)===v?'selected':''}>${v}</option>`).join('')}</select></div></div>
            <div class="prop-group"><label class="prop-label">Alinhamento</label><div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px;">${[['left','Esq.'],['center','Centro'],['right','Dir.']].map(([a,l])=>`<button type="button" onclick="updateStyle('${viewEl.id}','alignment','${a}')" style="height:34px;border:1px solid ${(s.alignment||'center')===a?'var(--accent)':'var(--border)'};background:${(s.alignment||'center')===a?'var(--accent-glow)':'var(--bg-tertiary)'};color:var(--text-secondary);border-radius:6px;cursor:pointer;">${l}</button>`).join('')}</div></div>
            <div class="prop-group"><label class="prop-label">Espaço entre eventos (px)</label><input type="number" min="8" max="100" class="prop-input" value="${val(s.gap??28)}" onchange="updateStyle('${viewEl.id}','gap',this.value)"></div>
        </div>
        <div class="prop-section"><div class="prop-title"><i class="fas fa-palette"></i> Cores e linha</div>
            <div class="prop-group"><label class="prop-label">Cor da linha</label>${colorInput('lineColor','#dbe3ef')}</div>
            <div class="prop-group"><label class="prop-label">Espessura da linha (px)</label><input type="number" min="1" max="12" class="prop-input" value="${val(s.lineWidth??3)}" onchange="updateStyle('${viewEl.id}','lineWidth',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Fundo do marcador</label>${colorInput('markerBg','#3b82f6')}</div>
            <div class="prop-group"><label class="prop-label">Cor do ícone</label>${colorInput('markerColor','#ffffff')}</div>
        </div>
        `;
    }

    if (viewEl.type === 'team') {
        const s = viewEl.styles || {};
        const items = ensureTeamItems(s);
        const val = (v='') => escapeAttr(v == null ? '' : String(v));
        const colorInput = (prop, fallback) => {
            const raw = s[prop] || fallback;
            const picker = /^#[0-9a-f]{6}$/i.test(raw) ? raw : fallback;
            return `<div class="color-row"><input type="color" value="${esc(picker)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"><input type="text" value="${esc(raw)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"></div>`;
        };
        html += `
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-users"></i> Conteúdo da equipe</div>
            <div class="prop-group"><label class="prop-label">Título</label><input class="prop-input" value="${val(s.title || 'Nossa equipe')}" onchange="updateStyle('${viewEl.id}','title',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Descrição</label><textarea class="prop-input" rows="2" onchange="updateStyle('${viewEl.id}','description',this.value)">${escapeHtml(s.description || '')}</textarea></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-list"></i> Membros</div>
            <div style="font-size:10px;color:var(--text-tertiary);line-height:1.5;margin-bottom:8px;">Arraste pelo botão ☷ para mudar a ordem.</div>
            ${items.map((item,index)=>`<div style="border:1px solid var(--border);background:var(--bg-tertiary);border-radius:8px;padding:10px;margin-bottom:8px;" ondragover="allowTeamDrop(event)" ondrop="dropTeam(event,'${viewEl.id}',${index})">
                <div style="display:flex;align-items:center;gap:7px;margin-bottom:8px;"><button type="button" draggable="true" title="Arrastar" ondragstart="startTeamDrag(event,'${viewEl.id}',${index})" style="width:32px;height:30px;border:1px solid var(--border);background:var(--bg-secondary);color:var(--text-secondary);border-radius:6px;cursor:grab;">☷</button><strong style="font-size:11px;flex:1;">Membro ${index+1}</strong><button type="button" onclick="removeTeamItem('${viewEl.id}',${index})" style="border:0;background:none;color:var(--error);font-size:18px;cursor:pointer;">×</button></div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Nome</label><input class="prop-input" value="${val(item.name || '')}" onchange="updateTeamItem('${viewEl.id}',${index},'name',this.value)"></div><div class="prop-group"><label class="prop-label">Cargo / função</label><input class="prop-input" value="${val(item.role || '')}" onchange="updateTeamItem('${viewEl.id}',${index},'role',this.value)"></div></div>
                <div class="prop-group"><label class="prop-label">Descrição</label><textarea class="prop-input" rows="2" onchange="updateTeamItem('${viewEl.id}',${index},'description',this.value)">${escapeHtml(item.description || '')}</textarea></div>
                <div class="prop-group"><label class="prop-label">Foto (URL)</label><input class="prop-input" value="${val(item.avatar || '')}" placeholder="https://..." onchange="updateTeamItem('${viewEl.id}',${index},'avatar',this.value)"></div>
                <label style="display:block;padding:9px;border:1px dashed var(--border);border-radius:7px;text-align:center;cursor:pointer;margin-bottom:8px;background:var(--bg-secondary);"><i class="fas fa-upload" style="color:var(--accent);margin-right:5px;"></i> Enviar foto <input type="file" accept="image/*" style="display:none" onchange="handleTeamAvatarUpload(this,'${viewEl.id}',${index})"></label>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">LinkedIn</label><input class="prop-input" value="${val(item.linkedin || '')}" placeholder="URL" onchange="updateTeamItem('${viewEl.id}',${index},'linkedin',this.value)"></div><div class="prop-group"><label class="prop-label">Instagram</label><input class="prop-input" value="${val(item.instagram || '')}" placeholder="URL" onchange="updateTeamItem('${viewEl.id}',${index},'instagram',this.value)"></div></div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Facebook</label><input class="prop-input" value="${val(item.facebook || '')}" placeholder="URL" onchange="updateTeamItem('${viewEl.id}',${index},'facebook',this.value)"></div><div class="prop-group"><label class="prop-label">Site</label><input class="prop-input" value="${val(item.website || '')}" placeholder="URL" onchange="updateTeamItem('${viewEl.id}',${index},'website',this.value)"></div></div>
            </div>`).join('')}
            <button type="button" onclick="addTeamItem('${viewEl.id}')" style="width:100%;height:36px;border:1px solid var(--accent);background:var(--accent-glow);color:var(--accent);border-radius:7px;cursor:pointer;font-weight:700;"><i class="fas fa-plus"></i> Adicionar membro</button>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-th-large"></i> Layout</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Largura</label><div style="display:grid;grid-template-columns:1fr 65px;gap:6px;"><input type="number" min="1" max="2000" class="prop-input" value="${val(s.width ?? 100)}" onchange="updateStyle('${viewEl.id}','width',this.value)"><select class="prop-select" onchange="updateStyle('${viewEl.id}','widthUnit',this.value)"><option value="%" ${(s.widthUnit||'%')==='%'?'selected':''}>%</option><option value="px" ${s.widthUnit==='px'?'selected':''}>px</option></select></div></div><div class="prop-group"><label class="prop-label">Colunas</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','columns',this.value)">${['1','2','3','4'].map(v=>`<option value="${v}" ${String(s.columns||'3')===v?'selected':''}>${v} ${v==='1'?'coluna':'colunas'}</option>`).join('')}</select></div></div>
            <div class="prop-group"><label class="prop-label">Alinhamento</label><div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px;">${[['left','Esq.'],['center','Centro'],['right','Dir.']].map(([a,l])=>`<button type="button" onclick="updateStyle('${viewEl.id}','alignment','${a}')" style="height:34px;border:1px solid ${(s.alignment||'center')===a?'var(--accent)':'var(--border)'};background:${(s.alignment||'center')===a?'var(--accent-glow)':'var(--bg-tertiary)'};color:var(--text-secondary);border-radius:6px;cursor:pointer;">${l}</button>`).join('')}</div></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Espaço entre cards (px)</label><input type="number" min="0" max="80" class="prop-input" value="${val(s.gap ?? 20)}" onchange="updateStyle('${viewEl.id}','gap',this.value)"></div><div class="prop-group"><label class="prop-label">Arredondamento (px)</label><input type="number" min="0" max="60" class="prop-input" value="${val(s.cardRadius ?? 14)}" onchange="updateStyle('${viewEl.id}','cardRadius',this.value)"></div></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-palette"></i> Cores</div>
            <div class="prop-group"><label class="prop-label">Cor do título</label>${colorInput('titleColor','#111827')}</div>
            <div class="prop-group"><label class="prop-label">Cor da descrição</label>${colorInput('descriptionColor','#6b7280')}</div>
            <div class="prop-group"><label class="prop-label">Fundo dos cards</label>${colorInput('cardBg','#ffffff')}</div>
            <div class="prop-group"><label class="prop-label">Cor do nome</label>${colorInput('nameColor','#111827')}</div>
            <div class="prop-group"><label class="prop-label">Cor do cargo</label>${colorInput('roleColor','#6b7280')}</div>
            <div class="prop-group"><label class="prop-label">Cor do texto</label>${colorInput('textColor','#4b5563')}</div>
            <div class="prop-group"><label class="prop-label">Fundo do avatar</label>${colorInput('avatarBg','#eef2f7')}</div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-text-height"></i> Tipografia</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Título (px)</label><input type="number" min="10" class="prop-input" value="${val(s.titleFontSize ?? 30)}" oninput="updateStyleLive('${viewEl.id}','titleFontSize',this.value)" onblur="updateStyle('${viewEl.id}','titleFontSize',this.value)"></div><div class="prop-group"><label class="prop-label">Descrição (px)</label><input type="number" min="10" class="prop-input" value="${val(s.descriptionFontSize ?? 14)}" oninput="updateStyleLive('${viewEl.id}','descriptionFontSize',this.value)" onblur="updateStyle('${viewEl.id}','descriptionFontSize',this.value)"></div></div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:6px;"><div class="prop-group"><label class="prop-label">Nome</label><input type="number" min="10" class="prop-input" value="${val(s.nameFontSize ?? 18)}" onchange="updateStyle('${viewEl.id}','nameFontSize',this.value)"></div><div class="prop-group"><label class="prop-label">Cargo</label><input type="number" min="9" class="prop-input" value="${val(s.roleFontSize ?? 13)}" onchange="updateStyle('${viewEl.id}','roleFontSize',this.value)"></div><div class="prop-group"><label class="prop-label">Texto</label><input type="number" min="10" class="prop-input" value="${val(s.textFontSize ?? 14)}" onchange="updateStyle('${viewEl.id}','textFontSize',this.value)"></div></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-image"></i> Avatar</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Tamanho (px)</label><input type="number" min="32" max="200" class="prop-input" value="${val(s.avatarSize ?? 84)}" onchange="updateStyle('${viewEl.id}','avatarSize',this.value)"></div><div class="prop-group"><label class="prop-label">Formato</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','avatarRadius',this.value)"><option value="50" ${String(s.avatarRadius??50)==='50'?'selected':''}>Circular</option><option value="20" ${String(s.avatarRadius??50)==='20'?'selected':''}>Arredondado</option><option value="0" ${String(s.avatarRadius??50)==='0'?'selected':''}>Quadrado</option></select></div></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-border-all"></i> Cartão</div>
            <div class="prop-group"><label class="prop-label">Tipo de borda</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','cardBorderStyle',this.value)">${['solid','dashed','dotted','double','none'].map(v=>`<option value="${v}" ${(s.cardBorderStyle||'solid')===v?'selected':''}>${v}</option>`).join('')}</select></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Espessura (px)</label><input type="number" min="0" max="10" class="prop-input" value="${val(s.cardBorderWidth ?? 1)}" onchange="updateStyle('${viewEl.id}','cardBorderWidth',this.value)"></div><div class="prop-group"><label class="prop-label">Padding (px)</label><input type="number" min="8" max="100" class="prop-input" value="${val(s.cardPadding ?? 24)}" onchange="updateStyle('${viewEl.id}','cardPadding',this.value)"></div></div>
            <div class="prop-group"><label class="prop-label">Cor da borda</label>${colorInput('cardBorderColor','#e5e7eb')}</div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-magic"></i> Hover</div>
            <div class="prop-group"><label class="prop-label">Fundo no Hover</label>${colorInput('hoverBg','#f8fafc')}</div><div class="prop-group"><label class="prop-label">Elevação (px)</label><input type="number" min="0" max="20" class="prop-input" value="${val(s.hoverLift ?? 3)}" onchange="updateStyle('${viewEl.id}','hoverLift',this.value)"></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-cloud"></i> Sombra</div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Ativar sombra</span><input type="checkbox" ${s.shadow?'checked':''} onchange="updateStyle('${viewEl.id}','shadow',this.checked)"></label></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">${[['shadowX','X'],['shadowY','Y'],['shadowBlur','Desfoque'],['shadowSpread','Espalhamento']].map(([p,l])=>`<div class="prop-group"><label class="prop-label">${l}</label><input type="number" class="prop-input" value="${val(s[p] ?? 0)}" onchange="updateStyle('${viewEl.id}','${p}',this.value)"></div>`).join('')}</div>
            <div class="prop-group"><label class="prop-label">Cor da sombra</label>${colorInput('shadowColor','rgba(0,0,0,0.10)')}</div>
        </div>`;
    }

    if (viewEl.type === 'pricing') {
        const s = viewEl.styles || {};
        const plans = ensurePricingPlans(s);
        const val = (v) => escapeAttr(v == null ? '' : String(v));
        const priceColor = (prop, fallback) => {
            const raw = String(s[prop] || fallback);
            const picker = /^#[0-9a-f]{6}$/i.test(raw) ? raw : fallback;
            return `<div class="color-row"><input type="color" value="${escapeAttr(picker)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"><input type="text" class="prop-input" value="${escapeAttr(raw)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"></div>`;
        };
        html += `
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-tags"></i> Preços</div>
            <div class="prop-group">
                <label class="prop-label">Título da seção</label>
                <input class="prop-input" value="${val(s.title || 'Planos e preços')}" onchange="updateStyle('${viewEl.id}','title',this.value)">
            </div>
            <div class="prop-group">
                <label class="prop-label">Descrição</label>
                <textarea class="prop-input" rows="2" onchange="updateStyle('${viewEl.id}','description',this.value)">${escapeHtml(s.description || 'Escolha o plano ideal para você.')}</textarea>
            </div>
        </div>

        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-list"></i> Planos</div>
            <div style="font-size:10px;color:var(--text-tertiary);line-height:1.5;margin-bottom:10px;">Edite cada plano abaixo. Arraste pelo ☷ para mudar a ordem.</div>
            ${plans.map((plan,index)=>`
                <div style="background:var(--bg-tertiary);border:1px solid var(--border);border-radius:8px;padding:10px;margin-bottom:9px;" ondragover="allowPricingDrop(event)" ondrop="dropPricing(event,'${viewEl.id}',${index})">
                    <div style="display:flex;align-items:center;gap:7px;margin-bottom:9px;">
                        <button type="button" draggable="true" title="Arrastar plano" ondragstart="startPricingDrag(event,'${viewEl.id}',${index})" style="width:32px;height:30px;border:1px solid var(--border);background:var(--bg-secondary);color:var(--text-secondary);border-radius:6px;cursor:grab;">☷</button>
                        <strong style="font-size:11px;flex:1;">Plano ${index + 1}</strong>
                        <button type="button" title="Excluir plano" onclick="removePricingPlan('${viewEl.id}',${index})" style="border:0;background:none;color:var(--error);font-size:18px;cursor:pointer;line-height:1;">×</button>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">
                        <div class="prop-group"><label class="prop-label">Nome</label><input class="prop-input" value="${val(plan.name || '')}" onchange="updatePricingPlan('${viewEl.id}',${index},'name',this.value)"></div>
                        <div class="prop-group"><label class="prop-label">Preço</label><input class="prop-input" value="${val(plan.price || '')}" placeholder="29" onchange="updatePricingPlan('${viewEl.id}',${index},'price',this.value)"></div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">
                        <div class="prop-group"><label class="prop-label">Período</label><input class="prop-input" value="${val(plan.period || '')}" placeholder="/mês" onchange="updatePricingPlan('${viewEl.id}',${index},'period',this.value)"></div>
                        <div class="prop-group"><label class="prop-label">Selo</label><input class="prop-input" value="${val(plan.badge || '')}" placeholder="Mais popular" onchange="updatePricingPlan('${viewEl.id}',${index},'badge',this.value)"></div>
                    </div>

                    <div class="prop-group"><label class="prop-label">Descrição do plano</label><input class="prop-input" value="${val(plan.description || '')}" onchange="updatePricingPlan('${viewEl.id}',${index},'description',this.value)"></div>

                    <div class="prop-group">
                        <label class="prop-label">Benefícios</label>
                        <textarea class="prop-input" rows="4" placeholder="Um benefício por linha" onchange="updatePricingFeatures('${viewEl.id}',${index},this.value)">${escapeHtml(Array.isArray(plan.features) ? plan.features.join('\\n') : '')}</textarea>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">
                        <div class="prop-group"><label class="prop-label">Texto do botão</label><input class="prop-input" value="${val(plan.buttonText || '')}" onchange="updatePricingPlan('${viewEl.id}',${index},'buttonText',this.value)"></div>
                        <div class="prop-group"><label class="prop-label">Link do botão</label><input class="prop-input" value="${val(plan.buttonLink || '')}" placeholder="#" onchange="updatePricingPlan('${viewEl.id}',${index},'buttonLink',this.value)"></div>
                    </div>

                    <label style="display:flex;align-items:center;justify-content:space-between;margin-top:7px;cursor:pointer;">
                        <span style="font-size:10px;color:var(--text-secondary);">Plano em destaque</span>
                        <input type="checkbox" ${plan.highlighted ? 'checked' : ''} onchange="updatePricingPlan('${viewEl.id}',${index},'highlighted',this.checked)">
                    </label>
                </div>
            `).join('')}
            <button type="button" onclick="addPricingPlan('${viewEl.id}')" style="width:100%;height:36px;border:1px solid var(--accent);background:var(--accent-glow);color:var(--accent);border-radius:7px;cursor:pointer;font-weight:700;"><i class="fas fa-plus"></i> Adicionar plano</button>
        </div>

        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-ruler-combined"></i> Layout</div>
            <div style="display:grid;grid-template-columns:1fr 80px;gap:7px;">
                <div class="prop-group"><label class="prop-label">Largura</label><input type="number" min="1" max="2000" class="prop-input" value="${val(s.width ?? 100)}" onchange="updateStyle('${viewEl.id}','width',this.value)"></div>
                <div class="prop-group"><label class="prop-label">Unidade</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','widthUnit',this.value)"><option value="%" ${(s.widthUnit || '%') === '%' ? 'selected' : ''}>%</option><option value="px" ${s.widthUnit === 'px' ? 'selected' : ''}>px</option></select></div>
            </div>
            <div class="prop-group"><label class="prop-label">Colunas</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','columns',this.value)">${['1','2','3','4'].map(n=>`<option value="${n}" ${String(s.columns || '3') === n ? 'selected' : ''}>${n} ${n === '1' ? 'coluna' : 'colunas'}</option>`).join('')}</select></div>
            <div class="prop-group"><label class="prop-label">Alinhamento da seção</label><div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px;">${[['left','Esq.'],['center','Centro'],['right','Dir.']].map(([a,l])=>`<button type="button" onclick="updateStyle('${viewEl.id}','alignment','${a}')" style="height:34px;border:1px solid ${(s.alignment || 'center') === a ? 'var(--accent)' : 'var(--border)'};background:${(s.alignment || 'center') === a ? 'var(--accent-glow)' : 'var(--bg-tertiary)'};color:var(--text-secondary);border-radius:6px;cursor:pointer;">${l}</button>`).join('')}</div></div>
            <div class="prop-group"><label class="prop-label">Espaço entre planos (px)</label><input type="number" min="0" max="80" class="prop-input" value="${val(s.gap ?? 20)}" onchange="updateStyle('${viewEl.id}','gap',this.value)"></div>
        </div>

        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-palette"></i> Cores</div>
            <div class="prop-group"><label class="prop-label">Cor do título</label>${priceColor('titleColor','#111827')}</div>
            <div class="prop-group"><label class="prop-label">Cor da descrição</label>${priceColor('descriptionColor','#6b7280')}</div>
            <div class="prop-group"><label class="prop-label">Fundo dos planos</label>${priceColor('cardBg','#ffffff')}</div>
            <div class="prop-group"><label class="prop-label">Cor do preço</label>${priceColor('priceColor','#111827')}</div>
            <div class="prop-group"><label class="prop-label">Cor dos benefícios</label>${priceColor('featureColor','#374151')}</div>
            <div class="prop-group"><label class="prop-label">Cor do ✓</label>${priceColor('checkColor','#22c55e')}</div>
        </div>`;
    }

    if (viewEl.type === 'gallery') {
        const s = viewEl.styles || {};
        const imgs = ensureGalleryImages(s);
        const colorInput = (prop, fallback) => {
            const raw = s[prop] || fallback;
            const picker = /^#[0-9a-f]{6}$/i.test(String(raw)) ? raw : fallback;
            return `<div class="color-row"><input type="color" value="${esc(picker)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"><input type="text" value="${esc(raw)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"></div>`;
        };
        html += `
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-images"></i> Galeria</div>
            <div class="prop-group"><label class="prop-label">Título</label><input class="prop-input" value="${esc(s.title || '')}" onchange="updateStyle('${viewEl.id}','title',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Descrição</label><textarea class="prop-input" rows="2" onchange="updateStyle('${viewEl.id}','description',this.value)">${escapeHtml(s.description || '')}</textarea></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-photo-film"></i> Imagens</div>
            <div style="font-size:10px;color:var(--text-tertiary);line-height:1.5;margin-bottom:8px;">Adicione imagens por URL ou envie várias imagens do computador. Arraste pelo ☷ para mudar a ordem.</div>
            <div class="prop-group"><label class="prop-label">URL da imagem</label><div style="display:grid;grid-template-columns:1fr 42px;gap:6px;"><input id="gallery-url-${viewEl.id}" class="prop-input" placeholder="https://..." onkeydown="if(event.key==='Enter'){event.preventDefault();addGalleryImageFromUrl('${viewEl.id}');}"><button type="button" onclick="addGalleryImageFromUrl('${viewEl.id}')" style="height:34px;border:1px solid var(--accent);background:var(--accent-glow);color:var(--accent);border-radius:6px;cursor:pointer;"><i class="fas fa-plus"></i></button></div></div>
            <label style="display:block;padding:9px;border:1px dashed var(--border);border-radius:7px;text-align:center;cursor:pointer;background:var(--bg-secondary);margin-bottom:10px;"><i class="fas fa-cloud-upload-alt" style="color:var(--accent);margin-right:5px;"></i> Enviar várias imagens <input type="file" accept="image/*" multiple style="display:none" onchange="handleGalleryUpload(this,'${viewEl.id}')"></label>
            ${imgs.map((img,index)=>`<div class="gallery-prop-item" data-gallery-index="${index}" ondragover="allowGalleryDrop(event)" ondrop="dropGalleryImage(event,'${viewEl.id}',${index})" style="border:1px solid var(--border);background:var(--bg-tertiary);border-radius:8px;padding:9px;margin-bottom:8px;">
                <div style="display:flex;align-items:center;gap:7px;margin-bottom:7px;"><button type="button" draggable="true" title="Arrastar" ondragstart="startGalleryDrag(event,'${viewEl.id}',${index})" style="width:32px;height:30px;border:1px solid var(--border);background:var(--bg-secondary);color:var(--text-secondary);border-radius:6px;cursor:grab;">☷</button><img src="${escapeAttr(img.src || '')}" style="width:42px;height:32px;object-fit:cover;border-radius:5px;background:#eee;" onerror="this.style.visibility='hidden'"><strong style="font-size:11px;flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">Imagem ${index+1}</strong><button type="button" onclick="removeGalleryImage('${viewEl.id}',${index})" style="border:0;background:none;color:var(--error);font-size:18px;cursor:pointer;">×</button></div>
                <div class="prop-group"><label class="prop-label">URL</label><input class="prop-input" value="${esc(img.src || '')}" onchange="updateGalleryImage('${viewEl.id}',${index},'src',this.value)"></div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Texto ALT</label><input class="prop-input" value="${esc(img.alt || '')}" onchange="updateGalleryImage('${viewEl.id}',${index},'alt',this.value)"></div><div class="prop-group"><label class="prop-label">Legenda</label><input class="prop-input" value="${esc(img.title || '')}" onchange="updateGalleryImage('${viewEl.id}',${index},'title',this.value)"></div></div>
                <div class="prop-group"><label class="prop-label">Link opcional</label><input class="prop-input" value="${esc(img.link || '')}" placeholder="https://..." onchange="updateGalleryImage('${viewEl.id}',${index},'link',this.value)"></div>
            </div>`).join('')}
            <button type="button" onclick="addGalleryImage('${viewEl.id}')" style="width:100%;height:36px;border:1px solid var(--accent);background:var(--accent-glow);color:var(--accent);border-radius:7px;cursor:pointer;font-weight:700;"><i class="fas fa-plus"></i> Adicionar imagem</button>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-table-cells"></i> Layout</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Largura</label><input type="number" min="1" max="2000" class="prop-input" value="${esc(s.width ?? 100)}" onchange="updateStyle('${viewEl.id}','width',this.value)"></div><div class="prop-group"><label class="prop-label">Unidade</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','widthUnit',this.value)"><option value="%" ${(s.widthUnit||'%')==='%'?'selected':''}>%</option><option value="px" ${s.widthUnit==='px'?'selected':''}>px</option></select></div></div>
            <div class="prop-group"><label class="prop-label">Colunas</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','columns',this.value)">${['1','2','3','4'].map(v=>`<option value="${v}" ${String(s.columns||'3')===v?'selected':''}>${v}</option>`).join('')}</select></div>
            <div class="prop-group"><label class="prop-label">Alinhamento</label><div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px;">${[['left','Esq.'],['center','Centro'],['right','Dir.']].map(([a,l])=>`<button type="button" onclick="updateStyle('${viewEl.id}','alignment','${a}');updateProperties('${viewEl.id}')" style="height:34px;border:1px solid ${(s.alignment||'center')===a?'var(--accent)':'var(--border)'};background:${(s.alignment||'center')===a?'var(--accent-glow)':'var(--bg-tertiary)'};color:var(--text-secondary);border-radius:6px;cursor:pointer;">${l}</button>`).join('')}</div></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Espaço horizontal (px)</label><input type="number" min="0" max="100" class="prop-input" value="${esc(s.gap ?? 16)}" onchange="updateStyle('${viewEl.id}','gap',this.value)"></div><div class="prop-group"><label class="prop-label">Espaço vertical (px)</label><input type="number" min="0" max="100" class="prop-input" value="${esc(s.rowGap ?? s.gap ?? 16)}" onchange="updateStyle('${viewEl.id}','rowGap',this.value)"></div></div>
            <div class="prop-group"><label class="prop-label">Altura das imagens (px)</label><input type="number" min="80" max="1000" class="prop-input" value="${esc(s.imageHeight ?? 220)}" onchange="updateStyle('${viewEl.id}','imageHeight',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Ajuste</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','objectFit',this.value)"><option value="cover" ${(s.objectFit||'cover')==='cover'?'selected':''}>Preencher</option><option value="contain" ${s.objectFit==='contain'?'selected':''}>Conter</option><option value="fill" ${s.objectFit==='fill'?'selected':''}>Esticar</option></select></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-paintbrush"></i> Aparência</div>
            <div class="prop-group"><label class="prop-label">Cor do título</label>${colorInput('titleColor','#111827')}</div>
            <div class="prop-group"><label class="prop-label">Cor da descrição</label>${colorInput('descriptionColor','#6b7280')}</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Tamanho do título</label><input type="number" min="12" max="80" class="prop-input" value="${esc(s.titleFontSize ?? 28)}" oninput="updateStyleLive('${viewEl.id}','titleFontSize',this.value)" onblur="updateStyle('${viewEl.id}','titleFontSize',this.value)"></div><div class="prop-group"><label class="prop-label">Tamanho descrição</label><input type="number" min="10" max="40" class="prop-input" value="${esc(s.descriptionFontSize ?? 14)}" oninput="updateStyleLive('${viewEl.id}','descriptionFontSize',this.value)" onblur="updateStyle('${viewEl.id}','descriptionFontSize',this.value)"></div></div>
            <div class="prop-group"><label class="prop-label">Raio das imagens (px)</label><input type="number" min="0" max="80" class="prop-input" value="${esc(s.borderRadius ?? 12)}" onchange="updateStyle('${viewEl.id}','borderRadius',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Borda</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','borderStyle',this.value)">${['none','solid','dashed','dotted'].map(v=>`<option value="${v}" ${(s.borderStyle||'none')===v?'selected':''}>${v}</option>`).join('')}</select></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Espessura</label><input type="number" min="0" max="20" class="prop-input" value="${esc(s.borderWidth ?? 0)}" onchange="updateStyle('${viewEl.id}','borderWidth',this.value)"></div><div class="prop-group"><label class="prop-label">Cor da borda</label>${colorInput('borderColor','#e5e7eb')}</div></div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Mostrar legenda</span><input type="checkbox" ${s.caption!==false?'checked':''} onchange="updateStyle('${viewEl.id}','caption',this.checked)"></label></div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Abrir em tela maior (Lightbox)</span><input type="checkbox" ${s.lightbox!==false?'checked':''} onchange="updateStyle('${viewEl.id}','lightbox',this.checked)"></label></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-wand-magic-sparkles"></i> Hover</div>
            <div class="prop-group"><label class="prop-label">Efeito</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','hover',this.value)"><option value="none" ${(s.hover||'zoom')==='none'?'selected':''}>Nenhum</option><option value="zoom" ${s.hover==='zoom'?'selected':''}>Zoom</option><option value="brightness" ${s.hover==='brightness'?'selected':''}>Iluminar</option><option value="dark" ${s.hover==='dark'?'selected':''}>Escurecer</option></select></div>
            <div class="prop-group"><label class="prop-label">Intensidade do zoom</label><input type="number" min="1" max="1.2" step="0.01" class="prop-input" value="${esc(s.hoverScale ?? 1.03)}" onchange="updateStyle('${viewEl.id}','hoverScale',this.value)"></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-cloud"></i> Sombra</div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Ativar sombra</span><input type="checkbox" ${s.shadow?'checked':''} onchange="updateStyle('${viewEl.id}','shadow',this.checked)"></label></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">${[['shadowX','X'],['shadowY','Y'],['shadowBlur','Desfoque'],['shadowSpread','Espalhamento']].map(([prop,label])=>`<div class="prop-group"><label class="prop-label">${label}</label><input type="number" class="prop-input" value="${esc(s[prop] ?? 0)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"></div>`).join('')}</div>
            <div class="prop-group"><label class="prop-label">Cor da sombra</label>${colorInput('shadowColor','rgba(0,0,0,0.14)')}</div>
        </div>`;
    }

    if (viewEl.type === 'bannerAd') {
        const s = { ...(viewEl.styles || {}), ...((viewEl.styles || {}).responsive?.[getResponsiveDeviceMode()] || {}) };
        const banners = ensureBannerSlides(s);
        html += `
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-panorama"></i> Banner / Rotação</div>
            <div style="font-size:10px;color:var(--text-tertiary);line-height:1.5;margin-bottom:8px;">Adicione mais de um banner e ative a troca automática. O intervalo é em segundos.</div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Troca automática</span><input type="checkbox" ${s.autoplay ? 'checked' : ''} onchange="updateStyle('${viewEl.id}','autoplay',this.checked);updateProperties('${viewEl.id}')"></label></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">
                <div class="prop-group"><label class="prop-label">Intervalo (ms)</label><input type="number" min="1000" max="60000" step="500" class="prop-input" value="${esc(s.autoplayInterval ?? 5000)}" onchange="updateStyle('${viewEl.id}','autoplayInterval',this.value)"></div>
                <div class="prop-group"><label class="prop-label">Transição</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','transition',this.value)"><option value="fade" ${(s.transition||'fade')==='fade'?'selected':''}>Fade</option><option value="slide" ${s.transition==='slide'?'selected':''}>Deslizar</option></select></div>
            </div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Pausar ao passar o mouse</span><input type="checkbox" ${s.pauseOnHover !== false ? 'checked' : ''} onchange="updateStyle('${viewEl.id}','pauseOnHover',this.checked)"></label></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-font"></i> Texto do banner</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">
                <div class="prop-group"><label class="prop-label">Tamanho do título (px)</label><input type="number" min="8" max="120" step="1" class="prop-input" value="${esc(s.titleFontSize ?? 28)}" oninput="updateStyleLive('${viewEl.id}','titleFontSize',this.value)" onblur="updateStyle('${viewEl.id}','titleFontSize',this.value)"></div>
                <div class="prop-group"><label class="prop-label">Tamanho do texto (px)</label><input type="number" min="8" max="80" step="1" class="prop-input" value="${esc(s.descriptionFontSize ?? 15)}" oninput="updateStyleLive('${viewEl.id}','descriptionFontSize',this.value)" onblur="updateStyle('${viewEl.id}','descriptionFontSize',this.value)"></div>
            </div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-images"></i> Banners</div>
            <div class="prop-group"><label class="prop-label">Adicionar por URL</label><div style="display:grid;grid-template-columns:1fr 42px;gap:6px;"><input id="banner-url-${viewEl.id}" class="prop-input" placeholder="https://..." onkeydown="if(event.key==='Enter'){event.preventDefault();addBannerSlideFromUrl('${viewEl.id}');}"><button type="button" onclick="addBannerSlideFromUrl('${viewEl.id}')" style="height:34px;border:1px solid var(--accent);background:var(--accent-glow);color:var(--accent);border-radius:6px;cursor:pointer;"><i class="fas fa-plus"></i></button></div></div>
            <label style="display:block;padding:9px;border:1px dashed var(--border);border-radius:7px;text-align:center;cursor:pointer;background:var(--bg-secondary);margin-bottom:10px;"><i class="fas fa-cloud-upload-alt" style="color:var(--accent);margin-right:5px;"></i> Enviar imagens dos banners <input type="file" accept="image/*" multiple style="display:none" onchange="handleBannerUpload(this,'${viewEl.id}')"></label>
            ${banners.map((banner,index)=>`<div style="border:1px solid var(--border);background:var(--bg-tertiary);border-radius:8px;padding:9px;margin-bottom:8px;">
                <div style="display:flex;align-items:center;gap:7px;margin-bottom:7px;"><img src="${escapeAttr(banner.src || '')}" style="width:58px;height:34px;object-fit:cover;border-radius:5px;background:#eee;"><strong style="font-size:11px;flex:1;">Banner ${index+1}</strong><button type="button" onclick="removeBannerSlide('${viewEl.id}',${index})" style="border:0;background:none;color:var(--error);font-size:18px;cursor:pointer;">×</button></div>
                <div class="prop-group"><label class="prop-label">URL da imagem</label><input class="prop-input" value="${esc(banner.src || '')}" onchange="updateBannerSlide('${viewEl.id}',${index},'src',this.value)"></div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">ALT</label><input class="prop-input" value="${esc(banner.alt || '')}" onchange="updateBannerSlide('${viewEl.id}',${index},'alt',this.value)"></div><div class="prop-group"><label class="prop-label">Título</label><input class="prop-input" value="${esc(banner.title || '')}" onchange="updateBannerSlide('${viewEl.id}',${index},'title',this.value)"></div></div>
                <div class="prop-group"><label class="prop-label">Descrição</label><textarea class="prop-input" rows="2" onchange="updateBannerSlide('${viewEl.id}',${index},'text',this.value)">${escapeHtml(banner.text || '')}</textarea></div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Texto do botão</label><input class="prop-input" value="${esc(banner.buttonText || '')}" onchange="updateBannerSlide('${viewEl.id}',${index},'buttonText',this.value)"></div><div class="prop-group"><label class="prop-label">Link do botão</label><input class="prop-input" value="${esc(banner.buttonLink || '')}" placeholder="https://..." onchange="updateBannerSlide('${viewEl.id}',${index},'buttonLink',this.value)"></div></div>
            </div>`).join('')}
            <button type="button" onclick="addBannerSlide('${viewEl.id}')" style="width:100%;height:36px;border:1px solid var(--accent);background:var(--accent-glow);color:var(--accent);border-radius:7px;cursor:pointer;font-weight:700;"><i class="fas fa-plus"></i> Adicionar banner</button>
        </div>`;
    }

    if (viewEl.type === 'carousel') {
        const carouselMode = getResponsiveDeviceMode();
        const s = { ...(viewEl.styles || {}), ...((viewEl.styles || {}).responsive?.[carouselMode] || {}) };
        const slides = ensureCarouselSlides(s);
        html += `
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-images"></i> Carrossel / Slider</div>
            <div class="prop-group"><label class="prop-label">Título</label><input class="prop-input" value="${esc(s.title || '')}" onchange="updateStyle('${viewEl.id}','title',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Descrição</label><textarea class="prop-input" rows="2" onchange="updateStyle('${viewEl.id}','description',this.value)">${escapeHtml(s.description || '')}</textarea></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-ruler-combined"></i> Layout</div>
            <div style="font-size:10px;color:var(--text-tertiary);margin-bottom:7px;">Esses campos seguem somente o dispositivo selecionado acima.</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">
                <div class="prop-group"><label class="prop-label">Largura</label><input type="number" min="1" max="2000" class="prop-input" value="${esc(s.width??100)}" onchange="setResponsiveStyle('${viewEl.id}','width',this.value)"></div>
                <div class="prop-group"><label class="prop-label">Unidade</label><select class="prop-select" onchange="setResponsiveStyle('${viewEl.id}','widthUnit',this.value)"><option value="%" ${(s.widthUnit||'%')==='%'?'selected':''}>%</option><option value="px" ${s.widthUnit==='px'?'selected':''}>px</option></select></div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">
                <div class="prop-group"><label class="prop-label">Altura do slide (px)</label><input type="number" min="120" max="1000" class="prop-input" value="${esc(s.slideHeight??360)}" onchange="setResponsiveStyle('${viewEl.id}','slideHeight',this.value)"></div>
                <div class="prop-group"><label class="prop-label">Espaço entre slides</label><input type="number" min="0" max="80" class="prop-input" value="${esc(s.gap??16)}" onchange="setResponsiveStyle('${viewEl.id}','gap',this.value)"></div>
            </div>
            <div class="prop-group"><label class="prop-label">Alinhamento</label><div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px;">${[['left','Esq.'],['center','Centro'],['right','Dir.']].map(([a,l])=>`<button type="button" onclick="setResponsiveStyle('${viewEl.id}','alignment','${a}')" style="height:34px;border:1px solid ${(s.alignment||'center')===a?'var(--accent)':'var(--border)'};background:${(s.alignment||'center')===a?'var(--accent-glow)':'var(--bg-tertiary)'};color:var(--text-secondary);border-radius:6px;cursor:pointer">${l}</button>`).join('')}</div></div>
            <div class="prop-group"><label class="prop-label">Navegação</label><select class="prop-select" onchange="setResponsiveStyle('${viewEl.id}','navigation',this.value)"><option value="none" ${(s.navigation||'none')==='none'?'selected':''}>Nenhuma</option><option value="arrows" ${s.navigation==='arrows'?'selected':''}>Somente setas</option><option value="dots" ${s.navigation==='dots'?'selected':''}>Somente dots</option><option value="both" ${s.navigation==='both'?'selected':''}>Setas + dots</option></select></div>
            <div style="border-top:1px solid var(--border);margin-top:9px;padding-top:9px;"><div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Troca automática dos slides</span><input type="checkbox" ${s.autoplay ? 'checked' : ''} onchange="updateStyle('${viewEl.id}','autoplay',this.checked);updateProperties('${viewEl.id}')"></label></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Intervalo (ms)</label><input type="number" min="1000" max="60000" step="500" class="prop-input" value="${esc(s.autoplayInterval ?? 4000)}" onchange="updateStyle('${viewEl.id}','autoplayInterval',this.value)"></div><div class="prop-group"><label class="prop-label">Pausar no mouse</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','pauseOnHover',this.value==='true')"><option value="true" ${s.pauseOnHover!==false?'selected':''}>Sim</option><option value="false" ${s.pauseOnHover===false?'selected':''}>Não</option></select></div></div></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">
                <div class="prop-group"><label class="prop-label">Slides visíveis</label><select class="prop-select" onchange="setResponsiveStyle('${viewEl.id}','slidesVisible',this.value)"><option value="1" ${(parseInt(s.slidesVisible||1,10)===1)?'selected':''}>1 slide</option><option value="2" ${(parseInt(s.slidesVisible||1,10)===2)?'selected':''}>2 slides</option><option value="3" ${(parseInt(s.slidesVisible||1,10)===3)?'selected':''}>3 slides</option></select></div>
                <div class="prop-group"><label class="prop-label">Altura mínima (px)</label><input type="number" min="120" max="1000" class="prop-input" value="${esc(s.slideHeight??360)}" onchange="setResponsiveStyle('${viewEl.id}','slideHeight',this.value)"></div>
            </div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-layer-group"></i> Slides</div>
            <div style="font-size:10px;color:var(--text-tertiary);line-height:1.5;margin-bottom:8px;">Adicione slides e arraste pelo ☷ para mudar a ordem.</div>
            <div class="prop-group"><label class="prop-label">URL da imagem</label><div style="display:grid;grid-template-columns:1fr 42px;gap:6px;"><input id="carousel-url-${viewEl.id}" class="prop-input" placeholder="https://..." onkeydown="if(event.key==='Enter'){event.preventDefault();addCarouselSlideFromUrl('${viewEl.id}');}"><button type="button" onclick="addCarouselSlideFromUrl('${viewEl.id}')" style="height:34px;border:1px solid var(--accent);background:var(--accent-glow);color:var(--accent);border-radius:6px;cursor:pointer;"><i class="fas fa-plus"></i></button></div></div>
            <label style="display:block;padding:9px;border:1px dashed var(--border);border-radius:7px;text-align:center;cursor:pointer;background:var(--bg-secondary);margin-bottom:10px;"><i class="fas fa-cloud-upload-alt" style="color:var(--accent);margin-right:5px;"></i> Enviar imagens dos slides <input type="file" accept="image/*" multiple style="display:none" onchange="handleCarouselUpload(this,'${viewEl.id}')"></label>
            ${slides.map((slide,index)=>`<div class="carousel-prop-item" ondragover="allowCarouselDrop(event)" ondrop="dropCarouselSlide(event,'${viewEl.id}',${index})" style="border:1px solid var(--border);background:var(--bg-tertiary);border-radius:8px;padding:9px;margin-bottom:8px;">
                <div style="display:flex;align-items:center;gap:7px;margin-bottom:7px;"><button type="button" draggable="true" title="Arrastar" ondragstart="startCarouselDrag(event,'${viewEl.id}',${index})" style="width:32px;height:30px;border:1px solid var(--border);background:var(--bg-secondary);color:var(--text-secondary);border-radius:6px;cursor:grab;">☷</button><img src="${escapeAttr(slide.src || '')}" style="width:48px;height:34px;object-fit:cover;border-radius:5px;background:#eee;"><strong style="font-size:11px;flex:1;">Slide ${index+1}</strong><button type="button" onclick="removeCarouselSlide('${viewEl.id}',${index})" style="border:0;background:none;color:var(--error);font-size:18px;cursor:pointer;">×</button></div>
                <div class="prop-group"><label class="prop-label">URL da imagem</label><input class="prop-input" value="${esc(slide.src || '')}" onchange="updateCarouselSlide('${viewEl.id}',${index},'src',this.value)"></div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">ALT</label><input class="prop-input" value="${esc(slide.alt || '')}" onchange="updateCarouselSlide('${viewEl.id}',${index},'alt',this.value)"></div><div class="prop-group"><label class="prop-label">Título</label><input class="prop-input" value="${esc(slide.title || '')}" onchange="updateCarouselSlide('${viewEl.id}',${index},'title',this.value)"></div></div>
                <div class="prop-group"><label class="prop-label">Texto</label><textarea class="prop-input" rows="2" onchange="updateCarouselSlide('${viewEl.id}',${index},'text',this.value)">${escapeHtml(slide.text || '')}</textarea></div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Texto do botão</label><input class="prop-input" value="${esc(slide.buttonText || '')}" onchange="updateCarouselSlide('${viewEl.id}',${index},'buttonText',this.value)"></div><div class="prop-group"><label class="prop-label">Link do botão</label><input class="prop-input" value="${esc(slide.buttonLink || '')}" placeholder="https://..." onchange="updateCarouselSlide('${viewEl.id}',${index},'buttonLink',this.value)"></div></div>
            </div>`).join('')}
            <button type="button" onclick="addCarouselSlide('${viewEl.id}')" style="width:100%;height:36px;border:1px solid var(--accent);background:var(--accent-glow);color:var(--accent);border-radius:7px;cursor:pointer;font-weight:700;"><i class="fas fa-plus"></i> Adicionar slide</button>
        </div>`;
    }


    if (viewEl.type === 'tabs') {
        const s = viewEl.styles || {};
        const items = ensureTabsItems(s);
        html += `
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-folder-open"></i> Conteúdo das Abas</div>
            <div class="prop-group"><label class="prop-label">Título</label><input class="prop-input" value="${esc(s.title||'Abas')}" onchange="updateStyle('${viewEl.id}','title',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Descrição</label><textarea class="prop-input" rows="2" onchange="updateStyle('${viewEl.id}','description',this.value)">${escapeHtml(s.description||'')}</textarea></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-list"></i> Abas</div>
            <div style="font-size:10px;color:var(--text-tertiary);margin-bottom:8px;">Arraste pelo ☷ para alterar a ordem.</div>
            ${items.map((item,index)=>`<div ondragover="allowTabsDrop(event)" ondrop="dropTabs(event,'${viewEl.id}',${index})" style="border:1px solid var(--border);background:var(--bg-tertiary);border-radius:8px;padding:10px;margin-bottom:8px;">
                <div style="display:flex;align-items:center;gap:7px;margin-bottom:8px;"><button type="button" draggable="true" title="Arrastar" ondragstart="startTabsDrag(event,'${viewEl.id}',${index})" style="width:32px;height:30px;border:1px solid var(--border);background:var(--bg-secondary);color:var(--text-secondary);border-radius:6px;cursor:grab;">☷</button><strong style="font-size:11px;flex:1;">Aba ${index+1}</strong><button type="button" onclick="removeTabItem('${viewEl.id}',${index})" style="border:0;background:none;color:var(--error);font-size:18px;cursor:pointer;">×</button></div>
                <div class="prop-group"><label class="prop-label">Nome da aba</label><input class="prop-input" value="${esc(item.title||'')}" onchange="updateTabItem('${viewEl.id}',${index},'title',this.value)"></div>
                <div class="prop-group"><label class="prop-label">Conteúdo</label><textarea class="prop-input" rows="4" onchange="updateTabItem('${viewEl.id}',${index},'content',this.value)">${escapeHtml(item.content||'')}</textarea></div>
            </div>`).join('')}
            <button type="button" onclick="addTabItem('${viewEl.id}')" style="width:100%;height:36px;border:1px solid var(--accent);background:var(--accent-glow);color:var(--accent);border-radius:7px;cursor:pointer;font-weight:700;"><i class="fas fa-plus"></i> Adicionar aba</button>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-th-large"></i> Layout</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Largura</label><input type="number" min="1" max="2000" class="prop-input" value="${esc(s.width??100)}" onchange="updateStyle('${viewEl.id}','width',this.value)"></div><div class="prop-group"><label class="prop-label">Unidade</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','widthUnit',this.value)"><option value="%" ${(s.widthUnit||'%')==='%'?'selected':''}>%</option><option value="px" ${s.widthUnit==='px'?'selected':''}>px</option></select></div></div>
            <div class="prop-group"><label class="prop-label">Alinhamento</label><div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px;">${[['left','Esq.'],['center','Centro'],['right','Dir.']].map(([a,l])=>`<button type="button" onclick="updateStyle('${viewEl.id}','alignment','${a}')" style="height:34px;border:1px solid ${(s.alignment||'left')===a?'var(--accent)':'var(--border)'};background:${(s.alignment||'left')===a?'var(--accent-glow)':'var(--bg-tertiary)'};color:var(--text-secondary);border-radius:6px;cursor:pointer">${l}</button>`).join('')}</div></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-palette"></i> Aparência</div>
            <div class="prop-group"><label class="prop-label">Estilo das abas</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','tabStyle',this.value)"><option value="underline" ${(s.tabStyle||'underline')==='underline'?'selected':''}>Linha inferior</option><option value="pill" ${s.tabStyle==='pill'?'selected':''}>Pílula</option><option value="box" ${s.tabStyle==='box'?'selected':''}>Caixa</option></select></div>
            <div class="prop-group"><label class="prop-label">Cor da aba</label>${(()=>{const raw=s.tabColor||'#475569';const pick=/^#[0-9a-f]{6}$/i.test(raw)?raw:'#475569';return `<div class="color-row"><input type="color" value="${esc(pick)}" onchange="updateStyle('${viewEl.id}','tabColor',this.value)"><input type="text" value="${esc(raw)}" onchange="updateStyle('${viewEl.id}','tabColor',this.value)"></div>`})()}</div>
            <div class="prop-group"><label class="prop-label">Cor da aba ativa</label>${(()=>{const raw=s.tabActiveColor||'#3b82f6';const pick=/^#[0-9a-f]{6}$/i.test(raw)?raw:'#3b82f6';return `<div class="color-row"><input type="color" value="${esc(pick)}" onchange="updateStyle('${viewEl.id}','tabActiveColor',this.value)"><input type="text" value="${esc(raw)}" onchange="updateStyle('${viewEl.id}','tabActiveColor',this.value)"></div>`})()}</div>
            <div class="prop-group"><label class="prop-label">Fundo do conteúdo</label>${(()=>{const raw=s.contentBg||'#ffffff';const pick=/^#[0-9a-f]{6}$/i.test(raw)?raw:'#ffffff';return `<div class="color-row"><input type="color" value="${esc(pick)}" onchange="updateStyle('${viewEl.id}','contentBg',this.value)"><input type="text" value="${esc(raw)}" onchange="updateStyle('${viewEl.id}','contentBg',this.value)"></div>`})()}</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Raio (px)</label><input type="number" min="0" max="60" class="prop-input" value="${esc(s.radius??10)}" onchange="updateStyle('${viewEl.id}','radius',this.value)"></div><div class="prop-group"><label class="prop-label">Padding (px)</label><input type="number" min="0" max="80" class="prop-input" value="${esc(s.padding??18)}" onchange="updateStyle('${viewEl.id}','padding',this.value)"></div></div>
        </div>`;
    }


    if (viewEl.type === 'accordion') {
        const s = viewEl.styles || {};
        const colorInputAcc = (prop, fallback) => {
            const raw = s[prop] || fallback;
            const pick = /^#[0-9a-f]{6}$/i.test(raw) ? raw : fallback;
            return `<div class="color-row"><input type="color" value="${esc(pick)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"><input class="prop-input" value="${esc(raw)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"></div>`;
        };
        html += `
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-list"></i> Conteúdo do Accordion</div>
            <div class="prop-group"><label class="prop-label">Título</label><input class="prop-input" value="${esc(s.title || 'Perguntas e respostas')}" onchange="updateStyle('${viewEl.id}','title',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Descrição</label><textarea class="prop-input" rows="2" onchange="updateStyle('${viewEl.id}','description',this.value)">${escapeHtml(s.description || '')}</textarea></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-layer-group"></i> Itens</div>
            <div style="font-size:10px;color:var(--text-tertiary);margin-bottom:8px;">Arraste pelo ☷ para alterar a ordem. Clique nos campos para editar.</div>
            ${ensureAccordionItems(s).map((item,i)=>`
                <div draggable="false" ondragover="allowAccordionDrop(event)" ondrop="dropAccordion(event,'${viewEl.id}',${i})" style="border:1px solid var(--border);background:var(--bg-tertiary);border-radius:8px;padding:10px;margin-bottom:8px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:8px;"><div style="display:flex;align-items:center;gap:8px;min-width:0;"><button type="button" draggable="true" title="Arrastar" ondragstart="startAccordionDrag(event,'${viewEl.id}',${i})" style="width:30px;height:28px;border:1px solid var(--border);background:var(--bg-secondary);color:var(--text-secondary);border-radius:6px;cursor:grab;">☷</button><strong style="font-size:11px;">Item ${i+1}</strong></div><button type="button" onclick="removeAccordionItem('${viewEl.id}',${i})" title="Excluir" style="border:0;background:none;color:var(--error);font-size:17px;cursor:pointer;">×</button></div>
                    <div class="prop-group"><label class="prop-label">Pergunta</label><input class="prop-input" value="${esc(item.question || '')}" onchange="updateAccordionItem('${viewEl.id}',${i},'question',this.value)"></div>
                    <div class="prop-group"><label class="prop-label">Resposta</label><textarea class="prop-input" rows="3" onchange="updateAccordionItem('${viewEl.id}',${i},'answer',this.value)">${escapeHtml(item.answer || '')}</textarea></div>
                    <label style="display:flex;align-items:center;justify-content:space-between;margin-top:8px;"><span style="font-size:10px;color:var(--text-secondary);">Começar aberta</span><input type="checkbox" ${item.open?'checked':''} onchange="updateAccordionItem('${viewEl.id}',${i},'open',this.checked)"></label>
                </div>`).join('')}
            <button type="button" onclick="addAccordionItem('${viewEl.id}')" style="width:100%;height:36px;border:1px solid var(--accent);background:var(--accent-glow);color:var(--accent);border-radius:7px;cursor:pointer;font-weight:700;"><i class="fas fa-plus"></i> Adicionar item</button>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-ruler-horizontal"></i> Layout</div>
            <div style="display:grid;grid-template-columns:1fr 90px;gap:6px"><div class="prop-group"><label class="prop-label">Largura</label><input type="number" min="1" max="2000" class="prop-input" value="${esc(s.width ?? 100)}" onchange="updateStyle('${viewEl.id}','width',this.value)"></div><div class="prop-group"><label class="prop-label">Unidade</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','widthUnit',this.value)"><option value="%" ${s.widthUnit !== 'px'?'selected':''}>%</option><option value="px" ${s.widthUnit === 'px'?'selected':''}>px</option></select></div></div>
            <div class="prop-group"><label class="prop-label">Alinhamento</label><div style="display:grid;grid-template-columns:repeat(3,1fr);gap:5px"><button type="button" class="prop-btn ${s.alignment==='left'?'active':''}" onclick="updateStyle('${viewEl.id}','alignment','left');updateProperties('${viewEl.id}')">Esq.</button><button type="button" class="prop-btn ${s.alignment==='center'?'active':''}" onclick="updateStyle('${viewEl.id}','alignment','center');updateProperties('${viewEl.id}')">Centro</button><button type="button" class="prop-btn ${s.alignment==='right'?'active':''}" onclick="updateStyle('${viewEl.id}','alignment','right');updateProperties('${viewEl.id}')">Dir.</button></div></div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Permitir várias abertas</span><input type="checkbox" ${s.multipleOpen?'checked':''} onchange="updateStyle('${viewEl.id}','multipleOpen',this.checked)"></label></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-palette"></i> Aparência</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Título (px)</label><input type="number" min="10" max="72" class="prop-input" value="${esc(s.titleFontSize ?? 28)}" oninput="updateStyleLive('${viewEl.id}','titleFontSize',this.value)" onblur="updateStyle('${viewEl.id}','titleFontSize',this.value)"></div><div class="prop-group"><label class="prop-label">Descrição (px)</label><input type="number" min="10" max="30" class="prop-input" value="${esc(s.descriptionFontSize ?? 14)}" oninput="updateStyleLive('${viewEl.id}','descriptionFontSize',this.value)" onblur="updateStyle('${viewEl.id}','descriptionFontSize',this.value)"></div></div>
            <div class="prop-group"><label class="prop-label">Cor do título</label>${colorInputAcc('titleColor','#111827')}</div>
            <div class="prop-group"><label class="prop-label">Cor da descrição</label>${colorInputAcc('descriptionColor','#6b7280')}</div>
            <div class="prop-group"><label class="prop-label">Fundo do cabeçalho</label>${colorInputAcc('questionBg','#ffffff')}</div>
            <div class="prop-group"><label class="prop-label">Fundo aberto</label>${colorInputAcc('questionOpenBg','#eff6ff')}</div>
            <div class="prop-group"><label class="prop-label">Fundo no hover</label>${colorInputAcc('questionHoverBg','#f8fafc')}</div>
            <div class="prop-group"><label class="prop-label">Cor da pergunta</label>${colorInputAcc('questionColor','#111827')}</div>
            <div class="prop-group"><label class="prop-label">Cor da pergunta aberta</label>${colorInputAcc('questionOpenColor','#1d4ed8')}</div>
            <div class="prop-group"><label class="prop-label">Fundo da resposta</label>${colorInputAcc('answerBg','#ffffff')}</div>
            <div class="prop-group"><label class="prop-label">Cor da resposta</label>${colorInputAcc('answerColor','#4b5563')}</div>
            <div class="prop-group"><label class="prop-label">Cor da borda</label>${colorInputAcc('borderColor','#e5e7eb')}</div>
            <div class="prop-group"><label class="prop-label">Cor do ícone</label>${colorInputAcc('iconColor','#3b82f6')}</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Ícone</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','iconType',this.value)"><option value="plus" ${(s.iconType||'plus')==='plus'?'selected':''}>Mais / menos</option><option value="chevron" ${s.iconType==='chevron'?'selected':''}>Chevron</option><option value="angle" ${s.iconType==='angle'?'selected':''}>Ângulo</option><option value="minus" ${s.iconType==='minus'?'selected':''}>Menos / mais</option></select></div><div class="prop-group"><label class="prop-label">Posição do ícone</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','iconPosition',this.value)"><option value="right" ${(s.iconPosition||'right')==='right'?'selected':''}>Direita</option><option value="left" ${s.iconPosition==='left'?'selected':''}>Esquerda</option></select></div></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Pergunta (px)</label><input type="number" min="10" max="40" class="prop-input" value="${esc(s.questionFontSize ?? 15)}" onchange="updateStyle('${viewEl.id}','questionFontSize',this.value)"></div><div class="prop-group"><label class="prop-label">Resposta (px)</label><input type="number" min="10" max="40" class="prop-input" value="${esc(s.answerFontSize ?? 14)}" onchange="updateStyle('${viewEl.id}','answerFontSize',this.value)"></div></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Borda (px)</label><input type="number" min="0" max="10" class="prop-input" value="${esc(s.borderWidth ?? 1)}" onchange="updateStyle('${viewEl.id}','borderWidth',this.value)"></div><div class="prop-group"><label class="prop-label">Raio (px)</label><input type="number" min="0" max="60" class="prop-input" value="${esc(s.radius ?? 10)}" onchange="updateStyle('${viewEl.id}','radius',this.value)"></div></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Espaço entre itens</label><input type="number" min="0" max="60" class="prop-input" value="${esc(s.itemSpacing ?? 10)}" onchange="updateStyle('${viewEl.id}','itemSpacing',this.value)"></div><div class="prop-group"><label class="prop-label">Padding vertical</label><input type="number" min="4" max="50" class="prop-input" value="${esc(s.paddingY ?? 16)}" onchange="updateStyle('${viewEl.id}','paddingY',this.value)"></div></div>
            <div class="prop-group"><label class="prop-label">Padding horizontal</label><input type="number" min="6" max="80" class="prop-input" value="${esc(s.paddingX ?? 18)}" onchange="updateStyle('${viewEl.id}','paddingX',this.value)"></div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Ativar sombra</span><input type="checkbox" ${s.shadow?'checked':''} onchange="updateStyle('${viewEl.id}','shadow',this.checked);updateProperties('${viewEl.id}')"></label></div>
            ${s.shadow ? `<div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">X</label><input type="number" class="prop-input" value="${esc(s.shadowX ?? 0)}" onchange="updateStyle('${viewEl.id}','shadowX',this.value)"></div><div class="prop-group"><label class="prop-label">Y</label><input type="number" class="prop-input" value="${esc(s.shadowY ?? 4)}" onchange="updateStyle('${viewEl.id}','shadowY',this.value)"></div><div class="prop-group"><label class="prop-label">Desfoque</label><input type="number" min="0" class="prop-input" value="${esc(s.shadowBlur ?? 16)}" onchange="updateStyle('${viewEl.id}','shadowBlur',this.value)"></div><div class="prop-group"><label class="prop-label">Espalhar</label><input type="number" class="prop-input" value="${esc(s.shadowSpread ?? 0)}" onchange="updateStyle('${viewEl.id}','shadowSpread',this.value)"></div></div><div class="prop-group"><label class="prop-label">Cor da sombra</label>${colorInputAcc('shadowColor','rgba(0,0,0,0.10)')}</div>` : ''}
        </div>`;
    }

    if (viewEl.type === 'progress') {
        const s = viewEl.styles || {};
        const colorInputProg = (prop, fallback) => {
            const raw = s[prop] || fallback;
            const pick = /^#[0-9a-f]{6}$/i.test(raw) ? raw : fallback;
            return `<div class="color-row"><input type="color" value="${esc(pick)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"><input class="prop-input" value="${esc(raw)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"></div>`;
        };
        html += `
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-tasks"></i> Conteúdo do Progress</div>
            <div class="prop-group"><label class="prop-label">Título</label><input class="prop-input" value="${esc(s.title || 'Nossos conhecimentos')}" onchange="updateStyle('${viewEl.id}','title',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Descrição</label><textarea class="prop-input" rows="2" onchange="updateStyle('${viewEl.id}','description',this.value)">${escapeHtml(s.description || '')}</textarea></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-list"></i> Barras</div>
            <div style="font-size:10px;color:var(--text-tertiary);margin-bottom:8px;">Arraste pelo ☷ para alterar a ordem.</div>
            ${ensureProgressItems(s).map((item,i)=>`<div draggable="false" ondragover="allowProgressDrop(event)" ondrop="dropProgress(event,'${viewEl.id}',${i})" style="border:1px solid var(--border);background:var(--bg-tertiary);border-radius:8px;padding:10px;margin-bottom:8px;">
                <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:8px;"><div style="display:flex;align-items:center;gap:8px;"><button type="button" draggable="true" title="Arrastar" ondragstart="startProgressDrag(event,'${viewEl.id}',${i})" style="width:30px;height:28px;border:1px solid var(--border);background:var(--bg-secondary);color:var(--text-secondary);border-radius:6px;cursor:grab;">☷</button><strong style="font-size:11px;">Barra ${i+1}</strong></div><button type="button" onclick="removeProgressItem('${viewEl.id}',${i})" title="Excluir" style="border:0;background:none;color:var(--error);font-size:17px;cursor:pointer;">×</button></div>
                <div class="prop-group"><label class="prop-label">Nome</label><input class="prop-input" value="${esc(item.label||'')}" onchange="updateProgressItem('${viewEl.id}',${i},'label',this.value)"></div>
                <div class="prop-group"><label class="prop-label">Valor (%)</label><input type="number" min="0" max="100" class="prop-input" value="${esc(item.value||0)}" onchange="updateProgressItem('${viewEl.id}',${i},'value',this.value)"></div>
            </div>`).join('')}
            <button type="button" onclick="addProgressItem('${viewEl.id}')" style="width:100%;height:36px;border:1px solid var(--accent);background:var(--accent-glow);color:var(--accent);border-radius:7px;cursor:pointer;font-weight:700;"><i class="fas fa-plus"></i> Adicionar barra</button>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-ruler-horizontal"></i> Layout</div>
            <div style="display:grid;grid-template-columns:1fr 90px;gap:7px;"><div class="prop-group"><label class="prop-label">Largura</label><input type="number" min="1" max="2000" class="prop-input" value="${esc(s.width??100)}" onchange="updateStyle('${viewEl.id}','width',this.value)"></div><div class="prop-group"><label class="prop-label">Unidade</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','widthUnit',this.value)"><option value="%" ${(s.widthUnit||'%')==='%'?'selected':''}>%</option><option value="px" ${s.widthUnit==='px'?'selected':''}>px</option></select></div></div>
            <div class="prop-group"><label class="prop-label">Alinhamento</label><div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px;">${[['left','Esq.'],['center','Centro'],['right','Dir.']].map(([a,l])=>`<button type="button" onclick="updateStyle('${viewEl.id}','alignment','${a}');updateProperties('${viewEl.id}')" style="height:34px;border:1px solid ${(s.alignment||'left')===a?'var(--accent)':'var(--border)'};background:${(s.alignment||'left')===a?'var(--accent-glow)':'var(--bg-tertiary)'};color:var(--text-secondary);border-radius:6px;cursor:pointer">${l}</button>`).join('')}</div></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-palette"></i> Aparência</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Título (px)</label><input type="number" min="10" max="72" class="prop-input" value="${esc(s.titleFontSize??28)}" oninput="updateStyleLive('${viewEl.id}','titleFontSize',this.value)" onblur="updateStyle('${viewEl.id}','titleFontSize',this.value)"></div><div class="prop-group"><label class="prop-label">Descrição (px)</label><input type="number" min="10" max="30" class="prop-input" value="${esc(s.descriptionFontSize??14)}" oninput="updateStyleLive('${viewEl.id}','descriptionFontSize',this.value)" onblur="updateStyle('${viewEl.id}','descriptionFontSize',this.value)"></div></div>
            <div class="prop-group"><label class="prop-label">Cor do título</label>${colorInputProg('titleColor','#111827')}</div>
            <div class="prop-group"><label class="prop-label">Cor da descrição</label>${colorInputProg('descriptionColor','#6b7280')}</div>
            <div class="prop-group"><label class="prop-label">Cor do nome</label>${colorInputProg('labelColor','#111827')}</div>
            <div class="prop-group"><label class="prop-label">Cor do percentual</label>${colorInputProg('valueColor','#475569')}</div>
            <div class="prop-group"><label class="prop-label">Cor do fundo da barra</label>${colorInputProg('trackColor','#e5e7eb')}</div>
            <div class="prop-group"><label class="prop-label">Cor do preenchimento</label>${colorInputProg('fillColor','#3b82f6')}</div>
            <div class="prop-group"><label class="prop-label">Cor final / listras</label>${colorInputProg('fillColorEnd','#60a5fa')}</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Nome (px)</label><input type="number" min="8" max="32" class="prop-input" value="${esc(s.labelFontSize??14)}" onchange="updateStyle('${viewEl.id}','labelFontSize',this.value)"></div><div class="prop-group"><label class="prop-label">Percentual (px)</label><input type="number" min="8" max="32" class="prop-input" value="${esc(s.valueFontSize??13)}" onchange="updateStyle('${viewEl.id}','valueFontSize',this.value)"></div></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Altura (px)</label><input type="number" min="2" max="60" class="prop-input" value="${esc(s.barHeight??12)}" onchange="updateStyle('${viewEl.id}','barHeight',this.value)"></div><div class="prop-group"><label class="prop-label">Raio (px)</label><input type="number" min="0" max="80" class="prop-input" value="${esc(s.radius??999)}" onchange="updateStyle('${viewEl.id}','radius',this.value)"></div></div>
            <div class="prop-group"><label class="prop-label">Estilo da barra</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','barStyle',this.value)"><option value="solid" ${(s.barStyle||'solid')==='solid'?'selected':''}>Sólida</option><option value="gradient" ${s.barStyle==='gradient'?'selected':''}>Gradiente</option><option value="striped" ${s.barStyle==='striped'?'selected':''}>Listrada</option></select></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;"><div class="prop-group"><label class="prop-label">Espaço entre barras</label><input type="number" min="0" max="80" class="prop-input" value="${esc(s.itemSpacing??18)}" onchange="updateStyle('${viewEl.id}','itemSpacing',this.value)"></div><div class="prop-group"><label class="prop-label">Peso do nome</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','labelWeight',this.value)"><option value="500" ${String(s.labelWeight||'600')==='500'?'selected':''}>Normal</option><option value="600" ${String(s.labelWeight||'600')==='600'?'selected':''}>Seminegrito</option><option value="700" ${String(s.labelWeight||'600')==='700'?'selected':''}>Negrito</option></select></div></div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Mostrar percentual</span><input type="checkbox" ${s.showPercent!==false?'checked':''} onchange="updateStyle('${viewEl.id}','showPercent',this.checked);updateProperties('${viewEl.id}')"></label></div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Percentual dentro da barra</span><input type="checkbox" ${s.showValueOnTrack?'checked':''} onchange="updateStyle('${viewEl.id}','showValueOnTrack',this.checked);updateProperties('${viewEl.id}')"></label></div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Animar preenchimento</span><input type="checkbox" ${s.animate!==false?'checked':''} onchange="updateStyle('${viewEl.id}','animate',this.checked)"></label></div>
            ${s.animate !== false ? `<div class="prop-group"><label class="prop-label">Duração da animação (ms)</label><input type="number" min="100" max="5000" class="prop-input" value="${esc(s.animationDuration??900)}" onchange="updateStyle('${viewEl.id}','animationDuration',this.value)"></div>` : ''}
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Ativar sombra</span><input type="checkbox" ${s.shadow?'checked':''} onchange="updateStyle('${viewEl.id}','shadow',this.checked);updateProperties('${viewEl.id}')"></label></div>
            ${s.shadow ? `<div class="prop-group"><label class="prop-label">Cor da sombra</label>${colorInputProg('shadowColor','rgba(0,0,0,0.10)')}</div><div class="prop-group"><label class="prop-label">Desfoque da sombra</label><input type="number" min="0" max="50" class="prop-input" value="${esc(s.shadowBlur??8)}" onchange="updateStyle('${viewEl.id}','shadowBlur',this.value)"></div>` : ''}
        </div>`;
    }

    if (viewEl.type === 'hero') {
        const s = viewEl.styles || {};
        const colorInputHero = (prop, fallback) => {
            const raw = s[prop] || fallback;
            const pick = /^#[0-9a-f]{6}$/i.test(raw) ? raw : fallback;
            return `<div class="color-row"><input type="color" value="${esc(pick)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"><input class="prop-input" value="${esc(raw)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"></div>`;
        };
        html += `
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-image"></i> Hero</div>
            <div class="prop-group"><label class="prop-label">Título</label><textarea class="prop-input" rows=2 onchange="updateStyle('${viewEl.id}','title',this.value)">${escapeHtml(s.title || '')}</textarea></div>
            <div class="prop-group"><label class="prop-label">Descrição</label><textarea class="prop-input" rows=3 onchange="updateStyle('${viewEl.id}','description',this.value)">${escapeHtml(s.description || '')}</textarea></div>
            <div class="prop-group"><label class="prop-label">Texto do botão</label><input class="prop-input" value="${esc(s.buttonText || 'Começar agora')}" onchange="updateStyle('${viewEl.id}','buttonText',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Link do botão</label><input class="prop-input" value="${esc(s.buttonLink || '#')}" onchange="updateStyle('${viewEl.id}','buttonLink',this.value)" placeholder="https://..."></div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Abrir link em nova aba</span><input type="checkbox" ${s.buttonTargetBlank?'checked':''} onchange="updateStyle('${viewEl.id}','buttonTargetBlank',this.checked)"></label></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-palette"></i> Fundo</div>
            <div class="prop-group"><label class="prop-label">Tipo do fundo</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','bgType',this.value);updateProperties('${viewEl.id}')"><option value="color" ${s.bgType==='color'?'selected':''}>Cor sólida</option><option value="gradient" ${(s.bgType||'gradient')==='gradient'?'selected':''}>Gradiente</option><option value="image" ${s.bgType==='image'?'selected':''}>Imagem</option></select></div>
            ${s.bgType==='image' ? `<div class="prop-group"><label class="prop-label">URL da imagem de fundo</label><input class="prop-input" value="${esc(s.bgImage || '')}" onchange="updateStyle('${viewEl.id}','bgImage',this.value)" placeholder="https://..."></div>` : ''}
            <div class="prop-group"><label class="prop-label">Cor principal</label>${colorInputHero('bgColor','#0f172a')}</div>
            ${s.bgType==='gradient' || !s.bgType ? `<div class="prop-group"><label class="prop-label">Cor final</label>${colorInputHero('bgColor2','#2563eb')}</div><div class="prop-group"><label class="prop-label">Direção do gradiente</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','gradientDirection',this.value)"><option value="to right" ${(s.gradientDirection||'to right')==='to right'?'selected':''}>Esquerda → direita</option><option value="to bottom" ${s.gradientDirection==='to bottom'?'selected':''}>Cima → baixo</option><option value="135deg" ${s.gradientDirection==='135deg'?'selected':''}>Diagonal</option><option value="to top right" ${s.gradientDirection==='to top right'?'selected':''}>Baixo esquerdo → cima direito</option></select></div>` : ''}
            ${s.bgType==='image' ? `<div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Ativar sobreposição escura</span><input type="checkbox" ${s.overlay!==false?'checked':''} onchange="updateStyle('${viewEl.id}','overlay',this.checked);updateProperties('${viewEl.id}')"></label></div>${s.overlay!==false ? `<div class="prop-group"><label class="prop-label">Cor da sobreposição</label>${colorInputHero('overlayColor','#000000')}</div>` : ''}` : ''}
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-ruler-combined"></i> Layout</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px"><div class="prop-group"><label class="prop-label">Altura mínima (px)</label><input type="number" min=180 max=1000 class="prop-input" value="${esc(s.minHeight ?? 480)}" onchange="updateStyle('${viewEl.id}','minHeight',this.value)"></div><div class="prop-group"><label class="prop-label">Largura do conteúdo (%)</label><input type="number" min=20 max=100 class="prop-input" value="${esc(s.contentWidth ?? 100)}" onchange="updateStyle('${viewEl.id}','contentWidth',this.value)"></div></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px"><div class="prop-group"><label class="prop-label">Padding vertical</label><input type="number" min=0 max=200 class="prop-input" value="${esc(s.paddingY ?? 70)}" onchange="updateStyle('${viewEl.id}','paddingY',this.value)"></div><div class="prop-group"><label class="prop-label">Padding horizontal</label><input type="number" min=0 max=120 class="prop-input" value="${esc(s.paddingX ?? 24)}" onchange="updateStyle('${viewEl.id}','paddingX',this.value)"></div></div>
            <div class="prop-group"><label class="prop-label">Alinhamento do conteúdo</label><div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px">${[['left','Esq.'],['center','Centro'],['right','Dir.']].map(([a,l])=>`<button type="button" onclick="updateStyle('${viewEl.id}','contentAlign','${a}');updateProperties('${viewEl.id}')" style="height:34px;border:1px solid ${(s.contentAlign||'center')===a?'var(--accent)':'var(--border)'};background:${(s.contentAlign||'center')===a?'var(--accent-glow)':'var(--bg-tertiary)'};color:var(--text-secondary);border-radius:6px;cursor:pointer">${l}</button>`).join('')}</div></div>
            <div class="prop-group"><label class="prop-label">Raio do Hero (px)</label><input type="number" min=0 max=80 class="prop-input" value="${esc(s.borderRadius ?? 0)}" onchange="updateStyle('${viewEl.id}','borderRadius',this.value)"></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-font"></i> Tipografia e botão</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px"><div class="prop-group"><label class="prop-label">Título (px)</label><input type="number" min=18 max=96 class="prop-input" value="${esc(s.titleFontSize ?? 52)}" oninput="updateStyleLive('${viewEl.id}','titleFontSize',this.value)" onblur="updateStyle('${viewEl.id}','titleFontSize',this.value)"></div><div class="prop-group"><label class="prop-label">Peso</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','titleWeight',this.value)">${['400','500','600','700','800','900'].map(w=>`<option value="${w}" ${String(s.titleWeight||'800')===w?'selected':''}>${w}</option>`).join('')}</select></div></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px"><div class="prop-group"><label class="prop-label">Descrição (px)</label><input type="number" min=11 max=36 class="prop-input" value="${esc(s.descriptionFontSize ?? 18)}" oninput="updateStyleLive('${viewEl.id}','descriptionFontSize',this.value)" onblur="updateStyle('${viewEl.id}','descriptionFontSize',this.value)"></div><div class="prop-group"><label class="prop-label">Linha descrição</label><input type="number" min=1 max=2.5 step=0.05 class="prop-input" value="${esc(s.descriptionLineHeight ?? 1.6)}" onchange="updateStyle('${viewEl.id}','descriptionLineHeight',this.value)"></div></div>
            <div class="prop-group"><label class="prop-label">Cor do título</label>${colorInputHero('titleColor','#ffffff')}</div>
            <div class="prop-group"><label class="prop-label">Cor da descrição</label>${colorInputHero('descriptionColor','#e2e8f0')}</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px"><div class="prop-group"><label class="prop-label">Fonte do botão (px)</label><input type="number" min=11 max=28 class="prop-input" value="${esc(s.buttonFontSize ?? 15)}" onchange="updateStyle('${viewEl.id}','buttonFontSize',this.value)"></div><div class="prop-group"><label class="prop-label">Raio botão</label><input type="number" min=0 max=60 class="prop-input" value="${esc(s.buttonRadius ?? 10)}" onchange="updateStyle('${viewEl.id}','buttonRadius',this.value)"></div></div>
            <div class="prop-group"><label class="prop-label">Fundo do botão</label>${colorInputHero('buttonBg','#3b82f6')}</div>
            <div class="prop-group"><label class="prop-label">Hover do botão</label>${colorInputHero('buttonBgHover','#2563eb')}</div>
            <div class="prop-group"><label class="prop-label">Texto do botão</label>${colorInputHero('buttonColor','#ffffff')}</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px"><div class="prop-group"><label class="prop-label">Padding vertical botão</label><input type="number" min=6 max=30 class="prop-input" value="${esc(s.buttonPaddingY ?? 13)}" onchange="updateStyle('${viewEl.id}','buttonPaddingY',this.value)"></div><div class="prop-group"><label class="prop-label">Padding horizontal botão</label><input type="number" min=10 max=60 class="prop-input" value="${esc(s.buttonPaddingX ?? 24)}" onchange="updateStyle('${viewEl.id}','buttonPaddingX',this.value)"></div></div>
        </div>`;
    }

    if (viewEl.type === 'newsletter') {
        const s = viewEl.styles || {};
        const colorInputNews = (prop, fallback) => {
            const raw = s[prop] || fallback;
            const pick = /^#[0-9a-f]{6}$/i.test(raw) ? raw : fallback;
            return `<div class="color-row"><input type="color" value="${esc(pick)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"><input class="prop-input" value="${esc(raw)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"></div>`;
        };
        html += `
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-paper-plane"></i> Newsletter</div>
            <div class="prop-group"><label class="prop-label">Título</label><input class="prop-input" value="${esc(s.title || 'Receba nossas novidades')}" onchange="updateStyle('${viewEl.id}','title',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Descrição</label><textarea class="prop-input" rows="2" onchange="updateStyle('${viewEl.id}','description',this.value)">${escapeHtml(s.description || '')}</textarea></div>
            <div class="prop-group"><label class="prop-label">Texto do botão</label><input class="prop-input" value="${esc(s.buttonText || 'Assinar newsletter')}" onchange="updateStyle('${viewEl.id}','buttonText',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Placeholder do e-mail</label><input class="prop-input" value="${esc(s.emailPlaceholder || 'Seu melhor e-mail')}" onchange="updateStyle('${viewEl.id}','emailPlaceholder',this.value)"></div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Mostrar campo Nome</span><input type="checkbox" ${s.showName?'checked':''} onchange="updateStyle('${viewEl.id}','showName',this.checked);updateProperties('${viewEl.id}')"></label></div>
            ${s.showName ? `<div class="prop-group"><label class="prop-label">Placeholder do nome</label><input class="prop-input" value="${esc(s.namePlaceholder || 'Seu nome')}" onchange="updateStyle('${viewEl.id}','namePlaceholder',this.value)"></div>` : ''}
            <div class="prop-group"><label class="prop-label">Mensagem de sucesso</label><input class="prop-input" value="${esc(s.successMessage || 'Obrigado! Inscrição realizada com sucesso.')}" onchange="updateStyle('${viewEl.id}','successMessage',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Texto de consentimento (opcional)</label><textarea class="prop-input" rows="2" onchange="updateStyle('${viewEl.id}','consent',this.value)">${escapeHtml(s.consent || '')}</textarea></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-ruler-horizontal"></i> Layout</div>
            <div style="display:grid;grid-template-columns:1fr 90px;gap:6px"><div class="prop-group"><label class="prop-label">Largura</label><input type="number" min="1" max="2000" class="prop-input" value="${esc(s.width ?? 100)}" onchange="updateStyle('${viewEl.id}','width',this.value)"></div><div class="prop-group"><label class="prop-label">Unidade</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','widthUnit',this.value)"><option value="%" ${s.widthUnit !== 'px'?'selected':''}>%</option><option value="px" ${s.widthUnit === 'px'?'selected':''}>px</option></select></div></div>
            <div class="prop-group"><label class="prop-label">Alinhamento</label><div style="display:grid;grid-template-columns:repeat(3,1fr);gap:5px"><button type="button" class="prop-btn ${s.alignment==='left'?'active':''}" onclick="updateStyle('${viewEl.id}','alignment','left');updateProperties('${viewEl.id}')">Esq.</button><button type="button" class="prop-btn ${s.alignment==='center'?'active':''}" onclick="updateStyle('${viewEl.id}','alignment','center');updateProperties('${viewEl.id}')">Centro</button><button type="button" class="prop-btn ${s.alignment==='right'?'active':''}" onclick="updateStyle('${viewEl.id}','alignment','right');updateProperties('${viewEl.id}')">Dir.</button></div></div>
            <div class="prop-group"><label class="prop-label">Formato</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','layout',this.value);updateProperties('${viewEl.id}')"><option value="inline" ${(s.layout||'inline')==='inline'?'selected':''}>Campos na mesma linha</option><option value="stacked" ${s.layout==='stacked'?'selected':''}>Campos empilhados</option></select></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Espaçamento (px)</label><input type="number" min="0" max="60" class="prop-input" value="${esc(s.gap ?? 10)}" onchange="updateStyle('${viewEl.id}','gap',this.value)"></div><div class="prop-group"><label class="prop-label">Altura do campo (px)</label><input type="number" min="30" max="100" class="prop-input" value="${esc(s.inputHeight ?? 44)}" onchange="updateStyle('${viewEl.id}','inputHeight',this.value)"></div></div>
            <div class="prop-group"><label class="prop-label">Altura do botão (px)</label><input type="number" min="30" max="100" class="prop-input" value="${esc(s.buttonHeight ?? 44)}" onchange="updateStyle('${viewEl.id}','buttonHeight',this.value)"></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-palette"></i> Cores</div>
            <div class="prop-group"><label class="prop-label">Fundo da seção</label>${colorInputNews('sectionBg','#f8fafc')}</div>
            <div class="prop-group"><label class="prop-label">Fundo do cartão</label>${colorInputNews('cardBg','#ffffff')}</div>
            <div class="prop-group"><label class="prop-label">Cor do título</label>${colorInputNews('titleColor','#111827')}</div>
            <div class="prop-group"><label class="prop-label">Cor da descrição</label>${colorInputNews('descriptionColor','#6b7280')}</div>
            <div class="prop-group"><label class="prop-label">Fundo do campo</label>${colorInputNews('inputBg','#ffffff')}</div>
            <div class="prop-group"><label class="prop-label">Cor do texto</label>${colorInputNews('inputColor','#111827')}</div>
            <div class="prop-group"><label class="prop-label">Cor do placeholder</label>${colorInputNews('placeholderColor','#9ca3af')}</div>
            <div class="prop-group"><label class="prop-label">Cor da borda</label>${colorInputNews('inputBorderColor','#d1d5db')}</div>
            <div class="prop-group"><label class="prop-label">Fundo do botão</label>${colorInputNews('buttonBg','#3b82f6')}</div>
            <div class="prop-group"><label class="prop-label">Cor do texto do botão</label>${colorInputNews('buttonColor','#ffffff')}</div>
            <div class="prop-group"><label class="prop-label">Fundo do botão no hover</label>${colorInputNews('buttonBgHover','#2563eb')}</div>
            <div class="prop-group"><label class="prop-label">Cor do consentimento</label>${colorInputNews('consentColor','#6b7280')}</div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-text-height"></i> Tipografia</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Título (px)</label><input type="number" min="12" max="72" class="prop-input" value="${esc(s.titleFontSize ?? 28)}" oninput="updateStyleLive('${viewEl.id}','titleFontSize',this.value)" onblur="updateStyle('${viewEl.id}','titleFontSize',this.value)"></div><div class="prop-group"><label class="prop-label">Descrição (px)</label><input type="number" min="10" max="32" class="prop-input" value="${esc(s.descriptionFontSize ?? 14)}" oninput="updateStyleLive('${viewEl.id}','descriptionFontSize',this.value)" onblur="updateStyle('${viewEl.id}','descriptionFontSize',this.value)"></div></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Campo (px)</label><input type="number" min="10" max="30" class="prop-input" value="${esc(s.inputFontSize ?? 14)}" onchange="updateStyle('${viewEl.id}','inputFontSize',this.value)"></div><div class="prop-group"><label class="prop-label">Botão (px)</label><input type="number" min="10" max="30" class="prop-input" value="${esc(s.buttonFontSize ?? 14)}" onchange="updateStyle('${viewEl.id}','buttonFontSize',this.value)"></div></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-border-all"></i> Bordas e cartão</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Borda do campo (px)</label><input type="number" min="0" max="10" class="prop-input" value="${esc(s.inputBorderWidth ?? 1)}" onchange="updateStyle('${viewEl.id}','inputBorderWidth',this.value)"></div><div class="prop-group"><label class="prop-label">Raio do campo (px)</label><input type="number" min="0" max="50" class="prop-input" value="${esc(s.inputRadius ?? 8)}" onchange="updateStyle('${viewEl.id}','inputRadius',this.value)"></div></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Raio do botão (px)</label><input type="number" min="0" max="50" class="prop-input" value="${esc(s.buttonRadius ?? 8)}" onchange="updateStyle('${viewEl.id}','buttonRadius',this.value)"></div><div class="prop-group"><label class="prop-label">Raio do cartão (px)</label><input type="number" min="0" max="80" class="prop-input" value="${esc(s.cardRadius ?? 16)}" onchange="updateStyle('${viewEl.id}','cardRadius',this.value)"></div></div>
            <div class="prop-group"><label class="prop-label">Padding do cartão (px)</label><input type="number" min="0" max="100" class="prop-input" value="${esc(s.cardPadding ?? 26)}" onchange="updateStyle('${viewEl.id}','cardPadding',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Espaçamento vertical da seção (px)</label><input type="number" min="0" max="120" class="prop-input" value="${esc(s.sectionPaddingY ?? 0)}" onchange="updateStyle('${viewEl.id}','sectionPaddingY',this.value)"></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-link"></i> Ação</div>
            <div class="prop-group"><label class="prop-label">URL de destino</label><input class="prop-input" value="${esc(s.actionUrl || '#')}" onchange="updateStyle('${viewEl.id}','actionUrl',this.value)" placeholder="https://..."></div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Abrir URL em nova aba</span><input type="checkbox" ${s.targetBlank?'checked':''} onchange="updateStyle('${viewEl.id}','targetBlank',this.checked)"></label></div>
        </div>
        <div class="prop-section">
            <div class="prop-title"><i class="fas fa-cloud"></i> Sombra</div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Ativar sombra</span><input type="checkbox" ${s.shadow?'checked':''} onchange="updateStyle('${viewEl.id}','shadow',this.checked);updateProperties('${viewEl.id}')"></label></div>
            ${s.shadow ? `<div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">X</label><input type="number" class="prop-input" value="${esc(s.shadowX ?? 0)}" onchange="updateStyle('${viewEl.id}','shadowX',this.value)"></div><div class="prop-group"><label class="prop-label">Y</label><input type="number" class="prop-input" value="${esc(s.shadowY ?? 8)}" onchange="updateStyle('${viewEl.id}','shadowY',this.value)"></div><div class="prop-group"><label class="prop-label">Desfoque</label><input type="number" min="0" class="prop-input" value="${esc(s.shadowBlur ?? 24)}" onchange="updateStyle('${viewEl.id}','shadowBlur',this.value)"></div><div class="prop-group"><label class="prop-label">Espalhar</label><input type="number" class="prop-input" value="${esc(s.shadowSpread ?? 0)}" onchange="updateStyle('${viewEl.id}','shadowSpread',this.value)"></div></div><div class="prop-group"><label class="prop-label">Cor da sombra</label>${colorInputNews('shadowColor','rgba(0,0,0,0.10)')}</div>` : ''}
        </div>`;
    }

    if (viewEl.type === 'navbar') {
        const s = viewEl.styles || {};
        const val = (v='') => esc(v == null ? '' : String(v));
        const colorInputNav = (prop, fallback) => {
            const raw = s[prop] || fallback;
            const picker = /^#[0-9a-f]{6}$/i.test(raw) ? raw : fallback;
            return `<div class="color-row"><input type="color" value="${esc(picker)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"><input type="text" value="${esc(raw)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"></div>`;
        };
        const items = ensureNavbarItems(s);
        html += `
        <div class="prop-section"><div class="prop-title"><i class="fas fa-bars"></i> Conteúdo</div>
            <div class="prop-group"><label class="prop-label">Nome da marca</label><input class="prop-input" value="${val(s.brand||'Minha Empresa')}" onchange="updateStyle('${viewEl.id}','brand',this.value)"></div>
            <div class="prop-group"><label class="prop-label">URL do logo</label><input class="prop-input" value="${val(s.logoUrl||'')}" placeholder="https://..." onchange="updateStyle('${viewEl.id}','logoUrl',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Texto alternativo</label><input class="prop-input" value="${val(s.logoAlt||'Logo')}" onchange="updateStyle('${viewEl.id}','logoAlt',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Altura do logo (px)</label><input type="number" min="18" max="80" class="prop-input" value="${val(s.logoHeight??34)}" onchange="updateStyle('${viewEl.id}','logoHeight',this.value)"></div>
        </div>
        <div class="prop-section"><div class="prop-title"><i class="fas fa-link"></i> Itens do menu</div>
            <div style="font-size:10px;color:var(--text-tertiary);margin-bottom:8px;">Arraste pelo ☷ para alterar a ordem.</div>
            ${items.map((item,i)=>`<div ondragover="allowNavbarDrop(event)" ondrop="dropNavbarItem(event,'${viewEl.id}',${i})" style="border:1px solid var(--border);background:var(--bg-tertiary);border-radius:8px;padding:9px;margin-bottom:8px;">
                <div style="display:flex;align-items:center;gap:6px;margin-bottom:7px;"><button type="button" draggable="true" title="Arrastar" ondragstart="startNavbarDrag(event,'${viewEl.id}',${i})" style="width:30px;height:28px;border:1px solid var(--border);background:var(--bg-secondary);color:var(--text-secondary);border-radius:6px;cursor:grab;">☷</button><strong style="font-size:11px;flex:1;">Item ${i+1}</strong><button type="button" onclick="removeNavbarItem('${viewEl.id}',${i})" style="border:0;background:none;color:var(--error);font-size:17px;cursor:pointer;">×</button></div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;"><div class="prop-group" style="margin:0"><label class="prop-label">Texto</label><input class="prop-input" value="${val(item.label||'')}" onchange="updateNavbarItem('${viewEl.id}',${i},'label',this.value)"></div><div class="prop-group" style="margin:0"><label class="prop-label">Link</label><input class="prop-input" value="${val(item.link||'#')}" onchange="updateNavbarItem('${viewEl.id}',${i},'link',this.value)"></div></div>
                <label style="display:flex;align-items:center;justify-content:space-between;margin-top:7px;"><span style="font-size:10px;color:var(--text-secondary)">Abrir em nova aba</span><input type="checkbox" ${item.targetBlank?'checked':''} onchange="updateNavbarItem('${viewEl.id}',${i},'targetBlank',this.checked)"></label>
            </div>`).join('')}
            <button type="button" onclick="addNavbarItem('${viewEl.id}')" style="width:100%;height:36px;border:1px solid var(--accent);background:var(--accent-glow);color:var(--accent);border-radius:7px;cursor:pointer;font-weight:700;"><i class="fas fa-plus"></i> Adicionar item</button>
        </div>
        <div class="prop-section"><div class="prop-title"><i class="fas fa-mobile-screen-button"></i> Menu mobile / Hambúrguer</div>
            <div class="prop-group"><label class="prop-label">Tipo do botão</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','mobileButtonType',this.value)"><option value="icon" ${String(s.mobileButtonType||'icon')==='icon'?'selected':''}>Somente ícone</option><option value="icon-text" ${String(s.mobileButtonType)==='icon-text'?'selected':''}>Ícone + Menu</option><option value="text" ${String(s.mobileButtonType)==='text'?'selected':''}>Somente texto</option></select></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Ícone</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','mobileIcon',this.value)"><option value="bars" ${String(s.mobileIcon||'bars')==='bars'?'selected':''}>☰ Barras</option><option value="list" ${String(s.mobileIcon)==='list'?'selected':''}>☷ Lista</option><option value="grid" ${String(s.mobileIcon)==='grid'?'selected':''}>▦ Grade</option><option value="ellipsis" ${String(s.mobileIcon)==='ellipsis'?'selected':''}>••• Pontos</option></select></div><div class="prop-group"><label class="prop-label">Posição</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','mobilePosition',this.value)"><option value="left" ${String(s.mobilePosition)==='left'?'selected':''}>Esquerda</option><option value="right" ${String(s.mobilePosition||'right')==='right'?'selected':''}>Direita</option></select></div></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Largura (px)</label><input type="number" min="30" max="100" class="prop-input" value="${val(s.mobileButtonWidth??42)}" onchange="updateStyle('${viewEl.id}','mobileButtonWidth',this.value)"></div><div class="prop-group"><label class="prop-label">Altura (px)</label><input type="number" min="28" max="70" class="prop-input" value="${val(s.mobileButtonHeight??38)}" onchange="updateStyle('${viewEl.id}','mobileButtonHeight',this.value)"></div></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Tamanho do ícone</label><input type="number" min="12" max="32" class="prop-input" value="${val(s.mobileButtonSize??18)}" onchange="updateStyle('${viewEl.id}','mobileButtonSize',this.value)"></div><div class="prop-group"><label class="prop-label">Raio (px)</label><input type="number" min="0" max="30" class="prop-input" value="${val(s.mobileButtonRadius??8)}" onchange="updateStyle('${viewEl.id}','mobileButtonRadius',this.value)"></div></div>
            <div class="prop-group"><label class="prop-label">Fundo do botão</label>${colorInputNav('mobileButtonBg','#ffffff')}</div>
            <div class="prop-group"><label class="prop-label">Cor do botão</label>${colorInputNav('mobileButtonColor','#1f2937')}</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Alinhamento dos itens</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','mobileItemAlign',this.value)"><option value="left" ${String(s.mobileItemAlign||'left')==='left'?'selected':''}>Esquerda</option><option value="center" ${String(s.mobileItemAlign)==='center'?'selected':''}>Centro</option><option value="right" ${String(s.mobileItemAlign)==='right'?'selected':''}>Direita</option></select></div><div class="prop-group"><label class="prop-label">Espaço entre itens</label><input type="number" min="0" max="30" class="prop-input" value="${val(s.mobileItemGap??4)}" onchange="updateStyle('${viewEl.id}','mobileItemGap',this.value)"></div></div>
            <div class="prop-group"><label class="prop-label">Espaço vertical dos itens</label><input type="number" min="4" max="30" class="prop-input" value="${val(s.mobileItemPaddingY??10)}" onchange="updateStyle('${viewEl.id}','mobileItemPaddingY',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Ponto de quebra (px)</label><input type="number" min="480" max="1400" class="prop-input" value="${val(s.mobileBreakpoint??1024)}" onchange="updateStyle('${viewEl.id}','mobileBreakpoint',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Fundo do menu aberto</label>${colorInputNav('mobileBg','#ffffff')}</div>
        </div>
        <div class="prop-section"><div class="prop-title"><i class="fas fa-ruler-horizontal"></i> Layout</div>
            <div class="prop-group"><label class="prop-label">Largura</label><div style="display:grid;grid-template-columns:1fr 80px;gap:6px"><input type="number" min="1" max="2000" class="prop-input" value="${val(s.width??100)}" onchange="updateStyle('${viewEl.id}','width',this.value)"><select class="prop-select" onchange="updateStyle('${viewEl.id}','widthUnit',this.value)"><option value="%" ${s.widthUnit!=='px'?'selected':''}>%</option><option value="px" ${s.widthUnit==='px'?'selected':''}>px</option></select></div></div>
            <div class="prop-group"><label class="prop-label">Distribuição</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','alignment',this.value)"><option value="space-between" ${String(s.alignment||'space-between')==='space-between'?'selected':''}>Logo + menu nas extremidades</option><option value="left" ${s.alignment==='left'?'selected':''}>Esquerda</option><option value="center" ${s.alignment==='center'?'selected':''}>Centro</option><option value="right" ${s.alignment==='right'?'selected':''}>Direita</option></select></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Padding vertical</label><input type="number" min="4" max="50" class="prop-input" value="${val(s.navPaddingY??14)}" onchange="updateStyle('${viewEl.id}','navPaddingY',this.value)"></div><div class="prop-group"><label class="prop-label">Padding horizontal</label><input type="number" min="4" max="60" class="prop-input" value="${val(s.navPaddingX??20)}" onchange="updateStyle('${viewEl.id}','navPaddingX',this.value)"></div></div>
            <div class="prop-group"><label class="prop-label">Espaço entre itens (px)</label><input type="number" min="4" max="80" class="prop-input" value="${val(s.itemGap??24)}" onchange="updateStyle('${viewEl.id}','itemGap',this.value)"></div>
        </div>
        <div class="prop-section"><div class="prop-title"><i class="fas fa-palette"></i> Cores</div>
            <div class="prop-group"><label class="prop-label">Fundo da barra</label>${colorInputNav('navBg','#ffffff')}</div>
            <div class="prop-group"><label class="prop-label">Cor do texto</label>${colorInputNav('navColor','#1f2937')}</div>
            <div class="prop-group"><label class="prop-label">Cor no hover</label>${colorInputNav('navHoverColor','#3b82f6')}</div>
            <div class="prop-group"><label class="prop-label">Cor de foco</label>${colorInputNav('navActiveColor','#3b82f6')}</div>
            <div class="prop-group"><label class="prop-label">Cor da borda</label>${colorInputNav('navBorderColor','#e5e7eb')}</div>
        </div>
        <div class="prop-section"><div class="prop-title"><i class="fas fa-font"></i> Tipografia</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Tamanho (px)</label><input type="number" min="9" max="30" class="prop-input" value="${val(s.fontSize??14)}" onchange="updateStyle('${viewEl.id}','fontSize',this.value)"></div><div class="prop-group"><label class="prop-label">Peso</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','fontWeight',this.value)">${['400','500','600','700','800'].map(v=>`<option value="${v}" ${String(s.fontWeight??'600')===v?'selected':''}>${v}</option>`).join('')}</select></div></div>
            <div class="prop-group"><label class="prop-label">Decoração do link</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','linkUnderline',this.value)"><option value="none" ${String(s.linkUnderline||'none')==='none'?'selected':''}>Nenhuma</option><option value="underline" ${s.linkUnderline==='underline'?'selected':''}>Sublinhado</option></select></div>
        </div>
        <div class="prop-section"><div class="prop-title"><i class="fas fa-border-all"></i> Bordas e sombra</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Borda (px)</label><input type="number" min="0" max="8" class="prop-input" value="${val(s.navBorderWidth??1)}" onchange="updateStyle('${viewEl.id}','navBorderWidth',this.value)"></div><div class="prop-group"><label class="prop-label">Raio (px)</label><input type="number" min="0" max="60" class="prop-input" value="${val(s.navRadius??0)}" onchange="updateStyle('${viewEl.id}','navRadius',this.value)"></div></div>
            <label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Ativar sombra</span><input type="checkbox" ${s.shadow?'checked':''} onchange="updateStyle('${viewEl.id}','shadow',this.checked);updateProperties('${viewEl.id}')"></label>
            ${s.shadow?`<div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-top:7px"><div class="prop-group"><label class="prop-label">X</label><input type="number" class="prop-input" value="${val(s.shadowX??0)}" onchange="updateStyle('${viewEl.id}','shadowX',this.value)"></div><div class="prop-group"><label class="prop-label">Y</label><input type="number" class="prop-input" value="${val(s.shadowY??4)}" onchange="updateStyle('${viewEl.id}','shadowY',this.value)"></div><div class="prop-group"><label class="prop-label">Desfoque</label><input type="number" min="0" class="prop-input" value="${val(s.shadowBlur??14)}" onchange="updateStyle('${viewEl.id}','shadowBlur',this.value)"></div><div class="prop-group"><label class="prop-label">Espalhar</label><input type="number" class="prop-input" value="${val(s.shadowSpread??0)}" onchange="updateStyle('${viewEl.id}','shadowSpread',this.value)"></div></div><div class="prop-group" style="margin-top:6px"><label class="prop-label">Cor da sombra</label>${colorInputNav('shadowColor','rgba(0,0,0,0.08)')}</div>`:''}
        </div>
        <div class="prop-section"><div class="prop-title"><i class="fas fa-mobile-alt"></i> Responsivo</div>
            <div class="prop-group"><label class="prop-label">Breakpoint tablet/mobile (px)</label><input type="number" min="480" max="1400" class="prop-input" value="${val(s.mobileBreakpoint??1024)}" onchange="updateStyle('${viewEl.id}','mobileBreakpoint',this.value)"></div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Menu fixo no topo</span><input type="checkbox" ${s.sticky?'checked':''} onchange="updateStyle('${viewEl.id}','sticky',this.checked)"></label></div>
            <div class="prop-group"><label class="prop-label">Fundo do menu mobile</label>${colorInputNav('mobileBg',s.mobileBg||'#ffffff')}</div>
        </div>`;
    }

    if (viewEl.type === 'footer') {
        const s = viewEl.styles || {};
        const footerCols = Array.isArray(s.columns) ? s.columns : [];
        const colorInputFooter = (prop, fallback) => `<div class="color-row"><input type="color" value="${esc(s[prop] || fallback)}" oninput="updateStyle('${viewEl.id}','${prop}',this.value)"><input type="text" value="${esc(s[prop] || fallback)}" onchange="updateStyle('${viewEl.id}','${prop}',this.value)"></div>`;
        html += `
        <div class="prop-section"><div class="prop-title"><i class="fas fa-id-card"></i> Conteúdo</div>
            <div class="prop-group"><label class="prop-label">Nome / marca</label><input class="prop-input" value="${esc(s.brand || 'Minha Empresa')}" onchange="updateStyle('${viewEl.id}','brand',this.value)"></div>
            <div class="prop-group"><label class="prop-label">URL do logo</label><input class="prop-input" value="${esc(s.logoUrl || '')}" placeholder="https://..." onchange="updateStyle('${viewEl.id}','logoUrl',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Texto alternativo</label><input class="prop-input" value="${esc(s.logoAlt || 'Logo')}" onchange="updateStyle('${viewEl.id}','logoAlt',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Descrição</label><textarea class="prop-input" rows="3" style="min-height:70px;resize:vertical" onchange="updateStyle('${viewEl.id}','description',this.value)">${escapeHtml(s.description || '')}</textarea></div>
        </div>
        <div class="prop-section"><div class="prop-title"><i class="fas fa-link"></i> Colunas de links</div>
            <div style="font-size:10px;color:var(--text-tertiary);margin-bottom:8px;">Adicione grupos de links que aparecem no rodapé.</div>
            ${footerCols.map((col,i)=>`<div style="border:1px solid var(--border);background:var(--bg-tertiary);border-radius:8px;padding:10px;margin-bottom:8px;">
                <div style="display:flex;align-items:center;gap:6px;margin-bottom:7px;"><strong style="font-size:11px;flex:1;">Coluna ${i+1}</strong><button type="button" onclick="removeFooterColumn('${viewEl.id}',${i})" style="border:0;background:none;color:var(--error);font-size:17px;cursor:pointer;">×</button></div>
                <div class="prop-group" style="margin:0 0 7px"><label class="prop-label">Título</label><input class="prop-input" value="${esc(col.title||'Links')}" onchange="updateFooterColumn('${viewEl.id}',${i},'title',this.value)"></div>
                ${(Array.isArray(col.items)?col.items:[]).map((item,j)=>`<div style="display:grid;grid-template-columns:1fr 1fr auto;gap:6px;margin-bottom:6px;"><input class="prop-input" value="${esc(item.label||'Link')}" placeholder="Texto" onchange="updateFooterColumnItem('${viewEl.id}',${i},${j},'label',this.value)"><input class="prop-input" value="${esc(item.link||'#')}" placeholder="URL" onchange="updateFooterColumnItem('${viewEl.id}',${i},${j},'link',this.value)"><button type="button" onclick="removeFooterColumnItem('${viewEl.id}',${i},${j})" style="width:34px;border:1px solid var(--border);background:var(--bg-secondary);color:var(--error);border-radius:6px;cursor:pointer;">×</button></div>`).join('')}
                <button type="button" onclick="addFooterColumnItem('${viewEl.id}',${i})" style="width:100%;height:30px;border:1px dashed var(--accent);background:var(--accent-glow);color:var(--accent);border-radius:6px;cursor:pointer;font-size:10px;font-weight:700;"><i class="fas fa-plus"></i> Adicionar link</button>
            </div>`).join('')}
            <button type="button" onclick="addFooterColumn('${viewEl.id}')" style="width:100%;height:36px;border:1px solid var(--accent);background:var(--accent-glow);color:var(--accent);border-radius:7px;cursor:pointer;font-weight:700;"><i class="fas fa-plus"></i> Adicionar coluna</button>
        </div>
        <div class="prop-section"><div class="prop-title"><i class="fas fa-address-book"></i> Contato</div>
            <div class="prop-group"><label class="prop-label">Título</label><input class="prop-input" value="${esc(s.contactTitle || 'Contato')}" onchange="updateStyle('${viewEl.id}','contactTitle',this.value)"></div>
            <div class="prop-group"><label class="prop-label">E-mail</label><input class="prop-input" value="${esc(s.email || '')}" onchange="updateStyle('${viewEl.id}','email',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Telefone</label><input class="prop-input" value="${esc(s.phone || '')}" onchange="updateStyle('${viewEl.id}','phone',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Endereço</label><input class="prop-input" value="${esc(s.address || '')}" onchange="updateStyle('${viewEl.id}','address',this.value)"></div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Exibir redes sociais</span><input type="checkbox" ${s.showSocial!==false?'checked':''} onchange="updateStyle('${viewEl.id}','showSocial',this.checked)"></label></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">
                <div class="prop-group"><label class="prop-label">Instagram</label><input class="prop-input" value="${esc(s.instagram || '')}" onchange="updateStyle('${viewEl.id}','instagram',this.value)"></div>
                <div class="prop-group"><label class="prop-label">Facebook</label><input class="prop-input" value="${esc(s.facebook || '')}" onchange="updateStyle('${viewEl.id}','facebook',this.value)"></div>
                <div class="prop-group"><label class="prop-label">LinkedIn</label><input class="prop-input" value="${esc(s.linkedin || '')}" onchange="updateStyle('${viewEl.id}','linkedin',this.value)"></div>
                <div class="prop-group"><label class="prop-label">YouTube</label><input class="prop-input" value="${esc(s.youtube || '')}" onchange="updateStyle('${viewEl.id}','youtube',this.value)"></div>
            </div>
        </div>
        <div class="prop-section"><div class="prop-title"><i class="fas fa-ruler-horizontal"></i> Layout</div>
            <div class="prop-group"><label class="prop-label">Largura</label><div style="display:grid;grid-template-columns:1fr 80px;gap:6px"><input type="number" min="1" max="2000" class="prop-input" value="${esc(s.width??100)}" onchange="updateStyle('${viewEl.id}','width',this.value)"><select class="prop-select" onchange="updateStyle('${viewEl.id}','widthUnit',this.value)"><option value="%" ${s.widthUnit!=='px'?'selected':''}>%</option><option value="px" ${s.widthUnit==='px'?'selected':''}>px</option></select></div></div>
            <div class="prop-group"><label class="prop-label">Largura máxima do conteúdo (px)</label><input type="number" min="320" max="2000" class="prop-input" value="${esc(s.contentWidth??1200)}" onchange="updateStyle('${viewEl.id}','contentWidth',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Alinhamento do copyright</label><div style="display:grid;grid-template-columns:repeat(3,1fr);gap:5px;">${[['left','Esq.'],['center','Centro'],['right','Dir.']].map(([a,l])=>`<button type="button" onclick="updateStyle('${viewEl.id}','alignment','${a}')" style="height:34px;border:1px solid ${(s.alignment||'left')===a?'var(--accent)':'var(--border)'};background:${(s.alignment||'left')===a?'var(--accent-glow)':'var(--bg-tertiary)'};color:var(--text-secondary);border-radius:6px;cursor:pointer">${l}</button>`).join('')}</div></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Padding vertical</label><input type="number" min="12" max="160" class="prop-input" value="${esc(s.paddingY??48)}" onchange="updateStyle('${viewEl.id}','paddingY',this.value)"></div><div class="prop-group"><label class="prop-label">Padding horizontal</label><input type="number" min="10" max="100" class="prop-input" value="${esc(s.paddingX??24)}" onchange="updateStyle('${viewEl.id}','paddingX',this.value)"></div></div>
            <div class="prop-group"><label class="prop-label">Espaçamento entre colunas (px)</label><input type="number" min="8" max="100" class="prop-input" value="${esc(s.columnGap??36)}" onchange="updateStyle('${viewEl.id}','columnGap',this.value)"></div>
        </div>
        <div class="prop-section"><div class="prop-title"><i class="fas fa-palette"></i> Aparência</div>
            <div class="prop-group"><label class="prop-label">Fundo</label>${colorInputFooter('footerBg','#111827')}</div>
            <div class="prop-group"><label class="prop-label">Cor principal</label>${colorInputFooter('footerColor','#f9fafb')}</div>
            <div class="prop-group"><label class="prop-label">Cor secundária</label>${colorInputFooter('mutedColor','#9ca3af')}</div>
            <div class="prop-group"><label class="prop-label">Cor de destaque</label>${colorInputFooter('accentColor','#3b82f6')}</div>
            <div class="prop-group"><label class="prop-label">Cor da linha</label>${colorInputFooter('borderColor','#243244')}</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Altura do logo (px)</label><input type="number" min="18" max="80" class="prop-input" value="${esc(s.logoHeight??34)}" onchange="updateStyle('${viewEl.id}','logoHeight',this.value)"></div><div class="prop-group"><label class="prop-label">Raio (px)</label><input type="number" min="0" max="60" class="prop-input" value="${esc(s.radius??0)}" onchange="updateStyle('${viewEl.id}','radius',this.value)"></div></div>
        </div>
        <div class="prop-section"><div class="prop-title"><i class="fas fa-font"></i> Tipografia</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Títulos (px)</label><input type="number" min="11" max="28" class="prop-input" value="${esc(s.titleFontSize??15)}" oninput="updateStyleLive('${viewEl.id}','titleFontSize',this.value)" onblur="updateStyle('${viewEl.id}','titleFontSize',this.value)"></div><div class="prop-group"><label class="prop-label">Textos (px)</label><input type="number" min="11" max="22" class="prop-input" value="${esc(s.textFontSize??14)}" onchange="updateStyle('${viewEl.id}','textFontSize',this.value)"></div></div>
            <div class="prop-group"><label class="prop-label">Copyright (px)</label><input type="number" min="10" max="18" class="prop-input" value="${esc(s.copyrightFontSize??12)}" onchange="updateStyle('${viewEl.id}','copyrightFontSize',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Texto do copyright</label><input class="prop-input" value="${esc(s.copyright || '')}" onchange="updateStyle('${viewEl.id}','copyright',this.value)"></div>
        </div>
        <div class="prop-section"><div class="prop-title"><i class="fas fa-border-all"></i> Bordas e sombra</div>
            <div class="prop-group"><label class="prop-label">Borda (px)</label><input type="number" min="0" max="8" class="prop-input" value="${esc(s.borderWidth??1)}" onchange="updateStyle('${viewEl.id}','borderWidth',this.value)"></div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Ativar sombra</span><input type="checkbox" ${s.shadow?'checked':''} onchange="updateStyle('${viewEl.id}','shadow',this.checked);updateProperties('${viewEl.id}')"></label></div>
            ${s.shadow ? `<div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-top:7px"><div class="prop-group"><label class="prop-label">X</label><input type="number" class="prop-input" value="${esc(s.shadowX??0)}" onchange="updateStyle('${viewEl.id}','shadowX',this.value)"></div><div class="prop-group"><label class="prop-label">Y</label><input type="number" class="prop-input" value="${esc(s.shadowY??-4)}" onchange="updateStyle('${viewEl.id}','shadowY',this.value)"></div><div class="prop-group"><label class="prop-label">Desfoque</label><input type="number" min="0" class="prop-input" value="${esc(s.shadowBlur??18)}" onchange="updateStyle('${viewEl.id}','shadowBlur',this.value)"></div><div class="prop-group"><label class="prop-label">Espalhar</label><input type="number" class="prop-input" value="${esc(s.shadowSpread??0)}" onchange="updateStyle('${viewEl.id}','shadowSpread',this.value)"></div></div><div class="prop-group" style="margin-top:6px"><label class="prop-label">Cor da sombra</label>${colorInputFooter('shadowColor','rgba(0,0,0,0.18)')}</div>` : ''}
        </div>`;
    }

    if (viewEl.type === 'logo') {
        const s = viewEl.styles || {};
        html += `<div class="prop-section"><div class="prop-title"><i class="fas fa-copyright"></i> Logo</div>
            <div class="prop-group"><label class="prop-label">Tipo</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','logoType',this.value);updateProperties('${viewEl.id}')"><option value="image" ${s.logoType!=='text'?'selected':''}>Imagem</option><option value="text" ${s.logoType==='text'?'selected':''}>Texto</option></select></div>`;
        if (s.logoType === 'text') {
            html += `<div class="prop-group"><label class="prop-label">Texto do logo</label><input class="prop-input" value="${esc(s.text || '')}" onchange="updateStyle('${viewEl.id}','text',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Cor do texto</label>${colorInputFooter('textColor','#111827')}</div>
            <div class="prop-group"><label class="prop-label">Tamanho (px)</label><input type="number" min="10" max="120" class="prop-input" value="${esc(s.fontSize??32)}" onchange="updateStyle('${viewEl.id}','fontSize',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Peso</label><select class="prop-select" onchange="updateStyle('${viewEl.id}','fontWeight',this.value)"><option value="500" ${String(s.fontWeight)==='500'?'selected':''}>500</option><option value="600" ${String(s.fontWeight)==='600'?'selected':''}>600</option><option value="700" ${String(s.fontWeight)==='700'?'selected':''}>700</option><option value="800" ${String(s.fontWeight??800)==='800'?'selected':''}>800</option><option value="900" ${String(s.fontWeight)==='900'?'selected':''}>900</option></select></div>`;
        } else {
            html += `<div class="prop-group"><label class="prop-label">URL da imagem</label><input class="prop-input" value="${esc(s.imageUrl || '')}" placeholder="https://..." onchange="updateStyle('${viewEl.id}','imageUrl',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Texto alternativo</label><input class="prop-input" value="${esc(s.alt || 'Logo')}" onchange="updateStyle('${viewEl.id}','alt',this.value)"></div>`;
        }
        html += `<div class="prop-group"><label class="prop-label">Link do logo</label><input class="prop-input" value="${esc(s.link || '')}" placeholder="https://..." onchange="updateStyle('${viewEl.id}','link',this.value)"></div>
            <div class="prop-group"><label style="display:flex;align-items:center;gap:8px"><input type="checkbox" ${s.targetBlank?'checked':''} onchange="updateStyle('${viewEl.id}','targetBlank',this.checked)"><span> Abrir link em nova aba</span></label></div>
            <div class="prop-group"><label class="prop-label">Alinhamento</label><div style="display:grid;grid-template-columns:repeat(3,1fr);gap:5px;">${[['left','Esq.'],['center','Centro'],['right','Dir.']].map(([a,l])=>`<button type="button" onclick="updateStyle('${viewEl.id}','alignment','${a}')" style="height:34px;border:1px solid ${(s.alignment||'left')===a?'var(--accent)':'var(--border)'};background:${(s.alignment||'left')===a?'var(--accent-glow)':'var(--bg-tertiary)'};color:var(--text-secondary);border-radius:6px;cursor:pointer">${l}</button>`).join('')}</div></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><div class="prop-group"><label class="prop-label">Altura (px)</label><input type="number" min="18" max="180" class="prop-input" value="${esc(s.height??54)}" onchange="updateStyle('${viewEl.id}','height',this.value)"></div><div class="prop-group"><label class="prop-label">Largura máx. (px)</label><input type="number" min="60" max="800" class="prop-input" value="${esc(s.maxWidth??260)}" onchange="updateStyle('${viewEl.id}','maxWidth',this.value)"></div></div>
            <div class="prop-group"><label class="prop-label">Raio (px)</label><input type="number" min="0" max="80" class="prop-input" value="${esc(s.radius??0)}" onchange="updateStyle('${viewEl.id}','radius',this.value)"></div>
            <div class="prop-group"><label class="prop-label">Opacidade</label><input type="range" min="0.1" max="1" step="0.05" value="${esc(s.opacity??1)}" oninput="updateStyle('${viewEl.id}','opacity',this.value)"></div>
            <div class="prop-group"><label style="display:flex;align-items:center;justify-content:space-between"><span class="prop-label" style="margin:0">Ativar sombra</span><input type="checkbox" ${s.shadow?'checked':''} onchange="updateStyle('${viewEl.id}','shadow',this.checked);updateProperties('${viewEl.id}')"></label></div>`;
        if (s.shadow) html += `<div class="prop-group"><label class="prop-label">Cor da sombra</label>${colorInputFooter('shadowColor','rgba(0,0,0,0.18)')}</div><div style="display:grid;grid-template-columns:1fr 1fr;gap:6px"><input type="number" class="prop-input" value="${esc(s.shadowY??4)}" onchange="updateStyle('${viewEl.id}','shadowY',this.value)"><input type="number" class="prop-input" value="${esc(s.shadowBlur??12)}" onchange="updateStyle('${viewEl.id}','shadowBlur',this.value)"></div>`;
        html += `</div>`;
    }

    if (['section','column','container'].includes(viewEl.type)) {
        html += `<div class="prop-section"><div class="prop-title"><i class="fas fa-paint-brush"></i> Estilo</div><div class="prop-group"><label class="prop-label">Cor de Fundo</label><div class="color-row"><input type="color" value="${esc(viewEl.styles.bgColor || '#ffffff')}" onchange="updateStyle('${viewEl.id}','bgColor',this.value)"><input type="text" value="${esc(viewEl.styles.bgColor || '#ffffff')}" onchange="updateStyle('${viewEl.id}','bgColor',this.value)"></div></div><div class="prop-group"><label class="prop-label">Padding</label><input type="text" class="prop-input" value="${esc(viewEl.styles.padding || '20px')}" onchange="updateStyle('${viewEl.id}','padding',this.value)"></div></div>`;
    }
    container.innerHTML = html;
}

function addFooterColumn(id) {
    const el = findElementById(pageContent, id); if (!el) return;
    el.styles = el.styles || {}; el.styles.columns = Array.isArray(el.styles.columns) ? el.styles.columns : [];
    el.styles.columns.push({title:`Coluna ${el.styles.columns.length+1}`, items:[{label:'Novo link',link:'#'}]});
    renderCanvas(); saveData(); updateProperties(id);
}
function removeFooterColumn(id,index) {
    const el=findElementById(pageContent,id); if(!el || !Array.isArray(el.styles?.columns)) return;
    el.styles.columns.splice(index,1); renderCanvas(); saveData(); updateProperties(id);
}
function updateFooterColumn(id,index,prop,value) {
    const el=findElementById(pageContent,id); if(!el || !Array.isArray(el.styles?.columns?.[index] ? el.styles.columns : null)) return;
    el.styles.columns[index][prop]=value; renderCanvas(); saveData();
}
function addFooterColumnItem(id,columnIndex) {
    const el=findElementById(pageContent,id); if(!el || !Array.isArray(el.styles?.columns)) return;
    const col=el.styles.columns[columnIndex]; if(!col) return;
    col.items=Array.isArray(col.items)?col.items:[]; col.items.push({label:'Novo link',link:'#'});
    renderCanvas(); saveData(); updateProperties(id);
}
function removeFooterColumnItem(id,columnIndex,itemIndex) {
    const el=findElementById(pageContent,id); if(!el || !Array.isArray(el.styles?.columns)) return;
    const col=el.styles.columns[columnIndex]; if(!col || !Array.isArray(col.items)) return;
    col.items.splice(itemIndex,1); renderCanvas(); saveData(); updateProperties(id);
}
function updateFooterColumnItem(id,columnIndex,itemIndex,prop,value) {
    const el=findElementById(pageContent,id); if(!el || !Array.isArray(el.styles?.columns)) return;
    const col=el.styles.columns[columnIndex]; if(!col || !Array.isArray(col.items) || !col.items[itemIndex]) return;
    col.items[itemIndex][prop]=value; renderCanvas(); saveData();
}

function handleImageUpload(input, id) {
    const file = input.files?.[0];
    input.value = '';
    if (!file) return;
    if (!file.type.startsWith('image/')) { showNotification('Selecione um arquivo de imagem.', 'error'); return; }
    const reader = new FileReader();
    reader.onload = e => {
        const el = findElementById(pageContent, id);
        if (!el) return;
        saveState();
        el.content = e.target.result;
        el.styles = el.styles || {};
        if (!el.styles.alt || el.styles.alt === 'Imagem') el.styles.alt = file.name.replace(/\.[^.]+$/, '');
        renderCanvas();
        updateProperties(id);
        saveData();
        showNotification('Imagem carregada! Para imagens muito grandes, prefira usar uma URL.');
    };
    reader.onerror = () => showNotification('Erro ao ler a imagem.', 'error');
    reader.readAsDataURL(file);
}

function applyVideoUrl(id) {
    const input = document.getElementById('video-url-' + id);
    if (!input) return;
    const value = input.value.trim();
    const el = findElementById(pageContent, id);
    if (!el) return;
    saveState();
    el.styles = el.styles || {};
    el.styles.videoUrl = value;
    el.styles.videoFileName = '';
    el.styles.videoSource = 'url';
    renderCanvas();
    updateProperties(id);
    saveData();
    showNotification(value ? 'URL do vídeo aplicada!' : 'URL do vídeo removida.');
}

function handleVideoFileUpload(input, id) {
    const file = input.files?.[0];
    input.value = '';
    if (!file) return;
    if (!file.type.startsWith('video/')) { showNotification('Selecione um arquivo de vídeo.', 'error'); return; }
    const reader = new FileReader();
    reader.onload = e => {
        const el = findElementById(pageContent, id);
        if (!el) return;
        saveState();
        el.styles = el.styles || {};
        el.styles.videoUrl = e.target.result;
        el.styles.videoFileName = file.name;
        el.styles.videoSource = 'html5';
        renderCanvas();
        updateProperties(id);
        saveData();
        showNotification('Vídeo carregado! Para vídeos grandes, prefira usar uma URL.');
    };
    reader.onerror = () => showNotification('Erro ao ler o vídeo.', 'error');
    reader.readAsDataURL(file);
}

function getVideoType(url) {
    const u = String(url || '').trim();
    if (!u) return 'empty';
    if (/^(?:https?:)?\/\/(?:www\.)?(?:youtube\.com|youtu\.be)(?:\/|$)/i.test(u)) return 'youtube';
    if (/^(?:https?:)?\/\/(?:www\.)?vimeo\.com(?:\/|$)/i.test(u)) return 'vimeo';
    if (/youtube-nocookie\.com|player\.vimeo\.com/i.test(u)) return 'embed';
    if (/\.m3u8(?:\?|#|$)/i.test(u)) return 'embed';
    if (/\.(mp4|webm|ogg|ogv|m4v|mov|avi)(?:\?|#|$)/i.test(u) || /^(?:blob:|data:video\/)/i.test(u)) return 'html5';
    return 'html5';
}
function getYouTubeId(url) {
    const raw = String(url || '').trim();
    try {
        const normalized = /^youtu\.be\//i.test(raw) ? 'https://' + raw : raw;
        const u = new URL(normalized);
        const host = u.hostname.replace(/^www\./i, '').toLowerCase();
        if (host === 'youtu.be') return (u.pathname.split('/').filter(Boolean)[0] || '').match(/^[A-Za-z0-9_-]{11}$/) ? u.pathname.split('/').filter(Boolean)[0] : '';
        if (host === 'youtube.com' || host === 'm.youtube.com' || host === 'youtube-nocookie.com') {
            const v = u.searchParams.get('v');
            if (v && /^[A-Za-z0-9_-]{11}$/.test(v)) return v;
            const parts = u.pathname.split('/').filter(Boolean);
            const idx = parts.findIndex(x => ['embed','shorts','live','v'].includes(x.toLowerCase()));
            if (idx >= 0 && parts[idx + 1] && /^[A-Za-z0-9_-]{11}$/.test(parts[idx + 1])) return parts[idx + 1];
        }
    } catch (e) {}
    const m = raw.match(/(?:youtube(?:-nocookie)?\.com\/(?:watch\?v=|embed\/|shorts\/|live\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/i);
    return m ? m[1] : '';
}
function getVimeoId(url) {
    const raw = String(url || '').trim();
    try {
        const u = new URL(raw);
        const parts = u.pathname.split('/').filter(Boolean);
        for (let i = parts.length - 1; i >= 0; i--) {
            if (/^\d+$/.test(parts[i])) return parts[i];
        }
    } catch (e) {}
    const m = raw.match(/vimeo\.com\/(?:video\/)?([0-9]+)/i);
    return m ? m[1] : '';
}
function videoStyle(s) {
    const ratio = ({'16:9':'56.25%','4:3':'75%','1:1':'100%','21:9':'42.857%'}[s.aspectRatio || '16:9'] || '56.25%');
    const radius = Math.max(0, Math.min(80, parseInt(s.borderRadius || 8,10) || 0));
    const opacity = Math.max(0, Math.min(1, parseFloat(s.opacity ?? 1) || 0));
    const shadowOn = s.shadow === true || s.shadow === 1 || s.shadow === '1' || String(s.shadow).toLowerCase() === 'true' || String(s.shadow).toLowerCase() === 'on' || String(s.shadow).toLowerCase() === 'yes';
    const shadowColor = String(s.shadowColor || 'rgba(0,0,0,0.28)');
    const shadow = shadowOn
        ? `${parseFloat(s.shadowX ?? 0) || 0}px ${parseFloat(s.shadowY ?? 6) || 0}px ${Math.max(0, parseFloat(s.shadowBlur ?? 18) || 0)}px ${parseFloat(s.shadowSpread ?? 0) || 0}px ${shadowColor}`
        : 'none';
    const objectFit = ['cover','contain','fill','none'].includes(s.objectFit) ? s.objectFit : 'cover';
    return { ratio, radius, opacity, shadow, objectFit };
}
function videoFrameHTML(v, innerHTML) {
    return `<div style="position:relative;width:100%;padding-bottom:${v.ratio};height:0;overflow:visible;border-radius:${v.radius}px;opacity:${v.opacity};box-shadow:${escapeAttr(v.shadow)}">` +
           `<div style="position:absolute;inset:0;overflow:hidden;border-radius:${v.radius}px;background:#000;">${innerHTML}</div></div>`;
}
function buildHTML5Video(url, s) {
    const v = videoStyle(s);
    const attrs = `${s.controls !== false ? ' controls' : ''}${s.autoplay ? ' autoplay' : ''}${s.muted ? ' muted' : ''}${s.loop ? ' loop' : ''} playsinline${s.poster ? ` poster="${escapeAttr(s.poster)}"` : ''}`;
    const video = `<video src="${escapeAttr(url)}"${attrs} style="position:absolute;inset:0;width:100%;height:100%;object-fit:${escapeAttr(v.objectFit)};border-radius:${v.radius}px;background:#000"></video>`;
    return videoFrameHTML(v, video);
}
function adBoxWidth(s){
    const n=parseFloat(s.width);
    if(!Number.isFinite(n)||n<=0) return '100%';
    const unit=s.widthUnit==='px'?'px':'%';
    const safe=unit==='%'?Math.min(100,Math.max(1,n)):Math.min(2000,Math.max(1,n));
    return `${safe}${unit}`;
}
function adAlignStyle(s){
    const a=['left','center','right'].includes(s.alignment)?s.alignment:'center';
    return `margin-left:${a==='center'||a==='right'?'auto':'0'};margin-right:${a==='center'||a==='left'?'auto':'0'};`;
}
function ensureBannerSlides(s){
    s=s||{};
    if(!Array.isArray(s.bannerSlides) || !s.bannerSlides.length){
        s.bannerSlides=[{src:String(s.imageUrl||''),alt:String(s.alt||'Banner'),title:String(s.title||''),text:String(s.description||''),buttonText:String(s.buttonText||''),buttonLink:String(s.buttonLink||'')}];
    }
    s.bannerSlides=s.bannerSlides.map((x,i)=>({src:String(x?.src||''),alt:String(x?.alt||`Banner ${i+1}`),title:String(x?.title||''),text:String(x?.text||''),buttonText:String(x?.buttonText||''),buttonLink:String(x?.buttonLink||'')})).filter(x=>x.src);
    if(!s.bannerSlides.length) s.bannerSlides=[{src:'https://placehold.co/1200x400?text=Banner+Publicidade',alt:'Banner',title:'',text:'',buttonText:'',buttonLink:''}];
    return s.bannerSlides;
}
function bannerSlideInner(slide,s,height,radius){
    const bg=String(slide.src||'').trim();
    const bgImage=bg?`background-image:url("${escapeAttr(bg)}");`:'';
    const overlay=s.overlay!==false?`<div style="position:absolute;inset:0;background:${escapeAttr(s.overlayColor||'rgba(0,0,0,.25)')};"></div>`:'';
    const text=(slide.title||slide.text||slide.buttonText)?`<div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;text-align:center;padding:24px;z-index:2;color:#fff;"><div>${slide.title?`<div data-banner-title="1" style="font-size:${Math.max(8,Math.min(120,parseFloat(s.titleFontSize||28)||28))}px;font-weight:800;color:${escapeAttr(s.titleColor||'#fff')};margin-bottom:7px;">${escapeHtml(slide.title)}</div>`:''}${slide.text?`<div data-banner-description="1" style="font-size:${Math.max(8,Math.min(80,parseFloat(s.descriptionFontSize||15)||15))}px;color:${escapeAttr(s.descriptionColor||'#fff')};margin-bottom:12px;">${escapeHtml(slide.text)}</div>`:''}${slide.buttonText?`<a href="${escapeAttr(slide.buttonLink||'#')}" ${s.targetBlank?'target="_blank" rel="noopener noreferrer"':''} onclick="event.stopPropagation();" style="display:inline-flex;padding:10px 18px;border-radius:8px;background:${escapeAttr(s.buttonBg||'#3b82f6')};color:${escapeAttr(s.buttonColor||'#fff')};text-decoration:none;font-weight:700;">${escapeHtml(slide.buttonText)}</a>`:''}</div></div>`:'';
    return `<div class="sbc-banner-slide" style="position:absolute;inset:0;width:100%;height:100%;background-color:#cbd5e1;background-size:${s.objectFit==='contain'?'contain':'cover'};background-position:center;background-repeat:no-repeat;${bgImage}overflow:hidden;border-radius:${radius}px;opacity:0;transition:${s.transition==='slide'?'transform .55s ease,opacity .35s ease':'opacity .5s ease'};transform:translateX(0);">${overlay}${text}</div>`;
}
function buildBannerAdHTML(s){
    const width=adBoxWidth(s), height=Math.max(80,Math.min(1200,parseFloat(s.height||220)||220));
    const radius=Math.max(0,Math.min(80,parseFloat(s.borderRadius||12)||12));
    const opacity=Math.max(0,Math.min(1,parseFloat(s.opacity??1)||0));
    const slides=ensureBannerSlides(s);
    const transition=s.transition==='slide'?'slide':'fade';
    const linkedSlides=slides.map((slide,i)=>{
        const inner=bannerSlideInner(slide,s,height,radius);
        return `<div class="sbc-banner-slide-wrap" data-banner-index="${i}" style="position:absolute;inset:0;opacity:${i===0?'1':'0'};transform:translateX(${i===0?'0':'100%'});transition:${transition==='slide'?'transform .55s ease,opacity .35s ease':'opacity .5s ease'};">${s.link && i===0?`<a href="${escapeAttr(s.link)}" ${s.targetBlank?'target="_blank" rel="noopener noreferrer"':''} style="display:block;width:100%;height:100%;text-decoration:none;">${inner}</a>`:inner}</div>`;
    }).join('');
    return `<div class="sbc-ad-widget sbc-banner-ad" data-banner-autoplay="${s.autoplay?'1':'0'}" data-banner-interval="${Math.max(1000,parseFloat(s.autoplayInterval||5000)||5000)}" data-banner-pause="${s.pauseOnHover!==false?'1':'0'}" data-banner-transition="${transition}" style="width:${escapeAttr(width)};max-width:100%;${adAlignStyle(s)}opacity:${opacity};box-sizing:border-box;"><div class="sbc-banner-viewport" style="position:relative;width:100%;height:${height}px;overflow:hidden;border-radius:${radius}px;">${linkedSlides}</div></div>`;
}

function buildVideoAdHTML(s){
    const width=adBoxWidth(s), radius=Math.max(0,Math.min(80,parseFloat(s.borderRadius||12)||12));
    const v=videoStyle(s); const video=buildVideoHTML({...s, poster:s.poster||''});
    const overlay=s.overlay!==false && (s.title||s.description||s.buttonText)?`<div style="position:absolute;inset:0;z-index:5;display:flex;align-items:flex-end;justify-content:center;padding:24px;text-align:center;pointer-events:none;background:${escapeAttr(s.overlayColor||'rgba(0,0,0,.28)')};"><div style="pointer-events:auto;">${s.title?`<div style="font-size:26px;font-weight:800;color:${escapeAttr(s.titleColor||'#fff')};margin-bottom:6px;">${escapeHtml(s.title)}</div>`:''}${s.description?`<div style="font-size:14px;color:${escapeAttr(s.descriptionColor||'#fff')};margin-bottom:10px;">${escapeHtml(s.description)}</div>`:''}${s.buttonText?`<a href="${escapeAttr(s.buttonLink||s.link||'#')}" ${s.targetBlank?'target="_blank" rel="noopener noreferrer"':''} style="display:inline-flex;padding:9px 16px;border-radius:8px;background:${escapeAttr(s.buttonBg||'#3b82f6')};color:${escapeAttr(s.buttonColor||'#fff')};text-decoration:none;font-weight:700;">${escapeHtml(s.buttonText)}</a>`:''}</div></div>`:'';
    const box=`<div style="position:relative;border-radius:${radius}px;overflow:hidden;">${video}${overlay}</div>`;
    return `<div class="sbc-ad-widget sbc-video-ad" style="width:${escapeAttr(width)};max-width:100%;${adAlignStyle(s)};box-sizing:border-box;">${box}</div>`;
}
function buildAdEmbedHTML(s){
    const width=adBoxWidth(s), height=Math.max(80,Math.min(1600,parseFloat(s.height||250)||250));
    const radius=Math.max(0,Math.min(80,parseFloat(s.borderRadius||8)||8));
    const borderWidth=Math.max(0,Math.min(10,parseFloat(s.borderWidth||1)||0));
    const code=String(s.code||'').trim(), iframeUrl=String(s.iframeUrl||'').trim();
    let inner='';
    if(iframeUrl){ inner=`<iframe src="${escapeAttr(iframeUrl)}" style="width:100%;height:${height}px;border:0;display:block;" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>`; }
    else if(code){ inner=`<iframe title="Publicidade / conteúdo incorporado" sandbox="allow-scripts allow-forms allow-popups allow-popups-to-escape-sandbox" style="width:100%;height:${height}px;border:0;display:block;background:${escapeAttr(s.background||'#fff')};" srcdoc="${escapeAttr(code)}"></iframe>`; }
    else inner=`<div style="height:${height}px;display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:13px;background:${escapeAttr(s.background||'#fff')};">Cole um código de Ads/HTML ou uma URL de iframe</div>`;
    return `<div class="sbc-ad-widget sbc-embed-ad" style="width:${escapeAttr(width)};max-width:100%;${adAlignStyle(s)};background:${escapeAttr(s.background||'#fff')};border:${borderWidth}px solid ${escapeAttr(s.borderColor||'#e5e7eb')};border-radius:${radius}px;overflow:hidden;opacity:${Math.max(0,Math.min(1,parseFloat(s.opacity??1)||0))};box-sizing:border-box;">${inner}</div>`;
}

function buildVideoHTML(s) {
    const url = String(s.videoUrl || '').trim();
    if (!url) return '<div style="padding:40px;text-align:center;background:#000;color:#fff;border-radius:8px;">Cole a URL de um vídeo</div>';
    const type = getVideoType(url), v = videoStyle(s);
    if (type === 'youtube') {
        const id = getYouTubeId(url);
        if (!id) return '<div style="padding:20px;color:#f00;">URL do YouTube inválida.</div>';
        const q = new URLSearchParams({rel:'0'});
        if (s.autoplay) q.set('autoplay','1');
        if (s.muted) q.set('mute','1');
        if (s.loop) { q.set('loop','1'); q.set('playlist',id); }
        if (s.controls === false) q.set('controls','0');
        const iframe = `<iframe src="https://www.youtube.com/embed/${id}?${q.toString()}" style="position:absolute;inset:0;width:100%;height:100%;display:block;border:0;border-radius:${v.radius}px" loading="lazy" referrerpolicy="strict-origin-when-cross-origin" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>`;
        return videoFrameHTML(v, iframe);
    }
    if (type === 'vimeo') {
        const id = getVimeoId(url);
        if (!id) return '<div style="padding:20px;color:#f00;">URL do Vimeo inválida.</div>';
        const q = new URLSearchParams();
        if (s.autoplay) q.set('autoplay','1');
        if (s.muted) q.set('muted','1');
        if (s.loop) q.set('loop','1');
        if (s.controls === false) q.set('controls','0');
        const iframe = `<iframe src="https://player.vimeo.com/video/${id}${q.toString() ? '?' + q.toString() : ''}" style="position:absolute;inset:0;width:100%;height:100%;display:block;border:0;border-radius:${v.radius}px" loading="lazy" referrerpolicy="strict-origin-when-cross-origin" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>`;
        return videoFrameHTML(v, iframe);
    }
    if (type === 'embed') {
        const iframe = `<iframe src="${escapeAttr(url)}" style="position:absolute;inset:0;width:100%;height:100%;border:0;border-radius:${v.radius}px" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>`;
        return videoFrameHTML(v, iframe);
    }
    return buildHTML5Video(url, s);
}

function buildNewsletterHTML(el) {
    const s = el.styles || {};
    const escN = v => escapeAttr(v == null ? '' : String(v));
    const escH = v => escapeHtml(v == null ? '' : String(v));
    const align = ['left','center','right'].includes(s.alignment) ? s.alignment : 'center';
    const layout = s.layout === 'stacked' ? 'stacked' : 'inline';
    const widthRaw = parseFloat(s.width);
    const width = Number.isFinite(widthRaw) && widthRaw > 0 ? `${s.widthUnit === 'px' ? Math.min(2000,widthRaw) : Math.min(100,Math.max(1,widthRaw))}${s.widthUnit === 'px' ? 'px' : '%'}` : '100%';
    const inputHeight = Math.min(100, Math.max(30, parseFloat(s.inputHeight ?? 44) || 44));
    const buttonHeight = Math.min(100, Math.max(30, parseFloat(s.buttonHeight ?? 44) || 44));
    const gap = Math.min(60, Math.max(0, parseFloat(s.gap ?? 10) || 0));
    const cardPadding = Math.min(100, Math.max(0, parseFloat(s.cardPadding ?? 26) || 0));
    const cardRadius = Math.min(80, Math.max(0, parseFloat(s.cardRadius ?? 16) || 0));
    const sectionRadius = Math.min(80, Math.max(0, parseFloat(s.sectionRadius ?? 0) || 0));
    const titleSize = Math.max(12, Math.min(72, parseFloat(s.titleFontSize ?? 28) || 28));
    const descSize = Math.max(10, Math.min(32, parseFloat(s.descriptionFontSize ?? 14) || 14));
    const inputSize = Math.max(10, Math.min(30, parseFloat(s.inputFontSize ?? 14) || 14));
    const buttonSize = Math.max(10, Math.min(30, parseFloat(s.buttonFontSize ?? 14) || 14));
    const shadow = s.shadow ? `${parseFloat(s.shadowX||0)||0}px ${parseFloat(s.shadowY||8)||0}px ${parseFloat(s.shadowBlur||24)||0}px ${parseFloat(s.shadowSpread||0)||0}px ${s.shadowColor||'rgba(0,0,0,.10)'}` : 'none';
    const borderWidth = Math.min(10, Math.max(0, parseFloat(s.inputBorderWidth ?? 1) || 0));
    const inputBase = `height:${inputHeight}px;box-sizing:border-box;width:100%;background:${escN(s.inputBg||'#ffffff')};color:${escN(s.inputColor||'#111827')};border:${borderWidth}px solid ${escN(s.inputBorderColor||'#d1d5db')};border-radius:${Math.max(0,parseFloat(s.inputRadius??8)||0)}px;padding:0 13px;font:500 ${inputSize}px/1.2 Inter,Arial,sans-serif;outline:none;`;
    const buttonBase = `height:${buttonHeight}px;box-sizing:border-box;background:${escN(s.buttonBg||'#3b82f6')};color:${escN(s.buttonColor||'#ffffff')};border:0;border-radius:${Math.max(0,parseFloat(s.buttonRadius??8)||0)}px;padding:0 18px;font:700 ${buttonSize}px/1.2 Inter,Arial,sans-serif;cursor:pointer;white-space:nowrap;`;
    const fields = [];
    if (s.showName) fields.push(`<input type="text" aria-label="Nome" placeholder="${escN(s.namePlaceholder||'Seu nome')}" style="${inputBase}">`);
    fields.push(`<input type="email" aria-label="E-mail" placeholder="${escN(s.emailPlaceholder||'Seu melhor e-mail')}" style="${inputBase}">`);
    const fieldWrap = layout === 'stacked'
        ? `<div style="display:flex;flex-direction:column;gap:${gap}px;min-width:0;flex:1;">${fields.map((f)=>`<div style="width:100%;">${f}</div>`).join('')}</div>`
        : `<div style="display:grid;grid-template-columns:${s.showName ? '1fr 1fr' : 'minmax(0,1fr)'};gap:${gap}px;min-width:0;flex:${s.showName ? '1' : '1'};">${fields.map((f)=>`<div style="min-width:0;">${f}</div>`).join('')}</div>`;
    const button = `<button type="button" onclick="newsletterSubmit('${el.id}', event)" style="${buttonBase}" onmouseenter="this.style.background='${escN(s.buttonBgHover||'#2563eb')}'" onmouseleave="this.style.background='${escN(s.buttonBg||'#3b82f6')}'">${escH(s.buttonText||'Assinar newsletter')}</button>`;
    const form = layout === 'stacked'
        ? `<div style="display:flex;flex-direction:column;gap:${gap}px;width:100%;">${fieldWrap}${button}</div>`
        : `<div style="display:flex;align-items:stretch;gap:${gap}px;width:100%;">${fieldWrap}<div style="flex:0 0 auto;">${button}</div></div>`;
    const title = s.title ? `<div style="font-size:${titleSize}px;line-height:1.18;font-weight:800;color:${escN(s.titleColor||'#111827')};margin-bottom:8px;">${escH(s.title)}</div>` : '';
    const desc = s.description ? `<div style="font-size:${descSize}px;line-height:1.55;color:${escN(s.descriptionColor||'#6b7280')};margin-bottom:18px;">${escH(s.description)}</div>` : '';
    const consent = s.consent ? `<div style="font-size:${Math.max(9,parseFloat(s.consentFontSize??12)||12)}px;line-height:1.45;color:${escN(s.consentColor||'#6b7280')};margin-top:12px;">${escH(s.consent)}</div>` : '';
    const cardStyle = `width:100%;box-sizing:border-box;background:${escN(s.cardBg||'#ffffff')};border-radius:${cardRadius}px;padding:${cardPadding}px;box-shadow:${shadow};`;
    return `<section class="sbc-newsletter-shell" data-newsletter-id="${escN(el.id)}" style="width:${width};max-width:100%;margin-left:${align==='center'||align==='right'?'auto':'0'};margin-right:${align==='center'||align==='left'?'auto':'0'};background:${escN(s.sectionBg||'#f8fafc')};border-radius:${sectionRadius}px;padding:${Math.max(0,parseFloat(s.sectionPaddingY??0)||0)}px 0;box-sizing:border-box;text-align:${align};"><div style="${cardStyle}"><div style="width:100%;max-width:980px;margin:0 auto;">${title}${desc}${form}${consent}</div></div></section>`;
}
function newsletterSubmit(id, event) {
    if (event) { event.preventDefault(); event.stopPropagation(); }
    const el = findElementById(pageContent, id);
    if (!el) return;
    const root = document.querySelector(`.sbc-newsletter-shell[data-newsletter-id="${CSS.escape(String(id))}"]`);
    const email = root?.querySelector('input[type="email"]');
    if (!email || !String(email.value || '').trim() || !email.checkValidity()) {
        showNotification('Digite um e-mail válido para assinar.', 'error');
        email?.focus();
        return;
    }
    const s = el.styles || {};
    showNotification(s.successMessage || 'Inscrição realizada com sucesso!', 'success');
}

function updateDividerIconControl(id, prop, value, outputId, suffix = '') {
    const el = findElementById(pageContent, id);
    if (!el) return;
    el.styles = el.styles || {};
    el.styles[prop] = value;

    const out = document.getElementById(outputId);
    if (out) out.textContent = `${value}${suffix}`;

    renderCanvas();
    saveData();

    selectedElementId = id;
    const selected = document.querySelector(`[data-id="${CSS.escape(id)}"]`);
    if (selected) selected.classList.add('selected');
}


let testimonialDragState = null;
function startTestimonialDrag(event, id, index) {
    testimonialDragState = { id, index };
    event.dataTransfer.effectAllowed = 'move';
    event.dataTransfer.setData('text/plain', String(index));
}
function allowTestimonialDrop(event) { event.preventDefault(); event.dataTransfer.dropEffect='move'; }
function dropTestimonial(event, id, targetIndex) {
    event.preventDefault();
    if (!testimonialDragState || testimonialDragState.id !== id) return;
    let from = testimonialDragState.index;
    let to = targetIndex;
    testimonialDragState = null;
    if (from < to) to--;
    reorderTestimonialItem(id, from, to);
}

function ensureGalleryImages(s) {
    if (!s || typeof s !== 'object') return [];
    if (!Array.isArray(s.images)) s.images = [];
    s.images = s.images.map((img, i) => ({
        src: String(img?.src || ''), alt: String(img?.alt || `Imagem ${i+1}`), title: String(img?.title || ''), link: String(img?.link || '')
    })).filter(img => img.src);
    return s.images;
}

function buildGalleryHTML(el) {
    const s = el.styles || {};
    const imgs = ensureGalleryImages(s);
    const align = ['left','center','right'].includes(s.alignment) ? s.alignment : 'center';
    const wn = parseFloat(s.width);
    const width = Number.isFinite(wn) && wn > 0 ? `${s.widthUnit === 'px' ? Math.min(2000,wn) : Math.min(100,Math.max(1,wn))}${s.widthUnit === 'px' ? 'px' : '%'}` : '100%';
    const cols = Math.min(4, Math.max(1, parseInt(s.columns || 3,10) || 3));
    const gap = Math.min(100, Math.max(0, parseFloat(s.gap || 16) || 16));
    const rowGap = Math.min(100, Math.max(0, parseFloat(s.rowGap ?? gap) || gap));
    const height = Math.min(1000, Math.max(80, parseFloat(s.imageHeight || 220) || 220));
    const radius = Math.min(80, Math.max(0, parseFloat(s.borderRadius || 12) || 0));
    const borderWidth = Math.min(20, Math.max(0, parseFloat(s.borderWidth || 0) || 0));
    const borderStyle = ['none','solid','dashed','dotted'].includes(s.borderStyle) ? s.borderStyle : 'none';
    const shadow = s.shadow ? `${parseFloat(s.shadowX||0)}px ${parseFloat(s.shadowY||6)}px ${parseFloat(s.shadowBlur||18)}px ${parseFloat(s.shadowSpread||0)}px ${s.shadowColor||'rgba(0,0,0,.14)'}` : 'none';
    const hover = s.hover || 'zoom';
    const scale = Math.min(1.2, Math.max(1, parseFloat(s.hoverScale || 1.03) || 1.03));
    const gridStyle = `display:grid;grid-template-columns:repeat(${cols},minmax(0,1fr));gap:${rowGap}px ${gap}px;width:100%;max-width:100%;box-sizing:border-box;`;
    const cards = imgs.map((img,index)=>{
        const hoverTransform = hover === 'zoom' ? `scale(${scale})` : 'scale(1)';
        const filter = hover === 'brightness' ? 'brightness(1.12)' : hover === 'dark' ? 'brightness(.82)' : 'none';
        const click = s.lightbox !== false ? `onclick="openGalleryLightbox('${el.id}',${index})"` : '';
        const imgTag = `<img src="${escapeAttr(img.src)}" alt="${escapeAttr(img.alt||'')}" style="width:100%;height:${height}px;object-fit:${s.objectFit||'cover'};display:block;border-radius:${radius}px;transition:transform .28s ease,filter .28s ease;transform-origin:center;" data-gallery-hover="${escapeAttr(hoverTransform)}" data-gallery-filter="${escapeAttr(filter)}">`;
        const caption = s.caption && img.title ? `<div style="padding:8px 4px 0;color:${escapeAttr(s.descriptionColor||'#6b7280')};font-size:12px;line-height:1.35;text-align:center;">${escapeHtml(img.title)}</div>` : '';
        const content = img.link ? `<a href="${escapeAttr(img.link)}"${s.linkOpen?' target="_blank" rel="noopener noreferrer"':''} style="display:block;text-decoration:none;color:inherit;">${imgTag}${caption}</a>` : `<div ${click} style="cursor:${s.lightbox!==false?'zoom-in':'default'};">${imgTag}${caption}</div>`;
        return `<figure class="sbc-gallery-item" style="margin:0;min-width:0;padding:0;background:transparent;overflow:visible;">${content}</figure>`;
    }).join('');
    const title = s.title ? `<div style="font-size:${Math.max(14,parseFloat(s.titleFontSize||28)||28)}px;font-weight:800;line-height:1.2;color:${escapeAttr(s.titleColor||'#111827')};margin-bottom:8px;">${escapeHtml(s.title)}</div>` : '';
    const desc = s.description ? `<div style="font-size:${Math.max(10,parseFloat(s.descriptionFontSize||14)||14)}px;line-height:1.5;color:${escapeAttr(s.descriptionColor||'#6b7280')};margin-bottom:22px;">${escapeHtml(s.description)}</div>` : '';
    return `<section class="sbc-gallery-shell" style="width:${width};max-width:100%;margin-left:${align==='center'||align==='right'?'auto':'0'};margin-right:${align==='center'||align==='left'?'auto':'0'};box-sizing:border-box;text-align:${align};"><header style="display:block;width:100%;text-align:${align};">${title}${desc}</header><div class="sbc-gallery-grid" style="${gridStyle}" data-gallery-id="${escapeAttr(el.id)}">${cards}</div></section>`;
}

let galleryDragState = null;
function startGalleryDrag(event,id,index){ galleryDragState={id,index}; event.dataTransfer.effectAllowed='move'; event.dataTransfer.setData('text/plain',String(index)); }
function allowGalleryDrop(event){ event.preventDefault(); event.dataTransfer.dropEffect='move'; }
function dropGalleryImage(event,id,targetIndex){ event.preventDefault(); if(!galleryDragState||galleryDragState.id!==id)return; let from=galleryDragState.index; let to=targetIndex; galleryDragState=null; if(from<to)to--; reorderGalleryImage(id,from,to); }
function reorderGalleryImage(id,from,to){ const el=findElementById(pageContent,id); if(!el||el.type!=='gallery')return; const imgs=ensureGalleryImages(el.styles||{}); if(from<0||to<0||from>=imgs.length||to>=imgs.length)return; saveState(); const [item]=imgs.splice(from,1); imgs.splice(to,0,item); el.styles.images=imgs; renderCanvas(); saveData(); selectedElementId=id; updateProperties(id); }
function addGalleryImage(id){ const el=findElementById(pageContent,id); if(!el)return; const imgs=ensureGalleryImages(el.styles||{}); imgs.push({src:'https://placehold.co/800x600?text=Nova+imagem',alt:`Imagem ${imgs.length+1}`,title:'',link:''}); el.styles.images=imgs; saveState(); renderCanvas(); saveData(); selectedElementId=id; updateProperties(id); }
function addGalleryImageFromUrl(id){ const input=document.getElementById('gallery-url-'+id); const url=String(input?.value||'').trim(); if(!url){showNotification('Informe a URL da imagem.','error');return;} const el=findElementById(pageContent,id); if(!el)return; saveState(); const imgs=ensureGalleryImages(el.styles||{}); imgs.push({src:url,alt:`Imagem ${imgs.length+1}`,title:'',link:''}); el.styles.images=imgs; if(input)input.value=''; renderCanvas(); saveData(); selectedElementId=id; updateProperties(id); }
function updateGalleryImage(id,index,prop,value){ const el=findElementById(pageContent,id); if(!el||el.type!=='gallery')return; const imgs=ensureGalleryImages(el.styles||{}); if(!imgs[index])return; saveState(); imgs[index][prop]=value; el.styles.images=imgs; renderCanvas(); saveData(); selectedElementId=id; }
function removeGalleryImage(id,index){ const el=findElementById(pageContent,id); if(!el||el.type!=='gallery')return; const imgs=ensureGalleryImages(el.styles||{}); if(imgs.length<=1){showNotification('Mantenha pelo menos 1 imagem.','error');return;} saveState(); imgs.splice(index,1); el.styles.images=imgs; renderCanvas(); saveData(); selectedElementId=id; updateProperties(id); }
function handleGalleryUpload(input,id){ const files=Array.from(input.files||[]); input.value=''; if(!files.length)return; const valid=files.filter(f=>f.type.startsWith('image/')); if(!valid.length){showNotification('Selecione imagens válidas.','error');return;} const el=findElementById(pageContent,id); if(!el)return; const imgs=ensureGalleryImages(el.styles||{}); saveState(); let remaining=valid.length; valid.forEach(file=>{ const reader=new FileReader(); reader.onload=e=>{ imgs.push({src:e.target.result,alt:file.name.replace(/\.[^.]+$/,''),title:file.name.replace(/\.[^.]+$/,''),link:''}); remaining--; if(remaining===0){ el.styles.images=imgs; renderCanvas(); saveData(); selectedElementId=id; updateProperties(id); showNotification(`${valid.length} imagem(ns) adicionada(s)!`); } }; reader.onerror=()=>{ remaining--; if(remaining===0){ el.styles.images=imgs; renderCanvas(); saveData(); updateProperties(id); showNotification('Algumas imagens não puderam ser carregadas.','error'); } }; reader.readAsDataURL(file); }); }
function bindGalleryHover(root){ if(!root)return; root.querySelectorAll('.sbc-gallery-item img[data-gallery-hover]').forEach(img=>{ const box=img.closest('.sbc-gallery-item'); const enter=()=>{img.style.transform=img.dataset.galleryHover||'scale(1)';img.style.filter=img.dataset.galleryFilter||'none';}; const leave=()=>{img.style.transform='scale(1)';img.style.filter='none';}; box?.addEventListener('mouseenter',enter);box?.addEventListener('mouseleave',leave); }); }
function openGalleryLightbox(id,index){ const el=findElementById(pageContent,id); if(!el||el.type!=='gallery')return; const imgs=ensureGalleryImages(el.styles||{}); const item=imgs[index]; if(!item)return; let overlay=document.getElementById('sbc-gallery-lightbox'); if(!overlay){ overlay=document.createElement('div'); overlay.id='sbc-gallery-lightbox'; overlay.style.cssText='position:fixed;inset:0;background:rgba(0,0,0,.82);display:flex;align-items:center;justify-content:center;z-index:99999;padding:30px;'; overlay.addEventListener('click',e=>{if(e.target===overlay)overlay.remove();}); document.body.appendChild(overlay); } overlay.innerHTML=`<button type="button" style="position:absolute;top:18px;right:22px;width:42px;height:42px;border:0;border-radius:50%;background:#fff;color:#111;font-size:24px;cursor:pointer;" onclick="document.getElementById('sbc-gallery-lightbox')?.remove()">×</button><img src="${escapeAttr(item.src)}" alt="${escapeAttr(item.alt||'')}" style="max-width:92vw;max-height:86vh;object-fit:contain;border-radius:10px;box-shadow:0 20px 60px rgba(0,0,0,.45);"><div style="position:absolute;bottom:18px;left:0;right:0;text-align:center;color:#fff;font-size:14px;">${escapeHtml(item.title||item.alt||'')}</div>`; }

function ensureCarouselSlides(s) {
    s=s||{};
    if(!Array.isArray(s.slides)||!s.slides.length){s.slides=[{src:'https://placehold.co/1200x600?text=Slide+1',alt:'Slide 1',title:'Slide 1',text:'Descrição do primeiro slide.',buttonText:'Saiba mais',buttonLink:'#'}];}
    s.slides=s.slides.map((x,i)=>({src:String(x?.src||''),alt:String(x?.alt||`Slide ${i+1}`),title:String(x?.title||`Slide ${i+1}`),text:String(x?.text||''),buttonText:String(x?.buttonText||''),buttonLink:String(x?.buttonLink||'')})).filter(x=>x.src);
    return s.slides;
}
function buildCarouselHTML(el){
    const base=el.styles||{};
    const mode=typeof getResponsiveDeviceMode==='function' ? getResponsiveDeviceMode() : 'desktop';
    const ro=base.responsive?.[mode]||{};
    const s={...base,...ro};
    // widthUnit e propriedades de layout do breakpoint atual têm prioridade.
    if (Object.prototype.hasOwnProperty.call(ro,'widthUnit')) s.widthUnit=ro.widthUnit;
    const slides=ensureCarouselSlides(s);
    const visible=Math.min(3,Math.max(1,parseInt(s.slidesVisible||1,10)||1));
    const height=Math.min(1000,Math.max(120,parseFloat(s.slideHeight||360)||360));
    const gap=Math.min(80,Math.max(0,parseFloat(s.gap||16)||16));
    const radius=Math.min(80,Math.max(0,parseFloat(s.borderRadius||14)||14));
    const fit=['cover','contain','fill'].includes(s.objectFit)?s.objectFit:'cover';
    const shadow=s.shadow?`${parseFloat(s.shadowX||0)||0}px ${parseFloat(s.shadowY||6)||0}px ${parseFloat(s.shadowBlur||18)||0}px ${parseFloat(s.shadowSpread||0)||0}px ${s.shadowColor||'rgba(0,0,0,.14)'}`:'none';
    // Quando houver somente 1 slide visível, não deixe o gap criar um espaço
    // entre a largura do viewport e o próximo slide. Para 2/3 visíveis,
    // mantemos o espaçamento configurado pelo usuário.
    const effectiveGap = visible === 1 ? 0 : gap;
    const trackWidth = visible === 1 ? '100%' : 'max-content';
    const slideWidth = visible === 1 ? '100%' : `calc((100% - ${(visible-1)*effectiveGap}px) / ${visible})`;
    const overlay=s.overlay!==false ? `<div style="position:absolute;inset:0;background:${escapeAttr(s.overlayColor||'rgba(0,0,0,.35)')};"></div>`:'';
    const cards=slides.map((sl,i)=>`<div class="sbc-carousel-slide" style="position:relative;flex:0 0 ${slideWidth};min-width:0;height:${height}px!important;overflow:hidden;box-sizing:border-box;"><img src="${escapeAttr(sl.src)}" alt="${escapeAttr(sl.alt)}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:${fit};display:block;">${overlay}<div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;padding:28px;text-align:center;z-index:2;box-sizing:border-box;"><div>${sl.title?`<div style="font-size:30px;font-weight:800;color:${escapeAttr(s.titleColor||'#fff')};margin-bottom:8px;">${escapeHtml(sl.title)}</div>`:''}${sl.text?`<div style="font-size:15px;line-height:1.5;color:${escapeAttr(s.textColor||'#fff')};margin-bottom:16px;">${escapeHtml(sl.text)}</div>`:''}${sl.buttonText?`<a href="${escapeAttr(sl.buttonLink||'#')}" onclick="event.preventDefault();event.stopPropagation();" style="display:inline-flex;align-items:center;justify-content:center;background:${escapeAttr(s.buttonBg||'#3b82f6')};color:${escapeAttr(s.buttonColor||'#fff')};padding:10px 16px;border-radius:8px;text-decoration:none;font-weight:700;">${escapeHtml(sl.buttonText)}</a>`:''}</div></div></div>`).join('');
    // Navegação configurável: nenhum, somente setas, somente dots ou ambos.
    // O padrão permanece sem controles, preservando a aparência atual desta versão.
    const navigation=['none','arrows','dots','both'].includes(s.navigation)?s.navigation:'none';
    const showArrows=navigation==='arrows'||navigation==='both';
    const showDots=navigation==='dots'||navigation==='both';
    const arrows=showArrows?`<button type="button" aria-label="Slide anterior" onclick="event.stopPropagation();carouselPrev('${el.id}')" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);z-index:4;width:40px;height:40px;border:0;border-radius:50%;background:rgba(0,0,0,.45);color:#fff;cursor:pointer;font-size:20px;">‹</button><button type="button" aria-label="Próximo slide" onclick="event.stopPropagation();carouselNext('${el.id}')" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);z-index:4;width:40px;height:40px;border:0;border-radius:50%;background:rgba(0,0,0,.45);color:#fff;cursor:pointer;font-size:20px;">›</button>`:'';
    const dots=showDots?`<div class="sbc-carousel-dots" style="position:absolute;left:0;right:0;bottom:14px;z-index:4;display:flex;justify-content:center;gap:7px;">${slides.map((_,i)=>`<button type="button" aria-label="Ir para slide ${i+1}" onclick="event.stopPropagation();goCarouselSlide('${el.id}',${i})" style="width:9px;height:9px;padding:0;border:0;border-radius:50%;background:rgba(255,255,255,.75);cursor:pointer;opacity:${i===0?'1':'.55'};"></button>`).join('')}</div>`:'';
    const head=`<div style="font-size:28px;font-weight:800;color:#111827;margin-bottom:8px;text-align:center;">${escapeHtml(s.title||'')}</div>${s.description?`<div style="font-size:14px;color:#6b7280;margin-bottom:18px;text-align:center;">${escapeHtml(s.description)}</div>`:''}`;
    return `<section class="sbc-carousel-shell" data-carousel-id="${escapeAttr(el.id)}" data-carousel-index="0" data-carousel-autoplay="${s.autoplay?'1':'0'}" data-carousel-interval="${Math.max(1000,parseFloat(s.autoplayInterval||4000)||4000)}" data-carousel-loop="${s.loop!==false?'1':'0'}" data-carousel-pause="${s.pauseOnHover!==false?'1':'0'}" style="width:100%;max-width:100%;min-width:0;box-sizing:border-box;">${head}<div class="sbc-carousel-frame" style="position:relative;width:100%;max-width:100%;min-width:0;overflow:hidden;border-radius:${radius}px;box-shadow:${shadow};box-sizing:border-box;"><div class="sbc-carousel-viewport" style="overflow:hidden;width:100%;max-width:100%;min-width:0;box-sizing:border-box;"><div class="sbc-carousel-track" style="display:flex;width:${trackWidth};gap:${effectiveGap}px;transform:translateX(0);transition:transform ${Math.max(100,parseFloat(s.transitionDuration||500)||500)}ms ease;">${cards}</div></div>${arrows}${dots}</div></section>`;
}
const carouselTimers=new Map();
function getCarouselRoot(id){return document.querySelector(`.sbc-carousel-shell[data-carousel-id="${String(id).replace(/"/g,'&quot;')}"]`);}
function goCarouselSlide(id,index){
    const root=getCarouselRoot(id),el=findElementById(pageContent,id);
    if(!root||!el)return;
    const slides=Array.from(root.querySelectorAll('.sbc-carousel-slide'));
    const track=root.querySelector('.sbc-carousel-track');
    const viewport=root.querySelector('.sbc-carousel-viewport');
    if(!slides.length||!track||!viewport)return;
    const vis=Math.min(3,Math.max(1,parseInt(el.styles?.slidesVisible||1,10)||1));
    const max=Math.max(0,slides.length-vis);
    let idx=Number(index)||0;
    if(el.styles?.loop!==false&&slides.length>vis){
        if(idx>max)idx=0;
        if(idx<0)idx=max;
    } else idx=Math.min(max,Math.max(0,idx));
    const first=slides[0];
    const gap=parseFloat(getComputedStyle(track).columnGap||getComputedStyle(track).gap||0)||0;
    const step=(first?.getBoundingClientRect().width||((viewport.clientWidth||1)/vis))+gap;
    track.style.transform=`translate3d(-${idx*step}px,0,0)`;
    root.dataset.carouselIndex=String(idx);
}
function carouselNext(id){const r=getCarouselRoot(id);if(r)goCarouselSlide(id,(parseInt(r.dataset.carouselIndex||0,10)||0)+1)}
function carouselPrev(id){const r=getCarouselRoot(id);if(r)goCarouselSlide(id,(parseInt(r.dataset.carouselIndex||0,10)||0)-1)}
function stopCarouselTimer(id){const t=carouselTimers.get(id);if(t){clearInterval(t);carouselTimers.delete(id)}}
function startCarouselTimer(id){stopCarouselTimer(id);const el=findElementById(pageContent,id),s=el?.styles||{};if(!s.autoplay)return;carouselTimers.set(id,setInterval(()=>carouselNext(id),Math.max(1000,parseFloat(s.autoplayInterval||4000)||4000)))}
function bindCarousel(root){const shell=root?.querySelector('.sbc-carousel-shell');if(!shell)return;const id=shell.dataset.carouselId;goCarouselSlide(id,0);startCarouselTimer(id);if(shell.dataset.carouselPause==='1'){shell.addEventListener('mouseenter',()=>stopCarouselTimer(id));shell.addEventListener('mouseleave',()=>startCarouselTimer(id));}}
let carouselDragState=null;
function startCarouselDrag(event,id,index){carouselDragState={id,index};event.dataTransfer.effectAllowed='move';event.dataTransfer.setData('text/plain',String(index));}
function allowCarouselDrop(event){event.preventDefault();event.stopPropagation();event.dataTransfer.dropEffect='move';}
function dropCarouselSlide(event,id,target){event.preventDefault();event.stopPropagation();if(!carouselDragState||carouselDragState.id!==id)return;let from=carouselDragState.index,to=target;carouselDragState=null;if(from<to)to--;reorderCarouselSlide(id,from,to)}
function reorderCarouselSlide(id,from,to){const el=findElementById(pageContent,id);if(!el||el.type!=='carousel')return;const a=ensureCarouselSlides(el.styles||{});if(from<0||to<0||from>=a.length||to>=a.length)return;saveState();const[item]=a.splice(from,1);a.splice(to,0,item);el.styles.slides=a;renderCanvas();saveData();selectedElementId=id;updateProperties(id)}
function addCarouselSlide(id){const el=findElementById(pageContent,id);if(!el)return;const a=ensureCarouselSlides(el.styles||{});a.push({src:`https://placehold.co/1200x600?text=Slide+${a.length+1}`,alt:`Slide ${a.length+1}`,title:`Slide ${a.length+1}`,text:'Descrição do slide.',buttonText:'Saiba mais',buttonLink:'#'});el.styles.slides=a;saveState();renderCanvas();saveData();selectedElementId=id;updateProperties(id)}
function addCarouselSlideFromUrl(id){const input=document.getElementById('carousel-url-'+id),url=String(input?.value||'').trim();if(!url){showNotification('Informe a URL da imagem.','error');return;}const el=findElementById(pageContent,id);if(!el)return;saveState();const a=ensureCarouselSlides(el.styles||{});a.push({src:url,alt:`Slide ${a.length+1}`,title:`Slide ${a.length+1}`,text:'Descrição do slide.',buttonText:'Saiba mais',buttonLink:'#'});el.styles.slides=a;if(input)input.value='';renderCanvas();saveData();selectedElementId=id;updateProperties(id)}
function updateCarouselSlide(id,index,prop,value){const el=findElementById(pageContent,id);if(!el||el.type!=='carousel')return;const a=ensureCarouselSlides(el.styles||{});if(!a[index])return;saveState();a[index][prop]=value;el.styles.slides=a;renderCanvas();saveData();selectedElementId=id;}
function removeCarouselSlide(id,index){const el=findElementById(pageContent,id);if(!el||el.type!=='carousel')return;const a=ensureCarouselSlides(el.styles||{});if(a.length<=1){showNotification('Mantenha pelo menos 1 slide.','error');return;}saveState();a.splice(index,1);el.styles.slides=a;renderCanvas();saveData();selectedElementId=id;updateProperties(id)}
function handleCarouselUpload(input,id){const files=Array.from(input.files||[]);input.value='';if(!files.length)return;const valid=files.filter(f=>f.type.startsWith('image/'));if(!valid.length){showNotification('Selecione imagens válidas.','error');return;}const el=findElementById(pageContent,id);if(!el)return;const a=ensureCarouselSlides(el.styles||{});saveState();let remaining=valid.length;valid.forEach(file=>{const reader=new FileReader();reader.onload=e=>{const base=file.name.replace(/\.[^.]+$/,'');a.push({src:e.target.result,alt:base,title:base,text:'Descrição do slide.',buttonText:'Saiba mais',buttonLink:'#'});remaining--;if(remaining===0){el.styles.slides=a;renderCanvas();saveData();selectedElementId=id;updateProperties(id);showNotification(`${valid.length} slide(s) adicionados!`);}};reader.onerror=()=>{remaining--;if(remaining===0){el.styles.slides=a;renderCanvas();saveData();updateProperties(id);showNotification('Algumas imagens não puderam ser carregadas.','error');}};reader.readAsDataURL(file);});}

const bannerTimers=new Map();
function getBannerRoot(id){return document.querySelector(`.sbc-banner-ad[data-banner-id="${String(id).replace(/"/g,'&quot;')}"]`) || document.querySelector(`.el-bannerAd .sbc-banner-ad`);}
function stopBannerTimer(id){const t=bannerTimers.get(id);if(t){clearInterval(t);bannerTimers.delete(id);}}
function goBannerSlide(id,index){
    const root=getBannerRoot(id); if(!root)return;
    const wraps=Array.from(root.querySelectorAll('.sbc-banner-slide-wrap')); if(!wraps.length)return;
    let idx=Number(index)||0; if(idx>=wraps.length)idx=0; if(idx<0)idx=wraps.length-1;
    const transition=root.dataset.bannerTransition||'fade';
    wraps.forEach((w,i)=>{
        w.style.opacity=i===idx?'1':'0';
        w.style.transform=transition==='slide'?`translateX(${i===idx?'0':'100%'})`:'translateX(0)';
        w.style.zIndex=i===idx?'2':'1';
    });
    root.dataset.bannerIndex=String(idx);
}
function startBannerTimer(id){
    stopBannerTimer(id);
    const root=getBannerRoot(id); if(!root||root.dataset.bannerAutoplay!=='1')return;
    const wraps=root.querySelectorAll('.sbc-banner-slide-wrap'); if(wraps.length<2)return;
    bannerTimers.set(id,setInterval(()=>goBannerSlide(id,(parseInt(root.dataset.bannerIndex||0,10)||0)+1),Math.max(1000,parseFloat(root.dataset.bannerInterval||5000)||5000)));
}
function bindBannerAd(root){
    const shell=root?.querySelector('.sbc-banner-ad'); if(!shell)return;
    const id=shell.closest('[data-id]')?.dataset.id || shell.dataset.bannerId || ('banner-'+Math.random());
    shell.dataset.bannerId=id;
    goBannerSlide(id,0); startBannerTimer(id);
    if(shell.dataset.bannerPause==='1'){
        shell.addEventListener('mouseenter',()=>stopBannerTimer(id));
        shell.addEventListener('mouseleave',()=>startBannerTimer(id));
    }
}
function updateBannerSlide(id,index,prop,value){const el=findElementById(pageContent,id);if(!el||el.type!=='bannerAd')return;const a=ensureBannerSlides(el.styles||{});if(!a[index])return;saveState();a[index][prop]=value;el.styles.bannerSlides=a;renderCanvas();saveData();selectedElementId=id;updateProperties(id);}
function addBannerSlide(id){const el=findElementById(pageContent,id);if(!el)return;const a=ensureBannerSlides(el.styles||{});saveState();a.push({src:`https://placehold.co/1200x400?text=Banner+${a.length+1}`,alt:`Banner ${a.length+1}`,title:`Banner ${a.length+1}`,text:'',buttonText:'',buttonLink:''});el.styles.bannerSlides=a;renderCanvas();saveData();selectedElementId=id;updateProperties(id);}
function addBannerSlideFromUrl(id){const input=document.getElementById('banner-url-'+id),url=String(input?.value||'').trim();if(!url){showNotification('Informe a URL da imagem.','error');return;}const el=findElementById(pageContent,id);if(!el)return;saveState();const a=ensureBannerSlides(el.styles||{});a.push({src:url,alt:`Banner ${a.length+1}`,title:'',text:'',buttonText:'',buttonLink:''});el.styles.bannerSlides=a;if(input)input.value='';renderCanvas();saveData();selectedElementId=id;updateProperties(id);}
function removeBannerSlide(id,index){const el=findElementById(pageContent,id);if(!el||el.type!=='bannerAd')return;const a=ensureBannerSlides(el.styles||{});if(a.length<=1){showNotification('Mantenha pelo menos 1 banner.','error');return;}saveState();a.splice(index,1);el.styles.bannerSlides=a;renderCanvas();saveData();selectedElementId=id;updateProperties(id);}
function handleBannerUpload(input,id){const files=Array.from(input.files||[]);input.value='';if(!files.length)return;const valid=files.filter(f=>f.type.startsWith('image/'));if(!valid.length){showNotification('Selecione imagens válidas.','error');return;}const el=findElementById(pageContent,id);if(!el)return;const a=ensureBannerSlides(el.styles||{});saveState();let remaining=valid.length;valid.forEach(file=>{const reader=new FileReader();reader.onload=e=>{const base=file.name.replace(/\.[^.]+$/,'');a.push({src:e.target.result,alt:base,title:base,text:'',buttonText:'',buttonLink:''});remaining--;if(remaining===0){el.styles.bannerSlides=a;renderCanvas();saveData();selectedElementId=id;updateProperties(id);showNotification(`${valid.length} banner(s) adicionados!`);}};reader.onerror=()=>{remaining--;if(remaining===0){el.styles.bannerSlides=a;renderCanvas();saveData();selectedElementId=id;updateProperties(id);showNotification('Algumas imagens não puderam ser carregadas.','error');}};reader.readAsDataURL(file);});}

function ensureNavbarItems(styles) {
    styles = styles || {};
    if (!Array.isArray(styles.items) || !styles.items.length) {
        styles.items = [
            {label:'Início',link:'#'},
            {label:'Sobre',link:'#sobre'},
            {label:'Serviços',link:'#servicos'},
            {label:'Contato',link:'#contato'}
        ];
    }
    return styles.items;
}
function addNavbarItem(id) {
    const el = findElementById(pageContent,id); if(!el || el.type!=='navbar') return;
    const items = ensureNavbarItems(el.styles || {}); saveState();
    items.push({label:`Item ${items.length+1}`,link:'#'}); el.styles.items=items;
    renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}
function updateNavbarItem(id,index,prop,value) {
    const el=findElementById(pageContent,id); if(!el||el.type!=='navbar')return;
    const items=ensureNavbarItems(el.styles||{}); if(!items[index])return; saveState();
    items[index][prop]=value; el.styles.items=items; renderCanvas(); saveData(); selectedElementId=id;
}
function removeNavbarItem(id,index) {
    const el=findElementById(pageContent,id); if(!el||el.type!=='navbar')return;
    const items=ensureNavbarItems(el.styles||{}); if(items.length<=1){showNotification('Mantenha pelo menos 1 item no menu.','error');return;}
    saveState(); items.splice(index,1); el.styles.items=items; renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}
function reorderNavbarItem(id,from,to) {
    const el=findElementById(pageContent,id); if(!el||el.type!=='navbar')return;
    const items=ensureNavbarItems(el.styles||{}); if(from===to||from<0||to<0||from>=items.length||to>=items.length)return;
    saveState(); const item=items.splice(from,1)[0]; items.splice(to,0,item); el.styles.items=items; renderCanvas(); saveData(); selectedElementId=id; updateProperties(id);
}
let navbarDragState=null;
function startNavbarDrag(event,id,index){navbarDragState={id,index};event.stopPropagation();event.dataTransfer.effectAllowed='move';event.dataTransfer.setData('text/plain',String(index));}
function allowNavbarDrop(event){event.preventDefault();event.stopPropagation();event.dataTransfer.dropEffect='move';}
function dropNavbarItem(event,id,target){event.preventDefault();event.stopPropagation();if(!navbarDragState||navbarDragState.id!==id)return;let from=navbarDragState.index,to=target;navbarDragState=null;if(from<to)to--;reorderNavbarItem(id,from,to);}
function toggleNavbarItemOpen(id,index){ /* reservado para compatibilidade */ }

function updateStyleLive(id, prop, value) {
    const el = findElementById(pageContent, id);
    if (!el) return;
    el.styles = el.styles || {};
    const mode = getResponsiveDeviceMode();
    if (isResponsiveStyleProp(prop)) {
        const r = ensureResponsiveStyles(el);
        const v = normalizeResponsiveCssValue(prop, value);
        if (v === '') delete r[mode][prop]; else r[mode][prop] = v;
    } else {
        el.styles[prop] = value;
    }
    selectedElementId = id;
    renderCanvas();

    // Banner: garante que a alteração numérica apareça imediatamente no canvas,
    // mesmo quando o motor responsivo tiver acabado de reconstruir o DOM.
    if (el.type === 'bannerAd' && (prop === 'titleFontSize' || prop === 'descriptionFontSize')) {
        const px = Math.max(8, parseFloat(String(value).replace(',', '.')) || (prop === 'titleFontSize' ? 28 : 15));
        const selector = prop === 'titleFontSize' ? '[data-banner-title=\"1\"]' : '[data-banner-description=\"1\"]';
        document.querySelectorAll(`[data-id=\"${CSS.escape(id)}\"] ${selector}`).forEach(node => {
            node.style.setProperty('font-size', `${px}px`, 'important');
        });
    }
    saveData();
}

function updateStyle(id, prop, value) {
    const el = findElementById(pageContent, id);
    if (!el) return;
    el.styles = el.styles || {};

    // No Tablet/Mobile, os controles visuais do mesmo painel do Desktop
    // passam a gravar somente no breakpoint atual. Conteúdo/dados do widget
    // continuam globais para não duplicar textos, links, slides etc.
    const mode = getResponsiveDeviceMode();
    if (mode !== 'desktop' && isResponsiveStyleProp(prop)) {
        saveState();
        const r = ensureResponsiveStyles(el);
        const v = normalizeResponsiveCssValue(prop, value);
        if (v === '') delete r[mode][prop]; else r[mode][prop] = v;
        renderCanvas();
        saveData();
        selectedElementId = id;
        updateProperties(id);
        return;
    }

    saveState();

    if (prop === 'content') {
        el.content = value;
    } else if (prop === 'textAlign') {
        el.styles.textAlign = ['left','center','right','justify'].includes(value) ? value : 'left';
    } else {
        if (el.type === 'carousel') {
            if (prop === 'width') {
                const n = parseFloat(String(value).replace(',', '.'));
                el.styles.width = Number.isFinite(n) && n > 0 ? String(Math.min(2000, Math.max(1, n))) : '100';
            } else if (prop === 'widthUnit') {
                el.styles.widthUnit = value === 'px' ? 'px' : '%';
            } else if (prop === 'slideHeight') {
                const n = parseFloat(String(value).replace(',', '.'));
                el.styles.slideHeight = String(Number.isFinite(n) ? Math.min(1000, Math.max(120, n)) : 360);
            } else if (prop === 'gap') {
                const n = parseFloat(String(value).replace(',', '.'));
                el.styles.gap = String(Number.isFinite(n) ? Math.min(80, Math.max(0, n)) : 16);
            } else if (prop === 'slidesVisible') {
                const n = parseInt(value, 10);
                el.styles.slidesVisible = String(Number.isFinite(n) ? Math.min(3, Math.max(1, n)) : 1);
            } else if (prop === 'alignment') {
                el.styles.alignment = ['left','center','right'].includes(value) ? value : 'center';
            } else {
                el.styles[prop] = value;
            }
        } else {
            el.styles[prop] = value;
        }
    }

    renderCanvas();
    saveData();

    selectedElementId = id;
    if (el.type === 'divider') {
        updateProperties(id);
    }
    const selected = document.querySelector(`[data-id="${CSS.escape(id)}"]`);
    if (selected) selected.classList.add('selected');
    if (prop === 'textAlign' && (el.type === 'heading' || el.type === 'text')) {
        const align = el.styles.textAlign;
        const content = selected?.querySelector('.el-content');
        const textNode = content?.querySelector('h2, p');
        selected?.style.setProperty('text-align', align, 'important');
        content?.style.setProperty('text-align', align, 'important');
        textNode?.style.setProperty('text-align', align, 'important');
        if (textNode) {
            textNode.style.display = 'block';
        }
        updateProperties(id);
    }
}

function setResponsive(mode, btn) {
    document.querySelectorAll('.responsive-bar button').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');

    const canvas = document.getElementById('canvas');
    if (!canvas) return;

    canvas.classList.remove('tablet', 'mobile', 'desktop');
    canvas.classList.add(mode === 'tablet' ? 'tablet' : mode === 'mobile' ? 'mobile' : 'desktop');
    canvas.dataset.responsiveMode = mode;

    requestAnimationFrame(() => {
        applyResponsiveEngine(canvas, mode);
        canvas.dispatchEvent(new CustomEvent('sbc:responsive', { detail: { mode } }));
        if (selectedElementId) updateProperties(selectedElementId);
    });

    showNotification(`Modo ${mode} ativado!`, 'info');
}



/* =========================================================
   SBC RESPONSIVIDADE V5 — MOTOR DE ADAPTAÇÃO REAL
   A V4 somente sobrescrevia CSS. A V5 normaliza o DOM depois
   que o editor/preview termina de renderizar, evitando que
   estilos inline e estruturas antigas mantenham o layout desktop.
   Os dados salvos NÃO são alterados.
   ========================================================= */
function normalizeResponsiveLayout(root, mode) {
    if (!root) return;
    const gridSelectors = [
        '.sbc-features-grid','.sbc-counters-grid','.sbc-pricing-grid',
        '.sbc-team-grid','.sbc-gallery-grid','.sbc-testimonials-grid','.sbc-form-grid'
    ];
    const grids = root.querySelectorAll(gridSelectors.join(','));
    grids.forEach(grid => {
        grid.style.setProperty('width','100%','important');
        grid.style.setProperty('max-width','100%','important');
        grid.style.setProperty('min-width','0','important');
        grid.style.setProperty('box-sizing','border-box','important');
        if (mode === 'mobile') {
            grid.style.setProperty('grid-template-columns','minmax(0,1fr)','important');
            grid.style.setProperty('gap','12px','important');
        } else if (mode === 'tablet') {
            grid.style.setProperty('grid-template-columns','repeat(2,minmax(0,1fr))','important');
            grid.style.setProperty('gap','16px','important');
        }
    });

    root.querySelectorAll('.columns-container').forEach(container => {
        container.style.setProperty('width','100%','important');
        container.style.setProperty('max-width','100%','important');
        container.style.setProperty('min-width','0','important');
        container.style.setProperty('box-sizing','border-box','important');
        if (mode === 'mobile') {
            container.style.setProperty('display','flex','important');
            container.style.setProperty('flex-direction','column','important');
            container.style.setProperty('flex-wrap','nowrap','important');
            container.style.setProperty('gap','0','important');
        } else if (mode === 'tablet') {
            container.style.setProperty('display','grid','important');
            container.style.setProperty('grid-template-columns','repeat(2,minmax(0,1fr))','important');
            container.style.setProperty('gap','16px','important');
        } else {
            container.style.setProperty('display','flex','important');
            container.style.setProperty('flex-direction','row','important');
            container.style.setProperty('flex-wrap','wrap','important');
            container.style.setProperty('gap','15px','important');
        }
    });

    root.querySelectorAll('.el-column').forEach(column => {
        column.style.setProperty('min-width','0','important');
        column.style.setProperty('max-width','100%','important');
        column.style.setProperty('box-sizing','border-box','important');
        if (mode === 'mobile') {
            column.style.setProperty('width','100%','important');
            column.style.setProperty('flex','0 0 100%','important');
        } else if (mode === 'tablet') {
            column.style.setProperty('width','auto','important');
            column.style.setProperty('flex','none','important');
        } else {
            column.style.setProperty('width','auto','important');
            column.style.setProperty('flex','1 1 0','important');
        }
    });

    root.querySelectorAll('.el-features,.el-features > .el-content,.el-features .sbc-features-shell').forEach(node => {
        node.style.setProperty('display','block','important');
        node.style.setProperty('width','100%','important');
        node.style.setProperty('max-width','100%','important');
        node.style.setProperty('min-width','0','important');
        node.style.setProperty('box-sizing','border-box','important');
        node.style.setProperty('flex','0 0 auto','important');
    });
    root.querySelectorAll('.el-features .sbc-features-shell > header').forEach(node => {
        node.style.setProperty('display','block','important');
        node.style.setProperty('width','100%','important');
        node.style.setProperty('max-width','100%','important');
        node.style.setProperty('min-width','0','important');
        node.style.setProperty('float','none','important');
        node.style.setProperty('clear','both','important');
    });

    root.querySelectorAll('.el-content,.widgets-container').forEach(node => {
        node.style.setProperty('width','100%','important');
        node.style.setProperty('max-width','100%','important');
        node.style.setProperty('min-width','0','important');
        node.style.setProperty('box-sizing','border-box','important');
    });

    if (mode === 'mobile') {
        root.querySelectorAll('.sbc-feature-card,.sbc-counter-card,.sbc-pricing-card,.sbc-team-card,.sbc-gallery-item,.sbc-testimonial-card').forEach(card => {
            card.style.setProperty('width','100%','important');
            card.style.setProperty('max-width','100%','important');
            card.style.setProperty('min-width','0','important');
            card.style.setProperty('box-sizing','border-box','important');
        });
        root.querySelectorAll('.sbc-features-shell > header').forEach(header => {
            header.style.setProperty('width','100%','important');
            header.style.setProperty('max-width','100%','important');
            header.style.setProperty('display','block','important');
            header.style.setProperty('text-align','center','important');
        });
    }
}

function applyResponsiveEngine(root, mode) {
    if (!root) return;

    mode = ['desktop','tablet','mobile'].includes(mode) ? mode : 'desktop';
    root.classList.remove('sbc-responsive-mobile','sbc-responsive-tablet','sbc-responsive-desktop');
    root.classList.remove('desktop','tablet','mobile');
    root.classList.add(mode);
    root.classList.add(`sbc-responsive-${mode}`);
    root.dataset.responsiveMode = mode;
    normalizeResponsiveLayout(root, mode);

    // Segunda passada: remove qualquer largura/grade estrutural antiga que
    // tenha vindo de inline style ou de uma renderização anterior.
    root.querySelectorAll('.columns-container').forEach(container => {
        if (mode === 'mobile') {
            container.style.setProperty('display','flex','important');
            container.style.setProperty('flex-direction','column','important');
            container.style.setProperty('flex-wrap','nowrap','important');
            container.style.setProperty('grid-template-columns','none','important');
            container.style.setProperty('width','100%','important');
        } else if (mode === 'tablet') {
            container.style.setProperty('display','grid','important');
            container.style.setProperty('grid-template-columns','repeat(2,minmax(0,1fr))','important');
            container.style.setProperty('flex-direction','unset','important');
            container.style.setProperty('flex-wrap','unset','important');
            container.style.setProperty('width','100%','important');
        } else {
            container.style.setProperty('display','flex','important');
            container.style.setProperty('flex-direction','row','important');
            container.style.setProperty('flex-wrap','wrap','important');
            container.style.setProperty('grid-template-columns','none','important');
            container.style.setProperty('width','100%','important');
        }
    });

    root.querySelectorAll('.columns-container > .el-column').forEach(column => {
        column.style.setProperty('min-width','0','important');
        column.style.setProperty('max-width','100%','important');
        if (mode === 'mobile') {
            column.style.setProperty('width','100%','important');
            column.style.setProperty('flex','0 0 100%','important');
            column.style.setProperty('grid-column','1 / -1','important');
        } else if (mode === 'tablet') {
            column.style.setProperty('width','auto','important');
            column.style.setProperty('flex','none','important');
            column.style.removeProperty('grid-column');
        } else {
            column.style.setProperty('width','auto','important');
            column.style.setProperty('flex','1 1 0','important');
            column.style.removeProperty('grid-column');
        }
    });
}

function applyResponsiveToMainCanvas() {
    const canvas = document.getElementById('canvas');
    if (!canvas) return;
    const mode = canvas.classList.contains('mobile') ? 'mobile' : (canvas.classList.contains('tablet') ? 'tablet' : 'desktop');
    applyResponsiveEngine(canvas, mode);
}


let previewDeviceMode = 'desktop';

function getPreviewElements() {
    return Array.isArray(pageContent) ? pageContent : [];
}

function cleanPreviewRoot(root) {
    if (!root) return;
    root.removeAttribute('id');
    root.classList.remove('selected','drag-over','dragging');
    root.querySelectorAll('.el-delete,.el-duplicate,.column-drop-placeholder,.drag-over,.dragging').forEach(node => node.remove());
    root.querySelectorAll('[data-id]').forEach(node => {
        node.removeAttribute('draggable');
        node.classList.remove('selected','drag-over','dragging');
        node.style.removeProperty('outline');
        node.style.removeProperty('box-shadow');
    });
}

function buildPreviewCanvas(mode) {
    const previewCanvas = document.createElement('div');
    previewCanvas.className = `canvas ${mode}`;
    previewCanvas.dataset.responsiveMode = mode;
    previewCanvas.style.width = '100%';
    previewCanvas.style.minWidth = '0';
    previewCanvas.style.margin = '0';
    previewCanvas.style.boxShadow = 'none';
    previewCanvas.style.background = '#fff';
    previewCanvas.style.backgroundImage = 'none';
    previewCanvas.style.overflowX = 'hidden';

    const previousMode = window.__sbcRenderModeOverride;
    window.__sbcRenderModeOverride = mode;
    try {
        getPreviewElements().forEach(el => previewCanvas.appendChild(createDOMElement(el)));
    } finally {
        if (previousMode === undefined) delete window.__sbcRenderModeOverride;
        else window.__sbcRenderModeOverride = previousMode;
    }

    cleanPreviewRoot(previewCanvas);
    applyResponsiveEngine(previewCanvas, mode);
    getPreviewElements().forEach(el => { const node = previewCanvas.querySelector(`[data-id="${CSS.escape(el.id)}"]`); if (node) applyElementResponsiveStyles(node, el, mode); });

    // MOBILE: garantir estruturalmente que qualquer widget Recursos ocupe
    // uma linha inteira, inclusive quando estiver dentro de colunas/containers
    // que originalmente eram horizontais. Isto é feito no DOM antes de o
    // Preview ser serializado, portanto não depende da cascata de CSS.
    if (mode === 'mobile') {
        const imp = (el, prop, value) => {
            if (el) el.style.setProperty(prop, value, 'important');
        };
        imp(previewCanvas, 'width', '100%');
        imp(previewCanvas, 'max-width', '100%');
        imp(previewCanvas, 'min-width', '0');
        imp(previewCanvas, 'padding', '0');

        previewCanvas.querySelectorAll('.el-features').forEach(widget => {
            // O próprio widget.
            ['display','width','max-width','min-width','box-sizing','flex','align-self'].forEach((prop, i) => {
                const values = ['block','100%','100%','0','border-box','0 0 auto','stretch'];
                imp(widget, prop, values[i]);
            });

            // Subir pela árvore até a linha de colunas.
            let parent = widget.parentElement;
            while (parent && parent !== previewCanvas) {
                if (parent.classList.contains('el-column')) {
                    imp(parent, 'display', 'block');
                    imp(parent, 'width', '100%');
                    imp(parent, 'max-width', '100%');
                    imp(parent, 'min-width', '0');
                    imp(parent, 'flex', '0 0 100%');
                    imp(parent, 'grid-column', '1 / -1');
                    imp(parent, 'box-sizing', 'border-box');
                } else if (parent.classList.contains('columns-container')) {
                    imp(parent, 'display', 'flex');
                    imp(parent, 'flex-direction', 'column');
                    imp(parent, 'flex-wrap', 'nowrap');
                    imp(parent, 'width', '100%');
                    imp(parent, 'max-width', '100%');
                    imp(parent, 'min-width', '0');
                    imp(parent, 'gap', '0');
                    imp(parent, 'box-sizing', 'border-box');
                } else if (parent.classList.contains('widgets-container')) {
                    imp(parent, 'display', 'flex');
                    imp(parent, 'flex-direction', 'column');
                    imp(parent, 'width', '100%');
                    imp(parent, 'max-width', '100%');
                    imp(parent, 'min-width', '0');
                    imp(parent, 'box-sizing', 'border-box');
                }
                parent = parent.parentElement;
            }

            const shell = widget.querySelector('.sbc-features-shell');
            const content = widget.querySelector(':scope > .el-content');
            const grid = widget.querySelector('.sbc-features-grid');
            [content, shell].forEach(el => {
                imp(el, 'display', 'block');
                imp(el, 'width', '100%');
                imp(el, 'max-width', '100%');
                imp(el, 'min-width', '0');
                imp(el, 'box-sizing', 'border-box');
            });
            const header = shell?.querySelector(':scope > header');
            imp(header, 'display', 'block');
            imp(header, 'width', '100%');
            imp(header, 'max-width', '100%');
            imp(header, 'min-width', '0');
            imp(header, 'float', 'none');
            imp(header, 'clear', 'both');
            imp(header, 'box-sizing', 'border-box');
            imp(grid, 'display', 'grid');
            imp(grid, 'grid-template-columns', 'minmax(0,1fr)');
            imp(grid, 'grid-auto-flow', 'row');
            imp(grid, 'width', '100%');
            imp(grid, 'max-width', '100%');
            imp(grid, 'min-width', '0');
            imp(grid, 'box-sizing', 'border-box');
            imp(grid, 'gap', '14px');
            widget.querySelectorAll('.sbc-feature-card').forEach(card => {
                imp(card, 'display', 'block');
                imp(card, 'width', '100%');
                imp(card, 'max-width', '100%');
                imp(card, 'min-width', '0');
                imp(card, 'box-sizing', 'border-box');
                imp(card, 'word-break', 'normal');
                imp(card, 'overflow-wrap', 'break-word');
            });
        });
    }
    return previewCanvas;
}

function openPreview(mode = previewDeviceMode) {
    mode = ['desktop','tablet','mobile'].includes(mode) ? mode : 'desktop';
    previewDeviceMode = mode;

    /* Preview real: abre uma NOVA ABA. O editor continua intacto na aba atual. */
    let tab = null;
    try { tab = window.open('', 'sbcPreviewWindow'); } catch (e) { tab = null; }
    if (!tab) {
        showNotification('O navegador bloqueou a nova aba do Preview. Permita pop-ups para este site.', 'error');
        return false;
    }
    window.__sbcPreviewTab = tab;

    const previewCanvas = buildPreviewCanvas(mode);

    // MOBILE FINAL: normaliza a árvore do widget antes de gerar o HTML da nova aba.
    // Assim o layout não depende da cascata de CSS do editor nem de estilos antigos salvos.
    if (mode === 'mobile') {
        const setImportant = (el, prop, value) => {
            if (el) el.style.setProperty(prop, value, 'important');
        };
        setImportant(previewCanvas, 'width', '100%');
        setImportant(previewCanvas, 'max-width', '100%');
        setImportant(previewCanvas, 'min-width', '0');
        previewCanvas.querySelectorAll('.columns-container').forEach(row => {
            setImportant(row,'display','flex');
            setImportant(row,'flex-direction','column');
            setImportant(row,'flex-wrap','nowrap');
            setImportant(row,'width','100%');
            setImportant(row,'max-width','100%');
            setImportant(row,'min-width','0');
            setImportant(row,'gap','0');
        });
        previewCanvas.querySelectorAll('.el-column,.widgets-container,.el-features,.el-features > .el-content,.el-features .sbc-features-shell').forEach(el => {
            setImportant(el,'display','block');
            setImportant(el,'width','100%');
            setImportant(el,'max-width','100%');
            setImportant(el,'min-width','0');
            setImportant(el,'box-sizing','border-box');
            setImportant(el,'margin-left','0');
            setImportant(el,'margin-right','0');
        });
        previewCanvas.querySelectorAll('.el-column').forEach(el => {
            setImportant(el,'flex','0 0 100%');
        });
        previewCanvas.querySelectorAll('.sbc-features-shell > header').forEach(el => {
            setImportant(el,'display','block');
            setImportant(el,'width','100%');
            setImportant(el,'max-width','100%');
            setImportant(el,'min-width','0');
            setImportant(el,'float','none');
            setImportant(el,'clear','both');
            setImportant(el,'margin','0 0 18px');
            setImportant(el,'text-align','center');
        });
        previewCanvas.querySelectorAll('.sbc-features-grid').forEach(grid => {
            setImportant(grid,'display','grid');
            setImportant(grid,'grid-template-columns','minmax(0,1fr)');
            setImportant(grid,'grid-auto-flow','row');
            setImportant(grid,'width','100%');
            setImportant(grid,'max-width','100%');
            setImportant(grid,'min-width','0');
            setImportant(grid,'gap','14px');
            setImportant(grid,'margin','0');
            setImportant(grid,'padding','0');
        });
        previewCanvas.querySelectorAll('.sbc-feature-card').forEach(card => {
            setImportant(card,'display','block');
            setImportant(card,'width','100%');
            setImportant(card,'max-width','100%');
            setImportant(card,'min-width','0');
            setImportant(card,'box-sizing','border-box');
            setImportant(card,'margin','0');
            setImportant(card,'overflow','hidden');
            setImportant(card,'word-break','normal');
            setImportant(card,'overflow-wrap','break-word');
        });
    }

    previewCanvas.classList.add(`sbc-responsive-${mode}`);
    previewCanvas.classList.remove('selected','drag-over','dragging');
    previewCanvas.querySelectorAll('[data-id]').forEach(node => {
        node.removeAttribute('draggable');
        node.classList.remove('selected','drag-over','dragging');
        Array.from(node.attributes).forEach(attr => {
            if (/^on/i.test(attr.name)) node.removeAttribute(attr.name);
        });
    });
    previewCanvas.querySelectorAll('.el-delete,.el-duplicate,.column-drop-placeholder').forEach(n => n.remove());

    const html = previewCanvas.outerHTML;
    const styleBlocks = Array.from(document.querySelectorAll('style')).map(s => s.textContent || '').join('\n');
    const links = Array.from(document.querySelectorAll('link[rel="stylesheet"]'))
        .map(l => `<link rel="stylesheet" href="${escapeAttr(l.href)}">`).join('\n');

    tab.document.open();
    tab.document.write(`<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>Preview — Construtor Pro</title>
${links}
<style>
${styleBlocks}

/* =========================================================
   PREVIEW EM NOVA ABA — isolamento total do editor
   ========================================================= */
html,body{margin:0!important;padding:0!important;width:100%!important;min-height:100%!important;background:#eef1f5!important;overflow-x:hidden!important;font-family:Inter,Arial,sans-serif!important;}
body{color:#111827!important;}
#previewApp{position:fixed;inset:0;display:flex;flex-direction:column;width:100vw;height:100vh;overflow:hidden;background:#eef1f5;}
#previewToolbar{height:58px;min-height:58px;flex:0 0 58px;display:flex;align-items:center;justify-content:space-between;gap:12px;padding:0 16px;background:#111827;color:#fff;border-bottom:1px solid #273244;box-sizing:border-box;z-index:10;}
#previewToolbar .title{font-weight:800;font-size:14px;white-space:nowrap;}
#previewToolbar .group{display:flex;align-items:center;gap:7px;}
#previewToolbar button{border:1px solid #374151;background:#1f2937;color:#d1d5db;border-radius:8px;padding:8px 12px;cursor:pointer;font:600 12px Inter,Arial,sans-serif;}
#previewToolbar button:hover,#previewToolbar button.active{background:#2563eb;border-color:#2563eb;color:#fff;}
#previewToolbar .close{background:#ef4444;border-color:#ef4444;color:#fff;}
#previewStage{flex:1 1 auto;min-height:0;width:100%;overflow:auto;display:flex;justify-content:center;align-items:flex-start;padding:24px;box-sizing:border-box;background:radial-gradient(circle at 1px 1px,#d7dce3 1px,transparent 0) 0 0/20px 20px,#eef1f5;}
#previewFrame{flex:0 0 auto;width:100%;min-height:calc(100vh - 106px);background:#fff;box-shadow:0 12px 40px rgba(0,0,0,.16);box-sizing:border-box;overflow:hidden;transition:width .2s ease;}
#previewFrame.tablet{width:768px;max-width:100%;}
#previewFrame.mobile{width:375px;max-width:100%;}
#previewFrame>.canvas{width:100%!important;max-width:100%!important;min-width:0!important;margin:0!important;padding:0!important;background:#fff!important;box-shadow:none!important;overflow-x:hidden!important;}
#previewFrame>.canvas [data-id]{border:0!important;outline:0!important;box-shadow:none!important;margin-bottom:0!important;background:transparent!important;}
#previewFrame>.canvas .el-section{display:block!important;width:100%!important;max-width:100%!important;min-width:0!important;box-sizing:border-box!important;}
#previewFrame>.canvas .el-column{display:block!important;border:0!important;background:transparent!important;box-sizing:border-box!important;min-width:0!important;max-width:100%!important;}
#previewFrame>.canvas .columns-container{box-sizing:border-box!important;width:100%!important;max-width:100%!important;min-width:0!important;align-items:stretch!important;}
#previewFrame>.canvas .widgets-container{box-sizing:border-box!important;width:100%!important;max-width:100%!important;min-width:0!important;display:flex!important;flex-direction:column!important;align-items:stretch!important;}
#previewFrame>.canvas .widgets-container>[data-id]{width:100%!important;max-width:100%!important;min-width:0!important;box-sizing:border-box!important;flex:0 0 auto!important;align-self:stretch!important;}
#previewFrame>.canvas .el-features,
#previewFrame>.canvas .el-testimonials,
#previewFrame>.canvas .el-pricing,
#previewFrame>.canvas .el-counters,
#previewFrame>.canvas .el-team,
#previewFrame>.canvas .el-gallery,
#previewFrame>.canvas .el-faq,
#previewFrame>.canvas .el-form,
#previewFrame>.canvas .el-timeline,
#previewFrame>.canvas .el-tabs,
#previewFrame>.canvas .el-accordion,
#previewFrame>.canvas .el-carousel,
#previewFrame>.canvas .el-newsletter,
#previewFrame>.canvas .el-hero,
#previewFrame>.canvas .el-navbar,
#previewFrame>.canvas .el-footer,
#previewFrame>.canvas .el-video,
#previewFrame>.canvas .el-heading,
#previewFrame>.canvas .el-text{width:100%!important;max-width:100%!important;min-width:0!important;flex:0 0 auto!important;}
#previewFrame.mobile>.canvas .columns-container{display:flex!important;flex-direction:column!important;flex-wrap:nowrap!important;align-items:stretch!important;width:100%!important;max-width:100%!important;gap:0!important;}
#previewFrame.mobile>.canvas .el-column{display:block!important;width:100%!important;max-width:100%!important;flex:0 0 100%!important;min-width:0!important;margin:0!important;}
#previewFrame.mobile>.canvas .el-section{display:block!important;width:100%!important;}
#previewFrame.mobile>.canvas .el-column>.widgets-container{width:100%!important;max-width:100%!important;}
#previewFrame.mobile>.canvas .sbc-features-grid,
#previewFrame.mobile>.canvas .sbc-counters-grid,
#previewFrame.mobile>.canvas .sbc-pricing-grid,
#previewFrame.mobile>.canvas .sbc-team-grid,
#previewFrame.mobile>.canvas .sbc-gallery-grid,
#previewFrame.mobile>.canvas .sbc-testimonials-grid,
#previewFrame.mobile>.canvas .sbc-form-grid{grid-template-columns:1fr!important;width:100%!important;min-width:0!important;}
#previewFrame.mobile>.canvas .sbc-feature-card,
#previewFrame.mobile>.canvas .sbc-counter-card,
#previewFrame.mobile>.canvas .sbc-pricing-card,
#previewFrame.mobile>.canvas .sbc-team-card,
#previewFrame.mobile>.canvas .sbc-gallery-item,
#previewFrame.mobile>.canvas .sbc-testimonial-card{width:100%!important;max-width:100%!important;min-width:0!important;}
#previewFrame.tablet>.canvas .columns-container{display:grid!important;grid-template-columns:repeat(2,minmax(0,1fr))!important;align-items:stretch!important;gap:16px!important;width:100%!important;max-width:100%!important;}
#previewFrame.tablet>.canvas .el-section{display:block!important;width:100%!important;}
#previewFrame.tablet>.canvas .el-column{display:block!important;width:auto!important;max-width:100%!important;flex:none!important;min-width:0!important;margin:0!important;}
#previewFrame.tablet>.canvas .el-column>.widgets-container{width:100%!important;max-width:100%!important;}
#previewFrame.tablet>.canvas .sbc-features-grid,
#previewFrame.tablet>.canvas .sbc-counters-grid,
#previewFrame.tablet>.canvas .sbc-pricing-grid,
#previewFrame.tablet>.canvas .sbc-team-grid,
#previewFrame.tablet>.canvas .sbc-gallery-grid,
#previewFrame.tablet>.canvas .sbc-testimonials-grid{grid-template-columns:repeat(2,minmax(0,1fr))!important;}
#previewFrame.mobile>.canvas h1{font-size:clamp(24px,8vw,34px)!important;line-height:1.12!important;}
#previewFrame.mobile>.canvas h2{font-size:clamp(22px,7vw,30px)!important;line-height:1.16!important;}
#previewFrame.mobile>.canvas p{font-size:15px!important;line-height:1.55!important;}
#previewFrame.mobile>.canvas .sbc-features-shell{padding-left:0!important;padding-right:0!important;}
#previewFrame.mobile>.canvas .sbc-features-shell>header{display:block!important;width:100%!important;text-align:center!important;margin-bottom:18px!important;}
#previewFrame.mobile>.canvas .sbc-features-grid{gap:12px!important;}
#previewFrame.mobile>.canvas .sbc-feature-card{padding:18px!important;}

/* RESPONSIVIDADE REAL: a largura do dispositivo manda no layout. */
#previewFrame>.canvas, #previewFrame>.canvas *{box-sizing:border-box!important;}
#previewFrame>.canvas [data-id]{min-width:0!important;max-width:100%!important;overflow-wrap:anywhere!important;}
#previewFrame>.canvas .el-content{width:100%!important;max-width:100%!important;min-width:0!important;box-sizing:border-box!important;}
#previewFrame>.canvas .el-heading h1,#previewFrame>.canvas .el-heading h2,#previewFrame>.canvas .el-heading h3,#previewFrame>.canvas .el-text p{max-width:100%!important;min-width:0!important;overflow-wrap:anywhere!important;word-break:normal!important;}

#previewFrame.tablet>.canvas .columns-container{grid-template-columns:repeat(2,minmax(0,1fr))!important;}
#previewFrame.tablet>.canvas .el-column{min-width:0!important;width:auto!important;}
#previewFrame.tablet>.canvas .sbc-features-shell,#previewFrame.tablet>.canvas .sbc-counters-shell,#previewFrame.tablet>.canvas .sbc-pricing-shell,#previewFrame.tablet>.canvas .sbc-team-shell,#previewFrame.tablet>.canvas .sbc-gallery-shell,#previewFrame.tablet>.canvas .sbc-testimonials-shell{width:100%!important;max-width:100%!important;min-width:0!important;}
#previewFrame.tablet>.canvas .sbc-features-shell>header,#previewFrame.tablet>.canvas .sbc-counters-shell>header,#previewFrame.tablet>.canvas .sbc-team-shell>header{display:block!important;width:100%!important;max-width:100%!important;}
#previewFrame.tablet>.canvas .sbc-features-grid,#previewFrame.tablet>.canvas .sbc-counters-grid,#previewFrame.tablet>.canvas .sbc-pricing-grid,#previewFrame.tablet>.canvas .sbc-team-grid,#previewFrame.tablet>.canvas .sbc-gallery-grid,#previewFrame.tablet>.canvas .sbc-testimonials-grid{grid-template-columns:repeat(2,minmax(0,1fr))!important;width:100%!important;}

#previewFrame.mobile>.canvas .columns-container{display:flex!important;flex-direction:column!important;flex-wrap:nowrap!important;gap:0!important;}
#previewFrame.mobile>.canvas .el-column{display:block!important;width:100%!important;max-width:100%!important;min-width:0!important;flex:0 0 100%!important;}
#previewFrame.mobile>.canvas .widgets-container{display:flex!important;flex-direction:column!important;width:100%!important;max-width:100%!important;min-width:0!important;gap:12px!important;}
#previewFrame.mobile>.canvas .widgets-container>[data-id]{width:100%!important;max-width:100%!important;min-width:0!important;flex:0 0 auto!important;}
#previewFrame.mobile>.canvas .sbc-features-shell,#previewFrame.mobile>.canvas .sbc-counters-shell,#previewFrame.mobile>.canvas .sbc-pricing-shell,#previewFrame.mobile>.canvas .sbc-team-shell,#previewFrame.mobile>.canvas .sbc-gallery-shell,#previewFrame.mobile>.canvas .sbc-testimonials-shell{display:block!important;width:100%!important;max-width:100%!important;min-width:0!important;margin-left:0!important;margin-right:0!important;}
#previewFrame.mobile>.canvas .sbc-features-shell>header,#previewFrame.mobile>.canvas .sbc-counters-shell>header,#previewFrame.mobile>.canvas .sbc-team-shell>header{display:block!important;width:100%!important;max-width:100%!important;margin-left:0!important;margin-right:0!important;text-align:center!important;}
#previewFrame.mobile>.canvas .sbc-features-grid,#previewFrame.mobile>.canvas .sbc-counters-grid,#previewFrame.mobile>.canvas .sbc-pricing-grid,#previewFrame.mobile>.canvas .sbc-team-grid,#previewFrame.mobile>.canvas .sbc-gallery-grid,#previewFrame.mobile>.canvas .sbc-testimonials-grid,#previewFrame.mobile>.canvas .sbc-form-grid{display:grid!important;grid-template-columns:minmax(0,1fr)!important;width:100%!important;max-width:100%!important;min-width:0!important;gap:12px!important;}
#previewFrame.mobile>.canvas .sbc-feature-card,#previewFrame.mobile>.canvas .sbc-counter-card,#previewFrame.mobile>.canvas .sbc-pricing-card,#previewFrame.mobile>.canvas .sbc-team-card,#previewFrame.mobile>.canvas .sbc-gallery-item,#previewFrame.mobile>.canvas .sbc-testimonial-card{width:100%!important;max-width:100%!important;min-width:0!important;box-sizing:border-box!important;}
#previewFrame.mobile>.canvas h1{font-size:clamp(26px,8vw,34px)!important;line-height:1.12!important;}
#previewFrame.mobile>.canvas h2{font-size:clamp(22px,7vw,30px)!important;line-height:1.16!important;}
#previewFrame.mobile>.canvas h3{font-size:clamp(18px,5.5vw,24px)!important;line-height:1.2!important;}
#previewFrame.mobile>.canvas p,#previewFrame.mobile>.canvas li{font-size:clamp(14px,4vw,16px)!important;line-height:1.5!important;}
#previewFrame.mobile>.canvas .sbc-feature-card{padding:16px!important;}
#previewFrame.mobile>.canvas .sbc-feature-card>div:nth-child(2){font-size:clamp(15px,4.5vw,18px)!important;line-height:1.25!important;white-space:normal!important;overflow-wrap:break-word!important;}
#previewFrame.mobile>.canvas .sbc-feature-card>div:nth-child(3){font-size:clamp(13px,3.8vw,15px)!important;line-height:1.5!important;white-space:normal!important;overflow-wrap:break-word!important;}

@media(max-width:600px){#previewToolbar .title{display:none;}#previewStage{padding:10px;}#previewFrame.mobile{width:min(375px,calc(100vw - 20px))!important;max-width:375px;}}

/* HARD FIX — responsive widget layout */
#previewFrame > .canvas .el-features,
#previewFrame > .canvas .el-features > .el-content,
#previewFrame > .canvas .el-features .sbc-features-shell {
  display:block!important;width:100%!important;max-width:100%!important;min-width:0!important;box-sizing:border-box!important;flex:0 0 auto!important;
}
#previewFrame > .canvas .el-features .sbc-features-shell > header {
  display:block!important;width:100%!important;max-width:100%!important;min-width:0!important;box-sizing:border-box!important;float:none!important;clear:both!important;
}
#previewFrame > .canvas .el-features .sbc-features-grid {
  display:grid!important;width:100%!important;max-width:100%!important;min-width:0!important;box-sizing:border-box!important;
}
#previewFrame.desktop > .canvas .el-features .sbc-features-grid {grid-template-columns:repeat(3,minmax(0,1fr))!important;gap:20px!important;}
#previewFrame.tablet > .canvas .el-features .sbc-features-grid {grid-template-columns:repeat(2,minmax(0,1fr))!important;gap:16px!important;}
#previewFrame.mobile > .canvas .el-features .sbc-features-grid {grid-template-columns:minmax(0,1fr)!important;gap:12px!important;}
#previewFrame.tablet > .canvas .el-features .sbc-feature-card,
#previewFrame.mobile > .canvas .el-features .sbc-feature-card {width:100%!important;max-width:100%!important;min-width:0!important;box-sizing:border-box!important;overflow-wrap:normal!important;word-break:normal!important;}
#previewFrame.mobile > .canvas .el-features .sbc-features-shell > header > div:first-child {font-size:clamp(22px,7vw,30px)!important;line-height:1.15!important;}
#previewFrame.mobile > .canvas .el-features .sbc-features-shell > header > div:last-child {font-size:clamp(14px,4vw,16px)!important;line-height:1.5!important;}
#previewFrame.mobile > .canvas .el-features .sbc-feature-card > div:nth-child(2) {font-size:clamp(16px,4.5vw,19px)!important;line-height:1.25!important;}
#previewFrame.mobile > .canvas .el-features .sbc-feature-card > div:nth-child(3) {font-size:clamp(13px,3.8vw,15px)!important;line-height:1.5!important;}

/* =========================================================
   V3 — VIEWPORT REAL PARA TABLET E MOBILE
   O tamanho do widget antigo não pode limitar o viewport.
   ========================================================= */
#previewFrame.tablet,
#previewFrame.mobile{
  overflow-x:hidden!important;
}
#previewFrame.tablet > .canvas,
#previewFrame.mobile > .canvas{
  width:100%!important;
  max-width:none!important;
  min-width:0!important;
}

/* O recurso/benefícios e toda a cadeia de pais devem acompanhar a tela. */
#previewFrame.tablet > .canvas .el-features,
#previewFrame.mobile > .canvas .el-features{
  width:100%!important;
  max-width:none!important;
  min-width:0!important;
  margin-left:0!important;
  margin-right:0!important;
  align-self:stretch!important;
  flex:1 1 100%!important;
  box-sizing:border-box!important;
}
#previewFrame.tablet > .canvas .el-features > .el-content,
#previewFrame.mobile > .canvas .el-features > .el-content,
#previewFrame.tablet > .canvas .el-features .sbc-features-shell,
#previewFrame.mobile > .canvas .el-features .sbc-features-shell{
  width:100%!important;
  max-width:none!important;
  min-width:0!important;
  margin-left:0!important;
  margin-right:0!important;
  box-sizing:border-box!important;
}

/* Tablet: tela cheia + 2 colunas confortáveis. */
#previewFrame.tablet > .canvas .el-features .sbc-features-grid{
  display:grid!important;
  grid-template-columns:repeat(2,minmax(0,1fr))!important;
  width:100%!important;
  max-width:none!important;
  min-width:0!important;
  gap:clamp(16px,2vw,28px)!important;
}
#previewFrame.tablet > .canvas .el-features .sbc-feature-card{
  width:100%!important;
  max-width:none!important;
  min-width:0!important;
  padding:clamp(18px,2.2vw,32px)!important;
  overflow-wrap:break-word!important;
  word-break:normal!important;
}
#previewFrame.tablet > .canvas .el-features .sbc-features-shell > header > div:first-child{
  font-size:clamp(28px,3vw,44px)!important;
  line-height:1.12!important;
  word-break:normal!important;
  overflow-wrap:break-word!important;
}
#previewFrame.tablet > .canvas .el-features .sbc-features-shell > header > div:last-child{
  font-size:clamp(15px,1.5vw,18px)!important;
  line-height:1.5!important;
  word-break:normal!important;
}

/* Mobile: uma coluna de verdade, sem três colunas comprimidas. */
#previewFrame.mobile > .canvas .el-features .sbc-features-grid{
  display:grid!important;
  grid-template-columns:minmax(0,1fr)!important;
  grid-auto-flow:row!important;
  width:100%!important;
  max-width:none!important;
  min-width:0!important;
  gap:14px!important;
}
#previewFrame.mobile > .canvas .el-features .sbc-feature-card{
  width:100%!important;
  max-width:none!important;
  min-width:0!important;
  padding:18px!important;
  text-align:left!important;
  overflow-wrap:break-word!important;
  word-break:normal!important;
}
#previewFrame.mobile > .canvas .el-features .sbc-feature-icon-box{
  margin-left:0!important;
  margin-right:auto!important;
}
#previewFrame.mobile > .canvas .el-features .sbc-features-shell > header{
  width:100%!important;
  max-width:none!important;
  margin-left:0!important;
  margin-right:0!important;
  text-align:center!important;
}
#previewFrame.mobile > .canvas .el-features .sbc-features-shell > header > div:first-child{
  font-size:clamp(25px,8vw,31px)!important;
  line-height:1.12!important;
  word-break:normal!important;
  overflow-wrap:break-word!important;
  hyphens:none!important;
}
#previewFrame.mobile > .canvas .el-features .sbc-features-shell > header > div:last-child{
  font-size:15px!important;
  line-height:1.5!important;
  word-break:normal!important;
  overflow-wrap:break-word!important;
}
#previewFrame.mobile > .canvas .el-features .sbc-feature-card > div:nth-child(2){
  font-size:18px!important;
  line-height:1.25!important;
  word-break:normal!important;
  overflow-wrap:break-word!important;
}
#previewFrame.mobile > .canvas .el-features .sbc-feature-card > div:nth-child(3){
  font-size:14px!important;
  line-height:1.5!important;
  word-break:normal!important;
  overflow-wrap:break-word!important;
}

</style>
</head>
<body>
<div id="previewApp">
  <div id="previewToolbar">
    <div class="group"><span class="title">👁 Preview — Construtor Pro</span></div>
    <div class="group">
      <button data-mode="desktop" class="${mode==='desktop'?'active':''}">🖥 Desktop</button>
      <button data-mode="tablet" class="${mode==='tablet'?'active':''}">▣ Tablet</button>
      <button data-mode="mobile" class="${mode==='mobile'?'active':''}">📱 Mobile</button>
      <button class="close" id="closePreviewTab">Fechar aba</button>
    </div>
  </div>
  <div id="previewStage"><div id="previewFrame" class="${mode}">${html}</div></div>
</div>
<script>
(function(){
  const frame=document.getElementById('previewFrame');
  function applyMode(mode){
    frame.classList.remove('desktop','tablet','mobile');
    frame.classList.add(mode);
    const canvas=frame.querySelector(':scope > .canvas');
    if(!canvas) return;
    canvas.classList.remove('desktop','tablet','mobile','sbc-responsive-desktop','sbc-responsive-tablet','sbc-responsive-mobile');
    canvas.classList.add(mode,'sbc-responsive-'+mode);
    canvas.dataset.responsiveMode=mode;

    canvas.querySelectorAll('.columns-container').forEach(el=>{
      el.style.setProperty('width','100%','important');
      el.style.setProperty('max-width','100%','important');
      el.style.setProperty('min-width','0','important');
      el.style.setProperty('box-sizing','border-box','important');
      if(mode==='mobile'){
        el.style.setProperty('display','flex','important');
        el.style.setProperty('flex-direction','column','important');
        el.style.setProperty('flex-wrap','nowrap','important');
        el.style.setProperty('gap','0','important');
      }else if(mode==='tablet'){
        el.style.setProperty('display','grid','important');
        el.style.setProperty('grid-template-columns','repeat(2,minmax(0,1fr))','important');
        el.style.setProperty('gap','16px','important');
      }else{
        el.style.setProperty('display','flex','important');
        el.style.setProperty('flex-direction','row','important');
        el.style.setProperty('flex-wrap','wrap','important');
        el.style.setProperty('gap','15px','important');
      }
    });

    canvas.querySelectorAll('.el-column').forEach(el=>{
      el.style.setProperty('min-width','0','important');
      el.style.setProperty('max-width','100%','important');
      el.style.setProperty('box-sizing','border-box','important');
      if(mode==='mobile'){
        el.style.setProperty('width','100%','important');
        el.style.setProperty('flex','0 0 100%','important');
      }else if(mode==='tablet'){
        el.style.setProperty('width','auto','important');
        el.style.setProperty('flex','none','important');
      }else{
        el.style.setProperty('width','auto','important');
        el.style.setProperty('flex','1 1 0','important');
      }
    });

    canvas.querySelectorAll('.el-features,.el-features > .el-content,.el-features .sbc-features-shell').forEach(el=>{
      el.style.setProperty('display','block','important');
      el.style.setProperty('width','100%','important');
      el.style.setProperty('max-width','100%','important');
      el.style.setProperty('min-width','0','important');
      el.style.setProperty('box-sizing','border-box','important');
      el.style.setProperty('flex','0 0 auto','important');
    });
    canvas.querySelectorAll('.el-features .sbc-features-shell > header').forEach(el=>{
      el.style.setProperty('display','block','important');
      el.style.setProperty('width','100%','important');
      el.style.setProperty('max-width','100%','important');
      el.style.setProperty('min-width','0','important');
      el.style.setProperty('float','none','important');
      el.style.setProperty('clear','both','important');
    });

    const grids=canvas.querySelectorAll('.sbc-features-grid,.sbc-counters-grid,.sbc-pricing-grid,.sbc-team-grid,.sbc-gallery-grid,.sbc-testimonials-grid,.sbc-form-grid');
    grids.forEach(el=>{
      el.style.setProperty('width','100%','important');
      el.style.setProperty('max-width','100%','important');
      el.style.setProperty('min-width','0','important');
      el.style.setProperty('box-sizing','border-box','important');
      el.style.setProperty('display','grid','important');
      el.style.setProperty('grid-template-columns',mode==='mobile'?'minmax(0,1fr)':mode==='tablet'?'repeat(2,minmax(0,1fr))':'repeat(3,minmax(0,1fr))','important');
      el.style.setProperty('gap',mode==='mobile'?'12px':mode==='tablet'?'16px':'20px','important');
    });

    canvas.querySelectorAll('.sbc-feature-card,.sbc-counter-card,.sbc-pricing-card,.sbc-team-card,.sbc-gallery-item,.sbc-testimonial-card').forEach(el=>{
      el.style.setProperty('width','100%','important');
      el.style.setProperty('max-width','100%','important');
      el.style.setProperty('min-width','0','important');
      el.style.setProperty('box-sizing','border-box','important');
    });
  }
  function forceFeatureViewport(mode){
    const canvas=document.querySelector('#previewFrame > .canvas');
    if(!canvas) return;
    const features=canvas.querySelectorAll('.el-features');
    features.forEach(widget=>{
      const nodes=[widget, widget.querySelector('.el-content'), widget.querySelector('.sbc-features-shell'), widget.querySelector('.sbc-features-grid')].filter(Boolean);
      nodes.forEach(n=>{
        n.style.setProperty('width','100%','important');
        n.style.setProperty('max-width','none','important');
        n.style.setProperty('min-width','0','important');
        n.style.setProperty('box-sizing','border-box','important');
        n.style.setProperty('margin-left','0','important');
        n.style.setProperty('margin-right','0','important');
        n.style.setProperty('align-self','stretch','important');
      });
      const grid=widget.querySelector('.sbc-features-grid');
      if(grid){
        grid.style.setProperty('display','grid','important');
        grid.style.setProperty('grid-template-columns',mode==='mobile'?'minmax(0,1fr)':'repeat(2,minmax(0,1fr))','important');
        grid.style.setProperty('gap',mode==='mobile'?'14px':'clamp(16px,2vw,28px)','important');
      }
      widget.querySelectorAll('.sbc-feature-card').forEach(card=>{
        card.style.setProperty('width','100%','important');
        card.style.setProperty('max-width','none','important');
        card.style.setProperty('min-width','0','important');
        card.style.setProperty('box-sizing','border-box','important');
        card.style.setProperty('overflow-wrap','break-word','important');
        card.style.setProperty('word-break','normal','important');
        if(mode==='mobile') card.style.setProperty('padding','18px','important');
      });
    });
  }

  function forceViewportColumns(mode){
    const canvas=document.querySelector('#previewFrame > .canvas');
    if(!canvas) return;
    canvas.querySelectorAll('.columns-container').forEach(row=>{
      const featureCol=row.querySelector(':scope > .el-column .el-features');
      if(!featureCol) return;
      if(mode==='tablet'){
        row.style.setProperty('display','grid','important');
        row.style.setProperty('grid-template-columns','minmax(0,1fr)','important');
        const col=featureCol.closest('.el-column');
        if(col){
          col.style.setProperty('grid-column','1 / -1','important');
          col.style.setProperty('width','100%','important');
          col.style.setProperty('max-width','none','important');
        }
      }else if(mode==='mobile'){
        row.style.setProperty('display','flex','important');
        row.style.setProperty('flex-direction','column','important');
        row.style.setProperty('flex-wrap','nowrap','important');
        row.style.setProperty('width','100%','important');
        const col=featureCol.closest('.el-column');
        if(col){
          col.style.setProperty('width','100%','important');
          col.style.setProperty('max-width','100%','important');
          col.style.setProperty('flex','0 0 100%','important');
        }
      }
    });
  }

  document.querySelectorAll('#previewToolbar button[data-mode]').forEach(btn=>btn.addEventListener('click',()=>{
    applyMode(btn.dataset.mode);
    forceViewportColumns(btn.dataset.mode);
    forceFeatureViewport(btn.dataset.mode);
    requestAnimationFrame(()=>{ forceViewportColumns(btn.dataset.mode); forceFeatureViewport(btn.dataset.mode); });
    document.querySelectorAll('#previewToolbar button[data-mode]').forEach(b=>b.classList.toggle('active',b===btn));
  }));
  document.getElementById('closePreviewTab').addEventListener('click',()=>window.close());
  applyMode('${mode}');
  forceViewportColumns('${mode}');
  forceFeatureViewport('${mode}');
  requestAnimationFrame(()=>{ forceViewportColumns('${mode}'); forceFeatureViewport('${mode}'); });
  // =========================================================
  // CARROSSEL DO PREVIEW — o preview é uma nova aba, portanto
  // os onclicks do editor não podem ser usados aqui. Recriamos
  // os controles diretamente nesta aba.
  // =========================================================
  function initPreviewBanners(){
    document.querySelectorAll('.sbc-banner-ad').forEach(function(shell){
      var wraps=Array.from(shell.querySelectorAll('.sbc-banner-slide-wrap'));
      if(!wraps.length) return;
      var index=0;
      var transition=shell.dataset.bannerTransition||'fade';
      function go(next){
        if(!wraps.length) return;
        index=(Number(next)||0)%wraps.length; if(index<0) index=wraps.length-1;
        wraps.forEach(function(w,i){
          w.style.opacity=i===index?'1':'0';
          w.style.transform=transition==='slide' ? 'translateX('+(i===index?'0':'100%')+')' : 'translateX(0)';
          w.style.zIndex=i===index?'2':'1';
        });
      }
      go(0);
      if(shell.dataset.bannerAutoplay==='1' && wraps.length>1){
        var interval=Math.max(1000,parseFloat(shell.dataset.bannerInterval||5000)||5000);
        var timer=setInterval(function(){go(index+1);},interval);
        if(shell.dataset.bannerPause==='1'){
          shell.addEventListener('mouseenter',function(){clearInterval(timer);timer=null;});
          shell.addEventListener('mouseleave',function(){if(timer===null) timer=setInterval(function(){go(index+1);},interval);});
        }
      }
    });
  }
  initPreviewBanners();
  requestAnimationFrame(initPreviewBanners);

  function initPreviewCarousels(){
    document.querySelectorAll('.sbc-carousel-shell').forEach(shell=>{
      const track=shell.querySelector('.sbc-carousel-track');
      const viewport=shell.querySelector('.sbc-carousel-viewport');
      const slides=Array.from(shell.querySelectorAll('.sbc-carousel-slide'));
      if(!track || !viewport || slides.length<2) return;

      let index=0;
      const loop=shell.dataset.carouselLoop !== '0';
      const transition=(track.style.transitionDuration || '500ms');
      track.style.transition='transform ' + transition + ' ease';

      const visibleRaw=parseInt(getComputedStyle(track).getPropertyValue('--carousel-visible')||'1',10);
      const visible=Number.isFinite(visibleRaw)&&visibleRaw>0?visibleRaw:1;
      const maxIndex=Math.max(0,slides.length-visible);

      function go(next){
        let target=Number(next)||0;
        if(loop && slides.length>visible){
          if(target>maxIndex) target=0;
          if(target<0) target=maxIndex;
        }else{
          target=Math.max(0,Math.min(maxIndex,target));
        }
        index=target;
        const distance=viewport.clientWidth / visible;
        track.style.transform='translate3d(-' + (index*distance) + 'px,0,0)';
        shell.dataset.carouselIndex=String(index);
        const dots=Array.from(shell.querySelectorAll('.sbc-carousel-dots button'));
        dots.forEach((dot,i)=>dot.style.opacity=i===index?'1':'.55');
      }

      const buttons=Array.from(shell.querySelectorAll('button'));
      buttons.forEach(btn=>{
        const text=(btn.textContent||'').trim();
        if(text==='‹'){
          btn.onclick=null;
          btn.addEventListener('click',e=>{e.preventDefault();e.stopPropagation();go(index-1);});
        }else if(text==='›'){
          btn.onclick=null;
          btn.addEventListener('click',e=>{e.preventDefault();e.stopPropagation();go(index+1);});
        }
      });

      const dotButtons=Array.from(shell.querySelectorAll('.sbc-carousel-dots button'));
      dotButtons.forEach((dot,i)=>{
        dot.onclick=null;
        dot.addEventListener('click',e=>{e.preventDefault();e.stopPropagation();go(i);});
      });

      let startX=null;
      viewport.addEventListener('pointerdown',e=>{startX=e.clientX;});
      viewport.addEventListener('pointerup',e=>{
        if(startX===null) return;
        const dx=e.clientX-startX;
        startX=null;
        if(Math.abs(dx)>40) go(dx<0?index+1:index-1);
      });
      window.addEventListener('resize',()=>go(index));
      go(0);

      if(shell.dataset.carouselAutoplay==='1'){
        const interval=Math.max(1000,parseFloat(shell.dataset.carouselInterval||4000)||4000);
        let timer=setInterval(()=>go(index+1),interval);
        if(shell.dataset.carouselPause==='1'){
          shell.addEventListener('mouseenter',()=>{clearInterval(timer);timer=null;});
          shell.addEventListener('mouseleave',()=>{
            if(timer===null) timer=setInterval(()=>go(index+1),interval);
          });
        }
      }
    });
  }
  initPreviewCarousels();
  requestAnimationFrame(initPreviewCarousels);

})();
<\/script>

<style id="sbc-layout-engine-final-last">
/* ÚLTIMA CAMADA: deve vencer estilos antigos e estilos inline de layout. */
#canvas .widgets-container{display:flex!important;flex-direction:column!important;align-items:stretch!important;width:100%!important;max-width:100%!important;min-width:0!important;gap:10px!important;}
#canvas .widgets-container>[data-id]{display:block!important;width:100%!important;max-width:100%!important;min-width:0!important;flex:0 0 auto!important;align-self:stretch!important;box-sizing:border-box!important;}
#canvas .el-features,#canvas .el-testimonials,#canvas .el-pricing,#canvas .el-counters,#canvas .el-team,#canvas .el-gallery,#canvas .el-faq,#canvas .el-form,#canvas .el-timeline,#canvas .el-tabs,#canvas .el-accordion,#canvas .el-carousel,#canvas .el-newsletter,#canvas .el-hero,#canvas .el-navbar,#canvas .el-footer,#canvas .el-video,#canvas .el-heading,#canvas .el-text,#canvas .el-divider,#canvas .el-spacer,#canvas .el-icon{width:100%!important;max-width:100%!important;min-width:0!important;flex:0 0 auto!important;box-sizing:border-box!important;}
#canvas .el-image,#canvas .el-button{flex:0 0 auto!important;max-width:100%!important;min-width:0!important;}
#canvas.sbc-responsive-desktop .columns-container{display:flex!important;flex-direction:row!important;flex-wrap:wrap!important;align-items:stretch!important;width:100%!important;max-width:100%!important;min-width:0!important;gap:15px!important;}
#canvas.sbc-responsive-desktop .el-column{flex:1 1 0!important;width:auto!important;max-width:100%!important;min-width:0!important;}
#canvas.sbc-responsive-tablet .columns-container{display:grid!important;grid-template-columns:repeat(2,minmax(0,1fr))!important;gap:12px!important;width:100%!important;max-width:100%!important;min-width:0!important;}
#canvas.sbc-responsive-tablet .el-column{width:auto!important;max-width:100%!important;min-width:0!important;flex:none!important;}
#canvas.sbc-responsive-mobile .columns-container{display:flex!important;flex-direction:column!important;flex-wrap:nowrap!important;align-items:stretch!important;gap:0!important;width:100%!important;max-width:100%!important;min-width:0!important;}
#canvas.sbc-responsive-mobile .el-column{display:block!important;width:100%!important;max-width:100%!important;min-width:0!important;flex:0 0 100%!important;margin-left:0!important;margin-right:0!important;}
#canvas.sbc-responsive-mobile .widgets-container>[data-id]{width:100%!important;max-width:100%!important;min-width:0!important;flex:0 0 auto!important;}
#canvas.sbc-responsive-mobile .sbc-features-shell,#canvas.sbc-responsive-mobile .sbc-testimonials-shell,#canvas.sbc-responsive-mobile .sbc-pricing-shell,#canvas.sbc-responsive-mobile .sbc-counters-shell,#canvas.sbc-responsive-mobile .sbc-team-shell,#canvas.sbc-responsive-mobile .sbc-gallery-shell,#canvas.sbc-responsive-mobile .sbc-faq-shell,#canvas.sbc-responsive-mobile .sbc-form-shell,#canvas.sbc-responsive-mobile .sbc-timeline-shell,#canvas.sbc-responsive-mobile .sbc-tabs-shell,#canvas.sbc-responsive-mobile .sbc-accordion-shell,#canvas.sbc-responsive-mobile .sbc-carousel-shell{width:100%!important;max-width:100%!important;min-width:0!important;}
#canvas.sbc-responsive-mobile .sbc-features-grid,#canvas.sbc-responsive-mobile .sbc-counters-grid,#canvas.sbc-responsive-mobile .sbc-pricing-grid,#canvas.sbc-responsive-mobile .sbc-team-grid,#canvas.sbc-responsive-mobile .sbc-gallery-grid,#canvas.sbc-responsive-mobile .sbc-testimonials-grid,#canvas.sbc-responsive-mobile .sbc-form-grid{display:grid!important;grid-template-columns:1fr!important;width:100%!important;max-width:100%!important;min-width:0!important;}
#canvas.sbc-responsive-mobile .sbc-feature-card,#canvas.sbc-responsive-mobile .sbc-counter-card,#canvas.sbc-responsive-mobile .sbc-pricing-card,#canvas.sbc-responsive-mobile .sbc-team-card,#canvas.sbc-responsive-mobile .sbc-gallery-item,#canvas.sbc-responsive-mobile .sbc-testimonial-card{width:100%!important;max-width:100%!important;min-width:0!important;}
#canvas.sbc-responsive-mobile .sbc-features-shell>header,#canvas.sbc-responsive-mobile .sbc-testimonials-shell>header{display:block!important;width:100%!important;max-width:100%!important;text-align:center!important;}
/* Carrossel: largura/alinhamento configurados pelo próprio widget vencem
   as regras estruturais que deixam os demais widgets em 100%. */
#canvas .widgets-container > [data-id].el-carousel,
#previewFrame > .canvas .widgets-container > [data-id].el-carousel {
    box-sizing:border-box!important;
    min-width:0!important;
    max-width:100%!important;
    flex:0 0 auto!important;
}
#canvas .widgets-container > [data-id].el-carousel .sbc-carousel-shell,
#previewFrame > .canvas .widgets-container > [data-id].el-carousel .sbc-carousel-shell {
    min-width:0!important;
    max-width:100%!important;
    box-sizing:border-box!important;
}

</style>

<style id="sbc-carousel-responsive-width-final-fix">
/* Carrossel: a largura configurada no breakpoint selecionado deve vencer
   as regras estruturais de 100% usadas pelos demais widgets. */
#canvas .widgets-container > [data-id].el-carousel,
#previewFrame > .canvas .widgets-container > [data-id].el-carousel {
    min-width:0 !important;
    max-width:100% !important;
    box-sizing:border-box !important;
}
#canvas .el-carousel .sbc-carousel-shell,
#previewFrame > .canvas .el-carousel .sbc-carousel-shell {
    min-width:0 !important;
    max-width:100% !important;
    box-sizing:border-box !important;
}
</style>

<style id="sbc-responsive-final-viewport-fix">
/* =========================================================
   AJUSTE FINAL — TABLET TELA CHEIA / MOBILE REAL
   ========================================================= */

/* Tablet: o preview usa toda a largura da janela. */
html body #previewFrame.tablet,
html body #previewFrame.preview-tablet {
    width: 100vw !important;
    max-width: none !important;
    min-width: 0 !important;
    margin: 0 !important;
    box-sizing: border-box !important;
}
html body #previewFrame.tablet > .canvas,
html body #previewFrame.preview-tablet > .canvas {
    width: 100% !important;
    max-width: none !important;
    min-width: 0 !important;
    margin: 0 !important;
    padding-left: clamp(20px, 4vw, 56px) !important;
    padding-right: clamp(20px, 4vw, 56px) !important;
    box-sizing: border-box !important;
}

/* Mobile: mantém o formato de celular, mas usa uma única coluna real. */
html body #previewFrame.mobile,
html body #previewFrame.preview-mobile {
    width: min(390px, calc(100vw - 20px)) !important;
    max-width: 390px !important;
    min-width: 0 !important;
    margin: 0 auto !important;
    box-sizing: border-box !important;
}
html body #previewFrame.mobile > .canvas,
html body #previewFrame.preview-mobile > .canvas {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    margin: 0 !important;
    padding-left: 16px !important;
    padding-right: 16px !important;
    box-sizing: border-box !important;
}

/* O widget de benefícios deve ocupar a largura do viewport,
   sem deixar o título em uma coluna estreita. */
html body #previewFrame.tablet > .canvas .el-features .sbc-features-shell,
html body #previewFrame.mobile > .canvas .el-features .sbc-features-shell,
html body #previewFrame.preview-tablet > .canvas .el-features .sbc-features-shell,
html body #previewFrame.preview-mobile > .canvas .el-features .sbc-features-shell {
    display: block !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
}

html body #previewFrame.tablet > .canvas .el-features .sbc-features-shell > header,
html body #previewFrame.mobile > .canvas .el-features .sbc-features-shell > header,
html body #previewFrame.preview-tablet > .canvas .el-features .sbc-features-shell > header,
html body #previewFrame.preview-mobile > .canvas .el-features .sbc-features-shell > header {
    display: block !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    float: none !important;
    margin: 0 0 20px !important;
    text-align: center !important;
}

/* Tablet: cards aproveitam a tela em duas colunas. */
html body #previewFrame.tablet > .canvas .el-features .sbc-features-grid,
html body #previewFrame.preview-tablet > .canvas .el-features .sbc-features-grid {
    display: grid !important;
    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    gap: 16px !important;
}

/* Mobile: título primeiro e cada benefício abaixo do outro. */
html body #previewFrame.mobile > .canvas .el-features .sbc-features-grid,
html body #previewFrame.preview-mobile > .canvas .el-features .sbc-features-grid,
html body #previewFrame.mobile > .canvas .el-features .sbc-features-shell > div:not(header),
html body #previewFrame.preview-mobile > .canvas .el-features .sbc-features-shell > div:not(header) {
    display: grid !important;
    grid-template-columns: minmax(0, 1fr) !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    gap: 12px !important;
}

html body #previewFrame.mobile > .canvas .el-features .sbc-feature-card,
html body #previewFrame.preview-mobile > .canvas .el-features .sbc-feature-card {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    padding: 16px !important;
    box-sizing: border-box !important;
}

/* V4 FINAL — o preview deve obedecer ao viewport, não ao grid original do editor. */
#previewFrame.tablet > .canvas .columns-container:has(> .el-column .el-features),
#previewFrame.preview-tablet > .canvas .columns-container:has(> .el-column .el-features) {
  display:grid!important;
  grid-template-columns:minmax(0,1fr)!important;
  width:100%!important;
  max-width:none!important;
  gap:20px!important;
}
#previewFrame.tablet > .canvas .columns-container:has(> .el-column .el-features) > .el-column:has(.el-features),
#previewFrame.preview-tablet > .canvas .columns-container:has(> .el-column .el-features) > .el-column:has(.el-features) {
  grid-column:1 / -1!important;
  width:100%!important;
  max-width:none!important;
  min-width:0!important;
}

/* Mobile: o widget de benefícios ocupa uma coluna única de verdade. */
#previewFrame.mobile > .canvas .columns-container:has(> .el-column .el-features),
#previewFrame.preview-mobile > .canvas .columns-container:has(> .el-column .el-features) {
  display:flex!important;
  flex-direction:column!important;
  flex-wrap:nowrap!important;
  width:100%!important;
  max-width:100%!important;
  gap:0!important;
}
#previewFrame.mobile > .canvas .columns-container:has(> .el-column .el-features) > .el-column:has(.el-features),
#previewFrame.preview-mobile > .canvas .columns-container:has(> .el-column .el-features) > .el-column:has(.el-features) {
  width:100%!important;
  max-width:100%!important;
  min-width:0!important;
  flex:0 0 100%!important;
}
#previewFrame.mobile > .canvas .el-features .sbc-features-grid,
#previewFrame.preview-mobile > .canvas .el-features .sbc-features-grid {
  display:grid!important;
  grid-template-columns:minmax(0,1fr)!important;
  grid-auto-flow:row!important;
  width:100%!important;
  max-width:100%!important;
  min-width:0!important;
  gap:12px!important;
}
#previewFrame.mobile > .canvas .el-features .sbc-feature-card,
#previewFrame.preview-mobile > .canvas .el-features .sbc-feature-card {
  width:100%!important;
  max-width:100%!important;
  min-width:0!important;
  padding:16px!important;
  box-sizing:border-box!important;
  word-break:normal!important;
  overflow-wrap:break-word!important;
}

/* Mobile: fonte confortável, sem quebrar palavras em letras. */
html body #previewFrame.mobile > .canvas .el-features .sbc-features-shell > header > div:first-child,
html body #previewFrame.preview-mobile > .canvas .el-features .sbc-features-shell > header > div:first-child {
    font-size: clamp(24px, 7.2vw, 32px) !important;
    line-height: 1.08 !important;
    word-break: normal !important;
    overflow-wrap: normal !important;
    hyphens: none !important;
}
html body #previewFrame.mobile > .canvas .el-features .sbc-features-shell > header > div:last-child,
html body #previewFrame.preview-mobile > .canvas .el-features .sbc-features-shell > header > div:last-child {
    font-size: clamp(14px, 3.9vw, 16px) !important;
    line-height: 1.5 !important;
    word-break: normal !important;
    overflow-wrap: break-word !important;
}
</style>

<script>
(function(){
    function bindPreviewNavbar(){
        document.querySelectorAll('.sbc-navbar-shell').forEach(function(navShell){
            var navToggle = navShell.querySelector('.sbc-navbar-toggle');
            if (!navToggle || navToggle.dataset.previewBound === '1') return;
            navToggle.dataset.previewBound = '1';
            navToggle.addEventListener('click', function(ev){
                ev.preventDefault();
                ev.stopPropagation();
                var isOpen = navShell.classList.toggle('sbc-navbar-mobile-open');
                navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                navToggle.setAttribute('aria-label', isOpen ? 'Fechar menu' : 'Abrir menu');
                var icon = navToggle.querySelector('[data-navbar-icon]');
                var label = navToggle.querySelector('.sbc-navbar-toggle-label');
                if (icon) {
                    var chosen = navToggle.dataset.navbarIcon || 'bars';
                    icon.className = 'fas fa-' + (isOpen ? 'xmark' : chosen);
                    icon.setAttribute('data-navbar-icon', chosen);
                }
                if (label && navToggle.dataset.navbarButtonType === 'text') {
                    label.textContent = isOpen ? 'Fechar' : 'Menu';
                }
            });
        });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', bindPreviewNavbar);
    else bindPreviewNavbar();
})();
<\/script>

</body></html>`);
    tab.document.close();
    try { tab.focus(); } catch(e) {}
    return true;
}

window.__sbcOpenPreview = function(mode) { return openPreview(mode || 'desktop'); };
window.openPreview = openPreview;

function bindPreviewNavbarInteractions(root) {
    if (!root) return;
    root.querySelectorAll('.sbc-navbar-shell').forEach(navShell => {
        const navToggle = navShell.querySelector('.sbc-navbar-toggle');
        if (!navToggle || navToggle.dataset.previewBound === '1') return;
        navToggle.dataset.previewBound = '1';
        navToggle.addEventListener('click', ev => {
            ev.preventDefault();
            ev.stopPropagation();
            const isOpen = navShell.classList.toggle('sbc-navbar-mobile-open');
            navToggle.setAttribute('aria-label', isOpen ? 'Fechar menu' : 'Abrir menu');
            navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    });
}

function closePreview() {
    const overlay = document.getElementById('previewOverlay');
    if (!overlay) return;
    overlay.classList.remove('active');
    overlay.style.display = 'none';
    document.body.style.overflow = '';
}

function setPreviewDevice(mode, button) {
    previewDeviceMode = ['desktop','tablet','mobile'].includes(mode) ? mode : 'desktop';
    document.querySelectorAll('.preview-device-btn').forEach(btn => btn.classList.remove('active'));
    if (button) button.classList.add('active');
    // O Preview oficial é sempre uma nova aba.
    openPreview(previewDeviceMode);
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        const overlay = document.getElementById('previewOverlay');
        if (overlay?.classList.contains('active')) closePreview();
    }
});

function savePage() {
    saveData();

    if (PAGE_ID && PAGE_ID !== 'default') {
        fetch('/construtor-sites/api/save-elements', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                pageId: parseInt(PAGE_ID, 10),
                elements: [{
                    type: '_layout',
                    content: JSON.stringify(pageContent),
                    x: 0,
                    y: 0,
                    width: 100,
                    height: 100,
                    styles: {}
                }]
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showNotification('✅ Página salva no servidor!');
            } else {
                showNotification('Salvo localmente. Servidor: ' + (data.error || 'erro'), 'info');
            }
        })
        .catch(() => {
            showNotification('✅ Salvo localmente (servidor indisponível)', 'info');
        });
        return;
    }

    showNotification('✅ Página salva!');
}

function clearCanvas() {
    if (confirm('Limpar tudo?')) {
        saveState();
        pageContent = [];
        selectedElementId = null;
        renderCanvas();
        updateProperties(null);
        saveData();
        showNotification('Canvas limpo!');
    }
}

function closeModal(id) { document.getElementById(id).classList.remove('active'); }

function showNotification(message, type = 'success') {
    document.querySelectorAll('.notification').forEach(n => n.remove());
    const div = document.createElement('div');
    div.className = 'notification ' + type;
    div.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
    document.body.appendChild(div);
    setTimeout(() => div.remove(), 3000);
}

document.addEventListener('keydown', (e) => {
    if (e.ctrlKey && e.key === 's') { e.preventDefault(); savePage(); }
    if (e.ctrlKey && e.key === 'z') { e.preventDefault(); undo(); }
    if (e.ctrlKey && e.key === 'y') { e.preventDefault(); redo(); }
    if (e.key === 'Delete' && selectedElementId) { deleteElement(selectedElementId); }
    if (e.ctrlKey && e.key === 'd' && selectedElementId) { e.preventDefault(); duplicateElement(selectedElementId); }
});


/* Reaplica depois de qualquer renderização assíncrona do editor. */
window.addEventListener('load', () => setTimeout(applyResponsiveToMainCanvas, 50));
if (window.MutationObserver) {
    let sbcResponsiveTimer = null;
    const observer = new MutationObserver(() => {
        clearTimeout(sbcResponsiveTimer);
        sbcResponsiveTimer = setTimeout(applyResponsiveToMainCanvas, 20);
    });
    window.addEventListener('DOMContentLoaded', () => {
        const canvas = document.getElementById('canvas');
        if (canvas) observer.observe(canvas, { childList: true, subtree: true });
    });
}
</script>
<style id="sbc-cards-fluid-responsive-v1">
/* =========================================================
   CARDS FLUIDOS V1
   A responsividade considera a largura REAL do canvas/container,
   e não apenas o tamanho da janela do navegador.
   ========================================================= */
.canvas,
.preview-frame {
    container-type: inline-size !important;
    container-name: sbcviewport;
}

/* Nunca deixe um card ser menor que o conteúdo que ele precisa
   para apresentar texto de forma legível. */
.canvas .sbc-feature-card,
.canvas .sbc-counter-card,
.canvas .sbc-pricing-card,
.canvas .sbc-team-card,
.canvas .sbc-testimonial-card,
.canvas .sbc-gallery-item,
.preview-frame .sbc-feature-card,
.preview-frame .sbc-counter-card,
.preview-frame .sbc-pricing-card,
.preview-frame .sbc-team-card,
.preview-frame .sbc-testimonial-card,
.preview-frame .sbc-gallery-item {
    min-width: 0 !important;
    max-width: 100% !important;
    width: 100% !important;
    box-sizing: border-box !important;
}

/* Conteúdo interno acompanha a largura do card. */
.canvas .sbc-feature-card > *,
.canvas .sbc-counter-card > *,
.canvas .sbc-pricing-card > *,
.canvas .sbc-team-card > *,
.canvas .sbc-testimonial-card > *,
.canvas .sbc-gallery-item > *,
.preview-frame .sbc-feature-card > *,
.preview-frame .sbc-counter-card > *,
.preview-frame .sbc-pricing-card > *,
.preview-frame .sbc-team-card > *,
.preview-frame .sbc-testimonial-card > *,
.preview-frame .sbc-gallery-item > * {
    min-width: 0 !important;
    max-width: 100% !important;
    box-sizing: border-box !important;
}

/* O navegador pode usar break-word, mas nunca deve quebrar palavras
   comuns no meio quando existe espaço suficiente. */
.canvas .sbc-feature-card,
.canvas .sbc-counter-card,
.canvas .sbc-pricing-card,
.canvas .sbc-team-card,
.canvas .sbc-testimonial-card,
.preview-frame .sbc-feature-card,
.preview-frame .sbc-counter-card,
.preview-frame .sbc-pricing-card,
.preview-frame .sbc-team-card,
.preview-frame .sbc-testimonial-card {
    overflow-wrap: break-word !important;
    word-break: normal !important;
    hyphens: none !important;
}

/* Entre 601 e 900px de largura REAL: 2 cards por linha. */
@container sbcviewport (min-width: 601px) and (max-width: 900px) {
    .sbc-features-grid,
    .sbc-counters-grid,
    .sbc-pricing-grid,
    .sbc-team-grid,
    .sbc-gallery-grid,
    .sbc-testimonials-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
    }

    .sbc-features-grid,
    .sbc-counters-grid,
    .sbc-pricing-grid,
    .sbc-team-grid,
    .sbc-gallery-grid,
    .sbc-testimonials-grid {
        gap: clamp(12px, 2.2cqw, 20px) !important;
    }

    .sbc-feature-card,
    .sbc-counter-card,
    .sbc-pricing-card,
    .sbc-team-card,
    .sbc-testimonial-card,
    .sbc-gallery-item {
        padding-left: clamp(12px, 2.5cqw, 24px) !important;
        padding-right: clamp(12px, 2.5cqw, 24px) !important;
    }

    .sbc-feature-card > div:nth-child(2) {
        font-size: clamp(15px, 2.2cqw, 18px) !important;
        line-height: 1.3 !important;
    }

    .sbc-feature-card > div:nth-child(3) {
        font-size: clamp(12px, 1.8cqw, 14px) !important;
        line-height: 1.5 !important;
    }
}

/* Até 600px de largura REAL: um card por linha.
   Isso evita exatamente o esmagamento mostrado no editor. */
@container sbcviewport (max-width: 600px) {
    .sbc-features-grid,
    .sbc-counters-grid,
    .sbc-pricing-grid,
    .sbc-team-grid,
    .sbc-gallery-grid,
    .sbc-testimonials-grid,
    .sbc-form-grid {
        grid-template-columns: minmax(0, 1fr) !important;
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        gap: 12px !important;
    }

    .sbc-feature-card,
    .sbc-counter-card,
    .sbc-pricing-card,
    .sbc-team-card,
    .sbc-testimonial-card,
    .sbc-gallery-item {
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        padding: 14px !important;
    }

    .sbc-feature-card > div:nth-child(2) {
        font-size: clamp(16px, 4.8cqw, 20px) !important;
        line-height: 1.25 !important;
        white-space: normal !important;
        word-break: normal !important;
        overflow-wrap: break-word !important;
    }

    .sbc-feature-card > div:nth-child(3) {
        font-size: clamp(12px, 3.8cqw, 15px) !important;
        line-height: 1.5 !important;
        white-space: normal !important;
        word-break: normal !important;
        overflow-wrap: break-word !important;
    }

    .sbc-feature-icon-box {
        max-width: 100% !important;
    }
}

/* Em qualquer largura, imagens e ícones não podem empurrar o card. */
.sbc-feature-card img,
.sbc-feature-card svg,
.sbc-counter-card img,
.sbc-counter-card svg,
.sbc-pricing-card img,
.sbc-pricing-card svg,
.sbc-team-card img,
.sbc-team-card svg,
.sbc-testimonial-card img,
.sbc-testimonial-card svg,
.sbc-gallery-item img,
.sbc-gallery-item svg {
    max-width: 100% !important;
    box-sizing: border-box !important;
}
</style>



<style id="sbc-responsive-hard-fix">
/* HARD FIX — o widget nunca divide o cabeçalho com os cards.
   O viewport controla o layout, não o tamanho antigo salvo. */
.canvas .el-features,
.canvas .el-features > .el-content,
.preview-frame .el-features,
.preview-frame .el-features > .el-content {
  display:block!important;
  width:100%!important;
  max-width:100%!important;
  min-width:0!important;
  flex:0 0 auto!important;
  box-sizing:border-box!important;
}
.canvas .el-features .sbc-features-shell,
.preview-frame .el-features .sbc-features-shell {
  display:block!important;
  width:100%!important;
  max-width:100%!important;
  min-width:0!important;
  box-sizing:border-box!important;
}
.canvas .el-features .sbc-features-shell > header,
.preview-frame .el-features .sbc-features-shell > header {
  display:block!important;
  width:100%!important;
  max-width:100%!important;
  min-width:0!important;
  box-sizing:border-box!important;
  float:none!important;
  clear:both!important;
}
.canvas .el-features .sbc-features-grid,
.preview-frame .el-features .sbc-features-grid {
  display:grid!important;
  width:100%!important;
  max-width:100%!important;
  min-width:0!important;
  box-sizing:border-box!important;
}
.canvas.sbc-responsive-desktop .el-features .sbc-features-grid,
.preview-frame.preview-desktop .el-features .sbc-features-grid {
  grid-template-columns:repeat(3,minmax(0,1fr))!important;
  gap:20px!important;
}
.canvas.sbc-responsive-tablet .el-features .sbc-features-grid,
.preview-frame.preview-tablet .el-features .sbc-features-grid {
  grid-template-columns:repeat(2,minmax(0,1fr))!important;
  gap:16px!important;
}
.canvas.sbc-responsive-mobile .el-features .sbc-features-grid,
.preview-frame.preview-mobile .el-features .sbc-features-grid {
  grid-template-columns:minmax(0,1fr)!important;
  gap:12px!important;
}
.canvas.sbc-responsive-mobile .el-features .sbc-feature-card,
.preview-frame.preview-mobile .el-features .sbc-feature-card,
.canvas.sbc-responsive-tablet .el-features .sbc-feature-card,
.preview-frame.preview-tablet .el-features .sbc-feature-card {
  width:100%!important;
  max-width:100%!important;
  min-width:0!important;
  box-sizing:border-box!important;
  overflow-wrap:normal!important;
  word-break:normal!important;
}
.canvas.sbc-responsive-mobile .el-features .sbc-features-shell > header > div:first-child,
.preview-frame.preview-mobile .el-features .sbc-features-shell > header > div:first-child {
  font-size:clamp(22px,7vw,30px)!important;
  line-height:1.15!important;
}
.canvas.sbc-responsive-mobile .el-features .sbc-features-shell > header > div:last-child,
.preview-frame.preview-mobile .el-features .sbc-features-shell > header > div:last-child {
  font-size:clamp(14px,4vw,16px)!important;
  line-height:1.5!important;
}
.canvas.sbc-responsive-mobile .el-features .sbc-feature-card > div:nth-child(2),
.preview-frame.preview-mobile .el-features .sbc-feature-card > div:nth-child(2) {
  font-size:clamp(16px,4.5vw,19px)!important;
  line-height:1.25!important;
}
.canvas.sbc-responsive-mobile .el-features .sbc-feature-card > div:nth-child(3),
.preview-frame.preview-mobile .el-features .sbc-feature-card > div:nth-child(3) {
  font-size:clamp(13px,3.8vw,15px)!important;
  line-height:1.5!important;
}
</style>
<script id="sbc-drag-v6">
(function(){
  function init(){
    const sidebar=document.querySelector('.sidebar-content');
    const canvas=document.getElementById('canvas');
    if(!sidebar||!canvas||window.__sbcDragV6) return;
    window.__sbcDragV6=true; window.__sbcUseDragV6=true;

    // Desliga a implementação anterior para que só exista UM mecanismo de arraste.
    // Os widgets passam a usar pointer events + pointer capture.
    sidebar.querySelectorAll('.widget-item[ondragstart]').forEach(item=>{
      const m=(item.getAttribute('ondragstart')||'').match(/dragSidebarWidget\(event,\s*'([^']+)'\)/);
      if(!m) return;
      item.dataset.widgetType=m[1];
      item.removeAttribute('ondragstart');
      item.setAttribute('draggable','false');
      item.style.touchAction='none';
      item.style.userSelect='none';
    });

    let drag=null;
    let suppressClickUntil=0;

    function targetAt(x,y){
      const cols=[...canvas.querySelectorAll('.el-column[data-id]')].filter(el=>{
        const r=el.getBoundingClientRect();
        return r.width>0&&r.height>0&&x>=r.left&&x<=r.right&&y>=r.top&&y<=r.bottom;
      });
      if(cols.length){
        // Em colunas aninhadas, pega a menor área.
        cols.sort((a,b)=>{
          const ra=a.getBoundingClientRect(), rb=b.getBoundingClientRect();
          return ra.width*ra.height-rb.width*rb.height;
        });
        return cols[0].dataset.id;
      }
      const containers=[...canvas.querySelectorAll('.el-container[data-id]')].filter(el=>{
        const r=el.getBoundingClientRect();
        return r.width>0&&r.height>0&&x>=r.left&&x<=r.right&&y>=r.top&&y<=r.bottom;
      });
      containers.sort((a,b)=>{
        const ra=a.getBoundingClientRect(), rb=b.getBoundingClientRect();
        return ra.width*ra.height-rb.width*rb.height;
      });
      return containers[0]?.dataset.id||null;
    }

    function highlight(id){
      canvas.querySelectorAll('.sbc-v6-drop-target').forEach(e=>e.classList.remove('sbc-v6-drop-target'));
      if(!id) return;
      const el=[...canvas.querySelectorAll('[data-id]')].find(n=>n.dataset.id===id);
      if(el) el.classList.add('sbc-v6-drop-target');
    }

    function endDrag(e,cancel){
      if(!drag) return;
      const d=drag;
      drag=null;
      try{d.item.releasePointerCapture(d.pointerId)}catch(_){ }
      d.item.classList.remove('sbc-pointer-dragging');
      document.body.classList.remove('sbc-widget-pointer-dragging');
      highlight(null);
      if(cancel||!d.dragging) return;
      const id=targetAt(e.clientX,e.clientY)||d.targetId;
      if(id){
        addWidgetToContainer(d.type,id);
      }else if(d.type==='container'||d.type==='section'){
        addWidgetFromSidebar(d.type);
      }else{
        showNotification('Solte exatamente dentro da área tracejada da coluna ou container.','error');
      }
    }

    sidebar.addEventListener('pointerdown',e=>{
      if(e.button!==0) return;
      const item=e.target.closest('.widget-item[data-widget-type]');
      if(!item||!sidebar.contains(item)) return;
      drag={item,type:item.dataset.widgetType,pointerId:e.pointerId,startX:e.clientX,startY:e.clientY,dragging:false,targetId:null};
      try{item.setPointerCapture(e.pointerId)}catch(_){ }
      e.preventDefault();
    },true);

    sidebar.addEventListener('pointermove',e=>{
      if(!drag||e.pointerId!==drag.pointerId) return;
      if(!drag.dragging){
        if(Math.hypot(e.clientX-drag.startX,e.clientY-drag.startY)<5) return;
        drag.dragging=true;
        suppressClickUntil=Date.now()+800;
        drag.item.classList.add('sbc-pointer-dragging');
        document.body.classList.add('sbc-widget-pointer-dragging');
      }
      e.preventDefault();
      drag.targetId=targetAt(e.clientX,e.clientY);
      highlight(drag.targetId);
    },true);

    sidebar.addEventListener('pointerup',e=>{
      if(drag&&e.pointerId===drag.pointerId) endDrag(e,false);
    },true);
    sidebar.addEventListener('pointercancel',e=>{
      if(drag&&e.pointerId===drag.pointerId) endDrag(e,true);
    },true);

    // pointer capture faz os eventos continuarem no item da sidebar mesmo quando
    // o mouse atravessa o canvas. Este listener document é o que recebe o movimento.
    document.addEventListener('pointermove',e=>{
      if(!drag||e.pointerId!==drag.pointerId) return;
      if(!drag.dragging){
        if(Math.hypot(e.clientX-drag.startX,e.clientY-drag.startY)<5) return;
        drag.dragging=true;
        suppressClickUntil=Date.now()+800;
        drag.item.classList.add('sbc-pointer-dragging');
        document.body.classList.add('sbc-widget-pointer-dragging');
      }
      e.preventDefault();
      drag.targetId=targetAt(e.clientX,e.clientY);
      highlight(drag.targetId);
    },true);
    document.addEventListener('pointerup',e=>{
      if(drag&&e.pointerId===drag.pointerId) endDrag(e,false);
    },true);
    document.addEventListener('pointercancel',e=>{
      if(drag&&e.pointerId===drag.pointerId) endDrag(e,true);
    },true);

    // Clique simples continua inserindo no alvo selecionado.
    sidebar.addEventListener('click',e=>{
      const item=e.target.closest('.widget-item[data-widget-type]');
      if(!item||!sidebar.contains(item)||drag) return;
      if(Date.now()<suppressClickUntil) { e.preventDefault(); e.stopPropagation(); return; }
      if(e.detail>1) return;
      addWidgetToContainer(item.dataset.widgetType, normalizeDropTargetId(selectedInsertTargetId)||resolveSelectedInsertTarget());
    },false);
  }
  if(document.readyState==='loading') document.addEventListener('DOMContentLoaded',init); else init();
})();
</script>
<style id="sbc-drag-v6-css">
.sbc-v6-drop-target{outline:4px solid #22c55e!important;outline-offset:-4px!important;background:rgba(34,197,94,.10)!important;}
body.sbc-widget-pointer-dragging .el-column,.sbc-widget-pointer-dragging .el-container{cursor:grabbing!important;}
</style>

<style id="sbc-elementor-device-preview-final">
/* =========================================================
   DEVICE PREVIEW — comportamento visual inspirado no Elementor
   Desktop = tela normal | Tablet = 768px | Mobile = 390px
   Uma única coluna nunca fica espremida no Tablet.
   ========================================================= */

/* EDITOR */
#canvas.canvas.desktop{
    width:100%!important;
    max-width:none!important;
    margin:40px auto!important;
}
#canvas.canvas.tablet{
    width:768px!important;
    max-width:calc(100% - 32px)!important;
    min-width:0!important;
    margin:40px auto!important;
    padding-left:24px!important;
    padding-right:24px!important;
}
#canvas.canvas.mobile{
    width:390px!important;
    max-width:calc(100% - 24px)!important;
    min-width:0!important;
    margin:40px auto!important;
    padding-left:16px!important;
    padding-right:16px!important;
}

/* Tablet: 1 coluna ocupa 100%. Com 2 ou mais colunas, usa 2 colunas. */
#canvas.sbc-responsive-tablet .columns-container:not(:has(> .el-column:nth-child(2))),
#canvas.tablet .columns-container:not(:has(> .el-column:nth-child(2))){
    grid-template-columns:minmax(0,1fr)!important;
}
#canvas.sbc-responsive-tablet .columns-container:has(> .el-column:nth-child(2)),
#canvas.tablet .columns-container:has(> .el-column:nth-child(2)){
    grid-template-columns:repeat(2,minmax(0,1fr))!important;
}

/* PREVIEW EM NOVA ABA */
#previewFrame.desktop{
    width:min(1440px,calc(100vw - 48px))!important;
    max-width:calc(100vw - 48px)!important;
    min-width:0!important;
}
#previewFrame.tablet,
#previewFrame.preview-tablet{
    width:768px!important;
    max-width:calc(100vw - 48px)!important;
    min-width:0!important;
    margin:0 auto!important;
}
#previewFrame.mobile,
#previewFrame.preview-mobile{
    width:390px!important;
    max-width:calc(100vw - 24px)!important;
    min-width:0!important;
    margin:0 auto!important;
}

/* O canvas interno acompanha exatamente a largura do dispositivo. */
#previewFrame>.canvas{
    width:100%!important;
    max-width:100%!important;
    min-width:0!important;
    margin:0!important;
}

/* Tablet com uma única coluna = largura total do tablet. */
#previewFrame.tablet>.canvas .columns-container:not(:has(> .el-column:nth-child(2))),
#previewFrame.preview-tablet>.canvas .columns-container:not(:has(> .el-column:nth-child(2))){
    grid-template-columns:minmax(0,1fr)!important;
}
#previewFrame.tablet>.canvas .columns-container:has(> .el-column:nth-child(2)),
#previewFrame.preview-tablet>.canvas .columns-container:has(> .el-column:nth-child(2)){
    grid-template-columns:repeat(2,minmax(0,1fr))!important;
}

/* Mobile sempre é uma coluna real. */
#previewFrame.mobile>.canvas .columns-container,
#previewFrame.preview-mobile>.canvas .columns-container{
    display:flex!important;
    flex-direction:column!important;
    flex-wrap:nowrap!important;
    width:100%!important;
    max-width:100%!important;
}
#previewFrame.mobile>.canvas .el-column,
#previewFrame.preview-mobile>.canvas .el-column{
    width:100%!important;
    max-width:100%!important;
    min-width:0!important;
    flex:0 0 100%!important;
}

/* Nada cria uma página horizontal falsa dentro do dispositivo. */
#previewFrame,
#previewFrame>.canvas,
#previewFrame>.canvas .el-section,
#previewFrame>.canvas .el-column,
#previewFrame>.canvas .el-container,
#previewFrame>.canvas .widgets-container{
    box-sizing:border-box!important;
    min-width:0!important;
}
</style>

</body>
</html>