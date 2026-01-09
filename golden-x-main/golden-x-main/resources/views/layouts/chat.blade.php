@php
$setting = \App\Setting::first();
@endphp
<div class="chat">
    <div class="chat__heading d-flex align-center justify-space-between">
        <div class="chat__online d-flex align-center">
            <div class="gx-con blue">
                <div class="icon lg"><svg class="icon">
                        <use xlink:href="/images/symbols.svg#support"></use>
                    </svg></div>
                <div class="title">
                    <div class="">
                        <span style="font-size: 14px;">Чат</span>
                        <div class="gx-row gx-gap-sm align-center text-gray">
                            <svg class="icon">
                                <use xlink:href="/images/symbols.svg#users"></use>
                            </svg>
                            <p class="online"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chat__buttons d-flex align-center justify-end">
            <a href="#" class="d-flex align-center justify-center" rel="popup" data-popup="popup--rules" onclick="return false;">
                <div class="icon"><svg class="icon">
                        <use xlink:href="/images/symbols.svg?v=21#rules"></use>
                    </svg></div>
            </a>
            <a href="#" class="d-flex align-center justify-center chatBtn">
                <div class="icon"><svg class="icon">
                        <use xlink:href="/images/symbols.svg?v=21#close"></use>
                    </svg></div>
            </a>
        </div>
    </div>
    <div class="chat__messages" ss-container>
    </div>
    <div class="chat__bottom">
        <div class="chat__send d-flex align-center justify-space-between">
            <div class="chat__input">
                <input autocomplete="off" type="text" onkeydown="if(event.keyCode==13){ disable(this);sendMess(this); }" id="messageChat" placeholder="Сообщение...">
            </div>
            <div class="chat__buttons d-flex align-center">
                <a class="d-flex align-center justify-center" id="btnStickers">
                    <svg class="icon">
                        <use xlink:href="/images/symbols.svg#stickers"></use>
                    </svg>
                </a>
                <a class="d-flex align-center justify-center" id="btnSmiles">
                    <svg class="icon">
                        <use xlink:href="/images/symbols.svg#smiles"></use>
                    </svg>
                </a>
            </div>
            <div class="chat__buttons d-flex align-center">
                <a onclick="disable(this);sendMess(this)" class="d-flex align-center justify-center send">
                    <svg class="icon">
                        <use xlink:href="/images/symbols.svg#send"></use>
                    </svg>
                </a>
            </div>
        </div>
        <div class="chat__smiles chat__smiles--smiles" ss-container>
            <div class="chat__smiles-scroll">
                @for ($i = 1; $i <= 59; $i++) <div onclick="AddSmile({{$i}})" class="chat__smiles-item d-flex align-center justify-center"><img src="/images/chat/smiles/{{$i}}.png"></div>
            @endfor
        </div>
    </div>
    <div class="chat__smiles chat__smiles--stickers" ss-container>
        <div class="chat__smiles-scroll">
            @for ($i = 1; $i <= 21; $i++) <div onclick="disable(this);sendSticker({{$i}}, this)" class="chat__smiles-item d-flex align-center justify-center"><img src="/images/chat/stickers/{{$i}}.png"></div>
        @endfor
    </div>
</div>
</div>
</div>