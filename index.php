<?php
session_start();
include('control.php');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta name="Description" content="Buscas cómo iniciar un negocio o cómo ganar dinero extra, tenemos diferentes modelos de negocio para ti, Promotec y franquicias telcel.">
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Promotec tu propio negocio.</title>

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
    <script src="https://unpkg.com/vue-recaptcha@latest/dist/vue-recaptcha.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit" async defer></script>

    <!-- Facebook Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
    n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
    document,'script','https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '238193969980173'); // Insert your pixel ID here.
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=238193969980173&ev=PageView&noscript=1"
    /></noscript>
    <!-- DO NOT MODIFY -->
    <!-- End Facebook Pixel Code -->

</head>

<body>
    <div id="overlay">
    </div>
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
                        <div class="col-md-6">
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
                             <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#modalRegister">Comienza a ganar dinero</button> 
                        </div>
                    </div>
                    <div class="row justify-content-center" id="descarga_app">
                        <div class="col-md-6">
                             <b><span>Descarga la App</span></b>
                             <br>
                             <br>
                             <a href="https://play.google.com/store/apps/details?id=com.promotormicrotec.promotec"><img src="assets/img/promotec-logo.png" alt="Entrar" class="logo"></a>
                        </div>
                    </div>
                    <div class="row justify-content-center" id="store-logos-container">
                        <div class="col-md-10">
                             <a href="https://itunes.apple.com/mx/app/promotec/id1093302288?mt=8"><img src="assets/img/ios.png" alt="Entrar" class="store-logo"></a>
                             <a href="#"><img src="assets/img/android.png" alt="Entrar" class="store-logo"></a>
                        </div>
                    </div>                                                
                </div>
            </div>
            <!--/ Descripcion -->

            <!--Banner de franquicias -->
            <div class="wow bounceInUp" id="franquicias-banner">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-9 vertical-center-text">
                            <a href="https://www.micro-tec.com.mx/pagina/microtec/franquicias.html"><p>
                                    O si te interesa invertir en una franquicia con excelentes ingresos tenemos una opción para tí!
                            </p></a>
                        </div>
                        <div class="col-md-3">
                        <a href="https://www.micro-tec.com.mx/pagina/microtec/franquicias.html"><img src="assets/img/franquicias.png" alt="Más información" class="franquicias-img"></a>
                        </div>
                    </div>               
                </div>
            </div>
            <!--/ Banner de franquicias -->
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
                                <a href="https://www.micro-tec.com.mx">Un programa de <img src="assets/img/micro-logo.png" class="footer-logo-microtec"></a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- /Footer -->

        <!--Login and PassRestore Template panel-->
        <div class="modal fade" id="modalLogin" tabindex="-1" role="dialog" aria-labelledby="modalLogin" aria-hidden="true">
            <div class="modal-dialog cascading-modal" role="document">
                <!--Content-->
                <div class="modal-content">
                    <!--Header-->
                    <div class="modal-header light-blue darken-3 white-text">
                        <h4 class="title"><i class="fa fa-user"></i> Acceso</h4>
                    </div>
                    <!-- A template for the panels for login process -->
                    <div v-for="panel in panels">
                        <!--Loading bar-->
                        <div v-if="panel.loading" id="loading">
                            <div class="container">
                                <h3> Cargando ... </h3>
                                <div class="progress primary-color-dark">
                                    <div class="indeterminate"></div>
                                </div>
                            </div>
                        </div>
                        <!-- If not loading, then show the panel -->
                        <form v-else v-on:submit.prevent name="geeral_form">
                            <div v-bind:class="[panel.isActive ? activeClass : hiddenClass]" :id="panel.id">
                            <!--Headers-->
                                <div class="container padding-top">
                                    <div class="row" id="header">
                                        <div class="col">
                                            <h2 style="text-align: center;">{{panel.header}}</h2>
                                        </div>
                                    </div>
                                    <div class="row" id="instructions">
                                        <div class="col">
                                            <h5 style="text-align: center;" v-html="panel.instructions"></h5>
                                        </div>
                                    </div>                                        
                                    <div class="row" id="response">
                                        <div class="col">
                                            <span :class="panel.response.color_text">
                                                {{panel.response.message}}
                                            </span>
                                        </div>
                                    </div> 
                                </div>

                                <!--Body-->
                                <div class="modal-body mb-1">
                                    <div class="inputs">
                                        <div v-for="input in panel.inputs" class="md-form form-sm">
                                            <i class="fa prefix" v-bind:class="input.iconClass"></i>
                                            <input placeholder="" type="text" v-model="globalInputs[input.vModel]" :id="input.id" class="form-control validate" required data-error="Corregir">
                                            <label class="active" :for="input.id">{{input.label}}</label>
                                        </div>
                                        <div v-if="panel.passInput" class="md-form form-sm">
                                            <i class="fa prefix fa-lock"></i>
                                            <input type="password" v-model="globalInputs.pass" id="pass" class="form-control validate">
                                            <label class="active" for="pass">Contraseña</label>
                                        </div>
                                    </div>
                                    <!-- Section for captcha -->
                                    <div v-if="panel.captcha" class="recaptcha">
                                        <template>
                                          <vue-recaptcha ref="recaptcha" sitekey="6LdZEwcUAAAAAC4DO6u_4JxHqs_Pqck7vJ9mQfFK" @verify="onVerify"></vue-recaptcha>
                                        </template>
                                    </div>
                                    <!-- /extra content -->
                                    <div class="row buttons modal-footer justify-content-center">
                                        <div v-for="button in panel.buttons">
                                            <a href="#"><button type="button" @keyup.enter="call(button.vueFunction)" @click="call(button.vueFunction)" class="waves-effect waves-light" :class="button.class">{{button.label}}<i class="fa ml-1" :class="button.icon"></i></button></a>
                                        </div>
                                    </div>
                                </div> 
                                <!--/. Body del login -->
                            </div>
                        </form>
                        <!--/.Panel de login--> 
                    </div>
                </div>

            </div>
        </div>
        <!--/. Login Modal-->

        <!--Modal: Login with Avatar Form-->
        <div class="modal fade" id="modalLoginAvatar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog cascading-modal modal-avatar modal-sm" role="document">
                <!--Content-->
                <div class="modal-content">

                    <!--Header-->
                    <div class="modal-header">
                        <img src="https://mdbootstrap.com/img/Photos/Avatars/img%20%281%29.jpg" class="rounded-circle img-responsive">
                    </div>
                    <!--Body-->
                    <div class="modal-body text-center mb-1">

                        <h5 class="mt-1 mb-2">Maria Doe</h5>

                        <div class="md-form ml-0 mr-0">
                            <input type="password" type="text" id="form29" class="form-control ml-0">
                            <label for="form29" class="ml-0">Enter password</label>
                        </div>

                        <div class="text-center">
                            <button class="btn btn-cyan mt-1">Login <i class="fa fa-sign-in ml-1"></i></button>
                        </div>
                    </div>

                </div>
                <!--/.Content-->
            </div>
        </div>
        <!--Modal: Login with Avatar Form-->

        <!--Modal: Register Form-->
        <div class="modal fade" id="modalRegister" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog cascading-modal" role="document">
                <!--Content-->
                <div class="modal-content">

                    <!--Header-->
                    <div class="modal-header light-blue darken-3 white-text">
                        <h4 class="title"><i class="fa fa-user-plus"></i> Registro </h4>
                    </div>
                    <!--Body-->
                    <div class="modal-body">
                        <div class="registro_instrucciones">
                            <p>Registra tus datos a continuación para empezar a ganar dinero: </p>
                        </div>
                        <div class="registro_respuesta">
                        </div>
                        <div class="md-form form-sm">
                            <div class="col error" id="error">
                            </div>
                        </div>
                        <div class="md-form form-sm">
                            <i class="fa fa-user prefix"></i>                
                            <input type="text" name="nombre" id="nombre" class="form-control validate" required>
                            <label for="nombre">Nombre <span class="red-text">*</span></label>
                        </div>
                        <div class="md-form form-sm">
                            <i class="fa fa-none prefix"></i>
                            <input type="text" id="apaterno" class="form-control validate" required>
                            <label for="apaterno">Apellido Paterno <span class="red-text">*</span></label>
                        </div>
                        <div class="md-form form-sm">
                            <i class="fa fa-none prefix"></i>
                            <input type="text" id="amaterno" class="form-control validate" required>
                            <label for="amaterno">Apellido Materno <span class="red-text">*</span></label>
                        </div>
                        <div class="md-form form-sm">
                            <i class="fa fa-mobile prefix"></i>
                            <input type='text' max-length="10" id="celular" class="form-control validate" required>
                            <label for="celular">Teléfono Celular<span class="red-text">*</span></label>
                        </div>

                        <div class="text-center form-sm mt-2">
                            <button @keyup.enter="submitRegister" @click="submitRegister" class="btn btn-indigo">Guardar solicitud <i class="fa fa-sign-in ml-1"></i></button>
                        </div>
                
                    </div>
                    <!--Footer-->
                    <div class="modal-footer">
                        <div class="options text-center text-md-right mt-1">
                            <p> Ya tienes una cuenta?<a href="#" @click="openLogin"> Acceder</a></p>
                        </div>
                        <button type="button" class="btn btn-outline-info waves-effect ml-auto" data-dismiss="modal">Cerrar <i class="fa fa-times-circle ml-1"></i></button>
                    </div>
                </div>
                <!--/.Content-->
            </div>
        </div>
        <!--Modal: Register Form-->

    </div>
</body>

    <!-- SCRIPTS --> 
        <!-- JQuery -->
        <script src="assets/js/jquery-3.1.1.min.js"></script>
        <!-- Vue js -->
        <script src="https://unpkg.com/vue"></script>
        <!-- Validators -->
        <script src="assets/js/validate.js"></script>
        <!-- Tooltips -->
        <script src="assets/js/tether.min.js"></script>
        <!-- Bootstrap core JavaScript -->
        <script src="assets/js/bootstrap.min.js"></script>
        <!-- Material design bootstrap js -->
        <script src="assets/js/mdb.min.js"></script>
        <!-- Slideshow component -->
        <script src="assets/js/slideshow.js"></script>
        <!-- Load and run slideshow -->
        <script type="text/javascript">
            //Array of images which you want to show: Use path you want.
            var images = ["assets/img/promotec_bg_1.jpg","assets/img/promotec_bg_2.jpg","assets/img/promotec_bg_3.jpg","assets/img/promotec_bg_4.jpg","assets/img/promotec_bg_5.jpg"];
            var element = $("#overlay");
            startSlideshow(element,images);
        </script>
        <!-- Wow animations -->
        <script src="assets/js/wow.js"></script>
        <!-- Wow animations initialization-->
        <script type="text/javascript">
            new WOW().init();
        </script>
        <!-- Custom JavaScript -->
        <script src="assets/js/custom.js"></script>
        <!-- Google analytics -->
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-102480590-1', 'auto');
          ga('send', 'pageview');

        </script>
        <script type="text/javascript">
            window.smartlook||(function(d) {
            var o=smartlook=function(){ o.api.push(arguments)},h=d.getElementsByTagName('head')[0];
            var c=d.createElement('script');o.api=new Array();c.async=true;c.type='text/javascript';
            c.charset='utf-8';c.src='https://rec.smartlook.com/recorder.js';h.appendChild(c);
            })(document);
            smartlook('init', 'fad4193ab3c289c1fe0c2b64ed576470ee47d90d');
        </script>