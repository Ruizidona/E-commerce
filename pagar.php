<?php
include "./php/conexion.php";
if(!isset($_GET['id_venta'])){
    header("Location: ./");
}
$datos = $conexion->query("select ventas.*,
        usuario.nombre,usuario.telefono,usuario.email
        from ventas
        inner join usuario on ventas.id_usuario = usuario.id
        where ventas.id=".$_GET['id_venta'])or die($conexion->error);
$datosUsuario = mysqli_fetch_row($datos);
$datos2 = $conexion->query("select * from envios where id_venta=".$_GET['id_venta'])or die($conexion->error);
$datosEnvio = mysqli_fetch_row($datos2);
$datos3 = $conexion->query("select productos_venta.*,
                productos.nombre as nombre_producto, productos.imagen
                from productos_venta inner join productos on productos_venta.id_producto = productos.id
                where id_venta =".$_GET['id_venta'])or die($conexion->error);

                #############
$total = $datosUsuario[2];
$descuento = "0";
$banderadescuento = false;

if($datosUsuario[6] != 0){
  $banderadescuento = true;
  $cupon = $conexion->query("select * from cupones where id= ".$datosUsuario[6]);
  $filaCupon =  mysqli_fetch_row($cupon);
  if($filaCupon[3] == "moneda"){
    $total= $total - $filaCupon[4];
    $descuento = $filaCupon[4]."ARS";
  }else{
    $total = $total - ($total * ($filaCupon[4]/100));
    $descuento = $filaCupon[4]."%";
  }
  
}

// SDK de Mercado Pago
require __DIR__ .  '/vendor/autoload.php';

// Agrega credenciales
MercadoPago\SDK::setAccessToken('TEST-2391154182610770-021522-9f4fb6f37fe50d273e586dd57f465661-715939022');

// Crea un objeto de preferencia
$preference = new MercadoPago\Preference();
$preference->payment_methods = array(
  "excluded_payment_types" => array(
    array("id" => "ticket")
  ),
  "installments" => 12
);
$preference->back_urls = array(
    "success" => "https://localhost/carrito/thankyou.php?id_venta=".$_GET['id_venta']."&metodo=mercado_pago",
    "failure" => "https://localhost/carrito/errorpago.php?error=failure",
    "pending" => "https://localhost/carrito/errorpago.php?error=pending",
);
$preference->auto_return = "approved";

// Crea un ítem en la preferencia
$datos=array();
if($banderadescuento){
  $item = new MercadoPago\Item();
  $item->title =  "producto con descuento";
  $item->quantity =  1;
  $item->unit_price = $total;
  $datos[]=$item;
}else{
while($f = mysqli_fetch_array($datos3)){
        $item = new MercadoPago\Item();
        $item->title =  $f['nombre_producto'];;
        $item->quantity =  $f['cantidad'];;
        $item->unit_price = $f['precio'];;
        $datos[]=$item;
    }
  }



$preference->items = $datos;
$preference->save();

#Tiene que estar en comillas dobles lo que esta en simples y viceversa por la notacion#

/*curl -X POST -H "Content-Type: application/json" "https://api.mercadopago.com/users/test_user?access_token=TEST-7160821895716887-021521-a222b099ad5c27f84e3c0a7bf615491e-151493379" -d "{'site_id':'MLA'}"
#Dos usuarios de prueba#
{"id":715939022,"nickname":"TT557583","password":"qatest9864","site_status":"active","email":"test_user_83227278@testuser.com"}
{"id":715936073,"nickname":"TESTTEUBSGWK","password":"qatest4776","site_status":"active","email":"test_user_18460016@testuser.com"}

*/
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Metodo de pago</title>

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
</head>
<body>



<div class="site-wrap">
  <?php include("./layouts/header.php"); ?> 

    <div class="site-section">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h2 class="h3 mb-3 text-black">Elige metodo de pago</h2>
          </div>
          <div class="col-md-7">

            <form action="#" method="post">
              
              <div class="p-3 p-lg-5 border">
                <div class="form-group row">
                  <div class="col-md-12">
                    <label for="c_fname" class="text-black">Venta nro <?php echo $_GET['id_venta'];  ?> </label>
                  </div>
                </div>

                
                <div class="form-group row">
                  <div class="col-md-12">
                    <label for="c_fname" class="text-black">Nombre: <?php echo $datosUsuario[6];  ?> </label>
                  </div>
                </div>
                

                
                <div class="form-group row">
                  <div class="col-md-12">
                    <label for="c_fname" class="text-black">Email: <?php echo $datosUsuario[8];  ?> </label>
                  </div>
                </div>
                
                <div class="form-group row">
                  <div class="col-md-12">
                    <label for="c_fname" class="text-black">Telefono: <?php echo $datosUsuario[7];  ?> </label>
                  </div>
                </div>
                
                
                <div class="form-group row">
                  <div class="col-md-12">
                    <label for="c_fname" class="text-black">Company(opcional): <?php echo $datosEnvio[2];  ?> </label>
                  </div>
                </div>

                
                <div class="form-group row">
                  <div class="col-md-12">
                    <label for="c_fname" class="text-black">Direccion: <?php echo $datosEnvio[3];  ?> </label>
                  </div>
                </div>

                
                <div class="form-group row">
                  <div class="col-md-12">
                    <label for="c_fname" class="text-black">Ciudad: <?php echo $datosEnvio[4];  ?> </label>
                  </div>
                </div>

              </div>
            </form>
          </div>
          <div class="col-md-5 ml-auto">
          
            <h4 class="h1">Total: <?php echo $datosUsuario[2];?></h4>
            <h5>Descuento: <?php echo $descuento;?></h5>
            <h5>Total Final: <?php echo $total;?></h5>
            
            <!--FORM DE MERCADOPAGO-->
            <form action="https://localhost/carrito/thankyou.php?id_venta=<?php echo $_GET['id_venta']?>&metodo=mercado_pago" method="POST">
                <img src="./images/MercadoPago.png" alt="MercadoPago" width="150px;">
                <script
                src="https://www.mercadopago.com.ar/integrations/v1/web-payment-checkout.js"
                data-preference-id="<?php echo $preference->id; ?>"
                data-elements-color="#E5A65E">
                </script>
            </form>

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

        