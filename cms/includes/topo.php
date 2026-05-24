<header class="header-global" style="display: flex; justify-content: space-between; align-items: center; padding: 20px 40px; background: #0f172a; border-bottom: 1px solid #1e293b;">
    <a href="../admin/index.php" style="color: #94a3b8; text-decoration: none; font-weight: 500; display: flex; align-items: center; gap: 8px;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg>
        Início
    </a>

    <div class="user-badge" style="display: flex; align-items: center; gap: 12px;">
        
        <div class="user-avatar" style="width: 36px; height: 36px; border-radius: 50%; overflow: hidden; background: #2563eb; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 14px;">
    <?php if (!empty($_SESSION['usuario_foto'])): ?>
        <img src="/cms/upload/<?= htmlspecialchars($_SESSION['usuario_foto']); ?>" 
             style="width: 100%; height: 100%; object-fit: cover;">
    <?php else: ?>
        <?= isset($_SESSION["usuario_nome"]) ? strtoupper(substr($_SESSION["usuario_nome"], 0, 1)) : 'U'; ?>
    <?php endif; ?>
</div>

        <a href="../admin/perfil.php?u=<?= urlencode($_SESSION['usuario_username'] ?? ''); ?>" class="btn-perfil" style="text-decoration: none; color: #f8fafc;">
            <span>Olá, <strong><?= htmlspecialchars($_SESSION["usuario_nome"] ?? 'Visitante'); ?></strong></span>
        </a>
        
        <span class="divider" style="color: #475569;">•</span>
        
        <a href="../processes/logout.php" class="btn-logout" title="Sair do sistema" style="text-decoration: none; color: #94a3b8; display: flex; align-items: center; gap: 6px;">
            <span>Sair</span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
        </a>
    </div>
</header>