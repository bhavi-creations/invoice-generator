<?php
  echo "invoice generator";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>

   <!-- BOOTSTRAP PLUGIN -->

   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

   <!-- ADDING STYLE SHEET  -->

   <link rel="stylesheet" href="img/style.css">
   

</head>
<body>
    <!--  LARGE SCREEN NAVBAR  --> 
    <header> 

      <nav class="navbar navbar-expand-lg navbar-light bg-light d-none d-lg-block">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img src="img/Bhavilogo.jpeg" alt="" height="50%" width="50%"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse mr-auto" id="navbarNav">
                <ul class="navbar-nav navbarleft">
                    <li class="nav-item">
                        <a class="nav-link active text-dark pe-5 me-5" aria-current="page" href="#">CREATE INVOICE</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark pe-5" href="#">VIEW INVOICES</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark ps-5" href="#">VIEW CUSTOMERS</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- SMALL SCREEN AND MEDIUM SCREEN  NAVBAR -->

    <nav class="navbar navbar-expand-lg navbar-light bg-light d-block d-lg-none ">
      <div class="container-fluid">
        <div class="navbar-header">
          <!-- <a class="navbar-brand" href="#"><img src="img/Bhavi-Logo-2.png" alt="" height="50%" width="50%"></a> -->
          <a class="navbar-brand" href="#">Navbar</a>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#">CREATE INVOICE</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">VIEW INVOICES</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">VIEW CUSTOMERS</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    </header>

    <!--  INVOICE  FORM  -->
    <section >
      <div class="container pt-5">
        
        <form class="border rounded p-4">
          <img src="img/Bhavilogo.jpeg" alt="" class="mx-auto d-block" height="30%" width="30%">
          <hr>
          <div class="row container-fluid">
              <div class="col-lg-6"> 
                <h4>INVOICE</h4> 
              </div> 
              <div class="col-lg-6"> 
                <h4>INVOICE NUMBER : BHAVI_KKD_2023</h4>
              </div>
          </div>
          <hr>
          <div class="row container-fluid">
            <div class="col-lg-6"> 
               <h6><strong>Bhavi Creations Pvt Ltd </strong> </h6> 
               <h6>Plot no28, H No70, 17-28, RTO Office Rd, opposite to New </h6> 
               <h6>RTO Office, behind J.N.T.U Engineering College Play Ground,</h6>  
               <h6> RangaRaoNagar,Kakinada,AndhraPradesh533003</h6> 
               <h6>Phone no.: 9642343434</h6> 
               <h6>Email: admin@bhavicreations.com</h6> 
               <h6>GSTIN: 37AAKCB6960H1ZB.</h6>   
             </div> 
              <div class="col-lg-6"> 
                <h6><strong>Customer Details</strong> </h6> 
                <h6> <input type="text" placeholder="Name" style="border: none;" ></h6>
                <h6> <input type="text" placeholder="Phone number" style="border: none;" ></h6>
                <h6> <input type="text" placeholder="Adress" style="border: none;" ></h6>
              </div>
          </div>
          <hr>
          <h3 class="text-center"><B>BILLING</B></h3>
          <div class="row">
            <div class="col-lg-6">
              <select>
                <option value="option1" selected>Option 1</option>
                <option value="option2">Option 2</option>
                <option value="option3" selected>Option 3</option>
            </select>
            </div>
            <div class="col-lg-6">
             <h6>Description</h6>
             <input type="text">
             <h6>Quality</h6>
             <input type="text">
             <h6>Quantity</h6>
             <input type="text">
             <h6>Price</h6>
             <input type="text">
             <h6>Description</h6>
             <input type="text">
            </div>
          </div>
      </form>
      
        </div>
      </div>
    </section>
</body>
</html>