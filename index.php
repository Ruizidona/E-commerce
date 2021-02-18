<?php

include './php/conexion.php';



?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Tienda</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mukta:300,400,700"> 
    <link rel="stylesheet" href="fonts/icomoon/style.css">

   <!--scroll no funciona en edge-->
 <style>
   html{
     scroll-behavior: smooth;
   }
 </style>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">


    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/style.css">

  <style>

#producto img {
    float: center;
    width:  100%;
    height: 350px;
    object-fit: cover;
    overflow: hidden;
}

  </style>
    
  </head>
  <body>

  <div class="site-wrap">
    <?php include("./layouts/header.php"); ?> 
    <div class="site-blocks-cover" style="background-image: url(images/hero_1.jpg);" data-aos="fade">
        <div class="row align-items-start align-items-md-center justify-content-end">
          <div class="col-md-5 text-center text-md-left pt-5 pt-md-0">
            <h1 class="mb-2" style="color:white;">Encontra los mejores vinos</h1>
            <div class="intro-text text-center text-md-left">
              <p class="mb-4"style="color:white;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus at iaculis quam. Integer accumsan tincidunt fringilla. </p>
              <p>
                <a href="#bajar" class="btn btn-sm btn-secondary">Ver Catálogo</a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

 

    <div class="site-section" id="#bajar">
      <div class="container">

        <div class="row mb-5">
          <div class="col-md-9 order-2">


            <div class="row mb-5">

            

            <?php
            include('./php/conexion.php');
            /*for($i=0;$i<50;$i++){
              $conexion->query("insert into productos (nombre, descripcion, precio, imagen, inventario, id_categoria, talla, color) values(
                'Producto $i','Esta es la descripcion', ".rand(10,1000).", 'cloth_2.jpg', ".rand(1,100).", 1, 'XL', 'GReen' 
              ) ")or die($conexion->error);
            }*/
            $limite = 6; //productos por pagina
            $totalQuery = $conexion->query("select count(*) from productos ORDER BY id DESC")or die($conexion->error);
            $totalProductos = mysqli_fetch_row ($totalQuery);
            $totalBotones = round($totalProductos[0] / $limite);
            if(isset($_GET['limite'])){
              $resultado = $conexion ->query("select * from productos where inventario > 0 order by id DESC limit ".$_GET['limite'].",".$limite) or die($conexion -> error);
            }else{
              $resultado = $conexion ->query("select * from productos where inventario > 0 order by id DESC limit ".$limite) or die($conexion -> error);
            }
           
           // die($totalBotones);
           
            while($fila = mysqli_fetch_array($resultado)){
            ?>
              <?php
              $stock = $fila['inventario'];
              
              ?>
              <div class="col-sm-6 col-lg-4 mb-4" data-aos="fade-up" >
                <div class="block-4 text-center border">
                  <figure class="block-4-image">
                    <a  id="producto" href="shop-single.php?id=<?php echo $fila['id']; ?>"><img src="images/<?php echo $fila['imagen']; ?>" alt="<?php echo $fila['nombre']; ?>" class="img-fluid" ></a>
                  </figure>
                  <div class="block-4-text p-4">
                    <h3><a href="shop-single.php?id=<?php echo $fila['id']; ?>"><?php echo $fila['nombre']; ?></a></h3>
                    <p class="mb-0"><?php echo $fila['descripcion']; ?></p>
                    <p class="text-primary ">Productos disponibles:<?php echo $stock?></p>
                    <p class="text-primary font-weight-bold mt-1">$<?php echo $fila['precio']; ?></p>
      
                    <p><button onclick="window.location.href='cart.php?id=<?php echo $fila[0]; ?>'" class="buy-now btn btn-sm btn-primary" <?php  echo ($stock <= 0) ? 'disabled="true"' : ''?> >Agregar al carrito</button></p>
                  </div>
                </div>
              </div>
              
              <?php } ?>


            </div>
            <div class="row" data-aos="fade-up">
              <div class="col-md-12 text-center">
                <div class="site-block-27">
                  <ul>
                    
                    <?php
                    //PAGINACIÓN
                    if(isset($_GET['limite'])){
                      if($_GET['limite']>0){
                       echo '<li><a href="index.php?limite='.($_GET['limite']-6).'">&lt;</a></li>';
                      }
                    }
                    for($k = 0;$k<$totalBotones;$k++){
                      echo '<li><a href="index.php?limite='.($k*6).'">'.($k+1).'</a></li>';
                    }
                    if(isset($_GET['limite'])){
                      if($_GET['limite']+6 < $totalBotones*9){
                        echo  '<li><a href="index.php?limite='.($_GET['limite']+6).'">&gt;</a></li>';
                      }
                    }else{
                      echo  '<li><a href="index.php?limite=9">&gt;</a></li>';
                    }

                    ?>
                   
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-3 order-1 mb-5 mb-md-0">
            <div class="border p-4 rounded mb-4">
              <h3 class="mb-3 h6 text-uppercase text-black d-block">Categorías</h3>
              <ul class="list-unstyled mb-0">
              <?php
              $re= $conexion->query("select * from categorias");
              while($f=mysqli_fetch_array($re)){  
              ?>
                <li class="mb-1"><a href="./busqueda.php?texto=<?php echo $f['nombre']?>" class="d-flex">
                <span>  <?php echo $f['nombre'];?></span> 
                <span class="text-black ml-auto">  
                <?php $re2= $conexion->query("select count(*) from productos where id_categoria = ".$f['id']);
                      $fila = mysqli_fetch_row($re2);
                      echo $fila[0];
                ?></span></a></li>
                <?php } ?>
              </ul>    
            </div>



            
            <div class="border p-4 rounded mb-4">
              <div class="mb-4">
              <div class="col-12 order-2 order-md-1 site-search-icon text-left">
              <form action="./busqueda.php" class="site-block-top-search mt-4" method="GET">
                <span class="icon icon-search2"></span>
                <input type="text" class="form-control border-2" placeholder="Buscar" name="texto">
              </form>
            </div>
               <!-- <h3 class="mb-3 h6 text-uppercase text-black d-block">Filter by Price</h3>
                <div id="slider-range" class="border-primary"></div>
                <input type="text" name="text" id="amount" class="form-control border-0 pl-0 bg-white" disabled="" />-->
              </div>
              </div>
              <div class="border p-4 rounded mb-4">
              <div class="mb-4">
                <h3 class="mb-3 h6 text-uppercase text-black d-block">Tamaño</h3>
                <label for="s_sm" class="d-flex">
                <a href="./busqueda.php?texto=Litro" class="ml-3">
                  <input type="hidden" id="s_sm" class="mr-2 mt-1"> <span class="text-black">Litro</span>
                </a>
                </label>
                <label for="s_sm" class="d-flex">
                <a href="./busqueda.php?texto=600" class="ml-3">
                  <input type="hidden" id="s_sm" class="mr-2 mt-1"> <span class="text-black">600mm</span>
                </a>
                </label>
                <label for="s_sm" class="d-flex">
                <a href="./busqueda.php?texto=350" class="ml-3">
                  <input type="hidden" id="s_sm" class="mr-2 mt-1"> <span class="text-black">350mm</span>
                </a>
                </label>
              </div>

              <div class="mb-4">
                <h3 class="mb-3 h6 text-uppercase text-black d-block">Marcas</h3>
                <?php
                $re = $conexion->query("SELECT * from marcas");
                while($f=mysqli_fetch_array($re)){
                ?>
                  <a href="./busqueda.php?texto=<?php echo $f['marca'];?>" class="d-flex color-item align-items-center" >
                  <span style="background-color:<?php echo $f['color'];?>" class="color d-inline-block rounded-circle mr-2"></span> <span class="text-black"><?php echo $f['marca'];?></span>
                </a>
                <?php } ?>            
              </div>
              </div>
            </div>
          </div>
        </div>
                  
        <div class="container" id="#categorias">
        

        <div class="row">
          <div class="col-md-12">
            <div class="site-section site-blocks-2">
                <div class="row justify-content-center text-center mb-5">
                  <div class="col-md-7 site-section-heading pt-4">
                    <h2>Categorías</h2>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6 col-md-6 col-lg-4 mb-4 mb-lg-0" data-aos="fade" data-aos-delay="">
                    <a class="block-2-item" href="./busqueda.php?texto=vino">
                      <figure class="image">
                        <img src="images/tinto.jpg" alt="" class="img-fluid">
                      </figure>
                      <div class="text">
                        <span class="text-uppercase">Collections</span>
                        <h3>Vinos</h3>
                      </div>
                    </a>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-4 mb-5 mb-lg-0" data-aos="fade" data-aos-delay="100">
                    <a class="block-2-item" href="./busqueda.php?texto=espumante">
                      <figure class="image">
                        <img src="images/espumoso.jpg" alt="" class="img-fluid">
                      </figure>
                      <div class="text">
                        <span class="text-uppercase">Collections</span>
                        <h3>Espumantes</h3>
                      </div>
                    </a>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-4 mb-5 mb-lg-0" data-aos="fade" data-aos-delay="200">
                    <a class="block-2-item" href="./busqueda.php?texto=licor">
                      <figure class="image">
                        <img src="images/licores.jpg" alt="" class="img-fluid">
                      </figure>
                      <div class="text">
                        <span class="text-uppercase">Collections</span>
                        <h3>Licores</h3>
                      </div>
                    </a>
                  </div>
                </div>
                </div>
            </div>
          </div>
        
        </div>         
      </div>
    </div>
    <?php include("./layouts/footer.php"); ?> 

    
  </div>

  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>

  <script src="js/main.js"></script>
    
  </body>
</html>