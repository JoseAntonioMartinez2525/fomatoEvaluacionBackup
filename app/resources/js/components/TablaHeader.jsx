import React from 'react';
import ReactDOM from 'react-dom/client';

function TablaHeader() {
    return (
        <div className="container">
            <div className="row justify-content-center">

            <h4>Puntaje máximo
              <label class="bg-black text-white px-4" for=""></label>
            </h4> 

            </div>
        </div>
    );
}

export default Example;

if (document.getElementById('example')) {
    const Index = ReactDOM.createRoot(document.getElementById("example"));

    Index.render(
        <React.StrictMode>
            <Example/>
        </React.StrictMode>
    )
}
