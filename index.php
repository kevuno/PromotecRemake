<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Promotec</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css">
    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Personalized styles -->
    <link href="assets/css/style.css" rel="stylesheet">
    <!-- Responsive styles -->
    <link href="assets/css/responsive_style.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <!-- MDB core CSS -->
    <link href="assets/css/mdb.min.css" rel="stylesheet" />
    <!-- Google captcha -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>


</head>

<body>
    <div id="main_container">

        <!--Navbar-->
        <div class="container-fluid">
            <div class="row navbar-promotec">
                <div class="col-8">
                    <img src="assets/img/promotec-banner.png" class="promotec-img" alt="">
                </div>
                <div class="col-4">
                    <div class="button-wrapper">           
                        <button class="btn btn-warning btn-sm btn-responsive" @click="openLogin">Acceso</button>
                    </div>            
                </div>            
            </div>
        </div>
        <!--/Navbar-->

        <!-- Contenido -->
        <div id="content">
            <!--Ganar dinero-->
            <div class="wow fadeIn" id="ganar_dinero-banner">
                <div class="container">
                        <div class="row justify-content-center">
                              <span>¡INICIA TU PROPIO NEGOCIO!</span>
                        </div>               
                </div>
            </div>
            <!--/Ganar dinero-->
            <!-- Floating social media buttons-->
            <div class="sticky-container">
                <ul class="sticky">
                    <li>
                        <span><img src="assets/img/facebook.png" width="48" height="48">
                        </span><a href="https://www.facebook.com/PromotorPromotec/" target="_blank">Promotec</a>
                    </li>
                    <li>
                        <span><img src="assets/img/twitter.png" width="48" height="48">
                        </span><a href="https://twitter.com/microtecoficial" target="_blank">@MicrotecOficial</a>
                    </li>
                    <li>
                        <span><img src="assets/img/whats.png" width="48" height="48">
                        </span><a href="#" target="_blank">2221123782</a>
                    </li>
                    <li>
                        <span><img src="assets/img/ubicacion.png" width="48" height="48">
                        <a href="https://www.google.com.mx/maps/place/Edificio+Diana/@19.0516241,-98.217736,19.67z/data=!4m5!3m4!1s0x0:0x77f64b884757e625!8m2!3d19.051662!4d-98.2174898" target="_blank">Avenida Juárez #2318, Edificio Diana Oficina 101</a></span>
                    </li>
                </ul>
            </div>
            <!--Descripcion-->
            <div class="wow fadeIn" id="descripcion">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="videoWrapper">
                                <iframe width="560" height="349" src="https://www.youtube.com/embed/VNeStPWHfzk?ecver=1" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row justify-content-center">
                        <div class="col-md-6">

                             <img src="assets/img/arrow.png" alt="Entrar"> 
                             <!-- Button trigger modal -->                   
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                             <button type="button" class="btn btn-danger btn-lg" @click="openRegister">Comienza a ganar dinero</button> 
                        </div>
                    </div>
                    <div class="row justify-content-center" id="descarga_app">
                        <div class="col-md-6">
                             <b><span>Descarga la App</span></b>
                             <br>
                             <br>
                             <a href=""><img src="assets/img/promotec-logo.png" alt="Entrar" class="logo"></a>
                        </div>
                    </div>
                    <div class="row justify-content-center" id="store-logos-container">
                        <div class="col-md-10">
                             <a href="#"><img src="assets/img/ios.png" alt="Entrar" class="store-logo"></a>
                             <a href="#"><img src="assets/img/android.png" alt="Entrar" class="store-logo"></a>
                        </div>
                    </div>                                                
                </div>
            </div>
            <!--/ Descripcion -->
            <!--Ganar dinero-->
            <div class="wow bounceInUp" id="franquicias-banner">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-9 vertical-center-text">
                            <a><p>
                                    O si te interesa invertir en una franquicia con excelentes ingresos tenemos una opción para tí!
                            </p></a>
                        </div>
                        <div class="col-md-3">
                        <a href=""><img src="assets/img/franquicias.png" alt="Más información" class="franquicias-img"></a>
                        </div>
                    </div>               
                </div>
            </div>
            <!--/ Ganar dinero -->
        </div>
        <!-- /Contenido -->

        <!-- Footer -->
        <footer class="page-footer footer center-on-small-only transparent">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-6">
                        <div class="float-left vertical-center">
                                <img src="assets/img/telcel_logo.png" class="footer-logo-telcel">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="float-right">
                                Un programa de <img src="assets/img/micro-logo.png" class="footer-logo-microtec">
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- /Footer -->

        <!--Modals-->
        <div class="modal small fade right" id="modalLRForm" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-full-height modal-right modal-md " role="document">
                <!--Content-->
                <div class="modal-content">
                    <!--Modal cascading tabs-->
                    <div class="modal-c-tabs">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs tabs-2 light-blue darken-3" role="tablist">
                            <li class="nav-item" id="login_tab">
                                <a id="login_tab_link" class="nav-link" data-toggle="tab" href="#login_panel" role="tab"><i class="fa fa-user mr-1"></i> Acceso</a>
                            </li>
                            <li class="nav-item" id="register_tab">
                                <a id="register_tab_link" class="nav-link active" data-toggle="tab" href="#register_panel" role="tab"><i class="fa fa-user-plus mr-1"></i> Registro</a>
                            </li>
                        </ul>
                        <!-- Tab panels -->
                        <div class="tab-content">
                            <!-- A template for the panels for login process -->
                            <div v-for="panel in panels">
                                <!--Loading bar-->
                                <div v-if="panel.loading" id="loading">
                                    <h3> Cargando ... </h3>
                                    <div class="progress primary-color-dark">
                                        <div class="indeterminate"></div>
                                    </div>
                                </div>
                                <form v-else v-on:submit.prevent>
                                    <div class="tab-pane fade in" v-bind:class="[panel.isActive ? activeClass : hiddenClass]" :id="panel.id" role="tabpanel">
                                        <div class="row" id="header">
                                            <div class="col">
                                                <h2 style="text-align: center;">{{panel.header}}</h2>
                                            </div>
                                        </div>
                                        <div class="row" id="instructions">
                                            <div class="col">                      
                                                <h3 class="red-text">{{panel.instructions}}</h3>
                                            </div>
                                        </div>
                                        <div class="row" id="response">
                                            <div class="col">
                                                <span class="red-text" v-html="panel.response"></span>
                                            </div>
                                        </div>

                                        <!--Body-->
                                        <div class="modal-body mb-1">
                                            <div class="inputs">
                                                <div v-for="input in panel.inputs" class="md-form form-sm">
                                                    <i class="fa prefix" v-bind:class="input.iconClass"></i>
                                                    <input placeholder="" type="text" v-model="globalInputs[input.vModel]" :id="input.id" class="form-control">
                                                    <label class="active" :for="input.id">{{input.label}}</label>
                                                </div>
                                            </div>
                                            <div v-if="panel.captcha" class="g-recaptcha" data-sitekey="6LdZEwcUAAAAAC4DO6u_4JxHqs_Pqck7vJ9mQfFK"></div>
                                            <div class="row buttons">
                                                <div class="col" v-for="button in panel.buttons">
                                                    <button @keyup.enter="call(button.vueFunction)" @click="call(button.vueFunction)" :class="button.class">{{button.label}}<i class="fa ml-1" :class="button.icon"></i></button>
                                                </div>
                                            </div>
                                        </div> 
                                        <!--/. Body del login -->
                                    </div>
                                </form>
                                <!--/.Panel de login--> 
                            </div>
                            
                           
                            <form action="check_login.php">
                                <!--Panel de registro-->
                                <div class="tab-pane fade show active" id="register_panel" role="tabpanel">

                                        <!--Body-->
                                        <div class="modal-body">
                                            <div class="registro_instrucciones">
                                                <p>Registra tus datos a continuación para empezar a ganar dinero: </p>
                                            </div>
                                            <div class="md-form form-sm">
                                                <i class="fa fa-user prefix"></i>                
                                                <input type="text" id="nombre" class="form-control">
                                                <label for="nombre">Nombre <span class="red-text">*</span></label>
                                            </div>
                                            <div class="md-form form-sm">
                                                <i class="fa fa-none prefix"></i>
                                                <input type="text" id="apaterno" class="form-control">
                                                <label for="form24">Apellido Paterno <span class="red-text">*</span></label>
                                            </div>
                                            <div class="md-form form-sm">
                                                <i class="fa fa-none prefix"></i>
                                                <input type="text" id="amaterno" class="form-control">
                                                <label for="nombre">Apellido Materno <span class="red-text">*</span></label>
                                            </div>
                                            <div class="md-form form-sm">
                                                <i class="fa fa-mobile prefix"></i>
                                                <input type="text" max-length="10" id="celular" class="form-control">
                                                <label for="celular">Teléfono Celular<span class="red-text">*</span></label>
                                            </div>

                                            <div class="text-center form-sm mt-2">
                                                <button @keyup.enter="submitRegister" @click="submitRegister" class="btn btn-indigo">Guardar solicitud <i class="fa fa-sign-in ml-1"></i></button>
                                            </div>

                                        </div>
                                </div>
                                <!--/.Panel registro-->
                            </form>
                        </div>

                    </div>
                </div>
                <!--/.Content-->
            </div>
            <!--Modal: Login / Register Form-->
        </div>
        <!--/Modals-->

    </div>

</body>

    <!-- SCRIPTS -->       
        <!-- JQuery -->
        <script src="assets/js/jquery-3.1.1.min.js"></script>
        <!-- Vue js -->
        <script src="https://unpkg.com/vue"></script>
        <!-- Tooltips -->
        <script src="assets/js/tether.min.js"></script>
        <!-- Bootstrap core JavaScript -->
        <script src="assets/js/bootstrap.min.js"></script>
        <!-- Material design bootstrap js -->
        <script src="assets/js/mdb.min.js"></script>
        <!-- Custom JavaScript -->
        <script src="assets/js/custom.js"></script>
        <!-- Select2.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
        <!-- Wow animations -->
        <script src="assets/js/wow.js"></script>
        <!-- Wow animations initialization-->
        <script type="text/javascript">
            new WOW().init();
        </script>
    