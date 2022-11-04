<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Cálculo de Notas</h1>
</div>
<div class="row">
    <?php
    if(isset($data["resultado"])){
    ?>
    <div class="col-12">
        <div class="card shadow mb-4">
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Asignaturas</h6>                                    
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Módulo</th><th>Media</th><th>Aprobados</th><th>Suspensos</th><th>Máximo</th><th>Mínimo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($data["resultado"]["asignaturas"] as $asignatura => $datosAsignatura){
                        ?>
                        <tr>
                            <td><?php echo ucfirst($asignatura); ?></td>
                            <td><?php echo number_format($datosAsignatura["media"],2,".",","); ?></td>
                            <td><?php echo $datosAsignatura["aprobados"]; ?></td>
                            <td><?php echo $datosAsignatura["suspensos"]; ?></td>
                            <td><?php echo $datosAsignatura["max"]["alumno"]. " : " .$datosAsignatura["max"]["nota"]; ?></td>
                            <td><?php echo $datosAsignatura["min"]["alumno"]. " : " .$datosAsignatura["min"]["nota"]; ?></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
                <?php
                }
                if(isset($data["resultado"])){
                ?>
                <div class="col-lg-6 col-12">
                    <div class="alert alert-success">
                        <h6>Arpueban todo:</h6>
                        <ol>
                            <?php
                                foreach($data["resultado"]["alumnos"] as $nombreAlumno => $datos){
                                    if($datos["suspensos"] === 0){
                                        echo "<li>$nombreAlumno</li>";
                                    }
                                }
                            ?>
                        </ol>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="alert alert-warning">
                        <h6>Suspenden al menos una asignatura:</h6>
                        <ol>
                            <?php
                                foreach($data["resultado"]["alumnos"] as $nombreAlumno => $datos){
                                    if($datos["suspensos"] == 1){
                                        echo "<li>$nombreAlumno</li>";
                                    }
                                }
                            ?>
                        </ol>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="alert alert-primary">
                        <h6>Promocionan:</h6>
                        <ol>
                            <?php
                                foreach($data["resultado"]["alumnos"] as $nombreAlumno => $datos){
                                    if($datos["suspensos"] <= 1){
                                        echo "<li>$nombreAlumno</li>";
                                    }
                                }
                            ?>
                        </ol>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="alert alert-danger">
                        <h6>NO promocionan:</h6>
                        <ol>
                            <?php
                                foreach($data["resultado"]["alumnos"] as $nombreAlumno => $datos){
                                    if($datos["suspensos"] > 2){
                                        echo "<li>$nombreAlumno</li>";
                                    }
                                }
                            ?>
                        </ol>
                    </div>
                </div>
                <?php
                }
                ?>
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div
                            class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary"></h6>                                    
                        </div>
                        <div class="card-body">
                            <form  method="post" action="./?sec=notas.alexandro">
                                <input type="hidden" name="sec" value="formulario" />
                                <div class="mb-3">
                                    <label for="areaTexto">Introduzca un array en json</label>
                                    <textarea class="form-control" id="areaTexto" name="datos" rows = 10><?php echo isset($data["input"]["datos"]) ? $data["input"]["datos"] : "";?></textarea>
                                    <p class="text-danger small"><?php echo isset($data["errores"]["datos"]) ? $data["errores"]["datos"] : "";?></p>
                                </div>
                                <div class="mb-3">
                                    <input type="submit" value="Enviar" name="Enviar" class="btn btn-primary"/>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>                        
            </div>
        </div>
    </div>
</div>