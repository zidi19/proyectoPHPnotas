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

include 'views/templates/header.php';
include 'views/notas.alexandro.view.php';
include 'views/templates/footer.php';