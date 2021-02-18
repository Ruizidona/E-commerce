<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Tienda</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mukta:300,400,700"> 
    <link rel="stylesheet" href="fonts/icomoon/style.css">

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
    width:  50%;
    height: 400px;
    object-fit: cover;
    overflow: hidden;
}

  </style>    
  </head>
  <body style="background-color:black;">

  <div class="site-wrap">
    <?php include("./layouts/headerBlack.php"); ?> 
    <hr class="mt-4">
    <h2 class="text-center mt-5 h1" style="color:#E5A65E;">NEW ARRIVALS</h2>
    <hr >

    <div class="site-section">
      <div class="container" >

        <div class="row mb-5">
          <div class="order-2">


            <div class="row mb-5">

            

            <?php
            include('./php/conexion.php');

            $limite = 3; //productos por pagina
            $totalQuery = $conexion->query("select count(*) from productos")or die($conexion->error);
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

              <div class="col-sm-6 col-lg-4 mb-4" data-aos="fade-up" id="bajar" >
                <div class="block-4 text-center border">
                  <figure class="block-4-image">
                    <a  id="producto" href="shop-single.php?id=<?php echo $fila['id']; ?>"><img src="images/<?php echo $fila['imagen']; ?>" alt="<?php echo $fila['nombre']; ?>" class="img-fluid"></a>
                  </figure>
                  <div class="block-4-text p-4">
                    <h3><a style="color:black;" href="shop-single.php?id=<?php echo $fila['id']; ?>"><?php echo $fila['nombre']; ?></a></h3>
                    <p class="mb-0"style="color:black;"><?php echo $fila['descripcion']; ?></p>
                    <p class="text font-weight-bold"style="color:#E5A65E;">$<?php echo $fila['precio']; ?></p>
                    <p><a href="cart.php?id=<?php echo $fila[0]; ?>" class="buy-now btn btn-sm"style="background-color:#E5A65E; color:white;">Agregar al carrito</a></p>
                  </div>
                </div>
              </div>
              
              <?php } ?>


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