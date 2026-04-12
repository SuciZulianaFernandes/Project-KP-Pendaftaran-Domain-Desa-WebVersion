<style>
:root{--inv-accent:#f59e0b;--inv-border:#e2e8f0;--inv-text:#64748b;--inv-red:#ef4444;--inv-red-bg:#fef2f2;--inv-green:#10b981;--inv-green-bg:#ecfdf5;--inv-blue:#3b82f6;--inv-blue-bg:#eff6ff;--inv-gray-bg:#f8fafc}
.inv-card{background:#fff;border-radius:14px;border:1px solid var(--inv-border);overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.04)}
.inv-toolbar{padding:18px 22px;display:flex;align-items:center;gap:12px;border-bottom:1px solid var(--inv-border);flex-wrap:wrap}
.inv-search{flex:1;min-width:200px;max-width:400px;position:relative}
.inv-search i{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:13px;pointer-events:none}
.inv-search input{width:100%;padding:9px 14px 9px 38px;border:1px solid var(--inv-border);border-radius:9px;font-size:14px;font-family:inherit;background:var(--inv-gray-bg);outline:none;transition:.2s}
.inv-search input:focus{border-color:var(--inv-accent);background:#fff;box-shadow:0 0 0 3px rgba(245,158,11,.1)}
.inv-btn-f{display:inline-flex;align-items:center;gap:7px;padding:9px 16px;background:var(--inv-gray-bg);color:var(--inv-text);font-size:14px;font-weight:500;border:1px solid var(--inv-border);border-radius:9px;cursor:pointer;font-family:inherit;transition:.2s}
.inv-btn-f:hover{background:#f1f5f9;color:#1e293b}
.inv-table{width:100%;border-collapse:collapse}
.inv-table thead{background:var(--inv-gray-bg)}
.inv-table thead th{padding:13px 22px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--inv-text);border-bottom:1px solid var(--inv-border)}
.inv-table tbody tr{transition:background .15s}
.inv-table tbody tr:hover{background:#fafbfc}
.inv-table tbody tr:not(:last-child) td{border-bottom:1px solid #f1f5f9}
.inv-table tbody td{padding:14px 22px;font-size:14px;vertical-align:middle}
.inv-id{font-weight:700}.inv-date{color:var(--inv-text);font-size:13px}.inv-amount{font-weight:700}
.inv-badge{display:inline-flex;align-items:center;gap:5px;padding:4px 13px;border-radius:20px;font-size:12px;font-weight:600}
.inv-badge .d{width:7px;height:7px;border-radius:50%}
.badge-red{background:var(--inv-red-bg);color:#991b1b}.badge-red .d{background:var(--inv-red)}
.badge-green{background:var(--inv-green-bg);color:#065f46}.badge-green .d{background:var(--inv-green)}
.badge-blue{background:var(--inv-blue-bg);color:#1e40af}.badge-blue .d{background:var(--inv-blue)}
.inv-btn-d{display:inline-flex;align-items:center;gap:5px;padding:6px 14px;background:transparent;color:var(--inv-accent);font-size:13px;font-weight:600;border:1px solid var(--inv-accent);border-radius:8px;cursor:pointer;text-decoration:none;font-family:inherit;transition:.2s}
.inv-btn-d:hover{background:var(--inv-accent);color:#fff;box-shadow:0 3px 8px rgba(245,158,11,.25);text-decoration:none}
.inv-footer{padding:14px 22px;display:flex;align-items:center;justify-content:space-between;border-top:1px solid var(--inv-border);background:#fafbfc;flex-wrap:wrap;gap:10px}
.inv-footer span{font-size:13px;color:var(--inv-text)}
.inv-pg{display:flex;gap:5px}
.inv-pg button{width:34px;height:34px;border-radius:8px;border:1px solid var(--inv-border);background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--inv-text);font-size:13px;font-weight:600;font-family:inherit;transition:.2s}
.inv-pg button:hover:not(.active):not(:disabled){background:#f1f5f9;color:#1e293b}
.inv-pg button.active{background:var(--inv-accent);border-color:var(--inv-accent);color:#fff;box-shadow:0 2px 6px rgba(245,158,11,.3)}
.inv-pg button:disabled{opacity:.4;cursor:not-allowed}
.inv-empty td{text-align:center;padding:44px 22px!important;color:var(--inv-text)!important}
.inv-empty td i{display:block;font-size:36px;color:#cbd5e1;margin-bottom:10px}
.inv-alert{border-radius:10px;border:none;font-size:14px;font-weight:500;padding:13px 18px;margin-bottom:0}
.inv-alert-success{background:var(--inv-green-bg);color:#065f46}
.inv-alert-danger{background:var(--inv-red-bg);color:#991b1b}
@keyframes invRow{from{opacity:0;transform:translateY(5px)}to{opacity:1;transform:translateY(0)}}
.inv-table tbody tr{animation:invRow .25s ease forwards;opacity:0}
.inv-btn-f{appearance:auto}
</style>
