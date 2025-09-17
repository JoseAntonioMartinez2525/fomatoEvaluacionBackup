import React, { useEffect, useState } from 'react';
import axios from 'axios';

const DocenteSelect = () => {
    const [docentes, setDocentes] = useState([]);
    const [selectedDocente, setSelectedDocente] = useState('');
    const [convocatoria, setConvocatoria] = useState('');

    useEffect(() => {
        const fetchDocentes = async () => {
            try {
               const response = await fetch('/formato-evaluacion/get-docentes');
                const data = await response.json();
                setDocentes(data);
            } catch (error) {
                console.error('Error fetching docentes:', error);
            }
        };

        fetchDocentes();
    }, []);

    const handleDocenteChange = async (event) => {
        const email = event.target.value;
        setSelectedDocente(email);

        if (email) {
            try {
                const response = await axios.get('/get-docente-data', { params: { email } });
                const data = response.data;

                if (data.docente) {
                    setConvocatoria(data.docente.convocatoria || 'Convocatoria no disponible');
                }
            } catch (error) {
                console.error('Error fetching docente data:', error);
            }

            try {
                const response = await fetch('/formato-evaluacion/get-dictaminators-responses');
                const dictaminatorResponses = await response.json();
                const selectedResponseForm3_19 = dictaminatorResponses.form3_19.find(res => res.email === email);

                if (selectedResponseForm3_19) {
                    document.querySelector('input[name="dictaminador_id"]').value = selectedResponseForm3_19.dictaminador_id || '0';
                    document.querySelector('input[name="user_id"]').value = selectedResponseForm3_19.user_id || '';
                    document.querySelector('input[name="email"]').value = selectedResponseForm3_19.email || '';
                    document.querySelector('input[name="user_type"]').value = selectedResponseForm3_19.user_type || '';
                    document.getElementById('score3_19').textContent = selectedResponseForm3_19.score3_19 || '0';
                    document.getElementById('comision3_19').textContent = selectedResponseForm3_19.comision3_19 || '0';

                    // Cantidades
                    document.getElementById('cantCGUtitular').textContent = selectedResponseForm3_19.cantCGUtitular || '0';
                    document.getElementById('cantCGUespecial').textContent = selectedResponseForm3_19.cantCGUespecial || '0';
                    document.getElementById('cantRespLab').textContent = selectedResponseForm3_19.cantRespLab || '0';
                    document.getElementById('cantCGUpermanente').textContent = selectedResponseForm3_19.cantCGUpermanente || '0';
                    document.getElementById('cantCAACtitular').textContent = selectedResponseForm3_19.cantCAACtitular || '0';
                    document.getElementById('cantCAACintegCom').textContent = selectedResponseForm3_19.cantCAACintegCom || '0';
                    document.getElementById('cantComDepart').textContent = selectedResponseForm3_19.cantComDepart || '0';
                    document.getElementById('cantComPEDPD').textContent = selectedResponseForm3_19.cantComPEDPD || '0';
                    document.getElementById('cantComPartPos').textContent = selectedResponseForm3_19.cantComPartPos || '0';
                    document.getElementById('cantRespPos').textContent = selectedResponseForm3_19.cantRespPos || '0';
                    document.getElementById('cantRespCarrera').textContent = selectedResponseForm3_19.cantRespCarrera || '0';
                    document.getElementById('cantRespProd').textContent = selectedResponseForm3_19.cantRespProd || '0';
                    document.getElementById('cantExamProf').textContent = selectedResponseForm3_19.cantExamProf || '0';
                    document.getElementById('cantExamAcademicos').textContent = selectedResponseForm3_19.cantExamAcademicos || '0';
                    document.getElementById('cantPRODEPformResp').textContent = selectedResponseForm3_19.cantPRODEPformResp || '0';
                    document.getElementById('cantPRODEPformInteg').textContent = selectedResponseForm3_19.cantPRODEPformInteg || '0';
                    document.getElementById('cantPRODEPenconsResp').textContent = selectedResponseForm3_19.cantPRODEPenconsResp || '0';
                    document.getElementById('cantPRODEPenconsInteg').textContent = selectedResponseForm3_19.cantPRODEPenconsInteg || '0';
                    document.getElementById('cantPRODEPconsResp').textContent = selectedResponseForm3_19.cantPRODEPconsResp || '0';
                    document.getElementById('cantPRODEPconsInteg').textContent = selectedResponseForm3_19.cantPRODEPconsInteg || '0';


                    // Subtotales
                    document.getElementById('subtotalCGUtitular').textContent = selectedResponseForm3_19.subtotalCGUtitular || '0';
                    document.getElementById('subtotalCGUespecial').textContent = selectedResponseForm3_19.subtotalCGUespecial || '0';
                    document.getElementById('subtotalCGUpermanente').textContent = selectedResponseForm3_19.subtotalCGUpermanente || '0';
                    document.getElementById('subtotalCAACtitular').textContent = selectedResponseForm3_19.subtotalCAACtitular || '0';
                    document.getElementById('subtotalCAACintegCom').textContent = selectedResponseForm3_19.subtotalCAACintegCom || '0';
                    document.getElementById('subtotalComDepart').textContent = selectedResponseForm3_19.subtotalComDepart || '0';
                    document.getElementById('subtotalComPEDPD').textContent = selectedResponseForm3_19.subtotalComPEDPD || '0';
                    document.getElementById('subtotalComPartPos').textContent = selectedResponseForm3_19.subtotalComPartPos || '0';
                    document.getElementById('subtotalRespPos').textContent = selectedResponseForm3_19.subtotalRespPos || '0';
                    document.getElementById('subtotalRespCarrera').textContent = selectedResponseForm3_19.subtotalRespCarrera || '0';
                    document.getElementById('subtotalRespProd').textContent = selectedResponseForm3_19.subtotalRespProd || '0';
                    document.getElementById('subtotalRespLab').textContent = selectedResponseForm3_19.subtotalRespLab || '0';
                    document.getElementById('subtotalExamProf').textContent = selectedResponseForm3_19.subtotalExamProf || '0';
                    document.getElementById('subtotalExamAcademicos').textContent = selectedResponseForm3_19.subtotalExamAcademicos || '0';
                    document.getElementById('subtotalPRODEPformResp').textContent = selectedResponseForm3_19.subtotalPRODEPformResp || '0';
                    document.getElementById('subtotalPRODEPformInteg').textContent = selectedResponseForm3_19.subtotalPRODEPformInteg || '0';
                    document.getElementById('subtotalPRODEPenconsResp').textContent = selectedResponseForm3_19.subtotalPRODEPenconsResp || '0';
                    document.getElementById('subtotalPRODEPenconsInteg').textContent = selectedResponseForm3_19.subtotalPRODEPenconsInteg || '0';
                    document.getElementById('subtotalPRODEPconsResp').textContent = selectedResponseForm3_19.subtotalPRODEPconsResp || '0';
                    document.getElementById('subtotalPRODEPconsInteg').textContent = selectedResponseForm3_19.subtotalPRODEPconsInteg || '0';

                    // Comisiones
                    document.querySelector('#comCGUtitular').textContent = selectedResponseForm3_19.comCGUtitular || '0';
                    document.querySelector('#comCGUespecial').textContent = selectedResponseForm3_19.comCGUespecial || '0';
                    document.querySelector('#comCGUpermanente').textContent = selectedResponseForm3_19.comCGUpermanente || '0';
                    document.querySelector('#comCAACtitular').textContent = selectedResponseForm3_19.comCAACtitular || '0';
                    document.querySelector('#comCAACintegCom').textContent = selectedResponseForm3_19.comCAACintegCom || '0';
                    document.querySelector('#comComDepart').textContent = selectedResponseForm3_19.comComDepart || '0';
                    document.querySelector('#comComPEDPD').textContent = selectedResponseForm3_19.comComPEDPD || '0';
                    document.querySelector('#comComPartPos').textContent = selectedResponseForm3_19.comComPartPos || '0';
                    document.querySelector('#comRespPos').textContent = selectedResponseForm3_19.comRespPos || '0';
                    document.querySelector('#comRespCarrera').textContent = selectedResponseForm3_19.comRespCarrera || '0';
                    document.querySelector('#comRespProd').textContent = selectedResponseForm3_19.comRespProd || '0';
                    document.querySelector('#comRespLab').textContent = selectedResponseForm3_19.comRespLab || '0';
                    document.querySelector('#comExamProf').textContent = selectedResponseForm3_19.comExamProf || '0';
                    document.querySelector('#comExamAcademicos').textContent = selectedResponseForm3_19.comExamAcademicos || '0';
                    document.querySelector('#comPRODEPformResp').textContent = selectedResponseForm3_19.comPRODEPformResp || '0';
                    document.querySelector('#comPRODEPformInteg').textContent = selectedResponseForm3_19.comPRODEPformInteg || '0';
                    document.querySelector('#comPRODEPenconsResp').textContent = selectedResponseForm3_19.comPRODEPenconsResp || '0';
                    document.querySelector('#comPRODEPenconsInteg').textContent = selectedResponseForm3_19.comPRODEPenconsInteg || '0';
                    document.querySelector('#comPRODEPconsResp').textContent = selectedResponseForm3_19.comPRODEPconsResp || '0';
                    document.querySelector('#comPRODEPconsInteg').textContent = selectedResponseForm3_19.comPRODEPconsInteg || '0';

                    // Observaciones
                    document.querySelector('#obsCGUtitular').textContent = selectedResponseForm3_19.obsCGUtitular || '';
                    document.querySelector('#obsCGUespecial').textContent = selectedResponseForm3_19.obsCGUespecial || '';
                    document.querySelector('#obsCGUpermanente').textContent = selectedResponseForm3_19.obsCGUpermanente || '';
                    document.querySelector('#obsCAACtitular').textContent = selectedResponseForm3_19.obsCAACtitular || '';
                    document.querySelector('#obsCAACintegCom').textContent = selectedResponseForm3_19.obsCAACintegCom || '';
                    document.querySelector('#obsComDepart').textContent = selectedResponseForm3_19.obsComDepart || '';
                    document.querySelector('#obsComPEDPD').textContent = selectedResponseForm3_19.obsComPEDPD || '';
                    document.querySelector('#obsComPartPos').textContent = selectedResponseForm3_19.obsComPartPos || '';
                    document.querySelector('#obsRespPos').textContent = selectedResponseForm3_19.obsRespPos || '';
                    document.querySelector('#obsRespCarrera').textContent = selectedResponseForm3_19.obsRespCarrera || '';
                    document.querySelector('#obsRespProd').textContent = selectedResponseForm3_19.obsRespProd || '';
                    document.querySelector('#obsRespLab').textContent = selectedResponseForm3_19.obsRespLab || '';
                    document.querySelector('#obsExamProf').textContent = selectedResponseForm3_19.obsExamProf || '';
                    document.querySelector('#obsExamAcademicos').textContent = selectedResponseForm3_19.obsExamAcademicos || '';
                    document.querySelector('#obsPRODEPformResp').textContent = selectedResponseForm3_19.obsPRODEPformResp || '';
                    document.querySelector('#obsPRODEPformInteg').textContent = selectedResponseForm3_19.obsPRODEPformInteg || '';
                    document.querySelector('#obsPRODEPenconsResp').textContent = selectedResponseForm3_19.obsPRODEPenconsResp || '';
                    document.querySelector('#obsPRODEPenconsInteg').textContent = selectedResponseForm3_19.obsPRODEPenconsInteg || '';
                    document.querySelector('#obsPRODEPconsResp').textContent = selectedResponseForm3_19.obsPRODEPconsResp || '';
                    document.querySelector('#obsPRODEPconsInteg').textContent = selectedResponseForm3_19.obsPRODEPconsInteg || '';


                } else {
                    console.error('No form3_19 data found for the selected dictaminador.');

                    // Reset input values if no data found
                    document.querySelector('input[name="dictaminador_id"]').value = '0';
                    document.querySelector('input[name="user_id"]').value = '0';
                    document.querySelector('input[name="email"]').value = '';
                    document.querySelector('input[name="user_type"]').value = '';

                    document.getElementById('score3_19').textContent = '0';

                    // Reset cantidad values
                    for (let i = 0; i < cant3_19.length; i++) {
                        const cantidad = cant3_19[i];
                        document.querySelector(`input[name="${cantidad}"]`).value = '0';
                    }

                    // Reset subtotal values
                    for (let j = 0; j < subtotal3_19.length; j++) {
                        const subtotal = subtotal3_19[j];
                        document.querySelector(`input[name="${subtotal}"]`).value = '0';
                    }

                    // Reset comision values
                    for (let k = 0; k < comision3_19.length; k++) {
                        const comision = comision3_19[k];
                        const element = document.querySelector(`input[name="${comision}"]`);
                        if (element) {
                            element.textContent = '0';
                        }
                    }

                    // Reset observation values
                    for (let l = 0; l < obs3_19.length; l++) {
                        const obs = obs3_19[l];
                        const element = document.querySelector(`input[name="${obs}"]`);
                        if (element) {
                            element.textContent = ''; // Asignar un valor vacío
                        }
                    }

                    document.getElementById('comision3_19').textContent = '0';
                }
            } catch (error) {
                console.error('Error fetching dictaminators responses:', error);
            }
        }
    };

    return (
        <div>
            <select id="docenteSelect" value={selectedDocente} onChange={handleDocenteChange}>
                <option value="">Select Docente</option>
                {docentes.map(docente => (
                    <option key={docente.email} value={docente.email}>
                        {docente.email}
                    </option>
                ))}
            </select>
            <div id="convocatoria">{convocatoria}</div>
        </div>
    );
};

export default DocenteSelect;