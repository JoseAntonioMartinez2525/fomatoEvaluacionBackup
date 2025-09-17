import React from 'react';
import ReactDOM from 'react-dom/client';


function TablaAdvanced() {
    return (
        <table className="table table-sm">
            <thead>
                <tr>
                    <th scope="col">Actividad</th>
                    <th className="table-ajust" scope="col">Puntaje a evaluar</th>
                    <th className="table-ajust" scope="col">Puntaje de la Comisión Dictaminadora</th>
                    <th className="table-ajust" scope="col">Observaciones</th>
                </tr>
            </thead>
        </table>
    );
}

export default TablaAdvanced;

