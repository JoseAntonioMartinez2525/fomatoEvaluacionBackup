//Comisiones Totales
function onActv2Comision() {
    // Obtener los valores de los inputs y convertirlos a números flotantes
    let comisionPosgrado = parseFloat(document.getElementById("comisionPosgrado").value);
    let comisionLic = parseFloat(document.getElementById("comisionLic").value);
    
    // Verificar que los valores son números válidos y no NaN
    if (isNaN(comisionPosgrado)) comisionPosgrado = 0;
    if (isNaN(comisionLic)) comisionLic = 0;
    
    // Realizar los cálculos
    let sumaComision = minWithSum(comisionPosgrado, comisionLic);
    
    // Redondear el resultado a 3 decimales sin convertir a cadena
    sumaComision = Math.round(sumaComision * 1000) / 1000;
    
    // Actualizar el contenido del td
    document.getElementById("actv2Comision").textContent = sumaComision.toFixed(2);

    console.log(sumaComision);
}




function onActv3Comision(){
    
    const comisionA = parseFloat(document.getElementById("comisionIncisoA").value);
    const comisionB = parseFloat(document.getElementById("comisionIncisoB").value);
    const comisionC = parseFloat(document.getElementById("comisionIncisoC").value);
    const comisionD = parseFloat(document.getElementById("comisionIncisoD").value);
    const comisionE = parseFloat(document.getElementById("comisionIncisoE").value);

    let ms = min60(comisionA,comisionB,comisionC,comisionD,comisionE).toFixed(2);
    document.querySelectorAll(".actv3Comision").forEach(function (element) {
        element.innerHTML = ms;
    });

}



function onActv3_2Comision(){
  const prom90_100 = parseFloat(document.getElementById("prom90_100").value);
  const prom80_90 = parseFloat(document.getElementById("prom80_90").value);
  const prom70_80 = parseFloat(document.getElementById("prom70_80").value);

  const promComision = min50(prom90_100,prom80_90,prom70_80);
  document.getElementById("comision3_2") .innerHTML= promComision; 
  console.log("Puntaje promedio de la Comision Dictaminadora:", promComision);

}


function onActv3Comision3(){
  
  const comisionA = parseFloat(document.getElementById("comIncisoA").value);
  const comisionB = parseFloat(document.getElementById("comIncisoB").value);
  const comisionC = parseFloat(document.getElementById("comIncisoC").value);
  const comisionD = parseFloat(document.getElementById("comIncisoD").value);


    const ms = minWithSumThree(comisionA,comisionB,comisionC,comisionD);
    document.getElementById("comision3_3") .innerHTML= ms;
    document.getElementById("comision3_3_copy") .innerHTML= ms;
    console.log(ms);

}



function onActv3Comision3_4(){
  
  const comision1 = parseFloat(document.getElementById("comInternacional").value);
  const comision2 = parseFloat(document.getElementById("comNacional").value);
  const comision3 = parseFloat(document.getElementById("comRegional").value);
  const comision4 = parseFloat(document.getElementById("comPreparacion").value);


    const comision3_4 = min60(comision1,comision2,comision3,comision4);
    document.getElementById("comision3_4") .innerHTML= comision3_4;
    console.log(comision3_4);



}

function onActv3Comision3_5(){
  
  const comDA = parseFloat(document.getElementById("comDA").value);
  const comNCAA = parseFloat(document.getElementById("comNCAA").value);



    const comision3_5 = minWithSumThreeFive(comDA,comNCAA);
    document.getElementById("comision3_5") .innerHTML= comision3_5;
    console.log(comision3_5);



}

function onActv3Comision3_6() {
  const comisionHoras3_6 = parseFloat(document.getElementById("comisionDict3_6").value);
  console.log("comisionHoras3_6:", comisionHoras3_6);

  const comisionDict3_6 = Math.min(comisionHoras3_6, 40);
  console.log("comisionDict3_6:", comisionDict3_6);

  document.getElementById("comision3_6").innerHTML = comisionDict3_6;
  console.log(comisionDict3_6);


}


function onActv3Comision3_7() {
  const comisionHoras3_7 = parseFloat(document.getElementById("comisionDict3_7").value);
  console.log("comisionHoras3_7:", comisionHoras3_7);

  const comisionDict3_7 = Math.min(comisionHoras3_7, 40);
  console.log("comisionDict3_7:", comisionDict3_7);

  document.getElementById("comision3_7").innerHTML = comisionDict3_7;
  console.log(comisionDict3_7);


}


function onActv3Comision3_8() {
  const comisionHoras3_8 = parseFloat(document.getElementById("comisionDict3_8").value);
  console.log("comisionHoras3_8:", comisionHoras3_8);

  const comisionDict3_8 = Math.min(comisionHoras3_8, 40);
  console.log("comisionDict3_8:", comisionDict3_8);

  document.getElementById("comision3_8").innerHTML = comisionDict3_8;
  console.log(comisionDict3_8);


}

function onActv3Comision3_8_1() {
  const comisionHoras3_8_1 = parseFloat(document.getElementById("comisionDict3_8_1").value);
  console.log("comisionHoras3_8_1:", comisionHoras3_8_1);

  const comisionDict3_8_1 = Math.min(comisionHoras3_8_1, 40);
  console.log("comisionDict3_8_1:", comisionDict3_8_1);

  document.getElementById("comision3_8_1").innerHTML = comisionDict3_8_1;
  console.log(comisionDict3_8_1);


}


function onActv3Comision3_9() {
  const comisionInputs = document.querySelectorAll('input[id^="tutoriasComision"]');
  const comisionValues = Array.from(comisionInputs).map(input => {
    const value = input.value;
    if (value === '') {
      return 0;
    }
    const floatValue = parseFloat(value);
    if (isNaN(floatValue)) {
      console.error(`Invalid value: ${value}`);
      return 0;
    }
    return floatValue;
  });

  const comision3_9 = minTutorias(...comisionValues);
   
  // Actualizar todos los elementos con la clase "comision3_9"
    document.querySelectorAll('.comision3_9').forEach(element => {
        element.textContent = comision3_9.toFixed(2);
    });

  console.log(comision3_9);


}


function onActv3Comision3_10(){
  //comisiones valores
  const comisionGrupal = parseFloat(document.querySelector('input[id^="comisionGrupal"]').value);
  const comisionIndividual = parseFloat(document.querySelector('input[id^="comisionIndividual"]').value);
  const sumaCom3_10 =  comisionGrupal + comisionIndividual;

  //minimo resultante Comision
  const comision3_10 = Math.min(sumaCom3_10,115);
  document.getElementById("comision3_10").innerHTML = comision3_10;
  console.log("Minimo Resultante de Comision 3.10: ", comision3_10);


  
}

function onActv3Comision3_11(){
  //comisiones valores
  const comisionAsesoria = parseFloat(document.querySelector('input[id^="comisionAsesoria"]').value);
  const comisionServicio = parseFloat(document.querySelector('input[id^="comisionServicio"]').value);
  const comisionPracticas =  parseFloat(document.querySelector('input[id^="comisionPracticas"]').value);
  const sumaCom3_11 =  comisionAsesoria + comisionServicio +  comisionPracticas;


  //minimo resultante Comision
  const comision3_11 = Math.min(sumaCom3_11,95);
  document.getElementById("comision3_11").innerHTML = comision3_11;
  console.log("Minimo Resultante de Comision 3.11: ", comision3_11);


  
}

function onActv3Comision3_12(){
  //comisiones valores

const comisionCientificos = parseFloat(document.querySelector('input[id^="comisionCientificos"]').value);
const comisionDivulgacion = parseFloat(document.querySelector('input[id^="comisionDivulgacion"]').value);
const comisionTraduccion = parseFloat(document.querySelector('input[id^="comisionTraduccion"]').value);
const comisionArbitrajeInt = parseFloat(document.querySelector('input[id^="comisionArbitrajeInt"]').value);
const comisionArbitrajeNac = parseFloat(document.querySelector('input[id^="comisionArbitrajeNac"]').value);
const comisionSinInt = parseFloat(document.querySelector('input[id^="comisionSinInt"]').value);
const comisionSinNac = parseFloat(document.querySelector('input[id^="comisionSinNac"]').value);
const comisionAutor = parseFloat(document.querySelector('input[id^="comisionAutor"]').value);
const comisionEditor = parseFloat(document.querySelector('input[id^="comisionEditor"]').value);
const comisionWeb = parseFloat(document.querySelector('input[id^="comisionWeb"]').value);


  const sumaCom3_12 =  comisionCientificos + comisionDivulgacion +  comisionTraduccion + comisionArbitrajeInt + 
  comisionArbitrajeNac + comisionSinInt + comisionSinNac + comisionAutor + comisionEditor + comisionWeb;

  //minimo resultante Comision
  const comision3_12 = Math.min(sumaCom3_12,150);
  
  // Actualizar todos los elementos con la clase "comision3_12"
    document.querySelectorAll('.comision3_12').forEach(element => {
        element.textContent = comision3_12.toFixed(2);
    });  console.log("Minimo Resultante de Comision 3.12: ", comision3_12);


  
}



function onActv3Comision3_13(){
  //comisiones valores

  const comisionInicioFinancimientoExt = parseFloat(document.querySelector('input[id^="comisionInicioFinancimientoExt"]').value);
  const comisionInicioInvInterno = parseFloat(document.querySelector('input[id^="comisionInicioInvInterno"]').value);
  const comisionReporteFinanciamExt =  parseFloat(document.querySelector('input[id^="comisionReporteFinanciamExt"]').value) ;
  const comisionReporteInvInt = parseFloat (document.querySelector('input[id^="comisionReporteInvInt"]').value);


  const sumaCom3_13 =  comisionInicioFinancimientoExt + comisionInicioInvInterno +  comisionReporteFinanciamExt + comisionReporteInvInt;

  //minimo resultante Comision
  const comision3_13 = Math.min(sumaCom3_13,130);
  document.getElementById("comision3_13").innerHTML = comision3_13;
  console.log("Minimo Resultante de Comision 3.13: ", comision3_13);


  
}

function onActv3Comision3_14(){
  //comisiones valores

  const comisionCongresoInt = parseFloat(document.getElementById("comisionCongresoInt").value);
  const comisionCongresoNac = parseFloat(document.getElementById("comisionCongresoNac").value);
  const comisionCongresoLoc =  parseFloat(document.getElementById("comisionCongresoLoc").value) ;


  //Comision minimo Resultante
  const comision3_14 = min40( comisionCongresoInt,comisionCongresoNac,comisionCongresoLoc);

  document.getElementById("comision3_14").innerHTML = comision3_14;
  console.log("Minimo Resultante de Comision 3.14: ", comision3_14);


  
}

function onActv3Comision3_15(){
  //comisiones valores

  const comisionPatententes = parseFloat(document.getElementById("comisionPatententes").value);
  const comisionPrototipos = parseFloat(document.getElementById("comisionPrototipos").value);


  //Comision minimo Resultante
  const comision3_15 = min60( comisionPatententes,comisionPrototipos);

  document.getElementById("comision3_15").innerHTML = comision3_15;
  console.log("Minimo Resultante de Comision 3.15: ", comision3_15);


  
}

function onActv3Comision3_16(){
  //comisiones valores

  const comisionArbInt = parseFloat(document.getElementById("comisionArbInt").value);
  const comisionArbNac = parseFloat(document.getElementById("comisionArbNac").value);
  const comisionPubInt = parseFloat(document.getElementById("comisionPubInt").value);
  const comisionPubNac = parseFloat(document.getElementById("comisionPubNac").value);
  const comisionRevInt = parseFloat(document.getElementById("comisionRevInt").value);
  const comisionRevNac = parseFloat(document.getElementById("comisionRevNac").value);
  const comisionRevista = parseFloat(document.getElementById("comisionRevista").value);

  //Comision minimo Resultante
  const comision3_16 = min30(comisionArbInt,comisionArbNac,comisionPubInt,comisionPubNac,comisionRevInt,comisionRevNac,comisionRevista);

  // Actualizar todos los elementos con la clase "comision3_19"
  document.querySelectorAll('.comision3_16').forEach(element => {
      element.textContent = comision3_16.toFixed(2);
  });
  
  console.log("Minimo Resultante de Comision 3.16: ", comision3_16);


  
}

function onActv3Comision3_17(){
  //comisiones valores

  const comisionDifusionExt = parseFloat(document.getElementById("comisionDifusionExt").value);
  const comisionDifusionInt = parseFloat(document.getElementById("comisionDifusionInt").value);
  const comisionRepDifusionExt = parseFloat(document.getElementById("comisionRepDifusionExt").value);
  const comisionRepDifusionInt = parseFloat(document.getElementById("comisionRepDifusionInt").value);


  //Comision minimo Resultante
  const comision3_17 = min50(comisionDifusionExt,comisionDifusionInt,comisionRepDifusionExt,comisionRepDifusionInt);

  document.getElementById("comision3_17").innerHTML = comision3_17;
  console.log("Minimo Resultante de Comision 3.17: ", comision3_17);


  
}

function onActv3Comision3_18(){
  //comisiones valores

  const comisionComOrgInt = parseFloat(document.getElementById("comisionComOrgInt").value);
  const comisionComOrgNac =  parseFloat(document.getElementById("comisionComOrgNac").value);
  const comisionComOrgReg = parseFloat(document.getElementById("comisionComOrgReg").value);
  const comisionComApoyoInt =  parseFloat(document.getElementById("comisionComApoyoInt").value);
  const comisionComApoyoNac = parseFloat(document.getElementById("comisionComApoyoNac").value);
  const comisionComApoyoReg =  parseFloat(document.getElementById("comisionComApoyoReg").value);
  const comisionCicloComOrgInt = parseFloat(document.getElementById("comisionCicloComOrgInt").value);
  const comisionCicloComOrgNac =  parseFloat(document.getElementById("comisionCicloComOrgNac").value);
  const comisionCicloComOrgReg = parseFloat(document.getElementById("comisionCicloComOrgReg").value);
  const comisionCicloComApoyoInt =  parseFloat(document.getElementById("comisionCicloComApoyoInt").value);
  const comisionCicloComApoyoNac =  parseFloat(document.getElementById("comisionCicloComApoyoNac").value);
  const comisionCicloComApoyoReg = parseFloat(document.getElementById("comisionCicloComApoyoReg").value);

  //Comision minimo Resultante
  const comision3_18 = min40(comisionComOrgInt,comisionComOrgNac,comisionComOrgReg,comisionComApoyoInt,
    comisionComApoyoNac,comisionComApoyoReg,comisionCicloComOrgInt,comisionCicloComOrgNac, 
    comisionCicloComOrgReg,comisionCicloComApoyoInt,comisionCicloComApoyoNac,comisionCicloComApoyoReg);

    // Actualizar todos los elementos con la clase "comision3_19"
    document.querySelectorAll('.comision3_18').forEach(element => {
        element.textContent = comision3_18.toFixed(2);
    });

  console.log("Minimo Resultante de Comision 3.18: ", comision3_18);


  
}

function onActv3Comision3_19(){
  //comisiones valores

  const comCGUtitular = parseFloat(document.getElementById("comCGUtitular").value);
  const comCGUespecial =  parseFloat(document.getElementById("comCGUespecial").value);
  const comCGUpermanente = parseFloat(document.getElementById("comCGUpermanente").value);
  const comCAACtitular =  parseFloat(document.getElementById("comCAACtitular").value);
  const comCAACintegCom = parseFloat(document.getElementById("comCAACintegCom").value);
  const comComDepart =  parseFloat(document.getElementById("comComDepart").value);
  const comComPEDPD = parseFloat(document.getElementById("comComPEDPD").value);
  const comComPartPos =  parseFloat(document.getElementById("comComPartPos").value);
  const comRespPos = parseFloat(document.getElementById("comRespPos").value);
  const comRespCarrera =  parseFloat(document.getElementById("comRespCarrera").value);
  const comRespProd =  parseFloat(document.getElementById("comRespProd").value);
  const comRespLab = parseFloat(document.getElementById("comRespLab").value);
  const comExamProf = parseFloat(document.getElementById("comExamProf").value);
  const comExamAcademicos =  parseFloat(document.getElementById("comExamAcademicos").value);
  const comPRODEPformResp = parseFloat(document.getElementById("comPRODEPformResp").value);
  const comPRODEPformInteg =  parseFloat(document.getElementById("comPRODEPformInteg").value);
  const comPRODEPenconsResp = parseFloat(document.getElementById("comPRODEPenconsResp").value);
  const comPRODEPenconsInteg =  parseFloat(document.getElementById("comPRODEPenconsInteg").value);
  const comPRODEPconsResp =  parseFloat(document.getElementById("comPRODEPconsResp").value);
  const comPRODEPconsInteg = parseFloat(document.getElementById("comPRODEPconsInteg").value);

  //Comision minimo Resultante
  const comision3_19 = min40(comCGUtitular, comCGUespecial, comCGUpermanente, comCAACtitular, 
    comCAACintegCom, comComDepart, comComPEDPD, comComPartPos, comRespPos, comRespCarrera, comRespProd, comRespLab, 
    comExamProf, comExamAcademicos, comPRODEPformResp, comPRODEPformInteg, comPRODEPenconsResp, comPRODEPenconsInteg, comPRODEPconsResp, comPRODEPconsInteg);

    // Actualizar todos los elementos con la clase "comision3_19"
    document.querySelectorAll('.comision3_19').forEach(element => {
        element.textContent = comision3_19.toFixed(2);
    });

        console.log("Minimo Resultante de Comision 3.19: ", comision3_19);

  
}

        // Función para asignar valores solo si el elemento existe
        function setValue(selector, value) {
          let element;
      
          // Verificar si el selector usa "name="
          if (selector.startsWith('input[name="')) {
              element = document.querySelector(selector);
          } else if (selector.startsWith('span[name="')) {
              element = document.querySelector(selector); // Caso especial para `span[name=""]`
          } else {
              element = document.getElementById(selector); // Para `id`
              if (!element) {
                  element = document.querySelector(selector); // Para `class` y otros selectores válidos
              }
          }
      
          // Si el elemento existe, asigna el valor correctamente
          if (element) {
              if (element.tagName === 'INPUT' || element.tagName === 'SELECT') {
                  element.value = value;
              } else {
                  element.textContent = value;
              }
              
              // Si el ID del elemento comienza con "obs" y el valor es vacío, aplica color de fondo
              if (element && element.id.startsWith("obs") && (value === "" || value === null || value === undefined)) {
                element.style.backgroundColor = "rgb(214, 255, 247)";
                element.style.padding = "15px 25px"; 
                element.style.display = "inline-block"; 
                element.style.minHeight = "30px";
              }


          } else {
              console.warn(`Elemento no encontrado: ${selector}`);
          }
      }