<?php $session = \Config\Services::session();?>

<div class="leftside-menu">

    <!-- LOGO -->
    <a href="#" class="logo text-center logo-light mt-2">
        <span class="logo-lg">
            <img src="<?php echo base_url()?>/assets/images/st4.png" alt="" height="90">
        </span>
        <span class="logo-sm">
            <img src="<?php echo base_url();?>/assets/images/st4.png" alt="" height="48">
        </span>
    </a>

    <!-- LOGO -->
    <a href="#" class="logo text-center logo-dark">
        <span class="logo-lg">
            <img src="<?php echo base_url();?>/assets/images/st4.png" alt="" height="16">
        </span>
        <span class="logo-sm">
            <img src="<?php echo base_url();?>/assets/images/st4.png" alt="" height="16">
        </span>
    </a>

    <div class="h-100" id="leftside-menu-container" data-simplebar>

        <!--- Sidemenu -->
        <ul class="side-nav mt-5">
            <li class="side-nav-title side-nav-item">MENÚ DEL SISTEMA</li>
            <?php if((int)$session->get('id_perfil') <= 4): ?>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#turnos" aria-expanded="false" aria-controls="gestion"
                    class="side-nav-link">
                    <i class="dripicons-user"></i>
                    <span> Usuarios </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="turnos">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="<?= base_url("/index.php/Agregar/usuarioSti")?>"><i class="dripicons-plus"></i>Alta
                                SAC</a>
                        </li>
                    
                        <li>
                            <a href="<?= base_url("/index.php/Inicio")?>"><i class="dripicons-search"></i> Buscar</a>
                        </li>

                    </ul>
                </div>
            </li>
            <?php endif ?>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#llamadas" aria-expanded="false" aria-controls="junta"
                    class="side-nav-link">
                    <i class="dripicons-graduation"></i>
                    <span> <?php echo ($session->get('id_perfil') >= 5)?'Cursos':'Crear Curso' ?> </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="llamadas">
                    <ul class="side-nav-second-level">
                        <?php if($session->get('id_perfil') <= 4): ?>
                        <li>
                            <a href="<?= base_url("/index.php/Agregar/Curso")?>"><i class="dripicons-plus"></i> Agregar
                                Curso</a>
                        </li>
                        <?php endif ?>
                        <?php if((int)$session->get('id_perfil') == 5 || (int)$session->get('id_perfil') == 6 || (int)$session->get('id_perfil') == 1): ?>
                        <li>
                            <a href="<?= base_url("/index.php/Principal/Participantes")?>"><i
                                    class="dripicons-user"></i> Gestionar Usuarios</a>
                        </li>
                        <?php endif ?>
                        <li>
                            <a href="<?= base_url("/index.php/Principal/Matricular")?>"><i
                                    class="dripicons-user-group"></i> Preinscripción</a>
                        </li>


                    </ul>
                </div>
             
            </li>
            <?php if((int)$session->get('id_perfil') <= 4): ?>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#turnos" aria-expanded="false" aria-controls="gestion"
                    class="side-nav-link">
                    <i class="dripicons-gear"></i>
                    <span> Configurar</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="turnos">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="<?= base_url("/index.php/Principal/dependencia")?>"><i class="dripicons-plus"></i>Dependencias/Ente</a>
                        </li>
                    
                        <li>
                            <a href="<?= base_url("/index.php/Inicio")?>"><i class="dripicons-search"></i> Correo</a>
                        </li>
                        <?php if((int)$session->get('id_perfil') == 1): ?>
                        <li>
                            <a href="<?= base_url("/index.php/Principal/Categoria")?>"><i class="dripicons-search"></i> Categoria Activa</a>
                        </li>
                        <?php endif ?>

                    </ul>
                </div>
            </li>
            <?php endif ?>

            <!-- <?php //if((int)$session->id_perfil == -1): ?>
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#reportes" aria-expanded="false" aria-controls="reportes" class="side-nav-link">
                            <i class="uil-clipboard-alt"></i>
                            <span>Reportes</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="reportes">
                            <ul class="side-nav-second-level">
                                <li>
                                    <a href="<?php echo base_url('index.php/Junta/reporteComentarios') ?>"> Reporte</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('index.php/Junta/reporteVotaciones') ?>"> Reporte de votaciones</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('index.php/Junta/reporteVisualizaciones') ?>"> Reporte de visualizaciones</a>
                                </li>
                            </ul>
                        </div>
                    </li>   
                <?php //endif?>  -->
        </ul>
        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->