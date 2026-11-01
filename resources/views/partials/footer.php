<?php /* Footer shared partial */ ?>
<footer style="background:var(--bg2);border-top:1px solid var(--border-s);margin-top:4rem;" role="contentinfo">
  <div class="max-w-6xl mx-auto px-4 py-12 grid grid-cols-2 md:grid-cols-4 gap-8">
    <div class="col-span-2 md:col-span-1">
      <div class="flex items-center gap-2.5 mb-3">
        <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0"
             style="background:linear-gradient(135deg,#7C3AED,#5B21B6);" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" class="w-3.5 h-3.5"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
        <span style="font-family:'Space Grotesk',sans-serif;font-weight:700;">AI<span style="color:#F59E0B;">Shop</span></span>
      </div>
      <p class="text-xs leading-relaxed" style="color:var(--muted);">AI订阅代充平台，支持ChatGPT、Claude、Grok等主流订阅，全自动发货。</p>
    </div>
    <div>
      <h4 class="text-xs font-semibold tracking-widest uppercase mb-4" style="color:var(--muted);">商品</h4>
      <ul class="space-y-2 text-sm" style="color:var(--muted);">
        <li><a href="<?= url('category/chatgpt') ?>" class="hover:text-white transition-colors">ChatGPT Plus</a></li>
        <li><a href="<?= url('category/claude') ?>" class="hover:text-white transition-colors">Claude Pro</a></li>
        <li><a href="<?= url('category/grok') ?>" class="hover:text-white transition-colors">Grok Premium</a></li>
        <li><a href="<?= url('category/midjourney') ?>" class="hover:text-white transition-colors">Midjourney</a></li>
      </ul>
    </div>
    <div>
      <h4 class="text-xs font-semibold tracking-widest uppercase mb-4" style="color:var(--muted);">支持</h4>
      <ul class="space-y-2 text-sm" style="color:var(--muted);">
        <li><a href="#" class="hover:text-white transition-colors">常见问题</a></li>
        <li><a href="#" class="hover:text-white transition-colors">Telegram 客服</a></li>
        <li><a href="#" class="hover:text-white transition-colors">服务状态</a></li>
      </ul>
    </div>
    <div>
      <h4 class="text-xs font-semibold tracking-widest uppercase mb-4" style="color:var(--muted);">关于</h4>
      <ul class="space-y-2 text-sm" style="color:var(--muted);">
        <li><a href="#" class="hover:text-white transition-colors">服务条款</a></li>
        <li><a href="#" class="hover:text-white transition-colors">隐私政策</a></li>
        <li><a href="#" class="hover:text-white transition-colors">退款政策</a></li>
      </ul>
    </div>
  </div>
  <div class="border-t max-w-6xl mx-auto px-4 py-4 flex flex-col sm:flex-row items-center justify-between gap-2" style="border-color:var(--border-s);">
    <p class="text-xs" style="color:var(--muted);">© 2024 AIShop. 本平台仅提供合法订阅充值服务，请遵守各平台用户协议。</p>
    <div class="flex items-center gap-1.5">
      <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:#10B981;" aria-hidden="true"></span>
      <span class="text-xs" style="color:#10B981;">服务运行正常</span>
    </div>
  </div>
</footer>
