<aside class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered bg-white  ">
    <div class="navbar-vertical-container">
      <div class="navbar-vertical-footer-offset">
        <!-- Logo -->

        <a class="navbar-brand" href="/admin" aria-label="">
          <img class="navbar-brand-logo" src="/images/logotype-dark.svg" alt="Logo" data-hs-theme-appearance="default">
          <img class="navbar-brand-logo" src="/images/logotype-dark.svg" alt="Logo" data-hs-theme-appearance="dark">
          <img class="navbar-brand-logo-mini" src="/images/logotype-dark.svg" alt="Logo" data-hs-theme-appearance="default">
          <img class="navbar-brand-logo-mini" src="/images/logotype-dark.svg" alt="Logo" data-hs-theme-appearance="dark">
        </a>

        <!-- End Logo -->

        <!-- Navbar Vertical Toggle -->
        <button type="button" class="js-navbar-vertical-aside-toggle-invoker navbar-aside-toggler">
          <i class="bi-arrow-bar-left navbar-toggler-short-align" data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>' data-bs-toggle="tooltip" data-bs-placement="right" title="Collapse"></i>
          <i class="bi-arrow-bar-right navbar-toggler-full-align" data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>' data-bs-toggle="tooltip" data-bs-placement="right" title="Expand"></i>
        </button>

        <!-- End Navbar Vertical Toggle -->

        <!-- Content -->
        <div class="navbar-vertical-content">
          <div id="navbarVerticalMenu" class="nav nav-pills nav-vertical card-navbar-nav">
            <!-- Collapse -->
            @if(Auth::user()->admin == 1)
            <div class="nav-item">
                <a class="nav-link " href="/admin/" data-placement="left">
                  <i class="bi-house-door nav-icon"></i>
                  <span class="nav-link-title">Главная</span>
                </a>
              </div>
            
            <div class="nav-item">
                <a class="nav-link " href="/admin/users" data-placement="left">
                  <i class="bi bi-people nav-icon"></i>
                  <span class="nav-link-title">Пользователи</span>
                </a>
              </div>
            @endif

               
            <span class="dropdown-header mt-4">Поддержка</span>
            <small class="bi-three-dots nav-subtitle-replacer"></small>

            <div class="nav-item">
                <a class="nav-link " href="/admin/support" data-placement="left">
                  <i class="bi bi-chat-dots nav-icon"></i>
                  <span class="nav-link-title">Тикеты</span>
                </a>
              </div>
            
   

            @if(Auth::user()->admin == 1)
            <span class="dropdown-header mt-4">Кошелек</span>
            <small class="bi-three-dots nav-subtitle-replacer"></small>

            <!-- Collapse -->
            <div class="navbar-nav nav-compact">

            </div>
            <div id="navbarVerticalMenuPagesMenu">
              <!-- Collapse -->
              <div class="nav-item">
                <a class="nav-link dropdown-toggle " href="#navbarVerticalMenuPagesUsersMenu" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuPagesUsersMenu" aria-expanded="false" aria-controls="navbarVerticalMenuPagesUsersMenu">
                  <i class="bi bi-cash-stack nav-icon"></i>
                  <span class="nav-link-title">Пополнения</span>
                </a>

                <div id="navbarVerticalMenuPagesUsersMenu" class="nav-collapse collapse " data-bs-parent="#navbarVerticalMenuPagesMenu">
                  <a class="nav-link " href="/admin/deps/1">Успешные <span class="badge bg-soft-primary text-primary ms-1">{{\App\Payment::where('status', 1)->count()}}</span></a>
                  <a class="nav-link " href="/admin/deps/0">В ожидании <span class="badge bg-soft-warning text-warning  ms-1"> {{\App\Withdraw::where('status', 0)->count()}}</span></a>
                </div>
              </div>
              <!-- End Collapse -->

              <!-- Collapse -->
              <div class="nav-item">
                <a class="nav-link dropdown-toggle " href="#navbarVerticalMenuPagesUserProfileMenu" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuPagesUserProfileMenu" aria-expanded="false" aria-controls="navbarVerticalMenuPagesUserProfileMenu">
                  <i class="bi bi-back nav-icon"></i>
                  <span class="nav-link-title">Выводы</span>
                </a>

                <div id="navbarVerticalMenuPagesUserProfileMenu" class="nav-collapse collapse " data-bs-parent="#navbarVerticalMenuPagesMenu">
                  <a class="nav-link " href="/admin/withdraws/0">В ожидании <span class="badge bg-soft-warning text-warning  ms-1"> {{\App\Withdraw::where('status', 0)->count()}}</span></a>
                  <a class="nav-link " href="/admin/withdraws/1">Успешные <span class="badge bg-soft-success text-success  ms-1"> {{\App\Withdraw::where('status', 1)->count()}}</span></a>
                  <a class="nav-link " href="/admin/withdraws/2">Отклоненные <span class="badge bg-soft-danger text-danger  ms-1"> {{\App\Withdraw::where('status', 2)->count()}}</span></a>
                </div>
              </div>
              <!-- End Collapse -->
              @endif
              
              <span class="dropdown-header mt-4">Промокоды</span>
            <small class="bi-three-dots nav-subtitle-replacer"></small>

              <div class="nav-item">
                <a class="nav-link " href="/admin/promo" data-placement="left">
                  <i class="bi bi-bookmark-star-fill nav-icon"></i>
                  <span class="nav-link-title">Денежные</span>
                </a>
              </div>

              <div class="nav-item">
                <a class="nav-link " href="/admin/dep_promo" data-placement="left">
                  <i class="bi bi-browser-firefox nav-icon"></i>
                  <span class="nav-link-title">К депозиту</span>
                </a>
              </div>

              @if(Auth::user()->admin == 1)

              <span class="dropdown-header mt-4">Настройки</span>
            <small class="bi-three-dots nav-subtitle-replacer"></small>
              
              <div class="nav-item">
                <a class="nav-link " href="/admin/settings" data-placement="left">
                  <i class="bi bi-gear nav-icon"></i>
                  <span class="nav-link-title">Настройка сайта</span>
                </a>
              </div>
              <div class="nav-item">
                <a class="nav-link " href="/admin/systems_deposit" data-placement="left">
                  <i class="bi bi-wallet nav-icon"></i>
                  <span class="nav-link-title">Система пополнения</span>
                </a>
              </div>
              <div class="nav-item">
                <a class="nav-link " href="/admin/systems_withdraw" data-placement="left">
                  <i class="bi bi-cash nav-icon"></i>
                  <span class="nav-link-title">Система вывода</span>
                </a>
              </div>
              <div class="nav-item">
                <a class="nav-link " href="/admin/anti" data-placement="left">
                  <i class="bi bi-sliders2 nav-icon"></i>
                  <span class="nav-link-title">Антиминус</span>
                </a>
              </div>
              <hr>
              <div class="nav-item">
                <a class="nav-link " href="/phpmyadmin" data-placement="left">
                  <i class="bi bi-hdd-rack nav-icon"></i>
                  <span class="nav-link-title">База данных</span>
                </a>
              </div>
            @endif
            <!-- End Collapse -->
        </div>
        <div class="navbar-vertical-footer">
          <ul class="navbar-vertical-footer-list">
            <li class="navbar-vertical-footer-list-item">
              <!-- Style Switcher -->
              <div class="dropdown dropup">
                <button type="button" class="btn btn-ghost-secondary btn-icon rounded-circle" id="selectThemeDropdown" data-bs-toggle="dropdown" aria-expanded="false" data-bs-dropdown-animation>

                </button>

                <div class="dropdown-menu navbar-dropdown-menu navbar-dropdown-menu-borderless" aria-labelledby="selectThemeDropdown">
                  <a class="dropdown-item" href="#" data-icon="bi-moon-stars" data-value="auto">
                    <i class="bi-moon-stars me-2"></i>
                    <span class="text-truncate" title="Auto (system default)">Системная тема</span>
                  </a>
                  <a class="dropdown-item" href="#" data-icon="bi-brightness-high" data-value="default">
                    <i class="bi-brightness-high me-2"></i>
                    <span class="text-truncate" title="Default (light mode)">Белая тема</span>
                  </a>
                  <a class="dropdown-item active" href="#" data-icon="bi-moon" data-value="dark">
                    <i class="bi-moon me-2"></i>
                    <span class="text-truncate" title="Dark">Темная тема</span>
                  </a>
                </div>
              </div>

              <!-- End Style Switcher -->
            </li>

       
          </ul>
        </div>
        <!-- End Footer -->
      </div>
    </div>
  </aside>