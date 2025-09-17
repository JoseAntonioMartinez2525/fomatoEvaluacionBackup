import React, { useEffect, useState } from 'react';
import axios from 'axios';
import jsPDF from 'jspdf';

const PdfGenerator = () => {
    const [convocatoriaData, setConvocatoriaData] = useState(null);

    useEffect(() => {
        const fetchConvocatoria = async () => {
            const response = await axios.get('/api/convocatoria'); // Adjust the endpoint as needed
            setConvocatoriaData(response.data);
        };

        fetchConvocatoria();

        const handleBeforePrint = () => {
            if (convocatoriaData) {
                const doc = new jsPDF();
                doc.text("Convocatoria: " + convocatoriaData.convocatoria, 10, 10);
                doc.save("convocatoria.pdf");
            }
        };

        window.onbeforeprint = handleBeforePrint;

        return () => {
            window.onbeforeprint = null; // Clean up the event listener
        };
    }, [convocatoriaData]);

    return null; // No button needed, just handle print automatically
};

export default PdfGenerator;
