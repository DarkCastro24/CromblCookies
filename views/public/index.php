<?php
// Se incluye la clase con las plantillas del documento.
require_once('../../app/helpers/public_page.php');
// Se imprime la plantilla del encabezado enviando el título de la página web.
Public_Page::headerTemplate('CromblCookies', null);
?>

<div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="../../resources/img/carousel/Carusel1.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="..." class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="..." class="d-block w-100" alt="...">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div><br>

<div class="container espacioIndex">
  <center>
    <h4><b>Example heading</b> <span class="badge bg-danger">New</span></h4>
  </center><br><br>
  <p class="lead">
    Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas excepturi, modi omnis expedita voluptate recusandae maxime, culpa nobis praesentium, dolore eligendi! Cupiditate quas deserunt sunt sed dignissimos sint dolores minus alias, autem fugit, vero veniam a neque ab dicta odit temporibus enim! Qui, debitis reprehenderit nam ratione ea magnam quod!
  </p><br>
  <div class="row">

    <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
      <div class="card" style="width: 18rem;">
        <img src="../../resources/img/carousel/procesadores.jpg" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">Card title</h5>
          <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
          <center><a href="#" class="btn btn-dark">Go somewhere</a></center>
        </div>
      </div><br>
    </div>

    <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
      <div class="card" style="width: 18rem;">
        <img src="../../resources/img/carousel/procesadores.jpg" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">Card title</h5>
          <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
          <center><a href="#" class="btn btn-dark">Go somewhere</a></center>
        </div>
      </div><br>
    </div>

    <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
      <div class="card" style="width: 18rem;">
        <img src="../../resources/img/carousel/procesadores.jpg" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">Card title</h5>
          <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
          <center><a href="#" class="btn btn-dark">Go somewhere</a></center>
        </div>
      </div><br>
    </div>

    <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
      <div class="card" style="width: 18rem;">
        <img src="../../resources/img/carousel/procesadores.jpg" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">Card title</h5>
          <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
          <center><a href="#" class="btn btn-dark">Go somewhere</a></center>
        </div>
      </div><br>
    </div>
  </div>
</div>


<div class="container px-4 py-5" id="hanging-icons">
  <h2 class="pb-2 border-bottom">Hanging icons</h2>
  <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
    <div class="col d-flex align-items-start">
      <div class="icon-square bg-light text-dark flex-shrink-0 me-3">
        <svg class="bi" width="1em" height="1em">
          <use xlink:href="#toggles2" />
        </svg>
      </div>
      <div>
        <h2>Featured title</h2>
        <p>Paragraph of text beneath the heading to explain the heading. We'll add onto it with another sentence and probably just keep going until we run out of words.</p>
        <a href="#" class="btn btn-dark">
          Primary button
        </a>
      </div>
    </div>
    <div class="col d-flex align-items-start">
      <div class="icon-square bg-light text-dark flex-shrink-0 me-3">
        <svg class="bi" width="1em" height="1em">
          <use xlink:href="#cpu-fill" />
        </svg>
      </div>
      <div>
        <h2>Featured title</h2>
        <p>Paragraph of text beneath the heading to explain the heading. We'll add onto it with another sentence and probably just keep going until we run out of words.</p>
        <a href="#" class="btn btn-dark">
          Primary button
        </a>
      </div>
    </div>
    <div class="col d-flex align-items-start">
      <div class="icon-square bg-light text-dark flex-shrink-0 me-3">
        <svg class="bi" width="1em" height="1em">
          <use xlink:href="#tools" />
        </svg>
      </div>
      <div>
        <h2>Featured title</h2>
        <p>Paragraph of text beneath the heading to explain the heading. We'll add onto it with another sentence and probably just keep going until we run out of words.</p>
        <a href="#" class="btn btn-dark">
          Primary button
        </a>
      </div>
    </div>
  </div>
</div>


<div class="container px-4 py-5" id="custom-cards">
  <h2 class="pb-2 border-bottom">Custom cards</h2>

  <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 align-items-stretch g-4 py-5">
    <div class="col">
      <div class="card card-cover h-100 overflow-hidden text-white bg-dark rounded-5 shadow-lg" style="background-image: url('unsplash-photo-1.jpg');">
        <div class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
          <h2 class="pt-5 mt-5 mb-4 display-6 lh-1 fw-bold">Short title, long jacket</h2>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card card-cover h-100 overflow-hidden text-white bg-dark rounded-5 shadow-lg" style="background-image: url('unsplash-photo-1.jpg');">
        <div class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
          <h2 class="pt-5 mt-5 mb-4 display-6 lh-1 fw-bold">Short title, long jacket</h2>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card card-cover h-100 overflow-hidden text-white bg-dark rounded-5 shadow-lg" style="background-image: url('unsplash-photo-1.jpg');">
        <div class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
          <h2 class="pt-5 mt-5 mb-4 display-6 lh-1 fw-bold">Short title, long jacket</h2>
        </div>
      </div>
    </div>
  </div>
</div>


<div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active" data-bs-interval="10000">
      <img src="../../resources/img/carousel/fuentes.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item" data-bs-interval="2000">
      <img src="../../resources/img/carousel/motherboard.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="../../resources/img/carousel/procesadores.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="../../resources/img/carousel/tarjetas.jpg" class="d-block w-100" alt="...">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>


<?php
// Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
Public_Page::footerTemplate('index.js');
?>