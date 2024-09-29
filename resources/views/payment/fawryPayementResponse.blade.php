<html>
  <head>
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  </head>
    <style>
      body {
        text-align: center;
        padding: 40px 0;
        background: #EBF0F5;
      }
        h1 {
          color: #88B04B;
          font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
          font-weight: 900;
          font-size: 70px;
          margin-bottom: 10px;
        }
        p {
          color: #404F5E;
          font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
          font-size:50px;
          margin: 0;
         font-weight: 600;
         margin: 100px;
         display: block;
        }
      i {
        color: #88B04B;
        font-size: 200px;
        line-height: 400px;
        margin-left:-15px;
      }
      .card {
        background: white;
        padding: 60px;
        border-radius: 4px;
        box-shadow: 0 2px 3px #C8D0D8;
        display: inline-block;
        margin: 0 auto;
        width: 100vw;
        height: 100vh;
      }
    </style>
    <body>
        @if(isset($_GET["success"]) && $_GET["success"] === "true")
          <div class="card">
              <img src="/assets/images/power logo.svg" class="img-fluid d-block mx-auto w-50 m-5 " alt="PowerFull Logo"  >
              <div style="border-radius:200px; height:400px; width:400px; background: #F8FAF5; margin:0 auto;">
                <i class="checkmark">✓</i>
              </div>
                <h1>Success</h1> 
                <p>Your card has been added successfully</p>
          </div>
        @else
        <div class="card">
          <img src="/assets/images/power logo.svg" class="img-fluid d-block mx-auto w-75 m-5 " alt="PowerFull Logo"  >
          <div style="border-radius:200px; height:400px; width:400px; background: #F8FAF5; margin:0 auto;">
            <i class="checkmark fail text text-danger">&#9747;</i>
          </div>
            <h1 class="text text-danger m-2">Payment Declined</h1> 
            <p>Failed to add your card <br> Please try again later <br> or contact your issuer bank</p>
        </div>
      @endif
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