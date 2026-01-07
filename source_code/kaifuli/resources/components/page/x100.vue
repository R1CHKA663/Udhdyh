<template v-if="user.is_admin">
  <div class="wheel">
    <div class="wheel__body">
      <div class="wheel__rotate">
        <div class="wheel__center">
          <div class="wheel__bonus-slider">
            <div class="wheel__bonus-cursor"></div>
            <div class="wheel__bonus-slider-list" style="transform: translateX(-680px);">
              <div class="wheel__slider-item" data-color="wheel__color-x2">
                <span>x2</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x3">
                <span>x3</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x10">
                <span>x10</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x15">
                <span>x15</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x20">
                <span>x20</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x100">
                <span>x100</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x2">
                <span>x2</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x3">
                <span>x3</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x10">
                <span>x10</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x15">
                <span>x15</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x20">
                <span>x20</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x100">
                <span>x100</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x2">
                <span>x2</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x3">
                <span>x3</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x10">
                <span>x10</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x15">
                <span>x15</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x20">
                <span>x20</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x100">
                <span>x100</span>
              </div>
            </div>
          </div>
          <div class="wheel__time">
            <div class="wheel__progress-bar">
              <div id="wheel__progress" style="width: 29%;"></div>
            </div>
            <span>29s</span>
          </div>
        </div>
        <div class="wheel__cursor"></div>
        <div class="wheel__separators">
          <svg viewBox="0 0 720 720" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M710.983 356.612C710.994 357.741 711 358.871 711 360" stroke="#1F50FA" stroke-opacity="0.08"
              stroke-width="18"></path>
            <path d="M710.651 344.365C710.702 345.493 710.747 346.622 710.786 347.75" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M709.892 332.137C709.982 333.263 710.066 334.389 710.145 335.516" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M708.707 319.943C708.835 321.065 708.959 322.188 709.077 323.311" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M707.096 307.798C707.264 308.914 707.427 310.032 707.584 311.15" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M705.063 295.716C705.27 296.826 705.471 297.937 705.667 299.05" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M702.609 283.713C702.855 284.815 703.095 285.918 703.33 287.023" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M699.738 271.802C700.022 272.895 700.3 273.99 700.574 275.086" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M696.453 259.999C696.775 261.082 697.091 262.166 697.403 263.252" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M692.758 248.318C693.117 249.389 693.472 250.461 693.821 251.535" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M688.658 236.773C689.054 237.831 689.446 238.89 689.832 239.951" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M684.157 225.378C684.59 226.421 685.018 227.466 685.441 228.513" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M679.261 214.147C679.731 215.175 680.195 216.204 680.654 217.236" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M673.977 203.094C674.482 204.104 674.981 205.117 675.477 206.132" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M668.309 192.232C668.849 193.224 669.384 194.219 669.914 195.216" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M662.267 181.574C662.841 182.547 663.41 183.522 663.975 184.5" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M655.856 171.134C656.463 172.086 657.066 173.041 657.665 173.998" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M649.084 160.924C649.725 161.854 650.361 162.787 650.992 163.724" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M641.96 150.956C642.633 151.864 643.301 152.774 643.965 153.688" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M634.493 141.243C635.197 142.127 635.896 143.013 636.592 143.903" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M626.691 131.797C627.426 132.655 628.156 133.517 628.881 134.382" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M618.565 122.629C619.328 123.461 620.088 124.296 620.844 125.135" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M610.123 113.75C610.915 114.554 611.704 115.363 612.488 116.175" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M601.377 105.17C602.197 105.947 603.013 106.728 603.825 107.512" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M592.336 96.9016C593.183 97.6492 594.025 98.4007 594.865 99.1564" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M583.013 88.9535C583.885 89.6711 584.753 90.3928 585.618 91.1187" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M573.417 81.3356C574.314 82.0222 575.207 82.7132 576.097 83.4084" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M563.562 74.0572C564.482 74.7121 565.399 75.3715 566.313 76.0353" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M553.459 67.1271C554.401 67.7495 555.34 68.3765 556.277 69.008" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M543.12 60.5539C544.083 61.1431 545.044 61.7369 546.002 62.3354" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M532.558 54.3455C533.541 54.9007 534.522 55.4606 535.5 56.0253" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M521.785 48.5096C522.788 49.0301 523.787 49.5555 524.784 50.0856" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M510.816 43.0531C511.836 43.5384 512.853 44.0285 513.868 44.5236" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M499.663 37.9828C500.699 38.4321 501.733 38.8864 502.764 39.3458" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M488.34 33.3048C489.391 33.7177 490.44 34.1357 491.487 34.5587" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M476.86 29.0248C477.925 29.4008 478.988 29.7819 480.049 30.1681" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M465.238 25.1481C466.315 25.4867 467.391 25.8304 468.465 26.1794" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M453.488 21.6793C454.576 21.9801 455.663 22.2861 456.749 22.5974" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M441.623 18.6227C442.722 18.8853 443.819 19.1532 444.915 19.4264" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M429.66 15.9821C430.767 16.2062 431.872 16.4357 432.977 16.6705" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M417.611 13.7605C418.725 13.9459 419.838 14.1366 420.95 14.3327" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M405.493 11.9609C406.612 12.1072 407.731 12.259 408.85 12.4162" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M319.943 11.2934C321.065 11.1646 322.187 11.0411 323.311 10.9231" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M307.798 12.9038C308.915 12.7359 310.032 12.5733 311.15 12.4161" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M295.716 14.9369C296.826 14.73 297.937 14.5286 299.05 14.3324" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M283.713 17.3905C284.815 17.1451 285.918 16.905 287.023 16.6702" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M271.802 20.2616C272.895 19.9779 273.99 19.6994 275.086 19.4262" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M259.999 23.5466C261.082 23.2249 262.166 22.9084 263.252 22.5971" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M248.318 27.2416C249.389 26.8823 250.461 26.5281 251.535 26.1791" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M236.773 31.3419C237.831 30.9454 238.89 30.5541 239.951 30.1678" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M225.378 35.8427C226.421 35.4096 227.466 34.9815 228.513 34.5584" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M214.147 40.7384C215.175 40.2691 216.204 39.8048 217.236 39.3455" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M203.094 46.023C204.104 45.5182 205.117 45.0183 206.132 44.5232" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M192.232 51.6903C193.224 51.1505 194.219 50.6155 195.216 50.0854" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M181.575 57.7331C182.547 57.159 183.522 56.5897 184.5 56.025" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M171.134 64.1442C172.086 63.5366 173.041 62.9335 173.999 62.3351" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M160.924 70.9157C161.854 70.2752 162.787 69.6392 163.724 69.0077" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M150.957 78.0395C151.864 77.3669 152.774 76.6987 153.688 76.0349" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M141.244 85.5068C142.127 84.803 143.013 84.1034 143.903 83.4082" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M131.797 93.3085C132.655 92.5743 133.517 91.8442 134.382 91.1183" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M122.629 101.435C123.461 100.671 124.296 99.9118 125.135 99.1561" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M113.75 109.877C114.554 109.084 115.363 108.296 116.175 107.512" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M105.17 118.623C105.947 117.803 106.728 116.987 107.512 116.175" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M96.9017 127.664C97.6492 126.817 98.4008 125.974 99.1565 125.135" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M88.9536 136.987C89.6711 136.115 90.3929 135.246 91.1188 134.381" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M81.3357 146.582C82.0223 145.686 82.7133 144.793 83.4085 143.903" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M74.0573 156.438C74.7122 155.518 75.3716 154.601 76.0354 153.687" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M67.1272 166.541C67.7496 165.599 68.3766 164.659 69.0081 163.723" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M60.554 176.88C61.1432 175.916 61.737 174.956 62.3355 173.998" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M54.3456 187.442C54.9008 186.459 55.4607 185.478 56.0254 184.5" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M48.5097 198.214C49.0302 197.212 49.5556 196.212 50.0857 195.215" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M43.0532 209.184C43.5385 208.164 44.0286 207.147 44.5237 206.132" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M37.9828 220.337C38.4322 219.301 38.8865 218.267 39.3459 217.235" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M33.3049 231.66C33.7178 230.609 34.1358 229.56 34.5588 228.513" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M29.0249 243.14C29.4009 242.075 29.782 241.012 30.1682 239.951" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M25.1482 254.762C25.4868 253.685 25.8305 252.609 26.1795 251.535" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M21.6794 266.512C21.9802 265.424 22.2862 264.337 22.5975 263.251" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M18.6228 278.376C18.8854 277.278 19.1533 276.181 19.4265 275.085" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M15.9822 290.34C16.2063 289.233 16.4358 288.127 16.6706 287.023" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M13.7606 302.389C13.946 301.275 14.1367 300.161 14.3328 299.049" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M11.961 314.507C12.1073 313.387 12.2591 312.268 12.4163 311.15" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M10.5853 326.681C10.6925 325.557 10.8051 324.433 10.9232 323.31" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M9.63536 338.896C9.70326 337.769 9.77659 336.642 9.85537 335.515" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M9.11228 351.136C9.1408 350.007 9.17476 348.879 9.21417 347.75" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M9.0167 363.388C9.0058 362.258 9.00035 361.129 9.00035 360" stroke="#1F50FA" stroke-opacity="0.08"
              stroke-width="18"></path>
            <path d="M9.34874 375.635C9.29844 374.506 9.25358 373.378 9.21417 372.249" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M10.108 387.863C10.0183 386.737 9.93414 385.611 9.85536 384.484" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M11.2935 400.057C11.1647 398.935 11.0412 397.812 10.9232 396.689" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M12.9039 412.202C12.736 411.085 12.5734 409.968 12.4162 408.849" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M14.937 424.284C14.7302 423.174 14.5287 422.062 14.3326 420.95" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M17.3907 436.287C17.1453 435.185 16.9051 434.081 16.6703 432.977" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M20.2618 448.198C19.978 447.104 19.6995 446.01 19.4263 444.914" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M23.5468 460C23.225 458.918 22.9085 457.834 22.5972 456.748" stroke="#1F50FA" stroke-opacity="0.08"
              stroke-width="18"></path>
            <path d="M27.2417 471.682C26.8824 470.611 26.5283 469.539 26.1793 468.465" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M31.3421 483.227C30.9456 482.169 30.5542 481.11 30.168 480.049" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M35.8429 494.622C35.4097 493.579 34.9816 492.534 34.5586 491.487" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M40.7385 505.852C40.2693 504.825 39.805 503.796 39.3456 502.764" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M46.0232 516.906C45.5184 515.895 45.0184 514.883 44.5233 513.868" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M51.6904 527.768C51.1507 526.776 50.6157 525.781 50.0855 524.784" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M57.7332 538.425C57.1592 537.453 56.5898 536.478 56.0251 535.5" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M64.1444 548.866C63.5367 547.914 62.9337 546.959 62.3352 546.001" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M70.9159 559.076C70.2754 558.146 69.6394 557.213 69.0079 556.276" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M78.0396 569.043C77.367 568.136 76.6988 567.226 76.035 566.312" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M85.5069 578.756C84.8031 577.873 84.1036 576.987 83.4083 576.097" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M93.3086 588.203C92.5744 587.345 91.8443 586.483 91.1184 585.618" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M101.435 597.371C100.672 596.539 99.9119 595.704 99.1562 594.864" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M109.877 606.25C109.085 605.446 108.296 604.637 107.512 603.825" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M118.623 614.829C117.803 614.053 116.987 613.272 116.175 612.488" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M127.664 623.098C126.817 622.351 125.974 621.599 125.135 620.843" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M136.987 631.046C136.115 630.329 135.247 629.607 134.381 628.881" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M146.583 638.664C145.686 637.978 144.793 637.287 143.903 636.591" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M156.438 645.943C155.518 645.288 154.601 644.628 153.687 643.965" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M166.541 652.873C165.599 652.25 164.66 651.623 163.723 650.992" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M176.88 659.446C175.917 658.857 174.956 658.263 173.998 657.664" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M187.442 665.654C186.459 665.099 185.478 664.539 184.5 663.975" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M198.214 671.49C197.212 670.97 196.213 670.444 195.215 669.914" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M209.184 676.947C208.164 676.461 207.147 675.971 206.132 675.476" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M220.337 682.017C219.301 681.568 218.267 681.113 217.235 680.654" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M231.66 686.695C230.609 686.282 229.56 685.864 228.513 685.441" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M243.14 690.975C242.075 690.599 241.012 690.218 239.951 689.832" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M254.762 694.852C253.685 694.513 252.609 694.169 251.535 693.82" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M266.512 698.321C265.424 698.02 264.337 697.714 263.251 697.402" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M278.377 701.377C277.278 701.115 276.181 700.847 275.085 700.573" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M290.34 704.018C289.233 703.794 288.127 703.564 287.023 703.329" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M302.389 706.239C301.275 706.054 300.162 705.863 299.049 705.667" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M314.507 708.039C313.387 707.893 312.268 707.741 311.15 707.584" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M326.681 709.415C325.557 709.307 324.433 709.195 323.31 709.077" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M338.896 710.365C337.769 710.297 336.642 710.223 335.515 710.145" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M351.136 710.888C350.007 710.859 348.879 710.825 347.75 710.786" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M363.388 710.983C362.258 710.994 361.129 711 360 711" stroke="#1F50FA" stroke-opacity="0.08"
              stroke-width="18"></path>
            <path d="M375.635 710.651C374.507 710.702 373.378 710.746 372.25 710.786" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M387.863 709.892C386.737 709.982 385.611 710.066 384.484 710.145" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M400.057 708.706C398.935 708.835 397.812 708.959 396.689 709.077" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M412.202 707.096C411.085 707.264 409.968 707.427 408.849 707.584" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M424.284 705.063C423.174 705.27 422.062 705.471 420.95 705.667" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M436.287 702.609C435.185 702.855 434.081 703.095 432.977 703.329" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M448.197 699.738C447.104 700.022 446.01 700.3 444.914 700.573" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M460 696.453C458.918 696.775 457.834 697.091 456.748 697.403" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M471.682 692.758C470.611 693.117 469.539 693.472 468.465 693.82" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M483.227 688.658C482.169 689.054 481.11 689.446 480.049 689.832" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M494.622 684.157C493.579 684.59 492.534 685.018 491.487 685.441" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M505.852 679.261C504.825 679.73 503.796 680.195 502.764 680.654" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M516.906 673.977C515.895 674.481 514.883 674.981 513.868 675.476" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M527.768 668.309C526.776 668.849 525.781 669.384 524.784 669.914" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M538.425 662.267C537.453 662.841 536.478 663.41 535.5 663.975" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M548.866 655.855C547.914 656.463 546.959 657.066 546.001 657.665" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M559.076 649.084C558.146 649.724 557.212 650.36 556.276 650.992" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M569.043 641.96C568.136 642.633 567.226 643.301 566.312 643.965" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M578.756 634.493C577.873 635.197 576.987 635.896 576.097 636.591" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M588.203 626.691C587.345 627.425 586.483 628.155 585.618 628.881" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M597.371 618.564C596.539 619.328 595.704 620.088 594.864 620.844" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M606.25 610.123C605.445 610.915 604.637 611.704 603.825 612.488" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M614.829 601.377C614.053 602.196 613.272 603.013 612.488 603.825" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M623.098 592.336C622.351 593.182 621.599 594.025 620.843 594.865" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M631.046 583.012C630.329 583.885 629.607 584.753 628.881 585.618" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M638.664 573.417C637.978 574.314 637.287 575.207 636.591 576.097" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M645.943 563.562C645.288 564.482 644.628 565.399 643.964 566.312" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M652.873 553.459C652.25 554.401 651.623 555.34 650.992 556.276" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M659.446 543.12C658.857 544.083 658.263 545.044 657.664 546.001" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M665.654 532.558C665.099 533.541 664.539 534.522 663.974 535.5" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M671.49 521.785C670.97 522.787 670.444 523.787 669.914 524.784" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M676.947 510.816C676.461 511.836 675.971 512.853 675.476 513.868" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M682.017 499.663C681.568 500.699 681.113 501.733 680.654 502.764" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M686.695 488.339C686.282 489.391 685.864 490.44 685.441 491.487" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M690.975 476.86C690.599 477.925 690.218 478.988 689.832 480.049" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M694.852 465.238C694.513 466.315 694.169 467.391 693.82 468.465" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M698.32 453.487C698.02 454.576 697.714 455.663 697.402 456.749" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M701.377 441.623C701.114 442.722 700.846 443.819 700.573 444.914" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M704.018 429.66C703.794 430.766 703.564 431.872 703.329 432.977" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M706.239 417.611C706.054 418.725 705.863 419.838 705.667 420.95" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M708.039 405.492C707.893 406.612 707.741 407.731 707.584 408.85" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M709.415 393.318C709.307 394.443 709.195 395.566 709.077 396.689" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M710.364 381.104C710.297 382.231 710.223 383.358 710.144 384.484" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
            <path d="M710.888 368.863C710.859 369.992 710.825 371.121 710.786 372.25" stroke="#1F50FA"
              stroke-opacity="0.08" stroke-width="18"></path>
          </svg>
        </div>
        <svg id="wheel__rotator" viewBox="0 0 800 800" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M389.887 10.0134C389.885 4.49067 394.358 0.0122676 399.878 0.010573C408.25 0.00800225 416.621 0.268658 424.978 0.792155C430.486 1.13724 434.672 5.88527 434.328 11.3972C433.983 16.9091 429.238 21.0976 423.729 20.7525C415.79 20.2552 407.838 20.0076 399.884 20.01C394.364 20.0117 389.889 15.5361 389.887 10.0134Z"
            fill="#2D71EF"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M438.815 11.8202C439.505 6.34087 444.504 2.45872 449.98 3.14922C458.287 4.19672 466.559 5.50512 474.784 7.07248C480.206 8.10567 483.764 13.3412 482.732 18.7664C481.699 24.1916 476.467 27.752 471.045 26.7189C463.231 25.2299 455.373 23.9869 447.481 22.9918C442.005 22.3013 438.125 17.2996 438.815 11.8202Z"
            fill="#10B566"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M487.131 19.7489C488.502 14.3993 493.947 11.1746 499.294 12.5464C507.404 14.6275 515.447 16.963 523.411 19.5495C528.661 21.2545 531.536 26.895 529.832 32.1479C528.128 37.4009 522.491 40.277 517.241 38.572C509.675 36.1148 502.034 33.8961 494.329 31.9191C488.983 30.5473 485.76 25.0985 487.131 19.7489Z"
            fill="#2D71EF"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M534.073 33.6744C536.103 28.5389 541.909 26.0226 547.042 28.054C554.828 31.1358 562.515 34.4616 570.092 38.0265C575.087 40.3764 577.232 46.3329 574.884 51.3307C572.535 56.3285 566.582 58.475 561.587 56.1251C554.389 52.7385 547.086 49.579 539.69 46.6513C534.557 44.6198 532.043 38.8099 534.073 33.6744Z"
            fill="#2D71EF"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M578.9 53.377C581.558 48.5366 587.634 46.7683 592.471 49.4274C599.81 53.4613 607.02 57.7249 614.09 62.212C618.751 65.1697 620.134 71.3483 617.178 76.0122C614.222 80.6761 608.047 82.0591 603.386 79.1014C596.669 74.8387 589.819 70.7883 582.848 66.956C578.01 64.2969 576.243 58.2174 578.9 53.377Z"
            fill="#5B0BE1"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M620.906 78.5461C624.149 74.0772 630.399 73.0848 634.865 76.3296C641.64 81.252 648.259 86.3862 654.712 91.7246C658.966 95.2436 659.564 101.547 656.047 105.803C652.53 110.06 646.23 110.657 641.977 107.138C635.846 102.067 629.558 97.1894 623.121 92.5131C618.655 89.2683 617.664 83.0151 620.906 78.5461Z"
            fill="#10B566"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M659.429 108.785C663.206 104.758 669.53 104.557 673.555 108.336C679.66 114.07 685.584 119.993 691.317 126.099C695.097 130.124 694.9 136.452 690.878 140.234C686.855 144.016 680.531 143.819 676.751 139.794C671.305 133.994 665.677 128.366 659.877 122.919C655.852 119.14 655.652 112.812 659.429 108.785Z"
            fill="#2D71EF"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M693.86 143.616C698.111 140.095 704.411 140.688 707.93 144.943C713.27 151.396 718.405 158.016 723.328 164.793C726.574 169.26 725.586 175.514 721.122 178.761C716.657 182.009 710.407 181.02 707.162 176.553C702.484 170.116 697.606 163.827 692.534 157.696C689.014 153.441 689.608 147.138 693.86 143.616Z"
            fill="#2D71EF"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M723.656 182.491C728.315 179.53 734.491 180.909 737.45 185.571C741.939 192.644 746.204 199.856 750.24 207.196C752.9 212.035 751.137 218.116 746.301 220.778C741.465 223.44 735.388 221.675 732.728 216.836C728.894 209.863 724.842 203.012 720.577 196.293C717.619 191.631 718.997 185.451 723.656 182.491Z"
            fill="#FFAA39"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M748.348 224.795C753.342 222.442 759.296 224.585 761.648 229.582C765.215 237.161 768.543 244.851 771.628 252.64C773.661 257.774 771.15 263.586 766.019 265.62C760.888 267.655 755.08 265.142 753.047 260.008C750.116 252.609 746.954 245.303 743.565 238.103C741.214 233.106 743.355 227.148 748.348 224.795Z"
            fill="#FFCF62"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M767.547 269.863C772.796 268.155 778.435 271.028 780.142 276.28C782.732 284.247 785.071 292.294 787.156 300.408C788.53 305.757 785.311 311.207 779.965 312.582C774.62 313.958 769.172 310.736 767.798 305.387C765.817 297.679 763.595 290.035 761.135 282.466C759.428 277.214 762.299 271.572 767.547 269.863Z"
            fill="#5B0BE1"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M780.949 316.984C786.371 315.947 791.605 319.504 792.641 324.929C794.212 333.158 795.525 341.435 796.577 349.746C797.27 355.225 793.394 360.229 787.918 360.923C782.443 361.617 777.442 357.738 776.748 352.259C775.749 344.363 774.502 336.5 773.009 328.682C771.973 323.258 775.528 318.02 780.949 316.984Z"
            fill="#0869EE"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M788.344 365.413C793.852 365.065 798.6 369.25 798.948 374.762C799.476 383.123 799.742 391.499 799.745 399.877C799.746 405.4 795.273 409.878 789.754 409.88C784.235 409.882 779.759 405.406 779.757 399.883C779.755 391.924 779.503 383.967 779.001 376.024C778.652 370.512 782.836 365.761 788.344 365.413Z"
            fill="#0869EE"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M789.614 414.388C795.122 414.733 799.308 419.481 798.964 424.993C798.44 433.355 797.655 441.698 796.608 450.01C795.918 455.489 790.919 459.372 785.443 458.681C779.967 457.991 776.087 452.989 776.778 447.51C777.772 439.613 778.518 431.687 779.015 423.743C779.36 418.231 784.105 414.043 789.614 414.388Z"
            fill="#FFAA39"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M784.739 463.136C790.161 464.169 793.72 469.405 792.687 474.83C791.121 483.06 789.296 491.239 787.216 499.354C785.845 504.704 780.4 507.929 775.054 506.557C769.707 505.185 766.485 499.736 767.856 494.387C769.831 486.677 771.565 478.907 773.053 471.088C774.085 465.663 779.318 462.103 784.739 463.136Z"
            fill="#4A00E0"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M773.797 510.888C779.047 512.593 781.921 518.234 780.218 523.487C777.633 531.455 774.798 539.341 771.718 547.132C769.688 552.267 763.882 554.784 758.749 552.752C753.617 550.721 751.102 544.911 753.132 539.775C756.058 532.374 758.751 524.883 761.207 517.312C762.911 512.059 768.548 509.183 773.797 510.888Z"
            fill="#12B969"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M756.96 556.892C761.955 559.242 764.1 565.198 761.752 570.196C758.189 577.778 754.389 585.246 750.358 592.589C747.7 597.429 741.625 599.197 736.787 596.538C731.95 593.879 730.183 587.8 732.84 582.959C736.67 575.983 740.28 568.889 743.664 561.686C746.013 556.688 751.966 554.542 756.96 556.892Z"
            fill="#0869EE"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M734.494 600.421C739.155 603.379 740.537 609.557 737.581 614.221C733.097 621.296 728.392 628.229 723.472 635.008C720.229 639.477 713.98 640.47 709.514 637.225C705.048 633.98 704.056 627.727 707.299 623.258C711.972 616.817 716.442 610.232 720.702 603.51C723.658 598.846 729.833 597.463 734.494 600.421Z"
            fill="#0869EE"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M706.752 640.789C711.006 644.308 711.603 650.612 708.087 654.868C702.752 661.325 697.215 667.613 691.485 673.722C687.708 677.749 681.384 677.95 677.359 674.17C673.335 670.391 673.134 664.063 676.911 660.036C682.354 654.232 687.614 648.259 692.682 642.125C696.199 637.868 702.498 637.27 706.752 640.789Z"
            fill="#FFAA39"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M674.173 677.361C677.952 681.385 677.756 687.714 673.733 691.496C667.631 697.233 661.351 702.776 654.901 708.119C650.65 711.64 644.35 711.046 640.83 706.792C637.311 702.538 637.905 696.234 642.156 692.713C648.283 687.638 654.25 682.371 660.047 676.921C664.069 673.139 670.393 673.336 674.173 677.361Z"
            fill="#F200AD"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M637.27 709.558C640.515 714.025 639.527 720.278 635.063 723.526C628.291 728.452 621.365 733.165 614.297 737.656C609.638 740.617 603.463 739.238 600.504 734.576C597.545 729.914 598.923 723.734 603.582 720.773C610.297 716.507 616.876 712.03 623.31 707.35C627.774 704.102 634.024 705.091 637.27 709.558Z"
            fill="#0869EE"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M596.625 736.873C599.285 741.711 597.522 747.792 592.686 750.454C585.35 754.492 577.889 758.299 570.314 761.869C565.321 764.222 559.367 762.079 557.015 757.083C554.663 752.086 556.805 746.128 561.798 743.775C568.995 740.384 576.082 736.768 583.052 732.931C587.887 730.269 593.964 732.034 596.625 736.873Z"
            fill="#FFCF62"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M552.879 758.875C554.912 764.009 552.401 769.821 547.27 771.855C539.486 774.942 531.607 777.783 523.645 780.374C518.396 782.083 512.757 779.21 511.05 773.958C509.343 768.706 512.214 763.064 517.462 761.356C525.027 758.894 532.512 756.195 539.906 753.263C545.038 751.228 550.845 753.741 552.879 758.875Z"
            fill="#0869EE"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M506.722 775.218C508.096 780.566 504.877 786.017 499.531 787.392C491.422 789.478 483.249 791.309 475.025 792.881C469.604 793.918 464.369 790.361 463.334 784.936C462.298 779.511 465.853 774.274 471.274 773.237C479.087 771.743 486.851 770.004 494.555 768.023C499.9 766.648 505.348 769.869 506.722 775.218Z"
            fill="#FFCF62"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M458.882 785.643C459.575 791.122 455.699 796.126 450.223 796.82C441.916 797.872 433.579 798.664 425.223 799.192C419.714 799.541 414.967 795.355 414.618 789.843C414.27 784.332 418.453 779.581 423.962 779.233C431.9 778.73 439.821 777.979 447.712 776.979C453.187 776.285 458.188 780.164 458.882 785.643Z"
            fill="#12B969"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M365.673 788.603C366.017 783.091 370.763 778.902 376.271 779.247C384.21 779.745 392.162 779.992 400.117 779.99C405.636 779.988 410.112 784.464 410.113 789.987C410.115 795.509 405.642 799.988 400.123 799.989C391.75 799.992 383.379 799.731 375.022 799.208C369.514 798.863 365.328 794.115 365.673 788.603Z"
            fill="#0869EE"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M317.268 781.233C318.301 775.808 323.533 772.248 328.955 773.281C336.769 774.77 344.628 776.013 352.519 777.008C357.995 777.699 361.875 782.7 361.185 788.18C360.495 793.659 355.496 797.541 350.02 796.851C341.713 795.803 333.441 794.495 325.216 792.927C319.794 791.894 316.236 786.659 317.268 781.233Z"
            fill="#0869EE"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M270.169 767.852C271.873 762.599 277.51 759.723 282.759 761.428C290.325 763.885 297.966 766.104 305.671 768.081C311.017 769.453 314.24 774.901 312.869 780.251C311.498 785.601 306.053 788.825 300.706 787.453C292.596 785.372 284.553 783.037 276.589 780.45C271.339 778.745 268.465 773.105 270.169 767.852Z"
            fill="#0869EE"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M225.117 748.669C227.465 743.671 233.418 741.525 238.413 743.875C245.611 747.261 252.914 750.421 260.31 753.348C265.443 755.38 267.957 761.19 265.927 766.325C263.897 771.461 258.091 773.977 252.958 771.946C245.172 768.864 237.485 765.538 229.908 761.973C224.913 759.623 222.768 753.667 225.117 748.669Z"
            fill="#4A00E0"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M182.822 723.988C185.778 719.324 191.953 717.941 196.614 720.899C203.331 725.161 210.181 729.212 217.153 733.044C221.99 735.703 223.757 741.782 221.1 746.623C218.442 751.463 212.366 753.232 207.529 750.572C200.19 746.539 192.98 742.275 185.91 737.788C181.249 734.83 179.866 728.652 182.822 723.988Z"
            fill="#FF4B4D"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M143.953 694.197C147.47 689.94 153.77 689.342 158.023 692.861C164.154 697.933 170.442 702.81 176.878 707.487C181.345 710.732 182.336 716.985 179.094 721.454C175.851 725.923 169.601 726.915 165.135 723.67C158.36 718.748 151.741 713.614 145.288 708.275C141.034 704.756 140.437 698.453 143.953 694.197Z"
            fill="#FFAA39"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M109.122 659.766C113.145 655.984 119.469 656.181 123.249 660.206C128.695 666.006 134.323 671.634 140.123 677.08C144.148 680.86 144.348 687.188 140.571 691.215C136.794 695.242 130.47 695.443 126.445 691.664C120.34 685.93 114.416 680.006 108.683 673.901C104.903 669.876 105.1 663.548 109.122 659.766Z"
            fill="#4A00E0"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M78.8785 621.239C83.3427 617.991 89.5927 618.98 92.8383 623.447C97.5157 629.884 102.394 636.173 107.466 642.304C110.986 646.559 110.392 652.862 106.14 656.384C101.889 659.905 95.5891 659.312 92.0696 655.057C86.7306 648.604 81.5954 641.984 76.6719 635.207C73.4263 630.74 74.4143 624.486 78.8785 621.239Z"
            fill="#2D71EF"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M53.6991 579.222C58.5349 576.56 64.6118 578.325 67.2722 583.163C71.1064 590.137 75.1585 596.988 79.4227 603.707C82.3815 608.369 81.0031 614.549 76.3439 617.509C71.6847 620.47 65.509 619.091 62.5502 614.428C58.0616 607.356 53.7962 600.144 49.7602 592.804C47.0998 587.965 48.8633 581.884 53.6991 579.222Z"
            fill="#2D71EF"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M33.9811 534.38C39.1122 532.345 44.9201 534.858 46.9535 539.992C49.8839 547.391 53.046 554.697 56.435 561.897C58.7865 566.894 56.6449 572.851 51.6516 575.204C46.6583 577.557 40.7041 575.415 38.3526 570.418C34.7852 562.839 31.4568 555.149 28.3721 547.36C26.3387 542.226 28.8499 536.414 33.9811 534.38Z"
            fill="#FF8B4A"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M20.0352 487.417C25.3807 486.042 30.8282 489.264 32.2024 494.612C34.1829 502.321 36.405 509.965 38.8653 517.534C40.5725 522.786 37.7015 528.428 32.4529 530.136C27.2042 531.845 21.5654 528.972 19.8582 523.72C17.2684 515.753 14.9294 507.706 12.8446 499.592C11.4704 494.243 14.6897 488.792 20.0352 487.417Z"
            fill="#5B0BE1"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M12.0817 439.077C17.5572 438.383 22.5582 442.262 23.2517 447.741C24.251 455.637 25.4981 463.5 26.9909 471.317C28.0268 476.742 24.4718 481.98 19.0505 483.016C13.6293 484.053 8.39476 480.495 7.35888 475.071C5.78743 466.842 4.47475 458.565 3.4228 450.254C2.72937 444.775 6.60607 439.771 12.0817 439.077Z"
            fill="#2D71EF"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M10.2461 390.12C15.7654 390.118 20.241 394.594 20.2427 400.116C20.2452 408.076 20.4975 416.033 20.9994 423.976C21.3477 429.488 17.1646 434.238 11.6563 434.587C6.14796 434.935 1.40027 430.75 1.05202 425.238C0.523712 416.877 0.258079 408.501 0.255509 400.123C0.253815 394.6 4.72674 390.121 10.2461 390.12Z"
            fill="#FFAA39"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M14.5575 341.319C20.0335 342.009 23.9132 347.011 23.2232 352.49C22.2286 360.387 21.4823 368.313 20.9853 376.256C20.6404 381.768 15.8953 385.957 10.3868 385.612C4.87824 385.267 0.692264 380.519 1.03714 375.007C1.56031 366.645 2.34591 358.302 3.39277 349.99C4.08284 344.51 9.08144 340.628 14.5575 341.319Z"
            fill="#2D71EF"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M24.9466 293.443C30.2929 294.815 33.5156 300.263 32.1446 305.613C30.1688 313.323 28.4356 321.093 26.9475 328.911C25.915 334.336 20.6826 337.897 15.2607 336.864C9.83887 335.831 6.28062 330.595 7.31318 325.17C8.87959 316.94 10.704 308.761 12.7838 300.645C14.1548 295.296 19.6002 292.071 24.9466 293.443Z"
            fill="#10B566"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M41.2507 247.248C46.3831 249.279 48.8979 255.089 46.8676 260.225C43.9417 267.626 41.249 275.117 38.7933 282.687C37.0893 287.94 31.4522 290.816 26.2025 289.111C20.9528 287.406 18.0784 281.766 19.7824 276.513C22.3673 268.544 25.2018 260.659 28.2817 252.868C30.3119 247.732 36.1184 245.216 41.2507 247.248Z"
            fill="#2D71EF"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M63.2129 203.461C68.0504 206.12 69.8176 212.2 67.1601 217.04C63.3302 224.016 59.7204 231.111 56.3358 238.313C53.9873 243.311 48.0344 245.458 43.0397 243.108C38.0449 240.758 35.8997 234.801 38.2482 229.804C41.8109 222.222 45.6108 214.754 49.6422 207.411C52.2997 202.571 58.3755 200.802 63.2129 203.461Z"
            fill="#5B0BE1"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M90.4864 162.775C94.9527 166.02 95.9444 172.273 92.7016 176.742C88.0282 183.182 83.5581 189.768 79.2981 196.49C76.3421 201.153 70.1673 202.536 65.5063 199.579C60.8453 196.621 59.463 190.442 62.419 185.778C66.9033 178.703 71.6086 171.771 76.528 164.991C79.7708 160.522 86.0202 159.53 90.4864 162.775Z"
            fill="#2D71EF"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M122.641 125.829C126.666 129.609 126.866 135.937 123.089 139.964C117.646 145.768 112.386 151.741 107.318 157.875C103.801 162.131 97.5017 162.729 93.2479 159.21C88.9941 155.691 88.3967 149.388 91.9136 145.132C97.2487 138.675 102.785 132.387 108.515 126.278C112.292 122.251 118.616 122.05 122.641 125.829Z"
            fill="#2D71EF"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M159.17 93.2076C162.689 97.4618 162.095 103.765 157.844 107.287C151.716 112.362 145.75 117.629 139.953 123.079C135.931 126.861 129.606 126.664 125.827 122.639C122.048 118.614 122.244 112.286 126.267 108.504C132.368 102.767 138.649 97.2234 145.099 91.881C149.35 88.3594 155.65 88.9533 159.17 93.2076Z"
            fill="#FFAA39"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M199.496 65.4241C202.455 70.0862 201.077 76.2656 196.418 79.2262C189.703 83.493 183.124 87.9698 176.69 92.6501C172.226 95.8976 165.976 94.9091 162.73 90.4421C159.485 85.9751 160.473 79.7213 164.937 76.4738C171.709 71.5472 178.635 66.8347 185.703 62.3434C190.362 59.3828 196.538 60.762 199.496 65.4241Z"
            fill="#2D71EF"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M242.985 42.9172C245.337 47.9136 243.195 53.8714 238.202 56.2244C231.006 59.6154 223.918 63.2319 216.948 67.0684C212.113 69.7304 206.036 67.9659 203.375 63.1271C200.715 58.2883 202.478 52.2077 207.314 49.5457C214.65 45.5072 222.111 41.7005 229.686 38.1309C234.679 35.778 240.634 37.9208 242.985 42.9172Z"
            fill="#5B0BE1"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M288.95 26.0417C290.657 31.2936 287.787 36.9358 282.538 38.644C274.974 41.1059 267.489 43.8049 260.094 46.7371C254.963 48.7717 249.155 46.259 247.121 41.1247C245.088 35.9905 247.599 30.179 252.73 28.1444C260.514 25.0578 268.393 22.2167 276.356 19.6253C281.604 17.9171 287.243 20.7898 288.95 26.0417Z"
            fill="#2D71EF"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M336.667 15.0638C337.702 20.4884 334.147 25.7261 328.726 26.7626C320.913 28.2564 313.149 29.9955 305.445 31.9772C300.1 33.3523 294.652 30.131 293.278 24.7822C291.904 19.4334 295.123 13.9827 300.469 12.6076C308.578 10.5215 316.751 8.69097 324.975 7.11856C330.396 6.08204 335.631 9.63925 336.667 15.0638Z"
            fill="#2D71EF"></path>
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M385.382 10.1565C385.73 15.6682 381.547 20.4187 376.039 20.7672C368.1 21.2694 360.179 22.021 352.288 23.021C346.813 23.7149 341.812 19.8358 341.118 14.3569C340.425 8.87793 344.301 3.87388 349.777 3.18002C358.084 2.12742 366.421 1.33623 374.777 0.807597C380.286 0.459133 385.033 4.64476 385.382 10.1565Z"
            fill="#FFAA39"></path>
        </svg>
      </div>
      <div class="wheel__mobile">
        <div class="wheel__bonus-slider">
          <div class="wheel__bonus-cursor"></div>
          <div class="wheel__overflow">
            <div class="wheel__bonus-slider-list" style="transform: translateX(0px);">
              <div class="wheel__slider-item" data-color="wheel__color-x2">
                <span>x2</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x3">
                <span>x3</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x10">
                <span>x10</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x15">
                <span>x15</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x20">
                <span>x20</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x100">
                <span>x100</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x2">
                <span>x2</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x3">
                <span>x3</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x10">
                <span>x10</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x15">
                <span>x15</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x20">
                <span>x20</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x100">
                <span>x100</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x2">
                <span>x2</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x3">
                <span>x3</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x10">
                <span>x10</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x15">
                <span>x15</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x20">
                <span>x20</span>
              </div>
              <div class="wheel__slider-item" data-color="wheel__color-x100">
                <span>x100</span>
              </div>
            </div>
          </div>
        </div>
        <div class="wheel__time">
          <div class="wheel__progress-bar">
            <div id="wheel__progress" style="width: 29%;"></div>
          </div>
          <span>29s</span>
        </div>
      </div>
      <div class="wheel__content">
        <div class="wheel__stats">
          <div class="wheel__stat">
            <span>БАНК</span>
            <p>
              <count-up :end-val="bank" :options="$countUpFormat.options" class="ww" />
            </p>
            <svg class="icon" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M0 19.79V21.875C0 23.5986 4.19922 25 9.375 25C14.5508 25 18.75 23.5986 18.75 21.875V19.79C16.7334 21.2109 13.0469 21.875 9.375 21.875C5.70312 21.875 2.0166 21.2109 0 19.79ZM15.625 6.25C20.8008 6.25 25 4.84863 25 3.125C25 1.40137 20.8008 0 15.625 0C10.4492 0 6.25 1.40137 6.25 3.125C6.25 4.84863 10.4492 6.25 15.625 6.25ZM0 14.668V17.1875C0 18.9111 4.19922 20.3125 9.375 20.3125C14.5508 20.3125 18.75 18.9111 18.75 17.1875V14.668C16.7334 16.3281 13.042 17.1875 9.375 17.1875C5.70801 17.1875 2.0166 16.3281 0 14.668ZM20.3125 15.2051C23.1104 14.6631 25 13.6572 25 12.5V10.415C23.8672 11.2158 22.2021 11.7627 20.3125 12.0996V15.2051ZM9.375 7.8125C4.19922 7.8125 0 9.56055 0 11.7188C0 13.877 4.19922 15.625 9.375 15.625C14.5508 15.625 18.75 13.877 18.75 11.7188C18.75 9.56055 14.5508 7.8125 9.375 7.8125ZM20.083 10.5615C23.0127 10.0342 25 8.99902 25 7.8125V5.72754C23.2666 6.95312 20.2881 7.61231 17.1533 7.76856C18.5938 8.4668 19.6533 9.4043 20.083 10.5615Z">
              </path>
            </svg>
          </div>
          <div class="wheel__stat">
            <span>В ИГРЕ</span>
            <p>23</p>
            <svg class="icon" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M12.4992 11.8415C15.7215 11.8415 18.3334 9.19087 18.3334 5.92077C18.3334 2.65066 15.7215 0 12.4992 0C9.27691 0 6.66501 2.65066 6.66501 5.92077C6.66501 9.19087 9.27691 11.8415 12.4992 11.8415ZM0.0208714 23.0587C-0.141703 24.0782 0.669222 25 1.67387 25H23.3255C24.3632 25 25.1411 24.0782 24.9785 23.0587C24.0381 17.1715 18.8201 13.8155 12.5002 13.8155C6.18021 13.8155 0.961272 17.1705 0.0208714 23.0587Z">
              </path>
            </svg>
          </div>
        </div>
        <div class="wheel__container">
          <div class="wheel__history">
            <div class="wheel__history-list">
              <div class="wheel__history-item" data-color="wheel__color-x2"></div>
              <div class="wheel__history-item" data-color="wheel__color-x3"></div>
              <div class="wheel__history-item" data-color="wheel__color-x10"></div>
              <div class="wheel__history-item" data-color="wheel__color-x2"></div>
              <div class="wheel__history-item" data-color="wheel__color-x20"></div>
              <div class="wheel__history-item" data-color="wheel__color-x15"></div>
              <div class="wheel__history-item" data-color="wheel__color-x15"></div>
              <div class="wheel__history-item" data-color="wheel__color-x10"></div>
              <div class="wheel__history-item" data-color="wheel__color-x100"></div>
              <div class="wheel__history-item" data-color="wheel__color-x2"></div>
              <div class="wheel__history-item" data-color="wheel__color-x3"></div>
              <div class="wheel__history-item" data-color="wheel__color-x10"></div>
              <div class="wheel__history-item" data-color="wheel__color-x2"></div>
              <div class="wheel__history-item" data-color="wheel__color-x20"></div>
              <div class="wheel__history-item" data-color="wheel__color-x15"></div>
              <div class="wheel__history-item" data-color="wheel__color-x15"></div>
              <div class="wheel__history-item" data-color="wheel__color-x10"></div>
              <div class="wheel__history-item" data-color="wheel__color-x100"></div>
            </div>
          </div>
          <div class="wheel__form">
            <div class="wheel__bet-buttons">
              <div class="wheel__btn" data-color="wheel__color-x2" @click="play(2)">x2</div>
              <div class="wheel__btn" data-color="wheel__color-x3" @click="play(3)">x3</div>
              <div class="wheel__btn" data-color="wheel__color-x10" @click="play(10)">x10</div>
              <div class="wheel__btn" data-color="wheel__color-x15" @click="play(15)">x15</div>
              <div class="wheel__btn" data-color="wheel__color-x20" @click="play(20)">x20</div>
              <div class="wheel__btn" data-color="wheel__color-x100" @click="play(100)">x100</div>
            </div>
            <div class="wheel__inputs">
              <input type="text" id="wheel__summ" placeholder="Сумма..." v-model="bet">
              <div class="wheel__buttons">
                <button type="submit" @click="bet /= 2">1/2</button>
                <button type="submit" @click="bet *= 2">x2</button>
                <button type="submit" @click="bet = user.balance">max</button>
              </div>
            </div>
          </div>
          <div class="wheel__hash">
          </div>
        </div>
      </div>
    </div>
    <div class="wheel__bets-body">
      <div class="wheel__title">
        <span>СТАВКИ ИГРОКОВ</span>
      </div>
      <div class="wheel__bets-content">
        <div class="wheel__bets">
          <TransitionGroup name="list">
            <div class="wheel__bet" v-for="key in bets" :key="key.id">
              <div class="wheel__bet-profile">
                <div class="wheel__ava" :style="`background: url(${key.img}) no-repeat center center / cover;`"></div>
                <div class="wheel__profile-info">
                  <span>Илья Терещенко</span>
                  <p>Сумма: <b :data-color="`wheel__color-x${key.color}`">{{key.bet}} <svg class="icon"
                        viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                          d="M0 9.49922V10.5C0 11.3273 2.01562 12 4.5 12C6.98438 12 9 11.3273 9 10.5V9.49922C8.03203 10.1812 6.2625 10.5 4.5 10.5C2.7375 10.5 0.967969 10.1812 0 9.49922ZM7.5 3C9.98438 3 12 2.32734 12 1.5C12 0.672656 9.98438 0 7.5 0C5.01562 0 3 0.672656 3 1.5C3 2.32734 5.01562 3 7.5 3ZM0 7.04062V8.25C0 9.07734 2.01562 9.75 4.5 9.75C6.98438 9.75 9 9.07734 9 8.25V7.04062C8.03203 7.8375 6.26016 8.25 4.5 8.25C2.73984 8.25 0.967969 7.8375 0 7.04062ZM9.75 7.29844C11.093 7.03828 12 6.55547 12 6V4.99922C11.4563 5.38359 10.657 5.64609 9.75 5.80781V7.29844ZM4.5 3.75C2.01562 3.75 0 4.58906 0 5.625C0 6.66094 2.01562 7.5 4.5 7.5C6.98438 7.5 9 6.66094 9 5.625C9 4.58906 6.98438 3.75 4.5 3.75ZM9.63984 5.06953C11.0461 4.81641 12 4.31953 12 3.75V2.74922C11.168 3.3375 9.73828 3.65391 8.23359 3.72891C8.925 4.06406 9.43359 4.51406 9.63984 5.06953Z">
                        </path>
                      </svg>
                    </b></p>
                </div>
              </div>
              <div class="wheel__bet-color" :data-color="`wheel__color-x${key.color}`">
                <span>x{{key.color}}</span>
              </div>
            </div>
          </TransitionGroup>
        </div>
        <div class="wheel__bets-not-found" style="display: none;">
          <div class="wheel__info-bet">
            <svg class="icon" viewBox="0 0 152 173" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M38.5499 109.038L72.5324 128.605V89.4432L38.5228 69.8757V109.038H38.5499ZM23.9048 117.454L72.5324 145.438V172.968L0 131.216V47.6865L23.9048 61.4541V117.454ZM76 44.2865L42.023 63.8757L76 83.4432L109.977 63.8757L76 44.3081V44.2865ZM76 27.4757L116.891 51.1027L140.795 37.3351L76 0L3.56512 41.7243L27.4645 55.4595L76 27.4757ZM128.063 117.454V88.9946L113.45 97.4108V109.038L79.4676 128.632V89.4703L152 47.7189V131.222L79.4676 173V145.497L128.063 117.454Z">
              </path>
            </svg>
            <span>ОЖИДАНИЕ СТАВОК...</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import CountUp from "vue-countup-v3";

export default {
  components: { CountUp },

  props: ['user'],
  data: () => {
    return {
      bets: [],
      bet: 10
    }
  },
  methods: {
    play(color) {
      this.bets.unshift({
        id: color,
        img: 'https://sun1-47.userapi.com/s/v1/ig2/rmLAeYHP1zw8a_gsqawx74jvB3U6h5YWwbK54ldng_oL2VaWqFLQ3ZrcWPKZR_-gkx5KI3hNkjS2XGh6mygEo2w-.jpg?size=200x200&quality=95&crop=151,33,300,300&ava=1',
        bet: this.bet,
        color
      })
    }
  },
  computed: {
    bank() {
      var bank = 0
      this.bets.forEach((e) => {
        bank += e.bet
      })
      return bank
    }
  }
}
</script>
<style>
.fadeBet-enter-active {
  transition: transform .8s ease;
}

.fadeBet-leave-active {
  transition: transform .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}

.fadeBet-enter,
.fadeBet-leave-to

/* .slide-fade-leave-active до версии 2.1.8 */
  {
  transform: rotateY(-90deg);
}

.wheel {
  position: relative;
  width: 100%;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  -webkit-box-align: stretch;
  -ms-flex-align: stretch;
  align-items: stretch;
  padding-bottom: 50px;
}

.ww span {
  position: relative !important;
  font-size: 21px !important;
  font-weight: bold !important;
  line-height: 25px !important;
  margin: 0 !important;
  color: var(--bs-body-color) !important;
  opacity: 1 !important;
}

.wheel__body {
  width: 600px;
  height: 837px;
  position: relative;
}

.wheel__rotate {
  position: relative;
  height: 350px;
  overflow: hidden;
  margin-bottom: 30px;
}

.wheel__rotate svg {
  width: 100%;
  height: 720px;
  -webkit-transition: 5s ease-in-out;
  transition: 5s ease-in-out;
}

.wheel__rotate:before {
  content: '';
  position: absolute;
  left: -10px;
  bottom: 0;
  width: 140px;
  height: 70px;
  background: -webkit-gradient(linear, left bottom, left top, from(#f1f2fd), to(rgba(241, 242, 253, 0)));
  background: linear-gradient(to top, #f1f2fd, rgba(241, 242, 253, 0));
  z-index: 3;
}

.wheel__rotate:after {
  content: '';
  position: absolute;
  right: -10px;
  bottom: 0;
  width: 140px;
  height: 70px;
  background: -webkit-gradient(linear, left bottom, left top, from(#f1f2fd), to(rgba(241, 242, 253, 0)));
  background: linear-gradient(to top, #f1f2fd, rgba(241, 242, 253, 0));
  z-index: 3;
}

.wheel__separators {
  position: absolute;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  -webkit-box-align: end;
  -ms-flex-align: end;
  align-items: flex-end;
}

.wheel__separators svg {
  width: 86%;
  top: 0;
  position: absolute;
}

.wheel__cursor {
  position: absolute;
  width: 30px;
  height: 30px;
  background: url(/img/wheel.svg) no-repeat center center/100%;
  left: 50%;
  -webkit-transform: translateX(-44%);
  transform: translateX(-44%);
  top: 25%;
}

.wheel__center {
  position: absolute;
  top: 40%;
  width: 420px;
  height: 420px;
  border-radius: 50%;
  left: 50%;
  -webkit-transform: translateX(-50%);
  transform: translateX(-50%);
  background: linear-gradient(180.14deg, #EDEEFF 0.12%, rgba(237, 238, 255, 0) 49.88%);
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  -webkit-box-align: start;
  -ms-flex-align: start;
  align-items: flex-start;
  overflow: hidden;
  z-index: 1;
}

.wheel__center:before {
  content: '';
  position: absolute;
  left: 0px;
  top: 50px;
  z-index: 3;
  height: 80px;
  width: 150px;
  background: -webkit-gradient(linear, left top, right top, from(#f1f2fd), to(transparent));
  background: linear-gradient(to right, #f1f2fd, transparent);
}

.wheel__center:after {
  content: '';
  position: absolute;
  right: 0px;
  top: 50px;
  z-index: 3;
  height: 80px;
  width: 150px;
  background: -webkit-gradient(linear, right top, left top, from(#f1f2fd), to(transparent));
  background: linear-gradient(to left, #f1f2fd, transparent);
}

.wheel__bonus-slider {
  position: relative;
  width: 100%;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  height: 80px;
  margin-top: 50px;
}

.wheel__bonus-slider-list {
  overflow: hidden;
  width: 99999px;
  position: absolute;
  left: 0;
  top: 0;
  -webkit-transition: 5s ease-in-out;
  transition: 5s ease-in-out;
}

.wheel__slider-item {
  position: relative;
  width: 80px;
  height: 80px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  border-radius: 50%;
  background: #fff;
  font-size: 18px;
  font-weight: bold;
  float: left;
  margin: 0 7px;
}

.wheel__bonus-cursor {
  position: absolute;
  width: 80px;
  height: 80px;
  background: url(/img/bonus__wheel.svg) no-repeat center center/100%;
  z-index: 1;
  top: -50px;
}

.wheel__time {
  position: absolute;
  top: 40%;
  z-index: 1;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
}

.wheel__time span {
  position: relative;
  padding-left: 20px;
  font-weight: bold;
}

.wheel__progress-bar {
  position: relative;
  width: 150px;
  height: 8px;
  background: #DDDEEB;
  border-radius: 50px;
  overflow: hidden;
}

.wheel__progress-bar #wheel__progress {
  position: absolute;
  left: 0;
  top: 0;
  width: auto;
  height: 100%;
  background: #235cdc;
  border-radius: 50px;
}

.wheel__content {
  position: relative;
}

.wheel__stats {
  position: relative;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  margin-bottom: 30px;
}

.wheel__stat {
  position: relative;
  padding: 20px 28px;
  width: calc(50% - 5px);
  background: #FFFFFF;
  border-radius: 15px;
  padding-bottom: 12px;
}

.wheel__stat:nth-child(2):before {
  content: '';
  -webkit-transform: matrix(-1, 0, 0, 1, 0, 0);
  transform: matrix(-1, 0, 0, 1, 0, 0);
  left: -72px;
}

.wheel__stat:nth-child(2) {
  text-align: right;
}

.wheel__stat:nth-child(2) .icon {
  left: 25px;
  top: 20px;
}

.wheel__stat span {
  position: relative;
  line-height: 19px;
  font-weight: 500;
  opacity: .5;
  font-size: 14px;
}

.wheel__stat p {
  position: relative;
  font-size: 21px;
  font-weight: bold;
  line-height: 25px;
  margin: 0;
}

.wheel__stat .icon {
  position: absolute;
  right: 25px;
  top: 20px;
  color: #C8D0DC;
}

.wheel__stat:before {
  content: '';
  position: absolute;
  bottom: -65px;
  left: -46px;
  width: 140%;
  height: 90px;
  background: url(/img/stat-bg.svg) no-repeat bottom left;
  z-index: -1;
}

.wheel__container {
  background: #FFFFFF;
  padding: 0 28px;
  border-radius: 15px;
  position: relative;
}

.wheel__container:before {
  content: '';
  position: absolute;
  left: 0;
  width: 100%;
  height: 50px;
  background: url(/img/stat-bg-2.svg) no-repeat top left/100%;
  top: -48px;
  z-index: -1;
}

.wheel__history {
  position: relative;
  overflow: hidden;
  padding: 20px 0px;
  padding-bottom: 10px;
  width: 100%;
  display: inline-block;
}

.wheel__history:before {
  content: '';
  position: absolute;
  right: 0;
  top: 0;
  width: 150px;
  height: 100%;
  background: -webkit-gradient(linear, right top, left top, from(white), to(transparent));
  background: linear-gradient(to left, white, transparent);
  z-index: 2;
}

.wheel__history-list {
  position: relative;
  width: 99999px;
}

.wheel__history-item {
  position: relative;
  width: 30px;
  height: 10px;
  border-radius: 50px;
  display: inline-block;
  margin-right: 5px;
}

.wheel__bet-buttons {
  position: relative;
  width: 100%;
  display: -ms-grid;
  display: grid;
  -ms-grid-columns: (3fr)[3];
  grid-template-columns: repeat(3, 3fr);
  grid-gap: 12px;
  margin-bottom: 12px;
}

.wheel__form {
  position: relative;
}

.wheel__inputs {
  position: relative;
}

.wheel__btn {
  padding: 13px 0px;
  text-align: center;
  border-radius: 15px;
  color: #fff;
  font-weight: bold;
  cursor: pointer;
}

.wheel__inputs {
  position: relative;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}

.wheel__inputs input {
  position: relative;
  border: 0;
  font-family: inherit;
  background: transparent;
  height: 60px;
  line-height: 60px;
  padding-left: 23px;
  border-radius: 15px;
  background: rgba(229, 232, 255, 0.5);
  font-weight: 500;
  width: 61%;
  outline: none;
}

.wheel__inputs input::-webkit-input-placeholder {
  -webkit-transition: 0.3s ease;
  transition: 0.3s ease;
  color: #898EB0;
}

.wheel__inputs input:focus::-webkit-input-placeholder {
  opacity: 0;
}

.wheel__buttons {
  position: relative;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: start;
  -ms-flex-pack: start;
  justify-content: flex-start;
}

.wheel__buttons button {
  position: relative;
  border: 0;
  font-family: inherit;
  background: transparent;
  height: 60px;
  line-height: 60px;
  border: 2px solid #E6E8FF;
  width: 60px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  color: #AEB3D2;
  font-weight: 500;
  margin: 0 4px;
  border-radius: 15px;
  -webkit-transition: 0.3s ease;
  transition: 0.3s ease;
}

.wheel__buttons button:hover {
  background: rgba(230, 232, 255, 0.3);
  color: #2052f7;
}

.wheel__hash {
  position: relative;
  width: 100%;
  padding: 40px 0px;
  text-align: center;
}

.wheel__hash span {
  font-weight: 500;
  color: #1F2C5E;
}

.wheel__hash em {
  font-weight: normal;
  color: #BDC5DA;
  padding-left: 5px;
}

.wheel .icon {
  display: inline-block;
  width: 24px;
  height: 24px;
  stroke-width: 0;
  stroke: currentColor !important;
  fill: currentColor !important;
}

.wheel__bets-body {
  width: calc(100% - 600px - 50px);
  padding-top: 75px;
  height: 778px;
  position: relative;
}

.wheel__title {
  text-transform: uppercase;
  font-weight: bold;
  position: relative;
  color: #2e3956;
  padding-left: 25px;
  margin-bottom: 35px;
}

.wheel__title:before {
  content: '';
  position: absolute;
  width: 8px;
  height: 8px;
  left: 0;
  top: 7px;
  background: #16CEA2;
  border-radius: 50%;
  -webkit-animation: scale 1.5s infinite ease;
  animation: scale 1.5s infinite ease;
  -webkit-animation-direction: alternate;
  animation-direction: alternate;
}

@-webkit-keyframes scale {
  0% {
    -webkit-transform: scale(1);
    transform: scale(1);
  }

  100% {
    -webkit-transform: scale(1.3);
    transform: scale(1.3);
  }
}

@keyframes scale {
  0% {
    -webkit-transform: scale(1);
    transform: scale(1);
  }

  100% {
    -webkit-transform: scale(1.3);
    transform: scale(1.3);
  }
}

.wheel__bets-content {
  background: rgba(255, 255, 255, 0.3);
  -webkit-box-shadow: 0px 8px 46px rgba(0, 0, 0, 0.04);
  box-shadow: 0px 8px 46px rgba(0, 0, 0, 0.04);
  border-radius: 24px;
  height: 100%;
  position: relative;
}

.wheel__bets-content:before {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 200px;
  background: -webkit-gradient(linear, left bottom, left top, from(#f5f6fe), to(transparent));
  background: linear-gradient(to top, #f5f6fe, transparent);
  z-index: 1;
  border-radius: 0px 0px 24px 24px;
}

.wheel__bets-not-found {
  position: absolute;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}

.wheel__bets-not-found .icon {
  width: 132px;
  height: 132px;
  padding-bottom: 20px;
}

.wheel__bets-not-found span {
  position: relative;
  font-weight: 500;
}

.wheel__info-bet {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  color: #B7C4D7;
}

.wheel__info-bet .icon {
  color: #DBE4EF;
}

.wheel__bets {
  width: 100%;
  position: relative;
  overflow: auto;
  height: 100%;
}

.wheel__bet {
  padding: 20px 24px;
  position: relative;
  border-bottom: 1px solid #eef0f7;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}

.wheel__bet:nth-last-child(1) {
  border: 0;
}

.wheel__bet-profile {
  position: relative;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}

.wheel__ava {
  position: relative;
  width: 54px;
  height: 54px;
  border-radius: 50%;
  -webkit-filter: drop-shadow(0px 4px 10px rgba(0, 0, 0, 0.15));
  filter: drop-shadow(0px 4px 10px rgba(0, 0, 0, 0.15));
}

.wheel__profile-info {
  position: relative;
  line-height: 25px;
  padding-left: 20px;
}

.wheel__profile-info span {
  color: #2e3956;
  font-weight: 500;
  font-size: 14px;
}

.wheel__profile-info p {
  font-size: 14px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  background: transparent !important;
  margin-bottom: 0;
}

.wheel__profile-info b[data-color=wheel__color-x2] {
  background: transparent;
  color: #3079F2;
}

.wheel__profile-info b[data-color=wheel__color-x3] {
  background: transparent;
  color: #FAC257;
}

.wheel__profile-info b[data-color=wheel__color-x10] {
  background: transparent;
  color: #01A155;
}

.wheel__profile-info b[data-color=wheel__color-x15] {
  background: transparent;
  color: #8E2DE2;
}

.wheel__profile-info b[data-color=wheel__color-x20] {
  background: transparent;
  color: #FC3C00;
}

.wheel__profile-info b[data-color=wheel__color-x100] {
  background: transparent;
  color: #E02E2E;
}

.wheel__profile-info b {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: start;
  -ms-flex-pack: start;
  justify-content: flex-start;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  margin-left: 5px;
}

.wheel__profile-info .icon {
  width: 15px;
  height: 12px;
  margin-left: 5px;
}

.wheel__bet-color {
  width: 54px;
  height: 54px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  border-radius: 50%;
}

.wheel__mobile {
  position: relative;
  margin-bottom: 20px;
  margin-top: 0;
  display: none;
}

.wheel__mobile .wheel__time {
  width: 100%;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  position: relative;
}

.wheel__mobile .wheel__bonus-slider {
  height: 60px;
  margin-bottom: 20px;
  margin-top: 0;
}

.wheel__mobile .wheel__bonus-slider:before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  width: 220px;
  height: 100%;
  background: -webkit-gradient(linear, left top, right top, from(#f0f1fd), to(transparent));
  background: linear-gradient(to right, #f0f1fd, transparent);
  z-index: 2;
}

.wheel__mobile .wheel__bonus-slider:after {
  content: '';
  position: absolute;
  right: 0;
  top: 0;
  width: 220px;
  height: 100%;
  background: -webkit-gradient(linear, right top, left top, from(#f0f1fd), to(transparent));
  background: linear-gradient(to left, #f0f1fd, transparent);
  z-index: 2;
}

.wheel__mobile .wheel__overflow {
  width: 100%;
  position: relative;
  overflow: hidden;
  height: 60px;
}

.wheel__mobile .wheel__progress-bar {
  width: 65%;
}

.mt-5.wheel {
  margin-top: 0 !important;
}

a,
a:hover {
  text-decoration: none;
  color: #fff;
}

[data-color=wheel__color-x2] {
  background: linear-gradient(258.62deg, #1E54E1 6%, #3079F2 100%);
  color: #fff;
}

[data-color=wheel__color-x3] {
  background: linear-gradient(258.62deg, #F4A202 6%, #FAC257 100%), #EB6E6E;
  color: #fff;
}

[data-color=wheel__color-x10] {
  background: linear-gradient(258.62deg, #12B969 6%, #01A155 100%);
  color: #fff;
}

[data-color=wheel__color-x15] {
  background: linear-gradient(258.62deg, #4A00E0 6%, #8E2DE2 100%);
  color: #fff;
}

[data-color=wheel__color-x20] {
  background: linear-gradient(258.62deg, #FF8B4A 6%, #FF8B4A 6.01%, #FC3C00 100%);
  color: #fff;
}

[data-color=wheel__color-x100] {
  background: linear-gradient(258.62deg, #FF4B4D 6%, #E02E2E 100%);
  color: #fff;
}

@media only screen and (max-width: 1200px) {

  .wheel__stat:before,
  .wheel__stat:nth-child(2):before,
  .wheel__container:before {
    content: none;
  }

  .wheel__stats {
    margin-bottom: 10px;
  }

  .wheel__separators {
    display: none;
  }

  .wheel__slider-item {
    width: 60px;
    height: 60px;
  }

  .wheel__slider-item span {
    font-size: 14px;
  }

  .wheel__center {
    display: none;
  }

  .wheel__mobile {
    display: block;
  }

  .wheel {
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
  }

  .wheel__body {
    width: 100%;
  }

  .wheel__rotate {
    height: 150px;
  }

  .wheel__rotate:before {
    left: 110px;
  }

  .wheel__rotate:after {
    right: 110px;
  }

  .wheel__cursor {
    top: 35px;
  }

  .wheel__stat {
    padding: 20px 28px;
  }

  .wheel__bet-buttons {
    -ms-grid-columns: (6fr)[6];
    grid-template-columns: repeat(6, 6fr);
  }

  .wheel__inputs input {
    width: 76%;
  }

  .wheel__body,
  .wheel__bets-body,
  .wheel__bets-content {
    height: auto;
  }

  .wheel__bets-body {
    padding-top: 40px;
    width: 100%;
  }

  .wheel__bets-content:before {
    content: none;
  }

  .wheel__hash {
    padding: 25px 0px;
  }

  .wheel__rotate svg {
    height: auto;
  }

  .wheel__rotate:after {
    content: none;
  }

  .wheel__rotate:before {
    content: '';
    left: 0;
    bottom: 0;
    width: 100%;
  }
}

@media only screen and (max-width: 990px) {
  .mt-5.wheel {
    max-width: 95%;
  }

  .wheel {
    padding-top: 70px;
  }

  .wheel__mobile .wheel__progress-bar {
    width: 87%;
  }
}

@media only screen and (max-width: 600px) {
  .wheel__cursor {
    top: 20px;
  }

  .wheel__mobile .wheel__bonus-slider:before,
  .wheel__mobile .wheel__bonus-slider:after {
    width: 100px;
  }

  .wheel__inputs {
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
  }

  .wheel__inputs input {
    width: 100%;
    margin-bottom: 12px;
  }

  .wheel__buttons {
    width: 100%;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
  }

  .wheel__bet-buttons {
    -ms-grid-columns: (3fr)[3];
    grid-template-columns: repeat(3, 3fr);
  }

  .wheel__mobile .wheel__progress-bar {
    width: 60%;
  }
}

@media only screen and (max-width: 450px) {
  .wheel__stats {
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
  }

  .wheel__stat {
    width: 100%;
  }

  .wheel__stat:nth-child(1) {
    margin-bottom: 12px;
  }

  .wheel__stat:nth-child(2) {
    text-align: left;
  }

  .wheel__stat:nth-child(2) .icon {
    right: 25px;
    top: 20px;
    left: inherit;
  }

  .wheel__bet {
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
  }

  .wheel__bet-profile {
    width: 100%;
    margin-bottom: 12px;
  }

  .wheel__bet-color {
    width: 100%;
    border-radius: 5px;
    height: 35px;
  }

  .wheel__rotate {
    height: 90px;
  }

  .wheel__rotate:before {
    height: 30px;
  }
}
</style>