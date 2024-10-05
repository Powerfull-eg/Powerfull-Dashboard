<html>
  <head>
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  </head>
    <style>
      @font-face {
        font-family: 'abdo';
        src: url('/assets/fonts/ALFONT_COM_ALFONT_COM_ABDO-MASTER-DEMIBOLD_1-1.OTF');
      }
      body {
        text-align: center;
        padding: 40px 0;
        font-family: 'abdo','Nunito Sans', sans-serif;
        overflow: hidden;
        width: 110%;
      }
      .content {
        height: 100%;
        background-color: #ec711c;
        border-top-left-radius: 15rem;
        padding: 20rem 0;
        color: #ffffff
      }
    </style>
    <body>
      @php
        $lang = isset($_GET['lang']) && $_GET['lang'] == 'ar' ? 'ar': 'en';
      @endphp
      <header>
        <div class="d-flex flex-md-row-reverse align-items-center mb-3 bg-white justify-content-between header-container">
          {{-- Avatar --}}
          <div class="d-flex w-25 flex-column align-items-center fs-2 fw-bold">
            <img src="/assets/images/avatar.png" alt="user avatar" class="logo" style="height: 200px; width: 200px; border-radius: 50%">
            <span style="color: #ec711c">{{$lang == 'ar' ? "! مرحبا بك": 'Welcome !'}}</span>
            <span>{{$_GET['username'] ?? ''}}</span>
          </div>
          {{-- Logo --}}
          <div class="w-50">
            <img src="/assets/images/power logo.svg" style="width: 300px;" alt="Powerfull Logo">
          </div>
          {{-- Return button --}}
          <div class="m-auto w-25 close">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-left" width="130" height="130" viewBox="0 0 24 24" stroke-width="2.5" stroke="#ec711c" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M15 6l-6 6l6 6" />
            </svg>
          </div>  
        </div>
        {{-- Page Title --}}
        <div class="w-100">
          <span style="font-size: 3rem" class="fw-bold text-center">{{$lang == "ar" ? "الدفع" : "Payment"}}</span>
        </div>
      </header>
        {{-- End Header --}}
        {{-- Content --}}
        <div class="content mt-5" style="">
          {{-- Result --}}
          <div class="result fw-bold d-flex flex-column align-items-center" style="font-size: 4rem;">
            <span>{{isset($_GET["success"]) && $_GET["success"] === "true" ? ($lang == "ar" ? "البطاقة مقبولة" : "Card Accepted") : ($lang == "ar" ? "البطاقة غير مقبولة" : "Card Rejected")}}</span>
            {{-- Card Icon --}}
            <div class="position-relative">
              <img src="{{isset($_GET["success"]) && $_GET["success"] === "true" ? asset("assets/images/card-sucess.png") : asset("assets/images/card-failed.png")}}" alt="">
            </div>
            {{-- Below Text --}}
            <div class="d-flex flex-column fw-normal p-3">
              <span>{{isset($_GET["success"]) && $_GET["success"] === "true" ? ($lang == "ar" ? "تـم تـسجـيـل الـبطـاقـة" : "Card Added") : ($lang == "ar" ? "لم يتم تسجيل البطاقة" : "Failed to add Card")}}</span>
              @if (isset($_GET["success"]) && $_GET["success"] === "true")
                  <span style="font-size: 5rem" class="d-inline-block text-center fw-bold">{{$lang == "ar" ? "بنجاح" : "Successfully"}}</span>
              @endif
            </div>
          </div>
        </div>
        {{-- End Content --}}
      {{-- @if(isset($_GET["success"]) && $_GET["success"] === "true")
      @else
      @endif --}}
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <script>
        const url = new URL(location.href);
        const status = url.searchParams.get("success") === "true" ? "success" : "fail";
        window.parent.postMessage({status:status}, '*');
        window.addEventListener("message",(m) =>{console.log(m.data)});
    </script>
    </body>
</html>