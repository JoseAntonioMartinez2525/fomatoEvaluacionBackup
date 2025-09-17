//Puntajes a Evaluar

let docencia = 0;
    function calculateSubtotal1() {
        // Get the values of each span element, parse them as float and sum them up
        const values = [
            parseFloat(document.getElementById("actv3Comision").innerText) || 0,
            parseFloat(document.getElementById("comision3_2").innerText) || 0,
            parseFloat(document.getElementById("comision3_3").innerText) || 0,
            parseFloat(document.getElementById("comision3_4").innerText) || 0,
            parseFloat(document.getElementById("comision3_5").innerText) || 0,
            parseFloat(document.getElementById("comision3_6").innerText) || 0,
            parseFloat(document.getElementById("comision3_7").innerText) || 0,
            parseFloat(document.getElementById("comision3_8").innerText) || 0,
        ];

        // Sum the values
        const subtotal = values.reduce((acc, value) => acc + value, 0);
        

        // Update the label with the subtotal
        document.getElementById("comision3_1To3_8").innerText = subtotal;
    }

    function calculateSubtotal2() {
        // Get the values of each span element, parse them as float and sum them up
        const values = [

            parseFloat(document.getElementById("comision3_9").innerText) || 0,
            parseFloat(document.getElementById("comision3_10").innerText) || 0,
            parseFloat(document.getElementById("comision3_11").innerText) || 0,


        ];

        // Sum the values
        const subtotal = values.reduce((acc, value) => acc + value, 0);
        

        // Update the label with the subtotal
        document.getElementById("comision3_9To3_11").innerText = subtotal;
    }

        function calculateSubtotal3() {
        // Get the values of each span element, parse them as float and sum them up
        const values = [

            parseFloat(document.getElementById("comision3_12").innerText) || 0,
            parseFloat(document.getElementById("comision3_13").innerText) || 0,
            parseFloat(document.getElementById("comision3_14").innerText) || 0,
            parseFloat(document.getElementById("comision3_15").innerText) || 0,
            parseFloat(document.getElementById("comision3_16").innerText) || 0,

        ];

        // Sum the values
        const subtotal = values.reduce((acc, value) => acc + value, 0);
        

        // Update the label with the subtotal
        document.getElementById("comision3_12To3_16").innerText = subtotal;
    }

function onActv3Subtotal(){
  const scoreOne =  parseFloat(document.getElementById("puntaje60").textContent);
  const quantityOne = parseFloat(document.getElementById("elaboracion").value);
  const scoreTwo = parseFloat(document.getElementById("puntaje40").textContent) ;
  const q2 = parseFloat(document.getElementById("elaboracion2").value);
  const s3 = parseFloat(document.getElementById("puntaje10").textContent) ;
  const q3 = parseFloat(document.getElementById("elaboracion3").value);
  const s4 = parseFloat(document.getElementById("puntaje20").textContent) ;
  const q4 = parseFloat(document.getElementById("elaboracion4").value);
  const s5 = parseFloat(document.getElementById("p10").textContent) ;
  const q5 = parseFloat(document.getElementById("elaboracion5").value);

  console.log("Score:", scoreOne);
  console.log("Quantity:", quantityOne);
  console.log("Score 2:", scoreTwo);
  console.log("Quantity 2:", q2);
  console.log("Score 3:", s3);
  console.log("Quantity 3:", q3);
  console.log("Score 4:", s4);
  console.log("Quantity 4:", q4);
  console.log("Score 5:", s5);
  console.log("Quantity 5:", q5);

  //calculos inciso a)
  const subtotalOne = subtotal(scoreOne,quantityOne);
 document.getElementById("elaboracionSubTotal1").innerHTML= subtotalOne;
 console.log(subtotalOne);

 //calculos inciso b)
 const subtotalTwo = subtotal(scoreTwo,q2);
 document.getElementById("elaboracionSubTotal2") .innerHTML= subtotalTwo;
 console.log(subtotalTwo);

  //calculos inciso c)
 const subtotal3 = subtotal(s3,q3);
 document.getElementById("elaboracionSubTotal3") .innerHTML= subtotal3;
 console.log(subtotal3);
 
 //calculos inciso d)
const subtotal4 = subtotal(s4,q4);
 document.getElementById("elaboracionSubTotal4") .innerHTML= subtotal4;
 console.log(subtotal4);

  //calculos inciso d)
const subtotal5 = subtotal(s5,q5);
 document.getElementById("elaboracionSubTotal5") .innerHTML= subtotal5;
 console.log(subtotal5);

 //calculos minimo resultante
 const score3_1 = min60 (subtotalOne,subtotalTwo,subtotal3,subtotal4,subtotal5);
 document.getElementById("score3_1") .innerHTML= score3_1;
 console.log("onActv3Subtotal ~ minimoResultante:", score3_1);

if (!isNaN(score3_1)) {
    docencia += score3_1;
}
if(docencia>=60){

  document.getElementById("docencia").innerHTML = 60;
}
 console.log("docencia:", docencia);
 document.getElementById("docencia").innerHTML = docencia;
 data.score3_1=score3_1;
 

}


function onActv3Puntaje() {
  const r1 = parseFloat(document.getElementById('r1').value);
  const r2 = parseFloat(document.getElementById('r2').value);
  const r3 = parseFloat(document.getElementById('r3').value);
  
  const ran1 = parseFloat(document.getElementById('ran1').textContent);
  const ran2 = parseFloat(document.getElementById('ran2').textContent);
  const ran3 = parseFloat(document.getElementById('ran3').textContent);

  const cant1Puntaje = subtotal(ran1, r1);
  const cant2Puntaje = subtotal(ran2, r2);
  const cant3Puntaje = subtotal(ran3, r3);


  document.getElementById("cant1").innerHTML = cant1Puntaje;

  document.getElementById("cant2").innerHTML = cant2Puntaje;
  document.getElementById("cant3").innerHTML = cant3Puntaje;
  console.log("r1:", r1);
  console.log("r2:", r2);
  console.log("r3:", r3);
  console.log("ran1:", ran1);
  console.log("ran2:", ran2);
  console.log("ran3:", ran3);

    console.log("Puntaje 1: ",cant1Puntaje);
    console.log("Puntaje 2: ",cant2Puntaje);
    console.log("Puntaje 3: ",cant3Puntaje);
      // Update data object with score values
  data.cant1 = cant1Puntaje;
  data.cant2 = cant2Puntaje;
  data.cant3 = cant3Puntaje;


  //promedio minimo subtotal
  const score3_2 = min50(cant1Puntaje, cant2Puntaje, cant3Puntaje);
  document.getElementById("score3_2") .innerHTML= score3_2; 
  console.log("Puntaje promedio del Puntaje a Evaluar :", score3_2);

if (!isNaN(score3_2)) {
    docencia += score3_2;
}
   console.log("docencia:", docencia);
    document.getElementById("docencia").innerHTML = docencia;
  data.score3_2=score3_2;
  
}



function onActv3SubTotal3(){
  //cantidades
  const c1 =  parseFloat(document.getElementById("rc1").value);
  const c2 =  parseFloat(document.getElementById("rc2").value);
  const c3 =  parseFloat(document.getElementById("rc3").value);
  const c4 =  parseFloat(document.getElementById("rc4").value);

  //puntaje
  const p1 = parseFloat(document.getElementById("p100").textContent);
  const p2 = parseFloat(document.getElementById("p50").textContent);
  const p3 = parseFloat(document.getElementById("p30").textContent);
  const p4 = parseFloat(document.getElementById("p25").textContent);

  //calculos inciso  a)
  const  i_a = subtotal(c1,p1);
  document.getElementById("stotal1").innerHTML= i_a;
  console.log("Inciso a-subtotal:", i_a);

    //calculos inciso  b)
  const  i_b = subtotal(c2,p2);
  document.getElementById("stotal2").innerHTML= i_b;
  console.log("Inciso b-subtotal:", i_b);

  //calculos inciso  c)
  const  i_c = subtotal(c3,p3);
  document.getElementById("stotal3").innerHTML= i_c;
  console.log("Inciso c-subtotal:", i_c);

    //calculos inciso  d)
  const  i_d = subtotal(c4,p4);
  document.getElementById("stotal4").innerHTML= i_d;
  console.log("Inciso d-subtotal:", i_d);

  //subtotal puntaje a evaluar
  const score3_3 =  minWithSumThree (i_a,i_b,i_c,i_d);
 document.getElementById("score3_3") .innerHTML= score3_3;
 console.log("Suma Puntaje a Evaluar:", score3_3);

if (!isNaN(score3_3)) {
    docencia += score3_3;
}

if(docencia>=700){

  document.getElementById("docencia").innerHTML = 700;
}
 document.getElementById("docencia").innerHTML = docencia;
 data.score3_3=score3_3;
  
}


function onActv3SubTotal3_4(){
  //cantidades
  const c1 =  parseFloat(document.getElementById("cantInternacional").value);
  const c2 =  parseFloat(document.getElementById("cantNacional").value);
  const c3 =  parseFloat(document.getElementById("cantidadRegional").value);
  const c4 =  parseFloat(document.getElementById("cantPreparacion").value);

  //puntaje
  const p1 = parseFloat(document.getElementById("p60").textContent);
  const p2 = parseFloat(document.getElementById("p30Nac").textContent);
  const p3 = parseFloat(document.getElementById("p20").textContent);
  const p4 = parseFloat(document.getElementById("p30Prep").textContent);

  //calculos inciso  a)
  const  cantInternacional2 = subtotal(c1,p1);
  document.getElementById("cantInternacional2").innerHTML= cantInternacional2;
  console.log("Internacional-subtotal:", cantInternacional2);

    //calculos inciso  b)
  const  cantNacional2 = subtotal(c2,p2);
  document.getElementById("cantNacional2").innerHTML= cantNacional2;
  console.log("Nacional-subtotal:", cantNacional2);

  //calculos inciso  c)
  const  cantidadRegional2 = subtotal(c3,p3);
  document.getElementById("cantidadRegional2").innerHTML= cantidadRegional2;
  console.log("Regional-subtotal:", cantidadRegional2);

    //calculos inciso  d)
  const  cantPreparacion2 = subtotal(c4,p4);
  document.getElementById("cantPreparacion2").innerHTML= cantPreparacion2;
  console.log("Preparacion-subtotal:", cantPreparacion2);

  //subtotal puntaje a evaluar
  const score3_4 =  min60 (cantInternacional2,cantNacional2, cantidadRegional2, cantPreparacion2);
 document.getElementById("score3_4") .innerHTML= score3_4;
 console.log("Suma Puntaje a Evaluar:", score3_4);

if (!isNaN(score3_4)) {
    docencia += score3_4;
}

if(docencia>=700){

  document.getElementById("docencia").innerHTML = 700;
}
 document.getElementById("docencia").innerHTML = docencia;
 data.score3_4 = score3_4;


}


function onActv3SubTotal3_5(){
  //cantidades
  const cantDA =  parseFloat(document.getElementById("cantDA").value);
  const cantCAAC =  parseFloat(document.getElementById("cantCAAC").value);


  //puntaje
  const p35 = parseFloat(document.getElementById("p35").textContent);
  const pCAAC40 = parseFloat(document.getElementById("pCAAC40").textContent);


  //calculos inciso  a)
  const  cantDA2 = subtotal(cantDA,p35);
  document.getElementById("cantDA2").innerHTML= cantDA2;
  console.log("DA-subtotal:", cantDA2);

    //calculos inciso  b)
  const  cantCAAC2 = subtotal(cantCAAC,pCAAC40);
  document.getElementById("cantCAAC2").innerHTML= cantCAAC2;
  console.log("Nacional-subtotal:", cantCAAC2);


  //subtotal puntaje a evaluar
  const score3_5 =  minWithSumThreeFive(cantDA2, cantCAAC2);
 document.getElementById("score3_5") .innerHTML= score3_5;
 console.log("Suma Puntaje a Evaluar:", score3_5);

if (!isNaN(score3_5)) {
    docencia += score3_5;
}

if(docencia>=700){

  document.getElementById("docencia").innerHTML = 700;
}
 document.getElementById("docencia").innerHTML = docencia;
 data.score3_5 = score3_5;


}

function onActv3SubTotal3_6(){
//Horas
  const puntaje3_6 =  parseFloat(document.getElementById("puntaje3_6").value);

  //Factor
  const pMedio = parseFloat(document.getElementById("pMedio").textContent);

const puntajeHoras3_6 = puntaje3_6 * pMedio;
 document.getElementById("puntajeHoras3_6").innerHTML= puntajeHoras3_6;


 const score3_6 = Math.min(puntajeHoras3_6,40);
 document.getElementById("score3_6").innerHTML= score3_6;

if (!isNaN(score3_6)) {
    docencia += score3_6;
    if(docencia>=700){

  document.getElementById("docencia").innerHTML = 700;
} else{
  document.getElementById("docencia").innerHTML = docencia;
}
}
 data.score3_6 = score3_6;
data.docencia  = docencia;
console.log("docencia 3:", docencia);

}



//Actividad 7
function onActv3SubTotal3_7(){
//Horas
  const puntaje3_7 =  parseFloat(document.getElementById("puntaje3_7").value);

  //Factor
  const pMedio2 = parseFloat(document.getElementById("pMedio2").textContent);

const puntajeHoras3_7 = puntaje3_7 * pMedio2;
 document.getElementById("puntajeHoras3_7").innerHTML= puntajeHoras3_7;


 const score3_7 = Math.min(puntajeHoras3_7,40);
 document.getElementById("score3_7").innerHTML= score3_7;

if (!isNaN(score3_7)) {
    docencia += score3_7;
}
 document.getElementById("docencia").innerHTML = docencia;
  data.score3_7 = score3_7;
  if(docencia>=700){

  document.getElementById("docencia").innerHTML = 700;
}

data.docencia  = docencia;
console.log("docencia 3:", docencia);
}





//Actividad 8
function onActv3SubTotal3_8(){
//Horas
  const puntaje3_8 =  parseFloat(document.getElementById("puntaje3_8").value);

  //Factor
  const p3_8 = parseFloat(document.getElementById("p3_8").textContent);

const puntajeHoras3_8 = puntaje3_8 * p3_8;
 document.getElementById("puntajeHoras3_8").innerHTML= puntajeHoras3_8;


 const score3_8 = Math.min(puntajeHoras3_8,40);
 document.getElementById("score3_8").innerHTML= score3_8;

if (!isNaN(score3_8)) {
    docencia += score3_8;
}
 document.getElementById("docencia").innerHTML = docencia;
 data.score3_8 = score3_8;
}

function onActv3SubTotal3_8_1(){
//Horas
  const puntaje3_8_1 =  parseFloat(document.getElementById("puntaje3_8_1").value);

  //Factor
  const p3_8_1 = parseFloat(document.getElementById("p3_8_1").textContent);

const puntajeHoras3_8_1 = puntaje3_8_1 * p3_8_1;
 document.getElementById("puntajeHoras3_8_1").innerHTML= puntajeHoras3_8_1;

const puntajeMaximoElement = document.getElementById("puntajeMaximo");
    let puntajeMaximo;

    if (puntajeMaximoElement.tagName === "INPUT") {
        puntajeMaximo = parseFloat(puntajeMaximoElement.value);
    } else {
        puntajeMaximo = parseFloat(puntajeMaximoElement.textContent);
    }

 const score3_8_1 = Math.min(puntajeHoras3_8_1,puntajeMaximo);
 document.getElementById("score3_8_1").innerHTML= score3_8_1;

if (!isNaN(score3_8_1)) {

    docencia += score3_8_1;
}
 document.getElementById("docencia").innerHTML = docencia;
 data.score3_8_1 = score3_8_1;
}


//Actividad 9
function onActv3SubTotal3_9(){

  //Puntaje
  const puntajeTutorias20_1 = parseFloat(document.getElementById("puntajeTutorias20_1").textContent);
  const puntajeTutorias15_1 = parseFloat(document.getElementById("puntajeTutorias15_1").textContent);
  const puntajeTutorias10_1 = parseFloat(document.getElementById("puntajeTutorias10_1").textContent);
  const puntajeTutorias55 = parseFloat(document.getElementById("puntajeTutorias55").textContent);
  const puntajeTutorias45 = parseFloat(document.getElementById("puntajeTutorias45").textContent);
  const puntajeTutorias35 = parseFloat(document.getElementById("puntajeTutorias35").textContent);
  const puntajeTutorias70 = parseFloat(document.getElementById("puntajeTutorias70").textContent);
  const puntajeTutorias60 = parseFloat(document.getElementById("puntajeTutorias60").textContent);
  const puntajeTutorias50 = parseFloat(document.getElementById("puntajeTutorias50").textContent);
  const puntajeTutorias30_1 = parseFloat(document.getElementById("puntajeTutorias30_1").textContent);
  const puntajeTutorias20_2 = parseFloat(document.getElementById("puntajeTutorias20_2").textContent);
  const puntajeTutorias15_2 = parseFloat(document.getElementById("puntajeTutorias15_2").textContent);
  const puntajeTutorias30_2 = parseFloat(document.getElementById("puntajeTutorias30_2").textContent);
  const puntajeTutorias20_3 = parseFloat(document.getElementById("puntajeTutorias20_3").textContent);
  const puntajeTutorias15_3 = parseFloat(document.getElementById("puntajeTutorias15_3").textContent);
  const puntajeTutorias15_4 = parseFloat(document.getElementById("puntajeTutorias15_4").textContent);
  const puntajeTutorias10_2 = parseFloat(document.getElementById("puntajeTutorias10_2").textContent);

  //Cantidad
  const puntaje3_9_1 = parseFloat(document.getElementById("puntaje3_9_1").value);
  const puntaje3_9_2 = parseFloat(document.getElementById("puntaje3_9_2").value);
  const puntaje3_9_3 =  parseFloat(document.getElementById("puntaje3_9_3").value);
  const puntaje3_9_4 = parseFloat(document.getElementById("puntaje3_9_4").value);
  const puntaje3_9_5 = parseFloat(document.getElementById("puntaje3_9_5").value);
  const puntaje3_9_6 =  parseFloat(document.getElementById("puntaje3_9_6").value);
  const puntaje3_9_7 = parseFloat(document.getElementById("puntaje3_9_7").value);
  const puntaje3_9_8 = parseFloat(document.getElementById("puntaje3_9_8").value);
  const puntaje3_9_9 =  parseFloat(document.getElementById("puntaje3_9_9").value);
  const puntaje3_9_10 = parseFloat(document.getElementById("puntaje3_9_10").value);
  const puntaje3_9_11 = parseFloat(document.getElementById("puntaje3_9_11").value);
  const puntaje3_9_12 =  parseFloat(document.getElementById("puntaje3_9_12").value);
  const puntaje3_9_13 = parseFloat(document.getElementById("puntaje3_9_13").value);
  const puntaje3_9_14 = parseFloat(document.getElementById("puntaje3_9_14").value);
  const puntaje3_9_15 =  parseFloat(document.getElementById("puntaje3_9_15").value);
  const puntaje3_9_16 = parseFloat(document.getElementById("puntaje3_9_16").value);
  const puntaje3_9_17 = parseFloat(document.getElementById("puntaje3_9_17").value);

  //calculos subtotales
    //a)
    const tutorias1 = subtotal(puntajeTutorias20_1, puntaje3_9_1);
    document.getElementById("tutorias1").innerHTML= tutorias1;
    console.log("tutorias 1:", tutorias1);

    //b
    const tutorias2 = subtotal(puntajeTutorias15_1, puntaje3_9_2);
    document.getElementById("tutorias2").innerHTML= tutorias2;
    console.log("tutorias 2:", tutorias2);

    //c)
    const tutorias3 = subtotal(puntajeTutorias10_1, puntaje3_9_3);
    document.getElementById("tutorias3").innerHTML= tutorias3;
    console.log("tutorias 3:", tutorias3);

    //d
    const tutorias4 = subtotal(puntajeTutorias55, puntaje3_9_4);
    document.getElementById("tutorias4").innerHTML= tutorias4;
    console.log("tutorias 4:", tutorias4);

    //e
    const tutorias5 = subtotal(puntajeTutorias45, puntaje3_9_5);
    document.getElementById("tutorias5").innerHTML= tutorias5;
    console.log("tutorias 5:", tutorias5);

    //f
    const tutorias6 = subtotal(puntajeTutorias35,puntaje3_9_6);
    document.getElementById("tutorias6").innerHTML = tutorias6; 
    console.log("tutorias 6:", tutorias6)
    
    //g
    const tutorias7 = subtotal(puntajeTutorias70,puntaje3_9_7);
    document.getElementById("tutorias7").innerHTML = tutorias7;
    console.log("🚀 ~ onActv3SubTotal3_9 ~ tutorias7:", tutorias7)
    
    //h
    const tutorias8 = subtotal(puntajeTutorias60,puntaje3_9_8);
    document.getElementById("tutorias8").innerHTML = tutorias8;
    console.log("🚀 ~ onActv3SubTotal3_9 ~ tutorias8:", tutorias8)
    
    //i
    const tutorias9 = subtotal(puntajeTutorias50,puntaje3_9_9);
    document.getElementById("tutorias9").innerHTML = tutorias9;
    console.log("🚀 ~ onActv3SubTotal3_9 ~ tutorias9:", tutorias9)

    //j
    const tutorias10 = subtotal(puntajeTutorias30_1,puntaje3_9_10);
    document.getElementById("tutorias10").innerHTML = tutorias10;
    console.log("🚀 ~ onActv3SubTotal3_9 ~ tutorias10:", tutorias10)

    //k
    const tutorias11 = subtotal(puntajeTutorias20_2,puntaje3_9_11);
    document.getElementById("tutorias11").innerHTML = tutorias11;
    console.log("🚀 ~ onActv3SubTotal3_9 ~ tutorias11:", tutorias11)

    //l
    const tutorias12 = subtotal(puntajeTutorias15_2,puntaje3_9_12);
    document.getElementById("tutorias12").innerHTML = tutorias12;
    console.log("🚀 ~ onActv3SubTotal3_9 ~ tutorias12:", tutorias12)

    //m
    const tutorias13 = subtotal(puntajeTutorias30_2,puntaje3_9_13);
    document.getElementById("tutorias13").innerHTML = tutorias13;
    console.log("🚀 ~ onActv3SubTotal3_9 ~ tutorias13:", tutorias13)

    //n
    const tutorias14 = subtotal(puntajeTutorias20_3,puntaje3_9_14);
    document.getElementById("tutorias14").innerHTML = tutorias14;
    console.log("🚀 ~ onActv3SubTotal3_9 ~ tutorias14:", tutorias14)

    //o
    const tutorias15 = subtotal(puntajeTutorias15_3,puntaje3_9_15);
    document.getElementById("tutorias15").innerHTML = tutorias15;
    console.log("🚀 ~ onActv3SubTotal3_9 ~ tutorias15:", tutorias15)

    //p
    const tutorias16 = subtotal(puntajeTutorias15_4,puntaje3_9_16);
    document.getElementById("tutorias16").innerHTML = tutorias16;
    console.log("🚀 ~ onActv3SubTotal3_9 ~ tutorias16:", tutorias16)
    
    //q
    const tutorias17 = subtotal(puntajeTutorias10_2,puntaje3_9_17);
    document.getElementById("tutorias17").innerHTML = tutorias17;
    console.log("🚀 ~ onActv3SubTotal3_9 ~ tutorias17:", tutorias17)

//subtotal minimo resultante
const score3_9 = minTutorias(tutorias1,tutorias2,tutorias3, tutorias4, tutorias6, tutorias7, tutorias8, tutorias9, tutorias10, tutorias11, tutorias12, tutorias13, tutorias14, tutorias15, tutorias16, tutorias17);
 document.getElementById("score3_9").innerHTML= score3_9;
 console.log("Puntaje a Evaluar minimo resultante Actividad 3.9 :", score3_9);
 
if (!isNaN(score3_9)) {
    docencia += score3_9;
  if(docencia>=700){

  document.getElementById("docencia").innerHTML = 700;
  }else{
    document.getElementById("docencia").innerHTML = docencia;
  }

}

 data.score3_9 = score3_9;
 data.docencia  = docencia;
console.log("docencia 3:", docencia);
}

function onActv3SubTotal3_10(){
    //Puntaje
  const puntajeGrupales = parseFloat(document.getElementById("puntajeGrupales").textContent);
  const puntajeIndividual = parseFloat(document.getElementById("puntajeIndividual").textContent);

  //Cantidad
  const grupalesCant = parseFloat(document.getElementById("grupalesCant").value);
  const individualCant = parseFloat(document.getElementById("individualCant").value);

  //Puntaje a evaluar
  const  evaluarGrupales=subtotal(puntajeGrupales,grupalesCant);
document.getElementById("evaluarGrupales").innerHTML = evaluarGrupales;
console.log("🚀 ~ onActv3SubTotal3_10 ~ evaluarGrupales:", evaluarGrupales)

  const  evaluarIndividual=subtotal(puntajeIndividual,individualCant);
  document.getElementById("evaluarIndividual").innerHTML = evaluarIndividual;
  console.log("🚀 ~ onActv3SubTotal3_10 ~ evaluarIndividual:", evaluarIndividual)

  const suma3_10 = evaluarGrupales + evaluarIndividual;
//subtotal minimo resultante
  const score3_10 = Math.min(suma3_10, 115);
  document.getElementById("score3_10").innerHTML= score3_10;
  console.log("🚀 ~ Puntaje a Evaluar minimo Resultante ~ score3_10:", score3_10)

    if (!isNaN(score3_10)) {
    docencia += score3_10;

    if(docencia>=700){

  document.getElementById("docencia").innerHTML = 700;
}else{
  document.getElementById("docencia").innerHTML = docencia;
}
}


  data.score3_10 = score3_10;
}

function onActv3SubTotal3_11(){
  //Puntaje

  const academica = parseFloat(document.getElementById("academica").textContent);
  const servicio =  parseFloat(document.getElementById("servicio").textContent);
  const practicas = parseFloat(document.getElementById("practicas").textContent);

  //Cantidad

  const cantAsesoria = parseFloat(document.getElementById("cantAsesoria").value);
  const cantServicio = parseFloat(document.getElementById("cantServicio").value);
  const cantPracticas = parseFloat(document.getElementById("cantPracticas").value);

//Puntaje a Evaluar
const subtotalAsesoria = subtotal(academica, cantAsesoria);
document.getElementById( "subtotalAsesoria" ).innerText= subtotalAsesoria;
console.log("Subtotal Asesoría ", subtotalAsesoria );

const  subtotalServicio = subtotal(servicio, cantServicio);
document.getElementById( "subtotalServicio" ).innerText= subtotalServicio;
console.log("Subtotal Servicios: ", subtotalServicio );

const subtotalPracticas = subtotal(practicas, cantPracticas);
document.getElementById( "subtotalPracticas" ).innerText= subtotalPracticas;
console.log("Subtotal Prácticas", subtotalPracticas);

//Puntaje Minimo resultante
const sumaScore3_11 = subtotalAsesoria + subtotalServicio + subtotalPracticas;
const score3_11 =  Math.min(sumaScore3_11,95);
document.getElementById("score3_11").innerHTML = score3_11;

if (!isNaN(score3_11)) {
    docencia += score3_11;
    
    if(docencia>=700){

  document.getElementById("docencia").innerHTML = 700;
}else{
  document.getElementById("docencia").innerHTML = docencia;
}
}

data.score3_11 = score3_11;

}

function onActv3SubTotal3_12(){
  //Puntaje

  const puntajeCientificos = parseFloat(document.getElementById("puntajeCientificos").textContent);
  const puntajeDivulgacion =  parseFloat(document.getElementById("puntajeDivulgacion").textContent);
  const puntajeTraduccion = parseFloat(document.getElementById("puntajeTraduccion").textContent);
  const puntajeArbitrajeInt = parseFloat(document.getElementById("puntajeArbitrajeInt").textContent);
  const puntajeArbitrajeNac = parseFloat(document.getElementById("puntajeArbitrajeNac").textContent);
  const puntajeSinInt = parseFloat(document.getElementById("puntajeSinInt").textContent);
  const puntajeSinNac = parseFloat(document.getElementById("puntajeSinNac").textContent);
  const puntajeAutor =  parseFloat(document.getElementById("puntajeAutor").textContent);
  const puntajeEditor = parseFloat(document.getElementById("puntajeEditor").textContent);
  const puntajeWeb = parseFloat(document.getElementById("puntajeWeb").textContent);

  //Cantidad

  const cantCientifico = parseFloat(document.getElementById("cantCientifico").value);
  const cantDivulgacion = parseFloat(document.getElementById("cantDivulgacion").value);
  const cantTraduccion = parseFloat(document.getElementById("cantTraduccion").value);
  const cantArbitrajeInt = parseFloat(document.getElementById("cantArbitrajeInt").value);
  const cantArbitrajeNac = parseFloat(document.getElementById("cantArbitrajeNac").value);
  const cantSinInt = parseFloat(document.getElementById("cantSinInt").value);
  const cantSinNac = parseFloat(document.getElementById("cantSinNac").value);
  const cantAutor = parseFloat(document.getElementById("cantAutor").value);
  const cantEditor = parseFloat(document.getElementById("cantEditor").value);    
  const cantWeb = parseFloat(document.getElementById("cantWeb").value); 

//Puntaje a Evaluar

const  subtotalCientificos = subtotal(puntajeCientificos,cantCientifico);
document.getElementById("subtotalCientificos").innerHTML=subtotalCientificos;


const  subtotalDivulgacion= subtotal(puntajeDivulgacion,cantDivulgacion);
document.getElementById("subtotalDivulgacion").innerHTML= subtotalDivulgacion;


const  subtotalTraduccion = subtotal(puntajeTraduccion,cantTraduccion);  
document.getElementById("subtotalTraduccion").innerHTML= subtotalTraduccion;


const subtotalArbitrajeInt = subtotal(puntajeArbitrajeInt,cantArbitrajeInt);
document.getElementById("subtotalArbitrajeInt").innerHTML = subtotalArbitrajeInt;


const  subtotalArbitrajeNac  = subtotal(puntajeArbitrajeNac,cantArbitrajeNac);
document.getElementById("subtotalArbitrajeNac").innerHTML= subtotalArbitrajeNac;


const  subtotalSinInt  = subtotal(puntajeSinInt,cantSinInt);
document.getElementById("subtotalSinInt").innerHTML= subtotalSinInt;


const  subtotalSinNac  = subtotal(puntajeSinNac,cantSinNac);
document.getElementById("subtotalSinNac").innerHTML=subtotalSinNac;


const  subtotalAutor  = subtotal(puntajeAutor,cantAutor);
document.getElementById("subtotalAutor").innerHTML=subtotalAutor;


const  subtotalEditor  = subtotal(puntajeEditor,cantEditor);
document.getElementById("subtotalEditor").innerHTML=subtotalEditor;


const  subtotalWeb  = subtotal(puntajeWeb,cantWeb);
document.getElementById("subtotalWeb").innerHTML=subtotalWeb;


//resultados
console.log("SubTotal Cientificos: ", subtotalCientificos);
console.log("SubTotal Divulgación: ", subtotalDivulgacion);
console.log("SubTotal Traducciones: ", subtotalTraduccion) ;
console.log("Subtotal Arbitraje Internacional: ",subtotalArbitrajeInt );
console.log("Subtotal Arbitraje Nacional: ",subtotalArbitrajeNac );
console.log("Subtotal Sin Internacional:", subtotalSinInt);
console.log("Subtotal Sin Nacional:", subtotalSinNac);
console.log("Subtotal Autor:", subtotalAutor);
console.log("Subtotal Editor:", subtotalEditor);
console.log("Subtotal Diseño Web:", subtotalWeb);

//Puntaje Minimo resultante
  sumaEvaluar12 = subtotalCientificos + subtotalDivulgacion + subtotalTraduccion + subtotalArbitrajeInt + subtotalArbitrajeNac +
  subtotalSinInt + subtotalSinNac + subtotalAutor + subtotalEditor + subtotalWeb;
  const score3_12 = Math.min(sumaEvaluar12,150);

  document.getElementById( "score3_12" ).innerHTML= score3_12;
  console.log ("Puntaje de las Evaluaciones 3-12: "), score3_12;

    if (!isNaN(score3_12)) {
    docencia += score3_12;
    if(docencia>=700){

  document.getElementById("docencia").innerHTML = 700;
}else{
  document.getElementById("docencia").innerHTML = docencia;
}
}


  data.score3_12 = score3_12;

}


function onActv3SubTotal3_13(){
  //Puntaje

  const puntajeInicioFinanExt = parseFloat(document.getElementById("puntajeInicioFinanExt").textContent);
  const puntajeInicioInvInterno =  parseFloat(document.getElementById("puntajeInicioInvInterno").textContent);
  const puntajeReporteFinanciamExt = parseFloat(document.getElementById("puntajeReporteFinanciamExt").textContent);
  const puntajeReporteInvInt = parseFloat(document.getElementById("puntajeReporteInvInt").textContent);
  

  //Cantidad

  const cantInicioFinanExt = parseFloat(document.getElementById("cantInicioFinanExt").value);
  const cantInicioInvInterno = parseFloat(document.getElementById("cantInicioInvInterno").value);
  const cantReporteFinanciamExt = parseFloat(document.getElementById("cantReporteFinanciamExt").value);
  const cantReporteInvInt = parseFloat(document.getElementById("cantReporteInvInt").value);


//Puntaje a Evaluar

const  subtotalInicioFinanExt = subtotal(puntajeInicioFinanExt,cantInicioFinanExt);
document.getElementById("subtotalInicioFinanExt").innerHTML=subtotalInicioFinanExt;


const  subtotalInicioInvInterno= subtotal(puntajeInicioInvInterno,cantInicioInvInterno);
document.getElementById("subtotalInicioInvInterno").innerHTML= subtotalInicioInvInterno;


const  subtotalReporteFinanciamExt = subtotal(puntajeReporteFinanciamExt,cantReporteFinanciamExt);  
document.getElementById("subtotalReporteFinanciamExt").innerHTML= subtotalReporteFinanciamExt;


const subtotalReporteInvInt = subtotal(puntajeReporteInvInt,cantReporteInvInt);
document.getElementById("subtotalReporteInvInt").innerHTML = subtotalReporteInvInt;



//resultados
console.log("SubTotal Cientificos: ", subtotalInicioFinanExt);
console.log("SubTotal Divulgación: ", subtotalInicioInvInterno);
console.log("SubTotal Traducciones: ", subtotalReporteFinanciamExt) ;
console.log("Subtotal Arbitraje Internacional: ",subtotalReporteInvInt );


//Puntaje Minimo resultante
  sumaEvaluar13 = subtotalInicioFinanExt + subtotalInicioInvInterno + subtotalReporteFinanciamExt + subtotalReporteInvInt;
  const score3_13 = Math.min(sumaEvaluar13,130);

  document.getElementById( "score3_13" ).innerHTML= score3_13;
  console.log ("Puntaje de las Evaluaciones 3.13: "), score3_13;

    if (!isNaN(score3_13)) {
    docencia += score3_13;
    if(docencia>=700){

  document.getElementById("docencia").innerHTML = 700;
}else{
  document.getElementById("docencia").innerHTML = docencia;
}
}

  data.score3_13 = score3_13;



}



function onActv3SubTotal3_14(){
  //Puntaje

  const puntajeCongresoInt = parseFloat(document.getElementById("puntajeCongresoInt").textContent);
  const puntajeCongresoNac =  parseFloat(document.getElementById("puntajeCongresoNac").textContent);
  const puntajeCongresoLoc = parseFloat(document.getElementById("puntajeCongresoLoc").textContent);

  

  //Cantidad

  const cantCongresoInt = parseFloat(document.getElementById("cantCongresoInt").value);
  const cantCongresoNac = parseFloat(document.getElementById("cantCongresoNac").value);
  const cantCongresoLoc = parseFloat(document.getElementById("cantCongresoLoc").value);



//Puntaje a Evaluar

const  subtotalCongresoInt = subtotal(puntajeCongresoInt,cantCongresoInt);
document.getElementById("subtotalCongresoInt").innerHTML=subtotalCongresoInt;


const  subtotalCongresoNac= subtotal(puntajeCongresoNac,cantCongresoNac);
document.getElementById("subtotalCongresoNac").innerHTML= subtotalCongresoNac;


const  subtotalCongresoLoc = subtotal(puntajeCongresoLoc,cantCongresoLoc);  
document.getElementById("subtotalCongresoLoc").innerHTML= subtotalCongresoLoc;

//resultados
console.log("SubTotal Congreso Internacional: ", subtotalCongresoInt);
console.log("SubTotal Congreso Nacional: ", subtotalCongresoNac);
console.log("SubTotal Congreso Local: ", subtotalCongresoLoc) ;


//Puntaje Minimo resultante
  const score3_14 = min40(subtotalCongresoInt,subtotalCongresoNac,subtotalCongresoLoc);

  document.getElementById( "score3_14" ).innerHTML= score3_14;
  console.log ("Puntaje de las Evaluaciones 3.14: "), score3_14;

    if (!isNaN(score3_14)) {
    docencia += score3_14;
      if(docencia>=700){

  document.getElementById("docencia").innerHTML = 700;
}
    else{
      document.getElementById("docencia").innerHTML = docencia;
    }
}
 
  data.score3_14 = score3_14;

}


function onActv3SubTotal3_15(){
  //Puntaje

  const puntajePatentes = parseFloat(document.getElementById("puntajePatentes").textContent);
  const puntajePrototipos =  parseFloat(document.getElementById("puntajePrototipos").textContent);


  //Cantidad

  const cantPatentes = parseFloat(document.getElementById("cantPatentes").value);
  const cantPrototipos = parseFloat(document.getElementById("cantPrototipos").value);

//Puntaje a Evaluar

const  subtotalPatentes = subtotal(puntajePatentes,cantPatentes);
document.getElementById("subtotalPatentes").innerHTML=subtotalPatentes;


const  subtotalPrototipos= subtotal(puntajePrototipos,cantPrototipos);
document.getElementById("subtotalPrototipos").innerHTML= subtotalPrototipos;


//resultados
console.log("SubTotal Registro de Patentes: ", subtotalPatentes);
console.log("SubTotal Desarrollo de Prototipos: ", subtotalPrototipos);


//Puntaje Minimo resultante
  const score3_15 = min60(subtotalPatentes,subtotalPrototipos);

  document.getElementById( "score3_15" ).innerHTML= score3_15;
  console.log ("Puntaje 3.15: "), score3_15;

    if (!isNaN(score3_15)) {
    docencia += score3_15;
      if(docencia>=700){

  document.getElementById("docencia").innerHTML = 700;
}else{
   document.getElementById("docencia").innerHTML = docencia;
}
}




 console.log("onActv3SubTotal3_15 ~ docencia:", docencia);
  data.score3_15 = score3_15;

}


function onActv3SubTotal3_16(){
  //Puntaje

  const puntajeArbInt = parseFloat(document.getElementById("puntajeArbInt").textContent);
  const puntajeArbINac =  parseFloat(document.getElementById("puntajeArbINac").textContent);
  const puntajePubInt = parseFloat(document.getElementById("puntajePubInt").textContent);
  const puntajePubINac =  parseFloat(document.getElementById("puntajePubINac").textContent);
  const puntajeRevInt = parseFloat(document.getElementById("puntajeRevInt").textContent);
  const puntajeRevINac =  parseFloat(document.getElementById("puntajeRevINac").textContent);
  const puntajeRevista = parseFloat(document.getElementById("puntajeRevista").textContent);


  //Cantidad

  const cantArbInt = parseFloat(document.getElementById("cantArbInt").value);
  const cantArbNac = parseFloat(document.getElementById("cantArbNac").value);
  const cantPubInt = parseFloat(document.getElementById("cantPubInt").value);
  const cantPubNac = parseFloat(document.getElementById("cantPubNac").value);
  const cantRevInt = parseFloat(document.getElementById("cantRevInt").value);
  const cantRevNac = parseFloat(document.getElementById("cantRevNac").value);
  const cantRevista = parseFloat(document.getElementById("cantRevista").value);

//Puntaje a Evaluar

const  subtotalArbInt = subtotal(puntajeArbInt,cantArbInt);
document.getElementById("subtotalArbInt").innerHTML=subtotalArbInt;


const  subtotalArbNac= subtotal(puntajeArbINac,cantArbNac);
document.getElementById("subtotalArbNac").innerHTML= subtotalArbNac;

const  subtotalPubInt = subtotal(puntajePubInt,cantPubInt);
document.getElementById("subtotalPubInt").innerHTML=subtotalPubInt;


const  subtotalPubNac= subtotal(puntajePubINac,cantPubNac);
document.getElementById("subtotalPubNac").innerHTML= subtotalPubNac;

const  subtotalRevInt = subtotal(puntajeRevInt,cantRevInt);
document.getElementById("subtotalRevInt").innerHTML=subtotalRevInt;


const  subtotalRevNac= subtotal(puntajeRevINac,cantRevNac);
document.getElementById("subtotalRevNac").innerHTML= subtotalRevNac;

const  subtotalRevista= subtotal(puntajeRevista,cantRevista);
document.getElementById("subtotalRevista").innerHTML= subtotalRevista;


//resultados
console.log("SubTotal Arbitraje a proyectos de investigación Internacional: ", subtotalArbInt);
console.log("SubTotal Arbitraje a proyectos de investigación Nacional: ", subtotalArbNac);
console.log("SubTotal Arbitraje de publicaciones Internacional: ",subtotalPubInt);
console.log("SubTotal Arbitraje de publicaciones Nacional: ", subtotalPubNac);
console.log("SubTotal Revisor(a) de libros, corrector(a) Internacional ", subtotalRevInt);
console.log("SubTotal Revisor(a) de libros, corrector(a) Nacional ", subtotalRevNac);
console.log("SubTotal Consejo editorial de revista, edición de revista: ", subtotalRevista);


//Puntaje Minimo resultante
  const score3_16 = min30(subtotalArbInt,subtotalArbNac,subtotalPubInt,subtotalPubNac,subtotalRevInt,subtotalRevNac,subtotalRevista);

  document.getElementById( "score3_16" ).innerHTML= score3_16;
  console.log ("Puntaje de las Evaluaciones 3.16: "), score3_16;

    if (!isNaN(score3_16)) {
    docencia += score3_16;
    if(docencia>=700){

  document.getElementById("docencia").innerHTML = 700;
} else{
   document.getElementById("docencia").innerHTML = docencia;
}
}
   console.log("onActv3SubTotal3_16 ~ docencia:", docencia);
  data.score3_16 = score3_16;

}


function onActv3SubTotal3_17(){
  //Puntaje

  const puntajeDifusionExt = parseFloat(document.getElementById("puntajeDifusionExt").textContent);
  const puntajeDifusionInt =  parseFloat(document.getElementById("puntajeDifusionInt").textContent);
  const puntajeRepDifusionExt = parseFloat(document.getElementById("puntajeRepDifusionExt").textContent);
  const puntajeRepDifusionInt =  parseFloat(document.getElementById("puntajeRepDifusionInt").textContent);



  //Cantidad

  const cantDifusionExt = parseFloat(document.getElementById("cantDifusionExt").value);
  const cantDifusionInt = parseFloat(document.getElementById("cantDifusionInt").value);
  const cantRepDifusionExt = parseFloat(document.getElementById("cantRepDifusionExt").value);
  const cantRepDifusionInt = parseFloat(document.getElementById("cantRepDifusionInt").value);

//Puntaje a Evaluar

const  subtotalDifusionExt = subtotal(puntajeDifusionExt,cantDifusionExt);
document.getElementById("subtotalDifusionExt").innerHTML=subtotalDifusionExt;


const  subtotalDifusionInt= subtotal(puntajeDifusionInt,cantDifusionInt);
document.getElementById("subtotalDifusionInt").innerHTML= subtotalDifusionInt;

const  subtotalRepDifusionExt = subtotal(puntajeRepDifusionExt,cantRepDifusionExt);
document.getElementById("subtotalRepDifusionExt").innerHTML=subtotalRepDifusionExt;


const  subtotalRepDifusionInt= subtotal(puntajeRepDifusionInt,cantRepDifusionInt);
document.getElementById("subtotalRepDifusionInt").innerHTML= subtotalRepDifusionInt;



//resultados
console.log("Inicio de proyectos de extensión y difusión con financiamiento externo: ", subtotalDifusionExt);
console.log("Inicio de proyectos de extensión y difusión internos, aprobados por CAAC: ", subtotalDifusionInt);
console.log("Reporte cumplido del periodo anual de proyecto de extensión y difusión con financiamiento externo: ",subtotalRepDifusionExt);
console.log("Reporte cumplido del periodo anual de proyecto de extensión y difusión internos, aprobados por CAAC: ", subtotalRepDifusionInt);



//Puntaje Minimo resultante
  const score3_17 = min50(subtotalDifusionExt,subtotalDifusionInt,subtotalRepDifusionExt,subtotalRepDifusionInt);

  document.getElementById( "score3_17" ).innerHTML= score3_17;
  console.log ("Puntaje de las Evaluaciones 3.17: "), score3_17;

  /*  
  if (!isNaN(score3_17)) {
    docencia += score3_17;
    if(docencia>=700){

  //document.getElementById("docencia").innerHTML = 700;
} 
    
}
 //document.getElementById("docencia").innerHTML = docencia;
  data.score3_17 = score3_17;
*/
}


function onActv3SubTotal3_18(){
  //Puntaje

  const puntajeComOrgInt = parseFloat(document.getElementById("puntajeComOrgInt").textContent);
  const puntajeComOrgNac =  parseFloat(document.getElementById("puntajeComOrgNac").textContent);
  const puntajeComOrgRegc = parseFloat(document.getElementById("puntajeComOrgRegc").textContent);
  const puntajeComApoyoInt =  parseFloat(document.getElementById("puntajeComApoyoInt").textContent);
  const puntajeComApoyoNac = parseFloat(document.getElementById("puntajeComApoyoNac").textContent);
  const puntajeComApoyoRegc =  parseFloat(document.getElementById("puntajeComApoyoRegc").textContent);
  const puntajeCicloComOrgInt = parseFloat(document.getElementById("puntajeCicloComOrgInt").textContent);
  const puntajeCicloComOrgNac =  parseFloat(document.getElementById("puntajeCicloComOrgNac").textContent);
  const puntajeCicloComOrgReg = parseFloat(document.getElementById("puntajeCicloComOrgReg").textContent);
  const puntajeCicloComApoyoInt =  parseFloat(document.getElementById("puntajeCicloComApoyoInt").textContent);
  const puntajeCicloComApoyoNac =  parseFloat(document.getElementById("puntajeCicloComApoyoNac").textContent);
  const puntajeCicloComApoyoReg = parseFloat(document.getElementById("puntajeCicloComApoyoReg").textContent);


  //Cantidad

  const cantComOrgInt = parseFloat(document.getElementById("cantComOrgInt").value);
  const cantComOrgNac =  parseFloat(document.getElementById("cantComOrgNac").value);
  const cantComOrgReg = parseFloat(document.getElementById("cantComOrgReg").value);
  const cantComApoyoInt =  parseFloat(document.getElementById("cantComApoyoInt").value);
  const cantComApoyoNac = parseFloat(document.getElementById("cantComApoyoNac").value);
  const cantComApoyoReg =  parseFloat(document.getElementById("cantComApoyoReg").value);
  const cantCicloComOrgInt = parseFloat(document.getElementById("cantCicloComOrgInt").value);
  const cantCicloComOrgNac =  parseFloat(document.getElementById("cantCicloComOrgNac").value);
  const cantCicloComOrgReg = parseFloat(document.getElementById("cantCicloComOrgReg").value);
  const cantCicloComApoyoInt =  parseFloat(document.getElementById("cantCicloComApoyoInt").value);
  const cantCicloComApoyoNac =  parseFloat(document.getElementById("cantCicloComApoyoNac").value);
  const cantCicloComApoyoReg = parseFloat(document.getElementById("cantCicloComApoyoReg").value);

//Puntaje a Evaluar

//de a) -> c)
const  subtotalComOrgInt = subtotal(puntajeComOrgInt,cantComOrgInt);
document.getElementById("subtotalComOrgInt").innerHTML=subtotalComOrgInt;


const  subtotalComOrgNac= subtotal(puntajeComOrgNac,cantComOrgNac);
document.getElementById("subtotalComOrgNac").innerHTML= subtotalComOrgNac;

const  subtotalComOrgReg = subtotal(puntajeComOrgRegc,cantComOrgReg);
document.getElementById("subtotalComOrgReg").innerHTML=subtotalComOrgReg;

//de d) -> f)

const  subtotalComApoyoInt= subtotal(puntajeComApoyoInt,cantComApoyoInt);
document.getElementById("subtotalComApoyoInt").innerHTML= subtotalComApoyoInt;

const  subtotalComApoyoNac = subtotal(puntajeComApoyoNac,cantComApoyoNac);
document.getElementById("subtotalComApoyoNac").innerHTML=subtotalComApoyoNac;


const  subtotalComApoyoReg= subtotal(puntajeComApoyoRegc,cantComApoyoReg);
document.getElementById("subtotalComApoyoReg").innerHTML= subtotalComApoyoReg;

// de g) -> i)
const  subtotalCicloComOrgInt = subtotal(puntajeCicloComOrgInt,cantCicloComOrgInt);
document.getElementById("subtotalCicloComOrgInt").innerHTML=subtotalCicloComOrgInt;


const  subtotalCicloComOrgNac= subtotal(puntajeCicloComOrgNac,cantCicloComOrgNac);
document.getElementById("subtotalCicloComOrgNac").innerHTML= subtotalCicloComOrgNac;

const subtotalCicloComOrgReg= subtotal(puntajeCicloComOrgReg,cantCicloComOrgReg);
document.getElementById("subtotalCicloComOrgReg").innerHTML= subtotalCicloComOrgReg;

//de j)-> l)

const  subtotalCicloComApoyoInt = subtotal(puntajeCicloComApoyoInt,cantCicloComApoyoInt);
document.getElementById("subtotalCicloComApoyoInt").innerHTML=subtotalCicloComApoyoInt;


const  subtotalCicloComApoyoNac= subtotal(puntajeCicloComApoyoNac,cantCicloComApoyoNac);
document.getElementById("subtotalCicloComApoyoNac").innerHTML= subtotalCicloComApoyoNac;

const subtotalCicloComApoyoReg= subtotal(puntajeCicloComApoyoReg,cantCicloComApoyoReg);
document.getElementById("subtotalCicloComApoyoReg").innerHTML= subtotalCicloComApoyoReg;


//resultados

console.log("a)  Comité organizador Internacional: ", subtotalComOrgInt);
console.log("b)  Comité organizador Nacional: ", subtotalComOrgNac);
console.log("c)  Comité organizador Regional: ",subtotalComOrgReg);
console.log("d)  Comisiones de Appoyo Internacional: ", subtotalComApoyoInt);
console.log("e)  Comisiones de Appoyo Nacional: ", subtotalComApoyoNac);
console.log("f)  Comisiones de Appoyo Regional: ", subtotalComApoyoReg);
console.log("g)  Ciclo de conferencias, simposio, coloquio, etc. Internacional, Comité organizador Internacional: ", subtotalCicloComOrgInt);
console.log("h)  Ciclo de conferencias, simposio, coloquio, etc. Internacional, Comité organizador  Nacional: ", subtotalCicloComOrgNac);
console.log("i)  Ciclo de conferencias, simposio, coloquio, etc. Internacional, Comité organizador  Regional/Institucional: ", subtotalCicloComOrgReg);
console.log("j)  Ciclo de conferencias, simposio, coloquio, etc. Internacional, Comisiones de apoyo Internacional: ",subtotalCicloComApoyoInt);
console.log("k)  Ciclo de conferencias, simposio, coloquio, etc. Internacional, Comisiones de apoyo Nacional: ",subtotalCicloComApoyoNac);
console.log("l)  Ciclo de conferencias, simposio, coloquio, etc. Internacional, Comisiones de apoyo Regional/Institucional: ",subtotalCicloComApoyoReg);


//Puntaje Minimo resultante
  const score3_18 = min40(subtotalComOrgInt,subtotalComOrgNac,subtotalComOrgReg,subtotalComApoyoInt,subtotalComApoyoNac,subtotalComApoyoReg,subtotalCicloComOrgInt,subtotalCicloComOrgNac, subtotalCicloComOrgReg,subtotalCicloComApoyoInt,subtotalCicloComApoyoNac,subtotalCicloComApoyoReg);

  document.getElementById( "score3_18" ).innerHTML= score3_18;
  console.log ("Puntaje de las Evaluaciones 3.18: ", score3_18);

    if (!isNaN(score3_18)) {
    docencia += score3_18;
    if(docencia>=700){

  document.getElementById("docencia").innerHTML = 700;
}
}



 //document.getElementById("docencia").innerHTML = docencia;
  data.score3_18 = score3_18;

}


function onActv3SubTotal3_19(){
  //Puntaje

  const puntajeCGU = parseFloat(document.getElementById("puntajeCGUtitular").textContent);
  const puntajeCGUespecial =  parseFloat(document.getElementById("puntajeCGUespecial").textContent);
  const puntajeCGUpermanente = parseFloat(document.getElementById("puntajeCGUpermanente").textContent);
  const puntajeCAACtitular =  parseFloat(document.getElementById("puntajeCAACtitular").textContent);
  const puntajeCAACintegCom = parseFloat(document.getElementById("puntajeCAACintegCom").textContent);
  const puntajeComDepart =  parseFloat(document.getElementById("puntajeComDepart").textContent);
  const puntajeComPEDPD = parseFloat(document.getElementById("puntajeComPEDPD").textContent);
  const puntajeComPartPos =  parseFloat(document.getElementById("puntajeComPartPos").textContent);
  const puntajeRespPos = parseFloat(document.getElementById("puntajeRespPos").textContent);
  const puntajeRespCarrera =  parseFloat(document.getElementById("puntajeRespCarrera").textContent);
  const puntajeRespProd =  parseFloat(document.getElementById("puntajeRespProd").textContent);
  const puntajeRespLab = parseFloat(document.getElementById("puntajeRespLab").textContent);
  const puntajeExamProf = parseFloat(document.getElementById("puntajeExamProf").textContent);
  const puntajeExamAcademicos =  parseFloat(document.getElementById("puntajeExamAcademicos").textContent);
  const puntajePRODEPformResp = parseFloat(document.getElementById("puntajePRODEPformResp").textContent);
  const puntajePRODEPformInteg =  parseFloat(document.getElementById("puntajePRODEPformInteg").textContent);
  const puntajePRODEPenconsResp = parseFloat(document.getElementById("puntajePRODEPenconsResp").textContent);
  const puntajePRODEPenconsInteg =  parseFloat(document.getElementById("puntajePRODEPenconsInteg").textContent);
  const puntajePRODEPconsResp =  parseFloat(document.getElementById("puntajePRODEPconsResp").textContent);
  const puntajePRODEPconsInteg = parseFloat(document.getElementById("puntajePRODEPconsInteg").textContent);


  //Cantidad

  const cantCGUtitular = parseFloat(document.getElementById("cantCGUtitular").value);
  const cantCGUespecial =  parseFloat(document.getElementById("cantCGUespecial").value);
  const cantCGUpermanente = parseFloat(document.getElementById("cantCGUpermanente").value);
  const cantCAACtitular =  parseFloat(document.getElementById("cantCAACtitular").value);
  const cantCAACintegCom = parseFloat(document.getElementById("cantCAACintegCom").value);
  const cantComDepart =  parseFloat(document.getElementById("cantComDepart").value);
  const cantComPEDPD = parseFloat(document.getElementById("cantComPEDPD").value);
  const cantComPartPos =  parseFloat(document.getElementById("cantComPartPos").value);
  const cantRespPos = parseFloat(document.getElementById("cantRespPos").value);
  const cantRespCarrera =  parseFloat(document.getElementById("cantRespCarrera").value);
  const cantRespProd =  parseFloat(document.getElementById("cantRespProd").value);
  const cantRespLab = parseFloat(document.getElementById("cantRespLab").value);
  const cantExamProf = parseFloat(document.getElementById("cantExamProf").value);
  const cantExamAcademicos =  parseFloat(document.getElementById("cantExamAcademicos").value);
  const cantPRODEPformResp = parseFloat(document.getElementById("cantPRODEPformResp").value);
  const cantPRODEPformInteg =  parseFloat(document.getElementById("cantPRODEPformInteg").value);
  const cantPRODEPenconsResp = parseFloat(document.getElementById("cantPRODEPenconsResp").value);
  const cantPRODEPenconsInteg =  parseFloat(document.getElementById("cantPRODEPenconsInteg").value);
  const cantPRODEPconsResp =  parseFloat(document.getElementById("cantPRODEPconsResp").value);
  const cantPRODEPconsInteg = parseFloat(document.getElementById("cantPRODEPconsInteg").value);

//Puntaje a Evaluar

//de a) -> c)
const  subtotalCGUtitular = subtotal(puntajeCGU,cantCGUtitular);
document.getElementById("subtotalCGUtitular").innerHTML=subtotalCGUtitular;


const  subtotalCGUespecial= subtotal(puntajeCGUespecial,cantCGUespecial);
document.getElementById("subtotalCGUespecial").innerHTML= subtotalCGUespecial;

const  subtotalCGUpermanente = subtotal(puntajeCGUpermanente,cantCGUpermanente);
document.getElementById("subtotalCGUpermanente").innerHTML=subtotalCGUpermanente;

//de d) -> f)

const  subtotalCAACtitular= subtotal(puntajeCAACtitular,cantCAACtitular);
document.getElementById("subtotalCAACtitular").innerHTML= subtotalCAACtitular;

const  subtotalCAACintegCom = subtotal(puntajeCAACintegCom,cantCAACintegCom);
document.getElementById("subtotalCAACintegCom").innerHTML=subtotalCAACintegCom;


const  subtotalComDepart= subtotal(puntajeComDepart,cantComDepart);
document.getElementById("subtotalComDepart").innerHTML= subtotalComDepart;

// de g) -> i)
const  subtotalComPEDPD = subtotal(puntajeComPEDPD,cantComPEDPD);
document.getElementById("subtotalComPEDPD").innerHTML=subtotalComPEDPD;


const  subtotalComPartPos= subtotal(puntajeComPartPos,cantComPartPos);
document.getElementById("subtotalComPartPos").innerHTML= subtotalComPartPos;

const subtotalRespPos= subtotal(puntajeRespPos,cantRespPos);
document.getElementById("subtotalRespPos").innerHTML= subtotalRespPos;

//de j)-> l)

const  subtotalRespCarrera = subtotal(puntajeRespCarrera,cantRespCarrera);
document.getElementById("subtotalRespCarrera").innerHTML=subtotalRespCarrera;


const  subtotalRespProd= subtotal(puntajeRespProd,cantRespProd);
document.getElementById("subtotalRespProd").innerHTML= subtotalRespProd;

const subtotalRespLab= subtotal(puntajeRespLab,cantRespLab);
document.getElementById("subtotalRespLab").innerHTML= subtotalRespLab;

//de  m) -> n)
const  subtotalExamProf= subtotal(puntajeExamProf,cantExamProf);
document.getElementById("subtotalExamProf").innerHTML= subtotalExamProf;

const subtotalExamAcademicos= subtotal(puntajeExamAcademicos,cantExamAcademicos);
document.getElementById("subtotalExamAcademicos").innerHTML= subtotalExamAcademicos;

//de  o1) -> p2)

const  subtotalPRODEPformResp= subtotal(puntajePRODEPformResp,cantPRODEPformResp);
document.getElementById("subtotalPRODEPformResp").innerHTML= subtotalPRODEPformResp;

const subtotalPRODEPformInteg= subtotal(puntajePRODEPformInteg,cantPRODEPformInteg);
document.getElementById("subtotalPRODEPformInteg").innerHTML= subtotalPRODEPformInteg;

const  subtotalPRODEPenconsResp= subtotal(puntajePRODEPenconsResp,cantPRODEPenconsResp);
document.getElementById("subtotalPRODEPenconsResp").innerHTML= subtotalPRODEPenconsResp;

const subtotalPRODEPenconsInteg= subtotal(puntajePRODEPenconsInteg,cantPRODEPenconsInteg);
document.getElementById("subtotalPRODEPenconsInteg").innerHTML= subtotalPRODEPenconsInteg;

//de q1) -> q2)

const  subtotalPRODEPconsResp= subtotal(puntajePRODEPconsResp,cantPRODEPconsResp);
document.getElementById("subtotalPRODEPconsResp").innerHTML= subtotalPRODEPconsResp;

const subtotalPRODEPconsInteg= subtotal(puntajePRODEPconsInteg,cantPRODEPconsInteg);
document.getElementById("subtotalPRODEPconsInteg").innerHTML= subtotalPRODEPconsInteg;
//resultados

console.log("a)  Representante del profesorado ante H. CGU Titular o suplente: ", subtotalCGUtitular);
console.log("b)  Representante del profesorado ante H. CGU por Participación como miembro de comisión especial: ", subtotalCGUespecial);
console.log("c)  Representante del profesorado ante H. CGU por Participación como miembro en comisión permanente: ",subtotalCGUpermanente);
console.log("d)  Representante del profesorado ante CAAC ante Titular o suplente: ", subtotalCAACtitular);
console.log("e)  Representante del profesorado ante CAAC ante Participación como integrante de comisión: ", subtotalCAACintegCom);
console.log("f)  Comisiones Departamentales: ", subtotalComDepart);
console.log("g)  Comisiones Dictaminadora del PEDPD: ", subtotalComPEDPD);
console.log("h)  Comisiones Participación como integrante del Comité Académico de Posgrado: ", subtotalComPartPos);
console.log("i)  Resonsable de posgrado: ", subtotalRespPos);
console.log("j)  Responsable de Carrera: ",subtotalRespCarrera);
console.log("k)  Responsable de unidad de producción: ",subtotalRespProd);
console.log("l)  Responsable de Laboratorio: ",subtotalRespLab);
console.log("m) Sinodalías de examen de oposición Profesorado: ", subtotalRespPos);
console.log("n) Sinodalías de examen de oposición Ayudantes académicos: ",subtotalRespCarrera);
console.log("o1) Cuerpo académico registrado ante PRODEP En formación Responsable: ",subtotalRespProd);
console.log("o2) Cuerpo académico registrado ante PRODEP En formación Integrante: ",subtotalRespLab);
console.log("p1) Cuerpo académico registrado ante PRODEP En consolidación Responsable: ", subtotalRespPos);
console.log("p2) Cuerpo académico registrado ante PRODEP En consolidación Integrante: ",subtotalRespCarrera);
console.log("q1) Cuerpo académico registrado ante PRODEP Consolidado Responsable: ",subtotalRespProd);
console.log("q2) Cuerpo académico registrado ante PRODEP Consolidado Integrante: ",subtotalRespLab);



//Puntaje Minimo resultante
  const score3_19 = min40(subtotalCGUtitular,subtotalCGUespecial,subtotalCGUpermanente,subtotalCAACtitular,subtotalCAACintegCom,subtotalComDepart,subtotalComPEDPD,subtotalComPartPos, 
    subtotalRespPos,subtotalRespCarrera,subtotalRespProd,subtotalRespLab,subtotalExamProf,subtotalExamAcademicos,subtotalPRODEPformResp,subtotalPRODEPformInteg,subtotalPRODEPenconsResp,
    subtotalPRODEPenconsInteg,subtotalPRODEPconsResp,subtotalPRODEPconsInteg);

  document.getElementById( "score3_19" ).innerHTML= score3_19;
  console.log ("Puntaje de las Evaluaciones 3.19: ", score3_19);

    if (!isNaN(score3_19)) {
    docencia += score3_19;
    
}


  data.score3_19 = score3_19;
  document.getElementById("docencia").innerHTML = score3_19;

    if(docencia>=700){

  document.getElementById("docencia").innerHTML = 700;
}
data.docencia  = docencia;
console.log("docencia 3:", docencia);


}
/*
        if (/WebKit/.test(navigator.userAgent)) {
            document.body.classList.add('webkit-print');
        }*/



