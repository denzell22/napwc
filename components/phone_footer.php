<head>
<style>
  .footer {
    background-color: #333;
    /*background-image: url('../images/footerbg.png'); */
    background-size: cover;
    color: black;
    padding: 30px 20px;
    text-align: center;
  }

  .footer .grid {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    gap: 10px;
  }

  .footer .box {
    text-align: left;
    list-style: none; /* Remove bullet points */
    padding: 0;
  }

  .footer .box h3 {
    font-family: Arial;
    font-size: 1.5rem;
    color: black;
    margin-bottom: 1.5rem;
    text-transform: capitalize;
  }

  .footer .box a {
    display: block;
    margin: 0.7rem 0;
    font-size: 1.2rem;
    color: black;
    text-decoration: none;
    transition: color 0.3s ease;

  }

  .footer .box p {
    font-size: 1.5rem;
    color: #4caf50; /* Green color */
    margin-bottom: 1.5rem;
    text-transform: capitalize;
  }

  .footer .box a:hover {
    color: #728156; /* Hover color: Lighter yellow */
  }

  .footer .credit {
    padding: 20px 0;
    border-top: 1px solid #B6C99C; /* Light gray border */
    font-size: 1rem;
    color: #B6C99C; /* Light gray color */
  }

  /* Media query for mobile phones */
  @media (max-width: 750px) {
    .footer .grid {
      flex-direction: column;
      text-align: left;
    }

    .footer .box {
      width: 100%;
      text-align: left;
    }
  }

  
</style>
</head>

<footer class="footer">
   <div class="grid">

      <ul class="box">
         <h3>Connect With Us!</h3>
         <li><a href="https://www.facebook.com/bmbnapwc">Facebook</a></li>
         <li><a href="https://www.instagram.com/parksandwildlifecenter/?hl=en">Instagram</a></li>
         <li><a href="https://www.youtube.com/@ninoyaquinoparksandwildlif6334">Youtube</a></li>
      </ul>


      <ul class="box">
         <h3>Bureaus</h3>
         <li><a href="http://www.bmb.gov.ph/"></i>Biodiversity Management</a></li>
         <li><a href="http://erdb.denr.gov.ph/">Ecosystems Research and Development</a></li>
         <li><a href="http://www.emb.gov.ph/">Environmental Management</a></li>
         <li><a href="http://forestry.denr.gov.ph/">Forestry Management</a></li>
         <li><a href="http://lmb.gov.ph/">Land Management</a></li>
         <li><a href="http://mgb.gov.ph/">Mines and Geosciences</a></li>
      </ul>

      <ul class="box">
         <h3>Regions</h3>
         <li><a href="https://www.denr.gov.ph/index.php/component/weblinks/?task=weblink.go&catid=12:regions&id=7:region-1">Region 1</a></li>
         <li><a href="https://www.denr.gov.ph/index.php/component/weblinks/?task=weblink.go&catid=12:regions&id=8:region-2">Region 2</a></li>
         <li><a href="https://www.denr.gov.ph/index.php/component/weblinks/?task=weblink.go&catid=12:regions&id=11:region-3">Region 3</a></li>
         <li><a href="https://www.denr.gov.ph/index.php/component/weblinks/?task=weblink.go&catid=12:regions&id=12:region-4a">Region 4A</a></li>
         <li><a href="https://www.denr.gov.ph/index.php/component/weblinks/?task=weblink.go&catid=12:regions&id=13:region-4b">Region 4B</a></li>
         <li><a href="https://www.denr.gov.ph/index.php/component/weblinks/?task=weblink.go&catid=12:regions&id=14:region-5">Region 5</a></li>
         <li><a href="https://www.denr.gov.ph/index.php/component/weblinks/?task=weblink.go&catid=12:regions&id=15:region-6">Region 6</a></li>
         <li><a href="https://www.denr.gov.ph/index.php/component/weblinks/?task=weblink.go&catid=12:regions&id=16:region-7">Region 7</a></li>
         <li><a href="https://www.denr.gov.ph/index.php/component/weblinks/?task=weblink.go&catid=12:regions&id=25:region-8">Region 8</a></li>
         <li><a href="https://www.denr.gov.ph/index.php/component/weblinks/?task=weblink.go&catid=12:regions&id=18:region-9">Region 9</a></li>
         <li><a href="https://www.denr.gov.ph/index.php/component/weblinks/?task=weblink.go&catid=12:regions&id=19:region-10">Region 10</a></li>
         <li><a href="https://www.denr.gov.ph/index.php/component/weblinks/?task=weblink.go&catid=12:regions&id=20:region-11">Region 11</a></li>
         <li><a href="https://www.denr.gov.ph/index.php/component/weblinks/?task=weblink.go&catid=12:regions&id=21:region-12">Region 12</a></li>
         <li><a href="https://www.denr.gov.ph/index.php/component/weblinks/?task=weblink.go&catid=12:regions&id=22:car">CAR</a></li>
         <li><a href="https://www.denr.gov.ph/index.php/component/weblinks/?task=weblink.go&catid=12:regions&id=23:caraga">CARAGA</a></li>
         <li><a href="https://www.denr.gov.ph/index.php/component/weblinks/?task=weblink.go&catid=12:regions&id=24:ncr">NCR</a></li>
      </div>

   </div>

      <div class="credit">&copy; Copyright @ <?= date('Y'); ?> by <span></span> | All rights reserved!</div>

</footer>
