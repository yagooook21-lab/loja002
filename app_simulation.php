<?php if(isset($app_mobile_ativo) && $app_mobile_ativo == "1"): ?>
<style>
/* =========================================
   APP SIMULATION CSS (MOBILE ONLY)
   ========================================= */
@media (max-width: 767px) {
  .download-app-bottom-banner-opacity {
    position: fixed;
    inset: 0;
    background-color: rgba(0, 0, 0, 0.6);
    z-index: 10000;
    animation: fadeIn 0.3s ease-out;
  }
  
  .download-app-bottom-banner-wrapper {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: #fff;
    z-index: 200000;
    padding: 24px 16px 16px;
    border-radius: 12px 12px 0 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0 !important;
    animation: slideUpBanner 0.4s cubic-bezier(0.32, 1, 0.23, 1);
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
    color: #333;
  }
  
  @keyframes slideUpBanner {
    from { transform: translateY(100%); }
    to { transform: translateY(0); }
  }
  
  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }
  
  .download-app-bottom-banner-row {
    display: flex;
    align-items: center;
    gap: 16px;
    width: 100%;
  }
  
  .download-app-bottom-banner-icon {
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  }
  
  .download-app-bottom-banner-header {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }
  
  .download-app-bottom-banner-rating-container {
    display: flex;
    align-items: center;
    gap: 8px;
  }
  
  .download-app-bottom-banner-rating-row {
    display: flex;
    align-items: center;
    gap: 2px;
  }
  
  .download-app-bottom-banner-rating {
    font-size: 14px;
    font-weight: 600;
    color: #333;
  }
  
  .download-app-bottom-banner-rating-icon {
    display: inline-block;
    width: 14px;
    height: 14px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%233483fa'%3E%3Cpath d='M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z'/%3E%3C/svg%3E");
    background-size: contain;
    background-repeat: no-repeat;
  }
  
  .download-app-bottom-banner-comments {
    font-size: 12px;
    color: #999;
  }
  
  .download-app-bottom-banner-title {
    font-size: 18px;
    font-weight: 600;
    line-height: 1.25;
    text-align: left;
    width: 100%;
    margin: 16px 0;
  }
  
  .download-app-bottom-banner-button {
    width: 100%;
    background-color: #3483fa;
    color: #fff;
    text-align: center;
    padding: 14px;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    transition: background-color 0.2s;
    border: none;
    cursor: pointer;
  }
  
  .download-app-bottom-banner-button:hover {
    background-color: #2968c8;
  }
  
  .download-app-bottom-banner-link {
    background: none;
    border: none;
    color: #3483fa;
    font-size: 14px;
    font-weight: 600;
    padding: 16px 8px 8px;
    cursor: pointer;
    text-align: center;
    width: 100%;
  }

  /* Transition Layer */
  #spa-transition-layer {
    position: fixed;
    inset: 0;
    z-index: 999999;
    background-color: #ffe600;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    transition: opacity 0.5s ease;
  }

  .spa-loader-spinner {
    width: 48px;
    height: 48px;
    border: 4px solid transparent;
    border-top: 4px solid #3483fa;
    border-radius: 50%;
    animation: store-loader-spin 0.8s linear infinite;
  }

  @keyframes store-loader-spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }

  /* SPA active classes */
  body.spa-active a.nav-logo { display: none !important; }
  body.spa-active button.nav-header-menu-switch { display: none !important; }
  body.spa-active span.nav-menu-link-cp { display: none !important; }
  body.spa-active .nav-header:before { box-shadow: none !important; }

  /* Bottom Nav */
  .spa-bottom-nav {
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      background: #ffffff;
      border-top: 1px solid #e5e5e5;
      z-index: 150000;
      padding-bottom: env(safe-area-inset-bottom);
      height: calc(60px + env(safe-area-inset-bottom));
      display: none; /* hidden by default, shown when spa is active */
      align-items: center;
      justify-content: center;
  }
  body.spa-active .spa-bottom-nav {
      display: flex;
  }

  .spa-bottom-nav-container {
      display: flex;
      width: 100%;
      height: 100%;
      justify-content: space-around;
      align-items: center;
      position: relative;
  }

  .spa-bottom-nav-item {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 4px;
      color: #666666;
      cursor: pointer;
      transition: all 0.2s ease;
      padding: 8px 0;
      position: relative;
  }

  .spa-bottom-nav-item.active {
      color: #3483fa;
  }

  .spa-bottom-nav-item.active::after {
      content: '';
      position: absolute;
      top: -9px;
      left: 0;
      width: 100%;
      height: 3px;
      background: #3483fa;
  }

  .spa-bottom-nav-icon {
      width: 24px;
      height: 24px;
  }

  .spa-bottom-nav-label {
      font-size: 11px;
      font-weight: 500;
      letter-spacing: -0.2px;
  }

  .spa-bottom-nav-item.center-item {
      position: relative;
      overflow: visible;
  }

  .spa-bottom-nav-item.center-item .spa-bottom-nav-icon-wrapper {
      position: absolute;
      bottom: 25px;
      background: #ffffff;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 1px solid #e5e5e5;
      box-shadow: 0 -4px 10px rgba(0,0,0,0.05);
  }

  .spa-bottom-nav-item.center-item .spa-bottom-nav-icon {
      color: #666666;
      width: 28px;
      height: 28px;
  }
}

/* DESKTOP: Esconde TUDO da simulação de app */
@media (min-width: 768px) {
  #app-simulation-container,
  #spa-transition-layer,
  .spa-bottom-nav,
  .download-app-bottom-banner-opacity,
  .download-app-bottom-banner-wrapper {
      display: none !important;
  }
  body.spa-active a.nav-logo { display: flex !important; }
  body.spa-active button.nav-header-menu-switch { display: flex !important; }
  body.spa-active span.nav-menu-link-cp { display: inline !important; }
}
</style>

<!-- HTML DO BANNER E POPUP -->
<div id="app-simulation-container" style="display:none;">
    <!-- Fundo Escuro -->
    <div class="download-app-bottom-banner-opacity" id="app-simulation-overlay"></div>
    
    <!-- Banner de Download -->
    <div class="download-app-bottom-banner-wrapper" id="app-simulation-banner">
      <div class="download-app-bottom-banner-row">
        <img 
          src="https://http2.mlstatic.com/frontend-assets/ml-web-navigation/ui-navigation/7.23.0/mercadolibre/modal-logo-120x120.jpg" 
          alt="App Logo" 
          width="73" 
          height="73" 
          class="download-app-bottom-banner-icon"
        />
        <div class="download-app-bottom-banner-header">
          <div class="download-app-bottom-banner-rating-container">
            <span style="position:absolute;clip:rect(1px,1px,1px,1px);padding:0;border:0;height:1px;width:1px;overflow:hidden;"> Qualificação [4.8] de 5</span>
            <div class="download-app-bottom-banner-rating-row" aria-hidden="true">
              <span class="download-app-bottom-banner-rating">4.8</span>
              <i class="download-app-bottom-banner-rating-icon"></i>
            </div>
          </div>
          <span class="download-app-bottom-banner-comments">+300 k opiniões</span>
        </div>
      </div>
      <span class="download-app-bottom-banner-title">Deseja abrir o aplicativo do mercado livre?</span>
      <button 
        id="download-app-bottom-banner-download" 
        class="download-app-bottom-banner-button"
      >
        Abrir app do Mercado Livre
      </button>
      <button 
        id="download-app-bottom-banner-close" 
        class="download-app-bottom-banner-link"
      >
        Agora não
      </button>
    </div>
</div>

<!-- Video Feed Modal (TikTok style) -->
<div id="video-feed-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; z-index:999999; background:#000; flex-direction:column; color:#fff;">
    <div id="video-feed-close" style="position:absolute; top:16px; right:16px; z-index:10; background:rgba(0,0,0,0.5); border-radius:50%; width:36px; height:36px; display:flex; align-items:center; justify-content:center; cursor:pointer;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
    </div>
    
    <div id="video-feed-container" style="flex:1; width:100%; height:100%; overflow-y:auto; scroll-snap-type: y mandatory; scrollbar-width: none;">
        <!-- Videos will be injected here -->
        <div id="video-feed-loader" style="width:100%; height:100%; display:flex; align-items:center; justify-content:center;">Carregando vídeos...</div>
    </div>
    
    <style>
        #video-feed-container::-webkit-scrollbar { display: none; }
        .video-feed-slide {
            width: 100%; height: 100%; scroll-snap-align: start; position: relative; display: flex; align-items: center; justify-content: center; background: #000; overflow: hidden;
        }
        .video-feed-overlay {
            position: absolute; bottom: 0; left: 0; right: 0; padding: 16px; padding-bottom: 24px;
            background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
            display: flex; flex-direction: column; gap: 8px; z-index: 5; pointer-events: auto;
        }
        .video-feed-title { font-size: 14px; font-weight: 600; text-shadow: 1px 1px 3px rgba(0,0,0,0.8); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .video-feed-price { font-size: 20px; font-weight: bold; text-shadow: 1px 1px 3px rgba(0,0,0,0.8); }
        .video-feed-btn {
            background: #3483fa; color: white; padding: 12px; border-radius: 6px; font-weight: 600; text-align: center; border: none; outline: none; margin-top: 8px; font-size: 16px; text-decoration: none;
        }
    </style>
</div>

<!-- Camada de Transição de Tela Cheia -->
<div id="spa-transition-layer" style="display:none;">
    <div id="spa-loading-spinner" class="spa-loader-spinner"></div>
    <img id="spa-logo" src="arquivos/logo/free-mercado-livre-icon-svg-download-png-14549372.webp" width="100" style="display:none;" />
</div>

<!-- Barra de Navegação SPA -->
<nav class="spa-bottom-nav">
  <div class="spa-bottom-nav-container">
    <div class="spa-bottom-nav-item active" onclick="window.scrollTo({top:0, behavior:'smooth'})">
        <svg class="spa-bottom-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
        <span class="spa-bottom-nav-label">Início</span>
    </div>
    <div class="spa-bottom-nav-item" onclick="window.location.href='catalogo.php'">
        <svg class="spa-bottom-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="7" x="3" y="3" rx="1"></rect><rect width="7" height="7" x="14" y="3" rx="1"></rect><rect width="7" height="7" x="14" y="14" rx="1"></rect><rect width="7" height="7" x="3" y="14" rx="1"></rect></svg>
        <span class="spa-bottom-nav-label">Categorias</span>
    </div>
    <div class="spa-bottom-nav-item center-item">
        <div class="spa-bottom-nav-icon-wrapper">
            <svg class="spa-bottom-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"></circle><circle cx="19" cy="21" r="1"></circle><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path></svg>
        </div>
        <span class="spa-bottom-nav-label">Carrinho</span>
    </div>
    <div class="spa-bottom-nav-item" id="btn-open-video-feed">
        <svg class="spa-bottom-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polygon points="10 8 16 12 10 16 10 8"></polygon></svg>
        <span class="spa-bottom-nav-label">Vídeos</span>
    </div>
    <div class="spa-bottom-nav-item">
        <svg class="spa-bottom-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"></line><line x1="4" x2="20" y1="6" y2="6"></line><line x1="4" x2="20" y1="18" y2="18"></line></svg>
        <span class="spa-bottom-nav-label">Mais</span>
    </div>
  </div>
</nav>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
    
    if (isMobile) {
        var container = document.getElementById('app-simulation-container');
        var banner = document.getElementById('app-simulation-banner');
        var overlay = document.getElementById('app-simulation-overlay');
        var btnDownload = document.getElementById('download-app-bottom-banner-download');
        var btnClose = document.getElementById('download-app-bottom-banner-close');
        
        var transitionLayer = document.getElementById('spa-transition-layer');
        var spinner = document.getElementById('spa-loading-spinner');
        var logo = document.getElementById('spa-logo');
        
        var isAppActive = sessionStorage.getItem('spa_active') === 'true' || window.matchMedia('(display-mode: standalone)').matches;
        
        if (isAppActive) {
            document.body.classList.add('spa-active');
            if (container) container.style.display = 'none';
        } else {
            // Show banner after 500ms only if not active
            setTimeout(function() {
                if (container) container.style.display = 'block';
            }, 500);
        }

        var closeBanner = function() {
            container.style.display = 'none';
        };

        overlay.addEventListener('click', closeBanner);
        btnClose.addEventListener('click', closeBanner);

        btnDownload.addEventListener('click', function() {
            // Close banner
            container.style.display = 'none';
            
            // Grava no sessionStorage para não mostrar em outras páginas na mesma aba
            sessionStorage.setItem('spa_active', 'true');
            
            // Ativa o body como SPA (o nav-bottom já vai aparecer com o CSS body.spa-active)
            document.body.classList.add('spa-active');
            
            // Request full screen se possível
            try {
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen();
                } else if (document.documentElement.webkitRequestFullscreen) {
                    document.documentElement.webkitRequestFullscreen();
                }
            } catch(e) {}

            // Sequence de transição
            transitionLayer.style.display = 'flex';
            
            setTimeout(function() {
                spinner.style.display = 'none';
                logo.style.display = 'block';
                
                setTimeout(function() {
                    transitionLayer.style.opacity = '0';
                    setTimeout(function() {
                        transitionLayer.style.display = 'none';
                        // Pronto, app está "aberto"
                    }, 300);
                }, 500);
            }, 600);
        });
        
        // --- Video Feed Logic ---
        var btnOpenFeed = document.getElementById('btn-open-video-feed');
        var modalFeed = document.getElementById('video-feed-modal');
        var btnCloseFeed = document.getElementById('video-feed-close');
        var feedContainer = document.getElementById('video-feed-container');
        var isFeedLoaded = false;
        
        btnCloseFeed.addEventListener('click', function() {
            modalFeed.style.display = 'none';
            // Pause all videos when modal closes
            var videos = feedContainer.querySelectorAll('video');
            videos.forEach(function(v) { v.pause(); });
            
            // Remove youtube iframes completely to stop sound, and we can rebuild them later if needed
            // OR just postMessage to pause, but removing src is easier for simple implementation
        });
        
        btnOpenFeed.addEventListener('click', function() {
            modalFeed.style.display = 'flex';
            
            if (!isFeedLoaded) {
                fetch('api/get_videos.php')
                .then(r => r.json())
                .then(videos => {
                    isFeedLoaded = true;
                    if (!videos || videos.length === 0) {
                        feedContainer.innerHTML = '<div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center;">Nenhum vídeo encontrado.</div>';
                        return;
                    }
                    
                    var html = '';
                    videos.forEach(function(v) {
                        var formatPrice = parseFloat(v.valor).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                        var formatOriginal = '';
                        var hasDiscount = false;
                        var discountTag = '';
                        
                        if (v.valor_original && v.valor_original > v.valor) {
                            hasDiscount = true;
                            formatOriginal = parseFloat(v.valor_original).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            if (v.desconto) {
                                var descNum = String(v.desconto).replace('%', '');
                                discountTag = '<span style="background: #00a650; color: #fff; padding: 2px 6px; border-radius: 4px; font-size: 12px; font-weight: 600; margin-right: 5px;">' + descNum + '% OFF</span>';
                            }
                        }
                        
                        html += '<div class="video-feed-slide">';
                        
                        if (v.video_type === 'youtube') {
                            html += '<iframe src="' + v.video_url + '?autoplay=1&mute=0&controls=0&modestbranding=1&loop=1" style="width:150%; height:150%; object-fit:cover; pointer-events:none;" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
                        } else {
                            html += '<video src="' + v.video_url + '" style="width:100%; height:100%; object-fit:contain;" loop playsinline controls="false"></video>';
                        }
                        
                        html += '<div class="video-feed-overlay">';
                        html += '<div class="video-feed-title">' + v.nome + '</div>';
                        if (hasDiscount) {
                            html += '<div style="display:flex; align-items:center; margin-bottom: 2px;">' + discountTag + '<s style="color:#999; font-size:14px;">R$ ' + formatOriginal + '</s></div>';
                        }
                        html += '<div class="video-feed-price">R$ ' + formatPrice + ' <span style="font-size:14px; font-weight:normal; color:#00a650; display:inline-block; margin-left:4px;">no Pix <i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i></span></div>';
                        html += '<a href="produto.php?codigo=' + v.codigo + '" class="video-feed-btn">Comprar Agora</a>';
                        html += '</div></div>';
                    });
                    
                    feedContainer.innerHTML = html;
                    
                    // Simple Intersection Observer to play/pause native videos
                    var observer = new IntersectionObserver(function(entries) {
                        entries.forEach(function(entry) {
                            var vid = entry.target.querySelector('video');
                            if (vid) {
                                if (entry.isIntersecting) {
                                    vid.play().catch(function(e){});
                                } else {
                                    vid.pause();
                                }
                            }
                        });
                    }, { threshold: 0.6 });
                    
                    var slides = feedContainer.querySelectorAll('.video-feed-slide');
                    slides.forEach(function(slide) { observer.observe(slide); });
                })
                .catch(function(e) {
                    feedContainer.innerHTML = '<div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center;">Erro ao carregar os vídeos.</div>';
                });
            } else {
                // se já carregou, a pessoa fechou e abriu de novo, talvez a gente precise dar play no vídeo visível
                // Apenas deixamos o scroll snap lidar ou a intersection observer atuar se estivermos mudando o display
            }
        });
    }
});
</script>
<?php endif; ?>
