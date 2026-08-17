{{--
    Information セクション共通のフッターナビ(Home / Post / Map)。
    Carinderia・Restaurant&Cafe・Other・Travelの4ページで共通して使う。
    今後アドミンが新しいメインカテゴリーを追加した時も、新しいページはこれを
    @include('information.footer.footer_nav') するだけでOK。
--}}
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
<nav class="fixed bottom-0 w-full z-50 bg-[#334155] shadow-[0_-4px_20px_-4px_rgba(30,58,138,0.15)] flex justify-around items-center h-20 px-4 pb-2 border-t border-slate-600/40">
  <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center gap-1 text-white/70 hover:text-white px-4 py-1 active:scale-90 transition-all duration-200">
    <i class="fa-solid fa-house text-[20px]"></i>
    <span class="text-[10px] font-bold tracking-wide" style="font-family:'Poppins','Noto Sans JP',sans-serif;">Home</span>
  </a>

  <a href="{{ route('information.create') }}" class="flex flex-col items-center justify-center gap-1 text-white/70 hover:text-white px-4 py-1 active:scale-90 transition-all duration-200">
    <div class="w-14 h-14 -mt-8 rounded-full flex items-center justify-center shadow-[0_12px_32px_-12px_rgba(30,58,138,0.35)] border-4 border-[#334155]"
         style="background: linear-gradient(135deg, #2f5fdb 0%, #e05237 33%, #f5b52e 66%, #5eab35 100%);">
      <i class="fa-solid fa-plus text-white text-[20px]"></i>
    </div>
    <span class="text-[10px] font-bold tracking-wide mt-1" style="font-family:'Poppins','Noto Sans JP',sans-serif;">Post</span>
  </a>

  <a href="{{ route('earth') }}" class="flex flex-col items-center justify-center gap-1 text-white/70 hover:text-white px-4 py-1 active:scale-90 transition-all duration-200">
    <i class="fa-solid fa-globe text-[20px]"></i>
    <span class="text-[10px] font-bold tracking-wide" style="font-family:'Poppins','Noto Sans JP',sans-serif;">Map</span>
  </a>
</nav>
