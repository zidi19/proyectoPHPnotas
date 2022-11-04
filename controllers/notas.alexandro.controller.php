<?php

$data = array();

function checkForm(array $post) : array {
    $errores = array();
    if(empty($post["datos"])){
        $errores["datos"] = "Este campo no puede estar vacío";
    }else{
        $asignaturas = json_decode($post["datos"], true);
        if(json_last_error() !== JSON_ERROR_NONE){
            $errores["datos"] = "El formato del Json no es correcto";
        }
        else{
            $arrayErrores = array();
            foreach($asignaturas as $asignatura => $alumnos){
                if(empty($asignatura)){
                    $arrayErrores .= "Error, no puede estar vacio";
                }
                if(!is_array($alumnos)){
                    $arrayErrores .= htmlentities($asignatura) . " no es un array";
                }
                else{
                    foreach ($alumnos as $nombreAlumno => $notas){
                        if(empty($nombreAlumno)){
                            $arrayErrores .= "la asignatura" . htmlentities($asignatura) ." tiene un alumno sin nombre ";
                        }
                        foreach ($notas as $nota){
                            if(!is_numeric($nota)){
                                $arrayErrores .= "la asignatura" . htmlentities($asignatura) . " tiene una nota que no es un int ";
                            }
                            else{
                                if($nota < 0 || $nota > 10){
                                    $arrayErrores .= "La asigantura " . htmlentities($asignatura) . ", el alumno " . htmlentities($alumnos) .
                                            ", tiene una nota " . htmlentities($nota) . " fuera de entre 0-10";
                                }
                            }  
                        }
                    }
                }
            }
            if(!empty($arrayErrores)){
                $errores["datos"] = $arrayErrores;
            }
        }
    }
    return $errores;
}

if(isset($_POST["Enviar"])){
    $data["errores"] = checkForm($_POST);
    $data["input"] = filter_var_array($_POST);
    if(empty($data["errores"])){
        $array = json_decode($_POST["datos"],true);
        $resultado = datosAsign($array);
        $data["resultado"] = $resultado;
    }
}  

function datosAsign(array $array) : array{
    $resultado = array();
    $alumnos = array();
    $alumnosSuspensos = array();
    foreach ($array as $asignatura => $alumno){
        $resultado[$asignatura] = array();
        $aprobados = 0;
        $suspensos = 0;
        $notaMaxima = array(
            "alumno" => " ",
            "nota" => -1
        );
        
        $notaMinima = array(
            "alumno" => "",
            "nota" => 11
        );
        $notaAcumulada = 0;
        $contadorAlumnos = 0;
        
        foreach ($alumno as $nombreAlumno => $notas){
                if(!isset($alumnos[$nombreAlumno])){
                    $alumnos[$nombreAlumno] = ["aprobados" => 0 , "suspensos" => 0];
                }
                $acumulacionNotaAlumnoAsignatura = 0;
                for($i =0 ;$i<count($notas);$i++){
                    $acumulacionNotaAlumnoAsignatura += $notas[$i];
                    
                    if($notas[$i] > $notaMaxima["nota"]){
                        $notaMaxima["alumno"] = $nombreAlumno;
                        $notaMaxima["nota"] = intval($notas[$i]);
                    }
                    if($notas[$i] < $notaMinima["nota"]){
                        $notaMinima["alumno"] = $nombreAlumno;
                        $notaMinima["nota"] = intval($notas[$i]);
                    }
                    
                }
                $nota = $acumulacionNotaAlumnoAsignatura/ count($notas);
                $contadorAlumnos++;
                $notaAcumulada += $nota;

                if($nota < 5){
                    $suspensos++;
                    if(array_key_exists($nombreAlumno, $alumnosSuspensos)){
                        $alumnosSuspensos[$nombreAlumno]["suspensos"]++;
                    }
                    else{
                        $alumnosSuspensos[$nombreAlumno] = array("suspensos" => 1);
                    }
                } else {
                    $aprobados++;
                    
                }
                if(!array_key_exists($nombreAlumno, $alumnosSuspensos)){
                    $alumnosSuspensos[$nombreAlumno] = array("suspensos" => 0);
                }
        }
        if($contadorAlumnos > 0){
            $resultado[$asignatura]["media"] = $notaAcumulada / $contadorAlumnos;
            $resultado[$asignatura]["max"] = $notaMaxima;
            $resultado[$asignatura]["min"] = $notaMinima; 
        }else{
            $resultado[$asignatura]["media"] = 0;
        }
        $resultado[$asignatura]["aprobados"] = $aprobados;
        $resultado[$asignatura]["suspensos"] = $suspensos;
    }
    return array("asignaturas" => $resultado, "alumnos" => $alumnosSuspensos);
}

include 'views/templates/header.php';
include 'views/notas.alexandro.view.php';
include 'views/templates/footer.php';